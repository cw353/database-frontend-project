<?php
	class Table {
		private $name, $label, $columns;
		function __construct(string $name, string $label, array $columns) {
			$this->name = $name;
			$this->label = $label;
			$this->columns = $columns;
		}
		function getName() {
			return $this->name;
		}
		function getLabel() {
			return $this->label;
		}
		function getColumns() {
			return $this->columns;
		}
	}

	class Column {
		private $name, $label, $sourceTableName, $sqlExpression;
		function __construct(string $name, string $label, string $sourceTableName, string $sqlExpression = null) {
			$this->name = $name;
			$this->label = $label;
			$this->sourceTableName = $sourceTableName;
			$this->sqlExpression = $sqlExpression;
		}
		function getName() {
			return $this->name;
		}
		function getLabel() {
			return $this->label;
		}
		function getSourceTableName() {
			return $this->sourceTableName;
		}
		function getSqlExpression() {
			return $this->sqlExpression;
		}
	}
?>
