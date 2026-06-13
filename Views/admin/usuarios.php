<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<div class="content d-flex flex-column" style="height: calc(100vh - 56px); overflow: hidden;">
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alerta-sistema py-2 mb-3 shadow-sm small">
            <?php foreach($_SESSION['msg'] as $m) echo htmlspecialchars($m); unset($_SESSION['msg']); ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark m-0">Gestão de Operadores</h3>
        <div class="d-flex gap-2">
            <button id="btn-aba-lista" class="btn btn-primary btn-sm px-3 fw-semibold active" data-bs-toggle="tab" data-bs-target="#list-u" disabled>
                <i class="fa-solid fa-users me-1"></i> Lista
            </button>
            <button id="btn-aba-cadastro" class="btn btn-success btn-sm px-3 fw-semibold" data-bs-toggle="tab" data-bs-target="#cad-u">
                <i class="fa-solid fa-user-plus me-1"></i> Novo Usuário
            </button>
        </div>
    </div>

    <div class="tab-content flex-grow-1" style="overflow: hidden;">
        
        <div class="tab-pane fade show active h-100" id="list-u">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column" style="border-radius: 12px;">
                <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-4">Nome</th>
                                <th>E-mail</th>
                                <th>Telefone / WhatsApp</th>
                                <th>Plano / Limite</th>
                                <th>Data de Cadastro</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($usuarios as $u): ?>
                                <?php $tipoAcesso = $u['tipo_acesso'] ?? 'limitado'; ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <?= strtoupper(substr($u['nome'], 0, 1)) ?>
                                            </div>
                                            <strong><?= htmlspecialchars($u['nome']) ?></strong>
                                        </div>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <?php if(!empty($u['telefone'])): ?>
                                            <span class="text-dark"><i class="fa-brands fa-whatsapp text-success me-1"></i> <?= htmlspecialchars($u['telefone']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small italic">Não informado</span>
                                        <?php endif; ?>
                                    </td>                                    
                                    <td>
                                        <?php if($u['id'] == 1): ?>
                                            <span class="badge bg-dark">Master</span>
                                        <?php else: ?>
                                            <span class="badge <?= $tipoAcesso === 'ilimitado' ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= $tipoAcesso === 'ilimitado' ? 'Ilimitado' : 'Limitado (2 Prods)' ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($u['criado_em'])) ?></td>
                                    <td class="text-end pe-4">
                                        <?php if($u['id'] != 1): ?>
                                            <button type="button" class="btn btn-sm btn-outline-primary border-0 me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditarUsuario" 
                                                    data-id="<?= $u['id'] ?>" 
                                                    data-nome="<?= htmlspecialchars($u['nome']) ?>" 
                                                    data-email="<?= htmlspecialchars($u['email']) ?>"
                                                    data-telefone="<?= htmlspecialchars($u['telefone'] ?? '') ?>" 
                                                    data-acesso="<?= $tipoAcesso ?>">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            
                                            <?php if($u['id'] != $_SESSION['usuario_id']): ?>
                                                <a href="router.php?acao=excluir_usuario&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Excluir este acesso?');">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted fw-normal border">Protegido</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade h-100" id="cad-u">
            <div class="card border-0 shadow-sm p-4 mx-auto" style="border-radius: 12px; max-width: 600px;">
                <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-shield-halved text-primary me-2"></i>Criar Novo Acesso</h5>
                <form action="router.php" method="POST">
                    <input type="hidden" name="acao" value="salvar_usuario">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nome Completo</label>
                        <input type="text" name="nome" class="form-control bg-light" placeholder="Ex: João Silva" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">E-mail de Acesso</label>
                        <input type="email" name="email" class="form-control bg-light" placeholder="joao@empresa.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Telefone / WhatsApp</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="(11) 99999-9999" maxlength="15" onkeyup="mascaraTelefone(this)" required>
                    </div>                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Senha Inicial</label>
                        <input type="password" name="senha" class="form-control bg-light" placeholder="No mínimo 6 caracteres" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        <i class="fa-solid fa-check-double me-1"></i> Confirmar Cadastro
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-dark m-0" id="modalEditarUsuarioLabel"><i class="fa-solid fa-user-gear text-primary me-2"></i>Editar Operador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="router.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="acao" value="editar_usuario">
                    <input type="hidden" name="usuario_id" id="edit-id">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nome Completo</label>
                        <input type="text" name="nome" id="edit-nome" class="form-control bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">E-mail de Acesso</label>
                        <input type="email" name="email" id="edit-email" class="form-control bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Telefone / WhatsApp</label>
                        <input type="text" name="telefone" id="edit-telefone" class="form-control" placeholder="(11) 99999-9999" maxlength="15" onkeyup="mascaraTelefone(this)" required>
                    </div>                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Plano / Limite de Acesso</label>
                        <select name="tipo_acesso" id="edit-acesso" class="form-select bg-light">
                            <option value="limitado">Limitado (2 produtos / 2 imagens)</option>
                            <option value="ilimitado">Ilimitado (Acesso Total)</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label small fw-bold text-muted">Nova Senha <span class="text-lowercase fw-normal text-danger">(deixe em branco para não alterar)</span></label>
                        <input type="password" name="senha" class="form-control bg-light" placeholder="Nova senha opcional">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

function mascaraTelefone(input) {
    let v = input.value.replace(/\D/g, ""); // Remove tudo que não for número
    
    if (v.length > 0) {
        v = "(" + v;
    }
    if (v.length > 3) {
        v = v.substring(0, 3) + ") " + v.substring(3);
    }
    if (v.length > 10) {
        v = v.substring(0, 10) + "-" + v.substring(10, 14);
    }
    
    input.value = v;
}

document.addEventListener('DOMContentLoaded', function () {

// 1. CÓDIGO NOVO: Controla a inversão dos botões de abas (Lista / Novo Usuário)
    const btnLista = document.getElementById('btn-aba-lista');
    const btnCadastro = document.getElementById('btn-aba-cadastro');

    if (btnLista && btnCadastro) {
        // Quando clicar no botão Novo Usuário
        btnCadastro.addEventListener('click', function () {
            btnCadastro.setAttribute('disabled', 'true'); // Desabilita o de cadastro
            btnLista.removeAttribute('disabled');        // Habilita o de lista
        });

        // Quando clicar no botão Lista
        btnLista.addEventListener('click', function () {
            btnLista.setAttribute('disabled', 'true');    // Desabilita o de lista
            btnCadastro.removeAttribute('disabled');     // Habilita o de cadastro
        });
    }




// 2. CÓDIGO EXISTENTE: Injeta os dados da tabela no Modal de Edição
    const modalEditar = document.getElementById('modalEditarUsuario');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Extrai as informações dos atributos data-* do botão clicado
            const id = button.getAttribute('data-id');
            const nome = button.getAttribute('data-nome');
            const email = button.getAttribute('data-email');
            const telefone = button.getAttribute('data-telefone');
            const acesso = button.getAttribute('data-acesso');

            // Injeta os dados capturados nos inputs correspondentes do modal
            modalEditar.querySelector('#edit-id').value = id;
            modalEditar.querySelector('#edit-nome').value = nome;
            modalEditar.querySelector('#edit-email').value = email;
            modalEditar.querySelector('#edit-telefone').value = telefone;
            modalEditar.querySelector('#edit-acesso').value = acesso;
        });
       
    }

});

</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>