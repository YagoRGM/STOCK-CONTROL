<?php
date_default_timezone_set('America/Sao_Paulo');

include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $status = $_POST['status'];
    $data = $_POST['data'];

    $stmt = $conexao->prepare('INSERT INTO produtos (nome_produto, descricao_produto, quantidade_produto, preco_produto, status_produto, imagem_produto, data_criacao_produto)
    VALUES (?,?,?,?,?,?,?)');
    $stmt->bind_param('ssiisbs', $nome, $descricao, $quantidade, $preco, $status, $null, $data);
    $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
    $stmt->send_long_data(5, $imagem);
    $stmt->execute();
    $adicionar_produto = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto - StockControl</title>
    <link rel="stylesheet" href="../css/cadastro_produto.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <div class="titulo">
            <h1><i class="fas fa-cart-plus"></i> Adicionar Produto</h1>
            <p>Preencha o formulário abaixo para adicionar um novo produto ao estoque.</p>
        </div>


        <form method="post" enctype="multipart/form-data" class="form-produto">
            <div class="form-header">
                <h2>Informações iniciais</h2>
            </div>

            <div class="inputbox">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" required>
            </div>

            <div class="inputbox">
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao" required>
            </div>

            <div class="inputbox">
                <label for="quantidade">Quantidade</label>
                <input type="number" name="quantidade" id="quantidade" required>
            </div>

            <div class="inputbox">
                <label for="preco">Preço</label>
                <input type="number" name="preco" id="preco" step="0.01" required>
            </div>

            <div class="inputbox">
                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                </select>
            </div>

            <div class="inputbox">
                <label for="imagem">Imagem</label>
                <input type="file" name="imagem" id="imagem" accept="image/*" required>
            </div>

            <input type="hidden" name="data" value="<?php echo date('Y-m-d H:i:s'); ?>">

            <div class="inputbox">
                <button type="submit" class="btn-adicionar">Adicionar Produto</button>
            </div>

        </form>
    </main>

    <?php include('footer.php') ?>
</body>
<?php if (isset($adicionar_produto) && $adicionar_produto): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Produto Adicionado!',
            text: 'O produto foi adicionado com sucesso. Você será redirecionado para a página de listar produtos.',
            confirmButtonColor: '#DA020E',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'listar_produtos.php';
        });
    </script>
<?php endif; ?>

</html>