<?php

class page_xsocialApp_page_activitypages_share extends Page{
	function init(){
		parent::init();
		$this->api->stickyGET('activity_id');
		
		$activity=$this->add('xsocialApp/Model_Activity');
		$activity->load($_GET['activity_id']);
		
		$form=$this->add('Form');
		$form->addField('text','say_something');
		$form->addField('dropdown','visibility')->setValueList(array('Public'=>'Public','Private'=>'Private','Friends'=>'Friends'));
		$form->addSubmit('Share');

		if($form->isSubmitted()){
			$shared_activity = $activity->share_it($form['visibility'],$form['say_something'],$activity);

			$new_shared_activity = $this->add('xsocialApp/View_Activity',array('activity_id'=>$shared_activity->id,'activity_array'=>$shared_activity));
			$new_shared_activity_html = $new_shared_activity->getHTML();

			$js=array(
					$this->js()->univ()->successMessage('You Just Shared An Activity'),
					$this->js()->_selector('#a6b94fb3___activity_xsocialapp_view_activity_list')->prepend($new_shared_activity_html)
				);
			$form->js(null,$js)->univ()->closeDialog()->execute();
		}

	}
}