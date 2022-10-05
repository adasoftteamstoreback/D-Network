
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
    <div class="table-responsive">
        <table id="otbASTDocPdtAdvTable" class="table table-striped xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <?php if(empty($tASTStaApv ) && $tASTStaDoc  != 3):?>
                        <th class="xCNHideWhenCancelOrApprove">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
                    <?php endif; ?>
                    <th><?php echo language('document/adjuststock/adjuststock','ลำดับ')?></th>
                    <?php foreach($aColumnShow as $HeaderColKey => $HeaderColVal):?>
                        <?php $tColumnNameHead = $HeaderColVal->FTShwFedShw; ?>
                        <?php 
                        if(in_array($tColumnNameHead,['FCAfterCount']) && (empty($tASTStaApv) && $tASTStaDoc != 3)):
                        ?>

                        <?php 
                        else: 
                        ?>
                            <th class="<?php echo $HeaderColVal->FTShwFedShw?>" nowrap title="<?php echo iconv_substr($HeaderColVal->FTShwNameUsr, 0,30,"UTF-8");?>" >
                                <?php echo iconv_substr($HeaderColVal->FTShwNameUsr, 0,30, "UTF-8");?>
                            </th>
                        <?php 
                        endif;
                        ?>
                    <?php endforeach;?>
                    <?php if((@$tASTStaApv == '') && @$tASTStaDoc != 3):?>
                        <th><?php echo language('document/adjuststock/adjuststock','tASTTBDelete')?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $nNumSeq        = 0;
                ?>
                <?php if($aDataDTList['rtCode'] == 1):?>
                    <?php foreach($aDataDTList['raItems'] as $DataTableKey => $aDataTableVal):?>

                        <tr class="text-center xCNTextDetail2 xCNDOCPdtItem nItem<?php echo $nNumSeq;?>" data-index="<?php echo $DataTableKey+1;?>" data-seqno="<?php echo $aDataTableVal['FNXtdSeqNoDel'];?>">
                            <?php if(empty($tASTStaApv ) && $tASTStaDoc  != 3):?>
                            <td class="text-center">
                                <label class="fancy-checkbox">
                                    <input id="ocbListItem<?php echo $aDataTableVal['FNXtdSeqNo']?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                    <span>&nbsp;</span>
                                </label>
                            </td>
                            <?php endif; ?>
                            0<td class="text-center"><?=$DataTableKey+1;?></td>
                            <?php foreach($aColumnShow as $DataKey => $DataVal): ?>

                                <?php
                                    $tColumnName        = $DataVal->FTShwFedShw;
                                    $nColWidth          = $DataVal->FNShwColWidth;
                                    $tColumnDataType    = substr($tColumnName,0,2);
                                    if($tColumnDataType == 'FC'){
                                        $tMaxlength     = '11';
                                        $tAlignFormat   = 'text-right';
                                        $tDataCol       =  $aDataTableVal[$tColumnName] != '' ? number_format($aDataTableVal[$tColumnName],2) : number_format(0,0);
                                        $InputType      = 'text';
                                        $tValidateType  = '';
                                    }
                                    if($tColumnDataType == 'FN'){
                                        $tMaxlength     = '';
                                        $tAlignFormat   = 'text-right';
                                        $tDataCol       = $aDataTableVal[$tColumnName] != '' ? number_format($aDataTableVal[$tColumnName],0) : number_format(0,0);
                                        $InputType      = 'number';
                                        $tValidateType  = '';
                                    }
                                    if($tColumnDataType == 'FD'){
                                        $tMaxlength     = '';
                                        $tAlignFormat   = 'text-left';
                                        $tDataCol       = date('Y-m-d H:i:s');
                                        $InputType      = 'text';
                                        $tValidateType  = '';
                                    }
                                    if($tColumnDataType == 'FT'){
                                        $tMaxlength     = '';
                                        $tAlignFormat   = 'text-left';
                                        $tDataCol       = $aDataTableVal[$tColumnName];
                                        $InputType      = 'text';
                                        $tValidateType  = '';
                                    }
                                ?>
                                <?php 
                                if(in_array($tColumnName,['FCAfterCount']) && (empty($tASTStaApv) && $tASTStaDoc != 3)):
                                ?>

                                <?php
                                 else:
                                 ?>
                                    <td nowrap class="<?php echo $tAlignFormat?>">
                                        <?php if($DataVal->FTShwStaAlwEdit == 1 && (empty($tASTStaApv) && $tASTStaDoc != 3)):?>
                                            <label class="xCNHide xCNPdtFont xWShowInLine<?php echo $aDataTableVal['FNXtdSeqNo'];?> xWShowValue<?php echo $tColumnName;?><?php echo $aDataTableVal['FNXtdSeqNo'];?>">
                                                <?php echo  $tDataCol != '' ? "".$tDataCol : '-'; ?>
                                            </label>
                                            <div class="xWEditInLine<?php echo $aDataTableVal['FNXtdSeqNo']?>">
                                                <input
                                                    type="<?php echo $InputType?>"
                                                    class="form-control text-right xCNInputNumericWithoutDecimal xWASTDisabledOnApv xCNPdtEditInLine <?php echo 'xW'.$tColumnName; ?> <?php echo $tValidateType;?>"
                                                    id="ohdAST<?php echo $tColumnName;?><?php echo $aDataTableVal['FNXtdSeqNo'];?>"
                                                    name="ohdAST<?php echo $tColumnName;?><?php echo $aDataTableVal['FNXtdSeqNo'];?>"
                                                    maxlength="<?php echo $tMaxlength;?>"
                                                    value="<?php echo  $tDataCol != '' ? "".$tDataCol : '-'; ?>"
                                                    data-field="<?php echo $tColumnName;?>"
                                                    style=" background: rgb(249, 249, 249);
                                                            box-shadow: 0px 0px 0px inset;
                                                            border-top: 0px !important;
                                                            border-left: 0px !important;
                                                            border-right: 0px !important;
                                                            padding: 0px !important;
                                                          "
                                                    autocomplete="off"
                                                >
                                            </div>
                                        <?php else:?>
                                            <label class="xCNPdtFont xWShowInLine xWShowValue<?php echo $tColumnName?><?php echo $DataTableKey+1?>"><?php echo $tDataCol?></label>
                                        <?php endif;?>
                                    </td>
                                <?php 
                                endif;
                                ?>
                            <?php endforeach; ?>
                            <?php if(empty($tASTStaApv ) && $tASTStaDoc  != 3):?>
                                <td nowrap class="text-center">
                                    <img class="xCNIconTable xCNIconDel" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" title="Remove" onclick="JSxASTRemoveRowDTTmp(this)">
                                </td>
                            <?php endif;?>
                        </tr>
                    <?php $nNumSeq++;?>
                    <?php endforeach?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2 xWTextNotfoundDataTablePdt' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData');?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

<div id="odvASTModalDelPdtDTTemp" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospASTConfirmDelPdtDTTemp"> - </span>
                <input type='hidden' id="ohdASTConfirmSeqDelete">
                <input type='hidden' id="ohdASTConfirmPdtDelete">
                <input type='hidden' id="ohdASTConfirmPunDelete">
                <input type='hidden' id="ohdASTConfirmDocDelete">
            </div>
            <div class="modal-footer">
                <button id="osmASTConfirmPdtDTTemp" type="button" class="btn xCNBTNPrimery"  onClick="JSoASTPdtDelChoose()"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>
<?php include("script/jAdjustStockPdtAdvTableData.php");?>
<!-- ========================================================================================================================================================================================================================= -->
