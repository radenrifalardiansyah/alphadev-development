<div class="table-responsive">
    <!-- <div id="message_error" class="alert alert-danger display-hide">
        <button class="close" data-close="alert" type="button"><i class="fa fa-times"></i></button>
        Silahkan cari Username anggota menggunakan form di atas!
    </div> -->

    <ul class="tree-board-table">
        <li>
            <!-------------------------------------------------------------------------------------------------------->
            <!-- =================== Parent Section =============================================================== -->
            <!-------------------------------------------------------------------------------------------------------->

            <?php 
                $id_member_board    = isset($memberboard->id) ? $memberboard->id : 0;
                $board_active       = isset($memberboard->status) ? $memberboard->status : 0;
            ?>

            <?php if( $memberboard && $board_active ): ?>
                <?php if( !empty($member_other) && $is_admin ): ?>
                    <?php echo kd_avatar_board($member_other->id, $board, 'photo-me', 0, $id_member_board, true); ?>
                <?php else: ?>
                    <?php echo kd_avatar_board($member->id, $board, 'photo-me', 0, $id_member_board, true); ?>
                <?php endif ?>
            <?php else: ?>
                <!-- Not Available To Add New Member -->
                <div class="photo-wrapper-board">
                    <div class="photo-content-board">
                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                    </div>
                    <div class="photo-name-notavailable">Not Available</div>
                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                    <?php echo kd_node_board(1,$board,true); ?>
                </div>
            <?php endif ?>
            <!-------------------------------------------------------------------------------------------------------->

            <ul class="child-1">
                <!---------------------------------------------------------------------------------------------------->
                <!-- =================== Child Level 1 ============================================================ -->
                <!---------------------------------------------------------------------------------------------------->
                <?php
                    $downleft           = kd_downline_board($id_member_board, 1, 1, 1);
                    $downcenter         = kd_downline_board($id_member_board, 1, 2, 1);
                    $downright          = kd_downline_board($id_member_board, 1, 3, 1);
                ?>

                <li>
                    <!------------------------------------>
                    <!-- Left Position ------------------->
                    <!------------------------------------>
                    <?php if( !empty($downleft) ): ?>
                        <a href="javascript:;">
                            <?php echo kd_avatar_board($downleft->id, $board, '', $downleft->sponsor); ?>
                        </a>
                    <?php else: ?>
                        <!-- Not Available To Add New Member -->
                        <div class="photo-wrapper-board">
                            <div class="photo-content-board">
                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                            </div>
                            <div class="photo-name-notavailable">Not Available</div>
                            <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                            <?php echo kd_node_board(1,$board,true); ?>
                        </div>
                    <?php endif?>
                    <!------------------------------------>

                    <ul class="child-2">
                        <!---------------------------------------------------------------------------------------------->
                        <!-- =================== Child Level 2 - Left Position ====================================== -->
                        <!---------------------------------------------------------------------------------------------->
                        <?php
                            $downleftL      = kd_downline_board($id_member_board, 2, 1, 1);;
                            $downcenterL    = kd_downline_board($id_member_board, 2, 2, 1);;
                            $downrightL     = kd_downline_board($id_member_board, 2, 3, 1);;
                        ?>

                        <li>
                            <!------------------------------------>
                            <!-- Left Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downleftL) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downleftL->id, $board, '', $downleftL->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Center Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downcenterL) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downcenterL->id, $board, '', $downcenterL->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Right Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downrightL) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downrightL->id, $board, '', $downrightL->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>
                        <!-------------------------------------------------------------------------------------------------------->
                    </ul>
                </li>

                <li>
                    <!------------------------------------>
                    <!-- Center Position ------------------>
                    <!------------------------------------>
                    <?php if( !empty($downcenter) ): ?>
                        <a href="javascript:;">
                            <?php echo kd_avatar_board($downcenter->id, $board, '', $downcenter->sponsor); ?>
                        </a>
                    <?php else: ?>
                        <!-- Not Available To Add New Member -->
                        <div class="photo-wrapper-board">
                            <div class="photo-content-board">
                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                            </div>
                            <div class="photo-name-notavailable">Not Available</div>
                            <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                            <?php echo kd_node_board(1,$board,true); ?>
                        </div>
                    <?php endif?>

                    <ul class="child-2">
                        <!---------------------------------------------------------------------------------------------->
                        <!-- =================== Child Level 2 - Right Position ===================================== -->
                        <!---------------------------------------------------------------------------------------------->
                        <?php
                            $downleftC      = kd_downline_board($id_member_board, 2, 4, 1);
                            $downcenterC    = kd_downline_board($id_member_board, 2, 5, 1);
                            $downrightC     = kd_downline_board($id_member_board, 2, 6, 1);
                        ?>

                        <li>
                            <!------------------------------------>
                            <!-- Left Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downleftC) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downleftC->id, $board, '', $downleftC->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Center Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downcenterC) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downcenterC->id, $board, '', $downcenterC->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Right Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downrightC) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downrightC->id, $board, '', $downrightC->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>
                    </ul>
                </li>

                <li>
                    <!------------------------------------>
                    <!-- Right Position ------------------>
                    <!------------------------------------>
                    <?php if( !empty($downright) ): ?>
                        <a href="javascript:;">
                            <?php echo kd_avatar_board($downright->id, $board, '', $downright->sponsor); ?>
                        </a>
                    <?php else: ?>
                        <!-- Not Available To Add New Member -->
                        <div class="photo-wrapper-board">
                            <div class="photo-content-board">
                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                            </div>
                            <div class="photo-name-notavailable">Not Available</div>
                            <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                            <?php echo kd_node_board(1,$board,true); ?>
                        </div>
                    <?php endif?>

                    <ul class="child-2">
                        <!---------------------------------------------------------------------------------------------->
                        <!-- =================== Child Level 2 - Right Position ===================================== -->
                        <!---------------------------------------------------------------------------------------------->
                        <?php
                            $downleftR      = kd_downline_board($id_member_board, 2, 7, 1);
                            $downcenterR    = kd_downline_board($id_member_board, 2, 8, 1);
                            $downrightR     = kd_downline_board($id_member_board, 2, 9, 1);
                        ?>

                        <li>
                            <!------------------------------------>
                            <!-- Left Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downleftR) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downleftR->id, $board, '', $downleftR->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Center Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downcenterR) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downcenterR->id, $board, '', $downcenterR->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Right Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downrightR) ): ?>
                                <a href="javascript:;">
                                    <?php echo kd_avatar_board($downrightR->id, $board, '', $downrightR->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not Available To Add New Member -->
                                <div class="photo-wrapper-board">
                                    <div class="photo-content-board">
                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-lock.jpg'; ?>" /></div>
                                    </div>
                                    <div class="photo-name-notavailable">Not Available</div>
                                    <!-- <div class="photo-name-notavailable2"><span>Empty</span></div> -->
                                    <?php echo kd_node_board(1,$board,true); ?>
                                </div>
                            <?php endif?>
                            <!------------------------------------>
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
