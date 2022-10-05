
<div class="table-responsive">
    <table id="otbMNPDocPdtAdvTableList" class="table xWPdtTableFont">
        <thead>
            <tr class="xCNCenter">
                <th class="xCNHideWhenCancelOrApprove">
                    <label class="fancy-checkbox">
                        <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                        <span class="ospListItem">&nbsp;</span>
                    </label>
                </th>
                <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTBNo')?></th>
                <th class="xCNTextBold">ปลายทางสาขา</th>
                <th class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDocRefPRS')?></th>
                <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtcode')?></th>
                <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtname')?></th>
                <th class="xCNTextBold"><?=language('common/main/main','tModalPriceUnit'); ?></th>
                <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_barcode')?></th>
                <th class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQTYBuy')?></th>
                <th class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPQtyConfirm')?></th>
                <th class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','หมายเหตุ')?></th>
                <th class="xCNHideWhenCancelOrApprove"><?=language('document/purchaseorder/purchaseorder','tPOTBDelete')?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $nCount_BCH = 0;
                $nCount_PDT = 0;
            ?>
            <?php if($aDataDocDTTemp['rtCode'] == 1){
                foreach($aDataDocDTTemp['raItems'] as $nIndex => $aDataTableVal){ ?>
                    <?php $nKey = $aDataTableVal['FNXtdSeqNo']; ?>
                    <tr class="xWPdtItem xWPdtItemList<?=$nKey?>"
                        data-key="<?=$nKey?>"
                        data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>"
                        data-barcode="<?=$aDataTableVal['FTXtdBarCode'];?>">
                        <td class="xCNHideWhenCancelOrApprove" style="text-align:center">
                            <label class="fancy-checkbox">
                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxMNPDTSelectMulDel(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </td>
                        <td style="text-align:center"><?=$aDataTableVal['FNXtdSeqNo'];?></td>

                        <?php if($aDataTableVal['FTXtdRmk'] == 'ChangeBCH' || $aDataTableVal['FTBchType'] == 4){//ถ้าเป็น สาขาจากแฟรนไซส์ ?>
                            <?php 
                                if($this->session->userdata("tSesUsrLevel") == 'HQ'){ //ถ้าเป็นการนำเข้าไฟล์จากสำนักงานใหญ่ ปล่อยให้เลือกเอง
                                    if($aDataTableVal['FTXtdRmk'] == 'ChangeBCH'){ //เป็นขา Edit
                                        $tBCHCode = $aDataTableVal['FTBchCode'];
                                        $tBCHName = $aDataTableVal['FTBchName'];
                                    }else{
                                        $tBCHCode = $aDataTableVal['FTBchCode'];
                                        $tBCHName = $aDataTableVal['FTBchName'];
                                    }
                                }else{
                                    $tBCHCode = $aDataTableVal['FTBchCode'];
                                    $tBCHName = $aDataTableVal['FTBchName'];
                                }
                            ?>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBchRefCode<?=$nKey?>" name="oetBchRefCode<?=$nKey?>" value="<?=$tBCHCode?>">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetBchRefName<?=$nKey?>" name="oetBchRefName<?=$nKey?>" value="<?=$tBCHName?>" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" data-keyIndex="<?=$nKey?>" class="btn xCNBtnBrowseAddOn xCNBchRef"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </td>
                        <?php }else{//ถ้าเป็น สาขาสำนักงานใหญ่ หรือ สาขาตัวแทนขาย ?>
                            <td><?=$aDataTableVal['FTBchName'];?></td>
                        <?php } ?>
                        <td><?=$aDataTableVal['FTXtdDocNoRef'];?></td>
                        <td><?=$aDataTableVal['FTPdtCode'];?></td>
                        <td><?=$aDataTableVal['FTXtdPdtName'];?></td>
                        <td><?=$aDataTableVal['FTPunName'];?></td>
                        <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                        <td class="text-right"><?=number_format($aDataTableVal['FCStkQty'],0);?></td>
                        <td class="text-right"><?=number_format($aDataTableVal['FCXtdQty'],0);?></td>
                        <?php 
                            if($aDataTableVal['FTTmpStatus'] == 'DUP'){
                                $tTextRmk = 'อ้างอิงเลขที่ใบขอซื้อผู้จำหน่ายซ้ำ';
                            }else{
                                if($aDataTableVal['FTTmpStatus'] == 1){
                                    $tTextRmk = '-';
                                }else{

                                    //Error
                                    if(in_array($aDataTableVal['FTTmpStatus'], ["2","3","4","7"])){
                                        if($aDataTableVal['rtTextError'] != ''){
                                            $tDataCol   = explode("$&", $aDataTableVal['rtTextError'])[1];
                                        }else{
                                            $tDataCol   = '-';
                                        }
                                    }else{
                                        $tDataCol   = '-';
                                    }

                                    $tTextRmk = $tDataCol;
                                } 
                            }
                        ?>
                        <td style="color: red !important; font-weight: bold;"><?=$tTextRmk;?></td>
                        <td  class="text-center xCNHideWhenCancelOrApprove">
                            <label class="xCNTextLink">
                                <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnRemoveDTRow(this)">
                            </label>
                        </td>
                    </tr>

                    <?php 
                        if($nIndex == 0){
                            $nCount_BCH = number_format($aDataTableVal['count_BCH'],0);
                            $nCount_PDT = number_format($aDataTableVal['count_PDT'],0);
                        } 
                    ?>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                    <td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">
                        <?=language('common/main/main','tCMNNotFoundData')?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>    

    $("document").ready(function () {
        
    });
    
    //ค้นหาในตาราง
    function JSvMNPSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML")
            .val()
            .toLowerCase();

        $("#otbMNPDocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle(
            $(this)
            .text()
            .toLowerCase()
            .indexOf(value) > -1
            );
        });
    }

    //จำนวนสรุป
    $('.xCNShowCountBCHList').text('<?=$nCount_BCH?>');
    $('.xCNShowCountPDTList').text('<?=$nCount_PDT?>');

    //คลิกเลือกทั้งหมดในสินค้า DT Tmp
    $('#ocmCENCheckDeleteAll').change(function(){
        var bStatus = $(this).is(":checked") ? true : false;
		if(bStatus == false){
			localStorage.removeItem("MNPDT_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
		}else{
            localStorage.removeItem("MNPDT_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
			$('.ocbListItem').each(function (e) {
                $(this).on( "click", FSxMNPDTSelectMulDel(this) );
            });
		}
    });

    //ลบแบบหลายรายการ
    $('#odvMNPDTModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSnMNPDTRemovePdtDTTempMultiple();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //ลบคอลัมน์ในฐานข้อมูล ลบคอม่า [หลายรายการ]
    function JSoMNPRemoveCommaData(paData){
        var aTexts              = paData.substring(0, paData.length - 2);
        var aDataSplit          = aTexts.split(" , ");
        var aDataSplitlength    = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

    //ลบคอลัมน์ในฐานข้อมูล ฐานข้อมูล [หลายรายการ]
    function JSnMNPDTRemovePdtDTTempMultiple(){
        JCNxOpenLoading();
        var tDocNo          = $("#oetMGTPODocNo").val();
        if(tDocNo == '' || tDocNo == null){
            var tDocNo = 'DUMMY';
        }
        var aDataSeqNo      = JSoMNPRemoveCommaData($('#odvMNPDTModalDelPdtInDTTempMultiple #ohdConfirmMNPDTSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvMNPDTModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvIVModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();

        setTimeout(function(){
            $('.modal-backdrop').remove();
            JCNxLayoutControll();
        }, 500);

        JCNxCloseLoading();

        $.ajax({
            type    : "POST",
            url     : "docMnpDocPODeleteDTMuti",
            data    : {
                'tDocNo'            : tDocNo,
                'tSeqCode'          : aDataSeqNo
            },
            cache: false,
            timeout: 0,
            success: function (oResult) {
                JSvMNPLoadPdtDataTableHtml();
                // var aResult = $.parseJSON(oResult);
                // if(aResult['nStaEvent'] == '1'){
                //     //เช็คทั้งหมดปลดล็อค
                //     $('#ocmCENCheckDeleteAll').attr("checked",false);
                //     //ถ้าลบจนหมดเเล้วให้โชว์ว่าไม่พบข้อมูล
                //     var tCheckIteminTable = $('#otbMNPDocPdtAdvTableList tbody tr').length;
                //     if(tCheckIteminTable == 0){
                //         $('#otbMNPDocPdtAdvTableList tbody').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">'+'<?=language('common/main/main','tCMNNotFoundData')?>'+'</td></tr>');
                //     }
                //     //ลบค่าใน local
                //     localStorage.removeItem('MNPDT_LocalItemDataDelDtTemp');
                //     //บล็อกปุ่มลบทั้งหมด
                //     $('#oliIVBtnDeleteMulti').addClass('disabled');
                // }else{
                //     alert(aResult['tStaMessg']);
                // }
            },
            error: function (jqXHR, textStatus, errorThrown) {}
        });
    }

    //ลบคอลัมน์ในฐานข้อมูล เก็บไว้ใน localstorage [หลายรายการ]
    function FSxMNPDTSelectMulDel(ptElm){
        let tSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');

        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("MNPDT_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("MNPDT_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tSeqNo'    : tSeqNo,
                'tPdtCode'  : tPdtCode,
                'tBarCode'  : tBarCode,
            });
            localStorage.setItem("MNPDT_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxMNPDTTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStMNPFindObjectByKey(aArrayConvert[0],'tSeqNo',tSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tSeqNo'    : tSeqNo,
                    'tPdtCode'  : tPdtCode,
                    'tBarCode'  : tBarCode,
                });
                localStorage.setItem("MNPDT_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxMNPDTTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("MNPDT_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("MNPDT_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxMNPDTTextInModalDelPdtDtTemp();
            }
        }
        JSxMNPDTShowButtonDelMutiDtTemp();
    }
    
    //ลบคอลัมน์ในฐานข้อมูล เช็คค่าใน array [หลายรายการ]
    function JStMNPFindObjectByKey(array,key,value){
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    //ลบคอลัมน์ในฐานข้อมูล เปิดปุ่มลบทั้งหมด [หลายรายการ]
    function JSxMNPDTShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("MNPDT_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#oliMNPDTBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#oliMNPDTBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#oliMNPDTBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ลบคอลัมน์ในฐานข้อมูล เก็บค่าใน Modal [หลายรายการ]
    function JSxMNPDTTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("MNPDT_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tMNPDTTextSeqNo   = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tMNPDTTextSeqNo    += aValue.tSeqNo;
                tMNPDTTextSeqNo    += " , ";
            });
            $('#odvMNPDTModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvMNPDTModalDelPdtInDTTempMultiple #ohdConfirmMNPDTSeqNoDelete').val(tMNPDTTextSeqNo);
        }
    }

    //ลบคอลัมน์ใน Temp [ตัวเดียว]
    function JSnRemoveDTRow(ele) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal    = $(ele).parent().parent().parent().attr("data-pdtcode");
            var tSeqno  = $(ele).parent().parent().parent().attr("data-seqno");
                          $(ele).parent().parent().parent().remove();
            JSxMNPRemoveDTTemp(tSeqno, tVal, ele);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบคอลัมน์ในฐานข้อมูล [รายการเดียว]
    function JSxMNPRemoveDTTemp(pnSeqNo,ptPDTCode,elem){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tDocNo          = $("#oetMGTPODocNo").val();
            if(tDocNo == '' || tDocNo == null){
                var tDocNo = 'DUMMY';
            }

            $.ajax({
                type    : "POST",
                url     : "docMnpDocPODeleteDTSingle",
                data    : {
                    ptDocNo     : tDocNo,
                    pnSeqNo     : pnSeqNo,
                    ptPDTCode   : ptPDTCode
                },
                cache   : false,
                timeout : 0,
                success: function (oResult) {
                    JSvMNPLoadPdtDataTableHtml();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    $(".xCNBchRef").click(function() {
        localStorage.nKeyIndex  = '';
        var nKeyIndex           = $(this).attr('data-keyIndex');
        var tElemName           = "oetBchRefName"+nKeyIndex;
        var tElemCode           = "oetBchRefCode"+nKeyIndex;
        var tUsrLevel           = "<?=$this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti       = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var nCountBch           = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
        var nLangEdits          = '<?=$this->session->userdata("tLangEdit") ?>';
        localStorage.nKeyIndex  = nKeyIndex;
        var tWhere              = " ";

        if (tUsrLevel != "HQ") {
            tWhere += " AND TCNMBranch.FTBchCode IN (" + tBchCodeMulti + ") ";
        } else {
            //tWhere += " AND TCNMBranch.FTBchType = '1' ";
        }

        // option 
        window.oBrowseBchCreated = {
            Title   : ['authen/user/user', 'tBrowseBCHTitle'],
            Table   : { Master  : 'TCNMBranch', PK  : 'FTBchCode'},
            Join    : {
                Table           : ['TCNMBranch_L'],
                On              : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition       : [tWhere]
            },
            GrideView: {
                ColumnPathLang  : 'authen/user/user',
                ColumnKeyLang   : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize     : ['10%', '75%'],
                DataColumns     : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal      : 50,
                Perpage         : 10,
                OrderBy         : ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack: {
                ReturnType      : 'S',
                Value           : [tElemCode, "TCNMBranch.FTBchCode"],
                Text            : [tElemName, "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName        : 'JSxSetNewValueBCHInTemp',
                ArgReturn       : ['FTBchCode']
            }
        };
        JCNxBrowseData('oBrowseBchCreated');
    });

    //อัพเดทสาขาใหม่
    function JSxSetNewValueBCHInTemp(paData){
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aDataNextFunc   = JSON.parse(paData);
            tBCHCode            = aDataNextFunc[0];


            $.ajax({
                type    : "POST",
                url     : "docMnpDocUpdateSeq",
                data    : {
                    ptBCHCode       : tBCHCode,
                    pnSeqNo         : localStorage.nKeyIndex
                },
                cache   : false,
                timeout : 0,
                success: function (oResult) {

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
        
    }

</script>