<?php
session_start();
require 'config.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.html');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['ultimo_acesso'] = time();
        header('Location: index.html');
        exit;
    }

    $erro = 'E-mail ou senha inválidos.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="card form">
    <h2>Locadora - Login</h2>
    <?php if ($erro): ?><div class="alert"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'expirada'): ?>
        <div class="alert">Sua sessão expirou. Faça login novamente.</div>
    <?php endif; ?>
    <form method="post">
        <label>E-mail</label>
        <input type="email" name="email" required>
        <label>Senha</label>
        <input type="password" name="senha" required>
        <button type="submit">Entrar</button>
    </form>
    <p><small>Teste: admin@locadora.com / 123456</small></p>
</div>
</body>
</html>
