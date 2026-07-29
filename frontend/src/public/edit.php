<?php
$mensagem = "";
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit();
}

// Busca os dados atuais da série para preencher o formulário
$dados_atuais = [];
$api_url_get = "http://api:8000/series/" . $id;
$response = @file_get_contents($api_url_get);
if ($response !== false) {
    $dados_atuais = json_decode($response, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_url_patch = "http://api:8000/series/" . $id; 
    
    $dados_enviar = [
        "titulo" => $_POST['titulo'],
        "genero" => $_POST['genero'],
        "ano_lancamento" => (int)$_POST['ano_lancamento'],
        "temporadas" => (int)$_POST['temporadas']
    ];

    $opcoes = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'PATCH',
            'content' => json_encode($dados_enviar)
        ]
    ];

    $contexto = stream_context_create($opcoes);
    $resultado = @file_get_contents($api_url_patch, false, $contexto);

    if ($resultado !== false) {
        header("Location: index.php");
        exit();
    } else {
        $mensagem = "<div class='alert alert-danger'>Erro ao atualizar a série.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Série</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Editar Série #<?= htmlspecialchars($id) ?></h4>
        </div>
        <div class="card-body">
            <?= $mensagem ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($dados_atuais['titulo'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gênero</label>
                    <input type="text" name="genero" class="form-control" value="<?= htmlspecialchars($dados_atuais['genero'] ?? '') ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ano de Lançamento</label>
                        <input type="number" name="ano_lancamento" class="form-control" value="<?= htmlspecialchars($dados_atuais['ano_lancamento'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Temporadas</label>
                        <input type="number" name="temporadas" class="form-control" value="<?= htmlspecialchars($dados_atuais['temporadas'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>