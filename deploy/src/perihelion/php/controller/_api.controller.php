<?php

final class PerihelionAPI {

	private $loc;
	private $input;

	public function __construct($loc, $input) {

		$this->loc = $loc;
		$this->input = $input;

	}

	public function response() {

		$loc = $this->loc;
		$input = $this->input;

		if ($loc[1] == 'v1') {

			if ($loc[2] == 'image') {

				// need an API key check here (!)

				if ($loc[3] == 'delete') {

					// /api/v1/image/delete/
					$image = new Image($input['imageID']);
					$image->deleteImage();

				}

				if ($loc[3] == 'set-default') {

					// /api/v1/image/set-default/

					$nilp = new NewImageListParameters();
					$nilp->imageObject = $input['imageObject'];
					$nilp->imageObjectID = $input['imageObjectID'];
					$nil = new NewImageList($nilp);
					$images = $nil->images();

					foreach ($images AS $imageID) {
						$originalStateImage = new Image($imageID);
						$image = new Image($imageID);
						if ($imageID == $input['imageID'] && $image->imageDisplayClassification != 'mainImage') {
							$image->imageDisplayClassification = 'mainImage';
						}
						if ($imageID != $input['imageID'] && $image->imageDisplayClassification = 'mainImage') {
							$image->imageDisplayClassification = '';
						}
						if ($originalStateImage != $image) {
							$cond = array('imageID' => $imageID);
							Image::update($image, $cond);
						}
					}

				}

			}

			if ($loc[2] == 'file') {

				// need an API key check here (!)

				if ($loc[3] == 'delete') {

					// /api/v1/file/delete/
					$file = new File($input['fileID']);
					$file->deleteFile();
					return json_encode($file);

				}

			}

			if ($loc[2] == 'note') {

				// need an API key check here (!)

				if ($loc[3] == 'delete') {

					// /api/v1/note/delete/
					$note = new Note($input['noteID']);
					$note->deleteNote();
					return json_encode($note);

				}

			}

			if ($loc[2] == 'partial') {

				if ($loc[3] == 'admin') {

					if (!Auth::isAdmin()) { die('HTTP/1.1 401 Unauthorized'); } // really @chishiki? come on bro

					if ($loc[4] == 'dev') {

						$dv = new DevView();

						if (isset($input['new-key-row'])) {
							return $dv->codeGeneratorFormKeyRow(null, 'int', 'NOT NULL', 'zero', false, true, false);
						}

						if (isset($input['new-field-row'])) {
							return $dv->codeGeneratorFormFieldRow();
						}

					}

				}

			}

		}

		$response = '{"api":"perihelion"}';
		return $response;

	}

}

?>