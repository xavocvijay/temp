<?php

namespace xsocialApp;

class View_Server_About extends \View{

	function init(){
		parent::init();

		$this->addClass('about');
		$this->js('reload')->reload();
                                                 
	}
	
	function recursiveRender(){

		$profile_of = $_GET['profile_of'];
		if(!$profile_of) $profile_of = $this->api->cu_id;

		$member= $this->add('xsocialApp/Model_AllVerifiedMembers');
		$member->tryLoad($profile_of);
		if(!$member->loaded()){
			$profile_of = $this->api->cu_id;
			$member->load($profile_of);
		}

		// $this->add('xsocialApp/View_ProfilePic',array('member_id'=>$member->id));
		// $this->add('View')->set($member['name']);

		$this->add('H3')->set('About');
		$this->addClass('accordian');
		$profile_categories =array();
		$category_fields =array();
		// CHECK IF ALL PROFILE FIELDS ARE AVAILABLE FOR THIS USER
		foreach ($pf=$this->add('xsocialApp/Model_ProfileFields')->setOrder('category') as $junk) {
			$pfv=$this->add('xsocialApp/Model_ProfileFieldValues');
			$pfv->addCondition('member_id',$member->id);
			$pfv->addCondition('profile_field_id',$pf->id);
			$pfv->tryLoadAny();
			if(!$pfv->loaded()){
				$pfv->save();
			}

			if(!in_array($pf['category'], $profile_categories)){
				$profile_categories[] = $pf['category'];
				$v=$this->add('View');
				$v->add('View')->setHTML("<b>".$pf['category']."</b>");
				$category_fields[$pf['category']]=$v;
			}
			$category_fields[$pf['category']]->add('View')->set($pf['name']. ' :: ' . $pfv['value']);

			$pfv->destroy();
		}

		if($member->is_current_user()){
			// throw new \Exception("Error Processing Request", 1);
			
			$btn = $this->add('Button')->set('Edit');
			$btn->js('click')->univ()->frameURL("Edit Profile",$this->api->url('xsocialApp_page_activitypages_about',array('member_id'=>$this->api->cu_id)));
		}

		// $grid=$this->add('Grid')->setModel($member->ref('xsocialApp/ProfileFieldValues'));		
		parent::recursiveRender();

	}
}
