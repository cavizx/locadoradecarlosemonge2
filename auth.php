<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['ultimo_acesso']) && time() - $_SESSION['ultimo_acesso'] > 1800) {
    session_unset();
    session_destroy();
    header('Location: login.php?erro=expirada');
    exit;
}

$_SESSION['ultimo_acesso'] = time();
?>
