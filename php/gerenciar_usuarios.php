<?php
date_default_timezone_set('America/Sao_Paulo');

include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$stmt_usuario = $conexao->prepare('SELECT * FROM usuarios');
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto - StockControl</title>
    <link rel="stylesheet" href="../css/gerenciar_usuarios.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <main>
        <div class="titulo">
            <h1><i class="fas fa-users"></i> Gerenciar Usuários</h1>
            <p>Visualize, edite e gerencie todos os usuários cadastrados no sistema.</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <?php if ($result_usuario->num_rows > 0): ?>
                <?php while ($usuario = $result_usuario->fetch_assoc()): ?>
                    <tbody>
                        <tr>
                            <th><?php echo htmlspecialchars($usuario['id_usuario']) ?></th>
                            <th><?php echo htmlspecialchars($usuario['nome_usuario']) ?></th>
                            <th><?php echo htmlspecialchars($usuario['email_usuario']) ?></th>
                            <th><?php echo htmlspecialchars($usuario['tipo_usuario']) ?></th>
                            <th>
                                <div class="acoes">
                                    <a href="editar_usuario?id=<?php echo htmlspecialchars($usuario['id_usuario']) ?>" class="botao" id="editar">Editar</a>
                                    <a href="excluir_usuario?id=<?php echo htmlspecialchars($usuario['id_usuario']) ?>" class="botao" id="excluir">Excluir</a>
                                </div>
                            </th>
                        </tr>
                    </tbody>
                <?php endwhile; ?>
            <?php endif; ?>
        </table>
        <a href="adicionar_usuario.php" id="adicionar_usuario">Adicionar Usuário</a>
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