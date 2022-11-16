<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	//$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

	$filter_expr = []; // filter expressions
	$filter_var = []; // comparands to bind for filters
	$filter_types = ''; // types of variables to bind for filters
	foreach ($table->getPrimaryKeys() as $pk) {
		$comparand = $_GET[$pk];
		$expr = "$pk = ?";
		array_push($filter_expr, $expr);
		array_push($filter_var, $comparand);
		$filter_types .= 's';
	}

	$query = 'delete from ' . $table->getName() . ' where ' . join(' and ', $filter_expr);

	echo $query;
	echo " - filter_var: " . join(', ', $filter_var);

	//$result = getQueryResult($mysqli, $query, $filter_var, $filter_types);*/
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Modify Record</title>
	</head>

	<body>

		<br>
		<br>
		<form method="post" action="chooseTable.php">
			<button type="submit">Filter Records for Another Table</button>
		</form>

	</body>
</html>

<?php
	/*$result && $result->free();
	$mysqli && $mysqli->close();*/
?>
