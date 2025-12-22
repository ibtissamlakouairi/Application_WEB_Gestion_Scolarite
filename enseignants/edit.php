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

$id = $_GET['id'];

// Récupérer les données de l’enseignant
$result = $conn->query("SELECT * FROM enseignant WHERE id = $id");
$enseignant = $result->fetch_assoc();

if (!$enseignant) {
    header("Location: list.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $conn->query("UPDATE enseignant SET nom='$nom', prenom='$prenom', email='$email' WHERE id=$id");
    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier un Enseignant</title>
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

    form {
      width: 400px;
      margin: 40px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #004080;
    }

    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      width: 100%;
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .btn-retour {
      display: block;
      width: fit-content;
      margin: 20px auto;
      padding: 10px 15px;
      background-color: #285ba7ff;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-retour:hover {
      background-color: #1e517eff;
    }

    header {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 15px 40px;
      border-radius: 8px;
      margin-bottom: 30px;
    }

    header a {
      padding: 8px 15px;
      background-color: #152691ff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }

    header a:hover {
      background-color: #0e1a66;
    }
  </style>
</head>
<body>

  <!-- Barre de déconnexion -->
  <header>
    <a href="../auth/logout.php">Déconnexion</a>
  </header>

  <h2>Modifier l'Enseignant</h2>

  <form method="POST">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($enseignant['nom']) ?>" required>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($enseignant['prenom']) ?>" required>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($enseignant['email']) ?>" required>

    <input type="submit" value="Mettre à jour">
  </form>

  <a href="list.php" class="btn-retour">⬅ Retour à la liste</a>

</body>
</html>
