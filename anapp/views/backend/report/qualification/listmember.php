<?php
$packagedata = adv_packages($member->package, false);

if ($member->package == MEMBER_SALES) {
    $cfg_period = isset($packagedata->upgrade_period) ? $packagedata->upgrade_period : 1;
} else {
    $cfg_period = isset($packagedata->maintain_period) ? $packagedata->maintain_period : 1;
}

$package_cfg = config_item('package');


$packagedata = isset($packagedata) ? $packagedata : null;

$start_period   = ($member->start_period && $member->start_period != '0000-00-00') ? $member->start_period : $member->datecreated;
$start_period   = date('Y-m', strtotime($start_period));
$end_period     = date('Y-m', strtotime($start_period . ' + ' . ($cfg_period - 1) . ' MONTH'));

$start          = $month = strtotime($start_period);
$end            = strtotime($end_period);
$periods        = array();
if ($end >= $start) {
    while ($month <= $end) {
        $_month = date('Y-m', $month);
        $month  = strtotime("+1 month", $month);
        $periods[$_month] = $_month;
    }
}
?>
<div class="card">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Informasi Kualifikasi Saya</h3>
            </div>
        </div>
    </div>
    <div class="table-container">
        <table class="table table-border align-items-center table-flush">
            <thead class="thead-light">
                <tr role="row" class="heading">
                    <th scope="col" style="width: 10px">#</th>
                    <th scope="col" class="text-center"><?php echo lang('month'); ?></th>
                    <th scope="col" class="text-center">Personal PV</th>
                    <th scope="col" class="text-center">Group PV</th>
                    <th scope="col" class="text-center">Group Aktif</th>
                    <th scope="col" class="text-center">Status <br> Upgrade</th>
                    <?php if ($member->package != MEMBER_SALES) : ?>
                        <th scope="col" class="text-center">Status <br> Maintenance</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="list">
                <?php if ($periods) : ?>
                    <?php $num = 1; ?>
                    <?php foreach ($periods as $period) : ?>
                        <?php
                        $personal           = $group = $group_active = 0;
                        $status             = '<span class="badge badge-danger">NOT QUALIFIED</span>';
                        $status_maintain    = '<span class="badge badge-danger">NOT QUALIFIED</span>';
                        $year               = date('Y', strtotime($period));
                        $month              = date('n', strtotime($period));
                        $cond               = array('year' => $year, 'month' => $month);
                        $getGrade           = $this->Model_Member->get_grade_by('id_member', $member->id, $cond, 1);
                        if ($getGrade) {
                            $personal       = adv_isset($getGrade->total_pv, 0, 0);
                            $group          = adv_isset($getGrade->total_pv_group, 0, 0);
                            $group_active   = adv_isset($getGrade->group_active, 0, 0);
                            if ($getGrade->qualified) {
                                $status     = '<span class="badge badge-success">QUALIFIED</span>';
                            }

                            if ($getGrade->qualified_maintain) {
                                $status_maintain     = '<span class="badge badge-success">QUALIFIED</span>';
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $num ?></td>
                            <td><?= adv_center($period) ?></td>
                            <td><?= adv_accounting($personal ? $personal : 0, '', true) ?></td>
                            <td><?= adv_accounting($group ? $group : 0, '', true) ?></td>
                            <td><?= $member->package != MEMBER_SALES ? (adv_center($group_active . ' ' . ($packagedata ? $package_cfg[$packagedata->package_prev] : ''))) : adv_center(0) ?></td>
                            <td><?= adv_center($status) ?></td>
                            <?php if ($member->package != MEMBER_SALES) : ?>
                                <td><?= adv_center($status_maintain) ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php $num++; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">No Data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$cfg_packages   = adv_packages();
$currency       = config_item('currency');
?>


<div class="card">
    <div class="card-header border-0">
        <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tabs-info-upgrade" data-toggle="tab" href="#info-upgrade-tab" role="tab" aria-controls="info-upgrade-tab" aria-selected="true">Informasi Kenaikan Peringkat</a>
                </li>
                <?php if ($member->package != MEMBER_SALES) : ?>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-info-maintenance" data-toggle="tab" href="#info-maintenance-tab" role="tab" aria-controls="info-maintenance-tab" aria-selected="false">Informasi Mempertahankan Peringkat</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="table-container">
        <div class="tab-content" id="bonusListContent">
            <div class="tab-pane fade show active" id="info-upgrade-tab" role="tabpanel" aria-labelledby="info-upgrade-tab">
                <div class="table-responsive table-container">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('grade'); ?></th>
                                <th scope="col" class="text-center">Personal PV<br>/ Bulan</th>
                                <th scope="col" class="text-center">Group PV<br>/ Bulan</th>
                                <th scope="col" class="text-center">Group Aktif<br>/ Bulan</th>
                                <th scope="col" class="text-center">Periode Qualifikasi</th>
                                <th scope="col" class="text-center">Lama Periode</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php
                            if ($cfg_packages) {
                                $num = 1;
                                foreach ($cfg_packages as $key => $row) {
                                    $upg_id     = adv_encrypt($row->package);
                                    if (!$row->package_next) {
                                        continue;
                                    }
                                    $package_next = adv_packages($row->package_next);
                                    if (!$package_next) {
                                        continue;
                                    }

                                    echo '
                                    <tr>
                                        <td>' . $num . '</td>
                                        <td class="text-center">
                                            ' . $row->package_name . br() . '
                                            <i class="ni ni-bold-down"></i>' . br() . '
                                            <b>' . $package_next->package_name . '</b>
                                        </td>
                                        <td class="text-right">' . adv_accounting($row->upgrade_personal_pv) . '</td>
                                        <td class="text-right">' . adv_accounting($row->upgrade_group_pv) . '</td>
                                        <td class="text-center">' . adv_accounting($row->upgrade_group_active) . ' <span class="ml-2"><small>' . $row->package . '</small></span></td>
                                        <td class="text-center">' . adv_accounting($row->upgrade_period_min) . ' x Qualified</td>
                                        <td class="text-center">' . adv_accounting($row->upgrade_period) . ' ' . lang('month') . '</td>
                                    </tr>';
                                    $num++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($member->package != MEMBER_SALES) : ?>
                <div class="tab-pane" id="info-maintenance-tab" role="tabpanel" aria-labelledby="info-maintenance-tab">
                    <div class="table-responsive table-container">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center"><?php echo lang('grade'); ?></th>
                                    <th scope="col" class="text-center">Personal PV<br>/ Bulan</th>
                                    <th scope="col" class="text-center">Group PV<br>/ Bulan</th>
                                    <th scope="col" class="text-center">Lama Periode</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                if ($cfg_packages) {
                                    $num = 1;
                                    foreach ($cfg_packages as $key => $row) {
                                        $upg_id     = adv_encrypt($row->package);
                                        if (!$row->package_prev) {
                                            continue;
                                        }

                                        echo '
                                            <tr>
                                                <td>' . $num . '</td>
                                                <td class="text-center">
                                                    ' . $row->package_name . br() . '
                                                </td>
                                                <td class="text-right">' . adv_accounting($row->maintain_personal_pv) . '</td>
                                                <td class="text-right">' . adv_accounting($row->maintain_group_pv) . '</td>
                                                <td class="text-center">' . adv_accounting($row->maintain_period) . ' ' . lang('month') . '</td>
                                            </tr>';
                                        $num++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>