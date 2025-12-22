<?php
session_start();
if (!isset($_SESSION['user'])) {
header("Location: ../auth/login.php");
    exit();

}

include('../config/connexion.php');
$result = $conn->query("SELECT * FROM enseignant");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des Enseignants</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f9ff;
      margin: 40px;
      color: #333;
    }

    h2 {
      text-align: center;
      color: #0066cc;
    }

    table {
      width: 80%;
      margin: 20px auto;
      border-collapse: collapse;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      background-color: #fff;
    }

    th, td {
      padding: 10px 15px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #cce7ff;
      color: #004080;
    }

    tr:hover {
      background-color: #e9f5ff;
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

    header {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 15px;
      padding: 15px 40px;
      border-radius: 8px;
      margin-bottom: 30px;
    }

    header a {
      padding: 8px 15px;
      background-color: #152691;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    header a:hover {
      background-color: #0e1a66;
    }

  </style>
</head>
<body>

  <header>   
    <a href="../index.php.php">Accueil</a>
    <a href="../auth/logout.php">Déconnexion</a>
  </header>

  <h2>Liste des Enseignants</h2>
    <a href="add.php" class="btn-ajout"> Ajouter un enseignant</a>


  <table>
    <tr>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Email</th>
      <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?= htmlspecialchars($row['nom']) ?></td>
        <td><?= htmlspecialchars($row['prenom']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
          <a href="edit.php?id=<?= $row['id'] ?>">Modifier</a> |
          <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet enseignant ?')">Supprimer</a>
        </td>
      </tr>
    <?php } ?>
  </table>


</body>
</html>

