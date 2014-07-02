<?php
namespace xsocialApp;

class View_CommentView extends \View{
	public $activity_id;
	public $comment_array=array();
	function init() {
		parent::init();

		// echo "<pre>";
		// echo print_r($this->comment_array);
		// echo "</pre>";

		if ( $this->activity_id===null )
			throw $this->exception( 'activity_id is not defined' )->addMoreInfo( "In View", $this->owner );
		
		$this->addClass('comment-'.$this->activity_id);
		

	}

	

	function recursiveRender() {
		$this->template->trySet('from_member',$this->comment_array['from_member']);
		$this->template->trySet('activity_detail',$this->comment_array['activity_detail']);

		$this->template->trySet('img',$this->comment_array['img']);
		$this->template->set('activity_id',$this->comment_array['id']);
		$this->template->set('like_status',($this->comment_array['like_status']=='Y'?'UnLike':'Like'));
		
		$this->add('xsocialApp/View_ProfilePic',array('profile_pic_url'=>$this->comment_array['profile_pic']),'profile_pic');
		// $this->add('xsocialApp/View_LikeView',array('activity_id'=>$this->activity_id,'current_like_status'=>$this->comment_array['like_status']),'like_view_spot');
		if($this->comment_array['from_member_id']==$this->api->xsocialauth->model->id){
			// throw new \Exception("Error Processing Request", 1);
			
			$this->add('xsocialApp/View_Activity_EditActivity',array('activity_id'=>$this->comment_array['id']),'edit');
			$this->add('xsocialApp/View_Activity_DeleteActivity',array('activity_id'=>$this->comment_array['id']),'delete');
			
		}else{
			$this->template->tryDel('edit_delete_block');
		}
		
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
		return array('view/xsocial-comment');
	}

}
