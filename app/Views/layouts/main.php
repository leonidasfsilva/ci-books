<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Biblioteca Web') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow-sm fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= base_url() ?>">
                <i class="fas fa-book me-2"></i>
                Biblioteca Web
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill mx-1" href="<?= base_url() ?>">
                            <i class="fas fa-home me-1"></i>Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill mx-1" href="<?= base_url('authors') ?>">
                            <i class="fas fa-user-edit me-1"></i>Autores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill mx-1" href="<?= base_url('subjects') ?>">
                            <i class="fas fa-tags me-1"></i>Assuntos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill mx-1" href="<?= base_url('books') ?>">
                            <i class="fas fa-book-open me-1"></i>Livros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill mx-1" href="<?= base_url('reports') ?>">
                            <i class="fas fa-chart-bar me-1"></i>Relatório
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-shrink-0" style="margin-top: 60px;">
        <div class="container-fluid px-1 px-sm-2 px-md-4 py-2 py-sm-3 py-md-5">
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <footer class="bg-dark text-light py-2 py-sm-3 py-md-4 mt-auto">
        <div class="container-fluid px-1 px-sm-2 px-md-4">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-book me-2"></i>Biblioteca Web
                    </h6>
                    <p class="mb-0 small">Gerencie seus livros, autores e assuntos de forma eficiente.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small">
                        <i class="fas fa-code me-1"></i>Desenvolvido com CodeIgniter 4
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/main.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>