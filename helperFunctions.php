<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

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
	function getForeignKeyLink(string $val, ForeignKeyInfo $foreignKeyInfo) {
		$table = $foreignKeyInfo->getTable();
		$field = $foreignKeyInfo->getField();
		return "<a href='viewFilteredRecords.php?table_to_query=$table&$field=$val&" . $field.'_op' . "=e'>$val</a>";
	}

	/* precondition: if $filter_var is not null, then $filter_types should not be null either and should contain the same number of chars as $filter_var contains values */
	function getQueryResult(mysqli $mysqli, string $query, array $filter_var = null, string $filter_types = null) {
		// if filtering data, use prepared statement
	  if ($filter_var && sizeof($filter_var) > 0) {
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

	function getModifyLink(Table $table, array $record) {
		$params = '';
		foreach($table->getPrimaryKeys() as $pk) {
			$params .= "&$pk=" . $record[$pk];
		}
		return "<a href='alterRecord.php?table_to_query=" . $table->getName() . "$params'>Modify Record</a>";
	}

	function getResultTable(mysqli_result $result, Table $table) {
		$toReturn = '';
		if (!$result) {
		 $toReturn .= "<p>An error occurred while trying to process your query.</p>";
		} else {
		 $toReturn .= "<p>$result->num_rows matching record" . ($result->num_rows === 1 ? ' was ' : 's were ') . 'found.</p>';
		}
		if ($result && $result->num_rows > 0) {
			$toReturn .= "<table>";
			$toReturn .= '<caption>' . $table->getLabel() . '</caption>';
			$toReturn .= '<tr>';
			foreach ($table->getColumns() as $col) {
				$toReturn .= '<th>' . $col->getLabel() . '</th>';
			}
			$toReturn .= '<th>Actions</th></tr>';
			while ($record = $result->fetch_assoc()) {
				$toReturn .= '<tr>';
				foreach ($table->getColumns() as $col) {
					$val = sanitizeHtml($record[$col->getName()]);
					$foreignKeyInfo = $col->getForeignKeyInfo();
					$toReturn .= '<td>';
					$toReturn .= empty($foreignKeyInfo) ? $val : getForeignKeyLink($val, $foreignKeyInfo);
					$toReturn .= '</td>';
				}
				$toReturn .= '<td>' . getModifyLink($table, $record) . '</td>';
				$toReturn .= '</tr>';
			}
			$toReturn .= '</table>';
		}
		return $toReturn;
	}

	function getForeignKeyDropdown(ForeignKeyInfo $foreignKeyInfo, mysqli $mysqli, string $value = null, string $selectname) {
		$table = $foreignKeyInfo->getTable();
		$field = $foreignKeyInfo->getField();
		$result = getQueryResult($mysqli, "select $field from $table order by $field");
		$toReturn = "<select name='$selectname'>";
		while ($result && $record = $result->fetch_assoc()) {
			$option = $record[$field];
			$toReturn .= "<option value='$option'";
			if (strval($option) === strval($value)) { $toReturn .= " selected"; }
			$toReturn .= ">$option</option>";
		}
		$toReturn .= '</select>';
		return $toReturn;
	}

	function getModifiableTable(Table $table, mysqli $mysqli, array $record = null) {
		$toReturn = '<table><tr><th>Field</th><th>Value</th></tr>';
		foreach ($table->getColumns() as $col) {
			$colname = $col->getName();
			$collabel = $col->getLabel();
			$toReturn .= "<tr><td>$collabel</td><td>";
			if ($fkInfo = $col->getForeignKeyInfo()) {
				$toReturn .= getForeignKeyDropdown($fkInfo, $mysqli, $record ? $record[$colname] : null, $collabel);
			} else {
				$toReturn .= "<input type='text' name='$colname'";
				if ($record) { $toReturn .= " value='" . $record[$colname] . "'"; }
				$toReturn .= '/>';
			}
			$toReturn .= '</td></tr>';
		
		}
		$toReturn .= '</table>';
		return $toReturn;
	}

?>
