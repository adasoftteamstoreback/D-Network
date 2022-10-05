<div class="row">

    <!--ค้นหา-->
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control xCNInputWithoutSingleQuote" autocomplete="off" id="oetSearchStep1Point1PdtHTML" name="oetSearchStep1Point1PdtHTML" onkeyup="JSvCLMStep1Point1SearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                <span class="input-group-btn">
                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvCLMStep1Point1SearchPdtHTML()">
                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"></div>

    <!--ค้นหาจากบาร์โค๊ด-->
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right xCNHideWhenCancelOrApprove">
        <!-- <div class="form-group">
            <input type="text" class="form-control" id="oetCLMInsertBarcode"  autocomplete="off" name="oetCLMInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);"  placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า" >
        </div> -->
    </div>

    <!--เพิ่มสินค้า-->
    <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 xCNHideWhenCancelOrApprove">
        <div style="margin-top:-2px;">
            <button type="button" id="obtCLMDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
        </div>
    </div>

</div>

<!--สินค้า-->
<div class="row" id="odvCLMStep1Point1DataPdtTableDTTemp"></div>

<!-- =========================================== กรุณาเลือกลูกค้า ก่อนเลือกสินค้า =========================================== -->
<div id="odvCLMModalPleseSelectCustomerOrCar" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกลูกค้า หรือรถ ก่อนทำรายการ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>

    $( document ).ready(function() {
        //แก้ไขจำนวน
        JSxCLMStep1Point1EditQty();

        //โหลดสินค้า
        JSxCLMStep1Point1LoadDatatable();
    });

    //โหลดสินค้า (Point1)
    function JSxCLMStep1Point1LoadDatatable(){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep1Point1Datatable",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
                'ptCLMStaApv'           : '<?=@$tCLMStaApv?>'
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvCLMStep1Point1DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //เพิ่มสินค้า
    $('#obtCLMDocBrowsePdt').unbind().click(function(){ 
        var dTime               = new Date();
        var dTimelocalStorage   = dTime.getTime();

        // ต้องเลือกรถ + ลูกค้าก่อน
        if($('#oetCLMFrmCstCode').val() != "" || $('#oetCLMFrmCarCode').val() != ""){
            
        }else{
            $('#odvCLMModalPleseSelectCustomerOrCar').modal('show');
            return;
        }

        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                'Qualitysearch'   : [],
                'PriceType'       : ["Cost", "tCN_Cost", "Company", "1"],
                'SelectTier'      : ['PDT'],
                'ShowCountRecord' : 10,
                'NextFunc'        : 'JSxAfterChoosePDT',
                'ReturnType'      : 'S',
                'SPL'             : ['',''],
                'BCH'             : ['',''],
                'SHP'             : ['',''],
                'TimeLocalstorage': dTimelocalStorage,
                'tTYPEPDT'        : '1,2,3,4,5'
            },
            cache: false,
            timeout: 0,
            success: function(tResult){
                $('#odvModalDOCPDT').modal({backdrop: 'static', keyboard: false})  
                $('#odvModalDOCPDT').modal({ show: true });

                //remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $('#odvModalsectionBodyPDT').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    //หลังจากเลือกสินค้า
    function JSxAfterChoosePDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            // JSxCLMEventRenderTemp(aNewPackData);      // Event Render : client
            JSxCLMEventInsertToTemp(aNewPackData);    // Event Insert : server
        }
    }

    //Render ตาราง
    function JSxCLMEventRenderTemp(paData){
        JCNxCloseLoading();

        //ช่องสแกนต้องเปิดเมื่อมีรายการใหม่เพิ่มขึ้นไป
        $('#oetCLMInsertBarcode').attr('readonly',false);
        $('#oetCLMInsertBarcode').val('');

        var aPackData = JSON.parse(paData);

        var tCheckIteminTableClass = $('#otbCLMStep1Point1DocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        if(tCheckIteminTableClass == true){
            $('#otbCLMStep1Point1DocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbCLMStep1Point1DocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        for(var i=0; i<nLen; i++){
            var oData           = aPackData[i];
            var oResult         = oData.packData;
            var tBarCode        = oResult.Barcode;          //บาร์โค๊ด
            var tProductCode    = oResult.PDTCode;          //รหัสสินค้า
            var tProductName    = oResult.PDTName;          //ชื่อสินค้า
            var tPunCode        = oResult.PUNCode;          //รหัสหน่วย
            var tUnitName       = oResult.PUNName;          //ชื่อหน่วยสินค้า
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            var nQty            = parseInt(oResult.Qty);    //จำนวน
            var tDuplicate      = $('#otbCLMStep1Point1DocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);

            if(tDuplicate == true ){
                //ถ้าสินค้าซ้ำ ให้เอา Qty +1
                var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                var nNewValue   = parseInt(nValOld) + parseInt(1);
                $('.otr'+tProductCode+tBarCode).find('.xCNQty').val(nNewValue);
            }else{
                //จำนวน
                var oQty = '<div class="xWEditInLine'+nKey+'">';
                    oQty += '<input ';
                    oQty += 'type="text" ';
                    oQty += 'class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine'+nKey+' xWShowInLine'+nKey+' "';
                    oQty += 'id="ohdQty'+nKey+'" ';
                    oQty += 'name="ohdQty'+nKey+'" ';
                    oQty += 'data-seq='+nKey+' ';
                    oQty += 'maxlength="10" ';
                    oQty += 'value="'+nQty+'"';
                    oQty += 'autocomplete="off" >';
                    oQty += '</div>';

                    tHTML += '<tr class="otr'+tProductCode+''+tBarCode+' xWPdtItem xWPdtItemList'+nKey+'"';
                    tHTML += '  data-index="'+nKey+'"';
                    tHTML += '  data-seqno="'+nKey+'"';
                    tHTML += '  data-key="'+nKey+'"';
                    tHTML += '  data-pdtcode="'+tProductCode+'"';
                    tHTML += '>';
                    tHTML += '<td align="center">'+nKey+'</td>';
                    tHTML += '<td>'+tProductCode+'</td>';
                    tHTML += '<td>'+tProductName+'</td>';
                    tHTML += '<td>'+tBarCode+'</td>';
                    tHTML += '<td>'+tUnitName+'</td>';
                    tHTML += '<td class="otdQty text-right" >'+oQty+'</td>';
                    tHTML += '<td nowrap class="text-center">';
                    tHTML += '  <label class="xCNTextLink">';
                    tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnRemoveDTRow(this)">';
                    tHTML += '  </label>';
                    tHTML += '</td>';
                    tHTML += '</tr>';
                nKey++;
            }
        }

        //สร้างตาราง
        $('#otbCLMStep1Point1DocPdtAdvTableList tbody').append(tHTML);

        //ให้ฟังก์ชั่น JS ทำงานได้
        JSxCLMStep1Point1EditQty();
    }

    //เอาข้อมูลสินค้าลง Temp
    function JSxCLMEventInsertToTemp(paData){
        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        var nKey             = parseInt($('#otbCLMStep1Point1DocPdtAdvTableList tr:last').attr('data-seqno'));

        $.ajax({
            type    : "POST",
            url     : "docClaimStep1Point1Insert",
            data    :{
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'tCLMDocNo'             : tCLMDocNo,
                'oPdtData'              : paData,
                'tSeqNo'                : 1
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if(aResult['nStaEvent']==1){
                    JCNxCloseLoading();
                    JSxCLMStep1Point1LoadDatatable();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ลบคอลัมน์ใน Temp
    function JSnRemoveDTRow(ele) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal    = $(ele).parent().parent().parent().attr("data-pdtcode");
            var tSeqno  = $(ele).parent().parent().parent().attr("data-seqno");
                          $(ele).parent().parent().parent().remove();
            JSxCLMRemoveDTTemp(tSeqno, tVal, ele);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบคอลัมน์ในฐานข้อมูล [รายการเดียว]
    function JSxCLMRemoveDTTemp(pnSeqNo,ptPDTCode,elem){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
                var tCLMDocNo    = "DUMMY";
            }else{
                var tCLMDocNo    = $("#ohdCLMDocNo").val();
            }

            $.ajax({
                type    : "POST",
                url     : "docClaimStep1Point1Remove",
                data    : {
                    'tCLMDocNo'  : tCLMDocNo,
                    'nSeqNo'     : pnSeqNo,
                    'tPDTCode'   : ptPDTCode
                },
                cache   : false,
                timeout : 0,
                success: function (oResult) {
                    var aResult = $.parseJSON(oResult);
                    if(aResult['rtCode'] == '1'){
                        $(elem).fadeOut();

                        //ถ้าลบจนหมดเเล้วให้โชว์ว่าไม่พบข้อมูล
                        var tCheckIteminTable = $('#otbCLMStep1Point1DocPdtAdvTableList tbody tr').length;
                        if(tCheckIteminTable == 0){
                            $('#otbCLMStep1Point1DocPdtAdvTableList tbody').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">'+'<?=language('common/main/main','tCMNNotFoundData')?>'+'</td></tr>');
                        }

                    }else{
                        alert(aResult['rtDesc']);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ค้นหาสินค้าใน temp
    function JSvCLMStep1Point1SearchPdtHTML() {
        var value = $("#oetSearchStep1Point1PdtHTML").val().toLowerCase();
        $("#otbCLMStep1Point1DocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    //เเก้ไขจำนวน และ ราคา (เช็คก่อน)
    function JSxCLMStep1Point1EditQty(){
        $('.xCNPdtEditInLine').click(function(){
            $(this).focus().select();
        });

        $('.xCNQty').off().on('change keyup', function(e) {
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var nQty    = $('#ohdQty'+nSeq).val();
                nNextTab    = parseInt(nSeq)+1;
                $('.xWValueEditInLine'+nNextTab).focus().select();
                
                JSxGetDisChgList(nSeq);
            }
        });
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxGetDisChgList(pnSeq){

        var nQty        = $('#ohdQty'+pnSeq).val();

        // Update Value
        $('.xWPdtItemList'+pnSeq).attr('data-qty',nQty);

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        if(pnSeq != undefined){
            $.ajax({
                type    : "POST",
                url     : "docClaimStep1Point1UpdateQTY",
                data    : {
                    'tCLMDocNo'         : tCLMDocNo,
                    'nSeq'              : pnSeq,
                    'nQty'              : nQty
                },
                catch   : false,
                timeout : 0,
                success : function (oResult){},
                error   : function (jqXHR, textStatus, errorThrown) { }
            });
        }
    }

</script>