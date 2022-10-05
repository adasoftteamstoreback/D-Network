<style>
    .col-xs-5ths,
    .col-sm-5ths,
    .col-md-5ths,
    .col-lg-5ths,
    .col-xs-2ths,
    .col-sm-2ths,
    .col-md-2ths,
    .col-lg-2ths {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-xs-2ths {
        width: 40%;
        float: left;
    }
    .col-xs-5ths {
        width: 20%;
        float: left;
    }

    @media (min-width: 768px) {
        .col-sm-5ths {
            width: 20%;
            float: left;
        }
        .col-sm-2ths {
            width: 40%;
            float: left;
        }
    }
    @media (min-width: 992px) {
        .col-md-5ths {
            width: 20%;
            float: left;
        }
        .col-sm-2ths {
            width: 40%;
            float: left;
        }
    }
    @media (min-width: 1200px) {
        .col-lg-2ths {
            width: 40%;
            float: left;
        }
    }
</style>

<!-- Panel เงื่อนไข--> 
<div class="col-lg-4 col-md-12">
    <div class="panel panel-default" style="margin-bottom: 25px; box-shadow:none !important; -webkit-box-shadow :none !important;">
        <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
            <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'เอกสารอ้างอิง'); ?></label>
        </div>
        <div class="panel-collapse collapse in" role="tabpanel">
            <div class="panel-body xCNPDModlue">
                
                <!--วันที่บันทึก-->
                <div class="form-group">
                    <label class="xCNLabelFrm">วันที่บันทึก</label>
                    <div class="input-group">
                        <?php $tDateStep3 = date('Y-m-d'); ?>
                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetStep3DateSave" name="oetStep3DateSave" placeholder="YYYY-MM-DD" value="<?=$tDateStep3?>" autocomplete="off" >
                        <span class="input-group-btn">
                            <button type="button" class="btn xCNBtnDateTime xCNBtnStep3DateSave">
                                <img src="<?=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                            </button>
                        </span>
                    </div>
                </div>

                <!--เลขที่ใบส่งของเคลม-->
                <div class="form-group">
                    <label class="xCNLabelFrm">เลขที่ใบส่งของเคลม</label>
                    <input type="text" class="form-control xCNInputStep3DNCN xCNInputReadOnly xCNInputWithoutSpc" maxlength="30" id="oetStep3DocNoSendClaim" name="oetStep3DocNoSendClaim" placeholder="เลขที่ใบส่งของเคลม" value="" autocomplete="off" >
                </div>

                <!--เลขที่อ้างอิงผลเคลม-->
                <div class="form-group">
                    <label class="xCNLabelFrm">เลขที่อ้างอิงผลเคลม</label>
                    <input type="text" class="form-control xCNInputStep3DNCN xCNInputReadOnly xCNInputWithoutSpc" maxlength="30" id="oetStep3DocNoClaim" name="oetStep3DocNoClaim" placeholder="เลขที่อ้างอิงผลเคลม" value="" autocomplete="off" >
                </div>

            </div>
        </div>
    </div>
</div>

