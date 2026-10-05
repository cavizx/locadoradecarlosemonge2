<?php
require 'auth.php';
require 'config.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = (int)($_POST['cliente_id'] ?? 0);
    $veiculo_id = (int)($_POST['veiculo_id'] ?? 0);
    $retirada = $_POST['data_retirada'] ?? '';
    $devolucao = $_POST['data_devolucao'] ?? '';
    $valor = (float)($_POST['valor'] ?? 0);

    if ($cliente_id && $veiculo_id && $retirada && $devolucao && $devolucao >= $retirada) {
        $stmt = $pdo->prepare("INSERT INTO locacoes (cliente_id, veiculo_id, data_retirada, data_devolucao, valor) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$cliente_id, $veiculo_id, $retirada, $devolucao, $valor]);

        $pdo->prepare("UPDATE veiculos SET disponivel = 0 WHERE id = ?")->execute([$veiculo_id]);
        $mensagem = 'Locação cadastrada com sucesso.';
    } else {
        $mensagem = 'Preencha os campos corretamente. A devolução deve ser igual ou posterior à retirada.';
    }
}

$clientes = $pdo->query("SELECT id, nome FROM clientes ORDER BY nome")->fetchAll();
$veiculos = $pdo->query("SELECT id, placa, marca, modelo FROM veiculos WHERE disponivel = 1 ORDER BY marca, modelo")->fetchAll();

$locacoes = $pdo->query("
    SELECT l.*, c.nome AS cliente, v.placa, v.marca, v.modelo
    FROM locacoes l
    INNER JOIN clientes c ON c.id = l.cliente_id
    INNER JOIN veiculos v ON v.id = l.veiculo_id
    ORDER BY l.data_retirada DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Locações</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="nav"><strong>Locadora</strong><div><a href="index.php">Início</a><a href="clientes.php">Clientes</a><a href="veiculos.php">Veículos</a><a href="logout.php">Sair</a></div></div>
<div class="container">
<div class="card">
<h2>Nova locação</h2>
<?php if ($mensagem): ?><div class="alert ok"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>
<form method="post">
<label>Cliente</label>
<select name="cliente_id" required>
<option value="">Selecione</option>
<?php foreach ($clientes as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option><?php endforeach; ?>
</select>
<label>Veículo</label>
<select name="veiculo_id" required>
<option value="">Selecione</option>
<?php foreach ($veiculos as $v): ?><option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['placa'].' - '.$v['marca'].' '.$v['modelo']) ?></option><?php endforeach; ?>
</select>
<label>Data de retirada</label><input type="date" name="data_retirada" required>
<label>Data de devolução</label><input type="date" name="data_devolucao" required>
<label>Valor</label><input type="number" step="0.01" name="valor" required>
<button type="submit">Cadastrar locação</button>
</form>
</div>

<div class="card">
<h2>Locações cadastradas</h2>
<table>
<tr><th>Retirada</th><th>Devolução</th><th>Cliente</th><th>Veículo</th><th>Valor</th></tr>
<?php foreach ($locacoes as $l): ?>
<tr>
<td><?= date('d/m/Y', strtotime($l['data_retirada'])) ?></td>
<td><?= date('d/m/Y', strtotime($l['data_devolucao'])) ?></td>
<td><?= htmlspecialchars($l['cliente']) ?></td>
<td><?= htmlspecialchars($l['placa'].' - '.$l['marca'].' '.$l['modelo']) ?></td>
<td>R$ <?= number_format($l['valor'], 2, ',', '.') ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</div>
</body>
</html>
