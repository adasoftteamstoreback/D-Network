<div id="odvCpnMainMenu" class="main-menu clearfix">
	<div class="xCNMrgNavMenu">
		<div class="row xCNavRow" style="width:inherit;">
			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
				<ol id="oliMenuNav" class="breadcrumb">
					<?php FCNxHADDfavorite('masChkImport/0/0');?> 
					<li id="oliInterfaceImportTitle" class="xCNLinkClick" style="cursor:pointer" onclick="JSvInterfaceImportCallPage()">
						<?=language('interface/interfacechkimport/interfacechkimport','tInterfaceHead') ?>
					</li>
				</ol>
			</div>
		</div>
	</div>
</div>
	<div class="main-content">
		<div class="panel panel-headline">
			<div class="row">
				<div class="col-md-12">
					<div class="panel-body">
						<div class="table-responsive" style="padding:20px">
							<table id="otbInterfaceImport" class="table table-striped"> 
								<thead>
									<tr>
									    <th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceId'); ?></th>
										<th nowrap class="text-center xCNTextBold" width="70%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceName'); ?></th>
										<th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceView'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
               						<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>
										
									<tr>
								     	<td align="center"><?=($key+1)?></td>
                    		   		    <td nowrap class="text-left"><?php echo $aValue['FTApiName'] ?></td>
										<td align="center">
                                    		<img class="xCNIconTable "  src="<?php echo  base_url().'/application/modules/common/assets/images/icons/view2.png'?>" onClick="JSvInterfaceImportCallPageAll('<?php echo $aValue['FTApiCode'] ?>')">
                               			</td>
                    				</tr>
              					  <?php endforeach; ?>
                                  <?php else : ?>		
                                    <tr><td class='text-center xCNTextDetail2' colspan='5'><?= language('interface/interfaceimport/interfaceimport','tInterfaceNodata')?></td></tr>
			                      <?php endif ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script src="<?=base_url('application/modules/interface/assets/src/interfacecheckimport/jInterfaceCheckImport.js')?>"></script>
<script>

    //ปิดเมนู
    JSxCheckPinMenuClose(); 


    function JSvInterfaceImportCallPageList(){ //กลับหน้าหลัก
    $.ajax({
        type    : "POST",
        url     : "maspageChkImportList",
        data    : {},
        cache   : false,
        timeout : 5000,
        success : function (tResult) {
            $('.odvMainContent').html(tResult);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}
    //โหลด แสดงข้อมูล ตาราง แต่ละข้อมูล
	function JSvInterfaceImportCallPageAll(paApiCode , pnPage){
        nData =  paApiCode;
        var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
            switch (nData) {
                case "00020": //ข้อมูล CostCenterToProfiCenter (Excel)
                    var tRoute  = 'maspageChkImportList';
                    break;
                case "00021": //ข้อมูล InterBA (Excel)
                    var tRoute  = 'maspageChkImportInterBA';
                    break;
                case "00022": //ข้อมูลพนักงานดูแลร้าน (SAP Master)
                    var tRoute  = 'maspageChkImportSaleStaff';
                    break;
                case "00023": //ข้อมูลลูกค้า (SAP Master)ส่งออกรถกองยาน (SAP)
                    var tRoute  = 'maspageChkImportCustomer';
                    break;
                case "00024": //ข้อมูล LocationID กับ Role(SAP Master)
                    var tRoute  = 'maspageChkImportRole';
                    break;
                case "00025": //ข้อมูล Saleที่ดูแลสาขา(SAP Master)
                    var tRoute   = 'maspageChkImportSaleforStore';    
                    break;
                case "00026": //ข้อมูลรถ(SAP Master)
                    var tRoute   = 'maspageChkImportCar';
                    break;
                case "00027": //ข้อมูลสินค้า(Excel)
                    var tRoute   = 'maspageChkImportMainProducts';    
                    break;
                default:
            }
        $.ajax({
            type    : "POST",
            url     : "maspageChkImportList",
            data    : {
                      nCode : nData,
                      nPageCurrent: nPageCurrent,  
            },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
               $('.odvMainContent').html(tResult);
               
            },
            error: function (jqXHR, textStatus, errorThrown) {
               JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

</script>