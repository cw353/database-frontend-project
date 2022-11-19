<rphp header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

  $colnames = [];
  $insert_values = [];
  $param_var = [];
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
				array_push($param_var, $val);
 	   		$param_types .= 's';
			}
 		}
	}

	$query = 'insert into ' . $table->getName() . ' (' . join(', ', $colnames) . ') values (' . join(', ', $insert_values) . ')';

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
		<title>Insert Record Results</title>
	</head>

	<body>
    <?php
      if ($num_affected < 0) {
        echo '<p>An error occurred and the operation could not be completed. ' . $mysqli->error . '</p>';
      } else {
        echo "<p>The operation has succeeded. $num_affected record";
        echo $num_affected === 1 ? ' has' : 's have';
        echo ' been modified.</p>';
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
