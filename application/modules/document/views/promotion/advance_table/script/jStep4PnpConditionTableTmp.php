<script>

    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        if(bIsApvOrCancel){
            $('form .xCNApvOrCanCelDisabledPdtPmtHDPnp').attr('disabled', true);
            $('#otbPromotionStep4PnpConditionTable .xCNIconDel').addClass('xCNDocDisabled');
            $('#otbPromotionStep4PnpConditionTable .xCNIconDel').removeAttr('onclick', true);
        }else{
            $('form .xCNApvOrCanCelDisabledPdtPmtHDPnp').attr('disabled', false);
            $('#otbPromotionStep4PnpConditionTable .xCNIconDel').removeClass('xCNDocDisabled');
            $('#otbPromotionStep4PnpConditionTable .xCNIconDel').attr('onclick', 'JSxPromotionStep4PnpConditionDataTableDeleteByKey(this)');
        }

        // Check All Control
        $('.xCNListItemAll').on('click', function(){
            var bIsCheckedAll = $(this).is(':checked');
            // console.log('bIsCheckedAll: ', bIsCheckedAll);
            if(bIsCheckedAll){
                $('.xCNPromotionPdtPmtHDPnpRow .xCNListItem').prop('checked', true);
            }else{
                $('.xCNPromotionPdtPmtHDPnpRow .xCNListItem').prop('checked', false);     
            }
        });

    });

    /**
     * Functionality : เรียกหน้าของรายการ PdtPmtHDPnp in Temp
     * Parameters : -
     * Creator : 04/01/2021 Worakorn
     * Return : Table List
     * Return Type : View
     */
    function JSvPromotionStep4PriceGroupConditionDataTableClickPage(ptPage) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xCNPromotionPdtPmtHDPnpPage .xWBtnNext").addClass("disabled");
                nPageOld = $(".xCNPromotionPdtPmtHDPnpPriPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xCNPromotionPdtPmtHDPnpPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxPromotionStep4GetHDPnpInTmp(nPageCurrent, true);
    }

    /**
     * Functionality : Update PdtPmtHDPnp in Temp by Primary Key
     * Parameters : -
     * Creator : 04/01/2021 Worakorn
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4PnpConditionDataTableEditInline(poElm){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $(poElm).parents('.xCNPromotionPdtPmtHDPnpRow').data('bch-code');
            var tDocNo = $(poElm).parents('.xCNPromotionPdtPmtHDPnpRow').data('doc-no');
            var tPnpCode = $(poElm).parents('.xCNPromotionPdtPmtHDPnpRow').data('pnp-code');
            var tPmhStaType = $(poElm).val();

            $.ajax({
                type: "POST",
                url: "promotionStepeUpdatePnpConditionInTmp",
                data: {
                    tDocNo: tDocNo,
                    tPnpCode: tPnpCode,
                    tBchCode: tBchCode,
                    tPmhStaType: tPmhStaType
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $nCurrentPage = $('.xCNPromotionPmtBrandDtPage').find('.btn.xCNBTNNumPagenation.active').text();
                    JSxPromotionStep4GetHDPnpInTmp($nCurrentPage, false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Delete PdtPmtHDCstPri in Temp by Primary Key
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4PnpConditionDataTableDeleteByKey(poElm) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            JCNxOpenLoading();

            var tBchCode = $(poElm).parents('.xCNPromotionPdtPmtHDPnpRow').data('bch-code');
            var tDocNo = $(poElm).parents('.xCNPromotionPdtPmtHDPnpRow').data('doc-no');
            var tPnpCode = $(poElm).parents('.xCNPromotionPdtPmtHDPnpRow').data('pnp-code');


            $.ajax({
                type: "POST",
                url: "promotionStep4DeletePnpConditionInTmp",
                data: {
                    tBchCode: tBchCode,
                    tDocNo: tDocNo,
                    tPnpCode: tPnpCode
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $nCurrentPage = $('.xCNPromotionPdtPmtHDPnpPage').find('.btn.xCNBTNNumPagenation.active').text();
                    JSxPromotionStep4GetHDPnpInTmp($nCurrentPage, true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    JCNxCloseLoading();
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }
</script>