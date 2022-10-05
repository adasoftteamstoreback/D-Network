<input type="text" class="form-control xCNHide xWRptAllInput" id="oetDLSSupplierCodeClick" name="oetDLSSupplierCodeClick" maxlength="5">
<input type="text" class="form-control xCNHide xWRptAllInput" id="ohdRptRoute" name="ohdRptRoute" value="<?php echo base_url("/monDelayExportExcel?") ?>" maxlength="5">

<div class="odvContentPage" id="odvContentPage">

<div id="odvAuditMainMenu" class="main-menu">
  <input type="hidden" name="oetSpcAgncyCode" id="oetSpcAgncyCode" value="">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNDepositVMaster">
                <div class="col-xs-12 col-md-6">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <li style="cursor:pointer;" ><img id="oimImgFavicon" style="cursor:pointer; margin-top: -10px;" width="20px;" height="20px;" src="<?php echo base_url();?>application/modules/common/assets/images/icons/favorit.PNG" class="xCNDisabled xWImgDisable"></li>
                        <li id="oliAuditTitle" class="xCNLinkClick" onclick="JSxMonitorDefultHeader()"><?= language('monitor/monitor/monitor','tDLSTitle')?></li>
                        <input type="hidden" id="oetSesUsrLevel" value="<?php echo $this->session->userdata('tSesUsrLevel'); ?>">
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                      <button class="btn xCNBTNPrimery  xCNBTNDefult2Btn" id="obtAUDback" onclick="JSxMonitorDefultHeader();"  type="button"><?= language('monitor/monitor/monitor','tDLSBtnBack')?></button>
                      <button class="btn xCNBTNDefult xCNBTNDefult2Btn" id="obtAUDExport" onclick="JSxMonitorExportExcel();"  type="button"><?= language('monitor/monitor/monitor','tDLSBtnExport')?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-content" id="odvContent">
  <form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmDLSSearchData">
    <div id="odvContentPageDLS"  class="panel panel-headline">
      <div class="panel-heading">
        <div class="row">
              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                  <div class="form-group">
                      <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelSplFrom')?></label>
                      <div class="input-group">
                          <input type="text" class="form-control xCNHide xWRptAllInput" id="oetDLSCustomerCodeFrom" name="oetDLSCustomerCodeFrom" maxlength="5">
                          <input type="text" placeholder="<?= language('monitor/monitor/monitor','tDLSlabelSplFrom')?>" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetDLSCustomerNameFrom" name="oetDLSCustomerNameFrom" readonly="">
                          <span class="input-group-btn">
                              <button id="obtDLSBrowseCustomerFrom" type="button" class="btn xCNBtnBrowseAddOn">
                                  <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                              </button>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                  <div class="form-group">
                      <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelSplTo')?></label>
                      <div class="input-group">
                          <input type="text" class="form-control xCNHide xWRptAllInput" id="oetDLSCustomerCodeTo" name="oetDLSCustomerCodeTo" maxlength="5">
                          <input type="text" placeholder="<?= language('monitor/monitor/monitor','tDLSlabelSplTo')?>" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetDLSCustomerNameTo" name="oetDLSCustomerNameTo" readonly="">
                          <span class="input-group-btn">
                              <button id="obtDLSBrowseCustomerTo" type="button" class="btn xCNBtnBrowseAddOn">
                                  <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                              </button>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                  <div class="form-group">
                      <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelSplGrp')?></label>
                      <div class="input-group">
                          <input type="text" class="form-control xCNHide xWRptAllInput" id="oetDLSCustomerCodeGroup" name="oetDLSCustomerCodeGroup" maxlength="5">
                          <input type="text" placeholder="<?= language('monitor/monitor/monitor','tDLSlabelSplGrp')?>" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetDLSCustomerNameGroup" name="oetDLSCustomerNameGroup" readonly="">
                          <span class="input-group-btn">
                              <button id="obtDLSBrowseCustomerGroup" type="button" class="btn xCNBtnBrowseAddOn">
                                  <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                              </button>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                  <div class="form-group">
                      <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelSplTyp')?></label>
                      <div class="input-group">
                          <input type="text" class="form-control xCNHide xWRptAllInput" id="oetDLSCustomerCodeType" name="oetDLSCustomerCodeType" maxlength="5">
                          <input type="text" placeholder="<?= language('monitor/monitor/monitor','tDLSlabelSplTyp')?>" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetDLSCustomerNameType" name="oetDLSCustomerNameType" readonly="">
                          <span class="input-group-btn">
                              <button id="obtDLSBrowseCustomerType" type="button" class="btn xCNBtnBrowseAddOn">
                                  <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                              </button>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                  <div class="form-group">
                      <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelSplClass')?></label>
                      <div class="input-group">
                          <input type="text" class="form-control xCNHide xWRptAllInput" id="oetDLSSupplierCodeClass" name="oetDLSSupplierCodeClass" maxlength="5">
                          <input type="text" placeholder="<?= language('monitor/monitor/monitor','tDLSlabelSplClass')?>" class="form-control xWPointerEventNone xWRptAllInput xCNInputNewUI" id="oetDLSSupplierNameClass" name="oetDLSSupplierNameClass" readonly="">
                          <span class="input-group-btn">
                              <button id="obtDLSBrowseCustomerClass" type="button" class="btn xCNBtnBrowseAddOn">
                                  <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                              </button>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                  <div class="form-group">
                      <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelClassDeldate')?></label>
                      <select class="selectpicker form-control" name="ocmDLSClass" id="ocmDLSClass">
                        <option class="xWPdtForSystem2" value="A"><?= language('monitor/monitor/monitor','tDLSlabelClassAll')?></option>
                        <?php
                              foreach ($aDataList['aItems'] as $nKey => $aValue) {
                                $tOptionval = $aValue['FTOdlType']."_".$aValue['FNOdlMin']."_".$aValue['FNOdlMax'];
                                // switch ($aValue['FTOdlType']) {
                                //     case "1":
                                //         $aValue['FTOdlType'] = language('service/overduel/overduel','tOdloption1');
                                //         $tTO =" - ";
                                // break;
                                //     case "2":
                                //         $aValue['FTOdlType'] = language('service/overduel/overduel','tOdloption2');
                                //         $tTO ="";
                                // break;
                              //} ?>
                              <option  value="<?php echo $tOptionval; ?>">
                                <?php if ($aValue['FTOdlType'] =="1") { echo language('service/overduel/overduel','tOdloption1'); }else{ ?>
                                <?php  echo language('service/overduel/overduel','tOdloption2'); } ?>
                                <?php echo $aValue['FNOdlMin']; ?> <?php if ($aValue['FNOdlMax']=='0' || $aValue['FNOdlMax']=='') { echo language('service/overduel/overduel','tDLSDays'); }else{ echo "- ".$aValue['FNOdlMax'].language('service/overduel/overduel','tDLSDays'); }  ?>

                            </option><?php
                              }
                         ?>
                      </select>
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelDeldateFrom')?></label>
                    <div class="input-group">
                      <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDLSRefBilDocDateFrom" name="oetDLSRefBilDocDateFrom" value="" placeholder="YYYY-MM-DD" maxlength="10">
                      <span class="input-group-btn">
                        <button id="obtDLSBrowseRefBilDocDateFrom" name="obtDLSBrowseRefBilDocDateFrom" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                      </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelDeldateTo')?></label>
                    <div class="input-group">
                      <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDLSRefBilDocDateTo" name="oetDLSRefBilDocDateTo" value="" placeholder="YYYY-MM-DD" maxlength="10">
                      <span class="input-group-btn">
                        <button id="obtDLSBrowseRefBilDocDateTo" name="obtDLSBrowseRefBilDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                      </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelFromDeldate')?></label>
                    <div class="input-group">
                      <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDLSRefDealDateFrom" name="oetDLSRefDealDateFrom" value="" placeholder="YYYY-MM-DD" maxlength="10">
                      <span class="input-group-btn">
                        <button id="obtDLSBrowseRefDealDateFrom" name="obtDLSBrowseRefDealDateFrom" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                      </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('monitor/monitor/monitor','tDLSlabelToDeldate')?></label>
                    <div class="input-group">

                      <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDLSRefDealDateTo" name="oetDLSRefDealDateTo" value="" placeholder="YYYY-MM-DD" maxlength="10">
                      <span class="input-group-btn">
                        <button id="obtDLSBrowseRefDealDateTo" name="obtDLSBrowseRefDealDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                      </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
              <div class="form-group">
                <label class="xCNLabelFrm"></label>
                <div >
                  <a id="oahADCSearchReset" class="btn xCNBTNPrimery  xCNBTNDefult1Btn" href="javascript:;" style="min-width: 130px;" onclick="JSxDLSSearchData()"><?= language('monitor/monitor/monitor','tDLSlabelSearch')?></a>
                  &nbsp;
                  <a id="oahADCAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" ><?= language('monitor/monitor/monitor','tDLSlabelClear')?></a>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive" id="otbDLSDataListSupl">

            </div>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive" id="otbDLSDataListSuplDetail">

            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>

