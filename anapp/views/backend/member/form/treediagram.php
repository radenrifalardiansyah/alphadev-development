<?php
$access_staff       = FALSE;
$access_add_member  = TRUE;
if ( $getstaff = an_get_current_staff() ) {
    if ($getstaff->access == 'partial') {
        $access_staff   = TRUE;
        $role_staff     = array();
        if ($getstaff->role) {
            $role_staff = $getstaff->role;
        }

        foreach (array(STAFF_ACCESS2) as $val) {
            if (empty($role_staff) || !in_array($val, $role_staff)){
                $access_add_member = FALSE;
            }
        }
    }
}
?>

<!-- Begin Table Responsive Tree Diagram -->
<div class="table-responsives">
    <ul class="tree-table">
        <li>
            <!-------------------------------------------------------------------------------------------------------->
            <!-- =================== Parent Section =============================================================== -->
            <!-------------------------------------------------------------------------------------------------------->
            <?php if( !empty($member_other) ): ?>

                <?php if( $member_other->type == ADMINISTRATOR): ?>

                    <!-- If View Tree of Member Login -->
                    <?php echo an_avatar($member->id, 'photo-me', 0, true); ?>

                <?php elseif($member_other->id == $member->id): ?>

                    <!-- If View Tree of Member Login -->
                    <?php echo an_avatar($member->id, '', $member->sponsor, true); ?>

                <?php else: ?>

                    <?php if( $is_down ): ?>

                        <!-- If View Tree of Member Login Downline -->
                        <?php if ( !$access_staff ): ?>
                            <div>
                                <?php $member_id = an_encrypt($member->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $member_id); ?>">
                                    <?php echo an_avatar($member->id, '', 0, true); ?>
                                </a><hr style="margin-bottom: 20px; border: none; border-bottom: 2px dotted #CCC;" />
                            </div>
                        <?php endif; ?>

                        <?php if( $is_down ): ?>
                            <?php $member_other_parent = an_encrypt($member_other->parent); ?>
                            <?php if ( $access_staff ): ?>
                                <?php if ( $member_other->parent > 8 ): ?>
                                    <p><a href="<?php echo base_url('member/tree/' . $member_other_parent); ?>" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-arrow-circle-up"></i> Upline</a></p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p><a href="<?php echo base_url('member/tree/' . $member_other_parent); ?>" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-arrow-circle-up"></i> Upline</a></p>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php echo an_avatar($member_other->id, 'photo-me', $member_other->sponsor, true); ?>

                    <?php else: ?>

                        <!-- If View Tree of Member Login -->
                        <?php echo an_avatar($member->id, 'photo-me', $member->sponsor, true); ?>

                    <?php endif ?>

                <?php endif ?>

            <?php else: ?>
                <!-- If View Tree of Member Login -->
                <?php echo an_avatar($member->id, 'photo-me', $member->sponsor, true); ?>

            <?php endif ?>
            <!-------------------------------------------------------------------------------------------------------->

            <ul class="child-1">
                <!---------------------------------------------------------------------------------------------------->
                <!-- =================== Child Level 1 ============================================================ -->
                <!---------------------------------------------------------------------------------------------------->
                <?php
                    $id_member_p    = ( !empty($member_other) && $is_down ? $member_other->id : $member->id );
                    $downleft       = an_downline($id_member_p, POS_LEFT);
                    $downright      = an_downline($id_member_p, POS_RIGHT);
                ?>

                <li>
                    <!------------------------------------>
                    <!-- Left Position ------------------->
                    <!------------------------------------>
                    <?php if( !empty($downleft) ): ?>
                        <?php $downleft_id = an_encrypt($downleft->id); ?>
                        <a href="<?php echo base_url('member/tree/' . $downleft_id); ?>">
                            <?php echo an_avatar($downleft->id, '', $downleft->sponsor); ?>
                        </a>
                    <?php else: ?>
                        <?php if( $access_add_member ): ?>
                            <!-- Available To Add New Member -->
                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member_p); ?>" data-position="<?php echo POS_LEFT?>">
                                <div class="photo-wrapper">
                                    <div class="photo-content">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-available">Available</div>
                                    <div class="photo-name-available2"><span>New Member</span></div>
                                    <?php echo an_node(1,true); ?>
                                </div>
                            </a>
                        <?php else: ?>
                            <!-- Not Available To Add New Member -->
                            <div class="photo-wrapper">
                                <div class="photo-content">
                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                </div>
                                <div class="photo-name-notavailable">Not Available</div>
                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                <?php echo an_node(1,true); ?>
                            </div>
                        <?php endif?>
                    <?php endif?>
                    <!------------------------------------>

                    <ul class="child-2">
                        <!---------------------------------------------------------------------------------------------->
                        <!-- =================== Child Level 2 - Left Position ====================================== -->
                        <!---------------------------------------------------------------------------------------------->
                        <?php
                            $id_member_c1   = ( !empty($downleft) ? $downleft->id : '' );
                            $downleftL      = an_downline($id_member_c1, POS_LEFT);
                            $downrightL     = an_downline($id_member_c1, POS_RIGHT);
                        ?>

                        <li>
                            <!------------------------------------>
                            <!-- Left Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downleftL) ): ?>
                                <?php $downleftL_id = an_encrypt($downleftL->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downleftL_id); ?>">
                                    <?php echo an_avatar($downleftL->id, '', $downleftL->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downleft) && $access_add_member ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member_c1); ?>" data-position="<?php echo POS_LEFT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo an_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo an_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Left Position ============================== -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member      = ( !empty($downleftL) ? $downleftL->id : '' );
                                    $downleftLL     = an_downline($id_member, POS_LEFT);
                                    $downleftLL_chL = ( !empty($downleftLL) ? an_downline( an_isset($downleftLL->id, 0), POS_LEFT) : '' );
                                    $downleftLR     = an_downline($id_member, POS_RIGHT);
                                ?>

                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downleftLL) ): ?>
                                        <?php $downleftLL_id = an_encrypt($downleftLL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftLL_id); ?>">
                                            <?php echo an_avatar($downleftLL->id, '', $downleftLL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftL) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downleftLR) ): ?>
                                        <?php $downleftLR_id = an_encrypt($downleftLR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftLR_id); ?>">
                                            <?php echo an_avatar($downleftLR->id, '', $downleftLR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftL) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Right Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downrightL) ): ?>
                                <?php $downrightL_id = an_encrypt($downrightL->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downrightL_id); ?>">
                                    <?php echo an_avatar($downrightL->id, '', $downrightL->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downleft) && $access_add_member ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member_c1); ?>" data-position="<?php echo POS_RIGHT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo an_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo an_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Right Position ============================= -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member          = ( !empty($downrightL) ? $downrightL->id : '' );
                                    $downrightLL        = an_downline($id_member, POS_LEFT);
                                    $downrightLR        = an_downline($id_member, POS_RIGHT);
                                ?>

                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downrightLL) ): ?>
                                        <?php $downrightLL_id = an_encrypt($downrightLL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightLL_id); ?>">
                                            <?php echo an_avatar($downrightLL->id, '', $downrightLL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightL) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downrightLR) ): ?>
                                        <?php $downrightLR_id = an_encrypt($downrightLR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightLR_id); ?>">
                                            <?php echo an_avatar($downrightLR->id, '', $downrightLR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightL) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>
                        <!-------------------------------------------------------------------------------------------------------->
                    </ul>
                </li>

                <li>
                    <!------------------------------------>
                    <!-- Right Position ------------------>
                    <!------------------------------------>
                    <?php if( !empty($downright) ): ?>
                        <?php $downright_id = an_encrypt($downright->id); ?>
                        <a href="<?php echo base_url('member/tree/' . $downright_id); ?>">
                            <?php echo an_avatar($downright->id, '', $downright->sponsor); ?>
                        </a>
                    <?php else: ?>
                        <?php if( $access_add_member ): ?>
                            <!-- Available To Add New Member -->
                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member_p); ?>" data-position="<?php echo POS_RIGHT?>">
                                <div class="photo-wrapper">
                                    <div class="photo-content">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-available">Available</div>
                                    <div class="photo-name-available2"><span>New Member</span></div>
                                    <?php echo an_node(1,true); ?>
                                </div>
                            </a>
                        <?php else: ?>
                            <!-- Not Available To Add New Member -->
                            <div class="photo-wrapper">
                                <div class="photo-content">
                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                </div>
                                <div class="photo-name-notavailable">Not Available</div>
                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                <?php echo an_node(1,true); ?>
                            </div>
                        <?php endif?>
                    <?php endif?>

                    <ul class="child-2">
                        <!---------------------------------------------------------------------------------------------->
                        <!-- =================== Child Level 2 - Right Position ===================================== -->
                        <!---------------------------------------------------------------------------------------------->
                        <?php
                            $id_member_c1   = ( !empty($downright) ? $downright->id : '' );
                            $downleftR      = an_downline($id_member_c1, POS_LEFT);
                            $downrightR     = an_downline($id_member_c1, POS_RIGHT);
                        ?>

                        <li>
                            <!------------------------------------>
                            <!-- Left Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downleftR) ): ?>
                                <?php $downleftR_id = an_encrypt($downleftR->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downleftR_id); ?>">
                                    <?php echo an_avatar($downleftR->id, '', $downleftR->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downright) && $access_add_member ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member_c1); ?>" data-position="<?php echo POS_LEFT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo an_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo an_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Left Position ============================== -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member      = ( !empty($downleftR) ? $downleftR->id : '' );
                                    $downleftRL     = an_downline($id_member, POS_LEFT);
                                    $downleftRR     = an_downline($id_member, POS_RIGHT);
                                ?>
                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downleftRL) ): ?>
                                        <?php $downleftRL_id = an_encrypt($downleftRL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftRL_id); ?>">
                                            <?php echo an_avatar($downleftRL->id, '', $downleftRL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftR) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downleftRR) ): ?>
                                        <?php $downleftRR_id = an_encrypt($downleftRR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftRR_id); ?>">
                                            <?php echo an_avatar($downleftRR->id, '', $downleftRR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftR) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Right Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downrightR) ): ?>
                                <?php $downrightR_id = an_encrypt($downrightR->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downrightR_id); ?>">
                                    <?php echo an_avatar($downrightR->id, '', $downrightR->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downright) && $access_add_member ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member_c1); ?>" data-position="<?php echo POS_RIGHT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo an_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo an_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Right Position ============================= -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member          = ( !empty($downrightR) ? $downrightR->id : '' );
                                    $downrightRL        = an_downline($id_member, POS_LEFT);
                                    $downrightRR        = an_downline($id_member, POS_RIGHT);
                                ?>

                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downrightRL) ): ?>
                                        <?php $downrightRL_id = an_encrypt($downrightRL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightRL_id); ?>">
                                            <?php echo an_avatar($downrightRL->id, '', $downrightRL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightR) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downrightRR) ): ?>
                                        <?php $downrightRR_id = an_encrypt($downrightRR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightRR_id); ?>">
                                            <?php echo an_avatar($downrightRR->id, '', $downrightRR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightR) && $access_add_member ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo an_encrypt($id_member); ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo an_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo an_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-------------------------------------------------------------------------------------------------------->
            </ul>
            <!-------------------------------------------------------------------------------------------------------->
        </li>
    </ul>
</div>
<!-- Begin Table Responsive Tree Diagram -->
