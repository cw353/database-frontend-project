<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	class Table {
		private $name, $label, $columns;
		function __construct(string $name, string $label, array $primaryKeys, array $columns) {
			$this->name = $name;
			$this->label = $label;
			$this->primaryKeys = $primaryKeys;
			$this->columns = $columns;
		}
		function getName() {
			return $this->name;
		}
		function getLabel() {
			return $this->label;
		}
		function getPrimaryKeys() {
			return $this->primaryKeys;
		}
		function getColumns() {
			return $this->columns;
		}
	}

	class Column {
		private $name, $label, $foreignKeyInfo, $sqlExpression;
		function __construct(string $name, string $label, ForeignKeyInfo $foreignKeyInfo = null, string $sqlExpression = null) {
			$this->name = $name;
			$this->label = $label;
			$this->foreignKeyInfo = $foreignKeyInfo;
			$this->sqlExpression = $sqlExpression;
		}
		function getName() {
			return $this->name;
		}
		function getLabel() {
			return $this->label;
		}
		function getForeignKeyInfo() {
			return $this->foreignKeyInfo;
		}
		function getSqlExpression() {
			return $this->sqlExpression === null
				? $this->name
				: $this->sqlExpression;
		}
		function getSqlExpressionWithAlias() {
			return $this->sqlExpression === null
				? $this->name
				: "$this->sqlExpression as $this->name";
		}
	}

	class ForeignKeyInfo {
		private $table, $field;
		function __construct(string $table, string $field) {
			$this->table = $table;
			$this->field = $field;
		}
		function getTable() {
			return $this->table;
		}
		function getField() {
			return $this->field;
		}
	}

?>
