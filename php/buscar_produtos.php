<?php
include('conexao.php');

$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$modo = isset($_GET['modo']) ? $_GET['modo'] : '';

if (!empty($pesquisa)) {
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE nome_produto LIKE ?");
    $like = "%" . $pesquisa . "%";
    $stmt->bind_param("s", $like);
} else {
    $stmt = $conexao->prepare("SELECT * FROM produtos");
}

$stmt->execute();
$result = $stmt->get_result();

// Se for apenas para autocomplete (nav)
if ($modo === 'nomes') {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div>' . htmlspecialchars($row['nome_produto']) . '</div>';
        }
    } else {
        echo '<div>Nenhum produto encontrado</div>';
    }
    exit;
}

// Caso contrário, retorna cards de produto (para listar_produtos.php)
if ($result->num_rows > 0) {
    while ($produto = $result->fetch_assoc()) {
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
    echo '<p>Nenhum produto encontrado.</p>';
}
