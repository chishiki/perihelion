<?php

final class BreadcrumbsView {

    private array $loc;
    private array $splice;
    private array $remove;
    private array $displayOnly;

	private $breadcrumbs;

    public function __construct(
		array $loc,
		array $splice = array('highlight'),
		array $remove = array(),
		array $displayOnly = array()
	) {

		$this->loc = $loc;
		$this->splice = $splice; // remove this item and everything following it
		$this->remove = $remove; // remove this item
		$this->displayOnly = $displayOnly; // display but not as a link

        $links = $this->links($this->loc);

		$items = '';
		foreach ($links AS $link) {
			$items .= '<li class="' . $link['class'] . '">' . ($link['href']?'<a href="'.$link['href'].'">'.$link['anchor'].'</a>':$link['anchor']) . '</li>';
		}

        $this->breadcrumbs = '

			<div class="container-fluid">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						' . $items . '
					</ol>
				</nav>
			</div>

		';

    }

    private function links($loc) {

        $links = array();
 		$loc = array_filter($loc);

 		// splice
 		foreach ($this->splice AS $splice) {
			$splicePos = array_search($splice, $loc);
			if ($splicePos) { array_splice($loc, $splicePos); }
		}

		// ignore
 		foreach ($this->remove AS $remove) {
			$removePos = array_search($remove, $loc);
			if ($removePos) { unset($loc[$removePos]); }
			$loc = array_values($loc);
		}

		$count = count($loc);

        $links[] = array('href' => '/' . Lang::prefix(), 'class' => 'breadcrumb-item' . (!$loc[1]?' active':''), 'anchor' => Lang::getLang('breadcrumbIndex'));

		for ($i = 0; $i < $count; $i++) {

			$href = '/' . Lang::prefix();
			for ($x = 0; $x <= $i; $x++) { $href .= $loc[$x] . '/'; }
			$class = 'breadcrumb-item';

			if (is_numeric($loc[$i])) { $anchor = $loc[$i]; }
			else { $anchor = Lang::getLang('breadcrumb' . StringUtilities::hyphensToCamel($loc[$i])); }

			if (!isset($loc[$i+1]) || is_numeric($loc[$i+1])) { $class .= ' active'; $href = null; }

			if (in_array($loc[$i],$this->displayOnly)) { $href = null; }

			$links[] = array('href' => $href, 'class' => $class, 'anchor' => $anchor);

		}

        return $links;

    }

    public function breadcrumbs() {

        return $this->breadcrumbs;

    }


}

?>