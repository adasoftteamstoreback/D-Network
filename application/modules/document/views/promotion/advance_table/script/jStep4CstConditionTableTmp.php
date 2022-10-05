<script>

    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        if(bIsApvOrCancel){
            $('form .xCNApvOrCanCelDisabledPdtPmtHDCst').attr('disabled', true);
            $('#otbPromotionStep4CstConditionTable .xCNIconDel').addClass('xCNDocDisabled');
            $('#otbPromotionStep4CstConditionTable .xCNIconDel').removeAttr('onclick', true);
        }else{
            $('form .xCNApvOrCanCelDisabledPdtPmtHDCst').attr('disabled', false);
            $('#otbPromotionStep4CstConditionTable .xCNIconDel').removeClass('xCNDocDisabled');
            $('#otbPromotionStep4CstConditionTable .xCNIconDel').attr('onclick', 'JSxPromotionStep4CstConditionDataTableDeleteByKey(this)');
        }

        // Check All Control
        $('.xCNListItemAll').on('click', function(){
            var bIsCheckedAll = $(this).is(':checked');
            // console.log('bIsCheckedAll: ', bIsCheckedAll);
            if(bIsCheckedAll){
                $('.xCNPromotionPdtPmtHDCstRow .xCNListItem').prop('checked', true);
            }else{
                $('.xCNPromotionPdtPmtHDCstRow .xCNListItem').prop('checked', false);     
            }
        });

    });

    /**
     * Functionality : เรียกหน้าของรายการ PdtPmtHDCst in Temp
     * Parameters : -
     * Creator : 04/01/2021 Worakorn
     * Return : Table List
     * Return Type : View
     */
    function JSvPromotionStep4PriceGroupConditionDataTableClickPage(ptPage) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xCNPromotionPdtPmtHDCstPage .xWBtnNext").addClass("disabled");
                nPageOld = $(".xCNPromotionPdtPmtHDCstPriPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xCNPromotionPdtPmtHDCstPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxPromotionStep4GetHDCstInTmp(nPageCurrent, true);
    }

    /**
     * Functionality : Update PdtPmtHDCst in Temp by Primary Key
     * Parameters : -
     * Creator : 04/01/2021 Worakorn
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4CstConditionDataTableEditInline(poElm){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $(poElm).parents('.xCNPromotionPdtPmtHDCstRow').data('bch-code');
            var tDocNo = $(poElm).parents('.xCNPromotionPdtPmtHDCstRow').data('doc-no');
            var tCstCode = $(poElm).parents('.xCNPromotionPdtPmtHDCstRow').data('cst-code');
            var tPmhStaType = $(poElm).val();

            $.ajax({
                type: "POST",
                url: "promotionStepeUpdateCstConditionInTmp",
                data: {
                    tDocNo: tDocNo,
                    tCstCode: tCstCode,
                    tBchCode: tBchCode,
                    tPmhStaType: tPmhStaType
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $nCurrentPage = $('.xCNPromotionPmtBrandDtPage').find('.btn.xCNBTNNumPagenation.active').text();
                    JSxPromotionStep4GetHDCstInTmp($nCurrentPage, false);
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
    function JSxPromotionStep4CstConditionDataTableDeleteByKey(poElm) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            JCNxOpenLoading();

            var tBchCode = $(poElm).parents('.xCNPromotionPdtPmtHDCstRow').data('bch-code');
            var tDocNo = $(poElm).parents('.xCNPromotionPdtPmtHDCstRow').data('doc-no');
            var tCstCode = $(poElm).parents('.xCNPromotionPdtPmtHDCstRow').data('cst-code');


            $.ajax({
                type: "POST",
                url: "promotionStep4DeleteCstConditionInTmp",
                data: {
                    tBchCode: tBchCode,
                    tDocNo: tDocNo,
                    tCstCode: tCstCode
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $nCurrentPage = $('.xCNPromotionPdtPmtHDCstPage').find('.btn.xCNBTNNumPagenation.active').text();
                    JSxPromotionStep4GetHDCstInTmp($nCurrentPage, true);
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