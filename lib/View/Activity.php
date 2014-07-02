<?php

namespace xsocialApp;

class View_Activity extends \View{
	public $activity_id;
	public $activity_array=array();

	


	function recursiveRender(){

		// echo "<pre>";
		// print_r($this->activity_array);
		// echo "</pre>";

		
		$this->template->set('activity_detail',$this->activity_array['activity_detail']);
		$this->template->set('activity_id',$this->activity_array['id']);
		$this->template->trySet('is_by_friend',$this->activity_array['is_by_friend']);
		$this->template->set('like_status',($this->activity_array['like_status']=='Y'?'UnLike':'Like'));
		
		$this->template->set('img',$this->activity_array['img']);
		$this->template->setHTML('activity',$this->activity_array['name']);
		$this->template->setHTML('video_url',$this->activity_array['video_url']);

		if($this->activity_array['from_member_id']==$this->api->xsocialauth->model->id){
			// throw new \Exception("Error Processing Request", 1);
			
			$this->add('xsocialApp/View_Activity_EditActivity',array('activity_id'=>$this->activity_array['id'],'activity_view'=>$this->name),'edit');

		}else{
			$this->template->tryDel('edit_delete_block');
		}


		$this->add('xsocialApp/View_ProfilePic',array('profile_pic_url'=>$this->activity_array['profile_pic'],'member_gender'=>$this->activity_array['member_gender']),'profile_pic');
		// $this->add('xsocialApp/View_LikeView',array('activity_id'=>$this->activity_id,'current_like_status'=>$this->activity_array['like_status']),'like_button');
		// $this->add('xsocialApp/View_ShareView',array('activity_id'=>$this->activity_id),'share_button');

		$this->add('xsocialApp/View_ActivityLikeList',array('activity_id'=>$this->activity_id),'activity_like_list');
		$this->add('xsocialApp/View_ActivityShareList',array('activity_id'=>$this->activity_id),'activity_share_list');
		
		$this->add('xsocialApp/View_ActivityComments',array('activity_id'=>$this->activity_id,'activity_array'=>$this->activity_array),'comments_block');

		$comments_activity=$this->add('xsocialApp/Model_Activity');
		$comments_activity->addCondition('related_activity_id',$this->activity_id);
		$comments_activity->addCondition('activity_type','Comment');
		
		parent::recursiveRender();
		return $this;
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
		return array('view/xsocial-activity');
	}
}