<?php
define( "DEFAULT_CONFIG",		"direct" );
define( "NETWORK_CONFIG_PAIRS", 	array(
					"Buffalo-A-472E"  => "Home Wi-Fi",
					"Buffalo-G-472E"  => "Home Wi-Fi",
					"HG8045-0184-a"   => "Home Wi-Fi",
					"HG8045-0184-bg"  => "Home Wi-Fi",
					"SWAN"            => "SWAN",
					"SWing"           => "Swing",
					"spd-ui-050"      => "spd-ui-50",
					"Yasu's iPhone X" => "direct",
					) );

$previous_network = '';
while( 1 ){
	$current_network = _get_current_network();
	if( $current_network != $previous_network ){
		$config_name = _get_config_name( $current_network );
		$command = sprintf( 'scselect "%s"', $config_name );
		`$command`;
		$notification = sprintf( "osascript -e 'display notification with title \"Switched to: %s\"'", $config_name );
		//`$notification`;
		$previous_network = $current_network;
	}else if( _get_config_name( $current_network ) == 'Home Wi-Fi' ){
		$output = `ping -c 1 192.168.0.200`;
		if( strpos( $output, 'icmp_seq' ) == false ){
			$command = sprintf( 'scselect "%s"', 'direct' );
			`$command`;
		}
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
	if( array_key_exists( $_network, NETWORK_CONFIG_PAIRS ) ){
		$config = NETWORK_CONFIG_PAIRS[ $_network ];
	}else{
		$config = DEFAULT_CONFIG;
	}
	return $config;
}
?>
