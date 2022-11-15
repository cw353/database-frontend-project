<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';
	require_once 'operators.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

	$filter_expr = []; // filter expressions
	$filter_var = []; // comparands to bind for filters
	$filter_types = ''; // types of variables to bind for filters
	$primaryKeys = $table->getPrimaryKeys();
	foreach ($primaryKeys as $pk) {
		// add to filters only if comparand was provided
		$comparand = $_GET[$pk];
		$expr = "$pk = ?";
		array_push($filter_expr, $expr);
		array_push($filter_var, $comparand);
		$filter_types .= 's';
	}

	$query = formulateSelectQuery($table, $filter_expr);
	$result = getQueryResult($mysqli, $query, $filter_var, $filter_types);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Modify Record</title>
	</head>

	<body>
		<?php if (!$result): ?>
			<p>An error occurred while trying to process your query.</p>
		<?php elseif ($result && $result->num_rows < 1): ?>
			<p>No records matching your query were found.</p>
		<?php else: ?>
			<form method="get" action="executeModifyRecord.php">
				<input type="hidden" name="table" value="<?php echo sanitizeHtml($tablename); ?>">
				<?php
					echo getModifiableTable($table, $mysqli, $result->fetch_assoc());
				?>
				<button type="submit">Apply Changes</button>
			</form>
		<?php endif; ?>



	</body>
</html>

<?php
	$result && $result->free();
	$mysqli && $mysqli->close();
?>
