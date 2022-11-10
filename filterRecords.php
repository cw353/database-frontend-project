<?php
	require_once 'helperFunctions.php';
	require_once 'dbClasses.php';
	require_once 'tables.php';

	$table_to_query = $_GET['table_to_query'];
	$table = $tables[$table_to_query];

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Filter Records</title>
	</head>

	<body>
		<header><?php echo 'Filter Records for ' . $table->getLabel() ?></header>
		<form method="get" action="viewFilteredRecords.php">
			<input type="hidden" name="table_to_query" value="<?php echo $table_to_query; ?>">
			<?php
				foreach ($table->getColumns() as $col) {
					$colname = $col->getName();
					echo "<label>$colname is ";
					echo "<input type='text' name='$colname'>";
					echo '</label><br>';
				}
			?>
			<button type="submit">Apply Filter</button>
		</form>


	</body>
</html>
