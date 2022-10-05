<?php
$tRoute = "customerbranchEventAdd";
$nCbrSeq =    '';
$tCbrRefBch =    '';
$tCbrRefBchName =    '';
$nCbrQtyPos =    1;
$tSrvCode =    '';
$tSrvName =    '';
$tPageEvent =    language('customerlicense/customerlicense/customerlicense', 'tCLBPageAdd');
$tServerDisabled = '';
$dataToggle = 'false';


?>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="custom-tabs-line tabs-line-bottom left-aligned">
            <div class="row" role="tablist" >
                    <ul class="nav" role="tablist" data-typetab="main" data-tabtitle="Bchinfo" style='cursor: pointer;'>
                        <li id="oliBchInfo1" class="xWMenu active" data-menutype="MSShop" onclick="JSxCallGetContentMSSHOP();">
                            <a role="tab" data-toggle="tab" data-target="#odvTabMSShop" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSMIDTID') ?></a>
                        </li>
                        <li id="oliBchAddr" class="xWMenu xWSubTab" data-typetab="main" data-tabtitle="Respond" data-menutype="Mapping" onclick="JSxCallGetContentRespond();">
                            <a role="tab" data-toggle="tab" data-target="#odvTabRespond" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSRespond') ?></a>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
</div>


<input type="hidden" name="ohdCbrSeq" id="ohdCbrSeq" value="<?= $nCbrSeq ?>">
<input type="hidden" name="ohdCbrRoute" id="ohdCbrRoute" value="<?= $tRoute ?>">

<div id="odvCCSRowContentMenu" class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!-- Tab Content Detail -->
					<div class="tab-content">
						<div id="odvTabMSShop" class="tab-pane fade active in"></div>
						<div id="odvTabRespond" class="tab-pane fade"></div>
					</div>
				</div>
			</div>