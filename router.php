<?php 
session_start(); 
require_once "config/conexao.php"; 
require_once "Controllers/CategoriaController.php"; 
require_once "Controllers/ProdutoController.php";

$acao = isset($_REQUEST["acao"]) ? $_REQUEST["acao"] : "";

switch ($acao) {

    case "fazer_login":
        require_once "Controllers/AuthController.php";
        (new AuthController())->login();
        break;

    case "cadastrar_categoria": 
        (new CategoriaController())->store();
        break;

    case "excluir_categoria": 
        (new CategoriaController())->delete();
        break;

    case "cadastrar_produto": 
        // A lógica de capturar o usuário logado e isolar as pastas foi implementada no ProdutoController!
        (new ProdutoController())->store(); 
        break;

    case "editar_produto": 
        (new ProdutoController())->update(); 
        break;

    case "excluir_produto": 
        (new ProdutoController())->delete(); 
        break;

    case "excluir_imagem_avulsa":
        $img_id = filter_input(INPUT_POST, "img_id", FILTER_VALIDATE_INT);
        if ($img_id) {
            // Busca o caminho da imagem para apagar o arquivo físico
            $stmt = $pdo->prepare("SELECT caminho FROM imagens WHERE id = ?");
            $stmt->execute([$img_id]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($img) {
                if (file_exists($img["caminho"])) {
                    @unlink($img["caminho"]); // Deleta do servidor/pasta
                }
                // Deleta do banco de dados
                $stmtDel = $pdo->prepare("DELETE FROM imagens WHERE id = ?");
                $resultado = $stmtDel->execute([$img_id]);
                
                if ($resultado) {
                    echo json_encode(["sucesso" => true]);
                    exit;
                }
            }
        }
        echo json_encode(["sucesso" => false, "erro" => "Não foi possível deletar."]);
        break;
        
    case "salvar_usuario":
        require_once "Controllers/AuthController.php";
        AuthController::check();

        // 🛡️ Bloqueio estrito: Só quem tem ID exatamente igual a 1 pode executar
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_id'] != 1) {
            header("Location: index.php?rota=admin/dashboard");
            exit;
        }       

        $nome = $_POST['nome'];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS); // 💡 NOVO
        $senha = $_POST['senha'];
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha) VALUES (?, ?, ?, ?)");
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        if($stmt->execute([$nome, $email, $telefone, $hash])){
            $_SESSION['msg'] = ["Usuário cadastrado com sucesso!"];
        }
        header("Location: index.php?rota=admin/usuarios");
        break;

    case "excluir_usuario":
        require_once "Controllers/AuthController.php";
        AuthController::check();

        // 🛡️ Bloqueio estrito: Só quem tem ID exatamente igual a 1 pode executar
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_id'] != 1) {
            header("Location: index.php?rota=admin/dashboard");
            exit;
        }

        $id = $_GET['id'];
        if($id != 1 && $id != $_SESSION['usuario_id']){
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['msg'] = ["Usuário removido!"];
        }
        header("Location: index.php?rota=admin/usuarios");
        break;
        
    case "registrar_visitante":
        $nome = $_POST['nome'];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS); // NOVO
        $senha = $_POST['senha'];
        
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
        if($stmt->execute([$nome, $email, $telefone, $hash])){
            echo "<script>alert('Conta criada com sucesso! Você já pode fazer login.'); window.close();</script>";
        }
        break;
        
    case "alternar_limite":
        require_once "Controllers/AuthController.php";
        AuthController::check();

        // 🛡️ Bloqueio estrito: Só quem tem ID exatamente igual a 1 pode executar
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_id'] != 1) {
            header("Location: index.php?rota=admin/dashboard");
            exit;
        }

        $user_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $novo_tipo = $_POST['tipo_acesso'];

        if ($user_id && in_array($novo_tipo, ['limitado', 'ilimitado'])) {
            $stmt = $pdo->prepare("UPDATE usuarios SET tipo_acesso = ? WHERE id = ?");
            $stmt->execute([$novo_tipo, $user_id]);
            $_SESSION['msg'] = ["Privilégio do usuário atualizado!"];
        }
        header("Location: index.php?rota=admin/usuarios");
        break;
        
    case "editar_usuario":
        require_once "Controllers/AuthController.php";
        AuthController::check();

        // 🛡️ Bloqueio estrito: Só quem tem ID exatamente igual a 1 pode executar
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_id'] != 1) {
            header("Location: index.php?rota=admin/dashboard");
            exit;
        }

        $id_usuario_editar = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $nome = $_POST['nome'];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS); // 💡 NOVO
        $tipo_acesso = $_POST['tipo_acesso'];
        $senha = $_POST['senha'];

        if ($id_usuario_editar) {
            // Se o usuário digitou uma nova senha, precisamos criptografá-la e atualizar junto
            if (!empty($senha)) {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ?, tipo_acesso = ?, senha = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $telefone, $tipo_acesso, $hash, $id_usuario_editar]);
            } else {
                // Caso contrário, atualiza apenas os dados básicos sem mexer na senha atual
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ?, tipo_acesso = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $telefone, $tipo_acesso, $id_usuario_editar]);
            }

            $_SESSION['msg'] = ["Dados do operador updated com sucesso!"];
        }

        header("Location: index.php?rota=admin/usuarios");
        break;        

    default: 
        header("Location: index.php"); 
        exit; 
}