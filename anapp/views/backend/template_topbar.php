<?php
    $nav_member_name    = $member->name;
    $nav_member_user    = strtoupper($member->username);
    $nav_member_since   = date('M Y', strtotime($member->datecreated));
    if ( $staff = an_get_current_staff() ) {
        $nav_member_name    = $staff->name;
        $nav_member_user    = $staff->username;
        $nav_member_since   = date('M Y', strtotime($staff->datecreated));
    }

    $active_page        = ( $this->uri->segment(1, 0) ? $this->uri->segment(1, 0) : '');
    $active_sub         = ( $this->uri->segment(2, 0) ? $this->uri->segment(2, 0) : '');
    $bg_color_top       = 'navbar-light bg-white';
    if ( strtolower($active_page) == 'profile' ) {
        $title_page     = '<i class="ni ni-circle-08 mr-2"></i> Profile';
        $bg_color_top   = 'navbar-dark bg-default';
    } elseif ( strtolower($active_page) == 'setting' ) {
        $title_page     = '<i class="ni ni-settings mr-2"></i> '. lang('menu_setting');
        $bg_color_top   = 'navbar-dark bg-gradient-info';
    } elseif ( strtolower($main_content) == 'error_404' ) {
        $bg_color_top   = 'navbar-dark bg-gradient-info';
    }
?>

<nav class="navbar navbar-top navbar-expand <?php echo $bg_color_top; ?> border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-light" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
                <?php if ( isset($title_page) && !empty($title_page) ) { ?>
                    <li class="nav-item d-none d-lg-inline-block">
                        <a class="nav-link font-weight-bold" href="#"><?php echo $title_page; ?></a>
                    </li>
                <?php } ?>
            </ul>
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center  ml-md-auto ">
                <li class="nav-item dropdown">
                    <?php
                        $menu_lang  = $this->input->cookie('an_lang');
                        $len_lang   = $menu_lang ? strlen($menu_lang) : 0;
                        if ( $len_lang == 7 ) {
                            $alias_lang = 'EN';
                            $txt_lang   = 'ENGLISH';
                            $img_lang   = BE_IMG_PATH .'us.png';
                            $oth_img    = BE_IMG_PATH .'id.png';
                            $oth_lang   = 'INDONESIA';
                            $oth_link   = 'bahasa';
                        } else {
                            $alias_lang = 'ID';
                            $txt_lang   = 'INDONESIA';
                            $img_lang   = BE_IMG_PATH .'id.png';
                            $oth_img    = BE_IMG_PATH .'us.png';
                            $oth_lang   = 'ENGLISH';
                            $oth_link   = 'english';
                        }
                    ?>
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <div class="media-body ml-2">
                                <img height="15px" src="<?php echo $img_lang ?>" alt="<?php echo $txt_lang; ?>" /> 
                                <span class="mb-0 text-sm font-weight-bold"><?php echo $alias_lang ?></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">
                                <img height="15px" src="<?php echo $img_lang ?>" alt="<?php echo $txt_lang; ?>" /> <?php echo $txt_lang; ?>
                            </h6>
                        </div>
                        <a href="javascript:;" data-lang="<?php echo $oth_link ?>" class="dropdown-item switchlang">
                            <img height="15px" src="<?php echo $oth_img; ?>" style="margin-right: 10px" /> 
                            <?php echo $oth_lang ?>
                        </a>
                    </div>
                </li>
                <?php if ( ! $is_admin ) { ?>
                    <?php $top_bar_total_item = count($this->cart->contents()); ?>
                    <!--
                    <li class="nav-item pr-0">
                        <a class="nav-link btn-tooltip" href="<?php echo base_url('shopping/cart'); ?>"  title="<?php echo lang('menu_shopping_cart') ?>" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="ni ni-bag-17"></i> 
                            <span class="font-weight-bold">
                                <sup id="cart-total-item"><?php echo ( $top_bar_total_item ? $top_bar_total_item : ''); ?></sup>
                            </span>
                        </a>
                    </li>
                    -->
                <?php } ?>
            </ul>
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <div class="media-body ml-2">
                                <i class="ni ni-single-02"></i>
                                <span class="ml-1 mb-0 text-sm font-weight-bold d-none d-lg-inline-block"><?php echo $nav_member_user; ?></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0"><?php echo $nav_member_name; ?></h6>
                        </div>
                        <a href="<?php echo base_url('profile') ?>" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>Profile</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <?php if ( an_is_assuming() ): ?>
                            <a href="<?php echo base_url('backend/revert') ?>" class="dropdown-item text-warning">
                                <i class="fa fa-user-secret"></i>
                                <span>Revert</span>
                            </a>
                        <?php endif ?>
                        <a href="<?php echo base_url('logout') ?>" class="dropdown-item text-danger">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>