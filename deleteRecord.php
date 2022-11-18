<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
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

	$num_affected = executeQueryGetAffected($mysqli, $query, $filter_var, $filter_types);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Delete Record</title>
	</head>

	<body>
		<?php
			if ($num_affected < 0) {
				echo "<p>An error occurred and the operation could not be completed.</p>";
			} else {
				echo "<p>The operation has succeeded. $num_affected record";
				echo $num_affected === 1 ? ' has' : 's have';
				echo ' been deleted. Any other records that reference this record have also been deleted as necessary.</p>';
			}
		?>
		<form method="get" action="viewFilteredRecords.php">
			<input type="hidden" name="table" value="<?php echo sanitizeHtml($tablename); ?>">
			<button type="submit">View Results</button>
		</form>
		<form method="post" action="chooseTable.php">
			<button type="submit">Filter Records for Another Table</button>
		</form>

	</body>
</html>

<?php
	$mysqli && $mysqli->close();
?>
