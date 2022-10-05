<script type="text/javascript">
    
    // Functionality : Add/Update Modal DisChage
    // Parameters : route
    // Creator : 02/07/2019 Wasin(Yoshi)
    // Return : -
    // Return Type : -
    function JSxSOOpenDisChgPanel(poParams){
        $("#odvSODisChgHDTable").html('');
        $("#odvSODisChgDTTable").html('');

        if(poParams.DisChgType  == 'disChgHD'){
            $('#ohdSODisChgType').val('disChgHD');
            $(".xWPIDisChgHeadPanel").text('<?php echo language('document/saleorder/saleorder','tSOAdvDiscountcharging');?>');
            JSxSODisChgHDList(1);
        }

        if(poParams.DisChgType  == 'disChgDT'){
            $('#ohdSODisChgType').val('disChgDT');
            $(".xWPIDisChgHeadPanel").text('<?php echo language('document/saleorder/saleorder','tSOAdvDiscountcharginglist');?>');
            JSxSODisChgDTList(1);
        }

        $('#odvSODisChgPanel').modal({backdrop: 'static', keyboard: false})  
        $('#odvSODisChgPanel').modal('show');
    }

    // Functionality : Call PI HD List
    // Parameters : route
    // Creator : 02/07/2019 Wasin(Yoshi)
    // Update : -
    // Return : -
    // Return Type : -
    function JSxSODisChgHDList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type: "POST",
            url: "dcmSODisChgHDList",
            data: {
                'tDocNo'            : $('#oetSODocNo').val(),
                'tSelectBCH'        : $('#oetSOFrmBchCode').val(),
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent,
                'tSOSesSessionID'   : $('#ohdSesSessionID').val(),
                'tSOUsrCode'        : $('#ohdSOUsrCode').val(),
                'tSOLangEdit'       : $('#ohdSOLangEdit').val(),
                'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
        },
            cache: false,
            timeout: 0,
            success: function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvSODisChgHDTable").html(oResult.tSOViewDataTableList);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                //JSxSODisChgHDList(pnPage);
            }
        });
    }

    //ส่วนลดรายการ
    function JSxSODisChgDTList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type    : "POST",
            url     : "dcmSODisChgDTList",
            data    : {
                'tSelectBCH'        : $('#oetSOFrmBchCode').val(),
                'tDocNo'            : $('#oetSODocNo').val(),
                'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent,
                'tSOSesSessionID'   : $('#ohdSesSessionID').val(),
                'tSOUsrCode'        : $('#ohdSOUsrCode').val(),
                'tSOLangEdit'       : $('#ohdSOLangEdit').val(),
                'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
            },
            cache: false,
            timeout: 0,
            success: function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvSODisChgDTTable").html(oResult.tSOViewDataTableList);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality : เปลี่ยนหน้า Pagenation Modal HD Dis/Chg 
    // Parameters : Event Click Pagenation Modal Dis/Chg HD 
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Return : View Table Dis/Chg HD
    // Return Type : View
    function JSvSODisChgHDClickPage(ptPage){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvSOHDList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvSOHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                    break;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvSOHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxSODisChgHDList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality : เปลี่ยนหน้า Pagenation Modal DT Dis/Chg 
    // Parameters : Event Click Pagenation Modal Dis/Chg DT 
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Return : View Table Dis/Chg DT
    // Return Type : View
    function JSvSODisChgDTClickPage(ptPage){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1; 
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvSODTList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvSODTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvSODTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxSODisChgDTList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality : คำนวณ ส่วนลด
    // Parameters : -
    // Creator : 27/06/2019 piya
    // Last Modified : -
    // Return : -
    // Return Type : -
    function JSxSOCalcDisChg(){
        var bLimitBeforeDisChg  = true;
        $('.xWPIDisChgTrTag').each(function(index){
            if($('.xWPIDisChgTrTag').length == 1){
                $('img.xWPIDisChgRemoveIcon').first().attr('onclick','JSxSOResetDisChgRemoveRow(this)').css('opacity', '1');
            }else{
                $('img.xWPIDisChgRemoveIcon').first().attr('onclick','').css('opacity','0.2');
            }

            if(bLimitBeforeDisChg){
                if(JCNbSOIsDisChgType('disChgDT')){
                    var nValuePrice     = DisChgDataRowDT.tSetPrice.replace(/,/g, '');
                    let cBeforeDisChg   = (parseFloat(DisChgDataRowDT.tQty) * parseFloat(nValuePrice));
                    $(this).find('td label.xWPIDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
                if(JCNbSOIsDisChgType('disChgHD')){
                    let cBeforeDisChg = $('#olbSumFCXtdNetAlwDis').val();
                    $(this).find('td label.xWPIDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
            }

            bLimitBeforeDisChg = false;

            var cCalc;
            var nDisChgType = $(this).find('td select.xWPIDisChgType').val();
            var cDisChgNum  = $(this).find('td input.xWPIDisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xWPIDisChgBeforeDisChg').text());
            var cDisChgValue = $(this).find('td label.xWPIDisChgValue').text();
            var cDisChgAfterDisChg = $(this).find('td label.xWPIDisChgAfterDisChg').text();

            if(nDisChgType == 1){ // ลดบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xWPIDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 2){ // ลด %
                var cDisChgPercent  = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xWPIDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }
            
            if(nDisChgType == 3){ // ชาร์จบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xWPIDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 4){ // ชาร์ท %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xWPIDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xWPIDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xWPIDisChgBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }

    // Functionality : Is Dis Chg Type
    // Parameters : -
    // Creator : 02/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Status true is create page
    // Return Type : Boolean
    function JCNbSOIsDisChgType(ptDisChgType){
        try{
            var tSODisChgType = $('#ohdSODisChgType').val();
            var bStatus = false;
            if(ptDisChgType == "disChgHD"){
                if(tSODisChgType == "disChgHD"){ // No have data
                    bStatus = true;
                }
            }
            if(ptDisChgType == "disChgDT"){
                if(tSODisChgType == "disChgDT"){ // No have data
                    bStatus = true;
                }
            }
            return bStatus;
        }catch(err){
            console.log('JCNbSOIsCreatePage Error: ', err);
        }
    }

    // Functionality : ตรวจสอบว่ามีแถวอยู่หรือไม่ ในการทำรายการลดชาร์จ
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Status Check Row Dis/Chg
    // Return Type : Boolean
    function JSbSOHasDisChgRow(){
        var bStatus     = false;
        var nRowCount   = $('.xWPIDisChgTrTag').length;
        if(nRowCount > 0){
            bStatus = true;
        }
        return bStatus;
    }

    // Functionality : Set Row ข้อมูลลดชาร์ทในตาราง Modal Dis/Chg
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : String Text Html Row Dis/Chg
    // Return Type : String
    function JStSOSetTrBody(pcBeforeDisChg, pcDisChgValue, pcAfterDisChg){
        let tTemplate   = $("#oscSOTrBodyTemplate").html();
        let oData       = {
            'cBeforeDisChg' : pcBeforeDisChg,
            'cDisChgValue'  : pcDisChgValue,
            'cAfterDisChg'  : pcAfterDisChg
        };
        let tRender     = JStSORenderTemplate(tTemplate,oData);
        return tRender;
    }

    // Functionality : Replace Value to template
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : String Template Html Row Dis/Chg
    // Return Type : String
    function JStSORenderTemplate(tTemplate,oData){
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

    // Functionality : Reset column index in dischg modal
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : -
    // Return Type : -
    function JSxSOResetDisChgColIndex(){
        $('.xWPIDisChgIndex').each(function(index){
            $(this).text(index+1);
        });
    }


    // Functionality : กำหนดวันที่ เวลา ให้กับแต่ละรายการ ลด/ชาร์จ
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : -
    // Return Type : -
    function JCNxSODisChgSetCreateAt(poEl){
        $(poEl).parents('tr.xWPIDisChgTrTag').find('input.xWPIDisChgCreatedAt').val(moment().format('DD-MM-YYYY HH:mm:ss'));
    }

    // เพิ่มข้อมูลส่วนลด
    function JCNvSOAddDisChgRow(poEl){

        //หาราคาที่อนุญาติลดเท่านั้น
        var cSumFCXtdNet = $('#olbSumFCXtdNetAlwDis').val();
        
        // Check Append Row Dis/chg HD
        if(JCNbSOIsDisChgType('disChgHD')){
            var tDisChgHDTemplate;
            if(JSbSOHasDisChgRow()){
                var oLastRow            = $('.xWPIDisChgTrTag').last();
                var cAfterDisChgLastRow = oLastRow.find('td label.xWPIDisChgAfterDisChg').text();
                tDisChgHDTemplate       = JStSOSetTrBody(cAfterDisChgLastRow,'0.00','0.00');     
            }else{
                tDisChgHDTemplate       = JStSOSetTrBody(cSumFCXtdNet,'0.00', '0.00');
            }

            $('#otrSODisChgHDNotFound').addClass('xCNHide');
            $('#otbSODisChgDataDocHDList tbody').append(tDisChgHDTemplate);
            JSxSOResetDisChgColIndex();
            JCNxSODisChgSetCreateAt(poEl);
            $('.dischgselectpicker').selectpicker();
        }
        
        // Check Append Row Dis/chg DT
        if(JCNbSOIsDisChgType('disChgDT')){
            var tDisChgHDTemplate;
            var cSumFCXtdNet    = accounting.formatNumber(DisChgDataRowDT.tNet, 2, ',');
            if(JSbSOHasDisChgRow()){
                var oLastRow            = $('.xWPIDisChgTrTag').last();
                var cAfterDisChgLastRow = oLastRow.find('td label.xWPIDisChgAfterDisChg').text();
                tDisChgHDTemplate       = JStSOSetTrBody(cAfterDisChgLastRow, '0.00', '0.00');
            }else{
                tDisChgHDTemplate       = JStSOSetTrBody(cSumFCXtdNet, '0.00', '0.00');
            }

            $('#otrSODisChgDTNotFound').addClass('xCNHide');
            $('#otbSODisChgDataDocDTList tbody').append(tDisChgHDTemplate);
            JSxSOResetDisChgColIndex();
            $('.dischgselectpicker').selectpicker();
        }
        JSxSOCalcDisChg();
    }


    // Functionality : Remove Dis/Chg Row In Modal
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : -
    // Return Type : -
    function JSxSOResetDisChgRemoveRow(poEl){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $(poEl).parents('.xWPIDisChgTrTag').remove();
            if(JSbSOHasDisChgRow()){
                JSxSOResetDisChgColIndex();
            }else{
                $('#otrSODisChgHDNotFound, #otrSODisChgDTNotFound').removeClass('xCNHide');
            }
            JSxSOCalcDisChg();
        }else{
            JCNxShowMsgSessionExpired();
        }   
    }

    // บันทึก
    function JSxSODisChgSave(){
        JCNxOpenLoading();
        var aDisChgItems        = [];
        var cBeforeDisChgSum    = 0.00;
        var cAfterDisChgSum     = 0.00;
        var tDisChgHD           = '';
        $('.xWPIDisChgTrTag').each(function(index){
            var tCreatedAt  = $(this).find('input.xWPIDisChgCreatedAt').val();
            var nSeqNo      = '';
            var tStaDis     = '';
            if(JCNbSOIsDisChgType('disChgDT')){
                nSeqNo  = DisChgDataRowDT.tSeqNo;
                tStaDis = DisChgDataRowDT.tStadis;
            }
            var cBeforeDisChg   = accounting.unformat($(this).find('td label.xWPIDisChgBeforeDisChg').text());
            var cAfterDisChg    = accounting.unformat($(this).find('td label.xWPIDisChgAfterDisChg').text());
            var cDisChgValue    = accounting.unformat($(this).find('td label.xWPIDisChgValue').text());
            var nDisChgType     = parseInt($(this).find('td select.xWPIDisChgType').val());
            var cDisChgNum      = accounting.unformat($(this).find('td input.xWPIDisChgNum').val());
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
        if(JCNbSOIsDisChgType('disChgHD')){
            // JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "dcmSOAddEditHDDis",
                data: {
                    'tBchCode'          : $('#oetSOFrmBchCode').val(),
                    'tDocNo'            : $('#oetSODocNo').val(),
                    'tVatInOrEx'        : $('#ocmSOFrmSplInfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                    'tDisChgItems'      : JSON.stringify(aDisChgItems),
                    'tDisChgSummary'    : JSON.stringify(oDisChgSummary),
                    'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                    'ohdSOUsrCode'        : $('#ohdSOUsrCode').val(),
                    'ohdSOLangEdit'       : $('#ohdSOLangEdit').val(),
                    'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
                    'ohdSOSesUsrBchCode'  : $('#ohdSOSesUsrBchCode').val(),
                },
                cache: false,
                timeout: 0,
                success: function(oResult){
                    var aReturnData = JSON.parse(oResult);
                    if(aReturnData['nStaEvent'] == '1'){
                        $('#odvSODisChgPanel').modal('hide');

                        var nDiscount = (cAfterDisChgSum-cBeforeDisChgSum);
                        $('#olbSumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(2)));
                        $('#olbDisChgHD').text(tDisChgHD);
                        $('#ohdSODisChgHD').val(tDisChgHD);
                        

                        JSxRendercalculate();
                        JCNxCloseLoading();
                        // JSvSOLoadPdtDataTableHtml();
                    }else{
                        var tMessageError = aReturnData['tStaMessg'];
                        $('#odvSODisChgPanel').modal('hide');
                        FSvCMNSetMsgErrorDialog(tMessageError);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }

        // Check Call In DT
        if(JCNbSOIsDisChgType('disChgDT')){
            $.ajax({
                type : "POST",
                url : "dcmSOAddEditDTDis",
                data : {
                    'tSeqNo'                : DisChgDataRowDT.tSeqNo,
                    'tBchCode'              : $('#oetSOFrmBchCode').val(),
                    'tDocNo'                : $('#oetSODocNo').val(),
                    'tVatInOrEx'            : $('#ocmSOFrmSplInfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                    'tDisChgItems'          : JSON.stringify(aDisChgItems),
                    'tDisChgSummary'        : JSON.stringify(oDisChgSummary),
                    'ohdSesSessionID'       : $('#ohdSesSessionID').val(),
                    'ohdSOUsrCode'          : $('#ohdSOUsrCode').val(),
                    'ohdSOLangEdit'         : $('#ohdSOLangEdit').val(),
                    'ohdSesUsrLevel'        : $('#ohdSesUsrLevel').val(),
                    'ohdSOSesUsrBchCode'    : $('#ohdSOSesUsrBchCode').val(),
                },
                cache: false,
                timeout: 0,
                success: function(tResult){
                    $('#odvSODisChgPanel').modal('hide');

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
    }



</script>