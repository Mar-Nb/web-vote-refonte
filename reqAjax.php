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
      echo json_encode(array("error" => "Non disponible en démo"));
      break;

    case "verif-user":
      $sql = "SELECT COUNT(name) FROM users WHERE name = ? GROUP BY name";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $_POST["name"]);
      $stmt->execute();
      $existe = $stmt->fetchColumn() != 0;

      if ($existe) {
        echo json_encode(array("userExiste" => true));
      } else {
        echo json_encode(array("userExiste" => false));
      }

      break;
    
    case "delete":
      echo json_encode(array("error" => "Non disponible en démo"));
      break;

    default:
      # code...
      break;
  }
}
