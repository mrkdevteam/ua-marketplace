<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\RozetkaCallbacks;

class Rozetka extends BaseController
{
	public $settings;

	public $callbacks;

	public $callbacks_rozetka;

	public $pages = array();

	public $subpages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->callbacks_rozetka = new RozetkaCallbacks();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addSubPages( $this->subpages )->register();
	}

	public function setSettings()
	{

			$args = array(
				array(
					'option_group'	=> 'mrkv_ua_rozetka_option_group',
					'option_name'	=> 'mrkv_uamrkpl_rozetka_shop_name', // Назва магазину
					'callback'		=> array( $this->callbacks_rozetka, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_rozetka_option_group',
					'option_name'	=> 'mrkv_uamrkpl_rozetka_company', // Назва компанії
					'callback'		=> array( $this->callbacks_rozetka, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_rozetka_option_group',
					'option_name'	=> 'mrkv_uamrkpl_rozetka_xml_tags_lang', // Мова xml-тегів прайсу
					'callback'		=> array( $this->callbacks_rozetka, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_rozetka_option_group',
					'option_name'	=> 'mrkv_uamrkpl_rozetka_global_vendor', // Глобальний виробник
					'callback'		=> array( $this->callbacks_rozetka, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_rozetka_option_group',
					'option_name'	=> 'mrkv_uamrkpl_rozetka_custom_vendor', // Бренди
					'callback'		=> array( $this->callbacks_rozetka, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_rozetka_option_group',
					'option_name'	=> 'mrkv_uamrkpl_rozetka_vendor_by_attributes', // Атрибути в якості брендів
					'callback'		=> array( $this->callbacks_rozetka, 'optionGroup' )
				),
				array(
					'option_group'	=> 'mrkv_ua_rozetka_option_group',
					'option_name'	=> 'mrkv_uamrkpl_rozetka_vendor_all_possibilities', // Метадані в якості брендів
					'callback'		=> array( $this->callbacks_rozetka, 'optionGroup' )
				)
			);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id'		=> 'mrkvuamp_rozetka_section',
				'title'		=> __( 'Rozetka Загальні Налаштування', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'setSettingsSectionSubtitle' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			// Store ('Магазин')
			array(
				'id' => 'mrkv_uamrkpl_rozetka_store',
				'title' => '<h3 style="margin:0;font-weight:500;font-style:italic;">Магазин</h3>',
				'callback' => function () {},
				'page' => 'mrkv_ua_marketplaces_rozetka',
				'section' => 'mrkvuamp_rozetka_section',
			),
			array(
				'id'		=> 'mrkv_uamrkpl_rozetka_shop_name',
				'title'		=> __( 'Назва магазину', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'getShopName' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka',
				'section'	=> 'mrkvuamp_rozetka_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_rozetka_shop_name',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_rozetka_company',
				'title'		=> __( 'Назва компанії', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'getCompanyName' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka',
				'section'	=> 'mrkvuamp_rozetka_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_rozetka_company',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_rozetka_xml_tags_lang',
				'title'		=> __( 'Мова xml-тегів прайсу <span style="font-weight:400;font-style:italic;">(deprecated)</span>', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'setRozetkaXmlTagsLang' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka',
				'section'	=> 'mrkvuamp_rozetka_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_rozetka_xml_tags_lang',
					'class'		=> 'mrkv_uamrkpl_rozetka_xml_tags_lang_class',
				)
			),
			// Product ('Товар')
			array(
				'id' => 'mrkv_uamrkpl_rozetka_product',
				'title' => '<h3 style="margin:0;font-weight:500;font-style:italic;">Товар</h3>',
				'callback' => function () {},
				'page' => 'mrkv_ua_marketplaces_rozetka',
				'section' => 'mrkvuamp_rozetka_section',
			),
			array(
				'id'		=> 'mrkv_uamrkpl_rozetka_global_vendor',
				'title'		=> __( 'Глобальний виробник', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'getGlobalVendor' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka',
				'section'	=> 'mrkvuamp_rozetka_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_rozetka_global_vendor',
					'class'		=> 'mrkv_uamrkpl_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_rozetka_set_vendor_names',
				'title'		=> __( 'Бренди', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'setVendorNames' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka',
				'section'	=> 'mrkvuamp_rozetka_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_rozetka_set_vendor_names',
					'class'		=> 'mrkv_uamrkpl_rozetka_set_vendor_names_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_rozetka_vendor_by_attributes',
				'title'		=> __( 'Атрибути в якості брендів', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'setVendorByAttributes' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka',
				'section'	=> 'mrkvuamp_rozetka_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_rozetka_vendor_by_attributes',
					'class'		=> 'mrkv_uamrkpl_rozetka_vendor_by_attributes_class',
				)
			),
			array(
				'id'		=> 'mrkv_uamrkpl_rozetka_vendor_all_possibilities',
				'title'		=> __( 'Метадані в якості брендів', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_rozetka, 'setVendorAllPossibilities' ),
				'page'		=> 'mrkv_ua_marketplaces_rozetka',
				'section'	=> 'mrkvuamp_rozetka_section',
				'args'		=> array(
					'label_for' => 'mrkv_uamrkpl_rozetka_vendor_all_possibilities',
					'class'		=> 'mrkv_uamrkpl_rozetka_vendor_all_possibilities_class',
				)
			)
		);

		$this->settings->setFields( $args );
	}

}
