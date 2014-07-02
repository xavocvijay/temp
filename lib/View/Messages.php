<?php

namespace xsocialApp;

class View_Messages extends \View{

function init(){
	parent::init();

	// $v=$this->add('View')->set('')->addClass('glyphicon glyphicon-envelope');
	
	$p=$this->add('View_Popover');

	$messagelist=$p->add('xsocialApp/View_Message_List');
	
	$messages=$this->add('xsocialApp/Model_Message');
	$messages->addCondition('message_to_member_id',$this->api->xsocialauth->model->id);
	$messages->addCondition('is_read',false);
	

	$messagelist->setModel($messages);

	$count=$messages->count()->getOne();
	
	if($count > 0) //for set color when friend request is more than 1
		$this->add('View')->set($count)->setStyle('background','#A81010');
	else
		$this->add('View')->set($count);

	$this->js('click',$p->showJS($this));


	
	}
}
