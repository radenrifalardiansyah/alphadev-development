<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Staff Class
 *
 * @subpackage	Libraries
 */
class AN_Staff extends CI_Session {
	
	/**
	 * 
	 */
	public $CI;
	
	/**
	 * 
	 */
	private $data;
	
	/**
	 * 
	 */
	private $role;
	
	/**
	 * 
	 */
	private $allowed_access;
	
	/**
	 * 
	 */
	private $restricted_access;
	
	/**
	 * 
	 */
	private $access_text;
	
	/**
	 * 
	 */
	protected static $DASHBOARD 		= array( 'backend/index' );
	protected static $PROFILE 			= array( 'backend/profile', 'member/personalinfo', 'member/changepassword' );
	protected static $INVOICE 			= array( 'backend/invoice' );
	protected static $LOGOUT 			= array( 'auth/logout' );
	protected static $SEARCHGENERAL 	= array( 
		'member/searchtree', 
		'member/searchboardtree', 
		'member/searchuplinetree', 
		'member/searchmemberdata', 
		'member/searchmember'
	);

	// MEMBER ACCESS
	protected static $MEMBER_CRUD 		= array( 
		'backend/membernew', 
		'backend/memberloan', 
		'member/memberreg',
		'member/memberloan',
		'member/searchuplinetree',
		'member/searchupline', 
		'member/searchsponsor',
		'member/checkusername', 
		'member/checkemail', 
		'member/checkphone', 
		'member/checkidcard', 
		'member/checkbill', 
		'address/selectprovince', 
		'address/selectdistrict', 
		'address/selectsubdistrict'
	);

	protected static $MEMBER_LIST 		= array('backend/memberlist', 'backend/bannedlist', 'member/memberlistsdata', 'member/memberloanlistsdata', 'member/memberloandepositelistsdata');
	protected static $MEMBER_TREE 		= array('backend/membertree');
	protected static $MEMBER_GEN 		= array('backend/membergeneration', 'member/generationtree', 'member/generationdata');
	protected static $MEMBER_UPDATE		= array('member/searchmemberdata','member/memberstatus', 'member/asbanned', 'member/asactive', 'backend/assume', 'backend/revert');
	protected static $VIEW_PROFILE 		= array('backend/profile');
	protected static $EDIT_PROFILE 		= array('backend/profile', 'member/personalinfo', 'member/changepassword');
	
	// BONUS
	protected static $BONUS_VIEW 		= array('backend/bonus', 'backend/deposite', 'backend/commission');
	protected static $BONUS_LIST 		= array(
		'commission/totalbonuslistdata', 
		'commission/historybonuslistdata', 
		'commission/memberbonuslistdata', 
		'commission/depositelistdata',
		'commission/memberdepositelistdata',
		'commission/commissionlistdata',
	);
	
	// BONUS WITHDRAW
	protected static $BONUS_WITHDRAW 	= array('backend/withdraw', 'commission/withdrawlistdata', 'commission/withdrawmonthlylistdata');
	protected static $WITHDRAW_CONFIRM 	= array('commission/withdrawaltransfer');

	// FLIP
	protected static $FLIP_VIEW 		= array('backend/fliptrx', 'backend/fliptopup', 'backend/flipinquiry');
	protected static $FLIP_LIST 		= array( 
		'flip/fliptrxlistdata', 
		'flip/fliptopuplistdata', 
		'flip/flipinquirylistdata', 
		'flip/topupsaldo', 
		'flip/checkstatustopup', 
		'flip/checkstatustransferflip'
	);

	// REPORT REGISTER AND RO
	protected static $REPORT_REGRO_VIEW 	= array('backend/registration', 'backend/historyro', 'backend/historyro');
	protected static $REPORT_REGRO_LIST 	= array('member/registerlistdata', 'member/rolistdata');
	protected static $REPORT_REGRO_CONFIRM 	= array('member/memberconfirm', 'member/membercancel');

	// REPORT SALES
	protected static $REPORT_SALES_VIEW 	= array('backend/sales');
	protected static $REPORT_SALES_LIST 	= array('shopping/shoporderlistsdata', 'shopping/getshoporderdetail', 'shopping/sethtmlshoporderdetail');
	protected static $REPORT_SALES_CONFIRM 	= array('shopping/confirmorder', 'shopping/cancelorder', 'shopping/confirmshipping');

	// REPORT SALES STOCKIST and OMZET
	protected static $REPORT_SALES_STOCKIST = array('backend/salesstockist');

	// REPORT
	protected static $REPORT_OMZET_VIEW 	= array('backend/omzet', 'backend/omzetorder');
	protected static $REPORT_OMZET_LIST 	= array(
		'member/omzetdailylistdata', 
		'member/omzetmonthlylistdata', 
		'shopping/omzetorderdailylistdata', 
		'shopping/omzetordermonthlylistdata'
	);

