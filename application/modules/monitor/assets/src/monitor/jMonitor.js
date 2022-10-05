var nLangEdits ='1';
//Function Name : Next Function Cashier
//Create by     : mos
//Date Create   : 20/09/2021
var oRptSupplierOptionFrom = function(poReturnInputSpl) {
    let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
    let aSplArgReturn = poReturnInputSpl.aArgReturn;
    let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
    let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    if( tAgnCode != ''  && tAgnCode != undefined){
        tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    }
    let oSplOptionReturn = {
        Title: ['supplier/supplier/supplier', 'tSPLTitle'],
        Table: {
            Master: 'TCNMSpl',
            PK: 'FTSplCode'
        },
        Join: {
            Table: ['TCNMSpl_L'],
            On: [
                'TCNMSpl.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits
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
            DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName'],
            DataColumnsFormat: ['', ''],
            Perpage: 10,
            OrderBy: ['TCNMSpl.FDCreateOn DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: [tSplInputReturnCode, "TCNMSpl.FTSplCode"],
            Text: [tSplInputReturnName, "TCNMSpl_L.FTSplName"]
        },
        NextFunc: {
            FuncName: tSplNextFuncName,
            ArgReturn: aSplArgReturn
        },
    };
    return oSplOptionReturn;
}

var oRptSupplierOptionTo = function(poReturnInputSpl) {
    let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
    let aSplArgReturn = poReturnInputSpl.aArgReturn;
    let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
    let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    if( tAgnCode != ''  && tAgnCode != undefined){
        tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    }
    let oSplOptionReturn = {
        Title: ['supplier/supplier/supplier', 'tSPLTitle'],
        Table: {
            Master: 'TCNMSpl',
            PK: 'FTSplCode'
        },
        Join: {
            Table: ['TCNMSpl_L'],
            On: [
                'TCNMSpl.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits
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
            DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName'],
            DataColumnsFormat: ['', ''],
            Perpage: 10,
            OrderBy: ['TCNMSpl.FDCreateOn DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: [tSplInputReturnCode, "TCNMSpl.FTSplCode"],
            Text: [tSplInputReturnName, "TCNMSpl_L.FTSplName"]
        },
        NextFunc: {
            FuncName: tSplNextFuncName,
            ArgReturn: aSplArgReturn
        },
    };
    return oSplOptionReturn;
}

//Function Name : Next Function Cashier
//Create by     : mos
//Date Create   : 20/09/2021
// จากผู้จำหน่าย
$('#obtMONBrowseSupplierFrom').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptSupplierOptionFroms = undefined;
     oRptSupplierOptionFroms = oRptSupplierOptionFrom({
         'tReturnInputCode': 'oetMONSupplierCodeFrom',
         'tReturnInputName': 'oetMONSupplierNameFrom',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseSpl',
         'aArgReturn': ['FTSplCode', 'FTSplName']
     });
     JCNxBrowseData('oRptSupplierOptionFroms');
 } else {
     JCNxShowMsgSessionExpired();
 }
});

//Function Name : Next Function Cashier
//Create by     : mos
//Date Create   : 20/09/2021
// ถึงผู้จำหน่าย
$('#obtMONBrowseSupplierTo').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptSupplierOptionTos = undefined;
     oRptSupplierOptionTos = oRptSupplierOptionTo({
         'tReturnInputCode': 'oetMONSupplierCodeTo',
         'tReturnInputName': 'oetMONSupplierNameTo',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseSpl',
         'aArgReturn': ['FTSplCode', 'FTSplName']
     });
     JCNxBrowseData('oRptSupplierOptionTos');
 } else {
     JCNxShowMsgSessionExpired();
 }
});

//Function Name : Next Function Cashier
//Create by     : mos
//Date Create   : 20/09/2021
function JSxRptConsNextFuncBrowseSpl(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tSplCode = aDataNextFunc[0];
       var tSplName = aDataNextFunc[1];

       var tSplCodeFrom = $('#oetMONSupplierCodeFrom').val();
       var tSplCodeTo = $('#oetMONSupplierCodeTo').val();

       //ถ้า input from ว่างให้เอาค่าที่เลือกมาใส่
       if (tSplCodeFrom == "" || tSplCodeFrom === undefined) {
           $('#oetMONSupplierCodeFrom').val(tSplCode);
           $('#oetMONSupplierNameFrom').val(tSplName);
       }

       //ถ้า input to ว่างให้เอาค่าที่เลือกมาใส่
       if (tSplCodeTo == "" || tSplCodeTo === undefined) {
           $('#oetMONSupplierCodeTo').val(tSplCode);
           $('#oetMONSupplierNameTo').val(tSplName);
       }

       //JSxUncheckinCheckbox('oetRptSupplierCodeTo');

   }
}



