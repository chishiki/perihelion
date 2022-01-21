<?php

final class CodeGenerator {

	private string $moduleName;
	private string $className;
	private bool $extendsORM;
	private bool $scope;
	private array $fieldArray;
	private string $tableName;

	public function __construct(CodeGeneratorArguments $arg) {

		$this->moduleName = $arg->moduleName;
		$this->className = $arg->className;
		$this->extendsORM = $arg->extendsORM;
		$this->scope = $arg->scope;
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
					if ($this->scope == 'site') { $model .= "\t\t\t\$whereClause[] = 'siteID = :siteID';\n"; }
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
							$parameters[] = "'" . $primaryKey . "' => \$this->" . $primaryKey;
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

			$class .= "\tprivate array \$results = array();\n";

			$class .= "\tpublic function __construct(" . ucfirst($this->moduleName) . $this->className . "ListParameters \$arg) {\n\n";

				$class .= "\t\t// WHERE\n";
				$class .= "\t\t\$wheres = array();\n";
				$class .= "\t\t\$wheres[] = '" . $this->tableName . ".deleted = 0';\n";
				foreach ($this->fieldArray['keys'] AS $keyName => $key) {
					$class .= "\t\tif (!is_null(\$arg->" . $keyName . ")) { \$wheres[] = '" . $this->tableName . "." . $keyName . " = :" . $keyName . "'; }\n";
				}
				$class .= "\t\tif (!is_null(\$arg->creator)) { \$wheres[] = '" . $this->tableName . ".creator = :creator'; }\n";
				$class .= "\t\tif (!is_null(\$arg->created)) { \$wheres[] = '" . $this->tableName . ".created = :created'; }\n";
				$class .= "\t\tif (!is_null(\$arg->updated)) { \$wheres[] = '" . $this->tableName . ".updated = :updated'; }\n";
				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$class .= "\t\tif (!is_null(\$arg->" . $fieldName . ")) { \$wheres[] = '" . $this->tableName . "." . $fieldName . " = :" . $fieldName . "'; }\n";
				}
				$class .= "\t\t\$where = ' WHERE ' . implode(' AND ', \$wheres);\n\n";

				$class .= "\t\t// SELECTOR\n";
				$class .= "\t\t\$selectorArray = array();\n";
				$class .= "\t\tforeach (\$arg->resultSet AS \$fieldAlias) { \$selectorArray[] = \$fieldAlias['field'] . ' AS ' . \$fieldAlias['alias']; }\n";
				$class .= "\t\t\$selector = implode(', ', \$selectorArray);\n\n";

				$class .= "\t\t// ORDER BY\n";
				$class .= "\t\t\$orderBys = array();\n";
				$class .= "\t\tforeach \$arg->orderBy AS \$fieldSort) { \$orderBys[] = \$fieldSort['field'] . ' ' . \$fieldSort['sort']; }\n\n";
				$class .= "\t\t\$orderBy = '';\n";
				$class .= "\t\tif (!empty(\$orderBys)) { \$orderBy = ' ORDER BY ' . implode(', ', \$orderBys); }\n\n";

				$class .= "\t\t// BUILD QUERY\n";
				$class .= "\t\t\$query = 'SELECT ' . \$selector . ' FROM building_Residence' . \$where . \$orderBy;\n";
				$class .= "\t\tif (\$arg->limit) { \$query .= ' LIMIT ' . (\$arg->offset?\$arg->offset.', ':'') . \$arg->limit; }\n\n";