	// PROMO CODE (DISCOUNT)
	protected static $PROMO_CODE_VIEW 	= array(
		'backend/promocodeglobal', 
		'backend/promocodespesific',
	);
	protected static $PROMO_CODE_LIST 	= array(
		'setting/promocodelistdata', 
	);
	protected static $PROMO_CODE_CRUD 	= array(
		'setting/savepromocode',
		'setting/checkpromocode',
		'setting/promocodestatus',
		'setting/deletepromocode',
	);

	// MANAGE PRODUCT
	protected static $MANAGE_PRODUCT_VIEW 	= array(
		'backend/categorylist', 
		'backend/productlist',  
		'backend/productinlist',
		'backend/productstocklist'
	);
	protected static $MANAGE_PRODUCT_LIST 	= array(
		'productmanage/categorylistsdata', 
		'productmanage/productlistsdata', 
		'productmanage/productinlistsdata',
		'productmanage/productstocklistsdata'
	);
	protected static $MANAGE_PRODUCT_CRUD 	= array(
		'productmanage/savecategory',
		'productmanage/saveproduct',
		'productmanage/saveproductstock',
		'productmanage/productstatus',
		'productmanage/categorystatus',
		'productmanage/productdelete',
		'productmanage/categorydelete',
	);

	// STAFF	
	protected static $STAFF 			= array(
		'staff/index', 
		'staff/manage', 
		'staff/managelist', 
		'staff/formstaff', 
		'staff/edit', 
		'staff/getstaff', 
		'staff/savestaff', 
		'staff/resetpassword', 
		'staff/del' 
	);

	// SETTING	
	protected static $SETTING			= array(
		'setting/general', 
		'setting/updatesetting', 
		'setting/updatecompany', 
		'setting/updatecompanybilling', 
		'setting/notification', 
		'setting/notificationlistdata', 
		'setting/notifdata', 
		'setting/updatenotification',
		'setting/reward', 
		'setting/rewardlistdata', 
		'setting/savereward', 
		'setting/withdraw', 
		'setting/updatewithdraw'
	);

	// MEMBER BOARD
	protected static $MEMBER_BOARD_VIEW 	= array('backend/boardlist', 'backend/boardtree');
	protected static $MEMBER_BOARD_LIST 	= array('member/boardlistsdata', 'member/searchboardtree');

	// PIN PRODUCT
	protected static $PIN_PRODUCT_VIEW 		= array('backend/pinlist', 'backend/pinhistorylist');
	protected static $PIN_PRODUCT_LIST 		= array('pin/depositepinlistdata', 'pin/pinmemberlistdata', 'pin/pinusedlistdata', 'pin/pinstatuslistdata', 'pin/pintransferlistsdata');
	protected static $PIN_PRODUCT_GENERATE 	= array('backend/pingenerate', 'pin/savegenerate');
    
    /**
     * Session Constructor
     *
     * The constructor runs the session routines automatically
     * whenever the class is instantiated.
     */
    public function __construct( $params = array() ) {
        $this->CI =& get_instance();
		$this->allowed_access = array();
		$this->restricted_access = array();
    }

	// --------------------------------------------------------------------
	
	public function staff( $id_staff ) {
		if ( empty( $id_staff ) )
			return false;
		
		if ( ! $staff = $this->CI->Model_Staff->get( $id_staff ) )
			return false;
		
		$this->data = $staff;
		
		// set role
		$this->_set_role();
		
		// return staff object
		return $this->data;
	}

	// --------------------------------------------------------------------
	
	public function has_access() {
		$path = $this->_get_current_path();
		
		if ( $this->data->access == 'all' ) {
			if ( in_array( $path, $this->restricted_access ) )
				return false;
			
			return true;
		}
		
		// partial access
		if ( in_array( $path, $this->allowed_access ) )
			return true;
		
		return false;
	}

	// --------------------------------------------------------------------
	
	public function get_access_text() {
		return $this->access_text;
	} 

	// --------------------------------------------------------------------
	
