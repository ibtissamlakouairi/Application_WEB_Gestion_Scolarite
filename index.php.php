<?php
session_start();
if (!isset($_SESSION['user'])) {
header("Location: auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Application de Gestion de Scolarité</title>
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
    ul {
      list-style-type: none;
      padding: 0;
      max-width: 400px;
      margin: 30px auto;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    li {
      border-bottom: 1px solid #ddd;
    }
    li:last-child {
      border-bottom: none;
    }
    li a {
      display: block;
      padding: 15px;
      text-decoration: none;
      color: #004080;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    li a:hover {
      background-color: #e9f5ff;
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

  <header>
    <a href="auth/logout.php">Déconnexion</a>
  </header>

  <h2>Application de Gestion de Scolarité</h2>

  <ul>
    <li><a href="etudiants/list.php">Gestion des Étudiants</a></li>
    <li><a href="modules/list.php">Gestion des Modules</a></li>
    <li><a href="enseignants/list.php">Gestion des Enseignants</a></li>
   <!-- <li><a href="notes/add.php">Saisie des notes</a></li> -->
    <li><a href="releves/releve.php?id=1">Relevé de notes</a></li>
</ul>

  </ul>

 
</body>
</html>