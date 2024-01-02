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
        $value = esc_attr( get_option( 'mrkv_uamrkpl_promua_global_vendor' ) );
        echo '<input type="text" class="regular-text" id="mrkv_uamrkpl_promua_global_vendor"
        name="mrkv_uamrkpl_promua_global_vendor" value="'. $value .'" placeholder="Введіть назву виробника">';
        echo '<p class="mrkv-font-italic">Для монобрендових магазинів. Це значення автоматично присвоюється всім товарам у вигрузці.</p>';
    }

    public function setVendorNames()
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_promua_custom_vendor' ) );
        $vendor_values = array( 'vendor_pwb_brand' , 'vendor_by_attributes', 'vendor_all_possibilities' );
        $vendorTypeChoice = array(
            'Плагін "Perfect Brands for WooCommerce"',
            'Використовувати атрибути як бренди',
            'Метадані в якості брендів'
        );
        $addSelected = array( ' ', ' ', ' ' );
        for ( $i = 0; $i < sizeof( $vendor_values ); $i++ ){
            if ( $vendor_values[$i] == $value ){
              $addSelected[$i] = 'selected';
            }
        }
        echo '<select ' . $value . ' id="mrkv_uamrkpl_promua_custom_vendor" name="mrkv_uamrkpl_promua_custom_vendor">';
        echo '<option value="your_vendor_choice">' . __( 'Ваш вибір...', 'mrkv-ua-marketplaces') . '</option>';
        for( $i = 0; $i < sizeof( $vendor_values ); $i++) {
            echo '<option '. $addSelected[$i] . ' value="' . $vendor_values[$i] . '">' . $vendorTypeChoice[$i] . '</option>';
        }
        echo '</select>';
        echo '<p class="mrkv-font-italic">' . __(' Оберіть варіант, що формує бренди товарів на вашому сайті.', 'mrkv-ua-marketplaces' ).'</p>';
    }

    public function setVendorByAttributes()
    {
        $value = get_option( 'mrkv_uamrkpl_promua_vendor_by_attributes' );
        $array_attributes = array();
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if ( $attribute_taxonomies ){
            foreach ( $attribute_taxonomies as $tax ){
                if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ){
                    $array_attributes[ $tax->attribute_name ] = $tax->attribute_label;
                }
            }
        }

        $vendor_keys = array();
        $vendor_values = array();
        $selected_vendors = array();
        $selected_vendors = array_pad( $selected_vendors, sizeof( $array_attributes ), '' );

        $k = 0;
        foreach ($array_attributes as $key => $val) {
            $vendor_keys[$k] = $key;
            $vendor_values[$k] = $val;
            $k++;
        }

        for ( $i = 0; $i < sizeof( $vendor_values ); $i++ ) {
            if ( $value == $vendor_keys[$i] ) {
                $selected_vendors[$i] = 'selected';
            }
        }
        echo '<form><select '. $value . ' id="mrkv_uamrkpl_promua_vendor_by_attributes" name="mrkv_uamrkpl_promua_vendor_by_attributes">';
        echo '<option value="">' . __( 'Виберіть атрибут, що задає бренди на вашому сайті', 'mrkv-ua-marketplaces' ) . '</option>';

        for( $j = 0; $j < sizeof( $vendor_values ); $j++) {
            echo '<option ' . $selected_vendors[$j] . ' value="' . $vendor_keys[$j] . '">' . $vendor_values[$j] . '</option>';
        }
    }

    public function setVendorAllPossibilities()
    {
        $value = get_option( 'mrkv_uamrkpl_promua_vendor_all_possibilities' );
        $postmetas = array();
        $posts = get_posts( array(
            'numberposts' => -1,
            'post_type'         => 'product',
            'suppress_filters'  => false,
        ) );
        if ( isset( $posts ) ) {
            foreach( $posts as $post ){
                $postmetas = get_post_meta($post->ID);
            }
        }
        $all_possible_vendor_keys = array();
        $all_possible_vendor_values = array();
        $add_selected_html_attrs = array();
        $k = 0;
        if ( is_array( $postmetas ) ) {
            foreach( $postmetas as $meta_key => $meta_value ) {
                $all_possible_vendor_keys[$k] = $meta_key;
                $all_possible_vendor_values[$k] = isset( $meta_value[0] ) ? $meta_key : '';
                $k++;
            }
        }
        for ( $i = 0; $i < sizeof( $all_possible_vendor_values ); $i++ ) {
            if ( $value == $all_possible_vendor_keys[$i] ){
                $add_selected_html_attrs[$i] = 'selected';
            } else {
                $add_selected_html_attrs[$i] = '';
            }
        }
        echo '<form><select '. $value . ' id="mrkv_uamrkpl_promua_vendor_all_possibilities" name="mrkv_uamrkpl_promua_vendor_all_possibilities">';
        echo '<option value="">' . __( 'Виберіть метадані, що задають бренди на вашому сайті', 'mrkv-ua-marketplaces' ) . '</option>';
        for( $j = 0; $j < sizeof( $all_possible_vendor_keys ); $j++) {
            $all_possible_vendor_values[$j] = isset( $all_possible_vendor_values[$j] ) ? $all_possible_vendor_values[$j] : '';
            echo '<option ' . $add_selected_html_attrs[$j] . ' value="' . $all_possible_vendor_keys[$j] . '">' . $all_possible_vendor_keys[$j] . '</option>';
            $index = $j;
        }
        echo '</form>';
    }

    public function getCheckboxBackgroundProcessXml() // Фоновий режим xml - activation checkbox
    {
        $checked = esc_attr( get_option( 'mrkv_uamrkpl_promua_background_proc_xml_chk' ) );
        echo '<input type="checkbox" class="regular-text" id="mrkv_uamrkpl_promua_background_proc_xml_chk"
            name="mrkv_uamrkpl_promua_background_proc_xml_chk" value="1" ' . checked( $checked, true, false ) . ' >';
        echo '<p class="mrkv-font-italic">' . __( 'Корисно для сайтів з великою кількістю товарів. Автооновлення xml щогодини не доступне.', 'mrkv-ua-marketplaces' );
        echo '</p>';
    }

    public function getBackgroundProductStepQuantity() // Кількість товарів за прохід - getting from input text field
    {
        $value = esc_attr( get_option( 'mrkv_uamrkpl_promua_background_proc_xml_step', 200 ) );
        echo '<input style="max-width:100px;" type="text" class="regular-text" id="mrkv_uamrkpl_promua_background_proc_xml_step"
        name="mrkv_uamrkpl_promua_background_proc_xml_step" value="'. $value .'" placeholder="">';
        echo '<p class="mrkv-font-italic">Кількість товарів за один прохід в фоновому режимі створення xml.</p>';
    }

}
