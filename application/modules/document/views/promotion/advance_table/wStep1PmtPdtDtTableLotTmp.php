<?php 
    if(isset($aDataDTTmp['raItems'])){
        $aLotDetail = $aDataDTTmp['raItems'];
    }
    $aLotPDTDetail = $aDataDTTmp['aPdtName'];
    $nNextRound = $nRound+1;
    $tPdtCode   = $tPdtCode;
    $tTable     = $tTable;
    $nMaxRound   = count($tPdtCode);
    $tPdtName   = $aLotPDTDetail[0]->FTPdtName;
    $nSeqno     = $nSeqno;
    $tCurrentSeq = $aWhereData['nSeqno'];
?>

<style>
    .xCNActivePDT{
        background-color: #5fa7d3 !important;
    }
</style>
<!-- tTable == 'TCNMPdtBrand' || tTable == 'TCNMPdtModel' -->

<div id="odvPromotionModalPopUpLot" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 7000; display: none;">
    <div class="modal-dialog modal-lg" style="width:60%">
        <div class="modal-content">
            <input type="hidden" id="ohdJR1BchCode" name="ohdJR1BchCode"    value="<?=@$tJR1BchCode?>">
            <input type="hidden" id="ohdJR1DocNo"   name="ohdJR1DocNo"      value="<?=@$tJR1DocNo?>">
            <input type="hidden" id="ohdJR1DocKey"  name="ohdJR1DocKey"     value="<?=@$tJR1DocKey?>">
            <input type="hidden" id="ohdJR1SrnCode" name="ohdJR1SrnCode"    value="<?=@$tJR1PdtCode?>">
            <input type="hidden" id="ohdJR1CstCode" name="ohdJR1CstCode"    value="<?=@$tJR1CstCode?>">
            <input type="hidden" id="ohdJR1CarCode" name="ohdJR1CarCode"    value="<?=@$tJR1CarCode?>">
            <input type="hidden" id="ohdPROType" name="ohdPROType"    value="<?=@$tTable?>">
            <div class="modal-header xCNModalHead">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?= language('document/promotion/promotion','tPMTHeadLotTitle');?> <?=@$tJR1PdtName;?></label>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="odvJR1ModalBodyDtsCompCstFlw">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <?php if($tTable == 'TCNMPdtBrand'){?>
                            <span style='font-weight: 700!important;'> <?= language('document/promotion/promotion','tPdtNameBrand');?></span> : <span><?= $tPdtName;?></span>
                        <?php }elseif($tTable == 'TCNMPdtModel'){?>
                            <span style='font-weight: 700!important;'> <?= language('document/promotion/promotion','tPdtNameModel');?></span> : <span><?= $tPdtName;?></span>
                        <?php }else{?>
                       <span style='font-weight: 700!important;'> <?= language('document/promotion/promotion','tPdtName');?></span> : <span><?= $tPdtName;?></span>
                       <?php }?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right" style='font-weight: 700!important;'>
                        <?= language('document/promotion/promotion','tPMTHeadLotTitleFRM');?> <?= $nRound+1;?> <?= language('document/promotion/promotion','tPMTHeadLotTitleTo');?> <?= $nMaxRound;?> <?= language('document/promotion/promotion','tLabel2');?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table id="otbJR1TablePdtSet" class="table table-striped" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th nowrap class="xCNTextBold" style="text-align:center" width="10%"><?= language('document/promotion/promotion','tTBNo');?></th>
                                        <th nowrap class="xCNTextBold" style="text-align:center" width="20%"><?= language('document/promotion/promotion','tPMTLotNo');?></th>
                                        <th nowrap class="xCNTextBold" style="text-align:center" width="70%"><?= language('document/promotion/promotion','tPMTLotName');?></th>
                                    </tr>
                                </thead>
                                <tbody class="LotDetail">
                                    <?php if(isset($aLotDetail)) {?>
                                    <?php foreach($aLotDetail as $nkey => $aValue) {?>
                                        <tr class="xWPromotionLotItemDT"
                                            data-PdtCode="<?=@$aValue->FTPdtCode;?>"
                                            data-lotno="<?=@$aValue->FTLotNo;?>"
                                            onclick="JSxPromotionLotEventClick(this);"
                                        >
                                        <td class="text-center"><?=@$nkey+1;?></td>
                                        <td class="text-left"><?=@$aValue->FTLotNo;?></td>
                                        <td class="text-left"><?=@$aValue->FTLotBatchNo;?></td>
                                    <?php }}else{ ?>
                                        <tr>
                                            <td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn"  onclick="JSxJR1EventCancelLotDTSetTemp(<?=$nNextRound?>);"><?=language('common/main/main', 'tCancel'); ?></button>
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" 
                           
                           <?php 
                            // if( $nCountPDTLot == $nRound){  
                            //     onclick = "JSxJR1EventSubmitLotDTSetTemp(this)"
                            // }else{
                            //     onclick =" JSvPromotionLoadModalShowPdtLotDT (, $nRound+1)"
                            // }data-dismiss="modal"
                            ?>
                        
                        onclick="JSxJR1EventSubmitLotDTSetTemp(<?=$nNextRound?>);"><?=language('common/main/main', 'tCMNConfirm'); ?></button>
                    </div>

			</div>
        <div>
    </div>
</div>



<scritp type="text/html" id="oscJR1TextInputChangeProduct">
    
</script>



<script type="text/javascript">
    $("document").ready(function(){

        localStorage.removeItem('ItemDataForCheckAgain');
        JSxCheckPinMenuClose();

        // Load View Table List DT Set Cst Follow
        // JSvJR1CallTblDTSCompCstFlw();
    });


// $(".xWpromotioconfirmcancel").unbind().click(function(){ 
//     $('#odvPromotionModalPopUpLot').modal('show');
// });

$(".xWpromotioconfirmcancel").unbind().click(function(){ 
    $('#odvPromotionModalPopUpLot').modal('show');

$('.modal-backdrop').remove();
    var aPassedArrayPdt = <?php echo json_encode($tPdtCode); ?>;
    var aPassedArraySeq = <?php echo json_encode($nSeqno); ?>;
    var nMaxRound = aPassedArrayPdt.length-1;
    var tPdtCode ='';
    var pnSeqno = <?php echo $tCurrentSeq ?>;
    var pnNextRound = <?php echo $nNextRound ?>;
    var tTable = $('#ohdPROType').val();

    var aFinding = $( "tbody.LotDetail" ).find( "tr.xCNActivePDT" );
    var aPdtArray = new Array();
    var aLotArray = new Array();
    
    $.ajax({
        type : "POST",
        url  : "docPromotionLotInsertTmp",
        data : {
            'tBchCode'  : $('#ohdPromotionBchCode').val(),
            'tDocNo'    : $('#oetPromotionDocNo').val(),
            'tPdtCode'  : tPdtCode,
            'tTable'    : tTable,
            'tLotno'    : aLotArray,
            'pnSeqno'   : pnSeqno
        },
        cache   : false,
        timeout : 0,
        success : function (tResult) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });

    if(nMaxRound >= pnNextRound){
        JSvPromotionLoadModalShowPdtLotDT(aPassedArrayPdt,aPassedArraySeq,pnNextRound,nMaxRound);
    }else{
        $('#odvPromotionModalPopUpLot').modal('hide');
        $('#odvPromotionAddPmtGroupModal').modal('show');
        JSxPromotionStep1GetPmtPdtDtInTmp(1, true);
    }
});

$(".xWpromotioconfirmcancelBranch").unbind().click(function(){ 
    $('#odvPromotionModalPopUpLot').modal('show');

$('.modal-backdrop').remove();
    var aPassedArrayPdt = <?php echo json_encode($tPdtCode); ?>;
    var aPassedArraySeq = <?php echo json_encode($nSeqno); ?>;
    var nMaxRound = aPassedArrayPdt.length-1;
    var tPdtCode ='';
    var pnSeqno = <?php echo $tCurrentSeq ?>;
    var pnNextRound = <?php echo $nNextRound ?>;
    var tTable = $('#ohdPROType').val();

    var aFinding = $( "tbody.LotDetail" ).find( "tr.xCNActivePDT" );
    var aPdtArray = new Array();
    var aLotArray = new Array();
    
    $.ajax({
        type : "POST",
        url  : "docPromotionLotInsertTmp",
        data : {
            'tBchCode'  : $('#ohdPromotionBchCode').val(),
            'tDocNo'    : $('#oetPromotionDocNo').val(),
            'tPdtCode'  : tPdtCode,
            'tTable'    : tTable,
            'tLotno'    : aLotArray,
            'pnSeqno'   : pnSeqno
        },
        cache   : false,
        timeout : 0,
        success : function (tResult) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });

    if(nMaxRound >= pnNextRound){
        JSvPromotionLoadModalShowPdtLotDT(aPassedArrayPdt,aPassedArraySeq,pnNextRound,nMaxRound);
    }else{
        $('#odvPromotionModalPopUpLot').modal('hide');
        $('#odvPromotionAddPmtGroupModal').modal('show');
        JSxPromotionStep1GetPmtBrandDtInTmp(1, true);
    }
});


$(".xWpromotionpleaseselect").unbind().click(function(){ 
    $('#odvPromotionModalPopUpLot').modal('show');
});

$(".xWpromotioconfirmSubmitBranch").unbind().click(function(){ 
    $('#odvPromotionModalPopUpLot').modal('show');

    $('.modal-backdrop').remove();
        var aPassedArrayPdt = <?php echo json_encode($tPdtCode); ?>;
        var aPassedArraySeq = <?php echo json_encode($nSeqno); ?>;
        var nMaxRound = aPassedArrayPdt.length-1;
        var tPdtCode ='';
        var pnSeqno = <?php echo $tCurrentSeq ?>;
        var pnNextRound = <?php echo $nNextRound ?>;
        var tTable = $('#ohdPROType').val();

        var aFinding = $( "tbody.LotDetail" ).find( "tr.xCNActivePDT" );
        var aPdtArray = new Array();
        var aLotArray = new Array();
        
        $.ajax({
            type : "POST",
            url  : "docPromotionLotInsertTmp",
            data : {
                'tBchCode'  : $('#ohdPromotionBchCode').val(),
                'tDocNo'    : $('#oetPromotionDocNo').val(),
                'tPdtCode'  : tPdtCode,
                'tTable'    : tTable,
                'tLotno'    : aLotArray,
                'pnSeqno'   : pnSeqno
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

        if(nMaxRound >= pnNextRound){
            JSvPromotionLoadModalShowPdtLotDT(aPassedArrayPdt,aPassedArraySeq,pnNextRound,nMaxRound);
        }else{
            $('#odvPromotionModalPopUpLot').modal('hide');
            $('#odvPromotionAddPmtGroupModal').modal('show');
            // JSxPromotionStep1GetPmtPdtDtInTmp(1, true);
            JSxPromotionStep1GetPmtBrandDtInTmp(1, true);
        }
});

$(".xWpromotioconfirmSubmit").unbind().click(function(){ 
    $('#odvPromotionModalPopUpLot').modal('show');

    $('.modal-backdrop').remove();
        var aPassedArrayPdt = <?php echo json_encode($tPdtCode); ?>;
        var aPassedArraySeq = <?php echo json_encode($nSeqno); ?>;
        var nMaxRound = aPassedArrayPdt.length-1;
        var tPdtCode ='';
        var pnSeqno = <?php echo $tCurrentSeq ?>;
        var pnNextRound = <?php echo $nNextRound ?>;
        var tTable = $('#ohdPROType').val();

        var aFinding = $( "tbody.LotDetail" ).find( "tr.xCNActivePDT" );
        var aPdtArray = new Array();
        var aLotArray = new Array();
        
        $.ajax({
            type : "POST",
            url  : "docPromotionLotInsertTmp",
            data : {
                'tBchCode'  : $('#ohdPromotionBchCode').val(),
                'tDocNo'    : $('#oetPromotionDocNo').val(),
                'tPdtCode'  : tPdtCode,
                'tTable'    : tTable,
                'tLotno'    : aLotArray,
                'pnSeqno'   : pnSeqno
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

        if(nMaxRound >= pnNextRound){
            JSvPromotionLoadModalShowPdtLotDT(aPassedArrayPdt,aPassedArraySeq,pnNextRound,nMaxRound);
        }else{
            $('#odvPromotionModalPopUpLot').modal('hide');
            $('#odvPromotionAddPmtGroupModal').modal('show');
            JSxPromotionStep1GetPmtPdtDtInTmp(1, true);
        }
});



    // Load View DTS CSTFolow
    function JSvJR1CallTblDTSCompCstFlw(){
        $.ajax({
            type : "POST",
            url  : "docJR1LoadViewTblPDTSetCstFlw",
            data : {
                'tBchCode'  : $('#odvPromotionModalPopUpLot #ohdJR1BchCode').val(),
                'tDocNo'    : $('#odvPromotionModalPopUpLot #ohdJR1DocNo').val(),
                'tDocKey'   : $('#odvPromotionModalPopUpLot #ohdJR1DocKey').val(),
                'tPdtCode'  : $('#odvPromotionModalPopUpLot #ohdJR1SrnCode').val(),
                'tCstCode'  : $('#odvPromotionModalPopUpLot #ohdJR1CstCode').val(),
                'tCarCode'  : $('#odvPromotionModalPopUpLot #ohdJR1CarCode').val(),
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                if(tResult != ""){
                    $('#odvPromotionModalPopUpLot #otbJR1TablePdtSet tbody').html(tResult);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // กดยืนยัน
    function JSxJR1EventSubmitLotDTSetTemp(pnNextRound){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var aPassedArrayPdt = <?php echo json_encode($tPdtCode); ?>;
            var aPassedArraySeq = <?php echo json_encode($nSeqno); ?>;
            var nMaxRound = aPassedArrayPdt.length-1;
            var tPdtCode ='';
            var pnSeqno = <?php echo $tCurrentSeq ?>;
            var tTable = $('#ohdPROType').val();

            var aFinding = $( "tbody.LotDetail" ).find( "tr.xCNActivePDT" );
            var aPdtArray = new Array();
            var aLotArray = new Array();
            $.each(aFinding, function( index, value ) {
                tPdtCode = $(value).data('pdtcode');
                var tLotno   = $(value).data('lotno');
                aPdtArray.push(tPdtCode);
                aLotArray.push(tLotno);
            });

            if(aLotArray.length == 0){
                $('#odvPromotionModalPopUpLot').modal('hide');
                $('#odvModalPromotionLotPleaseSelect').modal('show');
                return;
            }

            $('.modal-backdrop').remove();
            $.ajax({
                type : "POST",
                url  : "docPromotionLotInsertTmp",
                data : {
                    'tBchCode'  : $('#ohdPromotionBchCode').val(),
                    'tDocNo'    : $('#oetPromotionDocNo').val(),
                    'tPdtCode'  : tPdtCode,
                    'tLotno'    : aLotArray,
                    'tTable'    : tTable,
                    'pnSeqno'   : pnSeqno
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

             

            if(nMaxRound >= pnNextRound){
                if(tTable == 'TCNMPdtBrand' || tTable == 'TCNMPdtModel'){
                    JSvPromotionLoadModalShowBrandLotDT(tTable,aPassedArrayPdt,aPassedArraySeq,pnNextRound,nMaxRound);
                }else{
                    JSvPromotionLoadModalShowPdtLotDT(aPassedArrayPdt,aPassedArraySeq,pnNextRound,nMaxRound);
                }
            }else{
                $('#odvPromotionModalPopUpLot').modal('hide');
                $('#odvPromotionAddPmtGroupModal').modal('show');
                if(tTable == 'TCNMPdtBrand' || tTable == 'TCNMPdtModel'){
                    JSxPromotionStep1GetPmtBrandDtInTmp(1, true);
                }else{
                    JSxPromotionStep1GetPmtPdtDtInTmp(1, true);
                }
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // กดยกเลิก
    function JSxJR1EventCancelLotDTSetTemp(pnNextRound){
        var pnSeqnot = <?php echo $tCurrentSeq ?>;
        var tTable = $('#ohdPROType').val();
        $('#odvPromotionModalPopUpLot').modal('hide');
        if(tTable == 'pdt'){
            $(".xWpromotioconfirmcancel").trigger('click');
            // $('#odvModalPromotionLotCancel').modal('show');
        }else if(tTable == 'TCNMPdtBrand' || tTable == 'TCNMPdtModel'){
            $(".xWpromotioconfirmcancelBranch").trigger('click');
            // $('#odvModalPromotionLotCancelBranch').modal('show');
        }
    }
    
    // Onclick Select Lot
    function JSxPromotionLotEventClick(evn){
        if($(evn).hasClass("xCNActivePDT")){
            $(evn).removeClass("xCNActivePDT");
        }else{
            $(evn).addClass("xCNActivePDT");
        }
    }




    // Event Click Delet DT Set
    function JSnJR1RemovePdtSetInTemp(evn){
        let tBchCode    = $(evn).parents('.xWJR1TrItemDTSet').data('bchcode');
        let tDocNo      = $(evn).parents('.xWJR1TrItemDTSet').data('docno');
        let tPdsCode    = $(evn).parents('.xWJR1TrItemDTSet').data('pdscode');
        let tPdsCodeOrg = $(evn).parents('.xWJR1TrItemDTSet').data('pdscodeorg');
        let tSrnCode    = $(evn).parents('.xWJR1TrItemDTSet').data('srncode');
        let tCarCode    = $(evn).parents('.xWJR1TrItemDTSet').data('carcode');
        $.ajax({
            type : "POST",
            url  : "docJR1EventDelPDTDTSet",
            data : {
                'tBchCode'      : tBchCode,
                'tDocNo'        : tDocNo,
                'tPdsCode'      : tPdsCode,
                'tPdsCodeOrg'   : tPdsCodeOrg,
                'tSrnCode'      : tSrnCode,
                'tCarCode'      : tCarCode
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                let aDataReturn = JSON.parse(tResult);
                if(aDataReturn['rtCode'] == '1'){
                    JSvJR1CallTblDTSCompCstFlw();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // เปลี่ยนสินค้าเดิมในระบบเป็นสินค้าใหม่
    function JSnJR1EditPdtSetInTemp(evn){
        let tJR1BchCode     = $(evn).parents('.xWJR1TrItemDTSet').data('bchcode');
        let tJR1DocNo       = $(evn).parents('.xWJR1TrItemDTSet').data('docno');
        let tJR1PdsCode     = $(evn).parents('.xWJR1TrItemDTSet').data('pdscode');
        let tJR1PdsCodeOrg  = $(evn).parents('.xWJR1TrItemDTSet').data('pdscodeorg');
        let tJR1SrnCode     = $(evn).parents('.xWJR1TrItemDTSet').data('srncode');
        let tJR1CarCode     = $(evn).parents('.xWJR1TrItemDTSet').data('carcode');
    }

    // เปลี่ยนสินค้าเดิมในระบบเป็นสินค้าใหม่
    function JSnJR1RefreshPdtSetInTemp(evn){
        alert('Event Refresh Pdt Code ');
    }

</script>