var oRptSupplierGroupOption = function(poReturnInputSpl) {
    let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
    let aSplArgReturn = poReturnInputSpl.aArgReturn;
    let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
    let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    // if( tAgnCode != ''  && tAgnCode != undefined){
    //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    // }
    let oSplOptionReturn = {
      Title : ['supplier/supplier/supplier','tSplGroup'],
      Table:{Master:'TCNMSplGrp',PK:'FTSgpCode'},
      Join :{
          Table:	['TCNMSplGrp_L'],
          On:['TCNMSplGrp.FTSgpCode = TCNMSplGrp_L.FTSgpCode AND TCNMSplGrp_L.FNLngID = '+nLangEdits,]
      },
      GrideView:{
          ColumnPathLang	: 'supplier/supplier/supplier',
          ColumnKeyLang	: ['tCode','tName'],
          DataColumns		: ['TCNMSplGrp.FTSgpCode','TCNMSplGrp_L.FTSgpName'],
          Perpage			: 10,
          OrderBy		    : ['TCNMSplGrp.FDCreateOn DESC'],

      },
      CallBack:{
          ReturnType	: 'S',
          Value		: ["oetMONSupplierCodeGroup","TCNMSplGrp.FTSgpCode"],
          Text		: ["oetMONSupplierNameGroup","TCNMSplGrp_L.FTSgpName"],
      },
      RouteAddNew : 'groupsupplier',
      NextFunc: {
          FuncName: tSplNextFuncName,
          ArgReturn: aSplArgReturn
      },
    };
    return oSplOptionReturn;
}
$('#obtMONBrowseSupplierGroup').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptSupplierOptionGroup = undefined;
     oRptSupplierOptionGroup = oRptSupplierGroupOption({
         'tReturnInputCode': 'oetMONSupplierCodeGroup',
         'tReturnInputName': 'oetMONSupplierNameGroup',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseSplGroup',
         'aArgReturn': ['FTSgpCode', 'FTSgpName']
     });
     JCNxBrowseData('oRptSupplierOptionGroup');
 } else {
     JCNxShowMsgSessionExpired();
 }
});
function JSxRptConsNextFuncBrowseSplGroup(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tSplCode = aDataNextFunc[0];
       var tSplName = aDataNextFunc[1];
       $('#oetMONSupplierCodeGroup').val(tSplCode);
       $('#oetMONSupplierNameGroup').val(tSplName);
       // var tSplCode = $('#oetMONSupplierCodeGroup').val();
       // var tSplName = $('#oetMONSupplierNameGroup').val();
       // $('#oetMONSupplierCodeGroup').val(tSplCode);
       // $('#oetMONSupplierNameGroup').val(tSplName);
   }
}


var oRptSupplierTypeOption = function(poReturnInputSpl) {
    let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
    let aSplArgReturn = poReturnInputSpl.aArgReturn;
    let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
    let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    // if( tAgnCode != ''  && tAgnCode != undefined){
    //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    // }
    let oSplOptionReturn = {
      Title : ['supplier/suppliertype/suppliertype','tSTYTitle'],
      Table:{Master:'TCNMSplType',PK:'FTStyCode'},
      Join :{
          Table:	['TCNMSplType_L'],
          On:['TCNMSplType.FTStyCode = TCNMSplType_L.FTStyCode AND TCNMSplType_L.FNLngID = '+nLangEdits,]
      },
      GrideView:{
          ColumnPathLang	: 'supplier/suppliertype/suppliertype',
          ColumnKeyLang	: ['tSTYCode','tSTYName'],
          DataColumns		: ['TCNMSplType.FTStyCode','TCNMSplType_L.FTStyName'],
          Perpage			: 10,
          OrderBy		    : ['TCNMSplType.FDCreateOn DESC'],

      },
      CallBack:{
          ReturnType	: 'S',
          Value		: ["oetMONSupplierCodeType","TCNMSplType.FTStyCode"],
          Text		: ["oetMONSupplierNameType","TCNMSplType_L.FTStyName"],
      },
      RouteAddNew : 'groupsupplier',
      NextFunc: {
          FuncName: tSplNextFuncName,
          ArgReturn: aSplArgReturn
      },
    };
    return oSplOptionReturn;
}
$('#obtMONBrowseSupplierType').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptSupplierTypeOptions = undefined;
     oRptSupplierTypeOptions = oRptSupplierTypeOption({
         'tReturnInputCode': 'oetMONSupplierCodeType',
         'tReturnInputName': 'oetMONSupplierNameType',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseSplType',
         'aArgReturn': ['FTStyCode', 'FTStyName']
     });
     JCNxBrowseData('oRptSupplierTypeOptions');
 } else {
     JCNxShowMsgSessionExpired();
 }
});

