<?php
namespace xsocialApp;

class Model_Activity extends \Model_Table{
	public $table="xsocialApp_activities";
	function init(){
		parent::init();

		$this->hasOne('xsocialApp/MemberAll','from_member_id');
		$this->hasOne('xsocialApp/MemberAll','related_member_id');
		$this->hasOne('xsocialApp/Activity','related_activity_id');
		$this->hasOne('xsocialApp/Activity','original_activity_id');
		$this->addField('name')->caption('Activity');
		$this->add('filestore/Field_Image','img_id')->caption('Activity');
		$this->addField('activity_detail');
		$this->addField('video_url');

		$this->addField('visibility')->setValueList(array(100=>'Public',50=>'Friends',10=>'Private'))->defaultValue(100)->mandatory(true);
		$this->addField('activity_type')->enum(array('StatusUpadate','Comment','Like','Share','updateCoverPage','updateProfilePic','PostCardShared'));
		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('updated_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		
		$this->hasMany('xsocialApp/Activity','related_activity_id',null,'RelatedActivcities');
		$this->hasMany('xsocialApp/Activity','related_activity_id',null,'SharedActivcities');

		$this->addExpression('profile_pic')->set(function($m,$q){
			return $m->refSQL('from_member_id')->fieldQuery('profile_pic');
		});

		$member= $this->leftJoin('xsocialApp_members','from_member_id');
		$member->addField('member_gender','gender');

		$this->addExpression('like_status')->set(function($m,$q){
			return $m->api->db->dsql()->table('xsocialApp_activities', 'sss')
				->field($m->api->db->dsql()->expr('IF(id,"Y","N")'))
				->where('from_member_id',$m->api->xsocialauth->model->id)
				->where('activity_type','Like')
				->where('related_activity_id',$q->getField('id'));
		});

		$this->addExpression('is_by_friend')->set(function($a,$q){
			$user_id = $a->api->xsocialauth->model->id;
			$friends_table = $a->add('xsocialApp/Model_Friends',array('table_alias'=>'_df'));
			$friends_table->_dsql()->where(
					array(
							array('_df.request_from_id = '.$q->getField('from_member_id'). " and _df.request_to_id = $user_id and is_accepted=1"),
							array('_df.request_to_id = '.$q->getField('from_member_id'). " and _df.request_from_id = $user_id and is_accepted=1"),
						)
				);
			return $friends_table->count();

		});


		$this->addHook('beforeSave',$this);
		$this->addHook('afterLoad',$this);
		
	}

	function afterLoad(){
		// $this['name']= '<a href="#">sdfsdf</a>';
	}

	function beforeSave(){
		$this['updated_at']=date('Y-m-d H:i:s');
	}

	function is_liked(){
		if(!$this->loaded()) throw $this->exception('Cannot Find status on an unloaded activity');
		$liked_activity = $this->add('xsocialApp/Model_Activity');
		$liked_activity->addCondition('activity_type','Like');
		$liked_activity->addCondition('from_member_id',$this->api->xsocialauth->model->id);
		$liked_activity->addCondition('related_activity_id',$this->id);
		$liked_activity->tryLoadAny();

		if($liked_activity->loaded())
			return $liked_activity->id;
		else
			return false;
	}

	function like_it(){
		if(!$this->loaded()) throw $this->exception('Can\'t Like an unloaded activity');
		if($this->is_liked()) throw $this->exception('You have allready like this activity');
		$liked_activity = $this->add('xsocialApp/Model_Activity');
		$liked_activity['name']=$this->api->xsocialauth->model['name'] . " Liked '" . $this['name'] ."'";
		$liked_activity['activity_type']='Like';
		$liked_activity['from_member_id']=$this->api->xsocialauth->model->id;
		$liked_activity['related_activity_id']=$this->id;

		$liked_activity->save();

	}

	function unlike_it(){
		if(!$this->loaded()) throw $this->exception('Can\'t UnLike an unloaded activity');
		if(!($liked_acitivity_id = $this->is_liked())) throw $this->exception('You have allready unlike this activity');
		$unlike_activity= $this->add('xsocialApp/Model_Activity');
		$unlike_activity->load($liked_acitivity_id);
		$unlike_activity->delete();
	}

	function is_shared(){
		if(!$this->loaded()) throw $this->exception('Cannot Find status on an unloaded activity');
		$share_activity = $this->add('xsocialApp/Model_Activity');
		$share_activity->addCondition('activity_type','Share');
		$share_activity->addCondition('from_member_id',$this->api->cu_id);
		$share_activity->addCondition('related_activity_id',$this->id);
		$share_activity->tryLoadAny();

		if($share_activity->loaded())
			return $share_activity->id;
		else
			return false;

	}

	function share_it($visibility='Public',$activity_detail=null){
		if(!$this->loaded()) throw $this->exception('Can\'t Sahre an unloaded activity');

		// echo "<pre>";
		// echo print_r($other_fields);
		// echo "</pre>";
		$share_activity = $this->add('xsocialApp/Model_Activity');
		$share_activity['name']=$this->ref('from_member_id')->linkfyText('{{'.$this->api->cu_name.'/'.$this->api->cu_emailid.'}} Updated Status'). " Shared '" . $this['activity_detail'] ."'";
		$share_activity['activity_type']='Share';
		// $share_activity['activity_type']='PostCardShared';//for testing purpose
		$share_activity['from_member_id']=$this->api->cu_id;
		$share_activity['related_activity_id']=$this->id;
		$share_activity['visibility']=$visibility;
		$share_activity['img_id']=$this['img_id'];
		$share_activity['activity_detail']=$activity_detail;
		// throw new \Exception($this['img_id'], 1);
		

		$share_activity->save();
		// $this->api->exec_plugins('OnPostCardShared',array($share_activity['from_member_id']));


		return $share_activity;
	}


	function sharePostCard($visibility='Public',$activity_detail=null){
		if(!$this->loaded()) throw $this->exception('Can\'t Sahre an unloaded activity');

		// echo "<pre>";
		// echo print_r($other_fields);
		// echo "</pre>";

		//TODO CREATE POSTCARD 
		$share_activity = $this->add('xsocialApp/Model_Activity');
		$share_activity['name']=$this->ref('from_member_id')->linkfyText('{{'.$this->api->cu_name.'/'.$this->api->cu_emailid.'}} Updated Status'). " Shared '" . $this['activity_detail'] ."'";
		// $share_activity['activity_type']='Share';
		$share_activity['activity_type']='PostCardShared';//for testing purpose
		$share_activity['from_member_id']=$this->api->cu_id;
		$share_activity['related_activity_id']=$this->id;
		$share_activity['visibility']=$visibility;
		$share_activity['img_id']=$this['img_id'];
		$share_activity['activity_detail']=$activity_detail;
		// throw new \Exception($this['img_id'], 1);
		

		$share_activity->save();
		// $this->api->exec_plugins('OnPostCardShared',array($share_activity['from_member_id']));


		return $share_activity;
	}

	function commented($comment,$img_id=null){

		if(!$this->loaded()) throw $this->exception('Can\'t Sahre an unloaded activity');
		$comment_activity = $this->add('xsocialApp/Model_Activity');
		$comment_activity['name']=$this->api->cu_name . " Commented On '" . $this['name'] ."'";
		$comment_activity['activity_type']='Comment';
		$comment_activity['from_member_id']=$this->api->cu_id;
		$comment_activity['related_activity_id']=$this->id;
		$comment_activity['activity_detail']=$comment;
		$comment_activity['img_id']=$img_id;

		$comment_activity->save();	
	}

	function newPost($post_details){
		if($this->loaded()) throw $this->execption("New Post Can't be updated, It must be empty before save");
		$this['name']=$this->ref('from_member_id')->linkfyText('{{'.$this->api->cu_name.'/'.$this->api->cu_emailid.'}} Updated Status');
		
		// throw new \Exception($this['StatusUpadate'], 1);
		$this['from_member_id']=$this->api->cu_id;
		$this['activity_detail']=$post_details['activity_detail'];
		$this['img_id']=$post_details['img_id'];
		$this['video_url']=$post_details['video_url'];
		$this['visibility']=$post_details['visibility'];
		$this['activity_type']='StatusUpadate';
		$this->save();

		// throw new \Exception($this['img'], 1);
		

		return true;

	}

	function loadComments($for_activity_id){
		$this->addCondition('related_activity_id',$for_activity_id)
						->addCondition('activity_type','Comment')
						->setOrder('created_at');
	}

	function deleteActivity(){
		$this->delete();
		return true;
	}
	

}