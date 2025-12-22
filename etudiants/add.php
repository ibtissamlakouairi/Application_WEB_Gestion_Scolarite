<?php
session_start();

if (!isset($_SESSION['user'])) {
header("Location: ../auth/login.php");
    exit();
}


include('../config/connexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $modules_selected = $_POST['id_module']; // tableau de modules

    // 1) Insérer l'étudiant
    $stmt = $conn->prepare("INSERT INTO etudiant (nom, prenom, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $prenom, $email);
    $stmt->execute();
    $id_etudiant = $stmt->insert_id;
    $stmt->close();

    // 2) Enregistrer les modules associés
    $stmt2 = $conn->prepare("INSERT INTO etudiant_module (id_etudiant, id_module) VALUES (?, ?)");

    foreach ($modules_selected as $id_m) {
        $stmt2->bind_param("ii", $id_etudiant, $id_m);
        $stmt2->execute();
    }

    $stmt2->close();

    header("Location: list.php");
    exit();
}



$modules = $conn->query("SELECT * FROM module ORDER BY intitule");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Étudiant</title>
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
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      width: 100%;
      color: white;
      background-color: #0066cc;
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
      background-color: #25639d;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-retour:hover {
      background-color: #1e567e;
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

  <h2>Ajouter un Étudiant</h2>

  <form method="POST">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="email">Email :</label>
    <input type="text" id="email" name="email" required>

<label for="id_module">Modules :</label>
<select id="id_module" name="id_module[]" multiple required>
  <?php while ($m = $modules->fetch_assoc()) { ?>
    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['intitule']) ?></option>
  <?php } ?>
</select>

    <input type="submit" value="Ajouter">
  </form>

  <a href="list.php" class="btn-retour">⬅ Retour à la liste</a>

</body>
</html>
