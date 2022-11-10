<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';

	$con = new mysqli('localhost', 'root', 'root', 'project');
	$table_to_query = $_GET['table_to_query'];
	$table = $tables[$table_to_query];

	$filters = [];
	foreach ($table->getColumns() as $col) {
		$colname = $col->getName();
		if (!empty($_GET[$colname])) {
			$op = $_GET[$colname.'_op'];
			$comparand = (($op === 'like' or $op === 'ends') ? '%' : '')
				. sanitizeSql($con, $_GET[$colname])
				. (($op === 'like' or $op === 'starts') ? '%' : '');
			$sql_op = ($op === 'starts' or $op === 'ends') ? 'like' : $op;
			$col_expr = $col->getSqlExpression();
			$filter = "$col_expr $sql_op '$comparand'";
			array_push($filters, $filter);
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
