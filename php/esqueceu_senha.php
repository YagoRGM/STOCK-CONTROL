<?php
include('conexao.php');

$sucesso = false;
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email_digitado"]);
    $nova_senha = $_POST["senha_digitado"];
    $confirmar_senha = $_POST["confirmar_senha_digitado"];

    if ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if ($usuario) {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sqlUpdate = "UPDATE usuarios SET senha = ? WHERE email = ?";
            $stmtUpdate = $conexao->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ss", $senha_hash, $email);

            if ($stmtUpdate->execute()) {
                $sucesso = true;
            } else {
                $erro = "Erro ao atualizar a senha.";
            }
        } else {
            $erro = "Email não encontrado!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Esqueceu a senha</title>
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
            <a href="login.php" class="voltar"><i class="fas fa-arrow-left"></i> Voltar</a>
            <h2>Redefinir Senha</h2>

            <label>Email:</label>
            <input type="email" name="email_digitado" required>

            <label>Nova senha:</label>
            <input type="password" name="senha_digitado" required>

            <label>Confirmar nova senha:</label>
            <input type="password" name="confirmar_senha_digitado" required>

            <button type="submit">Alterar senha</button>
        </form>
    </div>

    <?php if ($sucesso) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Senha alterada com sucesso!',
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
