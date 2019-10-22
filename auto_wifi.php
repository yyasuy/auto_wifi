<?php
define( "HOME_NETWORK",			array(
					"Buffalo-A-472E",
					"Buffalo-G-472E",
					"HG8045-0184-a",
					"HG8045-0184-bg",
					"Extender-A-6D6A",
					"Extender-G-6D6A",
					) );
define( "DEFAULT_CONFIG",		"direct" );
define( "NETWORK_CONFIG_PAIRS", 	array(
					"SWAN"            => "SWAN",
					"SWing"           => "Swing",
					"spd-ui-050"      => "spd-ui-50",
					) );

$previous_network = '';
while( 1 ){
	$current_network = _get_current_network();
	if( $current_network != $previous_network ){
		// Swithcing the network configuration
		$config_name = _get_config_name( $current_network );
		$command = sprintf( 'scselect "%s"', $config_name );
		`$command`;
		printf( "Switched to %s\n", $config_name );

		// Edit the /etc/hosts file
		if( in_array( $current_network, HOME_NETWORK ) ){
			$command = "sudo sed -i -e 's/^#192.168.0.200/192.168.0.200/' /etc/hosts";
		}else{
			$command = "sudo sed -i -e 's/^192.168.0.200/#192.168.0.200/' /etc/hosts";
		}
		`$command`;
		printf( "$command\n" );

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
	if( array_key_exists( $_network, NETWORK_CONFIG_PAIRS ) ){
		$config = NETWORK_CONFIG_PAIRS[ $_network ];
	}else{
		$config = DEFAULT_CONFIG;
	}
	return $config;
}
?>
