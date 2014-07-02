<?php

namespace xsocialApp;

class View_Server_AddBlock extends \View{

	function init(){
		parent::init();

		$add_blocks = $this->add('xsocialApp/Model_AddBlock');

		$i=0;
		foreach ($add_blocks as $junk) {
			$images = $this->add('xsocialApp/Model_BlockImages');
			$images->addCondition('block_id',$add_blocks->id);
			$lister = $this->add('xsocialApp/View_Lister_AddBlock');
			$lister->setModel($images);
			$lister->addClass('.carousel-'.$i);
			$this->js(true)->univ()->myCarousel('.carousel-'.$i,$add_blocks['add_rotate_timing']*1000);
			$i++;
		}
	}
	
}	