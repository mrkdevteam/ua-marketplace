<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
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

        $this->callbacks_rozetka = new RozetkaCallbacks();

        $this->settings->addSubPages( $this->subpages )->register();
    }

}
