<div class="row">
    <!-- ตารางรายการคำถาม -->
    <input type="hidden" name="ohdErrMsg" id="ohdErrMsg" value="<?php echo language('document/prerepairresult/prerepairresult', 'tPreTableNodata') ?>">
    <div id="odvCPHDataPanelDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="panel panel-default" style="margin-bottom: 25px;">
            <div class="panel-collapse collapse in" role="tabpanel">
                <div class="panel-body">
                    <div class="row p-t-10">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <div style="padding-top: 25px;">
                                    <div class="table-responsive">
                                        <table class="table" id="otbDataTable">
                                            <thead>
                                                <tr class="xCNCenter">
                                                    <th class="xCNTextBold" style="width:10%;"><?php echo language('document/prerepairresult/prerepairresult', 'tPreTableNumber') ?></th>
                                                    <th class="xCNTextBold" style="width:40%;"><?php echo language('document/prerepairresult/prerepairresult', 'tPreTableList') ?></th>
                                                    <th class="xCNTextBold" style="width:10%;"><?php echo language('document/prerepairresult/prerepairresult', 'tPreTableNormal') ?></th>
                                                    <th class="xCNTextBold" style="width:40%;"><?php echo language('document/prerepairresult/prerepairresult', 'tPreTableAbnormal') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($aDataAnwser['rtCode'] == 1) {
                                                    foreach ($aDataAnwser['raAnwserDetail'] as $key => $tVal) {
                                                ?>
                                                        <?php
                                                        $aSeqAnw = (explode(";", $tVal['FTSetChkSeq']));
                                                        $aNameAnw = (explode(";", $tVal['FTSetChkName']));
                                                        $aSeqChk = (explode(";", $tVal['FNPdtSrvSeq']));
                                                        $tChkVal = $tVal['FTXsdAnsValue'];
                                                        if ($tVal['FTPsvStaSuggest'] == '1'){
                                                            $tSugges = "background: #f7f7f7;";
                                                        }else{
                                                            $tSugges = "";
                                                        }

                                                        if ($aSeqChk[0] == '0' && ($tChkVal == ' ' || $tChkVal == NULL )) {
                                                            $tCheckNormal = 'checked';
                                                        } else {
                                                            $tCheckNormal = '';
                                                        }

                                                        ?>
                                                        <tr style="<?= $tSugges ?>">
                                                            <td class="xWPretd text-center"><?= $key + 1; ?></td>
                                                            <td class="xWPretd"><?= $tVal['FTXsdPdtName']; ?></td>
                                                            <td class="xWPreNormal text-center">
                                                                <label class="fancy-checkbox" style="margin-left: 10px; display: inline-block;">
                                                                    <input type="checkbox" class="xWCheckNormal" data-type="<?= $tVal['FTPdtChkType'] ?>" data-pdtcode='<?= $tVal['FTPdtCode'] ?>' data-pdtcodesub='<?= $tVal['FTPdtCodeSub'] ?>' <?= $tCheckNormal ?>>
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            <td class="xWAnwserDT">
                                                                <?php foreach ($aSeqAnw as $nAnwkey => $tAnwVal) { ?>
                                                                    <?php if ($tVal['FTPdtChkType'] == 2) { ?>
                                                                        <label class="fancy-checkbox" style="margin-left: 10px; display: inline-block;">
                                                                            <?php
                                                                            if (in_array($tAnwVal, $aSeqChk)) {
                                                                                $tCheck = 'checked';
                                                                            } else {
                                                                                $tCheck = '';
                                                                            }
                                                                            ?>
                                                                            <input type="checkbox" class="ocbAnsCB ocbAnsCB<?= $key ?>" id="ocbAnsCB<?= $key ?>" data-type="<?= $tVal['FTPdtChkType'] ?>" data-pdtcode='<?= $tVal['FTPdtCode'] ?>' data-pdtcodesub='<?= $tVal['FTPdtCodeSub'] ?>' value="<?= $tAnwVal ?>" <?php echo $tCheck ?>>
                                                                            <span>&nbsp;</span>
                                                                            <span><?= $aNameAnw[$nAnwkey]; ?></span>
                                                                        </label>
                                                                    <?php } elseif ($tVal['FTPdtChkType'] == 3) { ?>
                                                                        <label style="margin-left: 10px; display: inline-block;">
                                                                            <?php
                                                                            if (in_array($tAnwVal, $aSeqChk)) {
                                                                                $tCheck = 'checked';
                                                                            } else {
                                                                                $tCheck = '';
                                                                            }
                                                                            ?>
                                                                            <input type="radio" class="ocbAnsCB ocbAnsCB<?= $key ?>" id="ocbAnsCB<?= $key ?>" data-pdtcode='<?= $tVal['FTPdtCode'] ?>' data-pdtcodesub='<?= $tVal['FTPdtCodeSub'] ?>' name="ocbAns<?= $key ?>" value="<?= $tAnwVal ?>" <?php echo $tCheck ?>>
                                                                            <span>&nbsp;</span>
                                                                            <span class="xWFontSpan"><?= $aNameAnw[$nAnwkey] ?></span>
                                                                        </label>
                                                                    <?php } elseif ($tVal['FTPdtChkType'] == 1) { ?>
                                                                        <div style="padding: 0px 10px;" >
                                                                            <label><?= $tVal['FTSetChkName'] ?></label><br>
                                                                            <input type="text" class="ocbAnsCB ocbAnsCB<?= $key ?>" id="ocbAnsCB<?= $key ?>" data-pdtcode='<?= $tVal['FTPdtCode'] ?>' data-type="<?= $tVal['FTPdtChkType'] ?>" data-pdtcodesub='<?= $tVal['FTPdtCodeSub'] ?>' name="ocbAns<?= $key ?>" value="<?= $tVal['FTXsdAnsValue'] ?>">
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } else { ?>
                                                <?php
                                                ?>
                                                    <tr>
                                                        <td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">
                                                            <?php if($nStadoc == '3'){
                                                            echo language('document/prerepairresult/prerepairresult', 'tPreTableNodata2');
                                                            }else{
                                                            echo language('document/prerepairresult/prerepairresult', 'tPreTableNodata2');
                                                            }?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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

<script>

    var msg = $('#ohdErrMsg').val();
    if($("#ohdPreDocRefCode").val() == ''){
        $(".xCNTextNotfoundDataPdtTable").html(msg);
    }


    var nStaApv = $("#ohdPreStaApv").val();
    if(nStaApv == '1' || nStaApv == '3'){
        $(".xWAnwserDT").each(function () { 
            $(".xWAnwserDT :input").attr("disabled", true);
            $(".xWPreNormal :input").attr("disabled", true);
        });
    }

    $('.xWCheckNormal').click(function() {
        $(this).parent().parent().parent().find(".xWAnwserDT").find("input").prop("checked", false);
        if ($(this).attr('data-type') == '1') {
            $(this).parent().parent().parent().find(".xWAnwserDT").find("input").val('');
        }
        $.ajax({
            type: "POST",
            url: 'docPreRepairResultEditAnwser',
            data: {
                'tPdtCode': $(this).attr('data-pdtcode'),
                'tPdtCodeSub': $(this).attr('data-pdtcodesub')
            },
            success: function() {

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });

    $('.ocbAnsCB').change(function() {
        $(this).parent().parent().parent().find(".xWPreNormal").find("input").prop("checked", false);
        var tGetId = $(this).attr('id');
        var tNewAnw = '';
        var tNewAnwVal = '';
        if ($(this).attr('data-type') == '1') {
            tNewAnwVal = $(this).val();
        } else {
            $("." + tGetId).each(function() {
                if ($(this).is(':checked')) {
                    tNewAnw += $(this).val() + ';';
                }
            });
            tNewAnw = tNewAnw.slice(0, -1);
        }
        $.ajax({
            type: "POST",
            url: 'docPreRepairResultEditAnwserInLine',
            data: {
                'tPdtCode': $(this).attr('data-pdtcode'),
                'tPdtCodeSub': $(this).attr('data-pdtcodesub'),
                'FNPdtSrvSeq': tNewAnw,
                'FTXsdAnsValue': tNewAnwVal
            },
            success: function() {

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });
</script>