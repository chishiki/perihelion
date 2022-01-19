<?php

final class CodeGenerator {

	private string $moduleName;
	private string $className;
	private bool $extendsORM;
	private array $fieldArray;
	private string $tableName;

	public function __construct(CodeGeneratorArguments $arg) {

		$this->moduleName = $arg->moduleName;
		$this->className = $arg->className;
		$this->extendsORM = $arg->extendsORM;
		$this->fieldArray = $arg->fieldArray;
		$this->tableName = $arg->moduleName . "_" . $arg->className;

	}

	public function generateSchema() {

		$schema = "CREATE TABLE `" . $this->moduleName . "_"  . $this->className . "` \n";

			$primaryKeys = array();
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				$schema .= "  `" . $keyName . "` " . $key['type'] . " " . $key['default'] . ($key['default']?" AUTO_INCREMENT":"") . ",\n";
				if ($key['primary']) { $primaryKeys[] = $keyName; }
			}

			$schema .= "  `siteID` int NOT NULL,\n";
			$schema .= "  `creator` int NOT NULL,\n";
			$schema .= "  `created` datetime NOT NULL,\n";
			$schema .= "  `updated` datetime NULL,\n";
			$schema .= "  `deleted` int NOT NULL,\n";

			foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
				$schema .= "  `" . $fieldName . "` " . $field['type'] . ($field['parameter']?"(".$field['parameter'].")":"") . " " . $field['default'] . ",\n";
			}

			if (!empty($primaryKeys)) {
				$schema .= "  PRIMARY KEY (`" . implode("", $primaryKeys) . "`)\n";
			}

		$schema .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

		return $schema;

	}

	public function generateModelClass() {

		$model = "final class " . $this->className . ($this->extendsORM?" extends ORM":"") . " {\n\n";

			$primaryKeys = array();
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				$model .= "\tpublic " . ($key['type']=="int"?"int":"string") . " $" . $keyName . ";\n";
				if ($key['primary']) { $primaryKeys[] = $keyName; }
			}

			$model .= "\tpublic int \$siteID;\n";
			$model .= "\tpublic int \$creator;\n";
			$model .= "\tpublic string \$created;\n";
			$model .= "\tpublic ?string \$updated;\n";
			$model .= "\tpublic int \$deleted;\n";

			foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
				$model .= "\tpublic " . ($field['nullable']?"?":"") . ($field['type']=="int"?"int":"string") . " $" . $fieldName . ";\n";
			}

			$model .= "\n";

			$model .= "\tpublic function __construct(";
				if (!empty($primaryKeys)) {
					$parameters = array();
					foreach ($primaryKeys AS $primaryKey) {
						$parameters[] = "$" . $primaryKey . " = null";
					}
					$model .= implode(", ", $parameters);
				}
			$model .= ") {\n\n";

				$model .= "\t\t\$dt = new DateTime();\n\n";

				foreach ($this->fieldArray['keys'] AS $keyName => $key) {
					$model .= "\t\t\$this->" . $keyName . " = " . $key['default-value'] . ";\n";
				}

				$model .= "\t\t\$this->siteID = \$_SESSION['siteID'];\n";
				$model .= "\t\t\$this->creator = \$_SESSION['userID'];\n";
				$model .= "\t\t\$this->created = \$dt->format('Y-m-d H:i:s');\n";
				$model .= "\t\t\$this->updated = null;\n";
				$model .= "\t\t\$this->deleted = 0;\n";

				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$model .= "\t\t\$this->" . $fieldName . " = " . $field['default-value'] . ";\n";
				}

				$model .= "\n";

				$model .= "\t\tif (";
					if (!empty($primaryKeys)) {
						$parameters = array();
						foreach ($primaryKeys AS $primaryKey) {
							$parameters[] = "$" . $primaryKey;
						}
						$model .= implode(" && ", $parameters);
					}
				$model .= ") {\n\n";

					$model .= "\t\t\t\$nucleus = Nucleus::getInstance()\n\n";

					$model .= "\t\t\t\$whereClause = array();\n";
					$model .= "\t\t\t\$whereClause[] = 'siteID = :siteID';\n";
					$model .= "\t\t\t\$whereClause[] = 'deleted = 0';\n";
					if (!empty($primaryKeys)) {
						foreach ($primaryKeys AS $primaryKey) {
							$model .= "\t\t\t\$whereClause[] = '" . $primaryKey . " = :" . $primaryKey . "';\n";
						}
					}

					$model .= "\n";

					$model .= "\t\t\t\$query = 'SELECT * FROM " . $this->tableName . " WHERE ' . implode(' AND ', \$whereClause) . ' LIMIT 1';\n";
					$model .= "\t\t\t\$statement = \$nucleus->database->prepare(\$query);\n";
					$model .= "\t\t\t\$statement->bindParam(':siteID', \$_SESSION['siteID'], PDO::PARAM_INT);\n";
					if (!empty($primaryKeys)) {
						foreach ($primaryKeys AS $primaryKey) {
							$model .= "\t\t\t\$statement->bindParam(':" . $primaryKey . "', $" . $primaryKey . ", PDO::PARAM_INT);\n";
						}
					}
					$model .= "\t\t\t\$statement->execute();\n\n";

					$model .= "\t\t\tif (\$row = \$statement->fetch()) {\n";
						$model .= "\t\t\t\tforeach (\$row AS \$key => \$value) { if (property_exists(\$this, \$key)) { \$this->\$key = \$value; } }\n";
					$model .= "\t\t\t}\n\n";

				$model .= "\t\t}\n\n";

			$model .= "\t}\n\n";

			$model .= "\tpublic function markAsDeleted() {\n\n";
				$model .= "\t\t\$dt = new DateTime();\n";
				$model .= "\t\t\$this->updated = \$dt->format('Y-m-d H:i:s');\n";
				$model .= "\t\t\$this->deleted = 1;\n";
				$model .= "\t\t\$conditions = array(";
					if (!empty($primaryKeys)) {
						$parameters = array();
						foreach ($primaryKeys AS $primaryKey) {
							$parameters[] = "'" . $primaryKey . "' => $" . $primaryKey;
						}
						$model .= implode(", ", $parameters);
					}
				$model .= ");\n";
				$model .= "\t\tself::update(\$this, \$conditions, true, false, '" . $this->moduleName . "_');\n\n";
			$model .= "\t}\n\n";

		$model .= "}";

		return $model;

	}

	public function generateListClass() {

		$class = "final class " . ucfirst($this->moduleName) . $this->className . "List {\n\n";

			$class .= "private array \$results = array();\n";

			$class .= "public function __construct(" . $this->className . "ListParameters \$arg) {\n\n";

				$class .= "// WHERE\n";
				$class .= "\$wheres = array();\n";
				$class .= "\$wheres[] = '" . $this->tableName . ".deleted = 0';\n";
				foreach ($this->fieldArray['keys'] AS $keyName => $key) {
					$class .= "if (!is_null(\$arg->" . $keyName . ")) { \$wheres[] = '" . $this->tableName . "." . $keyName . " = :" . $keyName . "'; }\n";
				}
				$class .= "if (!is_null(\$arg->creator)) { \$wheres[] = '" . $this->tableName . ".creator = :creator'; }\n";
				$class .= "if (!is_null(\$arg->created)) { \$wheres[] = '" . $this->tableName . ".created = :created'; }\n";
				$class .= "if (!is_null(\$arg->updated)) { \$wheres[] = '" . $this->tableName . ".updated = :updated'; }\n";
				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$class .= "if (!is_null(\$arg->" . $fieldName . ")) { \$wheres[] = '" . $this->tableName . "." . $fieldName . " = :" . $fieldName . "'; }\n";
				}
				$class .= "\$where = ' WHERE ' . implode(' AND ', \$wheres);\n\n";

				$class .= "// SELECTOR\n";
				$class .= "\$selectorArray = array();\n";
				$class .= "foreach (\$arg->resultSet AS \$fieldAlias) { \$selectorArray[] = \$fieldAlias['field'] . ' AS ' . \$fieldAlias['alias']; }\n";
				$class .= "\$selector = implode(', ', \$selectorArray);\n\n";

				$class .= "// ORDER BY\n";
				$class .= "\$orderBys = array();\n";
				$class .= "foreach \$arg->orderBy AS \$fieldSort) { \$orderBys[] = \$fieldSort['field'] . ' ' . \$fieldSort['sort']; }\n\n";
				$class .= "\$orderBy = '';\n";
				$class .= "if (!empty(\$orderBys)) { \$orderBy = ' ORDER BY ' . implode(', ', \$orderBys); }\n\n";

				$class .= "// BUILD QUERY\n";
				$class .= "\$query = 'SELECT ' . \$selector . ' FROM building_Residence' . \$where . \$orderBy;\n";
				$class .= "if (\$arg->limit) { \$query .= ' LIMIT ' . (\$arg->offset?\$arg->offset.', ':'') . \$arg->limit; }\n\n";

				$class .= "// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY\n";
				$class .= "\$nucleus = Nucleus::getInstance();\n";
				$class .= "\$statement = \$nucleus->database->prepare(\$query);\n";
				foreach ($this->fieldArray['keys'] AS $keyName => $key) {
					$class .= "if (!is_null(\$arg->" . $keyName . ")) { \$statement->bindParam(':" . $keyName . "', \$arg->" . $keyName . ", PDO::" . ($key['type']=="int"?"PARAM_INT":"PARAM_STR") . "); }\n";
				}
				$class .= "if (!is_null(\$arg->creator)) { \$statement->bindParam(':creator', \$arg->creator, PDO::PARAM_INT); }\n";
				$class .= "if (!is_null(\$arg->created)) { \$statement->bindParam(':created', \$arg->created, PDO::PARAM_STR); }\n";
				$class .= "if (!is_null(\$arg->updated)) { \$statement->bindParam(':updated', \$arg->updated, PDO::PARAM_STR); }\n";
				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$class .= "if (!is_null(\$arg->" . $fieldName . ")) { \$statement->bindParam(':" . $fieldName . "', \$arg->" . $fieldName . ", PDO::" . ($field['type']=="int"?"PARAM_INT":"PARAM_STR") . "); }\n";
				}
				$class .= "\$statement->execute();\n\n";

				$class .= "// WRITE QUERY RESULTS TO ARRAY\n";
				$class .= "while (\$row = \$statement->fetch()) { \$this->results[] =\$row; }\n\n";

			$class .= "}\n\n";

			$class .= "public function results() {\n\n";
				$class .= "return \$this->results;\n";
			$class .= "}\n\n";

			$class .= "public function resultCount() {\n\n";
				$class .= "return count(\$this->results);\n";
			$class .= "}\n\n";

		$class .= "}";
		
		return  $class;
		
	}

	public function generateListArgumentClass() {

		$class = "final class " . ucfirst($this->moduleName) . $this->className . "ListParameters {\n\n";

			$class .= "// list filters\n";
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				$class .= "public ?" . ($key['type']=="int"?"int":"string") . "$" . $keyName . ";\n";
			}
			$class .= "public ?int \$siteID;";
			$class .= "public ?int \$creator;";
			$class .= "public ?string \$created;";
			$class .= "public ?string \$updated;";
			foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
				$class .= "public ?" . ($field['type']=="int"?"int":"string") . " $" . $fieldName . ";\n";
			}
			$class .= "\n";

			$class .= "// view parameters\n";
			$class .= "public ?int \$currentPage;\n";
			$class .= "public ?int \$numberOfPages;\n\n";

			$class .= "// results, order, limit, offset\n";
			$class .= "public array \$resultSet;\n";
			$class .= "public array \$orderBy;\n";
			$class .= "public ?int \$limit;\n";
			$class .= "public ?int \$offset;\n\n";


			$class .= "public function __construct() {\n\n";

				$class .= "// list filters\n";
				foreach ($this->fieldArray['keys'] AS $keyName => $key) {
					$class .= "\$this->" . $keyName . " = null;\n";
				}
				$class .= "\$this->siteID = null;";
				$class .= "\$this->creator = null;";
				$class .= "\$this->created = null;";
				$class .= "\$this->updated = null;";
				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$class .= "public ?" . ($field['type']=="int"?"int":"string") . " $" . $fieldName . ";\n";
				}
				$class .= "\n";

				$class .= "// view parameters\n";
				$class .= "\$this->currentPage = null;\n";
				$class .= "\$this->numberOfPages = null;\n\n";

				$class .= "// results, order, limit, offset\n";
				$class .= "\$this->resultSet = array();\n";
				$class .= "\$object = new " . $this->className . "();\n";
				$class .= "foreach (\$object AS \$key => \$value) {\n";
					$class .= "\$this->resultSet[] = array('field' => 'building_Residence.'.\$key, 'alias' => \$key);\n";
				$class .= "}\n";
				$class .= "\$this->orderBy = array(\n";
					$class .= "array('field' => '" . $this->tableName . ".created', 'sort' => 'DESC')\n";
				$class .= ");\n";
				$class .= "\$this->limit = null;\n";
				$class .= "\$this->offset = null;\n\n";

			$class .= "}\n\n";

		$class .= "}";

		return $class;

	}

	public function compileFile() {

		$fileComponent = array();
		$fileComponent[] = "/*\n\n".$this->generateSchema()."\n\n*/";
		$fileComponent[] = $this->generateModelClass();
		$fileComponent[] = $this->generateListClass();
		$fileComponent[] = $this->generateListArgumentClass();

		$file = "<?php\n\n" . implode("\n\n\n\n", $fileComponent) . "\n\n?>";

		return htmlentities($file);

	}

}

final class CodeGeneratorArguments {

	public string $moduleName;
	public string $className;
	public bool $extendsORM;
	public array $fieldArray;

	public function __construct() {

		$this->moduleName = 'module'; // eg 'inventory' etc
		$this->className = 'Object'; // eg 'Product' etc
		$this->extendsORM = true;
		$this->fieldArray = array( // an array structured as following is expected by CodeGenerator
			'keys' => array(
				'keyNameA' => array (
					'type' => 'int',
					'default' => 'NOT NULL',
					'default-value' => 0,
					'nullable' => false,
					'auto-increment' => true,
					'primary' => true
				)
			),
			'fields' => array(
				'fieldNameA' => array (
					'type' => 'int',
					'parameter' => null, // int does not take a parameter in MySQL 8
					'default' => 'NOT NULL',
					'default-value' => '0',
					'nullable' => false
				),
				'fieldNameB' => array (
					'type' => 'datetime',
					'parameter' => null,
					'default' => 'NULL',
					'default-value' => 'null',
					'nullable' => true
				),
				'fieldNameN' => array (
					'type' => 'varchar',
					'parameter' => '255',
					'default' => 'NOT NULL',
					'default-value' => '\'\'',
					'nullable' => false
				)
			)
		);

	}

}

?>