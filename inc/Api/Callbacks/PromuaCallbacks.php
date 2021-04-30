<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class PromuaCallbacks extends BaseController
{

    public function optionGroup( $input )
    {
        return $input;
    }

    public function setSettingsSectionSubtitle()
    {
        echo '<p>Оберіть потрібні налаштування для створення xml-прайсу для сайту PromUA.</p>';
    }

    public function getShopName()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_promua_shop_name' ) );
        echo '<input type="text" class="regular-text" id="mrkv_uamrkpl_promua_shop_name"
        name="mrkv_uamrkpl_promua_shop_name" value="'. $value .'" placeholder="Введіть назву інтернет-магазину">';
    }

    public function getCompanyName()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_promua_company' ) );
        echo '<input type="text" class="regular-text" id="mrkv_uamrkpl_promua_company"
        name="mrkv_uamrkpl_promua_company" value="'. $value .'" placeholder="Введіть назву компанії">';
        echo '<div class="blank-block"></dive>';
    }

    public function getGlobalVendor()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_promua_main_maker' ) );
        echo '<input type="text" class="regular-text" id="mrkv_uamrkpl_promua_main_maker"
        name="mrkv_uamrkpl_promua_main_maker" value="'. $value .'" placeholder="Введіть назву виробника">';
        echo '<p class="mrkv-font-italic">Для монобрендових магазинів. Це значення автоматично присвоюється всім товарам у вигрузці.</p>';
    }

    public function setVendorNames()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_promua_brand_names' ) );
        echo '<select name="mrkv_uamrkpl_promua_brand_names" id="mrkv_uamrkpl_promua_brand_names">
                <option value="">Визначте як формуються бренди на вашому сайті</option>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="mercedes">Mercedes</option>
                <option value="audi">Audi</option>
            </select>';
    }

}
