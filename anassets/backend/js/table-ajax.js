// =========================================================================
// Global Function
// =========================================================================

// Grid Data
var gridTable = function(el, action=false, target='', limit='') {
    var url     = el.data('url');
    var grid    = new Datatable();
    var tgt     = ( target!="" ? target : [ -1, 0 ] );
    var lmt     = ( limit!="" ? limit : 10 );

    grid.init({
        src: el,
        onSuccess: function(grid) {
            $('.btn-tooltip').tooltip({html:true});
        },
        onError: function(grid) {},
        dataTable: {
            "aLengthMenu": [
                [10, 20, 50, 100, -1],
                [10, 20, 50, 100, "All"]                        // change per page values here
            ],
            "iDisplayLength": lmt,                               // default record count per page
            "bServerSide": true,                                // server side processing
            "sAjaxSource": url,       // ajax source
            "aoColumnDefs": [
              { 'bSortable': false, 'aTargets': tgt }
           ]
        }
    });

    grid.getTableWrapper().on( 'draw', function () {
        $('.btn-tooltip').tooltip({
            html:true
        });
        var _tooltip = $('[data-toggle="tooltip"]');
        if ( _tooltip.length ) {
            _tooltip.tooltip();
        }
        var _popover = $('[data-toggle="popover"]');
        if ( _popover.length ) {
            _popover.each(function() {
                ! function(e) {
                    e.data("color") && (a = "popover-" + e.data("color"));
                    var t = {
                        trigger: "focus",
                        template: '<div class="popover ' + a + '" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
                    };
                    _popover.popover(t)
              }($(this))
            });
        }
    });

    if( action ){
        gridExport( grid, '.table-export-excel', url );
    }
}

// Export Grid Data
var gridExport = function( dataTable, selectorBtn, sUrl, sAction, parameter = '' ) {
    // handle group actionsubmit button click
    dataTable.getTableWrapper().on('click', selectorBtn, function(e) {
        e.preventDefault();

        if ( typeof sAction == 'undefined' ){
            sAction = 'export_excel';
        }
        	
        //dataTable.addAjaxParam( "sAction", sAction );
        var params      = 'export='+sAction;
        
        var table       = $( selectorBtn ).closest( '.table-container' ).find( 'table' );

        // get all typeable inputs
        $( 'textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])', table ).each( function() {
            params += '&' + $(this).attr("name") + '=' + $(this).val();
            //dataTable.addAjaxParam( $(this).attr("name"), $(this).val() );
        });

        // get all checkable inputs
        $( 'input.form-filter[type="checkbox"]:checked, input.form-filter[type="radio"]:checked', table ).each( function() {
            params += '&' + $(this).attr("name") + '=' + $(this).val();
            //dataTable.addAjaxParam( $(this).attr("name"), $(this).val() );
        });
        
        //dataTable.getDataTable().fnDraw();
        //dataTable.clearAjaxParams();

        if (parameter) {
            params += '&' + parameter;
        }

        var link_export = sUrl + '?' + params;

        $("div#mask").fadeIn();
        document.location.href =(link_export);
        setTimeout(function () { 
            $("div#mask").fadeOut();
            URL.revokeObjectURL(link_export); 
        }, 100);
    });
};

// Grid DatePicker
var initPickers = function () {
    //init date pickers
    $('.date-picker').datepicker({
        // rtl: App.isRTL(),
        autoclose: true
    });

    $( '.date-picker-month' ).datepicker({
        // rtl: App.isRTL(),
        autoclose: true,
        viewMode: 'years',
        minViewMode: 'months'
    });
};

