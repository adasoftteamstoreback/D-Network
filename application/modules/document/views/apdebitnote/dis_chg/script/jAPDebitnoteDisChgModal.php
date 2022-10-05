<script type="text/javascript">   

/**
 * Functionality : Add or Update
 * Parameters : route
 * Creator : 23/05/2019 Piya
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxAPDOpenDisChgPanel(poParams) {
    $tDiscountcharg         = $('#oetDiscountcharg').val();
    $tDiscountcharginglist  =  $('#oetDiscountcharginglist').val();
    $("#odvAPDDisChgHDTable").html('');
    $("#odvAPDDisChgDTTable").html('');
    if(poParams.DisChgType == 'disChgHD'){
        $('#ohdAPDDisChgType').val('disChgHD');
        $(".xWAPDDisChgHeadPanel").text($tDiscountcharg);
        JSxAPDDisChgHDList(1);
    }
    if(poParams.DisChgType == 'disChgDT'){
        $('#ohdAPDDisChgType').val('disChgDT');
        $(".xWAPDDisChgHeadPanel").text($tDiscountcharginglist);
        JSxAPDDisChgDTList(1);
    }
    $('#odvAPDDisChgPanel').modal('show');
}

/**
 * Functionality : Call PI HD List
 * Parameters : route
 * Creator : 21/06/2019 Piya
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxAPDDisChgHDList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = '';
        $.ajax({
            type: "POST",
            url: "docAPDebitnoteDisChgHDList",
            data: $("#ofmAddAPD").serialize() + '&' + $("#ofmAPDRefPIHDForm").serialize() + '&oAdvanceSearch=' + oAdvanceSearch + '&nPageCurrent=' + nPageCurrent + '&tBchCode=' + $('#oetAPDBchCode').val(),
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                try{
                    var oResult = JSON.parse(tResult);
                    $("#odvAPDDisChgHDTable").html(oResult.tPIViewDataTableList);
                    JCNxCloseLoading();
                }catch(err){
                    console.log('JSxAPDDisChgHDList Error: ', err);
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
function JSxAPDDisChgDTList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        JCNxOpenLoading();

        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }

        var oAdvanceSearch = ''; // JSoAPDGetAdvanceSearchData();

        $.ajax({
            type: "POST",
            url: "docAPDebitnoteDisChgDTList",
            data: $("#ofmAddAPD").serialize() + '&tSeqNo=' + DisChgDataRowDT.tSeqNo + '&oAdvanceSearch=' + oAdvanceSearch + '&nPageCurrent=' + nPageCurrent + '&tBchCode=' + $('#oetAPDBchCode').val(),
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                try{
                    var oResult = JSON.parse(tResult);
                    $("#odvAPDDisChgDTTable").html(oResult.tPIViewDataTableList);
                    JCNxCloseLoading();
                }catch(err){
                    console.log('JSxAPDDisChgDTList Error: ', err);
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
function JSvAPDDisChgHDClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $("#odvAPDDisChgHDList .xWBtnNext").addClass("disabled");
                nPageOld = $("#odvAPDDisChgHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $("#odvAPDDisChgHDList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxAPDPIHDList(nPageCurrent);
        
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
function JSvAPDDisChgDTClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $("#odvAPDDisChgDTList .xWBtnNext").addClass("disabled");
                nPageOld = $("#odvAPDDisChgDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $("#odvAPDDisChgDTList .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxAPDPIDTList(nPageCurrent,null);
        
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
function JCNbAPDIsDisChgType(ptDisChgType){
    try{
        var tAPDDisChgType = $('#ohdAPDDisChgType').val();
        var bStatus = false;
        if(ptDisChgType == "disChgHD"){
            if(tAPDDisChgType == "disChgHD"){ // No have data
                bStatus = true;
            }
        }
        if(ptDisChgType == "disChgDT"){
            if(tAPDDisChgType == "disChgDT"){ // No have data
                bStatus = true;
            }
        }
        return bStatus;
    }catch(err){
        console.log('JCNbAPDIsCreatePage Error: ', err);
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
function JSxAPDCalcDisChg(){
    // console.log('Begin Cal >>>>>>>>>> ');
    var bLimitBeforeDisChg = true;
    $('.xWAPDDisChgTrTag').each(function(index){
        if($('.xWAPDDisChgTrTag').length == 1){
            $('img.xWAPDDisChgRemoveIcon').first().attr('onclick', 'JSxAPDResetDisChgRemoveRow(this)').css('opacity', '1');
        }else{
            $('img.xWAPDDisChgRemoveIcon').first().attr('onclick', '').css('opacity', '0.2');
        } 
        
        if(bLimitBeforeDisChg){
            if(JCNbAPDIsDisChgType('disChgDT')){
                let cBeforeDisChg = (parseFloat(DisChgDataRowDT.tQty) * parseFloat(DisChgDataRowDT.tSetPrice))
                $(this).first().find('td label.xWAPDDisChgBeforeDisChg').text(accounting.formatNumber(cBeforeDisChg, 2, ','));
            }
            if(JCNbAPDIsDisChgType('disChgHD')){
                // let cBeforeDisChg = $('label#olbCrdditNoteSumFCXtdNet').text();
                let cBeforeDisChg = $('#olbCrdSumFCXtdNetAlwDis').val();
                $(this).first().find('td label.xWAPDDisChgBeforeDisChg').text(accounting.formatNumber(cBeforeDisChg, 2, ','));
            }
        }
        bLimitBeforeDisChg = false;
        
        var cCalc;
        var nDisChgType = $(this).find('td select.xWAPDDisChgType').val();
        var cDisChgNum = $(this).find('td input.xWAPDDisChgNum').val();
        // console.log('DisChg Type: ', nDisChgType);
        var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xWAPDDisChgBeforeDisChg').text());
        var cDisChgValue = $(this).find('td label.xWAPDDisChgValue').text();
        var cDisChgAfterDisChg = $(this).find('td label.xWAPDDisChgAfterDisChg').text();
        
        if(nDisChgType == 1){ // ลดบาท
            // console.log('cDisChgBeforeDisChg: ', cDisChgBeforeDisChg);
            // console.log('cDisChgNum: ', cDisChgNum);
            cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
            // console.log('cCalc: ', cCalc);
            $(this).find('td label.xWAPDDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
        }
        
        if(nDisChgType == 2){ // ลด %
            var cDisChgPercent = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
            cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
            $(this).find('td label.xWAPDDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
        }
        
        if(nDisChgType == 3){ // ชาร์จบาท
            cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
            $(this).find('td label.xWAPDDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
        }
        
        if(nDisChgType == 4){ // ชาร์ท %
            var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
            cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
            $(this).find('td label.xWAPDDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
        }
        
        $(this).find('td label.xWAPDDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        $(this).next().not('#otrAPDDisChgDTNotFound').find('td label.xWAPDDisChgBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        
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
function JCNvAPDAddDisChgRow(){
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {   

        //เอาเฉพาะราคาที่อนุญาตลดมาคิดเท่านั้น
        cSumFCXtdNet = $('#olbCrdSumFCXtdNetAlwDis').val();

        if(!JSbAPDHasDisChgRow()){
            $('#otrAPDDisChgDTNotFound').remove();
        }
        
        var tCreatedAt = moment().format('YYYY-MM-DD HH:mm:ss');
        
        if(JCNbAPDIsDisChgType('disChgHD')){
            
            var tDisChgHDTemplate;
            // var cSumFCXtdNet = $('#olbCrdditNoteSumFCXtdNet').text();
            if(JSbAPDHasDisChgRow()){
                var oLastRow = $('.xWAPDDisChgTrTag').last();
                var cAfterDisChgLastRow = oLastRow.find('td label.xWAPDDisChgAfterDisChg').text();
                tDisChgHDTemplate = JStAPDSetTrBody(cAfterDisChgLastRow, '0.00', '0.00', tCreatedAt);     
            }else{
                tDisChgHDTemplate = JStAPDSetTrBody(cSumFCXtdNet, '0.00', '0.00', tCreatedAt);
            }


            $('#otrAPDDisChgHDNotFound').addClass('xCNHide');
            $('#otbDisChgDataDocHDList tbody').append(tDisChgHDTemplate);
            JSxAPDResetDisChgColIndex();
            $('.dischgselectpicker').selectpicker();
            // console.log('cSumFCXtdNet: ', cSumFCXtdNet);

        }

        if(JCNbAPDIsDisChgType('disChgDT')){
            // console.log('DisChgDataRowDT: ', DisChgDataRowDT);
            var tDisChgHDTemplate;
            var cSumFCXtdNet = accounting.formatNumber(DisChgDataRowDT.tNet, 2, ',');
            if(JSbAPDHasDisChgRow()){
                var oLastRow = $('.xWAPDDisChgTrTag').last();
                var cAfterDisChgLastRow = oLastRow.find('td label.xWAPDDisChgAfterDisChg').text();
                tDisChgHDTemplate = JStAPDSetTrBody(cAfterDisChgLastRow, '0.00', '0.00', tCreatedAt);     
            }else{
                tDisChgHDTemplate = JStAPDSetTrBody(cSumFCXtdNet, '0.00', '0.00', tCreatedAt);
            }


            $('#otrAPDDisChgDTNotFound').addClass('xCNHide');
            $('#otbDisChgDataDocDTList tbody').append(tDisChgHDTemplate);
            JSxAPDResetDisChgColIndex();
            $('.dischgselectpicker').selectpicker();
            // console.log('cSumFCXtdNet: ', cSumFCXtdNet);
        }

        JSxAPDCalcDisChg();
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
function JSxAPDDisChgSave() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        /*if(!JSbAPDHasDisChgRow()){
            FSvCMNSetMsgWarningDialog('ไม่พบรายการ ส่วนลด/ชาร์จ');
        }*/

        var aDisChgItems = [];
        var cBeforeDisChgSum = 0.00;
        var cAfterDisChgSum = 0.00;
        $('.xWAPDDisChgTrTag').each(function(index){
            
            var tCreatedAt = $(this).find('input.xWAPDDisChgCreatedAt').val();
            
            var nSeqNo = '';
            var tStaDis = '';
            if(JCNbAPDIsDisChgType('disChgDT')){
                nSeqNo = DisChgDataRowDT.tSeqNo;
                tStaDis = DisChgDataRowDT.tStadis;
            }
            
            var cBeforeDisChg = accounting.unformat($(this).find('td label.xWAPDDisChgBeforeDisChg').text());
            var cAfterDisChg = accounting.unformat($(this).find('td label.xWAPDDisChgAfterDisChg').text());
            var cDisChgValue = accounting.unformat($(this).find('td label.xWAPDDisChgValue').text());
            var nDisChgType = parseInt($(this).find('td select.xWAPDDisChgType').val());
            var cDisChgNum = accounting.unformat($(this).find('td input.xWAPDDisChgNum').val());
            
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
        
        if (JCNbAPDIsDisChgType('disChgHD')) {
            $.ajax({
                type: "POST",
                url: "docAPDebitnoteAddEditHDDis",
                data: {
                    tBchCode        : $('#oetAPDBchCode').val(),
                    tDocNo          : $('#oetAPDDocNo').val(),
                    tSplVatType     : JSxAPDIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                    tDisChgItems    : JSON.stringify(aDisChgItems),
                    tDisChgSummary  : JSON.stringify(oDisChgSummary)
                },
                cache: false,
                timeout: 5000,
                success: function (tResult) {
                    if (JCNbAPDIsDocType('havePdt')) {
                        JSvAPDLoadPdtDataTableHtml(1, false);
                    }
                    if (JCNbAPDIsDocType('nonePdt')) {
                        JSvAPDLoadNonePdtDataTableHtml(1, false);
                    }
                    $('#odvAPDDisChgPanel').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }

        if (JCNbAPDIsDisChgType('disChgDT')) {
            $.ajax({
                type: "POST",
                url: "docAPDebitnoteAddEditDTDis",
                data: {
                    tBchCode        : $('#oetAPDBchCode').val(),
                    tSeqNo          : DisChgDataRowDT.tSeqNo,
                    tDocNo          : $('#oetAPDDocNo').val(),
                    tSplVatType     : JSxAPDIsSplUseVatType('in') ? '1' : '2', // 1: รวมใน, 2: แยกนอก
                    tDisChgItems    : JSON.stringify(aDisChgItems),
                    tDisChgSummary  : JSON.stringify(oDisChgSummary)
                },
                cache: false,
                timeout: 5000,
                success: function (tResult) {
                    // console.log(tResult);
                    if (JCNbAPDIsDocType('havePdt')) {
                        JSvAPDLoadPdtDataTableHtml(1, false);
                    }
                    if (JCNbAPDIsDocType('nonePdt')) {
                        JSvAPDLoadNonePdtDataTableHtml(1, false);
                    }
                    $('#odvAPDDisChgPanel').modal('hide');
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
function JStAPDSetTrBody(pcBeforeDisChg, pcDisChgValue, pcAfterDisChg, ptCreatedAt){
    try{
        // console.log("JStAPDSetTrBody", pcBeforeDisChg);
        let tTemplate = $("#oscAPDTrBodyTemplate").html();
        let oData = {cBeforeDisChg: pcBeforeDisChg, cDisChgValue: pcDisChgValue, cAfterDisChg: pcAfterDisChg, tCreatedAt: ptCreatedAt};
        let tRender = JStAPDRenderTemplate(tTemplate, oData);
        return tRender;
    }catch(err){
        console.log("JStAPDSetTrBody Error: ", err);
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
function JStAPDRenderTemplate(tTemplate, oData){
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
        console.log("JStAPDRenderTemplate Error: ", err);
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
function JSxAPDResetDisChgColIndex(){
    $('.xWAPDDisChgIndex').each(function(index){
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
function JSxAPDResetDisChgRemoveRow(poEl){
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        $(poEl).parents('.xWAPDDisChgTrTag').remove();

        if(JSbAPDHasDisChgRow()){
            JSxAPDResetDisChgColIndex();
        }else{
            var oTrNotFound = $('#oscAPDTrNotFoundTemplate').html();
            $('.xWDisChgTBBody').append(oTrNotFound);
        }
        JSxAPDCalcDisChg();
        
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
function JSbAPDHasDisChgRow(){
    var bStatus = false;
    var nRowCount = $('.xWAPDDisChgTrTag').length;
    // console.log('nRowCount: ', nRowCount);
    if(nRowCount > 0){
        bStatus = true;
    }
    return bStatus;
}
</script>
