<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc;

final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{
		return array (
			Base\Enqueue::class,
			Base\SettingsLinks::class,

			Pages\Dashboard::class,
			Pages\Rozetka::class,
			Pages\Promua::class,

			Base\DashboardSettingsController::class,
			Base\RozetkaSettingsController::class,
			Base\PromuaSettingsController::class, // for free version not use PromUA
			Base\SupportController::class,

            // Addons
			Base\AjaxHandler::class,
			Base\WPCRONHandler::class,
			
			Core\WCShop\EditProduct\ExtraProductSettings::class,
			Core\WCShop\EditProduct\ExtraVariationSettings::class,

			Core\WCShopController::class,
			Core\WCShop\WCShopCollation::class,
			Core\WCShop\WCShopOffer::class,
			Core\WCShop\WCShopOfferSimple::class,
			Core\WCShop\WCShopOfferVariable::class,

			// Core\XMLController::class, // Прибрав, тому що є параметр у класа

			Core\Marketplaces\FactoryAPI::class,
			Core\Marketplaces\APIs\RozetkaAPI::class
		);
	}

	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services()
	{
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class 	From services array
	 * @return class instance  	New instance of the class
	 */
	private static function instantiate( $class )
	{
		$service = new $class();

		return $service;
	}
}
