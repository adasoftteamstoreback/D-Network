<script type="text/javascript">
    //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
    $('#obtJR1SubmitFromDoc').attr('disabled', false);
    $('#obtJR1ApproveDoc').attr('disabled', false);

    $('.selectpicker').selectpicker();

    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        startDate: new Date(),
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('#obtJR1BrowseBookingDate').unbind().click(function(){
        $('#oetJR1DocRefBookDate').datepicker('show');
    });
    $('#obtJR1BrowseRefExtDocDate').unbind().click(function(){
        $('#oetJR1DocRefExtDocDate').datepicker('show');
    });
    $('#obtJR1DocDate').unbind().click(function(){
        $('#oetJR1DocDate').datepicker('show');
    });
    $('#obtJR1DocTime').unbind().click(function(){
        $('#oetJR1DocTime').datetimepicker('show');
    });
    $('#obtJR1BookDate').unbind().click(function(){
        $('#oetJR1BookDate').datepicker('show');
    });
    $('#obtJR1PickInDate').unbind().click(function(){
        $('#oetJR1PickInDate').datepicker('show');
    });

    // ========================================== Start Browse ตารางนัดหมาย ==========================================
    var oJR1BookCalendar = function(poDataFnc) {
        let tInputReturnCode = poDataFnc.tReturnInputCode;
        let tInputReturnName = poDataFnc.tReturnInputName;
        let tNextFuncName = poDataFnc.tNextFuncName;
        let aArgReturn = poDataFnc.aArgReturn;
        let tParamsLoginLevel = poDataFnc.tParamsLoginLevel;
        let tParamsAgnCode = poDataFnc.tParamsAgnCode;
        let tParamsBchCode = poDataFnc.tParamsBchCode;
        let tCstCode = poDataFnc.tWhereCstCode;
        // Check Customer Code In Booking Calendar
        let tWhereCstCode = "";
        if (typeof tCstCode !== "undefined" && tCstCode != "") {
            tWhereCstCode += "AND ( TSVTBookHD.FTXshCstRef1 = '" + tCstCode + "')";
        }
        let tWhereAgency = "";
        let tWhereBranch = "";
        let tWhereStatus = "";
        // Check != HQ
        // if (tParamsLoginLevel != "HQ"){
        // Check Agency
        if (tParamsAgnCode != "") {
            tWhereAgency += " AND ( TSVTBookHD.FTXshToAgn = '" + tParamsAgnCode + "' OR ISNULL(TSVTBookHD.FTXshToAgn,'') = '' ) ";
        }
        // Check Branch
        if (tParamsBchCode != "") {
            tWhereBranch += " AND (TSVTBookHD.FTXshToBch = '" + tParamsBchCode + "')";
        }
        // }
        // Where Status
        tWhereStatus += " AND (TSVTBookHD.FTXshStaDoc = '1') AND (TSVTBookHD.FTXshStaApv = '1') AND (TSVTBookHD.FNXshStaDocAct = '1')";
        tWhereStatus += " AND (TSVTBookHD.FTXshStaClosed = '' OR TSVTBookHD.FTXshStaClosed IS NULL) AND TSVTBookHD.FTXshStaPrcDoc = 2	";

        // WHERE NOT IN JOB 1 DOCREF
        let tWhereNotINDocRef = "AND (TSVTBookHD.FTXshDocNo NOT IN ( SELECT JDR1.FTXshRefDocNo FROM TSVTJob1ReqHDDocRef JDR1 WITH(NOLOCK) ))";

        let oOptionReturn = {
            Title: ['document/jobrequest1/jobrequest1', 'tJR1Booking'],
            Table: {
                Master: 'TSVTBookHD',
                PK: 'FTXshDocNo'
            },
            Join: {
                Table: ['TCNMCst_L', 'TSVMCar', 'TSVMPos_L'],
                On: [
                    'TSVTBookHD.FTXshCstRef1    = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits,
                    'TSVTBookHD.FTXshCstRef2    = TSVMCar.FTCarCode',
                    'TSVTBookHD.FTAgnCode       = TSVMPos_L.FTAgnCode AND TSVTBookHD.FTBchCode = TSVMPos_L.FTBchCode AND TSVTBookHD.FTXshToPos = TSVMPos_L.FTSpsCode AND TSVMPos_L.FNLngID =' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tWhereStatus + tWhereAgency + tWhereBranch + tWhereNotINDocRef + tWhereCstCode]
            },
            GrideView: {
                ColumnPathLang: 'document/jobrequest1/jobrequest1',
                ColumnKeyLang: ['tJR1BKDocno', 'tJR1BKDocDate', 'tJR1BKDocCst', 'tJR1BKDocCarRegNo', 'tJR1BKDocBookDate'],
                ColumnsSize: ['10%', '10%', '45%', '10%', '15%'],
                WidthModal: 50,
                DataColumns: [
                    'TSVTBookHD.FTXshDocNo',
                    'TSVTBookHD.FDXshDocDate',
                    'TCNMCst_L.FTCstName',
                    'TSVMCar.FTCarRegNo',
                    'TSVTBookHD.FDXshBookDate',
                    'TSVTBookHD.FTXshCstRef1',
                    'TSVTBookHD.FTXshCstRef2',
                    'TSVMPos_L.FTSpsCode',
                    'TSVMPos_L.FTSpsName',
                    'TSVTBookHD.FDXshTimeStart',
                    // 'CONVERT(varchar,TSVTBookHD.FDXshTimeStart,121) AS FDXshDateStart',
                    // 'CONVERT(varchar,TSVTBookHD.FDXshTimeStart,24) AS FDXshTimeStart'
                ],
                DataColumnsFormat: ['', 'Date:0', '', '', 'Date:0', '', ''],
                DisabledColumns: [5, 6, 7, 8, 9],
                /*,10*/
                Perpage: 10,
                OrderBy: ['TSVTBookHD.FTXshDocNo'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TSVTBookHD.FTXshDocNo"],
                Text: [tInputReturnName, "TSVTBookHD.FTXshDocNo"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
            BrowseLev: 1,
            // DebugSQL: true,
        };
        return oOptionReturn;
    }
    $('#oimJR1BrowseBooking').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            let tCstCode = $('#oetJR1FrmCstCode').val();
            window.oJR1BookCalendarOption = undefined;
            oJR1BookCalendarOption = oJR1BookCalendar({
                'tParamsLoginLevel': '<?= $this->session->userdata("tSesUsrLevel") ?>',
                'tParamsAgnCode': $('#ohdJR1ADCode').val(),
                'tParamsBchCode': $('#ohdJR1BchCode').val(),
                'tReturnInputCode': 'oetJR1OldDocRefBookCode',
                'tReturnInputName': 'oetJR1OldDocRefBookCode',
                'tNextFuncName': 'JSxJR1CheckRefBooking',
                'aArgReturn': ['FTXshDocNo', 'FTXshCstRef1', 'FTXshCstRef2', 'FDXshBookDate', 'FTSpsCode', 'FTSpsName' /*,'FDXshDateStart'*/ , 'FDXshTimeStart'],
                'tWhereCstCode': tCstCode,
            });
            JCNxBrowseData('oJR1BookCalendarOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxJR1CheckRefBooking(aReturn) {
        if (aReturn == '' || aReturn == 'NULL') {
            $('#oetJR1DocRefBookCode').val('');
            $('#oetJR1DocRefBookName').val('');
        } else {
            if ($('.xWPdtItem').length > 0) {
                // var paPackData = JSON.stringify(aData);
                FSvCMNSetMsgWarningDialog('ล้างรายการสินค้าที่เลือกไว้ก่อนหน้า ยกเว้นรายการสินค้าตั้งต้น', 'JSxWhenSeletedBooking', '', aReturn);
            } else {
                JSxWhenSeletedBooking(aReturn);
            }
        }
    }

    // ฟังก์ชั่นทำงานหลังจากทำการเลือกข้อมูลไปค้นหาข้อมูลตารางนัดหมาย
    function JSxWhenSeletedBooking(aReturn) {
        // console.log(aReturn);
        if (aReturn == '' || aReturn == 'NULL') {} else {

            var aData = JSON.parse(aReturn);
            // console.log(aData);

            $('#oetJR1DocRefBookCode').val(aData[0]);
            $('#oetJR1DocRefBookName').val(aData[0]);

            // Check Box Status Booking
            $('#oetJR1BookUse').prop('checked', true);

            // Set Date Time Booking
            var dDate = aData[3].split(" ");
            $('#oetJR1DocRefBookDate').val(dDate[0]);
            // Set Booking Document
            var dTimeStart = aData[6];
            var tDateStart = dTimeStart.substr(0, 10).trim();
            var tTimeStart = dTimeStart.substr(11, 8).trim();
            $('#oetJR1BookDate').val(tDateStart);
            $('#oetJR1TimeBook').val(tTimeStart);
            // console.log('Date: ' + tDateStart);
            // console.log('Time: ' + tTimeStart);
            // var dBookingDate    = aData[6].split(" ");
            // $('#oetJR1BookDate').val(dBookingDate[0]);
            // $('#oetJR1TimeBook').val(aData[7]);
            // Set Pos Bay
            var tSpsCode = aData[4].split(" ");
            var tSpsName = aData[5].split(" ");
            $('#oetJR1BayCode').val(tSpsCode);
            $('#oetJR1BayName').val(tSpsName);
            //ข้อมูลลูกค้า
            var aReturnItemCst = [aData[1]];
            JSxWhenSeletedCustomer(JSON.stringify(aReturnItemCst), 'continue');
            //ข้อมูลรถ
            var aReturnItemCar = [aData[2]];
            JSxWhenSeletedCstCar(JSON.stringify(aReturnItemCar), 'continue');
            //ข้อมูลสินค้าจากเอกสารนัดหมาย
            var aReturnItemBooking = [aData[0], aData[2]];
            JSxWhenSeletedBookingDT(JSON.stringify(aReturnItemBooking));
        }

    }

    //เอาสินค้าจาก Booking มาลงในใบรับรถ
    function JSxWhenSeletedBookingDT(aReturn) {
        $.ajax({
            type: "POST",
            url: "docJR1EventInsertToDTCaseDTBooking",
            data: {
                "poItem": aReturn,
                "tAgnCode": $('#ohdJR1ADCode').val(),
                "tBchCode": $('#ohdJR1BchCode').val(),
                "tDocumentNumber": $('#oetJR1DocNo').val(),
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JSvJR1LoadPdtDataTableHtml();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // ============================================ Start Browse พนักงานรับรถ ==========================================
    var oJR1UsrValet = function(poDataFnc) {
        let tInputReturnCode = poDataFnc.tReturnInputCode;
        let tInputReturnName = poDataFnc.tReturnInputName;
        let tParamsLoginLevel = poDataFnc.tParamsLoginLevel;
        let tParamsAgnCode = poDataFnc.tParamsAgnCode;
        let tParamsBchCode = poDataFnc.tParamsBchCode;
        let tWhereCondition = "";
        // Check HQ
        if (tParamsLoginLevel != "HQ") {
            // Check Agency
            if (tParamsAgnCode != "") {
                tWhereCondition += " AND ( TCNTUsrGroup.FTAgnCode = '" + tParamsAgnCode + "' OR ISNULL(TCNTUsrGroup.FTAgnCode,'') = '' ) ";
            }
            // Check Branch
            if (tParamsBchCode != "") {
                tWhereCondition += " AND (TCNTUsrGroup.FTBchCode = '" + tParamsBchCode + "')";
            }
        }
        let oOptionReturn = {
            Title: ['document/jobrequest1/jobrequest1', 'tJR1MDUsrValet'],
            Table: {
                Master: 'TCNMUser',
                PK: 'FTUsrCode'
            },
            Join: {
                Table: ['TCNMUser_L', 'TCNTUsrGroup'],
                On: [
                    'TCNMUser.FTUsrCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits,
                    'TCNTUsrGroup.FTUsrCode = TCNMUser.FTUsrCode'
                ]
            },
            Where: {
                Condition: [tWhereCondition]
            },
            GrideView: {
                ColumnPathLang: 'document/jobrequest1/jobrequest1',
                ColumnKeyLang: ['tJR1MDUsrValetCode', 'tJR1MDUsrValetName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMUser.FTUsrCode'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMUser.FTUsrCode"],
                Text: [tInputReturnName, "TCNMUser_L.FTUsrName"]
            },
            BrowseLev: 1,
            // DebugSQL: true,
        }
        return oOptionReturn;
    };
    $('#oimJR1BrowseUsrValet').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oJR1UsrValetOption = undefined;
            oJR1UsrValetOption = oJR1UsrValet({
                'tReturnInputCode': 'oetJR1UsrValetCode',
                'tReturnInputName': 'oetJR1UsrValetName',
                'tParamsLoginLevel': '<?= $this->session->userdata("tSesUsrLevel") ?>',
                'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tParamsBchCode': '<?= $this->session->userdata("tSesUsrBchCodeDefault") ?>'
            });
            JCNxBrowseData('oJR1UsrValetOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ============================================ Start Browse ลูกค้า ================================================
    var oJR1Customer = function(poDataFnc) {
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let oOptionReturn = {
            Title: ['document/jobrequest1/jobrequest1', 'tJR1Cst'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join: {
                Table: ['TCNMCst_L', 'TCNMCstCredit' ,'TCNMCstLev'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits,
                    'TCNMCst_L.FTCstCode = TCNMCstCredit.FTCstCode',
                    'TCNMCst.FTClvCode = TCNMCstLev.FTClvCode'
                ]
            },
            Where: {
                Condition: ["AND TCNMCst.FTCstStaActive = '1' "]
            },
            GrideView: {
                ColumnPathLang: 'document/jobrequest1/jobrequest1',
                ColumnKeyLang: ['tJR1CstCode', 'tJR1CstName','','tJR1LabelCstTel'],
                ColumnsSize: ['15%', '75%','','20%'],
                WidthModal: 50,
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName', 'TCNMCst.FTCstCardID', 'TCNMCst.FTCstTel','TCNMCstLev.FTPplCode' ],
                DataColumnsFormat: ['', '','',''],
                DisabledColumns : [2, 4],
                Perpage         : 10,
                OrderBy         : ['TCNMCst.FDCreateOn DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMCst.FTCstCode"],
                Text: [tInputReturnName, "TCNMCst_L.FTCstName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
            RouteAddNew : 'customer',
            BrowseLev   : 0
        };
        return oOptionReturn;
    }

    $('#oimJR1BrowseCustomer').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oJR1CustomerOption = undefined;
            oJR1CustomerOption = oJR1Customer({
                'tReturnInputCode'  : 'oetJR1FrmCstCode',
                'tReturnInputName'  : 'oetJR1FrmCstName',
                'tNextFuncName'     : 'JSxWhenSeletedCustomer',
                'aArgReturn'        : ['FTCstCode', 'FTCstName', 'FTCstCardID', 'FTCstTel','FTPplCode']
            });
            JCNxBrowseData('oJR1CustomerOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ฟังก์ชั่นทำงานหลังจากทำการเลือกข้อมูลไปค้นหาข้อมูลลูกค้า
    function JSxWhenSeletedCustomer(aReturn, ptType) {

        //ถ้าเปลี่ยนลูกค้าใหม่ต้องเคลียร์ค่าออก
        if (ptType != 'continue') {
            JSxClearValueRefInAndCar();
        }

        //ล้างค่า
        $('#oetJR1CarRegCode').val('');
        $('#oetJR1CarRegName').val('');

        
        if (aReturn != '' || aReturn != 'NULL') {

            //กลุ่มราคา
            aData = JSON.parse(aReturn);
            if(ptType != 'continue'){
                $('#ohdJOB1CustomerPPLCode').val(aData[4]);
            }

            // Find Data Customer
            $.ajax({
                type: "POST",
                url: "docJR1FindCst",
                data: {
                    "poItem": aReturn
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    let aReturn = JSON.parse(tResult);
                    if (aReturn != "") {
                        // Clear Input Customer
                        $('#oetJR1FrmCstTel').val('');
                        $('#oetJR1FrmCstEmail').val('');
                        $('#oetJR1FrmCstAddr').val('');

                        // Check Data Customer Info
                        let aDataCst = aReturn.aDataCst;
                        if (aDataCst.rtCode == 1) {
                            let tJR1CstTel = (aDataCst.raItems.FTCstTel != '') ? aDataCst.raItems.FTCstTel : '';
                            let tJR1CstEmail = (aDataCst.raItems.FTCstEmail != '') ? aDataCst.raItems.FTCstEmail : '';

                            $('#oetJR1FrmCstCode').val(aDataCst.raItems.FTCstCode);
                            $('#oetJR1FrmCstName').val(aDataCst.raItems.FTCstName);
                            $('#oetJR1FrmCstTel').val(tJR1CstTel);
                            $('#oetJR1FrmCstEmail').val(tJR1CstEmail);
                        }

                        // Check Data Customer Address
                        let aDataCstAddr = aReturn.aDataCstAddr;
                        if (aDataCstAddr.rtCode == 1) {
                            // Check Type Vession Address
                            let nAddrVer = aDataCstAddr.raItems.FTAddVersion;
                            if (nAddrVer == 1) {
                                // ทีอยู่เวอร์ชั่น 1
                                let tFTAddV1No = aDataCstAddr.raItems.FTAddV1No;
                                let tFTAddV1Soi = aDataCstAddr.raItems.FTAddV1Soi;
                                let tFTAddV1Road = aDataCstAddr.raItems.FTAddV1Road;
                                let tFTSudName = aDataCstAddr.raItems.FTAddV1SubDistName;
                                let tFTDstName = aDataCstAddr.raItems.FTAddV1DstName;
                                let tFTPvnName = aDataCstAddr.raItems.FTAddV1PvnName;
                                let tFTAddV1PostCode = aDataCstAddr.raItems.FTAddV1PostCode;
                                $('#oetJR1FrmCstAddr').val(tFTAddV1No + ' ' + tFTAddV1Soi + ' ' + tFTAddV1Road + ' ' + tFTSudName + ' ' + tFTDstName + ' ' + tFTPvnName + ' ' + tFTAddV1PostCode);
                            } else if (nAddrVer == 2) {
                                // ทีอยู่เวอร์ชั่น 2
                                let tAddV2Desc1 = aDataCstAddr.raItems.FTAddV2Desc1;
                                let tAddV2Desc2 = aDataCstAddr.raItems.FTAddV2Desc2;
                                $('#oetJR1FrmCstAddr').val(tAddV2Desc1 + ' ' + tAddV2Desc2);
                            }
                        }

                        // Issue Update By : Napat(Jame) 16/11/2021
                        // ถ้าลูกค้าเป็นเจ้าของรถ 1 คัน ให้ Default รหัสรถ แต่ถ้ามีมากกว่า 1 ปล่อยให้ user เลือกเอง
                        let aDataCarCst = aReturn.aDataCarCst;
                        if (aDataCarCst.rtCode == 1) {
                            // console.log(aDataCarCst.raItems);
                            var nCountOwnerCar = aDataCarCst.raItems.length;
                            if (nCountOwnerCar == 1) {
                                var aReturnItemCar = [];
                                aReturnItemCar.push(aDataCarCst['raItems'][0]['FTCarCode']);
                                JSxWhenSeletedCstCar(JSON.stringify(aReturnItemCar), 'continue');
                            }
                        }


                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            // Clear Input Customer
            $('#oetJR1FrmCstTel').val('');
            $('#oetJR1FrmCstEmail').val('');
            $('#oetJR1FrmCstAddr').val('');
        }
    }

    //ล้างค่า
    function JSxClearValueRefInAndCar() {
        //เคลียร์ค่าเอกสารอ้างอิง
        $('#oetJR1DocRefBookCode').val('')
        $('#oetJR1DocRefBookName').val('')
        $('#oetJR1DocRefBookDate').val('')

        //เคลียร์ค่ารถ
        $('#oetJR1PvnCode').val('');
        $('#oetJR1PvnName').val('');
        $('#oetJR1CarRedLabel').prop("checked", false);
        $('#oetJR1CarTypeCode').val('');
        $('#oetJR1CarTypeName').val('');
        $('#oetJR1CarBrandCode').val('');
        $('#oetJR1CarBrandName').val('');
        $('#oetJR1CarModelCode').val('');
        $('#oetJR1CarModelName').val('');
        $('#oetJR1CarColorCode').val('');
        $('#oetJR1CarColorName').val('');
        $('#oetJR1CarEnginereqCode').val('');
        $('#oetJR1CarOwnerName').val('');
        $('#oetJR1CarGearCode').val('');
        $('#oetJR1CarGearName').val('');
        $('#oetJR1CarVIDRef').val('');
    }

    // ========================================== Start Browse ทะเบียนรถ ==============================================
    var oJR1CarCst = function(poDataFnc) {
        let tInputReturnCode = poDataFnc.tReturnInputCode;
        let tInputReturnName = poDataFnc.tReturnInputName;
        let tNextFuncName = poDataFnc.tNextFuncName;
        let aArgReturn = poDataFnc.aArgReturn;
        let tParamsCstCode = poDataFnc.tParamsCstCode;
        let tWhereParams = " AND TCNMCst.FTCstStaActive = 1 ";

        if (tParamsCstCode != "") {
            tWhereParams += " AND TSVMCar.FTCarOwner = '" + tParamsCstCode + "' ";
        }

        let oOptionReturn = {
            Title: ['document/jobrequest1/jobrequest1', 'tJR1CarCst'],
            Table: {
                Master: 'TSVMCar',
                PK: 'FTCarCode'
            },
            Join: {
                Table: ['TCNMCst_L', 'TCNMCst'],
                On: ["TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = " + nLangEdits,
                    "TSVMCar.FTCarOwner = TCNMCst.FTCstCode"]
            },
            Where: {
                Condition: [tWhereParams]
            },
            GrideView: {
                ColumnPathLang: 'document/jobrequest1/jobrequest1',
                ColumnKeyLang: ['tJR1CarCstCode', 'tJR1CarCstName', 'tJR1OwnerCstName'],
                ColumnsSize: ['15%', '15%', '60%'],
                WidthModal: 50,
                DataColumns: ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat: ['', '', ''],
                DisabledColumns: [0,3],
                Perpage: 10,
                OrderBy: ['TSVMCar.FDCreateOn DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TSVMCar.FTCarOwner"],
                Text: [tInputReturnName, "TSVMCar.FTCarRegNo"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            },
            RouteAddNew: 'masCARView',
            BrowseLev: 0
        };
        return oOptionReturn;
    };
    $('#oimJR1BrowseCarRegNo').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oJR1CarCstOption = undefined;
            oJR1CarCstOption = oJR1CarCst({
                'tReturnInputCode'  : 'oetJR1CarRegCode',
                'tReturnInputName'  : 'oetJR1CarRegName',
                'tNextFuncName'     : 'JSxWhenSeletedCstCar',
                'aArgReturn'        : ['FTCarCode', 'FTCarRegNo'],
                'tParamsCstCode'    : $('#oetJR1FrmCstCode').val()
            });
            JCNxBrowseData('oJR1CarCstOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ฟังก์ชั่นทำงานหลังจากทำการเลือกข้อมูลทะเบียนรถ
    function JSxWhenSeletedCstCar(aReturn, ptType) {
        if (aReturn != '' || aReturn != 'NULL') {
            // Find Data Car
            $.ajax({
                type: "POST",
                url: "docJR1FindCstCar",
                data: {
                    "poItem": aReturn
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    let aReturn = JSON.parse(tResult);
                    // console.log(aReturn);
                    if (aReturn != "") {
                        // Start Clear Input Car Info
                        $('#oetJR1PvnCode').val('');
                        $('#oetJR1PvnName').val('');
                        $('#oetJR1CarRedLabel').prop("checked", false);
                        $('#oetJR1CarTypeCode').val('');
                        $('#oetJR1CarTypeName').val('');
                        $('#oetJR1CarBrandCode').val('');
                        $('#oetJR1CarBrandName').val('');
                        $('#oetJR1CarModelCode').val('');
                        $('#oetJR1CarModelName').val('');
                        $('#oetJR1CarColorCode').val('');
                        $('#oetJR1CarColorName').val('');
                        $('#oetJR1CarEnginereqCode').val('');
                        $('#oetJR1CarOwnerName').val('');
                        $('#oetJR1CarGearCode').val('');
                        $('#oetJR1CarGearName').val('');
                        $('#oetJR1CarVIDRef').val('');
                        // End Clear Input Car Info
                        let aDataCarCst = aReturn.aDataCarCst;
                        if (aDataCarCst.rtCode == 1) {

                            // ถ้าเลือกรถ ให้เอารหัสลูกค้าไปใส่ที่ browse input
                            if (ptType != 'continue') {
                                var aCstData = [];
                                aCstData.push(aDataCarCst.raItems.FTCarOwnerCode);
                                aCstData.push(aDataCarCst.raItems.FTCarOwnerName);
                                JSxWhenSeletedCustomer(JSON.stringify(aCstData), 'continue');

                                //เคลียร์ค่าเอกสารอ้างอิง
                                $('#oetJR1DocRefBookCode').val('');
                                $('#oetJR1DocRefBookName').val('');
                                $('#oetJR1DocRefBookDate').val('');
                            }

                            // Set Province Car Customer
                            let tJR1CarCstPvnCode = (aDataCarCst.raItems.FTCarRegPvnCode != '') ? aDataCarCst.raItems.FTCarRegPvnCode : '';
                            let tJR1CarCstPvnName = (aDataCarCst.raItems.FTCarRegPvnName != '') ? aDataCarCst.raItems.FTCarRegPvnName : '';
                            $('#oetJR1PvnCode').val(tJR1CarCstPvnCode);
                            $('#oetJR1PvnName').val(tJR1CarCstPvnName);
                            $('#oetJR1CarRegCode').val(aDataCarCst.raItems.FTCarCode)
                            $('#oetJR1CarRegName').val(aDataCarCst.raItems.FTCarRegNo)
                            // Set Check Box Car Red Label
                            if (aDataCarCst.raItems.FTCarStaRedLabel == '1') {
                                $('#oetJR1CarRedLabel').prop("checked", true);
                            } else if (aDataCarCst.raItems.FTCarStaRedLabel == '2') {
                                $('#oetJR1CarRedLabel').prop("checked", false);
                            } else {
                                $('#oetJR1CarRedLabel').prop("checked", false);
                            }
                            // Set Car type
                            let tJR1CartypeCode = (aDataCarCst.raItems.FTCarTypeCode != '') ? aDataCarCst.raItems.FTCarTypeCode : '';
                            let tJR1CartypeName = (aDataCarCst.raItems.FTCarTypeName != '') ? aDataCarCst.raItems.FTCarTypeName : '';
                            $('#oetJR1CarTypeCode').val(tJR1CartypeCode);
                            $('#oetJR1CarTypeName').val(tJR1CartypeName);
                            // Set Car Brand
                            let tJR1CarBrandCode = (aDataCarCst.raItems.FTCarBrandCode != '') ? aDataCarCst.raItems.FTCarBrandCode : '';
                            let tJR1CarBrandName = (aDataCarCst.raItems.FTCarBrandName != '') ? aDataCarCst.raItems.FTCarBrandName : '';
                            $('#oetJR1CarBrandCode').val(tJR1CarBrandCode);
                            $('#oetJR1CarBrandName').val(tJR1CarBrandName);
                            // Set Car Model
                            let tJR1CarModelCode = (aDataCarCst.raItems.FTCarModelCode != '') ? aDataCarCst.raItems.FTCarModelCode : '';
                            let tJR1CarModelName = (aDataCarCst.raItems.FTCarModelName != '') ? aDataCarCst.raItems.FTCarModelName : '';
                            $('#oetJR1CarModelCode').val(tJR1CarModelCode);
                            $('#oetJR1CarModelName').val(tJR1CarModelName);
                            // Set Car Color
                            let tJR1CarColorCode = (aDataCarCst.raItems.FTCarColorCode != '') ? aDataCarCst.raItems.FTCarColorCode : '';
                            let tJR1CarColorName = (aDataCarCst.raItems.FTCarColorName != '') ? aDataCarCst.raItems.FTCarColorName : '';
                            $('#oetJR1CarColorCode').val(tJR1CarColorCode);
                            $('#oetJR1CarColorName').val(tJR1CarColorName);
                            // Set Car Owner Type
                            let tJR1CarEngineNo = (aDataCarCst.raItems.FTCarEngineNo != '') ? aDataCarCst.raItems.FTCarEngineNo : '';
                            $('#oetJR1CarEnginereqCode').val(tJR1CarEngineNo);
                            $('#oetJR1CarEnginereqName').val(tJR1CarEngineNo);
                            // Set Car Gear
                            let tJR1CarGearCode = (aDataCarCst.raItems.FTCarGearCode != '') ? aDataCarCst.raItems.FTCarGearCode : '';
                            let tJR1CarGearName = (aDataCarCst.raItems.FTCarGearName != '') ? aDataCarCst.raItems.FTCarGearName : '';
                            $('#oetJR1CarGearCode').val(tJR1CarGearCode);
                            $('#oetJR1CarGearName').val(tJR1CarGearName);
                            // Set Car VIDRef
                            let tJR1CarVIDRef = (aDataCarCst.raItems.FTCarVIDRef != '') ? aDataCarCst.raItems.FTCarVIDRef : '';
                            $('#oetJR1CarVIDRef').val(tJR1CarVIDRef);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {}
    }

    // ========================================== Start Browse ช่องให้บริการ ==============================================
    //เลือกช่องให้บริการ
    $('#oetJR1LastBrowseBay').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#ohdJR1ADCode').val();
        var tBchCode = $('#ohdJR1BchCode').val();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            //ซ่อนบราว์หลัก
            JSxCheckPinMenuClose();
            window.oBKBrowsePosOption = oPosOption({
                'tReturnInputCode': 'oetJR1BayCode',
                'tReturnInputName': 'oetJR1BayName',
                'tAgnCode': tAgnCode,
                'tBchCode': tBchCode,
                'aArgReturn': ['FTSpsCode', 'FTSpsName'],
            });
            setTimeout(function() {
                JCNxBrowseData('oBKBrowsePosOption');
            }, 500);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //ตัวแปร Option Browse Modal จุดให้บริการ
    var oPosOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tAgnCode = poDataFnc.tAgnCode;
        var tBchCode = poDataFnc.tBchCode;
        var aArgReturn = poDataFnc.aArgReturn;
        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";
        // if(tUsrLevel != "HQ"){
        //     tSQLWhereBch = " AND TSVMPos.FTBchCode IN ("+tBchMulti+") ";
        // }
        if (tAgnCode != "") {
            tSQLWhereAgn = " AND TSVMPos.FTAgnCode IN (" + tAgnCode + ") ";
        }
        if (tBchCode != "") {
            tSQLWhereBch += " AND TSVMPos.FTBchCode = '" + tBchCode + "' ";
        }
        var oOptionReturn = {
            Title: ['service/calendar/calendar', 'tCLDTitle'],
            Table: {
                Master: 'TSVMPos',
                PK: 'FTSpsCode'
            },
            Join: {
                Table: ['TSVMPos_L'],
                On: ['TSVMPos_L.FTBchCode = TSVMPos.FTBchCode AND TSVMPos_L.FTSpsCode = TSVMPos.FTSpsCode AND TSVMPos.FTAgnCode = TSVMPos_L.FTAgnCode AND TSVMPos_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tSQLWhereBch, tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang: 'service/calendar/calendar',
                ColumnKeyLang: ['tCLDCode', 'tCLDName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TSVMPos.FTSpsCode', 'TSVMPos_L.FTSpsName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TSVMPos.FTSpsCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetJR1BayCode", "TSVMPos.FTSpsCode"],
                Text: ["oetJR1BayName", "TSVMPos_L.FTSpsName"]
            }
        };
        return oOptionReturn;
    }

    // เลือกสินค้า
    $('#obtJR1DocBrowsePdt').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            let tCarCode = $('#oetJR1CarRegCode').val();
            let tCstCode = $('#oetJR1FrmCstCode').val();
            if (tCstCode != "" && tCarCode != "") {
                var dTime = new Date();
                var dTimelocalStorage = dTime.getTime();
                setTimeout(function() {
                    //อนุญาต "ซื้อ" ที่หน่วย และ อนุญาต "ซื้อ" ที่บาร์โค๊ด
                    var aWhereItem = [];
                    tPDTAlwSale = ' AND (PPCZ.FTPdtStaAlwSale = 1 ';
                    aWhereItem.push(tPDTAlwSale);

                    tPDTAlwSale = " OR ISNULL(PPCZ.FTPdtStaAlwSale,null) = null ) ";
                    aWhereItem.push(tPDTAlwSale);

                    tPDTAlwSale = ' AND (PBAR.FTBarStaAlwSale = 1 ';
                    aWhereItem.push(tPDTAlwSale);

                    tPDTAlwSale = " OR ISNULL(PBAR.FTBarStaAlwSale,null) = null ) ";
                    aWhereItem.push(tPDTAlwSale);

                    var tPplCode = $('#ohdJOB1CustomerPPLCode').val();
                    
                    $.ajax({
                        type: "POST",
                        url: "BrowseDataPDT",
                        data: {
                            'Qualitysearch'     : [],
                            'PriceType'         : ['Price4Cst',tPplCode],
                            'SelectTier'        : ["Barcode"],
                            'ShowCountRecord'   : 10,
                            'NextFunc'          : "FSvJR1NextFuncWhenShowDTSet",
                            'ReturnType'        : "S",
                            'SPL'               : ['', ''],
                            'BCH'               : [$('#ohdJR1BchCode').val(), $('#ohdJR1BchCode').val()],
                            'MCH'               : ['', ''],
                            'SHP'               : ['', ''],
                            'TimeLocalstorage'  : dTimelocalStorage,
                            'Where'             : aWhereItem,
                            'PDTService'        : true,
                            'aAlwPdtType'       : ['T1', 'T2', 'T5', 'S2', 'S5']
                        },
                        cache: false,
                        timeout: 0,
                        success: function(tResult) {
                            $("#odvModalDOCPDT").modal({
                                backdrop: "static",
                                keyboard: false
                            });
                            $("#odvModalDOCPDT").modal({
                                show: true
                            });
                            //remove localstorage
                            localStorage.removeItem("LocalItemDataPDT");
                            $("#odvModalsectionBodyPDT").html(tResult);
                            $("#odvModalDOCPDT #oliBrowsePDTSupply").css('display', 'none');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            } else {
                FSvCMNSetMsgWarningDialog('กรุณาเลือกข้อมูลทะเบียนรถก่อน');
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // หลังจากเลือกสินค้า
    function FSvJR1NextFuncWhenShowDTSet(poParam) {
        if (poParam == '' || poParam == 'NULL') {} else {
            let aDataPdtBrowse = JSON.parse(poParam);

            for(var i=0;i<aDataPdtBrowse.length;i++){
                var aNewPackData = JSON.stringify(aDataPdtBrowse[i]);
                var aNewPackData = "["+aNewPackData+"]";
                JSxJRQEventRenderTemp(aNewPackData);      // Event Render : client
            }
            var tJR1OptionAddPdt = $('#ocmJR1FrmInfoOthReAddPdt').val();

            JSxJRQEventInsertToTemp(poParam); // Event Insert : server
        }
    }

    //Event Insert : server
    function JSxJRQEventInsertToTemp(poParam){
        $.ajax({
                type    : "POST",
                url     : "docJR1EventInsertToDT",
                data    : {
                    "poItem"            : poParam,
                    "tAgnCode"          : $('#ohdJR1ADCode').val(),
                    "tBchCode"          : $('#ohdJR1BchCode').val(),
                    "tDocumentNumber"   : $('#oetJR1DocNo').val(),
                    "tJR1OptionAddPdt"   : $('#ocmJR1FrmInfoOthReAddPdt').val(),
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    var aReturn = JSON.parse(tResult);
                    switch (aReturn['nStatusRenderDTSet']) {
                        case '1':
                            // สินค้าปกติ
                            //JSvJR1LoadPdtDataTableHtml();
                            break;
                        case '2':
                            // สินค้าปกติชุด
                            JSxJR1EventInsPdtSetDefault(poParam);
                            break;
                        case '5':
                            // สินค้าบริการชุด
                            JSvJR1LoadModalShowDTSetCstFollow(poParam, 'add');
                            break;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
    }
    
    // แสดงหน้าจอสินค้าเซต
    function JSvJR1LoadModalShowDTSetCstFollow(poParam, ptType) {
        if (poParam == '' || poParam == 'NULL') {} else {
            let aDataPdtBrowse = JSON.parse(poParam);
            let tCarCode = $('#oetJR1CarRegCode').val();
            let tCstCode = $('#oetJR1FrmCstCode').val();
            $.ajax({
                type    : "POST",
                url     : "docJR1PdtSetBehindSltPage",
                data    : {
                    "poItem"            : poParam,
                    "tAgnCode"          : $('#ohdJR1ADCode').val(),
                    "tBchCode"          : $('#ohdJR1BchCode').val(),
                    "tDocumentNumber"   : $('#oetJR1DocNo').val(),
                    "tCstCode"          : tCstCode,
                    "tCarCode"          : tCarCode,
                    'ptTypeAction'      : ptType,
                    "tJR1OptionAddPdt"   : $('#ocmJR1FrmInfoOthReAddPdt').val()
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    if (tResult != "") {
                        if(ptType == 'edit'){
                            $('#odvJR1HtmlPopUpDTSet').html(tResult);
                            $('#odvJR1HtmlPopUpDTSet #odvJR1ModalPopUpPstSet').modal('show');
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    // สินค้าปกติชุด เพิ่มข้อมูลสินค้าชุดไม่ต้องแสดง Modal
    function JSxJR1EventInsPdtSetDefault(poParam) {
        if (poParam == '' || poParam == 'NULL') {} else {
            let aDataPdtBrowse = JSON.parse(poParam);
            let tCarCode = $('#oetJR1CarRegCode').val();
            let tCstCode = $('#oetJR1FrmCstCode').val();
            $.ajax({
                type    : "POST",
                url     : "docJR1EventInsPdtSetType2",
                data    : {
                    "poItem"            : poParam,
                    "tAgnCode"          : $('#ohdJR1ADCode').val(),
                    "tBchCode"          : $('#ohdJR1BchCode').val(),
                    "tDocumentNumber"   : $('#oetJR1DocNo').val(),
                    "tCstCode"          : tCstCode,
                    "tCarCode"          : tCarCode,
                },
                cache   : false,
                timeout : 0,
                success : function(tResult) {
                    var aReturn = JSON.parse(tResult);
                    if (aReturn['nStaEvent'] == '1') {
                        // Success Call Load Pdt Data Table
                        //JSvJR1LoadPdtDataTableHtml();
                    } else {
                        var tTextMsg = aReturn['tStaMessg'];
                        FSvCMNSetMsgErrorDialog('<p class="text-left">' + tTextMsg + '</p>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //เช็คก่อนกดปุ่ม บันทึก
    $('#obtJR1SubmitFromDoc').unbind().click(function() {
        let tFrmCstCode = $('#oetJR1FrmCstCode').val();
        let tFrmCarRegCode = $('#oetJR1CarRegCode').val();
        let tFrmCarMiter = $('#oetJR1CarMiter').val();
        let tCheckIteminTable = $('#otbJR1DocPdtAdvTableList .xWPdtItem').length;
        let tJR1StaDoc = $('#ohdJR1StaDoc').val();
        let tJR1StaApv = $('#ohdJR1StaApv').val();
        let tFrmBayCode = $('#oetJR1BayCode').val();
        let tFrmUsrValetCode = $('#oetJR1UsrValetCode').val();

        if (tFrmCstCode == "") {
            FSvCMNSetMsgWarningDialog('กรุณาเลือกลูกค้า');
            return false;
        }

        if (tFrmCarRegCode == "") {
            FSvCMNSetMsgWarningDialog('กรุณาเลือกข้อมูลทะเบียนรถ');
            return false;
        }

        if (tCheckIteminTable > 0) {
            if (tFrmBayCode != "") {
                if (tFrmUsrValetCode != "") {
                    //if(tFrmCarMiter != ""){
                    // Check Appove Not Check Update Remark
                    if (tJR1StaDoc == '1' && tJR1StaApv == '1') {
                        JSxJR1SubmitEventByButton();
                    } else {

                        //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
                        $('#obtJR1SubmitFromDoc').attr('disabled', true);

                        var tRoute = $('#ohdJR1Route').val();
                        if (tRoute == 'docJR1EventAdd') {
                            $('#obtJR1SubmitDocument').click();
                        } else {
                            JSxJR1SubmitEventByButton();
                        }
                    }
                    /*}else{*/
                    //$('#oetJR1CarMiter').focus();
                    /*}*/
                } else {
                    FSvCMNSetMsgWarningDialog('กรุณาเลือกพนักงานที่ทำการรับรถ');
                }
            } else {
                let tTextValidate = $('#oetJR1BayName').attr('data-validate-required');
                FSvCMNSetMsgWarningDialog(tTextValidate);
            }
        } else {
            FSvCMNSetMsgWarningDialog('<?= language('document/transferreceiptOut/transferreceiptOut', 'tConditionPDTEmptyDetail') ?>');
        }
    });

    // Check DocNo DocDate DocTime
    function JSxJR1AddEditDocument() {
        $('#ofmJR1AddForm').validate({
            focusInvalid: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            rules: {
                oetJR1DocNo: {
                    "required": {
                        depends: function(oElement) {
                            if ($("#ohdJR1Route").val() == "docJR1EventAdd") {
                                if ($('#ocbJR1StaAutoGenCode').is(':checked')) {
                                    return false;
                                } else {
                                    return true;
                                }
                            } else {
                                return false;
                            }
                        }
                    }
                },
                oetJR1DocDate: {
                    "required": true
                },
                oetJR1DocTime: {
                    "required": true
                },
            },
            messages: {
                oetJR1DocNo: {
                    "required": $('#oetJR1DocNo').attr('data-validate-required')
                },
                oetJR1DocDate: {
                    "required": $('#oetJR1DocDate').attr('data-validate-required')
                },
                oetJR1DocTime: {
                    "required": $('#oetJR1DocTime').attr('data-validate-required')
                },
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                if (element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if (tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function(form) {
                if (!$('#ocbJR1StaAutoGenCode').is(':checked')) {
                    if ($("#ohdJR1Route").val() == "docJR1EventAdd") {
                        JSxJR1ValidateDocCodeDublicate();
                    } else {
                        JSxJR1SubmitEventByButton();
                    }
                } else {
                    JSxJR1SubmitEventByButton();
                }
            }
        });
    }

    // ถ้าปล่อยให้คีย์เองต้อง ตรวจสอบรหัสเอกสารว่าซ้ำไหม
    function JSxJR1ValidateDocCodeDublicate() {
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName': 'TSVTJob1ReqHD',
                'tFieldName': 'FTXshDocNo',
                'tCode': $('#oetJR1DocNo').val()
            },
            success: function(oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdJR1CheckDuplicateCode").val(aResultData["rtCode"]);
                if ($("#ohdJR1CheckDuplicateCode").val() != 1) {
                    $('#ofmJR1FormAdd').validate().destroy();
                }
                $.validator.addMethod('dublicateCode', function(value, element) {
                    if ($("#ohdJR1Route").val() == "docJR1EventAdd") {
                        if ($('#ocbJR1StaAutoGenCode').is(':checked')) {
                            return true;
                        } else {
                            if ($("#ohdJR1CheckDuplicateCode").val() == 1) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    } else {
                        return true;
                    }
                });
                // Set Form Validate From Add Document
                $('#ofmJR1FormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetJR1DocNo: {
                            "dublicateCode": {}
                        }
                    },
                    messages: {
                        oetJR1DocNo: {
                            "dublicateCode": $('#oetJR1DocNo').attr('data-validate-duplicate')
                        }
                    },
                    errorElement: "em",
                    errorPlacement: function(error, element) {
                        error.addClass("help-block");
                        if (element.prop("type") === "checkbox") {
                            error.appendTo(element.parent("label"));
                        } else {
                            var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                            if (tCheck == 0) {
                                error.appendTo(element.closest('.form-group')).trigger('change');
                            }
                        }
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).closest('.form-group').addClass("has-error");
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).closest('.form-group').removeClass("has-error");
                    },
                    submitHandler: function(form) {
                        JSxJR1SubmitEventByButton();
                    }
                })
                $("#ofmJR1FormAdd").submit();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // บันทึก ข้อมูล HD
    function JSxJR1SubmitEventByButton() {
        if ($("#ohdJR1Route").val() != "docJR1EventAdd") {
            var tJR1DocNo = $('#oetJR1DocNo').val();
        }

        $.ajax({
            type: "POST",
            url: "docJR1ChkHavePdtForDocDTTemp",
            data: {
                'tJR1DocNo': tJR1DocNo
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aDataReturnChkTmp = JSON.parse(oResult);
                if (aDataReturnChkTmp['nStaReturn'] == '1') {
                    //พวก selectpicker
                    $('.selectpicker').prop("disabled", false)
                    $.ajax({
                        type    : "POST",
                        url     : $("#ohdJR1Route").val(),
                        data    : $("#ofmJR1AddForm").serialize(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult) {
                            var aDataReturnEvent = JSON.parse(oResult);
                            if (aDataReturnEvent['nStaReturn'] == '1') {
                                var nJR1StaCallBack = aDataReturnEvent['nStaCallBack'];
                                if (nJR1StaCallBack == '' || nJR1StaCallBack == null || nJR1StaCallBack == undefined) {
                                    nJR1StaCallBack = 1;
                                }
                                var tJR1DocNo = aDataReturnEvent['tCodeReturn'];
                                var tAgnCode = aDataReturnEvent['tAgnCode'];
                                var tBchCode = aDataReturnEvent['tBchCode'];
                                var tCarCode = aDataReturnEvent['tCarCode'];

                                // ############ Call Function Insert Files ############
                                var oObjUplFiles = {
                                    'ptElementID'   : 'odvJR1FilesDataTable',
                                    'ptBchCode'     : tBchCode,
                                    'ptDocNo'       : tJR1DocNo,
                                    'ptDocKey'      : 'TSVTJob1ReqHD'
                                };
                                JCNxUPFInsertDataFile(oObjUplFiles);

                                //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
                                $('#obtJR1SubmitFromDoc').attr('disabled', false);

                                setTimeout(function() {
                                    switch (nJR1StaCallBack) {
                                        case '1':
                                            JSvJR1CallPageEdit(tAgnCode, tBchCode, tJR1DocNo, tCarCode);
                                            break;
                                        case '2':
                                            JSvJR1CallPageAdd();
                                            break;
                                        default:
                                            JSvJR1CallPageEdit(tAgnCode, tBchCode, tJR1DocNo, tCarCode);
                                            break;
                                    }
                                }, 700);
                            } else {

                                //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
                                $('#obtJR1SubmitFromDoc').attr('disabled', false);

                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
                            $('#obtJR1SubmitFromDoc').attr('disabled', false);

                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                } else if (aDataReturnChkTmp['nStaReturn'] == '800') {
                    var tMsgDataTempFound = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgWarningDialog('<p class="text-left">' + tMsgDataTempFound + '</p>');
                } else {
                    var tMsgErrorFunction = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">' + tMsgErrorFunction + '</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // ค้นหารายการในตาราง Temp
    function JSvJR1SearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbJR1DocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    // ค้นหารายการในตาราง Temp
    function JSvJR1SearchModalPdtHTML() {
        var value = $("#oetSearchModalPdtHTML").val().toLowerCase();
        $("#otbJR1TablePdtSet tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    // ######################################################## Browse AD ########################################################
    $('#obtJR1BrowseAgency').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oJR1BrowseAgnOption = undefined;
            oJR1BrowseAgnOption = oAgnOption({
                'tReturnInputCode': 'ohdJR1ADCode',
                'tReturnInputName': 'ohdJR1ADName',
                'tNextFuncName': 'JSxJR1SetConditionAfterSelectAGN'
            });
            JCNxBrowseData('oJR1BrowseAgnOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oAgnOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var tUsrLevSession = '<?= $this->session->userdata("tSesUsrLevel"); ?>';
        var tWhereAgn = '';
        var oOptionReturn = {
            Title: ['company/branch/branch', 'tBchAgnTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: [' TCNMAgency.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tWhereAgn]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBchAgnCode', 'tBchAgnName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMAgency.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: ['FTAgnCode']
            }
        };
        return oOptionReturn;
    }

    //หลังจากเลือก
    function JSxJR1SetConditionAfterSelectAGN(poDataNextFunc) {
        var aData;
        if (poDataNextFunc != "NULL") {
            $('#ohdJR1BchCode , #oetJR1BchName').val('');
        }
    }

    // ###################################################### Browse Branch ######################################################
    $('#obtJR1BrowseBranch').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            // Clear Value Browse Data
            // $('#ohdJR1BchCode').val('');
            // $('#oetJR1BchName').val('');
            JSxCheckPinMenuClose();
            window.oJR1BrowseBranchOption = undefined;
            oJR1BrowseBranchOption = oBranchOption({
                'tReturnInputCode': 'ohdJR1BchCode',
                'tReturnInputName': 'oetJR1BchName',
                'tNextFuncName': 'JSxJR1SetConditionAfterSelectBCH'
            });
            JCNxBrowseData('oJR1BrowseBranchOption');
        } else {
            JCNxShowMsgSessionExpired();
        };
    });

    var oBranchOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var tSQLWhere = "";
        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        if (tUsrLevel != "HQ") { //แบบสาขา
            tSQLWhere = " AND TCNMBranch.FTBchCode IN (" + tBchMulti + ") ";
        } else { //สำนักงานใหญ่
            if ($('#ohdJR1ADCode').val() == '' || $('#ohdJR1ADCode').val() == null) {
                tSQLWhere += "";
            } else {
                tSQLWhere += " AND (TCNMBranch.FTAgnCode = " + $('#ohdJR1ADCode').val() + ")";
            }
        }
        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tSQLWhere]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: ['FTBchCode', 'FTBchName']
            },
            // DebugSQL : true
        };
        return oOptionReturn;
    }

    //หลังจากเลือกสาขา
    function JSxJR1SetConditionAfterSelectBCH(poDataNextFunc) {
        var aData;
        if (poDataNextFunc != "NULL") {
            var aResult = JSON.parse(poDataNextFunc);
            $('#ohdJR1BchCode').val(aResult[0]);
            $('#oetJR1BchName').val(aResult[1]);
        }
    }

    //พิมพ์เอกสาร
    function JSxJR1PrintDoc() {
        let tGrandText = $('#odvJR1DataTextBath').text();
        let tFireShow = $('#oetJR1FrmCarChkRmk1').val();
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tJR1BchCode); ?>'
            },
            {
                "DocCode": '<?= @$tJR1DocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tJR1BchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMVehicleRcv?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand=" + tGrandText + "&FireShow=" + tFireShow, '_blank');
    }

    //อนุมัติเอกสาร
    function JSxJR1DocumentApv(pbIsConfirm) {
        if (pbIsConfirm) {
            $("#odvJR1PopupApv").modal('hide');
            var tDocNo      = $('#oetJR1DocNo').val();
            var tAgnCode    = $('#ohdJR1ADCode').val();
            var tBchCode    = $('#ohdJR1BchCode').val();
            // $("#obtJR1SubmitFromDoc").trigger('click');
            // $('#obtJR1SubmitFromDoc').attr('disabled', true);
            $('#obtJR1ApproveDoc').attr('disabled', true);
            
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docJR1ApproveDocument",
                data    : {
                    'tDocNo'    : tDocNo,
                    'tBchCode'  : tBchCode
                },
                cache   : false,
                timeout : 0,
                async   : false,
                success : function(tResult) {
                    JCNxCloseLoading();
                    var aReturnData = JSON.parse(tResult);

                    /*if(aReturnData['tStaChkWah'] == 0){ //ไม่ต้องเช็คคลัง
                        // อนุมัติเอกสารสำเร็จ
                        JSvJR1CallPageEdit(tAgnCode, tBchCode, tDocNo, $('#oetJR1CarRegCode').val());
                        $('#obtJR1SubmitFromDoc').attr('disabled', false);
                        $('#obtJR1ApproveDoc').attr('disabled', false);

                        //ลบ queue
                        var poDelQnameParams = {
                            "ptPrefixQueueName" : 'RESJRQ',
                            "ptBchCode"         : "",
                            "ptDocNo"           : tDocNo,
                            "ptUsrCode"         : '<?=$this->session->userdata('tSesUsername')?>'
                        };
                        FSxCMNRabbitMQDeleteQname(poDelQnameParams);
                    }else{*/
                        if (aReturnData['nStaEvent'] == 1) {
                            JSxJR1StaDocCallModalMQ();
                        }else{

                        }
                    /*}*/
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            $("#odvJR1PopupApv").modal('show');
        }
    }

    function JSxJR1StaDocCallModalMQ(){
        var nJRQLangEdits        = nLangEdits;
        var tJRQFrmBchCode       = $("#ohdJR1BchCode").val();
        var tJRQUsrApv           = '<?=$this->session->userdata('tSesUsername')?>';
        var tJRQDocNo            = $("#oetJR1DocNo").val();
        var tJRQPrefix           = "RESJRQ";
        var tJRQStaApv           = $("#ohdJR1StaApv").val();
        var tJRQStaPrcStk        = $("#ohdJRQStaPrcStk").val();
        var tJRQQName            = tJRQPrefix + "_" + tJRQDocNo + "_" + tJRQUsrApv;
        var tJRQTableName        = "TSVTJob1ReqHD";
        var tJRQFieldDocNo       = "FTXshDocNo";
        var tJRQFieldStaApv      = "";
        var tJRQFieldStaDelMQ    = "";

        // MQ Message Config
        var poDocConfig = {
            tLangCode     : nJRQLangEdits,
            tUsrBchCode   : tJRQFrmBchCode,
            tUsrApv       : tJRQUsrApv,
            tDocNo        : tJRQDocNo,
            tPrefix       : tJRQPrefix,
            tStaDelMQ     : 1,
            tStaApv       : tJRQStaApv,
            tQName        : tJRQQName
        };

       // RabbitMQ STOMP Config
        var poMqConfig = {
            host        : "ws://" + oSTOMMQConfig.host + ":15674/ws",
            username    : oSTOMMQConfig.user,
            password    : oSTOMMQConfig.password,
            vHost       : oSTOMMQConfig.vhost
        };

        // Update Status For Delete Qname Parameter
        var poUpdateStaDelQnameParams   = {
            ptDocTableName      : tJRQTableName,
            ptDocFieldDocNo     : tJRQFieldDocNo,
            ptDocFieldStaApv    : tJRQFieldStaApv,
            ptDocFieldStaDelMQ  : "",
            ptDocStaDelMQ       : 1,
            ptDocNo             : tJRQDocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit: "JSvJR1WhenAproveDone",
            tCallPageList: "JSvJR1CallPageList"
        };

        // Check Show Progress %
        FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
    }

    //หลังจากอนุมัติ
    function JSvJR1WhenAproveDone(ptDocumentNumber){
        // Set time Out Check Stock Respon Behide Appove
        $.ajax({
            type    : "POST",
            url     : "docJR1CheckProductWahouse",
            data    : {
                'tDocNo'    : ptDocumentNumber,
                'tBchCode'  : $("#ohdJR1BchCode").val()
            },
            cache: false,
            timeout: 0,
            success: function(tResults) {
                var aReturnDataCheck = JSON.parse(tResults);
                if (aReturnDataCheck['nStaEvent'] == '1') {

                    // อนุมัติเอกสารสำเร็จ
                    JSvJR1CallPageEdit('', $("#ohdJR1BchCode").val(), ptDocumentNumber, $('#oetJR1CarRegCode').val());
                    $('#obtJR1SubmitFromDoc').attr('disabled', false);
                    $('#obtJR1ApproveDoc').attr('disabled', false);
                } else {

                    // ไม่ผ่าน
                    $('#obtJR1SubmitFromDoc').attr('disabled', false);
                    $('#obtJR1ApproveDoc').attr('disabled', false);
                    $("#odvJR1PopupApv").modal("hide");
                    $('.modal-backdrop').remove();

                    //เอาชื่อสินค้าที่ไม่พอ ไปโชว์
                    var tMessageError       = 'ไม่สามารถอนุมัติเอกสารได้เนื่องจากมีสินค้าบางรายการมีสต๊อกไม่เพียงพอ';
                    var aItemFail           = aReturnDataCheck['aItemFail'];
                    var tTextStockFail      = '';
                    var tTextStockFailShow  = '';
                    for(var i=0; i<aItemFail.length; i++){
                        tTextStockFail += '('+aItemFail[i][0]+')' + ' - ' + aItemFail[i][1] + ' [ร้องขอ : ' + aItemFail[i][2] + ' ชิ้น , พบในคลัง : ' + aItemFail[i][3] + ' ชิ้น] <br>';
                    }
                    tTextStockFailShow = '<p style="font-weight: bold;">'+tTextStockFail+'</p>';
                    
                    // Modal Stock ไม่พอต้องเเจ้งเตือน
                    $('#ospTextModalNotAproveStockFail').html('');
                    $('#ospTextModalNotAproveStockFail').html(tMessageError + '<br>' + tTextStockFailShow);
                    $("#odvJR1ModalNotAproveBecauseStockFail").modal("show");

                    //ลบ queue
                    // var poDelQnameParams = {
                    //         "ptPrefixQueueName" : 'RESJRQ',
                    //         "ptBchCode"         : "",
                    //         "ptDocNo"           : tDocNo,
                    //         "ptUsrCode"         : '<?=$this->session->userdata('tSesUsername')?>'
                    //     };
                    // FSxCMNRabbitMQDeleteQname(poDelQnameParams);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดตกลงแล้วจะโหลดหน้าจอสินค้าอีกครั้ง
    function JSxJR1ReloadDatatableDT(){
        $("#odvJR1ModalNotAproveBecauseStockFail").modal("hide");

        //อัพเดทสินค้าใน DT ไปที่ Temp อีกรอบ เพื่อเอาสถานะ ของสินค้าล่าสุด
        $.ajax({
            type    : "POST",
            url     : "docJR1MoveDTToTemp",
            data    : {
                'tDocNo'    : $('#oetJR1DocNo').val(),
                'tBchCode'  : $('#ohdJR1BchCode').val()
            },
            cache   : false,
            timeout : 0,
            success : function(tResults) {
                JSvJR1LoadPdtDataTableHtml();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //สแกนบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        var tCarCode = $('#oetJR1CarRegCode').val();
        var tCstCode = $('#oetJR1FrmCstCode').val();
        if (tCarCode != "" && tCstCode != "") {
            JSxCheckPinMenuClose();
            if (tValue.length === 0) {

            } else {
                $('#oetJR1InsertBarcode').attr('readonly', true);
                JCNSearchBarcodePdt(tValue);
                $('#oetJR1InsertBarcode').val('');
            }
        } else {
            FSvCMNSetMsgWarningDialog('กรุณาเลือกข้อมูลทะเบียนรถก่อน');
            $('#oetJR1InsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan) {

        if ($('#oetJR1CarRegCode').val() == "" || $('#oetJR1CarRegCode').val() == null) {
            $('#oetJR1InsertBarcode').attr('readonly', false);
            $('#oetJR1InsertBarcode').val('');
            FSvCMNSetMsgWarningDialog('กรุณาเลือกข้อมูลทะเบียนรถก่อน');
            return;
        }

        var tWhereCondition = "";

        //อนุญาต "ซื้อ" ที่หน่วย และ อนุญาต "ซื้อ" ที่บาร์โค๊ด
        var aWhereItem      = [];
        tPDTAlwSale         = ' AND (PPCZ.FTPdtStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PPCZ.FTPdtStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = ' AND (PBAR.FTBarStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PBAR.FTBarStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        var tPplCode = $('#ohdJOB1CustomerPPLCode').val();
        
        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                'Qualitysearch'     : [],
                'ReturnType'        : "S",
                'ShowCountRecord'   : 10,
                'aPriceType'        : ['Price4Cst',tPplCode],
                'NextFunc'          : "",
                'SelectTier'        : ["Barcode"],
                'SPL'               : '',
                'BCH'               : $('#ohdJR1BchCode').val(),
                'MCH'               : '',
                'SHP'               : '',
                'tTextScan'         : ptTextScan,
                'tTYPEPDT'          : '',
                'tSNPDT'            : '',
                'tWhere'            : aWhereItem,
                'aPackDataForSearch': {
                    'tSearchPDTType': "T1,T2,T5,S2,S5"
                }
            },
            catch: false,
            timeout: 0,
            success: function(tResult) {
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetJR1InsertBarcode').attr('readonly', false);
                    FSvCMNSetMsgWarningDialog('ไม่พบสินค้า หรือ สินค้าไม่มีบาร์โค้ด');
                    $('#oetJR1InsertBarcode').val('');
                } else {

                    // พบสินค้ามีหลายบาร์โค้ด
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvJS1ModalPDTMoreOne').modal('show');
                        $('#odvJS1ModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
                        for (i = 0; i < oText.length; i++) {
                            var aNewReturn = JSON.stringify(oText[i]);
                            var tTest = "[" + aNewReturn + "]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne" + i + " xCNColumnPDTMoreOne' data-information='" + oEncodePackData + "' style='cursor: pointer;'>";
                            tHTML += "<td>" + oText[i].pnPdtCode + "</td>";
                            tHTML += "<td>" + oText[i].packData.PDTName + "</td>";
                            tHTML += "<td>" + oText[i].packData.PUNName + "</td>";
                            tHTML += "<td>" + oText[i].ptBarCode + "</td>";
                            tHTML += "</tr>";
                            $('#odvJS1ModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        /*$('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvJS1ModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            $.ajax({
                                type: "POST",
                                url: "docJR1EventInsertToDT",
                                data: {
                                    "poItem": tJSON,
                                    "tAgnCode": $('#ohdJR1ADCode').val(),
                                    "tBchCode": $('#ohdJR1BchCode').val(),
                                    "tDocumentNumber": $('#oetJR1DocNo').val(),
                                },
                                cache: false,
                                timeout: 0,
                                success: function(tResult) {
                                    var aReturn = JSON.parse(tResult);
                                    switch (aReturn['nStatusRenderDTSet']) {
                                        case '1':
                                            // สินค้าปกติ
                                            JSvJR1LoadPdtDataTableHtml();
                                            break;
                                        case '2':
                                            // สินค้าปกติชุด
                                            JSxJR1EventInsPdtSetDefault(tJSON);
                                            break;
                                        case '5':
                                            // สินค้าบริการชุด
                                            JSvJR1LoadModalShowDTSetCstFollow(tJSON, 'add');
                                            break;
                                    }
                                    $('#oetJR1InsertBarcode').attr('readonly', false);
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        });*/

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click', function(e) {

                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align', 'right');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            $(this).children().last().css('text-align', 'right');
                        });
                    } else {
                        // let aDataPdtBrowse  = JSON.parse(tResult);
                        var aNewReturn = JSON.stringify(oText);

                        //เพิ่มสินค้า
                        FSvJR1NextFuncWhenShowDTSet(aNewReturn);

                        // console.log(aNewReturn);
                        /*$.ajax({
                            type: "POST",
                            url: "docJR1EventInsertToDT",
                            data: {
                                "poItem": aNewReturn,
                                "tAgnCode": $('#ohdJR1ADCode').val(),
                                "tBchCode": $('#ohdJR1BchCode').val(),
                                "tDocumentNumber": $('#oetJR1DocNo').val(),
                            },
                            cache: false,
                            timeout: 0,
                            success: function(tResult) {
                                var aReturn = JSON.parse(tResult);
                                switch (aReturn['nStatusRenderDTSet']) {
                                    case '1':
                                        // สินค้าปกติ
                                        JSvJR1LoadPdtDataTableHtml();
                                        break;
                                    case '2':
                                        // สินค้าปกติชุด
                                        JSxJR1EventInsPdtSetDefault(aNewReturn);
                                        break;
                                    case '5':
                                        // สินค้าบริการชุด
                                        JSvJR1LoadModalShowDTSetCstFollow(aNewReturn, 'add');
                                        break;
                                }
                                $('#oetJR1InsertBarcode').attr('readonly', false);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                            }
                        });*/
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });
    }

    //เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType) {
        if ($ptType == 1) {
            $("#odvJS1ModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                $.ajax({
                    type: "POST",
                    url: "docJR1EventInsertToDT",
                    data: {
                        "poItem": tJSON,
                        "tAgnCode": $('#ohdJR1ADCode').val(),
                        "tBchCode": $('#ohdJR1BchCode').val(),
                        "tDocumentNumber": $('#oetJR1DocNo').val(),
                        "tJR1OptionAddPdt"   : $('#ocmJR1FrmInfoOthReAddPdt').val(),
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        var aReturn = JSON.parse(tResult);
                        switch (aReturn['nStatusRenderDTSet']) {
                            case '1':
                                // สินค้าปกติ
                                //JSvJR1LoadPdtDataTableHtml();
                                break;
                            case '2':
                                // สินค้าปกติชุด
                                JSxJR1EventInsPdtSetDefault(tJSON);
                                break;
                            case '5':
                                // สินค้าบริการชุด
                                JSvJR1LoadModalShowDTSetCstFollow(tJSON, 'add');
                                break;
                        }
                        $('#oetJR1InsertBarcode').attr('readonly', false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        } else {
            $('#oetJR1InsertBarcode').attr('readonly', false);
            $('#oetJR1InsertBarcode').val('');
        }
    }

</script>