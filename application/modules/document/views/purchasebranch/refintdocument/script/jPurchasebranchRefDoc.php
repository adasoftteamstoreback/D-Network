<script>
$(document).ready(function(){

    $('.selectpicker').selectpicker('refresh');

    $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
    });

    $('#obtPRBBrowseBchNoStock').click(function(){ 
        $('#odvPRBModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oPRBBrowseBranchOption  = undefined;
                oPRBBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetPRBRefIntBchCode',
                    'tReturnInputName'  : 'oetPRBRefIntBchName',
                    'tNextFuncName'     : 'JSxPRBRefIntNextFunctBrowsBranch',
                    'tAgnCode'          : $('#oetPRBAgnCode').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oPRBBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });

    $('#obtPRBBrowseWahNostock').click(function(){ 
        $('#odvPRBModalRefIntDoc').modal('hide');
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oPRBBrowseWahOption  = undefined;
                oPRBBrowseWahOption         = oWahNoStockOption({
                    'tReturnInputCode'  : 'oetPRBRefIntWahCode',
                    'tReturnInputName'  : 'oetPRBRefIntWahName',
                    'tNextFuncName'     : 'JSxPRBRefIntNextFunctBrowsBranch',
                    'tPRBBchCode'       : $('#oetPRBRefIntBchCode').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oPRBBrowseWahOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });

    $('#obtPRBBrowsePDTNostockFRM').click(function(){
        JSxPRBBrowsePdt('from');
    });

    $('#obtPRBBrowsePDTNostockTo').click(function(){
        JSxPRBBrowsePdt('to');
    });


    $('#obtPRBBrowseRefExtDocDateFrm').unbind().click(function(){
        $('#oetPRBRefIntDocDateFrm').datepicker('show');
    });


    $('#obtPRBBrowseRefExtDocDateTo').unbind().click(function(){
        $('#oetPRBRefIntDocDateTo').datepicker('show');
    });

    JSxRefIntDocHDDataTable();
});


$('#odvPRBModalRefIntDoc').on('hidden.bs.modal', function () {
    $('#wrapper').css('overflow','auto');
    $('#odvPRBModalRefIntDoc').css('overflow','auto');
 
});

$('#odvPRBModalRefIntDoc').on('show.bs.modal', function () {
    $('#wrapper').css('overflow','hidden');
    $('#odvPRBModalRefIntDoc').css('overflow','auto');
});

function JSxPRBRefIntNextFunctBrowsBranch(ptData){
    JSxCheckPinMenuClose();
      $('#odvPRBModalRefIntDoc').modal("show");
    
}

$('#obtNoStocktDocFilter').on('click',function(){
    JSxRefIntDocHDDataTable();
});

$('#obtNoStockDocReset').on('click',function(){
    $('#ofmPRBSerchAdv').find('input').val('');
    JSxRefIntDocHDDataTable();
});

//Functionality : Export Excel UserLogin
//Parameters : Export Excel UserLogin
//Creator : 27/08/2021 Off
//Return : 
//Return Type : 
function JStNostockExport() {
    $("#ofmExportExcelNoStock").valid();
    $('#ofmExportExcelNoStock').submit();
    // $('#odvModalCondition').modal('hide');
    // $('.modal-backdrop').remove();
};

/*
function : Function Browse Pdt
Parameters : Error Ajax Function 
Creator : 22/05/2019 Piya(Tiger)
Return : Modal Status Error
Return Type : view
*/
function JSxPRBBrowsePdt(ptType) {
    $('#odvPRBModalRefIntDoc').modal('hide');
    $('#odvModalDOCPDT').modal({backdrop: 'static', keyboard: false});
    if(ptType == 'from'){
        tNextFunc = 'JSxPRBBrowsePdtFrom';
    }else{
        tNextFunc = 'JSxPRBBrowsePdtTo';
    }
        var dTime               = new Date();
        var dTimelocalStorage   = dTime.getTime();
        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                'Qualitysearch'   : ['SUP','NAMEPDT','CODEPDT','FromToBCH','FromToSHP','FromToPGP','FromToPTY'],
                'PriceType'       : ['Pricesell'],
                'SelectTier'      : ['PDT'],//PDT, Barcode
                'ShowCountRecord' : 10,
                'NextFunc'        : tNextFunc,
                'ReturnType'      : 'S', //S = Single M = Multi
                'SPL'             : ['',''],
                'BCH'             : [$('#oetAJPBchCode').val(),''],//Code, Name
                'SHP'             : ['',''],
                'TimeLocalstorage': dTimelocalStorage
            },
            cache: false,
            timeout: 0,
            success: function(tResult){
                $('#odvModalDOCPDT').modal({ show: true });
                localStorage.removeItem("LocalItemDataPDT");
                $('#odvModalsectionBodyPDT').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
}

function JSxPRBBrowsePdtFrom(poPdtData){
    var aDataPdt = JSON.parse(poPdtData);
    var tPdtCode = aDataPdt[0]['packData']['PDTCode'];
    var tPdtName = aDataPdt[0]['packData']['PDTName'];

    $('#oetPRBRefIntPDTCodeFrm').val(tPdtCode);
    $('#oetPRBRefIntPDTNameFrm').val(tPdtName);

    if($('#oetPRBRefIntPDTCodeTo').val() == ''){
        $('#oetPRBRefIntPDTCodeTo').val(tPdtCode);
        $('#oetPRBRefIntPdtNameTo').val(tPdtName);
    }
    $('#odvPRBModalRefIntDoc').modal('show');
    
}

function JSxPRBBrowsePdtTo(poPdtData){
    var aDataPdt = JSON.parse(poPdtData);
    var tPdtCode = aDataPdt[0]['packData']['PDTCode'];
    var tPdtName = aDataPdt[0]['packData']['PDTName'];

    $('#oetPRBRefIntPDTCodeTo').val(tPdtCode);
    $('#oetPRBRefIntPdtNameTo').val(tPdtName);

    if($('#oetPRBRefIntPDTCodeFrm').val() == ''){
        $('#oetPRBRefIntPDTCodeFrm').val(tPdtCode);
        $('#oetPRBRefIntPDTNameFrm').val(tPdtName);
    }
    $('#odvPRBModalRefIntDoc').modal('show');
}

//เรียกตารางเลขที่เอกสารอ้างอิง
function JSxRefIntDocHDDataTable(pnPage){
    if(pnPage == '' || pnPage == null){
            var pnNewPage = 1;
        }else{
            var pnNewPage = pnPage;
        }
        var nPageCurrent = pnNewPage;
        var tPRBRefIntBchCode  = $('#oetPRBRefIntBchCode').val();
        var tPRBRefIntWahCode  = $('#oetPRBRefIntWahCode').val();
        var tPRBRefIntDocNo  = $('#oetPRBRefIntDocNo').val();
        var oetPRBRefIntPDTCodeFrm  = $('#oetPRBRefIntPDTCodeFrm').val();
        var oetPRBRefIntPDTCodeTo  = $('#oetPRBRefIntPDTCodeTo').val();
        var tPRBRefIntStaDoc  = $('#oetPRBRefIntStaDoc').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPRBCallRefIntDocDataTable",
            data: {
                'tPRBRefIntBchCode'     : tPRBRefIntBchCode,
                'tPRBRefIntWahCode'     : tPRBRefIntWahCode,
                'tPRBRefIntDocNo'       : tPRBRefIntDocNo,
                'oetPRBRefIntPDTCodeFrm'  : oetPRBRefIntPDTCodeFrm,
                'oetPRBRefIntPDTCodeTo'   : oetPRBRefIntPDTCodeTo,
                'tPRBRefIntStaDoc'      : tPRBRefIntStaDoc,
                'nPRBRefIntPageCurrent' : nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                 $('#odvRefIntDocHDDataTable').html(oResult);
                 JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JSxRefIntDocHDDataTable(pnPage)
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
}


</script>