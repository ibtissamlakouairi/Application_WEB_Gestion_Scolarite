<?php
session_start();
if (!isset($_SESSION['user'])) {
header("Location: ../auth/login.php");
    exit();
}


include('../config/connexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $conn->query("INSERT INTO enseignant (nom, prenom, email) VALUES ('$nom','$prenom','$email')");
    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Enseignant</title>
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
      margin-bottom: 30px;
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

    form {
      background-color: #fff;
      max-width: 400px;
      margin: 0 auto;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #004080;
    }

    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      width: 100%;
      background-color: #0066cc;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #004999;
    }

    .btn-retour {
      display: block;
      width: fit-content;
      margin: 25px auto 0;
      padding: 10px 15px;
      background-color: #2667a0ff;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-retour:hover {
      background-color: #1e537e;
    }
  </style>
</head>
<body>

  <header>
    <a href="../auth/logout.php">Déconnexion</a>
  </header>

  <h2>Ajouter un Enseignant</h2>

  <form method="POST">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <input type="submit" value="Ajouter">
  </form>

  <a href="list.php" class="btn-retour">⬅ Retour à la liste</a>

</body>
</html>
