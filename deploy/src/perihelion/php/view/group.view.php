<?php

class GroupView {
	
	public $html;

	public function __construct($groupID = 0) {

	}
	
	public function groupDropdown($selectedGroupID = 0) {
	
		$groupArray = Group::getGroupArray();
		
		$this->html = "<select name=\"groupID\" class=\"form-control\">";
		
			foreach ($groupArray AS $groupID) {
				$group = new Group($groupID);
				$this->html .= "<option value=\"". $groupID . "\">" . $group->groupName() . "</option>";
			}
		
		$this->html .= "</select>";
		
		return $this->html;
	
	}
	
}

?>