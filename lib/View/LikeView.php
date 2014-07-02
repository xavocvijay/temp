<?php
namespace xsocialApp;

class View_LikeView extends \View{
	public $activity_id=null;
	public $current_like_status=null;
	function init() {
		parent::init();

		if ( $this->activity_id===null )
			throw $this->exception( 'activity_id is not defined' )->addMoreInfo( "In View", $this->owner );
		$this->setElement('a')->setAttr('href','#');

	}

	function recursiveRender() {


		if ( $_GET['like_unlike_activty_id'] == $this->activity_id ) {			
			$activity_to_perform_on = $this->add( 'xsocialApp/Model_Activity' );
			if ( $_GET['action']=='unlike' ) {
				$cu =$this->add( 'xsocialApp/Model_User' );		
				$existing_like = $this->add( 'xsocialApp/Model_Activity' );
				$existing_like->addCondition( 'from_member_id', $cu->id );
				$existing_like->addCondition( 'activity_type', 'Like' );
				$existing_like->addCondition( 'related_activity_id', $this->activity_id );
				$existing_like->tryLoadAny();
				$existing_like->delete();
				$this->current_like_status = 'N';
				 // Just unliked it so now caption should show like me again :)
				//TODO :: really delete or confirm before delete or Soft Delete
				// $this->set( 'Unlike' );

			}else {
				$cu =$this->add( 'xsocialApp/Model_User' );		
				$existing_like = $this->add( 'xsocialApp/Model_Activity' );
				$existing_like->addCondition( 'from_member_id', $cu->id );
				$existing_like->addCondition( 'activity_type', 'Like' );
				$existing_like->addCondition( 'related_activity_id', $this->activity_id );
				$existing_like->tryLoadAny();
				$existing_like->save();
				$this->current_like_status = 'Y';
				// $this->set( 'Unlike' ); // Just unliked it so now caption should show like me again :)
				// $this->set( 'Like' );
			}
		}

		if ( $this->current_like_status == 'Y' ) {
			// I AM ALREADY LIKED.. PERFORM UNLIKE HERE
			$this->set( 'Unlike' ); // Just unliked it so now caption should show like me again :)
			$this->js( 'click' )->reload( array( 'like_unlike_activty_id'=>$this->activity_id, 'action'=>'unlike' ) );
		}else {
			// I AM Still not liked perform like here
			$this->set( 'Like' ); // Just liked it so now when reloading caption should show unlike me :(
			$this->js( 'click' )->reload( array( 'like_unlike_activty_id'=>$this->activity_id, 'action'=>'like' ) );
		}
		parent::recursiveRender();
	}
}
