<?php


namespace xsocialApp;

class Model_ProfileFieldValues extends \Model_Table {
	var $table ='xsocialApp_profile_field_values';

	function init(){
		parent::init();
		$this->hasOne('xsocialApp/MemberAll','member_id');
		$this->hasOne('xsocialApp/ProfileFields','profile_field_id');
		$this->addField('value');
		$this->addField('visibility')->enum(array('Public','Private','Friends'))->defaultValue('Public')->mandatory(true);;

		$this->add('dynamic_model/Controller_AutoCreator');
	}

}
