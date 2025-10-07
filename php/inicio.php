<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Home - StockControl</title>
</head>
<body>
    <h2>Bem-vindo, <?php echo $_SESSION["nome"]; ?>!</h2>
    <p>Seu tipo de usuário é: <strong><?php echo $_SESSION["tipo"]; ?></strong></p>
    <p><a href="logout.php">Sair</a></p>
</body>
</html>
