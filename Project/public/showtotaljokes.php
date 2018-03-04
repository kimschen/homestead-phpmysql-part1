<?php

// Include the database connection
include_once __DIR__ . '/../includes/DatabaseConnection.php';

// Include the database function
include_once __DIR__ . '/../includes/DatabaseFunctions.php';

// Display total number of jokes by injecting the argument of totalJokes function
echo totalJokes($pdo);
