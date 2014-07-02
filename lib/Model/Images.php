<?php

namespace xsocialApp;

class Model_Images extends \Model_Table{
	public $table="xsocialApp_images";
	function init(){
		parent::init();

		$this->hasOne('xsocialApp/MemberAll','member_id');
		$this->hasOne('xsocialApp/AddBlock','block_id');
		$this->add('filestore/Field_Image','img_id');
		$this->addField('link')->caption('Web Link');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}