// =========================================================================
// Member List Function
// =========================================================================
var TableAjaxMemberList = function () {
    var handleRecordsMemberList = function() {
        gridTable( $("#list_table_member"), true );
    };

    var handleRecordsGenerationMemberList = function() {
        gridTable( $("#list_table_generation_member"), false );
    };

    var handleRecordsOmzetMemberList = function() {
        gridTable( $("#list_table_member_omzet"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsMemberList();
            handleRecordsGenerationMemberList();
            handleRecordsOmzetMemberList();
        }
    };
}();

// =========================================================================
// Member List Function
// =========================================================================
var TableAjaxMemberLoanList = function () {
    var handleRecordsMemberLoanList = function() {
        gridTable( $("#list_table_member_loan"), false );
    };

    var handleRecordsMemberDepositeLoanList = function() {
        gridTable( $("#list_table_member_deposite_loan"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsMemberLoanList();
            handleRecordsMemberDepositeLoanList();
        }
    };
}();

// =========================================================================
// Board List List Function
// =========================================================================
var TableAjaxBoardList = function () {
    var handleRecordsMemberBoardList = function() {
        gridTable( $("#list_table_member_board"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsMemberBoardList();
        }
    };
}();

// =========================================================================
// Find Agent List Function
// =========================================================================
var TableAjaxFindAgent = function () {
    var handleRecordsFindAgent = function() {
        var table       = $("#list_table_find_agent");
        var url         = table.data('url');
        var grid        = new Datatable();
        grid.addAjaxParam("search_province_id", $('.select_province').val());
        grid.addAjaxParam("search_district_id", $('.select_district').val());
        grid.addAjaxParam("search_subdistrict_id", $('.select_subdistrict').val());
        grid.init({
            src: table,
            onSuccess: function(grid) {},
            onError: function(grid) {},
            dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
                "aLengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]                        // change per page values here
                ],
                "iDisplayLength": 10,                               // default record count per page
                "bServerSide": true,                                // server side processing
                "sAjaxSource": url,                                 // ajax source
                "aoColumnDefs": [
                  { 'bSortable': false, 'aTargets': [ -3, -2, -1, 0 ] }
               ]
            }
        });

        grid.getTableWrapper().on('click', '.filter-submit', function(e){
            e.preventDefault();
            grid.addAjaxParam("search_province_id", $('.select_province').val());
            grid.addAjaxParam("search_district_id", $('.select_district').val());
            grid.addAjaxParam("search_subdistrict_id", $('.select_subdistrict').val());

            // get all typeable inputs
            $('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])').each(function(){
                grid.addAjaxParam($(this).attr("name"), $(this).val());
            });

            grid.getDataTable().fnDraw();
            grid.clearAjaxParams();
        });

        grid.getTableWrapper().on('click', '.filter-clear', function(e){
            e.preventDefault();
            $('textarea.form-filter, select.form-filter, input.form-filter').each(function(){
                if ( $(this).attr("name") !== 'search_sponsor') {
                    $(this).val("");
                }
            });
            $('input.form-filter[type="checkbox"]').each(function(){
                $(this).attr("checked", false);
            });   

            grid.getDataTable().fnDraw();
            grid.clearAjaxParams();
        });

        $("body").delegate( ".form-control", "keypress", function( e ) {
            var key = e.which;
            if(key == 13){ 
                $('#btn-find-agent').trigger('click');
                return false; 
            }
        });

        $("body").delegate( "#btn-find-agent", "click", function( e ) {
            grid.getTableWrapper().find('.filter-submit').click();
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsFindAgent();
        }
    };
}();

// =========================================================================
// Data PIN List Function
// =========================================================================
var TableAjaxPINList = function () {
    var handleRecordsPINMemberActiveList = function() {
        gridTable( $("#list_table_pin_member_active"), true );
    };
    var handleRecordsPINUsedList = function() {
        gridTable( $("#list_table_pin_used"), false );
    };
    var handleRecordsPINStatusList = function() {
        gridTable( $("#list_table_pin_status"), false );
    };
    var handleRecordsPINMemberList = function() {
        gridTable( $("#list_table_pin_member"), true );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsPINMemberActiveList();
            handleRecordsPINUsedList();
            handleRecordsPINStatusList();
            handleRecordsPINMemberList();
        }
    };
}();

// =========================================================================
// PIN Order List Function
// =========================================================================
var TableAjaxPINOrderList = function () {
    var handleRecordsPINOrderList = function() {
        gridTable( $("#list_table_pin_order"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsPINOrderList();
        }
    };
}();

// =========================================================================
// Product Manage List Function
// =========================================================================
var TableAjaxProductManageList = function () {
    var handleRecordsProductManageList = function() {
        gridTable( $("#list_table_product"), false );
    };

    var handleRecordsProductCategoryList = function() {
        gridTable( $("#list_table_category"), false );
    };

    var handleRecordsProductPointList = function() {
        gridTable( $("#list_table_product_point"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsProductManageList();
            handleRecordsProductCategoryList();
            handleRecordsProductPointList();
        }
    };
}();

// =========================================================================
// Promo Code List Function
// =========================================================================
var TableAjaxPromoCodeList = function () {
    var handleRecordsPromoCodeList = function() {
        gridTable( $("#list_table_promo_code"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsPromoCodeList();
        }
    };
}();

// =========================================================================
// Commission List Function
// =========================================================================
var TableAjaxCommissionList = function () {
    var handleRecordsTotalBonusList = function() {
        gridTable( $("#list_table_total_bonus"), true );
    };
    var handleRecordsHistoryBonusList = function() {
        gridTable( $("#list_table_history_bonus"), true );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsTotalBonusList();
            handleRecordsHistoryBonusList();
        }
    };
}();

// =========================================================================
// Deposite List Function
// =========================================================================
var TableAjaxDepositeList = function () {
    var handleRecordsDepositeList = function() {
        gridTable( $("#list_table_deposite"), true );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsDepositeList();
        }
    };
}();

// =========================================================================
// Commission List Function
// =========================================================================
var TableAjaxCommissionssList = function () {
    var handleRecordsTotalCommissionList = function() {
        var table       = $("#list_table_total_commission");
        if ( table.length ) {
            var url         = table.data('url');
            var grid        = new Datatable();
            grid.addAjaxParam("search_startdate", $('input[name=search_startdate]').val());
            grid.addAjaxParam("search_enddate", $('input[name=search_enddate]').val());
            grid.init({
                src: table,
                onSuccess: function(grid) {},
                onError: function(grid) {},
                dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
                    "aLengthMenu": [
                        [10, 20, 50, 100, -1],
                        [10, 20, 50, 100, "All"]                        // change per page values here
                    ],
                    "iDisplayLength": 10,                               // default record count per page
                    "bServerSide": true,                                // server side processing
                    "sAjaxSource": url,                                 // ajax source
                    "aoColumnDefs": [
                      { 'bSortable': false, 'aTargets': [ -1, 0 ] }
                   ]
                }
            });

            grid.getTableWrapper().on('click', '.filter-search', function(e){
                e.preventDefault();
                grid.addAjaxParam("search_startdate", $('input[name=search_startdate]').val());
                grid.addAjaxParam("search_enddate", $('input[name=search_enddate]').val());

                // get all typeable inputs
                $('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])', table).each(function(){
                    grid.addAjaxParam($(this).attr("name"), $(this).val());
                });

                grid.getDataTable().fnDraw();
                grid.clearAjaxParams();
            });

            grid.getTableWrapper().on('click', '.filter-clear', function(e){
                e.preventDefault();
                grid.addAjaxParam("search_startdate", $('input[name=search_startdate]').val());
                grid.addAjaxParam("search_enddate", $('input[name=search_enddate]').val());
                $('textarea.form-filter, select.form-filter, input.form-filter', table).each(function(){
                    $(this).val("");
                });

                grid.getDataTable().fnDraw();
                grid.clearAjaxParams();
            });

            $("body").delegate( "#btn-search-period-commission", "click", function( event ) {
                event.preventDefault();
                $('#btn_list_table_total_commission').trigger('click');
            });
        }

        $("body").delegate( "#btn-search-period-commission-detail", "click", function( event ) {
            event.preventDefault();
            var url         = $(this).data('url');
            var startdate   = $('input[name=search_startdate]').val();
            var enddate     = $('input[name=search_enddate]').val();
            var url_direct  = url +'?daterange='+ startdate + '|' + enddate;
            $(location).attr('href', url_direct);
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsTotalCommissionList();
        }
    };
}();

// =========================================================================
// Withdraw List Function
// =========================================================================
var TableAjaxWithdrawList = function () {
    var handleRecordsWithdrawList = function() {
        gridTable( $("#list_table_withdraw"), true );
    };
    var handleRecordsWithdrawSummaryList = function() {
        gridTable( $("#list_table_withdraw_summary"), true );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsWithdrawList();
            handleRecordsWithdrawSummaryList();
        }
    };
}();

// =========================================================================
// Flip List Function
// =========================================================================
var TableAjaxFlipList = function () {
    var handleRecordsFliptrxList = function() {
        gridTable( $("#list_table_flip_trx"), false, [ -2, -1, 0 ] );
    };
    var handleRecordsFlipTopupList = function() {
        gridTable( $("#list_table_flip_topup"), false );
    };
    var handleRecordsFlipInquiryList = function() {
        gridTable( $("#list_table_flip_inquiry"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsFliptrxList();
            handleRecordsFlipTopupList();
            handleRecordsFlipInquiryList();
        }
    };
}();

// =========================================================================
// Faspay List Function
// =========================================================================
var TableAjaxFaspayList = function () {
    var handleRecordsFaspaytrxList = function() {
        gridTable( $("#list_table_faspay_trx"), false, [ -2, -1, 0 ] );
    };
    var handleRecordsFaspaytrxTotalList = function() {
        gridTable( $("#list_table_faspay_trx_total"), false );
    };
    var handleRecordsFaspayInquiryList = function() {
        gridTable( $("#list_table_faspay_inquiry"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsFaspaytrxList();
            //handleRecordsFaspaytrxTotalList();
            handleRecordsFaspayInquiryList();
        }
    };
}();

// =========================================================================
// Shop Order Product List Function
// =========================================================================
var TableAjaxShopOrderList = function () {
    var handleRecordsShopList = function() {
        if ( $("#list_table_shop_pending").length ) {
            gridTable( $("#list_table_shop_pending"), true );
        }
        if ( $("#list_table_shop_confirmed").length ) {
            gridTable( $("#list_table_shop_confirmed"), true );
        }
        if ( $("#list_table_shop_done").length ) {
            gridTable( $("#list_table_shop_done"), true );
        }
        if ( $("#list_table_shop_cancelled").length ) {
            gridTable( $("#list_table_shop_cancelled"), true );
        }
        if ( $("#list_table_shop_history").length ) {
            gridTable( $("#list_table_shop_history"), false );
        }
        if ( $("#list_table_shop_stockist").length ) {
            gridTable( $("#list_table_shop_stockist"), false );
        }

        $('body').delegate('.btn_shop_order_status', 'click', function(){
            var status_order = $(this).data('status');
            if ( status_order == 'pending' ) {
                var btn_search = $('#btn_list_table_shop_pending');
                if ( btn_search.length ) {
                    btn_search.trigger('click');
                }
            }
            if ( status_order == 'confirmed' ) {
                var btn_search = $('#btn_list_table_shop_confirmed');
                if ( btn_search.length ) {
                    btn_search.trigger('click');
                }
            }
            if ( status_order == 'done' ) {
                var btn_search = $('#btn_list_table_shop_done');
                if ( btn_search.length ) {
                    btn_search.trigger('click');
                }
            }
            if ( status_order == 'cancelled' ) {
                var btn_search = $('#btn_list_table_shop_cancelled');
                if ( btn_search.length ) {
                    btn_search.trigger('click');
                }
            }
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsShopList();
        }
    };
}();

// =========================================================================
// Reward List Function
// =========================================================================
var TableAjaxOmzetList = function () {
    var handleRecordsOmzetDailyList = function() {
        gridTable( $("#list_table_omzet_daily"), true );
    };

    var handleRecordsOmzetMonthlyList = function() {
        gridTable( $("#list_table_omzet_monthly"), true );
    };

    var handleRecordsOmzetOrderDailyList = function() {
        gridTable( $("#list_table_omzet_order_daily"), true );
    };

    var handleRecordsOmzetOrderMonthlyList = function() {
        gridTable( $("#list_table_omzet_order_monthly"), true );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsOmzetDailyList();
            handleRecordsOmzetMonthlyList();
            handleRecordsOmzetOrderDailyList();
            handleRecordsOmzetOrderMonthlyList();
        }
    };
}();

// =========================================================================
// Reward List Function
// =========================================================================
var TableAjaxRewardList = function () {
    var handleRecordsRewardList = function() {
        gridTable( $("#list_table_reward"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsRewardList();
        }
    };
}();

// =========================================================================
// Rank Qualification List Function
// =========================================================================
var TableAjaxQualificationList = function () {
    var handleRecordsQualificationList = function() {
        gridTable( $("#list_table_rank_qualification"), false );
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsQualificationList();
        }
    };
}();

// =========================================================================
// Setting Staff List Function
// =========================================================================
var TableAjaxStaffList = function () {
    var handleRecordsStaffList = function() {
        gridTable( $("#list_table_staff") );
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsStaffList();
        }
    };
}();

// =========================================================================
// Setting Notification List Function
// =========================================================================
var TableAjaxNotifList = function () {
    var handleRecordsNotificationList = function() {
        gridTable( $("#notification_list") );
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsNotificationList();
        }
    };
}();

// =========================================================================
// Setting Reward List Function
// =========================================================================
var TableAjaxSettingRewardList = function () {
    var handleRecordsSettingRewardList = function() {
        gridTable( $("#list_table_setting_reward") );
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsSettingRewardList();
        }
    };
}();
