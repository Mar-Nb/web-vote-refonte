<?php
$statut = "";

switch ($_GET["statut"]) {
  case 'eleve':
    $statut = "Élève";
    break;
  
  case 'prof':
    $statut = "Professeur";
    break;
  
  case 'admin':
    $statut = "Administrateur";
    break;

  default:
    header("Location: /index.php");
    break;
}

$_POST["verif-statut"] = $_GET["statut"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/sandstone/bootstrap.min.css" integrity="sha256-zWAnZkKmT2MYxdCMp506rQtnA9oE2w0/K/WVU7V08zw=" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <title>Connexion : <?= $statut ?></title>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="/index.php">Web Vote</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a id="accueil" class="nav-link active" href="/index.php">Accueil
              <span class="visually-hidden">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a id="eleve" class="nav-link" href="/php/pageConnexion.php?statut=eleve">Élève</a>
          </li>
          <li class="nav-item">
            <a id="prof" class="nav-link" href="/php/pageConnexion.php?statut=prof">Professeur</a>
          </li>
          <li class="nav-item">
            <a id="admin" class="nav-link" href="/php/pageConnexion.php?statut=admin">Administrateur</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container m-4">
    <div class="row justify-content-center">
      <div class="col-4">
        <form action="/php/actionConnexion.php?statut=<?= $_GET["statut"] ?>" method="POST">
          <fieldset>
            <legend>Connexion : <?= $statut ?></legend>
            <div class="form-group">
              <div class="form-floating">
                <input type="text" class="form-control" id="pseudo">
                <label for="pseudo">Pseudo</label>
              </div>

              <div class="form-floating">
                <input type="password" class="form-control" id="mdp">
                <label for="mdp">Mot de passe</label>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

  <script src="/js/script.js"></script>
</body>
</html>