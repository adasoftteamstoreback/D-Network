
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-8 col-md-4 col-lg-4">
				<div class="form-group">
					<label class="xCNLabelFrm"><?php echo language('register/register','tSrvSearch')?></label>
					<div class="input-group">
						<input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchServer" name="oetSearchServer" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
						<span class="input-group-btn">
							<button id="oimSearchServer" class="btn xCNBtnSearch" type="button">
								<img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
							</button>
						</span>
					</div>
				</div>
			</div>
	
		
			<div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:34px;">
			<?php if($aAlwEventServer['tAutStaFull'] == 1 || $aAlwEventServer['tAutStaDelete'] == 1 ) : ?>
			<button onclick="FSfAPLRegExportJson()" class="btn btn-success"  ><?php echo language('register/register', 'tSrvFrmServerSync'); ?></button>  
			<?php endif; ?>           
				<div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
					<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
						<?= language('common/main/main','tCMNOption')?>
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li id="oliBtnDeleteAll" class="disabled">
							<a data-toggle="modal" data-target="#odvModalDelServer"><?= language('common/main/main','tDelAll')?></a>
						</li>
						<li id="" class=""  onclick="FSfAPLDataPdtSetExportJson()">
							<a data-toggle="modal" data-target=""><?= language('common/main/main','tExport')?></a>
						</li>
						<li id="" class="">
							<a data-toggle="modal" data-target="#odvModalImportPdtSet"><?= language('common/main/main','tImport')?></a>
						</li>
					</ul>
				</div>
			
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div id="ostDataServer"></div>
	</div>


<div class="modal fade" id="odvModalDelServer">
 	<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header xCNModalHead">
						<label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
				</div>
				<div class="modal-body">
					<span id="ospConfirmDelete"> - </span>
					<input type='hidden' id="ohdConfirmIDDelete">
				</div>
				<div class="modal-footer">
					<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoServerDelChoose()">
						<?=language('common/main/main', 'tModalConfirm')?>
					</button>
					<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
						<?=language('common/main/main', 'tModalCancel')?>
					</button>
				</div>
		</div>
	</div>
</div>



<!--Modal Success-->
<div class="modal fade" id="odvModalImportPdtSet">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('register/register','tSrvImportPdtSet'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="form-group">
                 <label class="xCNLabelFrm"><?=language('register/register', 'tMassageImportFile')?></label>
                <div class="input-group input-file-account" name="Fichier1">
                    <input type="text" class="form-control" placeholder='<?=language('register/register', 'tMassageImportFile')?>' />			
                    <span class="input-group-btn">
                     <button class="btn btn-info btn-choose" type="button"><i class="fa fa-folder-open" aria-hidden="true"></i> &nbsp;<?=language('register/register', 'tIMPISelectBrows')?></button>
                    </span>
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <button  type="button"  id="obtIFIModalMsgConfirm" class="btn xCNBTNPrimery" onclick="JSxIMRImportPdtSet()">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
				<button type="button" id="obtIFIModalMsgCancel" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>application/modules/common/assets/js/jquery.mask.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/src/jFormValidate.js"></script>


<script>
	$('#oimSearchServer').click(function(){
		JCNxOpenLoading();
		JSvServerDataTable();
	});
	$('#oetSearchServer').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
			JSvServerDataTable();
		}
	});
    $(function() {
        JSvIMPInputFilePdtSet();
    });

function FSfAPLRegExportJson(){

var aOjcChcked = [];
    $(".ocbListItem:checked").map(function(index){
        aOjcChcked[index] =  {
                        'ptUrlServer':$(this).val() , 
                        'ptSyncLast':$(this).data('synlst'), 
                       }
    }).get(); // <----
	console.log(aOjcChcked);
	
    $.ajax({
          type: "POST",
          url: "ServerEventSync",
          cache: false,
          timeout: 0,
          data:{ aOjcChcked:aOjcChcked },
          success: function(tResult) {
			var paDataReturn = JSON.parse(tResult);
            if(paDataReturn['rtCode']=='001'){
                FSvCMNSetMsgSucessDialog(paDataReturn['rtDesc']);
            }else{
                FSvCMNSetMsgErrorDialog(paDataReturn['rtDesc']);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
              JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
          }
      });
}


function FSfAPLDataPdtSetExportJson(){
	var aOjcChcked = [];
	$(".ocbListItem:checked").map(function(index){
        aOjcChcked[index] =  {
                        'ptSrvCode':$(this).data('srvcode')
                       }
    }).get(); // <----

$.ajax({
          type: "POST",
          url: 'ServerEventExportPdtSet',
          cache: false,
          timeout: 0,
		  data:{ aOjcChcked:aOjcChcked },
          success: function(tResult) {
            var aReturn = JSON.parse(tResult);
            if(aReturn['rtCode']=='1'){
                var oBlob=new Blob([tResult.trim()]);
                    var oLink=document.createElement('a');
                    oLink.href=window.URL.createObjectURL(oBlob);
                    oLink.download="TRGTPdtSet.json";
                    oLink.click();
            }else{
                FSvCMNSetMsgErrorDialog(aReturn['rtDesc']);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
              JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
          }
      });
}


    //Functionality : Event Call Page Register
    //Parameters : URL
    //Creator : 11/01/2021
    //Return : -
    //Return Type : -
    function JSxIMRImportPdtSet(){
        var file_data = $('#oefIMRCallApiImportPdtSet').prop('files')[0];   

        if(file_data!=undefined && file_data!=null){
        JCNxOpenLoading();
        var form_data = new FormData();                  
        form_data.append('oefIMRCallApiImportPdtSet', file_data);
        // alert(form_data);                             
        $.ajax({
            url: 'ServerEventImportPdtSet', // point to server-side PHP script 
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            Timeout : 0,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(res){
                JCNxCloseLoading();
            var paDataReturn = JSON.parse(res);
            if(paDataReturn['rtCode']=='1'){
                FSvCMNSetMsgSucessDialog('Success');
            }else{
                FSvCMNSetMsgErrorDialog(paDataReturn['rtDesc']);
            }
                // alert(res); 
                // display response from the PHP script, if any
            },
            error: function (jqXHR, textStatus, errorThrown) {
                    // JCNxResponseError(jqXHR, textStatus, errorThrown);
                    // JSxSaleImportUploadFile();
                }
        });
      }else{
        FSvCMNSetMsgSucessDialog('กรุณาเลือกไฟล');

      }
    }

    //Functionality : Event Call Page Register
    //Parameters : URL
    //Creator : 11/01/2021
    //Return : -
    //Return Type : -
    function JSvIMPInputFilePdtSet() {
        $(".input-file-account").before(
            function() {
                if ( ! $(this).prev().hasClass('input-ghost') ) {
                    var oElement = $("<input type='file' name='oefIMRCallApiImportPdtSet' id='oefIMRCallApiImportPdtSet' class='input-ghost' style='visibility:hidden; height:0'>");
                    oElement.attr("name",$(this).attr("name"));
                    oElement.change(function(){
                        oElement.next(oElement).find('input').val((oElement.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function(){
                        oElement.click();
                    });
                    $(this).find("button.btn-reset").click(function(){
                        oElement.val(null);
                        $(this).parents(".input-file-account").find('input').val('');
                    });
                    $(this).find('input').css("cursor","pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file-account').prev().click();
                        return false;
                    });
                    return oElement;
                }
            }
        );
    }

</script>
