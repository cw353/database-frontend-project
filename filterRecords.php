<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'dbClasses.php';
	require_once 'tables.php';
	require_once 'operators.php';

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
			<table>
				<tr>
					<th>Field</th>
					<th>Operator</th>
					<th>Comparand</th>
				</tr>
			<?php
				foreach ($table->getColumns() as $col) {
					$colname = $col->getName();
					echo '<tr>';
					echo '<td>' . $col->getLabel() . '</td>';
					echo "<td><select name=$colname" . '_op>';
					foreach ($operators as $key=>$val) {
						echo "<option value='$key'>" . $val['label'] . '</option>';
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
