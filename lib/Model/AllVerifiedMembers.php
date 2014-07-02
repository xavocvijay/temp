<?php
namespace xsocialApp;
class Model_AllVerifiedMembers extends Model_AllActiveMembers{
	function init(){
		parent::init();

		$this->addCondition('is_verify',true);
	}
}