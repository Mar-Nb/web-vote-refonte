<?php
session_start();
unset($_SESSION["pseudo"]);
unset($_SESSION["mdp"]);
unset($_SESSION["statut"]);
session_destroy();

header("Location: index.php");