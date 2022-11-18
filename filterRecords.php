<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'dbClasses.php';
	require_once 'tables.php';
	require_once 'operators.php';

	$tablename = $_GET['table'];
	$table = $tables[$tablename];

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Filter Records</title>
	</head>

	<body>
		<header><?php echo 'Filter Records for ' . sanitizeHtml($table->getLabel()) ?></header>
		<form method="get" action="viewFilteredRecords.php">
			<input type="hidden" name="table" value="<?php echo sanitizeHtml($tablename); ?>">
			<table>
				<tr>
					<th>Field</th>
					<th>Operator</th>
					<th>Comparand</th>
				</tr>
			<?php
				foreach ($table->getColumns() as $col) {
					if ($col->isReadable()) {
						$colname = sanitizeHtml($col->getName());
						echo '<tr>';
						echo '<td>' . sanitizeHtml($col->getLabel()) . '</td>';
						echo "<td><select name=$colname" . '_op>';
						foreach ($operators as $key=>$val) {
							echo "<option value='" . sanitizeHtml($key) . "'>" . sanitizeHtml($val['label']) . '</option>';
						}
						echo '</select></td>';
						echo '<td>' . getColumnInput($col) . '</td';
						//echo "<td><input type='text' name='$colname'></td>";
						echo '</tr>';
					}
				}
			?>
			</table>
			<button type="submit">Apply Filter</button>
		</form>


	</body>
</html>
