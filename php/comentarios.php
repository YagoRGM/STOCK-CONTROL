<?php
session_start();
include 'nav.php';
include 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_comentario'])) {
    $id_produto = intval($_POST['id_produto']);
    $conteudo = trim($_POST['conteudo']);

    if (!empty($conteudo) && $id_produto > 0) {
        $stmt = $conexao->prepare("INSERT INTO comentarios (id_produto, id_usuario, conteudo_comentario) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_produto, $id_usuario, $conteudo);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: comentarios.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_comentario'])) {
    $id_comentario = intval($_POST['excluir_comentario']);

    $stmt = $conexao->prepare("SELECT realizado_comentario FROM comentarios WHERE id_comentario = ?");
    $stmt->bind_param("i", $id_comentario);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    if ($status) {
        $stmt = $conexao->prepare("DELETE FROM comentarios WHERE id_comentario = ?");
        $stmt->bind_param("i", $id_comentario);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: comentarios.php");
    exit;
}

if (isset($_GET['realizado'])) {
    $id_comentario = intval($_GET['realizado']);
    if ($id_comentario > 0) {
        $stmt = $conexao->prepare("SELECT realizado_comentario FROM comentarios WHERE id_comentario = ?");
        $stmt->bind_param("i", $id_comentario);
        $stmt->execute();
        $stmt->bind_result($status_atual);
        $stmt->fetch();
        $stmt->close();

        if (!$status_atual) {
            $novo_status = 1;
            $stmt = $conexao->prepare("UPDATE comentarios SET realizado_comentario = ? WHERE id_comentario = ?");
            $stmt->bind_param("ii", $novo_status, $id_comentario);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: comentarios.php");
    exit;
}

$sql = "SELECT c.id_comentario, c.id_produto, c.conteudo_comentario, c.data_comentario, c.realizado_comentario, u.nome_usuario, p.nome_produto
        FROM comentarios c
        JOIN usuarios u ON c.id_usuario = u.id_usuario
        JOIN produtos p ON c.id_produto = p.id_produto
        ORDER BY c.data_comentario DESC";


$result = $conexao->query($sql);
if (!$result) {
    die("Erro na consulta: " . $conexao->error);
}

$produtos = $conexao->query("SELECT id_produto, nome_produto FROM produtos ORDER BY nome_produto ASC");
if (!$produtos) {
    die("Erro ao carregar produtos: " . $conexao->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comentários - Stock Control</title>
    <link rel="stylesheet" href="../css/comentarios.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<main>
    <div id="titulo">
        <h1><i class="fas fa-comment"></i>Adicionar Comentários</h1>
        <p>Use esse campo para adicionar comentários sobre algum produto da loja.</p>
    </div>

    <!-- FORMULÁRIO PARA NOVO COMENTÁRIO -->
    <form method="POST">
        <label for="id_produto">Produto:</label>
        <select name="id_produto" required>
            <option value="">Selecione um produto</option>
            <?php while ($p = $produtos->fetch_assoc()) : ?>
                <option value="<?= $p['id_produto'] ?>"><?= htmlspecialchars($p['nome_produto']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="conteudo">Comentário:</label>
        <textarea name="conteudo" placeholder="Digite seu comentário..." required></textarea>

        <button type="submit" name="novo_comentario">Adicionar Comentário</button>
    </form>

    <div id="titulo">
        <h1><i class="fas fa-comment"></i>Comentários Existentes</h1>
        <p>Realize as recomendações requisitadas e retire as que já foram feitas</p>
    </div>

    <!-- LISTAGEM DE COMENTÁRIOS -->
    <div id="comentarios-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="comentario">
                <p><strong>Usuário:</strong> <?= htmlspecialchars($row['nome_usuario']) ?></p>
                <p><strong>Produto:</strong> <?= htmlspecialchars($row['nome_produto']) ?></p>
                <p><strong>Comentário:</strong> <?= nl2br(htmlspecialchars($row['conteudo_comentario'])) ?></p>
                <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($row['data_comentario'])) ?></p>

                <?php if (!$row['realizado_comentario']): ?>
                    <form method="GET" style="display:inline;" class="form-realizado">
                        <input type="hidden" name="realizado" value="<?= $row['id_comentario'] ?>">
                        <button type="button" class="btn-realizado" data-comentario="<?= $row['id_comentario'] ?>">Marcar como realizado</button>
                    </form>
                <?php else: ?>
                    <p class="realizado">Recomendação já realizada!</p>
                    <form method="POST" style="display:inline;" class="form-excluir">
                        <input type="hidden" name="excluir_comentario" value="<?= $row['id_comentario'] ?>">
                        <button type="button" class="btn-excluir" data-comentario="<?= $row['id_comentario'] ?>">Excluir Comentário</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="sem-comentarios">Nenhum comentário encontrado.</p>
    <?php endif; ?>
    </div>
</main>

<script>
document.querySelectorAll('.btn-realizado').forEach(btn => {
    btn.addEventListener('click', function() {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja marcar este comentário como realizado?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, marcar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    });
});

document.querySelectorAll('.btn-excluir').forEach(btn => {
    btn.addEventListener('click', function() {
        Swal.fire({
            title: 'Excluir comentário?',
            text: "Essa ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    });
});
</script>

</body>
</html>
