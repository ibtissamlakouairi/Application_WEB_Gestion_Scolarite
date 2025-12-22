<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

include('../config/connexion.php');

if (!isset($_GET['id'])) {
    die("ID étudiant manquant.");
}

$id_etudiant = intval($_GET['id']);

// Récupérer l'étudiant
$stmt = $conn->prepare("SELECT * FROM etudiant WHERE id = ?");
$stmt->bind_param("i", $id_etudiant);
$stmt->execute();
$etudiant = $stmt->get_result()->fetch_assoc();

if (!$etudiant) {
    die("Étudiant introuvable.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['note_cc'] as $module_id => $cc) {
        $exam = floatval($_POST['note_exam'][$module_id]);
        $cc = floatval($cc);
        $moyenne = ($cc * 0.4) + ($exam * 0.6);

        // Vérifier si la note existe
        $stmtCheck = $conn->prepare("SELECT * FROM notes WHERE id_etudiant=? AND id_module=?");
        $stmtCheck->bind_param("ii", $id_etudiant, $module_id);
        $stmtCheck->execute();
        $res = $stmtCheck->get_result();

        if ($res->num_rows > 0) {
            // UPDATE
            $stmtUpdate = $conn->prepare("UPDATE notes SET note_cc=?, note_exam=?, moyenne_module=? WHERE id_etudiant=? AND id_module=?");
            $stmtUpdate->bind_param("ddiii", $cc, $exam, $moyenne, $id_etudiant, $module_id);
            $stmtUpdate->execute();
        } else {
            // INSERT
            $stmtInsert = $conn->prepare("INSERT INTO notes (id_etudiant, id_module, note_cc, note_exam, moyenne_module) VALUES (?, ?, ?, ?, ?)");
            $stmtInsert->bind_param("iiddd", $id_etudiant, $module_id, $cc, $exam, $moyenne);
            $stmtInsert->execute();
        }
    }
    $success = "Notes enregistrées avec succès !";
}

$stmt2 = $conn->prepare("
    SELECT m.id AS module_id, m.intitule,
           n.note_cc, n.note_exam, n.moyenne_module
    FROM etudiant_module em
    JOIN module m ON em.id_module = m.id
    LEFT JOIN notes n ON n.id_module = m.id AND n.id_etudiant = em.id_etudiant
    WHERE em.id_etudiant = ?
    ORDER BY m.intitule
");
$stmt2->bind_param("i", $id_etudiant);
$stmt2->execute();
$modules = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes de <?= htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f9ff;
            margin: 40px;
            color: #333;
            line-height: 1.6;
        }
        h2 {
            text-align: center;
            color: #0066cc;
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #cce7ff;
            font-weight: bold;
            color: #004080;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        input[type=number] {
            width: 80px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            background-color: #152691;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0e1a66;
        }
        .success {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
            background-color: #e8f5e8;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #c3e6c3;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        header {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px 40px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        header a {
            padding: 8px 15px;
            background-color: #152691;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        header a:hover {
            background-color: #0e1a66;
        }
        .form-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <a href="../index.php.php">Accueil</a>
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<h2>Notes de <?= htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']) ?></h2>

<?php if (!empty($success)) : ?>
    <p class="success"><?= $success ?></p>
<?php endif; ?>

<form method="POST" action="">
    <table>
        <tr>
            <th>Module</th>
            <th>CC (40%)</th>
            <th>Examen (60%)</th>
            <th>Moyenne</th>
        </tr>

        <?php while ($m = $modules->fetch_assoc()) : ?>
        <tr>
            <td><?= htmlspecialchars($m['intitule']) ?></td>
            <td>
                <input type="number" step="0.01" min="0" max="20" name="note_cc[<?= $m['module_id'] ?>]"
                       value="<?= isset($m['note_cc']) ? $m['note_cc'] : '' ?>">
            </td>
            <td>
                <input type="number" step="0.01" min="0" max="20" name="note_exam[<?= $m['module_id'] ?>]"
                       value="<?= isset($m['note_exam']) ? $m['note_exam'] : '' ?>">
            </td>
            <td>
                <?= isset($m['moyenne_module']) ? number_format($m['moyenne_module'], 2) : '-' ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="form-container">
        <button type="submit">Enregistrer</button>
    </div>
</form>

</body>
</html>