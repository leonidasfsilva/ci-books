<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Autor</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1>Criar Novo Autor</h1>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('authors/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nome">Nome do Autor:</label>
                <input type="text" id="nome" name="nome" value="<?= old('nome') ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= site_url('authors') ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>