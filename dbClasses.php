<!-- Classes to model tables and relationships in the database -->
<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	// Class to model a table in the database
	class Table {
		private $name, $label, $primaryKeys, $columns;
		/**
		 * Constructor.
		 * @param name The internal name of the table in the database.
		 * @param label The table name to display in the frontend.
		 * @param primaryKeys An array of names of the primary keys for the table.
		 * @param columns An array of Column objects representing the columns of the table.
		 */
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

	// Class to model a column in the database
	class Column {
		const READ = 0b1;
		const WRITE = 0b10;
		const OPTIONAL = 0b100;
		private $name, $label, $type, $foreignKeyInfo, $inputConstraints, $sqlExpression;
		/**
		 * Constructor
		 * @param name The internal name of the column in the database.
		 * @param label The column name to display in the frontend.
		 * @param type An informal descriptor of the data type of this column, used to select an appropriate HTML input element ('int' for integers, 'id' for counting number IDs, 'date' for dates, 'time' for times, 'money' for 2-decimal money values, and 'text' for textual values).
		 * @param foreignKeyInfo If this column is a foreign key column, this is a ForeignKeyInfo object representing information about the relationship between this column and the other column it references. Null by default (should be null for all non-foreign-key columns).
		 * @param inputConstraints A bit mask that determines whether this column is readable (COLUMN::READ - can be viewed by frontend users), writable (COLUMN::WRITE - can be modified by frontend users), and/or optional (COLUMN::OPTIONAL - can be left null by frontend users). (For example, columns representing derived attributes should be readable but not writable.) By default, columns are readable and writable but not optional.
		 * @param sqlExpression A SQL expression that should be used to compute the contents of this column. Null by default (should be non-null only for columns representing derived attributes).
		 */
		function __construct(string $name, string $label, string $type, ForeignKeyInfo $foreignKeyInfo = null, int $inputConstraints = Column::READ|COLUMN::WRITE, string $sqlExpression = null) {
			$this->name = $name;
			$this->label = $label;
			$this->type = $type;
			$this->foreignKeyInfo = $foreignKeyInfo;
			$this->inputConstraints = $inputConstraints;
			$this->sqlExpression = $sqlExpression;
		}
		function getName() {
			return $this->name;
		}
		function getLabel() {
			return $this->label;
		}
		function getType() {
			return $this->type;
		}
		function getForeignKeyInfo() {
			return $this->foreignKeyInfo;
		}
		function isReadable() {
			return $this->inputConstraints & Column::READ;
		}
		function isWritable() {
			return $this->inputConstraints & Column::WRITE;
		}
		function isOptional() {
			return $this->inputConstraints & Column::OPTIONAL;
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

	// Class representing information about a foreign key relationship.
	class ForeignKeyInfo {
		private $table, $field;
		/**
		 * Constructor
		 * @param table The name of the table that the foreign key references.
		 * @param field The name of the field that the foreign key references.
		 */
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
