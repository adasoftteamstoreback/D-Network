<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbMNGTableCustomByPDT" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold"><?=language('common/main/main','tCenterModalPDTCodePDT'); ?></th>
						<th nowrap class="xCNTextBold"><?=language('common/main/main','tCenterModalPDTNamePDT'); ?></th>
                        <th nowrap class="xCNTextBold"><?=language('common/main/main','tModalPriceUnit'); ?></th>
                        <th nowrap class="xCNTextBold"><?=language('common/main/main','tCenterModalQtyBal'); ?></th>
                        <th nowrap class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYBuy'); ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYReqSpl'); ?></th>
                        <th nowrap class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYReqSplBy'); ?></th>

                        <?php if(@$TYPE_MGT == 1){ //โชว์ใบโอน ?>
                            <th nowrap class="xCNTextBold" width="10%"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYReqBch'); ?></th>
                            <th nowrap class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYReqBchBy'); ?></th>
                        <?php }else if(@$TYPE_MGT == 2){ //โชว์สั่งขาย ?>
                            <th nowrap class="xCNTextBold" width="10%"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','จำนวนสั่งขาย'); ?></th>
                            <th nowrap class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','สาขาที่สั่งขาย'); ?></th>
                        <?php } ?>

                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYReqReject'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aGetDetailDT['rtCode'] == 1 ):?>
                        <?php foreach($aGetDetailDT['raItems'] AS $nKey => $aValue): ?>
                            <tr class="xCNTrKey<?=$nKey?>" data-pdtcode="<?=$aValue['FTPdtCode']?>" data-pdtname="<?=$aValue['FTXpdPdtName']?>" data-seqcode="<?=$aValue['FNXpdSeqNo']?>">
                                <td><?=$aValue['FTPdtCode']?></td>
                                <td><?=$aValue['FTXpdPdtName']?></td>
                                <td>
                                    <span class="xCNMNGPunName<?=$nKey?>"><?=$aValue['FTPunName']?></span>
                                    <span class="xCNMNGPunCode<?=$nKey?>" style="display:none;"><?=$aValue['FTPunCode']?></span>
                                    <span class="xCNMNGBarCode<?=$nKey?>" style="display:none;"><?=$aValue['FTXpdBarCode']?></span>
                                    <span class="xCNMNGFactor<?=$nKey?>"  style="display:none;"><?=$aValue['FCXpdFactor']?></span>
                                    <span class="xCNMNGSeqInDT<?=$nKey?>" style="display:none;"><?=$aValue['FNXpdSeqNo']?></span>
                                </td>
                                <td class="text-right"><?=($aValue['FCStkQty'] == '' ) ? '0' : number_format($aValue['FCStkQty'],0)?></td>
                                <td class="text-right"><?=($aValue['FCXpdQty'] == '' ) ? '0' : number_format($aValue['FCXpdQty'],0)?></td>
                                <td>
                                    <!--จำนวนขอซื้อ-->
                                    <div class="xWEditInLineReqBuy" data-keyupdate="reqbuy">
                                        <input type="text" class="form-control xCNInputNumericWithoutDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?>" 
                                                id="ohdReqBuy<?=$nKey?>" name="ohdReqBuy<?=$nKey?>" maxlength="10" 
                                                value="<?=number_format($aValue['FCXpdQtyPRS'],0)?>" autocomplete="off">
                                    </div> 
                                </td>
                                <td>
                                    <!--ขอซื้อผู้จำหน่าย-->
                                    <?php
                                        $aSPLConfig              = $aSPLConfig;
                                        if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){ //แฟรนไซส์
                                            $tSPLCodeDefault         = $aSPLConfig['rtSPLCode'];
                                            $tSPLNameDefault         = $aSPLConfig['rtSPLName'];
                                        }else{ 
                                            if($aValue['MGTSplCode'] == '' || $aValue['MGTSplCode'] == null){
                                                $tSPLCodeDefault = $aValue['PDTSPLCode'];
                                                $tSPLNameDefault = $aValue['PDTSPLName'];
                                           }else{
                                                $tSPLCodeDefault = $aValue['MGTSplCode'];
                                                $tSPLNameDefault = $aValue['MGTSplName'];
                                           }
                                        }
                                    ?>

                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                            id="oetMNGFrmSPLCode<?=$nKey?>"
                                            maxlength="50"
                                            value="<?=$tSPLCodeDefault?>"
                                        >
                                        <input
                                            type="text"
                                            class="form-control xControlForm xWPointerEventNone"
                                            id="oetMNGFrmSPLName<?=$nKey?>"
                                            maxlength="100"
                                            placeholder="<?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYReqSplBy'); ?>"
                                            value="<?=$tSPLNameDefault?>"
                                            readonly
                                        >
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button type="button" class="btn xCNBtnBrowseAddOn xCNMNGBrowseSPL" data-key="<?=$nKey?>">
                                                <img src="<?=base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td>    
                                    <!--จำนวนขอโอน-->
                                    <div class="xWEditInLineReqTnf" data-keyupdate="reqtnf">
                                        <input type="text" class="form-control xCNInputNumericWithoutDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?>" 
                                                id="ohdReqTnf<?=$nKey?>" name="ohdReqTnf<?=$nKey?>" maxlength="10" 
                                                value="<?=number_format($aValue['FCXpdQtyTR'],0)?>" autocomplete="off">
                                    </div> 
                                </td>
                                <td>
                                    <?php 
                                        if(@$TYPE_MGT == 1){ //โชว์ใบโอน 
                                            $tTextPlaceholder = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYReqBchBy');
                                        }else if(@$TYPE_MGT == 2){ //โชว์สั่งขาย 
                                            $tTextPlaceholder = 'สาขาที่สั่งขาย';
                                        } 
                                    ?>
                                    
                                    <!--สาขาที่ขอโอน-->
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                            id="oetMNGFrmBchCode<?=$nKey?>"
                                            maxlength="5"
                                            value="<?=$aValue['MGTBchCode']?>"
                                        >
                                        <input
                                            type="text"
                                            class="form-control xControlForm xWPointerEventNone"
                                            id="oetMNGFrmBchName<?=$nKey?>"
                                            maxlength="100"
                                            placeholder="<?=$tTextPlaceholder?>"
                                            value="<?=$aValue['MGTBchName']?>"
                                            readonly
                                        >
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button type="button" class="btn xCNBtnBrowseAddOn xCNMNGBrowseBCH" data-key="<?=$nKey?>">
                                                <img src="<?=base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn xCNBtnBrowseAddOn xWMNGCheckWahouse" data-key="<?=$nKey?>">
                                                <img src="<?=base_url().'/application/modules/common/assets/images/icons/view.png'?>" style="padding-top: 2px !important;padding-bottom: 2px !important;">
                                            </button>
                                        </span>
                                    </div>
                                    
                                </td>
                                <td>    
                                    <!--จำนวนไม่อนุมัติ-->
                                    <div class="xWEditInLineNotApv" data-keyupdate="notapv">
                                        <input type="text" class="form-control xCNInputNumericWithoutDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?>" 
                                                id="ohdNotApv<?=$nKey?>" name="ohdNotApv<?=$nKey?>" maxlength="10" 
                                                value="<?=number_format($aValue['FCXpdQtyCancel'],0)?>" autocomplete="off">
                                    </div> 
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aGetDetailDT['rnAllRow']?> <?=language('common/main/main','tRecord')?> </p>
    </div>
