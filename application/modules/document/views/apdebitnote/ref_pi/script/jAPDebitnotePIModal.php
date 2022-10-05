<script type="text/javascript">
/**
 * Functionality : Open Ref PI Modal
 * Parameters : route
 * Creator : 23/05/2019 Piya
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxAPDOpenPIPanel() {
    JSxAPDPIHDList(1);
    $('#odvAPDPIPanel').modal('show');
}

/**
 * Functionality : Call PI HD List
 * Parameters : route
 * Creator : 21/06/2019 Piya
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxAPDPIHDList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        $('#odvAPDRefPIDTTable').html('');
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = ''; // JSoAPDGetAdvanceSearchData();
        if('<?=$this->session->userdata("tSesUsrLevel")?>' == 'HQ'){
            var tBCHCode = '';
        }else{
            var tBCHCode = $('#oetAPDBchCode').val();
        }
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnoteRefPIHDList",
            data    : $("#ofmAPDRefPIHDForm").serialize() + '&oAdvanceSearch=' + oAdvanceSearch + '&nPageCurrent=' + nPageCurrent + '&tBranch=' + tBCHCode,
            cache   : false,
            success : function (tResult) {
                try{
                    var oResult = JSON.parse(tResult);
                    $("#odvAPDRefPIHDTable").html(oResult.tPIViewDataTableList);
                    JCNxCloseLoading();
                }catch(err){
                    console.log('JSxAPDPIHDList Error: ', err);
                }
            },
            error   : function (jqXHR, textStatus, errorThrown) {
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
function JSxAPDPIDTList(pnPage, poParams) {
    try{
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            
            JCNxOpenLoading();

            var nPageCurrent = pnPage;
            if (nPageCurrent == undefined || nPageCurrent == "") {
                nPageCurrent = "1";
            }
            var tDocNo = '';
            if(!(poParams == null)){
                tDocNo = poParams.tDocNo
            }else{
                tDocNo = window.tPIHDDocCode
            }
            var oAdvanceSearch = ''; // JSoAPDGetAdvanceSearchData();

            $.ajax({
                type: "POST",
                url: "docAPDebitnoteRefPIDTList",
                data: {
                    tDocNo: tDocNo,
                    oAdvanceSearch: oAdvanceSearch,
                    nPageCurrent: nPageCurrent
                },
                cache: false,
                success: function (tResult) {
                    try{
                        var oResult = JSON.parse(tResult);
                        $("#odvAPDRefPIDTTable").html(oResult.tPIViewDataTableList);
                        JCNxCloseLoading();
                    }catch(err){
                        console.log('JSxAPDPIDTList Error: ', err);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
            
        }else {
            JCNxShowMsgSessionExpired();
        }
    }catch(err){
        console.log('JSxAPDPIDTList Error: ', err);   
    }    
}

/**
 * Functionality : เปลี่ยนหน้า pagenation
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvAPDPIHDClickPage(ptPage) {
    var nPageCurrent = "";
    switch (ptPage) {
        case "next": //กดปุ่ม Next
            $("#odvAPDPIHDList .xWBtnNext").addClass("disabled");
            nPageOld = $("#odvAPDPIHDList .xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew;
            break;
        case "previous": //กดปุ่ม Previous
            nPageOld = $("#odvAPDPIHDList .xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew;
            break;
        default:
            nPageCurrent = ptPage;
    }
    JSxAPDPIHDList(nPageCurrent);
}

/**
 * Functionality : เปลี่ยนหน้า pagenation
 * Parameters : -
 * Creator : 22/05/2019 Piya
 * Return : View
 * Return Type : View
 */
