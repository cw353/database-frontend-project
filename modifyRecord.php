<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

	$filter_expr = []; // filter expressions
	$param_var = []; // comparands to bind for filters
	$param_types = ''; // types of variables to bind for filters
	$primaryKeys = $table->getPrimaryKeys();
	foreach ($primaryKeys as $pk) {
		// add to filters only if comparand was provided
		$comparand = $_GET[$pk];
		$expr = "$pk = ?";
		array_push($filter_expr, $expr);
		array_push($param_var, $comparand);
		$param_types .= 's';
	}

	$query = formulateSelectQuery($table, $filter_expr);

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
		<title>Modify Record</title>
	</head>

	<body>
		<?php if (!$result): ?>
     	<p>An error occurred while trying to process your query. <?php $mysqli->error ?></p>;
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
	!empty($result) && $result->free();
	!empty($stmt) && $stmt->close();
	!empty($mysqli) && $mysqli->close();
?>
