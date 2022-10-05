<?php 
    if($aLotDataList['tCode'] == '1'){
        $nCurrentPage = $aLotDataList['nCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>
<input type="hidden" id="ohdDotNo" name="ohdDotNo" value="<?=$tLotNo?>">
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="otbLotDataList" class="table table-striped"> <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>   
                        <?php endif; ?>
                        <th class="text-center xCNTextBold" style="width:40%;"><?= language('service/pdtlot/pdtlot','tLOTBrand')?></th>
                        <th class="text-center xCNTextBold" style="width:40%;"><?= language('service/pdtlot/pdtlot','tLOTModel')?></th>
                        <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                        <th class="text-center xCNTextBold" style="width:5%;"><?= language('service/pdtlot/pdtlot','tLOTDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || ($aAlwEventPdtLot['tAutStaEdit'] == 1 || $aAlwEventPdtLot['tAutStaRead'] == 1))  : ?>
                        <th class="text-center xCNTextBold" style="width:5%;"><?= language('service/pdtlot/pdtlot','tLOTEdit')?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aLotDataList['tCode'] == 1 ):?>
                        <?php foreach($aLotDataList['aItems'] AS $nKey => $aValue):?>
                            <?php 
                                $tPmoDummy = '';
                                if ($aValue['FTPmoCode'] == '') {
                                    $tPmoDummy = 'Dummy';
                                }else{
                                    $tPmoDummy = $aValue['FTPmoCode'];
                                }
                            ?>
                            <tr class="text-center xCNTextDetail2 otrPdtLot" id="otrPdtLot<?=$nKey?>" data-code="<?=$aValue['FTLotNo']?>" data-bcode="<?=$aValue['FTPbnCode']?>" data-mcode="<?=$tPmoDummy?>">
                                <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                                <td class="text-center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" >
                                        <span>&nbsp;</span>
                                    </label>
                                </td>
                                <?php endif; ?>
                                <td class="text-left"><?=$aValue['FTPbnName']?></td>
                                <td class="text-left"><?=$aValue['FTPmoName']?></td>
                                <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                                <td>
                                    <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSoPdtLotBAMDel('<?=$nCurrentPage?>','<?php echo $aValue['FTLotNo']?>','<?php echo $aValue['FTPbnCode']?>','<?=$aValue['FTPbnName']?>','<?php echo $aValue['FTPmoCode']?>','<?php echo $aValue['FTPmoName']?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')">
                                </td>
                                <?php endif; ?>
                                <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || ($aAlwEventPdtLot['tAutStaEdit'] == 1 || $aAlwEventPdtLot['tAutStaRead'] == 1)) : ?>
                                <td>
                                    <!-- เปลี่ยน -->
                                    <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallDotBAMPageEdit('<?php echo $aValue['FTLotNo']?>','<?php echo $aValue['FTPbnCode']?>','<?php echo $aValue['FTPmoCode']?>')">
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('common/main/main','tMainRptNotFoundDataInDB')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aLotDataList['nAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aLotDataList['nCurrentPage']?> / <?=$aLotDataList['nAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPagePdtLot btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPdtLotClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aLotDataList['nAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ --> 
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
                <button onclick="JSvPdtLotClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aLotDataList['nAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPdtLotClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>


<div class="modal fade" id="odvModalDelPdtLot">
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
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoPdtLotBAMDelChoose('<?=$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('ducument').ready(function(){
    localStorage.removeItem("LocalItemData");
});

$('.ocbListItem').click(function(){
    var tBch  = $(this).parent().parent().parent().data('bch');  //Bch
    var tDotCode = $(this).parent().parent().parent().data('code');  //code
    var tBrandCode = $(this).parent().parent().parent().data('bcode');  //Brand
    var tModelCode = $(this).parent().parent().parent().data('mcode');  //Model
    var tCheckData = tDotCode + tBrandCode + tModelCode;
    $(this).prop('checked', true);
    var LocalItemData = localStorage.getItem("LocalItemData");
    var obj = [];
    if(LocalItemData){
        obj = JSON.parse(LocalItemData);
    }else{ }
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if(aArrayConvert == '' || aArrayConvert == null){
        obj.push({"tCheckData": tCheckData, "tDotCode": tDotCode, "tBrandCode": tBrandCode, "tModelCode": tModelCode });
        localStorage.setItem("LocalItemData",JSON.stringify(obj));
        JSxTextBAMinModal();
    }else{
        var aReturnRepeat = findBAMObjectByKey(aArrayConvert[0],"tCheckData",tCheckData);
        if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
            obj.push({"tCheckData": tCheckData, "tDotCode": tDotCode, "tBrandCode": tBrandCode, "tModelCode": tModelCode });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxTextBAMinModal();
        }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
            localStorage.removeItem("LocalItemData");
            $(this).prop('checked', false);
            var nLength = aArrayConvert[0].length;
            for($i=0; $i<nLength; $i++){
                if(aArrayConvert[0][$i].tCheckData == tCheckData){
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
            JSxTextBAMinModal();
        }
    }
    JSxShowButtonTab2Choose();
})

function JSxTextBAMinModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {} else {
        var tTextCode = '';
        for ($i = 0; $i < aArrayConvert[0].length; $i++) {
            tTextCode += 'data:';
            tTextCode += aArrayConvert[0][$i].tDotCode;
            tTextCode += ' , ';
            tTextCode += aArrayConvert[0][$i].tBrandCode;
            tTextCode += ' , ';
            tTextCode += aArrayConvert[0][$i].tModelCode;
            tTextCode += ' , ';
        }
        $('#ospConfirmDelete').text($('#oetTextComfirmDeleteMulti').val());
        $('#ohdConfirmIDDelete').val(tTextCode);
    }
}

function findBAMObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return 'Dupilcate';
        }
    }
    return 'None';
}

