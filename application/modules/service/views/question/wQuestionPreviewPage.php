<?php
if ($aResult['rtCode'] == 1) {
    $tQahCode       = $aResult['raItems']['rtQahDocNo'];
    $tQahName       = $aResult['raItems']['rtQahName'];
    $tMenuTabDisable = "";
    $tMenuTabToggle = "tab";
    $tRoute         = "questionEventEdit";

    $tQahStart         = $aResult['raItems']['rtQahDateStart'];
    $tQahStop          = $aResult['raItems']['rtQahDateStop'];
    $tQgpCode          = $aResult['raItems']['rtQgpCode'];
    $tQgpName          = $aResult['raItems']['rtQgpName'];
    $tQsgCode          = $aResult['raItems']['rtQsgCode'];
    $tQsgName          = $aResult['raItems']['rtQsgName'];
    $tQahStaActive     = $aResult['raItems']['rtQahStaActive'];
} else {
    $tQahCode           = "";
    $tQahName           = "";
    $tRoute             = "questionEventAdd";
    $tMenuTabToggle     = "false";
    $tMenuTabDisable     = " disabled xCNCloseTabNav";

    $tQahStart          = $dGetDataNow;
    $tQahStop           = $dGetDataFuture;
    $tQAHAgnCode       = $tSesAgnCode;
    $tQgpCode          = "";
    $tQgpName          = "";
    $tQsgCode          = "";
    $tQsgName          = "";

    $tQahStaActive     = '1';
}

?>
<style>
    .xCNQuestionLabel {
        padding: 5px 10px;
        color: #232C3D !important;
        font-weight: 900;
    }

    .xCNQuestionLabelWidth {
        width: 260px;
    }

    .xCNQuestionHeadLabel {
        background-color: #f5f5f5;
        padding: 5px 10px;
        color: #232C3D !important;
        font-weight: 900;
    }

    .xWEJBoxFilter {
        border: 1px solid #ccc !important;
        position: relative !important;
        padding: 15px !important;
        margin-top: 10px !important;
        padding-bottom: 0px !important;
        margin-bottom: 10px !important;
    }

    .xWEJBoxFilter .xWEJLabelFilter {
        position: absolute !important;
        top: -15px;
        left: 15px !important;
        background: #fff !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .nav {
        cursor: pointer;
    }

    .xWtextbold {
        font-weight: bold;
    }

    .fancy-checkbox input[type="checkbox"]+span:before {
    margin-right: 4px !important;
    cursor: not-allowed;
    }
</style>
<div id="odvQuestionDetailControlPage">
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <label class="xCNLabelFrm xWEJLabelFilter" wfd-id="2186"><span class="xWtextbold"><?php echo language('service/question/question', 'tQAHSelectType4') ?></span></label>
                <div class="form-group">
                    <div class='row'>
                        <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                            <label class="xWtextbold"><?php echo language('service/question/question', 'tQAHCode') ?></label>
                        </div>
                        <div class="col-xs-12 col-md-10 text-left">
                            <label><?php echo $tQahCode ?></label>
                        </div>
                        <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                            <label class="xWtextbold"><?php echo language('service/question/question', 'tQAHName') ?></label>
                        </div>
                        <div class="col-xs-12 col-md-10 text-left">
                            <label><?php echo $tQahName ?></label>
                        </div>
                        <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                            <label class="xWtextbold"><?php echo language('service/question/question', 'tQAHQasGroup') ?></label>
                        </div>
                        <div class="col-xs-12 col-md-10 text-left">
                            <label><?php echo $tQgpName ?></label>
                        </div>
                        <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                            <label class="xWtextbold"><?php echo language('service/question/question', 'tQAHQasSubGroup') ?></label>
                        </div>
                        <div class="col-xs-12 col-md-10 text-left">
                            <label><?php echo $tQsgName ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

    </div>
</div>
<div id="odvQuestionDetailContentPagePreview">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" id="nCurrentPageTB">
            <div class="table-responsive">
                <table id="otbQahDetailDataList" class="table table-striped">
                    <!-- เปลี่ยน -->
                    <thead>
                        <tr>
                            <th nowarp class="text-center xCNTextBold" style="width:50%;" style=""><?= language('service/question/question', 'tQAHQuestion') ?></th>
                            <th nowarp class="text-center xCNTextBold" style="width:50%;" style=""><?= language('service/question/question', 'tQAHOptionAnwser') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($aQagDetailDataList['rtCode'] == 1) : ?>
                            <?php foreach ($aQagDetailDataList['raItems'] as $nKey => $aValue) : ?>
                                <tr class="text-center xCNTextDetail2 otrQuestionDetail" id="otrQuestionDetail<?= $nKey ?>" data-code="<?= $aValue['rtQadSeqNo'] ?>" data-name="<?= $aValue['rtQadName'] ?>">
                                    <td nowrap class="text-left"><?= $aValue['rtQadName'] ?></td>
                                    <?php $tAnwsweOption = "";
                                    ?>

                                    <?php if ($aValue['rtQadType'] == '1') { ?>
                                        <td nowrap class="text-left">
                                            <?php foreach ($aQagDetailDataList['raItems2'] as $nKey2 => $aValue2) { ?>
                                                <?php if ($aValue2['rtQadSeqNo'] == $aValue['rtQadSeqNo']) { ?>
                                                   
                                                    <label style="display: inline-block;">
                                                        <input type="radio" maxlength="1" name="orbOption<?php echo $nKey ?>" disabled>
                                                        <span class="xWFontSpan"><?= $aValue2['rtQasResuitName'] ?></span>
                                                    </label>
                                                    <span>&nbsp;</span>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } elseif ($aValue['rtQadType'] == '2') { ?>
                                        <td nowrap class="text-left">
                                            <?php foreach ($aQagDetailDataList['raItems2'] as $nKey2 => $aValue2) { ?>
                                                <?php if ($aValue2['rtQadSeqNo'] == $aValue['rtQadSeqNo']) { ?>
                                                        <label class="fancy-checkbox" style="display: inline-block;">
                                                            <input type="checkbox" maxlength="1" name="orbOption<?php echo $nKey ?>" disabled>
                                                            <span style="cursor: not-allowed;"><?= $aValue2['rtQasResuitName'] ?></span>
                                                        </label>
                                                        <span>&nbsp;</span>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } else { ?>
                                        <td nowrap class="text-left">
                                            <?php foreach ($aQagDetailDataList['raItems2'] as $nKey2 => $aValue2) { ?>
                                                <?php if ($aValue2['rtQadSeqNo'] == $aValue['rtQadSeqNo']) { ?>
                                                    <input type="text" class="form-control" value="<?php echo $aValue2['rtQasResuitName']; ?>" disabled>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>

                                    <?php foreach ($aQagDetailDataList['raItems2'] as $nKey2 => $aValue2) { ?>
                                        <?php if ($aValue2['rtQadSeqNo'] == $aValue['rtQadSeqNo']) { ?>
                                            <?php
                                            $tAnwsweOption .= $aValue2['rtQasResuitName'] . ','
                                            ?>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='8'><?= language('service/question/question', 'tQAHNoData') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>