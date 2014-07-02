<?php
namespace xsocialApp;
class Model_User extends Model_AllVerifiedMembers{
	function init(){
		parent::init();

		if(!@$this->api->xsocialauth){
			$this->api->js(true)->univ()->errorMessage('Not Logged In');
			return;
		}

		$this->addCondition('id',$this->api->xsocialauth->model->id);
		$this->tryLoadAny();
		if(!$this->loaded()){
			throw $this->exception('User cannot Be Loaded')
					->addMoreInfo('Id',$this->api->auth->model->id);
		}
	}
}