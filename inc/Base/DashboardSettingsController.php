<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;

class DashboardSettingsController extends BaseController
{
    public $callbacks;

    public $subpages = array();

    public function register()
    {

        if ( ! $this->activated( 'mrkvuamp_rozetka_activation' ) ) return;

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->setSubPages();

        $this->settings->addSubPages( $this->subpages )->register();

    }

    public function setSubPages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => 'mrkv_ua_marketplaces',
                'page_title' => 'Rozetka',
                'menu_title' => 'Rozetka',
                'capability' => 'manage_options',
                'menu_slug' => 'mrkv_ua_marketplaces_rozetka',
                'callback' => array( $this->callbacks, 'adminRozetka' )
            ),
        );
    }

}
