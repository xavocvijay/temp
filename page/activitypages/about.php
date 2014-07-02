<?php

class page_xsocialApp_page_activitypages_about extends Page{
	function init() {
		parent::init();
		$this->api->stickyGET( 'member_id' );
		$form = $this->add( 'Form' );


		// $form->setModel($this->add('xsocialApp/Model_MemberAll')->load($_GET['member_id']),array('profile_pic_id','first_name','last_name'));

		foreach ( $pf=$this->add( 'xsocialApp/Model_ProfileFields' ) as $junk ) {

			$field = $form->addField( $pf['type'], strtolower( str_replace( " ", "_", $pf['name'] ) ) );
			$field->set( $pf->getValueForCurrentUser( $_GET['member_id'] ) );

		}
		$form->addSubmit( 'Update Profile' );

		if ( $form->isSubmitted() ) {
			// $form->update();


			foreach ( $pf=$this->add( 'xsocialApp/Model_ProfileFields' ) as $junk ) {
				$value = $form->get( strtolower( str_replace( " ", "_", $pf['name'] ) ) );
				$pf->setValueForCurrentUser( $_GET['member_id'], $value );
			}

			// if($this->api->xsocialauth->model->updateProfilePic($form->getAllFields()))
			$form->js( null, array(
					$this->js()->_selector( '.about' )->trigger( 'reload' )

				) )->univ()->closeDialog()->execute();
		}




	}
}
