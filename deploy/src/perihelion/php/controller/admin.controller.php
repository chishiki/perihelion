<?php

class AdminController {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = array();
		$this->messageArray = array();
		
		if (!Auth::isLoggedIn()) {
			$_SESSION['forward_url'] = $_SERVER['REQUEST_URI'];
			$login = "/" . Lang::prefix() . "login/";
			header("Location: $login");
		}
		
		$role = Auth::getUserRole();
		if ($role != 'siteAdmin') { die(); }
		
	}
	
	public function setState() {

		$cronKey = Config::read('cron.key');

		if ($this->urlArray[0] == 'admin' && $this->urlArray[1] == 'cron' && isset($this->inputArray['cronKey'])) {

			if ($this->inputArray['cronKey'] != $cronKey) { $this->errorArray['cron'][] = 'The cron key is not correct.'; }
			if (!isset($this->inputArray['invoiceYearMonth'])) { $this->errorArray['invoiceYearMonth'][] = 'invoiceYearMonth is not set.'; }
			if (!isset($this->inputArray['invoiceDate'])) { $this->errorArray['invoiceDate'][] = 'invoiceDate is not set.'; }
			if (!isset($this->inputArray['transactionDateTime'])) { $this->errorArray['transactionDateTime'][] = 'transactionDateTime is not set.'; }
			
			if (empty($this->errorArray)) {
				
				$invoiceYearMonth = $this->inputArray['invoiceYearMonth']; // YYYY-MM
				$invoiceDate = $this->inputArray['invoiceDate']; // YYYY-MM-DD
				$transactionDateTime = $this->inputArray['transactionDateTime']; // YYYY-MM-DD H:i:s
				
				PropertyManagement::createMonthlyInvoices($invoiceYearMonth,$invoiceDate);
				PropertyManagement::processMonthlyFees($transactionDateTime);
				
				$this->messageArray[] = Lang::getLang('invoicesHaveBeenCreated');
				$this->messageArray[] = Lang::getLang('monthlyFeesHaveBeenAdded');
				$this->messageArray[] = Lang::getLang('cronJobComplete');
				
				$ioa = new Audit();
				$ioa->auditAction = 'InvoicesCreated';
				$ioa->auditObject = '';
				$ioa->auditObjectID = 0;
				$ioa->auditResult = 'success';
				$ioa->auditNote = '';
				Audit::createAuditEntry($ioa);
				$ioa->auditAction = 'MonthlyFeesCreated';
				Audit::createAuditEntry($ioa);
				
			}

		}
		
		if ($this->urlArray[0] == 'admin' && $this->urlArray[1] == 'audit' && !empty($this->inputArray)) {

			if (isset($this->inputArray['siteID'])) { $_SESSION['admin']['audit']['siteID'] = $this->inputArray['siteID']; }
			if (isset($this->inputArray['userID'])) { $_SESSION['admin']['audit']['userID'] = $this->inputArray['userID']; }
			if (isset($this->inputArray['auditObject'])) { $_SESSION['admin']['audit']['auditObject'] = $this->inputArray['auditObject']; }

		}

	}
	
	public function getErrors() {
		return $this->errorArray;
	}
	
	public function getMessages() {
		return $this->messageArray;
	}
	
}

?>