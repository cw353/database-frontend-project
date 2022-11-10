<?php
	require_once 'helperFunctions.php';
	require_once 'dbClasses.php';
	require_once 'tables.php';

	$con = new mysqli('localhost', 'root', 'root', 'project');
	$table_to_query = $_GET['table_to_query'];
	$table = $tables[$table_to_query];
	$filters = [];
	foreach ($table->getColumns() as $col) {
		$colname = $col->getName();
		if (!empty($_GET[$colname])) {
			array_push($filters, $colname . "='" . $_GET[$colname] . "'");
		}
	}
	$query = formulateSelectQuery($table, $filters);
	echo $query;
	$result = $con->query($query);

?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Filtered Records</title>
	</head>

	<body>
		<table border='1'>
			<caption><?php echo $table->getLabel() ?></caption>
			<tr>
				<?php
					foreach ($table->getColumns() as $col) {
						echo '<th>' . $col->getLabel() . '</th>';
					}
				?>
			</tr>
			<?php
				while ($record = $result->fetch_assoc()) {
					echo '<tr>';
					foreach ($table->getColumns() as $col) {
						echo '<td>' . $record[$col->getName()] . '</td>';
					}
					echo '</tr>';
				}
			?>
		</table>

		<br>

		<form method="post" action="chooseTable.php">
			<button type="submit">Filter Records for Another Table</button>
		</form>


	</body>
</html>
