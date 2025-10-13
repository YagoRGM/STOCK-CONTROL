<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';

if (!empty($pesquisa)) {
    $stmt_produtos = $conexao->prepare('SELECT * FROM produtos WHERE nome_produto LIKE ?');
    $like = "%" . $pesquisa . "%";
    $stmt_produtos->bind_param('s', $like);
} else {
    $stmt_produtos = $conexao->prepare('SELECT * FROM produtos');
}

$stmt_produtos->execute();
$result_produtos = $stmt_produtos->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Listar Produtos - StockControl</title>
    <link rel="stylesheet" href="../css/listar_produtos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
            <input type="search" id="campo-pesquisa" placeholder="Pesquise aqui...">
        </div>

        <div class="produtos_container" id="produtos-container">
            <?php
            if ($result_produtos->num_rows > 0) {
                while ($produto = $result_produtos->fetch_assoc()) {
                    $foto_produto = base64_encode($produto['imagem_produto']);
                    echo '
                    <div class="card_produto">
                        <div class="imagem_produto">
                            <img src="data:image;base64,' . htmlspecialchars($foto_produto) . '" alt="Imagem do produto">
                        </div>

                        <div class="titulo_produto">
                            <h1><strong>Título:</strong> ' . htmlspecialchars($produto['nome_produto']) . '</h1>
                        </div>

                        <div class="info_produto">
                            <p><strong>Descrição:</strong> ' . htmlspecialchars($produto['descricao_produto']) . '</p>
                            <p><strong>Preço:</strong> R$' . htmlspecialchars($produto['preco_produto']) . '</p>
                            <p><strong>Quantidade:</strong> ' . htmlspecialchars($produto['quantidade_produto']) . ' unidades</p>
                        </div>

                        <div class="operacoes">
                            <a href="editar_produto.php?id=' . htmlspecialchars($produto['id_produto']) . '" class="botao" id="editar">Editar</a>
                            <a href="#" class="botao btn-excluir" data-id="' . htmlspecialchars($produto['id_produto']) . '" id="excluir">Excluir</a>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Nenhum produto encontrado.</p>";
            }
            ?>
        </div>
    </main>

    <?php include('footer.php') ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const campoPesquisa = document.getElementById('campo-pesquisa');
            const container = document.getElementById('produtos-container');

            campoPesquisa.addEventListener('input', async () => {
                const termo = campoPesquisa.value.trim();
                const response = await fetch(`buscar_produtos.php?pesquisa=${encodeURIComponent(termo)}`);
                const html = await response.text();
                container.innerHTML = html;
                adicionarEventosExcluir();
            });

            function adicionarEventosExcluir() {
                const botoesExcluir = document.querySelectorAll('.btn-excluir');
                botoesExcluir.forEach(botao => {
                    botao.addEventListener('click', (e) => {
                        e.preventDefault();
                        const idProduto = botao.getAttribute('data-id');

                        Swal.fire({
                            title: 'Tem certeza?',
                            text: "Você não poderá reverter esta ação!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DA020E',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sim, excluir!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `excluir_produto.php?id=${idProduto}`;
                            }
                        });
                    });
                });
            }

            adicionarEventosExcluir();
        });
    </script>
</body>

</html>