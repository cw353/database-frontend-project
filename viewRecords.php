<?php
	require_once 'helperFunctions.php';
	require_once 'dbClasses.php';
	require_once 'tables.php';

	$con = new mysqli('localhost', 'root', 'root', 'project');
	$table_to_query = $_GET['table_to_query'];
	$table = $tables[$table_to_query];
	$query = formulateSelectQuery($table);
	$result = $con->query($query);

?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Records</title>
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
	</body>
</html>
