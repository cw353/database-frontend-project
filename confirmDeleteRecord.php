<!-- Confirm request to delete selected record -->
<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Confirm Deletion</title>
		<link rel="stylesheet" href="stylesheet.css"/>
	</head>

	<body>
		<header>Are you sure you want to delete this record? Any other records that reference this record will also be deleted.</header>
		<br>
		<a href='<?php echo $_SERVER['HTTP_REFERER']; ?>'>Cancel Deletion and Return to Previous Page</a>
		<br>
		<br>
		<a href='<?php echo 'deleteRecord.php?'.$_SERVER['QUERY_STRING']; ?>'>Confirm Deletion</a>
	</body>
</html>
