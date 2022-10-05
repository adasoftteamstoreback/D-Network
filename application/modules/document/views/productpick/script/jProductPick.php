<script>
    var nPCKStaBrowseType = $("#oetPCKStaBrowse").val();
    var tPCKCallBackOption = $("#oetPCKCallBackOption").val();

    $("document").ready(function() {
        localStorage.removeItem("LocalPCKHDItemData");
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        JSxPCKNavDefult();

        if (nPCKStaBrowseType != 1) {
            // JSvPCKCallPageList();
            switch (nPCKStaBrowseType) {
                case '2':
                    var tAgnCode = $('#oetPCKJumpAgnCode').val();
                    var tBchCode = $('#oetPCKJumpBchCode').val();
                    var tDocNo = $('#oetPCKJumpDocNo').val();
                    JSvPCKCallPageEdit(tDocNo);
                    break;
                default:
                    JSvPCKCallPageList();
            }
        } else {
            JSvPCKCallPageAdd();
        }
    });

    // Control menu bar
    function JSxPCKNavDefult() {
        if (nPCKStaBrowseType != 1 || nPCKStaBrowseType == undefined) {
            $(".xCNPCKVBrowse").hide();
            $(".xCNPCKVMaster").show();
            $("#oliPCKTitleAdd").hide();
            $("#oliPCKTitleEdit").hide();
            $("#odvBtnAddEdit").hide();
            $(".obtChoose").hide();
            $("#odvPCKBtnInfo").show();
            $("#oliPCKTitleDetail").hide();
        } else {
            $("#odvModalBody .xCNPCKVMaster").hide();
            $("#odvModalBody .xCNPCKVBrowse").show();
            $("#odvModalBody #odvPCKMainMenu").removeClass("main-menu");
            $("#odvModalBody #oliPCKNavBrowse").css("padding", "2px");
            $("#odvModalBody #odvPCKBtnGroup").css("padding", "0");
            $("#odvModalBody .xCNPCKBrowseLine").css("padding", "0px 0px");
            $("#odvModalBody .xCNPCKBrowseLine").css(
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
    function JSvPCKCallPageList() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            $.ajax({
                type: "GET",
                url: "docPCKList",
                data: {},
                cache: false,
                timeout: 5000,
                success: function(tResult) {
                    $("#odvPCKContentPage").html(tResult);
                    JSxPCKNavDefult();
                    JSvPCKCallPageDataTable();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : เรียกตารางรายการเอกสาร
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : Table List
     * Return Type : View
     */
    function JSvPCKCallPageDataTable(pnPage) {
        JCNxOpenLoading();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var oAdvanceSearch = JSoPCKGetAdvanceSearchData();
        $.ajax({
            type: "POST",
            url: "docPCKDataTable",
            data: {
                oAdvanceSearch: JSON.stringify(oAdvanceSearch),
                nPageCurrent: nPageCurrent
            },
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                $("#odvPCKContent").html(tResult);
                JSxPCKNavDefult();
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
    function JSoPCKGetAdvanceSearchData() {
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
                console.log("JSoPCKGetAdvanceSearchData Error: ", err);
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
    function JSxPCKClearSearchData() {
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
                JSvPCKCallPageDataTable();
            } catch (err) {
                console.log("JSxPCKClearSearchData Error: ", err);
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : หน้าจอเพิ่มเอกสาร
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : Add Page
     * Return Type : View
     */
    function JSvPCKCallPageAdd() {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPCKCallPageAdd",
            data: {},
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                nIndexInputEditInlineForVD = 0;
                if (nPCKStaBrowseType == 1) {
                    $(".xCNPCKVMaster").hide();
                    $(".xCNPCKVBrowse").show();
                } else {
                    $(".xCNPCKVBrowse").hide();
                    $(".xCNPCKVMaster").show();
                    $("#oliPCKTitleEdit").hide();
                    $("#oliPCKTitleAdd").show();
                    $("#odvPCKBtnInfo").hide();
                    $("#odvBtnAddEdit").show();
                    $("#obtPCKApprove").hide();
                    $("#obtPCKPrint").hide();
                    $("#obtPCKCancel").hide();
                    $("#obtPCKPrint").hide();
                    $("#oliPCKTitleDetail").hide();
                }

                $("#odvPCKContentPage").html(tResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    /**
     * Functionality : เรียกหน้าแก้ไข
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : Edit Page
     * Return Type : View
     */
    function JSvPCKCallPageEdit(ptDocNo) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "docPCKCallPageEdit",
                data: {
                    tDocNo: ptDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    if (tResult != "") {
                        $("#odvBtnAddEdit").show();
                        $(".xCNPCKVBrowse").hide();
                        $(".xCNPCKVMaster").show();
                        $("#oliPCKTitleEdit").show();
                        $("#oliPCKTitleAdd").hide();
                        $("#odvPCKBtnInfo").hide();
                        $("#odvBtnAddEdit").show();
                        $("#obtPCKApprove").show();
                        $("#obtPCKPrint").show();
                        $("#obtPCKCancel").show();
                        $("#oliPCKTitleDetail").hide();
                        $("#odvPCKContentPage").html(tResult);
                    }

                    JCNxLayoutControll();
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Delete Doc
     * Parameters : tCurrentPage, tDocNo
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPCKDocDel(tCurrentPage, tDocNo) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var aData = $("#ohdConfirmIDDelete").val();
            var aTexts = aData.substring(0, aData.length - 2);
            var aDataSplit = aTexts.split(" , ");
            var aDataSplitlength = aDataSplit.length;

            if (aDataSplitlength == "1") {
                $("#odvModalDel").modal("show");
                $("#ospConfirmDelete").html("ยืนยันการลบข้อมูล หมายเลข : " + tDocNo);

                $("#osmConfirm").on("click", function(evt) {
                    $("#odvModalDel").modal("hide");
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docPCKDelDoc",
                        data: {
                            tDocNo: tDocNo
                        },
                        cache: false,
                        success: function(tResult) {
                            JSvPCKCallPageDataTable(tCurrentPage);
                            JSxPCKNavDefult();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Multi Delete Doc
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPCKDelChoose() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            $("#odvModalDel").modal("hide");
            JCNxOpenLoading();

            var aData = $("#ohdConfirmIDDelete").val();
            var aTexts = aData.substring(0, aData.length - 2);
            console.log('aTexts: ', aTexts);
            var aDataSplit = aTexts.split(" , ");
            var aDataSplitlength = aDataSplit.length;

            var aDocNo = [];
            for ($i = 0; $i < aDataSplitlength; $i++) {
                aDocNo.push(aDataSplit[$i]);
            }
            console.log('aDocNo: ', aDocNo);
            if (aDataSplitlength > 1) {
                localStorage.StaDeleteArray = "1";
                $.ajax({
                    type: "POST",
                    url: "docPCKDelDocMulti",
                    data: {
                        aDocNo: aDocNo
                    },
                    success: function(tResult) {
                        JSvPCKCallPageDataTable();
                        JSxPCKNavDefult();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
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

    /**
     * Functionality : Insert Text In Modal Delete
     * Parameters : LocalStorage Data
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxTextinModal() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalPCKHDItemData"))];
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

    /**
     * Functionality : Function Chack And Show Button Delete All
     * Parameters : LocalStorage Data
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxShowButtonChoose() {
        //console.log('asdasd');
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalPCKHDItemData"))];
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

    /**
     * Functionality : Function Chack Value LocalStorage
     * Parameters : array, key, value
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function findObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    /**
     * Functionality : Click Page for Documet List
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPCKDataTableClickPage(ptPage) {
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
        JSvPCKCallPageDataTable(nPageCurrent);
    }
</script>