<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id"];

$sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $tipo = $_POST["tipo"]; 

    if (!empty($_POST["senha"])) {
        $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
        $update = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
        $update->bind_param("sssi", $nome, $email, $senha, $id_usuario);
    } else {
        $update = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $update->bind_param("ssi", $nome, $email, $id_usuario);
    }

    $update->execute();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - StockControl</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'nav.php'; ?>

    <main>
        <div id="banner">
            <h1>Meu Perfil</h1>
            <p>Gerencie suas informações de usuário</p>
        </div>
        <div class="container-form">
            <form method="POST">
                <h3>Informações Iniciais</h3>
    
                <div>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required placeholder="Nome Completo">
                </div>
    
                <div>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required placeholder="E-mail">
                </div>

                <h3>Criar Nova Senha</h3>
    
                <div>
                    <input type="password" id="senha" name="senha" placeholder="Nova senha" placeholder="Senha Atual">
                </div>
    
                <div>
                    <input type="password" id="nova_senha" name="nova_senha" placeholder="Nova senha">
                </div>
    
                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-section">
                <img src="../img/logo1.png" alt="Logo" class="footer-logo">
            </div>
            <div class="footer-section" id="footer1">
                <h4>Sistema de Gerenciamento de Estoque</h4>
                <p>Gerencie o estoque da loja StockControl de forma eficiente e prática. Usufrua de uma interface intuitiva e recursos poderosos para otimizar seu processo de gerenciamento.</p>
            </div>
            <div class="footer-section">
                <h3>Contato</h3>
                <p><strong>Endereço:</strong> Rua Av. Monsenhor Theodomiro Lobo, 100 - Parque Res. Maria Elmira,
                    Caçapava - SP, 12285-050<br>
                    <strong>Email:</strong> <a href="mailto:stockcontrolcontact@gmail.com"
                        target="_blank">stockcontrolcontact@gmail.com</a>
                    <br>
                    <strong>Telefone:</strong> (12) 1234-5678
                </p>
            </div>
        </div>
        <div class="bottom">
            &copy; Sistema StockControl. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
