<?php
	//require_once 'helperFunctions.php';
	require_once 'DBClasses.php';

	/*session_start();
	$con = null;
	if (!isset($_SESSION['con'])) {
		$_SESSION['con'] = new mysqli('localhost', 'root', 'root', 'project');
	}
	$con = $_SESSION['con'];*/

	$con = new mysqli('localhost', 'root', 'root', 'project');

	if (mysqli_connect_errno()) {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  } else {
		echo "Connection succeeded";
	}

	$result = $con->query('select * from truckrepairs');

	$table = new Table('driver', 'Drivers', [
		new Column('driverid', 'ID', 'driver'), 
		new Column('driverlicenseno', 'License #', 'driver'),
		new Column('drivername', 'Name', 'driver'),
		new Column('bonus', 'Bonus', 'driver'),
	]);

	$table = new Table('truckrepairs', 'Truck Repairs', [
		new Column('rtid', 'Repair Technician', 'repairtechnician'),
		new Column('truckid', 'Truck ID', 'truck'),
		new Column('repaircost', 'Repair Cost', 'truckrepairs'),
	]);

?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Test</title>
	</head>

	<body>
		<h3>View Test WIP</h3>
		<table border='1'>
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
						echo '<td>' . $record[$col->getName()] . '</td>';
					}
					echo '</tr>';
				}
			?>
		</table>
	</body>
</html>
