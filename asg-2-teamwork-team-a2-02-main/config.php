<?php

return [
  'database' => [
    'name' => $_ENV['DATABASE_NAME'] ?? 'movies',
    'username' => $_ENV['DATABASE_USER'] ?? 'root',
    'password' => $_ENV['DATABASE_PW'] ?? '',
    'connection' => $_ENV['DATABASE_CONNECTION'] ?? 'mysql:host=localhost'
  ]
];
