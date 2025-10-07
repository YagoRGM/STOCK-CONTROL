<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["tipo"] === "admin") {
    $_SESSION["tipo"] = "administrador";
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
        <section class="hero">
        <div class="conteudo">
            <h1>Seja Bem-vindo ao Stock Control</h1>
            <button id="btn-saiba-mais">Saiba mais</button>
        </div>
        </section>

        <div id="inicio">
         <p>Olá, <?php echo $_SESSION["tipo"]; ?> <?php echo $_SESSION["nome"]; ?>!</p>
        </div>
        <div id="container">
            <div class="card">
                <img src="../img/Plus.png" alt="">
                <h3>Cadastrar Produtos</h3>
            </div>
            <div class="card">
                <img src="../img/Bulleted List.png" alt="">
                <h3>Listar Produtos</h3>
            </div>
            <div class="card">
                <img src="../img/Forward Button.png" alt="">
                <h3>Registrar Entrada</h3>
            </div>
            <div class="card">
                <img src="../img/Forward Button.png" alt="">
                <h3>Registrar Saída</h3>
            </div>
            <div class="card">
                <img src="../img/In Transit.png" alt="">
                <h3>Ver Movimentações</h3>
            </div>
            <div class="card">
                <img src="../img/Users.png" alt="">
                <h3>Gerenciar Usuários</h3>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <div class="footer-section">
                <img src="../img/logo1.png" alt="Logo" class="footer-logo">
            </div>
            <div class="footer-section" id="footer1">
                <h4>Sistema de Gerenciamento de Estoque</h4>
                <p>Gerencie o estoque da loja StockControl de forma eficiente e prática. Usufrua de uma interface intuitiva e recursos poderosos para otimizar seu processo de gerenciamento.</p>
            </div>
            <div class="footer-section">
                <h3>Contato</h3>
                <p><strong>Endereço:</strong> Rua Av. Monsenhor Theodomiro Lobo, 100 - Parque Res. Maria Elmira,
                    Caçapava - SP, 12285-050<br>
                    <strong>Email:</strong> <a href="mailto:stockcontrolcontact@gmail.com"
                        target="_blank">stockcontrolcontact@gmail.com</a>
                    <br>
                    <strong>Telefone:</strong> (12) 1234-5678
                </p>
            </div>
        </div>
        <div class="bottom">
            &copy; Sistema StockControl. Todos os direitos reservados.
        </div>
    </footer>
    <script>
    
</script>

</body>
</html>
