<style>
    #odvRowDataEndOfBill .panel-heading{
        padding-top     : 10px !important;
        padding-bottom  : 10px !important;
    }
    #odvRowDataEndOfBill .panel-body{
        padding-top     : 0px !important;
        padding-bottom  : 0px !important;
    }
    #odvRowDataEndOfBill .list-group-item {
        padding-left    : 0px !important;
        padding-right   : 0px !important;
        border          : 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color           : #232C3D !important;
        font-weight     : 900;
    }
</style>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbCRVDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','รหัสสินค้า')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ชื่อสินค้า')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','บาร์โค้ด')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','หน่วย')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','จำนวน')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ตู้ฝาก')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ช่อง')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','สถานะ')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','วันที่รับของ')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','เหตุผล')?></th>
                </tr>
            </thead>
            <tbody>
            <?php 
                if($aDataDocDTTemp['rtCode'] == 1):
                    $dGetDataNow    = date('Y-m-d');
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal): 
                        $nKey = $aDataTableVal['FNXsdSeqNo'];

                        if( !empty($aDataTableVal['FDXshDatePick']) ) {
                            $tStatus    = "รับสินค้าแล้ว";
                            $nStaSave   = 0;
                            $tDisable   = "disabled";
                        }else{
                            $tStatus    = "ยังไม่ได้รับ";
                            $nStaSave   = 1;
                            $tDisable   = "";
                        }
                        $tPK = $aDataTableVal['FTBchCode'].$aDataTableVal['FTShpCode'].$aDataTableVal['FTPosCode'].$aDataTableVal['FNXsdLayNo'];

            ?>
                    <tr class="xWPdtItem" 
                        data-bchcode="<?=$aDataTableVal['FTBchCode']?>" 
                        data-shpcode="<?=$aDataTableVal['FTShpCode']?>" 
                        data-poscode="<?=$aDataTableVal['FTPosCode']?>" 
                        data-layno="<?=$aDataTableVal['FNXsdLayNo']?>"  
                        data-seqno="<?=$nKey?>" 
                        data-stasave="<?=$nStaSave?>"
                    >
                        <td class="text-left" style="vertical-align: middle;"><?=$aDataTableVal['FTPdtCode'];?></td>
                        <td class="text-left" style="vertical-align: middle;"><?=$aDataTableVal['FTXsdPdtName'];?></td>
                        <td class="text-left" style="vertical-align: middle;"><?=$aDataTableVal['FTXsdBarCode'];?></td>
                        <td class="text-left" style="vertical-align: middle;"><?=$aDataTableVal['FTPunName'];?></td>
                        <td class="text-right" style="vertical-align: middle;"><?=number_format($aDataTableVal['FCXsdQtyAll'],$nOptDecimalShow);?></td>

                        <?php if( $aDataTableVal['FNSeqLayNo'] == 1 ){ ?>
                            <td class="text-left" style="vertical-align: middle;" rowspan="<?=$aDataTableVal['FNMaxSeqLayNo']?>"><?=$aDataTableVal['FTPosName'];?></td>
                            <td class="text-left" style="vertical-align: middle;" rowspan="<?=$aDataTableVal['FNMaxSeqLayNo']?>"><?=(!empty($aDataTableVal['FTLayName'])?$aDataTableVal['FTLayName']:'Virtual');?></td>
                            <td class="text-left" style="vertical-align: middle;" rowspan="<?=$aDataTableVal['FNMaxSeqLayNo']?>"><?=$tStatus?></td>
                            <td class="text-left" style="min-width: 130px;width: 130px;padding-left: 6px !important;vertical-align: middle;" rowspan="<?=$aDataTableVal['FNMaxSeqLayNo']?>">
                                <div class="form-group" style="min-width: 130px;width: 130px;margin-bottom: 1px;">
                                    <div class="input-group">
                                        <input
                                            class="form-control xCNDatePicker xWKeyDatePick"
                                            type="text"
                                            id="oetCRVDatePick<?=$tPK?>"
                                            name="oetCRVDatePick<?=$tPK?>"
                                            placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'วันที่รับของ'); ?>"
                                            value="<?php if ($aDataTableVal['FDXshDatePick'] != "") {
                                                echo $aDataTableVal['FDXshDatePick'];
                                            } else {
                                                echo $dGetDataNow;
                                            } ?> 
                                            "
                                            <?=$tDisable?>
                                        >
                                        <span class="input-group-btn" >
                                            <button 
                                                data-bchcode="<?=$aDataTableVal['FTBchCode']?>" 
                                                data-shpcode="<?=$aDataTableVal['FTShpCode']?>" 
                                                data-poscode="<?=$aDataTableVal['FTPosCode']?>" 
                                                data-layno="<?=$aDataTableVal['FNXsdLayNo']?>" 
                                                data-seqno="<?=$nKey?>" 
                                                type="button" class="btn xCNBtnDateTime xWCRVBtnDatePick" <?=$tDisable?>><img class="xCNIconCalendar"
                                            ></button>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-left" style="min-width: 150px;width: 150px;padding-left: 6px !important;vertical-align: middle;" rowspan="<?=$aDataTableVal['FNMaxSeqLayNo']?>">
                                <div class="form-group" style="min-width: 150px;width: 150px;margin-bottom: 1px;">
                                    <div class="input-group">
                                        <input class="form-control xCNHide" id="oetCRVRsnCode<?=$tPK?>"name="oetCRVRsnCode<?=$tPK?>" maxlength="5" value="<?=$aDataTableVal['FTRsnCode']?>">
                                        <input
                                            class="form-control xWPointerEventNone"
                                            type="text"
                                            id="oetCRVRsnName<?=$tPK?>"
                                            name="oetCRVRsnName<?=$tPK?>"
                                            placeholder="<?php echo language('document/depositboxreservation/depositboxreservation','เหตุผล'); ?>"
                                            readonly
                                            value="<?=$aDataTableVal['FTRsnName']?>"
                                        >
                                        <span class="input-group-btn">
                                            <button 
                                                data-bchcode="<?=$aDataTableVal['FTBchCode']?>" 
                                                data-shpcode="<?=$aDataTableVal['FTShpCode']?>" 
                                                data-poscode="<?=$aDataTableVal['FTPosCode']?>" 
                                                data-layno="<?=$aDataTableVal['FNXsdLayNo']?>" 
                                                data-seqno="<?=$nKey?>" 
                                                type="button" class="btn xCNBtnBrowseAddOn xWCRVBtnBrowseRsn" <?=$tDisable?>
                                            ><img class="xCNIconFind"></button>
                                        </span>
                                    </div>
                                </div>
                            </td>

                        <?php } ?>
                    </tr>
            <?php 
                    endforeach;
                else:
            ?>
                <tr><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php /*include("script/jDepositBoxReservationPdtAdvTableData.php");*/ ?>

