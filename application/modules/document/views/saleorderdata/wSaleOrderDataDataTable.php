<style>
.sticky-col {
  position: sticky;
  right: 0;
  background-color: #2c81b6;
}

.sticky-col2 {
  position: sticky;
  right: 0;
  background-color: white;
}

.alert-container {
  position: fixed;
  bottom: 5px;
  right: 0%;
  width: 20%;
  background-color: rgba(255,255,255,.85);
  border-radius: 4px;
  box-shadow: 0 .25rem .75remrgba(0,0,0,.1);
}
.table {
  border-collapse: separate;
}


</style>
<?php
if ($aDataList['rtCode'] == '1') {
    $nCurrentPage   = $aDataList['rnCurrentPage'];
} else {
    $nCurrentPage = '1';
}
?>
<div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class='row'>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="width:25%;float:left;">
        <i class="fa fa-chevron-left f-s-14 t-plus-1" style='cursor : pointer' id="ToLeft"></i>
        <!-- <button id="ToLeft" class="btn xCNBTNPrimery" style="width:40%;text-align: left;"><?php echo language('document/saleorderdata/saleorderdata', 'Scroll Left'); ?></button> -->
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="width:25%;float:left;">
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="width:25%;float:left;">
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="width:25%;float:left;text-align: right;">
            <!-- <button id="Toright" class="btn xCNBTNPrimery" style="width:40%;"><?php echo language('document/saleorderdata/saleorderdata', 'Scroll Right'); ?></button> -->
            <i class="fa fa-chevron-right f-s-14 t-plus-1" style='cursor : pointer' id="Toright"></i>
        </div>
    </div>
</div>

