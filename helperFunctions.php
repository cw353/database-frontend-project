<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once('dbClasses.php');
	require_once('helperFunctions.php');

	/* sanitize a string for use in mysql */
	function sanitizeMysql(mysqli $mysqli, string $input) {
			return mysqli_real_escape_string($mysqli, $input);	
	}

	/* sanitize a string for use in html (returns empty string if empty) */
	function sanitizeHtml(string $input = null) {
			return empty($input) ? '' : htmlspecialchars($input, ENT_QUOTES);	
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
		return "<a href='viewFilteredRecords.php?table=$table&$field=$val&" . $field.'_op' . "=e'>$val</a>";
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

	function getActionLinks(Table $table, array $record) {
		$tablename = sanitizeHtml($table->getName());
		$params = '';
		foreach($table->getPrimaryKeys() as $pk) {
			$params .= '&' . sanitizeHtml($pk) . '=' . sanitizeHtml($record[$pk]);
		}
		return "<a href='modifyRecord.php?table=$tablename" . "$params'>Modify Record</a>" . " | <a href='deleteRecord.php?table=$tablename" . "$params'>Delete Record</a>";
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
			$toReturn .= '<caption>' . sanitizeHtml($table->getLabel()) . '</caption>';
			$toReturn .= '<tr>';
			foreach ($table->getColumns() as $col) {
				$toReturn .= '<th>' . sanitizeHtml($col->getLabel()) . '</th>';
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
				$toReturn .= '<td>' . getActionLinks($table, $record) . '</td>';
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
		$toReturn = "<select name='" . sanitizeHtml($selectname) . "'>";
		while ($result && $record = $result->fetch_assoc()) {
			$option = sanitizeHtml($record[$field]);
			$toReturn .= "<option value='$option'";
			if (strval($option) === sanitizeHtml(strval($value))) { $toReturn .= " selected"; }
			$toReturn .= ">$option</option>";
		}
		$toReturn .= '</select>';
		return $toReturn;
	}

	function getModifiableTable(Table $table, mysqli $mysqli, array $record = null) {
		$toReturn = '<table><tr><th>Field</th><th>Value</th></tr>';
		if (!empty($record)) {
			foreach($table->getPrimaryKeys() as $pk) {
				$toReturn .= "<input type='hidden' name='" . sanitizeHtml($pk.'_old') . "' value='" . sanitizeHtml($record[$pk]) . "'>";
			}
		}
		foreach ($table->getColumns() as $col) {
			$colname = sanitizeHtml($col->getName());
			$toReturn .= "<tr><td>" . sanitizeHtml($col->getLabel()) ."</td><td>";
			if ($fkInfo = $col->getForeignKeyInfo()) {
				$toReturn .= getForeignKeyDropdown($fkInfo, $mysqli, $record ? $record[$colname] : null, $colname);
			} else {
				$toReturn .= "<input type='text' name='$colname'";
				if ($record) { $toReturn .= " value='" . sanitizeHtml($record[$colname]) . "'"; }
				$toReturn .= '/>';
			}
			$toReturn .= '</td></tr>';
		
		}
		$toReturn .= '</table>';
		return $toReturn;
	}

?>
