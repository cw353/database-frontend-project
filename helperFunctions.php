<?php
	require_once('dbClasses.php');

	/* sanitize a string for use in mysql */
	function sanitizeSql(mysqli $mysqli, string $input) {
			return mysqli_real_escape_string($mysqli, $input);	
	}

	/* sanitize a string for use in html (returns empty string if empty) */
	function sanitizeHtml(?string $input) {
			return empty($input) ? '' : htmlspecialchars($input);	
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

	/* get a hyperlink to display for a foreign key value */
	function getForeignKeyLink(string $val, array $foreignKeyInfo) {
		$table = $foreignKeyInfo['table'];
		$field = $foreignKeyInfo['field'];
		return "<a href='viewFilteredRecords.php?table_to_query=$table&$field=$val&" . $field.'_op' . "=e'>$val</a>";
	}
?>
