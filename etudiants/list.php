<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}
include('../config/connexion.php');
$result = $conn->query("
    SELECT e.id, e.nom, e.prenom, e.email,
           GROUP_CONCAT(m.intitule SEPARATOR ', ') AS modules
    FROM etudiant e
    LEFT JOIN etudiant_module em ON e.id = em.id_etudiant
    LEFT JOIN module m ON em.id_module = m.id
    GROUP BY e.id
");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Liste des Étudiants</title>

<style>
body {
    font-family: Arial, sans-serif; background-color: #f2f9ff; margin: 40px; color: #333;
}

h2 {
    text-align: center; color: #0066cc;
}

table {
    width: 90%; margin: 20px auto; border-collapse: collapse; background-color: #fff;
}

th, td {
    padding: 10px; border: 1px solid #ddd; text-align: center;
}

th {
    background-color: #cce7ff;color: #004080;
}

a {
    color: #007bff;text-decoration: none;font-weight: bold;
}

a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    .btn-ajout {
      display: block;
      width: fit-content;
      margin: 20px auto;
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-ajout:hover {
      background-color: #0056b3;
    } 

a:hover {
    text-decoration: underline;
}

header {
    display: flex;justify-content: flex-end;  gap: 15px; margin-bottom: 20px;
}

header a {
    padding: 8px 15px; background-color: #152691; color: white; border-radius: 5px;
}
.logo {
    width: 100px; height: 100px;object-fit: cover; border-radius: 10px; display: block;
}

</style>
</head>
<body>
<header>
    <a href="../index.php.php">Accueil</a>
    <a href="../auth/logout.php">Déconnexion</a>
</header>
<h2>Liste des Étudiants</h2>
<a href="../etudiants/add.php" class="btn-ajout">+ Ajouter un etudiant</a>

<table>
<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Email</th>
    <th>Modules</th>
    <th>Notes</th>
    <th>Actions</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= htmlspecialchars($row['nom']) ?></td>
    <td><?= htmlspecialchars($row['prenom']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['modules']) ?></td>
    <td>
<a href="../notes/list.php?id=<?= $row['id'] ?>">Voir notes</a>
    </td>
    <td>
        <a href="edit.php?id=<?= $row['id'] ?>">Modifier</a> |
        <a href="delete.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Supprimer cet étudiant ?')">Supprimer</a>
    </td>
</tr>
<?php } ?>
</table>
</body>
</html>
