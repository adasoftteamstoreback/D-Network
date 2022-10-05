
<?php if($aDataDTSTmp['rtCode'] == '1' && !empty($aDataDTSTmp['raItems'])){ ?>
    <?php foreach($aDataDTSTmp['raItems'] AS $nKey => $aValueDTS){ ?>
        <?php
            $tJR1PdtCode    = $aValueDTS['FTPdtCode'];
            $tJR1PdtCodeOrg = $aValueDTS['FTPdtCodeOrg'];
            $tJR1PdtName    = $aValueDTS['FTXtdPdtName'];
            $tJR1PdtPsvType = $aValueDTS['FTPsvType'];
            $tJR1SrnCode    = $aValueDTS['FTSrnCode'];
            $tJR1SeqCode    = $aValueDTS['FNXtdSeqNo'];

            // $tJR1PdsCode    = $aValueDTS['FTPdtCodeSet'];
            // $tJR1PdsCodeOrg = $aValueDTS['FTPdtCodeSetOrg'];
            // $tJR1PdsName    = $aValueDTS['FTXtdPdtNameSet'];
            // $tJR1PdtPsvType = $aValueDTS['FTPsvType'];
            
            // Input Data Set Event
            $tJR1BchCode    = $aValueDTS['FTBchCode'];
            $tJR1DocNo      = $aValueDTS['FTXthDocNo'];
            // $tSrnCode       = $aValueDTS['FTSrnCode'];
            // $tCarCode       = $aValueDTS['FTCarCode'];

            // Paramiter CST Flw
            // $tJR1PdtCodeCstFlw      = $aValueDTS['FTPdtCodeCstFlw'];
            // $tJR1PdtNameCstFlw      = $aValueDTS['FTPdtNameCstFlw'];
            // $tJR1PdtCodeOrgCstFlw   = $aValueDTS['FTPdtCodeOrgCstFlw'];
            // $tJR1PdtNameOrgCstFlw   = $aValueDTS['FTPdtNameOrgCstFlw'];

            // Check Style Text CSS
            $tTextCssInCstFollow    = "";
            $nStatusChangPdtCstFlw  = '0';
            if( $tJR1PdtCode != $tJR1PdtCodeOrg && $tJR1PdtPsvType == '1'){
                $nStatusChangPdtCstFlw  = '1';
                $tTextCssInCstFollow    = "color: #2c82b6 !important;text-decoration: underline !important;";
            }
            // else{
            //     if(!empty($tJR1PdtCodeCstFlw) && !empty($tJR1PdtCodeOrgCstFlw)){
            //         if($tJR1PdtCodeCstFlw != $tJR1PdsCodeOrg){
            //             $nStatusChangPdtCstFlw  = '1';
            //             $tTextCssInCstFollow    = "color: #236b99 !important;text-decoration: underline !important;";
            //         }
            //     }
            // }

            // $tTextCssInCstFollow    = "";
            // $nStatusChangPdtCstFlw  = '0';
            // if($tJR1PdsCode != $tJR1PdsCodeOrg && $tJR1PdsPsvType == '1'){
            //     $nStatusChangPdtCstFlw  = '1';
            //     $tTextCssInCstFollow    = "color: #236b99 !important;text-decoration: underline !important;";
            // }else{
            //     if(!empty($tJR1PdtCodeCstFlw) && !empty($tJR1PdtCodeOrgCstFlw)){
            //         if($tJR1PdtCodeCstFlw != $tJR1PdsCodeOrg){
            //             $nStatusChangPdtCstFlw  = '1';
            //             $tTextCssInCstFollow    = "color: #236b99 !important;text-decoration: underline !important;";
            //         }
            //     }
            // }

            // Check Show Name Pdt
            // $tTextPdtName   = "";
            // if(!empty($tJR1PdtCodeCstFlw) && !empty($tJR1PdtCodeOrgCstFlw)){
            //     $tTextPdtName   = $tJR1PdtNameCstFlw;
            // }else{
            //     $tTextPdtName   = $tJR1PdsName;
            // }

            
        ?>
        <tr class="xWJR1TrItemDTSet"
            data-bchcode="<?=@$tJR1BchCode;?>"
            data-docno="<?=@$tJR1DocNo;?>"
            data-pdtcode="<?=@$tJR1PdtCode;?>"
            data-pdtcodeorg="<?=@$tJR1PdtCodeOrg;?>"
            data-srncode="<?=@$tJR1SrnCode;?>"
            data-seqcode="<?=@$tJR1SeqCode;?>"
        >
            <td class="text-center"><?=@$nKey+1;?></td>
            <td nowrap class="text-left xWJR1Pdt"  style="<?=@$tTextCssInCstFollow;?>">
                <div class="xWLabelPdt">
                    <?=@$tJR1PdtName?>
                </div>
                <div class="xWInputBrowsePdt xCNHide">
                    <div class="input-group">
                        <input 
                            type="text"
                            class="form-control xControlForm xCNHide"
                            id="oetJR1EditInLinePdtCode<?=@$tJR1PdtCode?>"
                            name="oetJR1EditInLinePdtCode<?=@$tJR1PdtCode?>"
                            maxlength="5"
                            value="<?=@$tJR1PdtCode?>"
                        >
                        <input 
                            type="text"
                            class="form-control xControlForm xWPointerEventNone"
                            id="oetJR1EditInLinePdtName<?=@$tJR1PdtCode?>"
                            name="oetJR1EditInLinePdtName<?=@$tJR1PdtCode?>"
                            maxlength="100"
                            placeholder="รายการสินค้าที่ต้องการเปลี่ยน"
                            value="<?=@$tJR1PdtName?>"
                            readonly
                        >
                        <span class="input-group-btn">
                            <button type="button" class="btn xCNBtnBrowseAddOn" onclick="JSxJR1EventBrowsePdtInline(this)">
                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
            </td>
            <td nowrap class="text-left"  style="<?=@$tTextCssInCstFollow;?>">
                <?php if($tJR1PdtPsvType == '1'): ?>
                    <?= language('document/jobrequest1/jobrequest1','tJR1PdtSetPsvType1');?>
                <?php elseif($tJR1PdtPsvType == '2'): ?>
                    <?= language('document/jobrequest1/jobrequest1','tJR1PdtSetPsvType2');?>
                <?php else: ?>
                    -
                <?php endif;?>
            </td>
            <td nowrap class="text-center">
                <?php if($tJR1PdtPsvType == '1'): ?>
                    <?php if($nStatusChangPdtCstFlw == '1'){ ?>
                        <img class="xCNIconTable xCNIconReback xCNIconRebackInLinePdt" onclick="JSnJR1RefreshPdtSetInTemp(this)">
                    <?php }else{ ?>
                        <img class="xCNIconTable xCNIconEdit xCNIconEditInLinePdt" onclick="JSnJR1EditPdtSetInTemp(this)" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>">
                    <?php }?>
                <?php elseif($tJR1PdtPsvType == '2'): ?>
                    <img class="xCNIconTable xCNIconDel xCNIconEditInLinePdt"  onclick="JSnJR1RemovePdtSetInTemp(this)" src="<?=base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                <?php else: ?>
                    -
                <?php endif;?>
                <!-- <img class="xCNIconTable xCNIconSave xCNIconSaveInLinePdt xCNHide" onclick="JSxJR1SaveEditInlinePdt(this)"> -->
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr><td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
<?php } ?>