<?php

/*
CREATE TABLE `perihelion_Image` (
`imageID` int(12) NOT NULL AUTO_INCREMENT,
  `imageDisplayOrder` int(4) NOT NULL,
  `siteID` int(8) NOT NULL,
  `imageSubmittedByUserID` int(12) NOT NULL,
  `imageSubmissionDateTime` datetime NOT NULL,
  `imagePath` varchar(255) NOT NULL,
  `s3url` varchar(100) NOT NULL,
  `imageObject` varchar(20) NOT NULL,
  `imageObjectID` int(8) NOT NULL,
  `imageDisplayClassification` varchar(20) NOT NULL,
  `imageOriginalName` varchar(50) NOT NULL,
  `imageType` varchar(30) NOT NULL,
  `imageSize` int(11) NOT NULL,
  `imageDimensionX` int(5) NOT NULL,
  `imageDimensionY` int(5) NOT NULL,
  `imageDisplayInGallery` int(1) NOT NULL,
  `imageMetaData` varchar(255) NOT NULL,
  PRIMARY KEY (`imageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
*/

final class Image extends ORM {

	public $imageID;
	public $imageDisplayOrder;
	public $siteID;
	public $imageSubmittedByUserID;
	public $imageSubmissionDateTime;
	public $imagePath;
	public $s3url;
	public $imageObject;
	public $imageObjectID;
	public $imageDisplayClassification;
	public $imageOriginalName;
	public $imageType;
	public $imageSize;
	public $imageDimensionX;
	public $imageDimensionY;
	public $imageDisplayInGallery;
	public $imageMetaData;

