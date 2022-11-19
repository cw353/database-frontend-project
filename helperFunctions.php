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
		$query .= ' order by ' . join(', ', $table->getPrimaryKeys());
		return $query;
	}

	/* get a hyperlink to display for a foreign key value */
	function getForeignKeyLink(string $val, ForeignKeyInfo $foreignKeyInfo) {
		$table = $foreignKeyInfo->getTable();
		$field = $foreignKeyInfo->getField();
		return "<a href='viewFilteredRecords.php?table=$table&$field=$val&" . $field.'_op' . "=e'>$val</a>";
	}

	/* precondition: if $param_var is not null, then $param_types should not be null either and should contain the same number of chars as $param_var contains values */
	function executeQueryGetResult(mysqli $mysqli, string $query, array $param_var = null, string $param_types = null) {
		// if filtering data, use prepared statement
	  if ($param_var && sizeof($param_var) > 0) {
    	$stmt = $mysqli->prepare($query);
    	$stmt->bind_param($param_types, ...$param_var);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	$stmt && $stmt->close();
  	} else {
    	// otherwise, use regular query
    	$result = $mysqli->query($query);
		}
		return $result;
	}

		/* precondition: if $param_var is not null, then $param_types should not be null either and should contain the same number of chars as $param_var contains values */
	function executeQueryGetAffected(mysqli $mysqli, string $query, array $param_var = null, string $param_types = null) {
		// if filtering data, use prepared statement
	  if ($param_var && sizeof($param_var) > 0) {
    	$stmt = $mysqli->prepare($query);
    	$stmt->bind_param($param_types, ...$param_var);
    	$stmt->execute();
    	$affected = $stmt->affected_rows;
    	$stmt && $stmt->close();
  	} else {
    	// otherwise, use regular query
    	$mysqli->query($query);
			$affected = $mysqli->affected_rows;
		}
		return $affected;
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
				if ($col->isReadable()) {
					$toReturn .= '<th>' . sanitizeHtml($col->getLabel()) . '</th>';
				}
			}
			$toReturn .= '<th>Actions</th></tr>';
			while ($record = $result->fetch_assoc()) {
				$toReturn .= '<tr>';
				foreach ($table->getColumns() as $col) {
					if ($col->isReadable()) {
						$val = sanitizeHtml($record[$col->getName()]);
						$foreignKeyInfo = $col->getForeignKeyInfo();
						$toReturn .= '<td>';
						$toReturn .= empty($foreignKeyInfo) ? $val : getForeignKeyLink($val, $foreignKeyInfo);
						$toReturn .= '</td>';
					}
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
		$result = executeQueryGetResult($mysqli, "select $field from $table order by $field");
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

	function getColumnInput(Column $col, string $val = null, bool $enforceRequired = true) {
		$toReturn = "<input name='" . sanitizeHtml($col->getName()) . "'";
		switch ($col->getType()) {
			case 'int':
				$toReturn .= " type='number'";
				break;
			case 'id':
				$toReturn .= " type='number' min='1'";
				break;
			case 'date':
				$toReturn .= " type='date'";
				break;
			case 'time':
				$toReturn .= " type='time'";
				break;
			case 'money':
				$toReturn .= " type='number' step='.01'";
				break;
			default:
				$toReturn .= " type='text'";
		}
		if (isset($val)) {
			$toReturn .= " value='" . sanitizeHtml($val) . "'";
		}
		if ($enforceRequired && !$col->isOptional()) {
			$toReturn .= " required";
		}
		$toReturn .= '/>';
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
			if ($col->isWritable()) {
				$colname = sanitizeHtml($col->getName());
				$toReturn .= "<tr><td>" . sanitizeHtml($col->getLabel()) ."</td><td>";
				if ($fkInfo = $col->getForeignKeyInfo()) {
					$toReturn .= getForeignKeyDropdown($fkInfo, $mysqli, $record ? $record[$colname] : null, $colname);
				} else {
					$toReturn .= getColumnInput($col, $record ? $record[$colname] : null);
					/*$toReturn .= "<input type='text' name='$colname'";
					if ($record) {
						$toReturn .= " value='" . sanitizeHtml($record[$colname]) . "'";
					}
					$toReturn .= '/>';*/
				}
				$toReturn .= '</td></tr>';
			}
		}
		$toReturn .= '</table>';
		return $toReturn;
	}

?>
