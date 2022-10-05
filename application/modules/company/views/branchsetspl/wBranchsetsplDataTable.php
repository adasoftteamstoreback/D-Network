<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>						
                        <?php endif; ?>   
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?=language('company/warehouse/warehouse','ลำดับ')?></th>
                        <th nowrap class="xCNTextBold" style="text-align:center;"><?=language('company/warehouse/warehouse','ผู้จำหน่าย')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?=language('company/warehouse/warehouse','สถานะ')?></th>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?=language('common/main/main','tCMNActionDelete')?></th>
						<?php endif; ?>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaEdit'] == 1 || $aAlwEvent['tAutStaRead'] == 1))  : ?>
                            <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?=language('common/main/main','tCMNActionEdit')?></th>
                        <?php endif; ?>
                    </tr>
				</thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                            <tr class="text-center xCNTextDetail2" data-code="<?=$aValue['FTSplCode']?>" data-bchcode='<?=$aValue['FTBchCode']?>' data-name="<?=$aValue['FTSplName']?>">
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <label class="fancy-checkbox">
                                            <input id="ocbListItem<?php echo $key?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                            <span>&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td class="text-center"><?= $aValue['rtRowID'] ?></td>
                                <td class="text-left"><?= $aValue['FTSplName']?></td>
                                <?php 
                                    if($aValue['FTStaUse'] == 1){
                                        $tTextStaUse = 'ใช้งาน';
                                    }else{
                                        $tTextStaUse = 'ไม่ใช้งาน';
                                    }
                                ?>
                                <td class="text-left"><?=$tTextStaUse?></td>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <img class="xCNIconTable xCNIconDelete" onClick="JSxBranchSetsplDelete('<?=$aValue['FTSplCode']?>','<?=$aValue['FTBchCode']?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')">
                                    </td>
                                <?php endif; ?>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaEdit'] == 1 || $aAlwEvent['tAutStaRead'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable xCNIconEdit" onClick="JSxBranchSetsplPageEdit('<?=$aValue['FTBchCode']?>','<?=$aValue['FTSplCode']?>')">
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='99'><?=language('common/main/main', 'tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
			</table>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-md-6">
		<p><?= language('common/main/main','tResultTotalRecord')?> <?= $aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPagespl btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvClickPage('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?> 
                <?php
                    if($nPage == $i){
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    }else{
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <button onclick="JSvClickPage('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvClickPage('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- เพิ่มข้อมูล -->
<div class="modal fade" id="odvModalInsertSetSpl">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?= language('common/main/main', 'เพิ่มข้อมูล')?></label>
			</div>
			<div class="modal-body">

                <!--ผู้จำหน่าย-->
				<div class="form-group">
					<label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTSUPFrom');?></label>
					<div class="input-group">
						<input type="text" class="form-control xCNHide" id="oetBrowseSetSPLCode" name="oetBrowseSetSPLCode" value="">
                        <input type="text" class="form-control xCNHide" id="oetBrowseSetSPLCodeOld" name="oetBrowseSetSPLCodeOld" value="">
						<input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTSUPFrom');?>" class="form-control xWPointerEventNone" id="oetBrowseSetSPLName" name="oetBrowseSetSPLName" value="" readonly>
						<span class="input-group-btn">
							<button id="obtBrowseSetSPL" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
						</span>
					</div>
				</div>

                <!--สถานะใช้งาน-->
				<div class="form-group">
					<div class="validate-input">
						<label class="fancy-checkbox">
							<input type="checkbox" id="ocbSetSPLUse" name="ocbSetSPLUse" checked value="1">
							<span>สถานะใช้งาน</span>
						</label>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn xCNBTNPrimery" onclick="JSxBranchSetSPLInsert()">
        			<?= language('common/main/main', 'tModalConfirm')?>
				</button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
        			<?= language('common/main/main', 'tModalCancel')?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- ลบข้อมูล -->
<div class="modal fade" id="odvModalDelBranchspl">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery">
        			<?= language('common/main/main', 'tModalConfirm')?>
				</button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
        			<?= language('common/main/main', 'tModalCancel')?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- ลบข้อมูลหลายตัว -->
<div id="odvModalDelDocMultipleSpl" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('ducument').ready(function() {

        //ลบข้อมูล
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
				JSxPaseCodeDelInModalsetSpl();
			}else{
				var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
				if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
					obj.push({"nCode": nCode, "tName": tName });
					localStorage.setItem("LocalItemData",JSON.stringify(obj));
					JSxPaseCodeDelInModalsetSpl();
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
					JSxPaseCodeDelInModalsetSpl();
				}
			}
			JSxShowButtonChoose();
		})

        $('#odvModalDelDocMultipleSpl #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSoSetSPLDelDocMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

	});

    function JSxPaseCodeDelInModalsetSpl() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == '') {} else {
            var tTextCode = '';
            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tTextCode += aArrayConvert[0][$i].nCode;
                tTextCode += ' , ';
            }
            $('#odvModalDelDocMultipleSpl #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvModalDelDocMultipleSpl #ohdConfirmIDDelMultiple').val(tTextCode);
        }
    }
    
    //ลบเอกสาร หลายตัว
    function JSoSetSPLDelDocMultiple(){
        var aDataDelMultiple    = $('#odvModalDelDocMultipleSpl #ohdConfirmIDDelMultiple').val();
        var aTextsDelMultiple   = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
        var aDataSplit          = aTextsDelMultiple.split(" , ");
        var nDataSplitlength    = aDataSplit.length;
        var aNewIdDelete        = [];
        for ($i = 0; $i < nDataSplitlength; $i++) {
            aNewIdDelete.push(aDataSplit[$i]);
        }
        if (nDataSplitlength > 1) {
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "branchSettingSPLEventDeleteMulti",
                data    : {'tDataDocNo' : aNewIdDelete , 'tBchCode' : '<?=@$tBchCode?>' },
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    console.log(oResult);
                    var aReturn = JSON.parse(oResult);
                    if (aReturn['nStaEvent'] == '1') {
                        $('#odvModalDelDocMultipleSpl').modal('hide');
                        $('#ohdConfirmIDDelMultiple').empty();
                        localStorage.removeItem('LocalItemData');
                        setTimeout(function () {
                            JSvBranchSetsplDataTable(1)
                        }, 500);
                    } else {
                        JCNxCloseLoading();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //ลบข้อมูล รายการเดียว
    function JSxBranchSetsplDelete(tSplCode, tBchCode, tYesOnNo){
        $('#odvModalDelBranchspl').modal('show');
        $('#odvModalDelBranchspl  #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ' ผู้จำหน่าย ' + tSplCode  + tYesOnNo);
        $('#odvModalDelBranchspl  #osmConfirm').on('click', function (evt) {

            $.ajax({
                type    : "POST",
                url     : "branchSettingSPLEventDelete",
                data    : { 'tSplCode': tSplCode , 'tBchCode': tBchCode },
                cache   : false,
                success : function (oResult) {
                    var aReturn = JSON.parse(oResult);
                    if (aReturn['nStaEvent'] == '1') {
                        $('#odvModalDelBranchspl').modal('hide');
                        $('#ospConfirmDelete').empty();
                        localStorage.removeItem('LocalItemData');
                        setTimeout(function () {
                            JSvBranchSetsplDataTable(1)
                        }, 500);
                    } else {
                        JCNxCloseLoading();
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        });
    }

	//เลือกผู้จำหน่าย
    $('#obtBrowseSetSPL').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseSPLOption = oSetBrowseSPL({
                'tReturnInputCode'  : 'oetBrowseSetSPLCode',
                'tReturnInputName'  : 'oetBrowseSetSPLName'
            });
            JCNxBrowseData('oBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

	var oSetBrowseSPL = function(poReturnInput) {
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var oOptionReturn = {
            Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
            Table   : {Master:'TCNMSpl', PK:'FTSplCode'},
            Join    : {
                Table   : ['TCNMSpl_L'],
                On      : ['TCNMSpl.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits]
            },
            GrideView   :{
                ColumnPathLang  : 'supplier/supplier/supplier',
                ColumnKeyLang   : ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize     : ['15%', '75%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName'],
                DataColumnsFormat: ['', ''],
                DisabledColumns : [],
                Perpage         : 10,
                OrderBy         : ['TCNMSpl.FDCreateOn DESC']
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMSpl.FTSplCode"],
                Text        : [tInputReturnName, "TCNMSpl_L.FTSplName"]
            }
        }
        return oOptionReturn;
    };

	//เพิ่มผู้จำหน่าย
	function JSxBranchSetSPLInsert(){
		if($('#oetBrowseSetSPLCode').val() == ''){
			$('#oetBrowseSetSPLName').focus();
			return;
		}
        
        if($("#ocbSetSPLUse:checked").val() == 1){
            nValue = 1;
        }else{
            nValue = 2;
        }
        
        $.ajax({
            type    : "POST",
            url     : "branchSettingSPLEventAdd",
            data    : {
                'tBchCode'      : '<?=@$tBchCode?>' , 
                'tAgnCode'      : '<?=@$tAgnCode?>' ,
				'tSPLCode'		: $('#oetBrowseSetSPLCode').val(),
                'tSPLCodeOld'   : $('#oetBrowseSetSPLCodeOld').val(),
				'tSPLUse'		: nValue
            },
            cache: false,
            Timeout: 5000,
            success: function (oResult) {
                console.log(tResult);

                var tResult = JSON.parse(oResult);
                if(tResult.nStatus == 0){
                    $('#odvModalInsertSetSpl').modal('hide');

                    setTimeout(function(){
                        JSvBranchSetsplDataTable(1);
                    }, 800);
                }else{
                    alert('พบข้อมูลซ้ำกรุณาลองใหม่อีกครั้ง');
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
	}
</script>
