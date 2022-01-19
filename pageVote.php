<?php
session_start();
require "vendor/autoload.php";

use App\SQLiteConnection;

// On empêche le changement de statut via l'URL
if ($_SESSION["statut"] != $_GET["statut"]) {
  header("Location: pageVote.php?statut=" . $_SESSION["statut"]);
} else if (empty($_SESSION["statut"])) {
  header("Location: index.php");
}

$pdo = (new SQLiteConnection())->connect();
?>

<!DOCTYPE html>
<html lang="fr" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/sandstone/bootstrap.min.css" integrity="sha256-zWAnZkKmT2MYxdCMp506rQtnA9oE2w0/K/WVU7V08zw=" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js" integrity="sha256-Y26AMvaIfrZ1EQU49pf6H4QzVTrOI8m9wQYKkftBt4s=" crossorigin="anonymous"></script>

  <title>Votre page de vote</title>
</head>

<body class="d-flex flex-column h-100">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="/index.php">Web Vote</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a id="accueil" class="nav-link" href="/index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a id="eleve" class="nav-link" href="pageConnexion.php?statut=eleve">Élève</a>
          </li>
          <li class="nav-item">
            <a id="prof" class="nav-link" href="pageConnexion.php?statut=prof">Professeur</a>
          </li>
          <li class="nav-item">
            <a id="admin" class="nav-link" href="pageConnexion.php?statut=admin">Administrateur</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container pb-5">
    <div class="row align-items-center ps-3 py-3">
      <div class="col">

        <?php
        // Si un élève est connecté...
        if ($_SESSION["statut"] == "eleve") :
        ?>

          <h2 class="text-decoration-underline py-5">Voici le formulaire de vote de <span class="fw-bold"><?= $_SESSION["pseudo"] ?></span></h2>
          <form method="post">
            <table class="table table-striped align-middle">
              <thead>
                <tr>
                  <th scope="col">Professeur et matière</th>
                  <th scope="col">Note</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Récupération des profs
                $sql = "SELECT name, class, id FROM users WHERE status = 'prof'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $profs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                // Récupération des (éventuelles) votes existants de l'utilisateur
                $sql = "SELECT * FROM vote WHERE idEleve = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(1, $_SESSION["idUser"], \PDO::PARAM_INT); // L'ID est un INT
                $stmt->execute();
                $votes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($profs as $key => $prof) :
                  $vote = 0;
                  if ($votes) {
                    foreach ($votes as $k => $v) {
                      if ($v["idProf"] == $prof["id"]) {
                        $vote = $v["value"];
                        break;
                      }
                    }
                  }
                ?>
                  <tr id="prof-v<?= $vote ?>" class="table-primary">
                    <th scope="row"><?= $prof["class"] ?> <br> <span class="small text-muted" style="font-variant: small-caps;"><?= $prof["name"] ?></span></th>
                    <td>
                      <div class="form-group">
                        <select class="form-select" name="vote-<?= $prof["id"] ?>">
                          <option value="0" <?= $vote != 0 ? "" : "selected" ?>>Non voté</option>
                          <option value="1" <?= $vote != 1 ? "" : "selected" ?>>Très mécontent</option>
                          <option value="2" <?= $vote != 2 ? "" : "selected" ?>>Mécontent</option>
                          <option value="3" <?= $vote != 3 ? "" : "selected" ?>>Moyen</option>
                          <option value="4" <?= $vote != 4 ? "" : "selected" ?>>Satisfait</option>
                          <option value="5" <?= $vote != 5 ? "" : "selected" ?>>Très satisfait</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <div class="d-grid gap-2 col-6 mx-auto mt-5">
              <button class="btn btn-lg btn-primary" type="button" id="btnVote">Voter</button>
            </div>
          </form>

        <?php
        // Si un professeur se connecte...
        elseif ($_SESSION["statut"] == "prof") :
          $data = array("Non voté" => 0, "Très mécontent" => 0, "Mécontent" => 0, "Moyen" => 0, "Satisfait" => 0, "Très satisfait" => 0);

          $sql = "SELECT COUNT(*) FROM vote WHERE idProf = ?";
          $stmt = $pdo->prepare($sql);
          $stmt->bindValue(1, $_SESSION["idUser"], \PDO::PARAM_INT); // L'ID est un INT
          $stmt->execute();
          $nbVote = $stmt->fetchColumn();

          $sql2 = "SELECT value, COUNT(value) as nbValue FROM vote WHERE idProf = ? GROUP BY value";
          $stmt = $pdo->prepare($sql2);
          $stmt->bindValue(1, $_SESSION["idUser"], \PDO::PARAM_INT);
          $stmt->execute();
          $lignes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

          foreach ($lignes as $key => $ligne) {
            switch ($ligne["value"]) {
              case 0:
                $data["Non voté"] = $ligne["nbValue"];
                break;
              case 1:
                $data["Très mécontent"] = $ligne["nbValue"];
                break;
              case 2:
                $data["Mécontent"] = $ligne["nbValue"];
                break;
              case 3:
                $data["Moyen"] = $ligne["nbValue"];
                break;
              case 4:
                $data["Satisfait"] = $ligne["nbValue"];
                break;
              case 5:
                $data["Très satisfait"] = $ligne["nbValue"];
                break;

              default:
                print_r($data);
                die();
            }
          }
        ?>

          <h2 class="text-decoration-underline py-5">
            Visualisation des votes de <span class="fw-bold"><?= $_SESSION["pseudo"] ?></span>
          </h2>
          <h3 class="text-muted">Nombre de votes : <?= $nbVote ?></h3>

          <canvas id="graph-repartition" class="mx-auto" width="350" height="275"></canvas>
          <script>
            const ctx = document.getElementById("graph-repartition").getContext("2d");
            const myChart = new Chart(ctx, {
              type: "doughnut",
              data: {
                labels: ["Non voté", "Très mécontent", "Mécontent", "Moyen", "Satisfait", "Très satisfait"],
                datasets: [{
                  label: 'Nombre de votes',
                  data: [<?= $data["Non voté"] ?>, <?= $data["Très mécontent"] ?>, <?= $data["Mécontent"] ?>, <?= $data["Moyen"] ?>, <?= $data["Satisfait"] ?>, <?= $data["Très satisfait"] ?>],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                  ],
                  borderWidth: 1,
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          </script>
        <?php endif; ?>
      </div>
    </div>

    <!-- Toast concernant le vote -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="voteToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong id="toastTitle" class="me-auto"></strong>
          <small>À l'instant</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div id="toastBody" class="toast-body text-white"></div>
      </div>
    </div>

    <footer class="footer mt-auto py-3 border-top">
      <p class="text-center text-muted">Refonte du projet <a href="https://github.com/Mar-Nb/web-vote">Web Vote</a></p>
      <p class="text-center text-muted">&copy; <?= date("Y") ?> Web Vote</p>
    </footer>

    <script src="js/script.js"></script>
</body>

</html>