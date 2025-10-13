<?php
session_start();
include('conexao.php');

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    // Validação básica
    if (empty($nome) || empty($email) || empty($senha) || empty($tipo)) {
        $_SESSION['mensagem_erro'] = "Todos os campos são obrigatórios.";
        header("Location: gerenciar_usuarios.php");
        exit;
    }

    // Verifica se o email já existe
    $stmt = $conexao->prepare("SELECT id_usuario FROM usuarios WHERE email_usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['mensagem_erro'] = "Este email já está cadastrado.";
        $stmt->close();
        header("Location: gerenciar_usuarios.php");
        exit;
    }
    $stmt->close();

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o usuário no banco
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario, tipo_usuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senhaHash, $tipo);

    if ($stmt->execute()) {
        $_SESSION['mensagem_sucesso'] = "Usuário adicionado com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao adicionar usuário: " . $stmt->error;
    }

    $stmt->close();
    header("Location: gerenciar_usuarios.php");
    exit;
} else {
    // Redireciona se o acesso não foi via POST
    header("Location: gerenciar_usuarios.php");
    exit;
}
?>
