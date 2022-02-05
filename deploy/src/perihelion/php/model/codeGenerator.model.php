<?php

final class CodeGenerator {

	private string $moduleName;
	private string $className;
	private bool $extendsORM;
	private string $scope;
	private array $fieldArray;

	private string $tableName;
	private String $classNameHyphens;
	private String $classNameUnderscore;
	private array $filters;

	public function __construct(CodeGeneratorArguments $arg) {

		$this->moduleName = $arg->moduleName;
		$this->className = $arg->className;
		$this->extendsORM = $arg->extendsORM;
		$this->scope = $arg->scope;
		$this->fieldArray = $arg->fieldArray;

		$this->tableName = $this->moduleName . "_" . $this->className;
		$this->classNameHyphens = StringUtilities::camelToHyphen($this->className);
		$this->classNameUnderscore = StringUtilities::camelToUnderscore($this->className);

		$this->filters = array();
		foreach ($this->fieldArray['keys'] AS $keyName => $key) {
			if ($key['filter']) { $this->filters[] = $keyName; }
		}
		foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
			if ($field['filter']) { $this->filters[] = $fieldName; }
		}

	}

	/* ======= SCHEMA ======= */

	private function generateSchema() : string {

		$schema = "CREATE TABLE `" . $this->moduleName . "_"  . $this->className . "` (\n";

			$primaryKeys = array();
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				$schema .= "  `" . $keyName . "` " . $key['type'] . " " . $key['default'] . ($key['auto-increment']?" AUTO_INCREMENT":"") . ",\n";
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
				$schema .= "  PRIMARY KEY (`" . implode("`, `", $primaryKeys) . "`)\n";
			}

		$schema .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

		return $schema;

	}

	/* ======= MODEL ======= */

	private function generateModelClass() : string {

		$model = "final class " . $this->className . ($this->extendsORM?" extends ORM":"") . " {\n\n";

			$primaryKeys = array();
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				$model .= "\tpublic " . ($key['type']=="int"?"?int":"?string") . " $" . $keyName . ";\n";
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
					$model .= "\t\t\$this->" . $keyName . " = null;\n";
				}

				$model .= "\t\t\$this->siteID = \$_SESSION['siteID'];\n";
				$model .= "\t\t\$this->creator = \$_SESSION['userID'];\n";
				$model .= "\t\t\$this->created = \$dt->format('Y-m-d H:i:s');\n";
				$model .= "\t\t\$this->updated = null;\n";
				$model .= "\t\t\$this->deleted = 0;\n";

				foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
					$model .= "\t\t\$this->" . $fieldName . " = " . $this->defaultValueDecoder($field['default-value']) . ";\n";
				}

				$model .= "\n";

				$model .= "\t\tif (";
					if (!empty($primaryKeys)) {
						$parameters = array();
						foreach ($primaryKeys AS $primaryKey) {
							$parameters[] = "!is_null($" . $primaryKey . ")";
						}
						$model .= implode(" && ", $parameters);
					}
				$model .= ") {\n\n";

					$model .= "\t\t\t\$nucleus = Nucleus::getInstance();\n\n";

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
					if ($this->scope == 'site') {
						$model .= "\t\t\t\$statement->bindParam(':siteID', \$_SESSION['siteID'], PDO::PARAM_INT);\n";
					}
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

	private function generateListClass() : string {

		$class = "final class " . ucfirst($this->moduleName) . $this->className . "List {\n\n";

			$class .= "\tprivate array \$results = array();\n\n";

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
				$class .= "\t\tforeach (\$arg->orderBy AS \$fieldSort) { \$orderBys[] = \$fieldSort['field'] . ' ' . \$fieldSort['sort']; }\n\n";
				$class .= "\t\t\$orderBy = '';\n";
				$class .= "\t\tif (!empty(\$orderBys)) { \$orderBy = ' ORDER BY ' . implode(', ', \$orderBys); }\n\n";

				$class .= "\t\t// BUILD QUERY\n";
				$class .= "\t\t\$query = 'SELECT ' . \$selector . ' FROM " . $this->tableName . "' . \$where . \$orderBy;\n";
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

			$class .= "\tpublic function results() : array {\n\n";
				$class .= "\t\treturn \$this->results;\n\n";
			$class .= "\t}\n\n";

			$class .= "\tpublic function resultCount() : int {\n\n";
				$class .= "\t\treturn count(\$this->results);\n\n";
			$class .= "\t}\n\n";

		$class .= "}";
		
		return  $class;
		
	}

	private function generateListArgumentClass() : string {

		$class = "final class " . ucfirst($this->moduleName) . $this->className . "ListParameters {\n\n";

			$class .= "\t// list filters\n";
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				$class .= "\tpublic ?" . ($key['type']=="int"?"int":"string") . " $" . $keyName . ";\n";
			}
			$class .= "\tpublic ?int \$siteID;\n";
			$class .= "\tpublic ?int \$creator;\n";
			$class .= "\tpublic ?string \$created;\n";
			$class .= "\tpublic ?string \$updated;\n";
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
					$class .= "\t\t\$this->" . $fieldName . " = null;\n";
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

	public function compileModelFile() : string {

		$fileComponent = array();
		$fileComponent[] = "/*\n\n".$this->generateSchema()."\n\n*/";
		$fileComponent[] = $this->generateModelClass();
		$fileComponent[] = $this->generateListClass();
		$fileComponent[] = $this->generateListArgumentClass();

		$file = "<?php\n\n" . implode("\n\n", $fileComponent) . "\n\n?>";

		return htmlentities($file);

	}

	/* ======= VIEW ======= */

	private function generateViewClass($functions) : string {

		$view = "final class " . ucfirst($this->moduleName) . $this->className . "View {\n\n";

			$view .= "\tprivate array \$loc;\n";
			$view .= "\tprivate array \$input;\n";
			$view .= "\tprivate array \$modules;\n";
			$view .= "\tprivate array \$errors;\n";
			$view .= "\tprivate array \$messages;\n\n";

			$view .= "\tpublic function __construct(\$loc = array(), \$input = array(), \$modules = array(), \$errors = array(), \$messages = array()) {\n\n";

				$view .= "\t\t\$this->loc = \$loc;\n";
				$view .= "\t\t\$this->input = \$input;\n";
				$view .= "\t\t\$this->modules = \$modules;\n";
				$view .= "\t\t\$this->errors = \$errors;\n";
				$view .= "\t\t\$this->messages = \$messages;\n\n";

			$view .= "\t}\n\n";

			$view .= $functions;

		$view .= "\n\n}";

		return $view;

	}

	private function generateViewForm() : string {

		$keys = array();
		foreach ($this->fieldArray['keys'] AS $keyName => $key) { $keys[] = $keyName; }

		$instance = lcfirst($this->className);

		$view = "\tpublic function " . $this->moduleName . $this->className . "Form(\$type, ";
			$params = array();
			foreach ($keys AS $keyName) { $params[] = "\$" . $keyName . " = null"; }
			$view .= implode(", ", $params);
		$view .= ") {\n\n";

			$view .= "\t\t\$hidden = '';\n";
			$view .= "\t\tif (\$type == 'update' && \$" . implode(" && $",$keys) . ") {\n";
				foreach ($keys AS $keyName) {
					$view .= "\t\t\t\$hidden .= '<input type=\"hidden\" name=\"" . $keyName . "\" value=\"' . \$" . $keyName . " . '\">';\n";
				}
			$view .= "\t\t}\n\n";

			$view .= "\t\t\$" . $instance . " = new " . $this->className . "($" . implode(", $", $keys) . ");\n";
			$view .= "\t\tif (!empty(\$this->input)) {\n";
				$view .= "\t\t\tforeach(\$this->input AS \$key => \$value) { if(isset(\$" . $instance . "->\$key)) { \$" . $instance . "->\$key = \$value; } }\n";
			$view .= "\t\t}\n\n";

			$view .= "\t\t\$form = '\n\n";
				$view .= "\t\t\t<form id=\"" . $this->classNameUnderscore . "_' . \$type . '_form\" method=\"post\" action=\"' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/' . \$type . '/'  . ";
			$view .= "($" . implode("&&$",$keys) . "?$" . implode(".'/'.$",$keys) . ".'/':'') . '\">\n\n";

			$view .= "\t\t\t' . \$hidden . '\n\n";

			$formKeys = array();
			foreach ($this->fieldArray['keys'] AS $keyName => $key) {
				if ($key['form'] == true) { $formKeys[$keyName] = $key; }
			}
			if (!empty($formKeys)) {
				$view .= "\t\t\t<div class=\"form-row\">\n\n";
					foreach ($formKeys AS $keyName => $key) {
						$view .= $this->formGroup($instance, $keyName, $key['type'], );
					}
				$view .= "\t\t\t</div>\n\n";
			}

			$formFields = array();
			$formTextareas = array();
			foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
				if ($field['form'] == true) {
					if ($field['type'] == 'text') {
						$formTextareas[$fieldName] = $field;
					} else {
						$formFields[$fieldName] = $field;
					}
				}
			}
			if (!empty($formFields)) {
				$view .= "\t\t\t<div class=\"form-row\">\n\n";
					foreach ($formFields AS $fieldName => $field) {
						$view .= $this->formGroup($instance, $fieldName, $field['type']);
					}
				$view .= "\t\t\t</div>\n\n";
			}
			if (!empty($formTextareas)) {
				foreach ($formTextareas AS $fieldName => $textarea) {
					$view .= "\t\t\t<div class=\"form-row\">\n\n";
						$view .= $this->formGroup($instance, $fieldName, $textarea['type'], array('col-12'));
					$view .= "\t\t\t</div>\n\n";
				}
			}

			$view .= "\t\t\t<div class=\"form-row\">\n\n";

				$view .= "\t\t\t\t<div class=\"form-group col-12 col-sm-4 col-md-3\">\n";
					$view .= "\t\t\t\t\t<a href\"/' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/\" class=\"btn btn-block btn-outline-secondary\" role=\"button\">' . Lang::getLang('returnToList') . '</a>\n";
				$view .= "\t\t\t\t</div>\n\n";

				$view .= "\t\t\t\t<div class=\"form-group col-12 col-sm-4 col-md-3 offset-md-3\">\n";
					$view .= "\t\t\t\t\t<button type=\"submit\" name=\"" . $this->classNameHyphens . "-' . \$type . '\" class=\"btn btn-block btn-outline-'. (\$type=='create'?'success':'primary') . '\">' . Lang::getLang(\$type) . '</button>\n";
				$view .= "\t\t\t\t</div>\n\n";

				$view .= "\t\t\t\t<div class=\"form-group col-12 col-sm-4 col-md-3\">\n";
					$view .= "\t\t\t\t\t<a href\"/' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/\" class=\"btn btn-block btn-outline-secondary\" role=\"button\">' . Lang::getLang('cancel') . '</a>\n";
				$view .= "\t\t\t\t</div>\n\n";

			$view .= "\t\t\t</div>\n\n";

		$view .= "\t\t\t</form>\n\n";

		$view .= "\t\t';\n\n";

		$view .= "\t\treturn \$form;\n\n";

		$view .= "\t}";

		return $view;

	}

	private function generateViewList(): string {

		$cols = array();
		$filters = array();
		foreach ($this->fieldArray['keys'] AS $keyName => $key) {
			if ($key['list']) { $cols[] = $keyName; }
			if ($key['filter']) { $filters[] = $keyName; }
		}
		foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
			if ($field['list']) { $cols[] = $fieldName; }
			if ($field['filter']) { $filters[] = $fieldName; }
		}

		$list = "\tpublic function " . $this->moduleName . $this->className . "List(";
			$list .= ucfirst($this->moduleName) . $this->className . "ListParameters \$arg";
		$list .= ") {\n\n";

			$list .= "\t\t\$list = '\n\n";

				if (!empty($this->filters)) {
					$list .= "\t\t\t<form>\n";
						$list .= "\t\t\t\t<div class=\"form-row\">\n";
							foreach($this->filters AS $filterKey) {
								$list .= $this->generateViewFilterDropdown($filterKey);
							}
							$list .= "\t\t\t\t\t<div class=\"form-group col-12 col-sm-6 col-md-3\">\n";
								$list .= "\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-outline-primary btn-block\">\n";
							$list .= "\t\t\t\t\t</div>\n";
						$list .= "\t\t\t\t</div>\n";
					$list .= "\t\t\t</form>\n\n";
				}

				$list .= "\t\t\t<div class=\"row mb-3\">\n";
					$list .= "\t\t\t\t<div class=\"col-12 col-md-8 col-lg-6\">\n";
						$list .= "\t\t\t\t\t' . PaginationView::paginate(\$arg->numberOfPages,\$arg->currentPage,'/' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/') . '\n";
					$list .= "\t\t\t\t</div>\n";
					$list .= "\t\t\t\t<div class\"col-12 col-md-4 col-lg-2 offset-lg-4\">\n";
						$list .= "\t\t\t\t\t<a href=\"/' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/create/\" class=\"btn btn-block btn-outline-success btn-sm\"><span class=\"fas fa-plus\"></span> ' . Lang::getLang('create') . '</a>\n";
					$list .= "\t\t\t\t</div>\n";
				$list .= "\t\t\t</div>\n\n";

				$list .= "\t\t\t<div class=\"table-container mb-3\">\n";
					$list .= "\t\t\t\t<div class=\"table-responsive\">\n";
						$list .= "\t\t\t\t\t<table class=\"table table-bordered table-striped table-sm\">\n";
							$list .= "\t\t\t\t\t\t<thead class\"thead-light\">\n";
								$list .= "\t\t\t\t\t\t\t<tr>\n";
									foreach ($cols AS $colName) {
										$list .= "\t\t\t\t\t\t\t\t<th scope=\"col\" class=\"text-center\">' . Lang::getLang('" . $this->moduleName . $this->className . ucfirst($colName) . "') . '</th>\n";
									}
								$list .= "\t\t\t\t\t\t\t</tr>\n";
							$list .= "\t\t\t\t\t\t</thead>\n";
							$list .= "\t\t\t\t\t\t<tbody>' . \$this->" . $this->moduleName . $this->className . "ListRows(\$arg) . '</tbody>\n";
						$list .= "\t\t\t\t\t</table>\n";
					$list .= "\t\t\t\t</div>\n";
				$list .= "\t\t\t</div>\n\n";

				$list .= "\t\t\t<div class=\"row\">\n";
					$list .= "\t\t\t\t<div class=\"col-12 col-md-8 col-lg-6\">\n";
						$list .= "\t\t\t\t\t' . PaginationView::paginate(\$arg->numberOfPages,\$arg->currentPage,'/' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/') . '\n";
					$list .= "\t\t\t\t</div>\n";
				$list .= "\t\t\t</div>\n\n";

			$list .= "\t\t';\n\n";

			$list .= "\t\t\$card = new CardView('" . $this->moduleName . "_" . $this->classNameUnderscore . "_admin_list',array('container'),'',array('col-12'),Lang::getLang('" . $this->moduleName . $this->className . "List'), \$list);\n";
			$list .= "\t\treturn \$card->card();\n\n";

		$list .= "\t}";

		return $list;

	}

	private function generateViewListRows() : string {

		$keys = array();
		$keyCols = array();
		$fieldCols = array();

		foreach ($this->fieldArray['keys'] AS $keyName => $key) {
			$keys[] = $keyName;
			if ($key['list']) { $keyCols[] = $keyName; }
		}

		foreach ($this->fieldArray['fields'] AS $fieldName => $field) {
			if ($field['list']) { $fieldCols[] = $fieldName; }
		}

		$rows = "\tpublic function " . $this->moduleName . $this->className . "ListRows(";
			$rows .= ucfirst($this->moduleName) . $this->className . "ListParameters \$arg";
		$rows .= ") {\n\n";

			$rows .= "\t\t\$list = new " . ucfirst($this->moduleName) . $this->className . "List(\$arg);\n";
			$rows .= "\t\t\$results = \$list->results();\n\n";
			$rows .= "\t\t\$rows = '';\n\n";

				$rows .= "\t\tforeach (\$results AS \$r) {\n\n";
					$rows .= "\t\t\t\$rows .= '\n\n";

						$rows .= "\t\t\t\t<tr ";
							if (!empty($keys)) {
								$rows .= "id=\"" . $this->moduleName . "_" . $this->classNameUnderscore . "_key_' . \$r['";
									$rows .= implode("'] . '_' . \$r['", $keys);
								$rows .= "'] . '\" ";
							}
							$rows .= "class=\"" . $this->moduleName . "-" . $this->classNameHyphens . "-list-row\"";
							if (!empty($keys)) {
								foreach ($keys AS $keyName) {
									$rows .= " data-row-" . StringUtilities::camelToHyphen($keyName) . "=\"' . \$r['" . $keyName . "'] . '\"";
								}
							}
						$rows .= ">\n";

							foreach ($keyCols AS $keyName) {
								$rows .= "\t\t\t\t\t<th scope=\"row\" class=\"text-center " . $this->moduleName . "-" . $this->classNameHyphens . "-list-cell\" data-cell-" . StringUtilities::camelToHyphen($keyName) . "=\"' . \$r['" . $keyName . "'] . '\">' .\$r['" . $keyName . "'] . '</th>\n";
							}

							foreach ($fieldCols AS $fieldName) {
								$rows .= "\t\t\t\t\t<td class=\"text-center " . $this->moduleName . "-" . $this->classNameHyphens . "-list-cell\" data-cell-" . StringUtilities::camelToHyphen($fieldName) . "=\"' . \$r['" . $fieldName . "'] . '\">' .\$r['" . $fieldName . "'] . '</td>\n";
							}

							$rows .= "\t\t\t\t\t<td class=\"text-center text-nowrap\">\n";

								$rows .= "\t\t\t\t\t\t<a href\"/' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/update/' . \$r['";
									if (!empty($keys)) { $rows .= implode("'] . '/' . \$r['", $keys); }
								$rows .= "'] . '/\" class=\"btn btn-sm btn-outline-primary\">\n";
									$rows .= "\t\t\t\t\t\t\t<span class=\"far fa-edit\"></span>\n";
									$rows .= "\t\t\t\t\t\t\t' . Lang::getLang('update') . '\n";
								$rows .= "\t\t\t\t\t\t</a>\n";

								$rows .= "\t\t\t\t\t\t<a href\"/' . Lang::prefix() . '" . $this->moduleName . "/admin/" . $this->classNameHyphens . "/confirm-delete/' . \$r['";
									if (!empty($keys)) { $rows .= implode("'] . '/' . \$r['", $keys); }
								$rows .= "'] . '/\" class=\"btn btn-sm btn-outline-danger\">\n";
									$rows .= "\t\t\t\t\t\t\t<span class=\"far fa-trash-alt\"></span>\n";
									$rows .= "\t\t\t\t\t\t\t' . Lang::getLang('delete') . '\n";
								$rows .= "\t\t\t\t\t\t</a>\n";

							$rows .= "\t\t\t\t\t</td>\n";

						$rows .= "\t\t\t\t</tr>\n\n";
					$rows .= "\t\t\t';\n\n";
				$rows .= "\t\t}\n\n";




			$rows .= "\t\treturn \$rows;\n\n";

		$rows .= "\t}";

		return $rows;

	}

	private function generateViewFilterFunction() : string {

		// filter
		$ff = "\tpublic function " . $this->moduleName . $this->className . "Filter(\$filterKey) {\n\n";

			$ff .= "\t\t\$arg = new " . ucfirst($this->moduleName) . $this->className . "ListParameters();\n";
			$ff .= "\t\t\$arg->resultSet = array();\n";
			$ff .= "\t\t\$arg->resultSet[] = array('field' => '" . $this->tableName . ".'.\$filterKey, 'alias' => \$filterKey);\n";
			$ff .= "\t\t\$valueList = new " . ucfirst($this->moduleName) . $this->className . "List(\$arg);\n";
			$ff .= "\t\t\$values = \$valueList->results();\n\n";

			$ff .= "\t\t\$filter = '<select name=\"\$filterKey\" class=\"form-control\">';\n";
				$ff .= "\t\t\t\$filter .= '<option value=\"\">' . Lang::getLang(\$filterKey) . '</option>';\n";
				$ff .= "\t\t\tforeach (\$values AS \$value) {\n";
					$ff .= "\t\t\t\t\$filter .= '<option value=\"' . \$value[\$filterKey] . '\">' . \$value[\$filterKey] . '</option>';\n";
				$ff .= "\t\t\t}\n";
			$ff .= "\t\t\$filter .= '</select>';\n\n";

			$ff .= "\t\treturn \$filter;\n\n";

		$ff .= "\t}";

		return $ff;

	}

	private function generateViewFilterDropdown($filterKey) : string {

		// $fd = "\t\t\t" . $this->moduleName . $this->className . "Filter(\$filterKey)";

		$fd = "\t\t\t\t\t<div class=\"form-group col-12 col-sm-6 col-md-3\">\n";
			$fd .= "\t\t\t\t\t\t' . \$this->" . $this->moduleName . $this->className . "Filter('$filterKey') . '\n";
		$fd .= "\t\t\t\t\t</div>\n";

		return $fd;
		
	}

	public function compileViewFile() : string {

		$schema = "/*\n\n".$this->generateSchema()."\n\n*/";

		$functionArray = array();
		$functionArray[] = $this->generateViewForm();
		$functionArray[] = $this->generateViewList();
		$functionArray[] = $this->generateViewListRows();
		$functionArray[] = $this->generateViewFilterFunction();

		$functions = implode("\n\n", $functionArray);
		$view = $this->generateViewClass($functions);
		$file = "<?php\n\n" . $schema . "\n\n" . $view . "\n\n?>";

		return htmlentities($file);

	}

	private function formGroup($instance, $fieldName, $fieldType, $cols = array('col-12','col-sm-6','col-md-4','col-lg-3','col-xl-2')) : string {

		$htmlFieldType = $this->mysqlTypeHtmlTypeDecoder($fieldType);

		$formGroup = "\t\t\t\t<div class=\"form-group " . implode(" ", $cols) . "\">\n";
			$formGroup .= "\t\t\t\t\t<label for=\"" . StringUtilities::camelToUnderscore($fieldName) . "\">' . Lang::getLang('" . $fieldName . "') . '</label>\n";
			if ($fieldType == 'text') {
				$formGroup .= "\t\t\t\t\t<textarea id=\"" . StringUtilities::camelToUnderscore($fieldName) . "\" class=\"form-control\" name=\"" . $fieldName . "\">' . \$" . $instance . "->" . $fieldName . " . '</textarea>\n";
			} else {
				$formGroup .= "\t\t\t\t\t<input type=\"" . $htmlFieldType . "\" id=\"" . StringUtilities::camelToUnderscore($fieldName) . "\" class=\"form-control\" name=\"" . $fieldName . "\" value=\"' . \$" . $instance . "->" . $fieldName . " . '\">\n";
			}
		$formGroup .= "\t\t\t\t</div>\n\n";

		return $formGroup;

	}

	/* ======= CONTROLLERS ======= */

	public function compileControllerFile() {}

	/* ======= UTILITIES ======= */

	private function mysqlTypeHtmlTypeDecoder($fieldType) : string {

		switch($fieldType) {

			case 'int':
				$type = 'number';
				break;
			case 'decimal':
				$type = 'number';
				break;
			case 'varchar':
				$type = 'text';
				break;
			case 'date':
				$type = 'date';
				break;
			case 'datetime':
				$type = 'date';
				break;
			case 'text':
				$type = 'textarea';
				break;
			default:
				$type = $fieldType;
		}

		return $type;

	}

	private function defaultValueDecoder($defaultValue) : string {

		switch($defaultValue) {

			case 'zero':
				$value = '0';
				break;
			case 'null':
				$value = 'null';
				break;
			case 'empty-string':
				$value = '\'\'';
				break;
			case 'current-timestamp':
				$value = 'CURRENT_TIMESTAMP';
				break;
			default:
				$value = 'null';
		}

		return $value;

	}

}

final class CodeGeneratorArguments {

	public string $moduleName;
	public string $className;
	public bool $extendsORM;
	public string $scope;
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
					'default-value' => 'zero',
					'primary' => true,
					'auto-increment' => true,
					'nullable' => false,
					'form' => false,
					'list' => true,
					'filter' => false
				)
			),
			'fields' => array(
				'fieldNameA' => array (
					'type' => 'int',
					'parameter' => null, // int does not take a parameter in MySQL 8
					'default' => 'NOT NULL',
					'default-value' => 'zero',
					'nullable' => false,
					'form' => true,
					'list' => true,
					'filter' => false
				),
				'fieldNameB' => array (
					'type' => 'datetime',
					'parameter' => null,
					'default' => 'NULL',
					'default-value' => 'null',
					'nullable' => true,
					'form' => true,
					'list' => true,
					'filter' => false
				),
				'fieldNameN' => array (
					'type' => 'varchar',
					'parameter' => 255,
					'default' => 'NOT NULL',
					'default-value' => 'empty-string',
					'nullable' => false,
					'form' => true,
					'list' => true,
					'filter' => false
				)
			)
		);

	}

}

?>