</div>
<script src="<?php echo base_url('application/modules/monitor/assets/src/delaystatus/jDelaystatus.js'); ?>"></script>
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
      JSxMonitorDefult();
      if ($("#oetDLSSupplierCodeClick").val()!="") {
        JSxDLSListDetail($("#oetDLSSupplierCodeClick").val());
      }else {

      }
  });
  $("#oahADCAdvanceSearch").click(function(event) {
      $('#oetDLSRefBilDocDateFrom').val('');
      $('#oetDLSRefBilDocDateTo').val('');
      $('#oetDLSRefDealDateFrom').val('');
      $('#oetDLSRefDealDateTo').val('');
      $("#oetDLSCustomerCodeFrom").val("");
      $("#oetDLSCustomerNameFrom").val("");
      $("#oetDLSCustomerCodeTo").val("");
      $("#oetDLSCustomerNameTo").val("");
      $("#oetDLSCustomerCodeGroup").val("");
      $("#oetDLSCustomerNameGroup").val("");
      $("#oetDLSCustomerCodeType").val("");
      $("#oetDLSCustomerNameType").val("");
      $("#oetDLSSupplierCodeClass").val("");
      $("#oetDLSSupplierNameClass").val("");
      $("#oetDLSSupplierCodeClick").val("");
      $("#ocmDLSClass").val("A");
      $('.selectpicker').selectpicker('refresh');
  });
  $('#obtDLSBrowseRefBilDocDateFrom').click(function(event){
      $('.xCNDatePicker').datepicker("hide");
      $('#oetDLSRefBilDocDateFrom').datepicker('show');
  });
  $('#obtDLSBrowseRefBilDocDateTo').click(function(event){
      $('.xCNDatePicker').datepicker("hide");
      $('#oetDLSRefBilDocDateTo').datepicker('show');
  });
  $('#obtDLSBrowseRefDealDateFrom').click(function(event){
      $('.xCNDatePicker').datepicker("hide");
      $('#oetDLSRefDealDateFrom').datepicker('show');
  });
  $('#obtDLSBrowseRefDealDateTo').click(function(event){
      $('.xCNDatePicker').datepicker("hide");
      $('#oetDLSRefDealDateTo').datepicker('show');
  });
  
  function JSxDLSSearchData() {
     var nStaSession = JCNxFuncChkSessionExpired();
     JCNxOpenLoading();
     if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
       $.ajax({
           type: "POST",
           url: "monDelayListSearchData",
           data: $('#ofmDLSSearchData').serialize(),
           cache: false,
           timeout: 0,
           success: function(wResult){
               $("#otbDLSDataListSuplDetail").html("");
               $("#otbDLSDataListSupl").html(wResult);
               JCNxCloseLoading();
           },
           error: function(jqXHR, textStatus, errorThrown) {
               JCNxResponseError(jqXHR, textStatus, errorThrown);
           }
       });
     }
  }
  function JSxMonitorDefultHeader() {
    var nStaSession = JCNxFuncChkSessionExpired();
    JCNxOpenLoading();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
      $.ajax({
          type: "POST",
          url: "monDelay/0/0",
          cache: false,
          timeout: 0,
          success: function(wResult){

              JCNxCloseLoading();
              $('#odvContentPage').html(wResult);

              //JSxMonitorDefult();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              JCNxResponseError(jqXHR, textStatus, errorThrown);
          }
      });
    }
  }
  function JSxMonitorDefult() {
    var nStaSession = JCNxFuncChkSessionExpired();
    JCNxOpenLoading();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
      $.ajax({
          type: "POST",
          url: "monDelayList",
          cache: false,
          timeout: 0,
          success: function(wResult){
              $('#oliAuditTitle').html("ตรวจสอบสถานะลูกหนี้ค้างชำระ");
              $("#otbDLSDataListSuplDetail").html("");
              $("#otbDLSDataListSupl").html(wResult);
              JCNxCloseLoading();
              $('#obtAUDback').hide();
              $('#obtAUDExport').show();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              JCNxResponseError(jqXHR, textStatus, errorThrown);
          }
      });
    }
  }
  function JSxDLSListDetail(ptCstCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    JCNxOpenLoading();
    JSxDLSClearColor();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
      $.ajax({
          type: "POST",
          url: "monDelayListSuplDetail",
          data: {
            "ocmDLSClass":$("#ocmDLSClass").val(),
            "oetDLSRefBilDocDateFrom":$("#oetDLSRefBilDocDateFrom").val(),
            "oetDLSRefBilDocDateTo":$("#oetDLSRefBilDocDateTo").val(),
            "oetDLSRefDealDateFrom":$("#oetDLSRefDealDateFrom").val(),
            "oetDLSRefDealDateTo":$("#oetDLSRefDealDateTo").val(),
            "oetDLSCustomerCodeFrom":$("#oetDLSCustomerCodeFrom").val(),
            "oetDLSCustomerCodeTo":$("#oetDLSCustomerCodeTo").val(),
            "oetDLSCustomerCodeGroup":$("#oetDLSCustomerCodeGroup").val(),
            "oetDLSCustomerCodeType":$("#oetDLSCustomerCodeType").val(),
            "oetDLSSupplierCodeClass":$("#oetDLSSupplierCodeClass").val(),
            "ptCstCode":ptCstCode
          },
          cache: false,
          timeout: 0,
          success: function(wResult){
              $("#otbDLSDataListSuplDetail").html(wResult);
              //$("#otrDLSLisHead"+ptCstCode+"")val;
              $("#oetDLSSupplierCodeClick").val(ptCstCode);
              $("#otrDLSLisHead"+ptCstCode+"").children('td, th').css('background-color','#60a7d4');
              JCNxCloseLoading();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              JCNxResponseError(jqXHR, textStatus, errorThrown);
          }
      });
    }
  }
  function JSxDLSClearColor() {
    var nlen = $('input[name="otrDLSLisHead[]"]').valueOf().length;
    for (var i = 0; i < nlen; i++) {
      var tId = $('input[name="otrDLSLisHead[]"]').valueOf()[i]['value'];
      $("#otrDLSLisHead"+tId+"").children('td, th').css('background-color','#FFFFFF');
    }
  }
  function JSvSCallPageEdit(ptDocumentNumber){
      var nStaSession = JCNxFuncChkSessionExpired();
      if(typeof nStaSession !== "undefined" && nStaSession == 1) {
          JCNxOpenLoading();
          $.ajax({
              type    : "POST",
              url     : "docABBPageEdit",
              data    : {'ptDocNo' : ptDocumentNumber},
              cache   : false,
              timeout : 0,
              success: function(tResult){
                  var aReturnData = JSON.parse(tResult);
                  if(aReturnData['nStaEvent'] == '1'){
                      JSxIVNavDefult('showpage_edit');
                      $('#odvContent').html(aReturnData['tViewPageAdd']);
                      $('#oliAuditTitle').html("ใบกำกับภาษีอย่างย่อ(ใบขาย/ใบคืน)");
                      JCNxCloseLoading();
                      $('#obtAUDback').show();
                      $('#obtAUDExport').hide()
                      //โหลดสินค้าใน Temp
                    //   JSvIVLoadPdtDataTableHtml();

                      //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
                    //   JSxIVControlFormWhenCancelOrApprove();
                  }else{
                      var tMessageError   = aReturnData['tStaMessg'];
                      FSvCMNSetMsgErrorDialog(tMessageError);
                      JCNxCloseLoading();
                  }
              },
              error: function (jqXHR, textStatus, errorThrown){
                  JCNxResponseError(jqXHR, textStatus, errorThrown);
              }
          });
      }else{
          JCNxShowMsgSessionExpired();
      }
  }
  function JSxIVNavDefult(ptType) {
      if(ptType == 'showpage_list'){
          $("#oliIVTitleAdd").hide();
          $("#oliIVTitleEdit").hide();
          $("#odvBtnAddEdit").hide();
          $('#odvBtnIVPageAddorEdit').show();
      }else if(ptType == 'showpage_add'){
          $("#oliIVTitleAdd").show();
          $("#oliIVTitleEdit").hide();
          $("#odvBtnAddEdit").show();
          $('#odvBtnIVPageAddorEdit').hide();

          $('#obtIVApproveDoc').hide();
          $('#obtIVPrintDoc').hide();
          $('#obtIVCancelDoc').hide();
          $('.xCNBTNSaveDoc').show();
      }else if(ptType == 'showpage_edit'){
          $("#oliIVTitleAdd").hide();
          $("#oliIVTitleEdit").show();

          $("#odvBtnAddEdit").show();
          $('#odvBtnIVPageAddorEdit').hide();
          $('#obtIVApproveDoc').show();
          $('#obtIVPrintDoc').show();
          $('#obtIVCancelDoc').show();
          $('.xCNBTNSaveDoc').show();
      }

      //ล้างค่า
      localStorage.removeItem('IV_LocalItemDataDelDtTemp');
      localStorage.removeItem('LocalItemData');
  }
  //สินค้าใน DT
  function JSvIVLoadPdtDataTableHtml(pnPage){
      var nStaSession = JCNxFuncChkSessionExpired();
      if(typeof nStaSession !== "undefined" && nStaSession == 1){
          if($("#ohdIVRoute").val() == "docInvoiceEventAdd"){
              var tIVDocNo    = "";
          }else{
              var tIVDocNo    = $("#oetIVDocNo").val();
          }

          var tIVStaApv       = $("#ohdIVStaApv").val();
          var tIVStaDoc       = $("#ohdIVStaDoc").val();
          var tIVVATInOrEx    = $("#ocmIVfoVatInOrEx").val();

          if(pnPage == '' || pnPage == null){
              var pnNewPage = 1;
          }else{
              var pnNewPage = pnPage;
          }
          var nPageCurrent  = pnNewPage;

          $.ajax({
              type    : "POST",
              url     : "docInvoiceTableDTTemp",
              data    : {
                  'tBCHCode'              : $('#ohdIVBchCode').val(),
                  'ptIVDocNo'             : tIVDocNo,
                  'ptIVStaApv'            : tIVStaApv,
                  'ptIVStaDoc'            : tIVStaDoc,
                  'ptIVVATInOrEx'         : tIVVATInOrEx,
              },
              cache: false,
              Timeout: 0,
              success: function (oResult){
                  var aReturnData = JSON.parse(oResult);
                  if(aReturnData['nStaEvent'] == '1') {
                      $('#odvIVDataPdtTableDTTemp').html(aReturnData['tIVPdtAdvTableHtml']);
                      var aIVEndOfBill = aReturnData['aIVEndOfBill'];
                      JSxIVSetFooterEndOfBill(aIVEndOfBill);
                      JCNxCloseLoading();
                  }else{
                      var tMessageError = aReturnData['tStaMessg'];
                      FSvCMNSetMsgErrorDialog(tMessageError);
                      JCNxCloseLoading();
                  }
              },
              error: function (jqXHR, textStatus, errorThrown) {
                  JCNxResponseError(jqXHR, textStatus, errorThrown);
              }
          });
      }else{
          JCNxShowMsgSessionExpired();
      }
  }
  //Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
  function JSxIVControlFormWhenCancelOrApprove(){
      var tStatusDoc = $('#ohdIVStaDoc').val();
      var tStatusApv = $('#ohdIVStaApv').val();

      //control ฟอร์ม
      if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){ //เอกสารยกเลิก
          //ปุ่มเลือก
          $('.xCNBtnBrowseAddOn').addClass('disabled');
          $('.xCNBtnBrowseAddOn').attr('disabled',true);

          //ปุ่มเวลา
          $('.xCNBtnDateTime').addClass('disabled');
          $('.xCNBtnDateTime').attr('disabled',true);

          //อินพุต
          $('.form-control').attr('readonly', true);

          //เพิ่มข้อมูลสินค้า
          $('.xCNHideWhenCancelOrApprove').hide();

          //พวก selectpicker
          $('.selectpicker').prop("disabled",true)
      }

      //control ปุ่ม
      if(tStatusDoc == 3 ){ //เอกสารยกเลิก
          //ปุ่มยกเลิก
          $('#obtIVCancelDoc').hide();

          //ปุ่มอนุมัติ
          $('#obtIVApproveDoc').hide();

          //ปุ่มบันทึก
          $('.xCNBTNSaveDoc').hide();
      }else if(tStatusDoc == 1 && tStatusApv == 1){ //เอกสารอนุมัติแล้ว
          //ปุ่มยกเลิก
          $('#obtIVCancelDoc').hide();

          //ปุ่มอนุมัติ
          $('#obtIVApproveDoc').hide();

          //ปุ่มบันทึก
          $('.xCNBTNSaveDoc').show();

          //สามารถกรอกหมายเหตุได้
          $('#otaIVRemark').attr('readonly', false);
      }
  }

  function JSxMonitorExportExcel() {
    var nStaSession = JCNxFuncChkSessionExpired();
    JCNxOpenLoading();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
      var data =
      "&ocmDLSClass="+$("#ocmDLSClass").val()+
      "&oetDLSRefBilDocDateFrom="+$("#oetDLSRefBilDocDateFrom").val()+
      "&oetDLSRefBilDocDateTo="+$("#oetDLSRefBilDocDateTo").val()+
      "&oetDLSRefDealDateFrom="+$("#oetDLSRefDealDateFrom").val()+
      "&oetDLSRefDealDateTo="+$("#oetDLSRefDealDateTo").val()+
      "&oetDLSCustomerCodeFrom="+$("#oetDLSCustomerCodeFrom").val()+
      "&oetDLSCustomerCodeTo="+$("#oetDLSCustomerCodeTo").val()+
      "&oetDLSCustomerCodeGroup="+$("#oetDLSCustomerCodeGroup").val()+
      "&oetDLSCustomerCodeType="+$("#oetDLSCustomerCodeType").val()+
      "&oetDLSSupplierCodeClass="+$("#oetDLSSupplierCodeClass").val()+
      "&ptCstCode="+$("#oetDLSSupplierCodeClick").val();
      JCNxCloseLoading();
      window.location.href = $("#ohdRptRoute").val()+data;

    }
  }
</script>
