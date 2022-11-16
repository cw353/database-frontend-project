<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	//$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

	$colnames = [];
	$placeholders = [];
	$values = [];
	$types = '';
	foreach ($table->getColumns() as $col) {
		$colname = $col->getName();
		$val = $_GET[$colname];
		if (isset($val)) {
			array_push($colnames, 
			if ($val === '') {
				$val = null;
			}
		}
		$comparand = $_GET[$colname];
		$expr = "$colname = ?";
		array_push($insert_expr, $expr);
		array_push($insert_var, $comparand);
		$insert_types .= 's';
	}

	$query = 'update ' . $table->getName() . ' set ' . join(', ', $set_expr) . ' where ' . join(' and ', $filter_expr);

	echo $query;
	echo " - set_var: " . join(', ', $set_var);
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
