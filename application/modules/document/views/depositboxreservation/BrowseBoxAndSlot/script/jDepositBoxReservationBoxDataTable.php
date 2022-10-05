<script type="text/javascript">

function JSxDoubleClickSelection(ptElm) 
{  
    var tSeqNo   = $('#ohdSeqNo').val();
    var nBrowseType   = $('#ohdBrowseType').val();
    var tBchCode = $(ptElm).data('bchcode');
    var tBchName = $(ptElm).data('bchname');
    var tBoxNo   = $(ptElm).data('boxno');
    var tBoxName = $(ptElm).data('boxname');
    var tShopCode = $(ptElm).data('shpcode');

    if (nBrowseType == 1) {
        $('#oetDBRFrmBchCode').val(tBchCode);
        $('#oetDBRFrmBchName').val(tBchName);
        $('#ohdBox'+tSeqNo+'').val(tBoxNo);
        $('#oetBox'+tSeqNo+'').val(tBoxName);
        $('#ohdShp'+tSeqNo+'').val(tShopCode);
        JSxEditBoxOrSlot(tSeqNo,nBrowseType);
        $('#odvDBRModalBrowseBox').modal('hide');
    } else {
        var tShpCode = $(ptElm).data('shpcode');
        var tLayCode = $(ptElm).data('laycode');
        var tLayName = $(ptElm).data('layname');
        var tStatus = $(ptElm).data('pshstause');
        if(tStatus == '2'){
            FSvCMNSetMsgErrorDialog("ไม่สามารถเลือกช่องฝากนี้ได้เนื่องจากช่องฝากนี้ถูกใช้งานแล้ว");
        }else if(tStatus == '3'){
            FSvCMNSetMsgErrorDialog("ไม่สามารถเลือกช่องฝากนี้ได้เนื่องจากช่องฝากนี้ปิดการใช้งาน");
        }else if(tStatus == '4'){
            FSvCMNSetMsgErrorDialog("ไม่สามารถเลือกช่องฝากนี้ได้เนื่องจากช่องฝากนี้ถูกจองแล้ว");
        }else{
            $('#oetDBRFrmBchCode').val(tBchCode);
            $('#oetDBRFrmBchName').val(tBchName);
            $('#ohdBox'+tSeqNo+'').val(tBoxNo);
            $('#oetBox'+tSeqNo+'').val(tBoxName);
            $('#ohdSlot'+tSeqNo+'').val(tLayCode);
            $('#oetSlot'+tSeqNo+'').val(tLayName);
            $('#ohdShp'+tSeqNo+'').val(tShpCode);
            JSxEditBoxOrSlot(tSeqNo,nBrowseType);
            $('#odvDBRModalBrowseBox').modal('hide');
        }
    }
    
}

$('.xBoxOrSlot').click(function(){
    $('.xBoxOrSlot').removeClass('active');
    $(this).addClass('active');
})

// Function Check Data Search And Add In Tabel DT Temp
function JSvDBRRefIntClickPageList(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld    = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld    = $('.xWPIPageDataTable .active').text(); // Get เลขก่อนหน้า
            nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSxRefIntDocHDDataTable(nPageCurrent);
}


