<script>   

/**
 * Functionality : Add or Update
 * Parameters : route
 * Creator : 23/05/2019 Piya
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxPNOpenDisChgPanel(poParams) {

    $tDiscountcharg = $('#oetDiscountcharg').val();
    $tDiscountcharginglist  =  $('#oetDiscountcharginglist').val();


    $("#odvPNDisChgHDTable").html('');
    $("#odvPNDisChgDTTable").html('');
    
    if(poParams.DisChgType == 'disChgHD'){
        $('#ohdPNDisChgType').val('disChgHD');
        $(".xWPNDisChgHeadPanel").text($tDiscountcharg);
        JSxPNDisChgHDList(1);
    }
    if(poParams.DisChgType == 'disChgDT'){
        $('#ohdPNDisChgType').val('disChgDT');
        $(".xWPNDisChgHeadPanel").text($tDiscountcharginglist);
        JSxPNDisChgDTList(1);
    }
   
    $('#odvPNDisChgPanel').modal('show');
    
    // console.log('JCNbPNIsDisChgType HD: ', JCNbPNIsDisChgType('disChgHD'));
    // console.log('JCNbPNIsDisChgType DT: ', JCNbPNIsDisChgType('disChgDT'));
    
}

/**
 * Functionality : Call PI HD List
 * Parameters : route
 * Creator : 21/06/2019 Piya
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxPNDisChgHDList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        JCNxOpenLoading();

        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }

        var oAdvanceSearch = ''; // JSoPNGetAdvanceSearchData();

        $.ajax({
            type: "POST",
            url: "docPNDisChgHDList",
            data: $("#ofmAddPN").serialize() + '&' + $("#ofmPNRefPIHDForm").serialize() + '&oAdvanceSearch=' + oAdvanceSearch + '&nPageCurrent=' + nPageCurrent + '&tBchCode=' + $('#oetPNBchCode').val(),
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                try{
                    var oResult = JSON.parse(tResult);
                    $("#odvPNDisChgHDTable").html(oResult.tPIViewDataTableList);
                    JCNxCloseLoading();
                }catch(err){
                    console.log('JSxPNDisChgHDList Error: ', err);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
 * Functionality : Call PI DT List
 * Parameters : route
 * Creator : 21/06/2019 Piya
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxPNDisChgDTList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        JCNxOpenLoading();

        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }

        var oAdvanceSearch = ''; // JSoPNGetAdvanceSearchData();

        $.ajax({
            type: "POST",
            url: "docPNDisChgDTList",
            data: $("#ofmAddPN").serialize() + '&tSeqNo=' + DisChgDataRowDT.tSeqNo + '&oAdvanceSearch=' + oAdvanceSearch + '&nPageCurrent=' + nPageCurrent + '&tBchCode=' + $('#oetPNBchCode').val(),
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                try{
                    var oResult = JSON.parse(tResult);
                    $("#odvPNDisChgDTTable").html(oResult.tPIViewDataTableList);
                    JCNxCloseLoading();
                }catch(err){
                    console.log('JSxPNDisChgDTList Error: ', err);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    
    }else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : เปลี่ยนหน้า pagenation
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvPNDisChgHDClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $("#odvPNDisChgHDList .xWBtnNext").addClass("disabled");
                nPageOld = $("#odvPNDisChgHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $("#odvPNDisChgHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxPNPIHDList(nPageCurrent);
        
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : เปลี่ยนหน้า pagenation
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvPNDisChgDTClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $("#odvPNDisChgDTList .xWBtnNext").addClass("disabled");
                nPageOld = $("#odvPNDisChgDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $("#odvPNDisChgDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxPNPIDTList(nPageCurrent,null);
        
    } else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
 * Functionality : Is Dis Chg Type
 * Parameters : -
 * Creator : 22/05/2019 piya
 * Last Modified : -
 * Return : Status true is create page
 * Return Type : Boolean
 */
function JCNbPNIsDisChgType(ptDisChgType){
    try{
        var tPNDisChgType = $('#ohdPNDisChgType').val();
        var bStatus = false;
        if(ptDisChgType == "disChgHD"){
            if(tPNDisChgType == "disChgHD"){ // No have data
                bStatus = true;
            }
        }
        if(ptDisChgType == "disChgDT"){
            if(tPNDisChgType == "disChgDT"){ // No have data
                bStatus = true;
            }
        }
        return bStatus;
    }catch(err){
        console.log('JCNbPNIsCreatePage Error: ', err);
    }
}

