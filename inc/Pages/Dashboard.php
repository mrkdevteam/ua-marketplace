<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\DashboardCallbacks;

class Dashboard extends BaseController
{
	public $settings;

	public $callbacks;

	public $callbacks_activation;

	public $pages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->callbacks_activation = new DashboardCallbacks();

		$this->setPages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();

		add_action( 'admin_notices', array( $this, 'pluginCacheClamesNotice' ) ); 	// Set notices
	}

	public function setPages()
	{
		$this->pages = array(
			array (
				'page_title'	=> 'UA Marketplaces Plugin',
				'menu_title'	=> 'UA Marketplaces',
				'capability'	=> 'manage_options',
				'menu_slug'		=> 'mrkv_ua_marketplaces',
				'callback'		=> array( $this->callbacks, 'adminDashboard' ),
				'icon_url'		=> 'dashicons-store',
				'position'		=> 65
			)
		);
	}

	public function setSettings()
	{

			$args = array(
				array(
					'option_group'	=> 'mrkv_ua_marketplaces_option_group',
					'option_name'	=> 'mrkv_ua_marketplaces',
					'callback'		=> array( $this->callbacks_activation, 'checkboxActivation' )
				)
			);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id'		=> 'mrkvuamp_activation_section',
				'title'		=> __( 'Маркетплейси', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_activation, 'marketplacesActivationSection' ),
				'page'		=> 'mrkv_ua_marketplaces'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array();

		foreach ( $this->marketplaces as $key => $value ) {
			$args[] = array(
				'id'		=> 'mrkvuamp_' . strtolower( $value ) . '_activation',
				'title'		=> $value,
				'callback'	=> array( $this->callbacks_activation, 'checkboxField' ),
				'page'		=> 'mrkv_ua_marketplaces',
				'section'	=> 'mrkvuamp_activation_section',
				'args'		=> array(
					'option_name'	=> 'mrkv_ua_marketplaces',
					'label_for' 	=> 'mrkvuamp_' . strtolower( $value ) . '_activation',
					'class'			=> strtolower( $value ) . '_activation_class'
				)
			);
		}

		$this->settings->setFields( $args );
	}

	public function pluginCacheClamesNotice()
	{
		global $pagenow;
		if ( ( $pagenow == 'admin.php' ) && ( 'mrkv_ua_marketplaces' === $_GET['page'] ) &&
			( ! isset( $_COOKIE['mrkvuamp_dashboard_notice'] ) ) ) {
			echo '<br><div id="mrkvuamp_dashboard_notice" class="notice notice-warning mrkvuamp_dashboard_notice" style="display:inline-block">
					<div style="display:flex">
						<div>
							<p>' . __( 'Якщо на вашому сайті працює плагін кешування, налаштуйте виключення для xml файлів.', 'mrkv-ua-marketplaces' ) .
					   		'</p>
							<p>' . __( 'Якщо додаєте у прайс більше 200 товарів, збільшіть php execution time до максимально можливого (наприклад, 3600).', 'mrkv-ua-marketplaces' ) .
					   		'</p>
						</div>
				   		<a id="mrkvuamp_dashboard_dismiss" type="button" class="notice-dismiss" href="?page=mrkv_ua_marketplaces&mrkvuamp_dismissed"
							style="position:relative;display:flex;text-decoration:none;">Dismiss</a>
					</div>
			   </div>';
		}
	}

}
