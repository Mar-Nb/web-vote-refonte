<?php
  session_start();
  $pdo = new PDO("sqlite:db/phpsqlite.db");

  // Pour éviter les injections SQL
  $pseudo = htmlspecialchars(strip_tags($_POST["pseudo"]));
  $mdp = htmlspecialchars(strip_tags($_POST["mdp"]));

  if ($_GET["statut"] == "admin") {
    $sql = "SELECT id, name, mdp FROM superusers WHERE name = ? and mdp = ?" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($pseudo, md5($mdp)));
  } else {
    $sql = "SELECT id, name, mdp FROM users WHERE name = ? and mdp = ? and status = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($pseudo, md5($mdp), $_GET["statut"]));
  }

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $_SESSION["idUser"] = $row["id"];
    $_SESSION["pseudo"] = $pseudo;
    $_SESSION["mdp"] = $mdp;

    // En cas de changement du $_GET["statut"]
    $_SESSION["statut"] = $_GET["statut"];

    if ($_GET["statut"] == "admin") { header("Location: pageAdmin.php"); }
    else { header("Location: pageVote.php?statut=" . $_SESSION["statut"]); }
  } else {
    session_destroy();
    header("Location: pageConnexion.php?statut=" . $_GET["statut"]);
  }