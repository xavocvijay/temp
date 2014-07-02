<?php

namespace xsocialApp;

class View_Server_ProfileCover extends \View{
	function init(){ 
		parent::init();

		$this->addClass('profilecover');
		$this->js('reload')->reload();

		$this->api->stickyGET('profile_of');
		$profile_id = $_GET['profile_of'];
		if(!$profile_id) $profile_id = $this->api->xsocialauth->model->id;

		$this->addClass('profilecover');
		$this->js('reload')->_selector('[component_type="ProfileCover"]')->reload();
		$member=$this->add('xsocialApp/Model_Member');
		$member->tryLoad($profile_id);
		if(!$member->loaded()){
			$profile_id = $this->api->xsocialauth->model->id;
			$member->load($profile_id);
		}

		$profilepic=$this->add('xsocialApp/View_ProfilePic',array('member_id'=>$member->id),'profile_pic');
		$profilepic->addClass('profilepic');
		$profilepic->js('reload')->reload();
		if(!$member->is_current_user()){
			$this->add('xsocialApp/View_FriendRequestBtn',array('related_with'=>$member),'friend_request_btn');
			
		}else{
			$edit_btn=$this->add('Button',null,'edit_btn')->set('Edit Cover');
			$profile_btn=$this->add('Button',null,'profile_edit')->set('Edit Profile Pic');
			$crop_cover_btn=$this->add('Button',null,'crop_btn')->set('Crop Cover');
			// $crop_pic_btn=$this->add('Button',null,'crop_pic_btn')->set('Crop PicCover');
			$edit_btn->js('click')->univ()->frameURL('Edit Cover',$this->api->url('xsocialApp_page_activitypages_profilecover',array('member_id'=>$this->api->xsocialauth->model->id)));
			$profile_btn->js('click')->univ()->frameURL('Edit Profile',$this->api->url('xsocialApp_page_activitypages_editprofile',array('member_id'=>$this->api->xsocialauth->model->id)));
		}

		$this->template->set('timeline_pic',$member['timeline_pic']); 		
		$this->template->set('member_name',$member['name']); 		
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
		return array('view/xsocial-profilecover');
	}	
}