function JSoPdtLotBAMDel(pnPage, tPdtLotCode, tPdtLotBrandCode, tPdtLotBrandName, tPdtLotModelCode, tPdtLotModelName, tYesOnNo) {
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {
        $('#odvModalDelPdtLot').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val()+' ยี่ห้อ '+tPdtLotBrandName+' รุ่น '+tPdtLotModelName + tYesOnNo);
        $('#osmConfirm').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {

                $.ajax({
                    type: "POST",
                    url: "maslotBAMEventDelete",
                    data: { 
                            'tPdtLotCode': tPdtLotCode,
                            'tPdtLotBrandCode': tPdtLotBrandCode,
                            'tPdtLotModelCode': tPdtLotModelCode 
                        },
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var aReturn = $.parseJSON(tResult);

                        if (aReturn['nStaEvent'] == '1') {
                            $('#odvModalDelPdtLot').modal('hide');
                            $('#ospConfirmDelete').empty();
                            localStorage.removeItem('LocalItemData');
                            $('#ospConfirmIDDelete').val('');
                            $('#ohdConfirmIDDelete').val('');
                            setTimeout(function() {
                                if (aReturn["nNumRowLot"] != 0) {
                                    if (aReturn["nNumRowLot"] > 10) {
                                        nNumPage = Math.ceil(aReturn["nNumRowLot"] / 10);
                                        if (pnPage <= nNumPage) {
                                            JSvDotBAMDataTable(pnPage, aReturn['tLotNo']);
                                        } else {
                                            JSvDotBAMDataTable(nNumPage, aReturn['tLotNo']);
                                        }
                                    } else {
                                        JSvDotBAMDataTable(1, aReturn['tLotNo']);
                                    }
                                } else {
                                    JSvDotBAMDataTable(1, aReturn['tLotNo']);
                                }
                            }, 500);
                        } else {
                            // JCNxOpenLoading();
                            // alert(aReturn['tStaMessg']);
                        }
                        JSxLotNavDefult();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }


        });
    }
}

function JSxShowButtonTab2Choose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
        $('#odvDotBAMTableList #oliBtnDeleteAll').addClass('disabled');
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $('#odvDotBAMTableList #oliBtnDeleteAll').removeClass('disabled');
        } else {
            $('#odvDotBAMTableList #oliBtnDeleteAll').addClass('disabled');
        }
    }
}

