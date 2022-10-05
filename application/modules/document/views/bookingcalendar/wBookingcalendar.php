<style>
	.xWDrpDwnMenuSaveBooking {
		margin-left: -55px;
		top: -80px;
	}
</style>

<!-- ตารางนัดหมาย -->
<input id="oetBKStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetBKCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div id="odvBKMainMenu" class="main-menu">
	<div class="xCNMrgNavMenu">
		<div class="xCNavRow" style="width:inherit;">
			<div class="xCNBKMaster row">
				<div class="col-xs-12  col-sm-8 col-md-8 col-lg-8">		
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docBookingCalendar/0/0');?>
						<li id="oliBKTitle"     class="xCNLinkClick" onclick="JSvBKCallPageList('')"><?= language('document/bookingcalendar/bookingcalendar','tBKTitle')?></li>
                        <li id="oliBKTitleAdd"  class="active"><a href="javascrip:;"><?= language('document/bookingcalendar/bookingcalendar','tBKTitleAdd')?></a></li>
						<li id="oliBKTitleEdit" class="active"><a href="javascrip:;"><?= language('document/bookingcalendar/bookingcalendar','tBKTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-10">
					<button  type="button" id="obtSubmitInvExpExcel" class="btn xCNBTNDefult xCNBTNDefult2Btn xCNHide" onclick="JSvBKExcel()"><?= language('movement/movement/movement', 'tMMTExportExcel') ?> </button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="xCNMenuCump" id="odvMenuCump">
	&nbsp;
</div>
<div class="main-content" id="odvMainContent" style="background-color: #F0F4F7;">    
	<div id="odvContentBK"></div>
</div>


<!--เปิดหน้าจอตารางงาน-->
<div id="odvModalPopupBookingCalendar" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 4000; display: none;">
    <div class="modal-dialog modal-lg" style="width:70%">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('document/bookingcalendar/bookingcalendar','tBKTitle') ?></label>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80px; background: #FFF;"></div>
            <div class="modal-footer">
				
				<!--บันทึก-->
				<button id="osmSaveBooking" data-eventclick='save' type="button" class="btn xCNBTNPrimery"><?= language('common/main/main', 'tSave') ?></button>

				<!--นัดหมาย-->
				<button id="osmConfirmBooking" data-eventclick='booking' type="button" class="btn xCNBTNPrimery"><?= language('common/main/main', 'ยืนยันนัดหมาย') ?></button>

				<!--ยกเลิก-->
                <button id="osmCancelBooking" type="button" class="btn xCNBTNDefult xCNBTNDefult2Btn"  data-eventclick='cancel'><?= language('common/main/main', 'tModalAdvClose'); ?></button>

				<!--ปิด-->
                <button type="button" class="btn xCNBTNDefult xCNCloseModal" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jBookingcalendar.php') ?>
