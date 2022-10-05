
var nStaInterfaceImportBrowseType   = $('#oetInterfaceExportStaBrowse').val();
var tCallInterfaceImportBackOption  = $('#oetInterfaceExportCallBackOption').val();

$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose();

    //ซ่อน ส่งออกเอกสารที่ค้างอนุมัติ
    $('#odvHistoryDocumentNotApv').hide();

});

//Call Rabbit MQ 
function JSxIFXCallRabbitMQ(){

    //ซ่อน ส่งออกเอกสารที่ค้างอนุมัติ
    $('#odvHistoryDocumentNotApv').hide();

    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){

        var tSpcCheck   = false;
        var tSpcAPICode = '';
        $('.progress-bar-chekbox:checked').each(function(i, obj) {
            var tApiCode = $(this).parent().parent().attr('data-apicode');
            if(tApiCode == '00030' || tApiCode == '00028'){ 
                //ต้นทุน FIT Auto (SAP + BIGDATA) จะต้องวิ่งเข้าไปหาเอกสารตรวจนับก่อน
                //ส่งออกรายได้ขายแยกตามประเภทการชำระเงิน(SAP)
                tSpcCheck   = true;
                tSpcAPICode = tApiCode;
                return;
            }
        });

        if(tSpcCheck == true){
            $.ajax({
                type    : "POST",
                url     : "interfaceexportCheckDocument",
                data    : {
                    'tBCHCodeFrm'   : $('#oetExBchCodeFrm'+tSpcAPICode).val(),
                    'tBCHCodeTo'    : $('#oetExBchCodeTo'+tSpcAPICode).val(),
                    'tMonth'        : $('#ocmExMonth'+tSpcAPICode + ' option:selected').val(),
                    'tYear'         : $('#ocmExYear'+tSpcAPICode + ' option:selected').val(),
                    'tDay'          : $('#oetExDateFrm'+tSpcAPICode).val(),
                    'tBillFrm'      : $('#oetExDocSaleCodeFrm'+tSpcAPICode).val(),
                    'tBillTo'       : $('#oetExDocSaleCodeTo'+tSpcAPICode).val(),
                    'tAPICode'      : tSpcAPICode
                },
                cache   : false,
                Timeout : 0,
                success : function(tResult){
                    var oResult = JSON.parse(tResult); 
                    if(oResult.nCount > 0){ //พบเอกสารค้าง
                        if($('.progress-bar-chekbox:checked').length == 1){
                            if(tSpcAPICode == '00030' || tSpcAPICode == '00028'){ 
                                if(tSpcAPICode == '00030'){ //ต้นทุน FIT Auto (SAP + BIGDATA) จะต้องวิ่งเข้าไปหาเอกสารตรวจนับก่อน
                                    //ถ้าเลือกแค่เส้นสามเส้นเดียว
                                    $('#odvInterfaceExportCostIsNull').modal('show');

                                    var aItemData = oResult.aItems;
                                    if(aItemData.rtCode == 1){
                                        var aItem = aItemData.raItems;
                                        var tHTML = '';
                                        for(var i=0; i<aItem.length; i++){
                                            switch (aItem[i].rnCodeNoti) {
                                                case '00013': //ใบรับเข้า (คลัง)
                                                    var tMsgDesc1     = 'เอกสารใบรับเข้า (คลัง)';
                                                    break;
                                                case '00014': //ใบเบิกออก (คลัง)
                                                    var tMsgDesc1     = 'เอกสารใบเบิกออก (คลัง)';
                                                    break;
                                                case '00015': //ใบจ่ายโอน (คลัง)
                                                    var tMsgDesc1     = 'เอกสารใบจ่ายโอน (คลัง)';
                                                    break;
                                                case '00016': //ใบรับโอน (คลัง)
                                                    var tMsgDesc1     = 'เอกสารใบรับโอน (คลัง)';
                                                    break;
                                                case '00017': //ใบโอนสินค้าระหว่างคลัง
                                                    var tMsgDesc1     = 'เอกสารใบโอนสินค้าระหว่างคลัง';
                                                    break;
                                                case '00008': //ใบจ่ายโอน (สาขา) [มีแล้ว]
                                                    var tMsgDesc1     = 'เอกสารใบจ่ายโอน (สาขา)';
                                                    break;
                                                case '00009': //ใบรับโอน (สาขา) [มีแล้ว]
                                                    var tMsgDesc1     = 'เอกสารใบรับโอน (สาขา)';
                                                    break;
                                                case '00012': //ใบโอนสินค้าระหว่างสาขา [มีแล้ว]
                                                    var tMsgDesc1     = 'เอกสารใบโอนสินค้าระหว่างสาขา';
                                                    break;
                                                case '00011': //ใบรับของ [มีแล้ว]
                                                    var tMsgDesc1     = 'เอกสารใบรับของ';
                                                    break;
                                                case '00018': //ใบลดหนี้ (แบบมีสินค้า)
                                                    var tMsgDesc1     = 'เอกสารใบลดหนี้';
                                                    break;
                                                case '00019': //ใบนัดหมาย
                                                    var tMsgDesc1     = 'เอกสารใบนัดหมาย';
                                                    break;
                                                case '00020': //ใบจองสินค้า
                                                    var tMsgDesc1     = 'เอกสารใบจองสินค้า';
                                                    break;
                                                case '00021': //ใบรับรถ
                                                    var tMsgDesc1     = 'เอกสารใบรับรถ';
                                                    break;
                                            }
                                            
                                            var nNumber = i+1;
                                            tHTML += '<tr>';
                                            tHTML += '<td>'+nNumber+'</td>';
                                            tHTML += '<td>'+aItem[i].rtBchName+'</td>';
                                            tHTML += '<td>'+tMsgDesc1+'</td>';
                                            tHTML += '<td>'+aItem[i].rtDocNo+'</td>';
                                            tHTML += '<td>'+aItem[i].rdDateFormat+'</td>';
                                            tHTML += '<tr>';
                                        }
                                    }

                                    //เปิด Table
                                    $('#otbInterfaceExportDocNotApv tbody').html('');
                                    $('#otbInterfaceExportDocNotApv tbody').append(tHTML);
                                    $('#odvHistoryDocumentNotApv').show();
                                }else if(tSpcAPICode == '00028'){ //ส่งออกรายได้ขายแยกตามประเภทการชำระเงิน(SAP)
                                    $('#odvInterfaceExportTextFile').modal('show');
                                }
                            }
                        }else{
                            if(tSpcAPICode == '00030' || tSpcAPICode == '00028'){ 
                                if(tSpcAPICode == '00030'){ //ต้นทุน FIT Auto (SAP + BIGDATA) จะต้องวิ่งเข้าไปหาเอกสารตรวจนับก่อน
                                    var tTextWarning = 'พบเอกสารที่มีผลกับสต็อกค้างอนุมัติ ระบบไม่สามารถส่งออกรายการต้นทุนได้ ยืนยันที่จะดำเนินการในหัวข้อรายการอื่นๆ ต่อหรือไม่ ?';
                                }else if(tSpcAPICode == '00028'){ //ส่งออกรายได้ขายแยกตามประเภทการชำระเงิน(SAP)
                                    var tTextWarning = 'พบเอกสารการขายยังไม่ถูกส่งมาที่สาขา ระบบไม่สามารถทำการส่งออกได้ ยืนยันที่จะดำเนินการในหัวข้อรายการอื่นๆ ต่อหรือไม่ ?';
                                }
                            }

                            //ถ้าเลือกเส้นสาม และเส้นอื่นๆ ด้วย
                            $('#ospExportCostIsNullButCanMoveOn').text(tTextWarning);
                            $('#odvInterfaceExportCostIsNullButCanMoveOn').modal('show');
                        }
                        return;
                    }else{
                        JSxIFXCallRabbitMQToExport('confirm');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JSxIFXCallRabbitMQToExport('confirm');
        }

        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //ส่ง Noti ว่ามีเอกสารค้างอะไรบ้าง
    function JSxIFXSendNotiForDocumentNotApv(){
        /*var tSpcCheck   = false;
        var tSpcAPICode = '';
        $('.progress-bar-chekbox:checked').each(function(i, obj) {
            var tApiCode = $(this).parent().parent().attr('data-apicode');
            if(tApiCode == '00030'){ //ต้นทุน FIT Auto (SAP + BIGDATA) จะต้องวิ่งเข้าไปหาเอกสารตรวจนับก่อน
                tSpcCheck   = true;
                tSpcAPICode = tApiCode;
                return;
            }
        });

        if(tSpcCheck == true){
            $.ajax({
                type    : "POST",
                url     : "interfaceexportSendNotiForDocNotApv",
                data    : {
                    'tBCHCodeFrm'   : $('#oetExBchCodeFrm'+tSpcAPICode).val(),
                    'tBCHCodeTo'    : $('#oetExBchCodeTo'+tSpcAPICode).val(),
                    'tMonth'        : $('#ocmExMonth'+tSpcAPICode + ' option:selected').val(),
                    'tYear'         : $('#ocmExYear'+tSpcAPICode + ' option:selected').val(),
                },
                cache   : false,
                timeout : 0,
                success : function(tResult) {

                    var oResult = JSON.parse(tResult);
                    if(oResult.rtCode == 1){
                        var aItem = oResult.raItems;
                        var tHTML = '';
                        for(var i=0; i<aItem.length; i++){
                            switch (aItem[i].rnCodeNoti) {
                                case '00013': //ใบรับเข้า (คลัง)
                                    var tMsgDesc1     = 'เอกสารใบรับเข้า (คลัง)';
                                    break;
                                case '00014': //ใบเบิกออก (คลัง)
                                    var tMsgDesc1     = 'เอกสารใบเบิกออก (คลัง)';
                                    break;
                                case '00015': //ใบจ่ายโอน (คลัง)
                                    var tMsgDesc1     = 'เอกสารใบจ่ายโอน (คลัง)';
                                    break;
                                case '00016': //ใบรับโอน (คลัง)
                                    var tMsgDesc1     = 'เอกสารใบรับโอน (คลัง)';
                                    break;
                                case '00017': //ใบโอนสินค้าระหว่างคลัง
                                    var tMsgDesc1     = 'เอกสารใบโอนสินค้าระหว่างคลัง';
                                    break;
                                case '00008': //ใบจ่ายโอน (สาขา) [มีแล้ว]
                                    var tMsgDesc1     = 'เอกสารใบจ่ายโอน (สาขา)';
                                    break;
                                case '00009': //ใบรับโอน (สาขา) [มีแล้ว]
                                    var tMsgDesc1     = 'เอกสารใบรับโอน (สาขา)';
                                    break;
                                case '00012': //ใบโอนสินค้าระหว่างสาขา [มีแล้ว]
                                    var tMsgDesc1     = 'เอกสารใบโอนสินค้าระหว่างสาขา';
                                    break;
                                case '00011': //ใบรับของ [มีแล้ว]
                                    var tMsgDesc1     = 'เอกสารใบรับของ';
                                    break;
                                case '00018': //ใบลดหนี้ (แบบมีสินค้า)
                                    var tMsgDesc1     = 'เอกสารใบลดหนี้';
                                    break;
                                case '00019': //ใบนัดหมาย
                                    var tMsgDesc1     = 'เอกสารใบนัดหมาย';
                                    break;
                                case '00020': //ใบจองสินค้า
                                    var tMsgDesc1     = 'เอกสารใบจองสินค้า';
                                    break;
                                case '00021': //ใบรับรถ
                                    var tMsgDesc1     = 'เอกสารใบรับรถ';
                                    break;
                            }

                            tHTML += '<tr>';
                            tHTML += '<td>'+aItem[i].rtBchName+'</td>';
                            tHTML += '<td>'+tMsgDesc1+'</td>';
                            tHTML += '<td>'+aItem[i].rtDocNo+'</td>';
                            tHTML += '<td>'+aItem[i].rdDate+'</td>';
                            tHTML += '<tr>';
                        }
                    }

                    //เปิด Table
                    $('#otbInterfaceExportDocNotApv tbody').html('');
                    $('#otbInterfaceExportDocNotApv tbody').append(tHTML);
                    $('#odvHistoryDocumentNotApv').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }*/
    }

    function JSxIFXCallRabbitMQToExport(ptType){
        if(ptType == 'remove'){
            //กรณีไม่ตรงเงื่อนไข ต้นทุน FIT Auto (SAP + BIGDATA)
            //ส่งออกรายได้ขายแยกตามประเภทการชำระเงิน(SAP)	
            $('.xCNCheckbox00030').prop('checked',false)
            $('.xCNCheckbox00028').prop('checked',false)
        }

        $.ajax({
            type    : "POST",
            url     : "interfaceexportAction",
            data    : $('#ofmInterfaceExport').serialize()+"&ptTypeEvent="+'getpassword',
            cache   : false,
            Timeout : 0,
            success : function(tResult){
                var aResult = JSON.parse(tResult);
                if(aResult.tHost == '' || aResult.tPort == ''  || aResult.tPassword == '' || aResult.tUser == '' || aResult.tVHost == ""){
                    alert('Connect ใน ตั้งค่า Config ไม่ครบ');
                    return;
                }else{
                    var tPassword = JCNtAES128DecryptData(aResult.tPassword,'5YpPTypXtwMML$u@','zNhQ$D%arP6U8waL');

                    $.ajax({
                        type    : "POST",
                        url     : "interfaceexportAction",
                        data    : $('#ofmInterfaceExport').serialize()+"&ptTypeEvent="+'confirm'+'&tPassword='+tPassword,
                        cache   : false,
                        Timeout : 0,
                        success: function(tResult){
                            $('#obtInterfaceExportConfirm').attr('disabled', true);
                            JCNxCloseLoading();
                            $('#odvInterfaceEmportSuccess').modal('show');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ส่งออก
    function JSxIFXExportDocumentNotApv(){
        window.open('interfaceexportExportForDocNotApv','_blank');
    }

