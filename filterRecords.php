<!-- Filter records for selected table -->
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
		<link rel="stylesheet" href="stylesheet.css"/>
	</head>

	<body>
		<header><?php echo 'Filter records for ' . sanitizeHtml($table->getLabel()) . ':'?></header>
		<form method="get" action="viewRecords.php">
			<input type="hidden" name="table" value="<?php echo sanitizeHtml($tablename); ?>">
			<table>
				<tr>
					<th>Field</th>
					<th>Operator</th>
					<th>Comparand</th>
				</tr>
			<?php
				foreach ($table->getColumns() as $col) {
					// enable filtering only for readable columns
					if ($col->isReadable()) {
						$colname = sanitizeHtml($col->getName());
						echo '<tr>';
						echo '<td>' . sanitizeHtml($col->getLabel()) . '</td>';
						echo "<td><select name=$colname" . '_op>';
						foreach ($operators as $key=>$val) {
							echo "<option value='" . sanitizeHtml($key) . "'>" . sanitizeHtml($val['label']) . '</option>';
						}
						echo '</select></td>';
						echo '<td>' . getColumnInput($col, null, false) . '</td';
						echo '</tr>';
					}
				}
			?>
			</table>
			<label>
				<input type="radio" name="joinop" value="a" checked>
				Match All
			</label>
			<label>
				<input type="radio" name="joinop" value="o">
				Match Any
			</label>
			<br>
			<button type="submit">Apply Filter</button>
		</form>


	</body>
</html>
