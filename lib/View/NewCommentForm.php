<?php
namespace xsocialApp;

class View_NewCommentForm extends \View{
	public $activity_id;
	function init() {
		parent::init();

		if ( $this->activity_id===null )
			throw $this->exception( 'activity_id is not defined' )->addMoreInfo( "In View", $this->owner );
		
	}

	function recursiveRender() {

		$comment=$this->add('xsocialApp/Model_Activity');
		$comment->load($this->activity_id);

		$new_comment = $this->add('xsocialApp/Model_Activity');
		$new_comment->addCondition('related_activity_id',$comment->id);

		$form=$this->add('Form');
		$form->setModel($new_comment,array('activity_detail','img_id'));
		// $form->addField('line','comment');

		if($form->isSubmitted()){
			$comment->commented($form['activity_detail'],$form['img_id']);
			// $form->js(null,$form->js()->_selector('.comments-block-'.$this->owner->activity_id)->trigger('reload'))->reload()->execute();
			$form->js(null,$form->js()->_selector('.activity')->trigger('reload'))->reload()->execute();
			
		}
		
		
		parent::recursiveRender();
	}

}
