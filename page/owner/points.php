<?php
class page_xsocialApp_page_owner_points extends page_xsocialApp_page_owner_main{
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$crud->setModel('xsocialApp/Point');

		$crud->addRef('xsocialApp/PointTransaction');
			
	}

}

