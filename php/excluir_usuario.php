<?php
include('conexao.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

// Verifica se o ID do usuário foi passado
if (!isset($_GET['id'])) {
    header("Location: gerenciar_usuarios.php");
    exit;
}

$id_usuario = (int) $_GET['id']; // Cast para int para segurança

// Evita que o próprio usuário se exclua
if ($id_usuario == $_SESSION["id_usuario"]) {
    $_SESSION['mensagem_erro'] = "Você não pode excluir a si mesmo!";
    header("Location: gerenciar_usuarios.php");
    exit;
}

// Prepara o DELETE
$stmt = $conexao->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    $_SESSION['mensagem_sucesso'] = "O usuário foi excluído com sucesso!";
} else {
    $_SESSION['mensagem_erro'] = "Não foi possível excluir o usuário.";
}

$stmt->close();

// Redireciona de volta ao gerenciar usuários
header("Location: gerenciar_usuarios.php");
exit;
?>
