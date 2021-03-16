<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\PromuaCallbacks;

class Promua extends BaseController
{
	public $settings;

	public $callbacks;

	public $callbacks_promua;

	public $pages = array();

	public $subpages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->callbacks_promua = new PromuaCallbacks();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addSubPages( $this->subpages )->register();
	}

	public function setSettings()
	{

			$args = array(
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_shop_name',
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_company',
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_main_maker',
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_brends',
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				)
			);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id'		=> 'mrkvuamp_promua_section',
				'title'		=> __( 'PromUA Загальні Налаштування', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'settingsSection' ),
				'page'		=> 'mrkv_ua_marketplaces_promua'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			array(
				'id'		=> 'mrkv_uamrkpl_promua_shop_name',
				'title'		=> __( 'Назва магазину', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'shopName' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_shop_name',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_company',
				'title'		=> __( 'Назва компанії', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'companyName' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_company',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_main_maker',
				'title'		=> __( 'Глобальний виробник', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'mainMaker' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_main_maker',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_brendNames',
				'title'		=> __( 'Бренди', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'brendNames' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_brend_names',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			)
		);

		$this->settings->setFields( $args );
	}

}
