<?php

class FileView {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function fileManager($fileObject, $fileObjectID, $baseFormURL) {

		$h = '<div class="container">';

		$h .= '<form id="perihelionFileManagerForm" name="perihelionFileManagerForm"  method="post" action="" . $baseFormURL . "" enctype="multipart/form-data">';
	
			$h .= '<input type="hidden" name="fileObject" value="' . $fileObjectID  . '">';
			$h .= '<input type="hidden" name="fileObjectID" value="' . $fileObjectID  . '">';
	
			$h .= '<div class="form-group row">';
			
				$h .= '<div class="col-sm-4 offset-sm-4">';
				
					$h .= '<label class="btn btn-secondary btn-block btn-file">';
						$h .= '<span id="perihelionFileManagerSubmitButtonText">' . Lang::getLang('selectFiles') . '</span> ';
						$h .= '<input type="file" id="perihelionFiles" name="perihelionFiles[]" style="display:none;" multiple>';
					$h .= '</label>';
					
				$h .= '</div>';
	
				$h .= '<div class="col-sm-4">';
				
					$h .= '<button type="submit" name="perihelionFileManagerSubmit" id="perihelionFileManagerSubmit" class="btn btn-primary btn-block" disabled="true">';
						$h .= '<span id="perihelionFileManagerSubmitIcon" class="fas fa-upload"></span> ';
						$h .= '<span id="perihelionFileManagerSubmitText">' . Lang::getLang('uploadFiles') . '</span>';
					$h .= '</button>';
					
				$h .= '</div>';
				
			$h .= '</div>';
	
		$h .= '</form>';

		$files = File::getObjectFileArray($fileObject, $fileObjectID);
		
		if (!empty($files)) {
			$h .= '<div class="row">';
				$h .= '<div class="col-12">';
					$h .= '<div class="table-responsive">';
						$h .= '<table class="table table-striped">';
						
						
							$h .= '<tr>';
								$h .= '<th>' . Lang::getLang('file') . '</th>';
								$h .= '<th class="hidden-xs hidden-sm">' . Lang::getLang('originalName') . '</th>';
								$h .= '<th class="hidden-xs hidden-sm">' . Lang::getLang('user') . '</th>';
								$h .= '<th class="hidden-xs">' . Lang::getLang('date') . '</th>';
								$h .= '<th class="text-right hidden-xs">' . Lang::getLang('size') . '</th>';
								if ($fileObject == 'Site') { $h .= '<th class="text-center hidden-xs">' . Lang::getLang('object') . '</th>'; }
								$h .= '<th class="text-center">' . Lang::getLang('delete') . '</th>';
							$h .= '</tr>';
						
							foreach ($files as $fileID) {
								
								$file = new File($fileID);
								$u = new User($file->fileSubmittedByUserID);
								$delete = $baseFormURL . 'delete/' . $fileID . '/';
				
								if ($file->fileSize < 1024) { $size = 1; } else { $size = $file->fileSize / 1024;}
								
								$h .= '<tr>';
									$h .= '<td class="hidden-xs"><a class="btn btn-primary btn-sm" href="/file/' . $file->fileID . '/" target="blank" download="' . $file->fileName . '"><span class="fas fa-download"></span></a></td>';
									$h .= '<td class="hidden-xs hidden-sm">' . $file->fileOriginalName . '</td>';
									$h .= '<td class="hidden-xs hidden-sm">' . $u->getUserDisplayName() . '</td>';
									$h .= '<td class="hidden-xs">' . date('Y-m-d',strtotime($file->fileSubmissionDateTime)) . '</td>';
									$h .= '<td class="text-right hidden-xs">' . number_format($size) . 'K</td>';
									if ($fileObject == 'Site') { $h .= '<td class="hidden-xs">' . $file->fileObject . ($file->fileObject!='Site'?" [' . $file->fileObjectID . ']":"") . '</td>'; }
									$h .= '<td class="text-center">';
										$h .= '<a class="btn btn-danger btn-sm" href="' . $delete . '"><span class="fas fa-trash-alt" style="color:#fff;"></span></a>';
									$h .= '</td>';
								$h .= '</tr>';
							}
						
						$h .= '</table>';
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		}

		$h .= '</div>';

		return $h;

	}

	public function newFileManager(NewFileViewParameters $arg) {

		$manager = '';
		if ($arg->includeForm) { $manager .= $this->newFileForm($arg); }
		if ($arg->includeList) { $manager .= $this->newFileList($arg); }
		$cardView = new CardView('new_file_manager_card', $arg->cardContainerDivClasses, $arg->breadcrumbs, array('col-12'), $arg->cardHeader, $arg->navtabs.$manager, false);
		return $cardView->card();

	}

