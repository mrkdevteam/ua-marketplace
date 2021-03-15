<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class DashboardCallbacks extends BaseController
{

    public function adminDashboard()
    {
        return require_once( "$this->plugin_path/templates/dashboard.php" );
    }

    // public function adminRozetka()
    // {
    //     //return require_once( "$this->plugin_path/templates/rozetka.php" );
    // }

    public function checkboxActivation( $input )
    {
        $output = array();

        foreach ( $this->activations as $key => $value ) {
            $output[$key] = isset( $input[$key] ) ? true : false;
        }
        return $output;
    }

    public function marketplacesActivationSection()
    {
        echo 'Активуйте маркетплейси, з якими буде взаїмодіяти ваш інтернет-магазин.';
    }

    public function checkboxField( $args )
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option( $option_name );
        $checked = isset( $checkbox[$name] ) ? ( $checkbox[$name] ? true : false) : false;
        echo '<div class="' . $classes . '"><input class="mrkv_chk" type="checkbox" id="' . $name . '"
            name="' . $option_name . '[' . $name . ']" value="1" class="" ' .
            ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
    }

}
