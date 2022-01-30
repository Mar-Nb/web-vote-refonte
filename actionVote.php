<?php
session_start();

$pdo = new PDO("sqlite:db/phpsqlite");
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
  $stmt->bindValue(1, $_SESSION["idUser"], PDO::PARAM_INT);
  $stmt->execute();

  // Insertion des (nouvelles) données de vote de l'utilisateur
  try {
    $pdo->beginTransaction();

    foreach ($_POST as $key => $v) {
      $sql = "INSERT INTO vote(idEleve, idProf, value) VALUES (" . $_SESSION["idUser"] . ", :idProf, :value)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(explode("-", $key)[1], $v));
    }
    
    $pdo->commit();
    http_response_code(200);
    echo json_encode(array("success" => true, "data" => $data));
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("success" => false, "error" => $e->getMessage()));
  }
} else {
  http_response_code(500);
  echo json_encode(array("success" => false));
}
