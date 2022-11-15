<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';
	require_once 'operators.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$table_to_query = $_GET['table_to_query'];
	$table = $tables[$table_to_query];

	$filter_expr = []; // filter expressions
	$filter_var = []; // comparands to bind for filters
	$filter_types = ''; // types of variables to bind for filters
	foreach ($table->getColumns() as $col) {
		$colname = $col->getName();
		// add to filters only if comparand was provided
		if (!empty($_GET[$colname])) {
			// operator info (if none provided, assume default case 'e')
			$op = isset($_GET[$colname.'_op']) ? $_GET[$colname.'_op'] : 'e';
			// comparand
			$comparand = (($op === 'c' or $op === 'end') ? '%' : '')
				. $_GET[$colname]
				. (($op === 'c' or $op === 'start') ? '%' : '');
			$sql_op = $operators[$op]['op']; // sql operator
			$attr = $col->getSqlExpression(); // attribute
			// build expression from attribute and operator with ? placeholder for var
			$expr = "$attr $sql_op ?";
			array_push($filter_expr, $expr);
			array_push($filter_var, $comparand);
			$filter_types .= 's';
		}
	}

	$query = formulateSelectQuery($table, $filter_expr);
	$result = getQueryResult($mysqli, $query, $filter_var, $filter_types);

?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Filtered Records</title>
	</head>

	<body>

		<?php
			echo getResultTable($result, $table);
		?>

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
