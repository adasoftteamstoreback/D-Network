<?= language('monitor/monitor/monitor','tDLSlabelLowtable')?>
<table class="table table-hover">
  <thead>
    <tr>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol1')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol2')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol3')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol4')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol7')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol8')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol9')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol10')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol11')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol12')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol13')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSLowtableThCol14')?></th>
    </tr>
  </thead>
  <tbody>

    <?php
    if (isset($aDLSDataList['raItems'])) {
      foreach ($aDLSDataList['raItems'] as $nKey => $aValue) {
        if (($aValue['FNXshDelayDate'])>0) {
          $tTextColor = "#FF0000";
        }else {
          $tTextColor = "#000000";
        }

         ?>

        <tr>
          <td class="text-center"><font color="<?php echo $tTextColor; ?>"><?php echo $nKey+1; ?></font></td>
          <td class="text-left"><font color="<?php echo $tTextColor; ?>"><?php echo $aValue['FTBchName']; ?></font></td>
          <td class="text-left"><font color="<?php echo $tTextColor; ?>"><?php echo $aValue['FTXshDocNo']; ?></font></td>
          <td class="text-center"><font color="<?php echo $tTextColor; ?>"><?php echo $aValue['FDXshDocDate']; ?></font></td>
          <td class="text-left"><font color="<?php echo $tTextColor; ?>"><?php echo $aValue['FTXshBillingNO']; ?></font></td>
          <td class="text-center"><font color="<?php echo $tTextColor; ?>"><?php echo $aValue['FDXshBillingDate']; ?></font></td>
          <td class="text-center"><font color="<?php echo $tTextColor; ?>"><?php echo $aValue['FDXshDueDate']; ?></font></td>
          <td class="text-right"><font color="<?php echo $tTextColor; ?>"><?php echo $aValue['FNXshDelayDate']; ?></font></td>
          <?php if($aValue['FNXshDocType'] == '1'){ ?>
          <td class="text-right"><font color="<?php echo $tTextColor; ?>"><?php echo number_format($aValue['FCXshGrand'],2); ?></font></td>
          <?php }else{ ?>
          <td class="text-right"><font color="<?php echo $tTextColor; ?>">-<?php echo number_format($aValue['FCXshGrand'],2); ?></font></td>
          <?php } ?>
          <td class="text-right"><font color="<?php echo $tTextColor; ?>"><?php echo number_format($aValue['FCXshPaid'],0); ?></font></td>
          <?php if($aValue['FNXshDocType'] == '1'){ ?>
          <td class="text-right"><font color="<?php echo $tTextColor; ?>"><?php echo number_format($aValue['FCXshLeft'],2); ?></font></td>
          <?php }else{ ?>
          <td class="text-right"><font color="<?php echo $tTextColor; ?>">-<?php echo number_format($aValue['FCXshLeft'],2); ?></font></td>
            <?php } ?>
          <td class="text-center">
            <img class="xCNIconTable" style="text-align: center;"
            src="<?php echo  base_url().'/application/modules/common/assets/images/icons/view2.png'?>"
            onclick="JSvSCallPageEdit('<?php echo $aValue['FTXshDocNo']; ?>')">
          </td>
        </tr>
        <?php
      }
    }else {
      ?>
       <tr>
         <td colspan="14" class="text-center"><?php echo language('bank/bank/bank','tBnkNoData'); ?></td>
       </tr><?php
    } ?>
  </tbody>
</table>
