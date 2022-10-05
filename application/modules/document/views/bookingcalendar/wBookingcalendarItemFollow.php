<div class="row" style="margin-top: 10px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped" id="otbBKPdtFollowTemp">
                <thead>
                    <tr>
                        <th class="text-center xCNTextBold" style="width:5%;"><?= language('common/main/main', 'tModalAdvNo') ?></th>
                        <th class="text-center xCNTextBold" ><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBFollow_pdtname') ?></th>
                        <th class="text-center xCNTextBold" style="width:15%;">
                            <?= language('document/bookingcalendar/bookingcalendar', 'tBKTBFollow_datelast') ?><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBFollow_datelastTime') ?>
                        </th>
                        <th class="text-center xCNTextBold" style="width:20%;">
                            <?= language('document/bookingcalendar/bookingcalendar', 'tBKTBFollow_date') ?><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBFollow_datedeal') ?>
                        </th>
                        <th class="text-center xCNTextBold xCNConfirmFollow" style="width:10%;"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBFollow_Insert') ?></th>
                        <th class="text-center xCNTextBold xCNCloseFollow" style="width:10%;"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBFollow_StatusFollow') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aItemFollow['rtCode'] == 1 ) : ?>
                        <?php foreach($aItemFollow['raItems'] AS $key=>$aValue) { ?>   
                            <?php 
                                $aOptionParam = array(
                                    'PDTCode'    => $aValue['FTPdtCode'],
                                    'PUNCode'    => $aValue['FTPunCode'],
                                    'Barcode'    => $aValue['FTBarCode'],
                                    'PUNName'    => $aValue['FTPunName'],
                                    'PDTName'    => $aValue['FTPdtName'],
                                    'SetOrSN'    => $aValue['FTPdtSetOrSN'], 
                                );
                                $aOption        = json_encode($aOptionParam);
                                $tEventClick    = 'JSxChooseItemFollow('.$aOption.')';
                            ?>    
                            <tr class="text-center xCNPDTFollow<?=$key?> xCNPDTFollowItem<?=$aValue['FTPdtCode']?>" >
                                <td class="text-center"><?=$key+1?></td>
                                <td class="text-left"><?=$aValue['FTPdtName']?></td>
                                <td class="text-center"><?=date('d/m/Y', strtotime($aValue['FDFlwLastDate']))?></td>
                                <td class="text-center"><?=date('d/m/Y', strtotime($aValue['FDFlwDateForcast']))?></td>
                                <td class="text-center xCNConfirmFollow">
                                    <button class="btn btn-outline-primary" onClick='<?=$tEventClick?>' type="button" style="border-color: #3995ff; padding: 2px 30px;">
                                        <span style="color:#3995ff;"><?= language('common/main/main', 'tAdd') ?></span>
                                    </button>
                                </td>
                                <td class="text-center xCNCloseFollow">
                                    <?php 
                                        if($aValue['FTFlwStaBook'] == 4){ //เลิกติดตามสินค้า
                                            $tDisabledButton = 'disabled';
                                            $tTextInButton = "เลิกติดตาม";
                                        }else{
                                            $tDisabledButton = '';
                                            $tTextInButton = language('common/main/main', 'tModalCancel');
                                        }
                                    ?>
                                    <button class="btn btn-outline-danger" <?=$tDisabledButton?> onclick="JSxCloseFollow('<?=$aValue['FTPdtCode']?>','<?=$key?>')" type="button" style="border-color: #e74c3c; padding: 2px 30px;">
                                        <span style="color:#e74c3c;"><?= $tTextInButton ?></span>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

    //Control ปุ่ม
    if('<?=@$tStaDoc?>' == '3' || '<?=@$tStaPrcDoc?>' == '2'){ //เอกสารยกเลิก + เอกสารนัดหมาย และยืนยันเเล้ว
        $('.xCNConfirmFollow , .xCNCloseFollow').hide();
    }

    //ยกเลิกการติดตาม
    function JSxCloseFollow(ptPDTCode,pnKey){

        //ซ่อนบราว์หลัก
        // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

        setTimeout(function(){ 
            $('#odvBKModalCloseFollowPDT').modal("show")
        }, 500);

        //กดยืนยัน
        $('.xCNClickCloseFollowPDT').on('click',function(){

            if($('#oetBookReasonFlwCode').val() == '' || $('#oetBookReasonFlwCode').val() == null){
                $('#oetBookReasonFlwName').focus();
                return;
            }else{
                setTimeout(function(){ 
                    $('#odvBKModalCloseFollowPDT').modal('hide');
                    // $('#odvModalPopupBookingCalendar').modal('show');
                }, 500);
            }

            $.ajax({
                type    : "POST",
                url     : "docBookingCalendarDeleteFollow",
                data    : {"tPDTCode"  : ptPDTCode , 'tCarReg' : $('#oetBKCarCode').val() , 'tReason' : $('#oetBookReasonFlwCode').val() },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    //โหลดข้อมูล follow
                    JSxLoadTableHistoryService();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });

    }

    //เลือกสินค้าติดตามเข้า DT
    function JSxChooseItemFollow(poObject){

        //Pack ข้อมูล
        aNewData = [];
        aItem    = {
            'packData' : {
                PDTCode : poObject.PDTCode,
                PUNCode : poObject.PUNCode,
                Barcode : poObject.Barcode,
                PUNName : poObject.PUNName,
                PDTName : poObject.PDTName,
                SetOrSN : poObject.SetOrSN
            }
        }
        aNewData.push(aItem);
        var aNewReturn  = JSON.stringify(aNewData);

        //โหลดข้อมูลหน้าจอสินค้า
        FSvBKNextFuncB4ToTemp(aNewReturn);

        //ลบข้อมูล
        $('.xCNPDTFollowItem'+poObject.PDTCode).remove();
        if($('#otbBKPdtFollowTemp > tbody > tr').length == 0){
            $('#otbBKPdtFollowTemp > tbody').append("<tr><td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>");
        }
    }
</script>