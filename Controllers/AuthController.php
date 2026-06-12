<?php

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
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            header("Location: index.php?rota=admin/dashboard");
        } else {
            $_SESSION['erro_login'] = "E-mail ou senha inválidos.";
            header("Location: index.php?rota=login");
        }
        exit;
    }

    public function logout() {
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
}