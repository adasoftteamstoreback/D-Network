<script>
    var nStaTBIBrowseType = $("#oetTBIStaBrowse").val(),
        tCallTBIBackOption = $("#oetTBICallBackOption").val();

    function JSxTBINavDefult() {
        try {
            $(".xCNTBIMaster").show(), $("#oliTBITitleAdd").hide(), $("#oliTBITitleEdit").hide(), $("#odvBtnAddEdit").hide(), $("#odvBtnTBIInfo").show()
        } catch (e) {
            console.log("JSxCardShiftTopUpCardShiftTopUpNavDefult Error: ", e)
        }
    }

    function JSvTBICallPageTransferReceipt() {
        try {
            var e = JCNxFuncChkSessionExpired();
            void 0 !== e && 1 == e ? ($("#oetSearchAll").val(""), $.ajax({
                type: "POST",
                url: "docTBIPageList",
                cache: !1,
                timeout: 0,
                success: function(e) {
                    $("#odvContentTransferReceipt").html(e), JSvTBICallPageTransferReceiptDataTable()
                },
                error: function(e, o, t) {
                    JCNxTBIResponseError(e, o, t)
                }
            })) : JCNxShowMsgSessionExpired()
        } catch (e) {
            console.log("JSvTBICallPageTransferReceipt Error: ", e)
        }
    }

    function JSvTBICallPageTransferReceiptDataTable(e) {
        JCNxOpenLoading();
        var o = JSoTBIGetAdvanceSearchData(),
            t = $("#ohdTBIDocType").val(),
            a = e;
        null != a && "" != a || (a = "1"), $.ajax({
            type: "POST",
            url: "docTBIPageDataTable",
            data: {
                oAdvanceSearch: o,
                nTBIDocType: t,
                nPageCurrent: a
            },
            cache: !1,
            timeout: 0,
            success: function(e) {
                var o = JSON.parse(e);
                if ("1" == o.nStaEvent) JSxTBINavDefult(), $("#ostContentTransferreceipt").html(o.tViewDataTable);
                else {
                    var t = o.tStaMessg;
                    FSvCMNSetMsgErrorDialog(t)
                }
                JCNxLayoutControll(), JCNxCloseLoading()
            },
            error: function(e, o, t) {
                JCNxResponseError(e, o, t)
            }
        })
    }

    function JSvTBITransferReceiptAdd() {
        try {
            var e = JCNxFuncChkSessionExpired();
            void 0 !== e && 1 == e ? (JCNxOpenLoading(), $.ajax({
                type: "POST",
                url: "docTBIPageAdd",
                cache: !1,
                timeout: 0,
                success: function(e) {
                    var o = JSON.parse(e);
                    if ("1" == o.nStaEvent) $(".xCNTBIMaster").show(), $("#oliTBITitleEdit").hide(), $("#oliTBITitleAdd").show(), $("#odvBtnTBIInfo").hide(), $("#odvBtnAddEdit").show(), JSxControlBTN("PAGEADD"), $("#odvContentTransferReceipt").html(o.tViewPageAdd), JCNxLayoutControll(), JCNxCloseLoading(), JSvTBILoadPdtDataTableHtml();
                    else {
                        var t = o.tStaMessg;
                        FSvCMNSetMsgErrorDialog(t)
                    }
                },
                error: function(e, o, t) {
                    JCNxTBIResponseError(e, o, t)
                }
            })) : JCNxShowMsgSessionExpired()
        } catch (e) {
            console.log("JSvTBITransferReceiptAdd Error: ", e)
        }
    }

    function JSvTBICallPageEdit(e) {
        try {
            var o = JCNxFuncChkSessionExpired();
            void 0 !== o && 1 == o ? (JCNxOpenLoading(), $.ajax({
                type: "POST",
                url: "docTBIPageEdit",
                data: {
                    ptDocNumber: e,
                    ptTBIDocType: $("#ohdTBIDocType").val()
                },
                cache: !1,
                timeout: 0,
                success: function(e) {
                    var o = JSON.parse(e);
                    if ("1" == o.nStaEvent) $(".xCNTBIMaster").show(), $("#oliTBITitleEdit").show(), $("#oliTBITitleAdd").hide(), $("#odvBtnTBIInfo").hide(), $("#odvBtnAddEdit").show(), $("#odvContentTransferReceipt").html(o.tViewPageAdd), JCNxLayoutControll(), JSvTBILoadPdtDataTableHtml();
                    else {
                        var t = o.tStaMessg;
                        FSvCMNSetMsgErrorDialog(t)
                    }
                },
                error: function(e, o, t) {
                    JCNxTBIResponseError(e, o, t)
                }
            })) : JCNxShowMsgSessionExpired()
        } catch (e) {
            console.log("JSvTBICallPageEdit Error: ", e)
        }
    }

    function JSxControlBTN(e) {
        "PAGEADD" == e && ($("#obtTBIPrintDoc").hide(), $("#obtTBICancelDoc").hide(), $("#obtTBIApproveDoc").hide())
    }

    function JSvTBILoadPdtDataTableHtml(e) {
        if ("docTBIEventAdd" == $("#ohdTBIRoute").val()) var t = "";
        else t = $("#oetTBIDocNo").val();
        var a = $("#ohdTBIStaApv").val(),
            r = $("#ohdTBIStaDoc").val();
        if ("" == e || null == e) var l = 1;
        else l = e;
        var c = l,
            n = $("#oetTBIFrmFilterPdtHTML").val();
        $.ajax({
            type: "POST",
            url: "docTBIPagePdtAdvanceTableLoadData",
            data: {
                ptSearchPdtAdvTable: n,
                ptTBIDocNo: t,
                ptTBIStaApv: a,
                ptTBIStaDoc: r,
                pnTBIPageCurrent: c,
                ptTBIBchCode: $("#oetTBIBchCode").val()
            },
            cache: !1,
            Timeout: 0,
            success: function(e) {
                localStorage.removeItem("TBI_LocalItemDataDelDtTemp");
                var o = JSON.parse(e);
                if ("1" == o.nStaEvent) $("#odvTBIDataPdtTableDTTemp").html(o.tTBIPdtAdvTableHtml), JCNxCloseLoading();
                else {
                    var t = o.tStaMessg;
                    FSvCMNSetMsgErrorDialog(t), JCNxCloseLoading()
                }
            },
            error: function(e, o, t) {
                JCNxResponseError(e, o, t)
            }
        })
    }

    function JCNxTBIResponseError(e, o, t) {
        try {
            JCNxResponseError(e, o, t)
        } catch (e) {
            console.log("JCNxTBIResponseError Error: ", e)
        }
    }

    function JSoTBIGetAdvanceSearchData() {
        return {
            tSearchAll: $("#oetSearchAll").val(),
            tSearchBchCodeFrom: $("#oetASTBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetASTBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetASTDocDateFrom").val(),
            tSearchDocDateTo: $("#oetASTDocDateTo").val(),
            tSearchStaDoc: $("#ocmASTStaDoc").val(),
            tSearchStaApprove: $("#ocmASTStaApprove").val(),
            tSearchStaDocAct: $("#ocmStaDocAct").val(),
            tSearchStaPrcStk: $("#ocmASTStaPrcStk").val()
        }
    }

    function JSxTBIClearSearchData() {
        var e = JCNxFuncChkSessionExpired();
        void 0 !== e && 1 == e ? ($("#oetSearchAll").val(""), $("#oetASTBchCodeFrom").val(""), $("#oetASTBchNameFrom").val(""), $("#oetASTBchCodeTo").val(""), $("#oetASTBchNameTo").val(""), $("#oetASTDocDateFrom").val(""), $("#oetASTDocDateTo").val(""), $(".xCNDatePicker").datepicker("setDate", null), $(".selectpicker").val("0").selectpicker("refresh"), JSvTBICallPageTransferReceiptDataTable()) : JCNxShowMsgSessionExpired()
    }

    function JSvTBIClickPage(e) {
        var o = JCNxFuncChkSessionExpired();
        if (void 0 !== o && 1 == o) {
            var t = "";
            switch (e) {
                case "next":
                    $(".xWBtnNext").addClass("disabled"), nPageOld = $(".xWPageTBIPdt .active").text(), nPageNew = parseInt(nPageOld, 10) + 1, t = nPageNew;
                    break;
                case "previous":
                    nPageOld = $(".xWPageTBIPdt .active").text(), nPageNew = parseInt(nPageOld, 10) - 1, t = nPageNew;
                    break;
                default:
                    t = e
            }
            JSvTBICallPageTransferReceiptDataTable(t)
        } else JCNxShowMsgSessionExpired()
    }

    function JSoTBIDelDocSingle(e, o) {
        var t = JCNxFuncChkSessionExpired();
        void 0 !== t && 1 == t ? ($("#odvTBIModalDelDocSingle #ospTextConfirmDelSingle").html($("#oetTextComfirmDeleteSingle").val() + o), $("#odvTBIModalDelDocSingle").modal("show"), $("#odvTBIModalDelDocSingle #osmTBIConfirmPdtDTTemp ").unbind().click(function() {
            JCNxOpenLoading(), $.ajax({
                type: "POST",
                url: "docTBIEventDelete",
                data: {
                    tTBIDocNo: o
                },
                cache: !1,
                timeout: 0,
                success: function(e) {
                    var o = JSON.parse(e);
                    "1" == o.nStaEvent ? ($("#odvTBIModalDelDocSingle").modal("hide"), $("#odvTBIModalDelDocSingle #ospTextConfirmDelSingle").html($("#oetTextComfirmDeleteSingle").val()), $(".modal-backdrop").remove(), setTimeout(function() {
                        JSvTBICallPageTransferReceipt()
                    }, 500)) : (JCNxCloseLoading(), FSvCMNSetMsgErrorDialog(o.tStaMessg))
                },
                error: function(e, t, a) {
                    if (JCNxResponseError(e, t, a), 404 != e.status) {
                        var r = e.status,
                            l = $(e.responseText).find("p:nth-child(3)").text();
                        JCNxPackDataToMQLog(l, r, "ลบใบรับโอน - สาขา", "ERROR", o)
                    }
                }
            })
        })) : JCNxShowMsgSessionExpired()
    }

    function JSxTBITransferReceiptDocCancel(e) {
        var o = $("#oetTBIDocNo").val(),
            t = $("#oetTBIBchCode").val();
        e ? $.ajax({
            type: "POST",
            url: "docTBIEventCencel",
            data: {
                tTBIDocNo: o,
                tBIBchCode: t
            },
            cache: !1,
            timeout: 5e3,
            success: function(e) {
                $("#odvTBIPopupCancel").modal("hide"), aResult = $.parseJSON(e), 1 == aResult.nSta ? JSvTBICallPageEdit(o) : (JCNxCloseLoading(), tMsgBody = aResult.tStaMessg, FSvCMNSetMsgErrorDialog(tMsgBody))
            },
            error: function(e, o, a) {
                if (JCNxResponseError(e, o, a), 404 != e.status) {
                    var r = e.status,
                        l = $(e.responseText).find("p:nth-child(3)").text();
                    JCNxPackDataToMQLog(l, r, "ยกเลิกใบรับโอน - สาขา", "ERROR", t)
                }
            }
        }) : $("#odvTBIPopupCancel").modal("show")
    }

    function JSxTBITransferReceiptStaApvDoc(e) {
        $("#ohdTBIRoute").val();
        try {
            if (e) {
                $("#ohdTBIStaPrcStk").val(2), $("#odvTBIModalAppoveDoc").modal("hide");
                var o = $("#oetTBIDocNo").val(),
                    t = $("#ohdTBIStaApv").val();
                $.ajax({
                    type: "POST",
                    url: "docTBIEventApproved",
                    data: {
                        tXthDocNo: o,
                        tXthStaApv: t,
                        tXthDocType: $("#ohdTBIFrmDocType").val(),
                        tXthBchCode: $("#oetTBIBchCode").val()
                    },
                    cache: !1,
                    timeout: 0,
                    success: function(e) {
                        let o = JSON.parse(e);
                        if ("900" != o.nStaEvent) {
                            if ("905" == o.nStaEvent) {
                                FSvCMNSetMsgErrorDialog(o.tStaMessg);
                                var t = o.tStaMessg,
                                    a = $("#oetTBIBchCode").val();
                                JCNxPackDataToMQLog(t, "", "อนุมัติใบรับโอน - สาขา", "WARNING", a)
                            } else JSoTBISubscribeMQ();
                            JCNxCloseLoading()
                        } else FSvCMNSetMsgErrorDialog(o.tStaMessg)
                    },
                    error: function(e, o, t) {
                        if (JCNxResponseError(e, o, t), 404 != e.status) {
                            var a = e.status,
                                r = $(e.responseText).find("p:nth-child(3)").text(),
                                l = $("#oetTBIBchCode").val();
                            JCNxPackDataToMQLog(r, a, "อนุมัติใบรับโอน - สาขา", "ERROR", l)
                        }
                    }
                })
            } else $("#odvTBIModalAppoveDoc").modal("show")
        } catch (e) {
            console.log("JSxTBITransferReceiptStaApvDoc Error: ", e)
        }
    }

    function JSoTBISubscribeMQ() {
        var e = $("#ohdTBILangEdit").val(),
            o = $("#oetTBIBchCode").val(),
            t = $("#ohdTBIApvCodeUsrLogin").val(),
            a = $("#oetTBIDocNo").val(),
            r = $("#ohdTBIStaApv").val(),
            l = {
                tLangCode: e,
                tUsrBchCode: o,
                tUsrApv: t,
                tDocNo: a,
                tPrefix: "RESTBI",
                tStaDelMQ: $("#ohdTBIStaDelMQ").val(),
                tStaApv: r,
                tQName: "RESTBI_" + a + "_" + t
            },
            c = {
                host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
                username: oSTOMMQConfig.user,
                password: oSTOMMQConfig.password,
                vHost: oSTOMMQConfig.vhost
            };
        FSxCMNRabbitMQMessage(l, c, {
            ptDocTableName: "TCNTPdtTbiHD",
            ptDocFieldDocNo: "FTXthDocNo",
            ptDocFieldStaApv: "FTXthStaPrcStk",
            ptDocFieldStaDelMQ: "FTXthStaDelMQ",
            ptDocStaDelMQ: "1",
            ptDocNo: a
        }, {
            tCallPageEdit: "JSvTBICallPageEdit",
            tCallPageList: "JSvTBICallPageTransferReceipt"
        })
    }
    $("document").ready(function() {
        switch (localStorage.removeItem("LocalItemData"), JSxCheckPinMenuClose(), JSxTBINavDefult(), nStaTBIBrowseType) {
            case "2":
                $("#oetTBIJumpAgnCode").val(), $("#oetTBIJumpBchCode").val();
                JSvTBICallPageEdit($("#oetTBIJumpDocNo").val());
                break;
            default:
                JSvTBICallPageTransferReceipt()
        }
    });
</script>