//Functionality: Event Multi Delete
//Parameters: Event Button Delete All
//Creator: 21/06/2021 Pakkahwat
//Return:  object Status Delete
//Return Type: object
function JSoPdtLotBAMDelChoose(pnPage) {
    //JCNxOpenLoading();

    var aData = $('#ohdConfirmIDDelete').val();
    // var aTexts = aData.substring(0, aData.length - 2);
    // console.log(aTexts)
    var aDataSplit = aData.split("data:");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    for ($i = 0; $i < aDataSplitlength; $i++) {
        if(aDataSplit[$i] == '' || aDataSplit[$i] == null){

        }else{
            aNewIdDelete.push(aDataSplit[$i]);
        }
    }
    if (aDataSplitlength > 1) {

        localStorage.StaDeleteArray = '1';

        $.ajax({
            type: "POST",
            url: "maslotBAMEventDeleteMulti",
            data: { 'tIDCode': aNewIdDelete },
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);
                console.log(aReturn);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDelPdtLot').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ospConfirmIDDelete').val('');
                    $('#ohdConfirmIDDelete').val('');
                    setTimeout(function() {
                        if (aReturn["nNumRowLot"] != 0) {
                            if (aReturn["nNumRowLot"] > 10) {
                                nNumPage = Math.ceil(aReturn["nNumRowLot"] / 10);
                                if (pnPage <= nNumPage) {
                                    JSvDotBAMDataTable(pnPage);
                                } else {
                                    JSvDotBAMDataTable(nNumPage);
                                }
                            } else {
                                JSvDotBAMDataTable(1);
                            }
                        } else {
                            JSvDotBAMDataTable(1);
                        }
                    }, 500);
                } else {
                    JCNxOpenLoading();
                    alert(aReturn['tStaMessg']);
                }
                JSxLotNavDefult();


            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });


    } else {
        localStorage.StaDeleteArray = '0';

        return false;
    }
}


$('#obtSearchBAMDevice').click(function(){
    var tLotCode = $('#oetLotCode').val();
    JCNxOpenLoading();
    JSvDotBAMDataTable(1,tLotCode);
});

function JSvDotBAMDataTable(pnPage, tLotNo) {
    var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
    JCNxOpenLoading();
    var tSMDBchCode = $('#oetPosBchCode').val();
    $.ajax({
        type: "POST",
        url: "maslotBAMDataTable",
        data: {
            tSearchAll: $('#oetSearchBAMDevice').val(),
            nPageCurrent: nPageCurrent,
            tDotNo: tLotNo
        },
        cache: false,
        Timeout: 0,
        success: function(tResult){
            $('#odvBAMContentPage').html(tResult);
            JSxDotBAMNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSvCallPageDotBAMAdd() {
    JCNxOpenLoading();
    var tDotCode = $('#ohdDotNo').val();
    $.ajax({
        type: "POST",
        url: "maslotBAMPageAdd",
        cache: false,
        data: {
            'tDotCode': tDotCode
        },
        timeout: 0,
        success: function(tResult) {
            $('#oliDotBAMTitleAdd').show();
            $('#oliDotBAMTitleEdit').hide();
            $('#odvBtnDotBAMAddEdit').show();
            $('#odvBtnDotBAMSearch').hide();
            $('#odvDotBAMTableList').hide();
            $('#odvBtnDotBAMInfo').hide();
            $('#otbLotDataList').hide();
            $('#odvBAMContentPage').html(tResult);

            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSvCallDotBAMPageEdit(ptLotNo,ptBrandNo,ptModelNo) {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "maslotBAMPageEdit",
        data: { 
                tLotNo: ptLotNo,
                tBrandNo: ptBrandNo,
                tModelNo: ptModelNo
            },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (tResult != '') {
                $('#oliDotBAMTitleAdd').hide();
                $('#oliDotBAMTitleEdit').show();
                $('#odvBtnDotBAMAddEdit').show();
                $('#odvBtnDotBAMSearch').hide();
                $('#odvDotBAMTableList').hide();
                $('#odvBtnDotBAMInfo').hide();
                $('#otbLotDataList').hide();
                $('#odvBAMContentPage').html(tResult);

                JCNxLayoutControll();
                JCNxCloseLoading();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}


</script>