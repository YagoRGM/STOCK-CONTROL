<?php
session_start();
include('conexao.php');

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['editar_usuario_id'])) {

    $id_usuario = intval($_POST['editar_usuario_id']);
    $nome = trim($_POST['nome_digitado']);
    $email = trim($_POST['email_digitado']);
    $tipo = trim($_POST['tipo_digitado']);

    if (empty($nome) || empty($email) || empty($tipo)) {
        $_SESSION['mensagem_erro'] = "Todos os campos são obrigatórios!";
        header("Location: gerenciar_usuarios.php");
        exit;
    }

    $stmt = $conexao->prepare("UPDATE usuarios SET nome_usuario = ?, email_usuario = ?, tipo_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("sssi", $nome, $email, $tipo, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensagem_sucesso'] = "Usuário atualizado com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar usuário!";
    }

    $stmt->close();
}

header("Location: gerenciar_usuarios.php");
exit;
?>
