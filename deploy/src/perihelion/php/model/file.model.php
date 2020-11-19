<?php

class File extends ORM {

	public $fileID;
	public $siteID;
	public $fileSubmittedByUserID;
	public $fileSubmissionDateTime;
	public $filePath;
	public $fileName;
	public $fileOriginalName;
	public $fileType;
	public $fileSize;
	public $fileObject;
	public $fileObjectID;
	public $fileTitleEnglish;
	public $fileTitleJapanese;
	public $fileNotes;
	
	public function __construct($fileID = 0) {
		
		$this->fileID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->fileSubmittedByUserID = $_SESSION['userID'];
		$this->fileSubmissionDateTime = date('Y-m-d H:i:s');
		$this->filePath = '';
		$this->fileName = '';
		$this->fileOriginalName = '';
		$this->fileType = '';
		$this->fileSize = 0;
		$this->fileObject = '';
		$this->fileObjectID = 0;
		$this->fileTitleEnglish = '';
		$this->fileTitleJapanese = '';
		$this->fileNotes = '';
		
		if ($fileID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_File WHERE fileID = :fileID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':fileID' => $fileID));
			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			}
			
		}

	}

	public function title() {
		
		if ($_SESSION['lang'] == 'ja' && $this->fileTitleJapanese != '') { $title = $this->fileTitleJapanese; } else { $title = $this->fileTitleEnglish; }
		if (!$title) { $title = $this->fileOriginalName; }
		return $title;
		
	}
	
	public static function uploadFile($fileArray, $fileObject, $fileObjectID) {

		$errorArray = array();
		// $allowedExts = array("bmp","gif","jpeg","jpg","png","ico","pdf","xls","xlsx","doc","docx","json","xml","csv");
		// $extension = end(explode(".", $fileArray["name"]));
		// if ($fileArray["error"] > 0) { $errorArray[$fileArray["name"]] = "This file returned an error of type " . $fileArray[error] . "."; }
		// if (!in_array($extension, $allowedExts)) { $errorArray[$fileArray["name"]] = "This file type is not allowed."; }
		// if ($fileArray["size"] < 2097152) { $errorArray[$fileArray["name"]] = "There is a 2MB size limit. This file is too large."; }

		if (empty($errorArray)) {
			
			$file = new File();
			$file->filePath = "/var/www/vault/files/";
			$file->fileName = $fileArray["name"];
			$file->fileOriginalName = $fileArray["name"];
			$file->fileType = pathinfo($fileArray["name"], PATHINFO_EXTENSION);
			$file->fileSize = $fileArray['size'];
			$file->fileObject = $fileObject;
			$file->fileObjectID = $fileObjectID;
			$file->fileTitleEnglish = $fileArray["name"];
			$file->fileTitleJapanese = $fileArray["name"];
			
			$fileID = self::insert($file);
			$conditions['fileID'] = $fileID;
			$newFilePath = $file->filePath . $fileID . '.' . $file->fileType;

			if (move_uploaded_file($fileArray['tmp_name'], $newFilePath)) {
				unset($file->fileID);
				$file->filePath = $newFilePath;
				$file->fileName = $fileID . '.' . $file->fileType;
				self::update($file, $conditions);
			} else {
				$errorArray[$file->fileOriginalName] = "There was an error uploading " . $file->fileOriginalName . ".";
				self::delete($file, $conditions);
			}

		}
		
		return $errorArray;
		
	}
	
	public static function objectHasFile($fileObject, $fileObjectID) {
		$nucleus = Nucleus::getInstance();
		$query = "SELECT * FROM perihelion_File WHERE fileObject = :fileObject AND fileObjectID = :fileObjectID";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':fileObject' => $fileObject, ':fileObjectID' => $fileObjectID));
		if ($row = $statement->fetch()) { return true; } else { return false; }
	}

	public static function getObjectFileArray($fileObject, $fileObjectID) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT fileID FROM perihelion_File WHERE fileObject = :fileObject AND fileObjectID = :fileObjectID";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':fileObject' => $fileObject, ':fileObjectID' => $fileObjectID));
		$objectFileArray = array();
		while ($row = $statement->fetch()) { $objectFileArray[] = $row['fileID']; }
		return $objectFileArray;
		
	}
	
	public static function fileArray() {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT fileID FROM perihelion_File WHERE siteID = :siteID ORDER BY fileID DESC ";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$files = array();
		while ($row = $statement->fetch()) { $files[] = $row['fileID']; }
		return $files;

	}
	
	public static function uploadFiles($fileUploadArray, $fileObject, $fileObjectID) {

		$errorArray = array(); 
		$allowedExts = self::allowedExtensions();

		for ($i = 0; $i < count($fileUploadArray['name']); $i++) {

			$f = explode(".", $fileUploadArray["name"][$i]);
			$ext = end($f);
			$extension = pathinfo($fileUploadArray["name"][$i], PATHINFO_EXTENSION);

			if ($ext != $extension) { $errorArray['fileUploads'][$fileUploadArray["name"][$i]] = "There was an extension check error."; }
			if ($fileUploadArray["error"][$i] == 1) {
				$errorArray['fileUploads'][$fileUploadArray["name"][$i]] = "This file is too large.";
			} elseif ($fileUploadArray["error"][$i] > 1) {
				$errorArray['fileUploads'][$fileUploadArray["name"][$i]] = "This file returned an error of type " . $fileUploadArray["error"][$i] . ".";
			}
			if (!in_array(strtolower($extension), $allowedExts)) { $errorArray['fileUploads'][$fileUploadArray["name"][$i]] = "This file type is not allowed."; }
			if ($fileUploadArray["size"][$i] > 10485760) { $errorArray['fileUploads'][$fileUploadArray["name"][$i]] = "There is a 10MB size limit. This file is too large."; }

			if (empty($errorArray)) {
				
				$file = new File();
				$file->filePath = substr($_SERVER['DOCUMENT_ROOT'],0,strrpos($_SERVER['DOCUMENT_ROOT'],'/'))."/vault/files/";
				$file->fileName = $fileUploadArray["name"][$i];
				$file->fileOriginalName = $fileUploadArray["name"][$i];
				$file->fileType = strtolower($extension);
				$file->fileSize = $fileUploadArray['size'][$i];
				$file->fileObject = $fileObject;
				$file->fileObjectID = $fileObjectID;
				$file->fileTitleEnglish = $fileUploadArray["name"][$i];
				$file->fileTitleJapanese = $fileUploadArray["name"][$i];
				
				$fileID = self::insert($file);
				$conditions['fileID'] = $fileID;
				$newFilePath = $file->filePath . $fileID . '.' . $file->fileType;

				if (move_uploaded_file($fileUploadArray['tmp_name'][$i], $newFilePath)) {
					unset($file->fileID);
					$file->filePath = $newFilePath;
					$file->fileName = $fileID . '.' . $file->fileType;
					self::update($file, $conditions);
				} else {
					$errorArray['fileUploads'][$file->fileOriginalName] = "There was an error uploading " . $file->fileOriginalName . ".";
					self::delete($file, $conditions);
				}

			}
			
		}
		
		return $errorArray;
		
	}
	
	public static function fileDownload($fileID) {

		$file = new File($fileID);

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file->filePath));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file->filePath));
		
		ob_clean();
		flush();
		readfile($file->filePath);
		
		exit;
		
	}
	
	public static function allowedExtensions() {

		$allowedExtensions = array(
			"bmp",
			"csv",
			"doc",
			"docx",
			"gif",
			"ico",
			"jpeg",
			"jpg",
			"json",
			"pdf",
			"png",
			"svg",
			"txt",
			"xls",
			"xlsx",
			"xml",
			"zip"
		);
		
		return $allowedExtensions;
		
	}

	public static function singleFileArrayConverter($fileArray) {

		// required because uploads of a single file because
		// PHP arrays differ for single files and multiple files
		// and uploadFiles() is built to support multiple files

		$newFileArray = array();
		foreach ($fileArray AS $key => $value) { $newFileArray[$key][0] = $value; }
		return $newFileArray;

	}

}

?>