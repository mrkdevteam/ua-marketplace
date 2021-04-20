<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class RozetkaCallbacks extends BaseController
{

    public function optionGroup( $input )
    {
        return $input;
    }

    public function settingsSection()
    {
        echo '<p>Оберіть потрібні налаштування для створення xml-прайсу для сайту Rozetka.</p>';
    }

    public function shopName()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_rozetka_shop_name' ) );
        echo '<input type="text" class="regular-text" id="mrkv_uamrkpl_rozetka_shop_name"
        name="mrkv_uamrkpl_rozetka_shop_name" value="'. $value .'" placeholder="Введіть назву інтернет-магазину">';
    }

    public function companyName()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_rozetka_company' ) );
        echo '<input type="text" class="regular-text" id="mrkv_uamrkpl_rozetka_company"
        name="mrkv_uamrkpl_rozetka_company" value="'. $value .'" placeholder="Введіть назву компанії">';
        echo '<div class="blank-block"></dive>';
    }

    public function mainMaker()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_rozetka_main_maker' ) );
        echo '<input type="text" class="regular-text" id="mrkv_uamrkpl_rozetka_main_maker"
        name="mrkv_uamrkpl_rozetka_main_maker" value="'. $value .'" placeholder="Введіть назву виробника">';
        echo '<p class="mrkv-font-italic">Для монобрендових магазинів. Це значення автоматично присвоюється всім товарам у вигрузці.</p>';
    }

    public function brendNames()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_rozetka_brend_names' ) );
        echo '<select name="mrkv_uamrkpl_rozetka_brend_names" id="mrkv_uamrkpl_rozetka_brend_names">
                <option value="">Визначте як формуються бренди на вашому сайті</option>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="mercedes">Mercedes</option>
                <option value="audi">Audi</option>
            </select>';
    }

}
