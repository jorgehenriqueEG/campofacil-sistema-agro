<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit();
}

$vendedor_id = $_SESSION['vendedor_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $cidade = $_POST['cidade'];
    $telefone = $_POST['telefone'];
    $tipo = $_POST['tipo_gado'];
    $quantidade = $_POST['quantidade'];

    $stmt = $conn->prepare("INSERT INTO clientes (vendedor_id,nome,cidade,telefone,tipo_gado,quantidade_animais) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("issssi",$vendedor_id,$nome,$cidade,$telefone,$tipo,$quantidade);
    $stmt->execute();
}

$clientes = $conn->query("SELECT * FROM clientes WHERE vendedor_id = $vendedor_id ORDER BY id DESC");
?>

<?php include("menu.php"); ?>

<div class="content">

<div class="card">
<h2>Cadastrar Cliente</h2>

<form method="POST">
<input type="text" name="nome" placeholder="Nome da Fazenda" required>
<input type="text" name="cidade" placeholder="Cidade" required>
<input type="text" name="telefone" placeholder="Telefone" required>

<select name="tipo_gado">
<option value="Corte">Gado de Corte</option>
<option value="Leite">Gado Leiteiro</option>
</select>

<input type="number" name="quantidade" placeholder="Quantidade de Animais">
<button type="submit">Cadastrar</button>
</form>
</div>

<div class="card">
<h2>Lista de Clientes</h2>

<table>
<tr>
<th>Nome</th>
<th>Cidade</th>
<th>Telefone</th>
<th>Tipo</th>
<th>Qtd</th>
</tr>

<?php while($c = $clientes->fetch_assoc()): ?>
<tr>
<td><?= $c['nome'] ?></td>
<td><?= $c['cidade'] ?></td>
<td><?= $c['telefone'] ?></td>
<td><?= $c['tipo_gado'] ?></td>
<td><?= $c['quantidade_animais'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

</div>