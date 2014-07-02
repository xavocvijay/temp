<?php

namespace xsocialApp;

class Model_Point extends \Model_Table{
	public $table="xsocialApp_points";

	function init(){
		parent::init();

		$this->addField('name')->enum(array('OnVerification','OnVerificationRefeCheck','OnContinueLogin','OnBirthday'));
		$this->addField('points');

		$this->hasMany('xsocialApp/PointTransaction','point_id');

		$this->add('dynamic_model/Controller_AutoCreator');

	}
}