	public function __construct($imageID = 0) {
		
		
	    $this->imageID = 0;
	    $this->imageDisplayOrder = 0;
	    $this->siteID = $_SESSION['siteID'];
	    $this->imageSubmittedByUserID = $_SESSION['userID'];
	    $this->imageSubmissionDateTime = date('Y-m-d H:i:s');
	    $this->imagePath = '';
	    $this->s3url = '';
	    $this->imageObject = '';
	    $this->imageObjectID = 0;
	    $this->imageDisplayClassification = '';
	    $this->imageOriginalName = '';
	    $this->imageType = '';
	    $this->imageSize = 0;
	    $this->imageDimensionX = 0;
	    $this->imageDimensionY = 0;
	    $this->imageDisplayInGallery = 0;
	    $this->imageMetaData = '';

	    if ($imageID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Image WHERE imageID = :imageID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':imageID' => $imageID));
			if ($row = $statement->fetch()) {
			    foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; }  }
			}
			
		}
		
	}

	public function src($width = null) {
		
		if ($width) {
			$src = "/image/" . $this->imageID . "/" . $width . "/";
		} else {
			$src = "/image/" . $this->imageID . "/";
		}
		
		return $src;
		
	}

	public function thumbSrc($width) {

		$origSrc = $this->src();
		$thumbPath = Config::read('physical.path') . 'vault/images/' . date('Y') . '/' . $this->imageID . '-' . $width . 'px.' . $this->imageType;
		
		if (file_exists($thumbPath)) {
			
			$thumbSrc = $origSrc . $width . "/";
			return $thumbSrc;
			
		} else {
			
			$thumbFormats =  self::thumbFormats();
			$thumbSizes = self::thumbSizes();
			if (in_array($this->imageType,$thumbFormats) && in_array($width,$thumbSizes)) {
				self::createThumbnail($this->imagePath, $this->imageType, $thumbPath, $width);
			}
			return $origSrc;
			
		}

	}

	public static function uploadImageFile($imageArray, $imageObject, $imageObjectID) { // used in project controller

	
		$errorArray = array();
		
		$allowedExts = array("gif","GIF","jpeg","JPEG","jpg","JPG","png","PNG","ico","ICO");
		$extension = end(explode(".", $imageArray["name"]));
		
		$imageType = $imageArray["type"];
		
		if (
			(
				$imageType == "image/jpeg" || 
				$imageType == "image/jpg" || 
				$imageType == "image/png" || 
				$imageType == "image/gif" || 
				$imageType == "image/ico"
			)
		    && ($imageArray["size"] < 10485760)
			&& in_array($extension, $allowedExts)
		) {

			if ($imageArray["error"] > 0) {
			
				return $imageArray['error'];
				
			} else {
			
				$image = new Image();
				$image->imagePath = Config::read('physical.path') . 'vault/images/';
				$image->imageObject = $imageObject;
				$image->imageObjectID = $imageObjectID;
				$image->imageOriginalName = $imageArray["name"];
				$image->imageType = substr($imageArray["type"],6);
				if ($image->imageType == 'jpeg') { $image->imageType = 'jpg'; }
				$image->imageSize = $imageArray['size'];
				$image->imageDimensionX = 0;
				$image->imageDimensionY = 0;

				$imageID = self::insert($image);
				$conditions['imageID'] = $imageID;
				$newImagePath = $image->imagePath . $imageID . '.' . $image->imageType;
				
				
				if (move_uploaded_file($imageArray['tmp_name'], $newImagePath)) {
					unset($image->imageID);
					$image->imagePath = '/' . $newImagePath;
					self::update($image, $conditions);
				} else {
					$errorArray[$image->imageOriginalName] = "There was an error uploading " . $image->imageOriginalName . ".";
					self::delete($image, $conditions);
				}

				return $errorArray;
				
			}
			
		} else {
			return 'IMAGE UPLOAD ERROR: Your image must be less than 10MB.';
		}

	}
	
	public static function createThumbnail($source, $filetype, $destination, $desiredWidth) {

		// SUPPORTS JPG AND PNG ONLY
		
		$filetype = strtolower($filetype);
		
		switch ($filetype) {
			
			case 'jpg':
			
				$sourceImage = imagecreatefromjpeg($source);
				$width = imagesx($sourceImage);
				$height = imagesy($sourceImage);
				$desiredHeight = floor($height * ($desiredWidth / $width));
				$virtualImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
				imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $width, $height);
				imagejpeg($virtualImage, $destination);
				break;
		
			case 'png':

				$sourceImage = imagecreatefrompng($source);
				$width = imagesx($sourceImage);
				$height = imagesy($sourceImage);
				$desiredHeight = floor($height * ($desiredWidth / $width));
				$virtualImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
				imagealphablending($virtualImage, false);
				imagesavealpha($virtualImage, true);
				$transparent = imagecolorallocatealpha($virtualImage, 255, 255, 255, 127);
				imagefilledrectangle($virtualImage, 0, 0, $width, $height, $transparent);
				imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $width, $height);

				imagepng($virtualImage, $destination);

				break;
				
		}

	}

	public static function objectHasImage($imageObject, $imageObjectID) {
		$nucleus = Nucleus::getInstance();
		$query = "SELECT * FROM perihelion_Image WHERE imageObject = :imageObject AND imageObjectID = :imageObjectID";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':imageObject' => $imageObject, ':imageObjectID' => $imageObjectID));
		if ($row = $statement->fetch()) { return true; } else { return false; }
	}

	public static function getObjectImageArray($imageObject, $imageObjectID, $limit = null) {
		
		// don't trust limit
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT imageID FROM perihelion_Image WHERE siteID = :siteID AND imageObject = :imageObject AND imageObjectID = :imageObjectID ORDER BY imageDisplayOrder" . ($limit?" LIMIT $limit":"");
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':imageObject' => $imageObject, ':imageObjectID' => $imageObjectID));
		$objectImageArray = array();
		while ($row = $statement->fetch()) { $objectImageArray[] = $row['imageID']; }
		return $objectImageArray;
		
	}
	
	public static function getObjectImageGalleryArray($imageObject, $imageObjectID) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT imageID FROM perihelion_Image WHERE siteID = :siteID AND imageObject = :imageObject AND imageObjectID = :imageObjectID AND imageDisplayInGallery = '1' ORDER BY imageDisplayOrder ASC";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':imageObject' => $imageObject, ':imageObjectID' => $imageObjectID));
		$objectImageArray = array();
		while ($row = $statement->fetch()) { $objectImageArray[] = $row['imageID']; }
		return $objectImageArray;
		
	}
	
	public static function getMainImageID($imageObject, $imageObjectID) {
	
		$site = new Site($_SESSION['siteID']);
	
		$nucleus = Nucleus::getInstance();
		$query = "SELECT imageID FROM perihelion_Image ";
		$query .= "WHERE imageObject = :imageObject AND imageObjectID = :imageObjectID AND imageDisplayClassification = 'mainImage' ";
		$query .= "LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		
		
		$statement->bindParam(':siteID',$_SESSION['siteID']);
		$statement->bindParam(':imageObject',$imageObject);
		$statement->bindParam(':imageObjectID',$imageObjectID);
		$statement->execute();
	
		if ($row = $statement->fetch()) { return $row['imageID']; } else { return self::getMostRecentImageID($imageObject, $imageObjectID); }
		
		
	
	}
	
	public static function getMostRecentImageID($imageObject, $imageObjectID) {
	
		$site = new Site($_SESSION['siteID']);
	
		$nucleus = Nucleus::getInstance();
		$query = "SELECT imageID FROM perihelion_Image WHERE imageObject = :imageObject AND imageObjectID = :imageObjectID ";
		$query .= "ORDER BY imageSubmissionDateTime DESC LIMIT 1";
		$statement = $nucleus->database->prepare($query);

		$statement->bindParam(':siteID',$_SESSION['siteID']);
		$statement->bindParam(':imageObject',$imageObject);
		$statement->bindParam(':imageObjectID',$imageObjectID);
		$statement->execute();
	
		if ($row = $statement->fetch()) { return $row['imageID']; } else { return 0; }

	}

	public static function imageArray($limit = null) {

		if ($limit) {
			$limitIsValid = Utilities::isValidLimitClause($limit);
			if (!$limitIsValid) { die('invalid limit clause in Image::imageArray()'); }
		}
		
		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT imageID FROM perihelion_Image WHERE siteID = :siteID ORDER BY imageSubmissionDateTime DESC" . ($limit?" LIMIT $limit":"");
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$imageArray = array();
		while ($row = $statement->fetch()) { $imageArray[] = $row['imageID']; }
		return $imageArray;

	}
	
	public static function lastImage($siteID, $imageObject = '', $imageObjectID = 0, $imageDisplayClassification = '') {

		
		$nucleus = Nucleus::getInstance();
		
		$query = "SELECT imageID FROM perihelion_Image WHERE siteID = :siteID ";
		if ($imageObject) { $query .= "AND imageObject = :imageObject "; }
		if ($imageObjectID) { $query .= "AND imageObjectID = :imageObjectID "; }
		if ($imageDisplayClassification) { $query .= "AND imageDisplayClassification = :imageDisplayClassification "; }
		$query .= "ORDER BY imageSubmissionDateTime DESC LIMIT 1";
		
		$statement = $nucleus->database->prepare($query);
		
		if ($imageObject) { $statement->bindParam(':imageObject', $imageObject); }
		if ($imageObjectID) { $statement->bindParam(':imageObjectID', $imageObjectID); }
		if ($imageDisplayClassification) { $statement->bindParam(':imageDisplayClassification', $imageDisplayClassification); }
		$statement->bindParam(':siteID', $siteID);
		
		$statement->execute();

		$imageID = 0;
		if ($row = $statement->fetch()) { $imageID = $row['imageID']; }
		return $imageID;

	}
	
	public static function allowedExtensions() {

		$allowedExtensions = array(
			"bmp",
			"gif",
			"ico",
			"jpeg",
			"jpg",
			"png",
			"svg"
		);
		
		return $allowedExtensions;
		
	}

	public static function uploadImages($imageUploadArray, $imageObject, $imageObjectID, $returnArrayOfImageIDs = false) {

		$errorArray = array();
		$arrayOfImageIDs = array();
		$allowedExts = self::allowedExtensions();

		for ($i = 0; $i < count($imageUploadArray['name']); $i++) {

			$nameExplode = explode(".", $imageUploadArray["name"][$i]);
			$ext = end($nameExplode);
			$extension = pathinfo($imageUploadArray["name"][$i], PATHINFO_EXTENSION);

			if ($ext != $extension) { $errorArray['imageUploads'][$imageUploadArray["name"][$i]] = "There was an extension check error."; }
			if ($imageUploadArray["error"][$i] == 1) {
				$errorArray['imageUploads'][$imageUploadArray["name"][$i]] = "This file is too large.";
			} elseif ($imageUploadArray["error"][$i] > 1) {
				$errorArray['imageUploads'][$imageUploadArray["name"][$i]] = "This file returned an error of type " . $imageUploadArray["error"][$i] . ".";
			}
			if (!in_array(strtolower($extension), $allowedExts)) { $errorArray['imageUploads'][$imageUploadArray["name"][$i]] = "This file type is not allowed."; }
			if ($imageUploadArray["size"][$i] > 10485760) { $errorArray['imageUploads'][$imageUploadArray["name"][$i]] = "There is a 10MB size limit. This file is too large."; }

			if (empty($errorArray)) {
				
				$image = new Image();
				$image->imagePath = Config::read('physical.path') . 'vault/images/';
				$image->imageObject = $imageObject;
				$image->imageObjectID = $imageObjectID;
				$image->imageOriginalName = $imageUploadArray["name"][$i];
				$image->imageType = strtolower($extension);
				$image->imageSize = $imageUploadArray['size'][$i];
				if ($imageObject == 'Property') { $image->imageDisplayInGallery = 1; }

				$imageID = self::insert($image);
				$arrayOfImageIDs[] = $imageID;
				$conditions['imageID'] = $imageID;
				
				$newFileName = $imageID . '.' . $image->imageType;
				$newFilePath = $image->imagePath . $newFileName;

				if (move_uploaded_file($imageUploadArray['tmp_name'][$i], $newFilePath)) {

					$bucket = 'perihelion-images';
					$key = $newFileName;
					$sourceFile = $newFilePath;
					$contentType = $imageUploadArray['type'][$i];
					$imageObject = $image->imageObject;
					$imageObjectID = $image->imageObjectID;
					
					$awsKey = Config::read('aws.key');
					if ($awsKey != 'xxxxxxx') {
						$aws = new AWS();
						$image->s3url = $aws->uploadImageToS3($bucket,$key,$sourceFile,$contentType,$imageObject,$imageObjectID);
					}
					
					unset($image->imageID);
					$image->imagePath = $newFilePath;
					self::update($image, $conditions);

					$thumbFormats =  self::thumbFormats();
					if (in_array($image->imageType,$thumbFormats)) {
						
						$thumbSizes =  self::thumbSizes();
						foreach ($thumbSizes as $width) {

							$thumbImageName = $imageID . '-' . $width . 'px.' . $image->imageType;
							$thumbPath = Config::read('physical.path') . 'vault/images/' . date('Y') . '/' . $thumbImageName;
							self::createThumbnail($newFilePath, $image->imageType, $thumbPath, $width);
							
							if ($awsKey != 'xxxxxxx') {
							$key = $thumbImageName;
							$sourceFile = $thumbPath;
							$aws->uploadImageToS3($bucket,$key,$sourceFile,$contentType,$imageObject,$imageObjectID);
							}

						}
						
					}
					
				} else {
					
					
					$errorArray['imageUploads'][$image->imageOriginalName] = "There was an error uploading " . $image->imageOriginalName . ".";
					self::delete($image, $conditions);
					// log this!
					
					
				}

			}
			
		}
		
		if ($returnArrayOfImageIDs) { return $arrayOfImageIDs; } else { return $errorArray; }
		
	}
	
	public static function thumbSizes() {
		$thumbSizes = array(50,90,150,210,270,300,330,350,600,690,768,992,1200,1400);
		return $thumbSizes;
	}
	
	public static function thumbFormats() {
		$thumbSizes = array('jpg','png');
		return $thumbSizes;
	}

}

