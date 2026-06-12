<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Esconde os alertas do sistema após 3 segundos
    $(".alerta-sistema").fadeIn("slow");
    setTimeout(function() { $(".alerta-sistema").fadeOut("slow"); }, 3000);
    
    // Objeto global temporário para gerenciar os arquivos ativos do input
    let arquivosAtuais = new DataTransfer();

    // Monitora a seleção de arquivos no input
    $(".input-preview-js").on("change", function(e) {
        var container = $(this).closest("form").find(".container-preview-js");
        var inputOriginal = this;
        
        // Se for o primeiro upload ou uma nova seleção, resetamos o gerenciador
        arquivosAtuais = new DataTransfer();
        container.html(""); 

        if (inputOriginal.files) {
            // Transforma a FileList em Array para podermos mapear com o Index
            Array.from(inputOriginal.files).forEach((file, index) => {
                if (!file.type.startsWith("image/")) return;
                
                // Adiciona o arquivo ao nosso gerenciador dinâmico
                arquivosAtuais.items.add(file);

                var reader = new FileReader();
                reader.onload = function(e_reader) {
                    // Cria o bloco da imagem com um botão de "X" flutuante e o atributo data-index
                    var template = `
                        <div class="position-relative item-preview-foto" data-index="${index}" style="width: 100px; height: 80px;">
                            <img src="${e_reader.target.result}" class="rounded border shadow-sm w-100 h-100" style="object-fit:cover;">
                            <button type="button" class="btn btn-danger btn-sm btn-remover-foto-js" style="position: absolute; top: -5px; right: -5px; padding: 2px 6px; font-size: 0.7rem; border-radius: 50%;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    `;
                    container.append(template);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    // Evento de clique no botão de remover "X"
    $(document).on("click", ".btn-remover-foto-js", function() {
        var itemPreview = $(this).closest(".item-preview-foto");
        var indexParaRemover = parseInt(itemPreview.data("index"));
        var form = $(this).closest("form");
        var inputFileInput = form.find(".input-preview-js")[0];

        // Cria um novo container de arquivos limpo
        var novoDataTransfer = new DataTransfer();

        // Passa por todos os arquivos atuais, ignorando o que o usuário deletou
        for (let i = 0; i < arquivosAtuais.files.length; i++) {
            if (i !== indexParaRemover) {
                novoDataTransfer.items.add(arquivosAtuais.files[i]);
            }
        }

        // Atualiza a nossa referência global
        arquivosAtuais = novoDataTransfer;

        // Atualiza os arquivos REAIS do input que o PHP vai receber
        inputFileInput.files = arquivosAtuais.files;

        // Remove o elemento visualmente da tela
        itemPreview.remove();

        // Atualiza os índices dos elementos restantes na tela para não quebrar os próximos cliques
        form.find(".item-preview-foto").each(function(novoIndex) {
            $(this).attr("data-index", novoIndex);
        });
    });
});
</script>
</body>
</html>