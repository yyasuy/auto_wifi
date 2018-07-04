<?php
$network_config_pairs = array(
	"Buffalo-A-472E"  => "Home Wi-Fi",
	"Buffalo-G-472E"  => "Home Wi-Fi",
	"HG8045-0184-a"   => "Home Wi-Fi",
	"HG8045-0184-bg"  => "Home Wi-Fi",
	"Yasu's iPhone X" => "iPhone",
	"SWAN"            => "SWAN",
	"SWing"           => "Swing",
	"spd-ui-050"      => "spd-ui-50",
);

$previous_network = '';
while( 1 ){
	$current_network = _get_current_network();
	if( $current_network != $previous_network ){
		$config_name = _get_config_name( $current_network );
		$command = sprintf( 'scselect "%s"', $config_name );
		`$command`;
		$notification = sprintf( "osascript -e 'display notification with title \"Switched to: %s\"'", $config_name );
		`$notification`;
		$previous_network = $current_network;
	}
	sleep( 5 );
}

function _get_current_network(){
	$output = `networksetup -getairportnetwork en0`;
	$pattern = '|(.+?)\s(.+?)\s(.+?)\s(.+)|';
	if( preg_match( $pattern, $output, $matches ) ){
		$current_network = trim( $matches[ 4 ] );
		return $current_network;
	}
	return '';
}

function _get_config_name( $_network ){
	global $network_config_pairs;
	if( array_key_exists( $_network, $network_config_pairs ) ){
		$config = $network_config_pairs[ $_network ];
	}else{
		$config = "iPhone";
	}
	return $config;
}
?>
