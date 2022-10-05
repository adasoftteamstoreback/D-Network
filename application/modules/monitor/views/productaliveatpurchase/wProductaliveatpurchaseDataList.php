<table class="table table-hover">
  <thead>
    <tr>
      <th class="text-center" width="5%"><?= language('monitor/monitor/monitor','tPAPLowtableThCol1')?></th>
      <th class="text-center" width="10%"><?= language('monitor/monitor/monitor','tPAPLowtableThCol2')?></th>
      <th class="text-center" width="10%"><?= language('monitor/monitor/monitor','tPAPBch')?></th>
      <th class="text-center" width="8%"><?= language('monitor/monitor/monitor','tPAPWahName')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol3')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol4')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol5')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol6')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol7')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol8')?></th>
      <!-- <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol9')?></th> -->
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol10')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol11')?> (%)</th>
      <th class="text-center" width="7%"><?= language('monitor/monitor/monitor','tPAPLowtableThCol12')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tPAPLowtableThCol13')?></th>
    </tr>
  </thead>
  <tbody>
    <?php if (isset($aPAPDataList['aItems'])) {
      foreach ($aPAPDataList['aItems'] as $nKey => $aValue) { ?>
        <tr>
          <td class="text-center"><?=$aValue['FTPdtCode']?></td>
          <td class="text-left"><?=$aValue['FTPdtName']?></td>
          <td class="text-left"><?=$aValue['FTBchName']?></td>
          <td class="text-left"><?=$aValue['FTWahName']?></td>
          <td class="text-right"><?=number_format($aValue['FCStkQty'],$nDecimal)?></td>
          <td class="text-right"><?=number_format($aValue['FCPdtLeadTime'],$nDecimal)?></td>
          <td class="text-right"><?=number_format($aValue['FCPdtDailyUseAvg'],$nDecimal)?></td>
          <td class="text-right"><?=number_format($aValue['FCPdtMin'],$nDecimal)?></td>
          <td class="text-right"><?=number_format($aValue['FCPdtMax'],$nDecimal)?></td>
          <td class="text-right"><?=number_format($aValue['FCPdtQtyOrdBuy'],$nDecimal)?></td>
          <!-- <td class="text-right"><?=number_format($aValue['FCPdtQtySugges'],$nDecimal)?></td> -->
          <td class="text-right">
            <?php 
              if($aValue['FCStkQty'] <= 0 ){
                $nServiceLevel = 100;
              }else{
                $nServiceLevel = ($aValue['FCPdtQtyOrdBuy'] * 100)/$aValue['FCStkQty'];
              }
              echo number_format($nServiceLevel,$nDecimal); 
            ?>
          </td>
          <td class="text-right"><?php echo number_format($aValue['FCPdtPerSLA'],$nDecimal)?></td>
          <?php 
            if ($aValue['FTPdtStaOrder'] != '') {
              $tStyleColor = 'color : red;';
            }else{
              $tStyleColor = '';
            }
          ?>
          <td class="text-left"><b style="<?php echo $tStyleColor ?>">
            <?php
              if ($aValue['FTPdtStaOrder'] != '') {
                echo language('monitor/monitor/monitor','ควรสั่งซื้อ');
              }else{
                echo language('monitor/monitor/monitor','ยังไม่ต้องสั่งซื้อ');
              }
            ?></b>
          </td>
          <td class="text-center">
            <img class="xCNIconTable" style="width: 17px;" src="<?=base_url('application/modules/common/assets/images/icons/view2.png')?>" onclick="JSvPAPViewSpl('<?=$aValue['FTPdtCode']?>','<?=$aValue['FTWahCode']?>')">
          </td>
        </tr>
        <?php }
    } else { ?>
       <tr>
         <td colspan="15" class="text-center"><?php echo language('bank/bank/bank','tBnkNoData'); ?></td>
       </tr>
    <?php } ?>
  </tbody>
</table>

<!-- =========================================== แหล่งจัดซื้อ ============================================= -->
<div id="odvDOModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('monitor/monitor/monitor','tPAPLowtableThCol13')?></label>
            </div>

            <div class="modal-body">
                <div class="row" id="odvDOFromRefIntDoc">
           
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>

        </div>
    </div>
</div>

<script>
  function JSvPAPViewSpl(ptPdtCode,ptWahCode) {  
    var oAdvanceSearch = JSoPAPGetAdvanceSearchData();
    JCNxOpenLoading();
      $.ajax({
        type: "POST",
        url: "monPAPGetSplBuyList",
        data: {
            'ptWahCode' : ptWahCode,
            'ptPdtCode' : ptPdtCode
        },
        cache: false,
        Timeout: 0,
        success: function (oResult){
            JCNxCloseLoading();
            $('#odvDOFromRefIntDoc').html(oResult);
            $('#odvDOModalRefIntDoc').modal({backdrop : 'static' , show : true});
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
      });
  }
</script>