<?php

class NoteView {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct(
		$urlArray = array(), 
		$inputArray = array(), 
		$errorArray = array(), 
		$messageArray = array()
	) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
	}
	
	public static function freshPerihelionNoteForm($formAction, $noteObject, $noteObjectID) {
	
		$h = "<form id=\"freshPerihelionNoteCreate\"  method=\"post\" action=\"" . $formAction . "\">";

			$h .= "<input type=\"hidden\" name=\"noteObject\" value=\"" . $noteObject . "\">";
			$h .= "<input type=\"hidden\" name=\"noteObjectID\" value=\"" . $noteObjectID . "\">";
			$h .= "<div class=\"form-group row\">";
										
				$h .= "<div class=\"col-md-6 offset-md-4\">";
					$h .= "<input type=\"text\" class=\"form-control\" id=\"noteContent\" name=\"noteContent\" placeholder=\"add note here...\">";
				$h .= "</div>";
					
				$h .= "<div class=\"col-md-2\">";
					$h .= "<button type=\"submit\" name=\"perihelionFreshNoteCreateSubmit\" class=\"btn btn-primary btn-block\">". Lang::getLang('addNote') . "</button>";
				$h .= "</div>";
				
			$h .= "</div>";
	
		$h .= "</form>";

		return $h;
		
	}
	
	public static function freskPerihelionNotesList($adminPageURL, $noteObject, $nodeObjectID) {
		
		$notes = Note::notes($noteObject,$nodeObjectID);
		
		if (!empty($notes)) {
			
			$h = "<div class=\"row\">";
				$h .= "<div class=\"col-12\">";
					$h .= "<div class=\"table-responsive\">";
						$h .= "<table class=\"table table-striped\">";

							$h .= "<tr>";
								$h .= "<th>" . Lang::getLang('note') . "</th>";
								$h .= "<th class=\"text-right\">" . Lang::getLang('delete') . "</th>";
							$h .= "</tr>";
						
							foreach ($notes as $noteID) {
								$note = new Note($noteID);
								$h .= "<tr>";
									$h .= "<td>" . $note->noteContent . "</td>";
									$h .= "<td class=\"text-right\">";
										$h .= "<a class=\"btn btn-danger btn-sm\" href=\"/" . Lang::prefix() . $adminPageURL . "/update/" . $nodeObjectID . "/notes/delete/" . $noteID . "/\">";
											$h .= "<span class=\"fas fa-trash-alt\" style=\"color:#fff;\"></span>";
										$h .= "</a>";
									$h .= "</td>";
								$h .= "</tr>";
							}
						
						$h .= "</table>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
			return $h;
			
		}
									
		
	}
	
}

?>