<?php
	require_once('dbClasses.php');

	/* sanitize a string for use in mysql */
	function sanitizeMysql(string $input) {
			return mysql_real_escape_string($input);	
	}

	/* sanitize a string for use in html */
	function sanitizeHtml(string $input) {
			return htmlspecialchars($input);	
	}

	/* formulate a select query for the specified table object */
	function formulateSelectQuery(Table $table) {
		$query = 'select *'; // select all columns
		// also select any additional expressions specified in the columns list
		foreach ($table->getColumns() as $col) {
			$sqlExpression = $col->getSqlExpression();
			$query .= $sqlExpression ? ", $sqlExpression as " . $col->getName() : '';
		}
		// specify the source table
		$query .= ' from ' . $table->getName();
		return $query;
	}
?>
