<?php

/*

CREATE TABLE `perihelion_Person` (
  `personID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NULL,
  `deleted` int NOT NULL,
  `personObject` varchar NOT NULL,
  `personObjectID` int NULL,
  `personLastNameEnglish` varchar(255) NULL,
  `personFirstNameEnglish` varchar(255) NULL,
  `personLastNameJapanese` varchar(255) NULL,
  `personFirstNameJapanese` varchar(255) NULL,
  `personLastNameJapaneseReading` varchar(255) NULL,
  `personFirstNameJapaneseReading` varchar(255) NULL,
  `personJobTitle` varchar(100) NULL,
  `personDivision` varchar(100) NULL,
  `personOffice` varchar(100) NULL,
  `personHomepage` varchar(255) NULL,
  `personHomeTelephone` varchar(50) NULL,
  `personMobileTelephone` varchar(50) NULL,
  `personOfficeTelephone` varchar(50) NULL,
  `personFax` varchar(50) NULL,
  `personMemo` text NULL,
  `personEmail1` varchar(100) NULL,
  `personEmail2` varchar(100) NULL,
  `personEmail3` varchar(100) NULL,
  PRIMARY KEY (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

*/

final class PerihelionPersonViewController {

	private array $loc;
	private array $input;
	private array $modules;
	private array $errors;
	private array $messages;

	public function __construct(array $loc = array(), array $input = array(), array $modules = array(), array $errors = array(), array $messages = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = $errors;
		$this->messages = $messages;

	}

	public function getView() {

		$loc = $this->loc;
		$input = $this->input;
		$modules = $this->modules;
		$errors = $this->errors;
		$messages = $this->messages;

		if ($loc[0] == 'perihelion' && $loc[1] == 'person') {

			$view = new PerihelionPersonView($loc, $input, $modules, $errors, $messages);
			$panko = new BreadcrumbsView($loc, array('highlight'), array(), array('perihelion'));

			// /perihelion/person/create/
			if ($loc[2] == 'create') {
				return $panko->breadcrumbs() . $view->perihelionPersonForm('create');
			}

			// /perihelion/person/update/<personID>/
			if ($loc[2] == 'update' && is_numeric($loc[3])) {
				return $panko->breadcrumbs() . $view->perihelionPersonForm('update', $loc[3]);
			}

			// /perihelion/person/confirm-delete/<personID>/
			if ($loc[2] == 'confirm-delete' && is_numeric($loc[3])) {
				return $panko->breadcrumbs() . $view->perihelionPersonConfirmDelete($loc[3]);
			}

			// /perihelion/person/
			$arg = new PerihelionPersonListParameters();
			if (isset($_SESSION['perihelion']['person']['filters'])) {
				foreach ($_SESSION['perihelion']['person']['filters'] AS $filterKey => $filterValue) {
					if (property_exists($arg, $filterKey) && !empty($filterValue)) { $arg->$filterKey = $filterValue; }
				}
			}
			$list = new PerihelionPersonList($arg);

			$arg->currentPage = 1;
			$arg->numberOfPages = ceil($list->resultCount()/25);
			$arg->limit = 25;
			$arg->offset = 0;

			if (is_numeric($loc[2]) && $loc[2] <= $arg->numberOfPages) {
				$currentPage = $loc[2];
				$arg->currentPage = $currentPage;
				$arg->offset = 25 * ($currentPage- 1);
			}

			return $panko->breadcrumbs() . $view->perihelionPersonList($arg);

		}

	}

}

?>