/**
* Functionality : คำนวณ ส่วนลด
* Parameters : -
* Creator : 27/06/2019 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxPNCalcDisChg(){
    // console.log('Begin Cal >>>>>>>>>> ');
    var bLimitBeforeDisChg = true;
    $('.xWPNDisChgTrTag').each(function(index){
        if($('.xWPNDisChgTrTag').length == 1){
            $('img.xWPNDisChgRemoveIcon').first().attr('onclick', 'JSxPNResetDisChgRemoveRow(this)').css('opacity', '1');
        }else{
            $('img.xWPNDisChgRemoveIcon').first().attr('onclick', '').css('opacity', '0.2');
        } 
        
        if(bLimitBeforeDisChg){
            if(JCNbPNIsDisChgType('disChgDT')){
                let cBeforeDisChg = (parseFloat(DisChgDataRowDT.tQty) * parseFloat(DisChgDataRowDT.tSetPrice))
                $(this).first().find('td label.xWPNDisChgBeforeDisChg').text(accounting.formatNumber(cBeforeDisChg, 2, ','));
            }
            if(JCNbPNIsDisChgType('disChgHD')){
                // let cBeforeDisChg = $('label#olbCrdditNoteSumFCXtdNet').text();
                let cBeforeDisChg = $('#olbCrdSumFCXtdNetAlwDis').val();
                $(this).first().find('td label.xWPNDisChgBeforeDisChg').text(accounting.formatNumber(cBeforeDisChg, 2, ','));
            }
        }
        bLimitBeforeDisChg = false;
        
        var cCalc;
        var nDisChgType = $(this).find('td select.xWPNDisChgType').val();
        var cDisChgNum = $(this).find('td input.xWPNDisChgNum').val();
        // console.log('DisChg Type: ', nDisChgType);
        var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xWPNDisChgBeforeDisChg').text());
        var cDisChgValue = $(this).find('td label.xWPNDisChgValue').text();
        var cDisChgAfterDisChg = $(this).find('td label.xWPNDisChgAfterDisChg').text();
        
        if(nDisChgType == 1){ // ลดบาท
            // console.log('cDisChgBeforeDisChg: ', cDisChgBeforeDisChg);
            // console.log('cDisChgNum: ', cDisChgNum);
            cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
            // console.log('cCalc: ', cCalc);
            $(this).find('td label.xWPNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
        }
        
        if(nDisChgType == 2){ // ลด %
            var cDisChgPercent = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
            cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
            $(this).find('td label.xWPNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
        }
        
        if(nDisChgType == 3){ // ชาร์จบาท
            cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
            $(this).find('td label.xWPNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
        }
        
        if(nDisChgType == 4){ // ชาร์ท %
            var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
            cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
            $(this).find('td label.xWPNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
        }
        
        $(this).find('td label.xWPNDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        $(this).next().not('#otrPNDisChgDTNotFound').find('td label.xWPNDisChgBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        
    });
    // console.log('End Cal >>>>>>>>>>');
}

/**
 * Functionality : Calc Dis Chg HD And Add
 * Parameters : -
 * Creator : 22/05/2019 piya
 * Last Modified : -
 * Return : Status true is create page
 * Return Type : Boolean
 */
