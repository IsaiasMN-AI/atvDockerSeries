<?php
$mensagem = "";
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_url = "http://api:8000/series/" . $id;
    
    $opcoes = [
        'http' => [
            'method'  => 'DELETE'
        ]
    ];

    $contexto = stream_context_create($opcoes);
    $resultado = @file_get_contents($api_url, false, $contexto);

    if ($resultado !== false) {
        header("Location: index.php");
        exit();
    } else {
        $mensagem = "<div class='alert alert-danger'>Erro ao excluir a série.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Excluir Série</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm border-danger mx-auto" style="max-width: 500px;">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Atenção!</h4>
        </div>
        <div class="card-body text-center">
            <?= $mensagem ?>
            <h5 class="card-title">Tem certeza que deseja excluir esta série?</h5>
            <p class="card-text text-muted">Ação irreversível para o ID #<?= htmlspecialchars($id) ?>.</p>
            
            <form method="POST" class="mt-4">
                <a href="index.php" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-danger">Sim, Excluir</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>