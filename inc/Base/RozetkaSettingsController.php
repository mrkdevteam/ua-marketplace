<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
// use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\RozetkaCallbacks;

class RozetkaSettingsController extends BaseController
{
    public $settings;

    public $callbacks;

    public $callbacks_rozetka;

    public $pages = array();

    public $subpages = array();

    public function register()
    {

        if ( ! $this->activated( 'mrkvuamp_rozetka_activation' ) ) return;

        $this->settings = new SettingsApi();

        // $this->callbacks = new AdminCallbacks();

        $this->callbacks_rozetka = new RozetkaCallbacks();

        // $this->setSubPages();

        $this->settings->addSubPages( $this->subpages )->register();

        // add_action( 'init', array( $this, 'rozetkaSettings' ) );
    }

    public function setSubPages()
    {
        // $this->subpages = array(
        //     array(
        //         'parent_slug' => 'mrkv_ua_marketplaces',
        //         'page_title' => 'Rozetka',
        //         'menu_title' => 'Rozetka',
        //         'capability' => 'manage_options',
        //         'menu_slug' => 'mrkv_ua_marketplaces_rozetka',
        //         'callback' => array( $this->callbacks_rozetka, 'adminRozetka' )
        //     ),
            // array(
            //     'parent_slug' => 'mrkv_ua_marketplaces',
            //     'page_title' => 'PromUA',
            //     'menu_title' => 'PromUA',
            //     'capability' => 'manage_options',
            //     'menu_slug' => 'mrkv_ua_marketplaces_promua',
            //     'callback' => array( $this->callbacks, 'adminPromua' )
            // ),
            // array(
            //     'parent_slug' => 'mrkv_ua_marketplaces',
            //     'page_title' => 'Підтримка',
            //     'menu_title' => 'Підтримка',
            //     'capability' => 'manage_options',
            //     'menu_slug' => 'mrkv_ua_marketplaces_support',
            //     'callback' => array( $this->callbacks, 'adminSupport' )
            // )
        // );
    }

    // public function rozetkaSettings()
    // {
    //     // echo 'Rozetka Налаштування';
    // }
}
