<?php
include('conexao.php');
session_start();

// Verifica sessão
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

// Dados do usuário
$id_usuario   = $_SESSION["id_usuario"];
$nome_usuario = $_SESSION["nome_usuario"];
$email_usuario = $_SESSION["email_usuario"];
$tipo_usuario = $_SESSION["tipo_usuario"];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Home - StockControl</title>
    <link rel="stylesheet" href="../css/inicio.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <!-- =============================
             SEÇÃO HERO (CARROSSEL)
        ============================== -->
        <section class="sessao-hero">
            <div class="carrossel">
                <img src="../img/torcida_independente.png" alt="Torcida Independente" class="slide ativo">
                <img src="../img/torcida_independente2.png" alt="Torcida Independente 2" class="slide">
                <img src="../img/torcida_independete3.png" alt="Torcida Mancha Verde" class="slide">
                <img src="../img/torcida_independete4.png" alt="Torcida Gaviões da Fiel" class="slide">
            </div>

            <div class="conteudo-hero">
                <h1>Bem-vindo ao <span>Stock Control</span></h1>
                <p>Gerencie seus produtos com praticidade, segurança e rapidez.</p>
                <a href="#secao-operacoes" id="btn-saiba-mais">Saiba mais</a>
            </div>

            <button class="seta seta-esquerda" aria-label="Imagem anterior">&#10094;</button>
            <button class="seta seta-direita" aria-label="Próxima imagem">&#10095;</button>
        </section>

        <!-- =============================
             SEÇÃO OPERAÇÕES
        ============================== -->
        <section id="secao-operacoes">
            <div class="boas-vindas">
                <p>Olá, <?php echo htmlspecialchars($nome_usuario); ?> | <?php echo htmlspecialchars($tipo_usuario); ?></p>
            </div>

            <div class="cards-container">
                <a href="cadastro_produtos.php" class="card-item">
                    <img src="../img/Plus.png" alt="Cadastrar Produtos">
                    <h3>Cadastrar Produtos</h3>
                </a>

                <a href="listar_produtos.php" class="card-item">
                    <img src="../img/Bulleted List.png" alt="Listar Produtos">
                    <h3>Listar Produtos</h3>
                </a>

                <a href="registrar_entrada.php" class="card-item">
                    <img src="../img/Entrada.png" alt="Registrar Entrada">
                    <h3>Registrar Entrada</h3>
                </a>

                <a href="registrar_saida.php" class="card-item">
                    <img src="../img/Saida.png" alt="Registrar Saída">
                    <h3>Registrar Saída</h3>
                </a>

                <a href="movimentacoes.php" class="card-item">
                    <img src="../img/In Transit.png" alt="Ver Movimentações">
                    <h3>Ver Movimentações</h3>
                </a>

                <?php if ($tipo_usuario === 'Administrador'): ?>
                    <a href="gerenciar_usuarios.php" class="card-item">
                        <img src="../img/Users.png" alt="Gerenciar Usuários">
                        <h3>Gerenciar Usuários</h3>
                    </a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include('footer.php'); ?>

    <script>
        const slides = document.querySelectorAll('.slide');
        const setaEsquerda = document.querySelector('.seta-esquerda');
        const setaDireita = document.querySelector('.seta-direita');
        let index = 0;

        const mostrarSlide = (novoIndex) => {
            slides[index].classList.remove('ativo');
            index = (novoIndex + slides.length) % slides.length;
            slides[index].classList.add('ativo');
        };

        setaEsquerda.addEventListener('click', () => mostrarSlide(index - 1));
        setaDireita.addEventListener('click', () => mostrarSlide(index + 1));
        setInterval(() => mostrarSlide(index + 1), 5000);
    </script>
</body>

</html>