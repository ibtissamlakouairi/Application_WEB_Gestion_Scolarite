<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

include('../config/connexion.php');

$etudiant = null;
$notes = null;
$moyenne_generale = null;
$mention = null;


if (isset($_GET['nom']) && !empty(trim($_GET['nom']))) {

    $nom = "%" . trim($_GET['nom']) . "%";

    // Recherche de l'étudiant par nom
    $stmt = $conn->prepare("SELECT * FROM etudiant WHERE nom LIKE ? OR prenom LIKE ? LIMIT 1");
    $stmt->bind_param("ss", $nom, $nom);
    $stmt->execute();
    $etudiant = $stmt->get_result()->fetch_assoc();

    if ($etudiant) {
        $id = $etudiant['id'];

        // Notes
        $stmt2 = $conn->prepare("
            SELECT m.intitule, n.note_cc, n.note_exam, n.moyenne_module
            FROM module m
            LEFT JOIN notes n
               ON m.id = n.id_module
              AND n.id_etudiant = ?
            ORDER BY m.intitule
        ");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $notes = $stmt2->get_result();

        // Moyenne générale
        $stmt3 = $conn->prepare("
            SELECT AVG(moyenne_module) AS moyenne_generale
            FROM notes
            WHERE id_etudiant = ?
        ");
        $stmt3->bind_param("i", $id);
        $stmt3->execute();
        $row = $stmt3->get_result()->fetch_assoc();

        if ($row && $row['moyenne_generale'] !== null) {
            $moyenne_generale = $row['moyenne_generale'];

            if ($moyenne_generale >= 16) $mention = "Très bien";
            elseif ($moyenne_generale >= 14) $mention = "Bien";
            elseif ($moyenne_generale >= 12) $mention = "Assez bien";
            elseif ($moyenne_generale >= 10) $mention = "Passable";
            else $mention = "Ajourné";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Relevé de notes</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f2f9ff;
    margin: 30px;
    color: #333;
}

h2 { text-align: center; color: #050505ff; }
p { text-align: center; font-weight: bold; }

table {
    border-collapse: collapse;
    width: 100%;
    background: white;
}
th, td {
    border: 1px solid #000;
    padding: 6px;
    text-align: center;
}

header {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-bottom: 20px;
}
header a {
    padding: 8px 15px;
    background-color: #152691;
    color: white;
    border-radius: 5px;
}

.search-form {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}
.search-form input {
    width: 200px;
    padding: 5px;
    border-radius: 5px;
}
.search-form button {
    padding: 6px 12px;
    background-color: #4f72c4ff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.print-only { display: none; }

@media print {
    .search-form, #print-btn, header { display: none; }
    .print-only {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .logo { width: 90px; }
    .school-info {
        text-align: center;
        flex: 1;
    }
    .school-info h1 {
        font-size: 24px;
        margin: 0;
    }
    .school-info p {
        font-size: 18px;
        margin: 0;
    }
}
</style>

<script>
function imprimer() { window.print(); }
</script>
</head>
<body>

<header>
    <a href="../index.php.php">Accueil</a>
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="print-only">
    <img src="../images/FPSB.png" class="logo">
    <div class="school-info">
        <h2>Faculté Polydisciplinaire Sidi Bennour</h2>
        <h3>Relevé de notes</3>
    </div>
    <img src="../images/UCD.png" class="logo">
</div>

<form method="GET" class="search-form">
    <label>Nom ou prénom :</label>
    <input type="text" name="nom" required placeholder="Entrer un nom">
    <button type="submit">Rechercher</button>
</form>

<hr>

<?php if (isset($_GET['nom']) && !$etudiant) { ?>
    <p style="color:red;">Aucun étudiant trouvé pour ce nom.</p>
<?php } ?>

<?php if ($etudiant) { ?>

<p>
<b>Nom :</b> <?= htmlspecialchars($etudiant['nom']) ?><br>
<b>Prénom :</b> <?= htmlspecialchars($etudiant['prenom']) ?><br>
<b>Email :</b> <?= htmlspecialchars($etudiant['email']) ?>
</p>

<table>
<tr>
    <th>Module</th>
    <th>CC</th>
    <th>Examen</th>
    <th>Moyenne</th>
</tr>

<?php while ($n = $notes->fetch_assoc()) { ?>
<tr>
    <td><?= htmlspecialchars($n['intitule']) ?></td>
    <td><?= isset($n['note_cc']) ? $n['note_cc'] : '-' ?></td>
    <td><?= isset($n['note_exam']) ? $n['note_exam'] : '-' ?></td>
    <td><?= isset($n['moyenne_module']) ? number_format($n['moyenne_module'], 2) : '-' ?></td>
</tr>
<?php } ?>
</table>

<br>

<?php if ($moyenne_generale !== null) { ?>
    <h3>Moyenne générale : <?= number_format($moyenne_generale, 2) ?></h3>
    <h3>Mention : <?= htmlspecialchars($mention) ?></h3>
<?php } ?>

<br>
<button id="print-btn" onclick="imprimer()">Imprimer</button>

<?php } ?>

</body>
</html>
