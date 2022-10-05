<script type="text/javascript">

var tBaseURL    = '<?php echo base_url(); ?>';
var nLangEdits  = '<?php echo $this->session->userdata("tLangEdit") ?>';

$(document).ready(function(){
    JCNxOpenLoading();
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });

    // Event Click Button Filter Date Data Form
    $('#obtBCMSALDate').unbind().click(function(){
        $('#oetBCMSALDate').datepicker('show');
    });

    $('.selectpicker').selectpicker();

    FSxBCMCallDataTable();
});

//ค้นหาข้อมูล
$('#obtBCMBtnFilter').click(function(){
    FSxBCMCallDataTable();
});

//ล้างข้อมูลการค้นหา
$('#obtBCMBtnRefresh').click(function(){

    var nCountBch = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    if(nCountBch != 1){ //ถ้ามีมากกว่า 1 สาขาต้อง reset 
        $('#oetBCMBchCode , #oetBCMBchName').val('');
    }
    
    $('#oetBCMPosCode , #oetBCMPosName').val('');
    $('.xCNDatePicker').datepicker("setDate",  new Date());
    $('.selectpicker').val('').selectpicker('refresh');

    FSxBCMCallDataTable(1);
});

//โหลดข้อมูลตาราง
function FSxBCMCallDataTable(nPageCurrent){
    if(nPageCurrent=='' || nPageCurrent == undefined || nPageCurrent == 'NaN' ){
        nPageCurrent = 1;
    }
    $.ajax({
        type: "POST",
        url: "dasBCMCallBatchDataTable",
        data: $('#ofmBCMSALFormFilter').serialize()+"&nPageCurrent="+nPageCurrent,
        cache: false,
        timeout: 0,
        success : function(paDataReturn){
            $('#odvPanelSaleData').html(paDataReturn);
            JCNxCloseLoading();
        },
        error : function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR,textStatus,errorThrown);
        }
    });
}

// Click Browse Branch
 $('#obtBCMBrowsBch').click(function(e) {
    e.preventDefault();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JSxCheckPinMenuClose();
        window.oPdtBrowseBranchOption = oPdtBrowseBranch({
            'tReturnInputCode': 'oetBCMBchCode',
            'tReturnInputName': 'oetBCMBchName',
        });
        JCNxBrowseData('oPdtBrowseBranchOption');
    } else {
        JCNxShowMsgSessionExpired();
    }
});

// Click Browse Pos
 $('#obtBCMBrowsPos').click(function(e) {
    e.preventDefault();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JSxCheckPinMenuClose();
        window.oPdtBrowsePosOption = oPdtBrowsePos({
            'tReturnInputCode': 'oetBCMPosCode',
            'tReturnInputName': 'oetBCMPosName',
        });
        JCNxBrowseData('oPdtBrowsePosOption');
    } else {
        JCNxShowMsgSessionExpired();
    }
});

