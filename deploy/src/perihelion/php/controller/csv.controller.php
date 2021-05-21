<?php

class CSVController {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		
		$authorizedRoles = array('siteAdmin','siteManager','siteAccountant','siteStaff');
		$role = Auth::getUserRole();
		if (!in_array($role,$authorizedRoles)) { die("You do not have permissions sufficient to export this data."); }

	}

	public function export() {

		$filename = 'export';
		$columns = array();
		$rows = array();

		foreach ($this->moduleArray AS $moduleName) {
			
			if ($this->urlArray[1] == $moduleName) {

				$moduleExportController = ModuleUtilities::moduleToClassName($moduleName, 'ExportController');
		        $mec = new $moduleExportController($this->urlArray, $this->inputArray, $this->moduleArray);
		        
		        $filename = $mec->filename();
		        $columns = $mec->columns();
		        $rows = $mec->rows();
		        
		    }
		    
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $filename . '.csv');
		header('Cache-Control: no-cache, must-revalidate');
		$output = fopen('php://output', 'w');
		fputcsv($output, $columns);
		foreach ($rows AS $row) { fputcsv($output, $row); }
		
	}

}

?>