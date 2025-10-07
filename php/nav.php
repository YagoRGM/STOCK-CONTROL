<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/8417e3dabe.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/nav.css">
    <title>Navegação</title>
</head>

<body>
    <nav>
        <div class="logo">
            <img src="../img/logo1.png" alt="Logo">
        </div>

        <div class="pesquisa">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" placeholder="Buscar...">
        </div>

        <ul class="navegacao">
            <li><a href="../php/inicio.php">Início</a></li>
            <li><a href="#">Gerenciar camisas</a></li>
            <li><a href="#">Movimentações</a></li>
            <li><a href="#">Usuários</a></li>
            <li><i class="fa-solid fa-moon" id="modo-noturno"></i></li>
            <li><a href="#" id="perfil">KF</a></li>
        </ul>
    </nav>
</body>
<script>
    const btnModo = document.getElementById("modo-noturno");
    const body = document.body;

    btnModo.addEventListener("click", () => {
        body.classList.toggle("dark-mode");

        if (body.classList.contains("dark-mode")) {
            btnModo.classList.replace("fa-moon", "fa-sun");
        } else {
            btnModo.classList.replace("fa-sun", "fa-moon");
        }
    });
</script>

</html>