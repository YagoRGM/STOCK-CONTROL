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

        <div class="pesquisa" style="position: relative;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" id="pesquisa-nav" placeholder="Buscar produtos...">
            <div id="sugestoes" class="sugestoes"></div>
        </div>

        <style>
            .sugestoes {
                position: absolute;
                top: 40px;
                left: 0;
                width: 50%;
                background: white;
                border-radius: 5px;
                box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
                z-index: 10;
            }

            .sugestoes div {
                padding: 8px;
                cursor: pointer;
            }

            .sugestoes div:hover {
                background-color: #f0f0f0;
            }
        </style>

        <ul class="navegacao">
            <li><a href="../php/inicio.php">Início</a></li>
            <li><a href="listar_produtos.php">Listar produtos</a></li>
            <li><a href="movimentacoes.php">Movimentações</a></li>
            <li><a href="gerenciar_usuarios.php">Usuários</a></li>
            <li><i class="fa-solid fa-moon" id="modo-noturno"></i></li>
            <li><a href="perfil.php" id="perfil"><?php echo htmlspecialchars($iniciais); ?></a></li>
        </ul>
    </nav>

    <script>
        const btnModo = document.getElementById("modo-noturno");
        const body = document.body;

        btnModo.addEventListener("click", () => {
            body.classList.toggle("dark-mode");
            btnModo.classList.toggle("fa-sun");
            btnModo.classList.toggle("fa-moon");
        });

        document.getElementById('pesquisa-nav').addEventListener('input', async function() {
            const termo = this.value.trim();
            const sugestoes = document.getElementById('sugestoes');

            if (termo.length === 0) {
                sugestoes.innerHTML = '';
                return;
            }

            const response = await fetch(`buscar_produtos.php?modo=nomes&pesquisa=${encodeURIComponent(termo)}`);
            const data = await response.text();

            sugestoes.innerHTML = data;

            // Ao clicar na sugestão → vai pra listagem filtrada
            sugestoes.querySelectorAll('div').forEach(div => {
                div.addEventListener('click', () => {
                    window.location.href = `listar_produtos.php?pesquisa=${encodeURIComponent(div.innerText)}`;
                });
            });
        });
    </script>
</body>

</html>