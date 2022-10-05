
<style>
    .xCNIconContentAPI  {  width:15px; height:15px; background-color:#e84393; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentDOC  {  width:15px; height:15px; background-color:#ffca28; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentPOS  {  width:15px; height:15px; background-color:#42a5f5; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentSL   {  width:15px; height:15px; background-color:#ff9030; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentWEB  {  width:15px; height:15px; background-color:#99cc33; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentVD   {  width:15px; height:15px; background-color:#dbc559; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentALL  {  width:15px; height:15px; background-color:#ff5733; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentETC  {  width:15px; height:15px; background-color:#92918c; display: inline-block; margin-right: 10px; margin-top: 0px; }

    .xCNTableScrollY{
        overflow-y      : auto; 
    }

    .xCNCheckboxBlockDefault:before{
        background      : #ededed !important;
    }

    .xCNInputBlock{
        background      : #ededed !important;
        pointer-events  : none;
    }

    #ospDetailFooter{
        font-weight     : bold;
    }

</style>
<div class="row">
    <div class="col-xs-8 col-md-4 col-lg-4">
        <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting','tTABAgcCar')?></label>
        <div class="form-group">
            <div class="input-group">
                <input type="text" 
                    class="form-control xCNInputWithoutSingleQuote" 
                    id="oetSearchAllCarInter" 
                    name="oetSearchAllCarInter" 
                    placeholder="<?php echo language('interface/connectionsetting/connectionsetting','tSearch')?>"
                    value="<?=$tSearchAllCarInter;?>">
                <span class="input-group-btn">
                    <button id="oimSearchCarInter" class="btn xCNBtnSearch" type="button">
                        <img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                    </button>
                </span>
            </div>
        </div>
    </div><br>

    <!-- <div class="col-md-8 text-right">                    
        <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
            <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                <?=language('common/main/main','tCMNOption')?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li id="oliBtnDeleteAll" class="disabled">
                    <a data-toggle="modal" data-target="#odvModalDeleteMutirecordCarInter"><?=language('common/main/main','tDelAll')?></a>
                </li>
            </ul>
        </div>
        <button id="obtSMLLayout" name="obtSMLLayout" class="xCNBTNPrimeryPlus" type="button" style="margin-left: 20px; margin-top: 0px;" onclick="JSvCallPageAddCarInter()">+</button>
    </div> -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%" id="otbTableForCheckbox">
                <thead>
					<tr class="xCNCenter">
						<th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('interface/connectionsetting/connectionsetting', 'tUsrCarID'); ?></th>
						<th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tCarOwner'); ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tIOCode'); ?></th>
						<th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tUsrCostCode'); ?></th>
						<th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tVatStatu'); ?></th>
						<th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tVatStaContac'); ?></th>
					</tr>
                </thead>
                <tbody>
                    <?php if($aCarInterData['rtCode'] == 1 ):?>
                        <?php foreach($aCarInterData['raItems'] AS $key=>$aValue){ ?>
                            <tr class="text-center xCNTextDetail2" data-code="<?=$aValue['FTCarRegNo']?>">
								<td style="text-align:center;"><?=$aValue['FTCarRegNo']?></td>
								<td style="text-align:left;"><?=$aValue['FTCstName']?></td>
                                <td style="text-align:left;"><?=$aValue['FTCbaIO']?></td>
								<td style="text-align:left;"><?=$aValue['FTCbaCostCenter']?></td>
								<td style="text-align:left;"><?=$aValue['FTCbaStaTaxName']?></td>
								<td style="text-align:center;"><?=$aValue['FTCbaConSta']?></td>
                            </tr>
                        <?php } ?>
                    <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='100'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
	<!-- เปลี่ยน -->
	<div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aCarInterData['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aCarInterData['rnCurrentPage']?> / <?=$aCarInterData['rnAllPage']?></p>
    </div>
	<!-- เปลี่ยน -->
    <div class="col-md-6">
        <div class="xWPageReasonGrp btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvCarInterClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aCarInterData['rnAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ --> 
                <?php 
                    if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <button onclick="JSvCarInterClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aCarInterData['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvCarInterClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!--Modal Delete Single-->
<div id="odvModalDeleteSingleCarInter" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 7000; display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirmDelete" type="button" class="btn xCNBTNPrimery"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>


<!--Modal Delete Mutirecord-->
<div class="modal fade" id="odvModalDeleteMutirecordCarInter">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
                <span id="ospConfirmDelete"><?=language('common/main/main', 'tModalDeleteMulti')?></span>
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSxDeleteMutirecordCarInter()"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<script>

    // //Functionality : เปลี่ยนหน้า pagenation
    // //Parameters : -
    // //Creator : 15/05/2018 wasin
    // //Return : View
    // //Return Type : View
    function JSvCarInterClickPage(ptPage) {
        var nPageCurrent = '';
        var nPageNew;
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld = $('.xWPageReasonGrp .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWPageReasonGrp .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSvConnectionSettingListCarInter(nPageCurrent);
    }

    // คลังสินค้าที่ยังไม่ตั้งค่า
    $('#oimSearchCarInter').click(function(){
        JCNxOpenLoading();
        JSxCallGetContentCarInter();
    });

    $('#oetSearchAllCarInter').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
            JSxCallGetContentCarInter();
		}
    });

    // Select List Userlogin Table Item
    $(function() {
    $('.ocbListItem').click(function(){
        var nCode = $(this).parent().parent().parent().data('code');  //code
        var tName = $(this).parent().parent().parent().data('name');  //code
        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }else{ }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"nCode": nCode, "tName": tName });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxPaseCodeDelInModal();

        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxPaseCodeDelInModal();

            }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].nCode == nCode){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                JSxPaseCodeDelInModal();
                }
            }
            JSxShowButtonChoose();
        });
    });
</script>

