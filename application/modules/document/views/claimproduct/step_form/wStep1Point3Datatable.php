
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbCLMStep1Point3DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','หน่วย')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ระยะทางรับประกัน')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','ระยะเวลารับประกัน')?></th>
                    <th nowrap class="xCNTextBold" ><?=language('document/invoice/invoice','เงื่อนไขรับประกัน')?></th>
                    <th nowrap class="xCNTextBold" style="width:150px;"><?=language('document/invoice/invoice','หมายเหตุ')?></th>
                    <th nowrap class="xCNTextBold" style="width:150px;"><?=language('document/invoice/invoice','แจ้งเคลมผู้จำหน่าย')?></th>
                    <th nowrap class="xCNTextBold" style="width:100px;"><?=language('document/invoice/invoice','วันที่เเจ้ง')?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($aDataList['rtCode'] == 1 ):?>
                <?php 
                    if(FCNnHSizeOf($aDataList['raItems'])!=0){
                        foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                            <?php $nKey = $aDataTableVal['FNPcdSeqNo']; ?>
                            <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?=$aDataTableVal['FTPcdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>"
                                data-key="<?=$nKey?>"
                                data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>"
                                data-seqno="<?=$nKey?>"
                                data-qty="<?=$aDataTableVal['FCPcdQty'];?>" >
                                <td nowrap style="text-align:center"><?=$nKey?></td>
                                <td nowrap><?=$aDataTableVal['FTPdtCode'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPcdPdtName'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPcdBarCode'];?></td>
                                <td nowrap><?=$aDataTableVal['FTPunName'];?></td>
                                <td nowrap class="text-right"><?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?></td>
                                <td nowrap class="text-center"><?=($aDataTableVal['FCPsvWaDistance'] == '' ) ? '-' : number_format($aDataTableVal['FCPsvWaDistance'],2);?></td>
                                <td nowrap class="text-center"><?=($aDataTableVal['FNPsvWaQtyDay'] == '' ) ? '-' : $aDataTableVal['FNPsvWaQtyDay'] . ' วัน' ;?></td>
                                <td nowrap class="text-left"><?=($aDataTableVal['FTPsvWaCond'] == '' ) ? '-' : $aDataTableVal['FTPsvWaCond'];?></td>
                                <td>
                                    <div class="xWEditInLine<?=$nKey?>">
                                        <input type="text" style="width:150px;" class="xCNRemarkStep1Point3 form-control xCNPdtEditInLine text-left xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdRmkStep1Point3<?=$nKey?>" name="ohdRmkStep1Point3<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="50" value="<?=$aDataTableVal['FTPcdRmk']?>" autocomplete="off">
                                    </div>
                                </td>
                                <td>
                                    <div >

                                        <?php 
                                            if($aDataTableVal['FTSplName'] == '' || $aDataTableVal['FTSplName'] == null){ //ขาเพิ่ม
                                                //เอาผู้จำหน่ายจากบิลขาย
                                                if($aItemBySPL['raItems'][0]['SPL_PI_Code'] != '' || $aItemBySPL['raItems'][0]['SPL_PI_Code'] != null){
                                                    $tSPLCode = $aItemBySPL['raItems'][0]['SPL_PI_Code'];
                                                    $tSPLName = $aItemBySPL['raItems'][0]['SPL_PI_Name'];
                                                }else if($aItemBySPL['raItems'][0]['SPL_PDT_Code'] != '' || $aItemBySPL['raItems'][0]['SPL_PDT_Code'] != null){ //เอาผู้จำหน่ายจากบาร์โค๊ด
                                                    $tSPLCode = $aItemBySPL['raItems'][0]['SPL_PDT_Code'];   
                                                    $tSPLName = $aItemBySPL['raItems'][0]['SPL_PDT_Name'];  
                                                }else{
                                                    $tSPLCode = '';
                                                    $tSPLName = '';
                                                }
                                            }else{ //ขาแก้ไข
                                                $tSPLCode = $aDataTableVal['FTSplCode'];
                                                $tSPLName = $aDataTableVal['FTSplName'];
                                            }
                                        ?>
                                        <div class="">
                                            <div class="input-group">
                                                <input  type="text" class="xCNSPL form-control xControlForm xCNHide" id="ohdSplCode<?=$nKey?>" name="ohdSplCode<?=$nKey?>" maxlength="50" value="<?=$tSPLCode?>">
                                                <input
                                                    type="text" 
                                                    class="form-control xControlForm xWPointerEventNone" 
                                                    id="ohdSplName<?=$nKey?>" name="ohdSplName<?=$nKey?>"
                                                    style="width:150px;"
                                                    maxlength="100"
                                                    placeholder="กรุณาเลือกผู้จำหน่าย" 
                                                    value="<?=$tSPLName?>" 
                                                    readonly
                                                >
                                                <span class="input-group-btn">
                                                    <button type="button" data-seq="<?=$nKey?>" class="btn xCNBtnBrowseAddOn xCNCLMBrowseSPL">
                                                        <img src="<?=base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>
                                            <div class="input-group">
                                                <?php 
                                                    if($aDataTableVal['DateReq'] == '' || $aDataTableVal['DateReq'] == null){
                                                        $tDateReq = date('Y-m-d');
                                                    }else{
                                                        $tDateReq = $aDataTableVal['DateReq'];
                                                    }
                                                ?>
                                                <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" style="width:100px;" id="ohdSplDate<?=$nKey?>" name="ohdSplDate<?=$nKey?>" data-seq="<?=$nKey?>" placeholder="YYYY-MM-DD" value="<?=$tDateReq?>" autocomplete="off" onchange="JSxStep1Point3ChangeDate(this.value,'<?=$nKey?>')">
                                                <span class="input-group-btn">
                                                    <button type="button" data-hiddenKeySPLDate="<?=$nKey?>" class="btn xCNBtnDateTime xCNTimeSPLDate">
                                                        <img src="<?=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $('.xCNDatePicker').datepicker({
            format                  : "yyyy-mm-dd",
            todayHighlight          : true,
            enableOnReadonly        : false,
            disableTouchKeyboard    : true,
            autoclose               : true
        });

        $('.xCNTimeSPLDate').unbind().click(function(){
            var nKey = $(this).attr('data-hiddenKeySPLDate');
            $('#ohdSplDate'+nKey).datepicker('show');
        });
        
    });

    //เลือกผู้จำหน่าย
    $('.xCNCLMBrowseSPL').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            var nSeq                  = $(this).attr('data-seq');
            window.nKeepSPLKey        = nSeq;
            window.oBrowseSplOption   = undefined;
            oBrowseSplOption          = oSplOption({
                'tParamsAgnCode'    : '<?=$this->session->userdata("tSesUsrAgnCode")?>',
                'tReturnInputCode'  : 'ohdSplCode'+nSeq,
                'tReturnInputName'  : 'ohdSplName'+nSeq,
                'aArgReturn'        : ['FTSplCode', 'FTSplName']
            });
            JCNxBrowseData('oBrowseSplOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oSplOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tParamsAgnCode      = poDataFnc.tParamsAgnCode;
        
        if( tParamsAgnCode != "" ){
            tWhereAgency = " AND ( TCNMSpl.FTAgnCode = '"+tParamsAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') = '' ) ";
        }else{
            tWhereAgency = "";
        }

        var oOptionReturn       = {
            Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
            Table   : {Master:'TCNMSpl', PK:'FTSplCode'},
            Join    : {
                Table: ['TCNMSpl_L', 'TCNMSplCredit'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode',
                ]
            },
            Where:{
                Condition : ["AND TCNMSpl.FTSplStaActive = '1' "]
            },
            GrideView:{
                ColumnPathLang      : 'supplier/supplier/supplier',
                ColumnKeyLang       : ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSpl.FTSplStaVATInOrEx','TCNMSplCredit.FNSplCrTerm'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2, 3],
                Perpage             : 10,
                OrderBy             : ['TCNMSpl_L.FTSplName ASC']
            },
            CallBack:{
                ReturnType      : 'S',
                Value           : [tInputReturnCode,"TCNMSpl.FTSplCode"],
                Text            : [tInputReturnName,"TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName        : 'JSxCLMUpdateSPLCodeInTemp',
                ArgReturn       : ['FTSplCode', 'FTSplName']
            },
        };
        return oOptionReturn;
    }
    
    //อัพเดทผู้จำหน่าย
    function JSxCLMUpdateSPLCodeInTemp(poDataNextFunc){
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            var tSPLCode         = aData[0];

            //อัพเดท
            JSxStep1Point3UpdateDTTmp(nKeepSPLKey , tSPLCode , 'SPLClaim');
        }
    }

    //อัพเดทหมายเหตุ
    $('.xCNRemarkStep1Point3').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var nSeq    = $(this).attr('data-seq');
            var tRmk    = $('#ohdRmkStep1Point3'+nSeq).val();
            nNextTab    = parseInt(nSeq)+1;
            $('.xWValueEditInLine'+nNextTab).focus().select();

            //อัพเดท
            JSxStep1Point3UpdateDTTmp(nSeq , tRmk , 'RmkClaim');
        }
    });

    //อัพเดทวันที่แจ้ง
    function JSxStep1Point3ChangeDate(pnValue,pnKey){
        //อัพเดท
        if(pnValue == '' || pnValue == null){
            
        }else{
            JSxStep1Point3UpdateDTTmp(pnKey,pnValue,'DateClaim');
        }
    }

    //Update ข้อมูล หมายเหตุ , ผู้จำหน่าย , วันที่แจ้ง
    function JSxStep1Point3UpdateDTTmp(pnSeq,ptValue,ptType){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep1Point3UpdateSPLAndDate",
            data    : {
                'tCLMDocNo'         : tCLMDocNo,
                'nSeq'              : pnSeq,
                'tValueUpdate'      : ptValue,
                'tTypeUpdate'       : ptType
            },
            catch   : false,
            timeout : 0,
            success : function (oResult){ /*console.log(oResult);*/ },
            error   : function (jqXHR, textStatus, errorThrown) { }
        });
    }
</script>