<?php
include('conexao.php');
session_start();
$id_usuario = $_SESSION['id_usuario'];

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$stmt = $conexao->prepare("SELECT nome_usuario, email_usuario, senha_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$mensagem = '';
$tipo = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];

    $update_query = "UPDATE usuarios SET nome_usuario = ?, email_usuario = ? WHERE id_usuario = ?";
    $update_stmt = $conexao->prepare($update_query);
    $update_stmt->bind_param("ssi", $nome, $email, $id_usuario);
    $update_stmt->execute();

    if (!empty($senha_atual) && !empty($nova_senha)) {
        if (password_verify($senha_atual, $usuario["senha_usuario"])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $update_senha = $conexao->prepare("UPDATE usuarios SET senha_usuario = ? WHERE id_usuario = ?");
            $update_senha->bind_param("si", $nova_senha_hash, $id_usuario);
            $update_senha->execute();
            $mensagem = 'Informações e senha atualizadas com sucesso!';
            $tipo = 'success';
        } else {
            $mensagem = 'Informações atualizadas, mas a senha atual está incorreta.';
            $tipo = 'warning';
        }
    } else {
        $mensagem = 'Informações atualizadas com sucesso!';
        $tipo = 'success';
    }

    $_SESSION["nome_usuario"] = $nome;
    $_SESSION["email_usuario"] = $email;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - StockControl</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <div class="titulo">
            <h1><i class="fas fa-user-circle"></i> Meu Perfil</h1>
            <p>Visualize e edite suas informações pessoais e preferências do sistema.</p>
            <div class="linha"></div>
        </div>

        <section class="container-form">
            <form method="POST">
                <h3>Informações Pessoais</h3>

                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome_usuario']); ?>" required>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email_usuario']); ?>" readonly>

                <h3>Alterar Senha</h3>

                <label for="senha_atual">Senha Atual</label>
                <input type="password" id="senha_atual" name="senha_atual" placeholder="Digite sua senha atual">

                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite sua nova senha">

                <button type="submit">Salvar Alterações</button>
                <button type="button" id="sair">Sair</button>
            </form>
        </section>
    </main>

    <?php include('footer.php'); ?>

    <script>
        document.getElementById('sair').addEventListener('click', () => {
            Swal.fire({
                title: 'Deseja sair?',
                text: 'Você será desconectado da sua conta.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, sair',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#DA020E',
                cancelButtonColor: '#555'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        });

        <?php if ($mensagem): ?>
            Swal.fire({
                title: '',
                text: '<?php echo $mensagem; ?>',
                icon: '<?php echo $tipo; ?>'
            }).then(() => {
                window.location.href = 'perfil.php';
            });
        <?php endif; ?>
    </script>
</body>

</html>