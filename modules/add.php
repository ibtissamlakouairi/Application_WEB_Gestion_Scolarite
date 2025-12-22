<?php
session_start();

if (!isset($_SESSION['user'])) {
header("Location: ../auth/login.php");
    exit();
}

include('../config/connexion.php');

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = trim($_POST['code']);
    $intitule = trim($_POST['intitule']);
    $id_enseignant = $_POST['id_enseignant'];

    // Requête préparée pour éviter les injections SQL
    $stmt = $conn->prepare("INSERT INTO module (code, intitule, id_enseignant) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $code, $intitule, $id_enseignant);
    $stmt->execute();
    $stmt->close();

    header("Location: list.php");
    exit();
}

// Récupération de la liste des enseignants
$enseignants = $conn->query("SELECT * FROM enseignant ORDER BY nom");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Module</title>
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
    select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ccc;
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
      background-color: #275e9a;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-retour:hover {
      background-color: #1e507e;
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
      background-color: #152691;
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

  <header>
    <a href="../auth/logout.php">Déconnexion</a>
  </header>

  <h2>Ajouter un Module</h2>

  <form method="POST">
    <label for="code">Code :</label>
    <input type="text" id="code" name="code" required>

    <label for="intitule">Intitulé :</label>
    <input type="text" id="intitule" name="intitule" required>

    <label for="id_enseignant">Enseignant :</label>
    <select id="id_enseignant" name="id_enseignant" required>
      <option value="">-- Sélectionner un enseignant --</option>
      <?php while ($e = $enseignants->fetch_assoc()) { ?>
        <option value="<?= $e['id'] ?>">
          <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?>
        </option>
      <?php } ?>
    </select>

    <input type="submit" value="Ajouter">
  </form>

  <a href="list.php" class="btn-retour">⬅ Retour à la liste</a>

</body>
</html>
