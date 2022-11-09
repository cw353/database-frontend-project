<?php
	require_once 'tables.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Records</title>
	</head>

	<body>
		<header>Choose a table for which to view records.</header>
		<form method="get" action="viewRecords.php">
			<select name="table_to_query">
				<?php
					foreach ($tables as $key=>$value) {
						echo "<option value=$key>" . $value->getLabel() . "</option>";
					}
				?>
			</select>
			<button type="submit">View Table Records</button>
		</form>
	</body>
</html>
