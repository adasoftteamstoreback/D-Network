<?php 
    if($aDataDTTmp['rtCode'] == '1'){
        $tJR1PdtCode    = $aDataDTTmp['raItems']['FTPdtCode'];
        $tJR1PdtName    = $aDataDTTmp['raItems']['FTXtdPdtName'];
        $tJR1BchCode    = $aDataDTTmp['raItems']['FTBchCode'];
        $tJR1DocNo      = $aDataDTTmp['raItems']['FTXthDocNo'];
        $tJR1DocKey     = $aDataDTTmp['raItems']['FTXthDocKey'];
        $tJR1CstCode    = $aDataDTTmp['raItems']['FTCstCode'];
        $tJR1CarCode    = $aDataDTTmp['raItems']['FTCarCode'];
        $tJR1SeqCode    = $aDataDTTmp['raItems']['FNXtdSeqNo'];
    }
?>

<div id="odvJR1ModalPopUpPstSet" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 7000; display: none;">
    <div class="modal-dialog modal-lg" style="width:60%">
        <div class="modal-content">
            <input type="hidden" id="ohdJR1StaEventEditInlinePdt" name="ohdJR1StaEventEditInlinePdt" value="0">
            <input type="hidden" id="ohdJR1BchCode" name="ohdJR1BchCode"    value="<?=@$tJR1BchCode?>">
            <input type="hidden" id="ohdJR1DocNo"   name="ohdJR1DocNo"      value="<?=@$tJR1DocNo?>">
            <input type="hidden" id="ohdJR1DocKey"  name="ohdJR1DocKey"     value="<?=@$tJR1DocKey?>">
            <input type="hidden" id="ohdJR1SrnCode" name="ohdJR1SrnCode"    value="<?=@$tJR1PdtCode?>">
            <input type="hidden" id="ohdJR1CstCode" name="ohdJR1CstCode"    value="<?=@$tJR1CstCode?>">
            <input type="hidden" id="ohdJR1CarCode" name="ohdJR1CarCode"    value="<?=@$tJR1CarCode?>">
            <input type="hidden" id="ohdJR1SeqCode" name="ohdJR1SeqCode"    value="<?=@$tJR1SeqCode?>">
            <div class="modal-header xCNModalHead">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?= language('document/jobrequest1/jobrequest1','tJR1Product');?> <?=@$tJR1PdtName;?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSxJR1EventSubmitDTSetTemp(this);" data-dismiss="modal"><?=language('common/main/main', 'tModalConfirm'); ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn"   <?php if( $tTypeAction == 'add' ){ echo 'onclick="JSxJR1EventCancelDTSetTemp()"'; } ?> data-dismiss="modal"><?=language('common/main/main', 'tModalCancel'); ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="odvJR1ModalBodyDtsCompCstFlw">
                <!-- ค้นหารายการสินค้า -->
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="padding-bottom: 20px;padding-left: 0px;">
                    <div class="">
                        <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1','tJR1LabelPdtSearch');?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchModalPdtHTML" name="oetSearchModalPdtHTML" onkeyup="JSvJR1SearchModalPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                            <span class="input-group-btn">
                                <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvJR1SearchModalPdtHTML()">
                                    <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive" style="max-height: 390px;overflow-y: scroll;margin-bottom: 15px;">
                            <table id="otbJR1TablePdtSet" class="table table-striped" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th nowrap class="xCNTextBold" style="text-align:center" width="10%"><?= language('document/jobrequest1/jobrequest1','tJR1PdtSetNo');?></th>
                                        <th nowrap class="xCNTextBold" style="text-align:center" width="65%"><?= language('document/jobrequest1/jobrequest1','tJR1PdtSetName');?></th>
                                        <th nowrap class="xCNTextBold" style="text-align:center" width="15%"><?= language('document/jobrequest1/jobrequest1','tJR1PdtSetType');?></th>
                                        <th nowrap class="xCNTextBold" style="text-align:center" width="10%"><?= language('document/jobrequest1/jobrequest1','tJR1PdtSetMng');?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <span>รายการทั้งหมด </span><span id="CountItems"></span><span> รายการ</span>
                    </div>
                </div>
            </div>
        <div>
    </div>
</div>


