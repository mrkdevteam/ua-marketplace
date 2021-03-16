<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
    public function adminDashboard()
    {
        return require_once( "$this->plugin_path/templates/dashboard.php" );
    }

    public function adminRozetka()
    {
        return require_once( "$this->plugin_path/templates/rozetka.php" );
    }

    public function adminPromua()
    {
        return require_once( "$this->plugin_path/templates/promua.php" );
    }

    public function adminSupport()
    {
        return require_once( "$this->plugin_path/templates/support.php" );
    }

}
