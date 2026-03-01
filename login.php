<?php
session_start();
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = $conn->prepare("SELECT * FROM vendedores WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {

            $_SESSION['vendedor_id'] = $usuario['id'];
            $_SESSION['vendedor_nome'] = $usuario['nome'];

            header("Location: dashboard.php");
            exit();

        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - CampoFácil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-body">

<div class="login-box">
    <h1>🐄 CampoFácil</h1>

    <?php if(isset($erro)) echo "<p class='erro'>$erro</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

    <a href="register.php">Criar conta</a>
</div>

</body>
</html>