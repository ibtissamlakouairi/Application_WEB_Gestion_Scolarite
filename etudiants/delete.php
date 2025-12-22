<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

include('../config/connexion.php');

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit();
}

$id = (int) $_GET['id'];

// Optionnel : supprimer les associations (sécurité si pas de FK CASCADE)
$stmt = $conn->prepare("DELETE FROM etudiant_module WHERE id_etudiant = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Supprimer l'étudiant
$stmt2 = $conn->prepare("DELETE FROM etudiant WHERE id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$stmt2->close();

header("Location: list.php");
exit();
?>
