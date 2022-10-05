<div id="odvCpnMainMenu" class="main-menu clearfix">
	<div class="xCNMrgNavMenu">
		<div class="row xCNavRow" style="width:inherit;">
			<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
				<ol id="oliMenuNav" class="breadcrumb">
					<?php FCNxHADDfavorite('masChkImport/0/0');?> 
					<li id="oliInterfaceImportTitle" class="xCNLinkClick" style="cursor:pointer" onclick="JSvInterfaceImportCallPage()">
						<?=language('interface/interfacechkimport/interfacechkimport','tInterfaceHead') ?>
					</li>
					<li id="oliInterfaceImportName" class="active"><a><?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceHeadProducts')?></a></li>
				</ol>
			</div>
			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-right p-r-0">	
				<button onclick="JSvInterfaceImportCallPage()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
			</div>
		</div>
	</div>
</div>

<div class="main-content">
	<div class="panel panel-headline">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-2 cols-sm-2 col-md-12 col-lg-2" style="padding-left:35px">
					<div class="form-group">
						<select class="form-control" id="ocmchkImporproduct" name="ocmchkImporproduct" maxlength="1" >
							<option class="" value="1"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProducts')?></option>
							<option class="" value="2"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProdutGroup')?></option>
							<option class="" value="3"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProductDept')?></option>
							<option class="" value="4"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceUnitSmall')?></option>
							<option class="" value="5"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProductComponent')?></option>
							<option class="" value="6"><?=language('interface/interfacechkimport/interfacechkimport','tInterfaceProductPrice2')?></option>
						</select>
					</div>
				</div>	
			</div>
			<div class="row">
				<div class="col-xs-12 cols-sm-12 col-md-12 col-lg-12">
					<div id="odvcontentProduct"> </div>
				</div>
			</div>
		</div>	
	</div>	
</div>	

<script>

	JSvInterfaceImportProducts(1)  // กำหนดหน้าเริ่มต้นเป็น 1 
	function JSvInterfaceImportProducts(nPageCurrent){     //  รับ paramiter  nPageCurrent นับจำนวนหน้า  
        $.ajax({
            type    : "POST",
            url     : "maspageChkImportProducts",  // ลิ้งไปหน้า Products
            data    : {
				      nPageCurrent:nPageCurrent  // ส่งค่าไป
			},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $('#odvcontentProduct').html(tResult); //แสดงผล
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
	
	// Even ทุกครั้งที่เปลี่ยน Select Box
	$('#ocmchkImporproduct').change(function(){
		$.ajax({
			type    : "POST",
			url     : "maspageChkImportSelectPage", // ลิ้งไปหน้า Controllers Fuction  FSvCChkImportSelectPage
			data    :  {
						tproduct : $('#ocmchkImporproduct').val()
					},
			cache   : false,
			timeout : 5000,
			success : function (tResult) {
				$('#odvcontentProduct').html(tResult);
			},
			error   : function (jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });


</script>