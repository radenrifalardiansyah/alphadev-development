<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Api Custom Controller.
 *
 * @class     Api
 * @version   1.0.0
 */
class Apicustom extends AN_Controller {

    protected $public_method = array( 'authenticate', 'forgot' );
    protected $accept_request_method = array( 'POST' );
    protected $excluded_request_method = array( 'OPTIONS' );
    protected $accept_request_ip = array( '127.0.0.1' );
    protected $secret_key = 'an_api';
    protected $requester = false;
    protected $post;
    protected $origin;

    /**
     * Constructor.
     */
    function __construct() {
        parent::__construct();

        if ( ! $this->post = $this->input->post() )
            $this->post = file_get_contents('php://input');

        if ( $this->_is_json( $this->post ) )
            $this->post = json_decode( $this->post, true );

        // load libs
        $this->load->library('user_agent');
        
        $uri = parse_url($this->agent->referrer());

        $origin = '*';
        if ( isset( $uri['scheme'] ) && isset( $uri['host'] ) ) {
        $origin = $uri['scheme'] . '://' . $uri['host'];
            if ( ! empty( $uri['port'] ) )
                $origin .= ':' . $uri['port'];
        }

        $this->origin = $origin;

        $client_ip  = an_get_current_ip();
        $method     = $this->router->fetch_method();
        $need_auth  = ! in_array( $method, $this->public_method );

        if ( isset( $_SERVER['REQUEST_METHOD'] ) && in_array( $_SERVER['REQUEST_METHOD'], $this->excluded_request_method )  )
            return $this->_response( false );

        // // require authentication token if the origin is from external site
        // if ( $need_auth && strpos( base_url(), $origin ) === false ) {
        //     if ( ! isset( $_SERVER['HTTP_X_AUTH_TOKEN'] ) || ! $auth_token = $_SERVER['HTTP_X_AUTH_TOKEN'] )
        //         return $this->_response( false, 403 );

        //     if ( ! $id = $this->_extract_token( $auth_token ) )
        //         return $this->_response( false, 403 );

            // if ( ! $requester = $this->api_model_member->get( $id ) )
            //     return $this->_response( false, 403 );

            // // the requester or current member API
            // $this->requester = $requester;

            // // update api date access
            // $this->api_model_access->update_by(
            //     array( 'token' => $auth_token ),
            //     array( 'dateaccess' => date( 'Y-m-d H:i:s' ) )
            // );
        // }

        // if ( ! isset( $_SERVER['REQUEST_METHOD'] ) )
        //     return $this->_response( false );

        // if ( ! in_array( $_SERVER['REQUEST_METHOD'], $this->accept_request_method ) )
        //     return $this->_response( false );

        // if ( ! in_array( $client_ip, $this->accept_request_ip ) ) {
        //     return $this->_response( false, 'INVALID_IP' );
        // }

        // if ( ! isset( $_SERVER['PHP_AUTH_USER'] ) )
        //     return $this->_response( false );

        // if ( ! $_SERVER['PHP_AUTH_USER'] )
        //     return $this->_response( false );

        // $auth_user = $this->encrypt->decode( $_SERVER['PHP_AUTH_USER'] );

        
        // if ( !$auth_user )
        //     return $this->_response( false );

        // if ( $auth_user !== $this->secret_key )
        //     return $this->_response( false );

    }

    // ------------------------------------------------------

    /**
     * Check if string is json
     * @private
     * @author Yuda
     * @param  string  $string the string
     * @return boolean is json
     */
    protected function _is_json($string) {
        if ( ! is_string($string) )
            return false;

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Response code header
     * @private
     * @author Yuda
     * @param integer [$code        = 200] response code
     */
    protected function _response_code( $code = 200 ) {
        if (function_exists('http_response_code')) {
            http_response_code($code);
        }
        header('X-PHP-Response-Code: ' . $code, true, $code);
    }

    /**
     * Response header
     * @private
     * @author Yuda
     */
    protected function _response_header() {
        header('Access-Control-Allow-Origin: ' . $this->origin);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, X-Auth-Token, Content-Type, Accept');
        header('Access-Control-Allow-Methods: GET, POST, PUT');

        $is_safari_apple_mobile = ($this->agent->is_browser('Safari') &&
            ($this->agent->is_mobile('iphone') || $this->agent->is_mobile('ipod') || $this->agent->is_mobile('ipad')));
        $is_ie = $this->agent->is_browser('Internet Explorer') || $this->agent->is_browser('MSIE') || $this->agent->is_browser('Mozilla'); // IE11 is seen as Mozilla by CI

        if($is_safari_apple_mobile || $is_ie)
            header('P3P: policyref="/w3c/p3p.xml", CP="CAO CURa ADMa DEVa TAIa OUR BUS IND UNI COM NAV STA"');
    }

    /**
     * Response API JSON
     * @author Yuda
     */
    protected function _response( $success, $data = array(), $client_ip = '' ) {
        $this->_response_header();

        if ( ! $success ) {
            if ( ! $data ) die();
            if ( is_numeric( $data ) ) {
                $this->_response_code( $data );
            }
        }

        $response = array( 'success' => $success );
        if ( $success && $data ) {
            if(is_array($data)) {
                foreach ($data as $key => $value) {
                    $response[$key] = $value;
                }
            }  else {
                $response['status'] = $data;
            }
        } else {
            if ( $data ) {
                if(is_array($data)) {
                    foreach ($data as $key => $value) {
                        $response[$key] = $value;
                    }
                }  else {
                    $response['status'] = $data;
                }
            }
        }

        if ( $client_ip ) {
            $response['your_ip'] = $client_ip;
        }

        $response = json_encode( $response );
        die( $response );
    }

    /**
     * API Get Prodyct
     * @author Yuda
     */
    function get_product(){
        $current_member     = an_get_current_member();
        $current_admin      = as_administrator($current_member);
        $id_member          = $this->input->post('id_member');
        $id_member          = an_isset($id_member, '');
        $stock              = $this->input->post('stock');
        $stock              = an_isset($stock, '');
        $type               = $this->input->post('type');
        $type               = an_isset($type, '');
        $post_type          = an_isset($type, '');
        $product            = array();

        if ( $id_member ) {
            $member         = an_get_memberdata_by_id($id_member);
            if ( !$member ) {
                return $this->_response( false );
            }
        }
        $products           = an_products(0, true);
        if ( $products ) {
            foreach ($products as $key => $val) {
                unset($val->created_by);
                unset($val->modified_by);
                unset($val->datecreated);
                unset($val->dateupdated);
                unset($val->datemodified);

                $val->id    = an_encrypt($val->id);
                $val->image = an_product_image($val->image, true);
                $val->sort  = ($key+1);
                $product[] = $val;
            }
        }

        return $this->_response( true, array('data' => $product));
    }

}

/* End of file Api.php */
/* Location: ./application/controllers/Api.php */