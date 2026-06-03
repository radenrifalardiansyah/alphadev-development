<?php 
    $member_id      = an_encrypt($member_other->id);
    $start_date     = isset($start_date) ? $start_date : date('Y-m-01');
    $end_date       = isset($start_date) ? $end_date : date('Y-m-d');
    $daterange      = array($start_date, $end_date);
?>

<div class="form-group row mb-0" id="period_reward">
    <label class="col-md-2 col-lg-1 col-form-label form-control-label"><?php echo lang('username'); ?></label>
    <label class="col-md-10 col-form-label form-control-label"><?php echo $member_other->username; ?></label>
</div>
<div class="form-group row mb-3" id="period_reward">
    <label class="col-md-2 col-lg-1 col-form-label form-control-label"><?php echo lang('name'); ?></label>
    <label class="col-md-10 col-form-label form-control-label"><?php echo $member_other->name; ?></label>
</div>
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
                <button type="button" class="btn btn-neutral" id="btn-search-period-commission-detail" data-url="<?php echo base_url('commission/commission/'.$member_id); ?>">Set Period</button>
            </div>
        </div>
    </div>
</div>
<div class="table-container">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr role="row" class="heading">
                <th scope="col" style="width: 10px">#</th>
                <th scope="col" class="text-center">Bonus</th>
                <th scope="col" class="text-center"><?php echo lang('total'); ?></th>
            </tr>
        </thead>
        <tbody class="list">
        <?php
            $bonus_type = config_item('bonus_type');
            $currency   = config_item('currency');
            if( $bonus_type && !empty($bonus_type) ){
                $i = 0;
                $total_bonus =  0;
                foreach($bonus_type as $key => $val){
                    $bonus = $this->Model_Bonus->get_total_bonus_member($member_other->id, $key, ($daterange) ? $daterange : $start_date);
                    if ( $bonus ) {
                        $total_bonus += $bonus;
                    }
                    echo '<tr>';
                    echo '<td class="text-center">' . ($i+=1) . '</td>';
                    echo '<td>' . strtoupper($val) . '</td>';
                    echo '<td>' . an_accounting($bonus, $currency, TRUE) . '</td>';
                    echo '</tr>';
                }

                echo '<tr>
                        <th colspan="2" class="text-right">Total Bonus</th>
                        <td style="background-color: #d2e8f3; font-weight:700">' . an_accounting($total_bonus, $currency, TRUE) . '</td>
                    </tr>';
            } else {
                echo '<tr><td colspan="3">Tipe Komisi bonus tidak ditemukan</td></tr>';
            }
        ?>
        </tbody>
    </table>
</div>