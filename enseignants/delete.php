<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

include('../config/connexion.php');
$id = $_GET['id'];
$conn->query("DELETE FROM enseignant WHERE id=$id");
header("Location: list.php");
?>
