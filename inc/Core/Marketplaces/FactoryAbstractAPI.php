<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\Marketplaces;

use \Inc\Core\Marketplaces\APIs\RozetkaAPI;
use \Inc\Core\Marketplaces\APIs\PromuaAPI;

abstract class FactoryAbstractAPI {

    public function create($marketplace_api)
    {
         switch ($marketplace_api) {
            case'rozetka':
                return new RozetkaAPI();
            case'promua':
            default:
                return new PromuaAPI();
        }
    }
}
