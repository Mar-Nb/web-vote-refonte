<?php

use App\SQLiteConnection;

try {
  $pdo = (new SQLiteConnection())->connect();
} catch (\Throwable $th) {
  echo 'La connexion à la base de données SQLite a échoué !';
}
