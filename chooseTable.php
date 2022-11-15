<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'tables.php';
	require_once 'helperFunctions.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Choose Table</title>
	</head>

	<body>
		<header>Choose a table for which to view records.</header>
		<form method="get" action="filterRecords.php">
			<select name="table">
				<?php
					foreach ($tables as $key=>$value) {
						echo "<option value='" . sanitizeHtml($key) . "'>" . sanitizeHtml($value->getLabel()) . "</option>";
					}
				?>
			</select>
			<button type="submit">Filter Table Records</button>
		</form>
	</body>
</html>
