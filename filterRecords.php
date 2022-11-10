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
			<table border='1'>
				<tr>
					<th>Field</th>
					<th>Operator</th>
					<th>Comparand</th>
				</tr>
			<?php
				$operators = [
					['=',  'is'],
					['!=', 'is not'],
					['<',  'is less than'],
					['<=', 'is less than or equal to'],
					['>',  'is greater than'],
					['>=', 'is greater than or equal to'], 
					['starts', 'starts with'],
					['ends', 'ends with'],
					['like',  'contains'],
				];
				foreach ($table->getColumns() as $col) {
					$colname = $col->getName();
					echo '<tr>';
					echo '<td>' . $col->getLabel() . '</td>';
					echo "<td><select name=$colname" . '_op>';
					foreach ($operators as [$op, $label]) {
						echo "<option value='$op'>$label</option>";
					}
					echo '</select></td>';
					echo "<td><input type='text' name='$colname'></td>";
					echo '</tr>';
				}
			?>
			</table>
			<button type="submit">Apply Filter</button>
		</form>


	</body>
</html>
