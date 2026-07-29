<?php
$api_url = "http://api:8000/series";
$response = @file_get_contents($api_url);
$series = $response ? json_decode($response, true) : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Séries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📺 Catálogo de Séries</h2>
        <a href="create.php" class="btn btn-success">+ Nova Série</a>
    </div>

    <?php if ($response === false): ?>
        <div class="alert alert-danger">Não foi possível conectar à API.</div>
    <?php elseif (empty($series)): ?>
        <div class="alert alert-warning">Nenhuma série cadastrada ainda.</div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover table-striped m-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Gênero</th>
                            <th>Ano</th>
                            <th>Temporadas</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($series as $serie): ?>
                            <tr>
                                <td><?= htmlspecialchars($serie['id']) ?></td>
                                <td><?= htmlspecialchars($serie['titulo']) ?></td>
                                <td><?= htmlspecialchars($serie['genero']) ?></td>
                                <td><?= htmlspecialchars($serie['ano_lancamento']) ?></td>
                                <td><?= htmlspecialchars($serie['temporadas']) ?></td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $serie['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="delete.php?id=<?= $serie['id'] ?>" class="btn btn-sm btn-danger">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>