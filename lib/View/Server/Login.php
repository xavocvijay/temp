<?php

namespace xsocialApp;

class View_Server_Login extends \View{
	function init(){
		parent::init();
		
		// $this->add('p')->set('Login Form');
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$form->addField('line','emailID','Email Id')->validateNotNull('Required Field');
		$form->addField('password','password','Password')->validateNotNull('Required Field');
		// $form->addSubmit('login');

		$form->add('Button')->set('Login')
		->addStyle(array('margin-top'=>'25px','margin-left'=>'37px'))
			->js('click')->submit();
		
		// Redirect to Verify Account
		$fb_login_btn=$this->add('Button')->set('Login Via FB');
			if($fb_login_btn->isClicked()){
				$config = getcwd() . '/lib/hybridauth/config.php';
				require_once( getcwd()."/lib/hybridauth/Hybrid/Auth.php" );
				try{
					
					$hybridauth = new \Hybrid_Auth( $config );

					$adapter = $hybridauth->authenticate( "facebook" );
					
					$user_profile = $adapter->getUserProfile();
					
				}
				catch( Exception $e ){
					die( "<b>got an error!</b> " . $e->getMessage() ); 
				}
		 	}

		$verify_btn=$this->add('Button')->set('Verification');
			if($verify_btn->isClicked()){
				$this->api->redirect($this->api->url(null,array('subpage'=>'xsocial-verifyaccount')));
		 	}

		if($form->isSubmitted()){
			$member=$this->add('xsocialApp/Model_AllActiveMembers');
		 	if(!$member->tryLogin($form['emailID'],$form['password']))
		 		$form->displayError('emailID','wrong username');
		 		
				// Redirect to Dashboard
				$this->js()->univ()->redirect($this->api->url(null,array('subpage'=>'xsocial-dashboard')))->execute();
			}
	}
		function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/xsocial-login');
	}
}
