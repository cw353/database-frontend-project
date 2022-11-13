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
		function __construct(string $name, string $label, array $foreignKeyInfo = null, string $sqlExpression = null) {
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
?>
