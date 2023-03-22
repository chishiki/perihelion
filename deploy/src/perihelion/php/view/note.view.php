<?php

final class NoteView {

	private $loc;
	private $input;
	private $errors;
	private $messages;
	
	public function __construct($loc = array(), $input = array(), $errors = array(), $messages = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->errors = $errors;
		$this->messages = $messages;

	}
	
	public function NoteFormCreate($formAction, $noteObject, $noteObjectID) {
	
		$form = '
		
			<form id="note_form"  method="post" action="' . $formAction . '">
				<input type="hidden" name="noteObject" value="' . $noteObject . '">
				<input type="hidden" name="noteObjectID" value="' . $noteObjectID . '">
				<div class="form-row">
					<div class="form-group col-12">
						<textarea class="form-control ckeditor" id="note_content" name="noteContent" rows="10" placeholder="add note here..."></textarea>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-12 col-sm-6 offset-sm-6 col-md-4 offset-md-8 col-lg-3 offset-lg-9 col-xl-2 offset-xl-10">
						<button type="submit" name="note-submit-create" class="btn btn-outline-primary btn-block">' . Lang::getLang('addNote') . '</button>
					</div>
				</div>
			</form>
			
		';

		return $form;
		
	}
	
	public function NotesList($baseURL, $noteObject = null, $noteObjectID = null, $orderBy = null) {

		$arg = new NoteListArguments();
		$arg->noteObject = $noteObject;
		$arg->noteObjectID = $noteObjectID;

		if (!is_null($orderBy) && in_array($orderBy,array('ASC','DESC'))) {
			$arg->orderBy = array(
				array('field' => 'noteID', 'sort' => $orderBy)
			);
		}

		$nl = new NoteList($arg);
		$notes = $nl->getNotes();

		$noteList = '';
		foreach ($notes as $note) {

			$creator = new User($note['creator']);

			$noteList .= '
				<div class="card mb-3">
					<div class="card-header d-flex flex-column flex-sm-row justify-content-sm-between">
						<span>' . $creator->getUserDisplayName() . ' <small>[' . $note['created'] . ']</small></span>
						<button type="button" class="btn btn-sm btn-outline-danger note-delete d-block d-sm-inline" data-note-id="' . $note['noteID'] . '">
							<span class="far fa-trash-alt"></span> ' . Lang::getLang('delete') . '
						</button>
					</div>
					<div class="card-body">
						<p class="card-text">' . $note['noteContent'] . '</p>
					</div>
				</div>
			';

		}

		return $noteList;

	}
	
}

?>