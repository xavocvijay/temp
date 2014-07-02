<?php

namespace xsocialApp;

class View_Server_Photos extends \View{
	function init(){
		parent::init();
		

		$photos=$this->add('xsocialApp/View_Photo_Lister');
		$images=$this->add('xsocialApp/Model_Activity');
		$images->addCondition('from_member_id',$this->api->xsocialauth->model->id);
		$images->setOrder('id','desc');
		$photos->setModel($images);



	}

	
}