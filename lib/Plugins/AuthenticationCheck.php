<?php

namespace xsocialApp;

class Plugins_AuthenticationCheck extends \componentBase\Plugin{
	public $namespace = 'xsocialApp';

	function init(){
		parent::init();
		
		$this->addHook('website-page-loaded',array($this,'AuthenticatePage'));
	}

	function AuthenticatePage($obj,&$page){
		$this->js(true)->_load('xsocialApp-js1');
		
		$subpage = $_GET['subpage'];
		
		if(strpos($subpage,	'xsocial-') === 0){
			$this->api->template->appendHTML('js_include','<link type="text/css" href="epan-components/xsocialApp/templates/css/xsocial.css" rel="stylesheet" />'."\n");
		}
		// ONLY WORKS FOR PAGES CONTAINS "xsocial-" IN PAGE
		// DO NOT CHECK FOR xsocial-login PAGE
		$allowed_pages =array('xsocial-verifyaccount','xsocial-login','xsocial-logout');
		
		if(strpos($subpage,	'xsocial-') === 0 AND !in_array($subpage, $allowed_pages)){

			// IF session has logged_in_user value meanse user is there
			// load auth in api->xsocialauth and login this user
			if($login_email = $this->api->recall('logged_in_social_user',false)){
				$xsocial_auth =$this->add('BasicAuth',array('is_default_auth'=>false));
				$xsocial_auth->setModel('xsocialApp/AllVerifiedMembers','emailID','password');
				$this->api->xsocialauth = $xsocial_auth;
				$this->api->cu_id = $xsocial_auth->model['id'];
				$this->api->cu_name = $xsocial_auth->model['first_name'] . ' ' . $xsocial_auth->model['last_name'];
				$this->api->cu_emailid = $xsocial_auth->model['emailID'];
				
				// TODO :: Set cu_id = null when logout

				$xsocial_auth->login($login_email);
			}else{
				// User is not logged in .. redirect to login page
				$this->api->redirect($this->api->url(null,array('subpage'=>'xsocial-login')));
				return;
			}			
		}
	}

	function getDefaultParams($new_epan){}
}