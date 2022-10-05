<?php 
    $aSplData = $aPAPSplDataList['aItems'][0];
    $tPdtCode = $aSplData['FTPdtCode'];
    $tPdtName = $aSplData['FTPdtName'];
?>
<div class="xCNtableFixHead">
    <label><span><?= language('monitor/monitor/monitor','tPAPPdtCode')?> :</span> <?=$tPdtCode?></label><br/>
    <label><span><?= language('monitor/monitor/monitor','tPAPLowtableThCol2')?> :</span> <?=$tPdtName?></label><br/>
    <?php 
        $tUsrLevel = $this->session->userdata('tSesUsrLevel');
        $tBchMulti = $this->session->userdata("tSesUsrBchCodeMulti");

        if( $tUsrLevel != "HQ" ){
            $tHideTable = '';
        }else{
            $tHideTable = 'xCNHide';
        }
    ?>
    <table class="table <?=$tHideTable?>" style="margin-top: 15px;">
        <thead>
            <tr>
                <th class="xRefIntTh text-center" width="60%"><?php echo language('monitor/monitor/monitor','tPdtUnit');?></th>
                <th class="xRefIntTh text-center" width="20%"><?php echo language('monitor/monitor/monitor','tAlwHq');?></th>
                <th class="xRefIntTh text-center" width="20%"><?php echo language('monitor/monitor/monitor','tAlwSpl');?></th>
            </tr>
        </thead>
        <tbody>

        <?php if($aPAPAlwPo['tCode'] == 1 ):?>
            <?php foreach($aPAPAlwPo['aItems'] AS $nKey => $aValue): ?>
                <?php 
                    $nAlwHQ = '';
                    $nAlwSPL = '';
                    if ($aValue['FTPdtStaAlwPoHQ'] == 1) {
                        $nAlwHQ = "checked";
                    }else{
                        $nAlwHQ = "";
                    }

                    if ($aValue['FTPdtStaAlwPoSPL'] == 1) {
                        $nAlwSPL = "checked";
                    }else{
                        $nAlwSPL = "";
                    }
                ?>
                <tr class="xCNTextDetail2" >
                    <td class="xRefIntTd text-left"><?=$aValue['FTPunName']?></td>
                    <td class="xRefIntTd text-center"><input type="checkbox" class="form-controll disabled" disabled <?php echo $nAlwHQ;?>></td>
                    <td class="xRefIntTd text-center"><input type="checkbox" class="form-controll disabled" disabled <?php //echo $nAlwSPL?>></td>
                </tr>
        <?php endforeach;?>
        <?php else:?>
            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php endif;?>
        </tbody>
    </table>
</div>

<div class="xCNtableFixHead" style="margin-top: 15px;"> 
    <table class="table">
        <thead >
            <tr>
                <th class="xRefIntTh text-center"><?php echo language('document/bookingorder/bookingorder','tTWXTable_barcode');?></th>
                <th class="xRefIntTh text-center"><?php echo language('monitor/monitor/monitor','tPAPBuySpl');?></th>
                <th class="xRefIntTh text-center"><?php echo language('monitor/monitor/monitor','tPAPBuySplContact');?></th>
                <th class="xRefIntTh text-center"><?php echo language('monitor/monitor/monitor','tPAPBuySplTel');?></th>
            </tr>
        </thead>
        <tbody>

        <?php if($aPAPSplDataList['tCode'] == 1 ):?>
            <?php foreach($aPAPSplDataList['aItems'] AS $nKey => $aValue): ?>
                <tr class="xCNTextDetail2" >
                    <td class="xRefIntTd text-center"><?php echo (!empty($aValue['FTBarCode']))? $aValue['FTBarCode'] : '-' ?></td>
                    <td class="xRefIntTd"><?php echo (!empty($aValue['FTSplName']))? $aValue['FTSplName'] : '-' ?></td>
                    <td class="xRefIntTd"><?php echo (!empty($aValue['FTCtrName']))? $aValue['FTCtrName'] : '-' ?></td>
                    <td class="xRefIntTd"><?php echo (!empty($aValue['FTCtrTel']))? $aValue['FTCtrTel'] : '-' ?></td>
                </tr>
        <?php endforeach;?>
        <?php else:?>
            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php endif;?>
        </tbody>
    </table>
</div>