<?php
date_default_timezone_set('America/Sao_Paulo');

include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

// Bloqueia acesso de funcionários
if ($_SESSION['tipo_usuario'] !== 'Administrador') {
    header("Location: inicio.php");
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
    <title>Gerenciar Usuários - StockControl</title>
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

        <!-- Mensagens via sessão -->
        <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: '<?= $_SESSION['mensagem_sucesso'] ?>',
                    confirmButtonColor: '#DA020E'
                });
            </script>
            <?php unset($_SESSION['mensagem_sucesso']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensagem_erro'])): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: '<?= $_SESSION['mensagem_erro'] ?>',
                    confirmButtonColor: '#DA020E'
                });
            </script>
            <?php unset($_SESSION['mensagem_erro']); ?>
        <?php endif; ?>
        <div class="table-container">
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
                                        <button class="botao" id="editar" onclick="abrirModal(<?= $usuario['id_usuario'] ?>, '<?= addslashes($usuario['nome_usuario']) ?>', '<?= addslashes($usuario['email_usuario']) ?>', '<?= $usuario['tipo_usuario'] ?>')">Editar</button>
                                        <a href="javascript:void(0)" class="botao" id="excluir" onclick="confirmarExclusao(<?php echo $usuario['id_usuario'] ?>)">Excluir</a>
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    <?php endwhile; ?>
                <?php endif; ?>
            </table>
        </div>

        <!-- Botão para abrir modal de adicionar usuário -->
        <a href="#" id="adicionar_usuario">Adicionar Usuário</a>
    </main>

    <?php include('footer.php') ?>

    <script>
        function confirmarExclusao(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DA020E',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'excluir_usuario.php?id=' + id;
                }
            })
        }
    </script>

    <!-- Modal Editar Usuário -->
    <div id="modalEditar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <form method="POST" id="formEditar" style="background:#fff; padding:25px; border-radius:12px; width:350px; max-width:90%; display:flex; flex-direction:column; gap:12px; box-shadow:0 4px 12px rgba(0,0,0,0.2); margin:auto;" action="editar_usuario.php">
            <h3 style="margin:0 0 10px 0; text-align:center;">Editar Usuário</h3>

            <input type="hidden" name="editar_usuario_id" id="editar_usuario_id">

            <label>Nome:</label>
            <input type="text" name="nome_digitado" id="nome_digitado" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:100%;">

            <label>Email:</label>
            <input readonly type="email" name="email_digitado" id="email_digitado" style="padding:8px; border-radius:6px; border:1px solid #ccc; width:100%;">

            <label>Tipo:</label>
            <select name="tipo_digitado" id="tipo_digitado" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:100%;">
                <option value="funcionario">Funcionário</option>
                <option value="Administrador">Administrador</option>
            </select>

            <div style="display:flex; justify-content:space-between; gap:10px; margin-top:10px;">
                <button type="submit" style="flex:1; padding:10px; border:none; border-radius:6px; background:#DA020E; color:#fff; cursor:pointer;">Salvar</button>
                <button type="button" onclick="fecharModal()" style="flex:1; padding:10px; border:none; border-radius:6px; background:#ccc; color:#000; cursor:pointer;">Cancelar</button>
            </div>
        </form>
    </div>

    <!-- Modal Adicionar Usuário -->
    <div id="modalAdicionar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <form method="POST" id="formAdicionar" style="background:#fff; padding:25px; border-radius:12px; width:350px; max-width:90%; display:flex; flex-direction:column; gap:12px; box-shadow:0 4px 12px rgba(0,0,0,0.2); margin:auto;" action="adicionar_usuario.php">
            <h3 style="margin:0 0 10px 0; text-align:center;">Adicionar Usuário</h3>

            <label>Nome:</label>
            <input type="text" name="nome" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:100%;">

            <label>Email:</label>
            <input type="email" name="email" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:100%;">

            <label>Senha:</label>
            <input type="password" name="senha" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:100%;">

            <label>Tipo:</label>
            <select name="tipo" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:100%;">
                <option value="funcionario">Funcionário</option>
                <option value="Administrador">Administrador</option>
            </select>

            <div style="display:flex; justify-content:space-between; gap:10px; margin-top:10px;">
                <button type="submit" style="flex:1; padding:10px; border:none; border-radius:6px; background:#DA020E; color:#fff; cursor:pointer;">Salvar</button>
                <button type="button" onclick="fecharModalAdicionar()" style="flex:1; padding:10px; border:none; border-radius:6px; background:#ccc; color:#000; cursor:pointer;">Cancelar</button>
            </div>
        </form>
    </div>

    <script>
        // Modal Editar
        function abrirModal(id, nome, email, tipo) {
            const idLogado = <?= $_SESSION["id_usuario"] ?>;

            if (id === idLogado) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Vá para a tela de perfil se desejar alterar seus dados.',
                    confirmButtonColor: '#DA020E'
                });
                return;
            }

            document.getElementById('editar_usuario_id').value = id;
            document.getElementById('nome_digitado').value = nome;
            document.getElementById('email_digitado').value = email;
            document.getElementById('tipo_digitado').value = tipo;

            document.getElementById('modalEditar').style.display = 'flex';
        }

        function fecharModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }

        // Modal Adicionar
        const btnAdicionar = document.getElementById('adicionar_usuario');
        const modalAdicionar = document.getElementById('modalAdicionar');

        btnAdicionar.onclick = function(event) {
            event.preventDefault();
            modalAdicionar.style.display = 'flex';
        }

        function fecharModalAdicionar() {
            modalAdicionar.style.display = 'none';
        }

        // Fechar clicando fora
        window.onclick = function(event) {
            if (event.target == modalAdicionar) {
                fecharModalAdicionar();
            }
            if (event.target == document.getElementById('modalEditar')) {
                fecharModal();
            }
        }
    </script>

</body>

</html>