<style>
    .NumberDuplicate{
        font-size   : 15px !important;
        color       : red;
        font-style  : italic;
    }

    .xCNSearchpadding{
        padding     : 0px 3px;
    }
</style>

<div id="odvSMLMainMenu" class="main-menu clearfix">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
					<?php FCNxHADDfavorite('SHPSmartLockerSize');?> 
						<li id="oliSMLTitle" class="xCNLinkClick" onclick="JSvSHPCallPageList()" style="cursor:pointer"><?=language('company/smartlockerlayout/smartlockerlayout','tSMLLayoutTitleHead')?></li>
					</ol>
				</div>
			</div>
		</div>
	<div class="xCNMenuCump xCNSMLBrowseLine" id="odvMenuCump">
        &nbsp;
    </div>
    <div class="main-content">
		<div id="odvPSHContentInfoSearchList"></div>
    </div>

<script>
	$(document).ready(function () {
		JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
		JSvSHPCallPageList()
	});
	function JSvSHPCallPageList() {
		$.ajax({
			type: "GET",
			url: "PSHSmartLockerCheckStatusSearchList",
			cache: false,
			timeout: 0,
			success: function(tResult) {
				$("#odvPSHContentInfoSearchList").html(tResult);
				
				// JSxDBRNavDefult('showpage_list');
				// JSvDBRCallPageDataTable();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
			}
		});
	}
</script>
