<script type="text/javascript">
    
    // Functionality    : เปิด Panel ส่วนลด
    // Parameters       : route
    // Creator          : 02/07/2021 Supawat
    function JSxQTOpenDisChgPanel(poParams){
        $("#odvQTDisChgHDTable").html('');
        $("#odvQTDisChgDTTable").html('');

        if(poParams.DisChgType  == 'disChgHD'){
            $('#ohdQTDisChgType').val('disChgHD');
            $(".xWQTDisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharging');?>');
            JSxQTDisChgHDList(1);
        }

        if(poParams.DisChgType  == 'disChgDT'){
            $('#ohdQTDisChgType').val('disChgDT');
            $(".xWQTDisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharginglist');?>');
            JSxQTDisChgDTList(1);
        }

        $('#odvQTDisChgPanel').modal({backdrop: 'static', keyboard: false})  
        $('#odvQTDisChgPanel').modal('show');
    }

    // Functionality    : Call HDDis
    // Parameters       : route
    // Creator          : 02/07/2021 Supawat
    function JSxQTDisChgHDList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type    : "POST",
            url     : "dcmQuotationDisChgHDList",
            data    : {
                'tBCHCode'          : $('#ohdTQBchCode').val(),
                'tDocNo'            : $('#oetTQDocNo').val(),
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache   : false,
            timeout : 0,
            success : function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvQTDisChgHDTable").html(oResult.tQTViewDataTableList);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality    : Call DTDis
    // Parameters       : route
    // Creator          : 02/07/2021 Supawat
    function JSxQTDisChgDTList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type    : "POST",
            url     : "dcmQuotationDisChgDTList",
            data    : {
                'tBCHCode'          : $('#ohdTQBchCode').val(),
                'tDocNo'            : $('#oetTQDocNo').val(),
                'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache: false,
            timeout: 0,
            success: function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvQTDisChgDTTable").html(oResult.tQTViewDataTableList);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality    : เปลี่ยนหน้า Pagenation Modal HD Dis/Chg 
    // Parameters       : Event Click Pagenation Modal Dis/Chg HD 
    // Creator          : 02/07/2021 Supawat
    function JSvQTDisChgHDClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvQTHDList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvQTHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                    break;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvQTHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxQTDisChgHDList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : เปลี่ยนหน้า Pagenation Modal DT Dis/Chg 
    // Parameters       : Event Click Pagenation Modal Dis/Chg DT 
    // Creator          : 02/07/2021 Supawat
    function JSvQTDisChgDTClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvQTDTList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvQTDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvQTDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxQTDisChgDTList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : คำนวณ ส่วนลด
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JSxQTCalcDisChg(){
        var bLimitBeforeDisChg  = true;
        $('.xWQTDisChgTrTag').each(function(index){
            if($('.xWQTDisChgTrTag').length == 1){
                $('img.xWQTDisChgRemoveIcon').first().attr('onclick','JSxQTResetDisChgRemoveRow(this)').css('opacity', '1');
            }else{
                $('img.xWQTDisChgRemoveIcon').first().attr('onclick','').css('opacity','0.2');
            }

            if(bLimitBeforeDisChg){
                if(JCNbQTIsDisChgType('disChgDT')){
                    let cBeforeDisChg = (parseFloat(DisChgDataRowDT.tQty) * parseFloat(DisChgDataRowDT.tSetPrice))
                    $(this).find('td label.xWQTDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
                if(JCNbQTIsDisChgType('disChgHD')){
                    let cBeforeDisChg = $('#olbTQSumFCXtdNet').text();
                    $(this).find('td label.xWQTDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
            }

            bLimitBeforeDisChg = false;

            var cCalc;
            var nDisChgType         = $(this).find('td select.xWQTDisChgType').val();
            var cDisChgNum          = $(this).find('td input.xWQTDisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xWQTDisChgBeforeDisChg').text());
            var cDisChgValue        = $(this).find('td label.xWQTDisChgValue').text();
            var cDisChgAfterDisChg  = $(this).find('td label.xWQTDisChgAfterDisChg').text();

            if(nDisChgType == 1){ // ลดบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xWQTDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 2){ // ลด %
                var cDisChgPercent  = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xWQTDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }
            
            if(nDisChgType == 3){ // ชาร์จบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xWQTDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 4){ // ชาร์ท %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xWQTDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xWQTDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xWQTDisChgBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }

    // Functionality    : ตรวจสอบว่ามีแถวอยู่หรือไม่ ในการทำรายการลดชาร์จ
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JSbQTHasDisChgRow(){
        var bStatus     = false;
        var nRowCount   = $('.xWQTDisChgTrTag').length;
        if(nRowCount > 0){
            bStatus = true;
        }
        return bStatus;
    }

    // Functionality    : Set Row ข้อมูลลดชาร์ทในตาราง Modal Dis/Chg
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JStQTSetTrBody(pcBeforeDisChg, pcDisChgValue, pcAfterDisChg){
        let tTemplate   = $("#oscQTTrBodyTemplate").html();
        let oData       = {
            'cBeforeDisChg' : pcBeforeDisChg,
            'cDisChgValue'  : pcDisChgValue,
            'cAfterDisChg'  : pcAfterDisChg
        };
        let tRender     = JStQTRenderTemplate(tTemplate,oData);
        return tRender;
    }

    // Functionality    : Replace Value to template
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JStQTRenderTemplate(tTemplate,oData){
        String.prototype.fmt    = function (hash) {
            let tString = this, nKey; 
            for(nKey in hash){
                tString = tString.replace(new RegExp('\\{' + nKey + '\\}', 'gm'), hash[nKey]); 
            }
            return tString;
        };
        let tRender = "";
        tRender     = tTemplate.fmt(oData);
        return tRender;
    }

    // Functionality    : Reset column index in dischg modal
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JSxQTResetDisChgColIndex(){
        $('.xWQTDisChgIndex').each(function(index){
            $(this).text(index+1);
        });
    }

    // Functionality    : กำหนดวันที่ เวลา ให้กับแต่ละรายการ ลด/ชาร์จ
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JCNxQTDisChgSetCreateAt(poEl){
        $(poEl).parents('tr.xWQTDisChgTrTag').find('input.xWQTDisChgCreatedAt').val(moment().format('DD-MM-YYYY HH:mm:ss'));
    }

    // Functionality    : เพิ่มรายการในส่วนลด
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JCNvQTAddDisChgRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {

            cSumFCXtdNet = $('#olbTQSumFCXtdNetAlwDis').val();

            // ส่วนลดท้ายบิล
            if(JCNbQTIsDisChgType('disChgHD')){
                var tDisChgHDTemplate;
                if(JSbQTHasDisChgRow()){
                    var oLastRow            = $('.xWQTDisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWQTDisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStQTSetTrBody(cAfterDisChgLastRow,'0.00','0.00');     
                }else{
                    tDisChgHDTemplate       = JStQTSetTrBody(cSumFCXtdNet,'0.00', '0.00');
                }

                $('#otrQTDisChgHDNotFound').addClass('xCNHide');
                $('#otbQTDisChgDataDocHDList tbody').append(tDisChgHDTemplate);
                JSxQTResetDisChgColIndex();
                JCNxQTDisChgSetCreateAt(poEl);
                $('.dischgselectpicker').selectpicker();
            }
            
            // ส่วนลดรายการ
            if(JCNbQTIsDisChgType('disChgDT')){
                var tDisChgHDTemplate;
                var cSumFCXtdNet    = accounting.formatNumber(DisChgDataRowDT.tNet, 2, ',');
                if(JSbQTHasDisChgRow()){
                    var oLastRow            = $('.xWQTDisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWQTDisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStQTSetTrBody(cAfterDisChgLastRow, '0.00', '0.00');
                }else{
                    tDisChgHDTemplate       = JStQTSetTrBody(cSumFCXtdNet, '0.00', '0.00');
                }

                $('#otrQTDisChgDTNotFound').addClass('xCNHide');
                $('#otbQTDisChgDataDocDTList tbody').append(tDisChgHDTemplate);
                JSxQTResetDisChgColIndex();
                $('.dischgselectpicker').selectpicker();
            }
            JSxQTCalcDisChg();
                    
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : ลบรายการใน
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JSxQTResetDisChgRemoveRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $(poEl).parents('.xWQTDisChgTrTag').remove();
            if(JSbQTHasDisChgRow()){
                JSxQTResetDisChgColIndex();
            }else{
                $('#otrQTDisChgHDNotFound, #otrQTDisChgDTNotFound').removeClass('xCNHide');
            }
            JSxQTCalcDisChg();
        }else{
            JCNxShowMsgSessionExpired();
        }   
    }

    // Functionality    : กดบันทึกส่วนลด
    // Parameters       : Event Click Button Save In Modal
    // Creator          : 02/07/2021 Supawat
    function JSxQTDisChgSave(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var aDisChgItems        = [];
            var cBeforeDisChgSum    = 0.00;
            var cAfterDisChgSum     = 0.00;
            var tDisChgHD           = '';

            $('.xWQTDisChgTrTag').each(function(index){
                var tCreatedAt  = $(this).find('input.xWQTDisChgCreatedAt').val();
                var nSeqNo      = '';
                var tStaDis     = '';
                if(JCNbQTIsDisChgType('disChgDT')){
                    nSeqNo  = DisChgDataRowDT.tSeqNo;
                    tStaDis = DisChgDataRowDT.tStadis;
                }
                var cBeforeDisChg   = accounting.unformat($(this).find('td label.xWQTDisChgBeforeDisChg').text());
                var cAfterDisChg    = accounting.unformat($(this).find('td label.xWQTDisChgAfterDisChg').text());
                var cDisChgValue    = accounting.unformat($(this).find('td label.xWQTDisChgValue').text());
                var nDisChgType     = parseInt($(this).find('td select.xWQTDisChgType').val());
                var cDisChgNum      = accounting.unformat($(this).find('td input.xWQTDisChgNum').val());
                // Dis Chg Summary
                cBeforeDisChgSum    += parseFloat(cBeforeDisChg);
                cAfterDisChgSum     += parseFloat(cAfterDisChg);
                // Dis Chg Text
                var tDisChgTxt = '';
                switch(nDisChgType){
                    case 1 : {
                        tDisChgTxt  = '-' + cDisChgNum;    
                        break;
                    }
                    case 2 : {
                        tDisChgTxt  = '-' + cDisChgNum + '%';
                        break;
                    }
                    case 3 : {
                        tDisChgTxt  = '+' + cDisChgNum;    
                        break;
                    }
                    case 4 : {
                        tDisChgTxt  = '+' + cDisChgNum + '%';    
                        break;
                    }
                    default : {}
                }
                aDisChgItems.push({
                    'cBeforeDisChg' : cBeforeDisChg,
                    'cDisChgValue'  : cDisChgValue,
                    'cAfterDisChg'  : cAfterDisChg,
                    'nDisChgType'   : nDisChgType,
                    'cDisChgNum'    : cDisChgNum,
                    'tDisChgTxt'    : tDisChgTxt,
                    'tCreatedAt'    : tCreatedAt,
                    'nSeqNo'        : nSeqNo,
                    'tStaDis'       : tStaDis
                });

                if(tDisChgHD!=''){
                    tDisChgHD += ','+tDisChgTxt;
                }else{
                    tDisChgHD += tDisChgTxt;
                }
            });

            var oDisChgSummary  = {
                'cBeforeDisChgSum'  : cBeforeDisChgSum,
                'cAfterDisChgSum'   : cAfterDisChgSum
            };

            // Check Call In HD
            if(JCNbQTIsDisChgType('disChgHD')){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "dcmQuotationAddEditHDDis",
                    data    : {
                        'tBchCode'          : $('#ohdTQBchCode').val(),
                        'tDocNo'            : $('#oetTQDocNo').val(),
                        'tVatInOrEx'        : $('#ocmTQfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){
                            $('#odvQTDisChgPanel').modal('hide');

                            var nDiscount       = (cAfterDisChgSum-cBeforeDisChgSum);
                            var nQTODecimalShow = $('#ohdTQDecimalShow').val();
                            $('#olbTQSumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(nQTODecimalShow)));
                            $('#olbTQDisChgHD').text(tDisChgHD);
                            $('#ohdTQDisChgHD').val(tDisChgHD);
                           
                            JSxRendercalculate();
                            JCNxCloseLoading();
                        }else{
                            var tMessageError = aReturnData['tStaMessg'];
                            $('#odvQTDisChgPanel').modal('hide');
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }

            // Check Call In DT
            if(JCNbQTIsDisChgType('disChgDT')){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "dcmQuotationAddEditDTDis",
                    data    : {
                        'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                        'tBchCode'          : $('#ohdTQBchCode').val(),
                        'tDocNo'            : $('#oetTQDocNo').val(),
                        'tVatInOrEx'        : $('#ocmTQfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(tResult){

                        $('#odvQTDisChgPanel').modal('hide');

                        //หลังจากบันทึกข้อมูลเเล้ว
                        var nSeq            = DisChgDataRowDT.tSeqNo;
                        var cAfterDisChg    = 0;
                        var tTextDisChgDT   = "";
                        for(var i=0;i<aDisChgItems.length;i++){
                            if(tTextDisChgDT == ""){
                                tTextDisChgDT = aDisChgItems[i].tDisChgTxt;
                            }else{
                                tTextDisChgDT = tTextDisChgDT + "," + aDisChgItems[i].tDisChgTxt;
                            }
                            cAfterDisChg = aDisChgItems[i].cAfterDisChg
                        }

                        $('#xWDisChgDTTmp'+nSeq).text(tTextDisChgDT);

                        if(cAfterDisChg == 0){
                            var nQty    = $('#ohdQty'+nSeq).val();
                            var cPrice  = $('#ohdPrice'+nSeq).val();
                            cAfterDisChg = parseFloat(nQty * cPrice);
                        }
                        $('#ospGrandTotal'+nSeq).text(numberWithCommas(parseFloat(cAfterDisChg).toFixed(2)));
                        $('.xWPdtItemList'+nSeq).attr('data-net',parseFloat(cAfterDisChg).toFixed(2));
                        if($('#olbDisChgHD').text() == ''){
                            $('#ospnetAfterHD'+nSeq).text(parseFloat(cAfterDisChg).toFixed(2));
                            $('.xWPdtItemList'+nSeq).attr('data-netafhd',parseFloat(cAfterDisChg).toFixed(2));
                        }
                        JSxRendercalculate();
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : Is Dis Chg Type
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JCNbQTIsDisChgType(ptDisChgType){
        try{
            var tDisChgType = $('#ohdQTDisChgType').val();
            var bStatus = false;
            if(ptDisChgType == "disChgHD"){
                if(tDisChgType == "disChgHD"){ 
                    bStatus = true;
                }
            }
            if(ptDisChgType == "disChgDT"){
                if(tDisChgType == "disChgDT"){
                    bStatus = true;
                }
            }
            return bStatus;
        }catch(err){
            console.log('JCNbQTIsDisChgType Error: ', err);
        }
    }

</script>