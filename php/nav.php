<?php
include_once('conexao.php');

$id_usuario = $_SESSION['id_usuario'];

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conexao->prepare("SELECT nome_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario_nav = $result->fetch_assoc();

$nome_completo = $usuario_nav['nome_usuario'];
$partes = explode(' ', trim($nome_completo));
$iniciais = '';
foreach ($partes as $parte) {
    if (!empty($parte)) {
        $iniciais .= strtoupper($parte[0]);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/8417e3dabe.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/nav.css">
    <title>Navegação</title>
</head>

<body>
    <nav>
        <div class="logo">
            <a href="inicio.php"><img src="../img/logo1.png" alt="Logo"></a>
        </div>

        <div class="hamburger" id="hamburger">
            <i class="fa-solid fa-bars"></i>
        </div>

        <ul class="navegacao" id="menu-nav">
            <li><a href="../php/inicio.php">Início</a></li>
            <li><a href="listar_produtos.php">Listar produtos</a></li>
            <li><a href="movimentacoes.php">Movimentações</a></li>

            <?php if ($_SESSION['tipo_usuario'] === 'Administrador'): ?>
                <li><a href="gerenciar_usuarios.php">Usuários</a></li>
            <?php endif; ?>

            <li><i class="fa-solid fa-moon" id="modo-noturno"></i></li>
            <li><a href="perfil.php" id="perfil"><?php echo htmlspecialchars($iniciais); ?></a></li>
        </ul>
    </nav>

    <script>
        const hamburger = document.getElementById("hamburger");
        const menuNav = document.getElementById("menu-nav");

        hamburger.addEventListener("click", () => {
            menuNav.classList.toggle("active");
        });

        // Modo noturno
        const btnModo = document.getElementById("modo-noturno");
        btnModo.addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");
            btnModo.classList.toggle("fa-sun");
            btnModo.classList.toggle("fa-moon");
        });
    </script>

</body>

</html>