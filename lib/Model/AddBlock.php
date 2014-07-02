<?php

namespace xsocialApp;

class Model_AddBlock extends \Model_Table{
		var $table="xsocial_addBlock";
		function init(){
			parent::init();

			$this->addField('name')->caption('Block Name');
			// $this->addField('add_rotate_timing')->caption('Timing In Seconds');
			$this->hasMany('xsocialApp/BlockImages','block_id');

			$this->add('dynamic_model/Controller_AutoCreator');

		}
}