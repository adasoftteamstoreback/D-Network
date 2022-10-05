<?php 
    if($aDataListHD['rtCode'] == '1'){
        $tBchName   = $aDataListHD['raItems']['FTBchName'];
        $tWahName   = $aDataListHD['raItems']['FTWahName'];
        $tPdtName   = $aDataListHD['raItems']['FTPdtName'];

        $cStkQty    = number_format($aDataListHD['raItems']['FCStkQty'],$nOptDecimalShow);
        $cXtdQtyInt = number_format($aDataListHD['raItems']['FCXtdQtyInt'],$nOptDecimalShow);
        $cXtdQtySbk = number_format($aDataListHD['raItems']['FCXtdQtySbk'],$nOptDecimalShow);
        $cXtdQtyBal = number_format($aDataListHD['raItems']['FCXtdQtyBal'],$nOptDecimalShow);
    }

?>

<style type="text/css">
    .xWPanelStkBook{
        padding-top: 10px !important;
        padding-bottom: 0px !important;
        border-bottom: 1px solid #ccc!important;
    }
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm" for="inputsm">สาขา</label>
                            <input type="text" class="form-control input-sm" id="oetInvStkBklBranch" name="oetInvStkBklBranch" value="<?=@$tBchName;?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm" for="inputsm">คลังสินค้า</label>
                            <input type="text" class="form-control input-sm" id="oetInvStkBklWah" name="oetInvStkBklWah" value="<?=@$tWahName;?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="xCNLabelFrm" for="inputsm">ชื่อสินค้า</label>
                            <input type="text" class="form-control input-sm" id="oetInvStkBklPdtName" name="oetInvStkBklPdtName" value="<?=@$tPdtName;?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm" for="inputsm">จำนวนคงคลัง</label>
                            <input type="text" class="form-control input-sm text-right" id="oetInvStkBklQty" name="oetInvStkBklQty" value="<?=@$cStkQty;?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm" for="inputsm">จำนวนค้างรับ</label>
                            <input type="text" class="form-control input-sm text-right" id="oetInvStkBklInt" name="oetInvStkBklInt" value="<?=@$cXtdQtyInt;?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm" for="inputsm">จำนวนจอง</label>
                            <input type="text" class="form-control input-sm text-right" id="oetInvStkBklQtyBk" name="oetInvStkBklQtyBk" value="<?=@$cXtdQtySbk;?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm" for="inputsm">จำนวนรวม</label>
                            <input type="text" class="form-control input-sm text-right" id="oetInvStkBklQtyBal" name="oetInvStkBklQtyBal" value="<?=$cXtdQtyBal;?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
                    <th nowrap class="xCNTextBold" style="width:7%;text-align:center;"><?= language('movement/movement/movement', 'ชื่อเอกสาร')?></th>
                    <th nowrap class="xCNTextBold" style="width:7%;text-align:center;"><?= language('movement/movement/movement', 'เลขที่เอกสาร')?></th>
                    <th nowrap class="xCNTextBold" style="width:7%;text-align:center;"><?= language('movement/movement/movement', 'วันที่เอกสาร')?></th>
                    <th nowrap class="xCNTextBold" style="width:7%;text-align:center;"><?= language('movement/movement/movement', 'สถานะเอกสาร')?></th>
                    <th nowrap class="xCNTextBold" style="width:7%;text-align:center;"><?= language('movement/movement/movement', 'เลขที่เอกสารอ้างอิง')?></th>
                    <th nowrap class="xCNTextBold" style="width:7%;text-align:center;"><?= language('movement/movement/movement', 'วันที่เอกสารอ้างอิง')?></th>
                    <th nowrap class="xCNTextBold" style="width:7%;text-align:center;"><?= language('movement/movement/movement', 'จำนวนจอง')?></th>
                </thead>
                <tbody>
                    <?php if($aDataListDT['rtCode'] == 1 ):?>
                        <?php if (FCNnHSizeOf($aDataListDT['raItems']) != 0): ?>
                            <?php foreach ($aDataListDT['raItems'] as $nKey => $aValue): ?>
                                <?php 
                                    if(@$aValue['rtStaDoc'] == 3) {
                                        $tClassStaDoc = 'text-danger';
                                        $tStaDoc = language('common/main/main', 'tStaDoc3');
                                    }else if (@$aValue['rtStaApv'] == 1){
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1');
                                    }else if (@$aValue['rtStaApv'] == 9){
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', '-');
                                    }else {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }

                                    // Check Ref Doc No
                                    if(@$aValue['rtRefDocNo'] != ''){
                                        $tClassRefDocNo = "text-left";
                                        $tRefDocNo      = $aValue['rtRefDocNo'];
                                    }else{
                                        $tClassRefDocNo = "text-center";
                                        $tRefDocNo      = '-';
                                    }

                                    // Check Ref Doc No
                                    if(@$aValue['rtRefDocDate'] != ''){
                                        $tRefDocDate    = $aValue['rtRefDocDate'];
                                    }else{
                                        $tRefDocDate    = '-';
                                    }


                                ?>
                                <tr class="text-center xCNTextDetail2" id="otrInvStkBkl<?=$nKey;?>">
                                    <td nowrap class="text-left"><?=@$aValue['rtDocName'];?></td>
                                    <td nowrap class="text-left"><?=@$aValue['rtDocNo'];?></td>
                                    <td nowrap class="text-center"><?=@$aValue['rtDocDate'];?></td>
                                    <td nowrap class="text-center">
                                        <label class="xCNTDTextStatus <?php echo @$tClassStaDoc; ?>">
                                            <?php echo @$tStaDoc ?>
                                        </label>
                                    </td>
                                    <td nowrap class="<?=@$tClassRefDocNo;?>"><?=@$tRefDocNo;?></td>
                                    <td nowrap class="text-center"><?=@$tRefDocDate;?></td>

                                    <td nowrap class="text-right"><?=number_format(@$aValue['rtQtyStkBkl'],$nOptDecimalShow);?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='12' style="text-align: center;"><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>