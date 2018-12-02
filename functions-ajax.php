<?php 

// This is the ajax file that is included in the main functions file. This file holds all of the AJAX code for FyxtRPG.com
//vvvvvvvvvvvvvvvvvvvvvvvvvvvvv AJAX SECTION vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
// This is just a sample of a few things in this file. 

//  POWER SEARCH ADD AJAX 
add_action('wp_ajax_addPowerAJAX', 'addPowerAJAX_callback');
function addPowerAJAX_callback() {
	global $wpdb;
	$charID = $_POST['charID'];
	$powerID = $_POST['powerID'];
	$powerDisc = $_POST['pDisc'];
	
	//get power details
	$getPowerDetailsSQL = "
	Select
	  fyxt_powers.name,
	  fyxt_powers.power_description
	From
	  fyxt_powers
	Where
	  fyxt_powers.idpowers = $powerID";
	$getPowerDetailsResults = $wpdb->get_row( $getPowerDetailsSQL );
	
	$pName = $getPowerDetailsResults->name;
	$pDesc = $getPowerDetailsResults->power_description;
		
	$charPowerTable = fyxt_char_powers;
	$wpdb->insert(
				  $charPowerTable,
				  array (
						  idfyxt_character => $charID,
						  power_name => $pName,
						  power_desc => $pDesc,
						  power_id => $powerID,
						  disc_id => $powerDisc
						)
				  );
	$result = $wpdb->print_error(); 				  								

	fyxt_charUpdated( $charID ); //adds timestamp to db for when character was updated.

	echo $result;
}

//new ajax to clear all Powers
add_action('wp_ajax_clearAllPowersAJAX', 'clearAllPowersAJAX_callback');
function clearAllPowersAJAX_callback() {
	global $wpdb;
	$charID = $_POST['charID'];
	$wpdb->delete(
					$charPowerTable,
					array (	
						  idfyxt_character => $charID
						  )
					);
}

//echo Power DIV output on screen
add_action('wp_ajax_echoPowerAJAX', 'echoPowerAJAX_callback');
function echoPowerAJAX_callback() {
	global $wpdb;
	$powerID = $_POST['powerID'];
	$pDetail = $_POST['pDetail'];
	
	$pListing = fyxt_printPower( $powerID, 3 ); //gets power data array
	$pHTML = $pListing['htmlOutput']; //outputs preformatted div with power data

	echo json_encode($pHTML);
	die();
}

//......code removed ............//

?>
