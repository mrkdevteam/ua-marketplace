<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\ExternalApi;

use \Inc\Base\BaseController;

class WoocommerceApi extends BaseController {

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

    /**
     * Base path for all API user resources.
     *
     * @var string
     */
    const ENDPOINT_USERS = '/wp-json/wp/v2/users';

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

    /**
     * Retrieve a subset of the site's users.
     *
     * @param  array   $filters
     * @param  string  $context
     * @param  integer $page
     *
     * @return array|WP_Error
     */
    public function get_users(array $filters = array(), $context = 'view', $page = 1)
    {
        return $this->get($this->build_url(self::ENDPOINT_USERS, array('filter' => $filters, 'context' => $context, 'page' => $page)));
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
                'Authorization' => 'Basic '.$this->token,
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


    // public function init() {
    //     // return register( 'http', $this->http, $this->$base_url, 'dev3localadmin', 'MEUuTNVoPa^nm9YcUo' );
    //     return register( $this->http, $this->$base_url, 'dev3localadmin', 'MEUuTNVoPa^nm9YcUo' );
    // }

    public function products_list() {
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            // $this->http = new \Wp_Http;
            // $this->init( $this->http, 'http://localhost:3000/', 'dev3localadmin', 'MEUuTNVoPa^nm9YcUo' );
            // WP_Query example
            // $query = new \WP_Query(array(
            //     'fields' => 'ids',
            //     // 'fields' => array('ids', 'post_title', 'post_content'),
            //     'post_type' => 'product',
            //     'taxonomy' => 'product_cat',
            //     'post_status' => 'publish',
            //     'orderby' => 'ID',
            //     'posts_per_page' => -1,
            // ));
            // $posts = $query->get_posts();

            // $result = $posts->parse_query( ['ID' => '13876'] );
            // $result = $posts['post_title'];
            // $args = $query->fill_query_vars( ['category_name', 'name', 'meta_key'] );
            // $result = $query->get($args);

            // $result = \WP_Post::get_instance( 13876 );
            // $result = $result->to_array();
            // $result = $result->post_title;

            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
            	'tax_query' => array(
            		array(
            			'relation' => 'AND',
            			// 'field'    => 'slug',
            	// 		// 'terms'    => 'editorial',
            		),
            	),
            	'posts_per_page' => -1,
            );
            $query = new \WP_Query( $args );

        	// Just keep the query's posts property
        	$posts = $query->posts;//return $posts;

        	// Get just post_title and post_content of each post
        	$posts = array_map(
        		function( $post ) {
        			return array(
                        'id' => $post->ID,
        				'post_title' => $post->post_title,
        				'post_content' => $post->post_content,
        				'post_name' => $post->post_name,
        			);
        		},
        		$posts
        	);

            // return $result;
            header( 'Content-type: text/xml' );
            // print $this->array2xml( $posts );
            // return $posts;
            $array = array();
            // $l = 0;
            foreach ( $posts as $key => $value ) {
                // $array[] = $this->array2xml( $value );
                // foreach ($value as $k => $v) {
                    // $array['offers']["offer"] = $value;
                    // $array["offer"] = $value;
                    $array[$key] = $value;
                    // $l++;
                // }
            }
            // \error_log(print_r($array,true));
            // \error_log( $this->array2xml( $array ) );
            // return $this->array2xml( $array );
            return $this->arr2xml( $array );
        }
    }

    public function array2xml($array, $xml = false) {
        if ( $xml === false ) {
            $xml_header = '<?xml version="1.0" encoding="UTF-8"?><shop></shop>';
            $xml = new \SimpleXMLElement( $xml_header );
        }
        foreach( $array as $key => $value ){
            if ( is_array( $value ) ) {
                $this->array2xml( $value, $xml->addChild( $key ) );
                if ( 'currencies' == $key ) {
                    // $value->addChild( 'currency' );
                    $currencies = $xml->addChild( 'currencies' );
                    $currency = $currencies->addChild( 'currency' );
                    $currency->addAttribute('id', $value[0]);
                    // $this->array2xml( $value, $xml->addAttribute( 'id', $value[0] ) );
                }
            } else {
                if ( \is_numeric($key) ) {
                    $key = 'id';
                    // $xml->removeChild( $key );
                    // $xml->unset( $key );
                } else {
                    $xml->addChild( $key, $value );
                }
            }
        }
        // return $xml->asXML();
        $xml->saveXML();
        return $xml->asXML(WP_CONTENT_DIR . '/uploads/mrkvuamprozetka.xml');
    }

    private function arr2xml($data, $root = true){
        $str = '';
        if ( $root ) {
            $str .= '<?xml version="1.0" encoding="UTF-8"?><offers>';
        }
        foreach ( $data as $key => $val ){
            //Remove the subscript in the key []
            $key = preg_replace('/\[\d*\]/', '', $key);
            if ( \is_numeric( $key ) ) {
                $key = '';
            }
            if ( 'id' == $key) {
                $str .= addAttribute( 'id', $key );
            }
            if ( is_array( $val ) ) {
                $child = $this->arr2xml( $val, false );
                $str .= "<$key>$child</$key>";
            }else{
                $str.= "<$key><![CDATA[$val]]></$key>";
            }
        }
        if ( $root ) {
            $str .= "</offers></xml>";
        }
        return $str;
    }

}
