<?php $nDecimal = FCNxHGetOptionDecimalShow(); ?>
<?php 
    if(isset($tRcvSeq) && !empty($tRcvSeq)){
        $tRcvSeqNo  = $tRcvSeq+1;
    }else{
        $tRcvSeqNo  = 1;
    }
?>
<?php if(isset($tRPPRcvCode) && !empty($tRPPRcvCode)): ?>
    <tr 
        id="otrRPPRowInputRCV<?=@$tRPPRcvCode;?>"
        class="otrInputRCV"
        data-agncode="<?=@$tAgnCode;?>"
        data-bchcode="<?=@$tBchCode;?>"
        data-docno="<?=@$tRPPDocNo;?>"
        data-rcvcode="<?=@$tRPPRcvCode;?>"
        data-rcvname="<?=@$tRPPRcvName;?>"
        data-seqno="<?=@$tRcvSeqNo;?>"
    >
        <td nowrap class="text-center"  style="vertical-align: middle;"><?=@$tRcvSeqNo;?></td>
        <td nowrap class="text-left"    style="vertical-align: middle;"><?=@$tRPPRcvName;?></td>
        <td nowrap class="text-left">
            <input 
                type="text"
                class="form-control"
                id="oetRPPStep1Point3XrcRefNo1<?=@$tRPPRcvCode;?>"
                name="oetRPPStep1Point3XrcRefNo1<?=@$tRPPRcvCode;?>"
            >
        </td>
        <td nowrap class="text-left">
            <div class="input-group">
                <input 
                    type="text" 
                    class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate"
                    id="oetRPPDateRcvRef<?=@$tRPPRcvCode;?>"
                    name="oetRPPDateRcvRef<?=@$tRPPRcvCode;?>"
                    data-validate-required="* กรุณากรอกข้อมูล"
                    value="<?=date('Y-m-d');?>"
                >
                <span class="input-group-btn">
                    <button id="obtRPPDateRcvRef" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                </span>
            </div>
        </td>
        <td nowrap class="text-left">
            <div class="input-group">
                <input 
                    type="text"
                    class="form-control xCNInputReadOnly xControlForm xCNHide"
                    id="oetRPPRcvBankCode<?=@$tRPPRcvCode;?>" 
                    name="oetRPPRcvBankCode<?=@$tRPPRcvCode;?>" 
                    maxlength="5" 
                >
                <input 
                    type="text"
                    class="form-control xControlForm xWPointerEventNone"
                    id="oetRPPRcvBankName<?=@$tRPPRcvCode;?>"
                    name="oetRPPRcvBankName<?=@$tRPPRcvCode;?>"
                    maxlength="100"
                    readonly=""
                >
                <span class="input-group-btn">
                    <button id="oimRPPBrowseBank<?=@$tRPPRcvCode;?>" type="button" class="btn xCNBtnBrowseAddOn">
                        <img src="http://localhost:8000/StoreBackFitAuto//application/modules/common/assets/images/icons/find-24.png">
                    </button>
                </span>
                <script type="text/javascript">
                    // Event CLick Browse Bank
                    $('#oimRPPBrowseBank<?=@$tRPPRcvCode;?>').unbind().click(function(){
                        let nStaSession  = JCNxFuncChkSessionExpired();
                        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                            var nLangEdits  = '<?=$this->session->userdata("tLangEdit")?>';

                            oOptionBrowseBank<?=@$tRPPRcvCode;?>    = {
                                Title   : ['bank/bank/bank','tBNKTitle'],
                                Table   : {Master:'TFNMBank',PK:'FTBnkCode'},
                                Join    : {
                                    Table   :	['TFNMBank_L'],
                                    On      :['TFNMBank_L.FTBnkCode = TFNMBank.FTBnkCode AND TFNMBank_L.FNLngID = '+nLangEdits,]
                                },
                                GrideView:{
                                    ColumnPathLang	    : 'bank/bank/bank',
                                    ColumnKeyLang	    : ['tBNKCode','tBNKName'],
                                    ColumnsSize         : ['15%','75%'],
                                    WidthModal          : 50,
                                    DataColumns		    : ['TFNMBank.FTBnkCode','TFNMBank_L.FTBnkName'],
                                    DataColumnsFormat   : ['',''],
                                    Perpage			    : 10,
                                    OrderBy			    : ['TFNMBank.FDCreateOn DESC'],
                                },
                                CallBack:{
                                    ReturnType  : 'S',
                                    Value       : ["oetRPPRcvBankCode<?=@$tRPPRcvCode;?>", "TFNMBank.FTBnkCode"],
                                    Text        : ["oetRPPRcvBankName<?=@$tRPPRcvCode;?>", "TFNMBank.FTBnkName"]
                                },
                            };
                            JCNxBrowseData('oOptionBrowseBank<?=@$tRPPRcvCode;?>');
                        }else{
                            JCNxShowMsgSessionExpired();
                        }
                    });
                </script>
            </div>
        </td>
        <td nowrap class="text-left">
            <input 
                type="text" 
                class="form-control" 
                id="oetRPPRcvBankBranch<?=@$tRPPRcvCode;?>"
                name="oetRPPRcvBankBranch<?=@$tRPPRcvCode;?>"
            >
        </td>
        <td nowrap class="text-left">
            <input
                type="text"
                class="form-control text-right xCNInputNumericWithDecimal"
                id="oetRPPRcvFAmt<?=@$tRPPRcvCode;?>"
                name="oetRPPRcvFAmt<?=@$tRPPRcvCode;?>"
                maxlength="12"
                value="<?=number_format(0.00,$nDecimal);?>"
                onblur="JSxRPPRcvCalcPaymentChg(this)"
            >
        </td>
        <td nowrap class="text-left">
            <input
                type="text"
                class="form-control text-right xCNInputNumericWithDecimal"
                id="oetRPPRcvXrcChg<?=@$tRPPRcvCode;?>"
                name="oetRPPRcvXrcChg<?=@$tRPPRcvCode;?>"
                maxlength="12"
                value="<?=number_format(0.00,$nDecimal);?>"
                onblur="JSxRPPRcvCalcPaymentChg(this)"
            >
        </td>
        <td nowrap class="text-left">
            <input
                type="text"
                class="form-control text-right xCNInputNumericWithDecimal"
                id="oetRPPRcvXrcNet<?=@$tRPPRcvCode;?>"
                name="oetRPPRcvXrcNet<?=@$tRPPRcvCode;?>"
                maxlength="12"
                value="<?=number_format(0.00,$nDecimal);?>"
                readonly
            >
        </td>
        <td nowrap class="text-center">
            <img class="xCNIconTable xCNIconDel" src="<?php echo  base_url('/application/modules/common/assets/images/icons/delete.png');?>" onclick="JSxRPPEventDelMNGRCV(this)">
        </td>
    </tr>
<?php endif; ?>