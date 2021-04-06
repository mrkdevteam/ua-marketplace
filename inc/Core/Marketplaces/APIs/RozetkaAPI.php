<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\Marketplaces\APIs;

use \Inc\Core\Marketplaces\APIs;

class RozetkaAPI extends AbstractAPI {

    private $base_url;

    private $http;

    private $token;

    /**
     * Base path for all API user resources.
     *
     * @var string
     */
    const ENDPOINT_ROZETKA = 'https://api-seller.rozetka.com.ua/';
    // const ENDPOINT_ROZETKA = 'https://api-seller.rozetka.com.ua/market-categories/category-options?category_id=380125';
    // const ENDPOINT_ROZETKA = 'https://api-seller.rozetka.com.ua/market-categories/search?category_id=4630027';

    /**
     * Constructor.
     *
     * @param WP_Http $http
     * @param string  $base_url
     * @param string  $username
     * @param string  $password
     */
    public function init(\WP_Http $http, $base_url, $username, $password)
    {
        $this->http = $http;
        $this->base_url = $base_url;
        $this->token = base64_encode($username . ':' . $password);
    }

    /**
     * Creates an API client from WordPress global objects.
     *
     * @param string $base_url
     * @param string $token
     *
     * @return WP_API_Client
     */
    public static function create($base_url, $token)
    {
        return new self(_wp_http_get_object(), $base_url, $token);
    }

    public function get_users(array $filters = array(), $context = 'view', $page = 1)
    {
        return $this->get($this->build_url(self::ENDPOINT_ROZETKA, array('filter' => $filters, 'context' => $context, 'page' => $page)));
    }

    public function get_category_info_by_id( $category_id, $token = 'L1GQBkMN-Dy17QRUgdWhWcSOWYXIBAMK' )
    {
        $url = 'https://api-seller.rozetka.com.ua/market-categories/search?category_id=' . $category_id;
        $result = wp_remote_get(
                $url,
                array(
                    'timeout'     => 5,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'user-agent'  => 'WordPress/5.3; ',
                    'headers'     => array('Content-Type'=> 'application/json', 'Authorization'=> 'Bearer ' . $token ),

                    'body'        => '',
                    'compress'    => false,
                    'decompress'  => true,
                    'sslverify'   => false,
                    'method'      => 'GET'
            )
        );
        $data = json_decode($result['body']);
        if ( $data->success ) {
            return $data->content->marketCategorys[0]->name;
        }
        return 'Category not found';
    }

    public function get_category_name_by_id( $collation_category_id )
    {
        $morkva_api_rozetka_cats_json = wp_remote_get( 'http://api.morkva.co.ua/morkvafiles/woo-rozetka/categories.json' );
        $morkva_api_rozetka_cats = json_decode( $morkva_api_rozetka_cats_json['body'], true);
        for ( $i=0; $i < \sizeof($morkva_api_rozetka_cats) ; $i++ ) {
            if ( \in_array( $collation_category_id, $morkva_api_rozetka_cats[$i] ) ) {
                return $morkva_api_rozetka_cats[$i]['name'];
            }
        }
        return 'Category not found';
    }

    /**
     * Builds a full API request URL from the given endpoint URL and query string arguments.
     *
     * @param string $endpoint
     * @param array  $query
     *
     * @return string
     */
    private function build_url($endpoint, array $query = array())
    {
        $url = $this->base_url.$endpoint;

        if (!empty($query)) {
            $url .= '?'.http_build_query($query);
        }

        return $url;
    }

    /**
     * Performs a GET request using the WordPress HTTP transport. Returns a WP_Error
     * on error.
     *
     * @param string $url
     * @param array  $args
     *
     * @return array|WP_Error
     */
    private function get($url, array $args = array())
    {
        $response = $this->http->get($url, $this->build_args($args));

        if (is_array($response) && $this->is_successful($response)) {
            $response = $this->decode_response($response);
        } elseif (is_array($response) && !$this->is_successful($response)) {
            $response = $this->convert_response_to_error($response);
        }

        return $response;
    }

    /**
    * Checks if the given response is considered successful as per the HTTP specification.
    * This means that the response has a 2xx status code.
    *
    * @param array $response
    *
    * @return bool
    */
   private function is_successful(array $response)
   {
       $status_code = $this->get_response_status_code($response);

       if (null === $status_code) {
           return false;
       }

       return $status_code >= 200 && $status_code < 300;
   }

   /**
     * Decodes the JSON object returned in given response. Returns a WP_Error on error.
     *
     * @param array $response
     *
     * @return array|WP_Error
     */
    private function decode_response(array $response)
    {
        $decoded = array();
        $headers = $this->get_response_headers($response);

        if (!isset($headers['content-type']) || false === stripos($headers['content-type'], 'application/json')) {
            return new WP_Error('invalid_response', 'The content-type of the response needs to be "application/json".');
        }

        if (isset($response['body'])) {
            $decoded = json_decode($response['body'], true);
        }

        if (null === $decoded) {
            return new WP_Error('invalid_json', 'The JSON response couldn\'t be decoded.');
        }

        return $decoded;
    }

    /**
     * Builds the WordPress HTTP transport arguments.
     *
     * @param array
     *
     * @return array
     */
    private function build_args(array $args = array())
    {
        return array_merge_recursive($args,
            array(
            'headers' => array(
                'Authorization' => 'Bearer '.$this->token,
            ),
        ));
    }

    /**
     * Extracts the response headers from the given response.
     *
     * @param array
     *
     * @return array
     */
    private function get_response_headers(array $response)
    {
        if (!isset($response['headers']) || !is_array($response['headers'])) {
            return array();
        }

        return $response['headers'];
    }

    /**
     * Extracts the status code from the given response.
     *
     * @param array $response
     *
     * @return int|null
     */
    private function get_response_status_code(array $response)
    {
        if (!isset($response['response']) || !isset($response['response']['code'])) {
            return null;
        }

        return $response['response']['code'];
    }

    /**
    * Converts the given response to a WP_Error object.
    *
    * @param array $response
    *
    * @return WP_Error
    */
   private function convert_response_to_error(array $response)
   {
       $response = $this->decode_response($response);
       $error = new WP_Error();

       if ($response instanceof WP_Error) {
           $error = $response;
       } elseif (is_array($response)) {
           array_walk($response, array($this, 'add_response_error'), $error);
       }

       return $error;
   }

   /**
     * Adds the response error to the given WP_Error instance.
     *
     * @param mixed    $response
     * @param mixed    $key
     * @param WP_Error $error
     */
    private function add_response_error($response, $key, WP_Error $error)
    {
        if (!is_array($response)) {
            return;
        }

        $error->add(
            isset($response['code']) ? $response['code'] : '',
            isset($response['message']) ? $response['message'] : ''
        );
    }


}
