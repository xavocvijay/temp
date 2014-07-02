<?php

namespace xsocialApp;

class Plugins_cropImagePlugin extends \componentBase\Plugin{
	public $namespace = 'xsocialApp';

	function init(){
		parent::init();	
		$this->addHook('website-page-loaded',array($this,'CropImage'));
	}

	function CropImage(){
		if($_GET['crop_image']){
				
			$targ_w = $_GET['w'];
			$targ_h = $_GET['h'];

			$jpeg_quality = 90;

			$old_dir = getcwd();
			chdir('..');
			$src = getcwd() . $this->api->xsocialauth->model['timeline_pic'];
			chdir($old_dir);

			$img_r = imagecreatefromjpeg($src);
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
			
			imagecopyresampled($dst_r,$img_r,0,0,$_GET['x'],$_GET['y'],
			    $targ_w,$targ_h,$_GET['w'],$_GET['h']);

			header('Content-type: image/jpeg');
			imagejpeg($dst_r, $src, $jpeg_quality);
			
			// $this->js()->reload()->execute();				
			$this->js(true)->_selector('.profilecover')->trigger('reload')->execute();
		}
	}
	
	function getDefaultParams($new_epan){}
}