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

    public $slug_activations = array();

    public $plugin_uploads_dir;

    public $plugin_uploads_rozetka_xmlname;
    public $plugin_uploads_promua_xmlname;

    public $plugin_uploads_dir_path;
    public $plugin_uploads_dir_url;

    public $marketplaces;

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/morkvawrs-plugin.php';
        $this->plugin_name = get_file_data( $this->plugin_path . '/morkvawrs-plugin.php', array( 'name'=>'Plugin Name' ) );
        $this->plugin_ver = get_file_data( $this->plugin_path . '/morkvawrs-plugin.php', array( 'ver'=>'Version' ) );
	$this->marketplaces = array ( 'Rozetka', 'PromUA' );

        // What marketplace is activated by user?
        $marketplace_activated = array();
        $marketplace_activated = ( null !== get_option( 'mrkv_ua_marketplaces' ) )
            ? get_option( 'mrkv_ua_marketplaces' ) : array();
        $rozetka_activated = ! empty( $marketplace_activated['mrkvuamp_rozetka_activation'] ) ? 'Rozetka' : '';
        $promua_activated = ! empty( $marketplace_activated['mrkvuamp_promua_activation'] ) ? 'PromUA' : '';

        $this->activations = array(
            'mrkvuamp_rozetka_activation'   => $rozetka_activated,
            'mrkvuamp_promua_activation'    => $promua_activated
        );

        foreach ( $this->activations as $key => $value ) {
            $this->slug_activations[$key] = \strtolower( $value );
        }

        $this->plugin_uploads_dir = '/' . $this->setPluginUploadsDir() . '/';

        $this->plugin_uploads_rozetka_xmlname = $this->setRozetkaXMLName();
        $this->plugin_uploads_promua_xmlname = $this->setPromuaXMLName();
        
        $this->plugin_uploads_dir_path = $this->create_uploads_dir( $this->plugin_uploads_dir );
        $this->plugin_uploads_dir_url = $this->get_uploads_url( $this->plugin_uploads_dir );
	}

    public function setRozetkaXMLName()
    {
        if ( ! empty( get_option( 'mrkv_uamrkpl_rozetka_xml_filename' ) ) ) {
            return get_option( 'mrkv_uamrkpl_rozetka_xml_filename' ) . '.xml';
        }
        return 'mrkvuamprozetka.xml';
    }

    public function setPromuaXMLName()
    {
        if ( ! empty( get_option( 'mrkv_uamrkpl_promua_xml_filename' ) ) ) {
            return get_option( 'mrkv_uamrkpl_promua_xml_filename' ) . '.xml';
        }
        return 'mrkvuamppromua.xml';
    }

    public function setPluginUploadsDir()
    {
        if ( ! empty( get_option( 'mrkv_uamrkpl_rozetka_xmlfile_dir' ) ) ) {
            return get_option( 'mrkv_uamrkpl_rozetka_xmlfile_dir' );
        }
        if ( empty( get_option( 'mrkv_uamrkpl_rozetka_xmlfile_dir' ) ) ) {
            return 'uamrktpls';
        }
        return 'uamrktpls';
    }

    public function create_uploads_dir($plugin_uploads_dir)
    {
        $upload_dir = wp_upload_dir();
        $uploads_uamrktpls_dir = $upload_dir['basedir'] . $plugin_uploads_dir;
        if( ! file_exists( $uploads_uamrktpls_dir ) ) wp_mkdir_p( $uploads_uamrktpls_dir );
        return $uploads_uamrktpls_dir;
    }

    public function get_uploads_url($plugin_uploads_dir)
    {
        $upload_dir = wp_get_upload_dir();
        $uploads_uamrktpls_url = $upload_dir['baseurl'] . $plugin_uploads_dir;
        return $uploads_uamrktpls_url;
    }

    public function activated( string $key )
    {
        $option = get_option('mrkv_ua_marketplaces');

        return isset( $option[$key] ) ? $option[$key] : false;
    }

}
