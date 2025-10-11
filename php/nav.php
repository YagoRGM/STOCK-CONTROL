<?php
include_once('conexao.php');

$id_usuario = $_SESSION['id_usuario'];

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}


// Consulta o nome completo
$stmt = $conexao->prepare("SELECT nome_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario_nav = $result->fetch_assoc();

$nome_completo = $usuario_nav['nome_usuario'];

// Gera as iniciais (ex: "Kaique Ferreira" → "KF")
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

        <div class="pesquisa">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" placeholder="Buscar...">
        </div>

        <ul class="navegacao">
            <li><a href="../php/inicio.php">Início</a></li>
            <li><a href="listar_produtos.php">Listar produtos</a></li>
            <li><a href="#">Movimentações</a></li>
            <li><a href="#">Usuários</a></li>
            <li><i class="fa-solid fa-moon" id="modo-noturno"></i></li>
            <li><a href="perfil.php" id="perfil"><?php echo htmlspecialchars($iniciais); ?></a></li>
        </ul>
    </nav>
</body>
<script>
    const btnModo = document.getElementById("modo-noturno");
    const body = document.body;

    btnModo.addEventListener("click", () => {
        body.classList.toggle("dark-mode");

        if (body.classList.contains("dark-mode")) {
            btnModo.classList.replace("fa-moon", "fa-sun");
        } else {
            btnModo.classList.replace("fa-sun", "fa-moon");
        }
    });
</script>

</html>