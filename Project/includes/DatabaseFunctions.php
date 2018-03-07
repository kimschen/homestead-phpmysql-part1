<?php

// Bind and execute given parameters
function query($pdo, $sql, $parameters = []) {

	$query = $pdo->prepare($sql);
	$query->execute($parameters);

	return $query;
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

		// If value has the same type as DateTime class, set format to Y-m-d
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

// Return total number of jokes
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












