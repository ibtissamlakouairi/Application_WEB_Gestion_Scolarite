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

// Récupérer l'étudiant
$stmt = $conn->prepare("SELECT id, nom, prenom, email FROM etudiant WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$etudiant = $res->fetch_assoc();
$stmt->close();

if (!$etudiant) {
    header("Location: list.php");
    exit();
}

// Récupérer la liste des modules disponibles
$modules = $conn->query("SELECT * FROM module ORDER BY intitule");

// Récupérer les modules déjà assignés à cet étudiant
$assigned = [];
$stmt2 = $conn->prepare("SELECT id_module FROM etudiant_module WHERE id_etudiant = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($r = $res2->fetch_assoc()) {
    $assigned[] = $r['id_module'];
}
$stmt2->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage / validation basique
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $modules_selected = isset($_POST['id_module']) ? $_POST['id_module'] : [];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // 1) Mettre à jour les informations de l'étudiant
        $stmtUpd = $conn->prepare("UPDATE etudiant SET nom = ?, prenom = ?, email = ? WHERE id = ?");
        $stmtUpd->bind_param("sssi", $nom, $prenom, $email, $id);
        $stmtUpd->execute();
        $stmtUpd->close();

        // 2) Supprimer les anciennes associations (si existantes)
        $stmtDel = $conn->prepare("DELETE FROM etudiant_module WHERE id_etudiant = ?");
        $stmtDel->bind_param("i", $id);
        $stmtDel->execute();
        $stmtDel->close();

        // 3) Insérer les nouvelles associations (si au moins 1 module sélectionné)
        if (!empty($modules_selected)) {
            $stmtIns = $conn->prepare("INSERT INTO etudiant_module (id_etudiant, id_module) VALUES (?, ?)");
            foreach ($modules_selected as $mid) {
                $mid = (int) $mid;
                $stmtIns->bind_param("ii", $id, $mid);
                $stmtIns->execute();
            }
            $stmtIns->close();
        }

        $conn->commit();
        header("Location: list.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        // Afficher erreur simple (ou logger)
        echo "Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage());
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier un Étudiant</title>
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
    input[type="email"],
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
      background-color: #286aa7ff;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-retour:hover {
      background-color: #1e5b7eff;
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

  <h2>Modifier un Étudiant</h2>

  <form method="POST">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($etudiant['email']) ?>" required>

    <label for="id_module">Modules :</label>
    <select id="id_module" name="id_module[]" multiple>
      <?php
      // réinitialiser le pointeur si nécessaire
      $modules->data_seek(0);
      while($m = $modules->fetch_assoc()) {
          $sel = in_array($m['id'], $assigned) ? 'selected' : '';
      ?>
        <option value="<?= $m['id'] ?>" <?= $sel ?>><?= htmlspecialchars($m['intitule']) ?></option>
      <?php } ?>
    </select>

    <input type="submit" value="Modifier">
  </form>

  <a href="list.php" class="btn-retour">⬅ Retour à la liste</a>

</body>
</html>
