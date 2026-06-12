<!DOCTYPE html>
<html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <title>Detalhes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .img-principal { width:100%; height:450px; object-fit:cover; border-radius:12px; }
            .thumb-carrossel { width:90px; height:75px; object-fit:cover; border-radius:8px; cursor:pointer; border:2px solid transparent; } 
            .thumb-carrossel.active, .thumb-carrossel:hover { border-color:#0d6efd; }
         </style>
    </head>

         <body class="bg-light">

            <div class="container py-5">

                <div class="mb-4">
                    <a href="index.php" class="btn btn-outline-secondary">← Voltar</a>
                </div>

                <div class="row g-5 bg-white p-4 rounded-4 shadow-sm">

                    <div class="col-md-6">

                        <?php if(!empty($imagens)): ?>

                            <img id="fotoPrincipal" src="<?= BASE_URL . htmlspecialchars($imagens[0]['caminho']) ?>" class="img-principal mb-3">
                            
                            <?php if(count($imagens) > 1): ?>

                                <div class="d-flex gap-2 flex-wrap justify-content-center">

                                    <?php foreach($imagens as $index => $img): ?>

                                        <img src="<?= BASE_URL . htmlspecialchars($img['caminho']) ?>" class="thumb-carrossel <?= $index === 0 ? 'active':'' ?>">

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        <?php endif; ?>
                    </div>
                            <div class="col-md-6 d-flex flex-column justify-content-center">
                                    <span class="badge bg-primary align-self-start mb-2"><?= htmlspecialchars($produto['categoria_nome']) ?></span>
                                    <h1 class="fw-bold"><?= htmlspecialchars($produto['nome']) ?></h1>
                                    <h2 class="text-success fw-bold mb-4">R$ <?= number_format($produto['preco'], 2, ",", ".") ?></h2>

                                    <!-- CONTATO -->
                                    <div class="card bg-light border-0 p-3 mt-4" style="border-radius: 12px; max-width: 450px;">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fa-solid fa-address-card text-primary me-1"></i> Contato com o Vendedor
                                        </h6>
                                        
                                        <div class="d-flex align-items-start">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 45px; height: 45px; font-weight: bold; font-size: 1.1rem;">
                                                <?= strtoupper(substr($produto['autor_nome'] ?? 'V', 0, 1)) ?>
                                            </div>
                                            
                                            <div class="flex-grow-1">
                                                <p class="m-0 small text-muted">Anunciado por:</p>
                                                <p class="m-0 fw-bold text-dark fs-5"><?= htmlspecialchars($produto['autor_nome'] ?? 'Administrador') ?></p>
                                                
                                                <hr class="my-2 opacity-10">

                                                <div class="mb-2">
                                                    <a href="mailto:<?= htmlspecialchars($produto['autor_email'] ?? '') ?>" class="text-secondary text-decoration-none small d-flex align-items-center gap-2">
                                                        <i class="fa-regular fa-envelope text-primary"></i> <?= htmlspecialchars($produto['autor_email'] ?? 'Sem e-mail cadastrado') ?>
                                                    </a>
                                                </div>

                                                <?php if (!empty($produto['autor_telefone'])): 
                                                    // Limpa o número removendo espaços, parênteses e traços para o link do WhatsApp
                                                    $telefoneLimpo = preg_replace('/[^0-9]/', '', $produto['autor_telefone']);
                                                    // Adiciona o código do país (55) caso o usuário não tenha digitado
                                                    if (strlen($telefoneLimpo) <= 11) { $telefoneLimpo = "55" . $telefoneLimpo; }
                                                    
                                                    // Texto pré-definido para o cliente enviar
                                                    $textoZap = rawurlencode("Olá! Vi o seu anúncio do produto '" . $produto['nome'] . "' na galeria e gostaria de mais informações.");
                                                ?>
                                                    <div class="mb-3">
                                                        <span class="text-secondary small d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-phone text-primary"></i> <?= htmlspecialchars($produto['autor_telefone']) ?>
                                                        </span>
                                                    </div>

                                                    <a href="https://wa.me/<?= $telefoneLimpo ?>?text=<?= $textoZap ?>" 
                                                    target="_blank" 
                                                    class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-medium shadow-sm" 
                                                    style="border-radius: 8px;">
                                                        <i class="fa-brands fa-whatsapp fs-5"></i> Conversar no WhatsApp
                                                    </a>
                                                <?php else: ?>
                                                    <p class="text-muted small m-0 italic"><i class="fa-solid fa-phone-slash me-1"></i> Telefone não disponibilizado.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>                                     
                                    <!-- CONTATO FIM -->
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                        <script>
                        $(document).ready(function(){ 
                            $(".thumb-carrossel").on("click", function(){ 
                                $(".thumb-carrossel").removeClass("active"); 
                                $(this).addClass("active"); 
                                let src=$(this).attr("src"); 
                                $("#fotoPrincipal").fadeOut(150, function(){ 
                                    $(this).attr("src", src).fadeIn(150); 
                                    }); 
                                    });
                                     });
                                     </script>
                                     </body>
                                     </html>