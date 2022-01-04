<?php
  session_start();
  require "vendor/autoload.php";
  use App\SQLiteConnection;

  // echo "Connexion en cours : " . $_POST["pseudo"] . ", statut = " . $_GET["statut"] . "\n";
  // echo "Mdp : " . $_POST["mdp"] . ", Hash : " . md5($_POST["mdp"]) . "\n";

  $pdo = (new SQLiteConnection())->connect();

  // Pour éviter les injections SQL
  $pseudo = htmlspecialchars(strip_tags($_POST["pseudo"]));
  $mdp = htmlspecialchars(strip_tags($_POST["mdp"]));

  $sql = "SELECT id, name, mdp FROM users WHERE name = ? and mdp = ? and status = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($pseudo, md5($mdp), $_GET["statut"]));
  $row = $stmt->fetch(\PDO::FETCH_ASSOC);

  if ($row) {
    echo "User [" . $_POST["pseudo"] . "] existe dans la base !";
    $_SESSION["idUser"] = $row["id"];
    $_SESSION["pseudo"] = $pseudo;
    $_SESSION["mdp"] = $mdp;

    // En cas de changement du $_GET["statut"]
    $_SESSION["statut"] = $_GET["statut"];

    header("Location: pageVote.php?statut=" . $_SESSION["statut"]);
  } else {
    session_destroy();
    header("Location: pageConnexion.php?statut=" . $_GET["statut"]);
  }