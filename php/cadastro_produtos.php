<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$nome_usuario = $_SESSION["nome_usuario"];
$email_usuario = $_SESSION["email_usuario"];
$tipo_usuario = $_SESSION["tipo_usuario"];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto - StockControl</title>
    <link rel="stylesheet" href="../css/cadastro_produto.css">
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <div class="titulo">
            <h1>Adicionar Produto</h1>
            <p>Preencha o formulário abaixo para adicionar um novo produto ao estoque.</p>
        </div>

        <form action="adicionar_produto_action.php" method="post" enctype="multipart/form-data" class="form-produto">
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

            <div class="inputbox">
                <button type="submit" class="btn-adicionar">Adicionar Produto</button>
            </div>

        </form>
    </main>

    <?php include('footer.php') ?>
</body>

</html>