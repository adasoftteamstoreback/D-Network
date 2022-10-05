<?php $tImgPathCar = base_url() . '/application/modules/common/assets/images/logo/fitauto.jpg'; ?>
<style>
    .testimonial-group>.row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
    }

    #odvSatDataStatusInfo .datepicker{
        width: 100%;
    }

    #odvSatDataStatusInfo .table-condensed{
        width: 100%;
    }

    .testimonial-group>.row>.col-xs-4 {
        flex: 0 0 auto;
    }

    .xWNodataImage {
        background-image: url(<?php echo $tImgPathCar ?>);
        background-repeat: no-repeat;
        background-position: center;
        background-size: 70% auto;
        opacity: 0.1;
    }
</style>

<?php
    $tRoute         = $tRoute;
    $aAllBay        = $aDataBAY['raItemsBay'];
    $aAllBayDetail  = $aDataBAY['raItemsBayDetail'];
    $aTmpBay        = array();
    foreach ($aAllBayDetail as $nKey => $aValue) {
        array_push($aTmpBay, $aValue['FTXshToPos']);
    }
    $aTmpBay = array_unique($aTmpBay);
?>

<div class="row">
    <!-- ตารางBay -->
    <div id="odvCPHDataPanelDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="panel panel-default" style="margin-bottom: 25px;">
            <div class="panel-collapse collapse in" role="tabpanel">
                <div class="panel-body" style="padding-top: 0px !important;">
                    <div class="testimonial-group">


                        <div class="row text-center">
                            <?php if(!empty($aAllBay)){ //พบข้อมูล ?>
                                <?php foreach ($aAllBay as $aKey => $nValue) { ?>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style='min-height: 800px;padding: 0px;border-right: 1px solid #dee2e6;'>
                                        <div id="odvBayHead" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;opacity:1">
                                            <label class="xCNTextDetail1"><?= $nValue['FtSpsName'] ?></label>
                                        </div>
                                        <?php if (count($aAllBayDetail) > 0 && in_array($nValue['FTSpsCode'], $aTmpBay)) { ?>
                                            <?php foreach ($aAllBayDetail as $aKey2 => $nValue2) { ?>
                                                <?php if ($nValue2['FTXshToPos'] == $nValue['FTSpsCode']) { ?>
                                                    <div style="padding: 10px;">
                                                        <div class="panel-default" style="border: 1px solid #d7d7d7; border-radius: 5px;">
                                                            <div class="panel-heading" style="padding: 10px !important;">
                                                                <div class="pull-left mark-font" style="color: #0081c2 !important;cursor: pointer;text-decoration: underline;" onClick="JSxGotoPageJob('<?= $nValue2['FTXshDocNo'] ?>','<?= $nValue2['FTBchCode'] ?>','<?= $nValue2['FTAgnCode'] ?>','<?= $nValue2['FTCstCode'] ?>')"><?= $nValue2['FTXshDocNo'] ?></div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            <?php
                                                            if ($nValue2['FTXshStaDoc'] == 3) {
                                                                $tClassStaDoc = 'text-success';
                                                                $tStaDoc = language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus' . $nValue2['FTXshStaDoc']);
                                                            } elseif ($nValue2['FTXshStaDoc'] == 2) {
                                                                $tClassStaDoc = 'text-warning';
                                                                $tStaDoc = language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus' . $nValue2['FTXshStaDoc']);
                                                            } elseif ($nValue2['FTXshStaDoc'] == 1) {
                                                                $tClassStaDoc = 'text-warning';
                                                                $tStaDoc = language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus' . $nValue2['FTXshStaDoc']);
                                                            } else {
                                                                $tClassStaDoc = 'text-warning';
                                                                $tStaDoc = language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus' . $nValue2['FTXshStaDoc']);
                                                            }
                                                            ?>
                                                            <div class="panel-body">
                                                                <div class="" style="text-align: left;">ทะเบียนรถยนต์ : <?= $nValue2['FTCarRegNo'] ?></div>
                                                                <div class="" style="text-align: left;">ชื่อลูกค้า : <?= $nValue2['FTCstName'] ?></div>
                                                                <div class=" <?= $tClassStaDoc ?>" style="text-align: right; font-weight: bold;"><?= $tStaDoc ?></div>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="xWNodataImage xWDetailList<?= $nValue['FTSpsCode'] ?>" style='height: 50%;margin: 30% auto; display: block;'></div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php }else{ //ไม่พบข้อมูล ?>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style='min-height: 800px;padding: 0px;border-right: 1px solid #dee2e6;'>
                                    <div id="odvBayHead" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;opacity:1">
                                        <label class="xCNTextDetail1">ไม่พบข้อมูล</label>
                                    </div>
                                    <div class="xWNodataImage" style='height: 50%;margin: 30% auto; display: block;'></div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>