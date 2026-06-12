<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
            body { 
                    overflow: hidden; 
                    min-height: 100vh; 
                    background-color: #f4f6f9; 
                 }

            .sidebar { 
                        width: 250px; 
                        background: #212529; 
                        color: #fff; 
                        min-height: calc(100vh - 56px); 
                     }

            .sidebar .nav-link { 
                                    color: #c2c7d0; 
                                    padding: 12px 20px; 
                                    display: block; 
                                    text-decoration: none;
                                    transition: all 0.2s; 
                                }

            .sidebar .nav-link:hover, 
            .sidebar .nav-link.active { 
                                            background: rgba(255,255,255,0.1); 
                                            color: #fff;
                                            font-weight: 500; 
                                      } 
                                      
            .content { 
                        flex: 1; 
                        padding: 30px;
                        background-color: #f8f9fa; 
                     } 
                     
            .alerta-sistema { 
                                display: none; 
                            } 
                            
            .img-preview-container img { 
                                            width: 100px; 
                                            height: 80px; 
                                            object-fit: cover; 
                                            border-radius: 8px; 
                                            border: 1px solid #ddd; 
                                        }
    </style>

</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold px-3" href="index.php?rota=admin/dashboard">ADMIN <span class="text-primary">GALLERY</span></a>
        <div class="ms-auto d-flex align-items-center px-3">
            <span class="text-white-50 me-3 small">Olá, <strong class="text-white"><?= $_SESSION['usuario_nome'] ?></strong></span>
            <a href="index.php?rota=logout" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
            </a>
        </div>
    </div>
</nav>

<div class="d-flex" style="margin-top: 56px;">
    
<!--
```

*(Lembre-se de ajustar o CSS do seu `Views/includes/header.php` se a sidebar estiver cobrindo a navbar).*

---

### 🚀 Credenciais de Acesso:
O sistema criará automaticamente este usuário na primeira execução:
*   **E-mail:** `admin@admin.com`
*   **Senha:** `admin123`

Agora seu painel está protegido, com login seguro, reconhecimento de usuário logado e opção de encerrar a sessão!

-->