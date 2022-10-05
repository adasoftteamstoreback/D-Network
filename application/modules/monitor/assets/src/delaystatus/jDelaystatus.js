var nLangEdits ='1';
//Function Name : Next Function Cashier
//Create by     : Off
//Date Create   : 20/09/2021
var oRptCustomerOptionFrom = function(poReturnInputCst) {
    let tCstNextFuncName = poReturnInputCst.tNextFuncName;
    let aCstArgReturn = poReturnInputCst.aArgReturn;
    let tCstInputReturnCode = poReturnInputCst.tReturnInputCode;
    let tCstInputReturnName = poReturnInputCst.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    if( tAgnCode != ''  && tAgnCode != undefined){
        tCondition += " AND TCNMCst.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMCst.FTAgnCode,'') ='' ";
    }
    let oCstOptionReturn = {
        Title: ['supplier/supplier/supplier', 'tSPLTitle'],
        Table: {
            Master: 'TCNMCst',
            PK: 'FTCstCode'
        },
        Join: {
            Table: ['TCNMCst_L'],
            On: [
                'TCNMCst.FTCstCode = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits
            ]
        },
        Where: {
            Condition: [tCondition]
        },
        GrideView: {
            ColumnPathLang: 'supplier/supplier/supplier',
            ColumnKeyLang: ['tCode', 'tName'],
            ColumnsSize: ['15%', '90%'],
            WidthModal: 50,
            DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName'],
            DataColumnsFormat: ['', ''],
            Perpage: 10,
            OrderBy: ['TCNMCst.FDCreateOn DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: [tCstInputReturnCode, "TCNMCst.FTCstCode"],
            Text: [tCstInputReturnName, "TCNMCst_L.FTCstName"]
        },
        NextFunc: {
            FuncName: tCstNextFuncName,
            ArgReturn: aCstArgReturn
        },
    };
    return oCstOptionReturn;
}

var oRptCustomerOptionTo = function(poReturnInputCst) {
    let tCstNextFuncName = poReturnInputCst.tNextFuncName;
    let aCstArgReturn = poReturnInputCst.aArgReturn;
    let tCstInputReturnCode = poReturnInputCst.tReturnInputCode;
    let tCstInputReturnName = poReturnInputCst.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    if( tAgnCode != ''  && tAgnCode != undefined){
        tCondition += " AND TCNMCst.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMCst.FTAgnCode,'') ='' ";
    }
    let oCstOptionReturn = {
        Title: ['supplier/supplier/supplier', 'tSPLTitle'],
        Table: {
            Master: 'TCNMCst',
            PK: 'FTCstCode'
        },
        Join: {
            Table: ['TCNMCst_L'],
            On: [
                'TCNMCst.FTCstCode = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits
            ]
        },
        Where: {
            Condition: [tCondition]
        },
        GrideView: {
            ColumnPathLang: 'supplier/supplier/supplier',
            ColumnKeyLang: ['tCode', 'tName'],
            ColumnsSize: ['15%', '90%'],
            WidthModal: 50,
            DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName'],
            DataColumnsFormat: ['', ''],
            Perpage: 10,
            OrderBy: ['TCNMCst.FDCreateOn DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: [tCstInputReturnCode, "TCNMCst.FTCstCode"],
            Text: [tCstInputReturnName, "TCNMCst_L.FTCstName"]
        },
        NextFunc: {
            FuncName: tCstNextFuncName,
            ArgReturn: aCstArgReturn
        },
    };
    return oCstOptionReturn;
}

//Function Name : Next Function Cashier
//Create by     : Off
//Date Create   : 29/10/2021
// จากผู้จำหน่าย
$('#obtDLSBrowseCustomerFrom').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptCustomerOptionFroms = undefined;
     oRptCustomerOptionFroms = oRptCustomerOptionFrom({
         'tReturnInputCode': 'oetDLSCustomerCodeFrom',
         'tReturnInputName': 'oetDLSCustomerNameFrom',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseCst',
         'aArgReturn': ['FTCstCode', 'FTCstName']
     });
     JCNxBrowseData('oRptCustomerOptionFroms');
 } else {
     JCNxShowMsgSessionExpired();
 }
});

//Function Name : Next Function Cashier
//Create by     : Off
//Date Create   : 29/10/2021
// ถึงผู้จำหน่าย
$('#obtDLSBrowseCustomerTo').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptCustomerOptionTos = undefined;
     oRptCustomerOptionTos = oRptCustomerOptionTo({
         'tReturnInputCode': 'oetDLSCustomerCodeTo',
         'tReturnInputName': 'oetDLSCustomerNameTo',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseCst',
         'aArgReturn': ['FTCstCode', 'FTCstName']
     });
     JCNxBrowseData('oRptCustomerOptionTos');
 } else {
     JCNxShowMsgSessionExpired();
 }
});

