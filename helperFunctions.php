<?php
	require_once('dbClasses.php');

	/* sanitize a string for use in mysql */
	function sanitizeSql(mysqli $mysqli, string $input) {
			return mysqli_real_escape_string($mysqli, $input);	
	}

	/* sanitize a string for use in html */
	function sanitizeHtml(string $input) {
			return htmlspecialchars($input);	
	}

	/* formulate a select query for the specified table object */
	function formulateSelectQuery(Table $table, $filters = null) {
		$query = 'select ';
		$columns = $table->getColumns();
		$numcols = sizeof($columns);
		for ($i = 0; $i < $numcols; $i++) {
			if ($i > 0) $query .= ', ';
			$query .= $columns[$i]->getSqlExpressionWithAlias();
		}
		$query .= ' from ' . $table->getName();
		if (!empty($filters)) {
			$query .= ' where ' . join(' and ', $filters);
		}
		return $query;
	}
?>
