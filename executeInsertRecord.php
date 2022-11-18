<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

  $colnames = [];
  $insert_values = [];
  $param_values = [];
  $param_types = '';
  foreach ($table->getColumns() as $col) {
		if ($col->isWritable()) {
	    $colname = $col->getName();
    	$val = $_GET[$colname];
			array_push($colnames, $colname);
			if ($val === '') {
				array_push($insert_values, 'NULL');
			} else {
				array_push($insert_values, '?');
				array_push($param_values, $val);
 	   		$param_types .= 's';
			}
 		}
	}

	$query = 'insert into ' . $table->getName() . ' (' . join(', ', $colnames) . ') values (' . join(', ', $insert_values) . ')';

	echo $query;
	echo ' - ' . join(', ', $param_values);

	$num_affected = executeQueryGetAffected($mysqli, $query, $param_values, $param_types);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Execute Modify Record</title>
	</head>

	<body>
		<?php
			if ($num_affected < 0) {
				echo "<p>An error occurred and the operation could not be completed.</p>";
				echo "error: " . $mysqli->errno;
			} else {
				echo "<p>The operation has succeeded. $num_affected record";
				echo $num_affected === 1 ? ' has' : 's have';
				echo ' been inserted.</p>';
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