//Function Name : Next Function Cashier
//Create by     : Off
//Date Create   : 29/10/2021
function JSxRptConsNextFuncBrowseCst(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tCstCode = aDataNextFunc[0];
       var tCstName = aDataNextFunc[1];

       var tSplCodeFrom = $('#oetDLSCustomerCodeFrom').val();
       var tSplCodeTo = $('#oetDLSCustomerCodeTo').val();

       //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
       if (tSplCodeFrom == "" || tSplCodeFrom === undefined) {
           $('#oetDLSCustomerCodeFrom').val(tCstCode);
           $('#oetDLSCustomerNameFrom').val(tCstName);
       }

       //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
       if (tSplCodeTo == "" || tSplCodeTo === undefined) {
           $('#oetDLSCustomerCodeTo').val(tCstCode);
           $('#oetDLSCustomerNameTo').val(tCstName);
       }

       //JSxUncheckinCheckbox('oetRptSupplierCodeTo');

   }
}



var oRptCustomerGroupOption = function(poReturnInputCst) {
    let tCstNextFuncName = poReturnInputCst.tNextFuncName;
    let aCstArgReturn = poReturnInputCst.aArgReturn;
    let tCstInputReturnCode = poReturnInputCst.tReturnInputCode;
    let tCstInputReturnName = poReturnInputCst.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    // if( tAgnCode != ''  && tAgnCode != undefined){
    //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    // }
    let oCstOptionReturn = {
      Title : ['supplier/supplier/supplier','tSplGroup'],
      Table:{Master:'TCNMCstGrp',PK:'FTCgpCode'},
      Join :{
          Table:	['TCNMCstGrp_L'],
          On:['TCNMCstGrp.FTCgpCode = TCNMCstGrp_L.FTCgpCode AND TCNMCstGrp_L.FNLngID = '+nLangEdits,]
      },
      GrideView:{
          ColumnPathLang	: 'supplier/supplier/supplier',
          ColumnKeyLang	: ['tCode','tName'],
          DataColumns		: ['TCNMCstGrp.FTCgpCode','TCNMCstGrp_L.FTCgpName'],
          Perpage			: 10,
          OrderBy		    : ['TCNMCstGrp.FDCreateOn DESC'],

      },
      CallBack:{
          ReturnType	: 'S',
          Value		: ["oetDLSCustomerCodeGroup","TCNMCstGrp.FTCgpCode"],
          Text		: ["oetDLSCustomerNameGroup","TCNMCstGrp_L.FTCgpName"],
      },
      RouteAddNew : 'groupcustomer',
      NextFunc: {
          FuncName: tCstNextFuncName,
          ArgReturn: aCstArgReturn
      },
    };
    return oCstOptionReturn;
}
$('#obtDLSBrowseCustomerGroup').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptCustomerOptionGroup = undefined;
     oRptCustomerOptionGroup = oRptCustomerGroupOption({
         'tReturnInputCode': 'oetDLSCustomerCodeGroup',
         'tReturnInputName': 'oetDLSCustomerNameGroup',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseCstGroup',
         'aArgReturn': ['FTCgpCode', 'FTCgpName']
     });
     JCNxBrowseData('oRptCustomerOptionGroup');
 } else {
     JCNxShowMsgSessionExpired();
 }
});
function JSxRptConsNextFuncBrowseCstGroup(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tSplCode = aDataNextFunc[0];
       var tSplName = aDataNextFunc[1];
       $('#oetDLSCustomerCodeGroup').val(tSplCode);
       $('#oetDLSCustomerNameGroup').val(tSplName);
       // var tSplCode = $('#oetDLSCustomerCodeGroup').val();
       // var tSplName = $('#oetDLSCustomerNameGroup').val();
       // $('#oetDLSCustomerCodeGroup').val(tSplCode);
       // $('#oetDLSCustomerNameGroup').val(tSplName);
   }
}


