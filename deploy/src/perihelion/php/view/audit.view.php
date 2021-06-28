<?php

class AuditView {
	
	private $loc;
	private $input;
	private $errors;

	public function __construct($loc = array(), $input = array(),  $errors = array()) {
		
		$this->loc = $loc;
		$this->input = $input;
		$this->errors = $errors;
		
	}
	
	public function auditTrail($type, $siteID = null, $userID = null, $auditObject = null, $startDate = null, $endDate = null) { // manager|admin

		if (!in_array($type, array('admin','manager'))) { die('do not pass go do not collect &dollar;200'); }

		$auditTrail = $this->auditTrailFilters($type, $siteID, $userID, $auditObject, $startDate, $endDate) . '
			<div class="table-responsive">
				<table class="table table-striped table-sm">
					<tbody>' . $this->auditTrailRows($type, $siteID, $userID, $auditObject, $startDate, $endDate) . '</tbody>
				</table>
			</div>
		';

		$cardView = new CardView('perihelion_audit_trail', array('container-fluid'), '', array('col-12'), Lang::getLang('auditTrail'), $auditTrail, false);
		return $cardView->card();
		
	}

	private function auditTrailFilters($type, $siteID, $userID, $auditObject, $startDate, $endDate) {

		$siteFilter = '';
		if ($type == 'admin') {
			$siteFilter = '<div class="col-12 col-xl-2 mb-2">' . SiteView::sitesDropdown($siteID) . '</div>';
		}

		$filters = '
			<form method="post" action="/' . Lang::prefix() . $type . '/audit/">
				<div class="form-group row">
					' . $siteFilter . '
					<div class="col-6 col-xl-2 mb-2">
						<div class="input-group">
							<div class="input-group-append"><div class="input-group-text"><span class="far fa-calendar-alt"></span></div></div>
							<input type="date" name="startDate" class="form-control" value="' . ($startDate?$startDate:'') . '">
						</div>
					</div>
					<div class="col-6 col-xl-2 mb-2">
						<div class="input-group">
							<div class="input-group-append"><div class="input-group-text"><span class="far fa-calendar-alt"></span></div></div>
							<input type="date" name="endDate" class="form-control" value="' . ($endDate?$endDate:'') . '">
						</div>
					</div>
					<div class="col-12 col-xl-2 mb-2">' . UserView::userDropdown($userID) . '</div>
					<div class="col-12 col-xl-2 mb-2">' . self::auditObjectDropdown($auditObject) . '</div>
					<div class="col-12 col-xl-2 mb-2"><button type="submit" class="form-control btn btn-primary">' . Lang::getLang('filter') . '</button></div>
				</div>
			</form>
		';
		
		return $filters;

	}

	private function auditTrailRows($type, $siteID, $userID, $auditObject, $startDate, $endDate) {

		$auditTrail = Audit::getAuditTrailArray($siteID, $userID, $auditObject, $startDate, $endDate, 100);

		$rows = '';

		foreach ($auditTrail as $auditID) {

			$ioa = new Audit($auditID);
			$site = new Site($ioa->siteID);
			$user = new User($ioa->auditUserID);

			$siteTD = '';
			$modalTD = '';
			if ($type == 'admin') {
				$siteTD = '<td class="text-nowrap"><span><img src="/perihelion/assets/images/favicons/favicon-' . $ioa->siteID . '.ico" style="width:16px;"> ' . $site->siteURL . '</span></td>';
				$modalTD = '<td class="text-center"><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#audit_modal_' . $ioa->auditID . '">' . Lang::getLang('view') . '</button>';
			}

			$rows .= '
				<tr>
					' . $siteTD . '
					<td class="text-nowrap">
						<span class="fas fa-clock" data-toggle="tooltip" data-placement="top" title="' . $ioa->auditDateTime . '"></span>
						' . $ioa->auditDateTime . '
					</td>
					<td class="text-nowrap">
						<a href="http://whatismyipaddress.com/ip/' . $ioa->auditIP . '" target="_blank">
						<span class="fas fa-globe" data-toggle="tooltip" data-placement="top" title="' . $ioa->auditIP . '"></span>
						</a> ' . $ioa->auditIP . '
					</td>
					<td>' . $user->username . '</td>
					<td>' . $ioa->auditAction . '</td>
					<td>' . $ioa->auditObject . ' ' . ($ioa->auditObjectID?'['.$ioa->auditObjectID.']':'') . '</td>
					<td>' . $ioa->auditResult . '</td>
					' . $modalTD . '
				</tr>
			' . $this->auditTrailModal($type, $ioa);

		}

		return $rows;

	}
	
	private function auditTrailModal($type, Audit $ioa) {

		$detail = '';
		if ($ioa->auditObject != 'Content' && !empty($ioa->auditNote)) {
			$json = json_decode($ioa->auditNote);
			$detail = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		}

		$modal = '
			<div class="modal fade" id="audit_modal_' . $ioa->auditID . '" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="audit_modal_label_' . $ioa->auditID . '">' . $ioa->auditObject . ($ioa->auditObjectID?' [' . $ioa->auditObjectID . ']':'') . '</h5>
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body"><pre>' . $detail . '</pre></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">' . Lang::getLang('close') . '</button>
						</div>
					</div>
				</div>
			</div>
		';

		return $modal;

	}
	
	private function auditObjectDropdown($auditObject) {

		$auditObjects = Audit::getAuditObjectArray();

		$h = '<select class="form-control" name="auditObject">';
			$h .= '<option value="">' . Lang::getLang('object') . '</option>';
			foreach($auditObjects AS $thisAuditObject) {
				$h .= '<option value="' . $thisAuditObject . '"' . ($thisAuditObject==$auditObject?' selected':'') . '>' . $thisAuditObject . '</option>';
			}
		$h .= '</select>';

		return $h;

	}

}

?>