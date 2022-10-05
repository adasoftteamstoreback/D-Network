<script>
    var nTransferBchOutStaBrowseType = $("#oetTransferBchOutStaBrowse").val();
    var tTransferBchOutCallBackOption = $("#oetTransferBchOutCallBackOption").val();

    $("document").ready(function() {
        localStorage.removeItem("LocalTransferBchOutHDItemData");
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        JSxTransferBchOutNavDefult();

        if (nTransferBchOutStaBrowseType != 1) {
            // JSvTransferBchOutCallPageList();
            switch (nTransferBchOutStaBrowseType) {
                case '2':
                    var tAgnCode = $('#oetTransferBchOutJumpAgnCode').val();
                    var tBchCode = $('#oetTransferBchOutJumpBchCode').val();
                    var tDocNo = $('#oetTransferBchOutJumpDocNo').val();
                    JSvTransferBchOutCallPageEdit(tDocNo);
                    break;
                default:
                    JSvTransferBchOutCallPageList();
            }
        } else {
            JSvTransferBchOutCallPageAdd();
        }
    });

    // Control menu bar
    function JSxTransferBchOutNavDefult() {
        if (nTransferBchOutStaBrowseType != 1 || nTransferBchOutStaBrowseType == undefined) {
            $(".xCNTransferBchOutVBrowse").hide();
            $(".xCNTransferBchOutVMaster").show();
            $("#oliTransferBchOutTitleAdd").hide();
            $("#oliTransferBchOutTitleEdit").hide();
            $("#odvBtnAddEdit").hide();
            $(".obtChoose").hide();
            $("#odvTransferBchOutBtnInfo").show();
            $("#oliTransferBchOutTitleDetail").hide();
        } else {
            $("#odvModalBody .xCNTransferBchOutVMaster").hide();
            $("#odvModalBody .xCNTransferBchOutVBrowse").show();
            $("#odvModalBody #odvTransferBchOutMainMenu").removeClass("main-menu");
            $("#odvModalBody #oliTransferBchOutNavBrowse").css("padding", "2px");
            $("#odvModalBody #odvTransferBchOutBtnGroup").css("padding", "0");
            $("#odvModalBody .xCNTransferBchOutBrowseLine").css("padding", "0px 0px");
            $("#odvModalBody .xCNTransferBchOutBrowseLine").css(
                "border-bottom",
                "1px solid #e3e3e3"
            );
        }
    }

    /**
     * Functionality : เรียกหน้าแรก(รายการเอกสาร)
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : Main Page
     * Return Type : View
     */
    function JSvTransferBchOutCallPageList() {
        // var nStaSession = JCNxFuncChkSessionExpired();
        // if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            $.ajax({
                type: "GET",
                url: "docTransferBchOutList",
                data: {},
                cache: false,
                timeout: 5000,
                success: function(tResult) {
                    $("#odvTransferBchOutContentPage").html(tResult);
                    JSxTransferBchOutNavDefult();
                    JSvTransferBchOutCallPageDataTable();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        // } else {
        //     JCNxShowMsgSessionExpired();
        // }
    }

    /**
     * Functionality : เรียกตารางรายการเอกสาร
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : Table List
     * Return Type : View
     */
    function JSvTransferBchOutCallPageDataTable(pnPage) {
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = JSoTransferBchOutGetAdvanceSearchData();
        $.ajax({
            type: "POST",
            url: "docTransferBchOutDataTable",
            data: {
                oAdvanceSearch: JSON.stringify(oAdvanceSearch),
                nPageCurrent: nPageCurrent
            },
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                $("#odvTransferBchOutContent").html(tResult);
                JSxTransferBchOutNavDefult();
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    /**
     * Functionality : ค้นหาขั้นสูง
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSoTransferBchOutGetAdvanceSearchData() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            try {
                let oAdvanceSearchData = {
                    tSearchAll: $("#oetSearchAll").val(),
                    tSearchBchCodeFrom: $("#oetBchCodeFrom").val(),
                    tSearchBchCodeTo: $("#oetBchCodeTo").val(),
                    tSearchDocDateFrom: $("#oetSearchDocDateFrom").val(),
                    tSearchDocDateTo: $("#oetSearchDocDateTo").val(),
                    tSearchStaDoc: $("#ocmStaDoc").val(),
                    tSearchStaApprove: $("#ocmStaApprove").val(),
                    tSearchStaDocAct: $("#ocmStaDocAct").val(),
                    tSearchStaPrcStk: $("#ocmStaPrcStk").val()
                };
                return oAdvanceSearchData;
            } catch (err) {
                console.log("JSoTransferBchOutGetAdvanceSearchData Error: ", err);
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : ล้างข้อมูลค้นหาขั้นสูง
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxTransferBchOutClearSearchData() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            try {
                var nCountBch = "<?= $this->session->userdata("nSesUsrBchCount"); ?>";
                if (nCountBch != 1) { //ถ้ามีมากกว่า 1 สาขาต้อง reset
                    $("#oetBchCodeFrom").val("");
                    $("#oetBchNameFrom").val("");
                    $("#oetBchCodeTo").val("");
                    $("#oetBchNameTo").val("");
                }

                $("#oetSearchAll").val("");
                $("#oetSearchDocDateFrom").val("");
                $("#oetSearchDocDateTo").val("");
                $(".xCNDatePicker").datepicker("setDate", null);
                $(".selectpicker").val("0").selectpicker("refresh");
                JSvTransferBchOutCallPageDataTable();
            } catch (err) {
                console.log("JSxTransferBchOutClearSearchData Error: ", err);
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //Functionality : หน้าจอเพิ่มเอกสาร
    function JSvTransferBchOutCallPageAdd() {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docTransferBchOutCallPageAdd",
            data: {},
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                nIndexInputEditInlineForVD = 0;
                if (nTransferBchOutStaBrowseType == 1) {
                    $(".xCNTransferBchOutVMaster").hide();
                    $(".xCNTransferBchOutVBrowse").show();
                } else {
                    $(".xCNTransferBchOutVBrowse").hide();
                    $(".xCNTransferBchOutVMaster").show();
                    $("#oliTransferBchOutTitleEdit").hide();
                    $("#oliTransferBchOutTitleAdd").show();
                    $("#odvTransferBchOutBtnInfo").hide();
                    $("#odvBtnAddEdit").show();
                    $("#obtTransferBchOutApprove").hide();
                    $("#obtTransferBchOutPrint").hide();
                    $("#obtTransferBchOutCancel").hide();
                    $("#obtTransferBchOutPrint").hide();
                    $("#oliTransferBchOutTitleDetail").hide();
                }

                $("#odvTransferBchOutContentPage").html(tResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Functionality : หน้าจอเพิ่มเอกสาร แบบ Jump มา
    function JSvTransferBchOutCallPageAdd_JumpTRB(ptDocumentNumber,ptWahouseTo,ptWahouseNameTo,ptBranchTo,ptBranchNameTo) {
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docTransferBchOutCallPageAdd",
            data    : {},
            cache   : false,
            timeout : 5000,
            success: function(tResult) {
                nIndexInputEditInlineForVD = 0;
                if (nTransferBchOutStaBrowseType == 1) {
                    $(".xCNTransferBchOutVMaster").hide();
                    $(".xCNTransferBchOutVBrowse").show();
                } else {
                    $(".xCNTransferBchOutVBrowse").hide();
                    $(".xCNTransferBchOutVMaster").show();
                    $("#oliTransferBchOutTitleEdit").hide();
                    $("#oliTransferBchOutTitleAdd").show();
                    $("#odvTransferBchOutBtnInfo").hide();
                    $("#odvBtnAddEdit").show();
                    $("#obtTransferBchOutApprove").hide();
                    $("#obtTransferBchOutPrint").hide();
                    $("#obtTransferBchOutCancel").hide();
                    $("#obtTransferBchOutPrint").hide();
                    $("#oliTransferBchOutTitleDetail").hide();
                }

                $("#odvTransferBchOutContentPage").html(tResult);

                JSxLoadPanelDocRefTRB(ptDocumentNumber,ptWahouseTo,ptWahouseNameTo,ptBranchTo,ptBranchNameTo);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //โหลดข้อมูลสินค้าพร้อมเอกสารอ้างอิง ของเอกสารใบขอโอน
    function JSxLoadPanelDocRefTRB(ptDocumentNumber,ptWahouseTo,ptWahouseNameTo,ptBranchTo,ptBranchNameTo){

        //สาขาปลายทาง
        $('#obtTransferBchOutBrowseBchTo').attr('disabled',true);
        $('#oetTransferBchOutXthBchToCode').val(ptBranchTo);
        $('#oetTransferBchOutXthBchToName').val(ptBranchNameTo);

        //คลังปลายทาง
        $('#oetTransferBchOutXthWhToCode').val(ptWahouseTo);
        $('#oetTransferBchOutXthWhToName').val(ptWahouseNameTo);

        //เลขที่เอกสารอ้างอิง
        $('#oetTransferBchOutXthRefInt').val(ptDocumentNumber);
        $('#oetTransferBchOutXthRefIntName').val(ptDocumentNumber);
        $('#oetTransferBchOutXthRefIntOld').val(ptDocumentNumber);

        //วันที่อ้างอิงเอกสารภายใน
        $('#oetTransferBchOutXthRefIntDate').val('<?=date('Y-m-d')?>').datepicker("refresh");
        
        $.ajax({
            type    : "POST",
            url     : "docTransferBchOutRefIntDocInsertDTToTemp",
            data    : {
                'tTransferBchOutDocNo'          : $('#oetTransferBchOutDocNo').val(),
                'tTransferBchOutFrmBchCode'     : $('#oetTransferBchOutBchCode').val(),
                'tRefIntDocNo'      			: ptDocumentNumber,
                'tRefIntBchCode'    			: ptBranchTo,
                'tSplStaVATInOrEx'  			: 1,
                'aSeqNo'            			: 'all'
            },
            cache   : false,
            Timeout : 0,
            success : function (oResult){
                //โหลดสินค้าใน Temp
                JSxTransferBchOutGetPdtInTmp();

                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // เรียกหน้าแก้ไข
    function JSvTransferBchOutCallPageEdit(ptDocNo) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type : "POST",
                url  : "docTransferBchOutCallPageEdit",
                data : {
                    tDocNo: ptDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    var aReturnData = JSON.parse(tResult)
                    if (aReturnData != "") {
                        $("#odvTransferBchOutContentPage").html(aReturnData['tViewPageEdit']);
                        $("#odvBtnAddEdit").show();
                        $(".xCNTransferBchOutVBrowse").hide();
                        $(".xCNTransferBchOutVMaster").show();
                        $("#oliTransferBchOutTitleEdit").show();
                        $("#oliTransferBchOutTitleAdd").hide();
                        $("#odvTransferBchOutBtnInfo").hide();
                        $("#odvBtnAddEdit").show();
                        $("#obtTransferBchOutPrint").show();
                        $("#oliTransferBchOutTitleDetail").hide();
                    }
                    if(aReturnData['FTXthStaDoc'] == 3){
                        $("#obtTransferBchOutApprove").hide();
                        $("#obtTransferBchOutCancel").hide();
                    }else{
                        if (aReturnData['FTXthStaApv'] == 1 && aReturnData['FTXthStaDoc'] != 3 && aReturnData['nStaDocRef'] != 2) {
                            // เช็ค เดือน ถ้า เดือนไม่เท่ากับเอกสาร จะวิ่งไปเช็คสิทธิ ว่า มีสิทธิเห็นปุ่มยกเลิกไหม ถ้ามี ยกเลิกได้ ถ้าไม่มี ยกเลิกไม่ได้
                            if(tDocDateCreate != '' && tDocDateToday != '' && tDocDateCreate != tDocDateToday){
                                if(tAutStaCancel == '1'){
                                    $("#obtTransferBchOutCancel").show();
                                }else{
                                    $("#obtTransferBchOutCancel").hide();
                                }
                            }
                        }else if(aReturnData['FTXthStaApv'] == 1 && (aReturnData['FTXthStaDoc'] == 3 || aReturnData['nStaDocRef'] == 2)){
                            $("#obtTransferBchOutApprove").hide();
                            $("#obtTransferBchOutCancel").hide();
                        }else{
                            $("#obtTransferBchOutApprove").show();
                            $("#obtTransferBchOutCancel").show();
                        }
                    }
                    JCNxLayoutControll();
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    
                    if (jqXHR.status != 404){
                        var tLogFunction = 'ERROR';
                        var tDisplayEvent = 'เรียกดูเอกสารใบจ่ายโอน - สาขา';
                        var tErrorStatus  = jqXHR.status;
                        var tLogDocNo   = ptDocNo;
                        var tHtmlError = $(jqXHR.responseText);
                        var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }else{
                        //JCNxSendMQPageNotFound(jqXHR,ptPODocNo);
                    }
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //Delete Doc
    function JSxTransferBchOutDocDel(tCurrentPage, tDocNo) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var aData            = $("#ohdConfirmIDDelete").val();
            var aTexts           = aData.substring(0, aData.length - 2);
            var aDataSplit       = aTexts.split(" , ");
            var aDataSplitlength = aDataSplit.length;
            if (aDataSplitlength == "1") {
                $("#odvModalDel").modal("show");
                $("#ospConfirmDelete").html("ยืนยันการลบข้อมูล หมายเลข : " + tDocNo);

                $("#osmConfirm").on("click", function(evt) {
                    $("#odvModalDel").modal("hide");
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docTransferBchOutDelDoc",
                        data: {
                            tDocNo: tDocNo
                        },
                        cache: false,
                        success: function(tResult) {
                            JSvTransferBchOutCallPageDataTable(tCurrentPage);
                            JSxTransferBchOutNavDefult();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                            //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                            if (jqXHR.status != 404){
                                var tLogFunction = 'ERROR';
                                var tDisplayEvent = 'ลบใบจ่ายโอน - สาขา';
                                var tErrorStatus = jqXHR.status;
                                var tHtmlError = $(jqXHR.responseText);
                                var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                                var tLogDocNo   = tDocNo;
                                JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                            }else{
                                //JCNxSendMQPageNotFound(jqXHR,ptPODocNo);
                            }
                        }
                    });
                });
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //Multi Delete Doc
    function JSxTransferBchOutDelChoose() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            $("#odvModalDel").modal("hide");
            JCNxOpenLoading();

            var aData = $("#ohdConfirmIDDelete").val();
            var aTexts = aData.substring(0, aData.length - 2);
            var aDataSplit = aTexts.split(" , ");
            var aDataSplitlength = aDataSplit.length;

            var aDocNo = [];
            for ($i = 0; $i < aDataSplitlength; $i++) {
                aDocNo.push(aDataSplit[$i]);
            }
            if (aDataSplitlength > 1) {
                localStorage.StaDeleteArray = "1";
                $.ajax({
                    type: "POST",
                    url: "docTransferBchOutDelDocMulti",
                    data: {
                        aDocNo: aDocNo
                    },
                    success: function(tResult) {
                        JSvTransferBchOutCallPageDataTable();
                        JSxTransferBchOutNavDefult();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                        //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                        if (jqXHR.status != 404){
                            var tLogFunction = 'ERROR';
                            var tDisplayEvent = 'ลบใบจ่ายโอน - สาขา';
                            var tErrorStatus = jqXHR.status;
                            var tHtmlError = $(jqXHR.responseText);
                            var tMsgErrorBody = tHtmlError.find('p:nth-child(3)').text();
                            var tLogDocNo   = aDocNo;
                            JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                        }else{
                            //JCNxSendMQPageNotFound(jqXHR,ptPODocNo);
                        }
                    }
                });
            } else {
                localStorage.StaDeleteArray = "0";
                return false;
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Insert Text In Modal Delete
    function JSxTextinModal() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalTransferBchOutHDItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {} else {
            var tTextCode = "";
            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tTextCode += aArrayConvert[0][$i].nCode;
                tTextCode += " , ";
            }
            //Disabled ปุ่ม Delete
            if (aArrayConvert[0].length > 1) {
                $(".xCNIconDel").addClass("xCNDisabled");
            } else {
                $(".xCNIconDel").removeClass("xCNDisabled");
            }

            $("#ospConfirmDelete").text("ท่านต้องการลบข้อมูลทั้งหมดหรือไม่ ?");
            $("#ohdConfirmIDDelete").val(tTextCode);
        }
    }

    // Function Chack And Show Button Delete All
    function JSxShowButtonChoose() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalTransferBchOutHDItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
            $("#oliBtnDeleteAll").addClass("disabled");
        } else {
            nNumOfArr = aArrayConvert[0].length;
            if (nNumOfArr > 1) {
                $("#oliBtnDeleteAll").removeClass("disabled");
            } else {
                $("#oliBtnDeleteAll").addClass("disabled");
            }
        }
    }

    // Function Chack Value LocalStorage
    function findObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    // Click Page for Documet List
    function JSvTransferBchOutDataTableClickPage(ptPage) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSvTransferBchOutCallPageDataTable(nPageCurrent);
    }
</script>