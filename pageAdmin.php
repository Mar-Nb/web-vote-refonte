<?php
session_start();

// On empêche le changement de statut
if (empty($_SESSION["statut"]) || $_SESSION["statut"] != "admin") {
  header("Location: index.php");
}
$pdo = new PDO("sqlite:db/phpsqlite.db");
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
  <script src="js/config.js"></script>

  <title>Page d'administration</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Web Vote</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a id="accueil" class="nav-link" href="index.php">Accueil</a>
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

        <form class="d-flex" action="logout.php">
          <button class="btn btn-danger my-2 my-sm-0" type="submit">Déconnexion</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container pb-5">
    <div class="row align-items-center ps-3 py-3">
      <div class="col">
        <h2 class="text-decoration-underline py-2 py-sm-5">Bienvenue, administrateur <span class="fw-bold"><?= $_SESSION["pseudo"] ?></span></h2>

        <div class="row align-items-center">
          <div class="col">
            <p>Voici les moyennes des professeurs des différentes matières, de la meilleure à la moins bonne.</p>
          </div>

          <div class="col col-sm-2 py-2 py-sm-3 d-grid gap-2">
            <button type="button" id="export-pdf" class="btn btn-dark">Export PDF</button>
          </div>
        </div>

        <table id="info-prof" class="table table-dark table-striped">
          <thead>
            <th scope="col">#</th>
            <th scope="col">Moyenne</th>
            <th scope="col">Nom</th>
            <th scope="col">Matière</th>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT ROUND(AVG(value), 2) as moy_vote, name, class FROM users, vote WHERE status = 'prof' AND users.id = vote.idProf GROUP BY idProf ORDER BY moy_vote DESC";
            $query = $pdo->query($sql);
            $lignes = $query->fetchAll(PDO::FETCH_ASSOC);

            $cpt = 1;
            foreach ($lignes as $key => $ligne) {
              $tr = "<tr>";
              $tr .= "<td>" . $cpt . "</td>";
              $tr .= "<td>" . $ligne["moy_vote"] . "<span class='fw-bold'> / 5 </span></td>";
              $tr .= "<td>" . $ligne["name"] . "</td>";
              $tr .= "<td>" . $ligne["class"] . "</td>";
              $tr .= "</tr>";

              $cpt++;
              echo $tr;
            }
            ?>
          </tbody>
        </table>

        <!-- Les graphiques par profs -->
        <div class="row align-items-center">
          <div class="col">
            <p>Voici les graphiques représentant les votes pour un professeur donné.</p>
          </div>

          <div class="col col-sm-2 py-2 py-sm-3 d-grid gap-2">
            <button type="button" id="export-images" class="btn btn-dark">Export images</button>
          </div>
        </div>

        <div class="row align-items-center justify-content-center">
          <?php
          $sql = "SELECT id, name, class FROM users WHERE status = 'prof'";
          $query = $pdo->query($sql);
          $profs = $query->fetchAll(PDO::FETCH_ASSOC);

          $data = array("Non voté" => 0, "Très mécontent" => 0, "Mécontent" => 0, "Moyen" => 0, "Satisfait" => 0, "Très satisfait" => 0);
          $sql2 = "SELECT value, COUNT(value) as nbValue FROM vote WHERE idProf = ? GROUP BY value";
          $stmt = $pdo->prepare($sql2);
          $stmt->bindValue(1, $profs[0]["id"], PDO::PARAM_INT);
          $stmt->execute();
          $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

          <select id="select-prof" class="form-select" aria-label="Select pour les profs">
            <?php
            foreach ($profs as $key => $prof) {
              echo "<option value='" . $prof["id"] . "'>" . $prof["name"] . " (" . $prof["class"] . ")</option>";
            }
            ?>
          </select>

          <div class="col pt-3">
            <canvas id="graph-repartition-donut" class="mx-auto" width="350" height="275"></canvas>

            <script id="script-donut">
              var ctxDonut = document.getElementById("graph-repartition-donut").getContext("2d");
              confChart.type = "doughnut";
              confChart.data.datasets[0].data = [<?= $data["Non voté"] ?>, <?= $data["Très mécontent"] ?>, <?= $data["Mécontent"] ?>, <?= $data["Moyen"] ?>, <?= $data["Satisfait"] ?>, <?= $data["Très satisfait"] ?>];
              var myChartDonut = new Chart(ctxDonut, confChart);
            </script>

            <input type="hidden" id="image-donut" value="">

            <script>
              document.getElementById("image-donut").value = myChartDonut.toBase64Image();
            </script>
          </div>

          <div class="col pt-3">
            <canvas id="graph-repartition-hist" class="mx-auto" width="350" height="275"></canvas>

            <script id="script-hist">
              var ctxHist = document.getElementById("graph-repartition-hist").getContext("2d");
              confChart.type = "bar";
              confChart.data.datasets[0].data = [<?= $data["Non voté"] ?>, <?= $data["Très mécontent"] ?>, <?= $data["Mécontent"] ?>, <?= $data["Moyen"] ?>, <?= $data["Satisfait"] ?>, <?= $data["Très satisfait"] ?>];
              var myChartHist = new Chart(ctxHist, confChart);
            </script>

            <input type="hidden" id="image-hist" value="">

            <script>
              document.getElementById("image-hist").value = myChartHist.toBase64Image();
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer mt-auto py-3 border-top">
    <p class="text-center text-muted">Refonte du projet <a href="https://github.com/Mar-Nb/web-vote">Web Vote</a></p>
    <p class="text-center text-muted">&copy; <?= date("Y") ?> Web Vote</p>
  </footer>

  <script src="js/script.js"></script>
</body>

</html>