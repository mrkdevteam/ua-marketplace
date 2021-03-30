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
			Base\PromuaSettingsController::class,
			Base\SupportController::class,
			Base\WCShopController::class,
			Base\XMLController::class,

			ExternalApi\WoocommerceApi::class,
			WCShop\WCShop::class
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
