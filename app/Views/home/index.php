<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron bg-light p-5 rounded">
            <h1 class="display-4">Bem-vindo à Biblioteca Web</h1>
            <p class="lead">Gerencie seu acervo de livros, autores e assuntos de forma eficiente.</p>
            <hr class="my-4">
            <p>Use o menu acima para navegar pelos diferentes módulos do sistema.</p>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Autores</h5>
                            <p class="card-text">Gerencie os autores do seu acervo.</p>
                            <a href="<?= base_url('authors') ?>" class="btn btn-primary">Acessar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Assuntos</h5>
                            <p class="card-text">Organize os assuntos dos livros.</p>
                            <a href="<?= base_url('subjects') ?>" class="btn btn-primary">Acessar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Livros</h5>
                            <p class="card-text">Cadastre e gerencie seus livros.</p>
                            <a href="<?= base_url('books') ?>" class="btn btn-primary">Acessar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Relatório</h5>
                            <p class="card-text">Visualize relatórios consolidados.</p>
                            <a href="<?= base_url('reports') ?>" class="btn btn-primary">Acessar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>