<script type="text/javascript">
    $("document").ready(function(){

        localStorage.removeItem('ItemDataForCheckAgain');
        JSxCheckPinMenuClose();

        // Load View Table List DT Set Cst Follow
        JSvJR1CallTblDTSCompCstFlw();
    });

    // Load View DTS CSTFolow
    function JSvJR1CallTblDTSCompCstFlw(){
        $.ajax({
            type : "POST",
            url  : "docJR1LoadViewTblPDTSetCstFlw",
            data : {
                'tBchCode'  : $('#odvJR1ModalPopUpPstSet #ohdJR1BchCode').val(),
                'tDocNo'    : $('#odvJR1ModalPopUpPstSet #ohdJR1DocNo').val(),
                'tDocKey'   : $('#odvJR1ModalPopUpPstSet #ohdJR1DocKey').val(),
                'tPdtCode'  : $('#odvJR1ModalPopUpPstSet #ohdJR1SrnCode').val(),
                'tCstCode'  : $('#odvJR1ModalPopUpPstSet #ohdJR1CstCode').val(),
                'tCarCode'  : $('#odvJR1ModalPopUpPstSet #ohdJR1CarCode').val(),
                'tSeqCode'  : $('#odvJR1ModalPopUpPstSet #ohdJR1SeqCode').val(),
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                if(tResult != ""){
                    $('#odvJR1ModalPopUpPstSet #otbJR1TablePdtSet tbody').html(tResult);
                    $("#CountItems").text($(".xWJR1TrItemDTSet").length);
                    if($(".xWJR1TrItemDTSet").length <= 10){
                        $(".table-responsive").css('overflow-y','');
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // กดยืนยัน
    function JSxJR1EventSubmitDTSetTemp(evn){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            
            setTimeout(function(){
                $('#odvJR1ModalPopUpPstSet').modal('hide');
                $('.modal-backdrop').remove();
                $('#odvJR1HtmlPopUpDTSet').html('');
            },500);

            setTimeout(function(){
                JSvJR1LoadPdtDataTableHtml();
            },1000);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ลบสินค้า
    function JSxJR1EventCancelDTSetTemp(){
        $.ajax({
            type : "POST",
            url  : "docJR1DeleteDTSetAndDTCaseCloseModal",
            data : {
                'tBchCode'  : $('#odvJR1ModalPopUpPstSet #ohdJR1BchCode').val(),
                'tDocNo'    : $('#odvJR1ModalPopUpPstSet #ohdJR1DocNo').val(),
                'tPdtCode'  : $('#odvJR1ModalPopUpPstSet #ohdJR1SrnCode').val(),
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                setTimeout(function(){
                    JSvJR1LoadPdtDataTableHtml();
                },1000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Event Click Delet DT Set
    function JSnJR1RemovePdtSetInTemp(evn){
        let tAgnCode    = $('#ohdJR1ADCode').val();
        let tBchCode    = $(evn).parents('.xWJR1TrItemDTSet').data('bchcode');
        let tDocNo      = $(evn).parents('.xWJR1TrItemDTSet').data('docno');
        let tPdtCode    = $(evn).parents('.xWJR1TrItemDTSet').data('pdtcode');
        let tPdtCodeOrg = $(evn).parents('.xWJR1TrItemDTSet').data('pdtcodeorg');
        let tSrnCode    = $(evn).parents('.xWJR1TrItemDTSet').data('srncode');
        let tCarCode    = $(evn).parents('.xWJR1TrItemDTSet').data('carcode');
        let tSeqCode    = $(evn).parents('.xWJR1TrItemDTSet').data('seqcode');
        $.ajax({
            type : "POST",
            url  : "docJR1EventDelPDTDTSet",
            data : {
                'tAgnCode'      : tAgnCode,
                'tBchCode'      : tBchCode,
                'tDocNo'        : tDocNo,
                'tPdtCode'      : tPdtCode,
                'tPdtCodeOrg'   : tPdtCodeOrg,
                'tSrnCode'      : tSrnCode,
                'tCarCode'      : tCarCode,
                'tSeqCode'      : tSeqCode
            },
            cache   : false,
            timeout : 0,
            success : function (tResult) {
                let aDataReturn = JSON.parse(tResult);
                if(aDataReturn['rtCode'] == '1'){
                    JSvJR1CallTblDTSCompCstFlw();
                    JSvJR1LoadPdtDataTableHtml();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // เปลี่ยนสินค้าเดิมในระบบเป็นสินค้าใหม่
    function JSnJR1EditPdtSetInTemp(evn){
        var nJR1StaEditInLinePdt    = $('#ohdJR1StaEventEditInlinePdt').val();
        if(nJR1StaEditInLinePdt == 0){
            // Set Status Event Edit Inline Active
            $('#ohdJR1StaEventEditInlinePdt').val(1);
            // Show Hide Input Edit Inline
            $(evn).parents('.xWJR1TrItemDTSet').find('.xWLabelPdt').addClass('xCNHide');
            $(evn).parents('.xWJR1TrItemDTSet').find('.xWInputBrowsePdt').removeClass('xCNHide');
            // Show Icon Edit Input
            $(evn).addClass('xCNHide');
            // $(evn).parents('.xWJR1TrItemDTSet').find('.xCNIconSaveInLinePdt').removeClass('xCNHide');
        }else{
            alert('ท่านได้ทำรายการเปลี่ยนสินค้าค้างอยู่ !!! กรุณายืนยันรายการสินค้าที่ทำการแก้ไขก่อน');
        }
    }

    // กด Click Brewse Modal สินค้าตัวใหม่มาแสดง
    function JSxJR1EventBrowsePdtInline(evn){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            let tJR1InlineData  = {
                'tJR1BchCode_Inline'    : $(evn).parents('.xWJR1TrItemDTSet').data('bchcode'),
                'tJR1PdtCode_Inline'    : $(evn).parents('.xWJR1TrItemDTSet').data('pdtcode'),
                'tJR1PdtCodeOrg_Inlne'  : $(evn).parents('.xWJR1TrItemDTSet').data('pdtcodeorg'),
                'tJR1SrnCode_Inlne'     : $(evn).parents('.xWJR1TrItemDTSet').data('srncode'),
                'tJR1CarCode_Inlne'     : $(evn).parents('.xWJR1TrItemDTSet').data('carcode'),
            };
            // ******** Delete Local Storage Set Data ********
            localStorage.removeItem('oJR1DataInlinePdt');

            // ******** Add Data Local Storage Set Data ********
            localStorage.setItem("oJR1DataInlinePdt",JSON.stringify(tJR1InlineData));

            if(tJR1InlineData['tJR1PdtCode_Inline'] != ""){
                let dTime               = new Date();
                let dTimelocalStorage   = dTime.getTime();
                // Start Hide Modal Product Set
                $('#odvJR1ModalPopUpPstSet').modal('hide');
                // Start Hide Modal Product Set
                setTimeout(function(){
                    $.ajax({
                        type : "POST",
                        url  : "BrowseDataPDT",
                        data : {
                            'Qualitysearch'   : [],
                            'PriceType'       : ["Cost","tCN_Cost","Company","1"],
                            'SelectTier'      : ["Barcode"],
                            'ShowCountRecord' : 10,
                            'NextFunc'        : "FSvJR1NextFuncWhenEditInlinePdt",
                            'ReturnType'      : "S",
                            'SPL'             : ['',''],
                            'BCH'             : ['',''],
                            'MCH'             : ['',''],
                            'SHP'             : ['',''],
                            'TimeLocalstorage': dTimelocalStorage,
                            'Where'           : [''],
                            'PDTService'      : true,
                            'aAlwPdtType'     : ['T1','T2','T5','S2','S5']
                        },
                        cache   : false,
                        timeout : 0,
                        success : function(tResult){
                            $("#odvModalDOCPDT").modal({backdrop: "static", keyboard: false});
                            $("#odvModalDOCPDT").modal({show: true});
                            //remove localstorage
                            localStorage.removeItem("LocalItemDataPDT");
                            $("#odvModalsectionBodyPDT").html(tResult);
                            $("#odvModalDOCPDT #oliBrowsePDTSupply").css('display','none');
                        }
                    });
                },500);
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
    
    // Behide Next Func Edit Inline Pdt
    function FSvJR1NextFuncWhenEditInlinePdt(poParam){
        if(poParam == '' || poParam == 'NULL'){
        }else{
            let aDataInlinePdt  = JSON.parse(localStorage.getItem('oJR1DataInlinePdt'));
            let aDataPdtBrowse  = JSON.parse(poParam);
            $.ajax({
                type : "POST",
                url  : "docJR1EventUpdInlinePdtSet",
                data : {
                    "poItemPdt"     : aDataPdtBrowse,
                    'poInlinePdt'   : aDataInlinePdt,
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    let aReturn = JSON.parse(tResult);
                    if(aReturn['nStaEvent'] == '1'){
                        // Start Hide Modal Product Set
                        $('#odvJR1ModalPopUpPstSet').modal('show');
                        JSvJR1CallTblDTSCompCstFlw();
                    }
                    // Set Status Event Edit Inline Active
                    $('#ohdJR1StaEventEditInlinePdt').val(0);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    // เปลี่ยนสินค้าเดิมในระบบเป็นสินค้าใหม่
    function JSnJR1RefreshPdtSetInTemp(evn){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            let tJR1InlineData  = {
                'tJR1BchCode_Inline'    : $(evn).parents('.xWJR1TrItemDTSet').data('bchcode'),
                'tJR1Docno_Inline'      : $(evn).parents('.xWJR1TrItemDTSet').data('docno'),
                'tJR1PdtCode_Inline'    : $(evn).parents('.xWJR1TrItemDTSet').data('pdtcode'),
                'tJR1PdtCodeOrg_Inlne'  : $(evn).parents('.xWJR1TrItemDTSet').data('pdtcodeorg'),
                'tJR1SrnCode_Inlne'     : $(evn).parents('.xWJR1TrItemDTSet').data('srncode'),
                'tJR1CarCode_Inlne'     : $(evn).parents('.xWJR1TrItemDTSet').data('carcode'),
            };
            $.ajax({
                type : "POST",
                url  : "docJR1EventRejectInlinePdtSet",
                data : {'poItemPdtSet' : tJR1InlineData},
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    let aReturn = JSON.parse(tResult);
                    if(aReturn['nStaEvent'] == '1'){
                        // Start Hide Modal Product Set
                        $('#odvJR1ModalPopUpPstSet').modal('show');
                        JSvJR1CallTblDTSCompCstFlw();
                    }
                    // Set Status Event Edit Inline Active
                    $('#ohdJR1StaEventEditInlinePdt').val(0);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

</script>