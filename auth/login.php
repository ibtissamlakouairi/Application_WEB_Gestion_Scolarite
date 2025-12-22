<?php
session_start();
include('../config/connexion.php');

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']); 
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM utilisateur WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user'] = $username; 
        header("Location: ../index.php.php"); 
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<style>
  body {
    background-color: #f2f9ff;
    font-family: Arial;
    text-align: center;
    margin-top: 100px;
  }
  .login-box {
    display: inline-block;
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  input[type="text"], input[type="password"] {
    width: 250px;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
  }
  input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  input[type="submit"]:hover {
    background-color: #0056b3;
  }
  .error {
    color: red;
  }
</style>
</head>
<body>

<div class="login-box">
  <h2>Connexion</h2>
  <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required><br>
    <input type="password" name="password" placeholder="Mot de passe" required><br>
    <input type="submit" value="Se connecter">
  </form>
</div>

</body>
</html>