//เลือกสาขา
var oPdtBrowseBranch = function(poReturnInput) {
    var tInputReturnCode = poReturnInput.tReturnInputCode;
    var tInputReturnName = poReturnInput.tReturnInputName;
    var tAgnCodeWhere = '<?= $this->session->userdata('tSesUsrAgnCode'); ?>';

    var nCountBCH = '<?= $this->session->userdata('nSesUsrBchCount') ?>';
    // alert(nCountBCH);
    if (nCountBCH != '0') {
        //ถ้าสาขามากกว่า 1
        tBCH = "<?= $this->session->userdata('tSesUsrBchCodeMulti'); ?>";
        tWhereBCH = " AND TCNMBranch.FTBchCode IN ( " + tBCH + " ) ";
    } else {
        tWhereBCH = '';
    }

    if (tAgnCodeWhere == '' || tAgnCodeWhere == null) {
        tWhereAgn = '';
    } else {
        tWhereAgn = " AND TCNMBranch.FTAgnCode = '" + tAgnCodeWhere + "'";
    }


    var oOptionReturn = {
        Title: ['company/branch/branch', 'tBCHTitle'],
        Table: {
            Master: 'TCNMBranch',
            PK: 'FTBchCode'
        },
        Join: {
            Table: ['TCNMBranch_L', 'TCNMAgency_L'],
            On: [
                'TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                'TCNMAgency_L.FTAgnCode = TCNMBranch.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits,
            ]
        },
        Where: {
            Condition: [tWhereBCH + tWhereAgn]
            // Condition: [tWhereAgn]
        },
        GrideView: {
            ColumnPathLang: 'company/branch/branch',
            ColumnKeyLang: ['tBCHCode', 'tBCHName'],
            ColumnsSize: ['15%', '75%'],
            DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName', 'TCNMAgency_L.FTAgnName', 'TCNMBranch.FTAgnCode'],
            DataColumnsFormat: ['', '', '', ''],
            DisabledColumns: [2, 3],
            WidthModal: 50,
            Perpage: 10,
            OrderBy: ['TCNMBranch.FTBchCode DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
            Text: [tInputReturnName, "TCNMBranch_L.FTBchName"],
        },
        NextFunc: {
            FuncName: 'JSxClearBrowseConditionBCH',
            ArgReturn: ['FTBchCode', 'FTAgnCode']
        },
        BrowseLev : 1
    }
    return oOptionReturn;
}



//เลือกจุดขาย
    var oPdtBrowsePos = function(poReturnInput) {
    var tInputReturnCode = poReturnInput.tReturnInputCode;
    var tInputReturnName = poReturnInput.tReturnInputName;
    var tBchCodeWhere = $('#oetBCMBchCode').val();


    if (tBchCodeWhere == '' || tBchCodeWhere == null) {
        tWhereBCH = '';
    } else {
        tWhereBCH = " AND TCNMPos.FTBchCode = '" + tBchCodeWhere + "'";
    }


    var oOptionReturn = {
        Title: ['sale/salemonitor/salemonitor', 'tBCMPos'],
        Table: {
            Master: 'TCNMPos',
            PK: 'FTPosCode'
        },
        Join: {
            Table: ['TCNMPos_L'],
            On: [
                'TCNMPos_L.FTBchCode = TCNMPos.FTBchCode AND TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FNLngID = ' + nLangEdits,
            ]
        },
        Where: {
            Condition: [tWhereBCH]
        },
        GrideView: {
            ColumnPathLang: 'sale/salemonitor/salemonitor',
            ColumnKeyLang: ['tBCMBrsPosCode', 'tBCMBrsPosName'],
            ColumnsSize: ['15%', '75%'],
            DataColumns: ['TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName'],
            DataColumnsFormat: ['', ''],
            WidthModal: 50,
            Perpage: 10,
            OrderBy: ['TCNMPos.FDCreateOn DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: [tInputReturnCode, "TCNMPos.FTPosCode"],
            Text: [tInputReturnName, "TCNMPos_L.FTPosName"],
        },
        BrowseLev : 1
    }
    return oOptionReturn;
}

function JSxClearBrowseConditionBCH(ptData){
    if (ptData != '' || ptData != 'NULL') {
        $('#obtBCMBrowsPos').prop('disabled',false);
        $('#oetBCMPosCode').val('');
        $('#oetBCMPosName').val('');
    }else{
        $('#obtBCMBrowsPos').prop('disabled',true);
    }
}


function JSxBCMConfirmRepiar(){

    JCNxOpenLoading();
    var oListItem = [];
    var i = 0;
    $(".ocbBCMListItem:checkbox:checked").map(function(){
        oListItem[i] = { tBchCode:$(this).data('bchcode') , tPosCode:$(this).data('poscode') , tShiftCode:$(this).val() };
        i++;
    }).get(); 

    $.ajax({
        type: "POST",
        url: "dasBCMCallMQRequestSaleData",
        data: {oListItem:oListItem},
        cache: false,
        timeout: 0,
        success : function(paDataReturn){
            var paDataReturn = JSON.parse(paDataReturn);
        
            if(paDataReturn['nStaEvent']==1){
                setTimeout(() => {
                    JCNxCloseLoading();
                    FSvCMNSetMsgSucessDialog('<?=language('sale/salemonitor/salemonitor', 'tBCMToolsRepairing')?>');
                    FSxBCMCallDataTable();
                    $('#odvBCMModalConfim').modal('hide');
                }, 5000);

            }else{
                FSvCMNSetMsgWarningDialog(paDataReturn['tStaMessg']);
            }
        },
        error : function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR,textStatus,errorThrown);
        }
    });
}

$('#obtBCMBtnRepair').click(function(){
    var oListItem = [];
    var i = 0;
    $(".ocbBCMListItem:checkbox:checked").map(function(){
        oListItem[i] = { tBchCode:$(this).data('bchcode') , tPosCode:$(this).data('poscode') , tShiftCode:$(this).val() };
        i++;
    }).get(); 
    var tTextAlter = '';
    if(i>0){
        tTextAlter = '<?=language('sale/salemonitor/salemonitor', 'tBCMToolsConfirmRepair')?>';
    }else{
        tTextAlter = '<?=language('sale/salemonitor/salemonitor', 'tBCMToolsConfirmRepairAll')?>';
    }
    $('#oepMassageConfirm').text(tTextAlter);
    $('#odvBCMModalConfim').modal('show');
});


</script>