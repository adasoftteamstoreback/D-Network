<style>
    .xCNBTNActivity{
        float           : right;
        margin-left     : 10px;
    }
</style>

<?php
    $tDetailPDT             = $aDetailPDT[$nNumberArray];
    $tPDTName               = $tDetailPDT->PDTName;             //ชื่อสินค้า
    $tPDTCode               = $tDetailPDT->PDTCode;             //รหัสสินค้า
    $tBarCode               = $tDetailPDT->Barcode;             //ชื่อสินค้า
    $nAll                   = $nAll;                            //จำนวนสินค้า ที่เลือกมาทั้งหมด
    $tNameNextFunc          = $tNameNextFunc;                   //ชื่อฟังก์ชั่นสำหรับ nextfunct
    $tPDTType               = $tPDTType;                        //สินค้าเป็นประเภทไหน 
    $tEventType             = $tEventType;                      //รูปแบบของการเลือกข้อมูล insert (pageadd) หรือ update (pageupdate)  
    $oOptionForLot          = $oOptionForLot;                   //การเพิ่มสินค้าแฟชั่นแบบ พิเศษ
?>

<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <span><b><?= language('common/main/main', 'tModalcodePDT') ?> : </b></span><?=$tPDTCode?>
                <?php if($nAll != 1){//ถ้ามีสินค้าแค่ตัวเดียวไม่ต้องโชว์สรุปว่ามีกี่รายการ ?>
                    <span style="float: right;"><b> <?= language('common/main/main', 'tPDTNumber') ?> <?=$nNumberArray+1?> <?= language('common/main/main', 'tCommonAllRecord') ?> <?=$nAll?> <?= language('common/main/main', 'tCommonlabelShow') ?> </b></span>
                <?php } ?><br>                
                <span><b><?= language('common/main/main', 'tModalnamePDT') ?> : </b></span><?=$tPDTName?><br>
                <span><b><?= language('common/main/main', 'tModalbarcodePDT') ?> : </b></span><?=$tBarCode?>
            </div>
        </div>

        <!--ส่วนตาราง-->
        <div class="row" style="margin-top: 10px;">
            <div class="col-lg-12">
               <div><hr></div>
               <div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span>จำนวนรับเข้า</label>
                    <input type="text" class="form-control text-right" maxlength="4" id="oetBrowsePDTLotInputReceive" name="oetBrowsePDTLotInputReceive" autocomplete="off" placeholder="0" value="1">
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('product/product/product','tPDTLotBatchNo');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetBrowsePDTLotNo" name="oetBrowsePDTLotNo" value="">
                        <input type="text" class="form-control xWPointerEventNone" id="oetBrowsePDTLotName" name="oetBrowsePDTLotName" value="" readonly>
                        <span class="input-group-btn">
                            <button id="obtBrowsePDTLot" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('product/product/product','tPDTLotDateMFG');?></label>
                    <div class="input-group">
                        <input type='text' class='form-control text-center xWDatePicker' maxlength="4" id="oetBrowsePDTLotStartDate" name="oetBrowsePDTLotStartDate" autocomplete="off" placeholder="YYYY-MM-DD">
                        <span class="input-group-btn">
                            <button id="obtBrowseStartDate" type="button" class="btn xCNBtnDateTime">
                                <img class="xCNIconFind">
                            </button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('product/product/product','tPDTLotDateEXP');?></label>
                    <div class="input-group">
                        <input type='text' class='form-control text-center xWDatePicker' maxlength="4" id="oetBrowsePDTLotExpireDate" name="oetBrowsePDTLotExpireDate" autocomplete="off" placeholder="YYYY-MM-DD">
                        <span class="input-group-btn">
                            <button id="obtBrowseExpireDate" type="button" class="btn xCNBtnDateTime">
                                <img class="xCNIconFind">
                            </button>
                        </span>
                    </div>
                </div>


            </div>
        </div>

        <!--ส่วนปุ่มล่างสุด-->
        <div class="row">
            <div class="col-lg-12">
                <div><hr></div>
                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNBTNActivity" onclick="JSxConfirmPDT_lot('<?=$tPDTCode?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn xCNBTNActivity" onclick="JSxCancelPDT_lot('<?=$tPDTCode?>')"><?=language('common/main/main', 'tCancel')?></button>
            </div>
        </div>
    </div>
</div>

<script>    

    //วันที่
    $(".xWDatePicker").datepicker({
        format          		: 'yyyy-mm-dd',
        todayHighlight  		: true,
        enableOnReadonly		: false,
        disableTouchKeyboard 	: true,
        autoclose       		: true,
        orientation     		: 'bottom' 
    });

    //วันที่
    $('#obtBrowseExpireDate').click(function() {
        $('#oetBrowsePDTLotExpireDate').datepicker('show')
    });

    //วันที่
    $('#obtBrowseStartDate').click(function() {
        $('#oetBrowsePDTLotStartDate').datepicker('show')
    });

    //กดปิดที่ modal ยืนยันว่าต้องกรอก
    $('#obtCantKeyISNull').click(function() {
        $('#odvModalCantKeyISNull').modal('hide');
    });                    

    //กดปิดที่ modal
    $('#obtCancelPDTLot').click(function() {
        $('#odvModalCancelLot').modal('hide');
    });           
    
    //เลือกล็อต
    $('#obtBrowsePDTLot').click(function(){JCNxBrowseData('oBrowseLot');});
    var tConditionLOT = " AND TCNMLot.FTLotStaUse IN ('1') ";
    var oBrowseLot = {
        Title   : ['product/product/product','LOT/BATCH'],
        Table   : {Master:'TCNMLot',PK:'FTLotNo'},
        Where :{
            Condition : [tConditionLOT]
        },
        GrideView:{
            ColumnPathLang	: 'product/product/product',
            ColumnKeyLang	: ['tPDTLotNo','tPDTLotBatchNo'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMLot.FTLotNo','TCNMLot.FTLotBatchNo'],
            DataColumnsFormat : ['',''],
            Perpage			: 10,
            OrderBy			: ['TCNMLot.FTLotNo DESC'],
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetBrowsePDTLotNo","TCNMLot.FTLotNo"],
            Text		: ["oetBrowsePDTLotName","TCNMLot.FTLotBatchNo"],
        }
    }

    //หน้าต่างยกเลิก
    function JSxCancelPDT_lot(ptPDTCode){
        if('<?=$tEventType?>' == 'insert'){ //ถ้ากดยกเลิกในรูปแบบหน้าจอ insert ต้องส่งค่ากลับไปให้ลบ
            //modal โชว์
            $('#odvModalCancelLot').modal('show');

            //กดยืนยันหลังจาก modal 
            $('#obtConfirmPDTLot').off();
            $('#obtConfirmPDTLot').click(function() {

                //ซ่อน modal ยกเลิกไป
                $('#odvModalCancelLot').modal('hide');

                if('<?=$nAll?>' > 1){ //มีสินค้าตัวถัดไปรอให้กรอกอยู่
                    if(<?=$nNumberArray+1?> == <?=$nAll?>){ //ถ้าเป็นตัวสุดท้ายก็ออกจากลูป
                        setTimeout(function(){ 
                            JSxCloseModalBrowse_lot();
                        }, 1000);
                    }else{ //สินค้าตัวถัดไป
                        JSxNextPDT_lot();
                    }
                }else{
                    setTimeout(function(){ 
                        JSxCloseModalBrowse_lot();
                    }, 1000);
                }

                //แพ็คข้อมูลส่งกับไป nextfunc
                var aNewResult  = JSON.stringify({
                    'tType'         : 'delete' , 
                    'nStaLastSeq'   : '<?=($nNumberArray+1 == $nAll) ? 1 : 0; ?>', //สินค้าแฟชั่นตัวสุดท้าย 1:ตัวสุดท้าย 0:ไม่ใช่ตัวสุดท้าย
                    'aResult'       :  { 'tPDTCode' : ptPDTCode } , 
                    'tRemark'       : '[dev] เอารหัสสินค้านี้ไปลบใน TCNTDocDTTmp และใน Grid ให้ด้วยครับ' 
                });

                return window['<?=$tNameNextFunc?>'](aNewResult);
            });
        }else if('<?=$tEventType?>' == 'update'){ //ถ้ากดยกเลิกในรูปแบบหน้าจอ update ไม่ต้องทำอะไร
            //ซ่อน modal ยกเลิกไป
            setTimeout(function(){ 
                JSxCloseModalBrowse_lot();
            }, 1000);
        }
    }

    //หน้าต่างยืนยัน 
    function JSxConfirmPDT_lot(ptPDTCode){
        //แพ็คข้อมูล
        var aResult = [];
        var aFashion = { 
            'tPDTCode'      : ptPDTCode , 
            'nReceive'      : $('#oetBrowsePDTLotInputReceive').val() , 
            'tLotBatchNo'   : $('#oetBrowsePDTLotNo').val() , 
            'dStartDate'    : $('#oetBrowsePDTLotStartDate').val() ,
            'dExpireDate'   : $('#oetBrowsePDTLotExpireDate').val()
        }
        aResult.push(aFashion);

        if(<?=$nAll?> > 1){ //มีสินค้าตัวถัดไปรอให้กรอกอยู่
            if(<?=$nNumberArray+1?> == <?=$nAll?>){ //ถ้าเป็นตัวสุดท้ายก็ออกจากลูป
                setTimeout(function(){ 
                    JSxCloseModalBrowse_lot();
                }, 1000);
            }else{ //สินค้าตัวถัดไป
                JSxNextPDT_lot();
            }
        }else{
            setTimeout(function(){ 
                JSxCloseModalBrowse_lot();
            }, 1000);
        }

        //แพ็คข้อมูลส่งกับไป nextfunc
        var aNewResult  = JSON.stringify({
            'tType'         : 'confirm' , 
            'nStaLastSeq'   : '<?=($nNumberArray+1 == $nAll) ? 1 : 0; ?>', //สินค้าล็อตสุดท้าย 1:ตัวสุดท้าย 0:ไม่ใช่ตัวสุดท้าย
            'aResult'       : aResult , 
            'tRemark'       : '[dev] เอาข้อมูลไปลงตาราง TCNTDocDT ระบบจะส่งสินค้าลูกกลับไป เฉพาะตัวที่ระบุจำนวนให้เท่านั้น'
        });
        return window['<?=$tNameNextFunc?>'](aNewResult);
    }

    //โหลดสินค้าตัวถัดไป
    function JSxNextPDT_lot(){
        $.ajax({
            type    : "POST",
            url     : 'LoadViewProductLot',
            data    : {
                        'aData'             : '<?=json_encode($aDetailPDT)?>' , 
                        'tPDTType'          : '<?=$tPDTType?>' , 
                        'tEventType'        : '<?=$tEventType?>' ,
                        'nNumber'           : '<?=$nNumberArray+1?>' , 
                        'tNameNextFunc'     : '<?=$tNameNextFunc?>',
                        'oOptionForLot'     : JSON.parse('<?=json_encode($oOptionForLot)?>')
                    },
            cache   : false,
            timeout : 0,
            success: function(tResult) {
                //ตัว รีโหลดเวลาโหลดสินค้า
                var tImage = "<img src='<?= base_url() ?>application/modules/common/assets/images/ada.loading.gif' class='xWImgLoading'>";
                $('#odvModalLotBodyPDT').html(tImage);
                $('#odvModalLotBodyPDT').css('height', '200px');

                setTimeout(function(){ 
                    var aDataReturn = JSON.parse(tResult);
                    $('#odvModalLotBodyPDT').html(aDataReturn['tHTML']); 
                    $('#odvModalLotBodyPDT').css('height', 'auto');
                }, 500);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                JCNxCloseLoading();
            }
        });
    }
    
</script>