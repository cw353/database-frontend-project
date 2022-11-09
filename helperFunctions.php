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
		$query = 'select ';
		$columns = $table->getColumns();
		$numcols = sizeof($columns);
		for ($i = 0; $i < $numcols; $i++) {
			if ($i > 0) $query .= ', ';
			$sqlExpression = $columns[$i]->getSqlExpression();
			$query .= $sqlExpression
				? "$sqlExpression as " . $columns[$i]->getName()
				: $columns[$i]->getName();
		}
		$query .= ' from ' . $table->getName();
		return $query;
	}
?>
