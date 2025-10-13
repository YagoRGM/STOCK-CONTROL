<?php
include('conexao.php');

$sucesso = false;
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome_digitado"]);
    $email = trim($_POST["email_digitado"]);
    $senha = password_hash($_POST["senha_digitado"], PASSWORD_DEFAULT);
    $tipo = $_POST["tipo_digitado"];

    $sql = "INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario, tipo_usuario) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);

    if ($stmt->execute()) {
        $sucesso = true;
    } else {
        $erro = "Erro ao cadastrar usuário.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="../img/logo1.png" alt="Logo StockControl" class="logo">
        </div>

        <form method="POST" action="">
            <a href="login.php" class="voltar">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>

            <h2>Cadastro de Usuário</h2>

            <label>Nome:</label>
            <input type="text" name="nome_digitado" required>

            <label>Email:</label>
            <input type="email" name="email_digitado" required>

            <label>Senha:</label>
            <input type="password" name="senha_digitado" required>

            <label>Tipo:</label>
            <select name="tipo_digitado">
                <option value="funcionario">Funcionário</option>
                <option value="Administrador">Administrador</option>
            </select>

            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <?php if ($sucesso) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Usuário cadastrado com sucesso. Você será redirecionado para o login.',
                confirmButtonColor: '#DA020E'
            }).then(() => {
                window.location.href = 'login.php';
            });
        </script>
    <?php elseif ($erro) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $erro ?>',
                confirmButtonColor: '#DA020E'
            });
        </script>
    <?php endif; ?>
</body>

</html>
