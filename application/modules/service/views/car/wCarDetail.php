<?php include('application\modules\common\views\wHeader.php'); ?>
<?php
if ($aResult['rtCode'] == 800) {
    $ptPathPrj          = base_url();
    $tImageNotFound     = $ptPathPrj . "application/modules/common/assets/images/DataNotFound.png";
    $tImage = "<div style='background: linear-gradient(179deg, rgba(255,255,255,1) 0%, rgb(253 253 253) 52%, rgb(231 242 251) 100%); height: 100%; width: 100%; display: block; position: fixed;'>";
    $tImage .= "<div>";
    $tImage .= "<img src=" . $tImageNotFound . " style='width: 16%; margin: 12% auto; display: block;'>";
    $tImage .= " </div>";
    $tImage .= "<div style='margin: 0px auto; display: block;'><p style='display: block; position: absolute; top: 48%; text-align: center; width: 100%; font-size: 47px !important;'>ไม่พบข้อมูลรถ</p></div>";
    $tImage .= "</div>";
    echo $tImage;
} else { ?>

    <?php
    $tCarCode                   = $aResult['raItems']['rtCarCode'];
    $tCarNoreq                  = $aResult['raItems']['rtCarRegNo'];
    $tCarEnginereq              = $aResult['raItems']['rtCarEngineNo'];
    $tCarPowerreq               = $aResult['raItems']['rtCarVIDRef'];
    $tCarStart                  = $aResult['raItems']['rtCarDOB'];
    $tCarStop                   = $aResult['raItems']['rtCarOwnChg'];
    $tImgObj                    = $aResult['raItems']['rtImgObj'];
    $tCarRedLabelStaActive      = $aResult['raItems']['rtCarStaRedLabel'];
    $tCstName                   = $aResult['raItems']['rtCstName'];
    $tCarTypeName               = $aResult['raItems']['rtCarTypeName'];
    $tCarBrandName              = $aResult['raItems']['rtCarBrandName'];
    $tCarModelName              = $aResult['raItems']['rtCarModelName'];
    $tCarColorName              = $aResult['raItems']['rtCarColorName'];
    $tCarGearName               = $aResult['raItems']['rtCarGearName'];
    $tCarPowerTypeName          = $aResult['raItems']['rtCarPowerTypeName'];
    $tCarEngineSizeName         = $aResult['raItems']['rtCarEngineSizeName'];
    $tCarCategoryName           = $aResult['raItems']['rtCarCategoryName'];
    $tCarRegProvince            = $aResult['raItems']['rtCarRegProvince'];
    $tCarPvnName                = $aResult['raItems']['rtPvnName'];
    ?>
    <div class="container" style="margin-top:10px; background: white; padding: 20px;" id="odvInforGeneralTap" role="tabpanel" aria-expanded="true">
        <div class="row">
            <div class="col-xs-6 col-sm-6">
                <?php   
                    if($tImgObj != ""){
                        echo FCNtHGetImagePageList(@$tImgObj, '100%');
                    }else{
                ?>
                    <img id="oimImgMasterCar" class="img-responsive xCNImgCenter" style="width: 100%;" src="../application/modules/common/assets/images/Noimage.png">
                <?php  } ?>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="col-xs-12 col-sm-12">

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?= language('service/car/car', 'tCARCodeDetail') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarCode; ?>" readonly>
                    </div>


                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARRegNumber') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarNoreq ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARProvince') ?></label>
                        <input type="text" class="form-control" value="<?php echo @$tCarPvnName; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCAREngineno') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarEnginereq ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARPowerno') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarPowerreq ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCAROwner') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCstName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCAREndDate') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarStop ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCAROption8') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarTypeName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARType') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarCategoryName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARBrand') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarBrandName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARModel') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarModelName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARColor') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarColorName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARGear') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarGearName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCAREngine') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarPowerTypeName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARSize') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarEngineSizeName ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARStartDate') ?></label>
                        <input type="text" class="form-control" value="<?php echo $tCarStart ?>" readonly>
                    </div>


                    <?php
                    if (isset($tCarRedLabelStaActive) && $tCarRedLabelStaActive == 1) {
                        $tRedLabelDisableStaActive   = ' checked';
                    } else {
                        $tRedLabelDisableStaActive   = '';
                    }
                    ?>
                    <div id="odvCarRedLabel" class="form-group">
                        <div class="validate-input">
                            <label class="fancy-checkbox">
                                <input type="checkbox" id="ocbCarRedLabel" name="ocbCarRedLabel" disabled <?php echo @$tRedLabelDisableStaActive; ?>>
                                <span> <?php echo language('service/car/car', 'tCARRedLabel'); ?></span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php } ?>