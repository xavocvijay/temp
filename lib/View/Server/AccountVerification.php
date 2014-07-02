<?php

namespace xsocialApp;

class View_Server_AccountVerification extends \View{
	function init(){
		parent::init();

		$form=$this->add("Form");                               
		$form->addField("line","email")->validateNotNull("Required field");     
		$form->addField("line","verification_code")->validateNotNull("Required field");

		$form->addSubmit("Verify");

		if($form->isSubmitted()){

			$member=$this->add('xsocialApp/Model_AllActiveMembers');

			if($member->Verify($form['email'],$form['verification_code'])){
				$this->api->redirect($this->api->url(null,array('subpage'=>'xsocial-login')));
			}
			else{
				// $this->api->js()->univ()->errorMessage("Validation Code not Valid")->execute();			

				$form->js()->reload()->execute();
			}
		} 

		if($_GET['verifycode']){

			$form['verification_code']= $_GET['verifycode'];
			$form->isSubmitted();
		}   

	}
}