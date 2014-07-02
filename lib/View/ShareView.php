<?php
namespace xsocialApp;

class View_ShareView extends \View{
	public $activity_id=null;
	function init(){
		parent::init();

		if($this->activity_id===null)
			throw $this->exception('activity_id is not defined')->addMoreInfo("In View", $this->owner);
		$this->set('share');
		$this->setElement('a')->setAttr('href','#');

   		$this->js('click')->univ()->frameURL('MyPopup',$this->api->url('xsocialApp_page_activitypages_share',array('activity_id'=>$this->activity_id)));
		
	}
} 