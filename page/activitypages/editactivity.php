<?php

class page_xsocialApp_page_activitypages_editactivity extends Page{
	function init(){
		parent::init();

		$this->api->stickyGET('edit_activity_id');
		$this->api->stickyGET('activity_view');
		// throw new \Exception($_GET['edit_activity_id'], 1);
		

		$activity=$this->add('xsocialApp/Model_Activity');
		$activity->load($_GET['edit_activity_id']);
		// $activity->addCondition('activity_type',$_GET['activity_type']);
		
		$form=$this->add('Form');
		$form->setModel($activity,array('activity_detail'));
		$form->addSubmit('Edit');		

		if($form->isSubmitted()){
			
			
			$form->update();
			$form->js(null,$this->js()->_selector('#'.$_GET['activity_view'])->trigger('reload'))->univ()->closeDialog()->execute();
		}
		// $form->setModel($activity);


		


		}
}
