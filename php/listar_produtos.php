<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$stmt_produtos = $conexao->prepare('SELECT * FROM produtos');
$stmt_produtos->execute();
$result_produtos = $stmt_produtos->get_result();


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Home - StockControl</title>
    <link rel="stylesheet" href="../css/listar_produtos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <div class="titulo">
            <h1><i class="fas fa-boxes"></i> Listar Produtos</h1>
            <p>Visualize, edite e gerencie todos os produtos disponíveis no sistema.</p>
        </div>


        <div class="pesquisa_produtos">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" placeholder="Pesquise aqui...">
        </div>

        <div class="produtos_container">
            <?php if ($result_produtos->num_rows > 0): ?>
                <?php while ($produto = $result_produtos->fetch_assoc()): ?>
                    <?php $foto_produto = base64_encode($produto['imagem_produto']); ?>
                    <div class="card_produto">

                        <div class="imagem_produto">
                            <img src="data:image;base64,<?php echo htmlspecialchars($foto_produto); ?>" alt="Imagem do produto">
                        </div>

                        <div class="titulo_produto">
                            <h1><strong>Título:</strong> <?php echo htmlspecialchars($produto['nome_produto']); ?></h1>
                        </div>

                        <div class="info_produto">
                            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($produto['descricao_produto']); ?></p>
                            <p><strong>Preço:</strong> R$<?php echo htmlspecialchars($produto['preco_produto']); ?></p>
                            <p><strong>Quantidade:</strong> <?php echo htmlspecialchars($produto['quantidade_produto']); ?> unidades</p>
                        </div>

                        <div class="operacoes">
                            <a href="editar_produto.php?id=<?php echo htmlspecialchars($produto['id_produto']); ?>" class="botao" id="editar">Editar</a>
                            <a href="excluir_produto.php?id=<?php echo htmlspecialchars($produto['id_produto']); ?>" class="botao" id="excluir">Excluir</a>
                        </div>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>

    </main>

    <?php include('footer.php') ?>

</body>

</html>