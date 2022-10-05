<?php
$tBchCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-12";
$tShpCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-12";
$tWahCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-12";
$tPdtCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-12";

if (!FCNbGetIsShpEnabled()) {
    $tBchCodeSelectClass = "col-lg-3 col-sm-4 col-md-4 col-xs-12";
    $tShpCodeSelectClass = "";
    $tWahCodeSelectClass = "col-lg-3 col-sm-4 col-md-4 col-xs-12";
    $tPdtCodeSelectClass = "col-lg-3 col-sm-4 col-md-4 col-xs-12";
}
?>

<div class="">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div id="odvSetionMovement">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
                                <div class="row">
                                    <!-- Browse สาขา -->
                                    <div class="<?= $tBchCodeSelectClass ?>">
                                        <?php
                                        $tBCHCode = $this->session->userdata("tSesUsrBchCodeDefault");
                                        $tBCHName = $this->session->userdata("tSesUsrBchNameDefault");
                                        ?>
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('movement/movement/movement', 'tMMTListBanch') ?></label>
                                            <div class="input-group">
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtBchStaSelectAll' name='oetMmtBchStaSelectAll' value="<?= $tBCHCode; ?>">
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtBchCodeSelect' name='oetMmtBchCodeSelect' value="<?= $tBCHCode; ?>">
                                                <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtBchNameSelect' name='oetMmtBchNameSelect' value="<?= $tBCHName; ?>" placeholder="<?= language('movement/movement/movement', 'tMMTListBanch') ?>" autocomplete="off" readonly>
                                                <span class="input-group-btn">
                                                    <?php
                                                    if ($this->session->userdata("tSesUsrLevel") == "HQ") {
                                                        $tDisabled = "";
                                                    } else {
                                                        $nCountBch = $this->session->userdata("nSesUsrBchCount");
                                                        if ($nCountBch == 1) {
                                                            $tDisabled = "disabled";
                                                        } else {
                                                            $tDisabled = "";
                                                        }
                                                    }
                                                    ?>
                                                    <button id="obtMmtMultiBrowseBranch" <?= $tDisabled; ?> type="button" class="btn xCNBtnDateTime">
                                                        <img src="<?= base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Browse ร้านค้า -->
                                    <div class="<?= $tShpCodeSelectClass ?> <?= !FCNbGetIsShpEnabled() ? 'xCNHide' : 'xCNHide'; ?>">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('movement/movement/movement', 'tMMTListShop') ?></label>
                                            <div class="input-group">
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtShpStaSelectAll' name='oetMmtShpStaSelectAll'>
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtShpCodeSelect' name='oetMmtShpCodeSelect'>
                                                <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtShpNameSelect' name='oetMmtShpNameSelect' placeholder="<?= language('movement/movement/movement', 'tMMTListShop') ?>" autocomplete="off" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtMmtMultiBrowseShop" type="button" class="btn xCNBtnDateTime">
                                                        <img src="<?= base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Browse คลังสินค้า -->
                                    <div class="<?= $tWahCodeSelectClass ?>">
                                        <?php
                                        $tWahCode = $this->session->userdata("tSesUsrWahCode");
                                        $tWahName = $this->session->userdata("tSesUsrWahName");
                                        ?>
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('movement/movement/movement', 'tMMTListWaHouse') ?></label>
                                            <div class="input-group">
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtWahStaSelectAll' name='oetMmtWahStaSelectAll' value="<?= $tWahCode ?>">
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtWahCodeSelect' name='oetMmtWahCodeSelect' value="<?= $tWahCode ?>">
                                                <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtWahNameSelect' name='oetMmtWahNameSelect' value="<?= $tWahName ?>" placeholder="<?= language('movement/movement/movement', 'tMMTListWaHouse') ?>" autocomplete="off" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtMmtMultiBrowseWaHouse" type="button" class="btn xCNBtnDateTime">
                                                        <img src="<?= base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Browse สินค้า -->
                                    <div class="<?= $tPdtCodeSelectClass ?>">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('movement/movement/movement', 'tMMTListProduct') ?></label>
                                            <div class="input-group">
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtPdtStaSelectAll' name='oetMmtPdtStaSelectAll'>
                                                <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtPdtCodeSelect' name='oetMmtPdtCodeSelect'>
                                                <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtPdtNameSelect' name='oetMmtPdtNameSelect' placeholder="<?= language('movement/movement/movement', 'tMMTListProduct') ?>" autocomplete="off" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtMmtMultiBrowseProduct" type="button" class="btn xCNBtnDateTime">
                                                        <img src="<?= base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Browse วันที่ -->
                                    <div class="<?= $tPdtCodeSelectClass ?>">
                                        <label class="xCNLabelFrm"><?= language('movement/movement/movement', 'tMEMCardDocMonth') ?></label>
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="ocmMmtMonth" id="ocmMmtMonth" style="width:100%">
                                                <?php if (!empty($aRrayMonth)) { ?>
                                                    <?php foreach ($aRrayMonth as $tKeyM => $aMonth) { ?>
                                                        <option value="<?= $tKeyM ?>" <?php if ($tMemCrdStartMonth == $tKeyM) {
                                                                                            echo 'selected';
                                                                                        }  ?>><?= $aMonth ?></option>
                                                    <?php } ?>
                                                <?php }  ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('movement/movement/movement', 'tMEMCardDocYear') ?></label>
                                            <select class="selectpicker form-control" name="ocmMmtYear" id="ocmMmtYear" style="width:100%">
                                                <?php
                                                for ($i = 0; $i <= 4; $i++) {
                                                ?>
                                                    <option value="<?= ($tMemCrdYear - $i) ?>"><?= ($tMemCrdYear - $i) ?></option>
                                                <?php  }  ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- เฉพาะสินค้าเคลี่อนไหว -->
                                    <div class="col-lg-3 col-sm-4 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"></label>
                                            <label class="fancy-checkbox">
                                                <input id="ocbMmtPdtActive" type="checkbox" name="ocbMmtPdtActive" value="1">
                                                <span><?= language('movement/movement/movement', 'tINVPdtSpcActive') ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- ปุ่มกรองข้อมูล -->
                                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"></label>
                                            <div id="odvBtnMovement" class="text-right">
                                                <button type="button" id="obtSubmitMmt" class="btn xCNBTNPrimery" onclick="JSvMevementSearchData()"><?= language('movement/movement/movement', 'tMMTListSearch') ?></button>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- ล้างข้อมูลข้อมูล -->
                                    <div class="col-lg-4 col-sm-2 col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"></label>
                                            <div id="odvBtnMovementClear" class="text-right">
                                                <button type="button" id="obtClearMmt" class="btn xCNBTNDefult" onclick="JSvMevementClearData()"><?= language('movement/movement/movement', 'tMMTListClear') ?></button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- แสดงข้อมูล ความเคลื่อนไหวสินค้า -->
        <div class="col-xs-12 col-md-12 col-lg-12">
            <section id="odvContentMovement"></section>
        </div>
    </div>
</div>

<?php include "script/jMovementAdd.php"; ?>