</div>

<script>

    $('#obtMNGCreateDocRef').hide();
    $('#obtMNGApproveDoc').hide();
    $('#obtMNGExportDoc').hide();
    $('#obtMNGGenFileAgain').hide();

    //ฟังก์ชั่นโหลดค่าครั้งเเรก
    JSxInsertDefaultSPLANdQty()
    function JSxInsertDefaultSPLANdQty(){
        $('#otbMNGTableCustomByPDT tbody tr').each(function (i, el) {
            var tSPLCode    = $(this).find('td:eq(6)').find('.xCNInputWithoutSingleQuote').val();
            if(tSPLCode == '' || tSPLCode == null){

            }else{
                var nKeepSPLKey     = ($(this).attr('data-seqcode') - 1);
                var ptPDTCode       = $('.xCNTrKey'+nKeepSPLKey).attr('data-pdtcode');
                var pnSeq           = $('.xCNTrKey'+nKeepSPLKey).attr('data-seqcode');
                var ptDataUpdate    = 'splto';
                var pnQTY           = $('.xCNTrKey'+nKeepSPLKey).find('td:eq(5)').find('#ohdReqBuy'+nKeepSPLKey).val();
                var ptRefTo         = tSPLCode;
                JSxUpdateQTYAndSPLAndBCH(ptDataUpdate , pnQTY , ptPDTCode , ptRefTo , pnSeq);
            }
        });
    }

    //ระบบค้นหา
    function JSvFindSearchPdtHTML(){
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbMNGTableCustomByPDT tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    //กดบันทึก จะเช็คเงื่อนไข
    function JSvMNGSaveCustomByPDT(){
        var aPackDataIns = [];
        var tStatus      = 2; //2 : ถ้ามีสินค้าผ่านทุกตัว , 1 : มีสินค้าไม่ตรงเงื่อนไข

        $('#otbMNGTableCustomByPDT tbody tr').each(function (i, el) {
            //จำนวนที่สั่ง
            var nQty    = $(this).find('td:eq(4)').text();

            //จำนวนขอซื้อ
            var nReqBuy = $(this).find('td:eq(5)').find('#ohdReqBuy'+i).val();
            if(nReqBuy == '' || nReqBuy == null || nReqBuy == .0000){
                nReqBuy = 0;
                $(this).find('td:eq(5)').find('#ohdReqBuy'+i).val(0);
            }

            //จำนวนขอโอน
            var nReqTnf = $(this).find('td:eq(7)').find('#ohdReqTnf'+i).val();
            if(nReqTnf == '' || nReqTnf == null || nReqTnf == .0000){
                nReqTnf = 0;
                $(this).find('td:eq(7)').find('#ohdReqTnf'+i).val(0);
            }

            //จำนวนที่ไม่อนุมัติ
            var nNotApv = $(this).find('td:eq(9)').find('#ohdNotApv'+i).val();
            if(nNotApv == '' || nNotApv == null || nNotApv == .0000){
                nNotApv = 0;
                $(this).find('td:eq(9)').find('#ohdNotApv'+i).val(0);
            }

            var nTotal = parseInt(nReqBuy) + parseInt(nReqTnf) + parseInt(nNotApv);

            if(nTotal > nQty || nTotal < nQty){ //ไม่ผ่าน

                $('#odvMGTModalPDTLessAndMore').modal('show');
                // alert('จำนวนเกินที่สั่ง หรือ จำนวนที่สั่งขาด');
                tStatus = 1;
                return false;
            }else{ //ผ่าน

                //ถ้าไม่ได้เลือกผู้จำหน่าย ต้องกรอก
                if(nReqBuy > 0 && $(this).find('td:eq(6)').find('#oetMNGFrmSPLCode'+i).val() == ''){
                    $(this).find('td:eq(6)').find('#oetMNGFrmSPLName'+i).focus();
                    tStatus = 1;
                    return false;
                }

                //ถ้าไม่ได้เลือกสาขามา ต้องบังคับ
                if(nReqTnf > 0 && $(this).find('td:eq(8)').find('#oetMNGFrmBchCode'+i).val() == ''){ 
                    $(this).find('td:eq(8)').find('#oetMNGFrmBchName'+i).focus();
                    tStatus = 1;
                    return false;
                }

                //ผู้จำหน่ายที่ขอซื้อ
                if(nReqBuy > 0){
                    var tReqBuy = $(this).find('td:eq(6)').find('#oetMNGFrmSPLCode'+i).val(); //ผู้จำหน่ายที่ขอซื้อ
                }else{
                    var tReqBuy = '';
                }

                //สาขาที่ขอโอน
                if(nReqTnf > 0){
                    var tReqTnf = $(this).find('td:eq(8)').find('#oetMNGFrmBchCode'+i).val(); //สาขาที่ขอโอน
                }else{
                    var tReqTnf = '';
                }

                tStatus = 2;
            }
        });

        //ถ้าผ่านทุกตัว
        if(tStatus == 2){
            JSxMNGSaveByPDT();
        }
    }

    //บันทึก
    function JSxMNGSaveByPDT(){
        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBSavePDTInTable",
            data    : {
                'ptDocumentNumber'    : '<?=$tDocumentNumber?>',
                'tMNGTypeDocument'    : $('#ohdMNGTypeDocument').val()
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {
                JSvMNGCallPageList();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //เลือกผู้จำหน่าย
    $('.xCNMNGBrowseSPL').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            var nKeyCode = $(this).attr('data-key');
            window.nKeepSPLKey           = nKeyCode;
            window.oMNGBrowseSplOption   = undefined;
            oMNGBrowseSplOption          = oSplOption({
                'tReturnInputCode'  : 'oetMNGFrmSPLCode'+nKeyCode,
                'tReturnInputName'  : 'oetMNGFrmSPLName'+nKeyCode,
                'tNextFuncName'     : 'JSxMGTUpdateSPLCodeInTemp',
                'aArgReturn'        : ['FTSplCode', 'FTSplName']
            });
            JCNxBrowseData('oMNGBrowseSplOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oSplOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tNextFuncName       = poDataFnc.tNextFuncName;

        var oOptionReturn       = {
            Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
            Table   : {Master:'TCNMSpl', PK:'FTSplCode'},
            Join    : {
                Table: ['TCNMSpl_L'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                ]
            },
            Where   : {
                Condition : ["AND TCNMSpl.FTSplStaActive = '1' "]
            },
            GrideView   : {
                ColumnPathLang      : 'supplier/supplier/supplier',
                ColumnKeyLang       : ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 10,
                OrderBy             : ['TCNMSpl_L.FTSplName ASC']
            },
            CallBack:{
                ReturnType          : 'S',
                Value               : [tInputReturnCode,"TCNMSpl.FTSplCode"],
                Text                : [tInputReturnName,"TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName            : tNextFuncName,
                ArgReturn           : aArgReturn
            }
        };
        return oOptionReturn;
    }

    //เลือกสาขา
    $('.xCNMNGBrowseBCH').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            var nKeyCode = $(this).attr('data-key');
            window.nKeepBCHKey           = nKeyCode;
            window.oMNGBrowseBchOption   = undefined;
            oMNGBrowseBchOption          = oBranchOption({
                'tReturnInputCode'  : 'oetMNGFrmBchCode'+nKeyCode,
                'tReturnInputName'  : 'oetMNGFrmBchName'+nKeyCode,
                'tNextFuncName'     : 'JSxMGTUpdateBCHCodeInTemp',
                'aArgReturn'        : ['FTBchCode', 'FTBchName'] 
            });
            JCNxBrowseData('oMNGBrowseBchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        tUsrLevel               = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti               = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch            = "";

        if('<?=$tMNGTypeDocument?>' == 1){ //ใบสั่งสินค้าจากสาขา 
            tSQLWhereBch += " AND TCNMBranch.FTBchType = '4' "; //เอาสาขาที่เฉพาะแฟรสไซส์
        }else{
            tSQLWhereBch += " AND TCNMBranch.FTBchType != '4' "; //เอาสาขาที่เป็นเฉพาะอยู่ใต้สำนักงานใหญ้
        }

        if(tUsrLevel != "HQ"){
            tSQLWhereBch += " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        tSQLWhereBch += " AND TCNMBranch.FTBchCode NOT IN ( " +"'<?=$tBchDocRef?>'"+ " ) ";

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn       = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhereBch]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode'],
                SourceOrder         : "ASC"
            },
            CallBack:{
                ReturnType          : 'S',
                Value               : [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text                : [tInputReturnName,"TCNMBranch_L.FTBchName"]
            },
            NextFunc:{
                FuncName            : tNextFuncName,
                ArgReturn           : aArgReturn
            }
        };
        return oOptionReturn;
    }

    //ขอซื้อผู้จำหน่าย
    function JSxMGTUpdateSPLCodeInTemp(poDataNextFunc){
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            var ptPDTCode       = $('.xCNTrKey'+nKeepSPLKey).attr('data-pdtcode');
            var pnSeq           = $('.xCNTrKey'+nKeepSPLKey).attr('data-seqcode');
            var ptDataUpdate    = 'splto';
            var pnQTY           = $('.xCNTrKey'+nKeepSPLKey).find('td:eq(5)').find('#ohdReqBuy'+nKeepSPLKey).val();
            var ptRefTo         = aData[0];
            JSxUpdateQTYAndSPLAndBCH(ptDataUpdate , pnQTY , ptPDTCode , ptRefTo , pnSeq);
        }
    }

    //ขอโอนสาขา
    function JSxMGTUpdateBCHCodeInTemp(poDataNextFunc){
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            var ptPDTCode       = $('.xCNTrKey'+nKeepBCHKey).attr('data-pdtcode');
            var pnSeq           = $('.xCNTrKey'+nKeepBCHKey).attr('data-seqcode');
            var ptDataUpdate    = 'bchto';
            var pnQTY           = $('.xCNTrKey'+nKeepBCHKey).find('td:eq(7)').find('#ohdReqTnf'+nKeepBCHKey).val();
            var ptRefTo         = aData[0];
            JSxUpdateQTYAndSPLAndBCH(ptDataUpdate , pnQTY , ptPDTCode , ptRefTo , pnSeq);
        }
    }

    //กรอกจำนวน สั่งซื้อ , ขอโอน , ไม่อนุมัติ
    $('.xWEditInLineReqTnf , .xWEditInLineReqBuy , .xWEditInLineNotApv').off().on('change keyup', function(e) {
        if (e.type === 'change' || e.keyCode === 13) {
            var ptDataUpdate     = $(this).attr('data-keyupdate');
            var pnQTY            = $(this).find('.xCNPdtEditInLine').val();
            if(pnQTY == '' || pnQTY == NaN || pnQTY == null){
                $(this).find('.xCNPdtEditInLine').val(0);
                pnQTY = 0;
            }
            var ptPDTCode        = $(this).parent().parent().attr('data-pdtcode');
            var pnSeq            = $(this).parent().parent().attr('data-seqcode');
            var ptRefTo          = '';
            
            //ถ้ากรอกเเล้วให้ตัวถัดไป focus (ใบสั่งซื้อ)
            if(ptDataUpdate == 'reqbuy'){
                var nSeqNext = parseInt(pnSeq);
                $(this).parent().parent().parent().find('#ohdReqBuy'+nSeqNext).select();
            }

            //ถ้ากรอกเเล้วให้ตัวถัดไป focus (ใบสั่งขาย ใบขอโอน)
            if(ptDataUpdate == 'reqtnf'){
                var nSeqNext = parseInt(pnSeq);
                $(this).parent().parent().parent().find('#ohdReqTnf'+nSeqNext).select();
            }

            //ถ้ากรอกเเล้วให้ตัวถัดไป focus (ใบสั่งขาย ใบขอโอน)
            if(ptDataUpdate == 'notapv'){
                var nSeqNext = parseInt(pnSeq);
                $(this).parent().parent().parent().find('#ohdNotApv'+nSeqNext).select();
            }

            JSxUpdateQTYAndSPLAndBCH(ptDataUpdate , pnQTY , ptPDTCode , ptRefTo , pnSeq);
        }
    });

    //อัพเดท จำนวน , ผู้จำหน่าย , สาขา 
    function JSxUpdateQTYAndSPLAndBCH(ptDataUpdate , pnQTY , ptPDTCode , ptRefTo , pnSeq){

        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBUpdateQTY",
            data    : {
                tDocumentNumber     : '<?=$tDocumentNumber?>',
                tDataUpdate         : ptDataUpdate,
                nQTY                : pnQTY,
                tPDTCode            : ptPDTCode,
                tRefTo              : ptRefTo,
                nSeq                : pnSeq
            },
            cache   : false,
            timeout : 5000,
            success : function (oResult) {
                // console.log(oResult);
                //update success
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    $('.xWMNGCheckWahouse').off('click').on('click',function(){
        var tKey     = $(this).attr('data-key');
        var tPdtCode = $(this).parents().parents().parents().parents().attr('data-pdtcode');
        var tPdtName = $(this).parents().parents().parents().parents().attr('data-pdtname');
        var tBchCode = $('#oetMNGFrmBchCode'+tKey).val();
        var tBchName = $('#oetMNGFrmBchName'+tKey).val();

        if( tBchCode != "" ){
            $('#obtMNGChkPdtStkBalBrowseWah').attr('disabled',false);
        }else{
            $('#obtMNGChkPdtStkBalBrowseWah').attr('disabled',true);
        }

        $('#oetMNGChkPdtStkBalPdtCode').val(tPdtCode);
        $('#oetMNGChkPdtStkBalPdtName').val(tPdtName);
        $('#oetMNGChkPdtStkBalWahCode').val('');
        $('#oetMNGChkPdtStkBalWahName').val('');
        $('#oetMNGChkPdtStkBalBchCode').val(tBchCode);
        $('#oetMNGChkPdtStkBalBchName').val(tBchName);
        $('#odvMNGModalChkPdtStkBal #odvMNGModalHeader #ospMNGModalPdtCode').text(tPdtCode);
        $('#odvMNGModalChkPdtStkBal #odvMNGModalHeader #ospMNGModalPdtName').text(tPdtName);

        var aPackData = {
            tPdtCode : tPdtCode,
            tBchCode : tBchCode,
            tWahCode : '',
        };
        JSxMNGPageChkPdtStkBal(aPackData);
    });


</script>