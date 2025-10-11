<?php
date_default_timezone_set('America/Sao_Paulo');

include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header('location: listar_produtos.php');
}

$id_produto = $_GET['id'];

$stmt_editar_produto = $conexao->prepare('SELECT * FROM produtos WHERE id_produto = ?');
$stmt_editar_produto->bind_param('i', $id_produto);
$stmt_editar_produto->execute();
$result_editar_produto = $stmt_editar_produto->get_result();
$produto_editar = $result_editar_produto->fetch_assoc();

$foto_produto = base64_encode($produto_editar['imagem_produto']);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $status = $_POST['status'];

    if (!empty($_FILES['imagem']['tmp_name'])) {
        $stmt = $conexao->prepare('UPDATE produtos SET nome_produto = ?, descricao_produto = ?, quantidade_produto = ?, preco_produto = ?, status_produto = ?, imagem_produto = ?  WHERE id_produto = ?');
        $stmt->bind_param('ssiisbi', $nome, $descricao, $quantidade, $preco, $status, $null, $id_produto);
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
        $stmt->send_long_data(5, $imagem);
    } else {
        $stmt = $conexao->prepare('UPDATE produtos SET nome_produto = ?, descricao_produto = ?, quantidade_produto = ?, preco_produto = ?, status_produto = ? WHERE id_produto = ?');
        $stmt->bind_param('ssiisi', $nome, $descricao, $quantidade, $preco, $status, $id_produto);
    }
    if ($stmt->execute()) {
        $editar_produto = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto - StockControl</title>
    <link rel="stylesheet" href="../css/editar_produto.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <div class="titulo">
            <h1>Editar Produto</h1>
            <p>Atualize as informações do produto no estoque.</p>
        </div>

        <form class="form-produto" method="post" enctype="multipart/form-data">
            <div class="inputbox_foto">
                <img id="preview" src="data:image;base64,<?php echo htmlspecialchars($foto_produto) ?>" alt="Imagem do produto">
                <input type="file" name="imagem" id="imagem" accept="image/*">
            </div>


            <div class="inputs">
                <div class="inputbox">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($produto_editar['nome_produto']) ?>" required>
                </div>

                <div class="inputbox">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" id="descricao" value="<?php echo htmlspecialchars($produto_editar['descricao_produto']) ?>" required>
                </div>

                <div class="inputbox">
                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade" id="quantidade" value="<?php echo htmlspecialchars($produto_editar['quantidade_produto']) ?>" required>
                </div>

                <div class="inputbox">
                    <label for="preco">Preço</label>
                    <input type="number" name="preco" id="preco" value="<?php echo htmlspecialchars($produto_editar['preco_produto']) ?>" step="0.01" required>
                </div>

                <div class="inputbox">
                    <label for="status">Status</label>
                    <select name="status" id="status" value="<?php echo htmlspecialchars($produto_editar['status_produto']) ?>" required>
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>

                <div class="inputbox">
                    <button type="submit" class="btn-adicionar">Adicionar Produto</button>
                </div>
            </div>

        </form>
    </main>

    <?php include('footer.php') ?>
</body>
<script>
    const inputImagem = document.getElementById('imagem');
    const preview = document.getElementById('preview');

    inputImagem.addEventListener('change', function(event) {
        const arquivo = event.target.files[0];
        if (arquivo) {
            const leitor = new FileReader();

            leitor.onload = function(e) {
                preview.src = e.target.result; // troca a imagem
            };

            leitor.readAsDataURL(arquivo); // lê o arquivo como Base64
        }
    });
</script>

<?php if (isset($editar_produto) && $editar_produto): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Edições salvas com sucesso!',
            text: 'O produto foi editado com sucesso. Você será redirecionado para a pagina de listar produtos',
            confirmButtonColor: '#DA020E',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'listar_produtos.php';
        });
    </script>
<?php endif; ?>

</html>