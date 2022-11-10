<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';
	require_once 'operators.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$table_to_query = $_GET['table_to_query'];
	$table = $tables[$table_to_query];

	$filter_expr = []; // filter expressions
	$filter_var = []; // comparands to bind for filters
	$types = ''; // types of variables to bind for filters
	foreach ($table->getColumns() as $col) {
		$colname = $col->getName();
		// add to filters only if comparand was provided
		if (!empty($_GET[$colname])) {
			$op = $_GET[$colname.'_op']; // operator info
			// comparand
			$comparand = (($op === 'c' or $op === 'end') ? '%' : '')
				. $_GET[$colname]
				. (($op === 'c' or $op === 'start') ? '%' : '');
			// sql operator
			$sql_op = empty($op)
				? '='
				: $operators[$op]['op'];
			$attr = $col->getSqlExpression(); // attribute
			// build expression from attribute and operator with ? placeholder for var
			$expr = "$attr $sql_op ?";
			array_push($filter_expr, $expr);
			array_push($filter_var, $comparand);
			$types .= 's';
		}
	}

	$query = formulateSelectQuery($table, $filter_expr);

	$result = null;
	$stmt = null;
	if (sizeof($filter_expr) > 0) {
		// use prepared statements if filtering data
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param($types, ...$filter_var);
		$stmt->execute();
		$result = $stmt->get_result();
	} else {
		// otherwise, use regular query
		$result = $mysqli->query($query);
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Filtered Records</title>
	</head>

	<body>
		<?php
			if (!$result) {
				echo "<p>An error occurred while trying to process your query.</p>";
			} else {
				echo "<p>$result->num_rows matching record" . ($result->num_rows === 1 ? ' was ' : 's were ') . 'found.</p>';
			}
		?>
		<?php if ($result && $result->num_rows > 0): ?>
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
							$val = sanitizeHtml($record[$col->getName()]);
							$foreignKeyInfo = $col->getForeignKeyInfo();
							echo '<td>';
							echo empty($foreignKeyInfo) ? $val : getForeignKeyLink($val, $foreignKeyInfo);
							echo '</td>';
						}
						echo '</tr>';
					}
				?>
			</table>
		<?php endif; ?>

		<br>

		<form method="post" action="chooseTable.php">
			<button type="submit">Filter Records for Another Table</button>
		</form>


	</body>
</html>

<?php
	$result && $result->free();
	$stmt && $stmt->close();
	$mysqli && $mysqli->close();
?>
