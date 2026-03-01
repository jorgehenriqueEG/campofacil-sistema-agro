<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit();
}

$vendedor_id = $_SESSION['vendedor_id'];

$sql = "
SELECT 
    v.id,
    c.nome AS cliente,
    c.telefone,
    p.nome AS produto,
    v.data_venda,
    v.data_alerta
FROM vendas v
JOIN clientes c ON v.cliente_id = c.id
JOIN produtos p ON v.produto_id = p.id
WHERE v.vendedor_id = $vendedor_id
ORDER BY v.data_alerta ASC
";

$result = $conn->query($sql);
?>

<?php include("menu.php"); ?>

<div class="content">
    <div class="card">
        <h2>Lista de Alertas</h2>

        <table>
            <tr>
                <th>Cliente</th>
                <th>Produto</th>
                <th>Data Venda</th>
                <th>Data Alerta</th>
                <th>WhatsApp</th>
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['cliente'] ?></td>
                <td><?= $row['produto'] ?></td>
                <td><?= date('d/m/Y', strtotime($row['data_venda'])) ?></td>
                <td style="color:red;font-weight:bold;">
                    <?= date('d/m/Y', strtotime($row['data_alerta'])) ?>
                </td>
                <td>
                    <a target="_blank" 
                       href="https://wa.me/55<?= preg_replace('/[^0-9]/','',$row['telefone']) ?>">
                       Enviar
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>