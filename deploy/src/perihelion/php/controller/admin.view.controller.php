<?php

class AdminViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function getView() {
		
		$role = Auth::getUserRole();
		if ($role != 'siteAdmin') { die("You do not have view permissions for the admin module."); }

		$menu = new MenuView($this->urlArray,$this->inputArray,$this->errorArray);
		
		if ($this->urlArray[1] == 'audit') {

			$view = new AuditView($this->urlArray,$this->inputArray,$this->errorArray);

			if (!empty($_SESSION['admin']['audit']['siteID'])) { $siteID = $_SESSION['admin']['audit']['siteID']; } else { $siteID = null; }
			if (!empty($_SESSION['admin']['audit']['userID'])) { $userID = $_SESSION['admin']['audit']['userID']; } else { $userID = null; }
			if (!empty($_SESSION['admin']['audit']['auditObject'])) { $auditObject = $_SESSION['admin']['audit']['auditObject']; } else { $auditObject = null; }
			if (!empty($_SESSION['admin']['audit']['auditObjectID'])) { $auditObjectID = $_SESSION['admin']['audit']['auditObjectID']; } else { $auditObjectID = null; }
			if (!empty($_SESSION['admin']['audit']['startDate'])) { $startDate = $_SESSION['admin']['audit']['startDate']; } else { $startDate = null; }
			if (!empty($_SESSION['admin']['audit']['endDate'])) { $endDate = $_SESSION['admin']['audit']['endDate']; } else { $endDate = null; }
			if (!empty($_SESSION['admin']['audit']['auditAction'])) { $auditAction = $_SESSION['admin']['audit']['auditAction']; } else { $auditAction = null; }
			return $menu->adminSubMenu() . $view->auditTrail('admin', $siteID, $userID, $auditObject, $auditObjectID, $startDate, $endDate, $auditAction);

		}
		
		if ($this->urlArray[1] == 'uptime') {

			$view = new UptimeView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->uptime('admin');

		}
		
		if ($this->urlArray[1] == 'not-found') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'server') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->server(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'language') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->lang();

		}
		
		if ($this->urlArray[1] == 'geography') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'currency') {
	
			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'blacklist') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}

		if ($this->urlArray[1] == 'cron') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->cron();

		}

		if ($this->urlArray[1] == 'dev') {

			$view = new CodeGeneratorView($this->urlArray,$this->inputArray,$this->errorArray);
			$arg = new CodeGeneratorArguments();

			if (isset($this->inputArray['code-generator-submit'])) {

				$input = $this->inputArray;

				$arg->moduleName = $input['moduleName'];
				$arg->className = $input['className'];
				$arg->scope = $input['scope'];

				if (isset($input['extendsORM'])) { $arg->extendsORM = true; } else { $arg->extendsORM = false; }

				$arg->fieldArray = array();

				foreach ($input['keys'] AS $oldKeyName => $key) {

					$keyName = $key['keyName'];
					$arg->fieldArray['keys'][$keyName]['type'] = $key['type'];
					$arg->fieldArray['keys'][$keyName]['default'] = $key['default'];
					$arg->fieldArray['keys'][$keyName]['default-value'] = $key['default-value'];

					if (isset($key['primary'])) { $arg->fieldArray['keys'][$keyName]['primary'] = true; }
					else { $arg->fieldArray['keys'][$keyName]['primary'] = false; }

					if (isset($key['auto-increment'])) { $arg->fieldArray['keys'][$keyName]['auto-increment'] = true; }
					else { $arg->fieldArray['keys'][$keyName]['auto-increment'] = false; }

					if (isset($key['nullable'])) { $arg->fieldArray['keys'][$keyName]['nullable'] = true; }
					else { $arg->fieldArray['keys'][$keyName]['nullable'] = false; }

					if (isset($key['form'])) { $arg->fieldArray['keys'][$keyName]['form'] = true; }
					else { $arg->fieldArray['keys'][$keyName]['form'] = false; }

					if (isset($key['list'])) { $arg->fieldArray['keys'][$keyName]['list'] = true; }
					else { $arg->fieldArray['keys'][$keyName]['list'] = false; }

					if (isset($key['filter'])) { $arg->fieldArray['keys'][$keyName]['filter'] = true; }
					else { $arg->fieldArray['keys'][$keyName]['filter'] = false; }

				}

				foreach ($input['fields'] AS $fieldName => $field) {

					$fieldName = $field['fieldName'];
					$arg->fieldArray['fields'][$fieldName]['type'] = $field['type'];

					if (!empty($field['parameter'])) { $arg->fieldArray['fields'][$fieldName]['parameter'] = $field['parameter']; }
					else { $arg->fieldArray['fields'][$fieldName]['parameter'] = null; }

					$arg->fieldArray['fields'][$fieldName]['default'] = $field['default'];
					$arg->fieldArray['fields'][$fieldName]['default-value'] = $field['default-value'];

					if (isset($field['nullable'])) { $arg->fieldArray['fields'][$fieldName]['nullable'] = true; }
					else { $arg->fieldArray['fields'][$fieldName]['nullable'] = false; }

					if (isset($field['form'])) { $arg->fieldArray['fields'][$fieldName]['form'] = true; }
					else { $arg->fieldArray['fields'][$fieldName]['form'] = false; }

					if (isset($field['list'])) { $arg->fieldArray['fields'][$fieldName]['list'] = true; }
					else { $arg->fieldArray['fields'][$fieldName]['list'] = false; }

					if (isset($field['filter'])) { $arg->fieldArray['fields'][$fieldName]['filter'] = true; }
					else { $arg->fieldArray['fields'][$fieldName]['filter'] = false; }

				}

			}

			return $menu->adminSubMenu() . $view->codeGeneratorForm($arg) . $view->codeGeneratorResults($arg);

		}

	}
	
}

?>