function JCNvPNAddDisChgRow(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {   

        //เอาเฉพาะราคาที่อนุญาตลดมาคิดเท่านั้น
        cSumFCXtdNet = $('#olbCrdSumFCXtdNetAlwDis').val();

        if(!JSbPNHasDisChgRow()){
            $('#otrPNDisChgDTNotFound').remove();
        }
        
        var tCreatedAt = moment().format('YYYY-MM-DD HH:mm:ss');
        
        if(JCNbPNIsDisChgType('disChgHD')){
            
            var tDisChgHDTemplate;
            // var cSumFCXtdNet = $('#olbCrdditNoteSumFCXtdNet').text();
            if(JSbPNHasDisChgRow()){
                var oLastRow = $('.xWPNDisChgTrTag').last();
                var cAfterDisChgLastRow = oLastRow.find('td label.xWPNDisChgAfterDisChg').text();
                tDisChgHDTemplate = JStPNSetTrBody(cAfterDisChgLastRow, '0.00', '0.00', tCreatedAt);     
            }else{
                tDisChgHDTemplate = JStPNSetTrBody(cSumFCXtdNet, '0.00', '0.00', tCreatedAt);
            }


            $('#otrPNDisChgHDNotFound').addClass('xCNHide');
            $('#otbDisChgDataDocHDList tbody').append(tDisChgHDTemplate);
            JSxPNResetDisChgColIndex();
            $('.dischgselectpicker').selectpicker();
            // console.log('cSumFCXtdNet: ', cSumFCXtdNet);

        }

        if(JCNbPNIsDisChgType('disChgDT')){
            // console.log('DisChgDataRowDT: ', DisChgDataRowDT);
            var tDisChgHDTemplate;
            var cSumFCXtdNet = accounting.formatNumber(DisChgDataRowDT.tNet, 2, ',');
            if(JSbPNHasDisChgRow()){
                var oLastRow = $('.xWPNDisChgTrTag').last();
                var cAfterDisChgLastRow = oLastRow.find('td label.xWPNDisChgAfterDisChg').text();
                tDisChgHDTemplate = JStPNSetTrBody(cAfterDisChgLastRow, '0.00', '0.00', tCreatedAt);     
            }else{
                tDisChgHDTemplate = JStPNSetTrBody(cSumFCXtdNet, '0.00', '0.00', tCreatedAt);
            }


            $('#otrPNDisChgDTNotFound').addClass('xCNHide');
            $('#otbDisChgDataDocDTList tbody').append(tDisChgHDTemplate);
            JSxPNResetDisChgColIndex();
            $('.dischgselectpicker').selectpicker();
            // console.log('cSumFCXtdNet: ', cSumFCXtdNet);
        }

        JSxPNCalcDisChg();
    } else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
 * Functionality : Save Dis Chg to DB
 * Parameters : -
 * Creator : 22/06/2019 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxPNDisChgSave() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        /*if(!JSbPNHasDisChgRow()){
            FSvCMNSetMsgWarningDialog('ไม่พบรายการ ส่วนลด/ชาร์จ');
        }*/

        var aDisChgItems = [];
        var cBeforeDisChgSum = 0.00;
        var cAfterDisChgSum = 0.00;
        $('.xWPNDisChgTrTag').each(function(index){
            
            var tCreatedAt = $(this).find('input.xWPNDisChgCreatedAt').val();
            
            var nSeqNo = '';
            var tStaDis = '';
            if(JCNbPNIsDisChgType('disChgDT')){
                nSeqNo = DisChgDataRowDT.tSeqNo;
                tStaDis = DisChgDataRowDT.tStadis;
            }
            
            var cBeforeDisChg = accounting.unformat($(this).find('td label.xWPNDisChgBeforeDisChg').text());
            var cAfterDisChg = accounting.unformat($(this).find('td label.xWPNDisChgAfterDisChg').text());
            var cDisChgValue = accounting.unformat($(this).find('td label.xWPNDisChgValue').text());
            var nDisChgType = parseInt($(this).find('td select.xWPNDisChgType').val());
            var cDisChgNum = accounting.unformat($(this).find('td input.xWPNDisChgNum').val());
            
            // Dis Chg Summary
            cBeforeDisChgSum += parseFloat(cBeforeDisChg);
            cAfterDisChgSum += parseFloat(cAfterDisChg);
            
            // Dis Chg Text
            var tDisChgTxt = '';
            switch(nDisChgType){
                case 1 : {
                    tDisChgTxt = '-' + cDisChgNum;    
                    break;
                }
                case 2 : {
                    tDisChgTxt = '-' + cDisChgNum + '%';
                    break;
                }
                case 3 : {
                    tDisChgTxt = '+' + cDisChgNum;    
                    break;
                }
                case 4 : {
                    tDisChgTxt = '+' + cDisChgNum + '%';    
                    break;
                }
                default : {}
            }
            
            aDisChgItems.push({
                cBeforeDisChg: cBeforeDisChg,
                cDisChgValue: cDisChgValue,
                cAfterDisChg: cAfterDisChg,
                nDisChgType: nDisChgType,
                cDisChgNum: cDisChgNum,
                tDisChgTxt: tDisChgTxt,
                tCreatedAt: tCreatedAt,
                nSeqNo: nSeqNo,
                tStaDis: tStaDis
            });
            
        });
        
        var oDisChgSummary = {cBeforeDisChgSum: cBeforeDisChgSum, cAfterDisChgSum: cAfterDisChgSum};
        
        if (JCNbPNIsDisChgType('disChgHD')) {
            $.ajax({
                type: "POST",
                url: "docPNAddEditHDDis",
                data: {
                    tBchCode        : $('#oetPNBchCode').val(),
                    tDocNo          : $('#oetPNDocNo').val(),
                    tSplVatType     : JSxPNIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                    tDisChgItems    : JSON.stringify(aDisChgItems),
                    tDisChgSummary  : JSON.stringify(oDisChgSummary)
                },
                cache: false,
                timeout: 5000,
                success: function (tResult) {
                    if (JCNbPNIsDocType('havePdt')) {
                        JSvPNLoadPdtDataTableHtml(1, false);
                    }
                    if (JCNbPNIsDocType('nonePdt')) {
                        JSvPNLoadNonePdtDataTableHtml(1, false);
                    }
                    $('#odvPNDisChgPanel').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }

        if (JCNbPNIsDisChgType('disChgDT')) {
            $.ajax({
                type: "POST",
                url: "docPNAddEditDTDis",
                data: {
                    tBchCode        : $('#oetPNBchCode').val(),
                    tSeqNo          : DisChgDataRowDT.tSeqNo,
                    tDocNo          : $('#oetPNDocNo').val(),
                    tSplVatType     : JSxPNIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                    tDisChgItems    : JSON.stringify(aDisChgItems),
                    tDisChgSummary  : JSON.stringify(oDisChgSummary)
                },
                cache: false,
                timeout: 5000,
                success: function (tResult) {
                    // console.log(tResult);
                    if (JCNbPNIsDocType('havePdt')) {
                        JSvPNLoadPdtDataTableHtml(1, false);
                    }
                    if (JCNbPNIsDocType('nonePdt')) {
                        JSvPNLoadNonePdtDataTableHtml(1, false);
                    }
                    $('#odvPNDisChgPanel').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
        
    }else {
        JCNxShowMsgSessionExpired();
    }
}

/**
* Functionality : Set <tr> body
* Parameters : poOldCard
* Creator : 13/11/2018 piya
* Last Modified : -
* Return : template
* Return Type : string
*/
function JStPNSetTrBody(pcBeforeDisChg, pcDisChgValue, pcAfterDisChg, ptCreatedAt){
    try{
        // console.log("JStPNSetTrBody", pcBeforeDisChg);
        let tTemplate = $("#oscPNTrBodyTemplate").html();
        let oData = {cBeforeDisChg: pcBeforeDisChg, cDisChgValue: pcDisChgValue, cAfterDisChg: pcAfterDisChg, tCreatedAt: ptCreatedAt};
        let tRender = JStPNRenderTemplate(tTemplate, oData);
        return tRender;
    }catch(err){
        console.log("JStPNSetTrBody Error: ", err);
    }
}

/**
* Functionality : Replace value to template
* Parameters : tTemplate, tData
* Creator : 31/10/2018 piya
* Last Modified : -
* Return : view
* Return Type : string
*/
function JStPNRenderTemplate(tTemplate, oData){
    try{
        String.prototype.fmt = function (hash) {
            let tString = this, nKey; 
            for(nKey in hash){
                tString = tString.replace(new RegExp('\\{' + nKey + '\\}', 'gm'), hash[nKey]); 
            }
            return tString;
        };
        let tRender = "";
        tRender = tTemplate.fmt(oData);

        return tRender;
    }catch(err){
        console.log("JStPNRenderTemplate Error: ", err);
    }
}

/**
* Functionality : Reset column index in dischg modal
* Parameters : -
* Creator : 27/06/2019 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxPNResetDisChgColIndex(){
    $('.xWPNDisChgIndex').each(function(index){
        $(this).text(index+1);
    });
}

/**
* Functionality : Remove Dis Chg Row
* Parameters : -
* Creator : 27/06/2019 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxPNResetDisChgRemoveRow(poEl){
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        $(poEl).parents('.xWPNDisChgTrTag').remove();

        if(JSbPNHasDisChgRow()){
            JSxPNResetDisChgColIndex();
        }else{
            var oTrNotFound = $('#oscPNTrNotFoundTemplate').html();
            $('.xWDisChgTBBody').append(oTrNotFound);
        }
        JSxPNCalcDisChg();
        
    } else {
        JCNxShowMsgSessionExpired();
    }    
}

/**
* Functionality : ตรวจสอบว่ามีแถวอยู่หรือไม่ ในการทำรายการลดชาร์จ
* Parameters : -
* Creator : 27/06/2019 piya
* Last Modified : -
* Return : status
* Return Type : boolean
*/
function JSbPNHasDisChgRow(){
    var bStatus = false;
    var nRowCount = $('.xWPNDisChgTrTag').length;
    // console.log('nRowCount: ', nRowCount);
    if(nRowCount > 0){
        bStatus = true;
    }
    return bStatus;
}
</script>
