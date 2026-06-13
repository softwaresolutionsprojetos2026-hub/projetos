<?php
require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/../Support/GaleriaPolicy.php";
require_once __DIR__ . "/../Models/Produto.php"; 
require_once __DIR__ . "/../Models/Categoria.php";

class ProdutoController {

    private $model; 
    private $catModel;

    public function __construct() { 
        $this->model = new Produto(); 
        $this->catModel = new Categoria(); 
    }

    private function currentUserId() {
        return (int) ($_SESSION['usuario_id'] ?? 0);
    }

    private function isMaster() {
        return AuthController::isMaster();
    }

    private function redirectDenied($rota = "index.php?rota=admin/produtos") {
        $_SESSION['msg'] = ["Acesso negado para o recurso solicitado."];
        header("Location: " . $rota);
        exit;
    }

    private function findManagedProduct($id) {
        return $this->model->findForUser($id, $this->currentUserId(), $this->isMaster());
    }

    private function countUploadedImages() {
        return GaleriaPolicy::countUploadedImages($_FILES['imagens']['name'] ?? []);
    }
    
    public function indexPublico() {
        $busca = isset($_GET["busca"]) ? trim($_GET["busca"]) : "";
        $cat_id = isset($_GET["categoria"]) ? filter_input(INPUT_GET, "categoria", FILTER_VALIDATE_INT) : "";

        $galeria = $this->model->allPublic($busca, $cat_id);
        $categorias = $this->catModel->all();
        $categoriaAtiva = $cat_id;

        require_once __DIR__ . "/../Views/galeria.php";
    }
    
    public function detalhePublico() { 
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) { 
            header("Location: index.php"); 
            exit; 
        }

        $produto = $this->model->find($id); 
        if (!$produto) { 
            die("Produto não encontrado!"); 
        }

