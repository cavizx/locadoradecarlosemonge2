<?php
require 'auth.php';
require 'config.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    if ($nome && $cpf) {
        try {
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, cpf, telefone, email, endereco) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $cpf, $telefone, $email, $endereco]);
            $mensagem = 'Cliente cadastrado com sucesso.';
        } catch (PDOException $e) {
            $mensagem = 'Não foi possível cadastrar. Verifique se o CPF já existe.';
        }
    }
}

$busca = trim($_GET['busca'] ?? '');
if ($busca !== '') {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nome LIKE ? OR cpf LIKE ? ORDER BY nome");
    $like = "%$busca%";
    $stmt->execute([$like, $like]);
    $clientes = $stmt->fetchAll();
} else {
    $clientes = $pdo->query("SELECT * FROM clientes ORDER BY nome")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Clientes</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="nav"><strong>Locadora</strong><div><a href="index.php">Início</a><a href="veiculos.php">Veículos</a><a href="locacoes.php">Locações</a><a href="logout.php">Sair</a></div></div>
<div class="container">
<div class="card">
<h2>Cadastrar cliente</h2>
<?php if ($mensagem): ?><div class="alert ok"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>
<form method="post">
<input name="nome" placeholder="Nome" required>
<input name="cpf" placeholder="CPF" required>
<input name="telefone" placeholder="Telefone">
<input type="email" name="email" placeholder="E-mail">
<input name="endereco" placeholder="Endereço">
<button type="submit">Cadastrar</button>
</form>
</div>

<div class="card">
<h2>Buscar cliente</h2>
<form method="get">
<input name="busca" value="<?= htmlspecialchars($busca) ?>" placeholder="Nome ou CPF">
<button type="submit">Buscar</button>
<a class="btn btn-secondary" href="clientes.php">Limpar</a>
</form>
</div>

<div class="card">
<h2>Clientes cadastrados</h2>
<table><tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>E-mail</th></tr>
<?php foreach ($clientes as $c): ?>
<tr><td><?= htmlspecialchars($c['nome']) ?></td><td><?= htmlspecialchars($c['cpf']) ?></td><td><?= htmlspecialchars($c['telefone']) ?></td><td><?= htmlspecialchars($c['email']) ?></td></tr>
<?php endforeach; ?>
</table>
</div>
</div>
</body>
</html>
