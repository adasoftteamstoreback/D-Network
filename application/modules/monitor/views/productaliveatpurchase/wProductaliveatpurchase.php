<input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPSupplierCodeClick" name="oetPAPSupplierCodeClick" maxlength="5">
<input type="text" class="form-control xCNHide xWRptAllInput" id="ohdRptRoute" name="ohdRptRoute" value="<?php echo base_url("/monPAPExportExcel?") ?>" maxlength="5">
<?php 
    $tLangID = $this->session->userdata("tLangEdit");
?>
<input type="hidden" name="oetLangID" id="oetLangID" value="<?=$tLangID?>">
<div class="odvContentPage" id="odvContentPage">

<div id="odvAuditMainMenu" class="main-menu">
  <input type="hidden" name="oetSpcAgncyCode" id="oetSpcAgncyCode" value=""> 
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNDepositVMaster">
                <div class="col-xs-12 col-md-6">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <li style="cursor:pointer;" ><img id="oimImgFavicon" style="cursor:pointer; margin-top: -10px;" width="20px;" height="20px;" src="<?php echo base_url();?>application/modules/common/assets/images/icons/favorit.PNG" class="xCNDisabled xWImgDisable"></li>
                        <li id="oliAuditTitle" class="xCNLinkClick" onclick="JSxMonitorDefultHeader()"><?= language('monitor/monitor/monitor','tPAPTitle')?></li>
                        <input type="hidden" id="oetSesUsrLevel" value="<?php echo $this->session->userdata('tSesUsrLevel'); ?>">
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                      <button class="btn xCNBTNDefult xCNBTNDefult2Btn" id="obtAUDExport" onclick="JSxMonitorExportExcel();"  type="button"><?= language('monitor/monitor/monitor','tMONBtnExport')?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-content" id="odvContent">
  <form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmPAPSearchData">
    <div id="odvContentPagePAP"  class="panel panel-headline">
      <div class="panel-heading">
        <div class="row">
            <?php 
                $tUsrLevel = $this->session->userdata('tSesUsrLevel');
                $tBchMulti = $this->session->userdata("tSesUsrBchCodeMulti");

                if( $tUsrLevel != "HQ" ){
                    $tSatAgnCode = $this->session->userdata('tSesUsrAgnCode');
                    $tSatAgnName = $this->session->userdata('tSesUsrAgnName');
                    $tDisabled = 'disabled';
                }else{
                    $tSatAgnCode ='';
                    $tSatAgnName ='';
                    $tDisabled = '';
                }
            ?>
            <input type="hidden" id="ohdUsrLevel" value="<?=$tUsrLevel?>">
            <input type="hidden" id="ohdBchMulti" value="<?=$tBchMulti?>">
            <!-- ตัวแทนขาย -->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPAgnName')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPAgnCode" name="oetPAPAgnCode"  maxlength="5" value="<?php echo $tSatAgnCode?>">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetPAPAgnName" name="oetPAPAgnName" value="<?php echo $tSatAgnName?>" readonly placeholder="<?= language('monitor/monitor/monitor','tPAPAgnName')?>">
                        <span class="input-group-btn">
                            <button id="obtPAPBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled?>>
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- สาขา -->
            <?php
                if ( $this->session->userdata("tSesUsrLevel") != "HQ" ){
                    if( $this->session->userdata("nSesUsrBchCount") <= 1 ){ //ค้นหาขั้นสูง
                        $tBCHCode 	= $this->session->userdata("tSesUsrBchCodeDefault");
                        $tBCHName 	= $this->session->userdata("tSesUsrBchNameDefault");
                    }else{
                        $tBCHCode 	= '';
                        $tBCHName 	= '';
                    }
                }else{
                    $tBCHCode 		= '';
                    $tBCHName 		= '';
                }
            ?>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPBch')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPBchCode" name="oetPAPBchCode" maxlength="5" value="<?=$tBCHCode?>">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" value="<?=$tBCHName?>" id="oetPAPBchName" name="oetPAPBchName" readonly placeholder="<?= language('monitor/monitor/monitor','tPAPBch')?>">
                        <span class="input-group-btn">
                            <button id="obtPAPBrowseBch" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- คลังสินค้า -->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPWahName')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPWahCode" name="oetPAPWahCode" maxlength="5">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetPAPWahName" name="oetPAPWahName" placeholder="<?= language('monitor/monitor/monitor','tPAPWahName')?>" readonly>
                        <span class="input-group-btn">
                            <button id="obtPAPBrowseWahCode" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- รหัสสินค้า -->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPPdtCode')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPPdtCode" name="oetPAPPdtCode" maxlength="5">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetPAPPdtName" name="oetPAPPdtName" readonly placeholder="<?= language('monitor/monitor/monitor','tPAPPdtCode')?>">
                        <span class="input-group-btn">
                            <button id="obtPAPBrowsePdtCode" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- กลุ่มสินค้า -->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPGrpProduct')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPGrpProductCode" name="oetPAPGrpProductCode" maxlength="5">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetPAPGrpProductName" name="oetPAPGrpProductName" placeholder="<?= language('monitor/monitor/monitor','tPAPGrpProduct')?>" readonly>
                        <span class="input-group-btn">
                            <button id="obtPAPBrowseGrpProductCode" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- ยี่ห้อสินค้า -->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPProductBrand')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPProducBrandCode" name="oetPAPProducBrandCode" maxlength="5">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetPAPProductBrandName" name="oetPAPProductBrandName" placeholder="<?= language('monitor/monitor/monitor','tPAPProductBrand')?>" readonly>
                        <span class="input-group-btn">
                            <button id="obtPAPBrowsePdtBrand" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- รุ่นสินค้า -->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPProductModel')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPProducModelCode" name="oetPAPProducModelCode" maxlength="5">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetPAPProductModelName" name="oetPAPProductModelName" placeholder="<?= language('monitor/monitor/monitor','tPAPProductModel')?>" readonly>
                        <span class="input-group-btn">
                            <button id="obtPAPBrowsePdtModel" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- ประเภทสินค้า -->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tPAPProductType')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xWRptAllInput" id="oetPAPProductTypeCode" name="oetPAPProductTypeCode" maxlength="5">
                        <input type="text" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetPAPProductTypeName" name="oetPAPProductTypeName" placeholder="<?= language('monitor/monitor/monitor','tPAPProductType')?>" readonly>
                        <span class="input-group-btn">
                            <button id="obtPAPBrowsePdtTypeCode" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- สถานะะสั่งซื้อ-->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('common/main/main', 'สถานะสั่งซื้อ'); ?></label>
                    <select class="selectpicker form-control" id="ocmStaDocOrder" name="ocmStaDocOrder">
                        <option value='0' selected><?php echo language('common/main/main', 'tAll'); ?></option>
                        <option value='1'><?php echo language('common/main/main', 'ควรสั่งซื้อ'); ?></option>
                        <option value='2'><?php echo language('common/main/main', 'ยังไม่ต้องสั่งซื้อ'); ?></option>
                    </select>
                </div>
            </div>

            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-4">
                <div class="form-group">
                    <label class="xCNLabelFrm"></label>
                    <div class="">
                        <a id="oahPAPSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?= language('monitor/monitor/monitor','tMONlabelClear')?></a>
                        &nbsp;
                        <a id="oahPAPAdvanceSearch" class="btn xCNBTNPrimery  xCNBTNDefult1Btn" style="width:25%" onclick="JSxPAPSearchData()"><?= language('monitor/monitor/monitor','tMONlabelSearch')?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" id="otbPAPDataListSupl">

                </div>
            </div>
        </div>
    </div>
