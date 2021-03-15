<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;

class PromuaSettingsController extends BaseController
{
    public $callbacks;

    public $subpages = array();

    public function register()
    {

        if ( ! $this->activated( 'mrkvuamp_promua_activation' ) ) return;

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
                'page_title' => 'PromUA',
                'menu_title' => 'PromUA',
                'capability' => 'manage_options',
                'menu_slug' => 'mrkv_ua_marketplaces_promua',
                'callback' => array( $this->callbacks, 'adminPromua' )
            )
        );
    }

}
