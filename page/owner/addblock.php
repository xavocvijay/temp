<?php

class page_xsocialApp_page_owner_addblock extends page_xsocialApp_page_owner_main{

	function init(){
		parent::init();

		$crud=$this->add('CRUD');

		$crud->setModel('xsocialApp/Model_AddBlock');
		$crud->addRef('xsocialApp/BlockImages',array('label'=>'Block Images'));
	}
}