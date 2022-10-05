<script type="text/javascript">
$('#oliCstBchAddr').unbind().click(function(){
    if($('#oetCstBchCode_InTab').val() !='' ){
        JSvCstBchAddrGetPageList();
    }
});



// Functionality: Set Branch Address Nav Default
function JCNxBranchAddressSetNavDefault(){
    // Hide Title And Button Default
    $('#olbBranchAddressAdd').hide();
    $('#olbBranchAddressEdit').hide();
    $('#odvBranchAddressBtnGrpAddEdit').hide();
    // Show Title And Button Default
    $('#olbBranchAddressInfo').show();
    $('#odvBranchAddressBtnGrpInfo').show();
}

//แสดงหน้า Customer Branch Address List
function JSvCstBchAddrGetPageList(pnPage) {
    try{
        var tSearchAll = $('#oetBchAddrSearchAll').val();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == '') {
            nPageCurrent = '1';
        }
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "customerBchAddrList",
            cache: false,
            Timeout: 5000,
            data: {
                tCstCode : $('#ohdCstCode').val() ,
                tCstBchCode : $('#ohdCstBchCode').val(),
                nPageCurrent: nPageCurrent
            },
            success: function(tResult) {
                JCNxCloseLoading();
                if (tResult != "") {
                    $('#odvBchTabAddr').html(tResult);
                    JSvCstBchAddrGetDataTable();
                    JCNxBranchAddressSetNavDefault();
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
        console.log('JSvTBACustomerDataTable Error: ', err);
    }
}



//แสดงหน้า Customer Branch Address Datatable
function JSvCstBchAddrGetDataTable(pnPage) {
    try{
        //var tSearchAll = $('#oetBchAddrSearchAll').val();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == '') {
            nPageCurrent = '1';
        }
        $.ajax({
            type: "POST",
            url: "customerBchAddrDataTable",
            data: {
                tCstCode : $('#ohdCstCode').val() ,
                tCstBchCode : $('#oetCstBchCode_InTab').val(),
                nPageCurrent: nPageCurrent
            },
            cache: false,
            Timeout: 5000,
            success: function(tResult) {
                if (tResult != "") {
                    $('#odvCstBchAddressContent').html(tResult);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }catch(err){
        console.log('JSvTBACustomerDataTable Error: ', err);
    }
}



//แสดง Paginations
function JSvTBAClickPage(ptPage) {
    try{
        var nPageCurrent = '';
        var nPageNew;
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld = $('.xWPageCst .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld = $('.xWPageCst .active').text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSvCstBchAddrGetDataTable(nPageCurrent);
    }catch(err){
        console.log('JSvTBAClickPage Error: ', err);
    }
}

//แสดงหน้า Customer Branch Address Page Add
function JSvCstBchAddrCallPageAdd() {
    try{
        JCNxOpenLoading();
      
        $.ajax({
            type: "POST",
            url: "customerBchAddrPageAdd",
            cache: false,
            timeout: 0,
            data:{
                tCstCode : $('#ohdCstCode').val() ,
                tCstBchCode : $('#oetCstBchCode_InTab').val(),
            },
            success: function(tResult) {
                JCNxCloseLoading();
                $('#odvCstBchAddressContent').html(tResult);
                // Hide Title And Button
                $('#olbBranchAddressEdit').hide();
                $('#odvBranchAddressBtnGrpInfo').hide();
                // Show Title And Button
                $('#olbBranchAddressAdd').show();
                $('#odvBranchAddressBtnGrpAddEdit').show();
                
  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }catch(err){
        console.log('JSvCallPageCustomerAdd Error: ', err);
    }
}

//แสดงหน้า Customer Branch Address Page Edit
function JSvCstBchAddrCallPageEdit(poBranchAddressData){
    try{
        JCNxOpenLoading();
        $.ajax({    
            type: "POST",
            url: "customerBchAddrPageEdit",
            cache: false,
            timeout: 0,
            data:poBranchAddressData,
            success: function(tResult) {
  
                $('#odvCstBchAddressContent').html(tResult);
                // Hide Title And Button
                $('#olbBranchAddressEdit').show();
                $('#odvBranchAddressBtnGrpInfo').hide();
                // Show Title And Button
                $('#olbBranchAddressAdd').hide();
                $('#odvBranchAddressBtnGrpAddEdit').show();
   
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

//Insert / Update Event
function JSxTBAAddUpdateEvent(){
    JCNxOpenLoading();
    var tCbrRoute = $('#ofmBranchAddressForm #ohdBranchAddressRoute').val();
    var oDataBchAddr =   $('#ofmBranchAddressForm').serialize();
    $.ajax({
        type: "POST",
        url: tCbrRoute,
        cache: false,
        timeout: 0,
        data:oDataBchAddr,
        success: function(tResult) {
        JCNxCloseLoading();
        var aReturn = JSON.parse(tResult);
        if(aReturn['nStaReturn']==1){
            JSvCstBchAddrGetDataTable();
            JCNxBranchAddressSetNavDefault();
        }else{
            FSvCMNSetMsgSucessDialog('Error');
        }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
        }
    })
}

//ลบข้อมูล Customer Branch Address
function JSaCstBchAddrDelete(poBranchAddressData){
    JCNxOpenLoading();
    $.ajax({
          type: "POST",
          url: "customerBchAddrDelete",
          cache: false,
          timeout: 0,
          data:poBranchAddressData,
          success: function(tResult) {
            JCNxCloseLoading();
            var aReturn = JSON.parse(tResult);
            $('#odvCstBchAddrModalDel').modal('hide');
            if(aReturn['nStaReturn']==1){
                JSvCstBchAddrGetDataTable();
                JCNxBranchAddressSetNavDefault();
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
