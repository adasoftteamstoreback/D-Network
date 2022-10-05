<script>

    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        if(bIsApvOrCancel){
            $('form .xCNApvOrCanCelDisabledPdtPmtHDRcv').attr('disabled', true);
            $('#otbPromotionStep4RcvConditionTable .xCNIconDel').addClass('xCNDocDisabled');
            $('#otbPromotionStep4RcvConditionTable .xCNIconDel').removeAttr('onclick', true);
        }else{
            $('form .xCNApvOrCanCelDisabledPdtPmtHDRcv').attr('disabled', false);
            $('#otbPromotionStep4RcvConditionTable .xCNIconDel').removeClass('xCNDocDisabled');
            $('#otbPromotionStep4RcvConditionTable .xCNIconDel').attr('onclick', 'JSxPromotionStep4RcvConditionDataTableDeleteByKey(this)');
        }

        // Check All Control
        $('.xCNListItemAll').on('click', function(){
            var bIsCheckedAll = $(this).is(':checked');
            // console.log('bIsCheckedAll: ', bIsCheckedAll);
            if(bIsCheckedAll){
                $('.xCNPromotionPdtPmtHDRcvRow .xCNListItem').prop('checked', true);
            }else{
                $('.xCNPromotionPdtPmtHDRcvRow .xCNListItem').prop('checked', false);     
            }
        });

    });

    /**
     * Functionality : เรียกหน้าของรายการ PdtPmtHDRcv in Temp
     * Parameters : -
     * Creator : 04/01/2021 Worakorn
     * Return : Table List
     * Return Type : View
     */
    function JSvPromotionStep4PriceGroupConditionDataTableClickPage(ptPage) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xCNPromotionPdtPmtHDRcvPage .xWBtnNext").addClass("disabled");
                nPageOld = $(".xCNPromotionPdtPmtHDRcvPriPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xCNPromotionPdtPmtHDRcvPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxPromotionStep4GetHDRcvInTmp(nPageCurrent, true);
    }

    /**
     * Functionality : Update PdtPmtHDRcv in Temp by Primary Key
     * Parameters : -
     * Creator : 04/01/2021 Worakorn
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4RcvConditionDataTableEditInline(poElm){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $(poElm).parents('.xCNPromotionPdtPmtHDRcvRow').data('bch-code');
            var tDocNo = $(poElm).parents('.xCNPromotionPdtPmtHDRcvRow').data('doc-no');
            var tRcvCode = $(poElm).parents('.xCNPromotionPdtPmtHDRcvRow').data('rcv-code');
            var tPmhStaType = $(poElm).val();

            $.ajax({
                type: "POST",
                url: "promotionStepeUpdateRcvConditionInTmp",
                data: {
                    tDocNo: tDocNo,
                    tRcvCode: tRcvCode,
                    tBchCode: tBchCode,
                    tPmhStaType: tPmhStaType
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $nCurrentPage = $('.xCNPromotionPmtBrandDtPage').find('.btn.xCNBTNNumPagenation.active').text();
                    JSxPromotionStep4GetHDRcvInTmp($nCurrentPage, false);
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
    function JSxPromotionStep4RcvConditionDataTableDeleteByKey(poElm) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            JCNxOpenLoading();

            var tBchCode = $(poElm).parents('.xCNPromotionPdtPmtHDRcvRow').data('bch-code');
            var tDocNo = $(poElm).parents('.xCNPromotionPdtPmtHDRcvRow').data('doc-no');
            var tRcvCode = $(poElm).parents('.xCNPromotionPdtPmtHDRcvRow').data('rcv-code');


            $.ajax({
                type: "POST",
                url: "promotionStep4DeleteRcvConditionInTmp",
                data: {
                    tBchCode: tBchCode,
                    tDocNo: tDocNo,
                    tRcvCode: tRcvCode
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $nCurrentPage = $('.xCNPromotionPdtPmtHDRcvPage').find('.btn.xCNBTNNumPagenation.active').text();
                    JSxPromotionStep4GetHDRcvInTmp($nCurrentPage, true);
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