<?php
date_default_timezone_set('America/Sao_Paulo');
include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

// Puxa produtos ativos
$stmt = $conexao->prepare('SELECT id_produto, nome_produto, quantidade_produto FROM produtos WHERE status_produto = "ativo"');
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produto = intval($_POST['id_produto']);
    $quantidade_remove = intval($_POST['quantidade']);
    $observacao = $_POST['observacao'];

    $stmt_qtd = $conexao->prepare('SELECT quantidade_produto FROM produtos WHERE id_produto = ?');
    $stmt_qtd->bind_param('i', $id_produto);
    $stmt_qtd->execute();
    $produto = $stmt_qtd->get_result()->fetch_assoc();

    if ($quantidade_remove > $produto['quantidade_produto']) {
        $erro = "Quantidade solicitada maior que o estoque disponível!";
    } else {
        $nova_quantidade = $produto['quantidade_produto'] - $quantidade_remove;

        $stmt_update = $conexao->prepare('UPDATE produtos SET quantidade_produto = ? WHERE id_produto = ?');
        $stmt_update->bind_param('ii', $nova_quantidade, $id_produto);
        $stmt_update->execute();

        $id_usuario = $_SESSION['id_usuario'];
        $tipo = 'saida';
        $stmt_mov = $conexao->prepare('INSERT INTO movimentacoes (id_produto, id_usuario, tipo, quantidade, observacao) VALUES (?, ?, ?, ?, ?)');
        $stmt_mov->bind_param('iisis', $id_produto, $id_usuario, $tipo, $quantidade_remove, $observacao);
        $stmt_mov->execute();

        $sucesso = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Saída - StockControl</title>
    <link rel="stylesheet" href="../css/editar_produto.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'nav.php'; ?>

<main>
    <div class="titulo">
        <h1>Registrar Saída</h1>
        <p>Selecione o produto e remova unidades do estoque.</p>
    </div>

    <form class="form-produto" method="post">
        <div class="inputs">

            <div class="inputbox">
    <label for="id_produto">Produto</label>
    <select name="id_produto" id="id_produto" required>
        <option value="">Selecione um produto</option>
        <?php
        $stmt = $conexao->prepare("SELECT id_produto, nome_produto, quantidade_produto FROM produtos WHERE status_produto = 'ativo'");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($p = $result->fetch_assoc()) {
                echo "<option value='{$p['id_produto']}'>" .
                        htmlspecialchars($p['nome_produto']) .
                        " (Estoque: {$p['quantidade_produto']})" .
                     "</option>";
            }
        } else {
            echo "<option disabled>Nenhum produto ativo encontrado</option>";
        }
        ?>
    </select>
</div>


            <div class="inputbox">
                <label for="quantidade">Quantidade a remover</label>
                <input type="number" name="quantidade" id="quantidade" min="1" required>
            </div>

            <div class="inputbox">
                <label for="observacao">Motivo / observação</label>
                <input type="text" name="observacao" id="observacao">
            </div>

            <div class="inputbox">
                <button type="submit" class="btn-adicionar">Registrar Saída</button>
            </div>
        </div>
    </form>
</main>

<?php include('footer.php') ?>
</body>

<?php if (isset($erro)): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Erro!',
    text: '<?php echo $erro; ?>',
    confirmButtonColor: '#DA020E'
});
</script>
<?php endif; ?>

<?php if (isset($sucesso)): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Saída registrada!',
    text: 'O estoque foi atualizado com sucesso.',
    confirmButtonColor: '#DA020E'
}).then(() => {
    window.location.href = 'listar_produtos.php';
});
</script>
<?php endif; ?>
</html>
