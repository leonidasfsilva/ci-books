<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Relatório Consolidado de Livros</h1>
<p class="lead">Relatório agrupado por autor, mostrando livros, assuntos e valores.</p>

<div class="card print-content">
    <div class="card-header">
        <h5>Dados do Relatório</h5>
    </div>
    <div class="card-body">
        <?php if (empty($reportData)): ?>
            <p class="text-muted">Nenhum dado encontrado no relatório.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Autor</th>
                            <th>Livro</th>
                            <th>Descrição</th>
                            <th>Ano de Publicação</th>
                            <th>Assuntos</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentAuthor = '';
                        foreach ($reportData as $row):
                            $showAuthor = $currentAuthor !== $row['Autores'];
                            if ($showAuthor) {
                                $currentAuthor = $row['Autores'];
                            }
                        ?>
                            <tr>
                                <td><?= $showAuthor ? esc($row['Autores']) : '' ?></td>
                                <td><?= esc($row['Titulo']) ?></td>
                                <td>
                                    <?php if (!empty($row['Editora'])): ?>
                                        Editora: <?= esc($row['Editora']) ?><br>
                                    <?php endif; ?>
                                    <?php if (!empty($row['Edicao'])): ?>
                                        Edição: <?= esc($row['Edicao']) ?>ª<br>
                                    <?php endif; ?>
                                    <?php if (!empty($row['AnoPublicacao'])): ?>
                                        Ano: <?= esc($row['AnoPublicacao']) ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row['AnoPublicacao'] ?: 'N/A' ?></td>
                                <td><?= esc($row['Assuntos'] ?: 'N/A') ?></td>
                                <td>R$ <?= number_format($row['Valor'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4 no-print">
    <div class="d-sm-none">
        <div class="row g-1">
            <div class="col-4">
                <a href="<?= base_url() ?>" class="btn btn-secondary w-100">
                    <i class="fas fa-home"></i>
                </a>
            </div>
            <div class="col-4">
                <button onclick="printReport()" class="btn btn-primary w-100">
                    <i class="fas fa-print"></i>
                </button>
            </div>
            <div class="col-4">
                <a href="<?= base_url('reports/exportExcel') ?>" class="btn btn-success w-100">
                    <i class="fas fa-file-excel"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="d-none d-sm-block text-end">
        <a href="<?= base_url() ?>" class="btn btn-secondary">Voltar ao Início</a>
        <button onclick="printReport()" class="btn btn-primary">Imprimir Relatório</button>
        <a href="<?= base_url('reports/exportExcel') ?>" class="btn btn-success">
            <i class="fas fa-file-excel me-1"></i>Baixar Excel
        </a>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .print-content {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6 !important;
    }
    body {
        background: white !important;
    }
    .navbar, .footer {
        display: none !important;
    }
}
</style>

<script>
function printReport() {
    window.print();
}
</script>
<?= $this->endSection() ?>