<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_product') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $menu_title; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $menu_title; ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-container">
                        <div class="table-actions-wrapper table-group-actions text-right">
                            <button class="btn btn-sm btn-info text-white table-export-excel">
                                <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                            </button>
                        </div>
                        <table class="table align-items-center table-flush" id="list_table_product" data-url="<?php echo base_url('productmanage/productstocklistsdata'); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" rowspan="2" style="width: 10px">#</th>
                                    <th scope="col" rowspan="2" class="text-center"><?php echo lang('product'); ?></th>
                                    <th scope="col" colspan="2" class="text-center">Stock</th>
                                    <th scope="col" rowspan="2" class="text-center">Total Stock</th>
                                    <th scope="col" rowspan="2" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="heading">
                                    <th scope="col" class="text-center">Stock IN</th>
                                    <th scope="col" class="text-center">Stock OUT</th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td>
                                        <div class="mb-1">
                                            <input type="text" class="form-control form-control-sm form-filter text-center" name="search_stock_in_min" placeholder="Min" />
                                        </div>
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_stock_in_max" placeholder="Max" />
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <input type="text" class="form-control form-control-sm form-filter text-center" name="search_stock_out_min" placeholder="Min" />
                                        </div>
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_stock_out_max" placeholder="Max" />
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <input type="text" class="form-control form-control-sm form-filter text-center" name="search_stock_min" placeholder="Min" />
                                        </div>
                                        <input type="text" class="form-control form-control-sm form-filter text-center" name="search_stock_max" placeholder="Max" />
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_product" title="Search"><i class="fa fa-search"></i></button>
                                        <button class="btn btn-sm btn-block btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <!-- Data Will Be Placed Here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