final class ImageUploader {

	public static function uploadImages($imageUploadArray, $imageObject, $imageObjectID = 0) {

		$imgArray = array();

		foreach ($imageUploadArray AS $inputFieldName => $parameterArray) {
			foreach ($parameterArray AS $parameter => $keysValues) {
				foreach ($keysValues AS $key => $value) {
					$imgArray[$key][$parameter] = $value;
				}
			}
		}

		foreach ($imgArray AS $metaData => $img) {

			if ($img['error'] == 0) {

				$extension = pathinfo($img['name'], PATHINFO_EXTENSION);

				$image = new Image();
				$image->imagePath = Config::read('physical.path') . 'vault/images/';
				$image->imageObject = $imageObject;
				$image->imageObjectID = $imageObjectID;
				$image->imageOriginalName = $img['name'];
				$image->imageType = strtolower($extension);
				$image->imageSize = $img['size'];
				$image->imageMetaData = $metaData;
				$imageID = Image::insert($image);

				$image = new Image($imageID);
				$newFileName = $imageID . '.' . $image->imageType;
				$newFilePath = $image->imagePath . $newFileName;
				$image->imagePath = $newFilePath;
				$cond = array('imageID' =>  $imageID);
				Image::update($image, $cond);

				move_uploaded_file($img['tmp_name'], $newFilePath);

			}

		}

	}

}

