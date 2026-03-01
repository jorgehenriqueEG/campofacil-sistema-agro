<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit();
}

$vendedor_id = $_SESSION['vendedor_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cliente = $_POST['cliente_id'];
    $produto = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    $data_venda = date("Y-m-d");

    $dias_query = $conn->query("SELECT dias_consumo FROM produtos WHERE id = $produto");
    $dias = $dias_query->fetch_assoc()['dias_consumo'];

    $data_alerta = date('Y-m-d', strtotime("+$dias days"));

    $stmt = $conn->prepare("INSERT INTO vendas (vendedor_id, cliente_id, produto_id, quantidade, data_venda, data_alerta) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiss", $vendedor_id, $cliente, $produto, $quantidade, $data_venda, $data_alerta);
    $stmt->execute();
}

$clientes = $conn->query("SELECT * FROM clientes WHERE vendedor_id = $vendedor_id");
$produtos = $conn->query("SELECT * FROM produtos WHERE vendedor_id = $vendedor_id");

$vendas = $conn->query("
SELECT v.*, c.nome AS cliente, c.telefone, p.nome AS produto
FROM vendas v
JOIN clientes c ON v.cliente_id = c.id
JOIN produtos p ON v.produto_id = p.id
WHERE v.vendedor_id = $vendedor_id
ORDER BY v.id DESC
");
?>

<?php include("menu.php"); ?>

<div class="content">

<div class="card">
<h2>Registrar Venda</h2>

<form method="POST">
<select name="cliente_id" required>
<option value="">Selecionar Cliente</option>
<?php while($c = $clientes->fetch_assoc()): ?>
<option value="<?= $c['id'] ?>"><?= $c['nome'] ?></option>
<?php endwhile; ?>
</select>

<select name="produto_id" required>
<option value="">Selecionar Produto</option>
<?php while($p = $produtos->fetch_assoc()): ?>
<option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
<?php endwhile; ?>
</select>

<input type="number" name="quantidade" placeholder="Quantidade" required>
<button type="submit">Registrar</button>
</form>
</div>

<div class="card">
<h2>Lista de Vendas</h2>

<table>
<tr>
<th>Cliente</th>
<th>Produto</th>
<th>Data Compra</th>
<th>Data Alerta</th>
<th>WhatsApp</th>
</tr>

<?php while($v = $vendas->fetch_assoc()): ?>
<tr>
<td><?= $v['cliente'] ?></td>
<td><?= $v['produto'] ?></td>
<td><?= date('d/m/Y', strtotime($v['data_venda'])) ?></td>
<td><?= date('d/m/Y', strtotime($v['data_alerta'])) ?></td>
<td><?= $v['telefone'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

</div>