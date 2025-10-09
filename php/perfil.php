<?php
include('conexao.php');
session_start();
$id_usuario = $_SESSION['id_usuario'];

// Verifica se o usuário está logado corretamente
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}


// Busca os dados atuais do usuário
$stmt = $conexao->prepare("SELECT nome_usuario, email_usuario, senha_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Atualização do perfil
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];

    // Atualiza nome e email sempre
    $update_query = "UPDATE usuarios SET nome_usuario = ?, email_usuario = ? WHERE id_usuario = ?";
    $update_stmt = $conexao->prepare($update_query);
    $update_stmt->bind_param("ssi", $nome, $email, $id_usuario);
    $update_stmt->execute();

    // Se quiser alterar a senha
    if (!empty($senha_atual) && !empty($nova_senha)) {
        if (password_verify($senha_atual, $usuario["senha_usuario"])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $update_senha = $conexao->prepare("UPDATE usuarios SET senha_usuario = ? WHERE id_usuario = ?");
            $update_senha->bind_param("si", $nova_senha_hash, $id_usuario);
            $update_senha->execute();
            echo "<script>alert('Senha atualizada com sucesso!');</script>";
        } else {
            echo "<script>alert('Senha atual incorreta.');</script>";
        }
    }

    // 🔥 Atualiza as informações na sessão
    $_SESSION["nome_usuario"] = $nome;
    $_SESSION["email_usuario"] = $email;

    echo "<script>alert('Informações atualizadas com sucesso!'); window.location='perfil.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - StockControl</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <section id="banner">
            <h1>Meu Perfil</h1>
            <p>Gerencie suas informações de usuário</p>
        </section>

        <section class="container-form">
            <form method="POST">
                <h3>Informações Pessoais</h3>

                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome_usuario']); ?>" required>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email_usuario']); ?>" required>

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

    <?php include('footer.php') ?>
</body>
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
                // Redireciona apenas se o usuário confirmar
                window.location.href = 'logout.php';
            }
        });
    });
</script>


</html>