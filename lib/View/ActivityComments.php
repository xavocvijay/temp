<?php

namespace xsocialApp;

class View_ActivityComments extends \View{
	public $activity_id;

	function init(){
		parent::init();
		$this->set('comment');
		$this->addClass('comments-block-'.$this->activity_id);
		$this->js('reload')->reload();
	}

	function recursiveRender(){
		$this_activity_comments = $this->add('xsocialApp/Model_Activity');
		$this_activity_comments->loadComments($this->activity_id);
		foreach ($this_activity_comments as $comment) {
			$this->add('xsocialApp/View_CommentView',array('activity_id'=>$this_activity_comments->id,'comment_array'=>$comment));
		}

		$this->add('xsocialApp/View_NewCommentForm',array('activity_id'=>$this->activity_id));

		parent::recursiveRender();
	}

	// function defaultTemplate(){
	// 	$l=$this->api->locate('addons',__NAMESPACE__, 'location');
	// 	$this->api->pathfinder->addLocation(
	// 		$this->api->locate('addons',__NAMESPACE__),
	// 		array(
	// 	  		'template'=>'templates',
	// 	  		'css'=>'templates/css'
	// 			)
	// 		)->setParent($l);
	// 	return array('view/xsocial-comments-list');
	// }
	

}	