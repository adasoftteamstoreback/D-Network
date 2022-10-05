<div class="panel panel-headline">
    <div class="panel-body">
        <div class="row">
            <!--ส่วนบน-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="xCNLabelMNGTitle"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPTitleDetail')?></label>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table id="otbMNGTableCreateDocRef" class="table">
                        <thead>
                            <tr class="xCNCenter">
                                <th nowrap class="xCNTextBold" style="width:5%;">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" class="ocmCENCheckAPVAll" id="ocmCENCheckAPVAll" disabled >
                                        <span class="ospListItemAll xCNDocDisabled">&nbsp;</span>
                                    </label>
                                </th>
                                <th nowrap class="xCNTextBold"><?=language('common/main/main','tModalAdvNo')?></th>
                                <th nowrap class="xCNTextBold"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHBchOrder')?></th>
                                <th nowrap class="xCNTextBold" style="width:15%;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHNumberDocBch')?></th>
                                <th nowrap class="xCNTextBold" style="width:6%;"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocType')?></th>
                                <th nowrap class="xCNTextBold">
                                    <?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocRef')?><br>
                                    <?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHTnfAndSpl')?>
                                </th>
                                <th nowrap class="xCNTextBold"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocRefTo')?></th>
                                <th nowrap class="xCNTextBold">
                                    <?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocDate')?><br>
                                    <?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHTnfAndSpl')?>
                                </th>                        
                                <th nowrap class="xCNTextBold"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusPrc')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($aDataList['rtCode'] == 1 ):?>
                                <?php $tKeepDocNo = ''; ?>
                                <?php $nSeq = 1; ?>
                                <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>

                                    <?php 

                                        $tCheckboxDisabled  = "disabled";
                                        $tClassDisabled     = 'xCNDocDisabled';
                                        
                                        //ประเภทเอกสาร
                                        if($aValue['MGTDocType'] == 1){
                                            $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPPRB');
                                        }else if($aValue['MGTDocType'] == 2){
                                            $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPPRS');
                                        }else if($aValue['MGTDocType'] == 3){
                                            $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPRJ');
                                        }else if($aValue['MGTDocType'] == 4){
                                            $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPPRS');
                                        }else if($aValue['MGTDocType'] == 5){
                                            $tTextDocRefType = language('document/managedocpurchaseorder/managedocpurchaseorder','ใบสั่งขาย');
                                        }else{
                                            $tTextDocRefType = '-';
                                        }

                                        //ขอโอน + ขอซื้อไปยัง
                                        if($aValue['MGTDocType'] == 1 || $aValue['MGTDocType'] == 5){ //ขอโอน + สั้งขาย 
                                            $tTextRefTo = $aValue['MGTBchName'];
                                        }else if($aValue['MGTDocType'] == 2 || $aValue['MGTDocType'] == 4){ //ขอซื้อ 
                                            $tTextRefTo = $aValue['MGTSplName'];
                                        }else{
                                            $tTextRefTo = '-';
                                        }

                                        //รวมคอลัมน์
                                        if($aValue['PARTITIONBYDOC'] == 1){
                                            $nRowspan = '';
                                        }else{
                                            $nRowspan = ''; //"rowspan=".$aValue['PARTITIONBYDOC'];
                                        }

                                        $tDocNoRefSendMQ = str_replace("#####","xxxxx",$aValue['MGTDocRef']);
                                    ?>

                                    <tr class="text-left xCNTrManageCreateDoc" data-doctype="<?=$aValue['MGTDocType']?>" data-docnoref="<?=$aValue['FTXphDocNo']?>" data-docrefsubq="<?=$tDocNoRefSendMQ;?>" data-docref="<?=$aValue['MGTDocRef'];?>" data-docbch="<?=$aValue['FTBchCode']?>" >
                                        <?php //if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                            <td nowrap class="text-center" <?=$nRowspan?>>
                                                <label class="fancy-checkbox">
                                                    <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem<?=$aValue['MGTDocType']?> xCNCheckbox_WaitAprove" name="ocbListItem[]" <?=$tCheckboxDisabled;?>>
                                                    <span class="ospListItem<?=$aValue['MGTDocType']?> ospListItem <?=$tClassDisabled?>">&nbsp;</span>
                                                </label>
                                            </td>
                                            <td <?=$nRowspan?> class="text-center"><?=$nSeq++?></td>
                                            <td <?=$nRowspan?>><?=$aValue['FTBchName']?></td>
                                            <td <?=$nRowspan?>><?=$aValue['FTXphDocNo']?></td>
                                        <?php //} ?>

                                        <td><?=$tTextDocRefType?></td>
                                        <td class="xCNMGTPercentProgress"><?=($aValue['MGTDocRef']  == '' ) ? '-' : $aValue['MGTDocRef']?></td>
                                        <td><?=$tTextRefTo?></td>
                                        <td class="text-center"><?=($aValue['MGTDate'] == '' ) ? '-' : $aValue['MGTDate']?></td>
                                        <td><span class="xCNMGTTextProgress xWCSSYellowColor"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPStatusPrc2')?></span></td>
                                        <input type="hidden" class="xCNMGTStatusProgress" value="0" >
                                    </tr>

                                    <?php $tKeepDocNo = $aValue['FTXphDocNo']; ?>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> </p>
            </div>
        </div>
    </div>