$('#obtConfirmBrowseBox').click(function(){
    var nBrowseType     = $('#ohdBrowseType').val();
    var tBchCode        = $('.xBoxOrSlot.active').data('bchcode');
    var tBchName        = $('.xBoxOrSlot.active').data('bchname');
    var tBoxNo          = $('.xBoxOrSlot.active').data('boxno');
    var tBoxName        = $('.xBoxOrSlot.active').data('boxname');
    var tShopCode       = $('.xBoxOrSlot.active').data('shpcode');
    var tSeqNo   = $('#ohdSeqNo').val();

    if (nBrowseType == 1) {
        $('#oetDBRFrmBchCode').val(tBchCode);
        $('#oetDBRFrmBchName').val(tBchName);
        $('#ohdBox'+tSeqNo+'').val(tBoxNo);
        $('#oetBox'+tSeqNo+'').val(tBoxName);
        $('#ohdShp'+tSeqNo+'').val(tShopCode);
        JSxEditBoxOrSlot(tSeqNo,nBrowseType);
        $('#odvDBRModalBrowseBox').modal('hide');
    } else {
        var tShpCode = $('.xBoxOrSlot.active').data('shpcode');
        var tLayCode = $('.xBoxOrSlot.active').data('laycode');
        var tLayName = $('.xBoxOrSlot.active').data('layname');
        var tStatus  = $('.xBoxOrSlot.active').data('layname');
        var tStatus  = $('.xBoxOrSlot.active').data('pshstause');
        if(tStatus == '2'){
            FSvCMNSetMsgErrorDialog("ไม่สามารถเลือกช่องฝากนี้ได้เนื่องจากช่องฝากนี้ถูกใช้งานแล้ว");
        }else if(tStatus == '3'){
            FSvCMNSetMsgErrorDialog("ไม่สามารถเลือกช่องฝากนี้ได้เนื่องจากช่องฝากนี้ปิดการใช้งาน");
        }else if(tStatus == '4'){
            FSvCMNSetMsgErrorDialog("ไม่สามารถเลือกช่องฝากนี้ได้เนื่องจากช่องฝากนี้ถูกจองแล้ว");
        }else{
            $('#oetDBRFrmBchCode').val(tBchCode);
            $('#oetDBRFrmBchName').val(tBchName);
            $('#ohdBox'+tSeqNo+'').val(tBoxNo);
            $('#oetBox'+tSeqNo+'').val(tBoxName);
            $('#ohdSlot'+tSeqNo+'').val(tLayCode);
            $('#oetSlot'+tSeqNo+'').val(tLayName);
            $('#ohdShp'+tSeqNo+'').val(tShpCode);
            JSxEditBoxOrSlot(tSeqNo,nBrowseType);
            $('#odvDBRModalBrowseBox').modal('hide');
        }
    }

    
});

//เเก้ไขตู้ฝากและช่อง
function JSxEditBoxOrSlot(pnSeq,pnBrowseType){
    var tDBRDocNo       = $("#oetDBRDocNo").val();
    var tDBRBchCode     = $("#ohdDBRBchCode").val();
    var tName           = $('#ohdPdtName'+pnSeq).val();
    if (pnBrowseType == 1) {
        var tPshType    = $('.xBoxOrSlot.active').data('pshtype');
        var tBoxOrSlot  = $('#ohdBox'+pnSeq).val();
        var tShpCode  = $('#ohdShp'+pnSeq).val();
        if(pnSeq != undefined){
            $.ajax({
                type    : "POST",
                url     : "docDBREditPdtInDTDocTemp",
                data    : {
                    'tDBRBchCode'        : tDBRBchCode,
                    'tDBRDocNo'          : tDBRDocNo,
                    'nDBRSeqNo'          : pnSeq,
                    'FTXtdPdtName'      : tName,
                    'nQty'              : tBoxOrSlot,
                    'tShpCode'           : tShpCode,
                    'nBrowseType'       : pnBrowseType
                },
                catch   : false,
                timeout : 0,
                success : function (oResult){
                    if(tPshType == '1'){
                        $("#obtDBRBrowseBoxsub"+pnSeq).prop("disabled",false);
                        $("#oetSlot"+pnSeq).val('');
                        $("#ohdSlot"+pnSeq).val('');
                    }else if(tPshType == '2'){
                        $("#obtDBRBrowseBoxsub"+pnSeq).prop("disabled",true);
                        $("#oetSlot"+pnSeq).val('Virtual');
                        $("#ohdSlot"+pnSeq).val('0');
                    }
                 },
                error   : function (jqXHR, textStatus, errorThrown) { }
            });
        }
    }else{
        var tBox   = $('#ohdBox'+pnSeq).val();
        var tSlot  = $('#ohdSlot'+pnSeq).val();
        var tShpCode    = $('#ohdShp'+pnSeq).val();
        if(pnSeq != undefined){
            $.ajax({
                type    : "POST",
                url     : "docDBREditPdtInDTDocTemp",
                data    : {
                    'tDBRBchCode'        : tDBRBchCode,
                    'tDBRDocNo'          : tDBRDocNo,
                    'nDBRSeqNo'          : pnSeq,
                    'FTXtdPdtName'       : tName,
                    'tBox'               : tBox,
                    'nQty'               : tSlot,
                    'tShpCode'           : tShpCode,
                    'nBrowseType'        : pnBrowseType
                },
                catch   : false,
                timeout : 0,
                success : function (oResult){ },
                error   : function (jqXHR, textStatus, errorThrown) { }
            });
        }
    }

    
}

</script>