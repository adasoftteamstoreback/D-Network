<script>

/**
 * Functionality : Add head of receipt row
 * Parameters : -
 * Creator : 07/06/2021 Off
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxPdtSVAddRow(){
    try{
        if(JCNnPdtSVCountRow('head') >= 10){return;}
        
        let nIndex = JCNnPdtSVGetMaxID('head');
        console.log('MaxID: ', JCNnPdtSVGetMaxID('head'));
        
        // Get template in wSlipMessageAdd.php
        var template = $.validator.format($.trim($('#oscSlipHeadRowTemplate').html()));
        // Add template
        $(template(++nIndex)).appendTo("#odvSmgSlipHeadContainer");
    }catch(err){
        console.log('JSxPdtSVAddRow Error: ', err);
    }
}

/**
 * Functionality : Open Model Delete
 * Parameters :
 * Creator : 13/07/2021 Off
 * Last Modified : -
 * Return : Row count
 * Return Type : number
 */
function JSxPdtSVEventDeleteDetail(poElement = null, poEvent = null) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#odvModalDeletePdtSVSetDetail #ospTextConfirmDelPdtSVSet').html($('#oetTextComfirmDeleteSingle').val());
        $('#odvModalDeletePdtSVSetDetail').modal('show');
        $('#odvModalDeletePdtSVSetDetail #osmConfirmDelPdtSVSet').unbind().click(function() {
            JSxPdtSvDeleteRowHead(poElement, poEvent)
            $('.modal-backdrop').remove();
            $('#odvModalDeletePdtSVSetDetail').modal('hide');
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : Count row in head of receipt or end of receipt
 * Parameters : ptReceiptPosition is position for limit(head or end)
 * Creator : 06/09/2018 piya
 * Last Modified : -
 * Return : Row count
 * Return Type : number
 */
function JCNnPdtSVCountRow(ptReceiptPosition){
    try{
        if(ptReceiptPosition == 'head'){
            let nHeadRow = $('#odvSmgSlipHeadContainer .xWSmgItemSelect').length;
            return nHeadRow;
        }
    }catch(err){
        console.log('JCNnPdtSVCountRow Error: ', err);
    }
}

/**
 * Functionality : Prepare sort number after move row
 * Parameters : ptReceiptType is type for sorting(head, end), 
 * pbUseStringFormat is use string format? (set true return string format, set false return object format)
 * Creator : 07/09/2018 piya
 * Last Modified : -
 * Return : Head of receipt or End of receipt value
 * Return Type : object
 */
function JSoSlipPdtSVSortabled(ptReceiptType, pbUseStringFormat){
    try{
        if(ptReceiptType == 'head'){
            let aSortData = JSaPdtSVGetSortData('head');
            let aSortabled = {};
            $.each(aSortData, (pnIndex, pnValue) => {
                let tValue = $('#odvSmgSlipHeadContainer .xWSmgItemSelect[id=' + pnValue + ']').find('.xWSmgDyForm').val();
                aSortabled[pnIndex] = tValue;
            });
            // console.log('Sortabled: ', aSortabled);
            if(pbUseStringFormat){
                return JSON.stringify(aSortabled);
            }else{
                return aSortabled;
            }
        }
    }catch(err){
        console.log('JSoSlipPdtSVSortabled Error: ', err);
    }
}

/**
 * Functionality : Delete head recive or end recive row
 * Parameters : poElement is Itself element, poEvent is Itself event
 * Creator : 07/06/2021 Off
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxPdtSvDeleteRowHead(poElement = null, poEvent = null){
    try{
        if((JCNnPdtSVCountRow('head') == 1)){return;}
            $(poElement).parents('.xWSmgItemSelect').remove();
        
    }catch(err){
        console.log('JSxSlipMessageDeleteRow Error: ', err);
    }
}

/**
 * Functionality : {description}
 * Parameters : ptReceiptType is type for Head of receipt("head") End of receipt("end"),
 * Creator : 07/09/2018 piya
 * Last Modified : -
 * Return : Max id number
 * Return Type : number
 */
function JCNnPdtSVGetMaxID(ptReceiptType){
    try{
        if(JCNnPdtSVCountRow(ptReceiptType) <= 0){return 0;}

        if(ptReceiptType == 'head'){
            let nMaxID = 0;
            let oHeadItems = $('#odvSmgSlipHeadContainer .xWSmgItemSelect');
            oHeadItems.each((pnIndex, poElement) => {
                let tElementID = $(poElement).attr('id');
                if(nMaxID < tElementID){
                    nMaxID = tElementID;
                }
            });
            return nMaxID;
        }
    }catch(err){
        console.log('JCNnPdtSVGetMaxID Error: ', err);
    }
}

/**
 * Functionality : Display head of receipt and end of receipt row
 * Parameters : ptReceiptType is type for Head of receipt("head") End of receipt("end"), 
 * pnRows is number for row item
 * Creator : 07/09/2018 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxPdtSVRowDefualt(ptReceiptType, pnRows){
    try{
        // Validate pnRows
        if(pnRows <= 0){return;}// Invalid exit function
        
        if(ptReceiptType == "head"){
            tReceiptType = "Head";
        }
        
        // Get template in wSlipMessageAdd.php
        var template = $.validator.format($.trim($("#oscSlip" + tReceiptType + "RowTemplate").html()));
        
        // Add template by pnRows
        for(let loop=1; loop<=pnRows; loop++){
            $(template(loop)).appendTo("#odvSmgSlip" + tReceiptType + "Container");
        }
    }catch(err){
        console.log('JSxPdtSVRowDefualt Error: ', err);
    }
}

/**
 * Functionality : Get data sort from sortable plugin
 * Parameters : ptReceiptType is type for get sort data(head, end)
 * Creator : 07/08/2018 piya
 * Last Modified : -
 * Return : Sort data
 * Return Type : array
 */
function JSaPdtSVGetSortData(ptReceiptType){
    try{
        if(ptReceiptType == 'head'){
            if(!(localStorage.getItem('headReceiptSort') == null)){
                return JSON.parse(localStorage.getItem('headReceiptSort'));
            }
        }
        if(ptReceiptType == 'end'){
            if(!(localStorage.getItem('endReceiptSort') == null)){
                return JSON.parse(localStorage.getItem('endReceiptSort'));
            }
        }
    }catch(err){
        console.log('JSaPdtSVGetSortData Error: ', err);
    }
}

/**
 * Functionality : Remove data sort from sortable plugin
 * Parameters : ptReceiptType is type for remove sort data(head, end, all)
 * Creator : 07/08/2018 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxPdtSVRemoveSortData(ptReceiptType){
    try{
        if(ptReceiptType == 'head'){
            localStorage.removeItem('headReceiptSort');
        }
        if(ptReceiptType == 'end'){
            localStorage.removeItem('endReceiptSort');
        }
        if(ptReceiptType == 'all'){
            localStorage.removeItem('headReceiptSort');
            localStorage.removeItem('endReceiptSort');
        }
    }catch(err){
        console.log('JSxPdtSVRemoveSortData Error: ', err);
    }
}
</script>

<script>
    $('document').ready(function() {
        if($('#ohdCheckType').val() != '2'){
            $('.xWPdtDetailList').hide();
            $('#odvAddMore').hide();
            $('.xWSmgItemSelect').remove();
            $('#odvProductSvStatus').hide();
        }

        if($('#ohdCheckType').val() == 1){
        // $("#ocbPdtSVStatus").prop('checked', true);
        // $('.xWPdtDetailList').show();
        // $('.xWSmgSortContainer').show();
        
        }else{
            // $('.xWPdtDetailList').hide(); 
        // $('.xWSmgSortContainer').hide();
        }

        $( ".xCNRadioType" ).each(function( index ) {
            if($(this).val() == $('#ohdtAnwserType').val()){
                $(this).prop("checked", true);
                if($('#ohdtAnwserType').val() == '1'){
                    $('#odvAddMore').hide();
                }
            }
        });

        // if($('#ohdtAnwserType').val() != '2'){
        //     $('#odvAddMore').hide();
        // }

        if($('#ohdtAnwserType').val() == '1'){
            $('.xWMoveIcon').hide();
            $('.xWIconDelete').hide();
            $('#odvClassType').removeClass('input-group');
        }

        $('#odvSmgSlipHeadContainer').sortable({
            items: '.xWSmgItemSelect',
            opacity: 0.7,
            axis: 'y',
            handle: '.xWSmgMoveIcon',
            update: function(event, ui) {
                var aToArray = $(this).sortable('toArray');
                var aSerialize = $(this).sortable('serialize', {
                    key: ".sort"
                });
            }
        });
    });

    
    if($('#ohdtStaEnter').val() != '2'){
            JSxPdtSVRowDefualt('head', 1);
            $('#odvAddMore').show();
    }



    $('#ocmPdtSvType').change(function(event) {
        if ($(this).val() == 1) {
            $('#odvProductSvStatus').hide();
            $('.xWPdtDetailList').hide();
            $('.xWSmgItemSelect').remove();
            $('#odvAddMore').hide();
        } else {
            $('#odvProductSvStatus').show();
            $('.xWPdtDetailList').show();
            $('.xWSmgItemSelect').remove();
            $('.xWSmgSortContainer').show();
            $( ".xCNRadioType" ).each(function( index ) {
                if($(this).val() == $('#ohdtAnwserType').val()){
                    $(this).prop("checked", true);
                }
            });
            JSxPdtSVRowDefualt('head', 1);
            $('#odvAddMore').show();
        }
    });

    // $('#ocbPdtSVStatus').change(function(event) {
    //     if($(this).is(':checked') == true){
    //         $('.xWSmgItemSelect').remove();
    //         $('.xWSmgSortContainer').show();
    //         $( ".xCNRadioType" ).each(function( index ) {
    //             if($(this).val() == $('#ohdtAnwserType').val()){
    //                 $(this).prop("checked", true);
    //             }
    //         });
    //         JSxPdtSVRowDefualt('head', 1);
    //         $('#odvAddMore').show();
    //     }else{
    //         $('.xWSmgSortContainer').hide();
    //         $('.xWSmgItemSelect').remove();
    //         $('#odvAddMore').hide();
    //         $("input:radio").attr("checked", false);
    //     }
    // });

    $('.xCNRadioType').change(function(event) {
        var nOldType = $('#ohdtOldAnwserType').val();
        if((nOldType == '2' || nOldType == '3') && $(this).val() == '1'){
            $('.xWSmgItemSelect').remove();
            JSxPdtSVRowDefualt('head', 1);
            $('#odvAddMore').hide();
            $('.xWMoveIcon').hide();
            $('.xWIconDelete').hide();
            $('#odvClassType').removeClass('input-group');
        } else if ((nOldType == '1') && ($(this).val() == '2' || $(this).val() == '3') ){
            $('.xWSmgItemSelect').remove();
            JSxPdtSVRowDefualt('head', 1);
            $('#odvAddMore').show();
        }
        $('#ohdtOldAnwserType').val($(this).val());
    });


    
</script>

<script type="text/html" id="oscSlipHeadRowTemplate">
    <div class="form-group xWSmgItemSelect" id="{0}">
        <div class="input-group validate-input" id='odvClassType'>
            <span class="input-group-btn xWMoveIcon">
                <div class="btn xWSmgMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
            </span>

            <div class="row">
                <div class="col-md-12 XWMsgType{0}">
                    <input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetSVPdtValue{0}" name="oetSVPdtValue[{0}]" value="" data-validate="<?php echo language('pos/slipMessage/slipmessage', 'tSMGValidHead'); ?>" required>
                </div>
                <div>
                </div>
            </div>
            <span class="input-group-btn">
                <img class="xCNIconTable xWIconDelete" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onclick="JSxPdtSVEventDeleteDetail(this, event)" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
            </span>
        </div>
</script>

