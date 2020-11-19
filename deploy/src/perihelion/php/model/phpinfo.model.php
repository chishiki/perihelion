<?php

class PhpInfo {

	private $phpinfo;
	
	public function __construct($tileID = null) {

	    $this->phpinfo = '';
	    
	    ob_start();
	    phpinfo();
	    $info = ob_get_clean();
	    
	    $doc = new DOMDocument();
	    $doc->loadHTML($info);
	    $nodes = $doc->getElementsByTagName('body');
	    
	    foreach($nodes as $node) {
	        $this->phpinfo = $node->ownerDocument->saveXML($node);
	        $this->phpinfo = substr($this->phpinfo, 6); // remove <body>
	        $this->phpinfo = substr($this->phpinfo, 0, -7); // remove </body>
	    }

	}

	public function getInfo() {

	    return $this->phpinfo;

	}
	
}

?>