final class ImageFetch {

	private $imageID;

	public function __construct($imageObject, $imageObjectID, $imageMetaData) {

		$this->imageID = null;

		$where = array();
		$where[] = 'siteID = :siteID';
		if ($imageObject) { $where[] = 'imageObject = :imageObject'; }
		if ($imageObjectID) { $where[] = 'imageObjectID = :imageObjectID'; }
		if ($imageMetaData) { $where[] = 'imageMetaData = :imageMetaData'; }

		$query = 'SELECT imageID FROM perihelion_Image WHERE ' . implode(' AND ',$where) . ' LIMIT 1';

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		if ($imageObject) { $statement->bindParam(':imageObject', $imageObject, PDO::PARAM_STR); }
		if ($imageObjectID) { $statement->bindParam(':imageObjectID', $imageObjectID, PDO::PARAM_INT); }
		if ($imageMetaData) { $statement->bindParam(':imageMetaData', $imageMetaData, PDO::PARAM_STR); }
		$statement->execute();

		if ($row = $statement->fetch()) {
			$this->imageID = $row['imageID'];
		}

	}

	public function imageExists() {
		if ($this->imageID) {
			return true;
		} else {
			return false;
		}
	}

	public function getImageID() {
		return $this->imageID;
	}

	public function getImageSrc() {
		$image = new Image($this->imageID);
		return $image->src();
	}

	public function getImagePath() {
		$image = new Image($this->imageID);
		return $image->imagePath;
	}

}

final class ImageMostRecent {

	private $imageID;

	public function __construct($imageObject, $imageObjectID = null) {

		$this->imageID = null;

		$nucleus = Nucleus::getInstance();

		$whereClause = array();
		$whereClause[] = 'siteID = :siteID';
		$whereClause[] = 'imageObject = :imageObject';
		if ($imageObjectID) { $whereClause[] = 'imageObjectID = :imageObjectID'; }

		$query = 'SELECT imageID FROM perihelion_Image ';
		$query .= 'WHERE ' . implode(' AND ',$whereClause) . ' ';
		$query .= 'ORDER BY imageSubmissionDateTime DESC LIMIT 1';

		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID',$_SESSION['siteID']);
		$statement->bindParam(':imageObject',$imageObject);
		if ($imageObjectID) { $statement->bindParam(':imageObjectID',$imageObjectID); }
		$statement->execute();

		if ($row = $statement->fetch()) { $this->imageID = $row['imageID']; }

	}

	public function imageID() {
		return $this->imageID;
	}

}

?>