</div>
</form>

</div>

</div>
<script src="<?php echo base_url('application/modules/monitor/assets/src/productaliveatpurchase/jProductaliveatpurchase.js'); ?>"></script>
<script type="text/javascript">
    $('document').ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        JSxPAPSearchData();
    });

    $('#oahPAPSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxPAPClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxPAPClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmPAPSearchData').find('input').val('');
            JSxMonitorDefultHeader();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
  
    function JSxPAPSearchData() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var oAdvanceSearch = JSoPAPGetAdvanceSearchData();
        JCNxOpenLoading();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        $.ajax({
            type: "POST",
            url: "monPAPList",
            data: {oAdvanceSearch : oAdvanceSearch},
            cache: false,
            timeout: 0,
            success: function(wResult){
                $("#otbPAPDataListSuplDetail").html("");
                $("#otbPAPDataListSupl").html(wResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        }
    }

    // รวม Values ต่างๆของการค้นหาขั้นสูง
    function JSoPAPGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tAgnCode            : $("#oetPAPAgnCode").val(),
            tBchCode            : $("#oetPAPBchCode").val(),
            tWahCode            : $("#oetPAPWahCode").val(),
            tPdtCode            : $("#oetPAPPdtCode").val(),
            tGrpProductCode     : $("#oetPAPGrpProductCode").val(),
            tProducBrandCode    : $("#oetPAPProducBrandCode").val(),
            tProducModelCode    : $("#oetPAPProducModelCode").val(),
            tProductTypeCode    : $("#oetPAPProductTypeCode").val(),
            nStaOrder           : $('#ocmStaDocOrder').val()
        };
        return oAdvanceSearchData;
    }

    function JSxMonitorDefultHeader() {
        var nStaSession = JCNxFuncChkSessionExpired();
        JCNxOpenLoading();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        $.ajax({
            type: "POST",
            url: "monPAP/0/0",
            cache: false,
            timeout: 0,
            success: function(wResult){

                $('#odvContentPage').html(wResult);

                //JSxMonitorDefult();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        }
    }

    function JSxMonitorExportExcel() {
        var nStaSession = JCNxFuncChkSessionExpired();
        JCNxOpenLoading();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        var data =
        "&oetPAPAgnCode="+$("#oetPAPAgnCode").val()+
        "&oetPAPBchCode="+$("#oetPAPBchCode").val()+
        "&oetPAPWahCode="+$("#oetPAPWahCode").val()+
        "&oetPAPPdtCode="+$("#oetPAPPdtCode").val()+
        "&oetPAPGrpProductCode="+$("#oetPAPGrpProductCode").val()+
        "&oetPAPProducBrandCode="+$("#oetPAPProducBrandCode").val()+
        "&oetPAPProducModelCode="+$("#oetPAPProducModelCode").val()+
        "&oetPAPProductTypeCode="+$("#oetPAPProductTypeCode").val()+
        "&nStaOrder="+$('#ocmStaDocOrder').val()
        JCNxCloseLoading();
        window.location.href = $("#ohdRptRoute").val()+data;
        }
    }

    // สาขา
    $('#obtPAPBrowseBch').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oPAPBrowseBch = undefined;
            oPAPBrowseBch = oPAPBrowseBchCode({
                'tReturnInputCode': 'oetPAPBchCode',
                'tReturnInputName': 'oetPAPBchName',
            });
            JCNxBrowseData('oPAPBrowseBch');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
    var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
    var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
    var tWhere 			= "";

    if(nCountBch == 1){
        $('#obtPAPBrowseBch').attr('disabled',true);
    }
    if(tUsrLevel != "HQ"){
        tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
    }else{
        tWhere = "";
    }

    var oPAPBrowseBchCode = function(poReturnInputBch) {
        let tInputReturnCode    = poReturnInputBch.tReturnInputCode;
        let tInputReturnName    = poReturnInputBch.tReturnInputName;
        var tWhere              = '';
        var tSQLWhereBch        = '';
        var tSQLWhereAgn        = '';
        var tAgnCode            = $('#oetPAPAgnCode').val();
        var tUsrLevel           = $('#ohdUsrLevel').val();
        var tBchMulti           = $('#ohdBchMulti').val();
        
        if(tUsrLevel != "HQ"){
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+")";
        }else{
            tSQLWhereBch = "";
        }

        if(tAgnCode != ""){
            tSQLWhereAgn = "AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }else{
            tSQLWhereAgn = "";
        }

        let oOptionReturn = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: [
                    'TCNMBranch.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits
                ]
            },
            Where: {
                Condition: [tSQLWhereAgn,tSQLWhereBch,tWhere]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBchCode', 'tBCHSubTitle'],
                ColumnsSize: ['15%', '90%'],
                WidthModal: 50,
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }

</script>
