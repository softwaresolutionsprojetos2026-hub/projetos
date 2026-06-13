<?php
require_once __DIR__ . "/../Support/GaleriaPolicy.php";

class AuthController {
    
    public function index() {
        if (isset($_SESSION['usuario_id'])) {
            header("Location: index.php?rota=admin/dashboard"); exit;
        }
        require_once __DIR__ . "/../Views/login.php";
    }

    public function login() {
        global $pdo;
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo_acesso'] ?? 'limitado';
            $_SESSION['usuario_telefone'] = $usuario['telefone'] ?? null;
            header("Location: index.php?rota=admin/dashboard");
        } else {
            $_SESSION['erro_login'] = "E-mail ou senha inválidos.";
            header("Location: index.php?rota=login");
        }
        exit;
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?rota=login");
        exit;
    }

    // Método auxiliar para proteger as rotas
    public static function check() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?rota=login");
            exit;
        }
    }

    public static function isMaster() {
        return GaleriaPolicy::isMasterSession($_SESSION);
    }

    public static function requireMaster() {
        self::check();

        if (!self::isMaster()) {
            $_SESSION['msg'] = ["Acesso negado: apenas o administrador master pode executar esta ação."];
            header("Location: index.php?rota=admin/dashboard");
            exit;
        }
    }
}