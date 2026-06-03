<?php
$the_member         = (!empty($member_other) && $member_other->type == MEMBER) ? $member_other : $member;
$as_admin           = as_administrator($the_member);
$as_member          = as_member($the_member);
$as_stockist        = $the_member->as_stockist;
$as_staff           = ($staff = an_get_current_staff()) ? true : false;
$as_mine            = ($the_member->id == $member->id) ? true : false;

$p_member_name      = $the_member->name;
$p_member_user      = $the_member->username;
$p_status           = 'Member';
$p_class            = 'default';
if ($as_admin) {
    $p_status       = 'Admin';
    $p_class        = 'danger';
}
if ($the_member->type == 1 && $as_staff) {
    $p_member_name  = $staff->name;
    $p_member_user  = $staff->username;
    if ($staff->id > 1) {
        $p_status   = 'Staff';
        $p_class    = 'success';
    }
}



$folder_path = BE_IMG_PATH . 'icons/avatar.png';
$avatar = '';

if ($the_member->photo && (file_exists(PROFILE_IMG_PATH . $the_member->photo))) {
    $avatar = PROFILE_IMG . $the_member->photo;
} else {
    $avatar = $folder_path;
}



// $avatar = (empty($the_member->photo) ? 'avatar.png' : $the_member->photo);



$data_profile = array(
    'member_other'  => $member_other,
    'the_member'    => $the_member,
    'staff'         => $as_staff ? an_get_current_staff() : false,
    'as_admin'      => $as_admin,
    'as_member'     => $as_member,
    'as_staff'      => $as_staff,
    'as_mine'       => $as_mine,
);
?>

<div class="header d-flex align-items-center" style="min-height: 350px; background-image: url(<?php echo BE_IMG_PATH . 'bg-profile.jpg'; ?>); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid mt--9">
        <div class="row">
            <div class="col-lg-7 col-md-10">
                <h1 class="display-4 text-white mb-0"><?php echo ucwords(strtolower($p_member_name)); ?></h1>
                <p class="text-white mt-0 mb-2">This is your profile page.</p>
            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--9">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="mb-0">Edit profile </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php $this->load->view(VIEW_BACK . 'member/profiledetail/personal_info', $data_profile); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-profile d-none d-lg-inline-block">
                <img src="<?php echo BE_IMG_PATH . 'bg-profile.jpg'; ?>" alt="Image placeholder" class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img src="<?php echo $avatar; ?>" class="rounded-circle profile-photo" style="width: 100px; height:100px;" id="profile-photo">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-sm btn-info  mr-4 profile-photo">Photo</a>
                        <a href="#" class="btn btn-sm btn-<?php echo $p_class; ?> float-right"><?php echo $p_status; ?></a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="text-center">
                        <h5 class="h3 mb-0"><?php echo ucwords(strtolower($p_member_name)); ?></h5>
                        <h5 class="h4 text-info"><?php echo $p_member_user; ?></h5>
                    </div>
                </div>
                <div class="card-footer text-center btn-profile-photo" style="display: none;">
                    <input type="file" accept="image/x-png,image/jpeg" name="profile_img" id="profile_img" class="d-none">
                    <button class="btn btn-primary" id="do_save_profile_photo" data-url="<?php echo base_url('member/changeprofilephoto/' . an_encrypt($the_member->id)); ?>">Simpan Foto Profile</button>
                </div>
            </div>
            <?php if ($as_member) { ?>
                <?php $photo_idcard = false; ?>
                <?php if ( $photo_idcard ) { ?>
                    <div class="card">
                        <div class="card-header pb-2">
                            <h5 class="h3 mb-0"><?php echo lang('reg_foto_ktp'); ?></h5>
                        </div>
                        <div class="card-body p-2">
                            <?php $file_src   = ASSET_PATH . 'backend/img/no_image.jpg'; ?>
                            <div class="thumbnail text-center mb-1">
                                <?php $idcard_src = an_idcard_image($the_member->idcard_img); ?>
                                <a href="<?php echo $idcard_src; ?>" class="btn btn-outline-info btn-tooltip px-0 py-0 mb-2" onclick="window.open(this.href,'targetWindow',
                                       `toolbar=no,
                                        location=no,
                                        status=no,
                                        menubar=no,
                                        scrollbars=yes,
                                        resizable=yes,
                                        width=600,
                                        height=400`); return false;" title="<?php echo $the_member->name; ?>">
                                    <img class="img-thumbnail" id="idcard_thumbnail" width="100%" src="<?php echo $idcard_src; ?>" style="cursor: pointer;">
                                </a>
                                <div class="caption">
                                    <p class="text-muted font-weight-bold mb-0" style="font-size: 14px">Format Foto ( jpg, jpeg, png ) dan Ukuran Max 2 MB</p>
                                    <div class="img-information" style="display: none;">
                                        <i class="ni ni-album-2 mr-1" id="type_img_thumbnail"></i>
                                        <span id="size_img_thumbnail"></span>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="idcard_file" id="idcard_file" class="form-control file-image" accept="image/x-png,image/jpeg">
                            <div class="text-center btn-idcard-photo" style="display: none;">
                                <hr class="my-3" />
                                <button type="button" class="btn btn-primary bg-gradient-default mb-2" id="do_save_idcard_photo" data-url="<?php echo base_url('member/changeidcardphoto/' . an_encrypt($the_member->id)); ?>"><?php echo lang('save') . ' ' . lang('reg_foto_ktp'); ?></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
                <?php if( $the_member->type_status == TYPE_STATUS_RESELLER ){ ?>
                    <div class="card">
                        <div class="card-header pb-2">
                            <h5 class="h3 mb-0">Logo Reseller</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="thumbnail text-center mb-1">
                                <?php $logo_src = an_logo_image($the_member->logo_img); ?>
                                <a href="<?php echo $logo_src; ?>" class="btn btn-outline-info btn-tooltip px-0 py-0 mb-2" onclick="window.open(this.href,'targetWindow',
                                       `toolbar=no,
                                        location=no,
                                        status=no,
                                        menubar=no,
                                        scrollbars=yes,
                                        resizable=yes,
                                        width=600,
                                        height=400`); return false;" title="<?php echo $the_member->name; ?>">
                                    <img class="img-thumbnail" id="logo_thumbnail" width="100%" src="<?php echo $logo_src; ?>" style="cursor: pointer;">
                                </a>
                                <div class="caption">
                                    <p class="text-muted font-weight-bold mb-0" style="font-size: 14px">Format Foto ( jpg, jpeg, png ) dan Ukuran Max 2 MB</p>
                                    <div class="img-information" style="display: none;">
                                        <i class="ni ni-album-2 mr-1" id="type_img_thumbnail"></i>
                                        <span id="size_img_thumbnail"></span>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="logo_file" id="logo_file" class="form-control file-image" accept="image/x-png,image/jpeg">
                            <div class="text-center btn-logo-image" style="display: none;">
                                <hr class="my-3" />
                                <button type="button" class="btn btn-primary bg-gradient-default mb-2" id="do_save_logo_image" data-url="<?php echo base_url('member/changelogoimg/' . an_encrypt($the_member->id)); ?>"><?php echo lang('save') . ' Logo'; ?></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0">Ganti Password</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($is_admin) {
                            $this->load->view(VIEW_BACK . 'member/profiledetail/password_member', $data_profile);
                        } else {
                            $this->load->view(VIEW_BACK . 'member/profiledetail/password_mine', $data_profile);
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>