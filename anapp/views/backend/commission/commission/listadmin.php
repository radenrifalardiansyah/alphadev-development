<?php 
    $start_date     = isset($start_date) ? $start_date : date('Y-m-01');
    $end_date       = isset($start_date) ? $end_date : date('Y-m-d');
?>

<div class="form-group row mb-3" id="period_reward">
    <label class="col-md-2 col-lg-1 col-form-label form-control-label"><?php echo lang('period'); ?></label>
    <div class="col-md-10">
        <div class="row input-daterange datepicker align-items-center">
            <div class="col col-md-4 col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control text-center" readonly name="search_startdate" data-date-format="yyyy-mm-dd" placeholder="From" value="<?php echo $start_date; ?>" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-neutral"><i class="ni ni-calendar-grid-58"></i></button>
                    </span>
                </div>
            </div>
            <div class="col-md-1 text-center">
                <label>s/d</label>
            </div>
            <div class="col col-md-4 col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control text-center" readonly name="search_enddate" data-date-format="yyyy-mm-dd" placeholder="To" value="<?php echo $end_date; ?>" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-neutral"><i class="ni ni-calendar-grid-58"></i></button>
                    </span>
                </div>
            </div>
            <div class="col-12 col-md-3 text-center">
                <button type="button" class="btn btn-neutral" id="btn-search-period-commission">Set Period</button>
            </div>
        </div>
    </div>
</div>

<div class="table-container">
    <table class="table align-items-center table-flush" id="list_table_total_commission" data-url="<?php echo base_url('commission/commissionlistdata'); ?>">
        <thead class="thead-light">
            <tr role="row" class="heading">
                <th scope="col" style="width: 10px">#</th>
                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                <th scope="col"><?php echo lang('name'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('total'); ?></th>
                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
            </tr>
            <tr role="row" class="filter" style="background-color: #f6f9fc">
                <td></td>
                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                <td>
                    <div class="mb-1">
                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                </td>
                <td style="text-align: center;">
                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-search" id="btn_list_table_total_commission" title="Search"><i class="fa fa-search"></i></button>
                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-clear" title="Reset"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </thead>
        <tbody class="list">
            <!-- Data Will Be Placed Here -->
        </tbody>
    </table>
</div>