	protected function _set_role() {
		$this->role = array();
		$this->access_text = array();
		
		if ( is_array( $this->data->role ) )
			$this->role = $this->data->role;
		
		$config_access_text = config_item( 'staff_access_text' );
		
		$this->_add_allowed_access( self::$DASHBOARD );
		$this->_add_allowed_access( self::$PROFILE );
		$this->_add_allowed_access( self::$SEARCHGENERAL );
		$this->_add_allowed_access( self::$INVOICE );
		$this->_add_allowed_access( self::$LOGOUT );
		
		foreach ( $this->role as $role ) {
			$this->access_text[] = $config_access_text[ $role ];
			switch ( $role ) {
				case STAFF_ACCESS1:
					$this->_add_allowed_access( self::$MEMBER_LIST );
					$this->_add_allowed_access( self::$MEMBER_TREE );
					$this->_add_allowed_access( self::$MEMBER_GEN );
					$this->_add_allowed_access( self::$VIEW_PROFILE );
					break;
				case STAFF_ACCESS2:
					$this->_add_allowed_access( self::$MEMBER_CRUD );
					$this->_add_allowed_access( self::$MEMBER_LIST );
					$this->_add_allowed_access( self::$MEMBER_TREE );
					$this->_add_allowed_access( self::$MEMBER_GEN );
					$this->_add_allowed_access( self::$MEMBER_UPDATE );
					$this->_add_allowed_access( self::$EDIT_PROFILE );
					break;
				case STAFF_ACCESS3:
					$this->_add_allowed_access( self::$MEMBER_BOARD_VIEW );
					$this->_add_allowed_access( self::$MEMBER_BOARD_LIST );
					break;
				case STAFF_ACCESS4:
					$this->_add_allowed_access( self::$PIN_PRODUCT_GENERATE );
					break;
				case STAFF_ACCESS5:
					$this->_add_allowed_access( self::$PIN_PRODUCT_VIEW );
					$this->_add_allowed_access( self::$PIN_PRODUCT_LIST );
					break;
				case STAFF_ACCESS6:
					$this->_add_allowed_access( self::$BONUS_VIEW );
					$this->_add_allowed_access( self::$BONUS_LIST );
					break;
				case STAFF_ACCESS7:
					$this->_add_allowed_access( self::$BONUS_WITHDRAW );
					$this->_add_allowed_access( self::$WITHDRAW_CONFIRM );
					break;
				case STAFF_ACCESS8:
					$this->_add_allowed_access( self::$REPORT_REGRO_VIEW );
					$this->_add_allowed_access( self::$REPORT_REGRO_LIST );
					$this->_add_allowed_access( self::$REPORT_REGRO_CONFIRM );
					break;
				case STAFF_ACCESS9:
					$this->_add_allowed_access( self::$REPORT_SALES_VIEW );
					$this->_add_allowed_access( self::$REPORT_SALES_LIST );
					$this->_add_allowed_access( self::$REPORT_SALES_CONFIRM );
					break;
				case STAFF_ACCESS10:
					$this->_add_allowed_access( self::$REPORT_SALES_VIEW );
					$this->_add_allowed_access( self::$REPORT_SALES_LIST );
					$this->_add_allowed_access( self::$REPORT_SALES_CONFIRM );
					break;
				case STAFF_ACCESS11:
					$this->_add_allowed_access( self::$REPORT_SALES_STOCKIST );
					$this->_add_allowed_access( self::$REPORT_SALES_LIST );
					$this->_add_allowed_access( self::$REPORT_OMZET_VIEW );
					$this->_add_allowed_access( self::$REPORT_OMZET_LIST );
					break;
				case STAFF_ACCESS12:
					$this->_add_allowed_access( self::$MANAGE_PRODUCT_VIEW );
					$this->_add_allowed_access( self::$MANAGE_PRODUCT_LIST );
					$this->_add_allowed_access( self::$MANAGE_PRODUCT_CRUD );
					break;
				case STAFF_ACCESS13:
					$this->_add_allowed_access( self::$FLIP_VIEW );
					$this->_add_allowed_access( self::$FLIP_LIST );
					break;
				// case STAFF_ACCESS16:
				// 	$this->_add_allowed_access( self::$PROMO_CODE_VIEW );
				// 	$this->_add_allowed_access( self::$PROMO_CODE_LIST );
				// 	$this->_add_allowed_access( self::$PROMO_CODE_CRUD );
				// 	break;
			}
		}
		
		if ( $this->data->access == 'all' ) {
			$this->access_text = array( 'Semua Fitur' );
			
			foreach ( array( STAFF_ACCESS14, STAFF_ACCESS15 ) as $role ) {
				if ( empty( $this->role ) || ! in_array( $role, $this->role ) ) {
					$this->access_text[] = 'Tidak bisa akses ' . $config_access_text[ $role ];
					switch( $role ) {
						case STAFF_ACCESS14:
							$this->_add_restricted_access( self::$STAFF );
							break;
						case STAFF_ACCESS15:
							$this->_add_restricted_access( self::$SETTING );
							break;
					}
				}
			}
			
		}
	}

	// --------------------------------------------------------------------
	
	protected function _get_current_path() {
		$controller = $this->CI->router->fetch_class();
		$method = $this->CI->router->fetch_method();
		return $controller . '/' . $method;
	}

	// --------------------------------------------------------------------
	
	protected function _add_allowed_access( $access ) {
		$this->_add_access( $access, $this->allowed_access );
	}

	// --------------------------------------------------------------------
	
	protected function _add_restricted_access( $access ) {
		$this->_add_access( $access, $this->restricted_access );
	}

	// --------------------------------------------------------------------
	
	protected function _add_access( $access, &$to ) {
		if ( is_string( $access ) ) {
			$to[] = $access;
			$to = array_unique( $to );
			return;
		}
		
		$to = array_merge( $to, $access );
		$to = array_unique( $to );
	}

	// --------------------------------------------------------------------
}
// END Session Class