
<?= language('monitor/monitor/monitor','tDLSlabelToptable')?>
<table class="table table-hover">
  <thead>
    <tr>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSToptableThCol1')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSToptableThCol2')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSToptableThCol3')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSToptableThCol4')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSToptableThCol5')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSToptableThCol6')?></th>
      <th class="text-center"><?= language('monitor/monitor/monitor','tDLSToptableThCol7')?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (isset($aDLSDataList['raItems'])) {
      foreach ($aDLSDataList['raItems'] as $nKey => $aValue) { ?>
        <tr onclick="JSxDLSListDetail('<?php echo $aValue['FTCstCode']; ?>')" id="otrDLSLisHead<?php echo $aValue['FTCstCode']; ?>" >
          <td class="text-center"><?php echo $nKey+1; ?><input name="otrDLSLisHead[]" value="<?php echo $aValue['FTCstCode']; ?>" hidden /></td>
          <td><?php echo $aValue['FTCstCode']; ?></td>
          <td><?php echo $aValue['FTCStName']; ?></td>
          <td><?php echo $aValue['FTCstAddress']; ?></td>
          <td><?php echo $aValue['FTCstTel']; ?></td>
          <td><?php echo $aValue['FTCstEmail']; ?></td>
          <td class="text-right"><?php echo number_format($aValue['FCXshGrand'],2); ?></td>
        </tr>
        <?php
      }
    }else { ?>
      <tr>
        <td colspan="7" class="text-center"><?php echo language('bank/bank/bank','tBnkNoData'); ?></td>
      </tr><?php
    } ?>
  </tbody>
</table>
