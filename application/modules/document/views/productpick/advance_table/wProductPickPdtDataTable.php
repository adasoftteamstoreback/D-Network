<div class="table-responsive">
    <!-- <table class="table table-striped xWPdtTableFont" id="otbPCKPdtTable"> -->
    <table class="table table-striped xWPdtTableFont xWPCKPdtTable" id="otbPCKPdtTable">

        <thead>
            <tr class="xCNCenter">
                <th width='5%' class="text-center xCNHideWhenCancelOrApprove" id="othCheckboxHide">
                    <label class="fancy-checkbox">
                        <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxPCKSelectAll(this)">
                        <span class="">&nbsp;</span>
                    </label>
                </th>
                <!-- <th><?= language('document/purchaseorder/purchaseorder', 'ลำดับ') ?></th> -->
                <th><?= language('document/purchaseorder/purchaseorder', 'รหัสสินค้า') ?></th>
                <th><?= language('document/purchaseorder/purchaseorder', 'ชื่อสินค้า') ?></th>
                <th><?= language('document/purchaseorder/purchaseorder', 'หน่วยสินค้า') ?></th>
                <th><?= language('document/purchaseorder/purchaseorder', 'บาร์โค้ด') ?></th>
                <th><?= language('document/purchaseorder/purchaseorder', 'จำนวน') ?></th>
                <th width='15%'><?= language('document/purchaseorder/purchaseorder', 'จำนวนหยิบ') ?></th>
                <th class="xCNPIBeHideMQSS xCNHideWhenCancelOrApprove"><?php echo language('common/main/main', 'tCMNActionDelete') ?></th>
            </tr>
        </thead>

        <tbody class="xCNPCKTBodyPdtItem">
            <?php $nNumSeq = 1; ?>
            <?php if ($aDataList['rtCode'] == 1) { ?>

                <?php foreach ($aDataList['raItems'] as $DataTableKey => $aDataTableVal) { ?>
                    <tr class="otr<?= $aDataTableVal['FTPdtCode']; ?><?php echo $aDataTableVal['FTXtdBarCode']; ?> xWPdtItemList<?= $aDataTableVal['FNXtdSeqNo'] ?> text-center xCNTextDetail2 nItem<?= $nNumSeq ?> xWPCKPdtItem xWPdtItem" data-key="<?= $aDataTableVal['FNXtdSeqNo'] ?>" data-index="<?= $DataTableKey ?>" data-docno="<?= $aDataTableVal['FTXthDocNo'] ?>" data-pdtname="<?= $aDataTableVal['FTXtdPdtName'] ?>" data-pdtcode="<?= $aDataTableVal['FTPdtCode'] ?>" data-puncode="<?= $aDataTableVal['FTPunCode'] ?>" data-seqno="<?= $aDataTableVal['FNXtdSeqNo'] ?>">
                        <td class="otdListItem xCNHideWhenCancelOrApprove">
                            <label class="fancy-checkbox text-center">
                                <input id="ocbListItem<?= $aDataTableVal['FNXtdSeqNo']; ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPCKSelectMulDel(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </td>
                        <!-- <td class="text-center"><?php echo $nNumSeq;  ?></td> -->
                        <td class="text-left"><?php echo $aDataTableVal['FTPdtCode'];  ?></td>
                        <td class="text-left"><?php echo $aDataTableVal['FTXtdPdtName'];  ?></td>
                        <td class="text-left"><?php echo $aDataTableVal['FTPunName'];  ?></td>
                        <td class="text-left"><?php echo $aDataTableVal['FTXtdBarCode'];  ?></td>
                        <td class="text-right"><?php echo number_format($aDataTableVal['FCXtdQty'], 0);  ?></td>
                        <td class="text-right otdQty">
                            <div class="xWEditInLine<?= $nNumSeq ?>">
                                <input id="ohdQty<?= $nNumSeq ?>" name="ohdQty<?= $nNumSeq ?>" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right  xWValueEditInLine<?= $nNumSeq ?> xWShowInLine<?= $nNumSeq ?>" data-qty="<?php echo number_format($aDataTableVal['FCXtdQty'], 0);  ?>" data-seq="<?= $nNumSeq ?>" type="text" maxlength="10" autocomplete="off" value="<?= str_replace(",", "", number_format($aDataTableVal['FCXtdQtyOrd'], 0)); ?>">
                            </div>
                        </td>
                        <td nowrap="" class="text-center xCNPIBeHideMQSS xCNHideWhenCancelOrApprove">
                            <label class="xCNTextLink">
                                <img class="xCNIconTable xWDelDocRef" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPCKDelPdtInDTTempSingle(this)">
                            </label>
                        </td>

                    </tr>
                    <?php $nNumSeq++; ?>
                <?php } ?>

            <?php } else { ?>
                <tr>
                    <!-- <td class='text-center xCNTextDetail2 xWPCKTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td> -->
                    <td class='text-center xCNTextDetail2 xWPCKTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'กรุณาเลือกเอกสารอ้างอิงใบสั่งงานเพื่อหยิบสินค้า') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php if ($aDataList['rnAllPage'] > 1) { ?>
    <div class="row odvPCKPdtDataTable">
        <div class="col-md-6">
            <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= $aDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= $aDataList['rnCurrentPage'] ?> / <?= $aDataList['rnAllPage'] ?></p>
        </div>
        <div class="col-md-6">
            <div class="xWPage btn-toolbar pull-right">
                <?php if ($nPage == 1) {
                    $tDisabledLeft = 'disabled';
                } else {
                    $tDisabledLeft = '-';
                } ?>
                <button onclick="JSvPCKPdtDataTableClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                    <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
                </button>
                <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                    <?php
                    if ($nPage == $i) {
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    } else {
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                    ?>
                    <button onclick="JSvPCKPdtDataTableClickPage('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
                <?php } ?>
                <?php if ($nPage >= $aDataList['rnAllPage']) {
                    $tDisabledRight = 'disabled';
                } else {
                    $tDisabledRight = '-';
                } ?>
                <button onclick="JSvPCKPdtDataTableClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                    <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
                </button>
            </div>
        </div>
    </div>
<?php } ?>

