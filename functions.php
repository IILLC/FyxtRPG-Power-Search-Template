<?php

//this is an example file of the code that holds the methods that are shared between the varous Fyxt RPG templates. 
//It also includes references to other php pages that help keep code organized. 
//This is only a partial example. Much of the core code has been removed. 

//external Fyxt RPG code
require_once( 'functions-ajax.php' ); //ajax functions
require_once( 'functions-page-controls.php' ); //page control functions
require_once( 'functions-lists.php' ); //all of the lists funcitons

//function to enqueue scripts and styles for fyxt
function fyxt_custom_scripts() {
	wp_enqueue_style( 'jquery-custom', get_stylesheet_directory_uri() . '/css/fyxt-theme-2/jquery-ui-1.10.4.custom.css' );
	wp_enqueue_style( 'jquery-custom', get_stylesheet_directory_uri() . '/css/fyxt-theme-2/jquery-ui-1.10.4.custom.min.css' );	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'tabs-script', get_stylesheet_directory_uri() . '/js/tabs.js', array( 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-tabs' ) );
}
add_action( 'wp_enqueue_scripts', 'fyxt_custom_scripts' );

//function to toggle a few things for the code going between local test server and remote live server.
function fyxt_hook_javascript() {
	
	$whitelist = array(
						'127.0.0.1',
						'::1'
						);
	
	if( !in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) ){
		// remote
		if ( is_page( 1772 ) || is_page( 3917 ) || is_page( 7112 ) ) {
			$output = '<script type="text/javascript" src="/wp-content/themes/ata-child-files/js/plupload.full.min.js" charset="UTF-8"></script>
				<script type="text/javascript" src="/wp-content/themes/ata-child-files/js/jquery.ui.plupload/jquery.ui.plupload.min.js" charset="UTF-8"></script>
				<link type="text/css" rel="stylesheet" href="/wp-content/themes/ata-child-files/js/jquery.ui.plupload/css/jquery.ui.plupload.css" media="screen" />
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">';
		}
	} else { 
  //local
		if ( is_page( 1772 ) || is_page( 3917 )|| is_page( 3932 ) ) {
			$output = '<script type="text/javascript" src="/fyxtrpg.com/wp-content/themes/ata-child-files/js/plupload.full.min.js" charset="UTF-8"></script>
				<script type="text/javascript" src="/fyxtrpg.com/wp-content/themes/ata-child-files/js/jquery.ui.plupload/jquery.ui.plupload.min.js" charset="UTF-8"></script>
				<link type="text/css" rel="stylesheet" href="/fyxtrpg.com/wp-content/themes/ata-child-files/js/jquery.ui.plupload/css/jquery.ui.plupload.css" media="screen" />
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">';
		}
	}
add_action('wp_head','fyxt_hook_javascript');

	echo $output;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////// BEGIN Character Build Stats and Character Card //////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//checks to see if character needs to be rebuilt or if it can be pulled from cache. 
function fyxt_isCharUpdateNeeded ( $charID, $fyxtAccountID ) {
	//checks to see if character needs to be built.
	if ( isset( $charID ) ) {
		$charLastUpdated = fyxt_lastCharUpdate ( $charID );
		$charCalcInfo = fyxt_characterBuiltInfo ( $charID );
	
	//if date is blank, later then card, or rebuild says 1
		if ( ( empty( $charCalcInfo->fyxt_cc_updated ) ) || ( $charLastUpdated > $charCalcInfo->fyxt_cc_updated ) || ( $charCalcInfo->fyxt_cc_rebuild_character == 1 ) ){
			//Builds character if needed ////////////////////////////////////////////////////////////////
			fyxt_updateCharacterCard ( $charID, $fyxtAccountID );
			$fireCharInfoFunction = 'Character has been rebuilt.';
			} else {
				$fireCharInfoFunction = 'Character does not need to be built. Grabbing card info.';
			}
	}
	$array = array(	
					"Character Last Updated" => $charLastUpdated,
					"Card Last Built" => $charCalcInfo->fyxt_cc_updated,
					"Force Rebuild" => $charCalcInfo->fyxt_cc_rebuild_character,
					"Build Message" => $fireCharInfoFunction
					);
	return $array;
}

////////////////////////Grab all of the Character's Calculated Data
function fyxt_getCharCardData ( $charID ) {
	if ( !empty( $charID ) ){
		global $wpdb;
		$charCardInfoSQL = "
		Select
		  fyxt_character_cards.*
		From
		  fyxt_character_cards
		Where
		  fyxt_character_cards.fyxt_cc_charID = $charID"; 
		$charCardInfoResults = $wpdb->get_row( $charCardInfoSQL );
		return $charCardInfoResults;
	} else {
		return;
	}
}

//................. Code Removed ............... //

?>
