<script>
	$('document').ready(function(){

		nStaPrcDoc = $('#ohdPkgStaPrcDoc').val();
		if(nStaPrcDoc != ''){
			$('.xWPKGSearchPanal').css('display','none');
		}else{
			$('.xWPKGSearchPanal').css('display','block');
		}

		$('.calendar').fullCalendar({
			dayClick: function (date, jsEvent, view) {
				$('#odvDate_Selected').html('');
				if(localStorage.DateSelect != ''){
					$(".fc-day[data-date]").css('background-color', '#fff');
				}

				$(this).css('background-color', '#c6c6c6');
				var dDateSelecte = date.format();  
				localStorage.DateSelect = dDateSelecte;

				$('#ohdPphCheckIn').val(dDateSelecte);

			},
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			defaultDate: '<?= date('Y-m-d')?>',
			navLinks: false, 
			editable: true,
			eventLimit: true,                 
			events: {
				url: 'Package_GetPkgFullCalendar',
				type: 'POST',
				data: { nPpkID : '<?=$nPpkID?>'},
				success: function (msg) {
					$('.icon-loading').hide();
				},
				error: function(data) {
					console.log(data);
					$('.icon-loading').hide();
				}
			},
			eventClick: function(event,date, jsEvent, view) {
				if(localStorage.DateSelect != ''){
					$(".fc-day[data-date]").css('background-color', '#fff');
				}
				$(this).css('background-color', '#087380');
				var dDateSelecte = event.datetime;  
				localStorage.DateSelect = dDateSelecte;

				$.ajax({
					type: "POST",
					url: "Package_GetPkgFullCalendarList",
					data: { nPpkID: event.id,
						dPphCheckIn: event.datetime
					},
					cache: false,
					success: function (msg) {
						$('#odvDate_Selected').html(msg);

						nStaPrcDoc = $('#ohdPkgStaPrcDoc').val();
						if(nStaPrcDoc == '1'){
							nHeight = $(window).height()-247;
							$('#olaDelHLDBtn').css('display','none')
						}
					},
					error: function (data) {
						console.log(data);
					}
				});
			}

		}); 

		$('.fc-state-default').click(function() {
			$('#ohdPphCheckIn').val('');
		});

	});

</script>
<div class="row">
	<div class="col-md-8">
		<div class="calendar"></div>
	</div>
	<div class="col-md-4">
		<input type="hidden" id="ohdPkgStaPrcDoc" value="<?= $oPkgDetail[0]->FTPkgStaPrcDoc?>">

		
		<div class="row xWPKGSearchPanal" style="margin-top: 8px;">
			<div class="col-md-4 xCNRemovePadding-Left">
				<input type="text" class="hide" id="ohdPpkID" value="<?=$nPpkID?>"> 
				<input type="text" class="hide" id="ohdPphCheckIn">

				<div class="form-group">
					<div class="wrap-input100 input100-select">
						<div>
							<select class="form-control input-valid" name="ocmPphSign" id="ocmPphSign">
								<option value="1"><?= language('ticket/package/package', 'tPkg_PackagePphSign1')?></option>
								<option value="-1"><?= language('ticket/package/package', 'tPkg_PackagePphSign-1')?></option>
							</select>
						</div>
						<span class="focus-input100"></span>
					</div>
				</div>

			</div>
			<div class="col-md-4 xCNRemovePadding-Left">
				<div class="form-group">
					<div class="wrap-input100 input100-select">
						<div>
							<select class="form-control input-valid" name="ocmPphAdjType" id="ocmPphAdjType">
								<optgroup label="<?= language('ticket/package/package', 'tPkg_TypeAdjustPrice')?>">
									<option value="1"><?= language('ticket/package/package', 'tPkg_PackageAdjType1')?></option>
									<option value="2"><?= language('ticket/package/package', 'tPkg_PackageAdjType2')?></option>
									<option value="3"><?= language('ticket/package/package', 'tPkg_PackageAdjType3')?></option>
								</optgroup>
							</select>
						</div>
						<span class="focus-input100"></span>
					</div>
				</div>

			</div>
			<div class="col-md-4 xCNRemovePadding-Left">
				<input type="hidden" id="ohdMsgPlsSelDate" value="<?= language('ticket/package/package', 'tPkg_PlsSelDate')?>">
				<input type="hidden" id="ohdMsgPlsEntAmtPerBht" value="<?= language('ticket/package/package', 'tPkg_PlsEntAmtPerBht')?>">
				<input type="hidden" id="ohdMsgEntNot0" value="<?= language('ticket/package/package', 'tPkg_EntNot0')?>">
				
				<div class="form-group">
					<div class="wrap-input100">
						<input class="input100" type="text" id="oetPphValue"
						name="oetPphValue"
						onkeypress="javascript: if (event.keyCode == 13) {event.preventDefault(); JSxPKGPkgSpcPriHLDSave();}"
						placeholder="<?= language('ticket/package/package', 'tPkg_AmountPerBaht')?>">
						<span class="focus-input100"></span>
					</div>
				</div>
			</div>
		</div>

		<div id="odvDate_Selected" style="margin-top:-10px;"></div>

	</div>
</div>