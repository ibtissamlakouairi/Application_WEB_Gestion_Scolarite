<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include('../config/connexion.php');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search != '') {
    $sql = "SELECT e.id, e.nom, e.prenom, e.email, m.intitule 
            FROM etudiant e 
            LEFT JOIN module m ON e.id_module = m.id
            WHERE e.nom LIKE '%$search%' 
            OR e.prenom LIKE '%$search%' 
            OR e.email LIKE '%$search%' 
            OR m.intitule LIKE '%$search%'";
} else {
    $sql = "SELECT e.id, e.nom, e.prenom, e.email, m.intitule 
            FROM etudiant e 
            LEFT JOIN module m ON e.id_module = m.id";
}

$result = $conn->query($sql);
?>

<h2>🔍 Recherche d’Étudiants</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
    <input type="submit" value="Rechercher">
</form>

<a href="etudiants.php">← Retour à la liste des étudiants</a>
<br><br>

<table border="1" cellpadding="5">
<tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Module</th></tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
  <td><?= $row['nom'] ?></td>
  <td><?= $row['prenom'] ?></td>
  <td><?= $row['email'] ?></td>
  <td><?= $row['intitule'] ?></td>
</tr>
<?php } ?>
</table>