				$class .= "\t\t// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY\n";
				$class .= "\t\t\$nucleus = Nucleus::getInstance();\n";
				$class .= "\t\t\$statement = \$nucleus->database->prepare(\$query);\n";
				foreach ($this->fieldArray['keys'] AS $keyName => $key) {
					$class .= "\t\tif (!is_null(\$arg->" . $keyName . ")) { \$statement->bindParam(':" . $keyName . "', \$arg->" . $keyName . ", PDO::" . ($key['type']=="int"?"PARAM_INT":"PARAM_STR") . "); }\n";
				}
				$class .= "\t\tif (!is_null(\$arg->creator)) { \$statement->bindParam(':creator', \$arg->creator, PDO::PARAM_INT); }\n";
				$class .= "\t\tif (!is_null(\$arg->created)) { \$statement->bindParam(':created', \$arg->created, PDO::PARAM_STR); }\n";
				$class .= "\t\tif (!is_null(\$arg->updated)) { \$statement->bindParam(':updated', \$arg->updated, PDO::PARAM_STR); }\n";
				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$class .= "\t\tif (!is_null(\$arg->" . $fieldName . ")) { \$statement->bindParam(':" . $fieldName . "', \$arg->" . $fieldName . ", PDO::" . ($field['type']=="int"?"PARAM_INT":"PARAM_STR") . "); }\n";
				}
				$class .= "\t\t\$statement->execute();\n\n";

				$class .= "\t\t// WRITE QUERY RESULTS TO ARRAY\n";
				$class .= "\t\twhile (\$row = \$statement->fetch()) { \$this->results[] = \$row; }\n\n";

			$class .= "\t}\n\n";

			$class .= "\tpublic function results() {\n\n";
				$class .= "\t\treturn \$this->results;\n";
			$class .= "\t}\n\n";

			$class .= "\tpublic function resultCount() {\n\n";
				$class .= "\t\treturn count(\$this->results);\n";
			$class .= "\t}\n\n";

		$class .= "}";
		
		return  $class;
		
	}

	public function generateListArgumentClass() {

		$class = "final class " . ucfirst($this->moduleName) . $this->className . "ListParameters {\n\n";

			$class .= "\t// list filters\n";
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				$class .= "\tpublic ?" . ($key['type']=="int"?"int":"string") . "$" . $keyName . ";\n";
			}
			$class .= "\tpublic ?int \$siteID;";
			$class .= "\tpublic ?int \$creator;";
			$class .= "\tpublic ?string \$created;";
			$class .= "\tpublic ?string \$updated;";
			foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
				$class .= "\tpublic ?" . ($field['type']=="int"?"int":"string") . " $" . $fieldName . ";\n";
			}
			$class .= "\n";

			$class .= "\t// view parameters\n";
			$class .= "\tpublic ?int \$currentPage;\n";
			$class .= "\tpublic ?int \$numberOfPages;\n\n";

			$class .= "\t// results, order, limit, offset\n";
			$class .= "\tpublic array \$resultSet;\n";
			$class .= "\tpublic array \$orderBy;\n";
			$class .= "\tpublic ?int \$limit;\n";
			$class .= "\tpublic ?int \$offset;\n\n";


			$class .= "\tpublic function __construct() {\n\n";

				$class .= "\t\t// list filters\n";
				foreach ($this->fieldArray['keys'] AS $keyName => $key) {
					$class .= "\t\t\$this->" . $keyName . " = null;\n";
				}
				$class .= "\t\t\$this->siteID = null;\n";
				$class .= "\t\t\$this->creator = null;\n";
				$class .= "\t\t\$this->created = null;\n";
				$class .= "\t\t\$this->updated = null;\n";
				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$class .= "\t\t\$this->" . ($field['type']=="int"?"int":"string") . " $" . $fieldName . " = null;\n";
				}
				$class .= "\n";

				$class .= "\t\t// view parameters\n";
				$class .= "\t\t\$this->currentPage = null;\n";
				$class .= "\t\t\$this->numberOfPages = null;\n\n";

				$class .= "\t\t// results, order, limit, offset\n";
				$class .= "\t\t\$this->resultSet = array();\n";
				$class .= "\t\t\$object = new " . $this->className . "();\n";
				$class .= "\t\tforeach (\$object AS \$key => \$value) {\n";
					$class .= "\t\t\t\$this->resultSet[] = array('field' => '" . $this->tableName . ".'.\$key, 'alias' => \$key);\n";
				$class .= "\t\t}\n";
				$class .= "\t\t\$this->orderBy = array(\n";
					$class .= "\t\t\tarray('field' => '" . $this->tableName . ".created', 'sort' => 'DESC')\n";
				$class .= "\t\t);\n";
				$class .= "\t\t\$this->limit = null;\n";
				$class .= "\t\t\$this->offset = null;\n\n";

			$class .= "\t}\n\n";

		$class .= "}";

		return $class;

	}

	public function compileModelFile() {

		$fileComponent = array();
		$fileComponent[] = "/*\n\n".$this->generateSchema()."\n\n*/";
		$fileComponent[] = $this->generateModelClass();
		$fileComponent[] = $this->generateListClass();
		$fileComponent[] = $this->generateListArgumentClass();

		$file = "<?php\n\n" . implode("\n\n", $fileComponent) . "\n\n?>";

		return htmlentities($file);

	}

	public function compileViewFile() {}

	public function compileControllerFile() {}

}

final class CodeGeneratorArguments {

	public string $moduleName;
	public string $className;
	public bool $extendsORM;
	public array $fieldArray;

	public function __construct() {

		$this->moduleName = 'module'; // eg 'inventory' etc
		$this->className = 'Component'; // eg 'Product' etc
		$this->extendsORM = true;
		$this->scope = 'site'; // [site|global]
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