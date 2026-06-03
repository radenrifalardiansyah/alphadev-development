<div class="card login-card kd-content" data-name="<?php echo $this->security->get_csrf_token_name(); ?>" data-code="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="row no-gutters">
        <div class="col-md-7" style="background-color: #f5f5f5;">
            <img src="<?php echo ASSET_PATH; ?>auth/images/pages/forgot.png" alt="login" class="login-card-img">
        </div>
        <div class="col-md-5">
            <div class="card-body pt-4 pb-3">
                <div class="card-title">
                    <center><span class="text-muted"><small><?php echo COMPANY_NAME; ?></small></span></center>
                    <p class="mb-0 mt-3"><i class="fa fa-lock"></i> Lupa Password</p>
                </div>
                <p class="login-card-description mb-4">Pastikan Username & Email anda match!</p>
                <form class="validate-form forget-form" method="post" action="<?php echo $action; ?>">
                    <div class="alert alert-danger error-validate" role="alert" style="line-height: 20px; text-align: center; display: none;">
                        <i class="mdi mdi-information-outline"></i>
                        <span style="font-size:12px;"> Ada beberapa kesalahan, silahkan cek formulir di bawah !</span>
                    </div>

                    <div class="alert alert-success success-validate" role="alert" style="line-height: 20px; text-align: center; display: none;">
                        <i class="mdi mdi-information-outline"></i>
                        <span style="font-size:12px;"> </span>
                    </div>

                    <div class="form-group has-icon-left">
                        <label for="username" class="sr-only">Username</label>
                        <div class="input-group">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            <div class="form-control-icon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-icon-left">
                        <label for="username" class="sr-only">Email</label>
                        <div class="input-group">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email akun anda">
                            <div class="form-control-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                    <input type="submit" name="forget" id="forget" class="btn btn-block login-btn mb-4" type="button" value="Submit">
                </form>

                <a href="<?php echo base_url('login'); ?>" class="forgot-password-link"><?php echo lang('back'); ?> Login</a>
                <nav class="login-card-footer-nav mt-5">
                    <a href="<?php echo base_url(); ?>"><?php echo COMPANY_NAME; ?> &copy; 2021</a>
                </nav>
            </div>
        </div>
    </div>
</div>