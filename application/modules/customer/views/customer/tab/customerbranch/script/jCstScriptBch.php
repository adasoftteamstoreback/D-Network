<script type="text/javascript">
$('#oliCstBch').unbind().click(function(){
    let tRoutePageAddOrEdit = '<?php echo @$tRoute;?>';
    if(tRoutePageAddOrEdit == 'customerEventAdd'){
        return;
    }else{
        JSvCstBchGetPageList();
    }
});

//ดึงข้อมูลมาแสดงหน้า List
function JSvCstBchGetPageList(pnPage) {
    try{
        var tSearchAll = $('#oetCstBchSearchAll').val();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == '') {
            nPageCurrent = '1';
        }
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "customerBchList",
            data: {
				tCstCode : $('#oetCstCode').val() ,
            },
            cache: false,
            Timeout: 5000,
            success: function(tResult) {
                JCNxCloseLoading();
                if (tResult != "") {
                    
                    $('#odvCstBchTab').html(tResult);
                    JSvCstBchGetDataTable();
                }
                $('#odvBtnAddEdit .xWBtnSave').addClass('hidden');
                $('#odvCstMasterImgContainer').addClass('hidden');
                // $('#odvContentContainer').removeClass('xWFullWidth');
               
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }catch(err){
        console.log('JSvCLNCustomerDataTable Error: ', err);
    }
}

//ดึงข้อมูลมาแสดงในตาราง
function JSvCstBchGetDataTable(pnPage) {
    try{
        var tSearchAll = $('#oetCstBchSearchAll').val();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == '') {
            nPageCurrent = '1';
        }
        $.ajax({
            type: "POST",
            url: "customerBchDataTable",
            data: {
				tCstCode : $('#oetCstCode').val() ,
                tSearchAll: tSearchAll,
                nPageCurrent: nPageCurrent
            },
            cache: false,
            Timeout: 5000,
            success: function(tResult) {
                if (tResult != "") {
                    $('#ostDataCustomerBranch').html(tResult);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }catch(err){
        console.log('JSvCLNCustomerDataTable Error: ', err);
    }
}

//paginations
function JSvCLBClickPage(ptPage) {
    try{
        var nPageCurrent = '';
        var nPageNew;
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld = $('.xWPageCstBch .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWPageCstBch .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSvCstBchGetDataTable(nPageCurrent);
    }catch(err){
        console.log('JSvCLBClickPage Error: ', err);
    }
}

//เรียกหน้า Add มาแสดง
function JSvCstBchCallPageAdd() {
    try{
        JCNxOpenLoading();
      
        $.ajax({
            type: "POST",
            url: "customerBchPageAdd",
            data: {
				tCstCode : $('#ohdCstCode').val() ,
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
  
                $('#odvCstBchTab').html(tResult);
   
                JCNxCloseLoading();
  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }catch(err){
        console.log('JSvCallPageCustomerAdd Error: ', err);
    }
}

//เรียกหน้า edit มาแสดง
function JSaCstBchCallEditEvent(pnCbrSeq){
    try{
        JCNxOpenLoading();
      
        $.ajax({    
            type: "POST",
            url: "customerBchPageEdit",
            cache: false,
            timeout: 0,
            data:{
                tCstCode : $('#ohdCstCode').val(),
                nCbrSeq  : pnCbrSeq,
            },
            success: function(tResult) {
                $('#odvCstBchTab').html(tResult);
                if ($('#ohdCstBchStatus').val() == 1) {
                    //ใช้งาน
                    $("#ocmCstBchStatus .selectpicker").val("1").selectpicker("refresh");
                }else{
                    //ไม่ใช้งาน
                    $("#ocmCstBchStatus .selectpicker").val("2").selectpicker("refresh");
                }
   
                JCNxCloseLoading();
  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }catch(err){
        console.log('JSvCallPageCustomerAdd Error: ', err);
    }


}

//insert/update event
function JSxCstBchAddUpdateEvent(){

    if($('#oetCstBchCode_InTab').val() == '' || $('#oetCstBchCode_InTab').val() == null){
        FSvCMNSetMsgWarningDialog('กรุณาระบุรหัสสาขา');
        return;
    }

    if($('#oetCstBchName_InTab').val() == '' || $('#oetCstBchName_InTab').val() == null){
        FSvCMNSetMsgWarningDialog('กรุณาระบุชื่ออ้างอิงสาขา');
        return;
    }

    var tCbrRoute = $('#ohdCstBchRoute').val();
    $.ajax({
        type    : "POST",
        url     : tCbrRoute,
        cache   : false,
        timeout : 0,
        data    : $("#ofmCstBchFormAdd").serialize(),
        success : function(tResult) {
        var aReturn = JSON.parse(tResult);
        if(aReturn['nStaEvent']=='01'){
            JSvCstBchGetPageList();
        }else{
            FSvCMNSetMsgSucessDialog('Error');
        }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}
</script>
