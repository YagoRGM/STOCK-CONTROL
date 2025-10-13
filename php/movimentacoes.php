<?php
date_default_timezone_set('America/Sao_Paulo');
include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

// Puxa todas as movimentações, juntando com usuários e produtos
$stmt_mov = $conexao->prepare(
    'SELECT m.id_movimentacao, m.tipo, m.quantidade, m.data_movimentacao, 
            u.nome_usuario, p.nome_produto, m.observacao
     FROM movimentacoes m
     INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
     INNER JOIN produtos p ON m.id_produto = p.id_produto
     ORDER BY m.data_movimentacao DESC'
);

$stmt_mov->execute();
$result_mov = $stmt_mov->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Movimentações - StockControl</title>
    <link rel="stylesheet" href="../css/gerenciar_usuarios.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <div class="titulo">
            <h1><i class="fas fa-exchange-alt"></i> Movimentações</h1>
            <p>Confira todas as entradas e saídas de produtos do estoque.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Data</th>
                        <th>Responsável</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_mov->num_rows > 0): ?>
                        <?php while ($mov = $result_mov->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($mov['id_movimentacao']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($mov['tipo'])) ?></td>
                                <td><?= htmlspecialchars($mov['nome_produto']) ?></td>
                                <td><?= htmlspecialchars($mov['quantidade']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($mov['data_movimentacao'])) ?></td>
                                <td><?= htmlspecialchars($mov['nome_usuario']) ?></td>
                                <td><?= htmlspecialchars($mov['observacao']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">Nenhuma movimentação encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <?php include('footer.php') ?>
</body>

</html>