<script type="text/javascript">

    // Functionality    : เปิด Panel ส่วนลด
    // Parameters       : route
    // Creator          : 14/10/2021 Wasin
    function JSxJR1OpenDisChgPanel(poParams){

        $("#odvJR1DisChgHDTable").html('');
        $("#odvJR1DisChgDTTable").html('');
        if(poParams.DisChgType  == 'disChgHD'){
            $('#ohdJR1DisChgType').val('disChgHD');
            $(".xWJR1DisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharging');?>');
            JSxJR1DisChgHDList(1);
        }

        if(poParams.DisChgType  == 'disChgDT'){
            $('#ohdJR1DisChgType').val('disChgDT');
            $(".xWJR1DisChgHeadPanel").text('<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharginglist');?>');
            JSxJR1DisChgDTList(1);
        }

        $('#odvJR1DisChgPanel').modal({backdrop: 'static', keyboard: false})  
        $('#odvJR1DisChgPanel').modal('show');
    }

    // Functionality    : Call HDDis
    // Parameters       : route
    // Creator          : 14/10/2021 Wasin
    function JSxJR1DisChgHDList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type    : "POST",
            url     : "docJR1DisChgHDList",
            data    : {
                'tBCHCode'          : $('#ohdJR1BchCode').val(),
                'tDocNo'            : $('#oetJR1DocNo').val(),
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache   : false,
            timeout : 0,
            success : function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvJR1DisChgHDTable").html(oResult.tJR1ViewDataTableList);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality    : Call DTDis
    // Parameters       : route
    // Creator          : 14/10/2021 Wasin
    function JSxJR1DisChgDTList(pnPage){
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type    : "POST",
            url     : "docJR1DisChgDTList",
            data    : {
                'tBCHCode'          : $('#ohdJR1BchCode').val(),
                'tDocNo'            : $('#oetJR1DocNo').val(),
                'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                'oAdvanceSearch'    : oAdvanceSearch,
                'nPageCurrent'      : nPageCurrent
            },
            cache: false,
            timeout: 0,
            success: function (tResult){
                var oResult = JSON.parse(tResult);
                $("#odvJR1DisChgDTTable").html(oResult.tJR1ViewDataTableList);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality    : เปลี่ยนหน้า Pagenation Modal HD Dis/Chg 
    // Parameters       : Event Click Pagenation Modal Dis/Chg HD 
    // Creator          : 14/10/2021 Wasin
    function JSvJR1DisChgHDClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvJR1HDList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvJR1HDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                    break;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvJR1HDList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxJR1DisChgHDList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : เปลี่ยนหน้า Pagenation Modal DT Dis/Chg 
    // Parameters       : Event Click Pagenation Modal Dis/Chg DT 
    // Creator          : 14/10/2021 Wasin
    function JSvJR1DisChgDTClickPage(ptPage){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var nPageCurrent    = "";
            switch(ptPage){
                case "next":
                    //กดปุ่ม Next
                    $("#odvJR1DTList .xWBtnNext").addClass("disabled");
                    nPageOld        = $("#odvJR1DTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                case "previous":
                    //กดปุ่ม Previous
                    nPageOld        = $("#odvJR1DTList .xWPage .active").text(); // Get เลขก่อนหน้า
                    nPageNew        = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent    = nPageNew;
                break;
                default:
                    nPageCurrent    = ptPage;
            }
            JSxJR1DisChgDTList(nPageCurrent);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : คำนวณ ส่วนลด
    // Parameters       : -
    // Creator          : 14/10/2021 Wasin
    function JSxJR1CalcDisChg(){
        var bLimitBeforeDisChg  = true;
        $('.xWJR1DisChgTrTag').each(function(index){
            if($('.xWJR1DisChgTrTag').length == 1){
                $('img.xWJR1DisChgRemoveIcon').first().attr('onclick','JSxJR1ResetDisChgRemoveRow(this)').css('opacity', '1');
            }else{
                $('img.xWJR1DisChgRemoveIcon').first().attr('onclick','').css('opacity','0.2');
            }

            if(bLimitBeforeDisChg){
                if(JCNbJR1IsDisChgType('disChgDT')){
                    let cBeforeDisChg = (parseFloat(DisChgDataRowDT.tQty) * parseFloat(DisChgDataRowDT.tSetPrice))
                    $(this).find('td label.xWJR1DisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
                if(JCNbJR1IsDisChgType('disChgHD')){
                    let cBeforeDisChg = $('#olbJR1SumFCXtdNet').text();
                    $(this).find('td label.xWJR1DisChgBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
                }
            }

            bLimitBeforeDisChg = false;

            var cCalc;
            var nDisChgType         = $(this).find('td select.xWJR1DisChgType').val();
            var cDisChgNum          = $(this).find('td input.xWJR1DisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xWJR1DisChgBeforeDisChg').text());
            var cDisChgValue        = $(this).find('td label.xWJR1DisChgValue').text();
            var cDisChgAfterDisChg  = $(this).find('td label.xWJR1DisChgAfterDisChg').text();

            if(nDisChgType == 1){ // ลดบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xWJR1DisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 2){ // ลด %
                var cDisChgPercent  = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xWJR1DisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }
            
            if(nDisChgType == 3){ // ชาร์จบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xWJR1DisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 4){ // ชาร์ท %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xWJR1DisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xWJR1DisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xWJR1DisChgBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }

    // Functionality    : ตรวจสอบว่ามีแถวอยู่หรือไม่ ในการทำรายการลดชาร์จ
    // Parameters       : -
    // Creator          : 14/10/2021 Wasin
    function JSbJR1HasDisChgRow(){
        var bStatus     = false;
        var nRowCount   = $('.xWJR1DisChgTrTag').length;
        if(nRowCount > 0){
            bStatus = true;
        }
        return bStatus;
    }

    // Functionality    : Set Row ข้อมูลลดชาร์ทในตาราง Modal Dis/Chg
    // Parameters       : -
    // Creator          : 14/10/2021 Wasin
    function JStJR1SetTrBody(pcBeforeDisChg, pcDisChgValue, pcAfterDisChg){
        let tTemplate   = $("#oscJR1TrBodyTemplate").html();
        let oData       = {
            'cBeforeDisChg' : pcBeforeDisChg,
            'cDisChgValue'  : pcDisChgValue,
            'cAfterDisChg'  : pcAfterDisChg
        };
        let tRender     = JStJR1RenderTemplate(tTemplate,oData);
        return tRender;
    }

    // Functionality    : Replace Value to template
    // Parameters       : -
    // Creator          : 14/10/2021 Wasin
    function JStJR1RenderTemplate(tTemplate,oData){
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
    // Creator          : 14/10/2021 Wasin
    function JSxJR1ResetDisChgColIndex(){
        $('.xWJR1DisChgIndex').each(function(index){
            $(this).text(index+1);
        });
    }

    // Functionality    : กำหนดวันที่ เวลา ให้กับแต่ละรายการ ลด/ชาร์จ
    // Parameters       : -
    // Creator          : 14/10/2021 Wasin
    function JCNxJR1DisChgSetCreateAt(poEl){
        $(poEl).parents('tr.xWJR1DisChgTrTag').find('input.xWJR1DisChgCreatedAt').val(moment().format('DD-MM-YYYY HH:mm:ss'));
    }

    // Functionality    : เพิ่มรายการในส่วนลด
    // Parameters       : -
    // Creator          : 14/10/2021 Wasin
    function JCNvJR1AddDisChgRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {

            cSumFCXtdNet = $('#olbJR1SumFCXtdNetAlwDis').val();

            // ส่วนลดท้ายบิล
            if(JCNbJR1IsDisChgType('disChgHD')){
                var tDisChgHDTemplate;
                if(JSbJR1HasDisChgRow()){
                    var oLastRow            = $('.xWJR1DisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWJR1DisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStJR1SetTrBody(cAfterDisChgLastRow,'0.00','0.00');     
                }else{
                    tDisChgHDTemplate       = JStJR1SetTrBody(cSumFCXtdNet,'0.00', '0.00');
                }

                $('#otrJR1DisChgHDNotFound').addClass('xCNHide');
                $('#otbJR1DisChgDataDocHDList tbody').append(tDisChgHDTemplate);
                JSxJR1ResetDisChgColIndex();
                JCNxJR1DisChgSetCreateAt(poEl);
                $('.dischgselectpicker').selectpicker();
            }
            
            // ส่วนลดรายการ
            if(JCNbJR1IsDisChgType('disChgDT')){
                var tDisChgHDTemplate;
                var cSumFCXtdNet    = accounting.formatNumber(DisChgDataRowDT.tNet, 2, ',');
                if(JSbJR1HasDisChgRow()){
                    var oLastRow            = $('.xWJR1DisChgTrTag').last();
                    var cAfterDisChgLastRow = oLastRow.find('td label.xWJR1DisChgAfterDisChg').text();
                    tDisChgHDTemplate       = JStJR1SetTrBody(cAfterDisChgLastRow, '0.00', '0.00');
                }else{
                    tDisChgHDTemplate       = JStJR1SetTrBody(cSumFCXtdNet, '0.00', '0.00');
                }

                $('#otrJR1DisChgDTNotFound').addClass('xCNHide');
                $('#otbJR1DisChgDataDocDTList tbody').append(tDisChgHDTemplate);
                JSxJR1ResetDisChgColIndex();
                $('.dischgselectpicker').selectpicker();
            }
            JSxJR1CalcDisChg();
                    
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality    : ลบรายการใน
    // Parameters       : -
    // Creator          : 14/10/2021 Wasin
    function JSxJR1ResetDisChgRemoveRow(poEl){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $(poEl).parents('.xWJR1DisChgTrTag').remove();
            if(JSbJR1HasDisChgRow()){
                JSxJR1ResetDisChgColIndex();
            }else{
                $('#otrJR1DisChgHDNotFound, #otrJR1DisChgDTNotFound').removeClass('xCNHide');
            }
            JSxJR1CalcDisChg();
        }else{
            JCNxShowMsgSessionExpired();
        }   
    }

    // Functionality    : กดบันทึกส่วนลด
    // Parameters       : Event Click Button Save In Modal
    // Creator          : 14/10/2021 Wasin
    function JSxJR1DisChgSave(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            var aDisChgItems        = [];
            var cBeforeDisChgSum    = 0.00;
            var cAfterDisChgSum     = 0.00;
            var tDisChgHD           = '';

            $('.xWJR1DisChgTrTag').each(function(index){
                var tCreatedAt  = $(this).find('input.xWJR1DisChgCreatedAt').val();
                var nSeqNo      = '';
                var tStaDis     = '';
                if(JCNbJR1IsDisChgType('disChgDT')){
                    nSeqNo  = DisChgDataRowDT.tSeqNo;
                    tStaDis = DisChgDataRowDT.tStadis;
                }
                var cBeforeDisChg   = accounting.unformat($(this).find('td label.xWJR1DisChgBeforeDisChg').text());
                var cAfterDisChg    = accounting.unformat($(this).find('td label.xWJR1DisChgAfterDisChg').text());
                var cDisChgValue    = accounting.unformat($(this).find('td label.xWJR1DisChgValue').text());
                var nDisChgType     = parseInt($(this).find('td select.xWJR1DisChgType').val());
                var cDisChgNum      = accounting.unformat($(this).find('td input.xWJR1DisChgNum').val());
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
            if(JCNbJR1IsDisChgType('disChgHD')){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docJR1AddEditHDDis",
                    data    : {
                        'tBchCode'          : $('#ohdJR1BchCode').val(),
                        'tDocNo'            : $('#oetJR1DocNo').val(),
                        'tVatInOrEx'        : 1, // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){
                            $('#odvJR1DisChgPanel').modal('hide');
                            var nDiscount       = (cAfterDisChgSum-cBeforeDisChgSum);
                            var nJR1DecimalShow = $('#ohdJR1DecimalShow').val();
                            $('#olbJR1SumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(nJR1DecimalShow)));
                            $('#olbJR1DisChgHD').text(tDisChgHD);
                            $('#ohdJR1HiddenDisChgHD').val(tDisChgHD);
                            JSxRendercalculate();
                            JCNxCloseLoading();
                        }else{
                            var tMessageError = aReturnData['tStaMessg'];
                            $('#odvJR1DisChgPanel').modal('hide');
                            FSvCMNSetMsgErrorDialog(tMessageError);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }

            // Check Call In DT
            if(JCNbJR1IsDisChgType('disChgDT')){
                JCNxOpenLoading();
                $.ajax({
                    type    : "POST",
                    url     : "docJR1AddEditDTDis",
                    data    : {
                        'tSeqNo'            : DisChgDataRowDT.tSeqNo,
                        'tBchCode'          : $('#ohdJR1BchCode').val(),
                        'tDocNo'            : $('#oetJR1DocNo').val(),
                        'tVatInOrEx'        : 1, // 1: รวมใน, 2: แยกนอก
                        'tDisChgItems'      : JSON.stringify(aDisChgItems),
                        'tDisChgSummary'    : JSON.stringify(oDisChgSummary)
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(tResult){
                        $('#odvJR1DisChgPanel').modal('hide');
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
    // Creator          : 14/10/2021 Wasin
    function JCNbJR1IsDisChgType(ptDisChgType){
        try{
            var tDisChgType = $('#ohdJR1DisChgType').val();
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
            console.log('JCNbJR1IsDisChgType Error: ', err);
        }
    }

</script>