var oRptCustomerTypeOption = function(poReturnInputCst) {
    let tCstNextFuncName = poReturnInputCst.tNextFuncName;
    let aCstArgReturn = poReturnInputCst.aArgReturn;
    let tCstInputReturnCode = poReturnInputCst.tReturnInputCode;
    let tCstInputReturnName = poReturnInputCst.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    // if( tAgnCode != ''  && tAgnCode != undefined){
    //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    // }
    let oCstOptionReturn = {
      Title : ['supplier/suppliertype/suppliertype','tSTYTitle'],
      Table:{Master:'TCNMCstType',PK:'FTCtyCode'},
      Join :{
          Table:	['TCNMCstType_L'],
          On:['TCNMCstType.FTCtyCode = TCNMCstType_L.FTCtyCode AND TCNMCstType_L.FNLngID = '+nLangEdits,]
      },
      GrideView:{
          ColumnPathLang	: 'supplier/suppliertype/suppliertype',
          ColumnKeyLang	: ['tSTYCode','tSTYName'],
          DataColumns		: ['TCNMCstType.FTCtyCode','TCNMCstType_L.FTCtyName'],
          Perpage			: 10,
          OrderBy		    : ['TCNMCstType.FDCreateOn DESC'],

      },
      CallBack:{
          ReturnType	: 'S',
          Value		: ["oetDLSCustomerCodeType","TCNMCstType.FTCtyCode"],
          Text		: ["oetDLSCustomerNameType","TCNMCstType_L.FTCtyName"],
      },
      RouteAddNew : 'groupsupplier',
      NextFunc: {
          FuncName: tCstNextFuncName,
          ArgReturn: aCstArgReturn
      },
    };
    return oCstOptionReturn;
}
$('#obtDLSBrowseCustomerType').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptCustomerTypeOptions = undefined;
     oRptCustomerTypeOptions = oRptCustomerTypeOption({
         'tReturnInputCode': 'oetDLSCustomerCodeType',
         'tReturnInputName': 'oetDLSCustomerNameType',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseCstType',
         'aArgReturn': ['FTCtyCode', 'FTCtyName']
     });
     JCNxBrowseData('oRptCustomerTypeOptions');
 } else {
     JCNxShowMsgSessionExpired();
 }
});

function JSxRptConsNextFuncBrowseCstType(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tCstCode = aDataNextFunc[0];
       var tCstName = aDataNextFunc[1];
       $('#oetDLSCustomerCodeType').val(tCstCode);
       $('#oetDLSCustomerNameType').val(tCstName);
   }
}




var oRptCustomerLevelOption = function(poReturnInputCst) {
    let tCstNextFuncName = poReturnInputCst.tNextFuncName;
    let aCstArgReturn = poReturnInputCst.aArgReturn;
    let tCstInputReturnCode = poReturnInputCst.tReturnInputCode;
    let tCstInputReturnName = poReturnInputCst.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    // if( tAgnCode != ''  && tAgnCode != undefined){
    //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    // }
    let oCstOptionReturn = {
      Title : ['supplier/supplierlev/supplierlev','tSLVTitle'],
      Table:{Master:'TCNMCstLev',PK:'FTClvCode'},
      Join :{
          Table:	['TCNMCstLev_L'],
          On:['TCNMCstLev.FTClvCode = TCNMCstLev_L.FTClvCode AND TCNMCstLev_L.FNLngID = '+nLangEdits,]
      },
      GrideView:{
          ColumnPathLang	: 'supplier/supplierlev/supplierlev',
          ColumnKeyLang	: ['tSLVCode','tSLVName'],
          DataColumns		: ['TCNMCstLev.FTClvCode','TCNMCstLev_L.FTClvName'],
          Perpage			: 10,
          OrderBy		    : ['TCNMCstLev.FDCreateOn DESC'],

      },
      CallBack:{
          ReturnType	: 'S',
          Value		: ["oetDLSSupplierCodeClass","TCNMCstLev.FTClvCode"],
          Text		: ["oetDLSSupplierNameClass","TCNMCstLev_L.FTClvName"],
      },
      RouteAddNew : 'groupcustomer',
      NextFunc: {
          FuncName: tCstNextFuncName,
          ArgReturn: aCstArgReturn
      },
    };
    return oCstOptionReturn;
}
$('#obtDLSBrowseCustomerClass').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptCustomerOption = undefined;
     oRptCustomerOption = oRptCustomerLevelOption({
         'tReturnInputCode': 'oetDLSSupplierCodeClass',
         'tReturnInputName': 'oetDLSSupplierNameClass',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseSplClass',
         'aArgReturn': ['FTClvCode', 'FTClvName']
     });
     JCNxBrowseData('oRptCustomerOption');
 } else {
     JCNxShowMsgSessionExpired();
 }
});
function JSxRptConsNextFuncBrowseSplClass(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tSplCode = aDataNextFunc[0];
       var tSplName = aDataNextFunc[1];
       $('#oetDLSSupplierCodeClass').val(tSplCode);
       $('#oetDLSSupplierNameClass').val(tSplName);
   }
}
