<?php

namespace xsocialApp;

class Model_PointTransaction extends \Model_Table{
	public $table="xsocialApp_points_transaction";

	function init(){
		parent::init();

		$this->hasOne('xsocialApp/Point','point_id');
		$this->hasOne('xsocialApp/MemberAll','member_id');
		$this->addField('points');
		$this->addField('remark');
		$this->addField('on_date')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->add('dynamic_model/Controller_AutoCreator');

	}
}