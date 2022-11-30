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
	foreach ($table->getPrimaryKeys() as $pk) {
		$comparand = $_GET[$pk];
		$expr = "$pk = ?";
		array_push($filter_expr, $expr);
		array_push($param_var, $comparand);
		$param_types .= 's';
	}

	$query = 'delete from ' . $table->getName() . ' where ' . join(' and ', $filter_expr);

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
		<title>Delete Record Results</title>
		<link rel="stylesheet" href="stylesheet.css"/>
	</head>

	<body>
    <?php
      if ($num_affected < 0) {
        echo '<p>An error occurred and the operation could not be completed. ' . $mysqli->error . '</p>';
      } else {
        echo "<p>The operation has succeeded. $num_affected record";
        echo $num_affected === 1 ? ' has' : 's have';
        echo ' been deleted. Any other records that reference this record have been deleted as necessary.</p>';
      }
    ?>
		<form method="get" action="viewRecords.php">
			<input type="hidden" name="table" value="<?php echo sanitizeHtml($tablename); ?>">
			<button type="submit">View Modified Table</button>
		</form>
		<form method="post" action="index.php">
			<button type="submit">Return to Home</button>
		</form>

	</body>
</html>

<?php
	$mysqli && $mysqli->close();
?>
