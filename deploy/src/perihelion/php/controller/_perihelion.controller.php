<?php

class Controller {
	
	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $jsonStr;
	
	public function __construct() {
		
		session_start();
		srand();

		if (Config::read('environment') == 'dev') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
		}

		$siteID = Site::siteID();

		$site = new Site($siteID);
		if ($site->siteDefaultTimeZone) {
			date_default_timezone_set($site->siteDefaultTimeZone);
		} else {
			date_default_timezone_set('UTC');
		}

		if (!isset($_SESSION['siteID'])) { Session::setSession('siteID', $siteID); }
		if (!isset($_SESSION['userID'])) { Session::setSession('userID', 0); }
		if ($siteID == 0) { $splashy = $this->splashy(); die($splashy); }

		$router = new Router($_SERVER['REQUEST_URI']);
		$this->urlArray = $router->getUrlArray();

		if (isset($_COOKIE['perihelion']) && !Auth::isLoggedIn()) {
			$sessionID = $_COOKIE['perihelion'];
			$session = new Session($sessionID);
			if ($session->isValid()) { Auth::login($session->userID, false); }
		}

		if (!empty($_POST)) { $this->inputArray = $_POST; } else { $this->inputArray = array(); }

		$jsonStr = file_get_contents('php://input');
		$this->jsonStr = (!empty($jsonStr)) ? $jsonStr : null;

		$this->moduleArray = array();
		$modulePaths = array_filter(glob($_SERVER['DOCUMENT_ROOT'] . '/satellites/*'), 'is_dir');
		foreach ($modulePaths AS $modulePath) {
		    require($modulePath . '/php/module.php');
		    $this->moduleArray[] = substr($modulePath, strrpos($modulePath, '/') + 1);
		}

