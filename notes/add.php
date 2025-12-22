<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../config/connexion.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$etudiants = $conn->query("SELECT * FROM etudiant ORDER BY nom");

if (!$etudiants) {
    die("Erreur lors de la récupération des étudiants : " . $conn->error);
}

$modules = null;
$id_etudiant = null;

/* ======================
   ÉTAPE 1 : sélection étudiant
====================== */
if (isset($_GET['id_etudiant'])) {
    $id_etudiant = intval($_GET['id_etudiant']);

    $stmt = $conn->prepare("
        SELECT m.id, m.intitule
        FROM etudiant_module me
        JOIN module m ON me.id_module = m.id
        WHERE me.id_etudiant = ?
        AND m.id NOT IN (
            SELECT id_module FROM notes WHERE id_etudiant = ?
        )
        ORDER BY m.intitule
    ");
    if (!$stmt) {
        die("Erreur de préparation : " . $conn->error);
    }

    $stmt->bind_param("ii", $id_etudiant, $id_etudiant);
    if (!$stmt->execute()) {
        die("Erreur lors de l'exécution de la requête : " . $stmt->error);
    }

    $modules = $stmt->get_result();
}

/* ======================
   ÉTAPE 2 : insertion note
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_etudiant = intval($_POST['id_etudiant']);
    $id_module   = intval($_POST['id_module']);
    $cc          = floatval($_POST['note_cc']);
    $exam        = floatval($_POST['note_exam']);

    if ($cc < 0 || $cc > 20 || $exam < 0 || $exam > 20) {
        die("Notes invalides, elles doivent être entre 0 et 20.");
    }

    $moyenne = ($cc * 0.4) + ($exam * 0.6);

    $stmt = $conn->prepare("
        INSERT INTO notes (id_etudiant, id_module, note_cc, note_exam, moyenne_module)
        VALUES (?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        die("Erreur de préparation : " . $conn->error);
    }

    $stmt->bind_param("iiddd", $id_etudiant, $id_module, $cc, $exam, $moyenne);
    if (!$stmt->execute()) {
        die("Erreur lors de l'insertion : " . $stmt->error);
    }

    header("Location: list.php?id_etudiant=" . $id_etudiant);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie des notes</title>

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

form {
    max-width: 500px;
    margin: 20px auto;
    background-color: #ffffff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #004080;
}

select, input[type="number"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #152691ff;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background-color: #0e1a66;
}

hr {
    max-width: 600px;
    margin: 30px auto;
    border: 1px solid #ddd;
}

p {
    text-align: center;
    font-weight: bold;
    color: #555;
}


    a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

a:hover {
    background-color: #cfe8ff;
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
<h2>Saisie des notes</h2>

<!-- ======================
     Sélection étudiant
====================== -->
<form method="GET" action="">
    <label for="id_etudiant">Étudiant :</label>
    <select name="id_etudiant" id="id_etudiant" required>
        <option value="">-- Choisir --</option>
        <?php while ($e = $etudiants->fetch_assoc()) : ?>
            <option value="<?= htmlspecialchars($e['id']) ?>"
                <?= ($id_etudiant == $e['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Valider</button>
</form>

<hr>

<!-- ======================
     Formulaire notes
====================== -->
<?php if ($modules && $modules->num_rows > 0) : ?>

    <form method="POST" action="">
        <input type="hidden" name="id_etudiant" value="<?= htmlspecialchars($id_etudiant) ?>">

        <label for="id_module">Module :</label>
        <select name="id_module" id="id_module" required>
            <?php while ($m = $modules->fetch_assoc()) : ?>
                <option value="<?= htmlspecialchars($m['id']) ?>">
                    <?= htmlspecialchars($m['intitule']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="note_cc">Note CC (40%) :</label>
        <input type="number" step="0.01" min="0" max="20" name="note_cc" id="note_cc" required><br><br>

        <label for="note_exam">Note Examen (60%) :</label>
        <input type="number" step="0.01" min="0" max="20" name="note_exam" id="note_exam" required><br><br>

        <button type="submit">Enregistrer</button>
    </form>

<?php elseif ($id_etudiant) : ?>
    <p><b>Tous les modules sont déjà notés pour cet étudiant.</b></p>
<?php endif; ?>



<br>
<br>
<?php if ($id_etudiant): ?>
    <a href="list.php?id_etudiant=<?= htmlspecialchars($id_etudiant) ?>">Liste des notes</a>
<?php else: ?>
    <p>Veuillez sélectionner un étudiant pour voir la liste des notes.</p>
<?php endif; ?>


</body>
</html>
