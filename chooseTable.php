<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'tables.php';
	require_once 'helperFunctions.php';

	switch ($_GET['action']) {
		case "view":
			$action = "viewFilteredRecords.php";
			$actionDesc = "for which to view records";
			break;
		case "filter":
			$action = "filterRecords.php";
			$actionDesc = "for which to filter records";
			break;
		case "insert":
			$action = "insertRecord.php";
			$actionDesc = "into which to insert a record";
			break;
		default: 
			$action = "";
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Choose Table</title>
		<link rel="stylesheet" href="stylesheet.css"/>
	</head>

	<body>
		<header>Choose a table <?php echo $actionDesc ?>:</header>
		<form method="get" action=<?php echo $action; ?>>
			<select name="table">
				<?php
					foreach ($tables as $key=>$value) {
						echo "<option value='" . sanitizeHtml($key) . "'>" . sanitizeHtml($value->getLabel()) . "</option>";
					}
				?>
			</select>
			<button type="submit">Choose</button>
		</form>
	</body>
</html>
