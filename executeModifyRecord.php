<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

	$set_expr = [];
	$set_var = [];
	$set_types = '';
	foreach ($table->getColumns() as $col) {
		if ($col->isWritable()) {
			$colname = $col->getName();
			$comparand = $_GET[$colname];
			$expr = "$colname = ?";
			array_push($set_expr, $expr);
			array_push($set_var, $comparand);
			$set_types .= 's';
		}
	}
	$filter_expr = []; // filter expressions
	$filter_var = []; // comparands to bind for filters
	$filter_types = ''; // types of variables to bind for filters
	foreach ($table->getPrimaryKeys() as $pk) {
		$comparand = $_GET[$pk.'_old'];
		$expr = "$pk = ?";
		array_push($filter_expr, $expr);
		array_push($filter_var, $comparand);
		$filter_types .= 's';
	}

	$query = 'update ' . $table->getName() . ' set ' . join(', ', $set_expr) . ' where ' . join(' and ', $filter_expr);

	echo $query;
	//echo " - set_var: " . join(', ', $set_var);
	//echo " - filter_var: " . join(', ', $filter_var);
	echo ' - ' . join(', ', array_merge($set_var, $filter_var));
	echo ' - ' . $set_types.$filter_types;

	$result = getQueryResult($mysqli, $query, array_merge($set_var, $filter_var), $set_types.$filter_types);

	if (!empty($result)) {
		echo var_dump($result);
	} else {
		echo " - result is empty";
	}
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
	$result && $result->free();
	$mysqli && $mysqli->close();
?>
