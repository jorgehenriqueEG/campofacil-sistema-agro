<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = $conn->prepare("INSERT INTO vendedores (nome, email, senha) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $nome, $email, $senha);
    $sql->execute();

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Criar Conta - CampoFácil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-body">

<div class="login-box">
    <h1>🐄 Criar Conta</h1>

    <form method="POST">
        <input type="text" name="nome" placeholder="Seu Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
    </form>

    <a href="login.php">Voltar para Login</a>
</div>

</body>
</html>