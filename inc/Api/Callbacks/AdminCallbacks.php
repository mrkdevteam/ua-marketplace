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

    // public function mrkvUAMarketplacesOptionGroup( $input )
    // {
    //     return $input;
    // }
    //
    // public function mrkvUAMarketplacesSectionDescr()
    // {
    //     echo 'Оберіть маркетплейси, з якими буде взаїмодіяти ваш інтернет-магазин.';
    // }

    public function mrkvUAMarketplacesField()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_rozetka_shop_name' ) );
        echo '<input tupe="text" class="regualr-text" name="mrkv_uamrkpl_rozetka_shop_name" value="'. $value .'" placeholder="Write smth...">';
    }

}