function JSvAPDPIDTClickPage(ptPage) {
    var nPageCurrent = "";
    switch (ptPage) {
        case "next": //กดปุ่ม Next
            $("#odvAPDPIDTList .xWBtnNext").addClass("disabled");
            nPageOld = $("#odvAPDPIDTList .xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew;
            break;
        case "previous": //กดปุ่ม Previous
            nPageOld = $("#odvAPDPIDTList .xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew;
            break;
        default:
            nPageCurrent = ptPage;
    }
    JSxAPDPIDTList(nPageCurrent, null);
}

/**
 * Functionality : PI DT Selected
 * Parameters : poEl is Itself element, poEv is Itself event
 * Creator : 21/06/2019 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSvAPDPIDTSelect(poEl, poEv) {
    try {
        var aPIDTSelected = [];
    } catch (err) {
        console.log('JSvAPDPIDTSelect Error: ', err);
    }
    
    if(!hasTablePrimary) { // ไม่มีการเลือกไว้  
        $('#' + id + '-' + item.id + '-tr-select-grid').addClass('table-primary');
        this.dtSelectTempItems.push(item); // เก็บค่า
    } else { // มีการเลือกไว้
        $('#' + id + '-' + item.id + '-tr-select-grid').removeClass('table-primary');
        var listToDelete = [item.id]; // item ที่จะเอาออก
        var arrayOfObjects = this.dtSelectTempItems; // ที่บรรจุ item

        arrayOfObjects.reduceRight(function (acc, obj, idx) {
            if (listToDelete.indexOf(obj.id) > -1) {
                arrayOfObjects.splice(idx, 1);
            }
        }, 0);
        this.dtSelectTempItems = arrayOfObjects;
    }
}

function mthInitSelectItems(items) {
    window.console.log('mthInitSelectItems: ', items);
    var id = this.id;
    $('#' + id + ' .tr-select-grid').removeClass('table-primary'); // ล้าง css class (table-primary) ทั้งหมด            
    for(var i = 0; i < items.length; i++){
        window.console.log('el id: ', '#' + id + '-' + items[i].id + '-tr-select-grid');
        $('#' + id + '-' + items[i].id + '-tr-select-grid').addClass('table-primary');
    }
}

function JSxAPDSelectPIHDDOC(poEl){
    $('.xWPIHDDocItems').removeClass('xCNActive');
    $(poEl).addClass('xCNActive');
    var tDocNo = $(poEl).data('code');
    window.tPIHDDocCode = tDocNo;
    
    var poParams = {
        tDocNo: tDocNo
    };
    if(JCNbAPDIsDocType('havePdt')){
        JSxAPDPIDTList('1', poParams);
    }
}

/**
 * ดักจับการเลือกทั้งหมด หรือไม่เลือกเลย
 * 
 */
function JSxAPDSelectPIDTAll(poEl){
    var bIsChecked = $(poEl).is(':checked');
    console.log('bIsChecked: ', bIsChecked);
    if(bIsChecked){
        $('.xWAPDSelectPIDTItem').prop('checked', true); // Checks it
    }else{
        $('.xWAPDSelectPIDTItem').prop('checked', false); // Unchecks it
    }
}

/**
 * เพิ่มรายการสินค้าจาก เอกสาร PI ที่เลือกไปไว้ใน DT Temp
 * 
 */
function JSxAPDAddPdtFromPIToDTTemp(){
    if(JCNbAPDIsDocType('havePdt')) {
    
        var aPdtItems = [];
        $('.xWPIDTDocItems .xWAPDSelectPIDTItem:checked').each(function(index){
            var tPdtCode    = $(this).parents('.xWPIDTDocItems').data('code');
            var tBarCode    = $(this).parents('.xWPIDTDocItems').data('barcode');
            var tPunCode    = $(this).parents('.xWPIDTDocItems').data('puncode');
            var tPrice      = $(this).parents('.xWPIDTDocItems').data('price');
            var nQty        = $(this).parents('.xWPIDTDocItems').data('qty');
            var tVatrate    = $(this).parents('.xWPIDTDocItems').data('vatrate');
            var tVatcode    = $(this).parents('.xWPIDTDocItems').data('vatcode');
            aPdtItems.push({
                pnPdtCode   : tPdtCode,
                ptBarCode   : tBarCode,
                ptPunCode   : tPunCode,
                packData    : {
                    Price   : tPrice,
                    Qty     : nQty,
                    Vatrate : tVatrate,
                    Vatcode : tVatcode
                }
            });
        });
        var tPdtItems = JSON.stringify(aPdtItems); 
        var tIsRefPI = '1';
        
        if(JSbAPDSetConditionFromPI()) {
            FSvPDTAddPdtIntoTableDT(tPdtItems, tIsRefPI);
            $('#oetAPDRefPICode').val(window.tPIHDDocCode);
            $('#oetAPDRefPIName').val(window.tPIHDDocCode);
            $('#odvAPDPIPanel').modal('hide');
        }
    }
}

//เพิ่มข้อมูลใน เงื่อนไข จากเอกสาร PI ที่เลือก/
function JSbAPDSetConditionFromPI(){
    var tShpCode    = $('.xWPIHDDocItems.xCNActive').data('shpcode');
    var tShpName    = $('.xWPIHDDocItems.xCNActive').data('shpname');
    var tWahCode    = $('.xWPIHDDocItems.xCNActive').data('wahcode');
    var tWahName    = $('.xWPIHDDocItems.xCNActive').data('wahname');
    var tSplcode    = $('.xWPIHDDocItems.xCNActive').data('splcode');
    var tSplname    = $('.xWPIHDDocItems.xCNActive').data('splname');
    var tVatInorEx  = $('.xWPIHDDocItems.xCNActive').data('vatinorex');
    var dDateRefIn  = $('.xWPIHDDocItems.xCNActive').data('daterefin');

    $('#oetAPDMchCode').val('');
    $('#oetAPDMchName').val('');
    $('#ohdAPDWahCodeInShp').val('');
    $('#ohdAPDWahNameInShp').val('');
    $('#oetAPDShpCode').val('');
    $('#oetAPDShpName').val('');
    $('#oetAPDPosCode').val('');
    $('#oetAPDPosName').val('');
    $('#ohdAPDWahCode').val('');
    $('#ohdAPDWahName').val('');
    $('#oetAPDWahCode').val('');
    $('#oetAPDWahName').val('');
    $('#oetAPDShpCode').val(tShpCode);
    $('#oetAPDShpName').val(tShpName);
    $('#oetAPDWahCode').val(tWahCode);
    $('#oetAPDWahName').val(tWahName);
    $('#oetAPDXphRefIntDate').val(dDateRefIn);
    //ถ้าอ้างอิงผู้จำหน่าย
    $('#oetAPDSplCode').val(tSplcode);
    $('#oetAPDSplName').val(tSplname);
    $('#ocmAPDXphVATInOrEx').val(tVatInorEx).selectpicker("refresh");
    return true;
}
</script>
