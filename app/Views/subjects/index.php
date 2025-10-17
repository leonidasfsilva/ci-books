o<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row mb-3 mb-md-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h1 class="display-6 display-md-5 fw-bold text-primary mb-0">
                    <i class="fas fa-tags me-2 me-md-3"></i>Gerenciar Assuntos
                </h1>
                <p class="text-muted mt-1 mt-md-2 small">Adicione, edite e gerencie seus assuntos com facilidade</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary rounded-pill btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="fas fa-plus me-1 me-md-2"></i><span class="d-none d-sm-inline">Novo </span>Assunto
                </button>
                <button class="btn btn-outline-primary rounded-pill btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1 me-md-2"></i><span class="d-none d-sm-inline">Atualizar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="mb-0 mb-sm-0 h6 h-md-5">
                    <i class="fas fa-list me-2"></i>Lista de Assuntos
                </h5>
                <span class="badge bg-light text-primary rounded-pill align-self-start">
                    <i class="fas fa-tag me-1"></i><?= count($subjects) ?> assuntos
                </span>
            </div>
            <div class="card-body p-3 p-md-4">
                <?php if (empty($subjects)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum assunto cadastrado</h5>
                        <p class="text-muted">Adicione seu primeiro assunto usando o botão acima.</p>
                    </div>
                <?php else: ?>
                    <!-- Desktop Table View -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="border-0">
                                            <i class="fas fa-hashtag me-1"></i>ID
                                        </th>
                                        <th class="border-0">
                                            <i class="fas fa-tag me-1"></i>Nome
                                        </th>
                                        <th class="border-0 text-center">
                                            <i class="fas fa-cogs me-1"></i>Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subjects as $subject): ?>
                                        <tr class="align-middle">
                                            <td>
                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                    #<?= $subject['codAs'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">
                                                    <?= esc($subject['Descricao']) ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-outline-warning btn-sm rounded-pill me-1"
                                                            onclick="editSubject(<?= $subject['codAs'] ?>, '<?= addslashes($subject['Descricao']) ?>')"
                                                            title="Editar assunto">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm rounded-pill"
                                                            onclick="confirmDeleteSubject(<?= $subject['codAs'] ?>, '<?= addslashes($subject['Descricao']) ?>')"
                                                            title="Excluir assunto">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-md-none">
                        <div class="row g-3">
                            <?php foreach ($subjects as $subject): ?>
                                <div class="col-12">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0 fw-bold text-truncate">
                                                    <?= esc($subject['Descricao']) ?>
                                                </h6>
                                                <span class="badge bg-primary rounded-pill ms-2 flex-shrink-0">
                                                    #<?= $subject['codAs'] ?>
                                                </span>
                                            </div>

                                            <div class="d-flex gap-2 mt-3">
                                                <button class="btn btn-outline-warning btn-sm rounded-pill flex-fill"
                                                        onclick="editSubject(<?= $subject['codAs'] ?>, '<?= addslashes($subject['Descricao']) ?>')">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm rounded-pill flex-fill"
                                                        onclick="confirmDeleteSubject(<?= $subject['codAs'] ?>, '<?= addslashes($subject['Descricao']) ?>')">
                                                    <i class="fas fa-trash-alt me-1"></i>Excluir
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title h6 h-md-5" id="addSubjectModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Adicionar Novo Assunto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="<?= base_url('subjects/create') ?>" method="post" class="needs-validation" novalidate>
                <div class="modal-body p-3 p-md-4">
                    <div class="mb-4">
                        <label for="Descricao" class="form-label fw-semibold required-field">
                            <i class="fas fa-tag me-1"></i>Nome do Assunto
                        </label>
                        <input type="text" class="form-control form-control-lg" id="Descricao" name="Descricao" required
                               placeholder="Digite o nome do assunto" maxlength="20">
                        <div class="invalid-feedback d-none">
                            Por favor, insira o nome do assunto (máx. 20 caracteres).
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill flex-fill">
                        <i class="fas fa-save me-2"></i>Adicionar Assunto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title h6 h-md-5" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i>Editar Assunto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="" method="post" id="editForm" class="needs-validation" novalidate>
                <div class="modal-body p-3 p-md-4">
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-4">
                        <label for="editDescricao" class="form-label fw-semibold required-field">
                            <i class="fas fa-tag me-1"></i>Nome do Assunto
                        </label>
                        <input type="text" class="form-control form-control-lg" id="editDescricao" name="Descricao" required maxlength="20">
                        <div class="invalid-feedback d-none">
                            Por favor, insira o nome do assunto (máx. 20 caracteres).
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill flex-fill">
                        <i class="fas fa-save me-2"></i>Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="deleteSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteSubjectModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h6>Tem certeza que deseja excluir o assunto?</h6>
                    <p class="text-muted mb-0"><strong>"<span id="deleteSubjectName"></span>"</strong></p>
                    <small class="text-muted">Esta ação não pode ser desfeita.</small>
                </div>
            </div>
            <div class="modal-footer bg-light d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <a href="" id="confirmDeleteSubjectBtn" class="btn rounded-pill flex-fill" style="background-color: #dc3545; border-color: #dc3545; color: white;">
                    <i class="fas fa-trash-alt me-2"></i>Excluir Assunto
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Reset form when add subject modal is closed
document.getElementById('addSubjectModal').addEventListener('hidden.bs.modal', function () {
    var form = this.querySelector('form');
    form.reset();
    form.classList.remove('was-validated');
});

function editSubject(id, descricao) {
    document.getElementById('editId').value = id;
    document.getElementById('editDescricao').value = descricao;
    document.getElementById('editForm').action = '<?= base_url('subjects/edit/') ?>' + id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

// Confirm delete subject with modal
function confirmDeleteSubject(subjectId, subjectName) {
    // Update modal content
    document.getElementById('deleteSubjectName').textContent = subjectName;
    document.getElementById('confirmDeleteSubjectBtn').href = '<?= base_url('subjects/delete/') ?>' + subjectId;

    // Show modal
    new bootstrap.Modal(document.getElementById('deleteSubjectModal')).show();
}
</script>
<?= $this->endSection() ?>