        $imagens = $this->model->getImagens($id);
        require_once __DIR__ . "/../Views/detalhe.php"; 
    }
    
    public function indexAdmin() { 
        $busca = isset($_GET["busca_prod"]) ? trim($_GET["busca_prod"]) : ""; 
        $cat_id = isset($_GET["filtro_cat"]) ? trim($_GET["filtro_cat"]) : ""; 
        
        // Se for o administrador master (ID 1), ele vê TODOS os produtos na listagem comum.
        // Se for um operador comum, passamos o ID dele para o model filtrar apenas as postagens dele.
        $usuario_filtro = ($_SESSION['usuario_id'] == 1) ? null : $_SESSION['usuario_id'];
        $produtos = $this->model->allAdmin($busca, $cat_id, $usuario_filtro);

        $categorias = $this->catModel->all();
        require_once __DIR__ . "/../Views/admin/produtos.php"; 
    }
    
    public function editAdmin() {
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) { 
            header("Location: index.php?rota=admin/produtos"); 
            exit; 
        }

        $produto = $this->findManagedProduct($id);
        if (!$produto) { 
            $this->redirectDenied();
        }

        $imagens = $this->model->getImagens($id); 
        $categorias = $this->catModel->all();

        require_once __DIR__ . "/../Views/admin/editar_produto.php";
    }

    public function store() {
        global $pdo;
        $usuario_id = $this->currentUserId();

        // Validação de limites para usuários do plano 'limitado'
        if (GaleriaPolicy::isLimitedUser($_SESSION['usuario_tipo'] ?? null)) {
            // 1. Validar limite de 2 produtos
            $stmtProd = $pdo->prepare("SELECT COUNT(*) FROM produtos WHERE usuario_id = ?");
            $stmtProd->execute([$usuario_id]);
            if ($stmtProd->fetchColumn() >= 2) {
                $_SESSION['msg'] = ["Limite atingido: Usuários limitados podem cadastrar apenas 2 produtos."];
                header("Location: index.php?rota=admin/produtos");
                exit;
            }

            // 2. Validar limite de 2 imagens no upload atual
            if (GaleriaPolicy::hasImageUploadLimitExceeded($_SESSION['usuario_tipo'] ?? null, $this->countUploadedImages())) {
                $_SESSION['msg'] = ["Limite atingido: Permitido no máximo 2 imagens por produto."];
                header("Location: index.php?rota=admin/produtos");
                exit;
            }
        }    

        $categoria_id = filter_input(INPUT_POST, "categoria_id", FILTER_VALIDATE_INT); 
        $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS); 
        $preco = filter_input(INPUT_POST, "preco", FILTER_VALIDATE_FLOAT);

        if ($categoria_id && !empty($nome) && $preco !== false) {
            // Atualizado para passar o $usuario_id ao Model
            $prod_id = $this->model->create($categoria_id, $nome, $preco, $usuario_id);
            // Passa o ID do usuário para organizar na pasta correta
            $this->uploadImagens($prod_id, $usuario_id);
            $_SESSION["msg"] = ["Sucesso: Produto gravado com êxito!"];
        }

        header("Location: index.php?rota=admin/produtos"); 
        exit;
    }

    public function update() {
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
        $categoria_id = filter_input(INPUT_POST, "categoria_id", FILTER_VALIDATE_INT);
        $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
        $preco = filter_input(INPUT_POST, "preco", FILTER_VALIDATE_FLOAT);
        $usuario_id = $this->currentUserId();

        if (!$id || !$this->findManagedProduct($id)) {
            $this->redirectDenied();
        }

        if (GaleriaPolicy::hasImageUploadLimitExceeded($_SESSION['usuario_tipo'] ?? null, $this->countUploadedImages())) {
            $_SESSION['msg'] = ["Limite atingido: Permitido no máximo 2 imagens por produto."];
            header("Location: index.php?rota=admin/produtos/editar&id=" . $id);
            exit;
        }

        if ($id && $categoria_id && !empty($nome) && $preco !== false) {
            $this->model->update($id, $categoria_id, $nome, $preco);

            if (isset($_FILES["imagens"]) && $_FILES["imagens"]["error"][0] === UPLOAD_ERR_OK) {
                
                // Apaga as imagens antigas físicas do servidor
                foreach($this->model->getImagens($id) as $img) { 
                    if (file_exists($img["caminho"])) { 
                        @unlink($img["caminho"]); 
                    } 
                }

                $this->model->deleteImagens($id);
                $this->uploadImagens($id, $usuario_id);
            }

            $_SESSION["msg"] = ["Sucesso: Produto atualizado com êxito!"];
        }

        header("Location: index.php?rota=admin/produtos"); 
        exit;
    }

    // Método de upload modificado para criar a pasta "uploads/ID_DO_USUARIO/"
    private function uploadImagens($prod_id, $usuario_id) {
        if (isset($_FILES["imagens"])) {
            // Define a pasta isolada usando o ID do usuário da sessão
            $dir = "uploads/" . $usuario_id . "/";

            // Se a pasta do usuário não existir, cria com permissões restritas.
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            for ($i = 0; $i < count($_FILES["imagens"]["name"]); $i++) {
                if ($_FILES["imagens"]["error"][$i] !== UPLOAD_ERR_OK) continue;

                if (!GaleriaPolicy::isAllowedImageFilename($_FILES["imagens"]["name"][$i])) {
                    continue;
                }

                $ext = strtolower(pathinfo($_FILES["imagens"]["name"][$i], PATHINFO_EXTENSION)); 
                
                // Mantém o nome único gerado pelo seu sistema, mas salvando no diretório do usuário
                $novo_nome = $dir . uniqid("img_", true) . "." . $ext;

                if (move_uploaded_file($_FILES["imagens"]["tmp_name"][$i], $novo_nome)) { 
                    $this->model->addImagem($prod_id, $novo_nome); 
                }
            }
        }
    }

    public function deleteImage() {
        AuthController::check();
        header('Content-Type: application/json; charset=utf-8');

        $img_id = filter_input(INPUT_POST, 'img_id', FILTER_VALIDATE_INT);
        if (!$img_id) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "erro" => "Imagem inválida."]);
            exit;
        }

        $imagem = $this->model->findImagemForUser($img_id, $this->currentUserId(), $this->isMaster());
        if (!$imagem) {
            http_response_code(403);
            echo json_encode(["sucesso" => false, "erro" => "Acesso negado para excluir esta imagem."]);
            exit;
        }

        if (is_file($imagem['caminho'])) {
            @unlink($imagem['caminho']);
        }

        $resultado = $this->model->deleteImagemById($img_id);
        echo json_encode(["sucesso" => (bool) $resultado]);
        exit;
    }

    public function delete() { 
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT); 
        
        if ($id) {
            if (!$this->findManagedProduct($id)) {
                $this->redirectDenied(isset($_GET['origem']) && $_GET['origem'] === 'moderacao' ? "index.php?rota=admin/moderacao" : "index.php?rota=admin/produtos");
            }

            // Remove os arquivos físicos da pasta do usuário de forma correta
            foreach($this->model->getImagens($id) as $img) { 
                if (file_exists($img["caminho"])) { 
                    @unlink($img["caminho"]); 
                } 
            } 
            
            // Remove o registro de imagens e do produto no banco
            $this->model->deleteImagens($id);
            $this->model->delete($id); 
            
            $_SESSION["msg"] = ["Aviso: Produto excluído!"]; 
        } 
        
        // Se a exclusão partiu da tela de moderação do Admin, redireciona de volta para lá
        if (isset($_GET['origem']) && $_GET['origem'] === 'moderacao') {
            header("Location: index.php?rota=admin/moderacao");
        } else {
            header("Location: index.php?rota=admin/produtos");
        }
        exit; 
    }
}