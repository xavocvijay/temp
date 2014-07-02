<?php
class page_xsocialApp_page_owner_main extends page_componentBase_page_owner_main{
	
	function init(){
		parent::init();

		$this->add('Menu')
			->addMenuItem('xsocialApp_page_owner_profilefields','Profile Fields')
			->addMenuItem('xsocialApp_page_owner_points','Point Management')
			->addMenuItem('xsocialApp_page_owner_addblock','Add Block Images');
	
		// $crud=$this->add('CRUD');
		// $crud->setModel('xsocialApp/ProfileFields');
	}

}
