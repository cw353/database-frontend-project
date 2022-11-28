<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Home Page</title>
		<link rel="stylesheet" href="stylesheet.css"/>
	</head>

	<body>
		<h1>ABC Dispatcher Company Dashboard</h1>
		<p class="motto">NEITHER RAIN NOR SNOW NOR GLO<span class="faded">O</span>M OF NI<span class="faded">GH</span>T CAN STAY THESE MES<span class="faded">S</span>ENGERS ABO<span class="faded">U</span>T THEIR DUTY.</p>
		<header>Choose an action:</header>
		<form method="get" action="chooseTable.php">
			<input type="hidden" name="action" value="view">
			<button type="submit">View Table Records</button>
		</form>
		<form method="get" action="chooseTable.php">
			<input type="hidden" name="action" value="filter">
			<button type="submit">Filter Table Records</button>
		</form>
		<form method="get" action="chooseTable.php">
			<input type="hidden" name="action" value="insert">
			<button type="submit">Insert New Record</button>
		</form>
	</body>
</html>
