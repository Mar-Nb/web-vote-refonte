<?php require "vendor/autoload.php"; ?>

<!DOCTYPE html>
<html lang="fr" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/sandstone/bootstrap.min.css" integrity="sha256-zWAnZkKmT2MYxdCMp506rQtnA9oE2w0/K/WVU7V08zw=" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <title>Web Vote</title>
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
            <a class="nav-link active" href="/index.php">Accueil
              <span class="visually-hidden">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/php/pageConnexion.php?statut=eleve">Élève</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/php/pageConnexion.php?statut=prof">Professeur</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/php/pageConnexion.php?statut=admin">Administrateur</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container pb-5 h-100">
    <div class="row align-items-center ps-3 py-3">
      <div class="col">
        <h2 class="text-decoration-underline py-5">Bienvenue, utilisateur !</h2>
        <div class="accordion" id="accordionPanelsStayOpenExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                Élève
              </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
              <div class="accordion-body">
                <h4 class="card-title">Votez de manière anonyme !</h4>
                <p class="card-text">Ce site vous permet d'évaluer 5 Unités d'Enseignement (UE) faisant partie de vos matières à l'IUT.
                  Cliquez sur <kbd>Élève</kbd>, connectez-vous avec vos identifiants, et votez sur la page à laquelle vous accédez
                  après votre connexion. Vos votes sont <span class="fw-bold">anonymes</span>, un professeur ne pourra pas remonter à votre nom en
                  consultant les votes rendus.
                </p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                Professeur
              </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
              <div class="accordion-body">
                <h4 class="card-title">Visualiser les votes sur votre matière !</h4>
                <p class="card-text">Il vous est possible de vous connecter afin de consulter les votes déjà envoyés par les élèves. La moyenne
                  et l'écart-type sont également calculés sur la page. Cliquez sur <kbd>Professeur</kbd>, connectez-vous avec vos identifiants,
                  et consultez les votes pour votre matière. Il ne vous sera pas possible de consulter les identifiants correspondant à chaque vote, ni la liste des élèves n'ayant pas voté.
                </p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                Administrateur
              </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
              <div class="accordion-body">
                <h4 class="card-title">Contrôlez les votes envoyés !</h4>
                <p class="card-text">Il vous est possible, en tant qu'Administrateur, de consulter l'ensemble des votes envoyés par les élèves.
                  Vous n'avez cependant pas accès aux identifiants correspondant aux votes. Cliquez sur <kbd>Administrateur</kbd>, connectez-vous
                  avec vos identifiants, et consultez l'ensemble des votes envoyés pour les 5 UE. Vous pouvez également exporter
                  les résultats au format PDF.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer mt-auto py-3 border-top">
    <p class="text-center text-muted">Refonte du projet <a href="https://github.com/Mar-Nb/web-vote">Web Vote</a></p>
    <p class="text-center text-muted">&copy; <?= date("Y") ?> Web Vote</p>
  </footer>
</body>

</html>