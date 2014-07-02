<?php
namespace xsocialApp;

class View_Activity_EditActivity extends \View{
	public $activity_id=null;
	public $activity_view=null;
	function init(){
		parent::init();
		$this->set('edit');
		if($this->activity_id)
			$this->js('click')->univ()->frameURL($this->api->url('xsocialApp_page_activitypages_editactivity',array('edit_activity_id'=>$this->activity_id,'activity_view'=>$this->activity_view)));
	}

	
	// function setModel($model){

	// 	$this->js('click')->univ()->frameURL($this->api->url('xsocialApp_page_activitypages_editactivity',array('edit_activity_id'=>$model->id)));
		
	// 	parent::setModel($model);

	// }

}