<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;

class SupportController extends BaseController
{
    public $callbacks;

    public $subpages = array();

    public function register()
    {
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
                'page_title' => 'Підтримка',
                'menu_title' => 'Підтримка',
                'capability' => 'manage_options',
                'menu_slug' => 'mrkv_ua_marketplaces_support',
                'callback' => array( $this->callbacks, 'adminSupport' )
            )
        );
    }

}
