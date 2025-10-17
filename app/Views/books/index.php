<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row mb-3 mb-md-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h1 class="display-6 display-md-5 fw-bold text-primary mb-0">
                    <i class="fas fa-book-open me-2 me-md-3"></i>Gerenciar Livros
                </h1>
                <p class="text-muted mt-1 mt-md-2 small">Adicione, edite e gerencie seus livros com facilidade</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary rounded-pill btn-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    <i class="fas fa-plus me-1 me-md-2"></i><span class="d-none d-sm-inline">Novo </span>Livro
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
                    <i class="fas fa-list me-2"></i>Lista de Livros
                </h5>
                <span class="badge bg-light text-primary rounded-pill align-self-start">
                    <i class="fas fa-book me-1"></i><?= count($books) ?> livros
                </span>
            </div>
            <div class="card-body p-3 p-md-4">
                <?php if (empty($books)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum livro cadastrado</h5>
                        <p class="text-muted">Adicione seu primeiro livro usando o formulário ao lado.</p>
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
                                            <i class="fas fa-heading me-1"></i>Título
                                        </th>
                                        <th class="border-0">
                                            <i class="fas fa-building me-1"></i>Editora
                                        </th>
                                        <th class="border-0">
                                            <i class="fas fa-hashtag me-1"></i>Edição
                                        </th>
                                        <th class="border-0">
                                            <i class="fas fa-calendar-alt me-1"></i>Ano
                                        </th>
                                        <th class="border-0">
                                            <i class="fas fa-dollar-sign me-1"></i>Valor
                                        </th>
                                        <th class="border-0">
                                            <i class="fas fa-users me-1"></i>Autores
                                        </th>
                                        <th class="border-0 text-center">
                                            <i class="fas fa-cogs me-1"></i>Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($books as $book): ?>
                                        <tr class="align-middle">
                                            <td>
                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                    #<?= $book['CodL'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">
                                                    <?= esc($book['Titulo']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="fas fa-building me-1"></i>
                                                    <?= esc($book['Editora'] ?: 'N/A') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill">
                                                    <?= esc($book['Edicao'] ?: 'N/A') ?>ª edição
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?= esc($book['AnoPublicacao'] ?: 'N/A') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">
                                                    <i class="fas fa-dollar-sign me-1"></i>
                                                    R$ <?= number_format($book['Valor'], 2, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($book['authors'])): ?>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <?php
                                                        $authorsArray = explode(', ', $book['authors']);
                                                        foreach (array_slice($authorsArray, 0, 2) as $author): ?>
                                                            <span class="badge bg-info text-white rounded-pill px-2 py-1">
                                                                <i class="fas fa-user me-1"></i>
                                                                <?= esc(trim($author)) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                        <?php if (count($authorsArray) > 2): ?>
                                                            <span class="badge bg-secondary rounded-pill px-2 py-1">
                                                                +<?= count($authorsArray) - 2 ?> mais
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-user-slash me-1"></i>Sem autores
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-outline-warning btn-sm rounded-pill me-1"
                                                            onclick="editBook(<?= $book['CodL'] ?>)"
                                                            title="Editar livro">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm rounded-pill"
                                                            onclick="confirmDeleteBook(<?= $book['CodL'] ?>, '<?= esc($book['Titulo']) ?>')"
                                                            title="Excluir livro">
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
                            <?php foreach ($books as $book): ?>
                                <div class="col-12">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0 fw-bold text-truncate">
                                                    <?= esc($book['Titulo']) ?>
                                                </h6>
                                                <span class="badge bg-primary rounded-pill ms-2 flex-shrink-0">
                                                    #<?= $book['CodL'] ?>
                                                </span>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-building me-1"></i>Editora
                                                    </small>
                                                    <span class="fw-medium">
                                                        <?= esc($book['Editora'] ?: 'N/A') ?>
                                                    </span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-dollar-sign me-1"></i>Valor
                                                    </small>
                                                    <span class="fw-bold text-success">
                                                        R$ <?= number_format($book['Valor'], 2, ',', '.') ?>
                                                    </span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-hashtag me-1"></i>Edição
                                                    </small>
                                                    <span class="badge bg-secondary rounded-pill">
                                                        <?= esc($book['Edicao'] ?: 'N/A') ?>ª
                                                    </span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-calendar-alt me-1"></i>Ano
                                                    </small>
                                                    <span class="fw-medium">
                                                        <?= esc($book['AnoPublicacao'] ?: 'N/A') ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <?php if (!empty($book['authors'])): ?>
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-1">
                                                        <i class="fas fa-users me-1"></i>Autores
                                                    </small>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <?php
                                                        $authorsArray = explode(', ', $book['authors']);
                                                        foreach ($authorsArray as $author): ?>
                                                            <span class="badge bg-info text-white rounded-pill px-2 py-1">
                                                                <i class="fas fa-user me-1"></i>
                                                                <?= esc(trim($author)) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($book['subjects'])): ?>
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-1">
                                                        <i class="fas fa-tags me-1"></i>Assuntos
                                                    </small>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <?php
                                                        $subjectsArray = explode(', ', $book['subjects']);
                                                        foreach ($subjectsArray as $subject): ?>
                                                            <span class="badge bg-success text-white rounded-pill px-2 py-1">
                                                                <i class="fas fa-tag me-1"></i>
                                                                <?= esc(trim($subject)) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="d-flex gap-2 mt-3">
                                                <button class="btn btn-outline-warning btn-sm rounded-pill flex-fill"
                                                        onclick="editBook(<?= $book['CodL'] ?>)">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm rounded-pill flex-fill"
                                                        onclick="confirmDeleteBook(<?= $book['CodL'] ?>, '<?= esc($book['Titulo']) ?>')">
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

<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title h6 h-md-5" id="addBookModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Adicionar Novo Livro
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="<?= base_url('books/create') ?>" method="post" class="needs-validation" novalidate onsubmit="prepareFormData(this)">
                <div class="modal-body p-2 p-sm-3 p-md-4" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden;">
                    <div class="mb-3 mb-md-4">
                        <label for="titulo" class="form-label fw-semibold required-field">
                            <i class="fas fa-heading me-1"></i>Título do Livro
                        </label>
                        <input type="text" class="form-control form-control-lg" id="titulo" name="titulo" required
                               placeholder="Digite o título do livro" maxlength="40">
                    </div>

                    <div class="row mb-3 mb-md-4">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label for="editora" class="form-label fw-semibold required-field">
                                <i class="fas fa-building me-1"></i>Editora
                            </label>
                            <input type="text" class="form-control" id="editora" name="editora" required
                                   placeholder="Digite o nome da editora" maxlength="40">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="valor" class="form-label fw-semibold required-field">
                                <i class="fas fa-dollar-sign me-1"></i>Valor (R$)
                            </label>
                            <input type="text" class="form-control" id="valor" name="valor"
                                   required placeholder="0,00">
                        </div>
                    </div>

                    <div class="row mb-3 mb-md-4">
                        <div class="col-6 col-md-6 mb-3 mb-md-0">
                            <label for="edicao" class="form-label fw-semibold required-field">
                                <i class="fas fa-hashtag me-1"></i>Edição
                            </label>
                            <input type="number" class="form-control" id="edicao" name="edicao"
                                   placeholder="1" min="1">
                        </div>

                        <div class="col-6 col-md-6">
                            <label for="ano_publicacao" class="form-label fw-semibold required-field">
                                <i class="fas fa-calendar-alt me-1"></i>Ano de Publicação
                            </label>
                            <input type="text" class="form-control" id="ano_publicacao" name="ano_publicacao"
                                   placeholder="2024" maxlength="4">
                        </div>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold required-field">
                            <i class="fas fa-users me-1"></i>Autores
                        </label>
                        <div class="d-flex flex-wrap gap-1 gap-md-2" id="addAuthorsContainer" style="max-height: 120px; overflow-y: auto;">
                            <?php if (empty($authors)): ?>
                                <p class="text-muted mb-0 small">Nenhum autor cadastrado.</p>
                            <?php else: ?>
                                <?php foreach ($authors as $author): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input add-authors" type="checkbox" name="authors[]" value="<?= $author['CodAu'] ?>"
                                               id="add_author_<?= $author['CodAu'] ?>">
                                        <label class="form-check-label badge bg-light text-dark border px-2 px-md-3 py-1 py-md-2 small" for="add_author_<?= $author['CodAu'] ?>">
                                            <i class="fas fa-user text-primary me-1"></i>
                                            <?= esc($author['Nome']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="mt-1 mt-md-2">
                            <small class="text-muted">Clique nos badges para selecionar autores</small>
                        </div>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold required-field">
                            <i class="fas fa-tags me-1"></i>Assuntos
                        </label>
                        <div class="d-flex flex-wrap gap-1 gap-md-2" id="addSubjectsContainer" style="max-height: 120px; overflow-y: auto;">
                            <?php if (empty($subjects)): ?>
                                <p class="text-muted mb-0 small">Nenhum assunto cadastrado.</p>
                            <?php else: ?>
                                <?php foreach ($subjects as $subject): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input add-subjects" type="checkbox" name="subjects[]" value="<?= $subject['codAs'] ?>"
                                               id="add_subject_<?= $subject['codAs'] ?>">
                                        <label class="form-check-label badge bg-light text-dark border px-2 px-md-3 py-1 py-md-2 small" for="add_subject_<?= $subject['codAs'] ?>">
                                            <i class="fas fa-tag text-success me-1"></i>
                                            <?= esc($subject['Descricao']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="mt-1 mt-md-2">
                            <small class="text-muted">Clique nos badges para selecionar assuntos</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill flex-fill">
                        <i class="fas fa-save me-2"></i>Adicionar Livro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title h6 h-md-5" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i>Editar Livro
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="" method="post" id="editForm" class="needs-validation" novalidate onsubmit="prepareFormData(this)">
                <div class="modal-body p-2 p-sm-3 p-md-4" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden;">
                    <input type="hidden" id="editId" name="id">

                    <div class="mb-3 mb-md-4">
                        <label for="editTitulo" class="form-label fw-semibold required-field">
                            <i class="fas fa-heading me-1"></i>Título do Livro
                        </label>
                        <input type="text" class="form-control form-control-lg" id="editTitulo" name="titulo" required maxlength="40">
                    </div>

                    <div class="row mb-3 mb-md-4">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label for="editEditora" class="form-label fw-semibold required-field">
                                <i class="fas fa-building me-1"></i>Editora
                            </label>
                            <input type="text" class="form-control" id="editEditora" name="editora" required maxlength="40">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="editValor" class="form-label fw-semibold required-field">
                                <i class="fas fa-dollar-sign me-1"></i>Valor (R$)
                            </label>
                            <input type="text" class="form-control" id="editValor" name="valor"
                                   required>
                        </div>
                    </div>

                    <div class="row mb-3 mb-md-4">
                        <div class="col-6 col-md-6 mb-3 mb-md-0">
                            <label for="editEdicao" class="form-label fw-semibold required-field">
                                <i class="fas fa-hashtag me-1"></i>Edição
                            </label>
                            <input type="number" class="form-control" id="editEdicao" name="edicao" min="1">
                        </div>

                        <div class="col-6 col-md-6">
                            <label for="editAnoPublicacao" class="form-label fw-semibold required-field">
                                <i class="fas fa-calendar-alt me-1"></i>Ano de Publicação
                            </label>
                            <input type="text" class="form-control" id="editAnoPublicacao" name="ano_publicacao"
                                   maxlength="4">
                        </div>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold required-field">
                            <i class="fas fa-users me-1"></i>Autores
                        </label>
                        <div class="d-flex flex-wrap gap-1 gap-md-2" id="editAuthorsContainer" style="max-height: 120px; overflow-y: auto;">
                            <?php foreach ($authors as $author): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input edit-authors" type="checkbox" name="authors[]" value="<?= $author['CodAu'] ?>"
                                           id="edit_author_<?= $author['CodAu'] ?>">
                                    <label class="form-check-label badge bg-light text-dark border px-2 px-md-3 py-1 py-md-2 small" for="edit_author_<?= $author['CodAu'] ?>">
                                        <i class="fas fa-user text-primary me-1"></i>
                                        <?= esc($author['Nome']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-1 mt-md-2">
                            <small class="text-muted">Clique nos badges para selecionar autores</small>
                        </div>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold required-field">
                            <i class="fas fa-tags me-1"></i>Assuntos
                        </label>
                        <div class="d-flex flex-wrap gap-1 gap-md-2" id="editSubjectsContainer" style="max-height: 120px; overflow-y: auto;">
                            <?php foreach ($subjects as $subject): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input edit-subjects" type="checkbox" name="subjects[]" value="<?= $subject['codAs'] ?>"
                                           id="edit_subject_<?= $subject['codAs'] ?>">
                                    <label class="form-check-label badge bg-light text-dark border px-2 px-md-3 py-1 py-md-2 small" for="edit_subject_<?= $subject['codAs'] ?>">
                                        <i class="fas fa-tag text-success me-1"></i>
                                        <?= esc($subject['Descricao']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-1 mt-md-2">
                            <small class="text-muted">Clique nos badges para selecionar assuntos</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill flex-fill">
                        <i class="fas fa-save me-2"></i><span class="d-none d-sm-inline">Salvar </span>Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Form validation with jQuery Validation
function initializeFormValidation() {
    // Add custom validation methods
    $.validator.addMethod("tituloPattern", function(value, element) {
        return this.optional(element) || /^[a-zA-ZÀ-ÿ0-9\s\-.\'&]+$/.test(value);
    }, "Título deve conter apenas letras, números, espaços, hífen, ponto, apóstrofo e &.");

    $.validator.addMethod("editoraPattern", function(value, element) {
        return this.optional(element) || /^[a-zA-ZÀ-ÿ0-9\s\-.\'&]*$/.test(value);
    }, "Editora deve conter apenas letras, números, espaços, hífen, ponto, apóstrofo e &.");

    $.validator.addMethod("anoPattern", function(value, element) {
        return this.optional(element) || /^\d{4}$/.test(value);
    }, "Ano deve ter exatamente 4 dígitos.");

    $.validator.addMethod("atLeastOneAuthor", function(value, element) {
        // Check if at least one author checkbox is checked
        const form = element.closest('form');
        const authorCheckboxes = form.querySelectorAll('input[name="authors[]"]:checked');
        return authorCheckboxes.length > 0;
    }, "Este campo é obrigatório.");

    $.validator.addMethod("atLeastOneSubject", function(value, element) {
        // Check if at least one subject checkbox is checked
        const form = element.closest('form');
        const subjectCheckboxes = form.querySelectorAll('input[name="subjects[]"]:checked');
        return subjectCheckboxes.length > 0;
    }, "Este campo é obrigatório.");

    // Initialize validation for add book form
    $("#addBookModal form").validate({
        rules: {
            titulo: {
                required: true,
                maxlength: 40,
                tituloPattern: true
            },
            editora: {
                required: true,
                maxlength: 40,
                editoraPattern: true
            },
            edicao: {
                required: true,
                number: true,
                min: 1
            },
            ano_publicacao: {
                required: true,
                anoPattern: true
            },
            messages: {
                ano_publicacao: {
                    required: "Este campo é obrigatório."
                }
            },
            valor: {
                required: true,
                number: true,
                min: 0.01
            },
            "authors[]": {
                atLeastOneAuthor: true
            },
            "subjects[]": {
                atLeastOneSubject: true
            }
        },
        messages: {
            titulo: {
                required: "Este campo é obrigatório.",
                maxlength: "Título deve ter no máximo 40 caracteres."
            },
            editora: {
                required: "Este campo é obrigatório.",
                maxlength: "Editora deve ter no máximo 40 caracteres."
            },
            edicao: {
                required: "Este campo é obrigatório.",
                number: "Edição deve ser um número.",
                min: "Edição deve ser maior que zero."
            },
            valor: {
                required: "Este campo é obrigatório.",
                number: "Valor deve ser um número.",
                min: "Valor deve ser maior que zero."
            }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            // Create error div if it doesn't exist
            let errorDiv = element.parent().find('.invalid-feedback');
            if (errorDiv.length === 0) {
                errorDiv = $('<div class="invalid-feedback"></div>');
                element.after(errorDiv);
            }
            errorDiv.html(error.text()).show();
            element.addClass('is-invalid');
        },
        success: function(label, element) {
            $(element).removeClass('is-invalid');
            $(element).parent().find('.invalid-feedback').hide();
        },
        submitHandler: function(form) {
            // Prepare form data before submission
            prepareFormData(form);
            form.submit();
        }
    });

    // Initialize validation for edit book form
    $("#editForm").validate({
        rules: {
            titulo: {
                required: true,
                maxlength: 40,
                tituloPattern: true
            },
            editora: {
                required: true,
                maxlength: 40,
                editoraPattern: true
            },
            edicao: {
                required: true,
                number: true,
                min: 1
            },
            ano_publicacao: {
                required: true,
                anoPattern: true
            },
            valor: {
                required: true,
                number: true,
                min: 0.01
            },
            "authors[]": {
                atLeastOneAuthor: true
            },
            "subjects[]": {
                atLeastOneSubject: true
            }
        },
        messages: {
            titulo: {
                required: "Este campo é obrigatório.",
                maxlength: "Título deve ter no máximo 40 caracteres."
            },
            editora: {
                required: "Este campo é obrigatório.",
                maxlength: "Editora deve ter no máximo 40 caracteres."
            },
            edicao: {
                required: "Este campo é obrigatório.",
                number: "Edição deve ser um número.",
                min: "Edição deve ser maior que zero."
            },
            valor: {
                required: "Este campo é obrigatório.",
                number: "Valor deve ser um número.",
                min: "Valor deve ser maior que zero."
            }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            // Create error div if it doesn't exist
            let errorDiv = element.parent().find('.invalid-feedback');
            if (errorDiv.length === 0) {
                errorDiv = $('<div class="invalid-feedback"></div>');
                element.after(errorDiv);
            }
            errorDiv.html(error.text()).show();
            element.addClass('is-invalid');
        },
        success: function(label, element) {
            $(element).removeClass('is-invalid');
            $(element).parent().find('.invalid-feedback').hide();
        },
        submitHandler: function(form) {
            // Prepare form data before submission
            prepareFormData(form);
            form.submit();
        }
    });
}

// Reset form when add book modal is closed
document.getElementById('addBookModal').addEventListener('hidden.bs.modal', function () {
    var form = this.querySelector('form');
    form.reset();
    form.classList.remove('was-validated');
    // Clear checkboxes
    form.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.checked = false;
    });
});

// Initialize jQuery MaskMoney for currency formatting
function initializeCurrencyFormatting() {
    // Apply maskMoney to all currency inputs
    $('input[name="valor"]').maskMoney({
        prefix: 'R$ ',
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: true
    });
}

// Allow free typing during input (global function)
function allowCurrencyInput(input) {
    // Just remove invalid characters but keep the value as-is for editing
    let value = input.value;

    // Remove any character that's not digit, comma, or period
    value = value.replace(/[^\d.,]/g, '');

    // Ensure only one decimal separator (prefer comma for Brazilian style)
    const commaCount = (value.match(/,/g) || []).length;
    const periodCount = (value.match(/\./g) || []).length;

    if (commaCount > 1 || (commaCount > 0 && periodCount > 0)) {
        // Remove extra separators, keeping only the last one
        const parts = value.split(/[,|\.]/);
        const lastSeparator = value.lastIndexOf(',') > value.lastIndexOf('.') ? ',' : '.';
        value = parts.slice(0, -1).join('') + lastSeparator + parts[parts.length - 1];
    }

    input.value = value;
}

// Format currency (global function)
function formatCurrency(input) {
    let value = input.value.replace(/[^\d.,]/g, '');

    if (value) {
        // Replace comma with period for parsing
        value = value.replace(',', '.');

        // Ensure only one decimal point
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }

        // Limit to 2 decimal places
        if (parts.length === 2 && parts[1].length > 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }

        const numericValue = parseFloat(value);
        if (!isNaN(numericValue)) {
            input.value = 'R$ ' + numericValue.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    } else {
        input.value = '';
    }
}

// Extract numeric value for form submission
function getNumericValue(formattedValue) {
    if (!formattedValue) return '';

    // Remove 'R$ ' prefix and formatting
    let numeric = formattedValue.replace('R$ ', '').replace(/\./g, '').replace(',', '.');

    return parseFloat(numeric) || '';
}

// Edit book function
function editBook(id) {
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    const modalTitle = document.querySelector('#editModalLabel');
    modalTitle.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Carregando...';

    fetch('<?= base_url('books/getBook/') ?>' + id)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
            // Reset modal title
            modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>Editar Livro';

            // Populate form fields
            document.getElementById('editId').value = data.CodL;
            document.getElementById('editTitulo').value = data.Titulo;
            document.getElementById('editEditora').value = data.Editora || '';
            document.getElementById('editEdicao').value = data.Edicao || '';
            document.getElementById('editAnoPublicacao').value = data.AnoPublicacao || '';
            document.getElementById('editValor').value = data.Valor ? parseFloat(data.Valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';

            // Clear all checkboxes
            document.querySelectorAll('.edit-authors').forEach(cb => cb.checked = false);
            document.querySelectorAll('.edit-subjects').forEach(cb => cb.checked = false);

            // Check selected authors
            if (data.authors && Array.isArray(data.authors)) {
                data.authors.forEach(authorId => {
                    const checkbox = document.getElementById('edit_author_' + authorId);
                    if (checkbox) checkbox.checked = true;
                });
            }

            // Check selected subjects
            if (data.subjects && Array.isArray(data.subjects)) {
                data.subjects.forEach(subjectId => {
                    const checkbox = document.getElementById('edit_subject_' + subjectId);
                    if (checkbox) checkbox.checked = true;
                });
            }

            // Set form action
            document.getElementById('editForm').action = '<?= base_url('books/edit/') ?>' + id;

            // Show modal
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            modalTitle.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Erro ao carregar';
            alert('Erro ao carregar dados do livro. Tente novamente.');
        });
}

// Confirm delete function
function confirmDelete(message) {
    return confirm(message);
}

// Confirm delete book with modal
function confirmDeleteBook(bookId, bookTitle) {
    // Create modal HTML
    const modalHtml = `
        <div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteBookModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Exclusão
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                            <h6>Tem certeza que deseja excluir o livro?</h6>
                            <p class="text-muted mb-0"><strong>"${bookTitle}"</strong></p>
                            <small class="text-muted">Esta ação não pode ser desfeita.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <a href="<?= base_url('books/delete/') ?>${bookId}" class="btn rounded-pill flex-fill" style="background-color: #dc3545; border-color: #dc3545; color: white;">
                            <i class="fas fa-trash-alt me-2"></i>Excluir Livro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if present
    const existingModal = document.getElementById('deleteBookModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteBookModal'));
    modal.show();

    // Clean up modal after hiding
    document.getElementById('deleteBookModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Prepare form data before submission
function prepareFormData(form) {
    const valorInput = form.querySelector('input[name="valor"]');
    if (valorInput && valorInput.value) {
        // Convert formatted currency back to numeric value
        const numericValue = getNumericValue(valorInput.value);
        valorInput.value = numericValue;
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCurrencyFormatting();
    initializeFormValidation();

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
<?= $this->endSection() ?>