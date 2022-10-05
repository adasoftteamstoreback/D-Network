<script type="text/javascript">
    
    // Functionality : Add/Update Modal DisChage
    // Parameters : route
    // Creator : 02/07/2019 Wasin(Yoshi)
    // Return : -
    // Return Type : -
    function JSxPXOpenDisChgPanel(poParams){
        $("#odvPXDisChgHDTable").html('');
        $("#odvPXDisChgDTTable").html('');

        if(poParams.DisChgType  == 'disChgHD'){
            $('#ohdPXDisChgType').val('disChgHD');
            $(".xWPXDisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPXAdvDiscountcharging');?>');
            JSxPXDisChgHDList(1);
        }

        if(poParams.DisChgType  == 'disChgDT'){
            $('#ohdPXDisChgType').val('disChgDT');
            $(".xWPXDisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPXAdvDiscountcharginglist');?>');
            JSxPXDisChgDTList(1);
        }

        $('#odvPXDisChgPanel').modal({backdrop: 'static', keyboard: false})  
        $('#odvPXDisChgPanel').modal('show');
    }

    // Functionality : Call PX HD List
    // Parameters : route
    // Creator : 02/07/2019 Wasin(Yoshi)
    // Update : -
    // Return : -
    // Return Type : -
    function JSxPXDisChgHDList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type: "POST",
            url: "docPXDisChgHDList",
            data: {
                'tBCHCode'          : $('#oetPXFrmBchCode').val(),
                'tDocNo'            : $('#oetPXDocNo').val(),
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache: false,
            timeout: 0,
            success: function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvPXDisChgHDTable").html(oResult.tPXViewDataTableList);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality : Call PX Document DTDisChg List
    // Parameters : route
    // Creator : 02/07/2019 Wasin(Yoshi)
    // Return : -
    // Return Type : -
    function JSxPXDisChgDTList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type: "POST",
            url: "docPXDisChgDTList",
            data: {
                'tBCHCode'          : $('#oetPXFrmBchCode').val(),
                'tDocNo'            : $('#oetPXDocNo').val(),
                'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache: false,
            timeout: 0,
            success: function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvPXDisChgDTTable").html(oResult.tPXViewDataTableList);
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
    function JSvPXDisChgHDClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvPXHDList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvPXHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                    break;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvPXHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxPXDisChgHDList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality : เปลี่ยนหน้า Pagenation Modal DT Dis/Chg 
    // Parameters : Event Click Pagenation Modal Dis/Chg DT 
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Return : View Table Dis/Chg DT
    // Return Type : View
    function JSvPXDisChgDTClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvPXDTList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvPXDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvPXDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxPXDisChgDTList(nPageCurrent);
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
    function JSxPXCalcDisChg(){
        var bLimitBeforeDisChg  = true;
        $('.xWPXDisChgTrTag').each(function(index){
            if($('.xWPXDisChgTrTag').length == 1){
                $('img.xWPXDisChgRemoveIcon').first().attr('onclick','JSxPXResetDisChgRemoveRow(this)').css('opacity', '1');
            }else{
                $('img.xWPXDisChgRemoveIcon').first().attr('onclick','').css('opacity','0.2');
            }

            if(bLimitBeforeDisChg){
                if(JCNbPXIsDisChgType('disChgDT')){
                    nPrice = (DisChgDataRowDT.tSetPrice).replace(",", "");
                    let cBeforeDisChg = (parseFloat(DisChgDataRowDT.tQty) * parseFloat(nPrice));
                    $(this).find('td label.xWPXDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
                if(JCNbPXIsDisChgType('disChgHD')){
                    // let cBeforeDisChg = $('label#olbPXSumFCXtdNet').text();
                    let cBeforeDisChg = $('#olbPXSumFCXtdNet').text();
                    $(this).find('td label.xWPXDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
            }

            bLimitBeforeDisChg = false;

            var cCalc;
            var nDisChgType = $(this).find('td select.xWPXDisChgType').val();
            var cDisChgNum  = $(this).find('td input.xWPXDisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xWPXDisChgBeforeDisChg').text());
            var cDisChgValue = $(this).find('td label.xWPXDisChgValue').text();
            var cDisChgAfterDisChg = $(this).find('td label.xWPXDisChgAfterDisChg').text();

            if(nDisChgType == 1){ // ลดบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xWPXDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 2){ // ลด %
                var cDisChgPercent  = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xWPXDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }
            
            if(nDisChgType == 3){ // ชาร์จบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xWPXDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 4){ // ชาร์ท %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xWPXDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xWPXDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xWPXDisChgBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }

    // Functionality : Is Dis Chg Type
    // Parameters : -
    // Creator : 02/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Status true is create page
    // Return Type : Boolean
    function JCNbPXIsDisChgType(ptDisChgType){
        try{
            var tPXDisChgType = $('#ohdPXDisChgType').val();
            var bStatus = false;
            if(ptDisChgType == "disChgHD"){
                if(tPXDisChgType == "disChgHD"){ // No have data
                    bStatus = true;
                }
            }
            if(ptDisChgType == "disChgDT"){
                if(tPXDisChgType == "disChgDT"){ // No have data
                    bStatus = true;
                }
            }
            return bStatus;
        }catch(err){
            console.log('JCNbPXIsCreatePage Error: ', err);
        }
    }

    // Functionality : ตรวจสอบว่ามีแถวอยู่หรือไม่ ในการทำรายการลดชาร์จ
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Status Check Row Dis/Chg
    // Return Type : Boolean
    function JSbPXHasDisChgRow(){
        var bStatus     = false;
        var nRowCount   = $('.xWPXDisChgTrTag').length;
        console.log('nRowDisChgCount: ',nRowCount);
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
    function JStPXSetTrBody(pcBeforeDisChg, pcDisChgValue, pcAfterDisChg){
        console.log("JStPXSetTrBody", pcBeforeDisChg);
        let tTemplate   = $("#oscPXTrBodyTemplate").html();
        let oData       = {
            'cBeforeDisChg' : pcBeforeDisChg,
            'cDisChgValue'  : pcDisChgValue,
            'cAfterDisChg'  : pcAfterDisChg
        };
        let tRender     = JStPXRenderTemplate(tTemplate,oData);
        return tRender;
    }

    // Functionality : Replace Value to template
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : String Template Html Row Dis/Chg
    // Return Type : String
    function JStPXRenderTemplate(tTemplate,oData){
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
    function JSxPXResetDisChgColIndex(){
        $('.xWPXDisChgIndex').each(function(index){
            $(this).text(index+1);
        });
    }


    // Functionality : กำหนดวันที่ เวลา ให้กับแต่ละรายการ ลด/ชาร์จ
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : -
    // Return Type : -
    function JCNxPXDisChgSetCreateAt(poEl){
        $(poEl).parents('tr.xWPXDisChgTrTag').find('input.xWPXDisChgCreatedAt').val(moment().format('DD-MM-YYYY HH:mm:ss'));
        console.log('DATE: ', $( poEl).parents('tr.xWPXDisChgTrTag').find('input.xWPXDisChgCreatedAt').val());    
    }

    // Functionality : Add Row Data Dis/Chg HD And DT
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Row Dis/Chg In Modal
    // Return Type : None
    function JCNvPXAddDisChgRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {

            cSumFCXtdNet = $('#olbPXSumFCXtdNetAlwDis').val();

            // Check Append Row Dis/chg HD
            if(JCNbPXIsDisChgType('disChgHD')){
                var tDisChgHDTemplate;
                if(JSbPXHasDisChgRow()){
                    var oLastRow            = $('.xWPXDisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWPXDisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStPXSetTrBody(cAfterDisChgLastRow,'0.00','0.00');     
                }else{
                    tDisChgHDTemplate       = JStPXSetTrBody(cSumFCXtdNet,'0.00', '0.00');
                }

                $('#otrPXDisChgHDNotFound').addClass('xCNHide');
                $('#otbPXDisChgDataDocHDList tbody').append(tDisChgHDTemplate);
                JSxPXResetDisChgColIndex();
                JCNxPXDisChgSetCreateAt(poEl);
                $('.dischgselectpicker').selectpicker();
            }
            
            // Check Append Row Dis/chg DT
            if(JCNbPXIsDisChgType('disChgDT')){
                console.log('DisChgDataRowDT: ',DisChgDataRowDT);
                var tDisChgHDTemplate;
                var cSumFCXtdNet    = accounting.formatNumber(DisChgDataRowDT.tNet, 2, ',');
                if(JSbPXHasDisChgRow()){
                    var oLastRow            = $('.xWPXDisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWPXDisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStPXSetTrBody(cAfterDisChgLastRow, '0.00', '0.00');
                }else{
                    tDisChgHDTemplate       = JStPXSetTrBody(cSumFCXtdNet, '0.00', '0.00');
                }

                $('#otrPXDisChgDTNotFound').addClass('xCNHide');
                $('#otbPXDisChgDataDocDTList tbody').append(tDisChgHDTemplate);
                JSxPXResetDisChgColIndex();
                $('.dischgselectpicker').selectpicker();
                console.log('cSumFCXtdNet: ', cSumFCXtdNet);
            }
            JSxPXCalcDisChg();
                    
        }else{
            JCNxShowMsgSessionExpired();
        }
    }


    // Functionality : Remove Dis/Chg Row In Modal
    // Parameters : -
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : -
    // Return Type : -
    function JSxPXResetDisChgRemoveRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $(poEl).parents('.xWPXDisChgTrTag').remove();
            if(JSbPXHasDisChgRow()){
                JSxPXResetDisChgColIndex();
            }else{
                $('#otrPXDisChgHDNotFound, #otrPXDisChgDTNotFound').removeClass('xCNHide');
            }
            JSxPXCalcDisChg();
        }else{
            JCNxShowMsgSessionExpired();
        }   
    }

    // Functionality : Functon Save Dis/Chg
    // Parameters : Event Click Button Save In Modal
    // Creator : 03/07/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : -
    // Return Type : None
    function JSxPXDisChgSave(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var aDisChgItems        = [];
            var cBeforeDisChgSum    = 0.00;
            var cAfterDisChgSum     = 0.00;

            $('.xWPXDisChgTrTag').each(function(index){
                var tCreatedAt  = $(this).find('input.xWPXDisChgCreatedAt').val();
                var nSeqNo      = '';
                var tStaDis     = '';
                if(JCNbPXIsDisChgType('disChgDT')){
                    nSeqNo  = DisChgDataRowDT.tSeqNo;
                    tStaDis = DisChgDataRowDT.tStadis;
                }
                var cBeforeDisChg   = accounting.unformat($(this).find('td label.xWPXDisChgBeforeDisChg').text());
                var cAfterDisChg    = accounting.unformat($(this).find('td label.xWPXDisChgAfterDisChg').text());
                var cDisChgValue    = accounting.unformat($(this).find('td label.xWPXDisChgValue').text());
                var nDisChgType     = parseInt($(this).find('td select.xWPXDisChgType').val());
                var cDisChgNum      = accounting.unformat($(this).find('td input.xWPXDisChgNum').val());
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
            });

            var oDisChgSummary  = {
                'cBeforeDisChgSum'  : cBeforeDisChgSum,
                'cAfterDisChgSum'   : cAfterDisChgSum
            };

            // Check Call In HD
            if(JCNbPXIsDisChgType('disChgHD')){
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docPXAddEditHDDis",
                    data: {
                        'tBchCode'          : $('#oetPXFrmBchCode').val(),
                        'tDocNo'            : $('#oetPXDocNo').val(),
                        'tVatInOrEx'        : $('#ocmPXFrmSplInfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){
                            $('#odvPXDisChgPanel').modal('hide');
                            JSvPXLoadPdtDataTableHtml();
                        }else{
                            var tMessageError = aReturnData['tStaMessg'];
                            $('#odvPXDisChgPanel').modal('hide');
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }

            // Check Call In DT
            if(JCNbPXIsDisChgType('disChgDT')){
                JCNxOpenLoading();
                $.ajax({
                    type : "POST",
                    url : "docPXAddEditDTDis",
                    data : {
                        'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                        'tBchCode'          : $('#oetPXFrmBchCode').val(),
                        'tDocNo'            : $('#oetPXDocNo').val(),
                        'tVatInOrEx'        : $('#ocmPXFrmSplInfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult){
                        console.log(tResult);
                        JSvPXLoadPdtDataTableHtml();
                        $('#odvPXDisChgPanel').modal('hide');
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



</script>