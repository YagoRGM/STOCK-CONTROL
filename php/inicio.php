<?php
include('conexao.php');
session_start();

$id_usuario = $_SESSION["id_usuario"];
$nome_usuario = $_SESSION["nome_usuario"];
$email_usuario = $_SESSION["email_usuario"];
$tipo_usuario = $_SESSION["tipo_usuario"];

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Home - StockControl</title>
    <link rel="stylesheet" href="../css/inicio.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <section class="sessao-hero">
            <div class="conteudo-hero">
                <h1>Seja Bem-vindo ao Stock Control</h1>
                <a href="#secao_operacoes" id="btn-saiba-mais">Saiba mais</a>
            </div>
        </section>


        <section id="secao_operacoes">
            <div id="boas-vindas">
                <p>Olá, <?php echo htmlspecialchars($nome_usuario) ?>! | <?php echo htmlspecialchars($tipo_usuario) ?></p>
            </div>
            <div id="cards-container">
                <a href="cadastro_produtos.php">
                    <div class="card-item">
                        <img src="../img/Plus.png" alt="Cadastrar Produtos">
                        <h3>Cadastrar Produtos</h3>
                    </div>
                </a>

                <a href="listar_produtos.php">
                    <div class="card-item">
                        <img src="../img/Bulleted List.png" alt="Listar Produtos">
                        <h3>Listar Produtos</h3>
                    </div>
                </a>

                <a href="registrar_entrada.php">
                    <div class="card-item">
                        <img src="../img/Forward Button.png" alt="Registrar Entrada">
                        <h3>Registrar Entrada</h3>
                    </div>
                </a>

                <a href="registrar_saida.php">
                    <div class="card-item">
                        <img src="../img/Forward Button.png" alt="Registrar Saída">
                        <h3>Registrar Saída</h3>
                    </div>
                </a>

                <a href="movimentacoes.php">
                    <div class="card-item">
                        <img src="../img/In Transit.png" alt="Ver Movimentações">
                        <h3>Ver Movimentações</h3>
                    </div>
                </a>

                <a href="gerenciar_usuarios.php">
                    <div class="card-item">
                        <img src="../img/Users.png" alt="Gerenciar Usuários">
                        <h3>Gerenciar Usuários</h3>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <?php include('footer.php') ?>

</body>

</html>