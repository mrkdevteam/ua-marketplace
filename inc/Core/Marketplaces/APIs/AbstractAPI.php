<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\Marketplaces\APIs;

abstract class AbstractAPI {

        /**
         * Base URL for the WordPress site that the client is connecting to.
         *
         * @var string
         */
        private $base_url;

        /**
         * WordPress HTTP transport used for communication.
         *
         * @var WP_Http
         */
        private $http;

        /**
         * The authorization token used by the client.
         *
         * @var string
         */
        private $token;

}