</div>


<script>
    $('#obtMNGBackStep').show();
    $('#obtMNGCreateDocRef').hide();
    $('#obtMNGApproveDoc').hide();
    $('#obtMNGExportDoc').hide();
    $('#obtMNGGenFileAgain').hide();
    var nLengthRow = $('#otbMNGTableCreateDocRef tbody tr').length;

    //ส่ง MQ
    JSxMNGCallMQ();
    function JSxMNGCallMQ(){
        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBCallMQCreateDoc",
            data    : { tTextDocRef : "<?=$tTextDocRef?>"},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                // console.log(tResult);
                JSoMNGCallSubscribeMQ();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //รับ MQ
    //JSoMNGCallSubscribeMQ();
    function JSoMNGCallSubscribeMQ() {
        $('#otbMNGTableCreateDocRef tbody tr').each(function (i, el) {

            var tLangCode   = '<?=$this->session->userdata("tLangEdit")?>';
            var tUsrBchCode = $(this).attr('data-docbch');
            var tUsrApv     = '<?=$this->session->userdata('tSesUsername')?>';
            var tDocNo      = $(this).attr('data-docref');
            var tDocNoSubq  = $(this).attr('data-docrefsubq');
            var tDocnoref   = $(this).attr('data-docnoref');
            var tPrefix     = "RESPRMGT";
            var tQName      = tPrefix + "_" + tDocNo + "_" + tUsrApv;

            var poDocConfig = {
                tLangCode   : tLangCode,
                tUsrBchCode : tUsrBchCode,
                tUsrApv     : tUsrApv,
                tDocNo      : tDocNo,
                tPrefix     : tPrefix,
                tStaDelMQ   : 0,
                tStaApv     : 0,
                tQName      : tQName
            };

            var oGetResponse        = oGetResponse + tDocNo;
            var tClassProgressBar   = 'xCNMGTProgressPercent' + tDocNoSubq;
            var tClassProgressName  = 'xCNMGTProgressNameInBar' + tDocNoSubq;

            var tProgress = '<div class="progress" style="margin: 10px; height: 25px;">';
                tProgress += '<div class="'+tClassProgressBar+' progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%">';
                tProgress += '<span class="'+tClassProgressName+'" style="display: block; margin-top: 4px;">1%</span>';
                tProgress += '</div>';
                tProgress += '</div>';
                tProgress += '</div>';

            $(this).find('.xCNMGTTextProgress').text('กำลังประมวลผล');
            $(this).find('.xCNMGTPercentProgress').html();
            $(this).find('.xCNMGTPercentProgress').html(tProgress);
            var oElem = $(this);

            return new Promise(resolve => {
                oGetResponse = setInterval(function(){
                    $.ajax({
                        url     : 'GetMassageQueueMutiDocument',
                        type    : 'post',
                        data    : { tQName : tQName },
                        async   : false,
                        success:function(res){
                            if(res.trim() == '' || res.trim() == null){
                                resolve(true);
                                // console.log('WAIT PROGRESS : ' + tQName);
                            }else{
                                $( "."+tClassProgressBar).css( "width", res.trim() + "%" ).attr( "aria-valuenow", res.trim() ); 
                                $( "."+tClassProgressName).text(res.trim() + "%"); 

                                if (res.trim() == '100') {
                                    //ส่งค่ากลับไป
                                    resolve(true);

                                    //ลบ Interval
                                    JSxRemoveSetInterval(oGetResponse);

                                    //ลบ queue
                                    var poDelQnameParams = {
                                        "ptPrefixQueueName" : 'RESPRMGT',
                                        "ptBchCode"         : "",
                                        "ptDocNo"           : tDocNo,
                                        "ptUsrCode"         : '<?=$this->session->userdata('tSesUsername')?>'
                                    };
                                    FSxCMNRabbitMQDeleteQname(poDelQnameParams);

                                    //เปลี่ยน ค่ากลับ
                                    $("."+tClassProgressBar).removeClass('active'); 
                                    oElem.find('.xCNMGTTextProgress').text('ประมวลผลสำเร็จเเล้ว');
                                    oElem.find('.xCNMGTTextProgress').removeClass('xWCSSYellowColor').addClass('xWCSSGreenColor');

                                    oElem.find('.xCNMGTStatusProgress').val(1);
                                    if((nLengthRow - 1) == i){ //ถ้าเป็นตัวสุดท้าย
                                        JSxCheckWhenSuccess();
                                        
                                        //แจ้งเตือนไปที่สาขานั้นๆ 
                                        // JSxNotiToBranchTRB(tDocnoref);
                                    }
                                    //console.log('SUCCESS : ' + tQName);
                                }else{
                                    //console.log('PROGRESS : ' + res.trim());
                                }
                            }
                        }
                    });
                }, 1000);
            });
        });
    }

    //สั้งให้ลบ interval
    function JSxRemoveSetInterval(poObjectFunction) {
        clearInterval(poObjectFunction);
    }  

    //ถ้าตัวสุดท้าย ประมวลผลครบหมดเเล้ว
    function JSxCheckWhenSuccess(){
        $('.ospListItem2').removeClass('xCNDocDisabled');
        $('.ospListItemAll').removeClass('xCNDocDisabled');
       
        //2 : คืออนุมัติได้เฉพาะใบสั่งซื้อ , ใบขอโอนอนุมัติไม่ได้
        $('#otbMNGTableCreateDocRef tbody tr').each(function (i, el) {
            var tDoctype      = $(this).attr('data-doctype');
            if(tDoctype == 2 || tDoctype == 4){
                $('.ocbListItem2 , .ocmCENCheckAPVAll').attr('disabled',false);
                return;
            }
        });

        //เปิดปุ่มอนุมัติ
        $('#obtMNGApproveDoc').show();
        $('#obtMNGApproveDoc').attr('disabled',true);
    }

    //เลือกทั้งหมด
    $('.ocmCENCheckAPVAll').click(function(){
        if($('.ocmCENCheckAPVAll').is(":checked")){
            //2 : คืออนุมัติได้เฉพาะใบสั่งซื้อ , ใบขอโอนอนุมัติไม่ได้
            $('.ocbListItem2').prop('checked',true);
            JSxMNGOpenButtonApvOpen();
        }else{
            //2 : คืออนุมัติได้เฉพาะใบสั่งซื้อ , ใบขอโอนอนุมัติไม่ได้
            $('.ocbListItem2').prop('checked',false);
        }
    });

    //เปิดให้ปุ่มอนุมัติเปิดใช้งาน
    function JSxMNGOpenButtonApvOpen(){
        $('#obtMNGApproveDoc').attr('disabled',false);
    }

    //ติ๊ก checkbox
    $('.xCNCheckbox_WaitAprove').click(function(){
        var tCheckEventClick = '';
        $(".xCNCheckbox_WaitAprove").each(function() {
            if($(this).prop("checked") == true){
                tCheckEventClick = '1';
                return;
            }
        });

        if(tCheckEventClick == 1){
            $('#obtMNGApproveDoc').attr('disabled',false);
        }else{
            $('#obtMNGApproveDoc').attr('disabled',true);
        }
    });

    //แจ้งเตือนไปที่สาขานั้นๆ 
    function JSxNotiToBranchTRB(ptDocNo){
        // ปัจจุบัน ใบขอโอน c# จะเป็นคน แจ้ง Noti ให้ 29-12-2021

        // $.ajax({
        //     url     : 'docMngDocPreOrdBNoti',
        //     type    : 'POST',
        //     data    : { 'ptDocNo' : ptDocNo },
        //     success:function(res){
        //         console.log(res);
        //     }
        // });
    }

    // เอาไว้ทดสอบ
    // var nLengthRow = $('#otbMNGTableCreateDocRef tbody tr').length;
    // $('#otbMNGTableCreateDocRef tbody tr').each(function (i, el) {
    //     var tDocNo              = $("#oetDODocNo").val();
    //     var tClassProgressBar   = 'xCNMGTProgressPercent' + tDocNo;
    //     var tClassProgressName  = 'xCNMGTProgressNameInBar' + tDocNo;

    //     var tProgress = '<div class="progress" style="margin: 10px; height: 25px;">';
    //         tProgress += '<div class="'+tClassProgressBar+' progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%">';
    //         tProgress += '<span class="'+tClassProgressName+'" style="display: block; margin-top: 4px;">1%</span>';
    //         tProgress += '</div>';
    //         tProgress += '</div>';
    //         tProgress += '</div>';

    //     $(this).find('.xCNMGTTextProgress').text('กำลังประมวลผล');
    //     $(this).find('.xCNMGTPercentProgress').html();
    //     $(this).find('.xCNMGTPercentProgress').html(tProgress);

    //     var nValue = 100;
    //     $( "."+tClassProgressBar).css( "width", nValue + "%" ).attr( "aria-valuenow", nValue ); 
    //     $( "."+tClassProgressName).text(nValue + "%"); 

    //     if(nValue == 100){
    //         $("."+tClassProgressBar).removeClass('active'); 

    //         $(this).find('.xCNMGTTextProgress').text('ประมวลผลสำเร็จเเล้ว');
    //         $(this).find('.xCNMGTStatusProgress').val(1);

    //         if((nLengthRow - 1) == i){ //ถ้าเป็นตัวสุดท้าย
    //             JSxCheckWhenSuccess();
    //         }
    //     }
    // });
</script>