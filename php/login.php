<?php
include('conexao.php');
session_start();

$sucesso = false;
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email_digitado"]);
    $senha = trim($_POST["senha_digitado"]);

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["id"] = $usuario["id"];
            $_SESSION["nome"] = $usuario["nome"];
            $_SESSION["tipo"] = $usuario["tipo"];
            $_SESSION["email"] = $usuario["email"];
            $sucesso = true; 
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
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

            <h2>Login</h2>
            <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>

            <label>Email:</label>
            <input type="email" name="email_digitado" required>

            <label>Senha:</label>
            <input type="password" name="senha_digitado" required>
            <a href="esqueceu_senha.php" class="opcoes">Esqueceu a senha?</a>

            <button type="submit">Entrar</button>

            <p class="links-baixo">
                <a href="cadastro.php" class="opcoes">Cadastrar novo usuário</a>
            </p>

        </form>
    </div>
<?php if ($sucesso) : ?>
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