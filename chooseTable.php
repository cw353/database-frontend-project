<?php
	require_once 'tables.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Records</title>
	</head>

	<body>
		<form method="get" action="viewRecords.php">
			<select name="table_to_query">
				<?php
					foreach ($tables as $key=>$value) {
						echo "<option value=$key>" . $value->getLabel() . "</option>";
					}
				?>
			</select>
			<input type="submit" value="Get Table Records">
		</form>
	</body>
</html>
