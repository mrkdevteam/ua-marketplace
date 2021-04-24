<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

class BaseController
{
    public $plugin_path;

    public $plugin_url;

	public $plugin;

    public $plugin_name;

    public $plugin_ver;

    public $activations = array();

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/mrkv-ua-marketplaces.php';
        $this->plugin_name = get_file_data( $this->plugin_path . '/mrkv-ua-marketplaces.php', array( 'name'=>'Plugin Name' ) );
        $this->plugin_ver = get_file_data( $this->plugin_path . '/mrkv-ua-marketplaces.php', array( 'ver'=>'Version' ) );

        $this->activations = array(
            'mrkvuamp_rozetka_activation'   => 'Rozetka',
            'mrkvuamp_promua_activation'    => 'PromUA' // for free version use only Rozetka
        );
	}

    public function activated( string $key )
    {
        $option = get_option('mrkv_ua_marketplaces');

        return isset( $option[$key] ) ? $option[$key] : false;
    }
}
