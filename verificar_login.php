<?php
session_start();

if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit;
}
?>