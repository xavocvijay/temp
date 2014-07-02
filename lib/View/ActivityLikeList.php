<?php
namespace xsocialApp;

class View_ActivityLikeList extends \View{
	public $activity_id=null;
	function init(){
		parent::init();

		if($this->activity_id===null)
			throw $this->exception('activity_id is not defined')->addMoreInfo("In View", $this->owner);
		$this->set('Activity Like List');

	}
}