<?php include('script/jProductPickPdtDataTable.php'); ?>


<script>
    $(document).ready(function() {
        JSxEditQtyAndPrice();

        if (bIsApvOrCancel && !bIsAddPage) {
            $('.xCNPdtEditInLine').attr('disabled', true);
        }


        if (bIsApvOrCancel && !bIsAddPage) {
            $('.xCNHideWhenCancelOrApprove').hide();
        }
    });
    //เเก้ไขจำนวน
    function JSxEditQtyAndPrice() {
        $('.xCNPdtEditInLine').click(function() {
            $(this).focus().select();
        });

        $('.xCNQty').off().on('change keyup', function(e) {
            if (e.type === 'change' || e.keyCode === 13) {
                var nSeq = $(this).attr('data-seq');
                var nQtyTmp = $(this).attr('data-qty');
                var nQty = $('#ohdQty' + nSeq).val();


                if (nQty > nQtyTmp) {
                    var tWarningMessage = 'กรอกจำนวนหยิบเกิน';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    $(this).val(nQtyTmp);
                    return;
                } else {
                    nNextTab = parseInt(nSeq) + 1;
                    $('.xWValueEditInLine' + nNextTab).focus().select();

                    JSxGetDisChgList(nSeq);
                }

            }
        });

    }

    //เเก้ไขจำนวน และ ราคา
    function JSxGetDisChgList(pnSeq) {
        var nQty = $('#ohdQty' + pnSeq).val();
        var tPCKDocNo = $("#oetPCKDocNo").val();
        var tPCKBchCode = $("#oetPCKBchCode").val();

        if (pnSeq != undefined) {
            $.ajax({
                type: "POST",
                url: "docPCKEditPdtInDTDocTemp",
                data: {
                    'tPCKBchCode': tPCKBchCode,
                    'tPCKDocNo': tPCKDocNo,
                    'nPCKSeqNo': pnSeq,
                    'nQty': nQty
                },
                catch: false,
                timeout: 0,
                success: function(oResult) {},
                error: function(jqXHR, textStatus, errorThrown) {}
            });
        }
    }

    // Check All
    $('#ocbCheckAll').click(function() {
        if ($(this).is(':checked') == true) {
            $('.ocbListItem').prop('checked', true);
            $("#odvPCKMngDelPdtInTableDT #oliPCKBtnDeleteMulti").removeClass("disabled");
        } else {
            $('.ocbListItem').prop('checked', false);
            $("#odvPCKMngDelPdtInTableDT #oliPCKBtnDeleteMulti").addClass("disabled");
        }
    });


    function FSxPCKSelectMulDel(ptElm) {
        // $('#otbDODocPdtAdvTableList #odvTBodyDOPdtAdvTableList .ocbListItem').click(function(){
        let tDODocNo = $('#oetPCKDocNo').val();
        let tDOSeqNo = $(ptElm).parents('.xWPdtItem').data('key');
        let tDOPdtCode = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        // let tDOBarCode = $(ptElm).parents('.xWPdtItem').data('barcode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp = localStorage.getItem("PCK_LocalItemDataDelDtTemp");
        let oDataObj = [];
        if (oLocalItemDTTemp) {
            oDataObj = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert = [JSON.parse(localStorage.getItem("PCK_LocalItemDataDelDtTemp"))];
        if (aArrayConvert == '' || aArrayConvert == null) {
            oDataObj.push({
                'tDocNo': tDODocNo,
                'tSeqNo': tDOSeqNo,
                'tPdtCode': tDOPdtCode,
                // 'tBarCode': tDOBarCode,
            });
            localStorage.setItem("PCK_LocalItemDataDelDtTemp", JSON.stringify(oDataObj));
            JSxPCKTextInModalDelPdtDtTemp();
        } else {
            var aReturnRepeat = JStPCKFindObjectByKey(aArrayConvert[0], 'tSeqNo', tDOSeqNo);
            if (aReturnRepeat == 'None') {
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo': tDODocNo,
                    'tSeqNo': tDOSeqNo,
                    'tPdtCode': tDOPdtCode,
                    // 'tBarCode': tDOBarCode,
                });
                localStorage.setItem("PCK_LocalItemDataDelDtTemp", JSON.stringify(oDataObj));
                JSxPCKTextInModalDelPdtDtTemp();
            } else if (aReturnRepeat == 'Dupilcate') {
                localStorage.removeItem("PCK_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i].tSeqNo == tDOSeqNo) {
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i] != undefined) {
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("PCK_LocalItemDataDelDtTemp", JSON.stringify(aNewarraydata));
                JSxPCKTextInModalDelPdtDtTemp();
            }
        }
        JSxPCKShowButtonDelMutiDtTemp();
        // });
    }





    $(document).on("keypress", 'form', function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });




    function FSxPCKSelectAll() {
        if ($('.ocbListItemAll').is(":checked")) {
            $('.ocbListItem').each(function(e) {
                if (!$(this).is(":checked")) {
                    $(this).on("click", FSxPCKSelectMulDel(this));
                }
            });
        } else {
            $('.ocbListItem').each(function(e) {
                if ($(this).is(":checked")) {
                    $(this).on("click", FSxPCKSelectMulDel(this));
                }
            });
        }

    }
</script>