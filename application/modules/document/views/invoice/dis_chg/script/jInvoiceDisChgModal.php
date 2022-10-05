<script type="text/javascript">
    
    // Functionality    : เปิด Panel ส่วนลด
    // Parameters       : route
    // Creator          : 02/07/2021 Supawat
    function JSxIVOpenDisChgPanel(poParams){
        $("#odvIVDisChgHDTable").html('');
        $("#odvIVDisChgDTTable").html('');

        if(poParams.DisChgType  == 'disChgHD'){
            $('#ohdIVDisChgType').val('disChgHD');
            $(".xWIVDisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharging');?>');
            JSxIVDisChgHDList(1);
        }

        if(poParams.DisChgType  == 'disChgDT'){
            $('#ohdIVDisChgType').val('disChgDT');
            $(".xWIVDisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharginglist');?>');
            JSxIVDisChgDTList(1);
        }

        $('#odvIVDisChgPanel').modal({backdrop: 'static', keyboard: false})  
        $('#odvIVDisChgPanel').modal('show');
    }

    // Functionality    : Call HDDis
    // Parameters       : route
    // Creator          : 02/07/2021 Supawat
    function JSxIVDisChgHDList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type    : "POST",
            url     : "docInvoiceDisChgHDList",
            data    : {
                'tBCHCode'          : $('#ohdIVBchCode').val(),
                'tDocNo'            : $('#oetIVDocNo').val(),
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache   : false,
            timeout : 0,
            success : function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvIVDisChgHDTable").html(oResult.tIVViewDataTableList);
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
    function JSxIVDisChgDTList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type    : "POST",
            url     : "docInvoiceDisChgDTList",
            data    : {
                'tBCHCode'          : $('#ohdIVBchCode').val(),
                'tDocNo'            : $('#oetIVDocNo').val(),
                'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache: false,
            timeout: 0,
            success: function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvIVDisChgDTTable").html(oResult.tIVViewDataTableList);
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
    function JSvIVDisChgHDClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvIVHDList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvIVHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                    break;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvIVHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxIVDisChgHDList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : เปลี่ยนหน้า Pagenation Modal DT Dis/Chg 
    // Parameters       : Event Click Pagenation Modal Dis/Chg DT 
    // Creator          : 02/07/2021 Supawat
    function JSvIVDisChgDTClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvIVDTList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvIVDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvIVDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxIVDisChgDTList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : คำนวณ ส่วนลด
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JSxIVCalcDisChg(){
        var bLimitBeforeDisChg  = true;
        $('.xWIVDisChgTrTag').each(function(index){
            if($('.xWIVDisChgTrTag').length == 1){
                $('img.xWIVDisChgRemoveIcon').first().attr('onclick','JSxIVResetDisChgRemoveRow(this)').css('opacity', '1');
            }else{
                $('img.xWIVDisChgRemoveIcon').first().attr('onclick','').css('opacity','0.2');
            }

            if(bLimitBeforeDisChg){
                if(JCNbIVIsDisChgType('disChgDT')){
                    let cBeforeDisChg = (parseFloat(DisChgDataRowDT.tQty) * parseFloat(DisChgDataRowDT.tSetPrice))
                    $(this).find('td label.xWIVDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
                if(JCNbIVIsDisChgType('disChgHD')){
                    let cBeforeDisChg = $('#olbIVSumFCXtdNet').text();
                    $(this).find('td label.xWIVDisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
            }

            bLimitBeforeDisChg = false;

            var cCalc;
            var nDisChgType         = $(this).find('td select.xWIVDisChgType').val();
            var cDisChgNum          = $(this).find('td input.xWIVDisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xWIVDisChgBeforeDisChg').text());
            var cDisChgValue        = $(this).find('td label.xWIVDisChgValue').text();
            var cDisChgAfterDisChg  = $(this).find('td label.xWIVDisChgAfterDisChg').text();

            if(nDisChgType == 1){ // ลดบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xWIVDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 2){ // ลด %
                var cDisChgPercent  = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xWIVDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }
            
            if(nDisChgType == 3){ // ชาร์จบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xWIVDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 4){ // ชาร์ท %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xWIVDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xWIVDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xWIVDisChgBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }

    // Functionality    : ตรวจสอบว่ามีแถวอยู่หรือไม่ ในการทำรายการลดชาร์จ
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JSbIVHasDisChgRow(){
        var bStatus     = false;
        var nRowCount   = $('.xWIVDisChgTrTag').length;
        if(nRowCount > 0){
            bStatus = true;
        }
        return bStatus;
    }

    // Functionality    : Set Row ข้อมูลลดชาร์ทในตาราง Modal Dis/Chg
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JStIVSetTrBody(pcBeforeDisChg, pcDisChgValue, pcAfterDisChg){
        let tTemplate   = $("#oscIVTrBodyTemplate").html();
        let oData       = {
            'cBeforeDisChg' : pcBeforeDisChg,
            'cDisChgValue'  : pcDisChgValue,
            'cAfterDisChg'  : pcAfterDisChg
        };
        let tRender     = JStIVRenderTemplate(tTemplate,oData);
        return tRender;
    }

    // Functionality    : Replace Value to template
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JStIVRenderTemplate(tTemplate,oData){
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
    function JSxIVResetDisChgColIndex(){
        $('.xWIVDisChgIndex').each(function(index){
            $(this).text(index+1);
        });
    }

    // Functionality    : กำหนดวันที่ เวลา ให้กับแต่ละรายการ ลด/ชาร์จ
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JCNxIVDisChgSetCreateAt(poEl){
        $(poEl).parents('tr.xWIVDisChgTrTag').find('input.xWIVDisChgCreatedAt').val(moment().format('DD-MM-YYYY HH:mm:ss'));
    }

    // Functionality    : เพิ่มรายการในส่วนลด
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JCNvIVAddDisChgRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {

            cSumFCXtdNet = $('#olbIVSumFCXtdNetAlwDis').val();

            // ส่วนลดท้ายบิล
            if(JCNbIVIsDisChgType('disChgHD')){
                var tDisChgHDTemplate;
                if(JSbIVHasDisChgRow()){
                    var oLastRow            = $('.xWIVDisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWIVDisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStIVSetTrBody(cAfterDisChgLastRow,'0.00','0.00');     
                }else{
                    tDisChgHDTemplate       = JStIVSetTrBody(cSumFCXtdNet,'0.00', '0.00');
                }

                $('#otrIVDisChgHDNotFound').addClass('xCNHide');
                $('#otbIVDisChgDataDocHDList tbody').append(tDisChgHDTemplate);
                JSxIVResetDisChgColIndex();
                JCNxIVDisChgSetCreateAt(poEl);
                $('.dischgselectpicker').selectpicker();
            }
            
            // ส่วนลดรายการ
            if(JCNbIVIsDisChgType('disChgDT')){
                var tDisChgHDTemplate;
                var cSumFCXtdNet    = accounting.formatNumber(DisChgDataRowDT.tNet, 2, ',');
                if(JSbIVHasDisChgRow()){
                    var oLastRow            = $('.xWIVDisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWIVDisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStIVSetTrBody(cAfterDisChgLastRow, '0.00', '0.00');
                }else{
                    tDisChgHDTemplate       = JStIVSetTrBody(cSumFCXtdNet, '0.00', '0.00');
                }

                $('#otrIVDisChgDTNotFound').addClass('xCNHide');
                $('#otbIVDisChgDataDocDTList tbody').append(tDisChgHDTemplate);
                JSxIVResetDisChgColIndex();
                $('.dischgselectpicker').selectpicker();
            }
            JSxIVCalcDisChg();
                    
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : ลบรายการใน
    // Parameters       : -
    // Creator          : 02/07/2021 Supawat
    function JSxIVResetDisChgRemoveRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $(poEl).parents('.xWIVDisChgTrTag').remove();
            if(JSbIVHasDisChgRow()){
                JSxIVResetDisChgColIndex();
            }else{
                $('#otrIVDisChgHDNotFound, #otrIVDisChgDTNotFound').removeClass('xCNHide');
            }
            JSxIVCalcDisChg();
        }else{
            JCNxShowMsgSessionExpired();
        }   
    }

    // Functionality    : กดบันทึกส่วนลด
    // Parameters       : Event Click Button Save In Modal
    // Creator          : 02/07/2021 Supawat
    function JSxIVDisChgSave(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var aDisChgItems        = [];
            var cBeforeDisChgSum    = 0.00;
            var cAfterDisChgSum     = 0.00;
            var tDisChgHD           = '';

            $('.xWIVDisChgTrTag').each(function(index){
                var tCreatedAt  = $(this).find('input.xWIVDisChgCreatedAt').val();
                var nSeqNo      = '';
                var tStaDis     = '';
                if(JCNbIVIsDisChgType('disChgDT')){
                    nSeqNo  = DisChgDataRowDT.tSeqNo;
                    tStaDis = DisChgDataRowDT.tStadis;
                }
                var cBeforeDisChg   = accounting.unformat($(this).find('td label.xWIVDisChgBeforeDisChg').text());
                var cAfterDisChg    = accounting.unformat($(this).find('td label.xWIVDisChgAfterDisChg').text());
                var cDisChgValue    = accounting.unformat($(this).find('td label.xWIVDisChgValue').text());
                var nDisChgType     = parseInt($(this).find('td select.xWIVDisChgType').val());
                var cDisChgNum      = accounting.unformat($(this).find('td input.xWIVDisChgNum').val());
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
            if(JCNbIVIsDisChgType('disChgHD')){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docInvoiceAddEditHDDis",
                    data    : {
                        'tBchCode'          : $('#ohdIVBchCode').val(),
                        'tDocNo'            : $('#oetIVDocNo').val(),
                        'tVatInOrEx'        : $('#ocmIVfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){
                            $('#odvIVDisChgPanel').modal('hide');

                            var nDiscount       = (cAfterDisChgSum-cBeforeDisChgSum);
                            var nIVDecimalShow = $('#ohdIVDecimalShow').val();
                            $('#olbIVSumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(nIVDecimalShow)));
                            $('#olbIVDisChgHD').text(tDisChgHD);
                            $('#ohdIVHiddenDisChgHD').val(tDisChgHD);
                           
                            JSxRendercalculate();
                            JCNxCloseLoading();
                        }else{
                            var tMessageError = aReturnData['tStaMessg'];
                            $('#odvIVDisChgPanel').modal('hide');
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }

            // Check Call In DT
            if(JCNbIVIsDisChgType('disChgDT')){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docInvoiceAddEditDTDis",
                    data    : {
                        'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                        'tBchCode'          : $('#ohdIVBchCode').val(),
                        'tDocNo'            : $('#oetIVDocNo').val(),
                        'tVatInOrEx'        : $('#ocmIVfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(tResult){

                        $('#odvIVDisChgPanel').modal('hide');

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
    function JCNbIVIsDisChgType(ptDisChgType){
        try{
            var tDisChgType = $('#ohdIVDisChgType').val();
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
            console.log('JCNbIVIsDisChgType Error: ', err);
        }
    }

</script>