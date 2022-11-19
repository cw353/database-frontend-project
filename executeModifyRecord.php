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
			if ($comparand === '') {
				$expr = "$colname = NULL";
				array_push($set_expr, $expr);
			} else {
				$expr = "$colname = ?";
				array_push($set_expr, $expr);
				array_push($set_var, $comparand);
				$set_types .= 's';
			}
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

	$param_var = array_merge($set_var, $filter_var);
	$param_types = $set_types.$filter_types;

  // if using variable parameters, use prepared statement
  if (sizeof($param_var) > 0) {
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param($param_types, ...$param_var);
		$num_affected = $stmt->execute() ? $stmt->affected_rows : -1;
  } else {
    // otherwise, use regular query
    $num_affected = $mysqli->query($query) ? $mysqli->affected_rows : -1;
  }

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Modify Record Results</title>
		<link rel="stylesheet" href="stylesheet.css"/>
	</head>

	<body>
		<?php
			if ($num_affected < 0) {
				echo '<p>An error occurred and the operation could not be completed. ' . $mysqli->error . '</p>';
			} else {
				echo "<p>The operation has succeeded. $num_affected record";
				echo $num_affected === 1 ? ' has' : 's have';
				echo ' been modified. Any other records that reference this record have been updated as necessary.</p>';
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
	!empty($stmt) && $stmt->close();
	!empty($mysqli) && $mysqli->close();
?>
