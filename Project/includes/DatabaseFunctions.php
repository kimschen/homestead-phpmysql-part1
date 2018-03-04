<?php

/*
-------------------------------------------------------------
| Dependency injection										|
-------------------------------------------------------------
| In practical terms, it's a method for making a single 	|
| variable available in multiple locations. 				|
-------------------------------------------------------------
*/

// Return total number of jokes
function totalJokes($pdo) {

	// Call the query function and pass it the empty $parameters array
	$query = query($pdo, 'SELECT COUNT(*) FROM `joke`');
	$row = $query->fetch();

	return $row[0];
}

// Get joke
function getJoke($pdo, $id) {

	// Create the array of $parameters for use in the query function
	$parameters = [':id' => $id];

	// Call the query function and provide the $parameters array
	$query = query($pdo, 'SELECT * FROM `joke` WHERE `id` = :id', $parameters);

	return $query->fetch();
}

// Bind and execute given parameters
function query($pdo, $sql, $parameters = []) {

	$query = $pdo->prepare($sql);
	$query->execute($parameters);

	return $query;
}

// Add joke
function insertJoke ($pdo, $fields) {

	$query = 'INSERT INTO `joke` (';

	foreach ($fields as $key => $value) {
		$query .= '`' . $key . '`,';
	}

	$query = rtrim($query, ',');
	$query .= ') VALUES (';

	foreach ($fields as $key => $value) {
		$query .= ':' . $key . ',';
	}

	$query = rtrim($query, ',');
	$query .= ')';

	$fields = processDates($fields);

	query($pdo, $query, $fields);
}

// Edit joke
function updateJoke($pdo, $fields) {

	$query = 'UPDATE `joke` SET';

    foreach ($fields as $key => $value) {
    	$query .= '`' . $key . '` = :' . $key . ',';
	}

	$query = rtrim($query, ',');
	$query .= ' WHERE `id` = :primaryKey';


	// Set the :primaryKey variable
	$fields['primaryKey'] = $fields['id'];

	$fields = processDates($fields);

	query($pdo, $query, $fields);

}

// Remove joke
function deleteJoke($pdo, $id) {

	$parameters = [':id' => $id];

	query($pdo,'DELETE FROM `joke`
				WHERE `id` = :id', $parameters);
}

function allJokes($pdo) {

	$jokes = query($pdo,'SELECT `joke`.`id`, `joketext`, `jokedate`, `name`, `email`
						FROM `joke`
						INNER JOIN `author`
						ON `authorid` = `author`.`id`');

	return $jokes->fetchAll();
}

function processDates($fields) {
	foreach ($fields as $key => $value) {

		if ($value instanceOf DateTime) {
			$fields[$key] = $value->format('Y-m-d');
		}
	}

	return $fields;

}

function allAuthors($pdo) {

	$authors = query($pdo, 'SELECT * FROM `author`');

	return $authors->fetchAll();
}

function deleteAuthor($pdo, $id) {

	$parameters = [':id' => $id];

	query($pdo,'DELETE FROM `author`
				WHERE `id` = :id', $parameters);

}

function insertAuthor($pdo, $fields) {

	$query = 'INSERT INTO `author` (';

	foreach ($fields as $key => $value) {
		$query .= '`' . $key . '`,';
	}

	$query = rtrim($query, ',');
	$query .= ') VALUES (';

	foreach ($fields as $key => $value) {
		$query .= ':' . $key . ',';
	}

	$query = rtrim($query, ',');

	$fields = processDates($fields);

	query($pdo, $query, $fields);

}

function findAll($pdo, $table) {

	$result = query($pdo, 'SELECT * FROM `' . $table . '`');

	return $result->fetchAll();

}

function delete($pdo, $table, $primaryKey, $id) {
	$parameters = [':id' => $id];

	query($pdo, 'DELETE FROM `' . $table . '` WHERE `' . $primaryKey . '` = :id', $parameters);
}

function insert($pdo, $table, $fields) {

	$query = 'INSERT INTO `' . $table . '` (';

	foreach ($fields as $key => $value) {
		$query .= '`' . $key . '`,';
	}

	$query = rtrim($query, ',');
	$query .= ') VALUES (';

	foreach ($fields as $key => $value) {
		$query .= ':' . $key . ',';
	}

	$query = rtrim($query, ',');

	$query .= ')';

	$fields = processDates($fields);

	query($pdo, $query, $fields);

}

function update($pdo, $table, $primaryKey, $fields) {

	$query = 'UPDATE `' . $table . '` SET';

	foreach ($fields as $key => $value) {
		$query .= '`' . $key . '` = :' .$key . ',';
	}

	$query = rtrim($query, ',');

	$query .= ' WHERE `' . $primaryKey . '` = :primaryKey';

	// Set the :primaryKey variable
	$fields['primaryKey'] = $fields['id'];

	$fields = processDates($fields);

	query($pdo, $query, $fields);

}

function findById($pdo, $table, $primaryKey, $value) {

	$query = 'SELECT * FROM `' . $table . '`
			  WHERE `' . $primaryKey . '` = :value';

	$parameters = [
		'value' => $value
	];

	$query = query($pdo, $query, $parameters);

	return $query->fetch();

}

function total($pdo, $table) {

	// Call the query function and pass it the empty $parameters array
	$query = query($pdo, 'SELECT COUNT(*) FROM `' . $table . '`');
	$row = $query->fetch();

	return $row[0];

}

function save($pdo, $table, $primaryKey, $record) {

	try {

		if ($record[$primaryKey] == '') {
				$record[$primaryKey] = null;
		}

		insert($pdo, $table, $record);

	}

	catch (PDOException $e) {

		update($pdo, $table, $primaryKey, $record);

	}

}