</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table" id='SODTable'>
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold"><?= language('document/saleorderdata/saleorderdata', 'tSODDocNo') ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?= language('document/saleorderdata/saleorderdata', 'tSODDocDate') ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?= language('document/saleorderdata/saleorderdata', 'tSODCustomer') ?></th>
                        <th nowrap class="xCNTextBold" width="5%"><?= language('document/saleorderdata/saleorderdata', 'tSODDocRef') ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?= language('document/saleorderdata/saleorderdata', 'tSODDocRefDate') ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?= language('document/saleorderdata/saleorderdata', 'tSODDocStatus') ?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?= language('document/saleorderdata/saleorderdata', 'tSODPCKNo') ?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?= language('document/saleorderdata/saleorderdata', 'tSODPCKStatus') ?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?= language('document/saleorderdata/saleorderdata', 'tSODABBNo') ?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?= language('document/saleorderdata/saleorderdata', 'tSODTAXNo') ?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?= language('document/saleorderdata/saleorderdata', 'tSODTAXBook') ?></th>
                        <th nowrap class="xCNTextBold sticky-col" width="8%"><?= language('document/saleorderdata/saleorderdata', 'tSODManage') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach ($aDataList['raItems'] as $nKey => $aValue) : ?>
                            <?php
                            // print_r($aValue);
                            $tSODocNo  = $aValue['FTXshDocNo'];
                            if (!empty($aValue['FTXshStaApv']) || $aValue['FTXshStaDoc'] == 3) {
                                $tCheckboxDisabled  = "disabled";
                                $tClassDisabled     = 'xCNDocDisabled';
                                $tTitle             = language('document/document/document', 'tDOCMsgCanNotDel');
                                $tOnclick           = '';
                            } else {
                                $tCheckboxDisabled  = "";
                                $tClassDisabled     = '';
                                $tTitle             = '';
                                $tOnclick           = "onclick=JSoSODelDocSingle('" . $nCurrentPage . "','" . $tSODocNo . "')";
                            }
                            //สถานะอ้างอิงใบขาย
                            $tClassStaSale = '';
                            //สถานะเอกสาร
                            if ($aValue['FTXshStaPrcDoc'] == '3') {
                                $tTextSODStatus    = 'Stock ไม่พอ';
                                $tClassStaSale      = '';
                            } elseif ($aValue['FTXshStaPrcDoc'] == '6') {
                                $tTextSODStatus    = 'รับสินค้าแล้ว';
                                $tClassStaSale      = 'text-success';
                            } elseif ($aValue['FTXshStaPrcDoc'] == '4' && $aValue['BookApv'] == '1') {
                                $tTextSODStatus    = 'รอฝากสินค้า';
                            } elseif ($aValue['FTXshStaPrcDoc'] == '4' && $aValue['BookApv'] == ''  && $aValue['SALEBOOK'] != '') {
                                $tTextSODStatus    = 'รอยืนยันจองช่องฝาก';
                            } elseif ($aValue['FTXshStaPrcDoc'] == '4' && $aValue['BookApv'] == ''  && $aValue['SALEBOOK'] == '') {
                                $tTextSODStatus    = 'รอจองช่องฝาก';
                            } elseif ($aValue['FTXshStaPrcDoc'] == '5') {
                                $tTextSODStatus    = 'ฝากสินค้าแล้ว';
                                $tClassStaSale      = 'text-success';
                            } elseif ($aValue['SODStatus'] == '1' && $aValue['DOCREF']  != '') {
                                $tTextSODStatus    = 'รับใบสั่งขายแล้ว';
                                $tClassStaSale      = '';
                            } else {
                                if ($aValue['DOCREF']  == '' && $aValue['FTXshStaApv']  == '1') {
                                    $tTextSODStatus    = 'รอสร้างใบจัดสินค้า';
                                    $tClassStaSale      = '';
                                } elseif ($aValue['DOCREF']  == '' && $aValue['FTXshStaApv']  != '1') {
                                    $tTextSODStatus    = 'รออนุมัติสั่งขาย';
                                    $tClassStaSale      = '';
                                } elseif ($aValue['PARTITIONBYDOC2'] == '') {
                                    $tTextSODStatus    = 'รอยืนยันจัดสินค้า';
                                    $tClassStaSale      = '';
                                } else {
                                    $tTextSODStatus    = 'จัดสินค้า ' . $aValue['PARTITIONBYDOC2'] . '/' . $aValue['PARTITIONBYDOC'];
                                    $tClassStaSale      = '';
                                }
                            }


                            //สถานะเอกสารใบจัด
                            if ($aValue['DOCREFStaApv'] == 1) {
                                $tClassPickStaApv   = 'text-success';
                                $tTextPickStatus    = language('document/saleorderdata/saleorderdata', 'tSODStaApv1');
                            } else {
                                $tClassPickStaApv   = '';
                                $tTextPickStatus    = language('document/saleorderdata/saleorderdata', 'tSODStaApv2');
                            }

                            
                            if ($aValue['FTXshStaDoc'] == 3) {
                                $tClassStaSale      = 'text-danger';
                                $tTextSODStatus     = 'ยกเลิก';
                            } else {
                                if ($aValue['SALEABB'] != '' || $aValue['SALEABB'] != null) {
                                    // $tClassStaSale  = 'text-success';
                                    $tStaSale       = language('document/saleorderdata/saleorderdata', 'tSOSaled');
                                } else {
                                    // $tClassStaSale  = 'text-warning';
                                    $tStaSale       = language('document/saleorderdata/saleorderdata', 'tSOWaitSale');
                                }
                            }
                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" data-code="<?= $aValue['FTXshDocNo'] ?>" data-name="<?= $aValue['FTXshDocNo'] ?>">
                                <?php
                                //รวมคอลัมน์
                                if ($aValue['PARTITIONBYDOC'] == 1 || $aValue['PARTITIONBYDOC'] == 0) {
                                    $nRowspan   = '';
                                } else {
                                    $nRowspan   = "rowspan=" . $aValue['PARTITIONBYDOC'];
                                }
                                ?>
                                <?php if ($tKeepDocNo != $aValue['FTXshDocNo']) { ?>
                                    <td <?= $nRowspan ?> nowrap class="text-left xWSODDocno"><?= (!empty($aValue['FTXshDocNo'])) ? $aValue['FTXshDocNo']   : '-' ?></td>
                                    <td <?= $nRowspan ?> nowrap class="text-center"><?= (!empty($aValue['FDXshDocDate'])) ? $aValue['FDXshDocDate'] : '-' ?></td>
                                    <td <?= $nRowspan ?> nowrap class="text-left"><?= (!empty($aValue['FTCstName'])) ? $aValue['FTCstName'] : '-' ?></td>
                                    <td <?= $nRowspan ?> nowrap class="text-left"><?= (!empty($aValue['ExRef'])) ? $aValue['ExRef'] : '-' ?></td>
                                    <td <?= $nRowspan ?> nowrap class="text-center"><?= (!empty($aValue['ExRefDate'])) ? $aValue['ExRefDate'] : '-' ?></td>
                                    <td <?= $nRowspan ?> nowrap class="text-left">
                                        <label class="xCNTDTextStatus <?= @$tClassStaSale ?> "><?= $tTextSODStatus ?>
                                        </label>
                                    </td>
                                <?php } ?>


                                <!--เอกสารอ้างอิง-->
                                <?php if ($aValue['DOCREF'] == '') { ?>
                                    <td nowrap class="text-left">
                                        -
                                    </td>
                                <?php } else { ?>
                                    <td nowrap class="text-left">
                                        <a onClick="JSvSODJumptoPCKPage('<?= $aValue['DOCREF'] ?>')" style='cursor: pointer;color : #1866ae;text-decoration: underline;'><?= $aValue['DOCREF'] ?> </a>
                                    </td>
                                <?php } ?>
                                <td nowrap class="text-left">
                                    <?php if ($aValue['DOCREF'] == '') { ?>
                                        <label class="xCNTDTextStatus"> -
                                        <? } else { ?>
                                            <label class="xCNTDTextStatus <?= $tClassPickStaApv; ?>"><?= $tTextPickStatus ?>
                                            </label>
                                        <? } ?>
                                </td>

                                <!-- เงื่อนไขจัดการ -->
                                <?php
                                if ($aValue['PARTITIONBYDOC'] != '' && $aValue['PARTITIONBYDOC'] == $aValue['PARTITIONBYDOC2'] && $aValue['FTXshStaApv'] == '1' && $aValue['SALEABB'] != '')
                                // if($aValue['PARTITIONBYDOC'] != '' && $aValue['PARTITIONBYDOC'] == $aValue['PARTITIONBYDOC2'] && $aValue['FTXshStaApv'] == '1')
                                {
                                    $tManageText = 'จองช่องฝาก';
                                    $tManageDisabled = '';
                                } else {
                                    $tManageText = 'จองช่องฝาก';
                                    $tManageDisabled = 'disabled';
                                }

                                if ($aValue['FTXshStaDoc'] == '3') {
                                    $tCancelDisabled = 'disabled';
                                } else {
                                    $tCancelDisabled = '';
                                }

                                if ($aValue['SALEBOOK'] != '') {
                                    $tRentalDisabled = 'disabled';
                                } else {
                                    $tRentalDisabled = '';
                                }
                                ?>

                                <?php if ($tKeepDocNo != $aValue['FTXshDocNo']) { ?>
                                    <?php if ($aValue['SALEABB'] == '') { ?>
                                        <td <?= $nRowspan ?> nowrap class="text-left">
                                            -
                                        </td>
                                    <?php } else { ?>
                                        <td <?= $nRowspan ?> nowrap class="text-left">
                                            <a onClick="JSvSODJumptoABBPage('<?= $aValue['SALEABB'] ?>')" style='cursor: pointer;color : #1866ae;text-decoration: underline;'><?= $aValue['SALEABB'] ?> </a>
                                        </td>
                                    <?php } ?>

                                    <?php if ($aValue['SALETAX'] == '') { ?>
                                        <td <?= $nRowspan ?> nowrap class="text-left">
                                            -
                                        </td>
                                    <?php } else { ?>
                                        <td <?= $nRowspan ?> nowrap class="text-left">
                                            <a onClick="JSvSODJumptoTAXPage('<?= $aValue['SALETAX'] ?>','<?= $aValue['FTBchCode'] ?>')" style='cursor: pointer;color : #1866ae;text-decoration: underline;'><?= $aValue['SALETAX'] ?> </a>
                                        </td>
                                    <?php } ?>

                                    <?php if ($aValue['SALEBOOK'] == '') { ?>
                                        <td <?= $nRowspan ?> nowrap class="text-left">
                                            -
                                        </td>
                                    <?php } else { ?>
                                        <td <?= $nRowspan ?> nowrap class="text-left">
                                            <a onClick="JSvSODJumptoCreateBook('<?= $aValue['SALEBOOK'] ?>','<?= $aValue['FTBchCode'] ?>','2')" style='cursor: pointer;color : #1866ae;text-decoration: underline;'><?= $aValue['SALEBOOK'] ?> </a>
                                        </td>
                                    <?php } ?>


                                    <!-- JSvDBRCallPageEdit -->
                                    <!-- <td <?= $nRowspan ?> nowrap class="text-left"><?= (!empty($aValue['SALETAX'])) ? $aValue['SALETAX'] : '-' ?></td> -->


                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>

                                        <td class="sticky-col2" <?= $nRowspan ?> nowrap>
                                            <?php if ($aValue['SODStatus'] == '1') { ?>
                                                <button style='width:50%' id="obtSODEdit" class="btn xCNBTNDefult xCNBTNDefult1Btn" onClick="JSvSODJumptoSOPage('<?= $aValue['FTXshDocNo'] ?>')"><?php echo language('document/saleorderdata/saleorderdata', 'tSODExamine'); ?></button>
                                            <?php } elseif ($aValue['PARTITIONBYDOC'] == $aValue['PARTITIONBYDOC2']) { ?>
                                                <button style='width:50%' id="obtSODEdit" class="btn xCNBTNDefult xCNBTNDefult1Btn" onClick="JSvSODJumptoSOPage('<?= $aValue['FTXshDocNo'] ?>')"><img class="xCNIconTable" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>"></button>
                                            <?php } else { ?>
                                                <button style='width:50%' id="obtSODEdit" class="btn xCNBTNDefult xCNBTNDefult1Btn" onClick="JSvSODJumptoSOPage('<?= $aValue['FTXshDocNo'] ?>')"><?php echo language('document/saleorderdata/saleorderdata', 'tSODExamine'); ?></button>
                                            <?php } ?>

                                            <?php
                                            if ($aValue['FTXshStaPrcDoc'] == '5' && $aValue['SALEABB'] != '') { ?>
                                                <button id="obtSODRentBook" style='min-width:100px;' disabled onClick="JSvSODJumptoCRVPageBefore('<?= $aValue['FTXshDocNo'] ?>','1')" class="btn xCNBTNDefult xCNBTNDefult1Btn">ดูประวัติ</button>
                                            <?php } elseif ($aValue['FTXshStaPrcDoc'] == '6') { ?>
                                                <button id="obtSODRentBook" style='min-width:100px;' <?= $tCancelDisabled ?> onClick="JSvSODJumptoCRVPageBefore('<?= $aValue['FTXshDocNo'] ?>','2')" class="btn xCNBTNDefult xCNBTNDefult1Btn">ดูประวัติ</button>
                                            <?php } else { ?>
                                                <button id="obtSODRentBook" style='min-width:100px;' <?= $tManageDisabled ?> <?= $tRentalDisabled ?> onClick="JSvSODJumptoCreateBook('<?= $aValue['FTXshDocNo'] ?>','<?= $aValue['FTBchCode'] ?>','1')" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?= $tManageText; ?></button>
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXshDocNo']; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
<!-- 
        <?php $nShowRecord  = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p> -->
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPIPageDataTable btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvSOClickPageList('previous')" class="btn btn-white btn-sm" <?= $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSvSOClickPageList('<?= $i ?>')" type="button" class="btn xCNBTNNumPagenation <?= $tActive ?>" <?= $tDisPageNumber ?>><?= $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvSOClickPageList('next')" class="btn btn-white btn-sm" <?= $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<audio id="myAudio">
  <source src="<?= base_url('application/modules/common/assets/sound/Noti-S1.wav'); ?>" type="audio/ogg">
</audio>


<div class="alert-container" hidden>
  <div class="alert" id = 'toaseAlert' style="border: 1px solid !important;">
    <p id='toase1'>รับคำสั่งซื้อแล้วเลขที่ Docno</p> 
    <p id='toase2'>เลขที่อ้างอิง rtDocRefNo</p>
    <p id='toase3'>วันที่ rtDocRefDate</p>
    <p id='toase4'>รหัสสาขา rtBchCode</p>
  </div>
</div>

<?php
// include('script/jSaleOrderDataTable.php') 
?>

<script>
clearInterval(oSetSaleOrderDataInterval);

var x = document.getElementById("myAudio"); 

function playAudio() { 
  x.play(); 
} 

$('#Toright').unbind().click(function(){
    $("div").scrollLeft(1000);
});
$('#ToLeft').unbind().click(function(){
    $("div").scrollLeft(-1000);
});



var oSetSaleOrderDataInterval = setInterval(JSxSORecallRabbit, 5000);

function JSxSORecallRabbit(){
    $.ajax({
        type: "POST",
        url: "dcmSOCheckUpdateMQ",
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            var aReturnData = JSON.parse(tResult);
            if(aReturnData['nStaEvent']!= '900'){
                if(aReturnData['nStaEvent'] == '404'){
                    // $("#toaseAlert").empty();
                    // var tToaseText = "<p>ไม่พบคำสั่งซื้อเลขที่ "+aReturnData['tDocno']+"</p>";
                    // $('#toaseAlert').append(tToaseText);
                    // $(function() {
                    // var alert = $(".alert-container");
                    // alert.hide();
                    //     alert.slideDown();
                    //     window.setTimeout(function() {
                    //     alert.slideUp();
                    //     }, 4000);
                    // });
                    // playAudio();
                }else{
                    var nflag = '0';
                    var aItems = aReturnData['aItems'];
                    $(".xWSODDocno").each(function () { 
                        if($(this).text() == aItems['rtDocNo']){
                            nflag = '1';
                            return false;
                        }
                    });
                    var tClassLenght = $( ".xWSODDocno" ).length;
                    if(tClassLenght == '0'){
                        $('#SODTable > tbody').empty();
                    }

                    if(nflag == '0'){
                        var tAddtr = "<tr class='text-center xCNTextDetail2 xWPIDocItems' data-code='"+aItems['rtDocNo']+"' data-name='"+aItems['rtDocNo']+"'>";
                        tAddtr += "<td nowrap='' class='text-left xWSODDocno'>"+aItems['rtDocNo']+"</td>";
                        tAddtr += "<td nowrap='' class='text-center'>"+aItems['rtDocRefDate']+"</td>";
                        tAddtr += "<td nowrap='' class='text-left'>"+aItems['tCstName']+"</td>";
                        tAddtr += "<td nowrap='' class='text-left'>"+aItems['rtDocRefNo']+"</td>";
                        tAddtr += "<td nowrap='' class='text-center'>"+aItems['rtDocRefDate']+"</td>";
                        tAddtr += "<td nowrap='' class='text-left'><label class='xCNTDTextStatus  '>รอสร้างใบจัดสินค้า</label></td>";
                        tAddtr += "<td nowrap='' class='text-left'>-</td></td>";
                        tAddtr += "<td nowrap='' class='text-left'>-</td></td>";
                        tAddtr += "<td nowrap='' class='text-left'>-</td></td>";
                        tAddtr += "<td nowrap='' class='text-left'>-</td></td>";
                        tAddtr += "<td nowrap='' class='text-left'>-</td></td>";
                        tAddtr += "<td class='sticky-col2' nowrap=''><button style='width:50%' id='obtSODEdit' class='btn xCNBTNDefult xCNBTNDefult1Btn' ";
                        tAddtr += 'onclick="JSvSODJumptoSOPage(\'' + aItems['rtDocNo'] + '\');" >ตรวจสอบ</button>';
                        tAddtr += "<button id='obtSODRentBook' disabled='' class='btn xCNBTNDefult xCNBTNDefult1Btn'>จองช่องฝาก</button></td></tr>";
                        $('#SODTable > tbody').prepend(tAddtr);
                        $("#toaseAlert").empty();
                        var tToaseText = "<p>รับคำสั่งซื้อแล้วเลขที่ "+aItems['rtDocNo']+"</p>";
                        tToaseText += "<p>เลขที่อ้างอิง "+aItems['rtDocRefNo']+"</p>";
                        tToaseText += "<p>วันที่ "+aItems['rtDocRefDate']+"</p>";
                        tToaseText += "<p>รหัสสาขา "+aItems['rtBchCode']+"</p>";
                        // toase1.text('รับคำสั่งซื้อแล้วเลขที่ '+aItems['rtDocNo']);
                        // toase2.text('เลขที่อ้างอิง '+aItems['rtDocRefNo']);
                        // toase3.text('วันที่ '+aItems['rtDocRefDate']);
                        // toase4.text('รหัสสาขา '+aItems['rtBchCode']);
                        $('#toaseAlert').append(tToaseText);
                        $(function() {
                        var alert = $(".alert-container");
                        alert.hide();
                            alert.slideDown();
                            window.setTimeout(function() {
                            alert.slideUp();
                            }, 4000);
                        });
                        playAudio();
                    }
                }

            }else{
                
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

$(".xCNMenuItem").click(function () { 
    clearInterval(oSetSaleOrderDataInterval);
    
});

</script>