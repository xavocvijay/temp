<?php

namespace xsocialApp;

class View_Server_Profile extends \View{
	public $member_id=null;
	public $profile_pic_url=false;
	public $member=null;
	public $member_gender=null;
	function init(){
		parent::init();		
		
		$this->add('Button')->set('Edit');
				
	}

	function recursiveRender(){
		// $this->template->trySet('member_name',$this->api->xsocialauth->model['first_name']);
		$profile_of= $_GET['profile_of'];
		if(!$profile_of) $profile_of=$this->api->xsocialauth->model->id;
		$member=$this->add('xsocialApp/Model_AllVerifiedMembers');
		$member->tryLoad($profile_of);
		if(!$member->loaded()){
			$profile_of = $this->api->cu_id;
			$member->load($profile_of);
		}

		// throw new \Exception($member['profile_pic']);
		if($member['profile_pic']){
				$src=$member['profile_pic'];
				$this->template->set('member_pic',$member['profile_pic']);
			}
			elseif($this->member['gender']=='Female'){
					$src='female.png';
					$this->template->set('member_pic',$src);
				}
			else{
				$src="male.png";			
				$this->template->set('member_pic',$src);
			}		
		
		$this->template->set('member_name',$member['name']);
		parent::recursiveRender();
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
		return array('view/xsocial-profile');
	}

}