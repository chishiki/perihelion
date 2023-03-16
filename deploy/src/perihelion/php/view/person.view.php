<?php

/*

CREATE TABLE `perihelion_Person` (
  `personID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NULL,
  `deleted` int NOT NULL,
  `personObject` varchar(50) NOT NULL,
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
  `personAcceptsEmail` int NOT NULL,
  `personGender` varchar(6) NOT NULL,
  `personBirthday` date DEFAULT NULL,
  `personAgeGroup` varchar(20) DEFAULT NULL,
  `personGuardian` varchar(255) DEFAULT NULL,
  `personActive` int NOT NULL,
  PRIMARY KEY (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

*/

final class PerihelionPersonView {

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

	public function perihelionPersonForm(
		string $type,
		Person $person,
		?string $formAction = null,
		bool $card = false
	):string {

		$hidden = '<input type="hidden" name="type" value="' . $type . '">';
		if ($type == 'update') {
			$hidden .= '<input type="hidden" name="personID" value="' . $person->personID . '">';
		}
		$hidden .= '<input type="hidden" name="personObject" value="' . $person->personObject . '">';
		$hidden .= '<input type="hidden" name="personObjectID" value="' . $person->personObjectID . '">';

		$form = '

			<form id="person_' . $type . '_form" method="post" action="' . ($formAction?:'') . '">

				' . $hidden . '
	
				<div class="form-row">
	
					<div class="form-group col-9 col-sm-9 col-md-8 col-lg-6 col-xl-4">
						<label for="person_object">' . Lang::getLang('personObject') . '</label>
						<input type="text" id="person_object" class="form-control" name="personObject" value="' . $person->personObject . '" readonly>
					</div>
	
					<div class="form-group col-3 col-sm-3 col-md-4 col-lg-2 col-xl-1">
						<label for="person_object_id">' . Lang::getLang('personObjectID') . '</label>
						<input type="number" id="person_object_id" class="form-control" name="personObjectID" value="' . $person->personObjectID . '" readonly>
					</div>
					
				</div>
				
				<hr />
				
				<div class="form-row">
	
					<div class="form-group col-12 col-sm-6 col-lg-4">
						<label for="person_last_name_english">' . Lang::getLang('personLastNameEnglish') . '</label>
						<input type="text" id="person_last_name_english" class="form-control" name="personLastNameEnglish" value="' . $person->personLastNameEnglish . '">
					</div>
	
					<div class="form-group col-12 col-sm-6 col-lg-4">
						<label for="person_first_name_english">' . Lang::getLang('personFirstNameEnglish') . '</label>
						<input type="text" id="person_first_name_english" class="form-control" name="personFirstNameEnglish" value="' . $person->personFirstNameEnglish . '">
					</div>
					
				</div>
				
				<div class="form-row">
	
					<div class="form-group col-12 col-sm-6 col-lg-4">
						<label for="person_last_name_japanese">' . Lang::getLang('personLastNameJapanese') . '</label>
						<input type="text" id="person_last_name_japanese" class="form-control" name="personLastNameJapanese" value="' . $person->personLastNameJapanese . '">
					</div>
	
					<div class="form-group col-12 col-sm-6 col-lg-4">
						<label for="person_first_name_japanese">' . Lang::getLang('personFirstNameJapanese') . '</label>
						<input type="text" id="person_first_name_japanese" class="form-control" name="personFirstNameJapanese" value="' . $person->personFirstNameJapanese . '">
					</div>
					
				</div>
				
				<div class="form-row">
	
					<div class="form-group col-12 col-sm-6 col-lg-4">
						<label for="person_last_name_japanese_reading">' . Lang::getLang('personLastNameJapaneseReading') . '</label>
						<input type="text" id="person_last_name_japanese_reading" class="form-control" name="personLastNameJapaneseReading" value="' . $person->personLastNameJapaneseReading . '">
					</div>
	
					<div class="form-group col-12 col-sm-6 col-lg-4">
						<label for="person_first_name_japanese_reading">' . Lang::getLang('personFirstNameJapaneseReading') . '</label>
						<input type="text" id="person_first_name_japanese_reading" class="form-control" name="personFirstNameJapaneseReading" value="' . $person->personFirstNameJapaneseReading . '">
					</div>
	
				</div>
				
				<hr />
				
				<div class="form-row">
				
					<div class="form-group col-12 col-sm-6 col-lg-3">
						<label for="person_job_title">' . Lang::getLang('personJobTitle') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-user-tie"></span>
							</div>
							<input type="text" id="person_job_title" class="form-control" name="personJobTitle" value="' . $person->personJobTitle . '">
						</div>
					</div>
	
					<div class="form-group col-12 col-sm-6 col-lg-3">
						<label for="person_division">' . Lang::getLang('personDivision') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-network-wired"></span>
							</div>
							<input type="text" id="person_division" class="form-control" name="personDivision" value="' . $person->personDivision . '">
						</div>
					</div>
	
					<div class="form-group col-12 col-sm-6 col-lg-3">
						<label for="person_office">' . Lang::getLang('personOffice') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-building"></span>
							</div>
							<input type="text" id="person_office" class="form-control" name="personOffice" value="' . $person->personOffice . '">
						</div>
					</div>
					
				</div>
				
				<hr />
				
				<div class="form-row">
	
					<div class="form-group col-12 col-sm-6 col-lg-3">
						<label for="person_mobile_telephone">' . Lang::getLang('personMobileTelephone') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-mobile-alt"></span>
							</div>
							<input type="tel" id="person_mobile_telephone" class="form-control" name="personMobileTelephone" value="' . $person->personMobileTelephone . '">
						</div>
					</div>
	
					<div class="form-group col-12 col-sm-6 col-lg-3">
						<label for="person_office_telephone">' . Lang::getLang('personOfficeTelephone') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-phone"></span>
							</div>
							<input type="tel" id="person_office_telephone" class="form-control" name="personOfficeTelephone" value="' . $person->personOfficeTelephone . '">
						</div>
					</div>
					
					<div class="form-group col-12 col-sm-6 col-lg-3">
						<label for="person_home_telephone">' . Lang::getLang('personHomeTelephone') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-phone"></span>
							</div>
							<input type="tel" id="person_home_telephone" class="form-control" name="personHomeTelephone" value="' . $person->personHomeTelephone . '">
						</div>
						
					</div>
	
					<div class="form-group col-12 col-sm-6 col-lg-3">
						<label for="person_fax">' . Lang::getLang('personFax') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-fax"></span>
							</div>
							<input type="tel" id="person_fax" class="form-control" name="personFax" value="' . $person->personFax . '">
						</div>
					</div>
					
				</div>
				
				<hr />
				
				<div class="form-row">
	
					<div class="form-group col-12 col-md-4">
						<label for="person_email1">' . Lang::getLang('personEmail1') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="far fa-envelope"></span>
							</div>
							<input type="email" id="person_email1" class="form-control" name="personEmail1" value="' . $person->personEmail1 . '">
						</div>
					</div>
	
					<div class="form-group col-12 col-md-4">
						<label for="person_email2">' . Lang::getLang('personEmail2') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="far fa-envelope"></span>
							</div>
							<input type="email" id="person_email2" class="form-control" name="personEmail2" value="' . $person->personEmail2 . '">
						</div>
					</div>
	
					<div class="form-group col-12 col-md-4">
						<label for="person_email3">' . Lang::getLang('personEmail3') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="far fa-envelope"></span>
							</div>
							<input type="email" id="person_email3" class="form-control" name="personEmail3" value="' . $person->personEmail3 . '">
						</div>
					</div>
	
				</div>
				
				<div class="form-row">

					<div class="form-group col-12 col-md-4">
						<div class="form-check">
							<input type="checkbox" id="person_accepts_email" name="personAcceptsEmail" class="form-check-input" value="1"' . ($person->personAcceptsEmail?' checked':'') . '>
							<label class="form-check-label" for="person_accepts_email">' . Lang::getLang('personAcceptsEmail') . '</label>
						</div>
					</div>
					
				</div>
				
				<hr />
				
				<div class="form-row">
				
					<div class="form-group col-12 col-md-6">
						<label for="person_homepage">' . Lang::getLang('personHomepage') . '</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">
								<span class="fas fa-desktop"></span>
							</div>
							<input type="url" id="person_homepage" class="form-control" name="personHomepage" value="' . $person->personHomepage . '" placeholder="http://example.com/">
						</div>
					</div>
					
				</div>
				
				<hr />
	
				<div class="form-row">
	
					<div class="form-group col-12">
						<label for="person_memo">' . Lang::getLang('personMemo') . '</label>
						<textarea id="person_memo" class="form-control" name="personMemo">' . $person->personMemo . '</textarea>
					</div>
	
				</div>
				
				<hr />
				
				<div class="form-row">

					<div class="form-group col-12 col-md-4">
						<div class="form-check">
							<input type="checkbox" id="person_active" name="personActive" class="form-check-input" value="1"' . ($person->personActive?' checked':'') . '>
							<label class="form-check-label" for="person_active">' . Lang::getLang('personActive') . '</label>
						</div>
					</div>
					
				</div>
				
				<hr />
	
				<div class="form-row">
	
					<div class="form-group col-12 col-sm-4 col-lg-3">
						<a href="/' . Lang::prefix() . 'perihelion/person/" class="btn btn-block btn-outline-secondary" role="button">
							<span class="fas fa-arrow-left"></span>
							' . Lang::getLang('returnToList') . '
						</a>
					</div>
	
					<div class="form-group col-12 col-sm-4 col-lg-3 offset-lg-3">
						<button type="submit" name="perihelion-person-' . $type . '" class="btn btn-block btn-outline-'. ($type=='create'?'success':'primary') . '">
							<span class="far fa-save"></span>
							' . Lang::getLang($type) . '
						</button>
					</div>
	
					<div class="form-group col-12 col-sm-4 col-lg-3">
						<a href="/' . Lang::prefix() . 'perihelion/person/" class="btn btn-block btn-outline-secondary" role="button">
							<span class="fas fa-times"></span>
							' . Lang::getLang('cancel') . '
						</a>
					</div>
	
				</div>

			</form>

		';

		if ($card) {

			$card = new CardView('perihelion_person_form',array('container-fluid'),'',array('col-12'),Lang::getLang('perihelionPerson' . ucfirst($type)), $form);
			return $card->card();

		} else {

			return $form;

		}



	}

