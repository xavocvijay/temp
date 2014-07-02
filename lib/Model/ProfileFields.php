<?php

namespace xsocialApp;

class Model_ProfileFields extends \Model_Table{
	var $table='xsocialApp_profile_fields';
	function init(){
		parent::init();

		$this->addField('category');
		$this->addField('name');
		$this->addField('type')->enum(array('DatePicker','line','text','DropDown'));
		$this->hasMany('xsocialApp/ProfileFieldValues','profile_field_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function getValueForCurrentUser($member_id){
		$pfv=$this->add('xsocialApp/Model_ProfileFieldValues');
		$pfv->addCondition('member_id',$member_id);
		$pfv->addCondition('profile_field_id',$this->id);
		$pfv->tryLoadAny();
		return $pfv['value'];
	}

	function setValueForCurrentUser($member_id,$value){
		$pfv=$this->add('xsocialApp/Model_ProfileFieldValues');
		$pfv->addCondition('member_id',$member_id);
		$pfv->addCondition('profile_field_id',$this->id);
		$pfv->tryLoadAny();
		$pfv['value'] = $value;
		$pfv->save();
	}
}