<?php
session_start();
require "vendor/autoload.php";

use App\SQLiteConnection;

$pdo = (new SQLiteConnection())->connect();
$data = "";
$ok = true;

foreach ($_POST as $key => $v) {
  if ($v >= 0 && $v <= 5) { $data .= $key . ", " . $v . "\n"; }
  else { $ok = false; break; }
}

if ($ok) {
  // On supprime l'existence d'un potentiel vote déjà existant de l'utilisateur
  $sql = "DELETE FROM vote WHERE idEleve = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $_SESSION["idUser"], \PDO::PARAM_INT);
  $stmt->execute();

  // Insertion des (nouvelles) données de vote de l'utilisateur
  try {
    foreach ($_POST as $key => $v) {
      $sql = "INSERT INTO vote(idEleve, idProf, value) VALUES (" . $_SESSION["idUser"] . ", :idProf, :value)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(explode("-", $key)[1], $v));
    }  
  } catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode(array("success" => false));
  }

  http_response_code(200);
  echo json_encode(array("success" => true, "data" => $data));
} else {
  http_response_code(500);
  echo json_encode(array("success" => false));
}
