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

	/* precondition: if $filter_expr is not null, then $filter_var and $filter_types should not be null either and should contain the same number of values as $filter_expr (array of strings for the former, array of chars for the latter) */
	function getQueryResult(mysqli $mysqli, string $query, ?array $filter_expr, ?array $filter_var, ?string $filter_types) {
		// if filtering data, use prepared statement
	  if ($filter_expr && sizeof($filter_expr) > 0) {
    	$stmt = $mysqli->prepare($query);
    	$stmt->bind_param($filter_types, ...$filter_var);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	$stmt && $stmt->close();
  	} else {
    	// otherwise, use regular query
    	$result = $mysqli->query($query);
		}
		return $result;
	}


	function getResultTable(mysqli_result $result, Table $table) {
		$toReturn = '';
		if (!$result) {
		 $toReturn .= "<p>An error occurred while trying to process your query.</p>";
		} else {
		 $toReturn .= "<p>$result->num_rows matching record" . ($result->num_rows === 1 ? ' was ' : 's were ') . 'found.</p>';
		}
		if ($result && $result->num_rows > 0) {
			$toReturn .= "<table border='1'>";
			$toReturn .= '<caption>' . $table->getLabel() . '</caption>';
			$toReturn .= '<tr>';
			foreach ($table->getColumns() as $col) {
				$toReturn .= '<th>' . $col->getLabel() . '</th>';
			}
			$toReturn .= '</tr>';
			while ($record = $result->fetch_assoc()) {
				$toReturn .= '<tr>';
				foreach ($table->getColumns() as $col) {
					$val = sanitizeHtml($record[$col->getName()]);
					$foreignKeyInfo = $col->getForeignKeyInfo();
					$toReturn .= '<td>';
					$toReturn .= empty($foreignKeyInfo) ? $val : getForeignKeyLink($val, $foreignKeyInfo);
					$toReturn .= '</td>';
				}
				$toReturn .= '</tr>';
			}
			$toReturn .= '</table>';
		}
		return $toReturn;
	}
?>
