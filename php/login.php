<?php
include('conexao.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email_digitado"];
    $senha = $_POST["senha_digitado"];

    $sql = "SELECT * FROM usuarios WHERE email_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario["senha_usuario"])) {
            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nome_usuario"] = $usuario["nome_usuario"];
            $_SESSION["email_usuario"] = $usuario["email_usuario"];
            $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];
            $sucesso = true;
        } else {
            $senha_incorreta = true;
        }
    } else {
        $usuario_nao_encontrado = true;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - StockControl</title>
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

            <h2>Entrar</h2>

            <label>Email:</label>
            <input type="email" name="email_digitado" required>

            <label>Senha:</label>
            <input type="password" name="senha_digitado" required>
            <a href="esqueceu_senha.php" class="opcoes">Esqueceu a senha?</a>

            <button type="submit">Entrar</button>

            <!-- <p class="links-baixo">
                <a href="cadastro.php" class="opcoes">Cadastrar novo usuário</a>
            </p> -->

        </form>
    </div>
    <?php if (isset($sucesso) && $sucesso) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Conectado!',
                text: 'Usuário conectado com sucesso. Você será redirecionado para a página inicial.',
                confirmButtonColor: '#DA020E'
            }).then(() => {
                window.location.href = 'inicio.php';
            });
        </script>
    <?php elseif (isset($senha_incorreta) && $senha_incorreta) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Senha incorreta, tente novamente',
                confirmButtonColor: '#DA020E'
            });
        </script>
    <?php elseif (isset($usuario_nao_encontrado) && $usuario_nao_encontrado) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Usuário não encontrado!',
                confirmButtonColor: '#DA020E'
            });
        </script>
    <?php endif; ?>
</body>

</html>