	public function newFileForm(NewFileViewParameters $arg) {

		$fileForm = '

			<div id="new_file_form_container" class="' . implode(' ', $arg->formContainerDivClasses) . '">
				
				<form id="new_file_form" method="post" action="' . $arg->formURL . '" enctype="multipart/form-data">
				
					<input type="hidden" name="fileObject" value="' . ($arg->fileObject?$arg->fileObject:'') . '">
					<input type="hidden" name="fileObjectID" value="' . ($arg->fileObjectID?$arg->fileObjectID:0) . '">
				
					<div class="form-group row">

						<div id="new_file_title_english" class="' . implode(' ', $arg->fileTitleDivClasses) . '">
							<!--<label id="new_file_title_english_label">' . Lang::getLang('fileTitleEnglish') . '</label>-->
							<div class="input-group">
								<div class="input-group-prepend"><div class="input-group-text">' . Lang::getLang('english') . '</div></div>
								<input id="new_file_title_english_input" type="text" name="fileTitleEnglish" class="form-control" value="">
							</div>
						</div>
						
						<div id="new_file_title_japanese" class="' . implode(' ', $arg->fileTitleDivClasses) . '">
							<!--<label id="new_file_title_japanese_label">' . Lang::getLang('fileTitleJapanese') . '</label>-->
							<div class="input-group">
								<div class="input-group-prepend"><div class="input-group-text">' . Lang::getLang('japanese') . '</div></div>
								<input id="new_file_title_japanese_input" type="text" name="fileTitleJapanese" class="form-control" value="">
							</div>
						</div>

						<div id="new_file_select" class="' . implode(' ', $arg->formSelectDivClasses) . '">
				
							<label id="new_file_select_label" class="btn btn-secondary btn-block btn-file">
								<span id="new_file_select_prompt">' . Lang::getLang('selectFiles') . '</span> 
								<input id="new_file_select_input" class="d-none" type="file" name="files-to-upload[]" accept="file/*"' . ($arg->allowMultiple?' multiple':'') . '>
							</label>
				
						</div>
				
						<div id="new_file_submit" class="' . implode(' ', $arg->formSubmitDivClasses) . '">
				
							<button type="submit" id="new_file_submit_button" name="submitted-files" class="btn btn-primary btn-block" disabled="true">
								<span id="new_file_submit_icon" class="fas fa-upload"></span> 
								<span id="new_file_submit_text">' . Lang::getLang('uploadFiles') . '</span>
							</button>
				
						</div>
						
					</div>
				
				</form>
			
			</div>
		
		';

		return $fileForm;

	}

	public function newFileList(NewFileViewParameters $arg) {

		// pagination
		/*
		$count = count($files);
		$perPage = 25;
		$numberOfPages = ceil($count/$perPage);
		if ($currentPage > $numberOfPages) { $currentPage = 1; }
		$startAt = ($currentPage - 1) * 25;
		$limit = "$startAt, $perPage";

		*/

		// PAGINATION
		// eg legacy: PaginationView::paginate($numberOfPages,$currentPage,'/designer/files/')

		$fileList = '

			<div id="new_file_list_container" class="' . implode(' ', $arg->listContainerDivClasses) . '">
	
				<div class="table-responsive">
				
					<table class="table table-striped table-sm">
			
						<thead class="thead-light">
			
							<tr>
								<th class="file-list-file text-center">' . Lang::getLang('file') . '</th>
								<th class="file-list-original-name text-center d-none d-xl-table-cell">' . Lang::getLang('fileName') . '</th>
								<th class="file-list-created text-center d-none d-md-table-cell">' . Lang::getLang('date') . '</th>
								<th class="file-list-size text-center d-none d-sm-table-cell">' . Lang::getLang('size') . '</th>
								<th class="file-list-file-object text-center d-none' . ($arg->displayObjectInfo?' d-xl-table-cell':'') . '">' . Lang::getLang('object') . '</th>
								<th class="file-list-action text-center">' . Lang::getLang('action') . '</th>
							</tr>
						
						</thead>
						
						<body>' . $this->newFileListRows($arg) . '</body>
						
					</table>
				
				</div>
			
			</div>

		';

		// PAGINATION
		// eg legacy: PaginationView::paginate($numberOfPages,$currentPage,'/designer/files/')

		return $fileList;

	}

	public function newFileListRows(NewFileViewParameters $arg) {

		$listArg = new NewFileListParameters();
		$listArg->fileObject = $arg->fileObject;
		$listArg->fileObjectID = $arg->fileObjectID;
		$fileList = new NewFileList($listArg);
		$files = $fileList->files();

		$rows = '';
		
		foreach ($files as $fileID) {

			$file = new File($fileID);
			$createdDT = new DateTime($file->created);
			$fileSize = $file->fileSize/1024;

			$rows .= '
				<tr data-file-id="' . $fileID . '" data-file-object="' . $file->fileObject . '" data-file-object-id="' . $file->fileObjectID . '">
					<td class="file-list-file text-center align-middle">
						<a class="btn btn-primary btn-sm" href="/file/' . $fileID . '/" target="blank" download="' . $file->fileName . '"><span class="fas fa-download" aria-hidden="true"></span></a>
					</td>
					<td class="file-list-original-name text-left align-middle d-none d-xl-table-cell">' . $file->title() . '</td>
					<td class="file-list-created text-center align-middle d-none d-md-table-cell">' . $createdDT->format('Y-m-d H:i:s') . '</td>
					<td class="file-list-size text-center align-middle d-none d-sm-table-cell">' . number_format($fileSize) . 'K</td>
					<td class="file-list-file-object text-center align-middle d-none' . ($arg->displayObjectInfo?' d-xl-table-cell':'') . '">' . $file->fileObject . '</td>
					</td>
					<td class="file-list-action text-center align-middle">
						<button type="button" class="btn btn-danger btn-sm file-delete"><span class="fas fa-trash-alt"></span></button>
					</td>
				</tr>
			';

		}

		return $rows;





	}

}

?>