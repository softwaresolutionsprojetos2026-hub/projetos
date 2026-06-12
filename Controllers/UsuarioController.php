<?php
require_once __DIR__ . "/../Models/Usuario.php";

class UsuarioController {
    private $model;

    public function __construct() {
        global $pdo;
        $this->model = new Usuario($pdo);
    }

    public function indexAdmin() {

        $usuarios = $this->model->all();
        
        require_once __DIR__ . "/../Views/admin/usuarios.php";
    }
}