	public function perihelionPersonConfirmDelete($personID) {

		$person = new Person($personID);
		if (!empty($this->input)) {
			foreach($this->input AS $key => $value) { if(isset($person->$key)) { $person->$key = $value; } }
		}

		$form = '

			<form id="person_confirm_delete_form" method="post" action="/' . Lang::prefix() . 'perihelion/person/delete/'.$personID.'/">

			<input type="hidden" name="personID" value="' . $personID . '">

			<div class="form-row">

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_object">' . Lang::getLang('personObject') . '</label>
					<input type="text" id="person_object" class="form-control" name="personObject" value="' . $person->personObject . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_object_id">' . Lang::getLang('personObjectID') . '</label>
					<input type="number" id="person_object_id" class="form-control" name="personObjectID" value="' . $person->personObjectID . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_last_name_english">' . Lang::getLang('personLastNameEnglish') . '</label>
					<input type="text" id="person_last_name_english" class="form-control" name="personLastNameEnglish" value="' . $person->personLastNameEnglish . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_first_name_english">' . Lang::getLang('personFirstNameEnglish') . '</label>
					<input type="text" id="person_first_name_english" class="form-control" name="personFirstNameEnglish" value="' . $person->personFirstNameEnglish . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_last_name_japanese">' . Lang::getLang('personLastNameJapanese') . '</label>
					<input type="text" id="person_last_name_japanese" class="form-control" name="personLastNameJapanese" value="' . $person->personLastNameJapanese . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_first_name_japanese">' . Lang::getLang('personFirstNameJapanese') . '</label>
					<input type="text" id="person_first_name_japanese" class="form-control" name="personFirstNameJapanese" value="' . $person->personFirstNameJapanese . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_last_name_japanese_reading">' . Lang::getLang('personLastNameJapaneseReading') . '</label>
					<input type="text" id="person_last_name_japanese_reading" class="form-control" name="personLastNameJapaneseReading" value="' . $person->personLastNameJapaneseReading . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_first_name_japanese_reading">' . Lang::getLang('personFirstNameJapaneseReading') . '</label>
					<input type="text" id="person_first_name_japanese_reading" class="form-control" name="personFirstNameJapaneseReading" value="' . $person->personFirstNameJapaneseReading . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_job_title">' . Lang::getLang('personJobTitle') . '</label>
					<input type="text" id="person_job_title" class="form-control" name="personJobTitle" value="' . $person->personJobTitle . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_division">' . Lang::getLang('personDivision') . '</label>
					<input type="text" id="person_division" class="form-control" name="personDivision" value="' . $person->personDivision . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_office">' . Lang::getLang('personOffice') . '</label>
					<input type="text" id="person_office" class="form-control" name="personOffice" value="' . $person->personOffice . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_homepage">' . Lang::getLang('personHomepage') . '</label>
					<input type="text" id="person_homepage" class="form-control" name="personHomepage" value="' . $person->personHomepage . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_home_telephone">' . Lang::getLang('personHomeTelephone') . '</label>
					<input type="text" id="person_home_telephone" class="form-control" name="personHomeTelephone" value="' . $person->personHomeTelephone . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_mobile_telephone">' . Lang::getLang('personMobileTelephone') . '</label>
					<input type="text" id="person_mobile_telephone" class="form-control" name="personMobileTelephone" value="' . $person->personMobileTelephone . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_office_telephone">' . Lang::getLang('personOfficeTelephone') . '</label>
					<input type="text" id="person_office_telephone" class="form-control" name="personOfficeTelephone" value="' . $person->personOfficeTelephone . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_fax">' . Lang::getLang('personFax') . '</label>
					<input type="text" id="person_fax" class="form-control" name="personFax" value="' . $person->personFax . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_email1">' . Lang::getLang('personEmail1') . '</label>
					<input type="text" id="person_email1" class="form-control" name="personEmail1" value="' . $person->personEmail1 . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_email2">' . Lang::getLang('personEmail2') . '</label>
					<input type="text" id="person_email2" class="form-control" name="personEmail2" value="' . $person->personEmail2 . '" disabled>
				</div>

				<div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
					<label for="person_email3">' . Lang::getLang('personEmail3') . '</label>
					<input type="text" id="person_email3" class="form-control" name="personEmail3" value="' . $person->personEmail3 . '" disabled>
				</div>

			</div>

			<div class="form-row">

				<div class="form-group col-12">
					<label for="person_memo">' . Lang::getLang('personMemo') . '</label>
					<textarea id="person_memo" class="form-control" name="personMemo" disabled>' . $person->personMemo . '</textarea>
				</div>

			</div>

			<div class="form-row">

				<div class="form-group col-12 col-sm-4 col-lg-3">
					<a href="/' . Lang::prefix() . 'perihelion/person/" class="btn btn-block btn-outline-secondary" role="button">
						<span class="fas fa-arrow-left"></span>
						' . Lang::getLang('returnToList') . '
					</a>
				</div>

				<div class="form-group col-12 col-sm-4 col-lg-3 offset-lg-3">
					<button type="submit" name="perihelion-person-delete" class="btn btn-block btn-outline-danger">
						<span class="far fa-trash-alt"></span>
						' . Lang::getLang('delete') . '
					</button>
				</div>

				<div class="form-group col-12 col-sm-4 col-lg-3">
					<a href="/' . Lang::prefix() . 'perihelion/person/" class="btn btn-block btn-outline-secondary" role="button">
						<span class="fas fa-times"></span>
						' . Lang::getLang('cancel') . '
					</a>
				</div>

			</div>

			</form>

		';

		$card = new CardView('perihelion_person_confirm_delete_form',array('container-fluid'),'',array('col-12'),Lang::getLang('perihelionPersonConfirmDelete'), $form);
		return $card->card();

	}

