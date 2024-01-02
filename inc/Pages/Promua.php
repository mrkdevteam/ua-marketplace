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
					'option_name'	=> 'mrkv_uamrkpl_promua_shop_name', // Назва магазину
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_company', // Назва компанії
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_global_vendor', // Глобальний виробник
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_custom_vendor', // Бренди
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_vendor_by_attributes', // Атрибути в якості брендів
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_vendor_all_possibilities', // Метадані в якості брендів
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_background_proc_xml_chk', // Фоновий режим xml
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_promua_option_group',
					'option_name'	=> 'mrkv_uamrkpl_promua_background_proc_xml_step', // Кількість товарів за прохід
					'callback'		=> array( $this->callbacks_promua, 'optionGroup' )
				),
			);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id'		=> 'mrkvuamp_promua_section',
				'title'		=> __( 'PromUA Загальні Налаштування', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'setSettingsSectionSubtitle' ),
				'page'		=> 'mrkv_ua_marketplaces_promua'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			// Store ('Магазин')
			array(
				'id' => 'mrkv_uamrkpl_promua_store',
				'title' => '<h3 style="margin:0;font-weight:500;font-style:italic;">Магазин</h3>',
				'callback' => function () {},
				'page' => 'mrkv_ua_marketplaces_promua',
				'section' => 'mrkvuamp_promua_section',
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_shop_name',
				'title'		=> __( 'Назва магазину', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'getShopName' ),
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
				'callback'	=> array( $this->callbacks_promua, 'getCompanyName' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_company',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			// Product ('Товар')
			array(
				'id' => 'mrkv_uamrkpl_promua_product',
				'title' => '<h3 style="margin:0;font-weight:500;font-style:italic;">Товар</h3>',
				'callback' => function () {},
				'page' => 'mrkv_ua_marketplaces_promua',
				'section' => 'mrkvuamp_promua_section',
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_global_vendor',
				'title'		=> __( 'Глобальний виробник', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'getGlobalVendor' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_global_vendor',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_set_vendor_names',
				'title'		=> __( 'Бренди', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'setVendorNames' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_set_vendor_names',
					'class'		=> 'mrkv_uamrkpl_promua_set_vendor_names_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_vendor_by_attributes',
				'title'		=> __( 'Атрибути в якості брендів', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'setVendorByAttributes' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_vendor_by_attributes',
					'class'		=> 'mrkv_uamrkpl_promua_vendor_by_attributes_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_vendor_all_possibilities',
				'title'		=> __( 'Метадані в якості брендів', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'setVendorAllPossibilities' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_vendor_all_possibilities',
					'class'		=> 'mrkv_uamrkpl_promua_vendor_all_possibilities_class',
				)
			),
			// Other ('Інше')
			array(
				'id' => 'mrkv_uamrkpl_promua_other',
				'title' => '<h3 style="margin:0;font-weight:500;font-style:italic;">Інше</h3>',
				'callback' => function () {},
				'page' => 'mrkv_ua_marketplaces_promua',
				'section' => 'mrkvuamp_promua_section',
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_background_proc_xml_chk',
				'title'		=> __( 'Фоновий режим xml', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_promua, 'getCheckboxBackgroundProcessXml' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_background_proc_xml_chk',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_promua_background_proc_xml_step', // Кількість товарів за прохід
				'title'		=> '',
				'callback'	=> array( $this->callbacks_promua, 'getBackgroundProductStepQuantity' ),
				'page'		=> 'mrkv_ua_marketplaces_promua',
				'section'	=> 'mrkvuamp_promua_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_promua_background_proc_xml_step',
					'class'		=> 'mrkv_uamrkpl_promua_background_proc_xml_step_class hidden',
				)
			),
		);

		$this->settings->setFields( $args );
	}

}
