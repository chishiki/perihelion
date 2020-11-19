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

		$h = '<form id="perihelionFileManagerForm" name="perihelionFileManagerForm"  method="post" action="" . $baseFormURL . "" enctype="multipart/form-data">';
	
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
								$h .= '<th>' . Lang::getLang('url') . '</th>';
								$h .= '<th class="hidden-xs hidden-sm">' . Lang::getLang('originalName') . '</th>';
								$h .= '<th class="hidden-xs">' . Lang::getLang('date') . '</th>';
								$h .= '<th class="text-right hidden-xs">' . Lang::getLang('size') . '</th>';
								if ($fileObject == 'Site') { $h .= '<th class="text-center hidden-xs">' . Lang::getLang('object') . '</th>'; }
								$h .= '<th class="text-center">' . Lang::getLang('delete') . '</th>';
							$h .= '</tr>';
						
							foreach ($files as $fileID) {
								
								$file = new File($fileID);
								$delete = $baseFormURL . 'delete/' . $fileID . '/';
				
								if ($file->fileSize < 1024) { $size = 1; } else { $size = $file->fileSize / 1024;}
								
								$h .= '<tr>';
									$h .= '<td class="hidden-xs"><a class="btn btn-secondary btn-sm" href="/file/' . $file->fileID . '/" target="blank" download><span class="fas fa-download"></span></a></td>';
									$h .= '<td class="hidden-xs"><a href="/file/' . $file->fileID . '/" target="blank">/file/' . $file->fileID . '/</a></td>';
									$h .= '<td class="hidden-xs hidden-sm">' . $file->fileOriginalName . '</td>';
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

		return $h;

	}

}

?>