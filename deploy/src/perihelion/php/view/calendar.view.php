<?php

class CalendarView {
	
	private $selectedDateTimeStamp;
	private $selectedYear;
	private $selectedMonth;
	private $selectedMonthName;
	private $selectedMonthFirstDay;
	private $selectedMonthLastDay;
	private $selectedMonthNumberOfDays;
	private $columnToPlaceFirstDay;
	private $numberOfDaysInFirstRow;
	private $columnToPlaceLastDay;
	private $numberOfDaysInLastRow;
	private $numberOfMiddleCalendarRows;
	private $selectedDateMinusOneYear;
	private $selectedDateMinusOneMonth;
	private $selectedDateMinusOneDay;
	private $selectedDatePlusOneDay;
	private $selectedDatePlusOneMonth;
	private $selectedDatePlusOneYear;
	
	public function __construct($selectedDate) {
		
		$this->selectedDateTimeStamp = strtotime($selectedDate);
		$this->selectedYear = date('Y', $this->selectedDateTimeStamp);
		$this->selectedMonth = date('m', $this->selectedDateTimeStamp);
		$this->selectedMonthName = date('F', $this->selectedDateTimeStamp);
		$this->selectedMonthFirstDay = date('Y-m-01', $this->selectedDateTimeStamp);
		$this->selectedMonthLastDay = date('Y-m-t', $this->selectedDateTimeStamp);
		$this->selectedMonthNumberOfDays = date('t', $this->selectedDateTimeStamp);
		
		$this->columnToPlaceFirstDay = date('w', strtotime($this->selectedMonthFirstDay));
		$this->numberOfDaysInFirstRow = 7 - $this->columnToPlaceFirstDay;
		
		$this->columnToPlaceLastDay = date('w', strtotime($this->selectedMonthLastDay));
		$this->numberOfDaysInLastRow = 1 + $this->columnToPlaceLastDay;
		
		if ($this->numberOfDaysInLastRow != 7) {
			$this->numberOfMiddleCalendarRows = ($this->selectedMonthNumberOfDays - $this->numberOfDaysInFirstRow - $this->numberOfDaysInLastRow) / 7;
		} else {
			$this->numberOfMiddleCalendarRows = ($this->selectedMonthNumberOfDays - $this->numberOfDaysInFirstRow) / 7;
		}
		
		$this->selectedDateMinusOneYear = date('Y-m-d' , strtotime($selectedDate . ' - 1 year'));
		$this->selectedDateMinusOneMonth = date('Y-m-d' , strtotime($selectedDate . ' - 1 month'));
		$this->selectedDateMinusOneDay = date('Y-m-d' , strtotime($selectedDate . ' - 1 day'));
		$this->selectedDatePlusOneDay = date('Y-m-d' , strtotime($selectedDate . ' + 1 day'));
		$this->selectedDatePlusOneMonth = date('Y-m-d' , strtotime($selectedDate . ' + 1 month'));
		$this->selectedDatePlusOneYear = date('Y-m-d' , strtotime($selectedDate . ' + 1 year'));
		
	}
	
	public function monthCalendar() {
	

		$html = '
			<div class="container">
				<div class="row">

					<div class="col-12 calMonth">
					
						<div class="calMonthHeader">' . $this->selectedMonthName . ', ' . $this->selectedYear . '</div>

		';
		
		$html .= $this->monthCalendarWeeks();
		
		
		$html .= '

					</div>

				</div> <!-- END ROW -->
			</div> <!-- END CONTAINER -->
		';
		
		return $html;
		
	}
	
	public function monthCalendarWeeks() {
		
		$html = '
			<div class="calMonthWeek calMonthDaysOfWeek">
				<div class="calMonthDay calMonthDayOfWeek">' . Lang::getLang('sun') . '</div>
				<div class="calMonthDay calMonthDayOfWeek">' . Lang::getLang('mon') . '</div>
				<div class="calMonthDay calMonthDayOfWeek">' . Lang::getLang('tue') . '</div>
				<div class="calMonthDay calMonthDayOfWeek">' . Lang::getLang('wed') . '</div>
				<div class="calMonthDay calMonthDayOfWeek">' . Lang::getLang('thu') . '</div>
				<div class="calMonthDay calMonthDayOfWeek">' . Lang::getLang('fri') . '</div>
				<div class="calMonthDay calMonthDayOfWeek">' . Lang::getLang('sun') . '</div>
			</div>
			
			<div class="calMonthWeek">
				<div class="calMonthDay calMonthDayOutOfRangeDay">28</div>
				<div class="calMonthDay calMonthDayOutOfRangeDay">29</div>
				<div class="calMonthDay calMonthDayOutOfRangeDay">30</div>
				<div class="calMonthDay"><a href="#">1</a></div>
				<div class="calMonthDay"><a href="#">2</a></div>
				<div class="calMonthDay"><a href="#">3</a></div>
				<div class="calMonthDay"><a href="#">4</a></div>
			</div>
			
			<div class="calMonthWeek">
				<div class="calMonthDay"><a href="#">5</a></div>
				<div class="calMonthDay"><a href="#">6</a></div>
				<div class="calMonthDay"><a href="#">7</a></div>
				<div class="calMonthDay"><a href="#">8</a></div>
				<div class="calMonthDay"><a href="#">9</a></div>
				<div class="calMonthDay"><a href="#">10</a></div>
				<div class="calMonthDay"><a href="#">11</a></div>
			</div>
			
			<div class="calMonthWeek">
				<div class="calMonthDay"><a href="#">12</a></div>
				<div class="calMonthDay"><a href="#">13</a></div>
				<div class="calMonthDay"><a href="#">14</a></div>
				<div class="calMonthDay"><a href="#">15</a></div>
				<div class="calMonthDay"><a href="#">16</a></div>
				<div class="calMonthDay"><a href="#">17</a></div>
				<div class="calMonthDay"><a href="#">18</a></div>
			</div>

			<div class="calMonthWeek">
				<div class="calMonthDay"><a href="#">19</a></div>
				<div class="calMonthDay"><a href="#">20</a></div>
				<div class="calMonthDay"><a href="#">21</a></div>
				<div class="calMonthDay"><a href="#">22</a></div>
				<div class="calMonthDay"><a href="#">23</a></div>
				<div class="calMonthDay"><a href="#">24</a></div>
				<div class="calMonthDay"><a href="#">25</a></div>
			</div>

			<div class="calMonthWeek">
				<div class="calMonthDay"><a href="#">26</a></div>
				<div class="calMonthDay"><a href="#">27</a></div>
				<div class="calMonthDay"><a href="#">28</a></div>
				<div class="calMonthDay"><a href="#">29</a></div>
				<div class="calMonthDay"><a href="#">30</a></div>
				<div class="calMonthDay"><a href="#">31</a></div>
				<div class="calMonthDay calMonthDayOutOfRangeDay">1</a></div>
			</div>
		';
		return $html;
		
	}
	
	
}


?>