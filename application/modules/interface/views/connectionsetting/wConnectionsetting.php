<input id="ohdCCSBrowse"         type="hidden" value="<?=$nBrowseType?>">
<input id="ohdCCSCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div id="odvCCSMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNCCSVMaster">
                <div class="col-xs-12 col-md-8">
                    <ol id="oliMenuNav" class="breadcrumb">	<!-- เปลี่ยน -->
                        <?php FCNxHADDfavorite('ConnectionSetting/0/0');?> 
                        <li id="oliCSSTitle" onclick="JSxCallGetConGeneral()" style="cursor:pointer"><?php echo language('interface/connectionsetting/connectionsetting', 'tCCSTitle') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="main-content">
	<div class="panel panel-headline">
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="custom-tabs-line tabs-line-bottom left-aligned">
						<ul class="nav" role="tablist" style='cursor: pointer;'> 
							<!-- ข้อมูลทั่วไป -->   
							<li id="oliGeneralInformation" class="xWMenu active" data-menutype="GeneralInformation" onclick="JSxCallGetConGeneral()">
								<a role="tab" data-toggle="tab" data-target="#odvGeneralInformation" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting','tTABGeneralInformation')?></a>
							</li>
							<!-- คลัง -->
							<!-- <li id="oliWahouse" class="xWMenu xWSubTab" data-menutype="Wahouse" onclick="JSxCallGetContent();">
								<a role="tab" data-toggle="tab" data-target="#odvWahouse" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting','tTABWahouse')?></a>
							</li> -->
							<!-- รหัสลูกค้าร้าน -->
							<!-- <li id="oliUsrShop" class="xWMenu xWSubTab" data-menutype="UsrShop" onclick="JSxCallGetContentUserShop();">
								<a role="tab" data-toggle="tab" data-target="#odvUsrShop" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting','tTABUsrShop')?></a>
							</li> -->
							<!-- ข้อมูลรถหน่วยงาน -->
							<!-- <li id="oliAgcCar" class="xWMenu xWSubTab" data-menutype="AgcCar" onclick="JSxCallGetContentCarInter();">
								<a role="tab" data-toggle="tab" data-target="#odvAgcCar" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting','tTABAgcCar')?></a>
							</li> -->
							<!-- ข้อมูล Mapping -->
							<!-- <li id="oliMapping" class="xWMenu xWSubTab" data-menutype="Mapping" onclick="JSxCallGetContentMapping();">
								<a role="tab" data-toggle="tab" data-target="#odvMapping" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting','tMappingsetup')?></a>
							</li> -->
							<!-- UMS -->
							<!-- <li id="oliMapping" class="xWMenu xWSubTab" data-menutype="Mapping" onclick="JSxCallGetContentUMS();">
								<a role="tab" data-toggle="tab" data-target="#odvUMS" aria-expanded="true"><?php echo language('interface/connectionsetting/connectionsetting','tUMS')?></a>
							</li> -->
						</ul>    
					</div>
				</div>
			</div>

			<div id="odvCCSRowContentMenu" class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!-- Tab Content Detail -->
					<div class="tab-content">
						<div id="odvGeneralInformation" class="tab-pane fade active in"></div>
						<div id="odvWahouse" class="tab-pane fade"></div>
						<div id="odvUsrShop" class="tab-pane fade"></div>
						<div id="odvAgcCar" class="tab-pane fade"></div>
						<div id="odvMapping" class="tab-pane fade"></div>
						<div id="odvUMS" class="tab-pane fade"></div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script src="<?php echo base_url('application/modules/interface/assets/src/connectionsetting/jConnectionSetting.js')?>"></script>
