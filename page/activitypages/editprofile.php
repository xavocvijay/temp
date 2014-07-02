<?php

class page_xsocialApp_page_activitypages_editprofile extends Page{
	function init(){
		parent::init();

		$short_name = 'pf';
		unset($this->owner->elements[$this->short_name]);
        $this->name = 't1' . '_' . $short_name;
        $this->short_name = $short_name;
        
        if (!$this->auto_track_element) {
            $this->owner->elements[$short_name] = true;
        } else {
            $this->owner->elements[$short_name] = $this;
        }

		$this->api->stickyGET('member_id');
		$form = $this->add('Form');

		$images=$this->add('xsocialApp/Model_Images');
		$images->addCondition('member_id',$_GET['member_id']);
		
		// $form->addField('upload','timeline_pic');
		$form->setModel($images,array('img_id'));


			
		$form->addSubmit('Update Profile');

		if($form->isSubmitted()){
			if(!$form['img_id'])
				$form->displayError('img_id','please select');
			
				
			if($this->api->xsocialauth->model->updateProfilePic($form->getAllFields()))
				$form->js(null,array(
								$this->js()->_selector('.profilepic')->trigger('reload'),
								$this->js()->_selector('.activity')->trigger('reload')

							))->univ()->closeDialog()->execute();
		}		




	}
}
