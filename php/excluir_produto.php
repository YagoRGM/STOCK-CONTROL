<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_produto = $_GET['id'];

if (isset($_GET['id'])) {
    $stmt = $conexao->prepare('DELETE FROM produtos WHERE id_produto = ?');
    $stmt->bind_param('i', $id_produto);
    $stmt->execute();
    header('location:listar_produtos.php');
}
