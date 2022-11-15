<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';
	require_once 'operators.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$table_to_query = $_GET['table_to_query'];
	$table = $tables[$table_to_query];

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Modify Record</title>
	</head>

	<body>
		<form method="" action="">
			<input type="hidden" name="table_to_query" value="<?php echo $table_to_query; ?>">
			<?php
				echo getModifiableTable($table, $mysqli);
			?>
			<button type="submit">Insert Record</button>
		</form>


	</body>
</html>

<?php
	$mysqli && $mysqli->close();
?>