function JSxRptConsNextFuncBrowseSplType(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tSplCode = aDataNextFunc[0];
       var tSplName = aDataNextFunc[1];
       $('#oetMONSupplierCodeType').val(tSplCode);
       $('#oetMONSupplierNameType').val(tSplName);
   }
}




var oRptSupplierLevelOption = function(poReturnInputSpl) {
    let tSplNextFuncName = poReturnInputSpl.tNextFuncName;
    let aSplArgReturn = poReturnInputSpl.aArgReturn;
    let tSplInputReturnCode = poReturnInputSpl.tReturnInputCode;
    let tSplInputReturnName = poReturnInputSpl.tReturnInputName;
    let tCondition          = '';
    let tAgnCode            = $('#oetSpcAgncyCode').val();
    // if( tAgnCode != ''  && tAgnCode != undefined){
    //     tCondition += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') ='' ";
    // }
    let oSplOptionReturn = {
      Title : ['supplier/supplierlev/supplierlev','tSLVTitle'],
      Table:{Master:'TCNMSplLev',PK:'FTSlvCode'},
      Join :{
          Table:	['TCNMSplLev_L'],
          On:['TCNMSplLev.FTSlvCode = TCNMSplLev_L.FTSlvCode AND TCNMSplLev_L.FNLngID = '+nLangEdits,]
      },
      GrideView:{
          ColumnPathLang	: 'supplier/supplierlev/supplierlev',
          ColumnKeyLang	: ['tSLVCode','tSLVName'],
          DataColumns		: ['TCNMSplLev.FTSlvCode','TCNMSplLev_L.FTSlvName'],
          Perpage			: 10,
          OrderBy		    : ['TCNMSplLev.FDCreateOn DESC'],

      },
      CallBack:{
          ReturnType	: 'S',
          Value		: ["oetMONSupplierCodeClass","TCNMSplLev.FTSlvCode"],
          Text		: ["oetMONSupplierNameClass","TCNMSplLev_L.FTSlvName"],
      },
      RouteAddNew : 'groupsupplier',
      NextFunc: {
          FuncName: tSplNextFuncName,
          ArgReturn: aSplArgReturn
      },
    };
    return oSplOptionReturn;
}
$('#obtMONBrowseSupplierClass').unbind().click(function() {
 var nStaSession = JCNxFuncChkSessionExpired();
 if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
     JSxCheckPinMenuClose(); // Hidden Pin Menu
     window.oRptSupplierOption = undefined;
     oRptSupplierOption = oRptSupplierLevelOption({
         'tReturnInputCode': 'oetMONSupplierCodeClass',
         'tReturnInputName': 'oetMONSupplierNameClass',
         'tNextFuncName': 'JSxRptConsNextFuncBrowseSplClass',
         'aArgReturn': ['FTSlvCode', 'FTSlvName']
     });
     JCNxBrowseData('oRptSupplierOption');
 } else {
     JCNxShowMsgSessionExpired();
 }
});
function JSxRptConsNextFuncBrowseSplClass(poDataNextFunc) {
   if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
       var aDataNextFunc = JSON.parse(poDataNextFunc);
       var tSplCode = aDataNextFunc[0];
       var tSplName = aDataNextFunc[1];
       $('#oetMONSupplierCodeClass').val(tSplCode);
       $('#oetMONSupplierNameClass').val(tSplName);
   }
}
