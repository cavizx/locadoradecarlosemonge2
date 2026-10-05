<?php
require 'auth.php';
require 'config.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = strtoupper(trim($_POST['placa'] ?? ''));
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $ano = (int)($_POST['ano'] ?? 0);

    if ($placa && $marca && $modelo && $ano) {
        try {
            $stmt = $pdo->prepare("INSERT INTO veiculos (placa, marca, modelo, ano) VALUES (?, ?, ?, ?)");
            $stmt->execute([$placa, $marca, $modelo, $ano]);
            $mensagem = 'Veículo cadastrado com sucesso.';
        } catch (PDOException $e) {
            $mensagem = 'Não foi possível cadastrar. Verifique se a placa já existe.';
        }
    }
}

$veiculos = $pdo->query("SELECT * FROM veiculos ORDER BY marca, modelo")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Veículos</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="nav"><strong>Locadora</strong><div><a href="index.php">Início</a><a href="clientes.php">Clientes</a><a href="locacoes.php">Locações</a><a href="logout.php">Sair</a></div></div>
<div class="container">
<div class="card">
<h2>Cadastrar veículo</h2>
<?php if ($mensagem): ?><div class="alert ok"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>
<form method="post">
<input name="placa" placeholder="Placa" required>
<input name="marca" placeholder="Marca" required>
<input name="modelo" placeholder="Modelo" required>
<input type="number" name="ano" placeholder="Ano" required>
<button type="submit">Cadastrar</button>
</form>
</div>
<div class="card">
<h2>Veículos</h2>
<table><tr><th>Placa</th><th>Marca</th><th>Modelo</th><th>Ano</th><th>Status</th></tr>
<?php foreach ($veiculos as $v): ?>
<tr><td><?= htmlspecialchars($v['placa']) ?></td><td><?= htmlspecialchars($v['marca']) ?></td><td><?= htmlspecialchars($v['modelo']) ?></td><td><?= $v['ano'] ?></td><td><?= $v['disponivel'] ? 'Disponível' : 'Indisponível' ?></td></tr>
<?php endforeach; ?>
</table>
</div>
</div>
</body>
</html>