		require_once Config::read('perihelion.path') . 'vendor/autoload.php';

	}

	public function orbit() {

		switch ($this->urlArray[0]) {
				
				case 'image':
				
					if (ctype_digit($this->urlArray[1])) {

						$dt = new DateTime();

						$imageID = $this->urlArray[1];


						$image = new Image($imageID);
						$imagePath = $image->imagePath;
						$thumbImagePath = $imagePath;
						$contentType = 'image/' . $image->imageType;
						$legitThumbs = Image::thumbSizes();

						if (ctype_digit($this->urlArray[2])) { $thumbPixels = $this->urlArray[2]; }
						if (!empty($this->urlArray[2]) && isset($thumbPixels) && in_array($thumbPixels,$legitThumbs)) { $thumbRequested = true; } else { $thumbRequested = false; }

						if ($thumbRequested) {
							$thumbImagePath = Config::read('physical.path') . 'vault/images/' . $dt->format('Y') . '/' . $imageID . '-' . $thumbPixels . 'px.' . $image->imageType;
						}

						header("Content-Type: " . $contentType);
						header("Server: perihelion.xyz");
						header("X-Powered-By: Perihelion");
						
						if (file_exists($thumbImagePath)) {
							header('Content-Length: ' . filesize($thumbImagePath));
							readfile($thumbImagePath);
						} else {
							$thumbFormats =  Image::thumbFormats();
							if($thumbRequested && in_array($image->imageType,$thumbFormats)) {
								Image::createThumbnail($imagePath, $image->imageType, $thumbImagePath, $thumbPixels);
							} 
							header('Content-Length: ' . filesize($imagePath));
							readfile($imagePath);
						}

						exit();
						
					}
					break;
				
				case 'download':

					if (ctype_digit($this->urlArray[1])) {
						
						File::fileDownload($this->urlArray[1]);
						break;
						
					}
					
				case 'file':
				
					if (ctype_digit($this->urlArray[1])) {

						$file = new File($this->urlArray[1]);
						$fileName = $file->fileName;
						header('Content-Disposition: attachment; filename="' . $fileName . '"');
						File::fileDownload($this->urlArray[1]);
						break;
						
					}

				case 'sitemap.xml':
				
					header("Content-Type: text/xml");
					header("Server: perihelion.xyz");
					header("X-Powered-By: Perihelion");
					
					$sitemap = new SitemapXmlView();
					print($sitemap->xml);
					
					break;
					
				case 'theme.css':
					
					header("Content-Type: text/css");
					header("Server: perihelion.xyz");
					header("X-Powered-By: Perihelion");
					
					$site = new Site($_SESSION['siteID']);
					$theme = new Theme($site->themeID);
					print $theme->themeCss;
					
					break;

				case 'csv':

					$csv = new CSVController($this->urlArray, $this->inputArray, $this->moduleArray);
					$csv->export();
					
					break;
					
				case 'webhooks':

					foreach ($this->moduleArray AS $moduleName) {
						if ($this->urlArray[1] == $moduleName) {
							$webhookClass = ModuleUtilities::moduleToClassName($moduleName, 'Webhook');
							if (class_exists($webhookClass)) {
								$hook = new $webhookClass($this->urlArray,$this->inputArray);
								$hook->process();
							}
						}
					}

					break;
				
				case 'rss':
				
					$rss = new RSS();
					$feed = $rss->getFeed($this->urlArray);
					header("Content-Type: application/xml; charset=utf-8");
					print $feed;
					
					break;
				
				case 'api':
				    
				    $api = new PerihelionAPI($this->urlArray,$this->inputArray);
				    foreach ($this->moduleArray AS $moduleName) {
				        if ($this->urlArray[1] == $moduleName) {
							$apiClass = ModuleUtilities::moduleToClassName($moduleName, 'API');
				            $api = new $apiClass($this->urlArray,$this->inputArray, $this->jsonStr);
				        }
				    }
					$response = $api->response();

				    header('Content-Type: application/json');
				    header("Server: perihelion.xyz");
				    print $response;
				    break;
				    
				case 'pdf':

					$doc = '<h1>Perihelion PDF</h1>';
					$fileObject = null;
					$fileObjectID = null;

					foreach ($this->moduleArray AS $moduleName) {
						if ($this->urlArray[1] == $moduleName) {
							$pdfClass = ModuleUtilities::moduleToClassName($moduleName, 'PDF');
							if (class_exists($pdfClass)) {
								$pdf = new $pdfClass($this->urlArray,$this->inputArray);
								$doc = $pdf->doc();
								if (method_exists($pdfClass, 'getFileObject') && method_exists($pdfClass, 'getFileObjectID')) {
									$fileObject = $pdf->getFileObject();
									$fileObjectID = $pdf->getFileObjectID();
								}
							}
						}
					}

					require_once Config::read('perihelion.path') . 'vendor/autoload.php';
				
					$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
					$fontDirs = $defaultConfig['fontDir'];

					$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
					$fontData = $defaultFontConfig['fontdata'];

					$mpdf = new \Mpdf\Mpdf([
						'fontDir' => array_merge($fontDirs, [
							'perihelion/assets/fonts',
						]),
						'fontdata' => [
							// Takaoフォント(Pゴシック)
							'takao_pgothic' => [
								'R' => 'TakaoPGothic.ttf'
							],
							// Takaoフォント(ゴシック)
							'takao_gothic' => [
								'R' => 'TakaoGothic.ttf'
							],
							// Takaoフォント(P明朝)
							'takao_pmincho' => [
								'R' => 'TakaoPMincho.ttf'
							],
							// Takaoフォント(明朝)
							'takao_mincho' => [
								'R' => 'TakaoMincho.ttf'
							]
						],
						'default_font' => 'takao_pgothic',
						'tempDir' => sys_get_temp_dir()
					]);
					$mpdf->autoScriptToLang = true;
					$mpdf->autoLangToFont = true;
					
					$stylesheet = file_get_contents(Config::read('web.root') . 'perihelion/assets/css/print.css');
					$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
					$mpdf->WriteHTML($doc);

					$printPDF = new PrintPDF($this->urlArray, $this->moduleArray);
					$filename = $printPDF->filename('pdf');

					if ($fileObject && $fileObjectID) {

						$fileArray = $this->saveObjectPDF($filename, $fileObject, $fileObjectID);
						$mpdf->Output($fileArray['filePath'],'F');
						$fileSize = filesize($fileArray['filePath']);
						$fileID = $fileArray['fileID'];

						$file = new File($fileID);
						$file->fileSize = $fileSize;
						$cond = array('fileID' => $fileID);
						File::update($file, $cond, true, false,'perihelion_');

					}

					$mpdf->Output($filename,\Mpdf\Output\Destination::DOWNLOAD);
				 
				case 'print':
					
					header("Content-Language: " . $_SESSION['lang']);
					header("Content-Type: text/html; charset=utf-8");
					header("Server: perihelion.xyz");
					header("X-Powered-By: Perihelion");
					
					$doc = 'perihelion print';
					foreach ($this->moduleArray AS $moduleName) {
				        if ($this->urlArray[1] == $moduleName) {
							$printClass = ModuleUtilities::moduleToClassName($moduleName, 'Print');
				            if (class_exists($printClass)) {
				            	$print = new $printClass($this->urlArray,$this->inputArray);
				            	$doc = $print->doc();
				            }
				        }
				    }
					print $doc;
					break;
				
				default:
				
					header("Content-Language: " . $_SESSION['lang']);
					header("Content-Type: text/html; charset=utf-8");
					header("Server: perihelion.xyz");
					header("X-Powered-By: Perihelion");
					
					$sc = new StateController($this->urlArray, $this->inputArray, $this->moduleArray);
					$sc->setState();
					$vc = new ViewController($this->urlArray, $this->inputArray, $this->moduleArray, $sc->getErrors(), $sc->getMessages());
					print($vc->getView());

			}

	}
	
	private function splashy() {
	
		$splashy = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
			<html>
				<head>
					<script>
						<!--
							function toPerihelion(){ window.location = "' . Config::read('copyright.url') . '"; }
						//-->
					</script>
					<title>Perihelion</title>
					<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
					<link rel="icon" type="image/x-icon" href="/assets/images/favicons/favicon-1.ico"/>
				</head>
				
				<body style="background-color:#fff;padding-top:150px;" onLoad="setTimeout(\'toPerihelion()\', 3000)">
					<div style="text-align:center;">
						<a href="https://perihelion.xyz/"><img src="/assets/images/perihelion-logo.png" style="max-width:100%;border-style:none;"></a>
					</div>
				</body>
				
			</html>
		';

		return($splashy);
	
	}

	private function saveObjectPDF($filename, $fileObject, $fileObjectID) {

		$file = new File();

		$file->filePath = substr($_SERVER['DOCUMENT_ROOT'],0,strrpos($_SERVER['DOCUMENT_ROOT'],'/'))."/vault/files/";
		$file->fileName = $filename;
		$file->fileOriginalName = $filename;
		$file->fileType = 'pdf';
		$file->fileSize = 0;
		$file->fileObject = $fileObject;
		$file->fileObjectID = $fileObjectID;
		$file->fileTitleEnglish = $filename;
		$file->fileTitleJapanese = $filename;

		$file->fileID = File::insert($file,true,'perihelion_');
		$file->filePath = $file->filePath . $file->fileID . '.' . $file->fileType;
		$cond = array('fileID' => $file->fileID);
		File::update($file, $cond, true,false, 'perihelion_');

		$fileArray = array();
		$fileArray['filePath'] = $file->filePath;
		$fileArray['fileID'] = $file->fileID;

		return $fileArray;

	}

}

?>