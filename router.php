<?php 
session_start(); 
require_once "config/conexao.php"; 
require_once "Controllers/AuthController.php";
require_once "Controllers/CategoriaController.php"; 
require_once "Controllers/ProdutoController.php";

$acao = isset($_REQUEST["acao"]) ? $_REQUEST["acao"] : "";

switch ($acao) {

    case "fazer_login":
        require_once "Controllers/AuthController.php";
        (new AuthController())->login();
        break;

    case "cadastrar_categoria": 
        AuthController::check();
        (new CategoriaController())->store();
        break;

    case "excluir_categoria": 
        AuthController::check();
        (new CategoriaController())->delete();
        break;

    case "cadastrar_produto": 
        AuthController::check();
        (new ProdutoController())->store(); 
        break;

    case "editar_produto": 
        AuthController::check();
        (new ProdutoController())->update(); 
        break;

    case "excluir_produto": 
        AuthController::check();
        (new ProdutoController())->delete(); 
        break;

    case "excluir_imagem_avulsa":
        (new ProdutoController())->deleteImage();
        break;
        
    case "salvar_usuario":
        AuthController::requireMaster();

        $nome = trim((string) ($_POST['nome'] ?? ''));
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $telefone = trim((string) filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS));
        $senha = (string) ($_POST['senha'] ?? '');
        
        if ($nome === '' || !$email || strlen($senha) < 6) {
            $_SESSION['msg'] = ["Preencha nome, e-mail válido e senha com no mínimo 6 caracteres."];
            header("Location: index.php?rota=admin/usuarios");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha, tipo_acesso) VALUES (?, ?, ?, ?, 'limitado')");
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        if($stmt->execute([$nome, $email, $telefone ?: null, $hash])){
            $_SESSION['msg'] = ["Usuário cadastrado com sucesso!"];
        }
        header("Location: index.php?rota=admin/usuarios");
        exit;
        break;

    case "excluir_usuario":
        AuthController::requireMaster();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if($id != 1 && $id != $_SESSION['usuario_id']){
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['msg'] = ["Usuário removido!"];
        }
        header("Location: index.php?rota=admin/usuarios");
        exit;
        break;
        
    case "registrar_visitante":
        $nome = trim((string) ($_POST['nome'] ?? ''));
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $telefone = trim((string) filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS));
        $senha = (string) ($_POST['senha'] ?? '');

        if ($nome === '' || !$email || strlen($senha) < 6) {
            $_SESSION['msg'] = ["Preencha nome, e-mail válido e senha com no mínimo 6 caracteres."];
            header("Location: index.php?rota=registrar");
            exit;
        }
        
        // Verifica se o e-mail já existe
        $check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $_SESSION['msg'] = ["Este e-mail já está cadastrado!"];
            header("Location: index.php?rota=registrar");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha, tipo_acesso) VALUES (?, ?, ?, ?, 'limitado')");
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        if($stmt->execute([$nome, $email, $telefone ?: null, $hash])){
            $_SESSION['msg'] = ["Conta criada com sucesso. Faça login para continuar."];
            header("Location: index.php?rota=login");
            exit;
        }
        break;
        
    case "alternar_limite":
        AuthController::requireMaster();

        $user_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $novo_tipo = $_POST['tipo_acesso'] ?? '';

        if ($user_id && in_array($novo_tipo, ['limitado', 'ilimitado'])) {
            $stmt = $pdo->prepare("UPDATE usuarios SET tipo_acesso = ? WHERE id = ?");
            $stmt->execute([$novo_tipo, $user_id]);
            $_SESSION['msg'] = ["Privilégio do usuário atualizado!"];
        }
        header("Location: index.php?rota=admin/usuarios");
        exit;
        break;
        
    case "editar_usuario":
        AuthController::requireMaster();

        $id_usuario_editar = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $nome = trim((string) ($_POST['nome'] ?? ''));
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $telefone = trim((string) filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS));
        $tipo_acesso = $_POST['tipo_acesso'] ?? '';
        $senha = (string) ($_POST['senha'] ?? '');

        if ($id_usuario_editar && $nome !== '' && $email && in_array($tipo_acesso, ['limitado', 'ilimitado'], true)) {
            // Se o usuário digitou uma nova senha, precisamos criptografá-la e atualizar junto
            if (!empty($senha)) {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ?, tipo_acesso = ?, senha = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $telefone ?: null, $tipo_acesso, $hash, $id_usuario_editar]);
            } else {
                // Caso contrário, atualiza apenas os dados básicos sem mexer na senha atual
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ?, tipo_acesso = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $telefone ?: null, $tipo_acesso, $id_usuario_editar]);
            }

            $_SESSION['msg'] = ["Dados do operador updated com sucesso!"];
        }

        header("Location: index.php?rota=admin/usuarios");
        exit;
        break;        

    default: 
        header("Location: index.php"); 
        exit; 
}