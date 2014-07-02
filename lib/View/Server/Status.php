<?php

namespace xsocialApp;

class View_Server_Status extends \View{
	public $status_text_box_field;

	function init(){
		parent::init();

		$status=$this->add('xsocialApp/Model_Activity');
		$status->getElement('activity_detail')->type('text')->caption('Say Something');
		$status->getElement('img_id')->caption('Image ');

		$form=$this->add('Form')->addClass('status-bar');
		$form->setModel($status,array('activity_detail','img_id','video_url','visibility'));
		$this->status_text_box_field = $form->getElement('activity_detail');
		$form->addSubmit('Post');

		$form->getElement('video_url')->addClass('status-video-url');
		if($form->isSubmitted()){	
			$activity = $this->add('xsocialApp/Model_Activity');
			$activity->newPost($form->getAllFields());

			$form->js(null,$this->js()->_selector('.activity')->trigger('reload'))->reload()->execute();
		}
	}

	function recursiveRender(){
		// $this->api->template->appendHTML('js_include','<script type="text/javascript" href="epan-components/xsocialApp/templates/js/xsocialApp-status.js" />');
		$this->js(true)->_load('xsocialApp-js1')
		->univ()->status();
		// ->hide_photo_video_field_of_status($this->status_text_box_field->name);
		parent::recursiveRender();
	}

	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css',
		  		'js'=>'templates/js'
				)
			)->setParent($l);
		return array('view/xsocial-status');
	}
}