<?php
$pdo = new PDO("sqlite:db/phpsqlite.db");

if (isset($_POST["ajax"])) {
  switch ($_POST["ajax"]) {
    case "graph":
      $data = array("Non voté" => 0, "Très mécontent" => 0, "Mécontent" => 0, "Moyen" => 0, "Satisfait" => 0, "Très satisfait" => 0);
      $sql2 = "SELECT value, COUNT(value) as nbValue FROM vote WHERE idProf = ? GROUP BY value";
      $stmt = $pdo->prepare($sql2);
      $stmt->bindValue(1, $_POST["idProf"], PDO::PARAM_INT);
      $stmt->execute();
      $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
      foreach ($lignes as $key => $ligne) {
        switch ($ligne["value"]) {
          case 0:
            $data["Non voté"] = intval($ligne["nbValue"]);
            break;
          case 1:
            $data["Très mécontent"] = intval($ligne["nbValue"]);
            break;
          case 2:
            $data["Mécontent"] = intval($ligne["nbValue"]);
            break;
          case 3:
            $data["Moyen"] = intval($ligne["nbValue"]);
            break;
          case 4:
            $data["Satisfait"] = intval($ligne["nbValue"]);
            break;
          case 5:
            $data["Très satisfait"] = intval($ligne["nbValue"]);
            break;
    
          default:
            print_r($data);
            die();
        }
      }
    
      echo json_encode(array("data" => array($data["Non voté"], $data["Très mécontent"], $data["Mécontent"], $data["Moyen"], $data["Satisfait"], $data["Très satisfait"])));
      break;

    case "new-user":
      try {
        $pdo->beginTransaction();

        if ($_POST["statut"] == "admin") {
          $sql = "INSERT INTO superusers (name, mdp) VALUES (?, ?)";
        } else {
          $sql = "INSERT INTO users (name, mdp, status, class) VALUES (?, ?, ?, ?)";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $_POST["nom"]);
        $stmt->bindValue(2, md5($_POST["mdp"]));

        if ($_POST["statut"] != "admin") {
          $stmt->bindValue(3, $_POST["statut"]);
          $stmt->bindValue(4, $_POST["statut"] == "prof" ? $_POST["classe"] : null);
        }

        $stmt->execute();

        if ($stmt->rowCount() != 1) { throw new Exception("L'utilisateur n'a pas pu être inséré.", 1); }
        $pdo->commit();
        echo json_encode(array("success" => true, "nom" => $_POST["nom"], "mdp" => $_POST["mdp"], "statut" => $_POST["statut"], "classe" => $_POST["classe"]));  
      } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(array("error" => $e->getMessage(), "success" => false, "nom" => $_POST["nom"], "mdp" => $_POST["mdp"], "statut" => $_POST["statut"], "classe" => $_POST["classe"]));
      }
      break;
    
    default:
      # code...
      break;
  }
}
