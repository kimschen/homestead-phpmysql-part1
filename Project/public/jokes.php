<?php

try {

	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../includes/DatabaseFunctions.php';

	// Display data from database
	$result = findAll($pdo, 'joke');

	$jokes = [];
	foreach ($result as $joke) {
		$author = findById($pdo, 'author', 'id', $joke['authorid']);

		$jokes[] = [
			'id' => $joke['id'],
			'joketext' => $joke['joketext'],
			'jokedate' => $joke['jokedate'],
			'name' => $author['name'],
			'email' => $author['email']
		];
	}

	$title = 'Joke List';

	$totalJokes = total($pdo, 'joke');

	ob_start();

	include __DIR__ . '/../templates/jokes.html.php';

	$output = ob_get_clean();

} catch (PDOException $e) {

	// Throw a detailed error message display with path and which line of code
	$output = 'Unable to connect to the database server: ' .

	$e->getMessage() . 'in ' .
	$e->getFile() . ':' .
	$e->getLine();

}

	include __DIR__ . '/../templates/layout.html.php';
