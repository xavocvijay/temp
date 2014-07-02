<?php
class page_xsocialApp_page_uninstall extends page_componentBase_page_uninstall{
	function init(){
		parent :: init();
		$this->uninstall();
	}

}
