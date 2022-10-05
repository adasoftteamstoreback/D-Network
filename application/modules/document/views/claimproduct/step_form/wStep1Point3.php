<div class="row">

    <!--ค้นหา-->
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control xCNInputWithoutSingleQuote" autocomplete="off" id="oetSearchStep1Point3PdtHTML" name="oetSearchStep1Point3PdtHTML" onkeyup="JSvCLMStep1Point3SearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                <span class="input-group-btn">
                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvCLMStep1Point3SearchPdtHTML()">
                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                    </button>
                </span>
            </div>
        </div>
    </div>

</div>

<!--สินค้า-->
<div class="row" id="odvCLMStep1Point3DataPdtTableDTTemp"></div>

<script>

    $( document ).ready(function() {
        //โหลดสินค้า
        JSxCLMStep1Point3LoadDatatable();
    });

    //โหลดสินค้า (Point3)
    function JSxCLMStep1Point3LoadDatatable(){

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        $.ajax({
            type    : "POST",
            url     : "docClaimStep1Point3Datatable",
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
                'ptCLMStaApv'           : '<?=@$tCLMStaApv?>'
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1') {
                    $('#odvCLMStep1Point3DataPdtTableDTTemp').html(aReturnData['tViewDataTable']);
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ค้นหาสินค้าใน temp
    function JSvCLMStep1Point3SearchPdtHTML() {
        var value = $("#oetSearchStep1Point3PdtHTML").val().toLowerCase();
        $("#otbCLMStep1Point3DocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

</script>