<div class="col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <label class="fancy-radio xCNRadioMain ">
                <input type="radio" class="xWClaimStep3Option" name="ordClaimStep3Option" value="1" checked >
                <span><i></i> <?php echo language('supplier/supplier/supplier','เปลี่ยนสินค้าใหม่')?></span>
            </label>
        </div>
        <div class="col-md-4 col-sm-12">
            <label class="fancy-radio xCNRadioMain" >
                <input type="radio" class="xWClaimStep3Option"  name="ordClaimStep3Option" value="2" >
                <span><i></i> <?php echo language('supplier/supplier/supplier','ชดเชยมูลค่า')?></span>
            </label>
        </div>
        <div class="col-md-4 col-sm-12">
            <label class="fancy-radio xCNRadioMain" >
                <input type="radio" class="xWClaimStep3Option"  name="ordClaimStep3Option" value="3" >
                <span><i></i> <?php echo language('supplier/supplier/supplier','เคลมไม่ได้')?></span>
            </label>
        </div>
    </div>
    <div class="row">

        <!--ผลเคลม-->
        <div style="display:none;">
            <div class="form-group">
                <label class="xCNLabelFrm"><span style = "color:red">*</span>ผลเคลม</label>
                <input type="text" class="form-control xCNInputStep3DNCN xCNInputNumericWithDecimal text-right" maxlength="3" id="oetStep3Percent" name="oetStep3Percent" placeholder="ผลเคลม" value="" autocomplete="off" >
            </div>
        </div>

        <!--ส่วนลดการเคลม-->
        <div class="col-md-4" style="display:none;" id="odvPanelValueStep3">
            <div class="form-group">
                <label class="xCNLabelFrm">ส่วนลดการเคลม</label>
                <input type="text" class="form-control xCNInputStep3DNCN xCNInputNumericWithDecimal text-right" maxlength="8" id="oetStep3Value" name="oetStep3Value" placeholder="ส่วนลดการเคลม" value="" autocomplete="off" >
            </div>
        </div>

        <!--จำนวนที่รับ-->
        <div class="col-md-4"  id="odvPanelGetStep3">
            <div class="form-group">
                <label class="xCNLabelFrm"><span style = "color:red">*</span>จำนวน</label>
                <input type="text" class="form-control xCNInputStep3DNCN xCNInputNumericWithDecimal text-right" maxlength="8" id="oetStep3Get" name="oetStep3Get" placeholder="จำนวน" value="" autocomplete="off" >
            </div>
        </div>

        <!--หมายเหตุ-->
        <div class="col-md-5"  id="odvPanelRemarkStep3">
            <div class="form-group">
                <label class="xCNLabelFrm">หมายเหตุ</label>
                <input type="text" class="form-control xCNInputStep3DNCN xCNInputWithoutSpc text-left" id="oetStep3Remark" name="oetStep3Remark" placeholder="หมายเหตุ" value="" autocomplete="off" >
            </div>
        </div>

        <!--เพิ่ม-->
        <div class="col-md-3">
            <div class="form-group">
                <label class="xCNLabelFrm"></label>
                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNStep3Save" style="border-radius: 2px !important; width: 100%;" type="button"> เพิ่ม </button>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="odvContentPDTStep3TableCNDN"></div>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $('.xCNDatePicker').datepicker({
            format                  : "yyyy-mm-dd",
            todayHighlight          : true,
            enableOnReadonly        : false,
            disableTouchKeyboard    : true,
            autoclose               : true
        });

        //โหลดตารางที่เคยบันทึกข้อมูล
        JSxLoadTableStep3Save()

        $('.xCNBtnStep3DateSave').click(function(event){
            $('#oetStep3DateSave').datepicker('show');
            event.preventDefault();
        });

    });

    //เปลี่ยนค่า
    $(".xWClaimStep3Option").click(function (e) { 
        if($(this).val() == '1'){ //เปลี่ยนสินค้าใหม่
            $("#odvPanelValueStep3").hide();
            $("#odvPanelGetStep3").removeClass('col-md-3').addClass('col-md-4');
            $("#odvPanelRemarkStep3").removeClass('col-md-3').addClass('col-md-5');
        }else if($(this).val() == '2'){ //ชดเชยมูลค่า
            $("#odvPanelValueStep3").show();
            $("#odvPanelValueStep3").removeClass('col-md-6').addClass('col-md-3');
            $("#odvPanelGetStep3").removeClass('col-md-4').addClass('col-md-3');
            $("#odvPanelRemarkStep3").removeClass('col-md-5').addClass('col-md-3');
        }else{ //เคลมไม่ได้
            $("#odvPanelValueStep3").hide();
            $("#odvPanelGetStep3").removeClass('col-md-3').addClass('col-md-4');
            $("#odvPanelRemarkStep3").removeClass('col-md-3').addClass('col-md-5');
        }
    });

    //ผลเคลม (%) ห้ามมากกว่า 100
    $('#oetStep3Percent').on('change', function(){
        var nValue = $(this).val();
        if(nValue > 100){
            $(this).val(100);
        } 

        if(nValue == 100 || nValue > 100){
            $('#oetStep3Value').attr('readonly',true);
        }else{
            $('#oetStep3Value').attr('readonly',false);
        }
    });

    //จำนวนรับ
    $('#oetStep3Get').on('change', function(){
        var nValue      = $(this).val();
        var nQTYSend    = $('#ospQTYSend').text();
        if(nValue > nQTYSend){ //ห้ามเกินจำนวนที่ส่งเคลม
            $(this).val('');
        }else{
            // ถ้ากรอกจำนวน ต้องห้ามเกินจำนวนที่เหลือ
            var nKeepQTY        = $('#ospQTYSaveClaim').text();
            var nKeepQTYInTable = 0;
            $("#otbCLMStep3TableCNDN tbody tr").each(function( index ) {
                nKeepQTYInTable = parseFloat(nKeepQTYInTable) + parseFloat($(this).find( "td:eq(4)" ).text());
            });

            var nBal     = parseFloat(nQTYSend) - parseFloat(nKeepQTYInTable);
            if(nValue > nBal){
                $(this).val('');
            }
        }
    });

    //กดเพิ่ม ใน CNDN
    function JSxLoadTableStep3Save(){
        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        //ล้างค่าใน input
        $('#odvContentStep3SaveAndGet .xCNInputStep3DNCN').val('');

        $.ajax({
            type    : "POST",
            url     : "docClaimStep3TableCNDT",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
                'nSeqPDT'               : $('#ospSeqPDT').text(),
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvContentPDTStep3TableCNDN').html(aReturnData['tViewDataTable']);
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
    
    //เพิ่มข้อมูล
    $('.xCNStep3Save').unbind().click(function(){

        var nTypeRadio = $("input[name='ordClaimStep3Option']:checked").val();
        if(nTypeRadio == 1){ //เปลี่ยนสินค้าใหม่
            var nPercent    = 100;
            var nValue      = 0;
        }else if(nTypeRadio == 2){ //ชดเชยมูลค่า
            var nPercent    = 0;
            var nValue      = $('#oetStep3Value').val();
        }else{ //เคลมไม่ได้
            var nPercent    = 0;
            var nValue      = -100; //รอคุยกับเน็ต
        }

        if($('#oetStep3Get').val() == '' || $('#oetStep3Get').val() == 0){
            $('#oetStep3Get').focus();
            return;
        }

        // //ถ้าเป็น 100% มูลค่าจะเป็น 0
        // if($('#oetStep3Percent').val() == 100 || $('#oetStep3Percent').val() == '100'){
        //     $('#oetStep3Value').val(0);
        // }

        var nKeepQTYInTableCNDN = 0;
        $("#otbCLMStep3TableCNDN tbody tr").each(function( index ) {
            if($(this).find( "td:eq(4)").text() == '' || $(this).find( "td:eq(4)").text() == NaN){

            }else{
                nKeepQTYInTableCNDN = parseFloat(nKeepQTYInTableCNDN) + parseFloat($(this).find( "td:eq(4)").text());
            }
        });
        if(nKeepQTYInTableCNDN >= $('#ospQTYSend').text()){
            $('#oetStep3Get').val(0);
            return;
        }

        //block ปุ่มกันไว้ว่าห้ามกดเบิ้ล
        $('.xCNStep3Save').attr('disabled',true);

        $.ajax({
            type    : "POST",
            url     : "docClaimStep3SaveInTemp",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'tCLMDocNo'             : $("#ohdCLMDocNo").val(),
                'tDateSave'             : $('#oetStep3DateSave').val(),
                'tDocNoSendClaim'       : $('#oetStep3DocNoSendClaim').val(),
                'tDocNoExClaim'         : $('#oetStep3DocNoClaim').val(),
                'nStep3Percent'         : nPercent,
                'nStep3Value'           : nValue,
                'nStep3Get'             : $('#oetStep3Get').val(),
                'nStep3Remark'          : $('#oetStep3Remark').val(),
                'nSeqNo'                : $('#ospSeqPDT').text(),
                'tPDTCode'              : $('#ospSaveAndGetPDTCode').text(),
                'tSPLCode'              : $('#ospSPLCode').text()
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){

                $('.xCNStep3Save').attr('disabled',false);
                $('#oetStep3Value').attr('readonly',false);

                //โหลดหน้าจอใหม่
                JSxLoadTableStep3Save();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });
</script>