<script>
    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });

    $('.xWCRVBtnDatePick').off('click').on('click', function(){
        // var nSeqNo = $(this).attr('data-seqno');
        var tBchCode = $(this).attr('data-bchcode');
        var tShpCode = $(this).attr('data-shpcode');
        var tPosCode = $(this).attr('data-poscode');
        var nLayNo   = $(this).attr('data-layno');
        var tPK      = tBchCode+tShpCode+tPosCode+nLayNo;
        $('#oetCRVDatePick'+tPK).datepicker('show');
    });

    // Click Button Browse Reason
    $('.xWCRVBtnBrowseRsn').off('click').on('click', function(){
        var tBchCode = $(this).attr('data-bchcode');
        var tShpCode = $(this).attr('data-shpcode');
        var tPosCode = $(this).attr('data-poscode');
        var nLayNo   = $(this).attr('data-layno');
        var tPK      = tBchCode+tShpCode+tPosCode+nLayNo;

        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oCRVBrowseRsnOption  = oCRVBrowseRsn({
                'tReturnInputCode'  : 'oetCRVRsnCode'+tPK,
                'tReturnInputName'  : 'oetCRVRsnName'+tPK
            });
            JCNxBrowseData('oCRVBrowseRsnOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Option Reason
    var oCRVBrowseRsn = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var oOptionReturn       = {
            Title : ['company/branch/branch','เหตุผล'],
            Table : {Master:'TCNMRsn',PK:'FTRsnCode'},
            Join :{
                Table : ['TCNMRsn_L'],
                On : ['TCNMRsn_L.FTRsnCode = TCNMRsn.FTRsnCode AND TCNMRsn_L.FNLngID = '+nLangEdits,]
            },
            Where   : {
                Condition : [" AND FTRsgCode = '022' "]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['รหัสเหตุผล','ชื่อเหตุผล'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMRsn.FTRsnCode','TCNMRsn_L.FTRsnName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 20,
                OrderBy             : ['TCNMRsn.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMRsn.FTRsnCode"],
                Text		: [tInputReturnName,"TCNMRsn_L.FTRsnName"],
            },
        }
        return oOptionReturn;
    };

    $('#obtCRVApproveDoc').off('click').on('click', function(){
        var tDocNo       = $('#oetCRVDocNo').val();
        var tBchCode     = $('#ohdCRVBchCode').val();
        var aItemsUpdate = [];

        // $('#otbCRVDocPdtAdvTableList tbody tr.xWPdtItem').each(function(){ 
        $('.xWKeyDatePick').each(function(){
            // console.log( $(this).attr('data-seqno') )
            // console.log( $(this).attr('data-stasave') )
            // var nSeqNo      = $(this).attr('data-seqno');
            var tBchCode = $(this).parents().parents().parents().parents().attr('data-bchcode');
            var tShpCode = $(this).parents().parents().parents().parents().attr('data-shpcode');
            var tPosCode = $(this).parents().parents().parents().parents().attr('data-poscode');
            var nLayNo   = $(this).parents().parents().parents().parents().attr('data-layno');
            var tPK      = tBchCode+tShpCode+tPosCode+nLayNo;
            var nStaSave    = $(this).parents().parents().parents().parents().attr('data-stasave');
            if( nStaSave == 1 ){
                var dCRVDatePick    = $('#oetCRVDatePick'+tPK).val();
                var dCRVRsnCode     = $('#oetCRVRsnCode'+tPK).val();
                if( dCRVDatePick != "" && dCRVRsnCode != "" ){
                    var aPdt = {
                        'FTBchCode'     : tBchCode,
                        'FTShpCode'     : tShpCode,
                        'FTPosCode'     : tPosCode,
                        'FNXsdLayNo'    : nLayNo,
                        'FDXshDatePick' : dCRVDatePick,
                        'FTRsnCode'     : dCRVRsnCode,
                    };
                    aItemsUpdate.push(aPdt);
                }
            }
        });

        if( aItemsUpdate.length > 0 ){
            $.ajax({
                type    : "POST",
                url     : "docCRVEventSave",
                data    : {
                    ptDocNo      : tDocNo,
                    ptBchCode    : tBchCode,
                    paItems      : aItemsUpdate,
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    // console.log(tResult);
                    // JSvCRVCallPageList();
                    var aReturnData = JSON.parse(oResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSvCRVCallPageList();
                    } else {
                        var tMessageError = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            alert('กรุณากำหนดวันที่รับของ และเหตุผล');
            return;
        }

        
    });
    
    
</script>


