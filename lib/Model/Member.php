<?php

namespace xsocialApp ;
class Model_Member extends Model_AllVerifiedMembers{
	function init(){
		parent::init();

		$this->hasMany('xsocialApp/MemberInfo','Members_id');

	}

}