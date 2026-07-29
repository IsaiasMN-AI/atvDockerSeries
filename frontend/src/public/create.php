<?php
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_url = "http://api:8000/series";
    
    $dados = [
        "titulo" => $_POST['titulo'],
        "genero" => $_POST['genero'],
        "ano_lancamento" => (int)$_POST['ano_lancamento'],
        "temporadas" => (int)$_POST['temporadas']
    ];

    $opcoes = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($dados)
        ]
    ];

    $contexto = stream_context_create($opcoes);
    $resultado = @file_get_contents($api_url, false, $contexto);

    if ($resultado !== false) {
        header("Location: index.php");
        exit();
    } else {
        $mensagem = "<div class='alert alert-danger'>Erro ao salvar. Verifique as validações da API.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Série</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Adicionar Nova Série</h4>
        </div>
        <div class="card-body">
            <?= $mensagem ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gênero</label>
                    <input type="text" name="genero" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ano de Lançamento</label>
                        <input type="number" name="ano_lancamento" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Temporadas</label>
                        <input type="number" name="temporadas" class="form-control" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>