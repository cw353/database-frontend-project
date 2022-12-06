<!-- View (optionally filtered) records for the selected table -->
<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';
	require_once 'operators.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

	$filter_expr = []; // filter expressions
	$param_var = []; // comparands to bind for filters
	$param_types = ''; // types of variables to bind for filters
	$joinop = (isset($_GET['joinop']) && $_GET['joinop'] === 'o') ? 'or' : 'and'; // operator to use when joining SQL clauses ('and' or 'or')
	foreach ($table->getColumns() as $col) {
		$colname = $col->getName();
		// add the column to filters only if a comparand was provided
		if (!empty($_GET[$colname])) {
			// operator info (if none provided, assume default case 'e')
			$op = isset($_GET[$colname.'_op']) ? $_GET[$colname.'_op'] : 'e';
			// comparand (including wildcard pattern matching characters if required by the selected operator)
			$comparand = (in_array($op, ['c', 'nc', 'end', 'nend']) ? '%' : '')
				. $_GET[$colname]
				. (in_array($op, ['c', 'nc', 'start', 'nstart'])  ? '%' : '');
			$sql_op = $operators[$op]['op']; // sql operator
			$attr = $col->getSqlExpression(); // attribute
			// build expression from attribute and operator with ? placeholder for var
			$expr = "$attr $sql_op ?";
			array_push($filter_expr, $expr);
			array_push($param_var, $comparand);
			$param_types .= 's';
		}
	}

	$query = formulateSelectQuery($table, $filter_expr, $joinop);

	// if using variable parameters, use prepared statement
	if (sizeof($param_var) > 0) {
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param($param_types, ...$param_var);
		$result = $stmt->execute() ? $stmt->get_result() : null;
	} else {
		// otherwise, use regular query
		$result = $mysqli->query($query);
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Records</title>
		<link rel="stylesheet" href="stylesheet.css"/>
	</head>

	<body>
		<?php
    	if (!$result) {
     		echo '<p>An error occurred while trying to process your query.</p>';
    	} else {
				echo getResultTable($result, $table);
			}
		?>

		<br>

		<form method="post" action="index.php">
			<button type="submit">Return to Home</button>
		</form>


	</body>
</html>

<?php
	!empty($result) && $result->free();
	!empty($stmt) && $stmt->close();
	!empty($mysqli) && $mysqli->close();
?>
