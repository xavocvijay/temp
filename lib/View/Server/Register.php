<?php

namespace xsocialApp;

class View_Server_Register extends \View{
	function init(){
		parent::init();

		$form=$this->add('Form');
		$form->addField('line','first_name')->validateNotNull('Required Field');
		$form->addField('line','last_name')->validateNotNull('Required Field');
		$date=$form->addField('DatePicker','DOB')->validateNotNull('Required Field');
		
		$form->addField('Radio','gender')->setValueList(array('Male'=>'Male','Female'=>'Female'))->validateNotNull('Required Field');
		$form->addField('line','emailId','E-mail')->validateNotNull()->validateField('filter_var($this->get(), FILTER_VALIDATE_EMAIL)');
		$form->addField('password','password')->validateNotNull('Required Field');
		$form->addField('password','repassword','Re-password')->validateNotNull('Required Field');
		$form->addField('line','referId','Refer Id');
		$form->addField('checkBox','term',
      					$form->js()->univ()->newWindow('?epan=web&subpage=term')
       					->getLink('I Agree to Rules and Terms')
    					)->validateNotNull('You must agree to the rules');

		$form->addSubmit('Register');

		if($form->isSubmitted()){
			
			//TODO Check Eligibility of member
			$member=$this->add('xsocialApp/Model_MemberAll');
			$year_old=$member->is_eligible($form['DOB']);
			if($year_old <= 14){	
				$form->displayError('DOB','you are not Eligible');
			}

			//todo check member agree with our term and condition
			if(!$form['term']){
				$form->displayError('term','you must agree with buddy trade term and condition');
			}	

			//TODO PASSWORD AND RE-PASSWORD CHECK 
			if($form['password'] != $form['repassword']){	
				//$form->js()->univ()->errorMessage('Re-type Password')->execute();
				$form->displayError('repassword','Re-password not Match');
			}
			
			//TODO CHECK REFER ID 
			if($form['referId'] != null){
				if(!$member->has_referId($form['referId'])){
					//$form->js()->univ()->errorMessage('Refer id is not Found')->execute();
					$form->displayError('referId','Refer Id Not Found');
				}
			}	

			//TODO CHECK EXISTING USER
			$user=$this->add('xsocialApp/Model_MemberAll');
			if($user->is_registered($form['emailId'])){
				$form->displayError('emailId','Email id Alredy Existing');
			}	

			//Check Capcha Validation	
			// if (!$form->getElement('captcha')->captcha->isSame($form->get('captcha'))){
			// 	$form->displayError('captcha','Text Not Match');
			// }
		  	
		 //  	$form->addField('line','captcha')->add('x_captcha/Controller_Captcha');


		// TODO INSERTING FORM VALUE INTO 
			// $visitor['firstname']=$form['first_name'];
			// $visitor['lastname']=$form['last_name'];
			
			// $visitor['password']=$form['password'];
			// $visitor['emailID']=$form['email_id'];
			// $random=rand(1000,99999); //generate random number (Activation code)
			// //$this->add('View')->set($random);		
			// $visitor['activation_code']=$random;

			$visitor=$this->add('xsocialApp/Model_MemberAll');			
			if($visitor->register($form->getAllFields())){
				$visitor->sendVerificationMail();
				$this->api->redirect($this->api->url(null,array('subpage'=>'xsocial-verifyaccount')));
			}

		//TODO SEND VERIFICATION EMAIL Working 
		
			if($visitor->save()){
				$form->js()->univ()->successMessage('Register Succesfully')->execute();				
			}

		}

	}
}