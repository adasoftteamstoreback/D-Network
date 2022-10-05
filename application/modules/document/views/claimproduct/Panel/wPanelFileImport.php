<!-- Panel ไฟลแนบ -->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?=language('common/main/main', 'tUPFPanelFile');?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvCLMDataFile" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvCLMDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvShowDataTable"></div>
            </div>
        </div>
    </div>
</div>
<script>


    var oCLMCallDataTableFile = {
        ptElementID     : 'odvShowDataTable',
        ptBchCode       : $('#ohdCLMBchCode').val(),
        ptDocNo         : $('#oetCLMDocNo').val(),
        ptDocKey        :'TCNTPdtClaimHD',
        ptSessionID     : '<?=$this->session->userdata("tSesSessionID")?>',
        pnEvent         : <?=$nStaUploadFile?>,
        ptCallBackFunct : '',
        ptStaApv        : $('#ohdCLMStaApv').val(),
        ptStaDoc        : $('#ohdCLMStaDocFile').val()
    }
    JCNxUPFCallDataTable(oCLMCallDataTableFile);
</script>
