<?php
session_start();

// Destrói todas as variáveis de sessão
$_SESSION = array();

// Invalida o cookie de sessão
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header("Location: login.php");
exit();
?>