	public function perihelionPersonList(PerihelionPersonListParameters $arg) {

		$list = '

			<div class="row mb-3">
				<div class="col-12 col-md-8 col-lg-10">
					' . PaginationView::paginate($arg->numberOfPages,$arg->currentPage,'/' . Lang::prefix() . 'perihelion/person/') . '
				</div>
				<div class="col-12 col-md-4 col-lg-2">
					<a href="/' . Lang::prefix() . 'perihelion/person/create/" class="btn btn-block btn-outline-success btn-sm"><span class="fas fa-plus"></span> ' . Lang::getLang('create') . '</a>
				</div>
			</div>

			<div class="table-container mb-3">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-sm">
						<thead class"thead-light">
							<tr>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personID') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personObject') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personObjectID') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personLastNameEnglish') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personFirstNameEnglish') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personLastNameJapanese') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personFirstNameJapanese') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personLastNameJapaneseReading') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personFirstNameJapaneseReading') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personJobTitle') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personDivision') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personOffice') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personHomepage') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personHomeTelephone') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personMobileTelephone') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personOfficeTelephone') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personFax') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personMemo') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personEmail1') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personEmail2') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('personEmail3') . '</th>
								<th scope="col" class="text-center text-nowrap">' . Lang::getLang('action') . '</th>
							</tr>
						</thead>
						<tbody>' . $this->perihelionPersonListRows($arg) . '</tbody>
					</table>
				</div>
			</div>

			<div class="row">
				<div class="col-12 col-md-8 col-lg-10">
					' . PaginationView::paginate($arg->numberOfPages,$arg->currentPage,'/' . Lang::prefix() . 'perihelion/person/') . '
				</div>
			</div>

		';

		$card = new CardView('perihelion_person_list',array('container-fluid'),'',array('col-12'),Lang::getLang('perihelionPersonList'), $list);
		return $card->card();

	}

	public function perihelionPersonListRows(PerihelionPersonListParameters $arg) {

		$list = new PerihelionPersonList($arg);
		$results = $list->results();

		$rows = '';

		foreach ($results AS $r) {

			$rows .= '

				<tr id="perihelion_person_key_' . $r['personID'] . '" class="perihelion-person-list-row" data-row-person-id="' . $r['personID'] . '">
					<th scope="row" class="text-center perihelion-person-list-cell" data-cell-person-id="' . $r['personID'] . '">' . $r['personID'] . '</th>
					<td class="text-center perihelion-person-list-cell" data-cell-person-object="' . $r['personObject'] . '">' . $r['personObject'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-object-id="' . $r['personObjectID'] . '">' . $r['personObjectID'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-last-name-english="' . $r['personLastNameEnglish'] . '">' . $r['personLastNameEnglish'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-first-name-english="' . $r['personFirstNameEnglish'] . '">' . $r['personFirstNameEnglish'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-last-name-japanese="' . $r['personLastNameJapanese'] . '">' . $r['personLastNameJapanese'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-first-name-japanese="' . $r['personFirstNameJapanese'] . '">' . $r['personFirstNameJapanese'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-last-name-japanese-reading="' . $r['personLastNameJapaneseReading'] . '">' . $r['personLastNameJapaneseReading'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-first-name-japanese-reading="' . $r['personFirstNameJapaneseReading'] . '">' . $r['personFirstNameJapaneseReading'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-job-title="' . $r['personJobTitle'] . '">' . $r['personJobTitle'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-division="' . $r['personDivision'] . '">' . $r['personDivision'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-office="' . $r['personOffice'] . '">' . $r['personOffice'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-homepage="' . $r['personHomepage'] . '">' . $r['personHomepage'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-home-telephone="' . $r['personHomeTelephone'] . '">' . $r['personHomeTelephone'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-mobile-telephone="' . $r['personMobileTelephone'] . '">' . $r['personMobileTelephone'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-office-telephone="' . $r['personOfficeTelephone'] . '">' . $r['personOfficeTelephone'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-fax="' . $r['personFax'] . '">' . $r['personFax'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-memo="' . $r['personMemo'] . '">' . $r['personMemo'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-email1="' . $r['personEmail1'] . '">' . $r['personEmail1'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-email2="' . $r['personEmail2'] . '">' . $r['personEmail2'] . '</td>
					<td class="text-center perihelion-person-list-cell" data-cell-person-email3="' . $r['personEmail3'] . '">' . $r['personEmail3'] . '</td>
					<td class="text-center text-nowrap">
						<a href="/' . Lang::prefix() . 'perihelion/person/update/' . $r['personID'] . '/" class="btn btn-sm btn-outline-primary">
							<span class="far fa-edit"></span>
							' . Lang::getLang('update') . '
						</a>
						<a href="/' . Lang::prefix() . 'perihelion/person/confirm-delete/' . $r['personID'] . '/" class="btn btn-sm btn-outline-danger">
							<span class="far fa-trash-alt"></span>
							' . Lang::getLang('delete') . '
						</a>
					</td>
				</tr>

			';

		}

		return $rows;

	}

	public function perihelionPersonFilter($filterKey, $selectedFilter = null) {

		$arg = new PerihelionPersonListParameters();
		$arg->resultSet = array();
		$arg->resultSet[] = array('field' => 'DISTINCT(perihelion_Person.'.$filterKey.')', 'alias' => $filterKey);
		$arg->orderBy = array();
		$arg->orderBy[] = array('field' => 'perihelion_Person.'.$filterKey, 'sort' => 'ASC');
		$valueList = new PerihelionPersonList($arg);
		$values = $valueList->results();

		$filter = '<select name="filters[' . $filterKey . ']" class="form-control">';
			$filter .= '<option value="">' . Lang::getLang($filterKey) . '</option>';
			foreach ($values AS $value) {
				$filter .= '<option value="' . $value[$filterKey] . '"' . ($value[$filterKey]==$selectedFilter?' selected':'') . '>' . $value[$filterKey] . '</option>';
			}
		$filter .= '</select>';

		return $filter;

	}

}

?>