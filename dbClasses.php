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
		const READ = 0b1;
		const WRITE = 0b10;
		private $name, $label, $foreignKeyInfo, $ioAccess, $sqlExpression;
		function __construct(string $name, string $label, ForeignKeyInfo $foreignKeyInfo = null, int $ioAccess = Column::READ|COLUMN::WRITE, string $sqlExpression = null) {
			$this->name = $name;
			$this->label = $label;
			$this->foreignKeyInfo = $foreignKeyInfo;
			$this->ioAccess = $ioAccess;
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
		function isReadable() {
			return $this->ioAccess & Column::READ;
		}
		function isWritable() {
			return $this->ioAccess & Column::WRITE;
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
