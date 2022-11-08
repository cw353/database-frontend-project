<?php
	class Table {
		private $name, $label, $columns;
		function __construct(string $name, string $label, array $columns) {
			$this->name = $name;
			$this->label = $label;
			$this->columns = $columns;
			/*foreach ($columns as $col) {
				array_push($this->columns, new Column($col['name'], $col['label'], $col['sourceTableName'] ? $col['sourceTableName'] : $this->name));
			}*/
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
		private $name, $label, $sourceTableName;
		function __construct(string $name, string $label, string $sourceTableName) {
			$this->name = $name;
			$this->label = $label;
			$this->sourceTableName = $sourceTableName;
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
	}
?>
