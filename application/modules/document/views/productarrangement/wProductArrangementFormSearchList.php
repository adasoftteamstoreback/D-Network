<div class="panel panel-headline">
    <div class="panel-heading">
      <form id="ofmPAMSearchAdv">
      <div class="row">
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
              <div class="form-group">
                  <div class="input-group">
                      <input
                          class="form-control xCNInputWithoutSingleQuote"
                          type="text"
                          id="oetPAMSearchAllDocument"
                          name="oetPAMSearchAllDocument"
                          placeholder="<?=language('document/adjuststock/adjuststock','tASTFillTextSearch')?>"

                          autocomplete="off"
                      >
                      <span class="input-group-btn">
                          <button type="button" class="btn xCNBtnDateTime" onclick="JSvTBICallPageTransferReceiptDataTable()">
                              <img class="xCNIconSearch">
                          </button>
                      </span>
                  </div>
              </div>
          </div>
          <a id="oahASTAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;"><?=language('common/main/main', 'tAdvanceSearch'); ?></a>
          <a id="oahASTSearchReset"   class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" onclick="JSxTBIClearSearchData()"><?=language('common/main/main', 'tClearSearch'); ?></a>
      </div>
      <!--ค้นหาขั้นสูง-->
      <div id="odvASTAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">

          <div class="row">
            <!-- สาขาที่สร้าง -->
            <?php
                $tSesUsrLevel = $this->session->userdata("tSesUsrLevel");
                if( $tSesUsrLevel != "HQ" ){
                    $tBchCodeDefault    = $this->session->userdata("tSesUsrBchCodeDefault");
                    $tBchNameDefault    = $this->session->userdata("tSesUsrBchNameDefault");

                    $nSesUsrBchCount    = $this->session->userdata("nSesUsrBchCount");
                    if( $nSesUsrBchCount == 1 ){
                        $tDisabledBch = "disabled";
                    }else{
                        $tDisabledBch = "";
                    }

                }else{
                    $tBchCodeDefault    = "";
                    $tBchNameDefault    = "";
                    $tDisabledBch       = "";
                }
            ?>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
              <!-- สาขาที่สร้าง -->
              <div class="form-group">
                  <label class="xCNLabelFrm"><?php echo language('document/productarrangement/productarrangement', 'tDocBchCreate') ?></label>
                  <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPAMBchCode" name="oetPAMBchCode" maxlength="5" value="<?=$tBchCodeDefault?>">
                      <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPAMBchName" name="oetPAMBchName" maxlength="100" placeholder="<?php echo language('document/productarrangement/productarrangement', 'tDocBchCreate') ?>" value="<?=$tBchNameDefault?>" readonly>
                      <span class="input-group-btn">
                          <button id="obtPAMBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?=$tDisabledBch?> >
                              <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                          </button>
                      </span>
                  </div>
              </div>
              <!-- สาขาที่สร้าง -->
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
              <!-- ประเภทใบจัด -->
              <div class="form-group">
                  <label class="xCNLabelFrm"><?php echo language('document/productarrangement/productarrangement', 'tPAMPackingType'); ?></label>
                  <select class="selectpicker xWPAMDisabledOnApv form-control xControlForm" id="ocmPAMPackType" name="ocmPAMPackType" maxlength="1">
                      <option value="" selected><?php echo language('common/main/main', 'tAll'); ?></option>
                      <option value="11"><?php echo language('document/productarrangement/productarrangement', 'tPAMDocType1'); ?></option>
                      <option value="13"><?php echo language('document/productarrangement/productarrangement', 'tPAMDocType2'); ?></option>
                  </select>
              </div>
              <!-- ประเภทใบจัด -->
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
              <!-- จากวันที่เอกสาร -->
              <div class="form-group">
                  <label class="xCNLabelFrm"><?php echo language('document/document/document', 'tDocDateFrom'); ?></label>
                  <div class="input-group">
                      <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetPAMDocDateFrm" name="oetPAMDocDateFrm" value="" placeholder="<?php echo language('document/document/document', 'tDocDateFrom') ?>">
                      <span class="input-group-btn">
                          <button id="obtPAMDocDateFrm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                      </span>
                  </div>
              </div>
              <!-- จากวันที่เอกสาร -->
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
              <!-- ถึงวันที่เอกสาร -->
              <div class="form-group">
                  <label class="xCNLabelFrm"><?php echo language('document/document/document', 'tDocDateTo'); ?></label>
                  <div class="input-group">
                      <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetPAMDocDateTo" name="oetPAMDocDateTo" value="" placeholder="<?php echo language('document/document/document', 'tDocDateTo') ?>">
                      <span class="input-group-btn">
                          <button id="obtPAMDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                      </span>
                  </div>
              </div>
              <!-- ถึงวันที่เอกสาร -->
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
              <!-- ที่เก็บ -->
              <div class="form-group">
                  <label class="xCNLabelFrm"><?php echo language('document/productarrangement/productarrangement', 'tPAMLocation') ?></label>
                  <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPAMPlcCode" name="oetPAMPlcCode" maxlength="5" value="">
                      <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPAMPlcName" name="oetPAMPlcName" maxlength="100" placeholder="<?php echo language('document/productarrangement/productarrangement', 'tPAMLocation') ?>" value="" readonly>
                      <span class="input-group-btn">
                          <button id="obtPAMBrowsePlc" type="button" class="btn xCNBtnBrowseAddOn">
                              <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                          </button>
                      </span>
                  </div>
              </div>
              <!-- ที่เก็บ -->
            </div>
            <!-- หมวดสินค้า 1-5 -->
            <?php for($i=1;$i<=2;$i++){ ?>
              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/productarrangement/productarrangement', 'tPAMCat'.$i) ?></label>
                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPAMCat<?=$i?>Code" name="oetPAMCat<?=$i?>Code" maxlength="10" value="">
                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPAMCat<?=$i?>Name" name="oetPAMCat<?=$i?>Name" maxlength="100" placeholder="<?php echo language('document/productarrangement/productarrangement', 'tPAMCat'.$i) ?>" value="" readonly>
                        <span class="input-group-btn">
                            <button id="obtPAMBrowseCat<?=$i?>" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
              </div>
            <?php } ?>
            <!-- หมวดสินค้า 1-2 -->
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
              <!-- สถานะเอกสาร -->
              <div class="form-group">
                  <label class="xCNLabelFrm"><?php echo language('document/document/document', 'tDocStaDoc'); ?></label>
                  <select class="selectpicker xWPAMDisabledOnApv form-control xControlForm" id="ocmPAMStaDoc" name="ocmPAMStaDoc" maxlength="1">
                      <option value="" selected><?php echo language('common/main/main', 'tAll'); ?></option>
                      <option value="2"><?php echo language('document/document/document', 'tDocStaProApv'); ?></option>
                      <option value="1"><?php echo language('document/document/document', 'tDocStaProApv1'); ?></option>
                      <option value="3"><?php echo language('document/document/document', 'tDocStaProDoc3'); ?></option>
                  </select>
              </div>
              <!-- สถานะเอกสาร -->
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group" style="width: 60%;">
                    <label class="xCNLabelFrm">&nbsp;</label>
                    <button  type="button" id="obtPAMConfirmSearch" class="btn xCNBTNPrimery" style="width:100%" ><?php echo language('common/main/main', 'tSearch'); ?></button>
                </div>
            </div>
          </div>

      </div>
    </form>
    <div id="ostPAMDataTableDocument">

    </div>
  </div>

</div>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jProductArrangementFormSearchList.php')?>
