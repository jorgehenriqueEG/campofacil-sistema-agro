<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit();
}

$vendedor_id = $_SESSION['vendedor_id'];

// CADASTRAR PRODUTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $dias = $_POST['dias_consumo'];

    $stmt = $conn->prepare("INSERT INTO produtos (vendedor_id, nome, valor, dias_consumo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $vendedor_id, $nome, $valor, $dias);
    $stmt->execute();
}

// LISTAR PRODUTOS
$produtos = $conn->query("SELECT * FROM produtos WHERE vendedor_id = $vendedor_id ORDER BY id DESC");
?>

<?php include("menu.php"); ?>

<div class="content">
    <div class="card">
        <h2>Cadastrar Produto</h2>

        <form method="POST">
            <input type="text" name="nome" placeholder="Nome do Produto" required>
            <input type="number" step="0.01" name="valor" placeholder="Valor" required>
            <input type="number" name="dias_consumo" placeholder="Dias de Consumo" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <div class="card">
        <h2>Lista de Produtos</h2>

        <table>
            <tr>
                <th>Nome</th>
                <th>Valor</th>
                <th>Dias</th>
            </tr>

            <?php while($p = $produtos->fetch_assoc()): ?>
            <tr>
                <td><?= $p['nome'] ?></td>
                <td>R$ <?= number_format($p['valor'],2,',','.') ?></td>
                <td><?= $p['dias_consumo'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>