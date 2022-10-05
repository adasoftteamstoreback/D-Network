
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPdtClaimResult')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPdtClaimResult
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPdtClaimResult
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) 
    , @ptSplCode varchar(20) 
    , @ptDocType varchar(2) -- สถานะการ Gen 11: CreditNote, 10: DebitNote
    , @FNResult INT OUTPUT AS

DECLARE @tTrans varchar(20)
DECLARE @tStaPrcDoc varchar(1) -- สถานะการทำงาน  1 : รออนุมัติ , 2 : รอส่งสินค้าไปยังผู้จำหน่าย , 3 : รอรับสินค้าจากผู้จำหน่าย , 4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 5 : รอส่งสินค้าให้ลูกค้า , 6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 7 : ปิดงานแล้ว

DECLARE @tAgnDoc varchar(10) --Agn เอกสาร
DECLARE @tBchDoc varchar(50) --สาขา เอกสาร
DECLARE @tGenDocNo varchar(30) --เลขที่ เอกสาร

DECLARE @tVatInOrExt varchar(1)
DECLARE @tVatCode varchar(5)
DECLARE @cVatRate numeric(18, 4)
DECLARE @tRteCode varchar(5)
DECLARE @cRteFac numeric(18, 4)

DECLARE	@nStoreRet int
DECLARE @tResult varchar(30)
DECLARE @dDateNow DATETIME

DECLARE @TblGenDoc TABLE
(
    FTXxhDocNo VARCHAR(30)
)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	1/11/2021		Net		create 
----------------------------------------------------------------------*/
SET @tTrans = 'GenWrn'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @dDateNow = GETDATE()

    -- Get สถานะเอกสาร
    SELECT DISTINCT @tStaPrcDoc = ISNULL(HD.FTPchStaPrcDoc, '')
    , @tAgnDoc = ISNULL(HD.FTAgnCode, '')
    FROM TCNTPdtClaimHD HD WITH(NOLOCK)
    INNER JOIN TCNTPdtClaimDTRcv DTRcv WITH(NOLOCK) ON
        HD.FTBchCode = DTRcv.FTBchCode AND HD.FTPchDocNo = DTRcv.FTPchDocNo
    INNER JOIN TCNTPdtClaimDTWrn DTWrn WITH(NOLOCK) ON
        DTRcv.FTBchCode = DTWrn.FTBchCode AND DTRcv.FTPchDocNo = DTWrn.FTPchDocNo
        AND DTRcv.FNPcdSeqNo = DTWrn.FNPcdSeqNo
    INNER JOIN TCNTPdtClaimDTRet DTRet WITH(NOLOCK) ON
        DTWrn.FTBchCode = DTRet.FTBchCode AND DTWrn.FTPchDocNo = DTRet.FTPchDocNo
        AND DTWrn.FNPcdSeqNo = DTRet.FNPcdSeqNo
    WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        AND DTWrn.FTSplCode = @ptSplCode AND ISNULL(DTRet.FTRetRefDoc,'') = ''
        AND ISNULL(DTRcv.FTRcvRefTwi,'') <> ''
        AND @ptDocType = (CASE WHEN ISNULL(DTWrn.FCWrnPercent,0)=0 AND ISNULL(DTWrn.FCWrnDNCNAmt,0)>0 
                                      THEN '10'
                               WHEN ISNULL(DTWrn.FCWrnPercent,0)>0 AND ISNULL(DTWrn.FCWrnPercent,0)<100 AND ISNULL(DTWrn.FCWrnDNCNAmt,0)>0 
                                      THEN '11'
                               ELSE ''
                          END)

    IF @tStaPrcDoc IN ('5','6')  -- อนุมัติแล้ว
    BEGIN

        --Gen เลขที่เอกสาร ใบรับของ
        INSERT @TblGenDoc 
        EXEC @nStoreRet = [dbo].[SP_CNtAUTAutoDocNo]
            @ptTblName = N'TPSTTaxHD'
		    , @ptDocType = @ptDocType
		    , @ptBchCode = @ptBchCode
		    , @ptShpCode = NULL
		    , @ptPosCode = NULL
		    , @pdDocDate = @dDateNow
		    , @ptResult = @tResult OUTPUT

        SET @tGenDocNo = (SELECT TOP 1 FTXxhDocNo FROM @TblGenDoc)


        --ถ้า Gen เลขที่เอกสารไม่ได้
        IF ISNULL(@tGenDocNo, '') = '' 
            THROW 50000, 'SP_CNtAUTAutoDocNo Error', 0;

        -- Get VatComp
        SELECT TOP 1 @tVatCode = VAT.FTVatCode, @cVatRate = VAT.FCVatRate
        , @tVatInOrExt = CMP.FTCmpRetInOrEx, @tRteCode = CMP.FTRteCode
        , @cRteFac = RTE.FCRteFraction
        FROM TCNMComp CMP WITH(NOLOCK)
        INNER JOIN(
            SELECT FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
        )VAT ON CMP.FTVatCode = VAT.FTVatCode
        INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
            CMP.FTRteCode = RTE.FTRteCode
        WHERE FNRank = 1

        -- Get VatAgn ถ้ามี
        IF ISNULL(@tAgnDoc, '') <> ''
        BEGIN
            SELECT TOP 1 @tVatCode = ISNULL(VAT.FTVatCode, @tVatCode)
            , @cVatRate = ISNULL(VAT.FCVatRate, @cVatRate)
            , @tVatInOrExt = ISNULL(AGN.FTCmpVatInOrEx, @tVatInOrExt)
            , @tRteCode = AGN.FTRteCode
            , @cRteFac = RTE.FCRteFraction
            FROM TCNMAgencySpc AGN WITH(NOLOCK)
            INNER JOIN(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT ON AGN.FTVatCode = VAT.FTVatCode
            INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
                AGN.FTRteCode = RTE.FTRteCode
            WHERE FNRank = 1 AND AGN.FTAgnCode = ISNULL(@tAgnDoc, '')
        END

        ---------- Gen เอกสาร ----------
        INSERT TCNTPdtClaimHDDocRef
        (
            FTAgnCode, FTBchCode, FTPchDocNo, FTXshRefType, FTXshRefDocNo
            , FTXshRefKey, FDXshRefDocDate
        )
        SELECT DISTINCT HD.FTAgnCode, HD.FTBchCode, HD.FTPchDocNo, '2', @tGenDocNo
        , (CASE WHEN @ptDocType='10' THEN 'DNAMT' ELSE 'CNAMT' END) , GETDATE()
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        

        INSERT TPSTTaxHDDocRef
        (
            FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, HD.FTPchDocNo, '1', 'CLAIM', GETDATE()
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        

        INSERT TPSTTaxHDCst
        (
            FTBchCode, FTXshDocNo, FTXshCardID, FTXshCstTel, FTXshCstName
            , FTXshCardNo, FNXshCrTerm, FDXshDueDate, FDXshBillDue, FTXshCtrName
            , FDXshTnfDate, FTXshRefTnfID, FNXshAddrShip, FTXshAddrTax, FTXshCourier
            , FTXshCourseID, FTXshCstRef, FTXshCstEmail
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, HDCst.FTXshCardID, '' AS FTXshCstTel, ISNULL(CStl.FTCstName,'')
        , HDCst.FTXshCardNo, HDCst.FNXshCrTerm, HDCst.FDXshDueDate, HDCst.FDXshBillDue, HDCst.FTXshCtrName
        , HDCst.FDXshTnfDate, HDCst.FTXshRefTnfID, HDCst.FNXshAddrShip, HDCst.FTXshAddrTax, NULL AS FTXshCourier
        , NULL AS FTXshCourseID, NULL AS FTXshCstRef, NULL AS FTXshCstEmail
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimHDCst HDCst WITH(NOLOCK) ON
            HD.FTBchCode = HDCst.FTBchCode AND HD.FTPchDocNo = HDCst.FTPchDocNo
        LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON
            HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = 1
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        INSERT TPSTTaxDT
        (
            FTBchCode, FTXshDocNo, FNXsdSeqNo, FTPdtCode, FTXsdPdtName
            , FTPunCode, FTPunName, FCXsdFactor, FTXsdBarCode, FTSrnCode
            , FTXsdVatType, FTVatCode, FCXsdVatRate, FTXsdSaleType, FCXsdSalePrice
            , FCXsdQty, FCXsdQtyAll, FCXsdSetPrice, FCXsdAmtB4DisChg
            , FTXsdDisChgTxt, FCXsdDis, FCXsdChg, FCXsdNet, FCXsdNetAfHD
            , FCXsdVat, FCXsdVatable, FCXsdWhtAmt, FTXsdWhtCode, FCXsdWhtRate
            , FCXsdCostIn, FCXsdCostEx, FTXsdStaPdt, FCXsdQtyLef, FCXsdQtyRfn
            , FTXsdStaPrcStk, FTXsdStaAlwDis, FNXsdPdtLevel, FTXsdPdtParent
            , FCXsdQtySet, FTPdtStaSet, FTXsdRmk, FTPplCode
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, 1 AS FNXsdSeqNo, PDT.FTPdtCode, CONVERT(VARCHAR(100),(CASE WHEN @ptDocType='10' THEN 'DebitNote ' ELSE 'CreditNote ' END )+SumAmt.FTSplName) AS FTXsdPdtName
        , PDT.FTPunCode, PDT.FTPunName, PDT.FCPdtUnitFact, PDT.FTBarCode, '' AS FTSrnCode
        , ISNULL(PDT.FTPdtStaVat,'2'), ISNULL(PDT.FTVatCode, @tVatCode), ISNULL(PDT.FCVatRate, @cVatRate), PDT.FTPdtSaleType, ISNULL(SumAmt.FCWrnDNCNAmt,0)
        , 1 AS FCXsdQty, 1*PDT.FCPdtUnitFact, ISNULL(SumAmt.FCWrnDNCNAmt,0), 1*ISNULL(SumAmt.FCWrnDNCNAmt,0)
        , '' AS FTXsdDisChgTxt, 0 AS FCXsdDis, 0 AS FCXsdChg, 1*ISNULL(SumAmt.FCWrnDNCNAmt,0),1*ISNULL(SumAmt.FCWrnDNCNAmt,0)
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (1 * ISNULL(SumAmt.FCWrnDNCNAmt,0)) * ISNULL(PDT.FCVatRate, @cVatRate)/(100+ISNULL(PDT.FCVatRate, @cVatRate))
                            ELSE (1 * ISNULL(SumAmt.FCWrnDNCNAmt,0)) * ISNULL(PDT.FCVatRate, @cVatRate)/100
                     END
                ELSE 0
          END AS FCXtdVat
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (1 * ISNULL(SumAmt.FCWrnDNCNAmt,0)) * 100/(100+ISNULL(PDT.FCVatRate, @cVatRate))
                            ELSE (1 * ISNULL(SumAmt.FCWrnDNCNAmt,0))
                     END
                ELSE (1 * ISNULL(SumAmt.FCWrnDNCNAmt,0))
          END AS FCXtdVatable
        , NULL AS FCXsdWhtAmt, NULL AS FTXsdWhtCode, NULL AS FCXsdWhtRate, NULL AS FCXsdCostIn, NULL AS FCXsdCostEx
        , '1' AS FTXsdStaPdt, 1, 0 AS FCXsdQtyRfn
        , '1' AS FTXsdStaPrcStk, PDT.FTPdtStaAlwDis, NULL AS FNXsdPdtLevel, '' AS FTXsdPdtParent
        , NULL AS FCXsdQtySet, '' AS FTPdtStaSet, '' AS FTXsdRmk, '' AS FTPplCode
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN(
            SELECT @ptBchCode AS FTBchCode, @ptDocNo AS FTPchDocNo
            , ISNULL(SUM(DTWrn.FCWrnDNCNAmt),0) AS FCWrnDNCNAmt
            , ISNULL(SPLL.FTSplName,'') AS FTSplName
            FROM TCNTPdtClaimDTWrn DTWrn WITH(NOLOCK)
            INNER JOIN TCNTPdtClaimDTRcv DTRcv WITH(NOLOCK) ON
                DTWrn.FTBchCode = DTRcv.FTBchCode AND DTWrn.FTPchDocNo = DTRcv.FTPchDocNo
                AND DTWrn.FNPcdSeqNo = DTRcv.FNPcdSeqNo
            INNER JOIN TCNTPdtClaimDTRet DTRet WITH(NOLOCK) ON
                DTWrn.FTBchCode = DTRet.FTBchCode AND DTWrn.FTPchDocNo = DTRet.FTPchDocNo
                AND DTWrn.FNPcdSeqNo = DTRet.FNPcdSeqNo
            LEFT JOIN TCNMSpl_L SPLL WITH(NOLOCK) ON
                DTWrn.FTSplCode = SPLL.FTSplCode AND SPLL.FNLngID = 1
            WHERE DTWrn.FTBchCode = @ptBchCode AND DTWrn.FTPchDocNo = @ptDocNo
                AND DTWrn.FTSplCode = @ptSplCode AND ISNULL(DTRet.FTRetRefDoc,'') = ''
                AND ISNULL(DTRcv.FTRcvRefTwi,'') <> ''
                AND @ptDocType = (CASE WHEN ISNULL(DTWrn.FCWrnPercent,0)=0 AND ISNULL(DTWrn.FCWrnDNCNAmt,0)>0 
                                              THEN '10'
                                          WHEN ISNULL(DTWrn.FCWrnPercent,0)>0 AND ISNULL(DTWrn.FCWrnPercent,0)<100 AND ISNULL(DTWrn.FCWrnDNCNAmt,0)>0 
                                              THEN '11'
                                          ELSE ''
                                     END)
            GROUP BY SPLL.FTSplName
        )SumAmt ON
            HD.FTBchCode = SumAmt.FTBchCode AND HD.FTPchDocNo = SumAmt.FTPchDocNo
        INNER JOIN(
            SELECT TOP 1 @ptBchCode AS FTBchCode, @ptDocNo AS FTPchDocNo
           , PDT.FTPdtCode, BAR.FTBarCode, PKS.FCPdtUnitFact, PUNL.FTPunCode, PUNL.FTPunName
           , PDT.FTPdtSaleType, PDT.FTPdtStaAlwDis
           , PDT.FTPdtStaVat, PDT.FTVatCode, VAT.FCVatRate
            FROM TCNMPdt PDT WITH(NOLOCK)
            INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                PDT.FTPdtCode = PKS.FTPdtCode
            INNER JOIN TCNMPdtBar BAR WITH(NOLOCK) ON
                PDT.FTPdtCode = BAR.FTPdtCode AND PKS.FTPunCode = BAR.FTPunCode
            LEFT JOIN TCNMPdtUnit_L PUNL WITH(NOLOCK) ON
                PKS.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID = 1
            INNER JOIN(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT ON
                PDT.FTVatCode = VAT.FTVatCode AND VAT.FNRank = 1
            WHERE PDT.FTPdtCode = (CASE WHEN @ptDocType='10' THEN 'DEBITNOTE' ELSE 'CREDITNOTE' END )
        )PDT ON
            HD.FTBchCode = PDT.FTBchCode AND HD.FTPchDocNo = PDT.FTPchDocNo
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        INSERT TPSTTaxHD
        (
            FTBchCode, FTXshDocNo, FTShpCode, FNXshDocType, FDXshDocDate
            , FTXshCshOrCrd, FTXshVATInOrEx, FTDptCode, FTWahCode, FTPosCode
            , FTShfCode, FNSdtSeqNo, FTUsrCode, FTSpnCode, FTXshApvCode
            , FTCstCode, FTXshDocVatFull, FTXshRefExt, FDXshRefExtDate, FTXshRefInt
            , FDXshRefIntDate, FTXshRefAE, FNXshDocPrint, FTRteCode, FCXshRteFac
            , FCXshTotal, FCXshTotalNV, FCXshTotalNoDis, FCXshTotalB4DisChgV, FCXshTotalB4DisChgNV
            , FTXshDisChgTxt, FCXshDis, FCXshChg, FCXshTotalAfDisChgV, FCXshTotalAfDisChgNV
            , FCXshRefAEAmt, FCXshAmtV, FCXshAmtNV, FCXshVat, FCXshVatable
            , FTXshWpCode, FCXshWpTax, FCXshGrand, FCXshRnd, FTXshGndText
            , FCXshPaid, FCXshLeft, FTXshRmk, FTXshStaRefund, FTXshStaDoc
            , FTXshStaApv, FTXshStaPrcStk, FTXshStaPaid, FNXshStaDocAct, FNXshStaRef
            , FTXshRefTax, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
            , FTXshStaETax, FTRsnCode, FCXshLeftCN, FCXshLeftDN, FTXshETaxStatus
        )
        SELECT DISTINCT @ptBchCode AS FTBchCode, @tGenDocNo AS FTXshDocNo, '' AS FTShpCode, CONVERT(INT,@ptDocType) AS FNXshDocType, GETDATE()
        , '1' AS FTXshCshOrCrd, @tVatInOrExt, '' AS FTDptCode, BCH.FTWahCode, '' AS FTPosCode
        , '' AS FTShfCode, NULL AS FNSdtSeqNo, HD.FTUsrcode AS FTUsrCode, '' AS FTSpnCode, '' AS FTXshApvCode
        , HD.FTCstCode, '' AS FTXshDocVatFull, '' AS FTXshRefExt, NULL AS FDXshRefExtDate, '' AS FTXshRefInt
        , NULL AS FDXshRefIntDate, '' AS FTXshRefAE, 0 AS FNXshDocPrint, @tRteCode, @cRteFac
        , DT.FCXthTotal, 0 AS FCXshTotalNV, 0 AS FCXshTotalNoDis, 0 AS FCXshTotalB4DisChgV, 0 AS FCXshTotalB4DisChgNV
        , '' AS FTXshDisChgTxt, DT.FCXsdDis , DT.FCXsdChg, 0 AS FCXshTotalAfDisChgV, 0 AS FCXshTotalAfDisChgNV
        , NULL AS FCXshRefAEAmt, DT.FCXthTotal, 0 AS FCXshAmtNV, DT.FCXthVat, DT.FCXthVatable
        , NULL AS FTXshWpCode, NULL AS FCXshWpTax, DT.FCXthTotal, 0 AS FCXshRnd, dbo.F_GETtPriceToString(DT.FCXthTotal) AS FTXshGndText
        , 0 AS FCXshPaid, 0 AS FCXshLeft, '' AS FTXshRmk, '1' AS FTXshStaRefund, '1' AS FTXshStaDoc
        , '1' AS FTXshStaApv, '1' AS FTXshStaPrcStk, '' AS FTXshStaPaid, 1 AS FNXshStaDocAct, 0 AS FNXshStaRef
        , '' AS FTXshRefTax, GETDATE(), @ptWho, GETDATE(), @ptWho
        , NULL AS FTXshStaETax, '' AS FTRsnCode, NULL AS FCXshLeftCN, NULL AS FCXshLeftDN, NULL AS FTXshETaxStatus
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNMBranch BCH WITH(NOLOCK) ON
            HD.FTBchCode = BCH.FTBchCode
        INNER JOIN (
            SELECT @ptBchCode AS FTBchCode, @ptDocNo AS FTPchDocNo
            , SUM(DT.FCXsdDis) AS FCXsdDis
            , SUM(DT.FCXsdChg) AS FCXsdChg
            , SUM(DT.FCXsdNet) AS FCXthTotal
            , SUM(DT.FCXsdVat) AS FCXthVat
            , SUM(DT.FCXsdVatable) AS FCXthVatable
            FROM TPSTTaxDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @tGenDocNo
        )DT ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        ---------- End Gen เอกสาร ----------

        
        IF( (SELECT COUNT(*) FROM TPSTTaxHD WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @tGenDocNo) = 0 OR
            (SELECT COUNT(*) FROM TPSTTaxDT WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @tGenDocNo) = 0 )
            THROW 50000, 'Gen Doc Empty', 0;

    END --End อนุมัติแล้ว


	COMMIT TRANSACTION @tTrans

    SELECT @tGenDocNo AS FTGenDocNo, '' AS FTErrMsg
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
    SELECT '' AS FTGenDocNo, ERROR_MESSAGE() AS FTErrMsg
END CATCH
GO


IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPdtReqBch')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPdtReqBch
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPdtReqBch
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) 
    , @FNResult INT OUTPUT AS

DECLARE @tTrans varchar(20)
DECLARE @nDocType INT -- 1:ใบขอโอน 2: ใบขอซื้อ
DECLARE @tStaDoc varchar(1) -- สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก

DECLARE @tAgnDoc varchar(10) --Agn เอกสารใบขอโอน
DECLARE @tBchDoc varchar(50) --สาขา เอกสารใบขอโอน
DECLARE @tPrbDocNo varchar(30) --เลขที่ เอกสารใบขอโอน

DECLARE @tVatInOrExt varchar(1)
DECLARE @tVatCode varchar(5)
DECLARE @cVatRate numeric(18, 4)
DECLARE @tRteCode varchar(5)
DECLARE @cRteFac numeric(18, 4)

DECLARE	@nStoreRet int
DECLARE @tResult varchar(30)
DECLARE @dDateNow DATETIME

DECLARE @TTmpPrbDocNo TABLE
(
    FTXxhDocNo VARCHAR(30)
)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	22/09/2021		Net		create 
07.01.00	16/12/2021		Net		กลับปลายทางต้นทาง 
----------------------------------------------------------------------*/
SET @tTrans = 'ReqBch'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @dDateNow = GETDATE()

    -- Get สถานะเอกสาร
    SELECT @nDocType = ISNULL(HD.FNXrhDocType, 0), @tStaDoc = ISNULL(HD.FTXrhStaDoc, '')
    --, @tAgnDoc = HD.FTXrhAgnFrm, @tBchDoc = HD.FTXrhRefFrm
    FROM TCNTPdtReqMgtHD HD WITH(NOLOCK)
    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXphDocNo = @ptDocNo
    
    -- Gen เอกสารเป็นของ สนญ
    SELECT @tAgnDoc = FTAgnCode, @tBchDoc = FTBchCode
    FROM TCNMBranch
    WHERE FTBchCode = @ptBchCode

    SET @tPrbDocNo = ''
    SET @tAgnDoc = ISNULL(@tAgnDoc,'')
    SET @tBchDoc = ISNULL(@tBchDoc,'')

    IF @nDocType = 1 AND @tStaDoc = '' AND @tBchDoc <> '' --ถ้าเป็นเอกสาร ใบขอโอน และยังไม่ประมวลผล
    BEGIN

        --Gen เลขที่เอกสาร ใบขอโอน
        INSERT @TTmpPrbDocNo 
        EXEC @nStoreRet = [dbo].[SP_CNtAUTAutoDocNo]
            @ptTblName = N'TCNTPdtReqBchHD'
		    , @ptDocType = N'13'
		    , @ptBchCode = @tBchDoc
		    , @ptShpCode = NULL
		    , @ptPosCode = NULL
		    , @pdDocDate = @dDateNow
		    , @ptResult = @tResult OUTPUT

        SET @tPrbDocNo = (SELECT TOP 1 FTXxhDocNo FROM @TTmpPrbDocNo)


        --ถ้า Gen เลขที่เอกสารไม่ได้
        IF ISNULL(@tPrbDocNo, '') = '' 
            THROW 50000, 'SP_CNtAUTAutoDocNo Error', 0;

        -- Get VatComp
        SELECT TOP 1 @tVatCode = VAT.FTVatCode, @cVatRate = VAT.FCVatRate
        , @tVatInOrExt = CMP.FTCmpRetInOrEx, @tRteCode = CMP.FTRteCode
        , @cRteFac = RTE.FCRteFraction
        FROM TCNMComp CMP WITH(NOLOCK)
        INNER JOIN(
            SELECT FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
        )VAT ON CMP.FTVatCode = VAT.FTVatCode
        INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
            CMP.FTRteCode = RTE.FTRteCode
        WHERE FNRank = 1

        -- Get VatAgn ถ้ามี
        IF ISNULL(@tAgnDoc, '') <> ''
        BEGIN
            SELECT TOP 1 @tVatCode = ISNULL(VAT.FTVatCode, @tVatCode)
            , @cVatRate = ISNULL(VAT.FCVatRate, @cVatRate)
            , @tVatInOrExt = ISNULL(AGN.FTCmpVatInOrEx, @tVatInOrExt)
            , @tRteCode = AGN.FTRteCode
            , @cRteFac = RTE.FCRteFraction
            FROM TCNMAgencySpc AGN WITH(NOLOCK)
            INNER JOIN(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT ON AGN.FTVatCode = VAT.FTVatCode
            INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
                AGN.FTRteCode = RTE.FTRteCode
            WHERE FNRank = 1 AND AGN.FTAgnCode = ISNULL(@tAgnDoc, '')
        END

        INSERT TCNTPdtReqBchHDDocRef
        (
            FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefType, FTXshRefDocNo, FTXshRefKey, FDXshRefDocDate
        )
        SELECT @tAgnDoc, @tBchDoc, @tPrbDocNo, '1', MHD.FTXrhDocPrBch, 'PRHQ', GETDATE()
        FROM TCNTPdtReqMgtHD MHD WITH(NOLOCK)
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXphDocNo = @ptDocNo
        
        INSERT TCNTPdtReqHqHDDocRef
        (
            FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefType, FTXshRefDocNo, FTXshRefKey, FDXshRefDocDate
        )
        SELECT ISNULL(MHD.FTXrhAgnFrm,''), MHD.FTXrhRefFrm, MHD.FTXrhDocPrBch, '2', @tPrbDocNo, 'PRB', GETDATE()
        FROM TCNTPdtReqMgtHD MHD WITH(NOLOCK)
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXphDocNo = @ptDocNo

        INSERT TCNTPdtReqBchDT
        (
            FTAgnCode, FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName
            , FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTXtdVatType
            , FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice, FCXtdQty
            , FCXtdQtyAll, FCXtdSetPrice, FCXtdAmt, FCXtdDisChgAvi, FTXtdDisChgTxt
            , FCXtdDis, FCXtdChg, FCXtdNet, FCXtdNetAfHD
            , FCXtdNetEx, FCXtdVat, FCXtdVatable, FCXtdWhtAmt, FTXtdWhtCode, FCXtdWhtRate
            , FCXtdCostIn, FCXtdCostEx, FTXtdStaPdt, FCXtdQtyLef, FCXtdQtyRfn
            , FTXtdStaPrcStk, FTXtdStaAlwDis, FNXtdPdtLevel, FTXtdPdtParent
            , FCXtdQtySet, FTXtdPdtStaSet, FTXtdRmk
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT @tAgnDoc, @tBchDoc, @tPrbDocNo, ROW_NUMBER() OVER(ORDER BY RDT.FNXpdSeqNo), RDT.FTPdtCode, RDT.FTXpdPdtName
        , RDT.FTPunCode, RDT.FTPunName, RDT.FCXpdFactor, RDT.FTXpdBarCode, PDT.FTPdtStaVat
        , ISNULL(PDT.FTVatCode, @tVatCode), ISNULL(VAT.FCVatRate, @cVatRate), PDT.FTPdtSaleType, ISNULL(PRI.FCPgdPriceRet, 0), MDT.FCXpdQtyTR
        , MDT.FCXpdQtyTR*MDT.FCXpdFactor, ISNULL(PRI.FCPgdPriceRet, 0), ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR, 0, ''
        , 0, 0, ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR, ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR
        , CASE WHEN PDT.FTPdtStaVat='2' THEN ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR --ไม่มีภาษี
               ELSE CASE WHEN @tVatInOrExt = '1' --รวมใน
                            THEN (ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR*100)/(100+@cVatRate)
                         ELSE ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR --แยกนอก
                    END
          END AS FCXtdNetEx
        , CASE WHEN PDT.FTPdtStaVat='2' THEN 0 --ไม่มีภาษี
               ELSE CASE WHEN @tVatInOrExt = '1' --รวมใน
                            THEN (ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR*7)/(100+@cVatRate)
                         ELSE (ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR*7)/(100) --แยกนอก
                    END
          END FCXtdVat
        , CASE WHEN PDT.FTPdtStaVat='2' THEN ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR --ไม่มีภาษี
               ELSE CASE WHEN @tVatInOrExt = '1' --รวมใน
                            THEN (ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR*100)/(100+@cVatRate)
                         ELSE ISNULL(PRI.FCPgdPriceRet, 0)*MDT.FCXpdQtyTR --แยกนอก
                    END
          END AS FCXtdVatable
        , 0, '', 0, 0, 0, '1', MDT.FCXpdQtyTR, 0, '', PDT.FTPdtStaAlwDis, 0, '', 0, '1', ''
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtReqMgtHD MHD WITH(NOLOCK)
        INNER JOIN TCNTPdtReqMgtDT MDT WITH(NOLOCK) ON
            MHD.FTAgnCode = MDT.FTAgnCode AND MHD.FTBchCode = MDT.FTBchCode
            AND MHD.FTXphDocNo = MDT.FTXphDocNo
        INNER JOIN TCNTPdtReqHqDT RDT WITH(NOLOCK) ON
            -- Gen เอกสารเป็นของ สนญ
            --MHD.FTXrhAgnTo = RDT.FTAgnCode AND MHD.FTXrhBchTo = RDT.FTBchCode 
            --AND MHD.FTXrhDocPrBch = RDT.FTXphDocNo AND MDT.FNXprSeqNo = RDT.FNXpdSeqNo
            ISNULL(MHD.FTXrhAgnFrm,'') = RDT.FTAgnCode AND MHD.FTXrhRefFrm = RDT.FTBchCode
            AND MHD.FTXrhDocPrBch = RDT.FTXphDocNo AND MDT.FNXprSeqNo = RDT.FNXpdSeqNo
        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
            RDT.FTPdtCode = PDT.FTPdtCode
        LEFT JOIN (
            SELECT FTVatCode, FCVatRate
            FROM(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT
            WHERE FNRank = 1
        )VAT ON PDT.FTVatCode = VAT.FTVatCode
        LEFT JOIN (         
            SELECT FTPdtCode, FTPunCode, FTPghDocType, FCPgdPriceRet
            FROM(
                SELECT FTPdtCode, FTPunCode, FTPghDocType, FCPgdPriceRet
                , ROW_NUMBER() OVER(PARTITION BY FTPdtCode, FTPunCode ORDER BY (CONVERT(VARCHAR(10), FDPghDStart, 121)+' '+FTPghTStart) DESC) AS FNRank
                FROM TCNTPdtPrice4PDT
                WHERE ISNULL(FTPghDocType, '') = '1' AND ISNULL(FTPghStaAdj, '') = '1'
                    AND ISNULL(FTPplCode, '') = ''
                    AND ( GETDATE() BETWEEN (CONVERT(VARCHAR(10), FDPghDStart, 121)+' '+FTPghTStart) AND (CONVERT(VARCHAR(10), FDPghDStop, 121)+' '+FTPghTStop) )
            )PRI
            WHERE FNRank = 1
        )PRI ON
            PRI.FTPdtCode = RDT.FTPdtCode AND PRI.FTPunCode = RDT.FTPunCode
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXphDocNo = @ptDocNo

        
        INSERT TCNTPdtReqBchHD
        (
            FTAgnCode, FTBchCode, FTXthDocNo, FTShpCode, FTXthTnfType, FNXthDocType
            , FDXthDocDate, FTXthVATInOrEx, FTXthCshOrCrd, FTXthOther, FTDptCode
            , FTXthAgnFrm, FTXthBchFrm, FTXthAgnTo, FTXthBchTo, FTXthShopFrm
            , FTXthShopTo, FTXthWhFrm, FTXthWhTo, FTUsrCode, FTSpnCode
            , FTXthApvCode, FTSplCode, FNXthDocPrint, FTRteCode, FCXthRteFac
            , FCXthTotal, FCXtVatNoDisChg, FCXthNoVatNoDisChg, FCXthVatDisChgAvi, FCXthNoVatDisChgAvi
            , FTXthDisChgTxt, FCXthDis, FCXthChg, FCXthRefAEAmt, FCXthVatAfDisChg
            , FCXthNoVatAfDisChg, FCXthAfDisChgAE, FTXthWpCode, FCXthVat, FCXthVatable
            , FCXthGrandB4Wht, FCXthWpTax, FCXthGrand, FCXthRnd, FTXthGndText
            , FCXthPaid, FCXthLeft, FTXthStaRefund, FTXthRmk, FTXthStaDoc
            , FTXthStaApv, FTXthStaPrcDoc, FTXthStaPaid, FNXthStaDocAct, FNXthStaRef, FTRsnCode
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT @tAgnDoc, @tBchDoc, @tPrbDocNo, '', '2', 4
        , GETDATE(), @tVatInOrExt, '1', '', ''
        --, MHD.FTXrhAgnTo, MHD.FTXrhBchTo, MHD.FTXrhAgnFrm, MHD.FTXrhRefFrm, ''
        , MHD.FTXrhAgnFrm, MHD.FTXrhRefFrm, MHD.FTXrhAgnTo, MHD.FTXrhBchTo, ''
        , '', BCHFrm.FTWahCode, BCHTo.FTWahCode, '', ''
        , '', '', 0, @tRteCode, @cRteFac, RDT.FCXtdNet, RDT.FCXtVatNoDisChg, RDT.FCXthNoVatNoDisChg, 0, 0
        , '', 0, 0, 0, RDT.FCXthVatAfDisChg, RDT.FCXthNoVatAfDisChg, 0, '', RDT.FCXthVat, RDT.FCXthVatable
        , RDT.FCXtdNetAfHD, 0, RDT.FCXtdNetAfHD, 0, '', 0, 0, '', '', '1'
        , '', '', '1', 1, 0, ''
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtReqMgtHD MHD WITH(NOLOCK)
        INNER JOIN TCNTPdtReqHqHD RHD WITH(NOLOCK) ON
            -- Gen เอกสารเป็นของ สนญ
            --MHD.FTXrhAgnTo = RHD.FTAgnCode AND MHD.FTXrhBchTo = RHD.FTBchCode 
            --AND MHD.FTXrhDocPrBch = RHD.FTXphDocNo
            ISNULL(MHD.FTXrhAgnFrm,'') = RHD.FTAgnCode AND ISNULL(MHD.FTXrhRefFrm,'') = RHD.FTBchCode 
            AND MHD.FTXrhDocPrBch = RHD.FTXphDocNo
        INNER JOIN (
            SELECT FTAgnCode, FTBchCode, FTXthDocNo, @ptDocNo AS FTXrhDocPrBch
            , SUM(FCXtdNet) AS FCXtdNet
            , SUM(FCXtdNetAfHD) AS FCXtdNetAfHD
            , SUM(CASE WHEN FTXtdStaAlwDis='2' AND FTXtdVatType='1' THEN FCXtdVat ELSE 0 END) AS FCXtVatNoDisChg
            , SUM(CASE WHEN FTXtdStaAlwDis='2' AND FTXtdVatType='2' THEN FCXtdVat ELSE 0 END) AS FCXthNoVatNoDisChg
            , SUM(CASE WHEN FTXtdStaAlwDis='1' AND FTXtdVatType='1' THEN FCXtdVat ELSE 0 END) AS FCXthVatAfDisChg
            , SUM(CASE WHEN FTXtdStaAlwDis='1' AND FTXtdVatType='2' THEN FCXtdVat ELSE 0 END) AS FCXthNoVatAfDisChg
            , SUM(FCXtdVat) AS FCXthVat
            , SUM(FCXtdVatable) AS FCXthVatable
            FROM TCNTPdtReqBchDT WITH(NOLOCK)
            WHERE FTBchCode = @tBchDoc AND FTXthDocNo = @tPrbDocNo
            GROUP BY FTAgnCode, FTBchCode, FTXthDocNo
        )RDT ON
            -- Gen เอกสารเป็นของ สนญ
            --MHD.FTXrhAgnFrm = RDT.FTAgnCode AND MHD.FTXrhRefFrm = RDT.FTBchCode 
            --AND MHD.FTXphDocNo = RDT.FTXrhDocPrBch
            MHD.FTAgnCode = RDT.FTAgnCode AND MHD.FTBchCode = RDT.FTBchCode 
            AND MHD.FTXphDocNo = RDT.FTXrhDocPrBch
        INNER JOIN TCNMBranch BCHFrm WITH(NOLOCK) ON
            MHD.FTXrhRefFrm = BCHFrm.FTBchCode
        INNER JOIN TCNMBranch BCHTo WITH(NOLOCK) ON
            MHD.FTXrhBchTo = BCHTo.FTBchCode
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXphDocNo = @ptDocNo

        IF (SELECT COUNT(*) FROM TCNTPdtReqBchHD WHERE FTXthDocNo = @tPrbDocNo) <= 0 OR (SELECT COUNT(*) FROM TCNTPdtReqBchDT WHERE FTXthDocNo = @tPrbDocNo) <= 0
            THROW 50000, 'Gen Doc Empty', 0;

    END --End ถ้าเป็นเอกสาร ใบขอโอน และยังไม่ประมวลผล

    SELECT @tPrbDocNo AS FTPrbDocNo, '' AS FTErrMsg

	COMMIT TRANSACTION @tTrans
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
	SELECT '' AS FTPrbDocNo, ERROR_MESSAGE() AS FTErrMsg
END CATCH
GO

IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxSvBookPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxSvBookPrc
GO

CREATE PROCEDURE [dbo].STP_DOCxSvBookPrc
     @ptBchCode varchar(5)
    ,@ptDocNo varchar(30)
    ,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @TTmpPrcStk TABLE 
( 
   FTBchCode varchar(5),
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTStkSysType varchar(1), 
   FTPdtCode varchar(20), 
   FTPdtParent varchar(20), 
   FCStkQty decimal(18,2), 
   FTWahCode varchar(5), 
   FDStkDate Datetime,
   FCStkSetPrice decimal(18,2),
   FCStkCostIn decimal(18,2),
   FCStkCostEx decimal(18,2)
) 
DECLARE @tStaPrc varchar(1)
DECLARE @tStaPrcStkFrm varchar(1)
DECLARE @tStaPrcStkTo varchar(1)
DECLARE @tStaDoc varchar(1) --1 สมบูรณ์ 3ยกเลิก
DECLARE @tTrans varchar(20)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	15/09/2021		Net		create 
07.01.00	16/12/2021		Net		ปรับ Process ตัด Stock 
----------------------------------------------------------------------*/
SET @tTrans = 'PrcBook'
BEGIN TRY
	BEGIN TRANSACTION @tTrans
	SET @tStaDoc = (SELECT TOP 1 FTXshStaDoc
                      FROM TSVTBookHD WITH(NOLOCK) 
                      WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo)

    IF @tStaDoc = '1' --เอกสารปกติ
    BEGIN
	    SET @tStaPrc = (CASE WHEN (SELECT COUNT(*)
                                   FROM(
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDT WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')=''
                                       UNION ALL
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDTSet WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')=''
                                    )Doc
                                  ) > 0
                             THEN '1' ELSE '2' END)
        
        -- ยังประมวลผล Stock ไม่ครบ
	    IF @tStaPrc = '1'	
	    BEGIN
            -- สถานะการตัด Stk ของคลัง
		    SET @tStaPrcStkFrm = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') 
                                  FROM TCNMWaHouse WAH WITH(NOLOCK)
						          INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON 
                                      DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCodeFrm = WAH.FTWahCode
						          WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo)

		    SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') 
                                  FROM TCNMWaHouse WAH WITH(NOLOCK)
						          INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON 
                                      DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCodeTo = WAH.FTWahCode
						          WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo)
            --End สถานะการตัด Stk ของคลัง


        
		    -- คลังต้นทาง ตัด Stk
		    IF @tStaPrcStkFrm = '2'
		    BEGIN
            
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeFrm, DT.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode,'') = ''
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeFrm, DTSet.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DTSet.FTPdtCode
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeFrm = STK.FTWahCode AND DTSet.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(STK.FTPdtCode,'') = ''
                    AND ISNULL(PDT.FTPdtStkControl,'') = '1'
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			    -- Update ตัด Stk ออกจากคลังต้นทาง
			    UPDATE STK WITH(ROWLOCK)
			    SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT DT.FTBchCode, DT.FTWahCodeFrm, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			        FROM TSVTBookDT DT WITH(NOLOCK)
			        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			        WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                        AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			        GROUP BY DT.FTBchCode, DT.FTWahCodeFrm, DT.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeFrm = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    --End Update ตัด Stk ออกจากคลังต้นทาง
            
			    -- Update ตัด Stk ออกจากคลังต้นทาง ตัวลูก
			    UPDATE STK WITH(ROWLOCK)
			    SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT DT.FTBchCode, DT.FTWahCodeFrm, DTSet.FTPdtCode, SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			        FROM TSVTBookDT DT WITH(NOLOCK)
			        INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                        DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                        AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			        INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                        PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			        WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                        AND ISNULL(DTSet.FTXsdStaPrcStk,'') = '' AND ISNULL(DTSet.FTPsvType,'') = '1'
			        GROUP BY DT.FTBchCode, DT.FTWahCodeFrm, DTSet.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeFrm = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    --End Update ตัด Stk ออกจากคลังต้นทาง ตัวลูก


            
                -- เก็บตัวที่ตัด Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '2' AS FTStkType, '4' AS FTStkSysType
			        , DT.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCodeFrm AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('', '1')
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCodeFrm, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้
            
                -- เก็บตัวที่ตัด Stk ไว้ ตัวลูก
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '2' AS FTStkType, '4' AS FTStkSysType
			        , DTSet.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DT.FTWahCodeFrm AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , 0 AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DTSet.FTXsdStaPrcStk,'') IN ('', '1')
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DTSet.FTPdtCode, DT.FTWahCodeFrm, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้

            END
		    --End คลังต้นทาง ตัด Stk
        
		    -- คลังต้นปลายทาง ตัด Stk
            IF @tStaPrcStkTo = '2'
		    BEGIN
            
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode,'') = ''
			    --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeTo, DTSet.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' 
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeTo = STK.FTWahCode AND DTSet.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode,'') = ''
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
                -- Update เพิ่ม Stk เข้าคลังปลายทาง
			    UPDATE STK WITH(ROWLOCK)
			    SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT DT.FTBchCode, DT.FTWahCodeTo, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			        FROM TSVTBookDT DT WITH(NOLOCK)
			        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			        WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                        AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			        GROUP BY DT.FTBchCode, DT.FTWahCodeTo, DT.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeTo = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    --End Update เพิ่ม Stk เข้าคลังปลายทาง
                
                -- Update เพิ่ม Stk เข้าคลังปลายทาง ตัวลูก
			    UPDATE STK WITH(ROWLOCK)
			    SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT DT.FTBchCode, DT.FTWahCodeTo, DTSet.FTPdtCode, SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			        FROM TSVTBookDT DT WITH(NOLOCK)
			        INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                        DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                        AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' 
			        INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                        PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			        WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                        AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
                        AND ISNULL(DTSet.FTPsvType,'') = '1'
			        GROUP BY DT.FTBchCode, DT.FTWahCodeTo, DTSet.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeTo = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    --End Update เพิ่ม Stk เข้าคลังปลายทาง ตัวลูก
                
                -- เก็บตัวที่เพิ่ม Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '1' AS FTStkType, '4' AS FTStkSysType
			        , DT.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('', '1')
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCodeTo, HD.FDXshDocDate
                --End เก็บตัวที่เพิ่ม Stk ไว้
                
                -- เก็บตัวที่เพิ่ม Stk ไว้ ตัวลูก
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '1' AS FTStkType, '4' AS FTStkSysType
			        , DTSet.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DT.FTWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , 0 AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('', '1')
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DTSet.FTPdtCode, DT.FTWahCodeTo, HD.FDXshDocDate
                --End เก็บตัวที่เพิ่ม Stk ไว้ ตัวลูก

            END
		    --End คลังต้นปลายทาง ตัด Stk
        
		    --Insert ลง Stock Card
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo

            
		    INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , FDCreateOn, FTCreateBy
            )
		    SELECT FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk
		    --End Insert ลง Stock Card

	    END
        --End ยังประมวลผล Stock ไม่ครบ
    END
    ELSE BEGIN --เอกสารยกเลิก
        
	    SET @tStaPrc = (CASE WHEN (SELECT COUNT(*)
                                   FROM(
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDT WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')='1'
                                       UNION ALL
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDTSet WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')='1'
                                    )Doc
                                  ) > 0
                             THEN '1' ELSE '2' END)

        
        -- ยังประมวลผล Stock ไม่ครบ
	    IF @tStaPrc = '1'	
	    BEGIN
            
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
	COMMIT TRANSACTION @tTrans
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
	SELECT ERROR_MESSAGE()
END CATCH
GO

/*รายงานกองยาน*/
    DROP PROCEDURE [dbo].[SP_RPTxSalTwoFleet]
    GO
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
    CREATE PROCEDURE [dbo].[SP_RPTxSalTwoFleet]
        @ptUsrSessionID VARCHAR(100),
        @ptAgnCode VARCHAR(20),
        @ptBchCode VARCHAR(255),
        @pdDocDateFrm VARCHAR(10),
        @pdDocDateTo VARCHAR(10),
        @pnLangID INT,
        @pnResult INT OUTPUT
    AS
    BEGIN TRY 
            DECLARE @nResult INT
            SET @nResult = @pnResult
            DECLARE @tSQL VARCHAR(MAX)
            SET @tSQL = ''

            DECLARE @tSQLFilter VARCHAR(255)
            SET @tSQLFilter = ''

            IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
            BEGIN
                SET @tSQLFilter += ' AND ISNULL(SHD.FTAgnCode,'''') = ''' + @ptAgnCode + ''' '
            END

            IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
            BEGIN
                SET @tSQLFilter += ' AND SHD.FTBchCode IN( ' +  @ptBchCode + ' ) '
            END
            
            IF ((@pdDocDateFrm <> '' OR @pdDocDateFrm <> NULL) AND  (@pdDocDateTo <> '' OR @pdDocDateTo <> NULL))
            BEGIN
                SET @tSQLFilter += ' AND CONVERT(VARCHAR(10),SHD.FDXshDocDate,121) BETWEEN ''' + @pdDocDateFrm + ''' AND ''' + @pdDocDateTo + ''' '
            END

        DELETE FROM TRPTSalTwoFleetTmp WHERE FTUsrSession = ''+@ptUsrSessionID+''
        SET @tSQL += ' INSERT INTO  TRPTSalTwoFleetTmp ' 
        SET @tSQL += ' SELECT SHD.FTBchCode, '
        SET @tSQL += ' BCH.FTBchName, '
        SET @tSQL += ' SHD.FDXshDocDate, '
        SET @tSQL += ' SHD.FTXshDocNo, '
        SET @tSQL += ' SDT.FTPdtCode, '
        SET @tSQL += ' SDT.FTXsdPdtName, '
        SET @tSQL += ' ISNULL(SDT.FCXsdSalePrice, 0) AS FCXsdSalePrice, '
        SET @tSQL += ' ISNULL(SDT.FCXsdQtyAll, 0) AS FCXsdQtyAll, '
        SET @tSQL += ' ISNULL(SDT.FCXsdAmtB4DisChg, 0) AS FCXsdAmtB4DisChg, '
        SET @tSQL += ' ISNULL(SDT.FCXsdDis, 0) AS FCXsdDis, '
        SET @tSQL += ' ISNULL(SDT.FCXsdNetAfHD, 0) AS FCXsdNetAfHD, '
        SET @tSQL += ' '''+@ptUsrSessionID+''' AS FTUsrSession , '
        SET @tSQL += ' HDCst.FTXshCstName , '
        SET @tSQL += ' CAR.FTCarRegNo , '
        SET @tSQL += ' INBA.FTCbaStaTax , '
        SET @tSQL += ' SDT.FCXsdVat '
        SET @tSQL += ' FROM TSVTSalTwoDT SDT WITH(NOLOCK) '
        SET @tSQL += ' INNER JOIN TSVTSalTwoHD SHD WITH(NOLOCK) ON SDT.FTAgnCode = SHD.FTAgnCode '
                                                SET @tSQL +=  ' AND SDT.FTBchCode = SHD.FTBchCode '
                                                SET @tSQL +=  ' AND SDT.FTXshDocNo = SHD.FTXshDocNo '
        SET @tSQL += ' INNER JOIN TSVTSalTwoHDCst HDCst WITH(NOLOCK) ON SDT.FTAgnCode = HDCst.FTAgnCode '
                                                SET @tSQL +=  ' AND SDT.FTBchCode = HDCst.FTBchCode '
                                                SET @tSQL +=  ' AND SDT.FTXshDocNo = HDCst.FTXshDocNo '
        SET @tSQL += ' INNER JOIN TSVMCar CAR WITH(NOLOCK) ON HDCst.FTCarCode = CAR.FTCarCode '
        SET @tSQL += ' LEFT JOIN TLKMCarInterBA INBA WITH(NOLOCK) ON CAR.FTCarRegNo = INBA.FTCarRegNo '
        SET @tSQL += ' LEFT JOIN TCNMBranch_L BCH WITH(NOLOCK) ON SHD.FTBchCode = BCH.FTBchCode '
                                                SET @tSQL +=  ' AND BCH.FNLngID = 1 '
        SET @tSQL +=  ' WHERE SHD.FNXshDocType = 1 '
        SET @tSQL +=  ' AND SHD.FTXshStaApv = 1 '
        SET @tSQL +=  ' AND SHD.FTXshStaDoc = 1 '
        SET @tSQL += @tSQLFilter
        EXEC(@tSQL)
        RETURN 0
    END TRY	
    BEGIN CATCH
        SET @pnResult = -1
        RETURN @pnResult
    END CATCH
    GO

/* รายงานสินค้าคงคลัง */
    DROP PROCEDURE [dbo].[SP_RPTxStockBal1002001]
    GO
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
    CREATE PROCEDURE [dbo].[SP_RPTxStockBal1002001] 
        @pnLngID int , 
        @ptComName Varchar(100),
        @ptRptCode Varchar(100),
        @ptUsrSession Varchar(255),

        @pnFilterType int, --1 BETWEEN 2 IN
        --สาขา
        @ptBchL Varchar(8000), --กรณี Condition IN
        @ptBchF Varchar(5),
        @ptBchT Varchar(5),

        --@ptBchF VARCHAR(100),
        --@ptBchT VARCHAR(100),
        @ptWahCodeF VARCHAR(100),
        @ptWahCodeT VARCHAR(100),

        @FTResult VARCHAR(8000) OUTPUT

    AS
    BEGIN TRY

        DECLARE @tSQL VARCHAR(8000)
        DECLARE @tSQL_Filter VARCHAR(8000)

        DECLARE @nLngID int
        DECLARE @tComName VARCHAR(100)
        DECLARE @tRptCode VARCHAR(100)
        DECLARE @tUsrSession VARCHAR(255)

        --Branch Code
        DECLARE @tBchF Varchar(5)
        DECLARE @tBchT Varchar(5)

        --DECLARE @tBchF VARCHAR(100)
        --DECLARE @tBchT VARCHAR(100)
        DECLARE @tWahCodeF VARCHAR(100)
        DECLARE @tWahCodeT VARCHAR(100)

        SET @tComName = @ptComName
        SET @tRptCode = @ptRptCode
        SET @tUsrSession = @ptUsrSession
        SET @nLngID = @pnLngID

        --Branch
        SET @tBchF  = @ptBchF
        SET @tBchT  = @ptBchT

        --SET @tBchF = @ptBchF
        --SET @tBchT = @ptBchT
        SET @tWahCodeF = @ptWahCodeF
        SET @tWahCodeT = @ptWahCodeT

        SET @tSQL_Filter = ' WHERE 1 = 1 AND PDT1.FTPdtStaActive = 1  '

        IF(@nLngID = '' OR @nLngID = NULL)
            BEGIN
                SET @nLngID = 1
            END
        ELSE IF(@nLngID <> '')
            BEGIN
                SET @nLngID = @pnLngID
            END

        IF @ptBchL = null
        BEGIN
            SET @ptBchL = ''
        END

        IF @tBchF = null
        BEGIN
            SET @tBchF = ''
        END
        IF @tBchT = null OR @tBchT = ''
        BEGIN
            SET @tBchT = @tBchF
        END

            IF @pnFilterType = '1'
        BEGIN
            IF (@tBchF <> '' AND @tBchT <> '')
            BEGIN
                SET @tSQL_Filter +=' AND STK.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
            END	
        END

        IF @pnFilterType = '2'
        BEGIN
            IF (@ptBchL <> '' )
            BEGIN
                SET @tSQL_Filter +=' AND STK.FTBchCode IN (' + @ptBchL + ')'
            END	
        END

        --คลังสินค้า
        IF(@tWahCodeF = '' OR @tWahCodeF = NULL)
                BEGIN
                    SET @tWahCodeF = ''
                END
        ELSE IF(@tWahCodeF <> '')
                BEGIN
                    SET @tWahCodeF = @tWahCodeF
                END

            --ถึงคลัง
        IF(@tWahCodeT = '' OR @tWahCodeT = NULL)
                BEGIN
                    SET @tWahCodeT = ''
                END
        ELSE IF(@tWahCodeT <> '')
                BEGIN
                    SET @tWahCodeT = @tWahCodeT
                END

        IF(@tWahCodeF <> '' AND @tWahCodeT <> '')
                BEGIN 
                    SET @tSQL_Filter += ' AND STK.FTWahCode BETWEEN '''+@tWahCodeF+''' AND '''+@tWahCodeT+''' ' 
                END
        ELSE IF(@tWahCodeF = '' AND @tWahCodeT = '')
                BEGIN 
                    SET @tSQL_Filter += ''
                END
        
        
        --DELETE FROM TRPTPdtStkBalTmp WITH (ROWLOCK) WHERE FTComName =  '' + @tComName + ''  AND FTRptCode = '' + @tRptCode + '' AND FTUsrSession = '' + @tUsrSession + ''	  
        DELETE FROM TRPTPdtStkBalTmp  WHERE FTComName =  '' + @tComName + ''  AND FTRptCode = '' + @tRptCode + '' AND FTUsrSession = '' + @tUsrSession + ''

        SET @tSQL  = ' INSERT INTO TRPTPdtStkBalTmp (FTComName,FTRptCode,FTUsrSession,FTWahCode,FTWahName,FTPdtCode,FTPdtName,FCStkQty,'	  
        SET @tSQL += ' FTPgpChainName,FCPdtCostAVGEX,FCPdtCostTotal,FTBchCode,FTBchName,FCPdtCostStd,FCPdtCostStdTotal)'
        SET @tSQL += ' SELECT '''+ @tComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'
        SET @tSQL +=  ' WAH.FTWAHCODE,WAH.FTWahName,PDT.FTPdtCode,PDT.FTPdtName,STK.FCStkQty,Grp_L.FTPgpChainName,ISNULL(AvgCost.FCPdtCostAVGEX,0) AS FCPdtCostAVGEX,ISNULL(AvgCost.FCPdtCostAVGEX,0)* ISNULL(STK.FCStkQty,0) AS FCPdtCostTotal ,
                        BCHL.FTBchCode,BCHL.FTBchName,ISNULL(AvgCost.FCPdtCostStd,0) AS FCPdtCostStd ,ISNULL(AvgCost.FCPdtCostStd,0)* ISNULL(STK.FCStkQty,0) AS FCPdtCostStdTotal 
                        FROM TCNTPDTSTKBAL STK WITH (NOLOCK)
                        LEFT JOIN VCN_ProductCost AvgCost WITH (NOLOCK) ON STK.FTPdtCode = AvgCost.FTPdtCode  
                        LEFT JOIN TCNMPdt PDT1 WITH (NOLOCK) ON  STK.FTPdtCode = PDT1.FTPdtCode
                        LEFT JOIN TCNMPDT_L PDT WITH (NOLOCK) ON  STK.FTPDTCODE = PDT.FTPDTCODE AND PDT.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + '''
                        LEFT JOIN TCNMWAHOUSE_L  WAH WITH (NOLOCK) ON STK.FTWAHCODE = WAH.FTWAHCODE AND STK.FTBchCode = WAH.FTBchCode AND WAH.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''' 
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON STK.FTBchCode = BCHL.FTBchCode AND BCHL.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + '''
                        LEFT JOIN TCNMPdtGrp_L Grp_L WITH (NOLOCK) ON Pdt1.FTPgpChain  =  Grp_L.FTPgpChain AND WAH.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''''
                            
        SET @tSQL += @tSQL_Filter		
        SET @tSQL +=' UNION'
        SET @tSQL += ' SELECT '''+ @tComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'
        SET @tSQL += ' WAH.FTWAHCODE,WAH.FTWahName,PDT.FTPdtCode,PDT.FTPdtName,STK.FCStkQty,Grp_L.FTPgpChainName,ISNULL(AvgCost.FCPdtCostAVGEX,0) AS FCPdtCostAVGEX,ISNULL(AvgCost.FCPdtCostAVGEX,0)* ISNULL(STK.FCStkQty,0) AS FCPdtCostTotal ,
                        BCHL.FTBchCode,BCHL.FTBchName,ISNULL(AvgCost.FCPdtCostStd,0) AS FCPdtCostStd ,ISNULL(AvgCost.FCPdtCostStd,0)* ISNULL(STK.FCStkQty,0) AS FCPdtCostStdTotal 
                            FROM TCNTPDTSTKBALBch STK WITH (NOLOCK)
                        LEFT JOIN VCN_ProductCost AvgCost WITH (NOLOCK) ON STK.FTPdtCode = AvgCost.FTPdtCode  
                        LEFT JOIN TCNMPdt PDT1 WITH (NOLOCK) ON  STK.FTPdtCode = PDT1.FTPdtCode
                        LEFT JOIN TCNMPDT_L PDT WITH (NOLOCK) ON  STK.FTPDTCODE = PDT.FTPDTCODE AND PDT.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + '''
                        LEFT JOIN TCNMWAHOUSE_L  WAH WITH (NOLOCK) ON STK.FTWAHCODE = WAH.FTWAHCODE AND STK.FTBchCode = WAH.FTBchCode AND WAH.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''' 
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON STK.FTBchCode = BCHL.FTBchCode AND BCHL.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + '''
                        LEFT JOIN TCNMPdtGrp_L Grp_L WITH (NOLOCK) ON Pdt1.FTPgpChain  =  Grp_L.FTPgpChain AND WAH.FNLNGID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''''

        SET @tSQL += @tSQL_Filter	
        EXECUTE(@tSQL)

        SET @FTResult = CONVERT(VARCHAR(8000),@tSQL)
        RETURN @FTResult
    END TRY
    BEGIN CATCH
        return -1
        PRINT @tSQL
    END CATCH
    GO

/* เอามาจากชิ */
    /****** Object:  StoredProcedure [dbo].[SP_RPTxPSSVatByDate1001007]    Script Date: 6/1/2565 12:15:22 ******/
    DROP PROCEDURE IF EXISTS [dbo].[SP_RPTxPSSVatByDate1001007]
    GO
    /****** Object:  StoredProcedure [dbo].[SP_RPTxPdtBalByBch]    Script Date: 6/1/2565 12:15:22 ******/
    DROP PROCEDURE IF EXISTS [dbo].[SP_RPTxPdtBalByBch]
    GO
    /****** Object:  StoredProcedure [dbo].[SP_RPTxPdtBalByBch]    Script Date: 6/1/2565 12:15:22 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
    CREATE PROCEDURE [dbo].[SP_RPTxPdtBalByBch] 
        @pnLngID int , 
        @pnComName Varchar(100),
        @ptRptCode Varchar(100),
        @ptUsrSession Varchar(255),
        @pnFilterType int, --1 BETWEEN 2 IN

        --1 ตัวแทนจำหน่าย Agency
        @ptAgnL Varchar(8000), --กรณี Condition IN
        
        --2 สาขา
        @ptBchL Varchar(8000), --กรณี Condition IN
        --@ptBchF Varchar(5),
        --@ptBchT Varchar(5),
        
        --3. Merchant
        @ptMerL Varchar(8000), --กรณี Condition IN
        --@ptMerF Varchar(10),
        --@ptMerT Varchar(10),

        --4. Shop Code
        @ptShpL Varchar(8000), --กรณี Condition IN
        --@ptShpF Varchar(10),
        --@ptShpT Varchar(10),

        --5. คลัง
        @ptWahCodeL Varchar(8000), --กรณี Condition IN

        --6. สินค้า
        @ptPdtF Varchar(20),
        @ptPdtT Varchar(20),

        --7. กลุ่มสินค้า
        @ptPgpF Varchar(20),
        @ptPgpT Varchar(20),
        
        --8.
        @FNResult INT OUTPUT 
    AS
    --------------------------------------
    -- Watcharakorn 
    -- Create 26/01/2021
    -- Temp name  TRPTPdtBalByBchTmp

    --------------------------------------
    BEGIN TRY

        DECLARE @nLngID int 
        DECLARE @nComName Varchar(100)
        DECLARE @tRptCode Varchar(100)
        DECLARE @tUsrSession Varchar(255)
        DECLARE @tSql VARCHAR(8000)
        DECLARE @tSql1 VARCHAR(8000)
        DECLARE @tSqlPdt VARCHAR(8000)
        DECLARE @tSqlBch1 VARCHAR(8000)

        DECLARE @tBchF Varchar(5)
        DECLARE @tBchT Varchar(5)

        --Merchant
        DECLARE @tMerF Varchar(10)
        DECLARE @tMerT Varchar(10)
        --Shop Code
        DECLARE @tShpF Varchar(10)
        DECLARE @tShpT Varchar(10)

        DECLARE @tPosCodeF Varchar(20)
        DECLARE @tPosCodeT Varchar(20)

        DECLARE @tDocDateF Varchar(10)
        DECLARE @tDocDateT Varchar(10)

        SET @nLngID = @pnLngID
        SET @nComName = @pnComName
        SET @tUsrSession = @ptUsrSession
        SET @tRptCode = @ptRptCode

            SET @FNResult= 0

        IF @nLngID = null
        BEGIN
            SET @nLngID = 1
        END	
        --Set ค่าให้ Paraleter กรณี T เป็นค่าว่างหรือ null
        --Agency
        --Branch
        IF @ptAgnL = null
        BEGIN
            SET @ptAgnL = ''
        END


        --Branch
        IF @ptBchL = null
        BEGIN
            SET @ptBchL = ''
        END
        --IF @tBchF = null
        --BEGIN
        --	SET @tBchF = ''
        --END
        --IF @tBchT = null OR @tBchT = ''
        --BEGIN
        --	SET @tBchT = @tBchF
        --END
        -------

        ---Merchant
        IF @ptMerL =null
        BEGIN
            SET @ptMerL = ''
        END
        --IF @tMerF =null
        --BEGIN
        --	SET @tMerF = ''
        --END
        --IF @tMerT =null OR @tMerT = ''
        --BEGIN
        --	SET @tMerT = @tMerF
        --END 
        ----------------
        
        --SHOP
        IF @ptShpL =null
        BEGIN
            SET @ptShpL = ''
        END
        --IF @tShpF =null
        --BEGIN
        --	SET @tShpF = ''
        --END
        --IF @tShpT =null OR @tShpT = ''
        --BEGIN
        --	SET @tShpT = @tShpF
        --END 
        ------------------

        --Warehouse
        IF @ptWahCodeL = null
        BEGIN
            SET @ptWahCodeL = ''
        END
        
        -----------------------------------
        IF @ptPdtF =null
        BEGIN
            SET @ptPdtF = ''
        END
        IF @ptPdtT =null OR @ptPdtT = ''
        BEGIN
            SET @ptPdtT = @ptPdtF
        END 

        IF @ptPgpF =null
        BEGIN
            SET @ptPgpF = ''
        END
        IF @ptPgpT =null OR @ptPgpT = ''
        BEGIN
            SET @ptPgpT = @ptPgpF
        END 

        
        --SET @tSql1 =   ' '-- WHERE 1=1 AND HD.FTXshStaDoc = ''1'' AND FTXsdStaPdt <> ''4'''
        --IF @pnFilterType = '1'
        --BEGIN
        --	IF (@tBchF <> '' AND @tBchT <> '')
        --	BEGIN
        --		SET @tSql1 +=' AND HD.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
        --	END

        --	IF (@tMerF <> '' AND @tMerT <> '')
        --	BEGIN
        --		SET @tSql1 +=' AND Shp.FTMerCode BETWEEN ''' + @tMerF + ''' AND ''' + @tMerT + ''''
        --	END

        --	IF (@tShpF <> '' AND @tShpT <> '')
        --	BEGIN
        --		SET @tSql1 +=' AND HD.FTShpCode BETWEEN ''' + @tShpF + ''' AND ''' + @tShpT + ''''
        --	END

        --	IF (@tPosCodeF <> '' AND @tPosCodeT <> '')
        --	BEGIN
        --		SET @tSql1 += ' AND HD.FTPosCode BETWEEN '''+@tPosCodeF+''' AND '''+@tPosCodeT+''''
        --	END		
        --END
        SET @tSql1 = ''
        IF @pnFilterType = 2
        BEGIN
                IF (@ptAgnL <> '')
            BEGIN
                SET @tSql1 +=' AND FTAgnCode IN (' + @ptAgnL + ')'
            END
            --SET @tSql1 += '111111'
            --PRINT @tSql1		
            IF (@ptBchL <> '')
            BEGIN
                SET @tSql1 +=' AND FTBchCode IN (' + @ptBchL + ')'
            END

            IF (@ptMerL <> '' )
            BEGIN
                SET @tSql1 +=' AND FTMerCode IN (' + @ptMerL + ')'
            END

            IF (@ptShpL <> '')
            BEGIN
                SET @tSql1 +=' AND FTShpCode IN (' + @ptShpL + ')'
            END
        END

        
        SET @tSqlPdt = ''
        IF (@ptWahCodeL <>'')
        BEGIN
            SET @tSqlPdt += ' AND STK.FTWahCode IN ('+@ptWahCodeL+ ')'
        END
        --INC.FTPdtCode,
        --INC.FTPgpChain

        IF (@ptPdtF <> '' AND @ptPdtT <> '')
        BEGIN
            SET @tSqlPdt +=' AND INC.FTPdtCode BETWEEN ''' + @ptPdtF + ''' AND ''' + @ptPdtT + ''''
        END

        IF (@ptPgpF <> '' AND @ptPgpT <> '')
        BEGIN
            SET @tSqlPdt +=' AND INC.FTPgpChain BETWEEN ''' + @ptPgpF + ''' AND ''' + @ptPgpT + ''''
        END



        SET @tSqlBch1 = ''
        IF (@ptBchL <> '')
        BEGIN
            SET @tSqlBch1 +=' AND STK.FTBchCode IN (' + @ptBchL + ')'
        END


        --PRINT @tSql1
        --PRINT @tSqlPdt
        DELETE FROM TRPTPdtBalByBchTmp WITH (ROWLOCK) WHERE FTComName =  '' + @nComName + ''  AND FTRptCode = '' + @tRptCode + '' AND FTUsrSession = '' + @tUsrSession + ''--ลบข้อมูล Temp ของเครื่องที่จะบันทึกขอมูลลง Temp
            
        SET @tSql  =' INSERT INTO TRPTPdtBalByBchTmp '
        SET @tSql +=' ('
        SET @tSql +=' FTComName,FTRptCode,FTUsrSession,'
        SET @tSql +=' FTBchCode,FTBchName,FTPdtCode,FTPdtName,FCStkQty,FCStkSetPrice,FCStkAmount'
        SET @tSql +=' ) '
        SET @tSql +=' SELECT FTComName,FTRptCode,FTUsrSession,'	
        SET @tSql +=' FTBchCode,FTBchName,FTPdtCode,FTPdtName,FCStkQty,FCStkSetPrice,FCStkAmount'
        SET @tSql +=' FROM '
        SET @tSql +=' ('
            SET @tSql +=' SELECT '''+ @nComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'	
            SET @tSql +=' INC.FTPdtCode,ISNULL(PDT.FTPdtName, '''') AS FTPdtName, ISNULL(INC.FTPgpChain, '''') AS FTPgpChain, ISNULL(STK.FTWahCode, '''') AS FTWahCode,' 
            SET @tSql +=' ISNULL(INC.FTBchCode, '''') AS FTBchCode,' --optional
            SET @tSql +=' ISNULL(BCH.FTBchName, '''') AS FTBchName,' --optional
            SET @tSql +=' ISNULL(INC.FTAgnCode, '''') AS FTAgnCode,' --optional
            SET @tSql +=' ISNULL(AGN.FTAgnName, '''') AS FTAgnName,' --optional
            SET @tSql +=' ISNULL(INC.FTMerCode, '''') AS FTMerCode,' --optional
            SET @tSql +=' ISNULL(MER.FTMerName, '''') AS FTMerName,' --optional
            SET @tSql +=' ISNULL(INC.FTShpCode, '''') AS FTShpCode,' --optional
            SET @tSql +=' ISNULL(SHP.FTShpName, '''') AS FTShpName,' --optional
            SET @tSql +=' SUM(ISNULL(STK.FCStkQty, 0)) AS FCStkQty,' 
            SET @tSql +=' SUM(ISNULL(PPA.FCPgdPriceRet, 0)) AS FCStkSetPrice,'
            SET @tSql +=' SUM(ISNULL(STK.FCStkQty, 0)) * SUM(ISNULL(PPA.FCPgdPriceRet, 0)) AS FCStkAmount'
            SET @tSql +=' FROM TCNTPdtStkbal STK WITH(NOLOCK)'
            SET @tSql +=' RIGHT JOIN'
            SET @tSql +=' ('
                        --Get Center Products สินค้าส่วนกลาง
                SET @tSql +=' SELECT FTPdtCode, FTPgpChain,NULL AS FTBchCode, NULL AS FTAgnCode, NULL AS FTMerCode, NULL AS FTShpCode' 
                SET @tSql +=' FROM TCNMPdt WITH(NOLOCK)' 
                SET @tSql +=' WHERE FTPdtCode NOT IN (SELECT FTPdtCode FROM TCNMPdtSpcBch WITH(NOLOCK))'

                SET @tSql +=' UNION'
                --Get Specific Products 
                --สินค้าพิเศษตามกลุ่มการแสดงรายงาน สาขา , ตัวแทนขาย , กลุ่มธุระกิจ , ร้านค้า ( ลูกค้าเลือกเงื่อนไขมา )
                SET @tSql +=' SELECT TCNMPdtSpcBch.FTPdtCode, FTPgpChain,FTBchCode, FTAgnCode, FTMerCode, FTShpCode' 
                SET @tSql +=' FROM TCNMPdtSpcBch WITH(NOLOCK) INNER JOIN TCNMPdt Pdt ON TCNMPdtSpcBch.FTPdtCode = Pdt.FTPdtCode'
                -- กรองข้อมูลสินค้าพิเศษตามกลุ่มการแสดงรายงาน สาขา , ตัวแทนขาย , กลุ่มธุระกิจ , ร้านค้า ( ลูกค้าเลือกเงื่อนไขมา )
                SET @tSql +=' WHERE 1 = 1' 
                --PRINT @tSql1
                SET @tSql += @tSql1

            SET @tSql +=' ) INC ON STK.FTPdtCode = INC.FTPdtCode'
            SET @tSql +=' LEFT JOIN'
                SET @tSql +=' ('
                    --หาราคาสินค้า ดึงราคาที่ actived ตามวันที่เรียกดูรายงาน
                    SET @tSql +=' SELECT PRI.*'
                    SET @tSql +=' FROM'
                        SET @tSql +=' ('
                            SET @tSql +=' SELECT ROW_NUMBER() OVER(PARTITION BY FTPdtCode ORDER BY FDPghDStart DESC) AS FNPriAtvSeq, FTPdtCode, FCPgdPriceRet'
                            SET @tSql +=' FROM TCNTPdtPrice4PDT'
                            SET @tSql +=' WHERE(CONVERT(CHAR(10), FDPghDStart, 126) <= CONVERT(CHAR(10), GETDATE(), 126) AND CONVERT(CHAR(10), FDPghDStop, 126) >= CONVERT(CHAR(10), GETDATE(), 126))'
                        SET @tSql +=' ) PRI'
                    SET @tSql +=' WHERE FNPriAtvSeq = 1'
                SET @tSql +=' ) PPA ON INC.FTPdtCode = PPA.FTPdtCode'
                SET @tSql +=' LEFT JOIN TCNMPdt_L PDT WITH(NOLOCK) ON INC.FTPdtCode = PDT.FTPdtCode   AND PDT.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''                                    
                SET @tSql +=' LEFT JOIN TCNMBranch_L BCH WITH(NOLOCK) ON INC.FTBchCode = BCH.FTBchCode AND BCH.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
                SET @tSql +=' LEFT JOIN TCNMAgency_L AGN WITH(NOLOCK) ON INC.FTAgnCode = AGN.FTAgnCode AND AGN.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
                SET @tSql +=' LEFT JOIN TCNMMerchant_L MER WITH(NOLOCK) ON INC.FTMerCode = MER.FTMerCode AND MER.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + '''' 
                SET @tSql +=' LEFT JOIN TCNMShop_L SHP WITH(NOLOCK) ON INC.FTShpCode = SHP.FTShpCode AND SHP.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''' ' 
            --order by ตามกลุ่มข้อมูลที่ user ส่งมา BchCode , ShpCode , MerCode , PdtGrpCode
            SET @tSql +=' WHERE 1=1'
            SET @tSql += @tSqlBch1
            SET @tSql += @tSqlPdt
            SET @tSql +=' GROUP BY INC.FTPdtCode, PDT.FTPdtName, INC.FTPgpChain,STK.FTWahCode,'
                    SET @tSql +=' INC.FTBchCode, BCH.FTBchName, INC.FTAgnCode, AGN.FTAgnName, INC.FTMerCode, MER.FTMerName, INC.FTShpCode, SHP.FTShpName '
            --SET @tSql +=' ORDER BY INC.FTPdtCode'
        SET @tSql +=' ) Complate'
        --SELECT @tSql
        EXECUTE (@tSql)
    END TRY

    BEGIN CATCH 
        SET @FNResult= -1
    END CATCH	
    GO
    
/* เอามาจากชิ */
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
    CREATE PROCEDURE [dbo].[SP_RPTxPSSVatByDate1001007] 
    --ALTER PROCEDURE [dbo].[SP_RPTxPSSVatByDate1001007] 
        @pnLngID int , 
        @pnComName Varchar(100),
        @ptRptCode Varchar(100),
        @ptUsrSession Varchar(255),
        @pnFilterType int, --1 BETWEEN 2 IN
        --สาขา
        @ptBchL Varchar(8000), --กรณี Condition IN
        @ptBchF Varchar(5),
        @ptBchT Varchar(5),
        --Merchant
        @ptMerL Varchar(8000), --กรณี Condition IN
        @ptMerF Varchar(10),
        @ptMerT Varchar(10),
        --Shop Code
        @ptShpL Varchar(8000), --กรณี Condition IN
        @ptShpF Varchar(10),
        @ptShpT Varchar(10),
        --เครื่องจุดขาย
        @ptPosL Varchar(8000), --กรณี Condition IN
        @ptPosF Varchar(20),
        @ptPosT Varchar(20),

        --@ptBchF Varchar(5),
        --@ptBchT Varchar(5),
        ----เครื่องจุดขาย
        --@ptPosCodeF Varchar(20),
        --@ptPosCodeT Varchar(20),

        @ptDocDateF Varchar(10),
        @ptDocDateT Varchar(10),
        ----ลูกค้า
        --@ptCstF Varchar(20),
        --@ptCstT Varchar(20),
        @FNResult INT OUTPUT 
    AS
    --------------------------------------
    -- Watcharakorn 
    -- Create 10/07/2019
    -- Temp name  TRPTSalRCTmp
    -- @pnLngID ภาษา
    -- @ptRptCdoe ชื่อรายงาน
    -- @ptUsrSession UsrSession
    -- @ptBchF จากรหัสสาขา
    -- @ptBchT ถึงรหัสสาขา
        --DECLARE @tPosCodeF Varchar(30)
        --DECLARE @tPosCodeT Varchar(30)
    -- @ptDocDateF จากวันที่
    -- @ptDocDateT ถึงวันที่
    -- @FNResult


    --------------------------------------
    BEGIN TRY

        DECLARE @nLngID int 
        DECLARE @nComName Varchar(100)
        DECLARE @tRptCode Varchar(100)
        DECLARE @tUsrSession Varchar(255)
        DECLARE @tSql VARCHAR(8000)
        DECLARE @tSqlDrop VARCHAR(8000)
        DECLARE @tSql1 nVARCHAR(Max)
        DECLARE @tSqlSale VARCHAR(8000)
        DECLARE @tTblName Varchar(255)
        DECLARE @tSqlS Varchar(255)
        DECLARE @tSqlR Varchar(255)
        DECLARE @tSql2 VARCHAR(255)

        --Branch Code
        DECLARE @tBchF Varchar(5)
        DECLARE @tBchT Varchar(5)
        --Merchant
        DECLARE @tMerF Varchar(10)
        DECLARE @tMerT Varchar(10)
        --Shop Code
        DECLARE @tShpF Varchar(10)
        DECLARE @tShpT Varchar(10)
        --Pos Code
        DECLARE @tPosF Varchar(20)
        DECLARE @tPosT Varchar(20)

        --DECLARE @tBchF Varchar(5)
        --DECLARE @tBchT Varchar(5)

        --DECLARE @tPosCodeF Varchar(20)
        --DECLARE @tPosCodeT Varchar(20)

        DECLARE @tDocDateF Varchar(10)
        DECLARE @tDocDateT Varchar(10)
        --ลูกค้า
        --DECLARE @tCstF Varchar(20)
        --DECLARE @tCstT Varchar(20)


        --SET @nLngID = 1
        --SET @nComName = 'Ada062'
        --SET @tRptName = 'PSSVat1001006'
        --SET @ptUsrSession = '001'
        --SET @tBchF = '001'
        --SET @tBchT = '001'

        --SET @tDocDateF = '2019-07-01'
        --SET @tDocDateT = '2019-07-10'


        --SET @nLngID = 1
        --SET @nComName = 'Ada062'
        --SET @tRptName = 'DailySaleByInv1001001'
        --SET @ptUsrSession = '001'
        --SET @tBchF = ''
        --SET @tBchT = ''

        --SET @tDocDateF = ''
        --SET @tDocDateT = ''

        SET @nLngID = @pnLngID
        SET @nComName = @pnComName
        SET @tUsrSession = @ptUsrSession
        SET @tRptCode = @ptRptCode

        --SET @tBchF = @ptBchF
        --SET @tBchT = @ptBchT

        --SET @tPosCodeF  = @ptPosCodeF 
        --SET @tPosCodeT = @ptPosCodeT 

        --Branch
        SET @tBchF  = @ptBchF
        SET @tBchT  = @ptBchT
        --Merchant
        SET @tMerF  = @ptMerF
        SET @tMerT  = @ptMerT
        --Shop
        SET @tShpF  = @ptShpF
        SET @tShpT  = @ptShpT
        --Pos
        SET @tPosF  = @ptPosF 
        SET @tPosT  = @ptPosT

        SET @tDocDateF = @ptDocDateF
        SET @tDocDateT = @ptDocDateT

        SET @FNResult= 0

        SET @tDocDateF = CONVERT(VARCHAR(10),@tDocDateF,121)
        SET @tDocDateT = CONVERT(VARCHAR(10),@tDocDateT,121)

        IF @nLngID = null
        BEGIN
            SET @nLngID = 1
        END	
        --Set ค่าให้ Paraleter กรณี T เป็นค่าว่างหรือ null

        IF @ptBchL = null
        BEGIN
            SET @ptBchL = ''
        END

        IF @tBchF = null
        BEGIN
            SET @tBchF = ''
        END
        IF @tBchT = null OR @tBchT = ''
        BEGIN
            SET @tBchT = @tBchF
        END

        IF @ptMerL =null
        BEGIN
            SET @ptMerL = ''
        END

        IF @tMerF =null
        BEGIN
            SET @tMerF = ''
        END
        IF @tMerT =null OR @tMerT = ''
        BEGIN
            SET @tMerT = @tMerF
        END 

        IF @ptShpL =null
        BEGIN
            SET @ptShpL = ''
        END

        IF @tShpF =null
        BEGIN
            SET @tShpF = ''
        END
        IF @tShpT =null OR @tShpT = ''
        BEGIN
            SET @tShpT = @tShpF
        END

        IF @tPosF = null
        BEGIN
            SET @tPosF = ''
        END
        IF @tPosT = null OR @tPosT = ''
        BEGIN
            SET @tPosT = @tPosF
        END

        --IF @tBchF = null
        --BEGIN
        --	SET @tBchF = ''
        --END
        --IF @tBchT = null OR @tBchT = ''
        --BEGIN
        --	SET @tBchT = @tBchF
        --END

        --IF @tPosCodeF = null
        --BEGIN
        --	SET @tPosCodeF = ''
        --END

        --IF @tPosCodeT = null OR @tPosCodeT = ''
        --BEGIN
        --	SET @tPosCodeT = @tPosCodeF
        --END



        IF @tDocDateF = null
        BEGIN 
            SET @tDocDateF = ''
        END

        IF @tDocDateT = null OR @tDocDateT =''
        BEGIN 
            SET @tDocDateT = @tDocDateF
        END

        SET @tSql2 =   ' WHERE 1=1 AND FTXshStaDoc = ''1'''
        SET @tSqlS =   ' WHERE 1=1 AND FTXshStaDoc = ''1'' AND FNXshDocType = ''1'''
        SET @tSqlR =   ' WHERE 1=1 AND FTXshStaDoc = ''1'' AND FNXshDocType = ''9'''

        IF @pnFilterType = '1'
        BEGIN
            IF (@tBchF <> '' AND @tBchT <> '')
            BEGIN
                SET @tSqlS +=' AND HD.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
                SET @tSqlR +=' AND HD.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
                SET @tSql2 +=' AND HD.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
            END

            IF (@tMerF <> '' AND @tMerT <> '')
            BEGIN
                SET @tSqlS +=' AND Shp.FTMerCode BETWEEN ''' + @tMerF + ''' AND ''' + @tMerT + ''''
                SET @tSqlR +=' AND Shp.FTMerCode BETWEEN ''' + @tMerF + ''' AND ''' + @tMerT + ''''
                SET @tSql2 +=' AND Shp.FTMerCode BETWEEN ''' + @tMerF + ''' AND ''' + @tMerT + ''''
            END

            IF (@tShpF <> '' AND @tShpT <> '')
            BEGIN
                SET @tSqlS +=' AND HD.FTShpCode BETWEEN ''' + @tShpF + ''' AND ''' + @tShpT + ''''
                SET @tSqlR +=' AND HD.FTShpCode BETWEEN ''' + @tShpF + ''' AND ''' + @tShpT + ''''
                SET @tSql2 +=' AND HD.FTShpCode BETWEEN ''' + @tShpF + ''' AND ''' + @tShpT + ''''
            END

            IF (@tPosF <> '' AND @tPosT <> '')
            BEGIN
                SET @tSqlS += ' AND HD.FTPosCode BETWEEN ''' + @tPosF + ''' AND ''' + @tPosT + ''''
                SET @tSqlR += ' AND HD.FTPosCode BETWEEN ''' + @tPosF + ''' AND ''' + @tPosT + ''''
                SET @tSql2 += ' AND HD.FTPosCode BETWEEN ''' + @tPosF + ''' AND ''' + @tPosT + ''''
            END		
        END

        IF @pnFilterType = '2'
        BEGIN
            IF (@ptBchL <> '' )
            BEGIN
                SET @tSqlS +=' AND HD.FTBchCode IN (' + @ptBchL + ')'
                SET @tSqlR +=' AND HD.FTBchCode IN (' + @ptBchL + ')'
                SET @tSql2 +=' AND HD.FTBchCode IN (' + @ptBchL + ')'
            END

            IF (@ptMerL <> '' )
            BEGIN
                SET @tSqlS +=' AND Shp.FTMerCode IN (' + @ptMerL + ')'
                SET @tSqlR +=' AND Shp.FTMerCode IN (' + @ptMerL + ')'
                SET @tSql2 +=' AND Shp.FTMerCode IN (' + @ptMerL + ')'
            END

            IF (@ptShpL <> '')
            BEGIN
                SET @tSqlS +=' AND HD.FTShpCode IN (' + @ptShpL + ')'
                SET @tSqlR +=' AND HD.FTShpCode IN (' + @ptShpL + ')'
                SET @tSql2 +=' AND HD.FTShpCode IN (' + @ptShpL + ')'
            END

            IF (@ptPosL <> '')
            BEGIN
                SET @tSqlS += ' AND HD.FTPosCode IN (' + @ptPosL + ')'
                SET @tSqlR += ' AND HD.FTPosCode IN (' + @ptPosL + ')'
                SET @tSql2 += ' AND HD.FTPosCode IN (' + @ptPosL + ')'
            END		
        END

        --IF (@tBchF <> '' AND @tBchT <> '')
        --BEGIN

        --	SET @tSqlS +=' AND HD.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
        --	SET @tSqlR +=' AND HD.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
        --	SET @tSql2 +=' AND HD.FTBchCode BETWEEN ''' + @tBchF + ''' AND ''' + @tBchT + ''''
        --END

        --IF (@tPosCodeF <> '' AND @tPosCodeT <> '')
        --	BEGIN
        --		SET @tSql2 += ' AND HD.FTPosCode BETWEEN '''+@tPosCodeF+''' AND '''+@tPosCodeT+''''
        --		SET @tSqlS += ' AND HD.FTPosCode BETWEEN '''+@tPosCodeF+''' AND '''+@tPosCodeT+''''
        --		SET @tSqlR += ' AND HD.FTPosCode BETWEEN '''+@tPosCodeF+''' AND '''+@tPosCodeT+''''
        --	END		

        IF (@tDocDateF <> '' AND @tDocDateT <> '')
        BEGIN
            SET @tSql2 +=' AND CONVERT(VARCHAR(10),FDXshDocDate,121) BETWEEN ''' + @tDocDateF + ''' AND ''' + @tDocDateT + ''''
            SET @tSqlS +=' AND CONVERT(VARCHAR(10),FDXshDocDate,121) BETWEEN ''' + @tDocDateF + ''' AND ''' + @tDocDateT + ''''
            SET @tSqlR +=' AND CONVERT(VARCHAR(10),FDXshDocDate,121) BETWEEN ''' + @tDocDateF + ''' AND ''' + @tDocDateT + ''''
        END

        DELETE FROM TRPTPSTaxHDDateTmp WITH (ROWLOCK) WHERE FTComName =  '' + @nComName + ''  AND FTRptCode = '' + @tRptCode + '' AND FTUsrSession = '' + @tUsrSession + ''--ลบข้อมูล Temp ของเครื่องที่จะบันทึกขอมูลลง Temp

        --SET @tTblName = 'TRPTPSTaxTmp'+ @nComName + ''

        ----if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].''+@tTblName''')) 
        --SET @tSqlDrop = ' if exists (select * from dbo.sysobjects where name = '''+@tTblName+ ''')'--id = object_id(N'[dbo].''+@tTblName'''))' 
        --SET @tSqlDrop += ' DROP TABLE '+ @tTblName + ''
        ----PRINT @tSqlDrop
        --EXECUTE(@tSqlDrop)

        --PRINT @tTblName 

        SET @tSqlSale  = ' INSERT INTO TRPTPSTaxHDDateTmp '
        SET @tSqlSale +=' ('
        SET @tSqlSale +=' FTComName,FTRptCode,FTUsrSession,'
        SET @tSqlSale +=' FTBchCode,FTBchName,FDXshdocDate,FTPosCode,FTXshDocNoSale,FTXshDocNoRefun,FCXshAmt,FCXshVat,FCXshAmtNV,FCXshGrandTotal,'
        --*NUI 2019-11-14
        SET @tSqlSale +=' FNAppType,FTPosRegNo'
        -----------
        SET @tSqlSale +=' )'
        SET @tSqlSale +=' SELECT '''+ @nComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'	
        SET @tSqlSale +=' vAT.FTBchCode,Vat.FTBchName,Vat.FDXshdocDate,Vat.FTPosCode,ISNULL(FTXshDocNoSale,'''') AS FTXshDocNoSale,ISNULL(FTXshDocNoRefun,'''') AS FTXshDocNoRefun,FCXshValue,FCXshVat,FCXshAmtNV,FCXshGrand, FNAppType,FTPosRegNo'
        --SET @tSqlSale +=' INTO  '+ @tTblName + ''
        SET @tSqlSale +=' FROM('
        SET @tSqlSale +=' SELECT HD.FTBchCode,FTBchName,CONVERT(VARCHAR(10),FDXshDocDate,121) AS FDXshdocDate,HD.FTPosCode,'
        --SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ISNULL(FCXshGrand,0)-ISNULL(FCXshAmtNV,0)-ISNULL(FCXshVat,0) ELSE (ISNULL(FCXshGrand,0)-ISNULL(FCXshAmtNV,0)-ISNULL(FCXshVat,0))*-1 END) AS FCXshValue, '
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshVatable,0)-ISNULL(FCXshAmtNV,0),2) ELSE ROUND((ISNULL(FCXshVatable,0)-ISNULL(FCXshAmtNV,0))*-1,2) END) AS FCXshValue, '
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshVat,0),2)	ELSE ROUND(ISNULL(FCXshVat,0)*-1,2) END) AS FCXshVat,'
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshAmtNV,0),2) ELSE ROUND(ISNULL(FCXshAmtNV,0)*-1,2) END) AS FCXshAmtNV,' 
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshGrand,0)-ISNULL(FCXshRnd,0),2) ELSE ROUND((ISNULL(FCXshGrand,0)-ISNULL(FCXshRnd,0))*-1,2) END) AS FCXshGrand,' 
        SET @tSqlSale +=' 1 AS FNAppType,POS.FTPosRegNo'--,Cst.FTCstBchCode,Cst.FTCstBusiness 
        SET @tSqlSale +=' FROM TPSTSalHD HD LEFT JOIN TCNMCst Cst ON HD.FTCstCode = Cst.FTCstCode' 
        SET @tSqlSale +=' LEFT JOIN TCNMPOS POS ON HD.FTPosCode = Pos.FTPosCode AND HD.FTBchCode = Pos.FTBchCode  LEFT JOIN' 
        SET @tSqlSale +=' TCNMCst_L Cst_L ON HD.FTCstCode = Cst_L.FTCstCode AND Cst_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	 
        SET @tSqlSale +=' LEFT JOIN TCNMBranch_L Bch_L ON HD.FTBchCode = Bch_L.FTBchCode AND Bch_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
        SET @tSqlSale +=' LEFT JOIN TCNMShop Shp WITH (NOLOCK) ON HD.FTBchCode = Shp.FTBchCode AND HD.FTShpCode = Shp.FTShpCode '
        SET @tSqlSale += @tSql2	
        SET @tSqlSale +=' GROUP BY HD.FTBchCode,FTBchName,CONVERT(VARCHAR(10),FDXshDocDate,121),HD.FTPosCode,POS.FTPosRegNo'
        SET @tSqlSale +=' ) Vat LEFT JOIN'
            SET @tSqlSale +=' (SELECT HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121) AS FDXshdocDate,HD.FTPosCode,'
            SET @tSqlSale +=' MIN(FTXshDocNo) + ''-'' + MAX(FTXshDocNo) AS FTXshDocNoSale'
            SET @tSqlSale +=' FROM TPSTSalHD HD LEFT JOIN TCNMCst Cst ON HD.FTCstCode = Cst.FTCstCode' 
            SET @tSqlSale +=' LEFT JOIN TCNMPOS POS ON HD.FTPosCode = Pos.FTPosCode AND HD.FTBchCode = Pos.FTBchCode  LEFT JOIN' 
            SET @tSqlSale +=' TCNMCst_L Cst_L ON HD.FTCstCode = Cst_L.FTCstCode AND Cst_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
            SET @tSqlSale +=' LEFT JOIN TCNMShop Shp WITH (NOLOCK) ON HD.FTBchCode = Shp.FTBchCode AND HD.FTShpCode = Shp.FTShpCode '
            SET @tSqlSale += @tSqlS
            SET @tSqlSale +=' GROUP BY HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121),HD.FTPosCode) Sale ON Vat.FTBchCode = Sale.FTBchCode AND Vat.FDXshdocDate = Sale.FDXshdocDate AND Vat.FTPosCode = Sale.FTPosCode '
            SET @tSqlSale +=' LEFT JOIN'
            SET @tSqlSale +=' (SELECT HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121) AS FDXshdocDate,HD.FTPosCode,'
            SET @tSqlSale +=' MIN(FTXshDocNo) + ''-'' + MAX(FTXshDocNo) AS FTXshDocNoRefun'
            SET @tSqlSale +=' FROM TPSTSalHD HD LEFT JOIN TCNMCst Cst ON HD.FTCstCode = Cst.FTCstCode' 
            SET @tSqlSale +=' LEFT JOIN TCNMPOS POS ON HD.FTPosCode = Pos.FTPosCode AND HD.FTBchCode = Pos.FTBchCode  LEFT JOIN' 
            SET @tSqlSale +=' TCNMCst_L Cst_L ON HD.FTCstCode = Cst_L.FTCstCode AND Cst_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
            SET @tSqlSale +=' LEFT JOIN TCNMShop Shp WITH (NOLOCK) ON HD.FTBchCode = Shp.FTBchCode AND HD.FTShpCode = Shp.FTShpCode '
            SET @tSqlSale += @tSqlR
            SET @tSqlSale +=' GROUP BY HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121),HD.FTPosCode) Ret ON Vat.FTBchCode = Ret.FTBchCode AND Vat.FDXshdocDate = Ret.FDXshdocDate AND Vat.FTPosCode = Ret.FTPosCode'	
        --PRINT @tSqlSale
        EXECUTE(@tSqlSale)

        --Vending
        SET @tSqlSale  = ' INSERT INTO TRPTPSTaxHDDateTmp '
        SET @tSqlSale +=' ('
        SET @tSqlSale +=' FTComName,FTRptCode,FTUsrSession,'
        SET @tSqlSale +=' FTBchCode,FTBchName,FDXshdocDate,FTPosCode,FTXshDocNoSale,FTXshDocNoRefun,FCXshAmt,FCXshVat,FCXshAmtNV,FCXshGrandTotal,'
        --*NUI 2019-11-14
        SET @tSqlSale +=' FNAppType,FTPosRegNo'
        -----------
        SET @tSqlSale +=' )'
        SET @tSqlSale +=' SELECT '''+ @nComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'	
        SET @tSqlSale +=' vAT.FTBchCode,Vat.FTBchName,Vat.FDXshdocDate,Vat.FTPosCode,ISNULL(FTXshDocNoSale,'''') AS FTXshDocNoSale,ISNULL(FTXshDocNoRefun,'''') AS FTXshDocNoRefun,FCXshValue,FCXshVat,FCXshAmtNV,FCXshGrand, FNAppType,FTPosRegNo'
        --SET @tSqlSale +=' INTO  '+ @tTblName + ''
        SET @tSqlSale +=' FROM('
        SET @tSqlSale +=' SELECT HD.FTBchCode,FTBchName,CONVERT(VARCHAR(10),FDXshDocDate,121) AS FDXshdocDate,HD.FTPosCode,'
        --SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ISNULL(FCXshGrand,0)-ISNULL(FCXshAmtNV,0)-ISNULL(FCXshVat,0) ELSE (ISNULL(FCXshGrand,0)-ISNULL(FCXshAmtNV,0)-ISNULL(FCXshVat,0))*-1 END) AS FCXshValue, '
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshVatable,0)-ISNULL(FCXshAmtNV,0),2) ELSE ROUND((ISNULL(FCXshVatable,0)-ISNULL(FCXshAmtNV,0))*-1,2) END) AS FCXshValue, '
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshVat,0),2)	ELSE ROUND(ISNULL(FCXshVat,0)*-1,2) END) AS FCXshVat,'
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshAmtNV,0),2) ELSE ROUND(ISNULL(FCXshAmtNV,0)*-1,2) END) AS FCXshAmtNV,' 
        SET @tSqlSale +=' SUM(CASE WHEN HD.FNXshDocType = ''1'' THEN ROUND(ISNULL(FCXshGrand,0)-ISNULL(FCXshRnd,0),2) ELSE ROUND((ISNULL(FCXshGrand,0)-ISNULL(FCXshRnd,0))*-1,2) END) AS FCXshGrand,' 
        SET @tSqlSale +=' 2 AS FNAppType,POS.FTPosRegNo'--,Cst.FTCstBchCode,Cst.FTCstBusiness 
        SET @tSqlSale +=' FROM TVDTSalHD HD LEFT JOIN TCNMCst Cst ON HD.FTCstCode = Cst.FTCstCode' 
        SET @tSqlSale +=' LEFT JOIN TCNMPOS POS ON HD.FTPosCode = Pos.FTPosCode AND HD.FTBchCode = Pos.FTBchCode ' 
        --NUI 2020-01-07
        SET @tSqlSale +=' INNER JOIN TVDTSalRC RC WITH(NOLOCK) ON HD.FTBchCode = RC.FTBchCode AND HD.FTXshDocNo = RC.FTXshDocNo'
        SET @tSqlSale +=' LEFT JOIN TFNMRcv Rcv WITH(NOLOCK) ON  RC.FTRcvCode = Rcv.FTRcvCode'			
        ------------
        SET @tSqlSale +=' LEFT JOIN TCNMCst_L Cst_L ON HD.FTCstCode = Cst_L.FTCstCode AND Cst_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	 
        SET @tSqlSale +=' LEFT JOIN TCNMBranch_L Bch_L ON HD.FTBchCode = Bch_L.FTBchCode AND Bch_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
        SET @tSqlSale +=' LEFT JOIN TCNMShop Shp WITH (NOLOCK) ON HD.FTBchCode = Shp.FTBchCode AND HD.FTShpCode = Shp.FTShpCode '
        SET @tSqlSale += @tSql2	
        SET @tSqlSale += ' AND Rcv.FTFmtCode <> ''004'''
        SET @tSqlSale +=' GROUP BY HD.FTBchCode,FTBchName,CONVERT(VARCHAR(10),FDXshDocDate,121),HD.FTPosCode,POS.FTPosRegNo'
        SET @tSqlSale +=' ) Vat LEFT JOIN'
            SET @tSqlSale +=' (SELECT HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121) AS FDXshdocDate,HD.FTPosCode,'
            SET @tSqlSale +=' MIN(HD.FTXshDocNo) + ''-'' + MAX(HD.FTXshDocNo) AS FTXshDocNoSale'
            SET @tSqlSale +=' FROM TVDTSalHD HD LEFT JOIN TCNMCst Cst ON HD.FTCstCode = Cst.FTCstCode' 
            SET @tSqlSale +=' LEFT JOIN TCNMPOS POS ON HD.FTPosCode = Pos.FTPosCode AND HD.FTBchCode = Pos.FTBchCode ' 
            --NUI 2020-01-07
            SET @tSqlSale +=' INNER JOIN TVDTSalRC RC WITH(NOLOCK) ON HD.FTBchCode = RC.FTBchCode AND HD.FTXshDocNo = RC.FTXshDocNo'
            SET @tSqlSale +=' LEFT JOIN TFNMRcv Rcv WITH(NOLOCK) ON  RC.FTRcvCode = Rcv.FTRcvCode'			
            ------------
            SET @tSqlSale +=' LEFT JOIN TCNMCst_L Cst_L ON HD.FTCstCode = Cst_L.FTCstCode AND Cst_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
            SET @tSqlSale +=' LEFT JOIN TCNMShop Shp WITH (NOLOCK) ON HD.FTBchCode = Shp.FTBchCode AND HD.FTShpCode = Shp.FTShpCode '
            SET @tSqlSale += @tSqlS
            SET @tSqlSale += ' AND Rcv.FTFmtCode <> ''004'''
            SET @tSqlSale +=' GROUP BY HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121),HD.FTPosCode) Sale ON Vat.FTBchCode = Sale.FTBchCode AND Vat.FDXshdocDate = Sale.FDXshdocDate AND Vat.FTPosCode = Sale.FTPosCode '
            SET @tSqlSale +=' LEFT JOIN'
            SET @tSqlSale +=' (SELECT HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121) AS FDXshdocDate,HD.FTPosCode,'
            SET @tSqlSale +=' MIN(HD.FTXshDocNo) + ''-'' + MAX(HD.FTXshDocNo) AS FTXshDocNoRefun'
            SET @tSqlSale +=' FROM TVDTSalHD HD LEFT JOIN TCNMCst Cst ON HD.FTCstCode = Cst.FTCstCode' 
            SET @tSqlSale +=' LEFT JOIN TCNMPOS POS ON HD.FTPosCode = Pos.FTPosCode AND HD.FTBchCode = Pos.FTBchCode ' 
            --NUI 2020-01-07
            SET @tSqlSale +=' INNER JOIN TVDTSalRC RC WITH(NOLOCK) ON HD.FTBchCode = RC.FTBchCode AND HD.FTXshDocNo = RC.FTXshDocNo'
            SET @tSqlSale +=' LEFT JOIN TFNMRcv Rcv WITH(NOLOCK) ON  RC.FTRcvCode = Rcv.FTRcvCode'			
            ------------
            SET @tSqlSale +=' LEFT JOIN TCNMCst_L Cst_L ON HD.FTCstCode = Cst_L.FTCstCode AND Cst_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''
            SET @tSqlSale +=' LEFT JOIN TCNMShop Shp WITH (NOLOCK) ON HD.FTBchCode = Shp.FTBchCode AND HD.FTShpCode = Shp.FTShpCode '
            SET @tSqlSale += @tSqlR
            SET @tSqlSale += ' AND Rcv.FTFmtCode <> ''004'''
            SET @tSqlSale +=' GROUP BY HD.FTBchCode,CONVERT(VARCHAR(10),FDXshDocDate,121),HD.FTPosCode) Ret ON Vat.FTBchCode = Ret.FTBchCode AND Vat.FDXshdocDate = Ret.FDXshdocDate AND Vat.FTPosCode = Ret.FTPosCode'	
        --PRINT @tSqlSale
        EXECUTE(@tSqlSale)

        RETURN SELECT * FROM TRPTPSTaxHDDateTmp WHERE FTComName = ''+ @nComName + '' AND FTRptCode = ''+ @tRptCode +'' AND FTUsrSession = '' + @tUsrSession 

        --SET @tSqlDrop += 'DROP TABLE '+ @tTblName + ''
        ----PRINT @tSqlDrop
        --EXECUTE(@tSqlDrop)
    END TRY

    BEGIN CATCH 
        SET @FNResult= -1
    END CATCH	

    GO


    IF EXISTS( SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxJob1RequestPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1 )
        DROP PROCEDURE [dbo].STP_DOCxJob1RequestPrc
    GO

    CREATE PROCEDURE [dbo].STP_DOCxJob1RequestPrc
        @ptBchCode varchar(5)
        , @ptDocNo varchar(30)
        , @ptWho varchar(100) 
        , @FNResult INT OUTPUT AS
    DECLARE @TTmpPrcStk TABLE 
    ( 
        FTBchCode varchar(5)
        , FTStkDocNo varchar(20)
        , FTStkType varchar(1)
        , FTStkSysType varchar(1)
        , FTPdtCode varchar(20)
        , FTPdtParent varchar(20)
        , FCStkQty decimal(18,2)
        , FTWahCode varchar(5)
        , FDStkDate Datetime
        , FCStkSetPrice decimal(18,2)
        , FCStkCostIn decimal(18,2)
        , FCStkCostEx decimal(18,2)
    ) 
    DECLARE @tStaPrc varchar(1)
    DECLARE @tStaPrcStkFrm varchar(1)
    DECLARE @tStaPrcStkTo varchar(1)
    DECLARE @tStaDoc varchar(1) --1 สมบูรณ์ 3ยกเลิก
    DECLARE @tTrans varchar(20)
    DECLARE @tWahCodeTo varchar(5) 
    /*---------------------------------------------------------------------
    Document History
    Version		Date			User	Remark
    07.00.00	19/11/2021		Net		create 
    07.01.00	23/11/2021		Net		ปรับการยกเลิก 
    07.02.00	24/11/2021		Net		ปรับการยกเลิก เอาจาก StockCard เลย
    ----------------------------------------------------------------------*/
    -- คลัง DT = ต้นทาง = คลังขาย
    -- โอนไปคลังปลายทาง = คลังจอง
    SET @tTrans = 'PrcJob1Req'
    BEGIN TRY
        BEGIN TRANSACTION @tTrans
        SET @tStaDoc = (SELECT TOP 1 FTXshStaDoc
                        FROM TSVTJob1ReqHD WITH(NOLOCK) 
                        WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo)

        IF @tStaDoc = '1' --เอกสารปกติ
        BEGIN
            SET @tStaPrc = (CASE WHEN (SELECT COUNT(*) AS FTXphStaPrcStk 
                                    FROM TSVTJob1ReqDT WITH(NOLOCK) 
                                    WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                        AND ISNULL(FTXsdStaPrcStk,'')<>'1' ) > 0
                                THEN '1' ELSE '2' END) -- 1ยังประมวลผลไม่หมด 2ประมวลผลหมดแล้ว

            
            -- ยังประมวลผล Stock ไม่ครบ
            IF @tStaPrc <> '2'	
            BEGIN
                
                --หาคลังจอง
                SET @tWahCodeTo = (SELECT TOP 1 FTWahCode
                                FROM TCNMWaHouse WAH WITH(NOLOCK)
                                WHERE WAH.FTBchCode = @ptBchCode AND ISNULL(FTWahStaType,'') = '7')
                
                --ถ้ามีไม่คลังจอง
                IF ISNULL(@tWahCodeTo, '') = '' 
                    THROW 50000, 'Wahouse not found', 0;
                
                -- ตัด Stk ออก คลังต้นทาง
                -- Create stk balance qty 0 ตัวที่ไม่เคยมี
                INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
                SELECT DISTINCT
                DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, 0 AS FCStkQty
                , GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
                FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
                    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
                -- Update ตัด Stk ออกจากคลังต้นทาง
                UPDATE STK WITH(ROWLOCK)
                SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
                , FDLastUpdOn = GETDATE()
                , FTLastUpdBy = @ptWho
                FROM TCNTPdtStkBal STK
                INNER JOIN (
                    SELECT DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
                    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                        DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                        AND ISNULL(DT.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    GROUP BY DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
                --End Update ตัด Stk ออกจากคลังต้นทาง

                -- เก็บตัวที่ตัด Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
                SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
                , '2' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
                , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
                , DT.FTPdtCode AS FTPdtCode
                , '' AS FTPdtParent
                , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
                , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
                , 0 AS FCStkCostIn
                , 0 AS FCStkCostEx
                FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                INNER JOIN TSVTJob1ReqDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCode, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้
                --End ตัด Stk ออก คลังต้นทาง
            



                -- ตัด Stk เข้า คลังปลายทาง 
                -- Create stk balance qty 0 ตัวที่ไม่เคยมี
                INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
                SELECT DISTINCT
                DT.FTBchCode, @tWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
                , GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
                FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode
                LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
                    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
                -- Update ตัด Stk เข้าคลังปลายทาง
                UPDATE STK WITH(ROWLOCK)
                SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
                , FDLastUpdOn = GETDATE()
                , FTLastUpdBy = @ptWho
                FROM TCNTPdtStkBal STK
                INNER JOIN (
                    SELECT DT.FTBchCode, @tWahCodeTo AS FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
                    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                        DT.FTBchCode = WAH.FTBchCode
                    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                        AND ISNULL(DT.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    GROUP BY DT.FTBchCode, DT.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
                --End Update ตัด Stk เข้าคลังปลายทาง

                -- เก็บตัวที่ตัด Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
                SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
                , '1' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
                , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
                , DT.FTPdtCode AS FTPdtCode
                , '' AS FTPdtParent
                , SUM(DT.FCXsdQtyAll) AS FCStkQty, @tWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
                , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
                , 0 AS FCStkCostIn
                , 0 AS FCStkCostEx
                FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                INNER JOIN TSVTJob1ReqDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้
                --End ตัด Stk เข้า คลังต้นปลายทาง 



                --Insert ลง Stock Card
                DELETE TCNTPdtStkCrd WITH(ROWLOCK)
                WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo

                INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
                (
                    FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                    , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                    , FDCreateOn, FTCreateBy
                )
                SELECT FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                    , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                    , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
                FROM @TTmpPrcStk
                --End Insert ลง Stock Card
            
            END
            --End ยังประมวลผล Stock ไม่ครบ
        END
        ELSE BEGIN --เอกสารยกเลิก
        
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                    WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                    WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                    WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                    ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
            WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, LTRIM(RTRIM(FTStkDocNo))+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                WHEN FTStkType='2' THEN '1'
                WHEN FTStkType='3' THEN '4'
                WHEN FTStkType='4' THEN '3'
                ELSE '5'
            END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
        

        
        COMMIT TRANSACTION @tTrans
        SET @FNResult= 0
        SELECT '' AS FTErrMsg
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION @tTrans
        SET @FNResult= -1
        SELECT ERROR_MESSAGE() AS FTErrMsg
    END CATCH
    GO


/* รายงาน - สินค้าคงคลังตามช่วงวัน */
    IF EXISTS( SELECT * FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxStockCardByDate')and OBJECTPROPERTY(id, N'IsProcedure') = 1 )
        DROP PROCEDURE [dbo].SP_RPTxStockCardByDate
    GO
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
    CREATE PROCEDURE [dbo].[SP_RPTxStockCardByDate]

        @pnLngID int ,
        @ptRptCode Varchar(100),
        @ptUsrSession Varchar(255),
        @ptBchCode Varchar(5000),
        @ptPdtF Varchar(20),
        @ptPdtT Varchar(20),
        @ptWahF Varchar(20),
        @ptWahT Varchar(20),
        @ptMonth Varchar(2), 
        @ptYear Varchar(4),
        @ptDocDateF Varchar(10),
        @ptDocDateT Varchar(10),
        @FNResult INT OUTPUT 
    AS
    BEGIN TRY

            DECLARE @tSqlIns VARCHAR(MAX)
            DECLARE @tSqlFilter VARCHAR(5000)


            SET @tSqlIns = ''
            SET @tSqlFilter = ''

            IF(@ptBchCode <> '' OR @ptBchCode <> NULL)
            BEGIN
                SET @tSqlFilter += ' AND Stk.FTBchCode IN ( ' + @ptBchCode + ') '
            END
            ELSE
            BEGIN
                SET @tSqlFilter += ' '
            END


        IF((@ptPdtF <> '' OR @ptPdtF <> NULL) AND (@ptPdtT <> '' OR @ptPdtT <> NULL) )
            BEGIN
                SET @tSqlFilter += ' AND Stk.FTPdtCode BETWEEN  ''' + @ptPdtF + ''''+ ' AND ''' + @ptPdtT + ''' ' 
            END
            ELSE
            BEGIN
                SET @tSqlFilter += ' ' 
            END

        IF((@ptWahF <> '' OR @ptWahF <> NULL) AND (@ptWahT <> '' OR @ptWahT <> NULL) )
            BEGIN
                SET @tSqlFilter += ' AND Stk.FTWahCode BETWEEN  ''' + @ptWahF + ''''+ ' AND ''' + @ptWahT + ''' ' 
            END
            ELSE
            BEGIN
                SET @tSqlFilter += '' 
            END

            IF((@ptDocDateF <> '' OR @ptDocDateF <> NULL) AND (@ptDocDateT <> '' OR @ptDocDateT <> NULL) )
            BEGIN
                SET @tSqlFilter += ' AND  CONVERT(VARCHAR(10),Stk.FDStkDate,121) BETWEEN ''' + @ptDocDateF + ''''+ ' AND ''' + @ptDocDateT + ''' '
            END
            ELSE
            BEGIN
                SET @tSqlFilter += ' ' 
            END


            IF(@ptMonth <> '' OR @ptMonth <> NULL)
            BEGIN
                SET @tSqlFilter += ' AND MONTH(Stk.FDStkDate) =  ''' + @ptMonth + ''' '
            END
            ELSE
            BEGIN
                SET @tSqlFilter += '' 
            END
            IF(@ptYear <> '' OR @ptYear <> NULL)
            BEGIN
                SET @tSqlFilter += ' AND YEAR(Stk.FDStkDate) =  ''' + @ptYear + ''' '
            END
            ELSE
            BEGIN
                SET @tSqlFilter += '' 
            END 

            --ลบข้อมูล Temp ของเครื่องที่จะบันทึกขอมูลลง Temp
            DELETE FROM TRPTTaxStockCardByDateTmp WITH (ROWLOCK) WHERE  FTRptCode = '' + @ptRptCode + '' AND FTUsrSession = '' + @ptUsrSession + ''
            
        

                SET @tSqlIns += ' INSERT INTO TRPTTaxStockCardByDateTmp'
                SET @tSqlIns += ' (FTRptCode,FTUsrSession,FTBchCode,FTBchName,FTWahCode,FTWahName,FTPdtCode,FTPdtName,FCStkQtyBal,FCStkCostStd)'	
    

                SET @tSqlIns += ' SELECT FTRptCode , FTUsrSession, '
                SET @tSqlIns += ' T.FTBchCode, FTBchName, FTWahCode, FTWahName, FTPdtCode, FTPdtName,'
                SET @tSqlIns += ' SUM((T.FCStkQtyMonEnd * 1) + (T.FCStkQtyIn * 1) + (T.FCStkQtyOut * -1) + (T.FCStkQtySaleDN * -1) + (T.FCStkQtyCN * 1) + (T.FCStkQtyAdj * 1)) AS FCStkQtyBal,'
                SET @tSqlIns += ' SUM(((T.FCStkQtyMonEnd * 1) + (T.FCStkQtyIn * 1) + (T.FCStkQtyOut * -1) + (T.FCStkQtySaleDN * -1) + (T.FCStkQtyCN * 1) + (T.FCStkQtyAdj * 1)) * T.FCPdtCostStd )   AS FCStkCostStd '
                SET @tSqlIns += ' FROM '
                SET @tSqlIns += '('
                        SET @tSqlIns += ' SELECT '
                        SET @tSqlIns +=  ''''+@ptRptCode +''' AS FTRptCode, '''+ @ptUsrSession +''' AS FTUsrSession,'
                        SET @tSqlIns += 'STK.FTBchCode, '
                        SET @tSqlIns += 'FTBchName, '
                        SET @tSqlIns += 'FDStkDate, '
                        SET @tSqlIns += 'FTStkDocNo, '
                        SET @tSqlIns += 'Stk.FTWahCode, '
                        SET @tSqlIns += 'Wah_L.FTWahName, '
                        SET @tSqlIns += 'Stk.FTPdtCode, '
                        SET @tSqlIns += 'Pdt_L.FTPdtName,'
                        SET @tSqlIns += ' CASE  '
                            SET @tSqlIns += ' WHEN FTStkType = ''0 '' '
                            SET @tSqlIns += ' THEN FCStkQty '
                            SET @tSqlIns += ' ELSE 0 '
                        SET @tSqlIns += ' END AS FCStkQtyMonEnd, '
                        SET @tSqlIns += ' CASE '
                            SET @tSqlIns += ' WHEN FTStkType = ''1'' '
                            SET @tSqlIns += ' THEN FCStkQty '
                            SET @tSqlIns += ' ELSE 0 '
                        SET @tSqlIns += ' END AS FCStkQtyIn, '
                        SET @tSqlIns += ' CASE '
                            SET @tSqlIns += ' WHEN FTStkType = ''2'' '
                            SET @tSqlIns += ' THEN FCStkQty '
                            SET @tSqlIns += ' ELSE 0 '
                        SET @tSqlIns += ' END AS FCStkQtyOut, '
                        SET @tSqlIns += ' CASE '
                            SET @tSqlIns += ' WHEN FTStkType = ''3'' '
                            SET @tSqlIns += ' THEN FCStkQty '
                            SET @tSqlIns += ' ELSE 0 '
                        SET @tSqlIns += ' END AS FCStkQtySaleDN, '
                        SET @tSqlIns += ' CASE '
                            SET @tSqlIns += ' WHEN FTStkType = ''4'' '
                            SET @tSqlIns += ' THEN FCStkQty '
                            SET @tSqlIns += ' ELSE 0 '
                        SET @tSqlIns += ' END AS FCStkQtyCN, '
                        SET @tSqlIns += ' CASE '
                            SET @tSqlIns += ' WHEN FTStkType = ''5'' '
                            SET @tSqlIns += ' THEN FCStkQty '
                            SET @tSqlIns += ' ELSE 0 '
                        SET @tSqlIns += ' END AS FCStkQtyAdj, '
                    SET @tSqlIns += ' Pdt.FCPdtCostStd  '
                    SET @tSqlIns += ' FROM TCNTPdtStkCrd Stk WITH(NOLOCK) '
                        SET @tSqlIns += ' LEFT JOIN TCNMWaHouse_L Wah_L WITH(NOLOCK) ON Stk.FTWahCode = Wah_L.FTWahCode '
                        SET @tSqlIns += ' AND Stk.FTBchCode = Wah_L.FTBchCode '
                        SET @tSqlIns += ' AND Wah_L.FNLngID =  '''+ CAST(@pnLngID AS VARCHAR(1)) + ''' LEFT JOIN TCNMPdt_L Pdt_L WITH(NOLOCK) ON Stk.FTPdtCode = Pdt_L.FTPdtCode '
                        SET @tSqlIns += ' AND Pdt_L.FNLngID = '''+ CAST(@pnLngID AS VARCHAR(1)) +''' LEFT JOIN TCNMBranch_L Bch_L WITH(NOLOCK) ON Stk.FTBchCode = Bch_L.FTBchCode '
                        SET @tSqlIns += ' AND Bch_L.FNLngID = '''+CAST(@pnLngID AS VARCHAR(1)) +''' LEFT JOIN TCNMPdt Pdt WITH(NOLOCK) ON Stk.FTPdtCode = Pdt.FTPdtCode '
                    SET @tSqlIns += ' WHERE ISNULL(Stk.FTPdtCode,'''') <> '''' '

                    SET @tSqlIns += @tSqlFilter

                    --SET @tSqlIns += ' AND Stk.FTPdtCode = '''+ @tPdt +''' AND Stk.FTWahCode = '''+ @tWah +''' AND Stk.FTBchCode = '''+ @tBchCode +'''  AND ( YEAR(Stk.FDStkDate) = '''+ @tBchCode +''' AND MONTH(Stk.FDStkDate) = '''+ @tMonth +'''  AND  CONVERT(VARCHAR(10),Stk.FDStkDate,121) BETWEEN  '''+ @tDocDateF +''' AND '''+ @tDocDateT +''' ) '
            
                SET @tSqlIns += ' ) T '
                SET @tSqlIns += ' GROUP BY '
                SET @tSqlIns += ' T.FTRptCode, '
                SET @tSqlIns += ' T.FTUsrSession, '
                SET @tSqlIns += ' T.FTBchCode, '
                SET @tSqlIns += ' FTBchName, '
                SET @tSqlIns += ' FTWahCode, '
                SET @tSqlIns += ' FTWahName, '
                SET @tSqlIns += ' FTPdtCode, '
                SET @tSqlIns += ' FTPdtName '
                EXECUTE(@tSqlIns)
    END TRY

    BEGIN CATCH 
        PRINT -1
    END CATCH	
    GO

/* เอามาจากเน็ต C# */
    IF EXISTS(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxPricePrc') AND OBJECTPROPERTY(id, N'IsProcedure') = 1)
        DROP PROCEDURE [dbo].STP_DOCxPricePrc
    GO
    CREATE PROCEDURE [dbo].STP_DOCxPricePrc
        @ptBchCode varchar(5)
        , @ptDocNo varchar(30)
        , @ptWho varchar(100) 
        , @FNResult INT OUTPUT AS
    DECLARE @tHQCode varchar(5)
    DECLARE @tBchTo varchar(5)	--2.--
    DECLARE @tZneTo varchar(30)	--2.--
    DECLARE @tAggCode  varchar(5)	--2.--
    DECLARE @tPplCode  varchar(5)	--2.--
    DECLARE @TTmpPrcPri TABLE 
    ( 
    --FTAggCode  varchar(5), /*Arm 63-06-08 Comment Code */
    --FTPghZneTo varchar(30), /*Arm 63-06-08 Comment Code */
    --FTPghBchTo varchar(5), /*Arm 63-06-08 Comment Code */
    FTPghDocNo varchar(20)
    , FTPplCode varchar(20)
    , FTPdtCode varchar(20)
    , FTPunCode varchar(5)
    , FDPghDStart datetime
    , FTPghTStart varchar(10)
    , FDPghDStop datetime
    , FTPghTStop varchar(10)
    , FTPghDocType varchar(1)
    , FTPghStaAdj varchar(1)
    , FCPgdPriceRet numeric(18, 4)
    --FCPgdPriceWhs numeric(18, 4), /*Arm 63-06-08 Comment Code */
    --FCPgdPriceNet numeric(18, 4), /*Arm 63-06-08 Comment Code */
    , FTPdtBchCode varchar(5)
    , FTPgdRmk varchar(200) --07.00.00--
    ) 
    DECLARE @tStaPrc varchar(1)		-- 6. --
    /*---------------------------------------------------------------------
    Document History
    version		Date			User	Remark
    02.01.00	23/03/2020		Em		create  
    02.02.00	08/06/2020		Arm     แก้ไข ยกเลิกฟิวด์
    04.01.00	08/10/2020		Em		แก้ไขกรณีข้อมูลซ้ำกัน
    05.01.00	11/05/2021		Em		แก้ไขเรื่อง Group ตาม PplCode ด้วย
    07.00.00	08/01/2022		Net		เพิ่ม Field Remark
    ----------------------------------------------------------------------*/
    BEGIN TRY
        --SET @tHQCode = ISNULL((SELECT TOP 1 FTBchCode FROM TCNMBranch WITH(NOLOCK) WHERE ISNULL(FTBchStaHQ, '') = '1' ), '')

        /*Arm 63-06-08 Comment Code */
        --SELECT TOP 1 @tAggCode = ISNULL(FTAggCode, '') , @tZneTo = ISNULL(FTXphZneTo, ''), @tBchTo = ISNULL(FTXphBchTo, '') 
        --, @tPplCode = ISNULL(FTPplCode, '') 
        --, @tStaPrc = ISNULL(FTXphStaPrcDoc, '')	-- 6. --
        --FROM TCNTPdtAdjPriHD WITH(NOLOCK) WHERE FTXphDocNo = @ptDocNo	--4.--
        
        /*Arm 63-06-08 Edit Code */
        SELECT TOP 1 @tPplCode = ISNULL(FTPplCode, '') 
        , @tStaPrc = ISNULL(FTXphStaPrcDoc, '')	-- 6. --
        FROM TCNTPdtAdjPriHD WITH(NOLOCK) 
        WHERE FTXphDocNo = @ptDocNo	--4.--
        /*Arm 63-06-08 End Edit Code */


        IF @tStaPrc <> '1'	-- 6. --
        BEGIN
            --INSERT INTO @TTmpPrcPri(FTAggCode, FTPghZneTo, FTPghBchTo, FTPplCode, FTPdtCode, FTPunCode, FDPghDStart, FTPghTStart, /*Arm 63-06-08 Comment Code */
            --FDPghDStop, FTPghTStop, FTPghDocNo, FTPghDocType, FTPghStaAdj, FCPgdPriceRet, FCPgdPriceWhs, FCPgdPriceNet, FTPdtBchCode) /*Arm 63-06-08 Comment Code */
            INSERT INTO @TTmpPrcPri
            (
                FTPplCode, FTPdtCode, FTPunCode, FDPghDStart, FTPghTStart
                , FDPghDStop, FTPghTStop, FTPghDocNo, FTPghDocType, FTPghStaAdj, FCPgdPriceRet, FTPdtBchCode
                , FTPgdRmk -- 07.00.00 --
            )
            -- SELECT DISTINCT ISNULL(HD.FTAggCode, '') AS FTAggCode, ISNULL(HD.FTXphZneTo, '') AS FTPghZneTo, ISNULL(HD.FTXphBchTo, '') AS FTPghBchTo, ISNULL(HD.FTPplCode, '') AS FTPplCode, /*Arm 63-06-08 Comment Code */
            SELECT DISTINCT ISNULL(HD.FTPplCode, '') AS FTPplCode
            , DT.FTPdtCode, DT.FTPunCode, HD.FDXphDStart, HD.FTXphTStart
            , HD.FDXphDStop, HD.FTXphTStop , HD.FTXphDocNo, HD.FTXphDocType, HD.FTXphStaAdj
            --DT.FCXpdPriceRet, DT.FCXpdPriceWhs, DT.FCXpdPriceNet, DT.FTXpdBchTo		--2.-- /*Arm 63-06-08 Comment Code */
            , DT.FCXpdPriceRet, DT.FTXpdBchTo		--2.--
            , ISNULL(HD.FTXphRmk, '') -- 07.00.00 --
            FROM TCNTPdtAdjPriDT DT WITH(NOLOCK)		--4.--
            INNER JOIN TCNTPdtAdjPriHD HD WITH(NOLOCK) ON 
                DT.FTBchCode = HD.FTBchCode AND DT.FTXphDocNo = HD.FTXphDocNo	--4.--
            WHERE HD.FTXphDocNo = @ptDocNo	-- 7. --

            -- 04.01.00 --
            DELETE TMP
            FROM @TTmpPrcPri TMP
            INNER JOIN TCNTPdtPrice4PDT PDT WITH(NOLOCK) ON 
                TMP.FTPdtCode = PDT.FTPdtCode AND TMP.FTPunCode = PDT.FTPunCode
                AND TMP.FDPghDStart = PDT.FDPghDStart AND TMP.FTPghTStart = PDT.FTPghTStart
                AND TMP.FTPplCode = PDT.FTPplCode	-- 05.01.00 --
                AND TMP.FTPghDocNo <= PDT.FTPghDocNo

            DELETE PDT
            FROM TCNTPdtPrice4PDT PDT
            INNER JOIN @TTmpPrcPri TMP ON 
                TMP.FTPdtCode = PDT.FTPdtCode AND TMP.FTPunCode = PDT.FTPunCode
                AND TMP.FDPghDStart = PDT.FDPghDStart AND TMP.FTPghTStart = PDT.FTPghTStart
                AND TMP.FTPplCode = PDT.FTPplCode	-- 05.01.00 --
                AND TMP.FTPghDocNo >= PDT.FTPghDocNo
            -- 04.01.00 --

            INSERT INTO TCNTPdtPrice4PDT
            (
                FTPdtCode, FTPunCode, FDPghDStart, FTPghTStart, FDPghDStop, FTPghTStop
                , FTPghDocNo, FTPghDocType, FTPghStaAdj, FCPgdPriceRet --FCPgdPriceWhs, FCPgdPriceNet, /*Arm 63-06-08 Comment Code */
                , FTPplCode
                , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                , FTPgdRmk -- 07.00.00 --
            )	-- 5.--
            SELECT FTPdtCode, FTPunCode, FDPghDStart, FTPghTStart, FDPghDStop, FTPghTStop
            , FTPghDocNo, FTPghDocType, FTPghStaAdj, FCPgdPriceRet --FCPgdPriceWhs, FCPgdPriceNet, 
            , FTPplCode
            , GETDATE(), @ptWho, GETDATE(), @ptWho	-- 5. --
            , FTPgdRmk -- 07.00.00 --
            FROM @TTmpPrcPri

        END	-- 6. --
        SET @FNResult= 0

    END TRY
    BEGIN CATCH
        --EXEC STP_MSGxWriteTSysPrcLog @ptComName, @ptWho, @ptDocNo , @tDate , @tTime
        SET @FNResult= -1
        select ERROR_MESSAGE()
    END CATCH
    GO
    IF EXISTS( SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxJob1RequestPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1 )
        DROP PROCEDURE [dbo].STP_DOCxJob1RequestPrc
    GO

    CREATE PROCEDURE [dbo].STP_DOCxJob1RequestPrc
        @ptBchCode varchar(5)
        , @ptDocNo varchar(30)
        , @ptWho varchar(100) 
        , @FNResult INT OUTPUT AS
    DECLARE @TTmpPrcStk TABLE 
    ( 
        FTBchCode varchar(5)
        , FTStkDocNo varchar(20)
        , FTStkType varchar(1)
        , FTStkSysType varchar(1)
        , FTPdtCode varchar(20)
        , FTPdtParent varchar(20)
        , FCStkQty decimal(18,2)
        , FTWahCode varchar(5)
        , FDStkDate Datetime
        , FCStkSetPrice decimal(18,2)
        , FCStkCostIn decimal(18,2)
        , FCStkCostEx decimal(18,2)
    ) 
    DECLARE @tStaPrc varchar(1)
    DECLARE @tStaPrcStkFrm varchar(1)
    DECLARE @tStaPrcStkTo varchar(1)
    DECLARE @tStaDoc varchar(1) --1 สมบูรณ์ 3ยกเลิก
    DECLARE @tTrans varchar(20)
    DECLARE @tWahCodeTo varchar(5) 
    /*---------------------------------------------------------------------
    Document History
    Version		Date			User	Remark
    07.00.00	19/11/2021		Net		create 
    07.01.00	23/11/2021		Net		ปรับการยกเลิก 
    07.02.00	24/11/2021		Net		ปรับการยกเลิก เอาจาก StockCard เลย
    ----------------------------------------------------------------------*/
    -- คลัง DT = ต้นทาง = คลังขาย
    -- โอนไปคลังปลายทาง = คลังจอง
    SET @tTrans = 'PrcJob1Req'
    BEGIN TRY
        BEGIN TRANSACTION @tTrans
        SET @tStaDoc = (SELECT TOP 1 FTXshStaDoc
                        FROM TSVTJob1ReqHD WITH(NOLOCK) 
                        WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo)

        IF @tStaDoc = '1' --เอกสารปกติ
        BEGIN
            SET @tStaPrc = (CASE WHEN (SELECT COUNT(*) AS FTXphStaPrcStk 
                                    FROM TSVTJob1ReqDT WITH(NOLOCK) 
                                    WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                        AND ISNULL(FTXsdStaPrcStk,'')<>'1' ) > 0
                                THEN '1' ELSE '2' END) -- 1ยังประมวลผลไม่หมด 2ประมวลผลหมดแล้ว

            
            -- ยังประมวลผล Stock ไม่ครบ
            IF @tStaPrc <> '2'	
            BEGIN
                
                --หาคลังจอง
                SET @tWahCodeTo = (SELECT TOP 1 FTWahCode
                                FROM TCNMWaHouse WAH WITH(NOLOCK)
                                WHERE WAH.FTBchCode = @ptBchCode AND ISNULL(FTWahStaType,'') = '7')
                
                --ถ้ามีไม่คลังจอง
                IF ISNULL(@tWahCodeTo, '') = '' 
                    THROW 50000, 'Wahouse not found', 0;
                
                -- ตัด Stk ออก คลังต้นทาง
                -- Create stk balance qty 0 ตัวที่ไม่เคยมี
                INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
                SELECT DISTINCT
                DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, 0 AS FCStkQty
                , GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
                FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
                    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
                -- Update ตัด Stk ออกจากคลังต้นทาง
                UPDATE STK WITH(ROWLOCK)
                SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
                , FDLastUpdOn = GETDATE()
                , FTLastUpdBy = @ptWho
                FROM TCNTPdtStkBal STK
                INNER JOIN (
                    SELECT DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
                    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                        DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                        AND ISNULL(DT.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    GROUP BY DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
                --End Update ตัด Stk ออกจากคลังต้นทาง

                -- เก็บตัวที่ตัด Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
                SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
                , '2' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
                , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
                , DT.FTPdtCode AS FTPdtCode
                , '' AS FTPdtParent
                , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
                , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
                , 0 AS FCStkCostIn
                , 0 AS FCStkCostEx
                FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                INNER JOIN TSVTJob1ReqDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCode, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้
                --End ตัด Stk ออก คลังต้นทาง
            



                -- ตัด Stk เข้า คลังปลายทาง 
                -- Create stk balance qty 0 ตัวที่ไม่เคยมี
                INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
                SELECT DISTINCT
                DT.FTBchCode, @tWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
                , GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
                FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode
                LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
                    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
                -- Update ตัด Stk เข้าคลังปลายทาง
                UPDATE STK WITH(ROWLOCK)
                SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
                , FDLastUpdOn = GETDATE()
                , FTLastUpdBy = @ptWho
                FROM TCNTPdtStkBal STK
                INNER JOIN (
                    SELECT DT.FTBchCode, @tWahCodeTo AS FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
                    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                        DT.FTBchCode = WAH.FTBchCode
                    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                        AND ISNULL(DT.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    GROUP BY DT.FTBchCode, DT.FTPdtCode
                ) DocStk  ON 
                    DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
                --End Update ตัด Stk เข้าคลังปลายทาง

                -- เก็บตัวที่ตัด Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
                SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
                , '1' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
                , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
                , DT.FTPdtCode AS FTPdtCode
                , '' AS FTPdtParent
                , SUM(DT.FCXsdQtyAll) AS FCStkQty, @tWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
                , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
                , 0 AS FCStkCostIn
                , 0 AS FCStkCostEx
                FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                INNER JOIN TSVTJob1ReqDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
                INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode
                WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้
                --End ตัด Stk เข้า คลังต้นปลายทาง 



                --Insert ลง Stock Card
                DELETE TCNTPdtStkCrd WITH(ROWLOCK)
                WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo

                INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
                (
                    FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                    , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                    , FDCreateOn, FTCreateBy
                )
                SELECT FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                    , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                    , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
                FROM @TTmpPrcStk
                --End Insert ลง Stock Card
            
            END
            --End ยังประมวลผล Stock ไม่ครบ
        END
        ELSE BEGIN --เอกสารยกเลิก
        
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                    WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                    WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                    WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                    ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
            WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, LTRIM(RTRIM(FTStkDocNo))+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                WHEN FTStkType='2' THEN '1'
                WHEN FTStkType='3' THEN '4'
                WHEN FTStkType='4' THEN '3'
                ELSE '5'
            END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode


        --  SET @tStaPrc = (CASE WHEN (SELECT COUNT(*) AS FTXphStaPrcStk 
    --                                FROM TSVTJob1ReqDT WITH(NOLOCK) 
    --                                WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
    --                                    AND ISNULL(FTXsdStaPrcStk,'')='1' ) > 0
    --                          THEN '1' ELSE '2' END) -- 1เคยตัด Stk ไปแล้ว 2ยังไม่เคยตัดStk

            
    --     -- เคยตัด Stk ไปแล้
        --  IF @tStaPrc <> '2'	
        --  BEGIN
                
    --         --หาคลังจอง
    --         SET @tWahCodeTo = (SELECT TOP 1 FTWahCode
    --                            FROM TCNMWaHouse WAH WITH(NOLOCK)
    --                            WHERE WAH.FTBchCode = @ptBchCode AND ISNULL(FTWahStaType,'') = '7')
                
    --         --ถ้ามีไม่คลังจอง
    --         IF ISNULL(@tWahCodeTo, '') = '' 
    --             THROW 50000, 'Wahouse not found', 0;
                
            --   -- ตัด Stk เข้า คลังต้นทาง
                ---- Create stk balance qty 0 ตัวที่ไม่เคยมี
                --INSERT INTO TCNTPdtStkBal
    --         (
    --             FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
    --         )
                --SELECT DISTINCT
    --         DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, 0 AS FCStkQty
                --, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
                --FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                --INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
    --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
    --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
    --             DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                --LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
    --             DT.FTBchCode = STK.FTBchCode AND DT.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
                --WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
                --    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
    --         --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
                ---- Update ตัด Stk เข้า คลังต้นทาง
                --UPDATE STK WITH(ROWLOCK)
                --SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
                --, FDLastUpdOn = GETDATE()
                --, FTLastUpdBy = @ptWho
                --FROM TCNTPdtStkBal STK
                --INNER JOIN (
    --             SELECT DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
                --    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                --    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
    --                 PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
    --             INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
    --                 DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                --    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
    --                 AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --    GROUP BY DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode
    --         ) DocStk  ON 
    --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
                ----End Update ตัด Stk เข้า คลังต้นทาง

    --         -- เก็บตัวที่ตัด Stk ไว้
    --         INSERT INTO @TTmpPrcStk
    --         (
    --             FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
    --             , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
    --         )
                --SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
                --, '1' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
    --         , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
                --, DT.FTPdtCode AS FTPdtCode
                --, '' AS FTPdtParent
                --, SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
                --, ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
                --, 0 AS FCStkCostIn
                --, 0 AS FCStkCostEx
                --FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                --INNER JOIN TSVTJob1ReqDT DT WITH(NOLOCK) ON
    --             HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
    --             AND HD.FTXshDocNo = DT.FTXshDocNo
                --INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
    --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
    --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
    --             DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
                --WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
    --             AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCode, HD.FDXshDocDate
    --         --End เก็บตัวที่ตัด Stk ไว้
            --   --End ตัด Stk ออก คลังต้นทาง
            



            --   -- ตัด Stk ออกคลังปลายทาง 
                ---- Create stk balance qty 0 ตัวที่ไม่เคยมี
                --INSERT INTO TCNTPdtStkBal
    --         (
    --             FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
    --         )
                --SELECT DISTINCT
    --         DT.FTBchCode, @tWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
                --, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
                --FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                --INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
    --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
    --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
    --             DT.FTBchCode = WAH.FTBchCode
                --LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
    --             DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
                --WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
    --             AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
                --    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
    --         --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
                ---- Update ตัด Stk ออกคลังปลายทาง
                --UPDATE STK WITH(ROWLOCK)
                --SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
                --, FDLastUpdOn = GETDATE()
                --, FTLastUpdBy = @ptWho
                --FROM TCNTPdtStkBal STK
                --INNER JOIN (
    --             SELECT DT.FTBchCode, @tWahCodeTo AS FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
                --    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                --    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
    --                 PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
    --             INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
    --                 DT.FTBchCode = WAH.FTBchCode
                --    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
    --                 AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --    GROUP BY DT.FTBchCode, DT.FTPdtCode
    --         ) DocStk  ON 
    --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
                ----End Update ตัด Stk ออกคลังปลายทาง

    --         -- เก็บตัวที่ตัด Stk ไว้
    --         INSERT INTO @TTmpPrcStk
    --         (
    --             FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
    --             , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
    --         )
                --SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
                --, '2' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
    --         , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
                --, DT.FTPdtCode AS FTPdtCode
                --, '' AS FTPdtParent
                --, SUM(DT.FCXsdQtyAll) AS FCStkQty, @tWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
                --, ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
                --, 0 AS FCStkCostIn
                --, 0 AS FCStkCostEx
                --FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                --INNER JOIN TSVTJob1ReqDT DT WITH(NOLOCK) ON
    --             HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
    --             AND HD.FTXshDocNo = DT.FTXshDocNo
                --INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
    --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
    --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
    --             DT.FTBchCode = WAH.FTBchCode
                --WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
    --             AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                --GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
    --         --End เก็บตัวที่ตัด Stk ไว้
            --   --End ตัด Stk เข้า คลังต้นปลายทาง 



            --   --Insert ลง Stock Card
            --   DELETE TCNTPdtStkCrd WITH(ROWLOCK)
            --   WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --   INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
    --         (
    --             FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
    --             , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
    --             , FDCreateOn, FTCreateBy
    --         )
            --   SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode, FTStkType, FTStkSysType
    --             , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
    --             , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            --   FROM @TTmpPrcStk
            --   --End Insert ลง Stock Card
            
        --  END
    --     --End เคยตัด Stk ไปแล้ว
        END
        

        
        COMMIT TRANSACTION @tTrans
        SET @FNResult= 0
        SELECT '' AS FTErrMsg
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION @tTrans
        SET @FNResult= -1
        SELECT ERROR_MESSAGE() AS FTErrMsg
    END CATCH
    GO
    IF EXISTS (SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxPrcStkPdtSet')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
        DROP PROCEDURE [dbo].STP_DOCxPrcStkPdtSet
    GO
    CREATE PROCEDURE [dbo].STP_DOCxPrcStkPdtSet
        @ptDocNo VARCHAR(25)
        , @pdStkDate DATETIME
        , @ptPdtCode VARCHAR(20)
        , @pcQty numeric(18, 4)
        , @ptStkType VARCHAR(1)	-- 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
        , @ptStkSysType VARCHAR(1)	-- ประเภทเอกสาร 1 : รับเข้า , 2 : ใบรับของ , 3 : โอนสินค้าระหว่างคลัง , 4 : ใบจอง , 5 : ใบจ่ายโอน --07.01.00--
        , @ptBchCode VARCHAR(5)
        , @ptWahCode VARCHAR(5)
        , @ptWho VARCHAR(50)
        , @FNResult INT OUTPUT AS
    DECLARE @tTrans VARCHAR(20)
    DECLARE @tStaPrcStkTo varchar(1)
    DECLARE @TTmpPrcStk TABLE 
    ( 
    FTComName varchar(50)
    , FTBchCode varchar(5)
    , FTStkDocNo varchar(25)
    , FTStkType varchar(1)
    , FTStkSysType varchar(1)
    , FTPdtCode varchar(20)
    , FCStkQty numeric(18, 4)
    , FTWahCode varchar(5)
    , FTPdtParent varchar(20)
    , FDStkDate Datetime
    , FCStkSetPrice numeric(18, 4)
    , FCStkCostIn numeric(18, 4)
    , FCStkCostEx numeric(18, 4)
    ) 
    /*---------------------------------------------------------------------
    Document History
    Version		Date			User	Remark
    05.01.00	29/10/2020		Em		create  
    06.01.00	08/08/2021		Em		แก้ไข Process ต้นทุน
    07.00.00    09/17/2021      Net     เพิ่มสินค้าชุดจากตาราง TSVTPdtSet
    07.01.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
    07.02.00	11/01/2022		Net		Trim DocNo
    ----------------------------------------------------------------------*/
    SET @tTrans = 'PrcStkSet'
    BEGIN TRY
        BEGIN TRANSACTION @tTrans 

        SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk, '') FROM TCNMWaHouse WAH WITH(NOLOCK)
                            WHERE WAH.FTBchCode = @ptBchCode AND WAH.FTWahCode = @ptWahCode)
        SET @ptDocNo = LTRIM(RTRIM(@ptDocNo)) --07.02.00--

        IF (@tStaPrcStkTo = '2')
        BEGIN
            --insert data to Temp
            INSERT INTO @TTmpPrcStk 
            (
                FTBchCode, FTStkDocNo, FTStkType, FTPdtCode, FCStkQty, FTWahCode, FDStkDate
                , FCStkSetPrice, FTPdtParent, FCStkCostIn, FCStkCostEx
                , FTStkSysType -- 07.01.00 --
            )
            SELECT @ptBchCode, @ptDocNo, @ptStkType, PS.FTPdtCodeSet
                , (PS.FCPstQty * PS.FCXsdFactor * @pcQty), @ptWahCode, @pdStkDate
                , 0 AS FCStkSetPrice, @ptPdtCode
                , 0 AS FCStkCostIn, 0 AS FCStkCostEx
                , @ptStkSysType -- 07.01.00 --
            FROM TCNTPdtSet PS WITH(NOLOCK)
            INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                PDT.FTPdtCode = PS.FTPdtCodeSet AND PDT.FTPdtStkControl = '1'
            WHERE PS.FTPdtCode = @ptPdtCode
            -- 07.01.00 --
            UNION ALL
            SELECT @ptBchCode, @ptDocNo, @ptStkType, PS.FTPdtCodeSub
                , (PS.FCPsvQty * PS.FCPsvFactor * @pcQty), @ptWahCode, @pdStkDate
                , 0 AS FCStkSetPrice, @ptPdtCode
                , 0 AS FCStkCostIn, 0 AS FCStkCostEx
                , @ptStkSysType
            FROM TSVTPdtSet PS WITH(NOLOCK)
            INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                PDT.FTPdtCode = PS.FTPdtCodeSub AND PDT.FTPdtStkControl = '1'
            WHERE PS.FTPdtCode = @ptPdtCode AND PS.FTPsvType = '1'
            -- 07.01.00 --

            INSERT INTO TCNTPdtStkBal
            (
                FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
            )
            SELECT DISTINCT TMP.FTBchCode, TMP.FTWahCode, TMP.FTPdtCode, 0 AS FCStkQty
                , GETDATE() AS FDLastUpdOn, @ptWho, GETDATE() AS FDCreateOn, @ptWho
            FROM @TTmpPrcStk TMP
            LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                TMP.FTBchCode = STK.FTBchCode AND TMP.FTWahCode = STK.FTWahCode AND TMP.FTPdtCode = STK.FTPdtCode
            WHERE ISNULL(STK.FTPdtCode, '') = ''	

            IF(@ptStkType = '1' OR @ptStkType = '4' OR @ptStkType = '5')
            BEGIN
                UPDATE STK WITH(ROWLOCK)
                SET FCStkQty = STK.FCStkQty + ISNULL(TMP.FCStkQty, 0)
                    , FDLastUpdOn = GETDATE()
                    , FTLastUpdBy = @ptWho
                FROM TCNTPdtStkBal STK
                INNER JOIN @TTmpPrcStk TMP ON 
                    TMP.FTBchCode = STK.FTBchCode AND TMP.FTWahCode = STK.FTWahCode AND TMP.FTPdtCode = STK.FTPdtCode	

                ---- 06.01.00 --
                ----Cost
                --UPDATE COST WITH(ROWLOCK)
                --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt, 0) + (TMP.FCStkQty*COST.FCPdtCostEx)
                --    , FCPdtQtyBal = FCPdtQtyBal + TMP.FCStkQty	
                --    , FDLastUpdOn = GETDATE()
                --FROM TCNMPdtCostAvg COST
                --INNER JOIN @TTmpPrcStk TMP ON 
    --             COST.FTPdtCode = TMP.FTPdtCode
                ---- 06.01.00 --
            END

            IF(@ptStkType = '2' OR @ptStkType = '3')
            BEGIN
                UPDATE STK WITH(ROWLOCK)
                SET FCStkQty = STK.FCStkQty - ISNULL(TMP.FCStkQty, 0)
                    , FDLastUpdOn = GETDATE()
                    , FTLastUpdBy = @ptWho
                FROM TCNTPdtStkBal STK
                INNER JOIN @TTmpPrcStk TMP ON 
                    TMP.FTBchCode = STK.FTBchCode AND TMP.FTWahCode = STK.FTWahCode AND TMP.FTPdtCode = STK.FTPdtCode	

                ---- 06.01.00 --
                ----Cost
                --UPDATE COST WITH(ROWLOCK)
                --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt, 0) - (TMP.FCStkQty*COST.FCPdtCostEx)
                --    , FCPdtQtyBal = FCPdtQtyBal - TMP.FCStkQty	
                --    , FDLastUpdOn = GETDATE()
                --FROM TCNMPdtCostAvg COST
                --INNER JOIN @TTmpPrcStk TMP ON 
    --             COST.FTPdtCode = TMP.FTPdtCode
                ---- 06.01.00 --
            END

            -- 07.01.00 --
            UPDATE COST
            SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
            ,FCPdtQtyBal = STK.FCStkQty
            ,FDLastUpdOn = GETDATE()
            FROM TCNMPdtCostAvg COST With(nolock)
            INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
            INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
                        FROM TCNTPdtStkBal STK with(nolock)
                        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
                            AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
                        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
            -- 07.01.00 --

            --insert to stock card
            INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FCStkQty
                , FCStkSetPrice, FTPdtParent, FCStkCostIn, FCStkCostEx, FDCreateOn, FTCreateBy
                , FTStkSysType -- 07.01.00 --
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FCStkQty
                , FCStkSetPrice, FTPdtParent, FCStkCostIn, FCStkCostEx
                , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
                , FTStkSysType -- 07.01.00 --
            FROM @TTmpPrcStk

        END


        COMMIT TRANSACTION @tTrans  
        SET @FNResult= 0
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION @tTrans
        SET @FNResult= -1
        SELECT ERROR_MESSAGE()
    END CATCH
    GO

IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPurchaseOrder')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPurchaseOrder
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPurchaseOrder
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) 
    , @FNResult INT OUTPUT AS

DECLARE @tTrans varchar(20)
DECLARE @tStaApv varchar(1) -- สถานะ ว่าง null ;รออนุมัติ  1:อนุมัติ(สร้างเอกสารแล้ว)

DECLARE @tAgnDoc varchar(10) --Agn เอกสารใบสั่งซื้อ
DECLARE @tBchDoc varchar(50) --สาขา เอกสารใบสั่งซื้อ
DECLARE @tPoDocNo varchar(30) --เลขที่ เอกสารใบสั่งซื้อ

DECLARE @tVatInOrExt varchar(1)
DECLARE @tVatCode varchar(5)
DECLARE @cVatRate numeric(18, 4)
DECLARE @tRteCode varchar(5)
DECLARE @cRteFac numeric(18, 4)

DECLARE	@nStoreRet int
DECLARE @tResult varchar(30)
DECLARE @dDateNow DATETIME

DECLARE @TTmpPoDocNo TABLE
(
    FTXshDocNo VARCHAR(30)
)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	22/09/2021		Net		create 
07.01.00	03/12/2021		Net		แก้ไข QtyLef  
07.02.00	13/12/2021		Net		ปรับราคาเป็น 0
07.03.00	24/01/2022		Net		แก้ไขการอ้างอิงเอกสารจาก PRS
----------------------------------------------------------------------*/
SET @tTrans = 'ReqBch'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @dDateNow = GETDATE()

    -- Get สถานะเอกสาร
    SELECT @tStaApv = ISNULL(HDDoc.FTXrhStaApv, '')
    FROM TAPTPoMgtHDDoc HDDoc WITH(NOLOCK)
    WHERE HDDoc.FTBchCode = @ptBchCode AND HDDoc.FTXpdDocPo = @ptDocNo

    
    -- Gen เอกสารเป็นของ สนญ
    SELECT @tAgnDoc = FTAgnCode, @tBchDoc = FTBchCode
    FROM TCNMBranch
    WHERE FTBchCode = @ptBchCode

    IF @tStaApv = '' AND @tBchDoc <> '' --ถ้าเป็นเอกสารรออนุมัติ
    BEGIN

        --Gen เลขที่เอกสาร ใบสั่งซื้อ
        INSERT @TTmpPoDocNo 
        EXEC @nStoreRet = [dbo].[SP_CNtAUTAutoDocNo]
            @ptTblName = N'TAPTPoHD'
		    , @ptDocType = N'2'
		    , @ptBchCode = @tBchDoc
		    , @ptShpCode = NULL
		    , @ptPosCode = NULL
		    , @pdDocDate = @dDateNow
		    , @ptResult = @tResult OUTPUT

        SET @tPoDocNo = (SELECT TOP 1 FTXshDocNo FROM @TTmpPoDocNo)


        --ถ้า Gen เลขที่เอกสารไม่ได้
        IF ISNULL(@tPoDocNo, '') = '' 
            THROW 50000, 'SP_CNtAUTAutoDocNo Error', 0;

        -- Get VatComp
        SELECT TOP 1 @tVatCode = VAT.FTVatCode, @cVatRate = VAT.FCVatRate
        , @tVatInOrExt = CMP.FTCmpRetInOrEx, @tRteCode = CMP.FTRteCode
        , @cRteFac = RTE.FCRteFraction
        FROM TCNMComp CMP WITH(NOLOCK)
        INNER JOIN(
            SELECT FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
        )VAT ON CMP.FTVatCode = VAT.FTVatCode
        INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
            CMP.FTRteCode = RTE.FTRteCode
        WHERE FNRank = 1

        -- Get VatAgn ถ้ามี
        IF ISNULL(@tAgnDoc, '') <> ''
        BEGIN
            SELECT TOP 1 @tVatCode = ISNULL(VAT.FTVatCode, @tVatCode)
            , @cVatRate = ISNULL(VAT.FCVatRate, @cVatRate)
            , @tVatInOrExt = ISNULL(AGN.FTCmpVatInOrEx, @tVatInOrExt)
            , @tRteCode = AGN.FTRteCode
            , @cRteFac = RTE.FCRteFraction
            FROM TCNMAgencySpc AGN WITH(NOLOCK)
            INNER JOIN(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT ON AGN.FTVatCode = VAT.FTVatCode
            INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
                AGN.FTRteCode = RTE.FTRteCode
            WHERE FNRank = 1 AND AGN.FTAgnCode = ISNULL(@tAgnDoc, '')
        END

        ---------- Gen เอกสาร ----------
        INSERT TAPTPoDT
        (
            FTBchCode, FTXphDocNo, FNXpdSeqNo, FTPdtCode, FTXpdPdtName
            , FTPunCode, FTPunName, FCXpdFactor, FTXpdBarCode, FTSrnCode
            , FTXpdVatType, FTVatCode, FCXpdVatRate, FTXpdSaleType, FCXpdSalePrice
            , FCXpdQty, FCXpdQtyAll, FCXpdSetPrice, FCXpdAmtB4DisChg, FTXpdDisChgTxt
            , FCXpdDis, FCXpdChg, FCXpdNet, FCXpdNetAfHD, FCXpdVat
            , FCXpdVatable, FCXpdWhtAmt, FTXpdWhtCode, FCXpdWhtRate, FCXpdCostIn
            , FCXpdCostEx, FCXpdQtyLef, FCXpdQtyRfn, FTXpdStaPrcStk, FTXpdStaAlwDis
            , FNXpdPdtLevel, FTXpdPdtParent, FCXpdQtySet, FTPdtStaSet, FTXpdRmk
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT @tBchDoc, @tPoDocNo, MDT.FNXppSeqNo, MDT.FTPdtCode, MDT.FTXpdPdtName
        , MDT.FTPunCode, MDT.FTPunName, MDT.FCXpdFactor, MDT.FTXpdBarCode, ''
        , PDT.FTPdtStaVat, ISNULL(PDT.FTVatCode, @tVatCode), ISNULL(VAT.FCVatRate, @cVatRate), PDT.FTPdtSaleType, 0
        , MDT.FCXpdQty, MDT.FCXpdQty*MDT.FCXpdFactor, 0, 0*MDT.FCXpdQty, ''
        , 0, 0, 0*MDT.FCXpdQty, 0*MDT.FCXpdQty
        , 0 AS FCXpdVat
        , 0 AS FCXpdVatable
        , 0, '', 0, 0, 0, MDT.FCXpdQty, 0, '', PDT.FTPdtStaAlwDis, 0, '', 0, '1', ''
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TAPTPoMgtHDDoc MHD WITH(NOLOCK)
        INNER JOIN TAPTPoMgtDT MDT WITH(NOLOCK) ON
            MHD.FTAgnCode = MDT.FTAgnCode AND MHD.FTBchCode = MDT.FTBchCode
            AND MHD.FTXphDocNo = MDT.FTXphDocNo AND MHD.FNXpdSeqNo = MDT.FNXpdSeqNo
        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
            MDT.FTPdtCode = PDT.FTPdtCode
        LEFT JOIN (
            SELECT FTVatCode, FCVatRate
            FROM(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT
            WHERE FNRank = 1
        )VAT ON PDT.FTVatCode = VAT.FTVatCode
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXpdDocPo = @ptDocNo

        --07.03.00--
        INSERT TAPTPoHDDocRef
        (
            FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate
        )
        SELECT TOP 1 @tAgnDoc, @tBchDoc, @tPoDocNo, MHD.FTXrhDocRqSpl, '1', 'PRS', GETDATE()
        FROM TAPTPoMgtHDDoc MHD WITH(NOLOCK)
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXpdDocPo = @ptDocNo

        INSERT TCNTPdtReqSplHDDocRef
        (
            FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefType, FTXshRefDocNo, FTXshRefKey, FDXshRefDocDate
        )
        SELECT TOP 1 PRS.FTAgnCode, PRS.FTBchCode, PRS.FTXphDocNo, '2', @tPoDocNo, 'PO', GETDATE()
        FROM TCNTPdtReqSplHD PRS WITH(NOLOCK)
        INNER JOIN TAPTPoMgtHDDoc MHD WITH(NOLOCK) ON
            PRS.FTXphDocNo = MHD.FTXrhDocRqSpl
        WHERE MHD.FTXpdDocPo = @ptDocNo
        --07.03.00--

        INSERT TAPTPoHDSpl
        (
            FTBchCode, FTXphDocNo, FTXphDstPaid, FNXphCrTerm, FDXphDueDate
            , FDXphBillDue, FTXphCtrName, FDXphTnfDate, FTXphRefTnfID, FTXphRefVehID
            , FTXphRefInvNo, FTXphQtyAndTypeUnit, FNXphShipAdd, FNXphTaxAdd
        )
        SELECT DISTINCT @tBchDoc, @tPoDocNo, '2', NULL, NULL
        , NULL, NULL, NULL, NULL, NULL
        , NULL, NULL, NULL, NULL
        FROM TAPTPoMgtHDDoc MHD WITH(NOLOCK)
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXpdDocPo = @ptDocNo


        
        INSERT TAPTPoHD
        (
            FTBchCode, FTXphDocNo, FTShpCode, FNXphDocType, FDXphDocDate
            , FTXphCshOrCrd, FTXphVATInOrEx, FTXphBchTo, FTDptCode, FTWahCode
            , FTUsrCode, FTXphApvCode, FTSplCode, FTXphRefExt, FDXphRefExtDate
            , FTXphRefInt, FDXphRefIntDate, FTXphRefAE, FNXphDocPrint, FTRteCode
            , FCXphRteFac, FCXphTotal, FCXphTotalNV, FCXphTotalNoDis, FCXphTotalB4DisChgV
            , FCXphTotalB4DisChgNV, FTXphDisChgTxt, FCXphDis, FCXphChg, FCXphTotalAfDisChgV
            , FCXphTotalAfDisChgNV, FCXphRefAEAmt, FCXphAmtV, FCXphAmtNV, FCXphVat
            , FCXphVatable, FTXphWpCode, FCXphWpTax, FCXphGrand, FCXphRnd
            , FTXphGndText, FCXphPaid, FCXphLeft, FTXphRmk, FTXphStaRefund
            , FTXphStaDoc, FTXphStaApv, FTXphStaPrcDoc, FTXphStaPaid, FNXphStaDocAct, FNXphStaRef
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT @tBchDoc, @tPoDocNo, '', 2, GETDATE()
        , '1', @tVatInOrExt, MHD.FTXrhBchTo, '', BCHTo.FTWahCode
        , @ptWho, '', MHD.FTSplCode, '', NULL
        , '', NULL, '', 0, @tRteCode
        , @cRteFac, PODT.FCXpdNetAfHD, PODT.FCXphTotalNV, PODT.FCXphTotalNoDis, PODT.FCXphTotalB4DisChgV
        , PODT.FCXphTotalB4DisChgNV, '', 0, 0, PODT.FCXphTotalB4DisChgV
        , PODT.FCXphTotalB4DisChgNV, 0, (PODT.FCXpdNetAfHD-PODT.FCXphTotalNV-(PODT.FCXphTotalB4DisChgV-PODT.FCXphTotalB4DisChgV))
        , (PODT.FCXphTotalNV-(PODT.FCXphTotalB4DisChgNV-PODT.FCXphTotalB4DisChgNV)), PODT.FCXthVat
        , PODT.FCXthVatable, '', 0, PODT.FCXpdNetAfHD, 0, '', 0, PODT.FCXpdNetAfHD, '', '1'
        , '1', '', '', '1', 1, 0
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TAPTPoMgtHDDoc MHD WITH(NOLOCK)
        INNER JOIN (
            SELECT FTBchCode, FTXphDocNo, @ptDocNo AS FTXpdDocPo
            , SUM(FCXpdNet) AS FCXpdNet
            , SUM(FCXpdNetAfHD) AS FCXpdNetAfHD
            , SUM(CASE WHEN FTXpdVatType='2' THEN FCXpdNetAfHD ELSE 0 END) AS FCXphTotalNV
            , SUM(CASE WHEN FTXpdStaAlwDis='2' THEN FCXpdNetAfHD ELSE 0 END) AS FCXphTotalNoDis
            , SUM(CASE WHEN FTXpdStaAlwDis='1' AND FTXpdVatType='1' THEN FCXpdNetAfHD ELSE 0 END) AS FCXphTotalB4DisChgV
            , SUM(CASE WHEN FTXpdStaAlwDis='1' AND FTXpdVatType='2' THEN FCXpdNetAfHD ELSE 0 END) AS FCXphTotalB4DisChgNV
            , SUM(FCXpdVat) AS FCXthVat
            , SUM(FCXpdVatable) AS FCXthVatable
            FROM TAPTPoDT WITH(NOLOCK)
            WHERE FTBchCode = @tBchDoc AND FTXphDocNo = @tPoDocNo
            GROUP BY FTBchCode, FTXphDocNo
        )PODT ON
            -- Gen เอกสารเป็นของ สนญ
            --MHD.FTXrhAgnFrm = RDT.FTAgnCode AND MHD.FTXrhRefFrm = RDT.FTBchCode 
            --AND MHD.FTXphDocNo = RDT.FTXrhDocPrBch
            MHD.FTBchCode = PODT.FTBchCode AND MHD.FTXpdDocPo = PODT.FTXpdDocPo
        INNER JOIN TCNMBranch BCHTo WITH(NOLOCK) ON
            MHD.FTXrhBchTo = BCHTo.FTBchCode
        WHERE MHD.FTBchCode = @ptBchCode AND MHD.FTXpdDocPo = @ptDocNo

        IF (SELECT COUNT(*) FROM TAPTPoHD WHERE FTXphDocNo=@tPoDocNo) <= 0 OR (SELECT COUNT(*) FROM TAPTPoDT WHERE FTXphDocNo=@tPoDocNo) <= 0
            THROW 50000, 'Gen Doc Empty', 0;

        ---------- End Gen เอกสาร ----------

    END --End ถ้าเป็นเอกสาร ใบสั่งซื้อ และยังไม่ประมวลผล
    
    SELECT @tPoDocNo AS FTPoDocNo, '' AS FTErrMsg

	COMMIT TRANSACTION @tTrans
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
	SELECT '' AS FTPoDocNo, ERROR_MESSAGE() AS FTErrMsg
END CATCH
GO


IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxSvBookPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxSvBookPrc
GO

CREATE PROCEDURE [dbo].STP_DOCxSvBookPrc
     @ptBchCode varchar(5)
    ,@ptDocNo varchar(30)
    ,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @TTmpPrcStk TABLE 
( 
   FTBchCode varchar(5),
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTStkSysType varchar(1), 
   FTPdtCode varchar(20), 
   FTPdtParent varchar(20), 
   FCStkQty decimal(18,2), 
   FTWahCode varchar(5), 
   FDStkDate Datetime,
   FCStkSetPrice decimal(18,2),
   FCStkCostIn decimal(18,2),
   FCStkCostEx decimal(18,2)
) 
DECLARE @tStaPrc varchar(1)
DECLARE @tStaPrcStkFrm varchar(1)
DECLARE @tStaPrcStkTo varchar(1)
DECLARE @tStaDoc varchar(1) --1 สมบูรณ์ 3ยกเลิก
DECLARE @tTrans varchar(20)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	15/09/2021		Net		create 
07.01.00	16/12/2021		Net		create 
----------------------------------------------------------------------*/
SET @tTrans = 'PrcBook'
BEGIN TRY
	BEGIN TRANSACTION @tTrans
	SET @tStaDoc = (SELECT TOP 1 FTXshStaDoc
                      FROM TSVTBookHD WITH(NOLOCK) 
                      WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo)

    IF @tStaDoc = '1' --เอกสารปกติ
    BEGIN
	    SET @tStaPrc = (CASE WHEN (SELECT COUNT(*)
                                   FROM(
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDT WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')=''
                                       UNION ALL
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDTSet WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')=''
                                    )Doc
                                  ) > 0
                             THEN '1' ELSE '2' END)
        
        -- ยังประมวลผล Stock ไม่ครบ
	    IF @tStaPrc = '1'	
	    BEGIN
            -- สถานะการตัด Stk ของคลัง
		    SET @tStaPrcStkFrm = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') 
                                  FROM TCNMWaHouse WAH WITH(NOLOCK)
						          INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON 
                                      DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCodeFrm = WAH.FTWahCode
						          WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo)

		    SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') 
                                  FROM TCNMWaHouse WAH WITH(NOLOCK)
						          INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON 
                                      DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCodeTo = WAH.FTWahCode
						          WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo)
            --End สถานะการตัด Stk ของคลัง


        
		    -- คลังต้นทาง ตัด Stk
		    IF @tStaPrcStkFrm = '2'
		    BEGIN
            
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeFrm, DT.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode,'') = ''
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeFrm, DTSet.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DTSet.FTPdtCode
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeFrm = STK.FTWahCode AND DTSet.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(STK.FTPdtCode,'') = ''
                    AND ISNULL(PDT.FTPdtStkControl,'') = '1'
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
                --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
            
                -- เก็บตัวที่ตัด Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '2' AS FTStkType, '4' AS FTStkSysType
			        , DT.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCodeFrm AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCodeFrm, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้
            
                -- เก็บตัวที่ตัด Stk ไว้ ตัวลูก
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '2' AS FTStkType, '4' AS FTStkSysType
			        , DTSet.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DT.FTWahCodeFrm AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , 0 AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DTSet.FTXsdStaPrcStk,'') = ''
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DTSet.FTPdtCode, DT.FTWahCodeFrm, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้

			    -- Update ตัด Stk ออกจากคลังต้นทาง
			    UPDATE STK WITH(ROWLOCK)
			    SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCStkQty,0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
                INNER JOIN @TTmpPrcStk DocStk ON
                    STK.FTBchCode = DocStk.FTBchCode AND STK.FTWahCode = DocStk.FTWahCode
                    AND STK.FTPdtCode = DocStk.FTPdtCode
			    --INNER JOIN (
       --             SELECT DT.FTBchCode, DT.FTWahCodeFrm, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			    --    FROM TSVTBookDT DT WITH(NOLOCK)
			    --    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
       --                 PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    --    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
       --                 AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			    --    GROUP BY DT.FTBchCode, DT.FTWahCodeFrm, DT.FTPdtCode
       --         ) DocStk  ON 
       --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeFrm = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    --End Update ตัด Stk ออกจากคลังต้นทาง
            
			    ---- Update ตัด Stk ออกจากคลังต้นทาง ตัวลูก
			    --UPDATE STK WITH(ROWLOCK)
			    --SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
			    --, FDLastUpdOn = GETDATE()
			    --, FTLastUpdBy = @ptWho
			    --FROM TCNTPdtStkBal STK
			    --INNER JOIN (
       --             SELECT DT.FTBchCode, DT.FTWahCodeFrm, DTSet.FTPdtCode, SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			    --    FROM TSVTBookDT DT WITH(NOLOCK)
			    --    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
       --                 DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
       --                 AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    --    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
       --                 PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    --    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
       --                 PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    --    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
       --                 AND ISNULL(DTSet.FTXsdStaPrcStk,'') = '' AND ISNULL(DTSet.FTPsvType,'') = '1'
			    --    GROUP BY DT.FTBchCode, DT.FTWahCodeFrm, DTSet.FTPdtCode
       --         ) DocStk  ON 
       --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeFrm = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    ----End Update ตัด Stk ออกจากคลังต้นทาง ตัวลูก

                
                -- เพิ่มเก็บตัวที่ตัด Stk แล้ว
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '2' AS FTStkType, '4' AS FTStkSysType
			        , DT.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCodeFrm AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCodeFrm, HD.FDXshDocDate
                --End เก็บตัวที่ตัด Stk ไว้
            
                -- เพิ่มเก็บตัวที่ตัด Stk ตัวลูกแล้ว
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '2' AS FTStkType, '4' AS FTStkSysType
			        , DTSet.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DT.FTWahCodeFrm AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , 0 AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DTSet.FTXsdStaPrcStk,'') = '1'
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DTSet.FTPdtCode, DT.FTWahCodeFrm, HD.FDXshDocDate
                --End เพิ่มเก็บตัวที่ตัด Stk ตัวลูกแล้ว
            

            END
		    --End คลังต้นทาง ตัด Stk
        
		    -- คลังต้นปลายทาง ตัด Stk
            IF @tStaPrcStkTo = '2'
		    BEGIN
            
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode,'') = ''
			    --End Create stk balance qty 0 ตัวที่ไม่เคยมี
                
			    -- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT
                    DT.FTBchCode, DT.FTWahCodeTo, DTSet.FTPdtCode, 0 AS FCStkQty
			        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
			    FROM TSVTBookDT DT WITH(NOLOCK)
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' 
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    DT.FTBchCode = STK.FTBchCode AND DT.FTWahCodeTo = STK.FTWahCode AND DTSet.FTPdtCode = STK.FTPdtCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode,'') = ''
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
                
                -- เก็บตัวที่เพิ่ม Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '1' AS FTStkType, '4' AS FTStkSysType
			        , DT.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCodeTo, HD.FDXshDocDate
                --End เก็บตัวที่เพิ่ม Stk ไว้
                
                -- เก็บตัวที่เพิ่ม Stk ไว้ ตัวลูก
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '1' AS FTStkType, '4' AS FTStkSysType
			        , DTSet.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DT.FTWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , 0 AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DTSet.FTPdtCode, DT.FTWahCodeTo, HD.FDXshDocDate
                --End เก็บตัวที่เพิ่ม Stk ไว้ ตัวลูก

                -- Update เพิ่ม Stk เข้าคลังปลายทาง
			    UPDATE STK WITH(ROWLOCK)
			    SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCStkQty,0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
                INNER JOIN @TTmpPrcStk DocStk ON
                    STK.FTBchCode = DocStk.FTBchCode AND STK.FTWahCode = DocStk.FTWahCode
                    AND STK.FTPdtCode = DocStk.FTPdtCode
			    --INNER JOIN (
       --             SELECT DT.FTBchCode, DT.FTWahCodeTo, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			    --    FROM TSVTBookDT DT WITH(NOLOCK)
			    --    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
       --                 PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    --    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
       --                 AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			    --    GROUP BY DT.FTBchCode, DT.FTWahCodeTo, DT.FTPdtCode
       --         ) DocStk  ON 
       --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeTo = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    ----End Update เพิ่ม Stk เข้าคลังปลายทาง
                
       --         -- Update เพิ่ม Stk เข้าคลังปลายทาง ตัวลูก
			    --UPDATE STK WITH(ROWLOCK)
			    --SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
			    --, FDLastUpdOn = GETDATE()
			    --, FTLastUpdBy = @ptWho
			    --FROM TCNTPdtStkBal STK
			    --INNER JOIN (
       --             SELECT DT.FTBchCode, DT.FTWahCodeTo, DTSet.FTPdtCode, SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			    --    FROM TSVTBookDT DT WITH(NOLOCK)
			    --    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
       --                 DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
       --                 AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    --    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
       --                 PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' 
			    --    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
       --                 PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    --    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
       --                 AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
       --                 AND ISNULL(DTSet.FTPsvType,'') = '1'
			    --    GROUP BY DT.FTBchCode, DT.FTWahCodeTo, DTSet.FTPdtCode
       --         ) DocStk  ON 
       --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCodeTo = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			    ----End Update เพิ่ม Stk เข้าคลังปลายทาง ตัวลูก
                
                -- เก็บตัวที่เพิ่ม Stk ไว้
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '1' AS FTStkType, '4' AS FTStkSysType
			        , DT.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1' --AND PDT.FTPdtStaAlwBook='1'
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCodeTo, HD.FDXshDocDate
                --End เก็บตัวที่เพิ่ม Stk ไว้
                
                -- เก็บตัวที่เพิ่ม Stk ไว้ ตัวลูก
                INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                    , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			        , '1' AS FTStkType, '4' AS FTStkSysType
			        , DTSet.FTPdtCode AS FTPdtCode
			        , '' AS FTPdtParent
			        , SUM(DT.FCXsdQty*DTSet.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DT.FTWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			        , 0 AS FCStkSetPrice
			        , 0 AS FCStkCostIn
			        , 0 AS FCStkCostEx
			    FROM TSVTBookHD HD WITH(NOLOCK)
			    INNER JOIN TSVTBookDT DT WITH(NOLOCK) ON
                    HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                    AND HD.FTXshDocNo = DT.FTXshDocNo
			    INNER JOIN TSVTBookDTSet DTSet WITH(NOLOCK) ON
                    DT.FTBchCode = DTSet.FTBchCode AND DT.FTXshDocNo = DTSet.FTXshDocNo
                    AND CONVERT(INT,DT.FTXsdSeq) = DTSet.FNXsdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DTSet.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    PKS.FTPdtCode = DTSet.FTPdtCode AND PKS.FTPunCode = DTSet.FTPunCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
                    AND ISNULL(DTSet.FTPsvType,'') = '1'
			    GROUP BY DT.FTBchCode, DT.FTXshDocNo, DTSet.FTPdtCode, DT.FTWahCodeTo, HD.FDXshDocDate
                --End เก็บตัวที่เพิ่ม Stk ไว้ ตัวลูก

            END
		    --End คลังต้นปลายทาง ตัด Stk
        
		    --Insert ลง Stock Card
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo

            
		    INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , FDCreateOn, FTCreateBy
            )
		    SELECT FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk
		    --End Insert ลง Stock Card

	    END
        --End ยังประมวลผล Stock ไม่ครบ
    END
    ELSE BEGIN --เอกสารยกเลิก
        
	    SET @tStaPrc = (CASE WHEN (SELECT COUNT(*)
                                   FROM(
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDT WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')='1'
                                       UNION ALL
                                       SELECT FTXshDocNo
                                       FROM TSVTBookDTSet WITH(NOLOCK) 
                                       WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                           AND ISNULL(FTXsdStaPrcStk,'')='1'
                                    )Doc
                                  ) > 0
                             THEN '1' ELSE '2' END)

        
        -- ยังประมวลผล Stock ไม่ครบ
	    IF @tStaPrc = '1'	
	    BEGIN
            
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    
		
	
	COMMIT TRANSACTION @tTrans
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
	SELECT ERROR_MESSAGE()
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxWahPdtTnfVD')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxWahPdtTnfVD
GO
CREATE PROCEDURE [dbo].STP_DOCxWahPdtTnfVD
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tTrans VARCHAR(20)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTBchCode varchar(5),
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   --FCStkQty decimal(18,2), 
   FCStkQty decimal(18,4),   -- 07.01.00 --
   FTWahCode varchar(5), 
   FDStkDate Datetime,
   --FCStkSetPrice decimal(18,2),
   --FCStkCostIn decimal(18,2),
   --FCStkCostEx decimal(18,2)
   FCStkSetPrice decimal(18,4), -- 07.01.00 --
   FCStkCostIn decimal(18,4), -- 07.01.00 --
   FCStkCostEx decimal(18,4) -- 07.01.00 --
   ) 
DECLARE @tStaPrc varchar(1)	
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.01.00 --
DECLARE @tStaDoc varchar(1) -- 07.01.00 -- 1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
00.01.00	22/07/2019		Em		create  
00.02.00	30/07/2019		Em		เพิ่มอัพเดทต้นทุน
00.03.00	25/10/2019		Em		เพิ่มการตรวจสอบรายการสินค้าที่ไม่มีใน Layout
00.04.00	04/11/2019		Em		เพิ่มการตรวจสอบรายการสินค้าที่ไม่มี PdtCode
02.00.00	05/02/2020		Em		เปลี่ยนไปใช้ WahCode ที่ DT
02.01.00	06/02/2020		Em		เพิ่มฟิลด์ FNCabSeq
02.02.00	10/03/2020		Em		แก้ไขกรณี Insert Qty เป็น 0
02.03.00	07/08/2020		Em		แก้ไขให้ sum Qty
02.04.00	28/08/2020		Em		เพิ่มเงื่อนไขการ join 
02.05.00	10/09/2020		Em		แก้ไขเงื่อนไขการ join คลังสินค้า
04.01.00	20/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
07.00.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcWahTnfVD'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  
	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk,'') AS FTXthStaPrcStk FROM TVDTPdtTwxHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)
	SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXthStaDoc,'') AS FTXthStaPrcStk FROM TVDTPdtTwxHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) -- 07.01.00 --
    
    -- 07.01.00 --            
    IF @tStaDoc = '1' -- เอกสารปกติ
    BEGIN
	    IF @tStaPrc <> '1'
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    --Update Stock In
		    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
		    SET FCStkQty = FCStkQty + ISNULL(Twx.FCXtdQtyAll,0)
		    ,FDLastUpdOn = GETDATE()
		    ,FTLastUpdBy = @ptWho
		    FROM TCNTPdtStkBal STK
		    --INNER JOIN (SELECT HD.FTBchCode,HD.FTXthWhTo, DT.FTPdtCode ,SUM(DT.FCXtdQty) AS FCXtdQtyAll
		    INNER JOIN (SELECT HD.FTBchCode,DT.FTXthWhTo, DT.FTPdtCode ,SUM(DT.FCXtdQty) AS FCXtdQtyAll	-- 5. --
				    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
				    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
				    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = DT.FTBchCode AND WAH.FTWahCode = DT.FTXthWhTo AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
				    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
				    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
				    --GROUP BY HD.FTBchCode, HD.FTXthWhTo, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhTo = STK.FTWahCode AND Twx.FTPdtCode = STK.FTPdtCode
				    GROUP BY HD.FTBchCode, DT.FTXthWhTo, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhTo = STK.FTWahCode AND Twx.FTPdtCode = STK.FTPdtCode	-- 5. --

		    --Update Stock In Vending
		    UPDATE TVDTPdtStkBal WITH(ROWLOCK)
		    SET FCStkQty = FCStkQty + ISNULL(Twx.FCXtdQtyAll,0)
		    ,FDLastUpdOn = GETDATE()
		    ,FTLastUpdBy = @ptWho
		    FROM TVDTPdtStkBal STK
		    --INNER JOIN (SELECT HD.FTBchCode, HD.FTXthWhTo, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode, SUM(DT.FCXtdQty) AS FCXtdQtyAll
		    INNER JOIN (SELECT HD.FTBchCode, DT.FTXthWhTo, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode, SUM(DT.FCXtdQty) AS FCXtdQtyAll	-- 5. --	-- 6. --
				    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
				    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
				    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTXthWhTo = WAH.FTWahCode AND WAH.FTWahStaType = '6'
				    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON DT.FTXthWhTo = WAH.FTWahCode AND WAH.FTWahStaType = '6'	-- 5. --
				    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND DT.FTXthWhTo = WAH.FTWahCode AND WAH.FTWahStaType = '6'	-- 9. --
						    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
				    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
				    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
				    --GROUP BY HD.FTBchCode, HD.FTXthWhTo, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhTo = STK.FTWahCode 
				    GROUP BY HD.FTBchCode, DT.FTXthWhTo, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhTo = STK.FTWahCode	-- 5. --	-- 6. --
				    AND Twx.FTPdtCode = STK.FTPdtCode AND Twx.FNLayRow = STK.FNLayRow AND Twx.FNLayCol = STK.FNLayCol
				    AND Twx.FNCabSeq = STK.FNCabSeq	-- 6. --

		    --Update Stock Out
		    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
		    SET FCStkQty = FCStkQty - ISNULL(Twx.FCXtdQtyAll,0)
		    ,FDLastUpdOn = GETDATE()
		    ,FTLastUpdBy = @ptWho
		    FROM TCNTPdtStkBal STK
		    --INNER JOIN (SELECT HD.FTBchCode, HD.FTXthWhFrm, DT.FTPdtCode ,SUM(DT.FCXtdQty) AS FCXtdQtyAll
		    INNER JOIN (SELECT HD.FTBchCode, DT.FTXthWhFrm, DT.FTPdtCode ,SUM(DT.FCXtdQty) AS FCXtdQtyAll	-- 5. --
		    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
		    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = DT.FTBchCode AND WAH.FTWahCode = DT.FTXthWhFrm AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    --GROUP BY HD.FTBchCode, HD.FTXthWhFrm, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhFrm = STK.FTWahCode AND Twx.FTPdtCode = STK.FTPdtCode
		    GROUP BY HD.FTBchCode, DT.FTXthWhFrm, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhFrm = STK.FTWahCode AND Twx.FTPdtCode = STK.FTPdtCode	-- 5. --

		    --Update Stock Out
		    UPDATE TVDTPdtStkBal WITH(ROWLOCK)
		    SET FCStkQty = FCStkQty - ISNULL(Twx.FCXtdQtyAll,0)
		    ,FDLastUpdOn = GETDATE()
		    ,FTLastUpdBy = @ptWho
		    FROM TVDTPdtStkBal STK
		    --INNER JOIN (SELECT HD.FTBchCode, HD.FTXthWhFrm, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode ,SUM(DT.FCXtdQty) AS FCXtdQtyAll
		    INNER JOIN (SELECT HD.FTBchCode, DT.FTXthWhFrm, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode ,SUM(DT.FCXtdQty) AS FCXtdQtyAll	-- 6. --
		    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
		    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
		    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTXthWhFrm = WAH.FTWahCode AND WAH.FTWahStaType = '6'
		    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON DT.FTXthWhFrm = WAH.FTWahCode AND WAH.FTWahStaType = '6'	-- 5. --
		    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND DT.FTXthWhTo = WAH.FTWahCode AND WAH.FTWahStaType = '6'	-- 9. --
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND DT.FTXthWhFrm = WAH.FTWahCode AND WAH.FTWahStaType = '6'	-- 10. --
				    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    --GROUP BY HD.FTBchCode, HD.FTXthWhFrm, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhFrm = STK.FTWahCode 
		    GROUP BY HD.FTBchCode, DT.FTXthWhFrm, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode) Twx  ON Twx.FTBchCode = STK.FTBchCode AND Twx.FTXthWhFrm = STK.FTWahCode	-- 5. --	-- 6. --
		    AND Twx.FTPdtCode = STK.FTPdtCode AND Twx.FNLayRow = STK.FNLayRow AND Twx.FNLayCol = STK.FNLayCol
		    AND Twx.FNCabSeq = STK.FNCabSeq	-- 6. --

		    --Create stk balance
		    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		    --SELECT DISTINCT HD.FTBchCode,HD.FTXthWhFrm,DT.FTPdtCode,0 AS FCStkQty,
		    --SELECT DISTINCT HD.FTBchCode,DT.FTXthWhFrm,DT.FTPdtCode,0 AS FCStkQty,	-- 5. --
		    --SELECT DISTINCT HD.FTBchCode,DT.FTXthWhFrm,DT.FTPdtCode,ISNULL(DT.FCXtdQty,0) AS FCStkQty,
		    SELECT HD.FTBchCode,DT.FTXthWhFrm,DT.FTPdtCode,SUM(ISNULL(DT.FCXtdQty,0)) AS FCStkQty,		-- 8. --
		    GETDATE() AS FDLastUpd,HD.FTLastUpdBy,
		    GETDATE() AS FDCreateOn,HD.FTCreateBy
		    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
		    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = DT.FTBchCode AND WAH.FTWahCode = DT.FTXthWhFrm AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    --LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
		    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND DT.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode		-- 5. --
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(DT.FTPdtCode,'') <> ''	AND ISNULL(STK.FTPdtCode,'') = '' -- 3. --  -- 4. --
		    GROUP BY HD.FTBchCode,DT.FTXthWhFrm,DT.FTPdtCode,HD.FTLastUpdBy,HD.FTCreateBy	-- 8. --

		    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		    --SELECT DISTINCT HD.FTBchCode,HD.FTXthWhTo,DT.FTPdtCode,0 AS FCStkQty,
		    --SELECT DISTINCT HD.FTBchCode,DT.FTXthWhTo,DT.FTPdtCode,0 AS FCStkQty,	-- 5. --
		    --SELECT DISTINCT HD.FTBchCode,DT.FTXthWhTo,DT.FTPdtCode,ISNULL(DT.FCXtdQty,0) AS FCStkQty,	-- 7. --
		    SELECT HD.FTBchCode,DT.FTXthWhTo,DT.FTPdtCode,SUM(ISNULL(DT.FCXtdQty,0)) AS FCStkQty,	-- 8. --
		    GETDATE() AS FDLastUpdOn,HD.FTLastUpdBy,
		    GETDATE() AS FDCreateOn,HD.FTCreateBy
		    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
		    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = DT.FTBchCode AND WAH.FTWahCode = DT.FTXthWhTo AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    --LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
		    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND DT.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode		-- 5. --
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(DT.FTPdtCode,'') <> ''	AND ISNULL(STK.FTPdtCode,'') = '' 	-- 3. --  -- 4. --
		    GROUP BY HD.FTBchCode,DT.FTXthWhTo,DT.FTPdtCode,HD.FTLastUpdBy,HD.FTCreateBy	-- 8. --

		    --Create stk balance Vending
		    INSERT INTO TVDTPdtStkBal(FTBchCode, FTWahCode, FNCabSeq, FNLayRow, FNLayCol, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)	-- 6. --
		    --SELECT DISTINCT HD.FTBchCode,HD.FTXthWhFrm, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode,0 AS FCStkQty,
		    --SELECT DISTINCT HD.FTBchCode,DT.FTXthWhFrm, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode,0 AS FCStkQty,		-- 5. --	-- 6. --
		    SELECT DISTINCT HD.FTBchCode,DT.FTXthWhFrm, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode,ISNULL(DT.FCXtdQty,0) AS FCStkQty,	-- 7. --
		    GETDATE() AS FDLastUpd,HD.FTLastUpdBy,
		    GETDATE() AS FDCreateOn,HD.FTCreateBy
		    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
		    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
		    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTXthWhFrm = WAH.FTWahCode AND WAH.FTWahStaType = '6'
		    --LEFT JOIN TVDTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
		    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON DT.FTXthWhFrm = WAH.FTWahCode AND WAH.FTWahStaType = '6'		-- 5. --
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND DT.FTXthWhFrm = WAH.FTWahCode AND WAH.FTWahStaType = '6'	-- 9. --
				    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    LEFT JOIN TVDTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND DT.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode		-- 5. --
			    AND DT.FNLayRow = STK.FNLayRow AND DT.FNLayCol = STK.FNLayCol
			    AND DT.FNCabSeq = STK.FNCabSeq	-- 6. --
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(DT.FTPdtCode,'') <> ''	AND ISNULL(STK.FTPdtCode,'') = '' 	-- 3. --  -- 4. --

		    INSERT INTO TVDTPdtStkBal(FTBchCode, FTWahCode, FNCabSeq, FNLayRow, FNLayCol, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)	-- 6. --
		    --SELECT DISTINCT HD.FTBchCode,HD.FTXthWhTo, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode,0 AS FCStkQty,
		    --SELECT DISTINCT HD.FTBchCode,DT.FTXthWhTo, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode,0 AS FCStkQty,		-- 5. --	-- 6. --
		    SELECT DISTINCT HD.FTBchCode,DT.FTXthWhTo, DT.FNCabSeq, DT.FNLayRow, DT.FNLayCol, DT.FTPdtCode,ISNULL(DT.FCXtdQty,0) AS FCStkQty,	-- 7. --
		    GETDATE() AS FDLastUpdOn,HD.FTLastUpdBy,
		    GETDATE() AS FDCreateOn,HD.FTCreateBy
		    FROM TVDTPdtTwxHD HD WITH(NOLOCK)
		    INNER JOIN TVDTPdtTwxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
		    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTXthWhTo = WAH.FTWahCode AND WAH.FTWahStaType = '6'
		    --LEFT JOIN TVDTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
		    --INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON DT.FTXthWhTo = WAH.FTWahCode AND WAH.FTWahStaType = '6'		-- 5. --
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND DT.FTXthWhTo = WAH.FTWahCode AND WAH.FTWahStaType = '6'	-- 9. --
				    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    LEFT JOIN TVDTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND DT.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode	-- 5. --
			    AND DT.FNLayRow = STK.FNLayRow AND DT.FNLayCol = STK.FNLayCol
			    AND DT.FNCabSeq = STK.FNCabSeq	-- 6. --
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(DT.FTPdtCode,'') <> ''	AND ISNULL(STK.FTPdtCode,'') = '' 	-- 3. --  -- 4. --
		
		    --insert data to Temp
		    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
		    SELECT HD.FTBchCode,HD.FTXthDocNo AS FTStkDocNo
		    ,'1' AS FTStkType
		    , DT.FTPdtCode AS FTPdtCode
		    --, SUM(FCXtdQty) AS FCStkQty,HD.FTXthWhTo AS FTWahCode,HD.FDXthDocDate AS FDStkDate
		    , SUM(FCXtdQty) AS FCStkQty,DT.FTXthWhTo AS FTWahCode,HD.FDXthDocDate AS FDStkDate		-- 5. --
		    --, ROUND(0,2) AS FCStkSetPrice
		    --, ROUND(0,2) AS FCStkCostIn
		    --, ROUND(0,2) AS FCStkCostEx
		    , ROUND(0,4) AS FCStkSetPrice -- 07.01.00 --
		    , ROUND(0,4) AS FCStkCostIn -- 07.01.00 --
		    , ROUND(0,4) AS FCStkCostEx -- 07.01.00 --
		    FROM TVDTPdtTwxDT DT with(nolock)
		    INNER JOIN TVDTPdtTwxHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = DT.FTBchCode AND WAH.FTWahCode = DT.FTXthWhTo AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
		    --GROUP BY HD.FTBchCode,HD.FTXthWhTo,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate
		    GROUP BY HD.FTBchCode,DT.FTXthWhTo,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate	-- 5. --


		    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
		    SELECT HD.FTBchCode,HD.FTXthDocNo AS FTStkDocNo
		    ,'2' AS FTStkType
		    ,DT.FTPdtCode AS FTPdtCode
		    --, SUM(FCXtdQty) AS FCStkQty,HD.FTXthWhFrm AS FTWahCode,HD.FDXthDocDate AS FDStkDate
		    , SUM(FCXtdQty) AS FCStkQty,DT.FTXthWhFrm AS FTWahCode,HD.FDXthDocDate AS FDStkDate		-- 5. --
		    --, ROUND(0,2) AS FCStkSetPrice
		    --, ROUND(0,2) AS FCStkCostIn
		    --, ROUND(0,2) AS FCStkCostEx
		    , ROUND(0,4) AS FCStkSetPrice -- 07.01.00 --
		    , ROUND(0,4) AS FCStkCostIn -- 07.01.00 --
		    , ROUND(0,4) AS FCStkCostEx -- 07.01.00 --
		    FROM TVDTPdtTwxDT DT with(nolock)
		    INNER JOIN TVDTPdtTwxHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
		    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = DT.FTBchCode AND WAH.FTWahCode = DT.FTXthWhFrm AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.01.00 --
		    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
		    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
		    --GROUP BY HD.FTBchCode,HD.FTXthWhFrm,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate
		    GROUP BY HD.FTBchCode,DT.FTXthWhFrm,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate	-- 5. --

		    --insert to stock card
		    INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
		    SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
		    GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk

		    -- 2. --
		    --Cost
		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*COST.FCPdtCostEx)
		    --,FCPdtQtyBal = STK.FCStkQty
		    --,FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '1'
		    --INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode

		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) - (TMP.FCStkQty*COST.FCPdtCostEx)
		    --,FCPdtQtyBal = STK.FCStkQty
		    --,FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '2'
		    --INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode
		    ---- 2. --

		    -- 07.00.00 --
		    UPDATE COST
		    SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
		    ,FCPdtQtyBal = STK.FCStkQty
		    ,FDLastUpdOn = GETDATE()
		    FROM TCNMPdtCostAvg COST With(nolock)
		    INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
		    INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
					    FROM TCNTPdtStkBal STK with(nolock)
					    WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
						    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
					    GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
		    -- 07.00.00 --
	    END	
    END
	ELSE -- 07.01.00 --
    BEGIN
    
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.01.00 --

	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxPurchaseCNPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxPurchaseCNPrc
GO
CREATE PROCEDURE [dbo].STP_DOCxPurchaseCNPrc
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tDate varchar(10)
DECLARE @tTime varchar(8)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTComName varchar(50), 
   FTBchCode varchar(5),	-- 3. --
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty decimal(18,4), 
   FTWahCode varchar(5), 
   FDStkDate Datetime,
   FCStkSetPrice decimal(18,4),
   FCStkCostIn decimal(18,4),
   FCStkCostEx decimal(18,4)
   ) 
DECLARE @tStaPrc varchar(1)		-- 2. --
DECLARE @tStaPrcStkTo varchar(1)	-- 04.01.00 --
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.02.00 --
DECLARE @tStaDoc varchar(1)      -- 07.02.00 -- 1:เอกสารปกติ 3:ยกเลิก
DECLARE @tTrans VARCHAR(20)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
00.01.00	17/06/2018		Em		create  
00.02.00	05/07/2019		Em		เพิ่มการตรวจสอบสถานะการประมวลผลเอกสาร
00.03.00	24/07/2019		Em		แก้ไขขนาดฟิลด์ Branch จาก 3 เป็น 5
00.04.00	30/07/2019		Em		เพิ่ม Insert StkBal และคำนวณต้นทุน
00.05.00	31/07/2019		Em		แก้ไขการปรับสต๊อก
04.01.00	20/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
05.02.00	30/03/2021		Em		แก้ไขให้ตรวจสอบจำนวนที่เป็น 0
06.01.00	08/08/2021		Em		แก้ไข Process ต้นทุน
07.01.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.02.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcCN'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  

	SET @tDate = CONVERT(VARCHAR(10),GETDATE(),121)
	SET @tTime = CONVERT(VARCHAR(8),GETDATE(),108)

	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXphStaPrcStk,'') AS FTXphStaPrcStk FROM TAPTPcHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXphDocNo = @ptDocNo)	-- 2. --
	SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXphStaDoc,'') AS FTXphStaDoc FROM TAPTPcHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXphDocNo = @ptDocNo) -- 07.02.00 --
    
    IF @tStaDoc <> '3' -- 07.02.00 --
    BEGIN
	    IF @tStaPrc <> '1'	-- 2. --
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    ---- 04.01.00 --
		    --SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
		    --				INNER JOIN TAPTPcHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTWahCode = WAH.FTWahCode
		    --				WHERE HD.FTBchCode = @ptBchCode AND HD.FTXphDocNo = @ptDocNo)
        
		    -- 07.02.00 --
		    SELECT TOP 1 @tStaPrcStkTo = ISNULL(WAH.FTWahStaPrcStk,''),
			    @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TAPTPcHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTWahCode = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXphDocNo = @ptDocNo
		    -- 07.02.00 --

		    IF @tStaPrcStkTo = '2'
		    BEGIN
			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk (FTComName,FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT @ptDocNo AS FTComName,HD.FTBchCode,HD.FTXphDocNo AS FTStkDocNo
			    ,'2' AS FTStkType
			    ,DT.FTPdtCode AS FTPdtStkCode
			    , SUM(FCXpdQtyAll) AS FCStkQty,HD.FTWahCode AS FTWahCode,HD.FDXphDocDate AS FDStkDate
			    --, ROUND(SUM(DT.FCXpdNet)/SUM(FCXpdQtyAll),4) AS FCStkSetPrice
			    , ROUND(SUM(DT.FCXpdNetAfHD)/SUM(FCXpdQtyAll),4) AS FCStkSetPrice -- 07.02.00 --
			    , ROUND(SUM(DT.FCXpdCostIn)/SUM(FCXpdQtyAll),4) AS FCStkCostIn
			    , ROUND(SUM(DT.FCXpdCostEx)/SUM(FCXpdQtyAll),4) AS FCStkCostEx
			    FROM TAPTPcDT DT with(nolock)
			    INNER JOIN TAPTPcHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXphDocNo = HD.FTXphDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXphDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode,HD.FTWahCode,HD.FTXphDocNo,HD.FNXphDocType,DT.FTPdtCode,HD.FDXphDocDate

			    --insert data to stock card
			    INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
			    SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
			    GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
			    FROM @TTmpPrcStk
			    WHERE FTComName = @ptDocNo

			    --update qty to stock balance
			    UPDATE TCNTPdtStkBal with(rowlock) 
			    --SET FCStkQty= BAL.FCStkQty + TMP.FCStkQty
			    SET FCStkQty= BAL.FCStkQty - TMP.FCStkQty	-- 5. --
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal BAL 
			    INNER JOIN @TTmpPrcStk TMP ON BAL.FTPdtCode =TMP.FTPdtCode AND BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode
			    WHERE TMP.FTComName=@ptDocNo 
			    AND ISNULL(TMP.FCStkQty,0)<>0

			    -- 4. --
			    --insert to Stock balance
			    INSERT INTO TCNTPdtStkBal with(rowlock)(FTBchCode,FTWahCode,FTPdtCode,FCStkQty,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
			    SELECT TMP.FTBchCode,TMP.FTWahCode,TMP.FTPdtCode,TMP.FCStkQty*(-1),GETDATE(),@ptWho,GETDATE(),@ptWho
			    FROM @TTmpPrcStk TMP
			    LEFT JOIN TCNTPdtStkBal BAL with(NOLOCK) ON TMP.FTBchCode = BAL.FTBchCode AND TMP.FTWahCode = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
			    WHERE ISNULL(BAL.FTPdtCode,'') = ''
			    -- 4. --

			    -- 4. --
			    --Cost
			    --UPDATE TCNMPdtCostAvg with(rowlock)
			    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) - (TMP.FCStkQty*TMP.FCStkCostEx)
			    ----,FCPdtCostEx = ROUND((ISNULL(FCPdtCostAmt,0) - (TMP.FCStkQty*TMP.FCStkCostEx)) / STK.FCStkQty,2)
			    ----,FCPdtCostEx = (CASE WHEN ISNULL(STK.FCStkQty,0) = 0 THEN FCPdtCostEx ELSE ROUND((ISNULL(FCPdtCostAmt,0) - (TMP.FCStkQty*TMP.FCStkCostEx)) / STK.FCStkQty,2) END) -- 05.02.00 --
			    --,FCPdtCostEx = (CASE WHEN (FCPdtQtyBal - ISNULL(TMP.FCStkQty,0)) = 0 THEN FCPdtCostEx ELSE ROUND(((ISNULL(FCPdtCostAmt,0) - (TMP.FCStkQty*TMP.FCStkCostEx))/(FCPdtQtyBal - ISNULL(TMP.FCStkQty,0))),4) END) -- 06.01.00 --
			    --,FCPdtCostLast = TMP.FCStkCostEx
			    ----,FCPdtQtyBal = STK.FCStkQty
			    --,FCPdtQtyBal = FCPdtQtyBal - TMP.FCStkQty	-- 06.01.00 --
			    --,FDLastUpdOn = GETDATE()
			    --FROM TCNMPdtCostAvg COST
			    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			    ----INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode
			    ---- 5. --
			    ----INNER JOIN (SELECT FTPdtCode,SUM(FCStkQty) AS FCStkQty FROM TCNTPdtStkBal with(nolock) WHERE FTBchCode = @ptBchCode GROUP BY FTPdtCode) STK ON COST.FTPdtCode = STK.FTPdtCode
		
			    --UPDATE TCNMPdtCostAvg with(rowlock)
			    --SET FCPdtCostIn = ROUND(ISNULL(FCPdtCostEx,0) + (ISNULL(FCPdtCostEx,0) * VAT.FCVatRate/100),4) 
			    --,FDLastUpdOn = GETDATE()
			    --FROM TCNMPdtCostAvg COST
			    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			    --INNER JOIN TCNMPdt PDT with(nolock) ON COST.FTPdtCode = PDT.FTPdtCode
			    --INNER JOIN (
			    --	SELECT FTVatCode,MAX(FDVatStart) AS FDVatStart 
			    --	FROM TCNMVatRate with(nolock) 
			    --	WHERE CONVERT(VARCHAR(10),FDVatStart,121) < CONVERT(VARCHAR(10),GETDATE(),121) 
			    --	GROUP BY FTVatCode) VATT ON PDT.FTVatCode = VATT.FTVatCode
			    --INNER JOIN TCNMVatRate VAT with(nolock) ON VATT.FTVatCode = VAT.FTVatCode AND VATT.FDVatStart = VAT.FDVatStart
			    ---- 5. --
            
			    --Cost
                -- 07.02.00 --
			    IF @tStaAlwCostAmt = '1'	
			    BEGIN
			        -- 07.01.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.01.00 --
                END
                -- 07.02.00 --

		    END

	    END	-- 2. --
        
    END
    ELSE -- 07.02.00 --
    BEGIN
    
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.02.00 --
    
	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
	SELECT ERROR_MESSAGE()
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxAdjustStockPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxAdjustStockPrc
GO
CREATE PROCEDURE [dbo].STP_DOCxAdjustStockPrc
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTComName varchar(50), 
   FTBchCode varchar(5), 
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty decimal(18,2), 
   FTWahCode varchar(5), 
   FDStkDate Datetime,
   --FCStkSetPrice decimal(18,2),
   --FCStkCostIn decimal(18,2),
   --FCStkCostEx decimal(18,2)
   FCStkSetPrice decimal(18,4), -- 07.01.00 --
   FCStkCostIn decimal(18,4), -- 07.01.00 --
   FCStkCostEx decimal(18,4) -- 07.01.00 --
   ) 
DECLARE @tStaPrc varchar(1)
DECLARE @tWahType varchar(1) -- 4. --
DECLARE @tAdjSeqChk varchar(1) -- 4.--
DECLARE @tStaPrcStkTo varchar(1)	-- 04.04.00 --
DECLARE @tStaAlwCostAmt varchar(1) -- 07.01.00 --
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
00.01.00	13/06/2019		Em		create  
00.02.00	03/07/2019		Em		แก้ไขความกว้างฟิลด์ FTBchCode จาก 3 เป็น 5
00.03.00	05/07/2019		Em		เพิ่มการตรวจสอบสถานะการประมวลผลเอกสาร
00.04.00	22/07/2019		Em		เพิ่มการปรับสต๊อก Vending
00.05.00	31/07/2019		Em		ปรับปรุงแก้ไข
00.06.00	01/08/2019		Em		เพิ่มการตรวจนับสินค้าทั่วไป
03.01.00	27/03/2020		Em		ปรับปรุงแก้ไข
03.02.00	28/03/2020		Em		แก้ไขให้ sum ยอดสต๊อกตามสินค้า
03.03.00	30/03/2020		Em		แก้ไขการ Sum ยอดขายที่ยังไม่ประมวลผล stk ตามสินค้าตามหน่วย
03.04.00	30/03/2020		Em		แก้ไขให้อัพเดท bal ตามจำนวนที่นับได้ + จำนวนที่ขายค้างอยู่ 
04.01.00	20/07/2020		Em		แก้ไขการใช้ฟิลด์ QtyAll
04.02.00	18/08/2020		Em		แก้ไขการใช้ฟิลด์ที่ใช้ตรวจสอบคลัง Vending
04.03.00	27/08/2020		Em		เพิ่มให้ insert ข้อมูลลงตาราง TVDTPdtStkBal
04.04.00	20/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.05.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
04.06.00	16/11/2020		Em		เพิ่มการตรวจสอบสถานะการตัดสต๊อก
04.07.00	16/11/2020		Em		เพิ่มการตรวจสอบรายการ void และการคืน
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
07.00.00	04/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00    26/01/2022      Net     แก้ไขตาม KPC
----------------------------------------------------------------------*/
BEGIN TRY
	--SET @tStaPrc = (SELECT TOP 1 ISNULL(FTAjhStaPrcStk,'') AS FTAjhStaPrcStk FROM TCNTPdtAdjStkHD WITH(NOLOCK) WHERE FTBchCode = @ptBchCode AND FTAjhDocNo = @ptDocNo )		-- 3. --
	
	-- 4. --
	SELECT TOP 1 @tWahType = ISNULL(WAH.FTWahStaType,''),
	 @tAdjSeqChk = ISNULL(HD.FTAjhApvSeqChk,'1'),
     @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,''), -- 07.01.00 --
	 @tStaPrc = ISNULL(FTAjhStaPrcStk,'')
	FROM TCNTPdtAdjStkHD HD WITH(NOLOCK) 
	INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON HD.FTAjhWhTo = WAH.FTWahCode 
	WHERE HD.FTBchCode = @ptBchCode AND HD.FTAjhDocNo = @ptDocNo 
	-- 4. --
	
	IF @tStaPrc <> '1'		-- 3. --
	BEGIN
		
		-- 05.01.00 --
		DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		-- 05.01.00 --

		-- 04.04.00 --
		SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
						INNER JOIN TCNTPdtAdjStkHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTAjhWhTo = WAH.FTWahCode
						WHERE HD.FTBchCode = @ptBchCode AND HD.FTAjhDocNo = @ptDocNo)

		IF @tStaPrcStkTo = '2'
		BEGIN
			IF @tWahType = '6'	-- 4. --
				BEGIN
					--Update sale before adjust
					UPDATE TCNTPdtAdjStkDT WITH(ROWLOCK)
					SET FCAjdSaleB4AdjC1 = ISNULL(SAL.FCXsdQty,0)
					--,FCAjdQtyAllDiff = ((ISNULL(DT.FCAjdQtyAll,0) + ISNULL(SAL.FCXsdQty,0)) - ISNULL(DT.FCAjdWahB4Adj,0))
					,FCAjdQtyAllDiff = ((ISNULL(DT.FCAjdQtyAllC1,0) + ISNULL(SAL.FCXsdQty,0)) - ISNULL(DT.FCAjdWahB4Adj,0))	-- 04.02.00 --
					FROM TCNTPdtAdjStkDT DT
					--LEFT JOIN (SELECT HD.FDXshDocDate, DT.FTPdtCode, VD.FNLayRow, VD.FNLayCol, SUM(DT.FCXsdQtyAll) AS FCXsdQty
					LEFT JOIN (SELECT HD.FDXshDocDate, DT.FTPdtCode, VD.FNLayRow, VD.FNLayCol, SUM(CASE WHEN HD.FNXshDocType = 9 THEN DT.FCXsdQtyAll *(-1) ELSE DT.FCXsdQtyAll END) AS FCXsdQty	 -- 04.07.00 --
						FROM TVDTSalHD HD WITH(NOLOCK)
						INNER JOIN TVDTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo 
						INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
						INNER JOIN TVDTSalDTVD VD WITH(NOLOCK) ON DT.FTBchCode = VD.FTBchCode AND DT.FTXshDocNo = VD.FTXshDocNo AND DT.FNXsdSeqNo = VD.FNXsdSeqNo
						INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = HD.FTBchCode AND WAH.FTWahCode = HD.FTWahCode AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.06.00 --
						WHERE ISNULL(HD.FTXshStaPrcStk,'') = ''
						AND DT.FTXsdStaPdt <> '4' -- 04.07.00 --
						GROUP BY HD.FDXshDocDate, DT.FTPdtCode, VD.FNLayRow, VD.FNLayCol) SAL ON DT.FTPdtCode = SAL.FTPdtCode AND DT.FNAjdLayRow = SAL.FNLayRow AND DT.FNAjdLayCol = SAL.FNLayCol AND DT.FDAjdDateTime > SAL.FDXshDocDate
					--WHERE DT.FDAjdDateTime > SAL.FDXshDocDate
					WHERE DT.FTBchCode = @ptBchCode AND DT.FTAjhDocNo = @ptDocNo -- 5. --

					--insert data to Temp
					INSERT INTO @TTmpPrcStk (FTComName,FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
					SELECT @ptDocNo AS FTComName,HD.FTBchCode,HD.FTAjhDocNo AS FTStkDocNo
					,'5' AS FTStkType
					,DT.FTPdtCode AS FTPdtCode
					--, SUM(((ISNULL(DT.FCAjdQtyAll,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0))) AS FCStkQty,HD.FTAjhWhTo AS FTWahCode,HD.FDAjhDocDate AS FDStkDate
					, SUM(((ISNULL(DT.FCAjdQtyAllC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0))) AS FCStkQty,HD.FTAjhWhTo AS FTWahCode,HD.FDAjhDocDate AS FDStkDate	-- 04.02.00 --
					--, ROUND(0,2) AS FCStkSetPrice
					--, ROUND(0,2) AS FCStkCostIn
					--, ROUND(0,2) AS FCStkCostEx
                    , ROUND(0,4) AS FCStkSetPrice -- 07.01.00 --
					, ROUND(0,4) AS FCStkCostIn -- 07.01.00 --
					, ROUND(0,4) AS FCStkCostEx -- 07.01.00 --
					FROM TCNTPdtAdjStkDT DT with(nolock)
					INNER JOIN TCNTPdtAdjStkHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo
					INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
					WHERE HD.FTBchCode=@ptBchCode AND HD.FTAjhDocNo =@ptDocNo
					AND HD.FTAjhDocType = '3'
					AND ((ISNULL(DT.FCAjdQtyAll,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) <> 0
					GROUP BY HD.FTBchCode,HD.FTAjhWhTo,HD.FTAjhDocNo,HD.FTAjhDocType,DT.FTPdtCode,HD.FDAjhDocDate

					--insert data to stock card
					INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
					SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
					GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
					FROM @TTmpPrcStk
					WHERE FTComName = @ptDocNo

					--update qty to stock balance
					UPDATE TCNTPdtStkBal with(rowlock) 
					SET FCStkQty= BAL.FCStkQty + TMP.FCStkQty
					,FDLastUpdOn = GETDATE()
					,FTLastUpdBy = @ptWho
					FROM TCNTPdtStkBal BAL
					INNER JOIN @TTmpPrcStk TMP ON BAL.FTPdtCode =TMP.FTPdtCode AND BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode
					WHERE TMP.FTComName=@ptDocNo 
					AND ISNULL(TMP.FCStkQty,0)<>0

					--insert to Stock balance
					INSERT INTO TCNTPdtStkBal with(rowlock)(FTBchCode,FTWahCode,FTPdtCode,FCStkQty,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
					--SELECT TMP.FTBchCode,TMP.FTWahCode,TMP.FTPdtCode,TMP.FCStkQty,GETDATE(),@ptWho,GETDATE(),@ptWho
					--FROM @TTmpPrcStk TMP
					--LEFT JOIN TCNTPdtStkBal BAL with(NOLOCK) ON TMP.FTBchCode = BAL.FTBchCode AND TMP.FTWahCode = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
					--WHERE ISNULL(BAL.FTPdtCode,'') = ''
					-- 04.03.00 --
					SELECT DT.FTBchCode,HD.FTAjhWhTo,DT.FTPdtCode,SUM(((ISNULL(DT.FCAjdUnitQtyC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0))) AS FCStkQty,GETDATE(),@ptWho,GETDATE(),@ptWho
					FROM TCNTPdtAdjStkHD HD with(nolock)
					INNER JOIN TCNTPdtAdjStkDT DT with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo
					INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
					LEFT JOIN TCNTPdtStkBal BAL with(NOLOCK) ON DT.FTBchCode = BAL.FTBchCode AND HD.FTAjhWhTo = BAL.FTWahCode AND DT.FTPdtCode = BAL.FTPdtCode
					WHERE HD.FTBchCode=@ptBchCode AND HD.FTAjhDocNo =@ptDocNo
					AND ISNULL(BAL.FTPdtCode,'') = ''
					GROUP BY DT.FTBchCode,HD.FTAjhWhTo,DT.FTPdtCode
					-- 04.03.00 --

					UPDATE TVDTPdtStkBal WITH(ROWLOCK)
					--SET FCStkQty= BAL.FCStkQty + ((ISNULL(DT.FCAjdUnitQty,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0))
					SET FCStkQty= BAL.FCStkQty + ((ISNULL(DT.FCAjdUnitQtyC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0))	-- 04.02.00 --
					,FDLastUpdOn = GETDATE()
					,FTLastUpdBy = @ptWho
					FROM TVDTPdtStkBal BAL
					--INNER JOIN TCNTPdtAdjStkDT DT with(nolock) ON BAL.FTPdtCode = DT.FTPdtCode AND BAL.FNLayRow = DT.FNAjdLayRow AND BAL.FNLayCol = DT.FNAjdLayCol
					--INNER JOIN TCNTPdtAdjStkHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo AND BAL.FTWahCode = HD.FTAjhWhTo
					INNER JOIN TCNTPdtAdjStkHD HD with(nolock) ON BAL.FTWahCode = HD.FTAjhWhTo -- 5. --
					INNER JOIN TCNTPdtAdjStkDT DT with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo AND BAL.FTPdtCode = DT.FTPdtCode AND BAL.FNLayRow = DT.FNAjdLayRow AND BAL.FNLayCol = DT.FNAjdLayCol -- 5. --
					INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
					WHERE HD.FTBchCode=@ptBchCode AND HD.FTAjhDocNo =@ptDocNo
					AND HD.FTAjhDocType = '3'
					--AND ((ISNULL(DT.FCAjdUnitQty,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) <> 0
					AND ((ISNULL(DT.FCAjdUnitQtyC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) <> 0	-- 04.02.00 --

					-- 04.03.00 --
					--insert to Stock balance
					INSERT INTO TVDTPdtStkBal with(rowlock)(FTBchCode,FTWahCode,FNCabSeq,FNLayRow,FNLayCol,FTPdtCode,FCStkQty,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
					SELECT DT.FTBchCode,HD.FTAjhWhTo,DT.FNCabSeq,DT.FNAjdLayRow,DT.FNAjdLayCol, DT.FTPdtCode,((ISNULL(DT.FCAjdUnitQtyC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) AS FCStkQty,GETDATE(),@ptWho,GETDATE(),@ptWho
					FROM TCNTPdtAdjStkHD HD with(nolock)
					INNER JOIN TCNTPdtAdjStkDT DT with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo
					INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
					LEFT JOIN TVDTPdtStkBal BAL with(NOLOCK) ON DT.FTBchCode = BAL.FTBchCode AND HD.FTAjhWhTo = BAL.FTWahCode AND DT.FTPdtCode = BAL.FTPdtCode AND BAL.FNLayRow = DT.FNAjdLayRow AND BAL.FNLayCol = DT.FNAjdLayCol
					WHERE HD.FTBchCode=@ptBchCode AND HD.FTAjhDocNo =@ptDocNo
					AND ISNULL(BAL.FTPdtCode,'') = ''
					-- 04.03.00 --
				
					--Cost
					--UPDATE TCNMPdtCostAvg with(rowlock)
					--SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*COST.FCPdtCostEx)
					--,FCPdtQtyBal = STK.FCStkQty
					--,FDLastUpdOn = GETDATE()
					--FROM TCNMPdtCostAvg COST
					--INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
					--INNER JOIN (SELECT FTPdtCode,SUM(FCStkQty) AS FCStkQty FROM TCNTPdtStkBal with(nolock) WHERE FTBchCode = @ptBchCode GROUP BY FTPdtCode) STK ON COST.FTPdtCode = STK.FTPdtCode
                    
                    -- 07.01.00 --
					IF @tStaAlwCostAmt = '1'
					BEGIN

					    -- 07.00.00 --
					    UPDATE COST
					    SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
					    ,FCPdtQtyBal = STK.FCStkQty
					    , FDLastUpdOn = GETDATE()
					    FROM TCNMPdtCostAvg COST With(nolock)
					    INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
					    INNER JOIN 
                        (
                            SELECT STK.FTBchCode,STK.FTWahCode,STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						    FROM TCNTPdtStkBal STK with(nolock)
						    WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
						        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						    GROUP BY STK.FTBchCode,STK.FTWahCode,STK.FTPdtCode
                        ) STK ON 
                            TMP.FTBchCode = STK.FTBchCode AND TMP.FTWahCode = STK.FTWahCode AND TMP.FTPdtCode = STK.FTPdtCode
					    -- 07.00.00 --

					END
                    -- 07.01.00 --

                    

				END
			ELSE
				BEGIN
					IF @tAdjSeqChk = '1'
						BEGIN
							-- 03.04.00 --
							UPDATE TCNTPdtAdjStkDT
							SET FCAjdWahB4Adj = (CASE WHEN ISNULL(TMP.FTPdtCode,'') = '' THEN 0 ELSE FCAjdWahB4Adj END)
							FROM TCNTPdtAdjStkDT DT 
							LEFT JOIN (SELECT FTPdtCode,MIN(FCPdtUnitFact) AS FCPdtUnitFact
										FROM TCNTPdtAdjStkDT WITH(NOLOCK)
										WHERE FTBchCode=@ptBchCode AND FTAjhDocNo =@ptDocNo
										GROUP BY FTPdtCode
										) TMP ON TMP.FTPdtCode = DT.FTPdtCode AND TMP.FCPdtUnitFact = DT.FCPdtUnitFact
							WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo
							-- 03.04.00 --

							--Update sale before adjust
							UPDATE TCNTPdtAdjStkDT WITH(ROWLOCK)
							SET FCAjdSaleB4AdjC1 = ISNULL(SAL.FCXsdQty,0)
							,FCAjdQtyAllDiff = ((ISNULL(DT.FCAjdQtyAllC1,0) + ISNULL(SAL.FCXsdQty,0)) - ISNULL(DT.FCAjdWahB4Adj,0))
							FROM TCNTPdtAdjStkDT DT
							--LEFT JOIN (SELECT HD.FDXshDocDate, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty
							--	FROM TPSTSalHD HD WITH(NOLOCK)
							--	INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo 
							--	WHERE ISNULL(HD.FTXshStaPrcStk,'') = ''
							--	GROUP BY HD.FDXshDocDate, DT.FTPdtCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode AND DT.FDAjdDateTime > SAL.FDXshDocDate
							-- 03.01.00 --
							--LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty
							--LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty -- 03.03.00 --
							LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode, SUM(CASE WHEN HD.FNXshDocType = 9 THEN DT.FCXsdQtyAll*(-1) ELSE DT.FCXsdQtyAll END) AS FCXsdQty -- 04.07.00 --
								FROM TPSTSalHD HD WITH(NOLOCK)
								--INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo 
								INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo AND DT.FTXsdStaPdt <> '4'	-- 04.07.00 --
								INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.06.00 --
								INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON WAH.FTBchCode = HD.FTBchCode AND WAH.FTWahCode = HD.FTWahCode AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' -- 04.06.00 --
								WHERE ISNULL(HD.FTXshStaPrcStk,'') = ''
								AND HD.FTBchCode = @ptBchCode
								--GROUP BY HD.FTBchCode, DT.FTPdtCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode
								GROUP BY HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode AND DT.FTPunCode = SAL.FTPunCode	-- 03.03.00 --
							-- 03.01.00 --
							--WHERE DT.FDAjdDateTime > SAL.FDXshDocDate
							WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo	-- 5. --

							--insert data to Temp
							INSERT INTO @TTmpPrcStk (FTComName,FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
							SELECT @ptDocNo AS FTComName,HD.FTBchCode,HD.FTAjhDocNo AS FTStkDocNo
							,'5' AS FTStkType
							,DT.FTPdtCode AS FTPdtCode
							--, ((ISNULL(DT.FCAjdQtyAllC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) AS FCStkQty
							, SUM(((ISNULL(DT.FCAjdQtyAllC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0))) AS FCStkQty	-- 03.02.00 --
							,HD.FTAjhWhTo AS FTWahCode,HD.FDAjhDocDate AS FDStkDate
							--, ROUND(0,2) AS FCStkSetPrice
							--, ROUND(0,2) AS FCStkCostIn
							--, ROUND(0,2) AS FCStkCostEx
							, ROUND(0,4) AS FCStkSetPrice -- 07.01.00 --
							, ROUND(0,4) AS FCStkCostIn -- 07.01.00 --
							, ROUND(0,4) AS FCStkCostEx -- 07.01.00 --
							FROM TCNTPdtAdjStkDT DT with(nolock)
							INNER JOIN TCNTPdtAdjStkHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo
							INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
							WHERE HD.FTBchCode=@ptBchCode AND HD.FTAjhDocNo =@ptDocNo
							AND HD.FTAjhDocType IN ('2','3')
							AND ((ISNULL(DT.FCAjdQtyAllC1,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) <> 0
							GROUP BY HD.FTBchCode,HD.FTAjhDocNo,DT.FTPdtCode,HD.FTAjhWhTo,FDAjhDocDate	-- 03.02.00 --

							--insert data to stock card
							INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
							SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
							GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
							FROM @TTmpPrcStk
							WHERE FTComName = @ptDocNo

							--update qty to stock balance
							--UPDATE TCNTPdtStkBal with(rowlock) 
							----SET FCStkQty= BAL.FCStkQty + TMP.FCStkQty
							--SET FCStkQty= TMP.FCStkQty	-- 03.03.00 --
							--,FDLastUpdOn = GETDATE()
							--,FTLastUpdBy = @ptWho
							--FROM TCNTPdtStkBal BAL
							--INNER JOIN @TTmpPrcStk TMP ON BAL.FTPdtCode =TMP.FTPdtCode AND BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode
							--WHERE TMP.FTComName=@ptDocNo 
							--AND ISNULL(TMP.FCStkQty,0)<>0
							-- 03.04.00 --
							UPDATE TCNTPdtStkBal with(rowlock) 
							SET FCStkQty= TMP.FCStkQty
							,FDLastUpdOn = GETDATE()
							,FTLastUpdBy = @ptWho
							FROM TCNTPdtStkBal BAL
							--INNER JOIN (SELECT HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode,SUM(DT.FCAjdQtyAll+DT.FCAjdSaleB4AdjC1) AS FCStkQty 
							INNER JOIN (SELECT HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode,SUM(DT.FCAjdQtyAllC1+DT.FCAjdSaleB4AdjC1) AS FCStkQty -- 04.01.00 --
									FROM TCNTPdtAdjStkDT DT with(nolock)
									INNER JOIN TCNTPdtAdjStkHD HD with(nolock) on HD.FTBchCode = DT.FTBchCode AND HD.FTAjhDocNo = DT.FTAjhDocNo
									INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
									WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo
									GROUP BY HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode
									) TMP ON TMP.FTAjhBchTo = BAL.FTBchCode AND TMP.FTAjhWhTo = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
							-- 03.04.00 --

							--insert to Stock balance
							INSERT INTO TCNTPdtStkBal with(rowlock)(FTBchCode,FTWahCode,FTPdtCode,FCStkQty,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
							SELECT TMP.FTBchCode,TMP.FTWahCode,TMP.FTPdtCode,TMP.FCStkQty,GETDATE(),@ptWho,GETDATE(),@ptWho
							FROM @TTmpPrcStk TMP
							LEFT JOIN TCNTPdtStkBal BAL with(NOLOCK) ON TMP.FTBchCode = BAL.FTBchCode AND TMP.FTWahCode = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
							WHERE ISNULL(BAL.FTPdtCode,'') = ''

							--Cost
							--UPDATE TCNMPdtCostAvg with(rowlock)
							--SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*COST.FCPdtCostEx)
							--,FCPdtQtyBal = STK.FCStkQty
							--,FDLastUpdOn = GETDATE()
							--FROM TCNMPdtCostAvg COST
							--INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
							--INNER JOIN (SELECT FTPdtCode,SUM(FCStkQty) AS FCStkQty FROM TCNTPdtStkBal with(nolock) WHERE FTBchCode = @ptBchCode GROUP BY FTPdtCode) STK ON COST.FTPdtCode = STK.FTPdtCode

                            
							--Cost
                            -- 07.01.00 --
							IF @tStaAlwCostAmt = '1'
							BEGIN
							    -- 07.00.00 --
							    UPDATE COST
							    SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
							    ,FCPdtQtyBal = STK.FCStkQty
							    ,FDLastUpdOn = GETDATE() 
							    FROM TCNMPdtCostAvg COST With(nolock)
							    INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
							    INNER JOIN (SELECT STK.FTBchCode,STK.FTWahCode,STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
										    FROM TCNTPdtStkBal STK with(nolock)
										    WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
											    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
										    GROUP BY STK.FTBchCode,STK.FTWahCode,STK.FTPdtCode) STK ON TMP.FTBchCode = STK.FTBchCode AND TMP.FTWahCode = STK.FTWahCode AND TMP.FTPdtCode = STK.FTPdtCode
							    -- 07.00.00 --
							END
                            -- 07.01.00 --

						END
					ELSE
						IF @tAdjSeqChk = '2'
							BEGIN
								-- 03.04.00 --
								UPDATE TCNTPdtAdjStkDT
								SET FCAjdWahB4Adj = (CASE WHEN ISNULL(TMP.FTPdtCode,'') = '' THEN 0 ELSE FCAjdWahB4Adj END)
								FROM TCNTPdtAdjStkDT DT 
								LEFT JOIN (SELECT FTPdtCode,MIN(FCPdtUnitFact) AS FCPdtUnitFact
											FROM TCNTPdtAdjStkDT WITH(NOLOCK)
											WHERE FTBchCode=@ptBchCode AND FTAjhDocNo =@ptDocNo
											GROUP BY FTPdtCode
											) TMP ON TMP.FTPdtCode = DT.FTPdtCode AND TMP.FCPdtUnitFact = DT.FCPdtUnitFact
								WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo
								-- 03.04.00 --

								--Update sale before adjust
								UPDATE TCNTPdtAdjStkDT WITH(ROWLOCK)
								SET FCAjdSaleB4AdjC2 = ISNULL(SAL.FCXsdQty,0)
								,FCAjdQtyAllDiff = ((ISNULL(DT.FCAjdQtyAllC2,0) + ISNULL(SAL.FCXsdQty,0)) - ISNULL(DT.FCAjdWahB4Adj,0))
								FROM TCNTPdtAdjStkDT DT
								--LEFT JOIN (SELECT HD.FDXshDocDate, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty
								--	FROM TPSTSalHD HD WITH(NOLOCK)
								--	INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo 
								--	WHERE ISNULL(HD.FTXshStaPrcStk,'') = ''
								--	GROUP BY HD.FDXshDocDate, DT.FTPdtCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode AND DT.FDAjdDateTime > SAL.FDXshDocDate
								-- 03.01.00 --
								--LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty
								--LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty -- 03.03.00 --
								LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode, SUM(CASE WHEN HD.FNXshDocType = 9 THEN DT.FCXsdQtyAll * (-1) ELSE DT.FCXsdQtyAll END) AS FCXsdQty -- 04.07.00 --
									FROM TPSTSalHD HD WITH(NOLOCK)
									--INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo 
									INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo AND DT.FTXsdStaPdt <> '4'	-- 04.07.00 --
									INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'	-- 04.06.00 --
									INNER JOIN TCNMWahouse WAH WITH(NOLOCK) ON WAH.FTBchCode = HD.FTBchCode AND WAH.FTWahCode = HD.FTWahCode AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.06.00 --
									WHERE ISNULL(HD.FTXshStaPrcStk,'') = ''
									AND HD.FTBchCode = @ptBchCode
									--GROUP BY HD.FTBchCode, DT.FTPdtCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode
									GROUP BY HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode AND DT.FTPunCode = SAL.FTPunCode	-- 03.03.00 --
								-- 03.01.00 --
								--WHERE DT.FDAjdDateTime > SAL.FDXshDocDate
								WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo	-- 5. --

								--insert data to Temp
								INSERT INTO @TTmpPrcStk (FTComName,FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
								SELECT @ptDocNo AS FTComName,HD.FTBchCode,HD.FTAjhDocNo AS FTStkDocNo
								,'5' AS FTStkType
								,DT.FTPdtCode AS FTPdtCode
								--, ((ISNULL(DT.FCAjdQtyAllC2,0) + ISNULL(DT.FCAjdSaleB4AdjC2,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) AS FCStkQty,HD.FTAjhWhTo AS FTWahCode,HD.FDAjhDocDate AS FDStkDate
								, SUM(((ISNULL(DT.FCAjdQtyAllC2,0) + ISNULL(DT.FCAjdSaleB4AdjC2,0)) - ISNULL(DT.FCAjdWahB4Adj,0))) AS FCStkQty,HD.FTAjhWhTo AS FTWahCode,HD.FDAjhDocDate AS FDStkDate	-- 03.02.00 --
								--, ROUND(0,2) AS FCStkSetPrice
								--, ROUND(0,2) AS FCStkCostIn
								--, ROUND(0,2) AS FCStkCostEx
                                , ROUND(0,4) AS FCStkSetPrice -- 07.01.00 --
								, ROUND(0,4) AS FCStkCostIn -- 07.01.00 --
								, ROUND(0,4) AS FCStkCostEx -- 07.01.00 --
								FROM TCNTPdtAdjStkDT DT with(nolock)
								INNER JOIN TCNTPdtAdjStkHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo
								INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
								WHERE HD.FTBchCode=@ptBchCode AND HD.FTAjhDocNo =@ptDocNo
								AND HD.FTAjhDocType IN ('2','3')
								AND ((ISNULL(DT.FCAjdQtyAllC2,0) + ISNULL(DT.FCAjdSaleB4AdjC2,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) <> 0
								GROUP BY HD.FTBchCode ,HD.FTAjhDocNo,DT.FTPdtCode,HD.FTAjhWhTo,HD.FDAjhDocDate	-- 03.02.00 --

								--insert data to stock card
								INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
								SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
								GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
								FROM @TTmpPrcStk
								WHERE FTComName = @ptDocNo

								--update qty to stock balance
								--UPDATE TCNTPdtStkBal with(rowlock) 
								----SET FCStkQty= BAL.FCStkQty + TMP.FCStkQty
								--SET FCStkQty= TMP.FCStkQty	-- 03.03.00 --
								--,FDLastUpdOn = GETDATE()
								--,FTLastUpdBy = @ptWho
								--FROM TCNTPdtStkBal BAL
								--INNER JOIN @TTmpPrcStk TMP ON BAL.FTPdtCode =TMP.FTPdtCode AND BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode
								--WHERE TMP.FTComName=@ptDocNo 
								--AND ISNULL(TMP.FCStkQty,0)<>0

								-- 03.04.00 --
								UPDATE TCNTPdtStkBal with(rowlock) 
								SET FCStkQty= TMP.FCStkQty
								,FDLastUpdOn = GETDATE()
								,FTLastUpdBy = @ptWho
								FROM TCNTPdtStkBal BAL
								--INNER JOIN (SELECT HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode,SUM(DT.FCAjdQtyAll+DT.FCAjdSaleB4AdjC2) AS FCStkQty 
								INNER JOIN (SELECT HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode,SUM(DT.FCAjdQtyAllC2+DT.FCAjdSaleB4AdjC2) AS FCStkQty	-- 04.01.00 --
										FROM TCNTPdtAdjStkDT DT with(nolock)
										INNER JOIN TCNTPdtAdjStkHD HD with(nolock) on HD.FTBchCode = DT.FTBchCode AND HD.FTAjhDocNo = DT.FTAjhDocNo
										INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
										WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo
										GROUP BY HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode
										) TMP ON TMP.FTAjhBchTo = BAL.FTBchCode AND TMP.FTAjhWhTo = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
								-- 03.04.00 --

								--insert to Stock balance
								INSERT INTO TCNTPdtStkBal with(rowlock)(FTBchCode,FTWahCode,FTPdtCode,FCStkQty,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
								SELECT TMP.FTBchCode,TMP.FTWahCode,TMP.FTPdtCode,TMP.FCStkQty,GETDATE(),@ptWho,GETDATE(),@ptWho
								FROM @TTmpPrcStk TMP
								LEFT JOIN TCNTPdtStkBal BAL with(NOLOCK) ON TMP.FTBchCode = BAL.FTBchCode AND TMP.FTWahCode = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
								WHERE ISNULL(BAL.FTPdtCode,'') = ''

								--Cost
								--UPDATE TCNMPdtCostAvg with(rowlock)
								--SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*COST.FCPdtCostEx)
								--,FCPdtQtyBal = STK.FCStkQty
								--,FDLastUpdOn = GETDATE()
								--FROM TCNMPdtCostAvg COST
								--INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
								--INNER JOIN (SELECT FTPdtCode,SUM(FCStkQty) AS FCStkQty FROM TCNTPdtStkBal with(nolock) WHERE FTBchCode = @ptBchCode GROUP BY FTPdtCode) STK ON COST.FTPdtCode = STK.FTPdtCode
								
                                
								--Cost
                                -- 07.01.00 --
								IF @tStaAlwCostAmt = '1'
								BEGIN
								    -- 07.00.00 --
								    UPDATE COST
								    SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
								    ,FCPdtQtyBal = STK.FCStkQty
								    ,FDLastUpdOn = GETDATE()
								    FROM TCNMPdtCostAvg COST With(nolock)
								    INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
								    INNER JOIN (
                                        SELECT STK.FTBchCode,STK.FTWahCode,STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
										FROM TCNTPdtStkBal STK with(nolock)
										WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
										    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
										GROUP BY STK.FTBchCode,STK.FTWahCode,STK.FTPdtCode
                                    ) STK ON 
                                        TMP.FTBchCode = STK.FTBchCode AND TMP.FTWahCode = STK.FTWahCode AND TMP.FTPdtCode = STK.FTPdtCode
								    -- 07.00.00 --
								END
                                -- 07.01.00 --
							END
						ELSE
							BEGIN
								-- 03.04.00 --
								UPDATE TCNTPdtAdjStkDT
								SET FCAjdWahB4Adj = (CASE WHEN ISNULL(TMP.FTPdtCode,'') = '' THEN 0 ELSE FCAjdWahB4Adj END)
								FROM TCNTPdtAdjStkDT DT 
								LEFT JOIN (SELECT FTPdtCode,MIN(FCPdtUnitFact) AS FCPdtUnitFact
											FROM TCNTPdtAdjStkDT WITH(NOLOCK)
											WHERE FTBchCode=@ptBchCode AND FTAjhDocNo =@ptDocNo
											GROUP BY FTPdtCode
											) TMP ON TMP.FTPdtCode = DT.FTPdtCode AND TMP.FCPdtUnitFact = DT.FCPdtUnitFact
								WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo
								-- 03.04.00 --

								--Update sale before adjust
								UPDATE TCNTPdtAdjStkDT WITH(ROWLOCK)
								SET FCAjdSaleB4AdjC1 = ISNULL(SAL.FCXsdQty,0)
								,FCAjdQtyAllDiff = ((ISNULL(DT.FCAjdQtyAll,0) + ISNULL(SAL.FCXsdQty,0)) - ISNULL(DT.FCAjdWahB4Adj,0))
								FROM TCNTPdtAdjStkDT DT
								--LEFT JOIN (SELECT HD.FDXshDocDate, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty
								--	FROM TPSTSalHD HD WITH(NOLOCK)
								--	INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo 
								--	WHERE ISNULL(HD.FTXshStaPrcStk,'') = ''
								--	GROUP BY HD.FDXshDocDate, DT.FTPdtCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode AND DT.FDAjdDateTime > SAL.FDXshDocDate
								-- 03.01.00 --
								--LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty
								--LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode, SUM(DT.FCXsdQtyAll) AS FCXsdQty -- 03.03.00 --
								LEFT JOIN (SELECT HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode, SUM(CASE WHEN HD.FNXshDocType = 9 THEN DT.FCXsdQtyAll * (-1) ELSE DT.FCXsdQtyAll END) AS FCXsdQty -- 04.07.00 --
									FROM TPSTSalHD HD WITH(NOLOCK)
									--INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo 
									INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXshDocNo = HD.FTXshDocNo AND DT.FTXsdStaPdt <> '4'	-- 04.07.00 --
									INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND ISNULL(PDT.FTPdtStkControl,'') = '1'	-- 04.06.00 --
									INNER JOIN TCNMWaHouse WAH WITH(NOLOcK) ON WAH.FTBchCode = HD.FTBchCode AND WAH.FTWahCode = HD.FTWahCode AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'	-- 04.06.00 --
									WHERE ISNULL(HD.FTXshStaPrcStk,'') = ''
									AND HD.FTBchCode = @ptBchCode
									--GROUP BY HD.FTBchCode, DT.FTPdtCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode
									GROUP BY HD.FTBchCode, DT.FTPdtCode, DT.FTPunCode) SAL ON DT.FTPdtCode = SAL.FTPdtCode AND DT.FTPunCode = SAL.FTPunCode	-- 03.03.00 --
								-- 03.01.00 --
								--WHERE DT.FDAjdDateTime > SAL.FDXshDocDate
								WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo	-- 5. --

								--insert data to Temp
								INSERT INTO @TTmpPrcStk (FTComName,FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
								SELECT @ptDocNo AS FTComName,HD.FTBchCode,HD.FTAjhDocNo AS FTStkDocNo
								,'5' AS FTStkType
								,DT.FTPdtCode AS FTPdtCode
								--, ((ISNULL(DT.FCAjdQtyAll,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) AS FCStkQty,HD.FTAjhWhTo AS FTWahCode,HD.FDAjhDocDate AS FDStkDate
								, SUM(((ISNULL(DT.FCAjdQtyAll,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0))) AS FCStkQty,HD.FTAjhWhTo AS FTWahCode,HD.FDAjhDocDate AS FDStkDate		-- 03.02.00 --
								--, ROUND(0,2) AS FCStkSetPrice
								--, ROUND(0,2) AS FCStkCostIn
								--, ROUND(0,2) AS FCStkCostEx
                                , ROUND(0,4) AS FCStkSetPrice -- 07.01.00 --
								, ROUND(0,4) AS FCStkCostIn -- 07.01.00 --
								, ROUND(0,4) AS FCStkCostEx -- 07.01.00 --
								FROM TCNTPdtAdjStkDT DT with(nolock)
								INNER JOIN TCNTPdtAdjStkHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo
								INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
								WHERE HD.FTBchCode=@ptBchCode AND HD.FTAjhDocNo =@ptDocNo
								AND HD.FTAjhDocType IN ('2','3')
								AND ((ISNULL(DT.FCAjdQtyAll,0) + ISNULL(DT.FCAjdSaleB4AdjC1,0)) - ISNULL(DT.FCAjdWahB4Adj,0)) <> 0
								GROUP BY HD.FTBchCode ,HD.FTAjhDocNo,DT.FTPdtCode,HD.FTAjhWhTo,HD.FDAjhDocDate	-- 03.02.00 --

								--insert data to stock card
								INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
								SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
								GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
								FROM @TTmpPrcStk
								WHERE FTComName = @ptDocNo

								--update qty to stock balance
								--UPDATE TCNTPdtStkBal with(rowlock) 
								----SET FCStkQty= BAL.FCStkQty + TMP.FCStkQty
								--SET FCStkQty= TMP.FCStkQty	-- 03.03.00 --
								--,FDLastUpdOn = GETDATE()
								--,FTLastUpdBy = @ptWho
								--FROM TCNTPdtStkBal BAL
								--INNER JOIN @TTmpPrcStk TMP ON BAL.FTPdtCode =TMP.FTPdtCode AND BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode
								--WHERE TMP.FTComName=@ptDocNo 
								--AND ISNULL(TMP.FCStkQty,0)<>0

								-- 03.04.00 --
								UPDATE TCNTPdtStkBal with(rowlock) 
								SET FCStkQty= TMP.FCStkQty
								,FDLastUpdOn = GETDATE()
								,FTLastUpdBy = @ptWho
								FROM TCNTPdtStkBal BAL
								INNER JOIN (SELECT HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode,SUM(DT.FCAjdQtyAll+DT.FCAjdSaleB4AdjC1) AS FCStkQty 
										FROM TCNTPdtAdjStkDT DT with(nolock)
										INNER JOIN TCNTPdtAdjStkHD HD with(nolock) on HD.FTBchCode = DT.FTBchCode AND HD.FTAjhDocNo = DT.FTAjhDocNo
										INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.05.00 --
										WHERE DT.FTBchCode=@ptBchCode AND DT.FTAjhDocNo =@ptDocNo
										GROUP BY HD.FTAjhBchTo,HD.FTAjhWhTo,DT.FTPdtCode
										) TMP ON TMP.FTAjhBchTo = BAL.FTBchCode AND TMP.FTAjhWhTo = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
								-- 03.04.00 --

								--insert to Stock balance
								INSERT INTO TCNTPdtStkBal with(rowlock)(FTBchCode,FTWahCode,FTPdtCode,FCStkQty,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
								SELECT TMP.FTBchCode,TMP.FTWahCode,TMP.FTPdtCode,TMP.FCStkQty,GETDATE(),@ptWho,GETDATE(),@ptWho
								FROM @TTmpPrcStk TMP
								LEFT JOIN TCNTPdtStkBal BAL with(NOLOCK) ON TMP.FTBchCode = BAL.FTBchCode AND TMP.FTWahCode = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
								WHERE ISNULL(BAL.FTPdtCode,'') = ''

								--Cost
								--UPDATE TCNMPdtCostAvg with(rowlock)
								--SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*COST.FCPdtCostEx)
								--,FCPdtQtyBal = STK.FCStkQty
								--,FDLastUpdOn = GETDATE()
								--FROM TCNMPdtCostAvg COST
								--INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
								--INNER JOIN (SELECT FTPdtCode,SUM(FCStkQty) AS FCStkQty FROM TCNTPdtStkBal with(nolock) WHERE FTBchCode = @ptBchCode GROUP BY FTPdtCode) STK ON COST.FTPdtCode = STK.FTPdtCode
                                
								--Cost
                                -- 07.01.00 --
								IF @tStaAlwCostAmt = '1'
								BEGIN
								    -- 07.00.00 --
								    UPDATE COST
								    SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
								    ,FCPdtQtyBal = STK.FCStkQty
								    ,FDLastUpdOn = GETDATE()
								    FROM TCNMPdtCostAvg COST With(nolock)
								    INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
								    INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
											    FROM TCNTPdtStkBal STK with(nolock)
											    WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
												    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
											    GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
								    -- 07.00.00 --
                                END
                                -- 07.01.00 --

							END
				END
		END

	END	-- 3. --
	SET @FNResult= 0
END TRY
BEGIN CATCH
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxBchPdtTnf')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxBchPdtTnf
GO
CREATE PROCEDURE [dbo].STP_DOCxBchPdtTnf
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tTrans VARCHAR(20)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTBchCode varchar(5), 
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty decimal(18,2), 
   FTWahCode varchar(5), 
   FDStkDate Datetime,
   --FCStkSetPrice decimal(18,2),
   --FCStkCostIn decimal(18,2),
   --FCStkCostEx decimal(18,2)
   FCStkSetPrice decimal(18,4), -- 07.01.00 --
   FCStkCostIn decimal(18,4), -- 07.01.00 --
   FCStkCostEx decimal(18,4) -- 07.01.00 --
   ) 
DECLARE @tStaPrc varchar(1)
DECLARE @tStaPrcStkFrm varchar(1)	-- 04.01.00 --
DECLARE @tStaPrcStkTo varchar(1)	-- 04.01.00 --		
DECLARE @tStaAlwCostAmtFrm varchar(1)	-- 07.01.00 --
DECLARE @tStaAlwCostAmtTo varchar(1)	-- 07.01.00 --
DECLARE @tStaDoc varchar(1)      -- 07.01.00 1:เอกสารปกติ 3:ยกเลิก
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
00.01.00	24/03/2020		Em		create  
00.02.00	26/03/2020		Em		แก้ไขข้อมูลลงตามสาขาต้นทางปลายทาง
00.03.00	09/04/2020		Em		แก้ไขปรับปรุง
04.01.00	21/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
07.00.00	04/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcBchTnf'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  
	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk,'') AS FTXthStaPrcStk FROM TCNTPdtTbxHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)	
    SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXthStaDoc,'') AS FTXthStaDoc FROM TCNTPdtTbxHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) -- 07.01.00 --

    IF @tStaDoc <> '3' -- 07.01.00 --
    BEGIN
	    IF @tStaPrc <> '1'	
	    BEGIN
		    -- 05.01.00 --
		    DELETE STK WITH(ROWLOCK)
		    FROM TCNTPdtStkCrd STK
		    --INNER JOIN TCNTPdtTbxHD HD WITH(NOLOCK) ON HD.FTXthDocNo = STK.FTStkDocNo AND (HD.FTXthWhFrm = STK.FTBchCode OR HD.FTXthWhTo = STK.FTBchCode) 
		    INNER JOIN TCNTPdtTbxHD HD WITH(NOLOCK) ON HD.FTXthDocNo = STK.FTStkDocNo AND (HD.FTXthBchFrm = STK.FTBchCode OR HD.FTXthBchTo = STK.FTBchCode) -- 07.01.00 --
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    -- 05.01.00 --

		    -- 04.01.00 --
		    --SET @tStaPrcStkFrm = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
		    --				INNER JOIN TCNTPdtTbxHD HD WITH(NOLOCK) ON HD.FTXthBchFrm = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
		    --				WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)
		    --SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
		    --				INNER JOIN TCNTPdtTbxHD HD WITH(NOLOCK) ON HD.FTXthBchTo = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
		    --				WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)
		
		    -- 07.01.00 --
		    SELECT TOP 1 @tStaPrcStkFrm = ISNULL(WAH.FTWahStaPrcStk,''),
			    @tStaAlwCostAmtFrm = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTbxHD HD WITH(NOLOCK) ON HD.FTXthBchFrm = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo

		    SELECT TOP 1 @tStaPrcStkTo =  ISNULL(WAH.FTWahStaPrcStk,''), 
			    @tStaAlwCostAmtTo = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTbxHD HD WITH(NOLOCK) ON HD.FTXthBchTo = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    -- 07.01.00 --

		    IF @tStaPrcStkFrm = '2'
		    BEGIN
			    --Create stk balance
			    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
			    SELECT DISTINCT HD.FTXthBchFrm,HD.FTXthWhFrm,DT.FTPdtCode,0 AS FCStkQty,	-- 00.02.00 --
			    GETDATE() AS FDLastUpd,@ptWho,	
			    GETDATE() AS FDCreateOn,@ptWho	
			    FROM TCNTPdtTbxHD HD WITH(NOLOCK)		
			    INNER JOIN TCNTPdtTbxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --		
			    --LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTXthBchFrm = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode -- 07.01.00 --
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    AND ISNULL(STK.FTPdtCode,'') = ''

			    --Update Out
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty - ISNULL(Tfb.FCXtdQtyAll,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = @ptWho	
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (SELECT HD.FTXthBchFrm,HD.FTLastUpdBy,HD.FTXthWhFrm, DT.FTPdtCode ,SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll	-- 00.03.00 --
				    FROM TCNTPdtTbxHD HD WITH(NOLOCK)
				    INNER JOIN TCNTPdtTbxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
				    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
				    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
				    GROUP BY HD.FTXthBchFrm,HD.FTLastUpdBy,HD.FTXthWhFrm, DT.FTPdtCode) Tfb  ON Tfb.FTXthBchFrm = STK.FTBchCode AND Tfb.FTXthWhFrm = STK.FTWahCode AND Tfb.FTPdtCode = STK.FTPdtCode	-- 00.03.00 --

			    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT HD.FTXthBchFrm,HD.FTXthDocNo AS FTStkDocNo	-- 00.02.00 --
			    ,'2' AS FTStkType
			    ,DT.FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty,HD.FTXthWhFrm AS FTWahCode,HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdSetPrice)/SUM(FCXtdQtyAll),2) AS FCStkSetPrice
			    --, ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),2) AS FCStkCostIn
			    --, ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),2) AS FCStkCostEx
			    , ROUND(SUM(DT.FCXtdNet)/SUM(FCXtdQtyAll),4) AS FCStkSetPrice -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),4) AS FCStkCostIn -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),4) AS FCStkCostEx -- 07.01.00 --
			    FROM TCNTPdtTbxDT DT with(nolock)
			    INNER JOIN TCNTPdtTbxHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTXthBchFrm,HD.FTXthWhFrm,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate	-- 00.02.00 --

                
                -- 07.01.00 --
		        IF @tStaAlwCostAmtFrm = '1'
		        BEGIN

		            -- 07.00.00 --
		            UPDATE COST
		            SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
		            ,FCPdtQtyBal = STK.FCStkQty
		            ,FDLastUpdOn = GETDATE()
		            FROM TCNMPdtCostAvg COST With(nolock)
		            INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '2'
		            INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
					            FROM TCNTPdtStkBal STK with(nolock)
					            WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
						            AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
					            GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
		            -- 07.00.00 --
        
                END
                -- 07.01.00 --

		    END

		    IF @tStaPrcStkTo = '2'
		    BEGIN
			    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
			    SELECT DISTINCT HD.FTXthBchTo,HD.FTXthWhTo,DT.FTPdtCode,0 AS FCStkQty,	-- 00.02.00 --
			    GETDATE() AS FDLastUpdOn,@ptWho,	
			    GETDATE() AS FDCreateOn,@ptWho		
			    FROM TCNTPdtTbxHD HD WITH(NOLOCK)		
			    INNER JOIN TCNTPdtTbxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo	
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --	
			    --LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTXthBchTo = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode -- 07.01.00 --
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    AND ISNULL(STK.FTPdtCode,'') = ''
			    GROUP BY HD.FTXthBchTo,HD.FTXthWhTo,DT.FTPdtCode	-- 00.02.00 --

			    --Update In
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty + ISNULL(Tfb.FCXtdQtyAll,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = @ptWho	
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (SELECT HD.FTXthBchTo,HD.FTLastUpdBy,HD.FTXthWhTo, DT.FTPdtCode ,SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll	-- 00.03.00 --
					    FROM TCNTPdtTbxHD HD WITH(NOLOCK)		
					    INNER JOIN TCNTPdtTbxDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo	
					    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
					    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
					    GROUP BY HD.FTXthBchTo,HD.FTLastUpdBy,HD.FTXthWhTo, DT.FTPdtCode) Tfb  ON Tfb.FTXthBchTo = STK.FTBchCode AND Tfb.FTXthWhTo = STK.FTWahCode AND Tfb.FTPdtCode = STK.FTPdtCode	-- 00.03.00 --

			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT HD.FTXthBchTo,HD.FTXthDocNo AS FTStkDocNo	-- 00.02.00 --
			    ,'1' AS FTStkType
			    ,DT.FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty,HD.FTXthWhTo AS FTWahCode,HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdSetPrice)/SUM(FCXtdQtyAll),2) AS FCStkSetPrice
			    --, ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),2) AS FCStkCostIn
			    --, ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),2) AS FCStkCostEx
			    , ROUND(SUM(DT.FCXtdNet)/SUM(FCXtdQtyAll),4) AS FCStkSetPrice -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),4) AS FCStkCostIn -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),4) AS FCStkCostEx -- 07.01.00 --
			    FROM TCNTPdtTbxDT DT with(nolock)
			    INNER JOIN TCNTPdtTbxHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTXthBchTo,HD.FTXthWhTo,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate	-- 00.02.00 --

                
                -- 07.01.00 --
		        IF @tStaAlwCostAmtTo = '1'
		        BEGIN

		            -- 07.00.00 --
		            UPDATE COST
		            SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
		            ,FCPdtQtyBal = STK.FCStkQty
		            ,FDLastUpdOn = GETDATE()
		            FROM TCNMPdtCostAvg COST With(nolock)
		            INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '1'
		            INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
					            FROM TCNTPdtStkBal STK with(nolock)
					            WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
						            AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
					            GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
		            -- 07.00.00 --

                END
                -- 07.01.00 --

		    END
		    -- 04.01.00 --

		    --insert to stock card
		    INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
		    SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
		    GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk

		    --Cost
		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*COST.FCPdtCostEx)
		    --,FCPdtQtyBal = STK.FCStkQty
		    --,FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '1'
		    --INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode

		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) - (TMP.FCStkQty*COST.FCPdtCostEx)
		    --,FCPdtQtyBal = STK.FCStkQty
		    --,FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '2'
		    --INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode
		
        
	    END	
    END
    ELSE -- 07.01.00 --
    BEGIN
        
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END

	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxBchPdtTnfIn')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxBchPdtTnfIn
GO
CREATE PROCEDURE [dbo].STP_DOCxBchPdtTnfIn
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tTrans VARCHAR(20)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTComName varchar(50), 
   FTBchCode varchar(5), 
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty float, 
   FTWahCode varchar(5), 
   FDStkDate Datetime ,
   --FCStkSetPrice decimal(18,2),
   --FCStkCostIn decimal(18,2),
   --FCStkCostEx decimal(18,2)
   FCStkSetPrice decimal(18,4), -- 07.01.00 --
   FCStkCostIn decimal(18,4), -- 07.01.00 --
   FCStkCostEx decimal(18,4) -- 07.01.00 --
   ) 
DECLARE @tStaPrc varchar(1)
DECLARE @tStaPrcStkTo varchar(1)	-- 04.01.00 --
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.01.00 --
DECLARE @tStaDoc varchar(1)      -- 07.01.00 1:เอกสารปกติ 3:ยกเลิก
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
03.01.00	25/03/2020		Em		create  
03.02.00	26/03/2020		Em		เพิ่มการตรวจสอบ DocType
03.03.00	27/03/2020		Em		แก้ไขขนาดฟิลด์ BchCode จาก 3 เป็น 5
03.04.00	13/04/2020		Em		แก้ไขปรับปรุง
04.01.00	21/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
07.00.00	04/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcBchIn'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  

	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk,'') FROM TCNTPdtTbiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)
	SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXthStaDoc,'') FROM TCNTPdtTbiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) -- 07.01.00 --
    
    IF @tStaDoc <> '3' -- 07.01.00 --
    BEGIN
	    IF @tStaPrc <> '1'
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    -- 04.01.00 --
		    --SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
		    --				INNER JOIN TCNTPdtTbiHD HD WITH(NOLOCK) ON HD.FTXthBchTo = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
		    --				WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)
        
		    -- 07.01.00 --
		    SELECT TOP 1 @tStaPrcStkTo = ISNULL(WAH.FTWahStaPrcStk,''),
			    @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTbiHD HD WITH(NOLOCK) ON HD.FTXthBchTo = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    -- 07.01.00 --

		    IF @tStaPrcStkTo = '2'
		    BEGIN
			    --Create stk balance	
			    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)		
			    SELECT DISTINCT HD.FTXthBchTo,HD.FTXthWhTo,DT.FTPdtCode,0 AS FCStkQty,	-- 03.01.00 --
			    GETDATE() AS FDLastUpdOn,HD.FTLastUpdBy,
			    GETDATE() AS FDCreateOn,HD.FTCreateBy
			    FROM TCNTPdtTbiHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTbiDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --	
			    --LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTXthBchTo = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode -- 07.01.00 --
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo -- 03.01.00 --
			    AND ISNULL(STK.FTPdtCode,'') = ''	

			    --Update balance In
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty + ISNULL(Tbi.FCXtdQtyAll,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = Tbi.FTLastUpdBy
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (SELECT HD.FTXthBchTo,HD.FTLastUpdBy,HD.FTXthWhTo, DT.FTPdtCode ,SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll -- 03.01.00 --
				    FROM TCNTPdtTbiHD HD WITH(NOLOCK)
				    INNER JOIN TCNTPdtTbiDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
				    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --	
				    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo	-- 03.01.00 --
				    GROUP BY HD.FTXthBchTo,HD.FTLastUpdBy,HD.FTXthWhTo, DT.FTPdtCode) Tbi  ON Tbi.FTXthBchTo = STK.FTBchCode AND Tbi.FTXthWhTo = STK.FTWahCode AND Tbi.FTPdtCode = STK.FTPdtCode -- 03.01.00 --

			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT HD.FTXthBchTo,HD.FTXthDocNo AS FTStkDocNo	-- 03.01.00 --
			    ,'1' AS FTStkType
			    ,DT.FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty,HD.FTXthWhTo AS FTWahCode,HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdSetPrice)/SUM(FCXtdQtyAll),2) AS FCStkSetPrice
			    --, ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),2) AS FCStkCostIn
			    --, ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),2) AS FCStkCostEx
			    , ROUND(SUM(DT.FCXtdNet)/SUM(FCXtdQtyAll),4) AS FCStkSetPrice -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),4) AS FCStkCostIn -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),4) AS FCStkCostEx -- 07.01.00 --
			    FROM TCNTPdtTbiDT DT with(nolock)
			    INNER JOIN TCNTPdtTbiHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --	
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode,HD.FTXthWhTo,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate,HD.FTXthBchTo

			    --insert to stock card
			    INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
			    SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
			    GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
			    FROM @TTmpPrcStk
            
		        --Cost
                 -- 07.01.00 --
		        IF @tStaAlwCostAmt = '1'
		        BEGIN

			        -- 07.00.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '1'
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.00.00 --

                END
                 -- 07.01.00 --
		    END
			
		    UPDATE TCNTPdtIntDTBch WITH(ROWLOCK)
		    SET FCXtdQtyRcv = ISNULL(FCXtdQtyRcv,0) + ISNULL(DTi.FCXtdQtyAll,0)
		    ,FTXtdRvtRef = HDi.FTXthDocNo 
		    ,FDLastUpdOn = GETDATE()
		    ,FTLastUpdBy = @ptWho
		    FROM TCNTPdtIntDTBch DT
		    INNER JOIN TCNTPdtTbiHD HDi WITH(NOLOCK) ON HDi.FTXthRefInt = DT.FTXthDocNo AND HDi.FTXthBchTo = DT.FTXthBchTo AND HDi.FTXthWhTo = DT.FTXthWahTo
		    INNER JOIN TCNTPdtTbiDT DTi WITH(NOLOCK) ON HDi.FTXthDocNo = DTi.FTXthDocNo AND DTi.FTPdtCode = DT.FTPdtCode
		    --WHERE HDi.FTXthBchTo = @ptBchCode AND HDi.FTXthDocNo = @ptDocNo
		    WHERE HDi.FTBchCode = @ptBchCode AND HDi.FTXthDocNo = @ptDocNo  -- 03.01.00 --
		    --AND ISNULL(DTi.FTXtdDocNoRef,'') = ''
		    AND HDi.FNXthDocType = 5  -- 2. --

		    UPDATE TCNTPdtTboHD WITH(ROWLOCK)
		    SET FTXthRefInt = @ptDocNo
		    FROM TCNTPdtTboHD HDo
		    INNER JOIN TCNTPdtTbiHD HDi WITH(NOLOCK) ON HDo.FTXthDocNo = HDi.FTXthRefInt
		    WHERE HDi.FTBchCode = @ptBchCode AND HDi.FTXthDocNo = @ptDocNo

	    END 
    END
    ELSE -- 07.01.00 --
    BEGIN
    
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.01.00 --

	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxBchPdtTnfOut')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxBchPdtTnfOut
GO
CREATE PROCEDURE [dbo].STP_DOCxBchPdtTnfOut
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tTrans VARCHAR(20)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTComName varchar(50), 
   FTBchCode varchar(5),  -- 2. --
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty float, 
   FTWahCode varchar(5), 
   FDStkDate Datetime ,
   --FCStkSetPrice decimal(18,2), -- 07.01.00 --
   --FCStkCostIn decimal(18,2), -- 07.01.00 --
   --FCStkCostEx decimal(18,2) -- 07.01.00 --
   FCStkSetPrice decimal(18,4),
   FCStkCostIn decimal(18,4),
   FCStkCostEx decimal(18,4)
   ) 
DECLARE @tStaPrc varchar(1)
DECLARE @tStaPrcStkFrm varchar(1)	-- 04.01.00 --
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.01.00 --
DECLARE @tStaDoc varchar(1)      -- 07.01.00 1:เอกสารปกติ 3:ยกเลิก
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
03.01.00	25/03/2020		Em		create  
03.02.00	27/03/2020		Em		แก้ไขขนาดฟิลด์สาขาจาก 3 เป็น 5
03.03.00	13/04/2020		Em		แก้ไขปรับปรุง
04.01.00	21/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
07.00.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcBchOut'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  

	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk,'') FROM TCNTPdtTboHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)
	SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXthStaDoc,'') FROM TCNTPdtTboHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) -- 07.01.00 --

    
    IF @tStaDoc <> '3' -- 07.01.00 --
    BEGIN
	    IF @tStaPrc <> '1'	-- 6. --
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    ---- 04.01.00 --
		    --SET @tStaPrcStkFrm = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
		    --				INNER JOIN TCNTPdtTboHD HD WITH(NOLOCK) ON HD.FTXthBchFrm = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
		    --				WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)

        
		    -- 07.01.00 --
		    SELECT TOP 1 @tStaPrcStkFrm = ISNULL(WAH.FTWahStaPrcStk,''),
			    @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTboHD HD WITH(NOLOCK) ON HD.FTXthBchFrm = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    -- 07.01.00 --

		    IF @tStaPrcStkFrm = '2'
		    BEGIN
			    --Create stk balance
			    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)	
			    SELECT DISTINCT HD.FTXthBchFrm,HD.FTXthWhFrm,DT.FTPdtCode,0 AS FCStkQty,	-- 03.03.00 --
			    GETDATE() AS FDLastUpd,HD.FTLastUpdBy,
			    GETDATE() AS FDCreateOn,HD.FTCreateBy
			    FROM TCNTPdtTboHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTboDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --	
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTXthBchFrm = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode	-- 03.03.00 --
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    AND ISNULL(STK.FTPdtCode,'') = ''

			    --Update balance Out
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty - ISNULL(Two.FCXtdQtyAll,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = Two.FTLastUpdBy
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (SELECT HD.FTXthBchFrm,HD.FTLastUpdBy,HD.FTXthWhFrm, DT.FTPdtCode ,SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll	-- 03.03.00 --
			    FROM TCNTPdtTboHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTboDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --	
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    GROUP BY HD.FTXthBchFrm,HD.FTLastUpdBy,HD.FTXthWhFrm, DT.FTPdtCode) Two  ON Two.FTXthBchFrm = STK.FTBchCode AND Two.FTXthWhFrm = STK.FTWahCode AND Two.FTPdtCode = STK.FTPdtCode	-- 03.03.00 --

			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT HD.FTXthBchFrm,HD.FTXthDocNo AS FTStkDocNo	-- 03.03.00 --
			    ,'2' AS FTStkType
			    ,DT.FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty,HD.FTXthWhFrm AS FTWahCode,HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdSetPrice)/SUM(FCXtdQtyAll),2) AS FCStkSetPrice
			    --, ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),2) AS FCStkCostIn
			    --, ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),2) AS FCStkCostEx
			    , ROUND(SUM(DT.FCXtdNet)/SUM(FCXtdQtyAll),4) AS FCStkSetPrice -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),4) AS FCStkCostIn -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),4) AS FCStkCostEx -- 07.01.00 --
			    FROM TCNTPdtTboDT DT with(nolock)
			    INNER JOIN TCNTPdtTboHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --	
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTXthBchFrm,HD.FTXthWhFrm,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate	-- 03.03.00 --

			    --insert to stock card
			    INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
			    SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
			    GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
			    FROM @TTmpPrcStk
            
                -- 07.01.00 --
		        IF @tStaAlwCostAmt = '1'
		        BEGIN
			        -- 07.00.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '2'
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.00.00 --
                END
                -- 07.01.00 --

		    END
		    -- 04.01.00 --

		    --Delete old data
		    DELETE FROM TCNTPdtIntDTBch WHERE  FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo

		    --Insert new data
		    INSERT INTO TCNTPdtIntDTBch(FTBchCode, FTXthDocNo, FNXtdSeqNo, FNXthDocType, FTXthBchTo, FTXthWahTo, 
			    FTPdtCode, FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FCXtdQty, FCXtdQtyRcv, FCXtdQtyAll,		
			    FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		    SELECT HD.FTBchCode, HD.FTXthDocNo, FNXtdSeqNo, '2', HD.FTXthBchTo, FTXthWhTo, 
			    FTPdtCode, FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FCXtdQty, 0 AS FCXtdQtyRcv, FCXtdQtyAll,		
			    GETDATE() AS FDLastUpdOn, @ptWho AS FTLastUpdBy, GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM TCNTPdtTboDT DT WITH(NOLOCK)
		    INNER JOIN TCNTPdtTboHD HD WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo 
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		
	    END	 
    END
    ELSE -- 07.01.00 --
    BEGIN
    
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.01.00 --

	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
END CATCH
GO
IF EXISTS (SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxPurchaseDNPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxPurchaseDNPrc
GO

CREATE PROCEDURE [dbo].STP_DOCxPurchaseDNPrc
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) 
    , @FNResult INT OUTPUT AS
DECLARE @tDate varchar(10)
DECLARE @tTime varchar(8)
DECLARE @TTmpPrcCost TABLE 
( 
    FTBchCode varchar(5)
    , FTPdtCode varchar(20)
    , FCPdtQty decimal(18, 4)
    , FCPdtCostIn decimal(18, 4)
    , FCPdtCostEx decimal(18, 4)
    , FCPdtCostAmt decimal(18, 4)
    , FCPdtVat decimal(18, 4)
) 
DECLARE @tStaPrcStk varchar(1)
DECLARE @tStaDoc varchar(1) -- 1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
DECLARE @nDec int
DECLARE @tAgnCode varchar(5)
DECLARE @tTrans varchar(20)
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.02.00 --
DECLARE @tStaPrcStkTo varchar(1)	-- 07.02.00 --
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	26/10/2021		Net		create  
07.01.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.02.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
-- ใบเพิ่มหนี้ ผู้จำหน่าย
-- มีผลกับ
-- ต้นทุน Avg
-- ต้นทุนรวม
SET @tTrans = 'PrcStk'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

	SET @tDate = CONVERT(VARCHAR(10), GETDATE(), 121)
	SET @tTime = CONVERT(VARCHAR(8), GETDATE(), 108)

	SET @tStaPrcStk = (SELECT TOP 1 ISNULL(FTXphStaPrcStk, '') AS FTXphStaPrcStk 
                       FROM TAPTPdHD WITH(NOLOCK) 
                       WHERE FTBchCode = @ptBchCode AND FTXphDocNo = @ptDocNo)

    SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXphStaDoc, '') AS FTXphStaDoc
                    FROM TAPTPdHD WITH(NOLOCK) 
                    WHERE FTBchCode = @ptBchCode AND FTXphDocNo = @ptDocNo)
    
	SET @tAgnCode = (SELECT TOP 1 FTAgnCode 
                     FROM TCNMBranch WITH(NOLOCK) 
                     WHERE FTBchCode = @ptBchCode)

	SET @nDec = (SELECT TOP 1 CAST(ISNULL(ISNULL(SPC.FTCfgStaUsrValue, CFG.FTSysStaUsrValue), 0) AS int)
	             FROM TSysConfig CFG
	             LEFT JOIN TCNTConfigSpc SPC ON 
                     SPC.FTSysCode = CFG.FTSysCode AND SPC.FTSysApp = CFG.FTSysApp AND SPC.FTSysKey = CFG.FTSysKey 
			             AND SPC.FTSysSeq = CFG.FTSysSeq AND SPC.FTAgnCode = @tAgnCode
	             WHERE CFG.FTSysCode = 'ADecPntSav' )

    IF @tStaDoc = '1' -- เอกสารปกติ
    BEGIN
	    IF @tStaPrcStk <> '1' --ยังไม่ประมวลผล Stock
	    BEGIN
            
		    -- 07.02.00 --
		    SELECT TOP 1 @tStaPrcStkTo = ISNULL(WAH.FTWahStaPrcStk,''),
			    @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TAPTPdHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTWahCode = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXphDocNo = @ptDocNo
		    -- 07.02.00 --
            
            -- 07.02.00 --
		    IF @tStaPrcStkTo = '2'
		    BEGIN
                -- Insert Temp
		        INSERT INTO @TTmpPrcCost
                (
                    FTBchCode, FTPdtCode, FCPdtQty, FCPdtCostIn, FCPdtCostEx, FCPdtCostAmt, FCPdtVat
                )
		        SELECT HD.FTBchCode, DT.FTPdtCode, SUM(FCXpdQtyAll) AS FCPdtQty
			    , ROUND(SUM(DT.FCXpdCostIn)/SUM(DT.FCXpdQtyAll), @nDec) AS FCStkCostIn
			    , ROUND(SUM(DT.FCXpdCostEx)/SUM(DT.FCXpdQtyAll), @nDec) AS FCStkCostEx 
                , 0, 0
		        FROM TAPTPdHD HD WITH(NOLOCK)
                INNER JOIN TAPTPdDT DT WITH(NOLOCK) ON
                    HD.FTBchCode = DT.FTBchCode AND HD.FTXphDocNo = DT.FTXphDocNo
		        WHERE HD.FTBchCode = @ptBchCode AND HD.FTXphDocNo = @ptDocNo 
                GROUP BY HD.FTBchCode, DT.FTPdtCode

                -- Insert ตัวที่ยังไม่มี
                INSERT INTO TCNMPdtCostAvg
                (
                    FTPdtCode, FCPdtCostEx, FCPdtCostIn, FCPdtCostLast, FCPdtCostAmt, FCPdtQtyBal, FDLastUpdOn
                )
                SELECT TMP.FTPdtCode, 0, 0, 0, 0, 0, GETDATE()
                FROM @TTmpPrcCost TMP
                LEFT JOIN TCNMPdtCostAvg COST WITH(NOLOCK) ON 
                    TMP.FTPdtCode = COST.FTPdtCode
                WHERE ISNULL(COST.FTPdtCode, '') = ''

                -- Update Cost
			    --UPDATE TCNMPdtCostAvg WITH(rowlock)
			    --SET FCPdtQtyBal = ISNULL(BAL.FCStkQty, 0)
       --         , FCPdtCostAmt = ISNULL(COST.FCPdtCostAmt, 0) + (ISNULL(TMP.FCPdtQty, 0)* ISNULL(TMP.FCPdtCostEx, 0))
       --         , FCPdtCostEx = CASE WHEN ISNULL(BAL.FCStkQty, 0) = 0 THEN 0
       --                              ELSE ROUND((ISNULL(COST.FCPdtCostAmt, 0) + (ISNULL(TMP.FCPdtQty, 0)* ISNULL(TMP.FCPdtCostEx, 0))) 
       --                                          / ISNULL(BAL.FCStkQty, 0) , @nDec) 
       --                              END
       --         , FCPdtCostLast = TMP.FCPdtCostEx
			    --FROM TCNMPdtCostAvg COST
			    --INNER JOIN @TTmpPrcCost TMP ON
       --             COST.FTPdtCode = TMP.FTPdtCode
		     --   INNER JOIN (
       --             SELECT FTPdtCode, SUM(FCStkQty) AS FCStkQty 
       --             FROM TCNTPdtStkBal WITH(NOLOCK) 
       --             GROUP BY FTPdtCode
       --         ) BAL ON 
       --             BAL.FTPdtCode = TMP.FTPdtCode

            
                -- Update Cost In
			    --UPDATE TCNMPdtCostAvg WITH(rowlock)
			    --SET FCPdtCostIn = ROUND(ISNULL(COST.FCPdtCostEx, 0) + (ISNULL(COST.FCPdtCostEx, 0) * VAT.FCVatRate/100), @nDec) 
			    --FROM TCNMPdtCostAvg COST
			    --INNER JOIN @TTmpPrcCost TMP ON
       --             COST.FTPdtCode = TMP.FTPdtCode
			    --INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
       --             COST.FTPdtCode = PDT.FTPdtCode
			    --INNER JOIN (
			    --	SELECT FTVatCode, MAX(FDVatStart) AS FDVatStart 
			    --	FROM TCNMVatRate WITH(NOLOCK) 
			    --	WHERE CONVERT(VARCHAR(10), FDVatStart, 121) <= CONVERT(VARCHAR(10), GETDATE(), 121) 
			    --	GROUP BY FTVatCode
       --         ) VATT ON 
       --             PDT.FTVatCode = VATT.FTVatCode
			    --INNER JOIN TCNMVatRate VAT WITH(NOLOCK) ON
       --             VATT.FTVatCode = VAT.FTVatCode AND VATT.FDVatStart = VAT.FDVatStart

			    -- 07.01.00 --
			    UPDATE TCNMPdtCostAvg with(rowlock)
			    SET FCPdtCostEx = (CASE WHEN ISNULL((FCPdtQtyBal + TMP.FCPdtQty),0) <= 0 THEN TMP.FCPdtCostEx ELSE (CASE WHEN ISNULL(COST.FCPdtCostAmt,0) <= 0 THEN TMP.FCPdtCostEx ELSE ROUND((ISNULL(COST.FCPdtCostAmt,0) + (TMP.FCPdtQty*TMP.FCPdtCostEx))/(FCPdtQtyBal + TMP.FCPdtQty),@nDec) END) END)
			    ,FCPdtCostLast = TMP.FCPdtCostEx
			    ,FDLastUpdOn = GETDATE()
			    FROM TCNMPdtCostAvg COST
			    INNER JOIN @TTmpPrcCost TMP ON COST.FTPdtCode = TMP.FTPdtCode
			    INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						    FROM TCNTPdtStkBal STK with(nolock)
						    WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							    AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						    GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode

			    --Cost
                -- 07.02.00 --
			    IF @tStaAlwCostAmt = '1'	
			    BEGIN
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE COST.FCPdtCostEx * STK.FCStkQty END),@nDec)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcCost TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.01.00 --
                END
                -- 07.02.00 --

            END
            -- 07.02.00 --

	    END --END ยังไม่ประมวลผล Stock
        
    END --END เอกสารปกติ
    ELSE -- 07.02.00 --
    BEGIN
    
	    IF @tStaPrcStk = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.02.00 --

	COMMIT TRANSACTION @tTrans
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
    SET @FNResult= -1
	SELECT ERROR_MESSAGE()
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxPurchaseInvPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxPurchaseInvPrc
GO
CREATE PROCEDURE [dbo].STP_DOCxPurchaseInvPrc
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tDate varchar(10)
DECLARE @tTime varchar(8)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTBchCode varchar(5),	-- 3. -- 
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty decimal(18,4), 
   FTWahCode varchar(5), 
   FDStkDate Datetime,
   FCStkSetPrice decimal(18,4),
   FCStkCostIn decimal(18,4),
   FCStkCostEx decimal(18,4)
   ) 
DECLARE @tStaPrc varchar(1)		-- 2. --
DECLARE @tStaPrcStkTo varchar(1)	-- 04.01.00 --
DECLARE @tTrans varchar(20)
DECLARE @nDec int -- 07.00.00 --
DECLARE @tAgnCode varchar(5)	-- 07.00.00 --
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.01.00 --
DECLARE @tStaDoc varchar(1) -- 07.01.00 -- 1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
00.01.00	12/06/2018		Em		create 
00.02.00	05/07/2019		Em		เพิ่มการตรวจสอบสถานะการประมวลผลเอกสาร 
00.03.00	24/07/2019		Em		แก้ไขขนาดฟิลด์ Branch จาก 3 เป็น 5
00.04.00	30/07/2019		Em		เพิ่ม Insert StkBal และปรับต้นทุน
00.05.00	31/07/2019		Em		แก้ไขการปรับต้นทุน
04.01.00	20/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
05.02.00	30/03/2021		Em		แก้ไขให้ตรวจสอบจำนวนที่เป็น 0
05.03.00	18/10/2021		Em		แก้ไขเรื่อง QtyBal < 0
07.00.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcStk'
BEGIN TRY
	BEGIN TRANSACTION @tTrans
	SET @tDate = CONVERT(VARCHAR(10),GETDATE(),121)
	SET @tTime = CONVERT(VARCHAR(8),GETDATE(),108)
	--SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXphStaPrcStk,'') AS FTXphStaPrcStk FROM TAPTPiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXphDocNo = @ptDocNo)	-- 2. --
	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXphStaApv,'') AS FTXphStaPrcStk FROM TAPTPiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXphDocNo = @ptDocNo)	-- 07.00.00 --
    SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXphStaDoc,'') AS FTXphStaDoc FROM TAPTPiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXphDocNo = @ptDocNo) -- 07.01.00 --

	-- 07.00.00 --
	SET @tAgnCode = (SELECT TOP 1 ISNULL(FTAgnCode,'') FROM TCNMBranch WITH(NOLOCK) WHERE FTBchCode = @ptBchCode)
	SET @nDec = (SELECT TOP 1 CAST(ISNULL(ISNULL(SPC.FTCfgStaUsrValue, CFG.FTSysStaUsrValue), 0) AS int)
	             FROM TSysConfig CFG
	             LEFT JOIN TCNTConfigSpc SPC ON 
                     SPC.FTSysCode = CFG.FTSysCode AND SPC.FTSysApp = CFG.FTSysApp AND SPC.FTSysKey = CFG.FTSysKey 
			             AND SPC.FTSysSeq = CFG.FTSysSeq AND SPC.FTAgnCode = @tAgnCode
	             WHERE CFG.FTSysCode = 'ADecPntSav' )

                 
    IF @tStaDoc = '1' -- เอกสารปกติ
    BEGIN
	    -- 07.00.00 --
	    IF @tStaPrc <> '1'	-- 2. --
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    -- 04.01.00 --
		    SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
						    INNER JOIN TAPTPiHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTWahCode = WAH.FTWahCode
						    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXphDocNo = @ptDocNo)

        
		    -- 07.01.00 --
		    SELECT TOP 1 @tStaPrcStkTo = ISNULL(WAH.FTWahStaPrcStk,''),
		        @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TAPTPiHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTWahCode = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXphDocNo = @ptDocNo
		    -- 07.01.00 --

		    IF @tStaPrcStkTo = '2'
		    BEGIN
			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT HD.FTBchCode,HD.FTXphDocNo AS FTStkDocNo
			    ,'1' AS FTStkType
			    ,DT.FTPdtCode AS FTPdtStkCode
			    , SUM(FCXpdQtyAll) AS FCStkQty,HD.FTWahCode AS FTWahCode,HD.FDXphDocDate AS FDStkDate
			    --, ROUND(SUM(DT.FCXpdNet)/SUM(FCXpdQtyAll),4) AS FCStkSetPrice
			    , ROUND(SUM(DT.FCXpdNetAfHD)/SUM(FCXpdQtyAll),4) AS FCStkSetPrice -- 07.01.00 --
			    , ROUND(SUM(DT.FCXpdCostIn)/SUM(FCXpdQtyAll),4) AS FCStkCostIn
			    , ROUND(SUM(DT.FCXpdCostEx)/SUM(FCXpdQtyAll),4) AS FCStkCostEx
			    FROM TAPTPiDT DT with(nolock)
			    INNER JOIN TAPTPiHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXphDocNo = HD.FTXphDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXphDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode,HD.FTWahCode,HD.FTXphDocNo,HD.FNXphDocType,DT.FTPdtCode,HD.FDXphDocDate

			    ----insert data to stock card
			    --INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
			    --SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
			    --GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
			    --FROM @TTmpPrcStk

			    ----update qty to stock balance
			    --UPDATE TCNTPdtStkBal with(rowlock) 
			    --SET FCStkQty= BAL.FCStkQty + TMP.FCStkQty
			    --,FDLastUpdOn = GETDATE()
			    --,FTLastUpdBy = @ptWho
			    --FROM TCNTPdtStkBal BAL
			    --INNER JOIN @TTmpPrcStk TMP ON BAL.FTPdtCode =TMP.FTPdtCode AND BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode
			    --AND ISNULL(TMP.FCStkQty,0) <> 0

			    ---- 4. --
			    ----insert to Stock balance
			    --INSERT INTO TCNTPdtStkBal with(rowlock)(FTBchCode,FTWahCode,FTPdtCode,FCStkQty,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
			    --SELECT TMP.FTBchCode,TMP.FTWahCode,TMP.FTPdtCode,TMP.FCStkQty,GETDATE(),@ptWho,GETDATE(),@ptWho
			    --FROM @TTmpPrcStk TMP
			    --LEFT JOIN TCNTPdtStkBal BAL with(NOLOCK) ON TMP.FTBchCode = BAL.FTBchCode AND TMP.FTWahCode = BAL.FTWahCode AND TMP.FTPdtCode = BAL.FTPdtCode
			    --WHERE ISNULL(BAL.FTPdtCode,'') = ''
			    ---- 4. --

			    -- 4. --
			    --Cost
			    --UPDATE TCNMPdtCostAvg with(rowlock)
			    ----SET FCPdtCostAmt = ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*TMP.FCStkCostEx)
			    ----,FCPdtCostEx = ROUND((ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*TMP.FCStkCostEx)) / STK.FCStkQty,2)
			    ----,FCPdtCostEx = (CASE WHEN ISNULL(STK.FCStkQty,0) <= 0 THEN FCPdtCostEx ELSE ROUND((ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*TMP.FCStkCostEx)) / STK.FCStkQty,2) END) -- 05.02.00 --
			    --SET FCPdtCostEx = (CASE WHEN ISNULL(STK.FCStkQty,0) <= 0 THEN TMP.FCStkCostEx ELSE (CASE WHEN ISNULL(FCPdtCostAmt,0) <= 0 THEN TMP.FCStkCostEx ELSE ROUND((ISNULL(FCPdtCostAmt,0) + (TMP.FCStkQty*TMP.FCStkCostEx))/STK.FCStkQty,4) END) END) -- 05.03.00 --
			    --,FCPdtCostLast = TMP.FCStkCostEx
			    --,FCPdtQtyBal = STK.FCStkQty
			    --,FDLastUpdOn = GETDATE()
			    --FROM TCNMPdtCostAvg COST
			    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			    ----INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode
			    ---- 5. --
			    --INNER JOIN (SELECT FTPdtCode,SUM(FCStkQty) AS FCStkQty FROM TCNTPdtStkBal with(nolock) WHERE FTBchCode = @ptBchCode GROUP BY FTPdtCode) STK ON COST.FTPdtCode = STK.FTPdtCode
		
			    --UPDATE TCNMPdtCostAvg with(rowlock)
			    --SET FCPdtCostIn = ROUND(ISNULL(FCPdtCostEx,0) + (ISNULL(FCPdtCostEx,0) * VAT.FCVatRate/100),4) 
			    --,FCPdtCostAmt = ROUND((CASE WHEN FCPdtQtyBal <= 0 THEN 0 ELSE FCPdtCostEx * FCPdtQtyBal END),4)	-- 05.03.00 --
			    --,FDLastUpdOn = GETDATE()
			    --FROM TCNMPdtCostAvg COST
			    --INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			    --INNER JOIN TCNMPdt PDT with(nolock) ON COST.FTPdtCode = PDT.FTPdtCode
			    --INNER JOIN (
			    --	SELECT FTVatCode,MAX(FDVatStart) AS FDVatStart 
			    --	FROM TCNMVatRate with(nolock) 
			    --	WHERE CONVERT(VARCHAR(10),FDVatStart,121) < CONVERT(VARCHAR(10),GETDATE(),121) 
			    --	GROUP BY FTVatCode) VATT ON PDT.FTVatCode = VATT.FTVatCode
			    --INNER JOIN TCNMVatRate VAT with(nolock) ON VATT.FTVatCode = VAT.FTVatCode AND VATT.FDVatStart = VAT.FDVatStart
			    ---- 5. --

			    INSERT INTO TCNMPdtCostAvg(FTPdtCode,FCPdtCostEx,FCPdtCostIn,FCPdtCostLast,FCPdtCostAmt,FCPdtQtyBal,FDLastUpdOn)
			    SELECT TMP.FTPdtCode,FCStkCostEx,FCStkCostIn,FCStkCostEx,(FCStkQty*FCStkCostEx) AS FCStkCostAmt,FCStkQty,GETDATE()
			    FROM @TTmpPrcStk TMP
			    LEFT JOIN TCNMPdtCostAvg COST with(nolock) ON TMP.FTPdtCode = COST.FTPdtCode
			    WHERE ISNULL(COST.FTPdtCode,'') = ''
			    -- 4. --
            
			    --Cost
			    IF @tStaAlwCostAmt = '1' -- 07.01.00 --
			    BEGIN
			        -- 07.01.00 --
			        UPDATE TCNMPdtCostAvg with(rowlock)
			        SET FCPdtCostEx = (CASE WHEN ISNULL((FCPdtQtyBal + TMP.FCStkQty),0) <= 0 THEN TMP.FCStkCostEx ELSE (CASE WHEN ISNULL(COST.FCPdtCostAmt,0) <= 0 THEN TMP.FCStkCostEx ELSE ROUND((ISNULL(COST.FCPdtCostAmt,0) + (TMP.FCStkQty*TMP.FCStkCostEx))/(FCPdtQtyBal + TMP.FCStkQty),@nDec) END) END)
			        ,FCPdtCostLast = TMP.FCStkCostEx
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode

			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE COST.FCPdtCostEx * STK.FCStkQty END),@nDec)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.01.00 --
                END
                -- 07.01.00 --

		    END
		
		    -- 5. --
		    UPDATE TCNMPdtSpl with(rowlock)
		    SET FCSplLastPrice = DT.FCXpdSetPrice
		    FROM TCNMPdtSpl SPL
		    INNER JOIN TAPTPiHD HD with(nolock) ON SPL.FTSplCode = HD.FTSplCode
		    INNER JOIN TAPTPiDT DT with(nolock) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXphDocNo = DT.FTXphDocNo AND SPL.FTPdtCode = DT.FTPdtCode AND SPL.FTBarCode = DT.FTXpdBarCode
		    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXphDocNo =@ptDocNo
		    -- 5. --
	    END		-- 2. --
    END
	ELSE -- 07.01.00 --
    BEGIN
    
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.01.00 --

	COMMIT TRANSACTION @tTrans
	SET @FNResult= 0
END TRY
BEGIN CATCH
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
	SELECT ERROR_MESSAGE()
END CATCH
GO
IF EXISTS(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxWahPdtTnf')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxWahPdtTnf
GO

CREATE PROCEDURE [dbo].STP_DOCxWahPdtTnf
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) , @FNResult INT OUTPUT AS
DECLARE @tTrans VARCHAR(20)
DECLARE @TTmpPrcStk TABLE 
( 
    FTBchCode varchar(5)
    , FTStkDocNo varchar(20)
    , FTStkType varchar(1)
    , FTPdtCode varchar(20)
    , FCStkQty decimal(18, 4)
    , FTWahCode varchar(5)
    , FDStkDate Datetime
    , FCStkSetPrice decimal(18, 4)
    , FCStkCostIn decimal(18, 4)
    , FCStkCostEx decimal(18, 4)
) 
DECLARE @tStaPrc varchar(1)
DECLARE @tStaPrcStkFrm varchar(1)
DECLARE @tStaPrcStkTo varchar(1)
DECLARE @TTmpPrcStkFhn TABLE 
( 
    FTBchCode varchar(5)
    , FTStfDocNo varchar(20)
    , FTStfType varchar(1)
    , FTPdtCode varchar(20)
    , FTFhnRefCode varchar(30)
    , FCStfQty decimal(18, 4)
    , FTWahCode varchar(5)
    , FDStfDate Datetime
    , FCStfSetPrice decimal(18, 4)
    , FCStfCostIn decimal(18, 4)
    , FCStfCostEx decimal(18, 4)
) 	-- 06.01.00 --
-- 07.00.00 --
DECLARE @tStaDoc varchar(1) -- 1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
DECLARE @nDec int
DECLARE @tAgnCode varchar(5)
-- 07.00.00 --
DECLARE @tStaAlwCostAmtFrm varchar(1)	-- 07.02.00 --
DECLARE @tStaAlwCostAmtTo varchar(1)	-- 07.02.00 --
/*---------------------------------------------------------------------
Document History
Versin		Date			User	Remark
00.01.00	28/03/2019		Em		create  
00.02.00	13/06/2019		Em		แก้ไขชื่อตาราง
00.03.00	05/07/2019		Em		เพิ่มการตรวจสอบสถานะการประมวลผลเอกสาร
00.04.00	22/07/2019		Em		แก้ไขขนาดฟิลด์ BchCode จาก 3 เป็น 5
00.05.00	30/07/2019		Em		เพิ่มอัพเดทต้นทุน
00.06.00	31/07/2019		Em		แก้ไขปรับปรุง
00.07.00	20/09/2019		Em		แก้ไขปรับปรุง
04.01.00	19/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
06.01.00	04/05/2021		Em		แก้ไขให้รองรับสินค้าแฟชั่น
06.02.00	01/07/2021		Em		แก้ไข Rounding ให้เป็น 4 หลัก
06.03.00	08/08/2021		Em		แก้ไข Process ต้นทุน
07.00.00	27/10/2021		Net		เพิ่มขา ยกเลิก
07.01.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.02.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcWahTnf'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  

	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk, '') AS FTXthStaPrcStk 
                    FROM TCNTPdtTwxHD with(nolock) 
                    WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)	-- 3. --
    
    SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXthStaDoc, '') AS FTXphStaDoc
                    FROM TCNTPdtTwxHD WITH(NOLOCK) 
                    WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)
    
	SET @tAgnCode = (SELECT TOP 1 FTAgnCode 
                     FROM TCNMBranch WITH(NOLOCK) 
                     WHERE FTBchCode = @ptBchCode)

	SET @nDec = (SELECT TOP 1 CAST(ISNULL(ISNULL(SPC.FTCfgStaUsrValue, CFG.FTSysStaUsrValue), 0) AS int)
	             FROM TSysConfig CFG
	             LEFT JOIN TCNTConfigSpc SPC ON 
                     SPC.FTSysCode = CFG.FTSysCode AND SPC.FTSysApp = CFG.FTSysApp AND SPC.FTSysKey = CFG.FTSysKey 
                     AND SPC.FTSysSeq = CFG.FTSysSeq AND SPC.FTAgnCode = @tAgnCode
	             WHERE CFG.FTSysCode = 'ADecPntSav' )

    IF @tStaDoc = '1' -- เอกสารปกติ -- 07.00.00 --
    BEGIN
	    IF @tStaPrc <> '1'	-- 3. -- ยังไม่ประมวลผล Stock
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    -- 06.01.00 --
		    DELETE TFHTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStfDocNo = @ptDocNo
		    -- 06.01.00 --

		    ---- 04.01.00 --
		    --SET @tStaPrcStkFrm = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk, '') 
      --                            FROM TCNMWaHouse WAH WITH(NOLOCK)
						--          INNER JOIN TCNTPdtTwxHD HD WITH(NOLOCK) ON 
      --                                HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
						--          WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)

		    --SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk, '') 
      --                           FROM TCNMWaHouse WAH WITH(NOLOCK)
						--         INNER JOIN TCNTPdtTwxHD HD WITH(NOLOCK) ON 
      --                               HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
						--         WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)
            
		    -- 07.02.00 --
		    SELECT TOP 1 @tStaPrcStkFrm = ISNULL(WAH.FTWahStaPrcStk,'') ,
			    @tStaAlwCostAmtFrm = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTwxHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo

		    SELECT TOP 1 @tStaPrcStkTo = ISNULL(WAH.FTWahStaPrcStk,'') ,
			    @tStaAlwCostAmtTo = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTwxHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    -- 07.02.00 --

		    IF @tStaPrcStkFrm = '2' -- คลังต้นทางตัด Stock
		    BEGIN
			    --Create stk balance
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT HD.FTBchCode, HD.FTXthWhFrm, DT.FTPdtCode, 0 AS FCStkQty
			    , GETDATE() AS FDLastUpd, @ptWho	-- 7. --
			    , GETDATE() AS FDCreateOn, @ptWho	-- 7. --
			    FROM TCNTPdtTwxHD HD WITH(NOLOCK)		--4.--
			    INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON 
                    HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo		--4.-
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode, '') = ''
			
			    --Update Out
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty - ISNULL(Tfw.FCXtdQtyAll, 0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho	-- 7. --
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhFrm, DT.FTPdtCode , SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll
			        FROM TCNTPdtTwxHD HD WITH(NOLOCK)
			        INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON 
                        HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			        WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			        GROUP BY HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhFrm, DT.FTPdtCode
                ) Tfw ON 
                    Tfw.FTBchCode = STK.FTBchCode AND Tfw.FTXthWhFrm = STK.FTWahCode AND Tfw.FTPdtCode = STK.FTPdtCode

			    INSERT INTO @TTmpPrcStk 
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTPdtCode, FCStkQty, FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
			    , '2' AS FTStkType
			    , FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty, HD.FTXthWhFrm AS FTWahCode, HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdNet)/SUM(FCXtdQtyAll), 4) AS FCStkSetPrice	-- 06.02.00 --
			    , ROUND(SUM(DT.FCXtdNet), 4) AS FCStkSetPrice	-- 07.02.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll), 4) AS FCStkCostIn	-- 06.02.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll), 4) AS FCStkCostEx	-- 06.02.00 --
			    FROM TCNTPdtTwxDT DT with(nolock)
			    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                    DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode, HD.FTXthWhFrm, HD.FTXthDocNo, DT.FTPdtCode, HD.FDXthDocDate

			    -- 06.01.00 --
			    IF EXISTS (SELECT FTPdtCode FROM TCNTPdtTwxDTFhn with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) 
                BEGIN
				    --insert data to Temp
				    INSERT INTO @TTmpPrcStkFhn 
                    (
                        FTBchCode, FTStfDocNo, FTStfType, FTPdtCode, FTFhnRefCode, FCStfQty, FTWahCode, FDStfDate, FCStfSetPrice, FCStfCostIn, FCStfCostEx
                    )
				    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
				    , '2' AS FTStkType
				    , DT.FTPdtCode AS FTPdtCode, DTF.FTFhnRefCode
				    , SUM(DTF.FCXtdQty * DT.FCXtdFactor) AS FCStkQty, HD.FTXthWhFrm AS FTWahCode, HD.FDXthDocDate AS FDStkDate
				    --, ROUND(SUM(FCXtdNet)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkSetPrice	-- 06.02.00 --
				    , ROUND(SUM(FCXtdNet), 4) AS FCStkSetPrice	-- 07.02.00 --
				    , ROUND(SUM(DT.FCXtdCostIn)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostIn	-- 06.02.00 --
				    , ROUND(SUM(DT.FCXtdCostEx)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostEx	-- 06.02.00 --
				    FROM TCNTPdtTwxDT DT with(nolock)
				    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                        DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
				    INNER JOIN TCNTPdtTwxDTFhn DTF with(nolock) ON 
                        DT.FTBchCode = DTF.FTBchCode AND DT.FTXthDocNo = DTF.FTXthDocNo AND DT.FNXtdSeqNo = DTF.FNXtdSeqNo AND DT.FTPdtCode = DTF.FTPdtCode
				    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
				    GROUP BY HD.FTBchCode, HD.FTXthWhFrm, HD.FTXthDocNo, DT.FTPdtCode, DTF.FTFhnRefCode, HD.FDXthDocDate

				    IF EXISTS (SELECT FTPdtCode FROM @TTmpPrcStkFhn) 
                    BEGIN

					    --Update Out
					    UPDATE TFHTPdtStkBal WITH(ROWLOCK)
					    SET FCStfBal = ISNULL(STK.FCStfBal, 0) - ISNULL(TMP.FCStfQty, 0)
					    , FDLastUpdOn = GETDATE()
					    , FTLastUpdBy = @ptWho	
					    FROM TFHTPdtStkBal STK
					    INNER JOIN @TTmpPrcStkFhn TMP ON 
                            STK.FTBchCode = TMP.FTBchCode AND STK.FTWahCode = TMP.FTWahCode AND STK.FTPdtCode = TMP.FTPdtCode AND STK.FTFhnRefCode = TMP.FTFhnRefCode

					    --Create stk balance
					    INSERT INTO TFHTPdtStkBal
                        (
                            FTBchCode, FTWahCode, FTPdtCode, FTFhnRefCode, FCStfBal, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                        )
					    SELECT TMP.FTBchCode, TMP.FTWahCode, TMP.FTPdtCode, TMP.FTFhnRefCode, TMP.FCStfQty*(-1) AS FCStkQty
					    , GETDATE() AS FDLastUpdOn, @ptWho AS FTLastUpdBy, GETDATE() AS FDCreateOn, @ptWho FTCreateBy
					    FROM @TTmpPrcStkFhn TMP
					    LEFT JOIN TFHTPdtStkBal BAL WITH(NOLOCK) ON 
                            BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode AND BAL.FTPdtCode = TMP.FTPdtCode AND BAL.FTFhnRefCode = TMP.FTFhnRefCode
					    WHERE ISNULL(BAL.FTFhnRefCode, '') = ''

					    --insert stk card
					    INSERT INTO TFHTPdtStkCrd
                        ( 
                            FTBchCode, FDStfDate, FTStfDocNo, FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty, FCStfSetPrice
                            , FCStfCostIn, FCStfCostEx, FDCreateOn, FTCreateBy
                        )		--3.--
					    SELECT  FTBchCode, FDStfDate, FTStfDocNo, FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty, FCStfSetPrice
                            , FCStfCostIn, FCStfCostEx, GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy		--3.--
					    FROM @TTmpPrcStkFhn
				    END
			    END
			    -- 06.01.00 --

                
		        --Cost
                -- 07.02.00 --
		        IF @tStaAlwCostAmtFrm = '1'
                BEGIN
			        -- 07.01.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.01.00 --
                END
                -- 07.02.00 --

		    END --End คลังต้นทางตัด Stock

		    IF @tStaPrcStkTo = '2' -- คลังปลายทางตัด Stock
		    BEGIN

			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT HD.FTBchCode, HD.FTXthWhTo, DT.FTPdtCode, 0 AS FCStkQty 	-- 7. --
			    , GETDATE() AS FDLastUpdOn, @ptWho 	-- 7. --
			    , GETDATE() AS FDCreateOn, @ptWho		-- 7. --
			    FROM TCNTPdtTwxHD HD WITH(NOLOCK)		--4.--
			    INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON
                    HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo		--4.--
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode, '') = ''
			    GROUP BY HD.FTBchCode, HD.FTXthWhTo, DT.FTPdtCode	-- 7. --

			    --Update In
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty + ISNULL(Tfw.FCXtdQtyAll, 0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho	-- 7. --
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhTo, DT.FTPdtCode , SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll
					FROM TCNTPdtTwxHD HD WITH(NOLOCK)		--4.--
					INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON 
                        HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo	--4.--
					INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
					WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
					GROUP BY HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhTo, DT.FTPdtCode
                ) Tfw  ON 
                    Tfw.FTBchCode = STK.FTBchCode AND Tfw.FTXthWhTo = STK.FTWahCode AND Tfw.FTPdtCode = STK.FTPdtCode

			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk 
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTPdtCode, FCStkQty, FTWahCode, FDStkDate
                    , FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
			    , '1' AS FTStkType
			    , FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty, HD.FTXthWhTo AS FTWahCode, HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdNet)/SUM(FCXtdQtyAll), 4) AS FCStkSetPrice	-- 06.02.00 --
			    , ROUND(SUM(FCXtdNet), 4) AS FCStkSetPrice	-- 07.02.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll), 4) AS FCStkCostIn	-- 06.02.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll), 4) AS FCStkCostEx	-- 06.02.00 --
			    FROM TCNTPdtTwxDT DT with(nolock)
			    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                    DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode, HD.FTXthWhTo, HD.FTXthDocNo, DT.FTPdtCode, HD.FDXthDocDate

			    -- 06.01.00 --
			    IF EXISTS (SELECT FTPdtCode FROM TCNTPdtTwxDTFhn with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) 
                BEGIN

				    --insert data to Temp
				    DELETE FROM @TTmpPrcStkFhn

				    INSERT INTO @TTmpPrcStkFhn 
                    (
                        FTBchCode, FTStfDocNo, FTStfType, FTPdtCode, FTFhnRefCode, FCStfQty, FTWahCode, FDStfDate, FCStfSetPrice, FCStfCostIn, FCStfCostEx
                    )
				    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
				    , '1' AS FTStkType
				    , DT.FTPdtCode AS FTPdtCode, DTF.FTFhnRefCode
				    , SUM(DTF.FCXtdQty * DT.FCXtdFactor) AS FCStkQty, HD.FTXthWhTo AS FTWahCode, HD.FDXthDocDate AS FDStkDate
				    --, ROUND(SUM(FCXtdNet)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkSetPrice	-- 06.02.00 --
				    , ROUND(SUM(FCXtdNet), 4) AS FCStkSetPrice	-- 07.02.00 --
				    , ROUND(SUM(DT.FCXtdCostIn)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostIn	-- 06.02.00 --
				    , ROUND(SUM(DT.FCXtdCostEx)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostEx	-- 06.02.00 --
				    FROM TCNTPdtTwxDT DT with(nolock)
				    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                        DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
				    INNER JOIN TCNTPdtTwxDTFhn DTF with(nolock) ON 
                        DT.FTBchCode = DTF.FTBchCode AND DT.FTXthDocNo = DTF.FTXthDocNo AND DT.FNXtdSeqNo = DTF.FNXtdSeqNo AND DT.FTPdtCode = DTF.FTPdtCode
				    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
				    GROUP BY HD.FTBchCode, HD.FTXthWhTo, HD.FTXthDocNo, DT.FTPdtCode, DTF.FTFhnRefCode, HD.FDXthDocDate

				    IF EXISTS( SELECT FTPdtCode FROM @TTmpPrcStkFhn) 
                    BEGIN

					    --Update Out
					    UPDATE TFHTPdtStkBal WITH(ROWLOCK)
					    SET FCStfBal = ISNULL(STK.FCStfBal, 0) + ISNULL(TMP.FCStfQty, 0)
					    , FDLastUpdOn = GETDATE()
					    , FTLastUpdBy = @ptWho	
					    FROM TFHTPdtStkBal STK
					    INNER JOIN @TTmpPrcStkFhn TMP ON 
                            STK.FTBchCode = TMP.FTBchCode AND STK.FTWahCode = TMP.FTWahCode AND STK.FTPdtCode = TMP.FTPdtCode AND STK.FTFhnRefCode = TMP.FTFhnRefCode

					    --Create stk balance
					    INSERT INTO TFHTPdtStkBal
                        (
                            FTBchCode, FTWahCode, FTPdtCode, FTFhnRefCode, FCStfBal, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                        )
					    SELECT TMP.FTBchCode, TMP.FTWahCode, TMP.FTPdtCode, TMP.FTFhnRefCode, TMP.FCStfQty
					    , GETDATE() AS FDLastUpdOn, @ptWho AS FTLastUpdBy, GETDATE() AS FDCreateOn, @ptWho FTCreateBy
					    FROM @TTmpPrcStkFhn TMP
					    LEFT JOIN TFHTPdtStkBal BAL WITH(NOLOCK) ON 
                            BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode AND BAL.FTPdtCode = TMP.FTPdtCode AND BAL.FTFhnRefCode = TMP.FTFhnRefCode
					    WHERE ISNULL(BAL.FTFhnRefCode, '') = ''

					    --insert stk card
					    INSERT INTO TFHTPdtStkCrd
                        ( 
                            FTBchCode, FDStfDate, FTStfDocNo, FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty
                            , FCStfSetPrice, FCStfCostIn, FCStfCostEx, FDCreateOn, FTCreateBy
                        )		--3.--
					    SELECT  FTBchCode, FDStfDate, FTStfDocNo, FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty
                        , FCStfSetPrice, FCStfCostIn, FCStfCostEx, GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy		--3.--
					    FROM @TTmpPrcStkFhn
				    END
			    END
			    -- 06.01.00 --
                
		        --Cost
                -- 07.02.00 --
		        IF @tStaAlwCostAmtTo = '1'
                BEGIN
			        -- 07.01.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.01.00 --
                END
                -- 07.02.00 --

		    END --End คลังปลายทางตัด Stock
		    -- 04.01.00 --

		    --insert to stock card
		    INSERT INTO TCNTPdtStkCrd with(rowlock)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FDCreateOn, FTCreateBy
            )
		    SELECT FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx
		    , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk

		    -- 5. --
		    --Cost
		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt, 0) + (TMP.FCStkQty*COST.FCPdtCostEx)
		    ----, FCPdtQtyBal = STK.FCStkQty
		    --, FCPdtQtyBal = FCPdtQtyBal + TMP.FCStkQty	-- 06.03.00 --
		    --, FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON 
      --          COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '1'
		    ----INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode

		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt, 0) - (TMP.FCStkQty*COST.FCPdtCostEx)
		    ----, FCPdtQtyBal = STK.FCStkQty
		    --, FCPdtQtyBal = FCPdtQtyBal - TMP.FCStkQty	-- 06.03.00 --
		    --, FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON 
      --          COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '2'
		    ----INNER JOIN TCNTPdtStkBal STK with(nolock) ON COST.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode = @ptBchCode
		    ---- 5. --
            

	    END	-- 3. --End ยังไม่ประมวลผล Stock
    
    END --END เอกสารปกติ
    ELSE -- เอกสารยกเลิก -- 07.00.00 --
    BEGIN
	    IF @tStaPrc = '1'	-- ประมวลผล Stock แล้ว
	    BEGIN
        
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo + 'C' 

		    DELETE TFHTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStfDocNo = @ptDocNo + 'C' 

		    SET @tStaPrcStkFrm = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk, '') 
                                  FROM TCNMWaHouse WAH WITH(NOLOCK)
						          INNER JOIN TCNTPdtTwxHD HD WITH(NOLOCK) ON 
                                      HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
						          WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)

		    SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk, '') 
                                 FROM TCNMWaHouse WAH WITH(NOLOCK)
						         INNER JOIN TCNTPdtTwxHD HD WITH(NOLOCK) ON 
                                     HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
						         WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)

		    IF @tStaPrcStkFrm = '2' -- คลังต้นทางตัด Stock
		    BEGIN

			    --Create stk balance
			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT HD.FTBchCode, HD.FTXthWhFrm, DT.FTPdtCode, 0 AS FCStkQty
			    , GETDATE() AS FDLastUpd, @ptWho
			    , GETDATE() AS FDCreateOn, @ptWho
			    FROM TCNTPdtTwxHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON 
                    HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			        AND ISNULL(STK.FTPdtCode, '') = ''
			
			    --Update In
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty + ISNULL(Tfw.FCXtdQtyAll, 0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhFrm, DT.FTPdtCode , SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll
			        FROM TCNTPdtTwxHD HD WITH(NOLOCK)
			        INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON 
                        HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
			        WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			        GROUP BY HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhFrm, DT.FTPdtCode
                ) Tfw ON 
                    Tfw.FTBchCode = STK.FTBchCode AND Tfw.FTXthWhFrm = STK.FTWahCode AND Tfw.FTPdtCode = STK.FTPdtCode

			    INSERT INTO @TTmpPrcStk 
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTPdtCode, FCStkQty, FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
			    , '1' AS FTStkType
			    , FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty, HD.FTXthWhFrm AS FTWahCode, HD.FDXthDocDate AS FDStkDate
			    , ROUND(SUM(FCXtdNet)/SUM(FCXtdQtyAll), 4) AS FCStkSetPrice
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll), 4) AS FCStkCostIn
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll), 4) AS FCStkCostEx
			    FROM TCNTPdtTwxDT DT with(nolock)
			    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                    DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode, HD.FTXthWhFrm, HD.FTXthDocNo, DT.FTPdtCode, HD.FDXthDocDate

                --สินค้า Fashion
			    IF EXISTS (SELECT FTPdtCode FROM TCNTPdtTwxDTFhn with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) 
                BEGIN

				    --insert data to Temp
				    INSERT INTO @TTmpPrcStkFhn 
                    (
                        FTBchCode, FTStfDocNo, FTStfType, FTPdtCode, FTFhnRefCode, FCStfQty, FTWahCode, FDStfDate, FCStfSetPrice, FCStfCostIn, FCStfCostEx
                    )
				    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
				    , '1' AS FTStkType
				    , DT.FTPdtCode AS FTPdtCode, DTF.FTFhnRefCode
				    , SUM(DTF.FCXtdQty * DT.FCXtdFactor) AS FCStkQty, HD.FTXthWhFrm AS FTWahCode, HD.FDXthDocDate AS FDStkDate
				    , ROUND(SUM(FCXtdNet)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkSetPrice
				    , ROUND(SUM(DT.FCXtdCostIn)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostIn
				    , ROUND(SUM(DT.FCXtdCostEx)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostEx
				    FROM TCNTPdtTwxDT DT with(nolock)
				    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                        DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
				    INNER JOIN TCNTPdtTwxDTFhn DTF with(nolock) ON 
                        DT.FTBchCode = DTF.FTBchCode AND DT.FTXthDocNo = DTF.FTXthDocNo AND DT.FNXtdSeqNo = DTF.FNXtdSeqNo AND DT.FTPdtCode = DTF.FTPdtCode
				    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
				    GROUP BY HD.FTBchCode, HD.FTXthWhFrm, HD.FTXthDocNo, DT.FTPdtCode, DTF.FTFhnRefCode, HD.FDXthDocDate

				    IF EXISTS (SELECT FTPdtCode FROM @TTmpPrcStkFhn) 
                    BEGIN

					    --Update In
					    UPDATE TFHTPdtStkBal WITH(ROWLOCK)
					    SET FCStfBal = ISNULL(STK.FCStfBal, 0) + ISNULL(TMP.FCStfQty, 0)
					    , FDLastUpdOn = GETDATE()
					    , FTLastUpdBy = @ptWho	
					    FROM TFHTPdtStkBal STK
					    INNER JOIN @TTmpPrcStkFhn TMP ON 
                            STK.FTBchCode = TMP.FTBchCode AND STK.FTWahCode = TMP.FTWahCode AND STK.FTPdtCode = TMP.FTPdtCode AND STK.FTFhnRefCode = TMP.FTFhnRefCode

					    --Create stk balance
					    INSERT INTO TFHTPdtStkBal
                        (
                            FTBchCode, FTWahCode, FTPdtCode, FTFhnRefCode, FCStfBal, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                        )
					    SELECT TMP.FTBchCode, TMP.FTWahCode, TMP.FTPdtCode, TMP.FTFhnRefCode, TMP.FCStfQty AS FCStkQty
					    , GETDATE() AS FDLastUpdOn, @ptWho AS FTLastUpdBy, GETDATE() AS FDCreateOn, @ptWho FTCreateBy
					    FROM @TTmpPrcStkFhn TMP
					    LEFT JOIN TFHTPdtStkBal BAL WITH(NOLOCK) ON 
                            BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode AND BAL.FTPdtCode = TMP.FTPdtCode AND BAL.FTFhnRefCode = TMP.FTFhnRefCode
					    WHERE ISNULL(BAL.FTFhnRefCode, '') = ''

					    --insert stk card
					    INSERT INTO TFHTPdtStkCrd
                        ( 
                            FTBchCode, FDStfDate, FTStfDocNo, FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty, FCStfSetPrice
                            , FCStfCostIn, FCStfCostEx, FDCreateOn, FTCreateBy
                        )		--3.--
					    SELECT  FTBchCode, FDStfDate, FTStfDocNo + 'C' , FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty, FCStfSetPrice
                        , FCStfCostIn, FCStfCostEx, GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy		--3.--
					    FROM @TTmpPrcStkFhn
				    END
			    END

                
		        --Cost
                -- 07.02.00 --
		        IF @tStaAlwCostAmtFrm = '1'
                BEGIN
			        -- 07.01.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.01.00 --
                END

		    END --End คลังต้นทางตัด Stock

		    IF @tStaPrcStkTo = '2' -- คลังปลายทางตัด Stock
		    BEGIN

			    INSERT INTO TCNTPdtStkBal
                (
                    FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                )
			    SELECT DISTINCT HD.FTBchCode, HD.FTXthWhTo, DT.FTPdtCode, 0 AS FCStkQty
			    , GETDATE() AS FDLastUpdOn, @ptWho
			    , GETDATE() AS FDCreateOn, @ptWho	
			    FROM TCNTPdtTwxHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON 
                    HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                    HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    AND ISNULL(STK.FTPdtCode, '') = ''
			    GROUP BY HD.FTBchCode, HD.FTXthWhTo, DT.FTPdtCode

			    --Update Out
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty - ISNULL(Tfw.FCXtdQtyAll, 0)
			    , FDLastUpdOn = GETDATE()
			    , FTLastUpdBy = @ptWho
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (
                    SELECT HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhTo, DT.FTPdtCode , SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll
                    FROM TCNTPdtTwxHD HD WITH(NOLOCK)	
					INNER JOIN TCNTPdtTwxDT DT WITH(NOLOCK) ON 
                        HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
					INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                        PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
					WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
					GROUP BY HD.FTBchCode, HD.FTLastUpdBy, HD.FTXthWhTo, DT.FTPdtCode
                ) Tfw  ON 
                    Tfw.FTBchCode = STK.FTBchCode AND Tfw.FTXthWhTo = STK.FTWahCode AND Tfw.FTPdtCode = STK.FTPdtCode

			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk
                (
                    FTBchCode, FTStkDocNo, FTStkType, FTPdtCode, FCStkQty, FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
                )
			    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
			    , '2' AS FTStkType
			    , FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty, HD.FTXthWhTo AS FTWahCode, HD.FDXthDocDate AS FDStkDate
			    , ROUND(SUM(FCXtdNet)/SUM(FCXtdQtyAll), 4) AS FCStkSetPrice
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll), 4) AS FCStkCostIn
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll), 4) AS FCStkCostEx
			    FROM TCNTPdtTwxDT DT with(nolock)
			    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                    DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode, HD.FTXthWhTo, HD.FTXthDocNo, DT.FTPdtCode, HD.FDXthDocDate

                -- สินค้า Fashion
			    IF EXISTS (SELECT FTPdtCode FROM TCNTPdtTwxDTFhn with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) 
                BEGIN

				    --insert data to Temp
				    DELETE FROM @TTmpPrcStkFhn

				    INSERT INTO @TTmpPrcStkFhn 
                    (
                        FTBchCode, FTStfDocNo, FTStfType, FTPdtCode, FTFhnRefCode, FCStfQty, FTWahCode, FDStfDate, FCStfSetPrice, FCStfCostIn, FCStfCostEx
                    )
				    SELECT HD.FTBchCode, HD.FTXthDocNo AS FTStkDocNo
				    , '2' AS FTStkType
				    , DT.FTPdtCode AS FTPdtCode, DTF.FTFhnRefCode
				    , SUM(DTF.FCXtdQty * DT.FCXtdFactor) AS FCStkQty, HD.FTXthWhTo AS FTWahCode, HD.FDXthDocDate AS FDStkDate
				    , ROUND(SUM(FCXtdNet)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkSetPrice
				    , ROUND(SUM(DT.FCXtdCostIn)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostIn
				    , ROUND(SUM(DT.FCXtdCostEx)/SUM(DTF.FCXtdQty * DT.FCXtdFactor), 4) AS FCStkCostEx
				    FROM TCNTPdtTwxDT DT with(nolock)
				    INNER JOIN TCNTPdtTwxHD HD with(nolock) ON 
                        DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
				    INNER JOIN TCNTPdtTwxDTFhn DTF with(nolock) ON 
                        DT.FTBchCode = DTF.FTBchCode AND DT.FTXthDocNo = DTF.FTXthDocNo AND DT.FNXtdSeqNo = DTF.FNXtdSeqNo AND DT.FTPdtCode = DTF.FTPdtCode
				    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
				    GROUP BY HD.FTBchCode, HD.FTXthWhTo, HD.FTXthDocNo, DT.FTPdtCode, DTF.FTFhnRefCode, HD.FDXthDocDate

				    IF EXISTS(SELECT FTPdtCode FROM @TTmpPrcStkFhn) 
                    BEGIN

					    --Update Out
					    UPDATE TFHTPdtStkBal WITH(ROWLOCK)
					    SET FCStfBal = ISNULL(STK.FCStfBal, 0) - ISNULL(TMP.FCStfQty, 0)
					    , FDLastUpdOn = GETDATE()
					    , FTLastUpdBy = @ptWho	
					    FROM TFHTPdtStkBal STK
					    INNER JOIN @TTmpPrcStkFhn TMP ON 
                            STK.FTBchCode = TMP.FTBchCode AND STK.FTWahCode = TMP.FTWahCode AND STK.FTPdtCode = TMP.FTPdtCode AND STK.FTFhnRefCode = TMP.FTFhnRefCode

					    --Create stk balance
					    INSERT INTO TFHTPdtStkBal
                        (
                            FTBchCode, FTWahCode, FTPdtCode, FTFhnRefCode, FCStfBal, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
                        )
					    SELECT TMP.FTBchCode, TMP.FTWahCode, TMP.FTPdtCode, TMP.FTFhnRefCode, TMP.FCStfQty * (-1)
					    , GETDATE() AS FDLastUpdOn, @ptWho AS FTLastUpdBy, GETDATE() AS FDCreateOn, @ptWho FTCreateBy
					    FROM @TTmpPrcStkFhn TMP
					    LEFT JOIN TFHTPdtStkBal BAL WITH(NOLOCK) ON 
                            BAL.FTBchCode = TMP.FTBchCode AND BAL.FTWahCode = TMP.FTWahCode AND BAL.FTPdtCode = TMP.FTPdtCode AND BAL.FTFhnRefCode = TMP.FTFhnRefCode
					    WHERE ISNULL(BAL.FTFhnRefCode, '') = ''

					    --insert stk card
					    INSERT INTO TFHTPdtStkCrd
                        ( 
                            FTBchCode, FDStfDate, FTStfDocNo, FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty, FCStfSetPrice
                            , FCStfCostIn, FCStfCostEx, FDCreateOn, FTCreateBy
                        )		--3.--
					    SELECT  FTBchCode, FDStfDate, FTStfDocNo + 'C' , FTWahCode, FTPdtCode, FTFhnRefCode, FTStfType, FCStfQty, FCStfSetPrice
                        , FCStfCostIn, FCStfCostEx, GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy		--3.--
					    FROM @TTmpPrcStkFhn
				    END
			    END

                
		        --Cost
                -- 07.02.00 --
		        IF @tStaAlwCostAmtTo = '1'
                BEGIN
			        -- 07.01.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.01.00 --
                END

		    END --End คลังปลายทางตัด Stock

		    --insert to stock card
		    INSERT INTO TCNTPdtStkCrd with(rowlock)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FDCreateOn, FTCreateBy
            )
		    SELECT FTBchCode, FDStkDate, FTStkDocNo + 'C' , FTWahCode, FTPdtCode, FTStkType, FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx
		    , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk

		    --Cost
		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt, 0) + (TMP.FCStkQty*COST.FCPdtCostEx)
		    --, FCPdtQtyBal = FCPdtQtyBal + TMP.FCStkQty
		    --, FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON 
      --          COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '1'

		    --UPDATE TCNMPdtCostAvg with(rowlock)
		    --SET FCPdtCostAmt = ISNULL(FCPdtCostAmt, 0) - (TMP.FCStkQty*COST.FCPdtCostEx)
		    --, FCPdtQtyBal = FCPdtQtyBal - TMP.FCStkQty
		    --, FDLastUpdOn = GETDATE()
		    --FROM TCNMPdtCostAvg COST
		    --INNER JOIN @TTmpPrcStk TMP ON 
      --          COST.FTPdtCode = TMP.FTPdtCode AND TMP.FTStkType = '2'


	    END	--End ประมวลผล Stock แล้ว
    
    END --END เอกสารยกเลิก

	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName, @ptWho, @ptDocNo , @tDate , @tTime
    SET @FNResult= -1
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxWahPdtTnfIn')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxWahPdtTnfIn
GO
CREATE PROCEDURE [dbo].STP_DOCxWahPdtTnfIn
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tTrans VARCHAR(20)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTComName varchar(50), 
   FTBchCode varchar(5), 
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty float, 
   FTWahCode varchar(5), 
   FDStkDate Datetime ,
   --FCStkSetPrice decimal(18,2),
   --FCStkCostIn decimal(18,2),
   --FCStkCostEx decimal(18,2)
   FCStkSetPrice decimal(18,4), -- 07.01.00 --
   FCStkCostIn decimal(18,4), -- 07.01.00 --
   FCStkCostEx decimal(18,4) -- 07.01.00 --
   ) 
DECLARE @tStaPrc varchar(1)		-- 5. --
DECLARE @tStaPrcStkTo varchar(1)	-- 04.01.00 --
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.01.00 --
DECLARE @tStaDoc varchar(1) -- 07.01.00 -- 1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
00.01.00	12/02/2019		Em		create  
00.02.00	28/03/2019		Em		เพิ่มการ Update stock balance
00.03.00	23/04/2019		Em		เพิ่มการอัพเดท Stock Vending และแก้ไขการอ้างอิงเอกสาร
00.04.00	17/06/2019		Em		แก้ไขเอาฟิลด์ StkCode และ Insert StkCard
00.05.00	05/07/2019		Em		เพิ่มการตรวจสอบสถานะการประมวลผลเอกสาร
02.01.00	03/03/2020		Em		ปรับตามโครงสร้างใหม่
03.01.00	13/03/2020		Em		แก้ไขให้อัพเดทเลขที่อ้างอิง
04.01.00	20/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
07.00.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcWahIn'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  

	--SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk,'') FROM TCNTPdtTwiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)	-- 5. --
	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk,'') FROM TCNTPdtTwiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)  -- 02.01.00 --
    SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXthStaDoc,'') FROM TCNTPdtTwiHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo) -- 07.01.00 --

    -- 07.01.00 --            
    IF @tStaDoc = '1' -- เอกสารปกติ
    BEGIN
	    IF @tStaPrc <> '1'	-- 5. --
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    --SET @tStaPrcStkTo = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
		    --				INNER JOIN TCNTPdtTwiHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
		    --				WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)
        
        
		    -- 07.01.00 --
		    SELECT TOP 1 @tStaPrcStkTo = ISNULL(WAH.FTWahStaPrcStk,'') ,
			    @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTwiHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhTo = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    -- 07.01.00 --

		    IF @tStaPrcStkTo = '2'
		    BEGIN
			    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)		--4.--
			    SELECT DISTINCT HD.FTBchCode,HD.FTXthWhTo,DT.FTPdtCode,0 AS FCStkQty,		--4.--
			    GETDATE() AS FDLastUpdOn,HD.FTLastUpdBy,
			    GETDATE() AS FDCreateOn,HD.FTCreateBy
			    FROM TCNTPdtTwiHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTwiDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhTo = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    AND ISNULL(STK.FTPdtCode,'') = ''		--4.--
			    AND ISNULL(DT.FTXtdStaPrcStk,'') = ''	-- 02.01.00 --
			    AND ISNULL(HD.FTXthWhTo,'') <> ''	-- 02.01.00 --

			    --Update balance In
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty + ISNULL(Twi.FCXtdQtyAll,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = Twi.FTLastUpdBy
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (SELECT HD.FTBchCode,HD.FTLastUpdBy,HD.FTXthWhTo, DT.FTPdtCode ,SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll		--4.--
			    FROM TCNTPdtTwiHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTwiDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    AND ISNULL(DT.FTXtdStaPrcStk,'') = ''	-- 02.01.00 --
			    AND ISNULL(HD.FTXthWhTo,'') <> ''	-- 02.01.00 --
			    GROUP BY HD.FTBchCode,HD.FTLastUpdBy,HD.FTXthWhTo, DT.FTPdtCode) Twi  ON Twi.FTBchCode = STK.FTBchCode AND Twi.FTXthWhTo = STK.FTWahCode AND Twi.FTPdtCode = STK.FTPdtCode		--4.--

			    --3.--
			    UPDATE TVDTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty + ISNULL(DTV.FCXtdQty,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = DT.FTLastUpdBy
			    FROM TVDTPdtStkBal STK
			    INNER JOIN TCNTPdtTwiDTVD DTV WITH(NOLOCK) ON STK.FTBchCode = DTV.FTBchCode AND STK.FNLayRow = DTV.FNLayRow AND STK.FNLayCol = DTV.FNLayCol
			    INNER JOIN TCNTPdtTwiDT DT WITH(NOLOCK) ON DTV.FTBchCode = DT.FTBchCode AND DTV.FTXthDocNo = DT.FTXthDocNo AND DTV.FNXtdSeqNo = DT.FNXtdSeqNo
			    INNER JOIN TCNTPdtTwiHD HD WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo AND ISNULL(HD.FTXthWhTo,'') = STK.FTWahCode  -- 02.01.00 --
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXthDocNo = @ptDocNo
			    AND ISNULL(DT.FTXtdStaPrcStk,'') = ''	-- 02.01.00 --
			    AND ISNULL(HD.FTXthWhTo,'') <> ''	-- 02.01.00 --
			    --3.--

			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT HD.FTBchCode,HD.FTXthDocNo AS FTStkDocNo
			    ,'1' AS FTStkType
			    ,DT.FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty,HD.FTXthWhTo AS FTWahCode,HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdSetPrice)/SUM(FCXtdQtyAll),2) AS FCStkSetPrice
			    --, ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),2) AS FCStkCostIn
			    --, ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),2) AS FCStkCostEx
			    , ROUND(SUM(DT.FCXtdNet),4) AS FCStkSetPrice -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),4) AS FCStkCostIn -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),4) AS FCStkCostEx -- 07.01.00 --
			    FROM TCNTPdtTwiDT DT with(nolock)
			    INNER JOIN TCNTPdtTwiHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    AND ISNULL(DT.FTXtdStaPrcStk,'') = ''	-- 02.01.00 --
			    AND ISNULL(HD.FTXthWhTo,'') <> ''	-- 02.01.00 --
			    GROUP BY HD.FTBchCode,HD.FTXthWhTo,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate

			    --4.--
			    --insert to stock card
			    INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
			    SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
			    GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
			    FROM @TTmpPrcStk
			    --4.--
            
		        --Cost
                -- 07.01.00 --
		        IF @tStaAlwCostAmt = '1'
		        BEGIN
			        -- 07.00.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.00.00 --
                END
                -- 07.01.00 --

		    END

		    UPDATE TCNTPdtIntDT WITH(ROWLOCK)
		    SET FCXtdQtyRcv = ISNULL(FCXtdQtyRcv,0) + ISNULL(DTi.FCXtdQtyAll,0)
		    ,FTXtdRvtRef = @ptDocNo	-- 03.01.00 --
		    ,FDLastUpdOn = GETDATE()
		    ,FTLastUpdBy = @ptWho
		    FROM TCNTPdtIntDT DT
		    INNER JOIN TCNTPdtTwiHD HDi WITH(NOLOCK) ON HDi.FTXthRefInt = DT.FTXthDocNo
		    INNER JOIN TCNTPdtTwiDT DTi WITH(NOLOCK) ON HDi.FTXthDocNo = DTi.FTXthDocNo AND DTi.FTPdtCode = DT.FTPdtCode
		    WHERE HDi.FTBchCode = @ptBchCode AND HDi.FTXthDocNo = @ptDocNo
		    AND ISNULL(DTi.FTXtdDocNoRef,'') = ''


		    --3.--
		    UPDATE TCNTPdtTwoHD WITH(ROWLOCK)
		    SET FTXthRefInt = @ptDocNo
		    FROM TCNTPdtTwoHD HDo
		    INNER JOIN TCNTPdtTwiHD HDi WITH(NOLOCK) ON HDo.FTXthDocNo = HDi.FTXthRefInt
		    WHERE HDi.FTBchCode = @ptBchCode AND HDi.FTXthDocNo = @ptDocNo
		    --3.--

		    UPDATE TCNTPdtIntDT WITH(ROWLOCK)
		    SET FCXtdQtyRcv = ISNULL(FCXtdQtyRcv,0) + ISNULL(DTi.FCXtdQtyAll,0)
		    ,FTXtdRvtRef = @ptDocNo	-- 03.01.00 --
		    ,FDLastUpdOn = GETDATE()
		    ,FTLastUpdBy = @ptWho
		    FROM TCNTPdtIntDT DT
		    INNER JOIN TCNTPdtTwiDT DTi WITH(NOLOCK) ON DT.FTBchCode = DTi.FTXtdBchRef AND DT.FTXthDocNo = DTi.FTXtdDocNoRef AND DT.FTPdtCode = DT.FTPdtCode
		    WHERE DTi.FTBchCode = @ptBchCode AND DTi.FTXthDocNo = @ptDocNo
		    AND ISNULL(DTi.FTXtdDocNoRef,'') <> ''

	    END -- 5. --
    END
	ELSE -- 07.01.00 --
    BEGIN
    
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.01.00 --


	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxWahPdtTnfOut')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
DROP PROCEDURE [dbo].STP_DOCxWahPdtTnfOut
GO
CREATE PROCEDURE [dbo].STP_DOCxWahPdtTnfOut
 @ptBchCode varchar(5)
,@ptDocNo varchar(30)
,@ptWho varchar(100) ,@FNResult INT OUTPUT AS
DECLARE @tTrans VARCHAR(20)
DECLARE @TTmpPrcStk TABLE 
   ( 
   FTComName varchar(50), 
   FTBchCode varchar(5), 
   FTStkDocNo varchar(20), 
   FTStkType varchar(1), 
   FTPdtCode varchar(20), 
   FCStkQty float, 
   FTWahCode varchar(5), 
   FDStkDate Datetime ,
   --FCStkSetPrice decimal(18,2),
   --FCStkCostIn decimal(18,2),
   --FCStkCostEx decimal(18,2)
   FCStkSetPrice decimal(18,4), -- 07.01.00 --
   FCStkCostIn decimal(18,4), -- 07.01.00 --
   FCStkCostEx decimal(18,4) -- 07.01.00 --
   ) 
DECLARE @tStaPrc varchar(1)		-- 6. --
DECLARE @tStaPrcStkFrm varchar(1)	-- 04.01.00 --
DECLARE @tStaAlwCostAmt varchar(1)	-- 07.01.00 --
DECLARE @tStaDoc varchar(1) -- 07.01.00 -- 1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
00.01.00	08/02/2019		Em		create  
00.02.00	23/04/2019		Em		เพิ่มการอัพเดท Stock Vending
00.05.00	17/06/2019		Em		แก้ไขเอาฟิลด์ StkCode และ Insert StkCard
00.06.00	05/07/2019		Em		เพิ่มการตรวจสอบสถานะการประมวลผลเอกสาร
03.01.00	26/03/2020		Em		เพิ่มฟิลด์ FNXthDocType ใน IntDT
03.02.00	27/03/2020		Em		แก้ไข Type ลง stkcard
04.01.00	19/10/2020		Em		เพิ่มการตรวจสอบคลังตัดสต๊อก
04.02.00	26/10/2020		Em		เพิ่มการตรวจสอบสถานะควบคุมสต๊อก
05.01.00	12/03/2021		Em		ป้องกันการ Process ซ้ำ
07.00.00	05/11/2021		Em		แก้ไขการคำนวณต้นทุน และ Stk
07.01.00	26/01/2022		Net	    แก้ไขตาม KPC, เพิ่มขายกเลิก
----------------------------------------------------------------------*/
SET @tTrans = 'PrcWahOut'
BEGIN TRY
	BEGIN TRANSACTION @tTrans  

	SET @tStaPrc = (SELECT TOP 1 ISNULL(FTXthStaPrcStk,'') FROM TCNTPdtTwoHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)	-- 6. --
	SET @tStaDoc = (SELECT TOP 1 ISNULL(FTXthStaDoc,'') FROM TCNTPdtTwoHD with(nolock) WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo)  -- 07.01.00 --
    
    -- 07.01.00 --            
    IF @tStaDoc = '1' -- เอกสารปกติ
    BEGIN
	    IF @tStaPrc <> '1'	-- 6. --
	    BEGIN
		    -- 05.01.00 --
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo
		    -- 05.01.00 --

		    --Delete old data
		    DELETE FROM TCNTPdtIntDT WHERE  FTBchCode = @ptBchCode AND FTXthDocNo = @ptDocNo
		
		    --Insert new data
		    INSERT INTO TCNTPdtIntDT(FTBchCode, FTXthDocNo, FNXthDocType, FNXtdSeqNo, FTXthWahTo, FTPdtCode, FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FCXtdQty, FCXtdQtyRcv, FCXtdQtyAll,	-- 03.02.00 --
			    FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		    SELECT HD.FTBchCode, HD.FTXthDocNo, 2, FNXtdSeqNo, HD.FTXthWhTo , FTPdtCode, FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FCXtdQty, 0 AS FCXtdQtyRcv, FCXtdQtyAll,	-- 03.02.00 --
			    GETDATE() AS FDLastUpdOn, @ptWho AS FTLastUpdBy, GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM TCNTPdtTwoDT DT WITH(NOLOCK)
		    INNER JOIN TCNTPdtTwoHD HD WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo

		    -- 04.01.00 --
		    --SET @tStaPrcStkFrm = (SELECT TOP 1 ISNULL(WAH.FTWahStaPrcStk,'') FROM TCNMWaHouse WAH WITH(NOLOCK)
		    --				INNER JOIN TCNTPdtTwoHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
		    --				WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo)

		    -- 07.01.00 --
		    SELECT TOP 1 @tStaPrcStkFrm = ISNULL(WAH.FTWahStaPrcStk,'') ,
			    @tStaAlwCostAmt = ISNULL(WAH.FTWahStaAlwCostAmt,'')
		    FROM TCNMWaHouse WAH WITH(NOLOCK)
		    INNER JOIN TCNTPdtTwoHD HD WITH(NOLOCK) ON HD.FTBchCode = WAH.FTBchCode AND HD.FTXthWhFrm = WAH.FTWahCode
		    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
		    -- 07.01.00 --

		    IF @tStaPrcStkFrm = '2'
		    BEGIN
			    INSERT INTO TCNTPdtStkBal(FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)		--5.--
			    SELECT DISTINCT HD.FTBchCode,HD.FTXthWhFrm,DT.FTPdtCode,0 AS FCStkQty,		--5.--
			    GETDATE() AS FDLastUpd,HD.FTLastUpdBy,
			    GETDATE() AS FDCreateOn,HD.FTCreateBy
			    FROM TCNTPdtTwoHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTwoDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON HD.FTBchCode = STK.FTBchCode AND HD.FTXthWhFrm = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode		--5.--
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    AND ISNULL(STK.FTPdtCode,'') = ''		--5.--

			    --Update balance Out
			    UPDATE TCNTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty - ISNULL(Two.FCXtdQtyAll,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = Two.FTLastUpdBy
			    FROM TCNTPdtStkBal STK
			    INNER JOIN (SELECT HD.FTBchCode,HD.FTLastUpdBy,HD.FTXthWhFrm, DT.FTPdtCode ,SUM(DT.FCXtdQtyAll) AS FCXtdQtyAll
			    FROM TCNTPdtTwoHD HD WITH(NOLOCK)
			    INNER JOIN TCNTPdtTwoDT DT WITH(NOLOCK) ON HD.FTBchCode = DT.FTBchCode AND HD.FTXthDocNo = DT.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode = @ptBchCode AND HD.FTXthDocNo = @ptDocNo
			    GROUP BY HD.FTBchCode,HD.FTLastUpdBy,HD.FTXthWhFrm, DT.FTPdtCode) Two  ON Two.FTBchCode = STK.FTBchCode AND Two.FTXthWhFrm = STK.FTWahCode AND Two.FTPdtCode = STK.FTPdtCode

			    --2.--
			    --Update stock balance vending
			    UPDATE TVDTPdtStkBal WITH(ROWLOCK)
			    SET FCStkQty = FCStkQty - ISNULL(DTV.FCXtdQty,0)
			    ,FDLastUpdOn = GETDATE()
			    ,FTLastUpdBy = DT.FTLastUpdBy
			    FROM TVDTPdtStkBal STK
			    INNER JOIN  TCNTPdtTwoDTVD DTV WITH(NOLOCK) ON STK.FTBchCode = DTV.FTBchCode AND STK.FNLayRow = DTV.FNLayRow AND STK.FNLayCol = DTV.FNLayCol
			    INNER JOIN TCNTPdtTwoDT DT WITH(NOLOCK) ON STK.FTBchCode = DT.FTBchCode AND STK.FTPdtCode = DT.FTPdtCode AND DTV.FNXtdSeqNo = DT.FNXtdSeqNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXthDocNo = @ptDocNo
			    --2.--
		
			    --5.--
			    --insert data to Temp
			    INSERT INTO @TTmpPrcStk (FTBchCode,FTStkDocNo,FTStkType,FTPdtCode,FCStkQty,FTWahCode,FDStkDate,FCStkSetPrice,FCStkCostIn,FCStkCostEx)
			    SELECT HD.FTBchCode,HD.FTXthDocNo AS FTStkDocNo
			    --,'1' AS FTStkType
			    ,'2' AS FTStkType	-- 03.02.00 --
			    ,DT.FTPdtCode AS FTPdtCode
			    , SUM(FCXtdQtyAll) AS FCStkQty,HD.FTXthWhFrm AS FTWahCode,HD.FDXthDocDate AS FDStkDate
			    --, ROUND(SUM(FCXtdSetPrice)/SUM(FCXtdQtyAll),2) AS FCStkSetPrice
			    --, ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),2) AS FCStkCostIn
			    --, ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),2) AS FCStkCostEx
			    , ROUND(SUM(DT.FCXtdNet),4) AS FCStkSetPrice -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostIn)/SUM(FCXtdQtyAll),4) AS FCStkCostIn -- 07.01.00 --
			    , ROUND(SUM(DT.FCXtdCostEx)/SUM(FCXtdQtyAll),4) AS FCStkCostEx -- 07.01.00 --
			    FROM TCNTPdtTwoDT DT with(nolock)
			    INNER JOIN TCNTPdtTwoHD HD with(nolock) ON DT.FTBchCode = HD.FTBchCode AND DT.FTXthDocNo = HD.FTXthDocNo
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'	-- 04.02.00 --
			    WHERE HD.FTBchCode=@ptBchCode AND HD.FTXthDocNo =@ptDocNo
			    GROUP BY HD.FTBchCode,HD.FTXthWhFrm,HD.FTXthDocNo,DT.FTPdtCode,HD.FDXthDocDate
		
			    --insert to stock card
			    INSERT INTO TCNTPdtStkCrd with(rowlock)(FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,FDCreateOn,FTCreateBy)
			    SELECT FTBchCode,FDStkDate,FTStkDocNo,FTWahCode,FTPdtCode,FTStkType,FCStkQty,FCStkSetPrice,FCStkCostIn,FCStkCostEx,
			    GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
			    FROM @TTmpPrcStk
			    --5.--
            
		        --Cost
                -- 07.01.00 --
		        IF @tStaAlwCostAmt = '1'
		        BEGIN
			        -- 07.00.00 --
			        UPDATE COST
			        SET FCPdtCostAmt = ROUND((CASE WHEN STK.FCStkQty <= 0 THEN 0 ELSE FCPdtCostEx * STK.FCStkQty END),4)
			        ,FCPdtQtyBal = STK.FCStkQty
			        ,FDLastUpdOn = GETDATE()
			        FROM TCNMPdtCostAvg COST With(nolock)
			        INNER JOIN @TTmpPrcStk TMP ON COST.FTPdtCode = TMP.FTPdtCode
			        INNER JOIN (SELECT STK.FTPdtCode,SUM(STK.FCStkQty) FCStkQty
						        FROM TCNTPdtStkBal STK with(nolock)
						        WHERE EXISTS(SELECT FTWahCode FROM TCNMWahouse WAH with(nolock) WHERE STK.FTBchCode = WAH.FTBchCode AND STK.FTWahCode = WAH.FTWahCode 
							        AND ISNULL(WAH.FTWahStaPrcStk,'') = '2' AND ISNULL(WAH.FTWahStaAlwCostAmt,'') = '1')
						        GROUP BY STK.FTPdtCode) STK ON TMP.FTPdtCode = STK.FTPdtCode
			        -- 07.00.00 --
                END
                -- 07.01.00 --

		    END
		    -- 04.01.00 --
		
	    END	 -- 6. --
        
    END
	ELSE -- 07.01.00 --
    BEGIN
    
	    IF @tStaPrc = '1'	--เคยตัด Stk ไปแล้ว
	    BEGIN
            
            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode

        END
    END
    -- 07.01.00 --

	COMMIT TRANSACTION @tTrans  
	SET @FNResult= 0
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION @tTrans  
    --EXEC STP_MSGxWriteTSysPrcLog @ptComName,@ptWho,@ptDocNo ,@tDate ,@tTime
    SET @FNResult= -1
	SELECT ERROR_MESSAGE()
END CATCH
GO

IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPdtClaimSendSpl')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPdtClaimSendSpl
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPdtClaimSendSpl
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) 
    , @ptSplCode varchar(20) 
    , @FNResult INT OUTPUT AS

DECLARE @tTrans varchar(20)
DECLARE @tStaPrcDoc varchar(1) -- สถานะการทำงาน  1 : รออนุมัติ , 2 : รอส่งสินค้าไปยังผู้จำหน่าย , 3 : รอรับสินค้าจากผู้จำหน่าย , 4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 5 : รอส่งสินค้าให้ลูกค้า , 6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 7 : ปิดงานแล้ว

DECLARE @tAgnDoc varchar(10) --Agn เอกสาร
DECLARE @tBchDoc varchar(50) --สาขา เอกสาร
DECLARE @tGenDocNo varchar(30) --เลขที่ เอกสาร

DECLARE @tVatInOrExt varchar(1)
DECLARE @tVatCode varchar(5)
DECLARE @cVatRate numeric(18, 4)
DECLARE @tRteCode varchar(5)
DECLARE @cRteFac numeric(18, 4)

DECLARE	@nStoreRet int
DECLARE @tResult varchar(30)
DECLARE @dDateNow DATETIME

DECLARE @TblGenDoc TABLE
(
    FTXxhDocNo VARCHAR(30)
)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	1/11/2021		Net		create 
07.01.00	30/01/2022		Net		แก้ไขการสร้างใบเบิกออก 
----------------------------------------------------------------------*/
SET @tTrans = 'GenTwo'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @dDateNow = GETDATE()

    -- Get สถานะเอกสาร
    SELECT DISTINCT @tStaPrcDoc = ISNULL(HD.FTPchStaPrcDoc, '')
    , @tAgnDoc = ISNULL(HD.FTAgnCode, '')
    FROM TCNTPdtClaimHD HD WITH(NOLOCK)
    INNER JOIN TCNTPdtClaimDTSpl DTSpl WITH(NOLOCK) ON
        HD.FTBchCode = DTSpl.FTBchCode AND HD.FTPchDocNo = DTSpl.FTPchDocNo
    WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        AND DTSpl.FTSplCode = @ptSplCode AND ISNULL(DTSpl.FTPcdRefTwo,'') = ''

    IF @tStaPrcDoc = '2'  -- อนุมัติแล้ว
    BEGIN

        --Gen เลขที่เอกสาร ใบรับของ
        INSERT @TblGenDoc 
        EXEC @nStoreRet = [dbo].[SP_CNtAUTAutoDocNo]
            @ptTblName = N'TCNTPdtTwoHD'
		    , @ptDocType = N'2'
		    , @ptBchCode = @ptBchCode
		    , @ptShpCode = NULL
		    , @ptPosCode = NULL
		    , @pdDocDate = @dDateNow
		    , @ptResult = @tResult OUTPUT

        SET @tGenDocNo = (SELECT TOP 1 FTXxhDocNo FROM @TblGenDoc)


        --ถ้า Gen เลขที่เอกสารไม่ได้
        IF ISNULL(@tGenDocNo, '') = '' 
            THROW 50000, 'SP_CNtAUTAutoDocNo Error', 0;

        -- Get VatComp
        SELECT TOP 1 @tVatCode = VAT.FTVatCode, @cVatRate = VAT.FCVatRate
        , @tVatInOrExt = CMP.FTCmpRetInOrEx, @tRteCode = CMP.FTRteCode
        , @cRteFac = RTE.FCRteFraction
        FROM TCNMComp CMP WITH(NOLOCK)
        INNER JOIN(
            SELECT FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
        )VAT ON CMP.FTVatCode = VAT.FTVatCode
        INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
            CMP.FTRteCode = RTE.FTRteCode
        WHERE FNRank = 1

        -- Get VatAgn ถ้ามี
        IF ISNULL(@tAgnDoc, '') <> ''
        BEGIN
            SELECT TOP 1 @tVatCode = ISNULL(VAT.FTVatCode, @tVatCode)
            , @cVatRate = ISNULL(VAT.FCVatRate, @cVatRate)
            , @tVatInOrExt = ISNULL(AGN.FTCmpVatInOrEx, @tVatInOrExt)
            , @tRteCode = AGN.FTRteCode
            , @cRteFac = RTE.FCRteFraction
            FROM TCNMAgencySpc AGN WITH(NOLOCK)
            INNER JOIN(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT ON AGN.FTVatCode = VAT.FTVatCode
            INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
                AGN.FTRteCode = RTE.FTRteCode
            WHERE FNRank = 1 AND AGN.FTAgnCode = ISNULL(@tAgnDoc, '')
        END

        ---------- Gen เอกสาร ----------
        INSERT TCNTPdtClaimHDDocRef
        (
            FTAgnCode, FTBchCode, FTPchDocNo, FTXshRefType, FTXshRefDocNo
            , FTXshRefKey, FDXshRefDocDate
        )
        SELECT DISTINCT HD.FTAgnCode, HD.FTBchCode, HD.FTPchDocNo, '2', @tGenDocNo
        , 'TNFOUT', GETDATE()
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        
        
        INSERT TCNTPdtTwoHDRef
        (
            FTBchCode, FTXthDocNo, FTXthCtrName, FDXthTnfDate, FTXthRefTnfID
            , FTXthRefVehID, FTXthQtyAndTypeUnit, FNXthShipAdd, FTViaCode
        )
        SELECT HD.FTBchCode, @tGenDocNo, ISNULL(CSTL.FTCstName,''), NULL, NULL
        , HDCst.FTCarCode, NULL, NULL, NULL
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimHDCst HDCst WITH(NOLOCK) ON
            HD.FTBchCode = HDCst.FTBchCode AND HD.FTPchDocNo = HDCst.FTPchDocNo
        LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON
            HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = 1
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        INSERT TCNTPdtTwoDT
        (
            FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName
            , FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTXtdVatType
            , FTVatCode, FCXtdVatRate, FCXtdQty, FCXtdQtyAll, FCXtdSetPrice
            , FCXtdAmt, FCXtdVat, FCXtdVatable, FCXtdNet, FCXtdCostIn
            , FCXtdCostEx, FTXtdStaPrcStk, FNXtdPdtLevel, FTXtdPdtParent, FCXtdQtySet
            , FTXtdPdtStaSet, FTXtdRmk
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, ROW_NUMBER() OVER(ORDER BY DT.FNPcdSeqNo) AS FNXtdSeqNo, DT.FTPdtCode, DT.FTPcdPdtName
        , DT.FTPunCode, DT.FTPunName, DT.FCPcdFactor, DT.FTPcdBarCode, ISNULL(PDT.FTPdtStaVat,'2') 
        , ISNULL(PDT.FTVatCode, @tVatCode) , ISNULL(VAT.FCVatRate, @cVatRate) , DT.FCPcdQty, DT.FCPcdQtyAll, ISNULL(PRI.FCPgdPriceRet,0)
        , DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0) AS FCXtdAmt
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/100
                     END
                ELSE 0
          END AS FCXtdVat
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0)) * 100/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0))
                     END
                ELSE (DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0))
          END AS FCXtdVatable
        , (DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0)) AS FCXtdNet, NULL AS FCXtdCostIn
        , NULL AS FCXtdCostEx, '' AS FTXtdStaPrcStk, NULL AS FNXtdPdtLevel, NULL AS FTXtdPdtParent, NULL AS FCXtdQtySet
        , NULL AS FTXtdPdtStaSet, '' AS FTXtdRmk
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        INNER JOIN TCNTPdtClaimDTSpl DTSpl WITH(NOLOCK) ON
            DT.FTBchCode = DTSpl.FTBchCode AND DT.FTPchDocNo = DTSpl.FTPchDocNo
            AND DT.FNPcdSeqNo = DTSpl.FNPcdSeqNo
        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
            DT.FTPdtCode = PDT.FTPdtCode
        INNER JOIN(
            SELECT FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
        )VAT ON
            PDT.FTVatCode = VAT.FTVatCode AND VAT.FNRank = 1
        LEFT JOIN TCNTPdtPrice4PDT PRI WITH(NOLOCK) ON
            DT.FTPdtCode = PRI.FTPdtCode AND DT.FTPunCode = PRI.FTPunCode AND ISNULL(PRI.FTPplCode,'')=''
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
            AND DTSpl.FTSplCode = @ptSplCode
            AND ISNULL(DTSpl.FTPcdRefTwo,'') = ''

        INSERT TCNTPdtTwoHD
        (
            FTBchCode, FTXthDocNo, FNXthDocType, FTXthRsnType, FDXthDocDate
            , FTXthVATInOrEx, FTDptCode, FTXthMerCode, FTXthShopFrm, FTXthShopTo
            , FTXthWhFrm, FTXthWhTo, FTXthPosFrm, FTXthPosTo, FTSplCode
            , FTXthOther, FTUsrCode, FTSpnCode, FTXthApvCode, FTXthRefExt
            , FDXthRefExtDate, FTXthRefInt, FDXthRefIntDate, FNXthDocPrint, FCXthTotal
            , FCXthVat, FCXthVatable, FTXthRmk, FTXthStaDoc, FTXthStaApv
            , FTXthStaPrcStk, FTXthStaDelMQ, FNXthStaDocAct, FNXthStaRef, FTRsnCode
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, '2' AS FTXthDocType, '3' AS FTXthRsnType, GETDATE() AS FDXthDocDate
        , @tVatInOrExt AS FTXthVATInOrEx, '' AS FTDptCode, '' AS FTXthMerCode, '' AS FTXthShopFrm, '' AS FTXthShopTo
        , DT.FTWahCode, '' AS FTXthWhTo, '' AS FTXthPosFrm, '' AS FTXthPosTo, DTSpl.FTSplCode
        , '' AS FTXthOther, HD.FTUsrcode, '' AS FTSpnCode, '' AS FTXthApvCode, '' AS FTXthRefExt
        , NULL AS FDXthRefExtDate, '' AS FTXthRefInt, NULL AS FDXthRefIntDate, 0 AS FNXthDocPrint, TDT.FCXthTotal AS FCXthTotal
        , TDT.FCXthVat, TDT.FCXthVatable, '' AS FTXthRmk, '1' AS FTXthStaDoc, '' AS FTXthStaApv
        , '' AS FTXthStaPrcStk, '' AS FTXthStaDelMQ, 1 AS FNXthStaDocAct, '' AS FNXthStaRef, '' AS FTRsnCode
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        INNER JOIN TCNTPdtClaimDTSpl DTSpl WITH(NOLOCK) ON
            DT.FTBchCode = DTSpl.FTBchCode AND DT.FTPchDocNo = DTSpl.FTPchDocNo
            AND DT.FNPcdSeqNo = DTSpl.FNPcdSeqNo
        INNER JOIN (
            SELECT @ptBchCode AS FTBchCode, @ptDocNo AS FTPchDocNo
            , SUM(DT.FCXtdNet) AS FCXthTotal
            , SUM(DT.FCXtdVat) AS FCXthVat
            , SUM(DT.FCXtdVatable) AS FCXthVatable
            FROM TCNTPdtTwoDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = @ptBchCode AND DT.FTXthDocNo = @tGenDocNo
        )TDT ON
            HD.FTBchCode = TDT.FTBchCode AND HD.FTPchDocNo = TDT.FTPchDocNo
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
            AND DTSpl.FTSplCode = @ptSplCode
            AND ISNULL(DTSpl.FTPcdRefTwo,'') = ''

        ---------- End Gen เอกสาร ----------

        
        IF( (SELECT COUNT(*) FROM TCNTPdtTwoHD WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 OR
            (SELECT COUNT(*) FROM TCNTPdtTwoDT WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 )
            THROW 50000, 'Gen Doc Empty', 0;

    END --End อนุมัติแล้ว


	COMMIT TRANSACTION @tTrans

    SELECT @tGenDocNo AS FTGenDocNo, '' AS FTErrMsg
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
    SELECT '' AS FTGenDocNo, ERROR_MESSAGE() AS FTErrMsg
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPdtClaimRcvSpl')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPdtClaimRcvSpl
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPdtClaimRcvSpl
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) 
    , @ptSplCode varchar(20) 
    , @FNResult INT OUTPUT AS

DECLARE @tTrans varchar(20)
DECLARE @tStaPrcDoc varchar(1) -- สถานะการทำงาน  1 : รออนุมัติ , 2 : รอส่งสินค้าไปยังผู้จำหน่าย , 3 : รอรับสินค้าจากผู้จำหน่าย , 4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 5 : รอส่งสินค้าให้ลูกค้า , 6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 7 : ปิดงานแล้ว

DECLARE @tAgnDoc varchar(10) --Agn เอกสาร
DECLARE @tBchDoc varchar(50) --สาขา เอกสาร
DECLARE @tGenDocNo varchar(30) --เลขที่ เอกสาร

DECLARE @tVatInOrExt varchar(1)
DECLARE @tVatCode varchar(5)
DECLARE @cVatRate numeric(18, 4)
DECLARE @tRteCode varchar(5)
DECLARE @cRteFac numeric(18, 4)

DECLARE	@nStoreRet int
DECLARE @tResult varchar(30)
DECLARE @dDateNow DATETIME

DECLARE @TblGenDoc TABLE
(
    FTXxhDocNo VARCHAR(30)
)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	1/11/2021		Net		create 
07.01.00    23/11/2021      Net     แก้ไขการเลือก Vat
07.02.00    30/01/2022      Net     แก้ไขการสร้างใบรับเข้า
----------------------------------------------------------------------*/
SET @tTrans = 'GenTwi'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @dDateNow = GETDATE()

    -- Get สถานะเอกสาร
    SELECT DISTINCT @tStaPrcDoc = ISNULL(HD.FTPchStaPrcDoc, '')
    , @tAgnDoc = ISNULL(HD.FTAgnCode, '')
    FROM TCNTPdtClaimHD HD WITH(NOLOCK)
    INNER JOIN TCNTPdtClaimDTRcv DTRcv WITH(NOLOCK) ON
        HD.FTBchCode = DTRcv.FTBchCode AND HD.FTPchDocNo = DTRcv.FTPchDocNo
    WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        AND DTRcv.FTSplCode = @ptSplCode AND ISNULL(DTRcv.FTRcvRefTwi,'') = ''

    IF @tStaPrcDoc IN ('3','4')  -- อนุมัติแล้ว
    BEGIN

        --Gen เลขที่เอกสาร ใบรับของ
        INSERT @TblGenDoc 
        EXEC @nStoreRet = [dbo].[SP_CNtAUTAutoDocNo]
            @ptTblName = N'TCNTPdtTwiHD'
		    , @ptDocType = N'5'
		    , @ptBchCode = @ptBchCode
		    , @ptShpCode = NULL
		    , @ptPosCode = NULL
		    , @pdDocDate = @dDateNow
		    , @ptResult = @tResult OUTPUT

        SET @tGenDocNo = (SELECT TOP 1 FTXxhDocNo FROM @TblGenDoc)


        --ถ้า Gen เลขที่เอกสารไม่ได้
        IF ISNULL(@tGenDocNo, '') = '' 
            THROW 50000, 'SP_CNtAUTAutoDocNo Error', 0;

        -- Get VatComp
        SELECT TOP 1 @tVatCode = VAT.FTVatCode, @cVatRate = VAT.FCVatRate
        , @tVatInOrExt = CMP.FTCmpRetInOrEx, @tRteCode = CMP.FTRteCode
        , @cRteFac = RTE.FCRteFraction
        FROM TCNMComp CMP WITH(NOLOCK)
        INNER JOIN(
            SELECT FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
        )VAT ON CMP.FTVatCode = VAT.FTVatCode
        INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
            CMP.FTRteCode = RTE.FTRteCode
        WHERE FNRank = 1

        -- Get VatAgn ถ้ามี
        IF ISNULL(@tAgnDoc, '') <> ''
        BEGIN
            SELECT TOP 1 @tVatCode = ISNULL(VAT.FTVatCode, @tVatCode)
            , @cVatRate = ISNULL(VAT.FCVatRate, @cVatRate)
            , @tVatInOrExt = ISNULL(AGN.FTCmpVatInOrEx, @tVatInOrExt)
            , @tRteCode = AGN.FTRteCode
            , @cRteFac = RTE.FCRteFraction
            FROM TCNMAgencySpc AGN WITH(NOLOCK)
            INNER JOIN(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT ON AGN.FTVatCode = VAT.FTVatCode
            INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
                AGN.FTRteCode = RTE.FTRteCode
            WHERE FNRank = 1 AND AGN.FTAgnCode = ISNULL(@tAgnDoc, '')
        END
        
        ---------- Gen เอกสาร ----------
        INSERT TCNTPdtClaimHDDocRef
        (
            FTAgnCode, FTBchCode, FTPchDocNo, FTXshRefType, FTXshRefDocNo
            , FTXshRefKey, FDXshRefDocDate
        )
        SELECT DISTINCT HD.FTAgnCode, HD.FTBchCode, HD.FTPchDocNo, '2', @tGenDocNo
        , 'TNFIN', GETDATE()
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        
        INSERT TCNTPdtTwiHDRef
        (
            FTBchCode, FTXthDocNo, FTXthCtrName, FDXthTnfDate, FTXthRefTnfID
            , FTXthRefVehID, FTXthQtyAndTypeUnit, FNXthShipAdd, FTViaCode
        )
        SELECT HD.FTBchCode, @tGenDocNo, ISNULL(CSTL.FTCstName,''), NULL, NULL
        , HDCst.FTCarCode, NULL, NULL, NULL
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimHDCst HDCst WITH(NOLOCK) ON
            HD.FTBchCode = HDCst.FTBchCode AND HD.FTPchDocNo = HDCst.FTPchDocNo
        LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON
            HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = 1
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        INSERT TCNTPdtTwiDT
        (
            FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName
            , FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTXtdVatType
            , FTVatCode, FCXtdVatRate, FCXtdQty, FCXtdQtyAll, FCXtdSetPrice
            , FCXtdAmt, FCXtdVat, FCXtdVatable, FCXtdNet, FCXtdCostIn
            , FCXtdCostEx, FTXtdStaPrcStk, FNXtdPdtLevel, FTXtdPdtParent, FCXtdQtySet
            , FTXtdPdtStaSet, FTXtdRmk, FTXtdBchRef, FTXtdDocNoRef
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, ROW_NUMBER() OVER(ORDER BY DTRcv.FNPcdSeqNo) AS FNXtdSeqNo, DTRcv.FTRcvPdtCode, DT.FTPcdPdtName
        , DT.FTPunCode, DT.FTPunName, DT.FCPcdFactor, DT.FTPcdBarCode, ISNULL(PDT.FTPdtStaVat,'2')
        , ISNULL(PDT.FTVatCode, @tVatCode), ISNULL(VAT.FCVatRate, @cVatRate), DTRcv.FCRcvPdtQty, DTRcv.FCRcvPdtQty * DT.FCPcdFactor, ISNULL(PRI.FCPgdPriceRet,0)
        , DTRcv.FCRcvPdtQty * ISNULL(PRI.FCPgdPriceRet,0)
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DTRcv.FCRcvPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DTRcv.FCRcvPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/100
                     END
                ELSE 0
          END AS FCXtdVat
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DTRcv.FCRcvPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) * 100/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DTRcv.FCRcvPdtQty * ISNULL(PRI.FCPgdPriceRet,0))
                     END
                ELSE (DTRcv.FCRcvPdtQty * ISNULL(PRI.FCPgdPriceRet,0))
          END AS FCXtdVatable
        , (DTRcv.FCRcvPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) AS FCXtdNet, NULL AS FCXtdCostIn
        , NULL AS FCXtdCostEx, '' AS FTXtdStaPrcStk, NULL AS FNXtdPdtLevel, NULL AS FTXtdPdtParent, NULL AS FCXtdQtySet
        , NULL AS FTXtdPdtStaSet, '' AS FTXtdRmk, '' AS FTXtdBchRef, '' AS FTXtdDocNoRef
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        INNER JOIN TCNTPdtClaimDTRcv DTRcv WITH(NOLOCK) ON
            DT.FTBchCode = DTRcv.FTBchCode AND DT.FTPchDocNo = DTRcv.FTPchDocNo
            AND DT.FNPcdSeqNo = DTRcv.FNPcdSeqNo
        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
            DT.FTPdtCode = PDT.FTPdtCode
        INNER JOIN(
            SELECT TOP 1 FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
            ORDER BY FDVatStart DESC
        )VAT ON
            PDT.FTVatCode = VAT.FTVatCode AND VAT.FNRank = 1
        LEFT JOIN TCNTPdtPrice4PDT PRI WITH(NOLOCK) ON
            DT.FTPdtCode = PRI.FTPdtCode AND DT.FTPunCode = PRI.FTPunCode AND ISNULL(PRI.FTPplCode,'')=''
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
            AND ISNULL(DTRcv.FTRcvRefTwi,'') = ''

        INSERT TCNTPdtTwiHD
        (
            FTBchCode, FTXthDocNo, FNXthDocType, FTXthRsnType, FDXthDocDate
            , FTXthVATInOrEx, FTDptCode, FTXthMerCode, FTXthShopFrm, FTXthShopTo
            , FTXthWhFrm, FTXthWhTo, FTXthPosFrm, FTXthPosTo, FTSplCode
            , FTXthOther, FTUsrCode, FTSpnCode, FTXthApvCode, FTXthRefExt
            , FDXthRefExtDate, FTXthRefInt, FDXthRefIntDate, FNXthDocPrint, FCXthTotal
            , FCXthVat, FCXthVatable, FTXthRmk, FTXthStaDoc, FTXthStaApv
            , FTXthStaPrcStk, FTXthStaDelMQ, FNXthStaDocAct, FNXthStaRef, FTRsnCode, FTXthTypRefFrm
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
            , FTCstCode
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, 1, '3' AS FTXthRsnType, GETDATE() AS FDXthDocDate
        , @tVatInOrExt, '' AS FTDptCode, '' AS FTXthMerCode, '' AS FTXthShopFrm, '' AS FTXthShopTo
        , '' AS FTXthWhFrm
        , CASE WHEN ISNULL(BCH.FTWahCode,'') = '' 
                   THEN (SELECT TOP 1 FTWahCode FROM TCNMWaHouse WITH(NOLOCK) WHERE FTBchCode = HD.FTBchCode AND FTWahStaType = '1')
               ELSE BCH.FTWahCode
          END AS FTXthWhTo
        , '' AS FTXthPosFrm, '' AS FTXthPosTo, @ptSplCode AS FTSplCode
        , '' AS FTXthOther, HD.FTUsrcode, '' AS FTSpnCode, '' AS FTXthApvCode, '' AS FTXthRefExt
        , NULL AS FDXthRefExtDate, '' AS FTXthRefInt, NULL AS FDXthRefIntDate, 0 AS FNXthDocPrint, TDT.FCXthTotal
        , TDT.FCXthVat, TDT.FCXthVatable, '' AS FTXthRmk, '1' AS FTXthStaDoc, '' AS FTXthStaApv
        , '' AS FTXthStaPrcStk, '' AS FTXthStaDelMQ, 1 AS FNXthStaDocAct, 0 AS FNXthStaRef, '' AS FTRsnCode, '3' AS FTXthTypRefFrm
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        , HD.FTCstCode
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        INNER JOIN TCNTPdtClaimDTRcv DTRcv WITH(NOLOCK) ON
            DT.FTBchCode = DTRcv.FTBchCode AND DT.FTPchDocNo = DTRcv.FTPchDocNo
            AND DT.FNPcdSeqNo = DTRcv.FNPcdSeqNo
        INNER JOIN (
            SELECT @ptBchCode AS FTBchCode, @ptDocNo AS FTPchDocNo
            , SUM(DT.FCXtdNet) AS FCXthTotal
            , SUM(DT.FCXtdVat) AS FCXthVat
            , SUM(DT.FCXtdVatable) AS FCXthVatable
            FROM TCNTPdtTwiDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = @ptBchCode AND DT.FTXthDocNo = @tGenDocNo
        )TDT ON
            HD.FTBchCode = TDT.FTBchCode AND HD.FTPchDocNo = TDT.FTPchDocNo
        INNER JOIN TCNMBranch BCH WITH(NOLOCK) ON
            HD.FTBchCode = BCH.FTBchCode
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
            AND ISNULL(DTRcv.FTRcvRefTwi,'') = ''

        ---------- End Gen เอกสาร ----------

        IF( (SELECT COUNT(*) FROM TCNTPdtTwiHD WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 OR
            (SELECT COUNT(*) FROM TCNTPdtTwiDT WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 )
            THROW 50000, 'Gen Doc Empty', 0;

    END --End อนุมัติแล้ว


	COMMIT TRANSACTION @tTrans

    SELECT @tGenDocNo AS FTGenDocNo, '' AS FTErrMsg
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
    SELECT '' AS FTGenDocNo, ERROR_MESSAGE() AS FTErrMsg
END CATCH
GO
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPdtClaimRetCst')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPdtClaimRetCst
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPdtClaimRetCst
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptStaPick varchar(1) --  1 : เบิก, ว่าง ไม่เบิก
    , @ptWho varchar(100) 
    , @FNResult INT OUTPUT AS

DECLARE @tTrans varchar(20)
DECLARE @tStaPrcDoc varchar(1) -- สถานะการทำงาน  1 : รออนุมัติ , 2 : รอส่งสินค้าไปยังผู้จำหน่าย , 3 : รอรับสินค้าจากผู้จำหน่าย , 4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 5 : รอส่งสินค้าให้ลูกค้า , 6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 7 : ปิดงานแล้ว

DECLARE @tAgnDoc varchar(10) --Agn เอกสาร
DECLARE @tBchDoc varchar(50) --สาขา เอกสาร
DECLARE @tGenDocNo varchar(30) --เลขที่ เอกสาร

DECLARE @tVatInOrExt varchar(1)
DECLARE @tVatCode varchar(5)
DECLARE @cVatRate numeric(18, 4)
DECLARE @tRteCode varchar(5)
DECLARE @cRteFac numeric(18, 4)

DECLARE	@nStoreRet int
DECLARE @tResult varchar(30)
DECLARE @dDateNow DATETIME

DECLARE @TblGenDoc TABLE
(
    FTXxhDocNo VARCHAR(30)
)
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	2/11/2021		Net		create 
07.01.00	11/11/2021		Net		เพิ่ม CstCode 
07.02.00	30/01/2022		Net		แก้ไขการสร้างใบเบิกออก
----------------------------------------------------------------------*/
SET @tTrans = 'GenTwo'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @dDateNow = GETDATE()

    -- Get สถานะเอกสาร
    SELECT DISTINCT @tStaPrcDoc = ISNULL(HD.FTPchStaPrcDoc, '')
    , @tAgnDoc = ISNULL(HD.FTAgnCode, '')
    FROM TCNTPdtClaimHD HD WITH(NOLOCK)
    INNER JOIN TCNTPdtClaimDTSpl DTSpl WITH(NOLOCK) ON
        HD.FTBchCode = DTSpl.FTBchCode AND HD.FTPchDocNo = DTSpl.FTPchDocNo
    INNER JOIN TCNTPdtClaimDTWrn DTWrn WITH(NOLOCK) ON
        DTSpl.FTBchCode = DTWrn.FTBchCode AND DTSpl.FTPchDocNo = DTWrn.FTPchDocNo
        AND DTSpl.FNPcdSeqNo = DTWrn.FNPcdSeqNo
    INNER JOIN TCNTPdtClaimDTRet DTRet WITH(NOLOCK) ON
        DTWrn.FTBchCode = DTRet.FTBchCode AND DTWrn.FTPchDocNo = DTRet.FTPchDocNo
        AND DTWrn.FNWrnSeq = DTRet.FNWrnSeq
    WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        AND ISNULL(DTRet.FTRetRefDoc2,'') = ''
        AND ISNULL(DTRet.FCRetPdtQty,0) > 0
        AND ISNULL(DTSpl.FTPcdStaPick,'') = @ptStaPick

    IF @tStaPrcDoc IN ('5','6')  -- อนุมัติแล้ว
    BEGIN

        --Gen เลขที่เอกสาร ใบเบิกออก
        INSERT @TblGenDoc 
        EXEC @nStoreRet = [dbo].[SP_CNtAUTAutoDocNo]
            @ptTblName = N'TCNTPdtTwoHD'
		    , @ptDocType = N'2'
		    , @ptBchCode = @ptBchCode
		    , @ptShpCode = NULL
		    , @ptPosCode = NULL
		    , @pdDocDate = @dDateNow
		    , @ptResult = @tResult OUTPUT

        SET @tGenDocNo = (SELECT TOP 1 FTXxhDocNo FROM @TblGenDoc)


        --ถ้า Gen เลขที่เอกสารไม่ได้
        IF ISNULL(@tGenDocNo, '') = '' 
            THROW 50000, 'SP_CNtAUTAutoDocNo Error', 0;

        -- Get VatComp
        SELECT TOP 1 @tVatCode = VAT.FTVatCode, @cVatRate = VAT.FCVatRate
        , @tVatInOrExt = CMP.FTCmpRetInOrEx, @tRteCode = CMP.FTRteCode
        , @cRteFac = RTE.FCRteFraction
        FROM TCNMComp CMP WITH(NOLOCK)
        INNER JOIN(
            SELECT FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
        )VAT ON CMP.FTVatCode = VAT.FTVatCode
        INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
            CMP.FTRteCode = RTE.FTRteCode
        WHERE FNRank = 1

        -- Get VatAgn ถ้ามี
        IF ISNULL(@tAgnDoc, '') <> ''
        BEGIN
            SELECT TOP 1 @tVatCode = ISNULL(VAT.FTVatCode, @tVatCode)
            , @cVatRate = ISNULL(VAT.FCVatRate, @cVatRate)
            , @tVatInOrExt = ISNULL(AGN.FTCmpVatInOrEx, @tVatInOrExt)
            , @tRteCode = AGN.FTRteCode
            , @cRteFac = RTE.FCRteFraction
            FROM TCNMAgencySpc AGN WITH(NOLOCK)
            INNER JOIN(
                SELECT FTVatCode, FCVatRate
                , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
                FROM TCNMVatRate
                WHERE FDVatStart <= GETDATE()
            )VAT ON AGN.FTVatCode = VAT.FTVatCode
            INNER JOIN TFNMRate RTE WITH(NOLOCK) ON
                AGN.FTRteCode = RTE.FTRteCode
            WHERE FNRank = 1 AND AGN.FTAgnCode = ISNULL(@tAgnDoc, '')
        END

        ---------- Gen เอกสาร ----------
        INSERT TCNTPdtClaimHDDocRef
        (
            FTAgnCode, FTBchCode, FTPchDocNo, FTXshRefType, FTXshRefDocNo
            , FTXshRefKey, FDXshRefDocDate
        )
        SELECT DISTINCT HD.FTAgnCode, HD.FTBchCode, HD.FTPchDocNo, '2', @tGenDocNo
        , 'TNFOUT', GETDATE()
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        
        
        INSERT TCNTPdtTwoHDRef
        (
            FTBchCode, FTXthDocNo, FTXthCtrName, FDXthTnfDate, FTXthRefTnfID
            , FTXthRefVehID, FTXthQtyAndTypeUnit, FNXthShipAdd, FTViaCode
        )
        SELECT HD.FTBchCode, @tGenDocNo, ISNULL(CSTL.FTCstName,''), NULL, NULL
        , HDCst.FTCarCode, NULL, NULL, NULL
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimHDCst HDCst WITH(NOLOCK) ON
            HD.FTBchCode = HDCst.FTBchCode AND HD.FTPchDocNo = HDCst.FTPchDocNo
        LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON
            HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = 1
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        INSERT TCNTPdtTwoDT
        (
            FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName
            , FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTXtdVatType
            , FTVatCode, FCXtdVatRate, FCXtdQty, FCXtdQtyAll, FCXtdSetPrice
            , FCXtdAmt, FCXtdVat, FCXtdVatable, FCXtdNet, FCXtdCostIn
            , FCXtdCostEx, FTXtdStaPrcStk, FNXtdPdtLevel, FTXtdPdtParent, FCXtdQtySet
            , FTXtdPdtStaSet, FTXtdRmk
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, ROW_NUMBER() OVER(ORDER BY DTRet.FNRetSeq) AS FNXtdSeqNo, DT.FTPdtCode, DT.FTPcdPdtName
        , DT.FTPunCode, DT.FTPunName, DT.FCPcdFactor, DT.FTPcdBarCode, ISNULL(PDT.FTPdtStaVat,'2') 
        , ISNULL(PDT.FTVatCode, @tVatCode) , ISNULL(VAT.FCVatRate, @cVatRate) , DTRet.FCRetPdtQty, DTRet.FCRetPdtQty * DT.FCPcdFactor, ISNULL(PRI.FCPgdPriceRet,0)
        , DTRet.FCRetPdtQty * ISNULL(PRI.FCPgdPriceRet,0) AS FCXtdAmt
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DTRet.FCRetPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DTRet.FCRetPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/100
                     END
                ELSE 0
          END AS FCXtdVat
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DTRet.FCRetPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) * 100/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DTRet.FCRetPdtQty * ISNULL(PRI.FCPgdPriceRet,0))
                     END
                ELSE (DTRet.FCRetPdtQty * ISNULL(PRI.FCPgdPriceRet,0))
          END AS FCXtdVatable
        , (DTRet.FCRetPdtQty * ISNULL(PRI.FCPgdPriceRet,0)) AS FCXtdNet, NULL AS FCXtdCostIn
        , NULL AS FCXtdCostEx, '' AS FTXtdStaPrcStk, NULL AS FNXtdPdtLevel, NULL AS FTXtdPdtParent, NULL AS FCXtdQtySet
        , NULL AS FTXtdPdtStaSet, '' AS FTXtdRmk
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        INNER JOIN TCNTPdtClaimDTSpl DTSpl WITH(NOLOCK) ON
            DT.FTBchCode = DTSpl.FTBchCode AND DT.FTPchDocNo = DTSpl.FTPchDocNo
            AND DT.FNPcdSeqNo = DTSpl.FNPcdSeqNo
        INNER JOIN TCNTPdtClaimDTWrn DTWrn WITH(NOLOCK) ON
            DTSpl.FTBchCode = DTWrn.FTBchCode AND DTSpl.FTPchDocNo = DTWrn.FTPchDocNo
            AND DTSpl.FNPcdSeqNo = DTWrn.FNPcdSeqNo
        INNER JOIN TCNTPdtClaimDTRet DTRet WITH(NOLOCK) ON
            DTWrn.FTBchCode = DTRet.FTBchCode AND DTWrn.FTPchDocNo = DTRet.FTPchDocNo
            AND DTWrn.FNWrnSeq = DTRet.FNWrnSeq 
        INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
            DT.FTPdtCode = PDT.FTPdtCode
        INNER JOIN(
            SELECT TOP 1 FTVatCode, FCVatRate
            , ROW_NUMBER() OVER(PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS FNRank
            FROM TCNMVatRate
            WHERE FDVatStart <= GETDATE()
            ORDER BY FDVatStart DESC
        )VAT ON
            PDT.FTVatCode = VAT.FTVatCode AND VAT.FNRank = 1
        LEFT JOIN TCNTPdtPrice4PDT PRI WITH(NOLOCK) ON
            DT.FTPdtCode = PRI.FTPdtCode AND DT.FTPunCode = PRI.FTPunCode AND ISNULL(PRI.FTPplCode,'')=''
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
            AND ISNULL(DTRet.FTRetRefDoc2,'') = ''
            AND ISNULL(DTRet.FCRetPdtQty,0) > 0
            AND ISNULL(DTSpl.FTPcdStaPick,'') = @ptStaPick

        INSERT TCNTPdtTwoHD
        (
            FTBchCode, FTXthDocNo, FNXthDocType, FTXthRsnType, FDXthDocDate
            , FTXthVATInOrEx, FTDptCode, FTXthMerCode, FTXthShopFrm, FTXthShopTo
            , FTXthWhFrm, FTXthWhTo, FTXthPosFrm, FTXthPosTo, FTSplCode
            , FTXthOther, FTUsrCode, FTSpnCode, FTXthApvCode, FTXthRefExt
            , FDXthRefExtDate, FTXthRefInt, FDXthRefIntDate, FNXthDocPrint, FCXthTotal
            , FCXthVat, FCXthVatable, FTXthRmk, FTXthStaDoc, FTXthStaApv
            , FTXthStaPrcStk, FTXthStaDelMQ, FNXthStaDocAct, FNXthStaRef, FTRsnCode
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy, FTCstCode
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, '2' AS FTXthDocType, '5' AS FTXthRsnType, GETDATE() AS FDXthDocDate
        , @tVatInOrExt AS FTXthVATInOrEx, '' AS FTDptCode, '' AS FTXthMerCode, '' AS FTXthShopFrm, '' AS FTXthShopTo
        , (CASE WHEN ISNULL(@ptStaPick,'')='' THEN BCH.FTWahCode ELSE DTSpl.FTWahCode END) AS FTXthWhFrm, '' AS FTXthWhTo, '' AS FTXthPosFrm
        , '' AS FTXthPosTo, '' AS FTSplCode, '' AS FTXthOther, HD.FTUsrcode, '' AS FTSpnCode, '' AS FTXthApvCode, '' AS FTXthRefExt
        , NULL AS FDXthRefExtDate, '' AS FTXthRefInt, NULL AS FDXthRefIntDate, 0 AS FNXthDocPrint, TDT.FCXthTotal AS FCXthTotal
        , TDT.FCXthVat, TDT.FCXthVatable, '' AS FTXthRmk, '1' AS FTXthStaDoc, '' AS FTXthStaApv
        , '' AS FTXthStaPrcStk, '' AS FTXthStaDelMQ, 1 AS FNXthStaDocAct, '' AS FNXthStaRef, '' AS FTRsnCode
        , GETDATE(), @ptWho, GETDATE(), @ptWho, HD.FTCstCode AS FTCstCode
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        INNER JOIN TCNTPdtClaimDTSpl DTSpl WITH(NOLOCK) ON
            DT.FTBchCode = DTSpl.FTBchCode AND DT.FTPchDocNo = DTSpl.FTPchDocNo
            AND DT.FNPcdSeqNo = DTSpl.FNPcdSeqNo
        INNER JOIN TCNTPdtClaimDTWrn DTWrn WITH(NOLOCK) ON
            DTSpl.FTBchCode = DTWrn.FTBchCode AND DTSpl.FTPchDocNo = DTWrn.FTPchDocNo
            AND DTSpl.FNPcdSeqNo = DTWrn.FNPcdSeqNo
        INNER JOIN TCNTPdtClaimDTRet DTRet WITH(NOLOCK) ON
            DTWrn.FTBchCode = DTRet.FTBchCode AND DTWrn.FTPchDocNo = DTRet.FTPchDocNo
            AND DTWrn.FNWrnSeq = DTRet.FNWrnSeq 
        INNER JOIN (
            SELECT @ptBchCode AS FTBchCode, @ptDocNo AS FTPchDocNo
            , SUM(DT.FCXtdNet) AS FCXthTotal
            , SUM(DT.FCXtdVat) AS FCXthVat
            , SUM(DT.FCXtdVatable) AS FCXthVatable
            FROM TCNTPdtTwoDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = @ptBchCode AND DT.FTXthDocNo = @tGenDocNo
        )TDT ON
            HD.FTBchCode = TDT.FTBchCode AND HD.FTPchDocNo = TDT.FTPchDocNo
        INNER JOIN TCNMBranch BCH WITH(NOLOCK) ON
            HD.FTBchCode = BCH.FTBchCode
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
            AND ISNULL(DTRet.FTRetRefDoc2,'') = ''
            AND ISNULL(DTRet.FCRetPdtQty,0) > 0
            AND ISNULL(DTSpl.FTPcdStaPick,'') = @ptStaPick

        ---------- End Gen เอกสาร ----------

        
        IF( (SELECT COUNT(*) FROM TCNTPdtTwoHD WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 OR
            (SELECT COUNT(*) FROM TCNTPdtTwoDT WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 )
            THROW 50000, 'Gen Doc Empty', 0;

    END --End อนุมัติแล้ว


	COMMIT TRANSACTION @tTrans

    SELECT @tGenDocNo AS FTGenDocNo, '' AS FTErrMsg
	SET @FNResult= 0
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
    SELECT '' AS FTGenDocNo, ERROR_MESSAGE() AS FTErrMsg
END CATCH
GO
IF EXISTS( SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxJob2OrderPrc')and OBJECTPROPERTY(id, N'IsProcedure') = 1 )
    DROP PROCEDURE [dbo].STP_DOCxJob2OrderPrc
GO

CREATE PROCEDURE [dbo].STP_DOCxJob2OrderPrc
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
    , @ptWho varchar(100) 
    , @FNResult INT OUTPUT AS
DECLARE @TTmpPrcStk TABLE 
( 
    FTBchCode varchar(5)
    , FTStkDocNo varchar(20)
    , FTStkType varchar(1)
    , FTStkSysType varchar(1)
    , FTPdtCode varchar(20)
    , FTPdtParent varchar(20)
    , FCStkQty decimal(18,2)
    , FTWahCode varchar(5)
    , FDStkDate Datetime
    , FCStkSetPrice decimal(18,2)
    , FCStkCostIn decimal(18,2)
    , FCStkCostEx decimal(18,2)
) 
DECLARE @tStaPrc varchar(1)
DECLARE @tStaPrcStkFrm varchar(1)
DECLARE @tStaPrcStkTo varchar(1)
DECLARE @tStaDoc varchar(1) --1 สมบูรณ์ 3ยกเลิก
DECLARE @tTrans varchar(20)
DECLARE @tWahCodeTo varchar(5) 
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	19/11/2021		Net		create 
07.01.00	24/11/2021		Net		ปรับการยกเลิก เอาจาก StockCard เลย
----------------------------------------------------------------------*/
-- คลัง DT = ต้นทาง = คลังขาย
-- โอนไปคลังปลายทาง = คลังจอง
SET @tTrans = 'PrcJob2Ord'
BEGIN TRY
	BEGIN TRANSACTION @tTrans
	SET @tStaDoc = (SELECT TOP 1 FTXshStaDoc
                      FROM TSVTJob2OrdHD WITH(NOLOCK) 
                      WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo)

    IF @tStaDoc = '1' --เอกสารปกติ
    BEGIN
	    SET @tStaPrc = (CASE WHEN (SELECT COUNT(*) AS FTXphStaPrcStk 
                                   FROM TSVTJob2OrdDT WITH(NOLOCK) 
                                   WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                       AND ISNULL(FTXsdStaPrcStk,'')<>'1' ) > 0
                             THEN '1' ELSE '2' END) -- 1ยังประมวลผลไม่หมด 2ประมวลผลหมดแล้ว

        
        -- ยังประมวลผล Stock ไม่ครบ
	    IF @tStaPrc <> '2'	
	    BEGIN
            
            --หาคลังจอง
            SET @tWahCodeTo = (SELECT TOP 1 FTWahCode
                               FROM TCNMWaHouse WAH WITH(NOLOCK)
                               WHERE WAH.FTBchCode = @ptBchCode AND ISNULL(FTWahStaType,'') = '7')
            
            --ถ้ามีไม่คลังจอง
            IF ISNULL(@tWahCodeTo, '') = '' 
                THROW 50000, 'Wahouse not found', 0;
            
		    -- ตัด Stk ออก คลังต้นทาง
			-- Create stk balance qty 0 ตัวที่ไม่เคยมี
			INSERT INTO TCNTPdtStkBal
            (
                FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
            )
			SELECT DISTINCT
            DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, 0 AS FCStkQty
			, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                DT.FTBchCode = STK.FTBchCode AND DT.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			-- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			INSERT INTO TCNTPdtStkBal
            (
                FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
            )
			SELECT DISTINCT
            DT.FTBchCode, DTP.FTWahCode, DT.FTPdtCode, 0 AS FCStkQty
			, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
            INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
                DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
                AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' 
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                DT.FTBchCode = STK.FTBchCode AND DTP.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DTP.FTXsdStaPrcStk,'') = ''
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                AND ISNULL(DT.FTPsvType,'')='1'
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
			-- Update ตัด Stk ออกจากคลังต้นทาง
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
			, FDLastUpdOn = GETDATE()
			, FTLastUpdBy = @ptWho
			FROM TCNTPdtStkBal STK
			INNER JOIN (
                SELECT DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			    FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			    GROUP BY DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk ออกจากคลังต้นทาง
            
			-- Update ตัด Stk ออกจากคลังต้นทาง ตัวลูก
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
			, FDLastUpdOn = GETDATE()
			, FTLastUpdBy = @ptWho
			FROM TCNTPdtStkBal STK
			INNER JOIN (
                SELECT DT.FTBchCode, DTP.FTWahCode, DT.FTPdtCode, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			    FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
                INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
                    DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
                    AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DTP.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			    GROUP BY DT.FTBchCode, DTP.FTWahCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk ออกจากคลังต้นทาง ตัวลูก

            -- เก็บตัวที่ตัด Stk ไว้
            INSERT INTO @TTmpPrcStk
            (
                FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
            )
			SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			, '2' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			, DT.FTPdtCode AS FTPdtCode
			, '' AS FTPdtParent
			, SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			, ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			, 0 AS FCStkCostIn
			, 0 AS FCStkCostEx
			FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			INNER JOIN TSVTJob2OrdDT DT WITH(NOLOCK) ON
                HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                AND HD.FTXshDocNo = DT.FTXshDocNo
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('','1') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้
            
            -- เก็บตัวที่ตัด Stk ไว้ ตัวลูก
            INSERT INTO @TTmpPrcStk
            (
                FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
            )
			SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			, '2' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			, DT.FTPdtCode AS FTPdtCode
			, '' AS FTPdtParent
			, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DTP.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			, ROUND(SUM(DTP.FCXsdSetPrice)/SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact),2) AS FCStkSetPrice
			, 0 AS FCStkCostIn
			, 0 AS FCStkCostEx
			FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			INNER JOIN TSVTJob2OrdDT DTP WITH(NOLOCK) ON
                HD.FTAgnCode = DTP.FTAgnCode AND HD.FTBchCode = DTP.FTBchCode
                AND HD.FTXshDocNo = DTP.FTXshDocNo
            INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
                DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
                AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
            INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                AND ISNULL(DTP.FTXsdStaPrcStk,'') IN ('','1') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DTP.FTWahCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้ ตัวลูก
		    --End ตัด Stk ออก คลังต้นทาง
        



		    -- ตัด Stk เข้า คลังปลายทาง 
			-- Create stk balance qty 0 ตัวที่ไม่เคยมี
			INSERT INTO TCNTPdtStkBal
            (
                FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
            )
			SELECT DISTINCT
            DT.FTBchCode, @tWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
			, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode
			LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                AND ISNULL(DT.FTXsdStaPrcStk,'') = ''
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			-- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			INSERT INTO TCNTPdtStkBal
            (
                FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
            )
			SELECT DISTINCT
            DT.FTBchCode, @tWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
			, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
            INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
                DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
                AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode
			LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                AND ISNULL(DTP.FTXsdStaPrcStk,'') = ''
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
			-- Update ตัด Stk เข้าคลังปลายทาง
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
			, FDLastUpdOn = GETDATE()
			, FTLastUpdBy = @ptWho
			FROM TCNTPdtStkBal STK
			INNER JOIN (
                SELECT DT.FTBchCode, @tWahCodeTo AS FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			    FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			    GROUP BY DT.FTBchCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk เข้าคลังปลายทาง
            
			-- Update ตัด Stk เข้าคลังปลายทาง ตัวลูก
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
			, FDLastUpdOn = GETDATE()
			, FTLastUpdBy = @ptWho
			FROM TCNTPdtStkBal STK
			INNER JOIN (
                SELECT DT.FTBchCode, @tWahCodeTo AS FTWahCode, DT.FTPdtCode, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			    FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
                INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
                    DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
                    AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                    DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                    AND ISNULL(DTP.FTXsdStaPrcStk,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			    GROUP BY DT.FTBchCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk เข้าคลังปลายทาง ตัวลูก

            -- เก็บตัวที่ตัด Stk ไว้
            INSERT INTO @TTmpPrcStk
            (
                FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
            )
			SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			, '1' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			, DT.FTPdtCode AS FTPdtCode
			, '' AS FTPdtParent
			, SUM(DT.FCXsdQtyAll) AS FCStkQty, @tWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			, ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			, 0 AS FCStkCostIn
			, 0 AS FCStkCostEx
			FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			INNER JOIN TSVTJob2OrdDT DT WITH(NOLOCK) ON
                HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                AND HD.FTXshDocNo = DT.FTXshDocNo
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('','1') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้
            
            -- เก็บตัวที่ตัด Stk ไว้ ตัวลูก
            INSERT INTO @TTmpPrcStk
            (
                FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
                , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
            )
			SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			, '1' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			, DT.FTPdtCode AS FTPdtCode
			, '' AS FTPdtParent
			, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, @tWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			, ROUND(SUM(DTP.FCXsdSetPrice)/SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact),2) AS FCStkSetPrice
			, 0 AS FCStkCostIn
			, 0 AS FCStkCostEx
			FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			INNER JOIN TSVTJob2OrdDT DTP WITH(NOLOCK) ON
                HD.FTAgnCode = DTP.FTAgnCode AND HD.FTBchCode = DTP.FTBchCode
                AND HD.FTXshDocNo = DTP.FTXshDocNo
            INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
                DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
                AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
            INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
                DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                AND ISNULL(DTP.FTXsdStaPrcStk,'') IN ('','1') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้ ตัวลูก
		    --End ตัด Stk เข้า คลังต้นปลายทาง 



		    --Insert ลง Stock Card
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo

		    INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , FDCreateOn, FTCreateBy
            )
		    SELECT FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk
		    --End Insert ลง Stock Card
		
	    END
        --End ยังประมวลผล Stock ไม่ครบ
    END
    ELSE BEGIN --เอกสารยกเลิก

	    SET @tStaPrc = (CASE WHEN (SELECT COUNT(*) AS FTXphStaPrcStk 
                                   FROM TSVTJob2OrdDT WITH(NOLOCK) 
                                   WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                       AND ISNULL(FTXsdStaPrcStk,'')='1' ) > 0
                             THEN '1' ELSE '2' END) -- 1เคยตัด Stk ไปแล้ว 2ยังไม่เคยตัดStk

        
        -- เคยตัด Stk ไปแล้
	    IF @tStaPrc <> '2'	
	    BEGIN
            
            UPDATE BAL WITH(ROWLOCK)
            SET FCStkQty = BAL.FCStkQty + 
                CASE WHEN FTStkType='1' THEN -ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='2' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='3' THEN ISNULL(STK.FCStkQty,0)
                     WHEN FTStkType='4' THEN -ISNULL(STK.FCStkQty,0)
                     ELSE ISNULL(STK.FCStkQty,0) END 
            FROM TCNTPdtStkBal BAL
            INNER JOIN TCNTPdtStkCrd STK WITH(NOLOCK) ON
                BAL.FTPdtCode = STK.FTPdtCode AND BAL.FTBchCode = STK.FTBchCode
                AND BAL.FTWahCode = STK.FTWahCode
            WHERE STK.FTStkDocNo = @ptDocNo AND STK.FTBchCode = @ptBchCode

            DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

            --FTStkType สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
            INSERT TCNTPdtStkCrd
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent, FDCreateOn, FTCreateBy
            )
            SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode
            , CASE WHEN FTStkType='1' THEN '2'
                   WHEN FTStkType='2' THEN '1'
                   WHEN FTStkType='3' THEN '4'
                   WHEN FTStkType='4' THEN '3'
                   ELSE '5'
              END AS FTStkType, FTStkSysType
            , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
            , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
            FROM TCNTPdtStkCrd WITH(NOLOCK)
            WHERE FTStkDocNo = @ptDocNo AND FTBchCode = @ptBchCode
   --         --หาคลังจอง
   --         SET @tWahCodeTo = (SELECT TOP 1 FTWahCode
   --                            FROM TCNMWaHouse WAH WITH(NOLOCK)
   --                            WHERE WAH.FTBchCode = @ptBchCode AND ISNULL(FTWahStaType,'') = '7')
            
   --         --ถ้ามีไม่คลังจอง
   --         IF ISNULL(@tWahCodeTo, '') = '' 
   --             THROW 50000, 'Wahouse not found', 0;
            
		 --   -- ตัด Stk เข้า คลังต้นทาง
			---- Create stk balance qty 0 ตัวที่ไม่เคยมี
			--INSERT INTO TCNTPdtStkBal
   --         (
   --             FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
   --         )
			--SELECT DISTINCT
   --         DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, 0 AS FCStkQty
			--, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			--FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			--LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
   --             DT.FTBchCode = STK.FTBchCode AND DT.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
			--    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --             AND DT.FTWahCode<>@tWahCodeTo
   --         --End Create stk balance qty 0 ตัวที่ไม่เคยมี

			---- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			--INSERT INTO TCNTPdtStkBal
   --         (
   --             FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
   --         )
			--SELECT DISTINCT
   --         DT.FTBchCode, DTP.FTWahCode, DT.FTPdtCode, 0 AS FCStkQty
			--, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			--FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
   --         INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
   --             DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
   --             AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			--LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
   --             DT.FTBchCode = STK.FTBchCode AND DTP.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1'
			--    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --             AND DTP.FTWahCode<>@tWahCodeTo
   --         --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
			---- Update ตัด Stk เข้า คลังต้นทาง
			--UPDATE STK WITH(ROWLOCK)
			--SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
			--, FDLastUpdOn = GETDATE()
			--, FTLastUpdBy = @ptWho
			--FROM TCNTPdtStkBal STK
			--INNER JOIN (
   --             SELECT DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			--    FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			--    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --                 PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
   --             INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --                 DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			--    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
   --                 AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --                 AND DT.FTWahCode<>@tWahCodeTo
			--    GROUP BY DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode
   --         ) DocStk  ON 
   --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			----End Update ตัด Stk เข้า คลังต้นทาง
            
			---- Update ตัด Stk เข้า คลังต้นทางd ตัวลูก
			--UPDATE STK WITH(ROWLOCK)
			--SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
			--, FDLastUpdOn = GETDATE()
			--, FTLastUpdBy = @ptWho
			--FROM TCNTPdtStkBal STK
			--INNER JOIN (
   --             SELECT DT.FTBchCode, DTP.FTWahCode, DT.FTPdtCode, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			--    FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
   --             INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
   --                 DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
   --                 AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			--    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
   --                 DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			--    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --                 PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
   --             INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --                 DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			--    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
   --                 AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --                 AND DTP.FTWahCode<>@tWahCodeTo
			--    GROUP BY DT.FTBchCode, DTP.FTWahCode, DT.FTPdtCode
   --         ) DocStk  ON 
   --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			----End Update ตัด Stk เข้า คลังต้นทาง ตัวลูก

   --         -- เก็บตัวที่ตัด Stk ไว้
   --         INSERT INTO @TTmpPrcStk
   --         (
   --             FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
   --             , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
   --         )
			--SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			--, '1' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
   --         , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			--, DT.FTPdtCode AS FTPdtCode
			--, '' AS FTPdtParent
			--, SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			--, ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			--, 0 AS FCStkCostIn
			--, 0 AS FCStkCostEx
			--FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			--INNER JOIN TSVTJob2OrdDT DT WITH(NOLOCK) ON
   --             HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
   --             AND HD.FTXshDocNo = DT.FTXshDocNo
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
   --             AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --             AND DT.FTWahCode<>@tWahCodeTo
			--GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCode, HD.FDXshDocDate
   --         --End เก็บตัวที่ตัด Stk ไว้
            
   --         -- เก็บตัวที่ตัด Stk ไว้ ตัวลูก
   --         INSERT INTO @TTmpPrcStk
   --         (
   --             FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
   --             , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
   --         )
			--SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			--, '1' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
   --         , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			--, DT.FTPdtCode AS FTPdtCode
			--, '' AS FTPdtParent
			--, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, DTP.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			--, ROUND(SUM(DTP.FCXsdSetPrice)/SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact),2) AS FCStkSetPrice
			--, 0 AS FCStkCostIn
			--, 0 AS FCStkCostEx
			--FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			--INNER JOIN TSVTJob2OrdDT DTP WITH(NOLOCK) ON
   --             HD.FTAgnCode = DTP.FTAgnCode AND HD.FTBchCode = DTP.FTBchCode
   --             AND HD.FTXshDocNo = DTP.FTXshDocNo
   --         INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
   --             DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
   --             AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
   --         INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
   --             DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
   --             AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --             AND DTP.FTWahCode<>@tWahCodeTo
			--GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DTP.FTWahCode, HD.FDXshDocDate
   --         --End เก็บตัวที่ตัด Stk ไว้  ตัวลูก
		 --   --End ตัด Stk ออก คลังต้นทาง
        



		 --   -- ตัด Stk ออกคลังปลายทาง 
			---- Create stk balance qty 0 ตัวที่ไม่เคยมี
			--INSERT INTO TCNTPdtStkBal
   --         (
   --             FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
   --         )
			--SELECT DISTINCT
   --         DT.FTBchCode, @tWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
			--, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			--FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode
			--LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
   --             DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
   --             AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
			--    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --         --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			---- Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
			--INSERT INTO TCNTPdtStkBal
   --         (
   --             FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
   --         )
			--SELECT DISTINCT
   --         DT.FTBchCode, @tWahCodeTo, DT.FTPdtCode, 0 AS FCStkQty
			--, GETDATE() AS FDLastUpd, @ptWho, GETDATE() AS FDCreateOn, @ptWho
			--FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
   --         INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
   --             DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
   --             AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON 
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode
			--LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
   --             DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
   --             AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1'
			--    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --         --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
			---- Update ตัด Stk ออกคลังปลายทาง
			--UPDATE STK WITH(ROWLOCK)
			--SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
			--, FDLastUpdOn = GETDATE()
			--, FTLastUpdBy = @ptWho
			--FROM TCNTPdtStkBal STK
			--INNER JOIN (
   --             SELECT DT.FTBchCode, @tWahCodeTo AS FTWahCode, DT.FTPdtCode, SUM(DT.FCXsdQtyAll) AS FCXtdQtyAll
			--    FROM TSVTJob2OrdDT DT WITH(NOLOCK)
			--    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --                 PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
   --             INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --                 DT.FTBchCode = WAH.FTBchCode
			--    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
   --                 AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --                 AND DT.FTWahCode<>@tWahCodeTo
			--    GROUP BY DT.FTBchCode, DT.FTPdtCode
   --         ) DocStk  ON 
   --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			----End Update ตัด Stk ออกคลังปลายทาง
            
			---- Update ตัด Stk ออกคลังปลายทาง ตัวลูก
			--UPDATE STK WITH(ROWLOCK)
			--SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
			--, FDLastUpdOn = GETDATE()
			--, FTLastUpdBy = @ptWho
			--FROM TCNTPdtStkBal STK
			--INNER JOIN (
   --             SELECT DT.FTBchCode, @tWahCodeTo AS FTWahCode, DT.FTPdtCode, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCXtdQtyAll
			--    FROM TSVTJob2OrdDT DTP WITH(NOLOCK)
   --             INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
   --                 DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
   --                 AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
			--    INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
   --                 DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			--    INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --                 PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
   --             INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --                 DT.FTBchCode = WAH.FTBchCode
			--    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
   --                 AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --                 AND DTP.FTWahCode<>@tWahCodeTo
			--    GROUP BY DT.FTBchCode, DT.FTPdtCode
   --         ) DocStk  ON 
   --             DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			----End Update ตัด Stk ออกคลังปลายทาง ตัวลูก

   --         -- เก็บตัวที่ตัด Stk ไว้
   --         INSERT INTO @TTmpPrcStk
   --         (
   --             FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
   --             , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
   --         )
			--SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			--, '2' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
   --         , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			--, DT.FTPdtCode AS FTPdtCode
			--, '' AS FTPdtParent
			--, SUM(DT.FCXsdQtyAll) AS FCStkQty, @tWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			--, ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			--, 0 AS FCStkCostIn
			--, 0 AS FCStkCostEx
			--FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			--INNER JOIN TSVTJob2OrdDT DT WITH(NOLOCK) ON
   --             HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
   --             AND HD.FTXshDocNo = DT.FTXshDocNo
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
   --             AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --             AND DT.FTWahCode<>@tWahCodeTo
			--GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
   --         --End เก็บตัวที่ตัด Stk ไว้
            
   --         -- เก็บตัวที่ตัด Stk ไว้ ตัวลูก
   --         INSERT INTO @TTmpPrcStk
   --         (
   --             FTBchCode, FTStkDocNo, FTStkType, FTStkSysType, FTPdtCode, FTPdtParent, FCStkQty
   --             , FTWahCode, FDStkDate, FCStkSetPrice, FCStkCostIn, FCStkCostEx
   --         )
			--SELECT DT.FTBchCode, DT.FTXshDocNo AS FTStkDocNo
			--, '2' AS FTStkType --สถานะสินค้า 1:เข้า/ซื้อ, 2:ออก 3:ขาย FullSlip/DN, 4:คืนใบ ABB/CN  ,5:Adjust
   --         , '4' AS FTStkSysType --ประเภทเอกสาร  เช่น   1:รับเข้า , 2:ใบรับของ , 3:โอนสินค้าระหว่างคลัง , 4:ใบจอง , 5:ใบจ่ายโอน
			--, DT.FTPdtCode AS FTPdtCode
			--, '' AS FTPdtParent
			--, SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact) AS FCStkQty, @tWahCodeTo AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			--, ROUND(SUM(DTP.FCXsdSetPrice)/SUM(DTP.FCXsdQtyAll*DT.FCXsdQtySet*PKS.FCPdtUnitFact),2) AS FCStkSetPrice
			--, 0 AS FCStkCostIn
			--, 0 AS FCStkCostEx
			--FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			--INNER JOIN TSVTJob2OrdDT DTP WITH(NOLOCK) ON
   --             HD.FTAgnCode = DTP.FTAgnCode AND HD.FTBchCode = DTP.FTBchCode
   --             AND HD.FTXshDocNo = DTP.FTXshDocNo
   --         INNER JOIN TSVTJob2OrdDTSet DT WITH(NOLOCK) ON
   --             DTP.FTAgnCode = DT.FTAgnCode AND DTP.FTBchCode = DT.FTBchCode
   --             AND DTP.FTXshDocNo = DT.FTXshDocNo AND DTP.FNXsdSeqNo = DT.FNXsdSeqNo
   --         INNER JOIN TCNMPdtPackSize PKS WITH(NOLOCK) ON
   --             DT.FTPdtCode = PKS.FTPdtCode AND DT.FTPunCode = PKS.FTPunCode
			--INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
   --             PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
   --         INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
   --             DT.FTBchCode = WAH.FTBchCode
			--WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
   --             AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
   --             AND DTP.FTWahCode<>@tWahCodeTo
			--GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
   --         --End เก็บตัวที่ตัด Stk ไว้ ตัวลูก
		 --   --End ตัด Stk เข้า คลังต้นปลายทาง 



		 --   --Insert ลง Stock Card
		 --   DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		 --   WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

		 --   INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
   --         (
   --             FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
   --             , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
   --             , FDCreateOn, FTCreateBy
   --         )
		 --   SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode, FTStkType, FTStkSysType
   --             , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
   --             , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		 --   FROM @TTmpPrcStk
		 --   --End Insert ลง Stock Card
		
	    END
        --End เคยตัด Stk ไปแล้ว
    END
    

	
	COMMIT TRANSACTION @tTrans
	SET @FNResult= 0
    SELECT '' AS FTErrMsg
END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION @tTrans
	SET @FNResult= -1
	SELECT ERROR_MESSAGE() AS FTErrMsg
END CATCH
GO


/* 
    CREATE BY   : Napat(Jame) 10/12/2021 
    STORENAME   : SP_CNoBrowseProduct
    DESCRIPTION : เพิ่ม Option ตรวจสอบสินค้าคงคลัง : All = แสดงทั้งหมด, 1 = เฉพาะสินค้าที่มีสต็อค ,2 = เฉพาะสินค้าที่ไม่มีสต็อค
*/
IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_CNoBrowseProduct') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_CNoBrowseProduct
END
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SP_CNoBrowseProduct]
	--ผู้ใช้และสิท
	@ptUsrCode VARCHAR(10),
	@ptUsrLevel VARCHAR(10),
	@ptSesAgnCode VARCHAR(10),
	@ptSesBchCodeMulti VARCHAR(100),
	@ptSesShopCodeMulti VARCHAR(100),
	@ptSesMerCode VARCHAR(20),
	@ptWahCode VARCHAR(5),

	--กำหนดการแสดงข้อมูล
	@pnRow INT,
	@pnPage INT,
	@pnMaxTopPage INT,
	--ค้นหาตามประเภท
	@ptFilterBy VARCHAR(80),
	@ptSearch VARCHAR(1000),

	--OPTION PDT
	@ptWhere VARCHAR(8000),
	@ptNotInPdtType VARCHAR(8000),
	@ptPdtCodeIgnorParam VARCHAR(30),
	@ptPDTMoveon VARCHAR(1),
	@ptPlcCodeConParam VARCHAR(10),
	@ptDISTYPE VARCHAR(1),
	@ptPagename VARCHAR(10),
	@ptNotinItemString VARCHAR(8000),
	@ptSqlCode VARCHAR(20),
	
	--Price And Cost
	@ptPriceType VARCHAR(30),
	@ptPplCode VARCHAR(30),
	
	@pnLngID INT
AS
BEGIN

DECLARE @tSQL VARCHAR(MAX)
DECLARE @tSQLMaster VARCHAR(MAX)

DECLARE @tUsrCode VARCHAR(10)
DECLARE @tUsrLevel VARCHAR(10)
DECLARE @tSesAgnCode VARCHAR(10)
DECLARE @tSesBchCodeMulti VARCHAR(100)
DECLARE @tSesShopCodeMulti VARCHAR(100)
DECLARE @tSesMerCode VARCHAR(20)
DECLARE @tWahCode VARCHAR(5)

DECLARE @nRow INT
DECLARE @nPage INT
DECLARE @nMaxTopPage INT

DECLARE @tFilterBy VARCHAR(80)
DECLARE @tSearch VARCHAR(80)

	--OPTION PDT
DECLARE	@tWhere VARCHAR(8000)
DECLARE	@tNotInPdtType VARCHAR(8000)
DECLARE	@tPdtCodeIgnorParam VARCHAR(30)
DECLARE	@tPDTMoveon VARCHAR(1)
DECLARE	@tPlcCodeConParam VARCHAR(10)
DECLARE	@tDISTYPE VARCHAR(1)
DECLARE	@tPagename VARCHAR(10)
DECLARE	@tNotinItemString VARCHAR(8000)
DECLARE	@tSqlCode VARCHAR(10)

	--Price And Cost
DECLARE	@tPriceType VARCHAR(10)
DECLARE	@tPplCode VARCHAR(10)

DECLARE @nLngID INT

---///2021-09-10 -Nattakit K. :: สร้างสโตร
SET @tUsrCode = @ptUsrCode
SET @tUsrLevel = @ptUsrLevel
SET @tSesAgnCode = @ptSesAgnCode
SET @tSesBchCodeMulti = @ptSesBchCodeMulti
SET @tSesShopCodeMulti = @ptSesShopCodeMulti
SET @tSesMerCode = @ptSesMerCode
SET @tWahCode = @ptWahCode

SET @nRow = @pnRow
SET @nPage = @pnPage
SET @nMaxTopPage = @pnMaxTopPage

SET @tFilterBy = @ptFilterBy
SET @tSearch = @ptSearch

SET @tWhere = @ptWhere
SET @tNotInPdtType = @ptNotInPdtType
SET @tPdtCodeIgnorParam = @ptPdtCodeIgnorParam
SET @tPDTMoveon = @ptPDTMoveon
SET @tPlcCodeConParam = @ptPlcCodeConParam
SET @tDISTYPE = @ptDISTYPE
SET @tPagename = @ptPagename
SET @tNotinItemString = @ptNotinItemString
SET @tSqlCode = @ptSqlCode

SET @tPriceType = @ptPriceType
SET @tPplCode = @ptPplCode
SET @nLngID = @pnLngID

    SET @tSQLMaster = ' SELECT Base.*, '

    IF @nPage = 1 BEGIN
            SET @tSQLMaster += ' COUNT(*) OVER() AS rtCountData '
    END ELSE BEGIN
            SET @tSQLMaster += ' 0 AS rtCountData '
    END

    SET @tSQLMaster += ' FROM ( '
    SET @tSQLMaster += ' SELECT DISTINCT'

    IF @nMaxTopPage > 0 BEGIN
        SET @tSQLMaster += ' TOP ' + CAST(@nMaxTopPage  AS VARCHAR(10)) + ' '
    END

        --SET @tSQLMaster += ' ROW_NUMBER () OVER (ORDER BY Products.FDCreateOn DESC) AS FNRowID,'
    SET @tSQLMaster += ' Products.FTPdtForSystem, '
    SET @tSQLMaster += ' Products.FTPdtCode,PDT_IMG.FTImgObj,'

    IF @ptUsrLevel != 'HQ'  BEGIN
            SET @tSQLMaster += ' PDLSPC.FTAgnCode,PDLSPC.FTBchCode,PDLSPC.FTShpCode,PDLSPC.FTMerCode, '
    END ELSE BEGIN
            SET @tSQLMaster += ' '''' AS FTAgnCode,'''' AS FTBchCode,'''' AS  FTShpCode,'''' AS FTMerCode, '
    END 

    SET @tSQLMaster += ' Products.FTPdtStaLot,'
    SET @tSQLMaster += ' Products.FTPtyCode,'
    SET @tSQLMaster += ' Products.FTPgpChain,'
    SET @tSQLMaster += ' Products.FTPdtStaVatBuy,Products.FTPdtStaVat,Products.FTVatCode,Products.FTPdtStaActive, Products.FTPdtSetOrSN, Products.FTPdtStaAlwDis,Products.FTPdtType,Products.FCPdtCostStd,'
    SET @tSQLMaster += ' PDTSPL.FTSplCode,PDTSPL.FTUsrCode AS FTBuyer,PBAR.FTBarCode,PPCZ.FTPunCode,PPCZ.FCPdtUnitFact,'
    SET @tSQLMaster += ' Products.FTCreateBy,'
    SET @tSQLMaster += ' Products.FDCreateOn'
    SET @tSQLMaster += ' FROM'
    SET @tSQLMaster += ' TCNMPdt Products WITH (NOLOCK)'

    IF @tPagename = 'Promotion' BEGIN------//-----------------เงื่อนไขพิเศษ ตามหน้า-------------
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtLot PDTLOT WITH (NOLOCK) ON Products.FTPdtCode = PDTLOT.FTPdtCode '
    END
    
    IF @ptUsrLevel != 'HQ'  BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtSpcBch PDLSPC WITH (NOLOCK) ON Products.FTPdtCode = PDLSPC.FTPdtCode'
    END

    SET @tSQLMaster += ' INNER JOIN TCNMPdtPackSize PPCZ WITH (NOLOCK) ON Products.FTPdtCode = PPCZ.FTPdtCode LEFT JOIN TCNMPdtBar PBAR WITH (NOLOCK)  ON Products.FTPdtCode = PBAR.FTPdtCode  AND PPCZ.FTPunCode = PBAR.FTPunCode' --//หาบาร์โค้ด
    SET @tSQLMaster += ' LEFT JOIN TCNMPdtSpl PDTSPL WITH (NOLOCK) ON PBAR.FTPdtCode = PDTSPL.FTPdtCode AND PBAR.FTBarCode = PDTSPL.FTBarCode '--//ผู้จำหน่าย
    SET @tSQLMaster += ' LEFT JOIN TCNMImgPdt AS PDT_IMG WITH(NOLOCK) ON Products.FTPdtCode = PDT_IMG.FTImgRefID AND PDT_IMG.FTImgTable = ''TCNMPdt'' AND PDT_IMG.FNImgSeq = 1 '					
    
    ---//--------การจอยตาราง------///
    IF @tFilterBy = 'FTPdtCode' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' '--//รหัสสินค้า
    END

    IF @tFilterBy = 'TCNTPdtStkBal' BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON Products.FTPdtCode = STK.FTPdtCode AND STK.FTBchCode IN ('+@tSesBchCodeMulti+') AND STK.FTWahCode = '''+@tWahCode+''' '
    END		

    --IF @tFilterBy = 'FTPdtName' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNMPdt_L PDTL WITH (NOLOCK)       ON Products.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID   = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''' '--//หาชื่อสินค้า
    --END

    /*IF @tFilterBy = 'PDTANDBarcode' OR @tFilterBy = 'FTPlcCode' OR @tSqlCode != '' BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtPackSize PPCZ WITH (NOLOCK) ON PDT.FTPdtCode = PPCZ.FTPdtCode LEFT JOIN TCNMPdtBar PBAR WITH (NOLOCK)      ON PDT.FTPdtCode = PBAR.FTPdtCode  AND PPCZ.FTPunCode = PBAR.FTPunCode' --//หาบาร์โค้ด
    END

    IF @tFilterBy = 'FTBarCode' BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtPackSize PPCZ WITH (NOLOCK) ON PDT.FTPdtCode = PPCZ.FTPdtCode LEFT JOIN TCNMPdtBar PBAR WITH (NOLOCK)      ON PDT.FTPdtCode = PBAR.FTPdtCode  AND PPCZ.FTPunCode = PBAR.FTPunCode' --//หาบาร์โค้ด
    END*/

    IF @tFilterBy = 'FTPunCode' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtUnit_L PUNL WITH (NOLOCK)   ON PPCZ.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''' ' --//หาหน่วย
    END								

    IF @tFilterBy = 'FTPgpChain' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtGrp_L PGL WITH (NOLOCK)     ON PGL.FTPgpChain = Products.FTPgpChain AND PGL.FNLngID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''' '--//หากลุ่มสินค้า
    END							

    IF @tFilterBy = 'FTPtyCode' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtType_L PTL WITH (NOLOCK)    ON Products.FTPtyCode = PTL.FTPtyCode   AND PTL.FNLngID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''' '--//หาประเภทสินค้า
    END	

    IF @tFilterBy = 'FTBuyer' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' '--//ผู้จัดซื้อ
    END

    /* IF @tSqlCode != '' BEGIN------//----------------ผู้จำหน่าย-------------------
        SET @tSQLMaster += ' LEFT JOIN TCNMPdtSpl PDTSPL WITH (NOLOCK) ON PBAR.FTPdtCode = PDTSPL.FTPdtCode AND PBAR.FTBarCode = PDTSPL.FTBarCode '--//ผู้จำหน่าย
    END*/

    ---//--------การจอยตาราง------///

    SET @tSQLMaster += ' LEFT JOIN TCNMPdtCategory CATINFO WITH (NOLOCK) ON Products.FTPdtCode = CATINFO.FTPdtCode '

    SET @tSQLMaster += ' WHERE ISNULL(Products.FTPdtCode,'''') != '''' '


    ---//--------การค้นหา------///
    IF @tFilterBy = 'FTPdtCode' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' AND ( Products.FTPdtCode  COLLATE THAI_BIN    LIKE ''%' + @tSearch + '%'' )'--//รหัสสินค้า
    END

    IF @tFilterBy = 'FTPdtName' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' AND ( UPPER(PDTL.FTPdtName)  COLLATE THAI_BIN    LIKE UPPER(''%' + @tSearch + '%'') ) '--//หาชื่อสินค้า
    END

    IF @tFilterBy = 'FTBarCode' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' AND ( PBAR.FTBarCode  COLLATE THAI_BIN    LIKE ''%' + @tSearch + '%'' )' --//หาบาร์โค้ด
    END

    IF @tFilterBy = 'PDTANDBarcode' AND @tSearch <> '' BEGIN
				SET @tSQLMaster += ''
        --SET @tSQLMaster += ' AND ( PBAR.FTPdtCode =''' + @tSearch + '''  OR  PBAR.FTBarCode =''' + @tSearch + ''' )' --//หาบาร์โค้ด
    END

    IF @tFilterBy = 'FTPunCode' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' AND ( PUNL.FTPunName  COLLATE THAI_BIN    LIKE ''%' + @tSearch + '%'' OR PUNL.FTPunCode COLLATE THAI_BIN LIKE ''%' + @tSearch + '%'' )' --//หาหน่วย
    END								

    IF @tFilterBy = 'FTPgpChain' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' AND ( PGL.FTPgpName   COLLATE THAI_BIN    LIKE ''%' + @tSearch + '%'' OR PGL.FTPgpChainName COLLATE THAI_BIN LIKE ''%' + @tSearch + '%'' ) '--//หากลุ่มสินค้า
    END							

    IF @tFilterBy = 'FTPtyCode' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' AND ( PTL.FTPtyName   COLLATE THAI_BIN    LIKE ''%' + @tSearch + '%'' ) '--//หาประเภทสินค้า
    END	

    IF @tFilterBy = 'FTBuyer' AND @tSearch <> '' BEGIN
        SET @tSQLMaster += ' '--//ผู้จัดซื้อ
    END

    IF @tPagename = 'Promotion' BEGIN------//-----------------เงื่อนไขพิเศษ ตามหน้า-------------
        SET @tSQLMaster += ' AND (Products.FTPdtStaLot = ''2'' OR Products.FTPdtStaLot = ''1'' AND Products.FTPdtStaLot = ''1'' AND ISNULL(PDTLOT.FTLotNo,'''') <> '''' ) '
    END
    ---//--------การค้นหา------///

    ---//--------การมองเห็นสินค้าตามผู้ใช้------///
    IF @tUsrLevel != 'HQ' BEGIN
        --//---------------------- การมองเห็นเฉพาะสินค้าตามระดับผู้ใช้--------------------------//
        SET @tSQLMaster += ' AND ( ('
        SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''+@tSesAgnCode+''' '

                    IF @tSesMerCode != '' AND @tSesMerCode != '' BEGIN 
                            SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''+@tSesMerCode+''' '
                    END

                    IF (SELECT ISNULL(FTBchCode,'') FROM TCNTUsrGroup WHERE FTUsrCode = @tUsrCode )<>'' BEGIN
                            IF (@tSesBchCodeMulti <> '') BEGIN
                                SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''') IN ('+@tSesBchCodeMulti+') '
                            END ELSE BEGIN
                                SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''') = '''' '
                            END
                    END
                                
                    IF @tSesShopCodeMulti != '' BEGIN 
                            SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTShpCode,'''') IN ('+@tSesShopCodeMulti+') '
                    END

        SET @tSQLMaster += ' )'
        -- |-------------------------------------------------------------------------------------------| 

        --//---------------------- การมองเห็นสินค้าระดับสาขา (สำหรับผู้ใช้ระดับร้านค้า)--------------------------//
    IF @tSesShopCodeMulti != '' BEGIN 
        SET @tSQLMaster += ' OR ('--//กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่อยู่ใน Bch แต่ไม่ผูก Shp
        SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''+@tSesAgnCode+''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''+@tSesMerCode+''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''') IN ('+@tSesBchCodeMulti+') '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTShpCode,'''') = '''' '
        SET @tSQLMaster += ' )'

        SET @tSQLMaster += ' OR (' --//กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่อยู่ใน Bch แต่ไม่ผูก Shp และไม่ผูก Mer
        SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''+@tSesAgnCode+''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''') IN ('+@tSesBchCodeMulti+') '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTShpCode,'''') = '''' '
        SET @tSQLMaster += ' )'

        SET @tSQLMaster += ' OR (' --//กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่ไม่ผูก Bch และ ไม่ผูก Shp
        SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''+@tSesAgnCode+''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''+@tSesMerCode+''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''') = '''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTShpCode,'''') = '''' '
        SET @tSQLMaster += ' )'

        SET @tSQLMaster += ' OR (' --//กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่ไม่ผูก Mer และสินค้าผูก Bch / Shp
        SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''+@tSesAgnCode+''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''' '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''') IN ('+@tSesBchCodeMulti+') '
        SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTShpCode,'''') IN ('+@tSesShopCodeMulti+') '
        SET @tSQLMaster += ' )'
    END
    -- |-------------------------------------------------------------------------------------------| 

    -- //---------------------- การมองเห็นสินค้าระดับส่วนกลางหรือสินค้าที่ไม่ได้ผูกกับอะไรเลย--------------------------//
    SET @tSQLMaster += ' OR ('

    SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''+@tSesAgnCode+''' '

    IF @tSesMerCode != '' AND @tSesMerCode != '' BEGIN --//กรณีผู้ใช้ผูก Mer จะต้องเห็นสินค้าที่ไม่ได้ผูก Mer ด้วย
            SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''' '
    END

    IF (SELECT ISNULL(FTBchCode,'') FROM TCNTUsrGroup WHERE FTUsrCode= @tUsrCode)<>'' BEGIN --//กรณีผู้ใช้ผูก Bch จะต้องเห็นสินค้าที่ไม่ได้ผูก Bch ด้วย
            SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''')  = '''' '
    END

    IF @tSesShopCodeMulti != '' BEGIN 
            SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTShpCode,'''') = '''' '
    END

    SET @tSQLMaster += ' )'
    -- |-------------------------------------------------------------------------------------------| 

    -- //---------------------- การมองเห็นสินค้าระดับส่วนกลางหรือสินค้าที่ไม่ได้ผูกกับอะไรเลย--------------------------//
    SET @tSQLMaster += ' OR ('
    SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''' '
    SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''' '
    SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTBchCode,'''') = '''' '
    SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTShpCode,'''') = '''' '
    SET @tSQLMaster += ' ))'
    -- |-------------------------------------------------------------------------------------------| 

    END
    ---//--------การมองเห็นสินค้าตามผู้ใช้------///


    -----//----Option-----//------

    IF @tWhere != '' BEGIN
        SET @tSQLMaster += @tWhere
    END
    
    IF @tNotInPdtType != '' BEGIN-----//------------- ไม่แสดงสินค้าตาม ประเภทสินค้า -------------------
        SET @tSQLMaster += ' AND ISNULL(Products.FTPDtCode,'''') NOT IN ('+@tNotInPdtType+') '
    END

    IF @tPdtCodeIgnorParam != '' BEGIN----//-------------สินค้าที่ไม่ใช่ตัวข้อมูลหลักในการจัดสินค้าชุด-------------------
        SET @tSQLMaster += ' AND ISNULL(Products.FTPDtCode,'''') != '''+@tPdtCodeIgnorParam+''' '
    END

    IF @tPDTMoveon != '' BEGIN------//---------สินค้าเคลื่อนไหว---------
        SET @tSQLMaster += ' AND  Products.FTPdtStaActive = '''+@tPDTMoveon+''' '
    END

    IF @tPlcCodeConParam != '' AND @tFilterBy = 'FTPlcCode' BEGIN---/ที่เก็บ-  //กรณีที่เข้าไปหา plc code เเล้วไม่เจอ PDT เลย ต้องให้มันค้นหา โดย KEYWORD : EMPTY
            IF  @tPlcCodeConParam != 'EMPTY' BEGIN
                    SET @tSQLMaster += ' AND  PBAR.FTBarCode = '''+@tPlcCodeConParam+''' '
            END
            ELSE BEGIN
                    SET @tSQLMaster += ' AND  PPCZ.FTPdtCode = ''EMPTY'' AND PPCZ.FTPunCode = ''EMPTY'' '
            END
    END

    IF @ptDISTYPE != '' BEGIN------//----------------อนุญาตลด----------------
        SET @tSQLMaster += ' AND  Products.FTPdtStaAlwDis = '''+@ptDISTYPE+''' '
    END

    IF @tPagename = 'PI' BEGIN------//-----------------เงื่อนไขพิเศษ ตามหน้า-------------
        SET @tSQLMaster += ' AND  Products.FTPdtSetOrSN != ''4'' '
    END

    IF @tNotinItemString  != '' BEGIN-------//-----------------ไม่เอาสินค้าอะไรบ้าง NOT IN-----------
        SET @tSQLMaster += @tNotinItemString
    END

    IF @tSqlCode != '' BEGIN------//----------------ผู้จำหน่าย-------------------
        SET @tSQLMaster += ' AND  ( PDTSPL.FTSplCode = '''+@tSqlCode+'''  OR ISNULL(PDTSPL.FTSplCode,'''') = '''' ) '
    END
    -----//----Option-----//------
        
    SET @tSQLMaster += ' ) Base '

    IF @nRow != ''  BEGIN------------เงื่อนไขพิเศษ แบ่งหน้า----
        SET @tSQLMaster += ' ORDER BY Base.FDCreateOn DESC '
        SET @tSQLMaster += ' OFFSET '+CAST(((@nPage-1)*@nRow) AS VARCHAR(10))+' ROWS FETCH NEXT '+CAST(@nRow AS VARCHAR(10))+' ROWS ONLY'
    END
    ----//----------------------Data Master And Filter-------------//			

    ----//----------------------Query Builder-------------//

    SET @tSQL = '  SELECT PDT.rtCountData ,PDT.FTAgnCode,PDT.FTBchCode AS FTPdtSpcBch,PDT.FTShpCode,PDT.FTMerCode,PDT.FTImgObj,';
    SET @tSQL += ' PDT.FTPdtCode,PDT_L.FTPdtName,PDT.FTPdtForSystem,PDT.FTPdtStaVatBuy,PDT.FTPdtStaVat,PDT.FTVatCode,ISNULL(VAT.FCVatRate, 0) AS FCVatRate, '
    SET @tSQL += ' PDT.FTPdtStaActive,PDT.FTPdtSetOrSN,PDT.FTPgpChain,PDT.FTPtyCode,ISNULL(PDT_AGE.FCPdtCookTime,0) AS FCPdtCookTime,ISNULL(PDT_AGE.FCPdtCookHeat,0) AS FCPdtCookHeat, '
    SET @tSQL += ' PDT.FTPunCode,PDT_UNL.FTPunName,PDT.FCPdtUnitFact, PDT.FTSplCode,PDT.FTBuyer,PDT.FTBarCode,PDT.FTPdtStaAlwDis,PDT.FTPdtType,PDT.FCPdtCostStd,PDT.FTPdtStaLot'

    IF @tPriceType = 'Pricesell' OR @tPriceType = '' BEGIN------///ถ้าเป็นราคาขาย---
        SET @tSQL += '  ,0 AS FCPgdPriceNet,VPA.FCPgdPriceRet AS FCPgdPriceRet,0 AS FCPgdPriceWhs'
    END

    IF @tPriceType = 'Price4Cst' BEGIN------// //ถ้าเป็นราคาทุน-----
        SET @tSQL += '  ,0 AS FCPgdPriceNet,0 AS FCPgdPriceWhs,'
        SET @tSQL += '  CASE'
        SET @tSQL += '  WHEN ISNULL(PCUS.FCPgdPriceRet,0) <> 0 THEN PCUS.FCPgdPriceRet'
        SET @tSQL += '  WHEN ISNULL(PBCH.FCPgdPriceRet,0) <> 0 THEN PBCH.FCPgdPriceRet'
        SET @tSQL += '  WHEN ISNULL(PEMPTY.FCPgdPriceRet,0) <> 0 THEN PEMPTY.FCPgdPriceRet'
        SET @tSQL += '  ELSE 0'
        SET @tSQL += '  END AS FCPgdPriceRet'
    END

    IF @tPriceType = 'Cost' BEGIN------//-----
        SET @tSQL += '  ,ISNULL(VPC.FCPdtCostStd,0)       AS FCPdtCostStd    , ISNULL(FCPdtCostAVGIN,0)     AS FCPdtCostAVGIN,'
        SET @tSQL += '  ISNULL(VPC.FCPdtCostAVGEx,0)     AS FCPdtCostAVGEx  , ISNULL(FCPdtCostLast,0)      AS FCPdtCostLast,'
        SET @tSQL += '  ISNULL(VPC.FCPdtCostFIFOIN,0)    AS FCPdtCostFIFOIN , ISNULL(FCPdtCostFIFOEx,0)    AS FCPdtCostFIFOEx'
    END

    SET @tSQL += ' FROM ('
    SET @tSQL +=  @tSQLMaster
    SET @tSQL += ' ) PDT ';
    SET @tSQL += ' LEFT JOIN TCNMPdt_L AS PDT_L WITH(NOLOCK) ON PDT.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''' '
    SET @tSQL += ' LEFT JOIN TCNMPdtUnit_L AS PDT_UNL WITH(NOLOCK) ON PDT.FTPunCode = PDT_UNL.FTPunCode  AND PDT_UNL.FNLngID = ''' + CAST(@nLngID  AS VARCHAR(10)) + ''''
    --SET @tSQL += ' LEFT OUTER JOIN TCNMImgPdt AS PDT_IMG WITH(NOLOCK) ON PDT.FTPdtCode = PDT_IMG.FTImgRefID AND PDT_IMG.FTImgTable = ''TCNMPdt'' AND PDT_IMG.FNImgSeq = 1 '
    SET @tSQL += ' LEFT OUTER JOIN TCNMPdtAge AS PDT_AGE WITH(NOLOCK) ON PDT.FTPdtCode = PDT_AGE.FTPdtCode '
    SET @tSQL += ' LEFT OUTER JOIN VCN_VatActive AS VAT WITH(NOLOCK) ON PDT.FTVatCode = VAT.FTVatCode '

    IF @tPriceType = 'Pricesell' OR @tPriceType = ''  BEGIN------//-----
        --SET @tSQL += '  '
        SET @tSQL += '  LEFT JOIN VCN_Price4PdtActive VPA WITH(NOLOCK) ON VPA.FTPdtCode = PDT.FTPdtCode AND VPA.FTPunCode = PDT_UNL.FTPunCode'
    END

    IF @tPriceType = 'Price4Cst' BEGIN
        --//----ราคาของ customer
        SET @tSQL += '  LEFT JOIN ( '
        SET @tSQL += ' SELECT * FROM ('
        SET @tSQL += ' SELECT '
        SET @tSQL += ' ROW_NUMBER () OVER ( PARTITION BY FTPdtCode , FTPunCode ORDER BY FDPghDStart DESC) AS FNRowPart,'
        SET @tSQL += ' FTPdtCode , '
        SET @tSQL += ' FTPunCode , '
        SET @tSQL += ' FCPgdPriceRet '
        SET @tSQL += ' FROM TCNTPdtPrice4PDT WHERE  '
        SET @tSQL += ' FDPghDStart <= CONVERT (VARCHAR(10), GETDATE(), 121)'
        SET @tSQL += ' AND FDPghDStop >= CONVERT (VARCHAR(10), GETDATE(), 121)'
        SET @tSQL += ' AND FTPghTStart <= CONVERT(time,GETDATE())'
        SET @tSQL += ' AND FTPghTStop >= CONVERT(time,GETDATE())'
        SET @tSQL += ' AND FTPplCode = '''+@tPplCode+''' '
        SET @tSQL += ' ) AS PCUS '
        SET @tSQL += ' WHERE PCUS.FNRowPart = 1 '
        SET @tSQL += ' ) PCUS ON PDT.FTPdtCode = PCUS.FTPdtCode AND PDT.FTPunCode = PCUS.FTPunCode' 

        --// --ราคาของสาขา
        SET @tSQL += ' LEFT JOIN ('
        SET @tSQL += ' SELECT * FROM ('
        SET @tSQL += ' SELECT '
        SET @tSQL += ' ROW_NUMBER () OVER ( PARTITION BY FTPdtCode , FTPunCode ORDER BY FDPghDStart DESC) AS FNRowPart,'
        SET @tSQL += ' FTPdtCode , '
        SET @tSQL += ' FTPunCode , '
        SET @tSQL += ' FCPgdPriceRet '
        SET @tSQL += ' FROM TCNTPdtPrice4PDT WHERE  '
        SET @tSQL += ' FDPghDStart <= CONVERT (VARCHAR(10), GETDATE(), 121)'
        SET @tSQL += ' AND FDPghDStop >= CONVERT (VARCHAR(10), GETDATE(), 121)'
        SET @tSQL += ' AND FTPghTStart <= CONVERT(time,GETDATE())'
        SET @tSQL += ' AND FTPghTStop >= CONVERT(time,GETDATE())'
        SET @tSQL += ' AND FTPplCode = (SELECT FTPplCode FROM TCNMBranch WHERE FTPplCode != '''' AND FTBchCode = (SELECT TOP 1 FTBchCode FROM TCNMBranch WHERE FTAgnCode = '''+@tSesAgnCode+''' ))'
        SET @tSQL += ') AS PCUS '
        SET @tSQL += ' WHERE PCUS.FNRowPart = 1 '
        SET @tSQL += ' ) PBCH ON PDT.FTPdtCode = PBCH.FTPdtCode AND PDT.FTPunCode = PBCH.FTPunCode '

        --// --ราคาที่ไม่กำหนด PPL
        SET @tSQL += ' LEFT JOIN ('
        SET @tSQL += ' SELECT * FROM ('
        SET @tSQL += ' SELECT '
        SET @tSQL += ' ROW_NUMBER () OVER ( PARTITION BY FTPdtCode , FTPunCode ORDER BY FDPghDStart DESC) AS FNRowPart,'
        SET @tSQL += ' FTPdtCode , '
        SET @tSQL += ' FTPunCode , '
        SET @tSQL += ' FCPgdPriceRet '
        SET @tSQL += 'FROM TCNTPdtPrice4PDT WHERE  '
        SET @tSQL += ' FDPghDStart <= CONVERT (VARCHAR(10), GETDATE(), 121)'
        SET @tSQL += 'AND FDPghDStop >= CONVERT (VARCHAR(10), GETDATE(), 121)'
        SET @tSQL += ' AND FTPghTStart <= CONVERT(time,GETDATE())'
        SET @tSQL += ' AND FTPghTStop >= CONVERT(time,GETDATE())'
        SET @tSQL += ' AND ISNULL(FTPplCode,'''') = '''' '
        SET @tSQL += ' ) AS PCUS '
        SET @tSQL += ' WHERE PCUS.FNRowPart = 1 '
        SET @tSQL += ' ) PEMPTY ON PDT.FTPdtCode = PEMPTY.FTPdtCode AND PDT.FTPunCode = PEMPTY.FTPunCode'
    END

    IF @tPriceType = 'Cost' BEGIN
    SET @tSQL += '  LEFT JOIN VCN_ProductCost VPC WITH(NOLOCK) ON VPC.FTPdtCode = PDT.FTPdtCode'
    END

EXECUTE(@tSQL)
END
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxAPHisPayDebt') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxAPHisPayDebt
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxAPHisPayDebt]
	@ptAgnCode VARCHAR(20),
	@ptUsrSession VARCHAR(100),
	@pnLangID INT,
	@ptBchCode VARCHAR(5000),
	@ptSplCodeFrm VARCHAR(30),
	@ptSplCodeTo VARCHAR(30),
	@pnStaLeft VARCHAR(1),
	@pdXpdDueDateFrm VARCHAR(10),
	@pdXpdDueDateTo VARCHAR(10)

AS
BEGIN TRY
            
			DECLARE @tSQL  VARCHAR(MAX)
			SET @tSQL = ' '

			DECLARE @tSQLFilter VARCHAR(500)
			SET @tSQLFilter = ''

			IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
				BEGIN
					 SET @tSQLFilter += ' AND ISNULL(HD.FTAgnCode,'''')  = ''' + @ptAgnCode + ''' '
				END

			IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
				BEGIN
					 SET @tSQLFilter += ' AND HD.FTBchCode  IN ('+@ptBchCode+')'
				END

			IF ((@ptSplCodeFrm <> '' OR @ptSplCodeFrm <> NULL) AND  (@ptSplCodeTo <> '' OR @ptSplCodeTo <> NULL))
				BEGIN
						SET @tSQLFilter += ' AND ISNULL(HD.FTSplCode,'''') BETWEEN ''' + @ptSplCodeFrm + ''' AND ''' + @ptSplCodeTo + ''' '
				END


			IF (@pnStaLeft <> '' OR @pnStaLeft <> NULL )
				BEGIN
				     IF (@pnStaLeft = '1')
					     BEGIN 
						      SET @tSQLFilter += ' AND ISNULL(HD.FCXphLeft,0) > 0 '
						 END
					 ELSE IF (@pnStaLeft = '2')
					     BEGIN 
						      SET @tSQLFilter += ' AND ISNULL(HD.FCXphLeft,0) > 0'
						 END
					 ELSE IF (@pnStaLeft = '3')
					     BEGIN 
						      SET @tSQLFilter += ' AND ISNULL(HD.FCXphLeft,0) = 0 '
						 END
					 ELSE
					     BEGIN 
						      SET @tSQLFilter += ' '
						 END
				END
			ELSE 
			    BEGIN
					 SET @tSQLFilter += ''
				END

		   -----------------------------------------------------------------------------------
		    DECLARE @tSQLFilter2 VARCHAR(100)
			SET @tSQLFilter2 = ''

			IF ((@pdXpdDueDateFrm <> '' OR @pdXpdDueDateFrm <> NULL) AND  (@pdXpdDueDateTo <> '' OR @pdXpdDueDateTo <> NULL))
				BEGIN
						SET @tSQLFilter2 += ' AND CONVERT(VARCHAR(10),ISNULL(FDXpdDueDate,''''),121) BETWEEN  ''' + @pdXpdDueDateFrm + ''' AND ''' + @pdXpdDueDateTo + ''' '
				END



            DELETE FROM TRPTAPHisPayDebtTmp WITH (ROWLOCK) WHERE FTUsrSessID =  '' + @ptUsrSession + ''

            SET @tSQL += ' INSERT INTO TRPTAPHisPayDebtTmp '
			SET @tSQL += ' SELECT A.* '
			SET @tSQL += ' FROM ( '
			SET @tSQL += ' SELECT  '
				   SET @tSQL += ' HD.FTSplCode, '
				   SET @tSQL += ' SPL.FTSplName, '
				   SET @tSQL += ' HD.FTBchCode, '
				   SET @tSQL += ' ISNULL(BCHL.FTBchName,''N/A'') AS FTBchName, '
				   SET @tSQL += ' HD.FTXphDocNo,  '
				   SET @tSQL += ' HD.FDXphDocDate, '
				   SET @tSQL += ' DATEADD(DAY, ISNULL(CRD.FNSplCrTerm,0), REF.FDXshRefDocDate) AS FDXpdDueDate, '
				   SET @tSQL += ' CASE WHEN ISNULL(HD.FCXphLeft,0)  = 0 THEN HD.FDLastUpdOn '
				   SET @tSQL += ' ELSE NULL END AS FDXpdPayDate, '
				   SET @tSQL += ' ISNULL(HD.FCXphGrand,0) AS FCXphGrand, '
				   SET @tSQL += ' ISNULL(HD.FCXphPaid,0) AS FCXphPaid, '
				   SET @tSQL += ' ISNULL(HD.FCXphLeft,0) AS FCXphLeft, '
				   SET @tSQL += ' '''+ @ptUsrSession +''' AS FTUsrSessID '
			SET @tSQL += ' FROM TAPTPiHD HD '
				 SET @tSQL += ' INNER JOIN TAPTPiHDDocRef REF ON HD.FTXphDocNo = REF.FTXshDocNo '
												  SET @tSQL += ' AND REF.FTXshRefType = 3 '
												  SET @tSQL += ' AND REF.FTXshRefKey = ''BillNote'' '
				 SET @tSQL += ' LEFT JOIN TCNMSplCredit CRD ON HD.FTSplCode = CRD.FTSplCode '
				 SET @tSQL += ' LEFT JOIN TCNMSpl_L SPL ON HD.FTSplCode = SPL.FTSplCode AND SPL.FNLngID =  ' + CAST(@pnLangID AS varchar(1))
				 SET @tSQL += ' LEFT JOIN TCNMBranch BCH ON ISNULL(HD.FTAgnCode,'''') = BCH.FTAgnCode AND HD.FTBchCode = BCH.FTBchCode '
				 SET @tSQL += ' LEFT JOIN TCNMBranch_L BCHL ON BCH.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID =  ' + CAST(@pnLangID AS varchar(1))

			SET @tSQL += ' WHERE ISNULL(REF.FDXshRefDocDate,'''') <> '''' '
			SET @tSQL += ' AND HD.FTXphStaDoc = 1 '
			SET @tSQL += ' AND HD.FTXphStaApv = 1 '
			
			SET @tSQL += @tSQLFilter
		
			SET @tSQL += ' ) A '
			SET @tSQL += ' WHERE A.FTSplCode <> '''' '

			SET @tSQL += @tSQLFilter2
		
			
			--PRINT(@tSQL)
			EXECUTE(@tSql)
			
            return 0
END TRY
BEGIN CATCH
  return -1
END CATCH
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxCstFollowAFSrvTmp') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxCstFollowAFSrvTmp
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxCstFollowAFSrvTmp]
	@ptSessionID VARCHAR(100),
	@pnLangID INT,
	@ptAgnCode VARCHAR(20),
	@ptBchCode VARCHAR(255),
	@ptCstCodeFrm VARCHAR(20),
	@ptCstCodeTo VARCHAR(20),
	@ptCarRegNoFrm VARCHAR(30),
	@ptCarRegNoTo VARCHAR(30),
	@pdServDateFrm VARCHAR(10),
	@pdServDateTo VARCHAR(10),
	@pnResult INT OUTPUT
AS
BEGIN TRY
    DECLARE @tSQL   VARCHAR(MAX)
	SET @tSQL = ''

	DECLARE @tSQLFilter VARCHAR(MAX)
	SET @tSQLFilter = ''

	IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
	BEGIN
			SET @tSQLFilter += ' AND ISNULL(HD.FTAgnCode,'''') = ''' + @ptAgnCode + ''' '
	END

	IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
	BEGIN
			SET @tSQLFilter += ' AND HD.FTBchCode IN( ' +  @ptBchCode + ' ) '
	END
		
	IF ((@ptCstCodeFrm <> '' OR @ptCstCodeFrm <> NULL) AND  (@ptCstCodeTo <> '' OR @ptCstCodeTo <> NULL))
	BEGIN
			SET @tSQLFilter += ' AND HD.FTCstCode BETWEEN ''' + @ptCstCodeFrm + ''' AND ''' + @ptCstCodeTo + ''' '
	END

	IF ((@ptCarRegNoFrm <> '' OR @ptCarRegNoFrm <> NULL) AND  (@ptCarRegNoTo <> '' OR @ptCarRegNoTo <> NULL))
	BEGIN
			SET @tSQLFilter += ' AND CAR.FTCarCode BETWEEN ''' + @ptCarRegNoFrm + ''' AND ''' + @ptCarRegNoTo + ''' '
	END

	IF ((@pdServDateFrm <> '' OR @pdServDateFrm <> NULL) AND  (@pdServDateTo <> '' OR @pdServDateTo <> NULL))
	BEGIN
			SET @tSQLFilter += ' AND CONVERT(VARCHAR(10), HD.FDXshDocDate, 121) BETWEEN ''' + @pdServDateFrm + ''' AND ''' + @pdServDateTo + ''' '
	END

    DELETE FROM TRPTCstFollowAFSrvTmp WHERE FTUsrSession = ''+@ptSessionID+''

	SET @tSQL += ' INSERT INTO TRPTCstFollowAFSrvTmp(' 
	SET @tSQL += ' FDXshDocDate,'
  SET @tSQL += ' FTCstName,'
  SET @tSQL += ' FTXshDocNo,'
  SET @tSQL += ' FTCarRegNo,'
  SET @tSQL += ' FNXshQtyAfterSrv,'
  SET @tSQL += ' FTCstTel,'
  SET @tSQL += ' FNRptRowSeq,'
  SET @tSQL += ' FTUsrSession)'

    SET @tSQL += ' SELECT T.* FROM ( '
	SET @tSQL += ' SELECT HD.FDXshDocDate,  '

		   SET @tSQL += ' CSTL.FTCstName, '
		   SET @tSQL += ' HD.FTXshDocNo, '
		   SET @tSQL += ' CAR.FTCarRegNo,  '
		   SET @tSQL += ' DATEDIFF(DAY, CONVERT(VARCHAR(10), HD.FDXshDocDate, 121), GETDATE()) AS FNXshQtyAfterSrv, '
		   SET @tSQL += ' CST.FTCstTel, '
			 SET @tSQL += ' ROW_NUMBER() OVER(ORDER BY HD.FTXshDocNo) AS FNRptRowSeq, '
		   SET @tSQL += ' '''+@ptSessionID+''' AS FTUsrSession '

	SET @tSQL += ' FROM TSVTJob2OrdHD HD WITH(NOLOCK) '

		SET @tSQL += ' LEFT JOIN TSVTJob2OrdHDCst HDCST WITH(NOLOCK) ON HD.FTXshDocNo = HDCST.FTXshDocNo '
		 SET @tSQL += ' LEFT JOIN TSVMCar CAR WITH(NOLOCK) ON HDCST.FTCarCode = CAR.FTCarCode '
		 SET @tSQL += ' LEFT JOIN TCNMCst CST WITH(NOLOCK) ON HD.FTCstCode = CST.FTCstCode '
		 SET @tSQL += ' LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON HD.FTCstCode = CSTL.FTCstCode '
												  SET @tSQL += ' AND CSTL.FNLngID = '+ '' + CAST(@pnLangID AS VARCHAR(1)) +''
		 SET @tSQL += ' LEFT JOIN TSVTJob5ScoreHDDocRef JSC WITH(NOLOCK) ON HD.FTXshDocNo = JSC.FTXshRefDocNo '
															 SET @tSQL += ' AND JSC.FTXshRefType = 2 '
	SET @tSQL += ' WHERE HD.FTXshStaDoc = 1 '
	SET @tSQL += ' AND HD.FTXshStaClosed = 1 '
	SET @tSQL += ' AND ISNULL(JSC.FTXshRefDocNo,'''') = '''' '

	SET @tSQL += @tSQLFilter


	--AND HD.FTAgnCode = '00001'
	--AND HD.FTBchCode IN ('00001','99999')
	--AND HD.FTCstCode BETWEEN 'AR0000100003' AND 'AR0000100004'
	--AND CAR.FTCarRegNo BETWEEN '00001' AND '99999'
	--AND CONVERT(VARCHAR(10), HD.FDXshDocDate, 121) BETWEEN '2020-01-01' AND '2025-01-01' 

	SET @tSQL += ' ) T '

	SET @tSQL += ' WHERE FNXshQtyAfterSrv >= 3 '
	EXEC(@tSQL)
	RETURN 0
END TRY	
BEGIN CATCH
    DECLARE @nResult INT
	SET @nResult = -1
	RETURN @nResult
END CATCH
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxCstForcastByCar') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxCstForcastByCar
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxCstForcastByCar]
	@ptUsrSessID VARCHAR(100),
	@ptAgnCode VARCHAR(20),
	@ptBchCode VARCHAR(255),
	@ptCstCodeFrm VARCHAR(20),
	@ptCstCodeTo VARCHAR(20),
	@ptCarRegNoFrm VARCHAR(20),
	@ptCarRegNoTo VARCHAR(20),
	@pdLastServDateFrm VARCHAR(10),
	@pdLastServDateTo VARCHAR(10),
	@pnLangID  INT,
	@pnResult  INT OUTPUT
AS
BEGIN TRY

		DECLARE @tSQL VARCHAR(MAX)
		SET @tSQL = ''

		DECLARE @tSQLFilter VARCHAR(MAX)
		SET @tSQLFilter = ''

		IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
		BEGIN
			 SET @tSQLFilter += ' AND ISNULL(FLW.FTAgnCode,'''') = ''' + @ptAgnCode + ''' '
		END

		IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
		BEGIN
			 SET @tSQLFilter += ' AND FLW.FTBchCode IN( ' +  @ptBchCode + ' ) '
		END
		
	    IF ((@ptCstCodeFrm <> '' OR @ptCstCodeFrm <> NULL) AND  (@ptCstCodeTo <> '' OR @ptCstCodeTo <> NULL))
		BEGIN
			 SET @tSQLFilter += ' AND CAR.FTCarOwner BETWEEN ''' + @ptCstCodeFrm + ''' AND ''' + @ptCstCodeTo + ''' '
		END

		IF ((@ptCarRegNoFrm <> '' OR @ptCarRegNoFrm <> NULL) AND  (@ptCarRegNoTo <> '' OR @ptCarRegNoTo <> NULL))
		BEGIN
			 SET @tSQLFilter += ' AND CAR.FTCarCode BETWEEN ''' + @ptCarRegNoFrm + ''' AND ''' + @ptCarRegNoTo + ''' '
		END

		IF ((@pdLastServDateFrm <> '' OR @pdLastServDateFrm <> NULL) AND  (@pdLastServDateTo <> '' OR @pdLastServDateTo <> NULL))
		BEGIN
			 SET @tSQLFilter += ' AND CONVERT(VARCHAR(10),FLW.FDFlwLastDate,121) BETWEEN ''' + @pdLastServDateFrm + ''' AND ''' + @pdLastServDateTo + ''' '
		END

        DELETE FROM TRPTCstForcastByCar WHERE FTUsrSession = ''+@ptUsrSessID+''

		SET @tSQL += ' INSERT INTO TRPTCstForcastByCar(' 
		SET @tSQL += ' FDFlwDateForcast,'
		SET @tSQL += ' FTCstCode,'
		SET @tSQL += ' FTXshCstName,'
		SET @tSQL += ' FTCstTel,'
		SET @tSQL += ' FTCstAddress,'
		SET @tSQL += ' FTCstEmail,'
		SET @tSQL += ' FTCarRegNo,'
		SET @tSQL += ' FDFlwLastDate,'
		SET @tSQL += ' FNXshScoreValue,'
		SET @tSQL += ' FTBchName,'
		SET @tSQL += ' FNRptRowSeq,'
		SET @tSQL += ' FTUsrSession)'


		
		SET @tSQL += ' SELECT CFW.FDFlwDateForcast, '
			   SET @tSQL += ' CFW.FTCstCode, '
			   SET @tSQL += ' HDC.FTXshCstName, '
			   SET @tSQL += ' CST.FTCstTel, '
			   SET @tSQL += ' CASE '
				   SET @tSQL += ' WHEN CADR.FTAddVersion = ''1'' '
				   SET @tSQL += ' THEN ISNULL(CADR.FTAddV1No, '''') + '''' + ISNULL(CADR.FTAddV1Soi, '''') + '''' + ISNULL(SUB.FTSudName, '''') + '''' + ISNULL(SDT.FTDstName, '''') + '''' + ISNULL(PVN.FTPvnName, '''') + '''' + ISNULL(CADR.FTAddV1PostCode, '''') '
				   SET @tSQL += ' ELSE CADR.FTAddV2Desc1 + '''' + CADR.FTAddV2Desc2 '
			   SET @tSQL += ' END AS FTCstAddress, '
			   SET @tSQL += ' CST.FTCstEmail, '
			   SET @tSQL += ' CFW.FTCarRegNo, '
			   SET @tSQL += ' CFW.FDFlwLastDate, '
			   SET @tSQL += ' SCH.FNXshScoreValue, '
			   SET @tSQL += ' BCH.FTBchName, '
				 SET @tSQL += ' ROW_NUMBER() OVER(ORDER BY CFW.FTCstCode) AS FNRptRowSeq, '
			   SET @tSQL += ' '''+@ptUsrSessID+''' AS FTUsrSession '
		SET @tSQL += ' FROM '
		SET @tSQL += ' ( '
			SET @tSQL += ' SELECT FLW.* '
			SET @tSQL += ' FROM '
			SET @tSQL += ' ( '
				SET @tSQL += ' SELECT ROW_NUMBER() OVER(PARTITION BY FLW.FTCarCode '
					   SET @tSQL += ' ORDER BY FLW.FTCarCode, '
								SET @tSQL += ' FLW.FDFlwDateForcast) AS FNClwPartNo, '
					   SET @tSQL += ' FLW.FDFlwDateForcast, '
					   SET @tSQL += ' FLW.FTFlwDocRef, '
					   SET @tSQL += ' FLW.FTAgnCode, '
					   SET @tSQL += ' FLW.FTBchCode, '
					   SET @tSQL += ' FLW.FTCarCode, '
					   SET @tSQL += ' CAR.FTCarRegNo, '
					   SET @tSQL += ' CAR.FTCarOwner AS FTCstCode, '
					   SET @tSQL += ' FLW.FDFlwLastDate '
				SET @tSQL += ' FROM TSVTCstFollow FLW '
					 SET @tSQL += ' INNER JOIN TSVMCar CAR ON CAR.FTCarCode = FLW.FTCarCode '
				SET @tSQL += ' WHERE FTFlwStaBook = 1 '
				SET @tSQL += @tSQLFilter
			SET @tSQL += ' ) FLW '
			SET @tSQL += ' WHERE FLW.FNClwPartNo = 1 '
		SET @tSQL += ' ) CFW '
		SET @tSQL += ' LEFT JOIN TSVTJob2OrdHDCst HDC ON HDC.FTAgnCode = CFW.FTAgnCode '
										  SET @tSQL += ' AND HDC.FTBchCode = CFW.FTBchCode '
										  SET @tSQL += ' AND HDC.FTXshDocNo = CFW.FTFlwDocRef '

		SET @tSQL += ' INNER JOIN TCNMCst CST ON CFW.FTCstCode = CST.FTCstCode '

		SET @tSQL += ' INNER JOIN TCNMCst_L CSTL ON CST.FTCstCode = CSTL.FTCstCode '
									 SET @tSQL += ' AND CSTL.FNLngID = ' + CAST(@pnLangID AS VARCHAR(1))

		SET @tSQL += ' LEFT JOIN (
											SELECT B.* FROM (
												SELECT ROW_NUMBER() OVER(PARTITION BY A.FTCstCode , A.FTAddVersion ORDER BY A.FTCstCode ASC) AS PartID , A.* FROM TCNMCstAddress_L A
											) AS B WHERE B.PartID <= 1
										) AS CADR ON '
									 SET @tSQL += ' CST.FTCstCode = CADR.FTCstCode '
									 SET @tSQL += ' AND CADR.FNLngID = ' + CAST(@pnLangID AS VARCHAR(1))

		SET @tSQL += ' LEFT JOIN TSVTJob5ScoreHDDocRef SCR ON CFW.FTFlwDocRef = SCR.FTXshRefDocNo '
											   SET @tSQL += ' AND SCR.FTXshRefType = 1 '

		SET @tSQL += ' LEFT JOIN TSVTJob5ScoreHD SCH ON SCH.FTAgnCode = SCR.FTAgnCode '
										 SET @tSQL += ' AND SCH.FTBchCode = SCR.FTBchCode '
										 SET @tSQL += ' AND SCH.FTXshDocNo = SCR.FTXshDocNo '
										 SET @tSQL += ' AND SCR.FTXshRefType = 1 '

		SET @tSQL += ' LEFT JOIN TCNMBranch_L BCH ON CFW.FTBchCode = BCH.FTBchCode '
									  SET @tSQL += ' AND BCH.FNLngID =  ' + CAST(@pnLangID AS VARCHAR(1))

		SET @tSQL += ' LEFT JOIN TCNMSubDistrict_L SUB ON CADR.FTAddV1SubDist = SUB.FTSudCode '
										   SET @tSQL += ' AND SUB.FNLngID =  ' + CAST(@pnLangID AS VARCHAR(1))

		SET @tSQL += ' LEFT JOIN TCNMDistrict_L SDT ON CADR.FTAddV1DstCode = SDT.FTDstCode '
										SET @tSQL += ' AND SDT.FNLngID =  ' + CAST(@pnLangID AS VARCHAR(1))

		SET @tSQL += ' LEFT JOIN TCNMProvince_L PVN ON CADR.FTAddV1PvnCode = PVN.FTPvnCode '
										SET @tSQL += ' AND PVN.FNLngID =  ' + CAST(@pnLangID AS VARCHAR(1))

		EXEC(@tSQL)
		RETURN 0

END TRY
BEGIN CATCH 

    RETURN -1

END CATCH
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxPdtAdjStkHis') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxPdtAdjStkHis
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxPdtAdjStkHis]
	-- System Report --
	@pnLngID int , 
	@ptComName Varchar(100),
	@ptRptCode Varchar(100),
	@ptUsrSession Varchar(255),
	-- ตัวแทนขาย
	@ptAgnCode VARCHAR(20),
	-- สาขา
	@ptBchCode VARCHAR(500),
	-- วันที่เริ่ม - วันที่สิ้นสุด
	@pdDocDateFrm VARCHAR(10),
	@pdDocDateTo VARCHAR(10),
	-- รีเทิร์นค่ากลับ
	@pnResult INT OUTPUT
AS
BEGIN TRY
	-- DECLARE SYSTEMS REPORT
	DECLARE @nLngID int 
	DECLARE @tComName Varchar(100)
	DECLARE @tRptCode Varchar(100)
	DECLARE @tUsrSession Varchar(255)


	SET @nLngID 		= @pnLngID
	SET @tComName 		= @ptComName
	SET @tUsrSession	= @ptUsrSession
	SET @tRptCode 		= @ptRptCode
	
	-- DECLARE SQL MAIN
	DECLARE @tSQL VARCHAR(MAX)
	SET @tSQL	= ''
	
	-- DECLARE SQL FILTER
	DECLARE @tSQLFilter VARCHAR(255)
	SET @tSQLFilter = ''

	-- CHECK AGENCY CODE
	IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
	BEGIN
		SET @tSQLFilter += ' AND ISNULL(BCH.FTAgnCode,'''') = ''' + @ptAgnCode + ''' '
	END
	
	-- CHECK BRANCH CODE
	IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
	BEGIN
		SET @tSQLFilter += ' AND HD.FTBchCode IN( ' +  @ptBchCode + ' ) '
	END
	
	-- CHECK DOCUMENT DATE FROM - TO
	IF ((@pdDocDateFrm <> '' OR @pdDocDateFrm <> NULL) AND  (@pdDocDateTo <> '' OR @pdDocDateTo <> NULL))
	BEGIN
		SET @tSQLFilter += ' AND CONVERT(VARCHAR(10),HD.FDAjhDocDate,121) BETWEEN ''' + @pdDocDateFrm + ''' AND ''' + @pdDocDateTo + ''' '
	END
	
	-- DELETE CLEAR TRPTPdtAdjStkHisTmp
	DELETE FROM TRPTPdtAdjStkHisTmp WHERE FTComName =  '' + @tComName + ''  AND FTRptCode = '' + @tRptCode + '' AND FTUsrSession = '' + @tUsrSession + ''
	
	-- SQL QUERY TEXT
	SET @tSQL	+='INSERT INTO TRPTPdtAdjStkHisTmp ('
	SET @tSQL	+='	FTComName,FTRptCode,FTUsrSession,'
	SET @tSQL +=' FTBchCode,FTBchName,FTWahCode,FTWahName,FTAjhDocNo,FDAjhDocDate,FTPdtCode,FTPdtName,FCAjdWahB4Adj,FCAjdQtyAll,FCAjdQtyAllDiff'
	SET @tSQL +=')'
	SET @tSQL +='SELECT '''+ @tComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'
	SET @tSQL +='  HD.FTBchCode,BCHL.FTBchName,WH.FTWahCode,WH.FTWahName,HD.FTAjhDocNo,HD.FDAjhDocDate,DT.FTPdtCode,DT.FTPdtName, '
	SET @tSQL +='	 DT.FCAjdWahB4Adj,'
	SET @tSQL +='  CASE'
	SET @tSQL	+=' 	 WHEN HD.FTAjhApvSeqChk = 1	THEN ISNULL(DT.FCAjdQtyAllC1, 0)'
	SET @tSQL +='		 WHEN HD.FTAjhApvSeqChk = 2	THEN ISNULL(DT.FCAjdQtyAllC2, 0)'
	SET @tSQL	+='		 WHEN HD.FTAjhApvSeqChk = 3	THEN ISNULL(DT.FCAjdQtyAll, 0)'
	SET @tSQL +='  ELSE 0'
	SET @tSQL +='  END AS FCAjdQtyAll,'
	SET @tSQL +='  DT.FCAjdQtyAllDiff'
	SET @tSQL +=' FROM TCNTPdtAdjStkDT DT WITH(NOLOCK)'
	SET @tSQL +=' LEFT JOIN TCNTPdtAdjStkHD HD WITH(NOLOCK) ON DT.FTBchCode = HD.FTBchCode AND DT.FTAjhDocNo = HD.FTAjhDocNo'
	SET @tSQL +=' LEFT JOIN TCNMWaHouse_L WH WITH(NOLOCK) ON HD.FTBchCode = WH.FTBchCode AND HD.FTAjhWhTo = WH.FTWahCode AND WH.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''' '
	SET @tSQL +=' LEFT JOIN TCNMBranch BCH WITH(NOLOCK) ON HD.FTBchCode = BCH.FTBchCode'
	SET @tSQL +=' LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''' '
	SET @tSQL +=' WHERE HD.FTAjhStaDoc = 1'
	SET @tSQL +=' AND HD.FTAjhStaApv = 1 '
	SET @tSQL += @tSQLFilter
	EXECUTE(@tSQL)
END TRY
BEGIN CATCH
   RETURN -1
END CATCH
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxJobScoreAnalyze') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxJobScoreAnalyze
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxJobScoreAnalyze] 
       @ptUsrSession VARCHAR(100),
	   @pnLangID INT,
	   @ptAgnCode VARCHAR(20),
	   @ptBchCode VARCHAR(255),
	   @pdAmsDateFrm VARCHAR(20),
	   @pdAmsDateTo VARCHAR(20),
	   @pnResult INT
AS
BEGIN TRY 
        DECLARE @nResult INT
		SET @nResult = @pnResult
		DECLARE @tSQL VARCHAR(MAX)
		DECLARE @tSQLFilter VARCHAR(255)
		SET @tSQLFilter = ''

		IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
		BEGIN
			 SET @tSQLFilter += ' AND ISNULL(FTAgnCode,'''') = ''' + @ptAgnCode + ''' '
		END

		IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
		BEGIN
			 SET @tSQLFilter += ' AND FTBchCode IN( ' +  @ptBchCode + ' ) '
		END
		
	    IF ((@pdAmsDateFrm <> '' OR @pdAmsDateFrm <> NULL) AND  (@pdAmsDateTo <> '' OR @pdAmsDateTo <> NULL))
		BEGIN
			 SET @tSQLFilter += ' AND CONVERT(VARCHAR(10),FDCreateOn,121) BETWEEN ''' + @pdAmsDateFrm + ''' AND ''' + @pdAmsDateTo + ''' '
		END

        DELETE FROM TRPTJobScoreAnalyzeTmp WHERE FTUsrSession = ''+@ptUsrSession+''

        SET @tSQL = ' INSERT INTO TRPTJobScoreAnalyzeTmp '
		SET @tSQL += ' SELECT ' 
		       SET @tSQL += ' ROW_NUMBER() OVER (ORDER BY JOB5.FTQahDocNo) AS FNRptRowSeq, '
			   SET @tSQL += ' FNQahGroup = DENSE_RANK() OVER( '
			   SET @tSQL += ' ORDER BY JOB5.FTQahDocNo), '
			   SET @tSQL += ' JOB5.FNQadSeqNo, '
			   SET @tSQL += ' QAH.FTQahName, '
			   SET @tSQL += ' QAD.FTQadName, '
			   SET @tSQL += ' SCO.FNSJob5core1, '
			   SET @tSQL += ' SCO.FNSJob5core2, '
			   SET @tSQL += ' SCO.FNSJob5core3, '
			   SET @tSQL += ' SCO.FNSJob5core4, '
			   SET @tSQL += ' SCO.FNSJob5core5, '
			   SET @tSQL += ' CAST(CASE '
						SET @tSQL += ' WHEN ISNULL(SCO.FNSJob5coreTotal, 0) > 0 '
						SET @tSQL += ' THEN((SCO.FNSJob5core1 * 1) + (SCO.FNSJob5core2 * 2) + '
						SET @tSQL += ' (SCO.FNSJob5core3 * 3) + (SCO.FNSJob5core4 * 4) + '
						SET @tSQL += ' (SCO.FNSJob5core5 * 5)) / SCO.FNSJob5coreTotal '
						SET @tSQL += ' ELSE 0 '
					SET @tSQL += ' END AS NUMERIC(18, 2)) AS FCJob5AvgScore, '
			   SET @tSQL += ' '''' AS FTScoAsmCrt, '
			   SET @tSQL += ' '''+@ptUsrSession+''' AS FTUsrSession '
		SET @tSQL += ' FROM '
		SET @tSQL += ' ( '
			SET @tSQL += ' SELECT DISTINCT '
				   SET @tSQL += ' FTQahDocNo, '
				   SET @tSQL += ' FNQadSeqNo '
			SET @tSQL += ' FROM TSVTJob5ScoreDT '
			SET @tSQL += ' WHERE  ISNULL(FTQahDocNo,'''') <> '''' '
			SET @tSQL += @tSQLFilter
		SET @tSQL += ' ) JOB5 '
		SET @tSQL += ' INNER JOIN TCNTQaDT QAD ON JOB5.FTQahDocNo = QAD.FTQahDocNo '
								   SET @tSQL += ' AND JOB5.FNQadSeqNo = QAD.FNQadSeqNo '
		SET @tSQL += ' INNER JOIN TCNTQaHD QAH ON QAH.FTQahDocNo = QAD.FTQahDocNo '
		SET @tSQL += ' LEFT JOIN '
		SET @tSQL += ' ( '
			SET @tSQL += ' SELECT FTQahDocNo, '
				   SET @tSQL += ' FNQadSeqNo, '
				   SET @tSQL += ' SUM(CASE '
						   SET @tSQL += ' WHEN FTXsdStaAnsValue = ''1'' '
						   SET @tSQL += ' THEN CAST(FTXsdStaAnsValue AS INT) '
						   SET @tSQL += ' ELSE 0 '
					   SET @tSQL += ' END) AS FNSJob5core1, '
				   SET @tSQL += ' SUM(CASE '
						   SET @tSQL += ' WHEN FTXsdStaAnsValue = ''2'' '
						   SET @tSQL += ' THEN CAST(FTXsdStaAnsValue AS INT) '
						   SET @tSQL += ' ELSE 0 '
					   SET @tSQL += ' END) AS FNSJob5core2, '
				   SET @tSQL += ' SUM(CASE '
						   SET @tSQL += ' WHEN FTXsdStaAnsValue = ''3'' '
						   SET @tSQL += ' THEN CAST(FTXsdStaAnsValue AS INT) '
						   SET @tSQL += ' ELSE 0 '
					   SET @tSQL += ' END) AS FNSJob5core3, '
				   SET @tSQL += ' SUM(CASE '
						   SET @tSQL += ' WHEN FTXsdStaAnsValue = ''4'' '
						   SET @tSQL += ' THEN CAST(FTXsdStaAnsValue AS INT) '
						   SET @tSQL += ' ELSE 0 '
					   SET @tSQL += ' END) AS FNSJob5core4, '
				   SET @tSQL += ' SUM(CASE '
						   SET @tSQL += ' WHEN FTXsdStaAnsValue = ''5'' '
						   SET @tSQL += ' THEN CAST(FTXsdStaAnsValue AS INT) '
						   SET @tSQL += ' ELSE 0 '
					   SET @tSQL += ' END) AS FNSJob5core5, '
				   SET @tSQL += ' SUM(CAST(FTXsdStaAnsValue AS INT)) AS FNSJob5coreTotal '
			SET @tSQL += ' FROM TSVTJob5ScoreDTAns DT '
			SET @tSQL += 'LEFT JOIN TSVTJob5ScoreHD HD ON DT.FTXshDocNO = HD.FTXshDocNO '
			SET @tSQL += 'WHERE 1=1 '
			SET @tSQL += @tSQLFilter
			SET @tSQL += ' GROUP BY FTQahDocNo, '
					 SET @tSQL += ' FNQadSeqNo '
		SET @tSQL += ' ) SCO ON JOB5.FTQahDocNo = SCO.FTQahDocNo '
				 SET @tSQL += ' AND JOB5.FNQadSeqNo = SCO.FNQadSeqNo '
			
		EXEC (@tSQL)
		RETURN @nResult
 
END TRY	
BEGIN CATCH
	SET @nResult = -1
	RETURN @nResult
END CATCH
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxCstLostCont') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxCstLostCont
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxCstLostCont] 
       @ptUsrSession VARCHAR(100),
	   @pnLangID INT,
	   @ptAgnCode VARCHAR(20),
	   @ptBchCode VARCHAR(20),
	   @pnLostContNum INT,
	   @ptCstCodeFrm VARCHAR(20),
	   @ptCstCodeTo VARCHAR(20),
	   @ptCarRegNoFrm VARCHAR(20),
	   @ptCarRegNoTo VARCHAR(20),
	   @pnResult INT OUTPUT
AS
BEGIN TRY 
  
  DECLARE @tBchCode     VARCHAR(20) 
  DECLARE @nResult     INT 
  SET @nResult = @pnResult

  DECLARE @tSQL     VARCHAR(MAX)
  SET @tSQL = ''

  DECLARE @tSubFilter1   VARCHAR(255)  
  SET @tSubFilter1 = ''

  DECLARE @tSubFilter2   VARCHAR(200)
  SET @tSubFilter2 = ''

  DECLARE @tMainFilter   VARCHAR(200)  
  SET @tMainFilter  = ''


  IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
    BEGIN
	     SET @tSubFilter1 += ' AND ISNULL(FTAgnCode,'''') = ''' + @ptAgnCode + ''' '
	END

  
  IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
    BEGIN
	     SET @tSubFilter1 += ' AND FTBchCode IN( ' +  @ptBchCode + ' ) '
	END

  IF ((@ptCarRegNoFrm <> '' OR @ptCarRegNoFrm <> NULL) AND  (@ptCarRegNoTo <> '' OR @ptCarRegNoTo <> NULL))
    BEGIN
	     SET @tSubFilter1 += ' AND TSVMCar.FTCarCode BETWEEN ''' + @ptCarRegNoFrm + ''' AND ''' + @ptCarRegNoTo + ''' '
    END

  
  IF ((@ptCstCodeFrm <> '' OR @ptCstCodeFrm <> NULL) AND  (@ptCstCodeTo <> '' OR @ptCstCodeTo <> NULL))
    BEGIN
	     SET @tMainFilter += ' WHERE CST.FTCstCode BETWEEN ''' + @ptCstCodeFrm + ''' AND ''' + @ptCstCodeTo + ''' '
    END

  --DELETE FROM TRPTSVCstLostContTmp WHERE FTUsrSession = ' ''' + @ptUsrSession + ''' '
  DELETE FROM TRPTSVCstLostContTmp WITH (ROWLOCK) WHERE FTUsrSession =  '' + @ptUsrSession + ''

  SET @tSQL += ' INSERT INTO TRPTSVCstLostContTmp(FNRptRowSeq, '
									 SET @tSQL += ' FTUsrSession, '
									 SET @tSQL += ' FDFlwLastDate, '
									 SET @tSQL += ' FTCstName, '
									 SET @tSQL += ' FTFlwDocRef, '
									 SET @tSQL += ' FTCarRegNo, '
									 SET @tSQL += ' FNXshScoreValue, '
									 SET @tSQL += ' FTCstTel) '

  SET @tSQL += ' SELECT ROW_NUMBER() OVER(ORDER BY FDFlwLastDate) AS FNTmpRowsNo, '
  SET @tSQL += ' ''' +@ptUsrSession + ''' AS FTUsrSession,  '  
  SET @tSQL += ' CSF.FDFlwLastDate, '
  SET @tSQL += ' CSTL.FTCstName, '
  SET @tSQL += ' CSF.FTFlwDocRef, '  
  SET @tSQL += ' CAR.FTCarRegNo, '
  SET @tSQL += ' SCR.FNXshScoreValue, '
  SET @tSQL += ' CST.FTCstTel '
  SET @tSQL += ' FROM ( '
  SET @tSQL += ' SELECT FLW.* FROM ( '
				 SET @tSQL += '	SELECT ' 
				 SET @tSQL += ' ROW_NUMBER() OVER ( PARTITION BY TSVTCstFollow.FTCarCode ORDER BY TSVTCstFollow.FDFlwLastDate DESC) FNFlwPartNo, '
				 SET @tSQL += ' FTBchCode,TSVTCstFollow.FTCarCode AS FTCarCode,FTCarRegNo,FTFlwDocRef,FDFlwLastDate, '
				 SET @tSQL += ' DATEDIFF(DAY, FDFlwLastDate,GETDATE()) AS FDFlwPTD	'			
				 SET @tSQL += ' FROM TSVTCstFollow '
				 SET @tSQL += ' INNER JOIN TSVMCar ON TSVTCstFollow.FTCarCode = TSVMCar.FTCarCode'
				 SET @tSQL += ' WHERE ISNULL(FTFlwDocRef,'''') <> '''' '
				 SET @tSQL += @tSubFilter1
  SET @tSQL +=  ' ) FLW ' 
  SET @tSQL +=  ' WHERE  FLW.FNFlwPartNo = 1 '
  SET @tSQL +=  ' AND    FLW.FDFlwPTD >=  ' + CAST(@pnLostContNum AS VARCHAR)
  SET @tSQL +=  ' ) CSF '
  SET @tSQL +=  ' INNER JOIN TSVTJob2OrdHD JOB WITH(NOLOCK) '
  SET @tSQL +=  ' ON CSF.FTFlwDocRef = JOB.FTXshDocNo AND CSF.FTBchCode = JOB.FTBchCode ' 
  SET @tSQL +=  ' INNER JOIN TSVMCar CAR WITH(NOLOCK)  ON CSF.FTCarCode = CAR.FTCarCode '
  SET @tSQL +=  ' INNER JOIN TCNMCst CST WITH(NOLOCK) ON CST.FTCstCode = CAR.FTCarOwner '
  SET @tSQL +=  ' LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID= ' + CAST(@pnLangID AS VARCHAR)
  SET @tSQL +=  ' LEFT  JOIN TSVTJob5ScoreHDDocRef REF WITH(NOLOCK) ON REF.FTXshRefDocNo = CSF.FTFlwDocRef '
  SET @tSQL +=  ' LEFT JOIN TSVTJob5ScoreHD SCR WITH(NOLOCK) ON REF.FTXshDocNo = SCR.FTXshDocNo ' 
  SET @tSQL += @tMainFilter
  --PRINT(@tSQL)
  EXEC(@tSQL)
  --RETURN  @nResult
END TRY	
BEGIN CATCH
	SET @nResult = -1
	RETURN @nResult
END CATCH
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxSalAvgByQtyByWeek') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxSalAvgByQtyByWeek
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxSalAvgByQtyByWeek]
	-- Add the parameters for the stored procedure here
	@ptAgnCode VARCHAR(20),
	@ptSessionID VARCHAR(150),
	@pnLangID INT,
	@ptBchCode VARCHAR(255),
	@ptDayOfWeek VARCHAR(1),
	@ptRptSubBy VARCHAR(20),
	@ptDayOfWeekFrm VARCHAR(1),
	@ptDayOfWeekTo VARCHAR(1)
AS
BEGIN TRY
      DECLARE @tSQL VARCHAR(MAX)
			SET @tSQL = ''

			DECLARE @tSQLFilter VARCHAR(MAX)
			SET @tSQLFilter = ''

			--IF (@ptAgnCode <> '' OR @ptAgnCode <> NULL)
			--BEGIN
			--	 SET @tSQLFilter += ' AND ISNULL(HD.FTAgnCode,'''') = ''' + @ptAgnCode + ''' '
			--END

			IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
			BEGIN
				 SET @tSQLFilter += ' AND HD.FTBchCode IN ( ' +  @ptBchCode + ' ) '
			END
		
			/*IF (@ptDayOfWeek <> '' OR @ptDayOfWeek <> NULL)
			BEGIN
				 SET @tSQLFilter += ' AND DATEPART(WEEKDAY, HD.FDXshDocDate) =  ' + @ptDayOfWeek
			END*/
	
			IF (@ptDayOfWeekFrm <> '' OR @ptDayOfWeekFrm <> NULL)
			BEGIN
					SET @tSQLFilter +=' AND DATEPART(WEEKDAY, HD.FDXshDocDate) BETWEEN ''' + @ptDayOfWeekFrm + ''' AND ''' + @ptDayOfWeekTo + ''''
			END


			DECLARE @tSubRptBy VARCHAR(150)
			SET @tSubRptBy = ''

			DECLARE @tRptSubShow VARCHAR(150)
			SET @tRptSubShow = ''

			DECLARE @tRptGroupBy VARCHAR(150)
			SET @tRptGroupBy = ''

			DECLARE @tRptOrderBy VARCHAR(150)
			SET @tRptOrderBy = ''

			DECLARE @tRptGetSubName VARCHAR(300)
			SET @tRptGetSubName = ''

			IF((@ptRptSubBy <> '' OR @ptRptSubBy <> NULL) AND @ptRptSubBy = 'PdtType')
			   BEGIN
			        SET @tSubRptBy += ' ISNULL(PDT.FTPtyCode, '''') AS FTPtyCode, '
					SET @tRptGroupBy += ' ISNULL(PDT.FTPtyCode, ''''), '
					SET @tRptSubShow += ' T.FTPtyCode,PTY.FTPtyName, '
					SET @tRptOrderBy += ' T.FTPtyCode, '
					SET @tRptGetSubName += ' LEFT JOIN TCNMPdtType_L PTY ON T.FTPtyCode = PTY.FTPtyCode AND PTY.FNLngID =  ' + CAST(@pnLangID AS varchar(1))
			   END

			IF((@ptRptSubBy <> '' OR @ptRptSubBy <> NULL) AND @ptRptSubBy = 'PdtBrand')
			   BEGIN
			        SET @tSubRptBy += ' ISNULL(PDT.FTPbnCode, '''') AS FTPbnCode, '
					SET @tRptGroupBy += ' ISNULL(PDT.FTPbnCode, ''''), '
					SET @tRptSubShow += ' T.FTPbnCode,BND.FTPbnName, '
					SET @tRptOrderBy += ' T.FTPbnCode, '
					SET @tRptGetSubName += ' LEFT JOIN TCNMPdtBrand_L BND ON T.FTPbnCode = BND.FTPbnCode AND BND.FNLngID =  ' + CAST(@pnLangID AS varchar(1))
			   END

			IF((@ptRptSubBy <> '' OR @ptRptSubBy <> NULL) AND @ptRptSubBy = 'PdtModel')
			   BEGIN
			        SET @tSubRptBy += ' ISNULL(PDT.FTPmoCode, '''') AS FTPmoCode, '
					SET @tRptGroupBy += ' ISNULL(PDT.FTPmoCode, ''''), '
					SET @tRptSubShow += ' T.FTPmoCode,MOD.FTPmoName, '
					SET @tRptOrderBy += ' T.FTPmoCode, '
					SET @tRptGetSubName += ' LEFT JOIN TCNMPdtModel_L MOD ON T.FTPmoCode = MOD.FTPmoCode AND MOD.FNLngID =  ' + CAST(@pnLangID AS varchar(1))
			   END

			IF((@ptRptSubBy <> '' OR @ptRptSubBy <> NULL) AND @ptRptSubBy = 'PdtChain')
			   BEGIN
			        SET @tSubRptBy += ' ISNULL(PDT.FTPgpChain, '''') AS FTPgpChain, '
					SET @tRptGroupBy += ' ISNULL(PDT.FTPgpChain, ''''), '
					SET @tRptSubShow += ' T.FTPgpChain,GRP.FTPgpName, '
					SET @tRptOrderBy += ' T.FTPgpChain, '
					SET @tRptGetSubName += ' LEFT JOIN TCNMPdtGrp_L GRP ON T.FTPgpChain = GRP.FTPgpChain AND GRP.FNLngID =  ' + CAST(@pnLangID AS varchar(1))
			   END

			
			SET DATEFIRST 1;

			DELETE FROM TRPTSalAvgByQtyByWeekTmp WHERE FTUsrSession = ''+@ptSessionID+''

			SET @tSQL += ' INSERT INTO TRPTSalAvgByQtyByWeekTmp '

			SET @tSQL += ' SELECT 
			       ROW_NUMBER() OVER (PARTITION BY T.FTBchCode , '+ @tRptOrderBy +' T.FNXshDayOfWeek ORDER BY T.FTBchCode,'+@tRptOrderBy+' T.FNXshDayOfWeek ) AS FTXshSeqNo , 
			       T.FTBchCode, 
				   BCH.FTBchName, ' + @tRptSubShow + '
				   T.FNXshDayOfWeek, 
				   T.FCXsdSalePrice / NULLIF(T.FCXsdQtyByWeek,0) AS FCXsdSalePrice, 
				   T.FCXsdQtyByWeek, 
				   (T.FCXsdQtyByWeek * 100) / NULLIF(T.FCXsdQtyTotol,0) AS FCXshPercentByQty, 
				   T.FCXsdNet, 
				   (T.FCXsdNet * 100) / NULLIF(T.FCXsdNetTotol,0) AS FCXshPercentByTotal, 
				   T.FCXshDisChg, 
				   T.FCXshGrand, 
				   (T.FCXshGrand * 100) / NULLIF(T.FCXsdGrandTotol,0) AS FCXshPercentByGrand, 
				   T.FCXshGrand / NULLIF(T.FCXsdQtyByWeek,0) AS FCXshSalAvgByQty , '

			SET @tSQL += ' '''+@ptSessionID+''' AS FTUsrSession '

			SET @tSQL += ' FROM
			(
				SELECT HD.FTBchCode,  ' 

					   SET @tSQL += ' DATEPART(WEEKDAY, HD.FDXshDocDate) AS FNXshDayOfWeek, '

					   SET @tSQL += @tSubRptBy

					   SET @tSQL += ' SUM(CASE
							   WHEN HD.FNXshDocType = 1
							   THEN DT.FCXsdSalePrice
							   ELSE DT.FCXsdSalePrice * -1
						   END) AS FCXsdSalePrice, 
					   SUM(CASE
							   WHEN HD.FNXshDocType = 1
							   THEN DT.FCXsdQty
							   ELSE DT.FCXsdQty * -1
						   END) AS FCXsdQtyByWeek, 
				(
					SELECT SUM(CASE
								   WHEN HD.FNXshDocType = 1
								   THEN DT.FCXsdQty
								   ELSE DT.FCXsdQty * -1
							   END) AS FCXsdQtyTotol
					FROM TPSTSalHD HD
						 INNER JOIN TPSTSalDT DT ON HD.FTBchCode = DT.FTBchCode
													AND HD.FTXshDocNo = DT.FTXshDocNo
				 '

				 SET @tSQL += '	WHERE DATEPART(WEEK, HD.FDXshDocDate) = DATEPART(WEEK, GETDATE())
						  AND CONVERT(VARCHAR(7), HD.FDXshDocDate, 111) = CONVERT(VARCHAR(7), GETDATE(), 111)'

					
				 --SET @tSQL += '	WHERE DATEPART(WEEK, HD.FDXshDocDate) = DATEPART(WEEK, ''2021-11-05'')
					--	  AND CONVERT(VARCHAR(7), HD.FDXshDocDate, 111) = CONVERT(VARCHAR(7), ''2021-11-05'', 111)'

					SET @tSQL +=@tSQLFilter
						   --Filter
						  --AND HD.FTBchCode IN ('00020','00020')
						  --AND DATEPART(WEEKDAY, HD.FDXshDocDate) = 3

				SET @tSQL += ' ) AS FCXsdQtyTotol, 
					   SUM(CASE
							   WHEN HD.FNXshDocType = 1
							   THEN HD.FCXshTotal
							   ELSE HD.FCXshTotal * -1
						   END) AS FCXsdNet, 
				(
					SELECT SUM(CASE
								   WHEN HD.FNXshDocType = 1
								   THEN HD.FCXshTotal
								   ELSE HD.FCXshTotal * -1
							   END) AS FCXsdQtyTotol
					FROM TPSTSalHD HD
						 INNER JOIN TPSTSalDT DT ON HD.FTBchCode = DT.FTBchCode
													AND HD.FTXshDocNo = DT.FTXshDocNo '
													
                SET @tSQL += '	WHERE DATEPART(WEEK, HD.FDXshDocDate) = DATEPART(WEEK, GETDATE())
						  AND CONVERT(VARCHAR(7), HD.FDXshDocDate, 111) = CONVERT(VARCHAR(7), GETDATE(), 111) '


				SET @tSQL +=@tSQLFilter

				SET @tSQL += ' ) AS FCXsdNetTotol, 
					   SUM(CASE
							   WHEN HD.FNXshDocType = 1
							   THEN(HD.FCXshDis - FCXshChg)
							   ELSE(HD.FCXshDis - FCXshChg) * -1
						   END) AS FCXshDisChg, 
					   SUM(CASE
							   WHEN HD.FNXshDocType = 1
							   THEN HD.FCXshGrand - FCXshRnd
							   ELSE(HD.FCXshGrand * FCXshRnd) - 1
						   END) AS FCXshGrand, 
				(
					SELECT SUM(CASE
								   WHEN HD.FNXshDocType = 1
								   THEN HD.FCXshGrand - FCXshRnd
								   ELSE(HD.FCXshGrand - FCXshRnd) * -1
							   END) AS FCXsdGrandTotol
					FROM TPSTSalHD HD
						 INNER JOIN TPSTSalDT DT ON HD.FTBchCode = DT.FTBchCode
													AND HD.FTXshDocNo = DT.FTXshDocNo
					WHERE DATEPART(WEEK, HD.FDXshDocDate) = DATEPART(WEEK, GETDATE())
						  AND CONVERT(VARCHAR(7), HD.FDXshDocDate, 111) = CONVERT(VARCHAR(7), GETDATE(), 111) '
						   --Filter
						   SET @tSQL +=@tSQLFilter
						  --AND HD.FTBchCode IN ('00020','00020')
						  --AND DATEPART(WEEKDAY, HD.FDXshDocDate) = 3

				SET @tSQL += ' ) AS FCXsdGrandTotol
				FROM TPSTSalHD HD
					 INNER JOIN TPSTSalDT DT ON HD.FTBchCode = DT.FTBchCode
												AND HD.FTXshDocNo = DT.FTXshDocNo
					 INNER JOIN TCNMPdt PDT ON DT.FTPdtCode = PDT.FTPdtCode
				WHERE DATEPART(WEEK, HD.FDXshDocDate) = DATEPART(WEEK, GETDATE())
					  AND CONVERT(VARCHAR(7), HD.FDXshDocDate, 111) = CONVERT(VARCHAR(7), GETDATE(), 111) '
					  --Filter
					  SET @tSQL +=@tSQLFilter
					  --AND HD.FTBchCode IN ('00020','00020')
					  --AND DATEPART(WEEKDAY, HD.FDXshDocDate) = 3

				--SET @tSQL += ' GROUP BY ISNULL(HD.FTBchCode,''''), '
				
				SET @tSQL += ' GROUP BY HD.FTBchCode, '

				         --Parameter Group By
						 SET @tSQL += @tRptGroupBy 
						  
						 SET @tSQL += ' DATEPART(WEEKDAY, HD.FDXshDocDate) '

			SET @tSQL += ' ) T '

			--Parameter Join Name Sub
			SET @tSQL += @tRptGetSubName
			SET @tSQL += ' LEFT JOIN TCNMBranch_L BCH ON T.FTBchCode = BCH.FTBchCode AND BCH.FNLngID = ' + CAST(@pnLangID AS varchar(1))

			SET @tSQL += ' ORDER BY T.FTBchCode, '

			 --Parameter ORDER By 
			SET @tSQL += @tRptOrderBy

			SET @tSQL += ' T.FNXshDayOfWeek '
			EXEC(@tSQL)
	RETURN 0
END TRY
BEGIN CATCH
   RETURN -1
END CATCH
GO

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxPurCreditorAge') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxPurCreditorAge
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxPurCreditorAge]
	@pnLngID int , 
	@pnComName Varchar(100),
	@ptRptCode Varchar(100),
	@ptUsrSession Varchar(255),
	@pnFilterType int, --1 BETWEEN 2 IN
	@ptAgnL Varchar(8000), --Agency Condition IN 
	@ptBchL Varchar(8000), --สาขา Condition IN
	@ptShpL Varchar(8000), 
	@ptStaApv Varchar(1),--FTXphStaApv สถานะ อนุมัติ เอกสาร ว่าง:ยังไม่ทำ, 1:อนุมัติแล้ว
	                     --การส่งค่า 0 : ไม่ Condition 1 : ยังไม่อนุมัติ 2 : อนุมัติแล้ว            
	@ptStaPaid Varchar(1),-- FTXphStaPaid สถานะ รับ/จ่ายเงิน 1:ยังไม่จ่าย 2:บางส่วน, 3:ครบ
	@ptSplF Varchar(20), @ptSplT Varchar(20),-- FTSplCode รหัสผู้จำหน่าย
	@ptSgpF Varchar(5),@ptSgpT Varchar(5),-- FTSgpCode กลุ่มผู้จำหน่าย
	@ptStyF Varchar(5),@ptStyT Varchar(5),--FTStyCode ประเภทผู้จำหน่าย	
	@ptDocDateF Varchar(10), @ptDocDateT Varchar(10), 
	@FNResult INT OUTPUT 
AS
BEGIN TRY	
	DECLARE @nLngID int 
	DECLARE @nComName Varchar(100)
	DECLARE @tRptCode Varchar(100)
	DECLARE @tUsrSession Varchar(255)
	DECLARE @tSql VARCHAR(8000)
	DECLARE @tSql1 VARCHAR(8000)

	--Branch Code
	DECLARE @tBchF Varchar(5)
	DECLARE @tBchT Varchar(5)
	--Cashier
	DECLARE @tUsrF Varchar(10)
	DECLARE @tUsrT Varchar(10)
	--Pos Code
	DECLARE @tPosF Varchar(20)
	DECLARE @tPosT Varchar(20)

	DECLARE @tDocDateF Varchar(10)
	DECLARE @tDocDateT Varchar(10)

	SET @nLngID = @pnLngID
	SET @nComName = @pnComName
	SET @tUsrSession = @ptUsrSession
	SET @tRptCode = @ptRptCode

	--Branch
	
	SET @FNResult= 0

	SET @ptDocDateF = CONVERT(VARCHAR(10),@ptDocDateF,121)
	SET @ptDocDateT = CONVERT(VARCHAR(10),@ptDocDateT,121)

	IF @nLngID = null
	BEGIN
		SET @nLngID = 1
	END	

	IF @ptAgnL = null
	BEGIN
		SET @ptAgnL = ''
	END

	IF @ptBchL = null
	BEGIN
		SET @ptBchL = ''
	END

	IF @ptShpL = null
	BEGIN
		SET @ptShpL = ''
	END

	--Purchase
	IF @ptStaApv = NULL ----การส่งค่า 0 : ไม่ Condition 1 : ยังไม่อนุมัติ 2 : อนุมัติแล้ว   FTXphStaApv สถานะ อนุมัติ เอกสาร ว่าง:ยังไม่ทำ, 1:อนุมัติแล้ว
	BEGIN
		SET @ptStaApv = ''
	END

	IF @ptStaPaid = NULL --FTXphStaPaid สถานะ รับ/จ่ายเงิน 1:ยังไม่จ่าย 2:บางส่วน, 3:ครบ
	BEGIN
		SET @ptStaPaid = ''
	END

	IF @ptSplF =NULL -- FTSplCode รหัสผู้จำหน่าย
	BEGIN
		SET @ptSplF = ''
	END
	IF @ptSplT =null OR @ptSplT = ''
	BEGIN
		SET @ptSplT = @ptSplF
	END 

	IF @ptSgpF =NULL -- FTSgpCode กลุ่มผู้จำหน่าย
	BEGIN
		SET @ptSgpF = ''
	END
	IF @ptSgpT =null OR @ptSgpT = ''
	BEGIN
		SET @ptSgpT = @ptSgpF
	END 

	IF @ptStyF =NULL --FTStyCode ประเภทผู้จำหน่าย
	BEGIN
		SET @ptStyF = ''
	END
	
	IF @ptDocDateF = null
	BEGIN 
		SET @ptDocDateF = ''
	END

	IF @ptDocDateT = null OR @ptDocDateT =''
	BEGIN 
		SET @ptDocDateT = @ptDocDateF
	END
	
		
	SET @tSql1 =   ' WHERE ISNULL(HD.FTXphStaPaid,'''') <> ''3'' '
	--Center
	IF (@ptAgnL <> '' )
	BEGIN
		SET @tSql1 +=' AND Bch.FTAgnCode IN (' + @ptAgnL + ')' --Agency
	END


	IF (@ptBchL <> '' )
	BEGIN
		SET @tSql1 +=' AND HD.FTBchCode IN (' + @ptBchL + ')' --Branch
	END


	IF (@ptShpL <> '' )
	BEGIN
		SET @tSql1 +=' AND HD.FTShpCode IN (' + @ptShpL + ')' --Shop
	END
	
	--Purchase
	IF (@ptStaApv <> '') BEGIN
		IF(@ptStaApv = '3') BEGIN
				SET @tSql1 +=' AND  HD.FTXphStaDoc = ''3'' ' 
			END
		ELSE
			SET @tSql1 +=' AND HD.FTXphStaApv =  ''' + @ptStaApv + ''' AND HD.FTXphStaDoc = ''1'' ' 
	END ELSE BEGIN	
		SET @tSql1 +=' AND HD.FTXphStaDoc = ''1'' ' 
	END

	IF (@ptStaPaid<> '') -- FTXphStaPaid สถานะ รับ/จ่ายเงิน 1:ยังไม่จ่าย 2:บางส่วน, 3:ครบ
	BEGIN
		SET @tSql1 +=' AND HD.FTXphStaPaid = ''' + @ptStaPaid + '''' 
	END

	--Supplier
	IF (@ptSplF<> '') -- FTSplCode รหัสผู้จำหน่าย
	BEGIN
		SET @tSql1 +=' AND HD.FTSplCode BETWEEN ''' + @ptSplF + ''' AND ''' + @ptSplT + '''' 
	END

	IF (@ptSgpF<> '') -- FTSgpCode กลุ่มผู้จำหน่าย
	BEGIN
		SET @tSql1 +=' AND Spl.FTSgpCode BETWEEN ''' + @ptSgpF + ''' AND ''' + @ptSgpT + '''' 
	END

	IF (@ptSgpF<> '') -- FTStyCode ประเภทผู้จำหน่าย
	BEGIN
		SET @tSql1 +=' AND Spl.FTStyCode BETWEEN ''' + @ptSgpF + ''' AND ''' + @ptSgpT + '''' 
	END

	
	IF (@ptDocDateF <> '' AND @ptDocDateT <> '')
	BEGIN
    	SET @tSql1 +=' AND CONVERT(VARCHAR(10),HD.FDXphDocDate,121) BETWEEN ''' + @ptDocDateF + ''' AND ''' + @ptDocDateT + ''''	
	END

	DELETE FROM TRPTPurCreditorAgeTmp  WHERE FTComName =  '' + @nComName + ''  AND FTRptCode = '' + @tRptCode + '' AND FTUsrSession = '' + @tUsrSession + ''--Åº¢éÍÁÙÅ Temp ¢Í§à¤Ã×èÍ§·Õè¨ÐºÑ¹·Ö¡¢ÍÁÙÅÅ§ Temp
	
    --Purchase
	SET @tSql = 'INSERT INTO TRPTPurCreditorAgeTmp'
	SET @tSql +=' (FTComName,FTRptCode,FTUsrSession,'
	SET @tSql +=' FTSplCode,FTSplName,FCSplCrLimit,FDXphDueDate,FTXphDocNo,FTXphRefInt,FDXphDocDate,FNXphCrTerm,'
	SET @tSql +=' FCXphBFDue60,FCXphBFDue31And60,FCXphBFDue0And30,FCXphOVDue1,FCXphOVDue2And7,FCXphOVDue8And15,FCXphOVDue16And30,FCXphOVDue31And60,'
	SET @tSql +=' FCXphOVDue61And90,FCXphOVDue90,FCXshLeft'
	SET @tSql +=' )'
	SET @tSql +=' SELECT '''+ @nComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'
	SET @tSql +=' HD.FTSplCode ,Spl_L.FTSplName,ISNULL(SplCrd.FCSplCrLimit,0) AS FCSplCrLimit,'
	SET @tSql +=' CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121) AS FDXphDueDate,HD.FTXphDocNo,FTXphRefInt,'
	SET @tSql +=' CONVERT(VARCHAR(10),HD.FDXphDocDate,121) AS FDXphDocDate,ISNULL(HDSpl.FNXphCrTerm,0) AS FNXphCrTerm,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) > 60 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphBFDue60,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) BETWEEN 31 AND 60 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphBFDue31And60,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) BETWEEN 0 AND 30 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphBFDue0And30,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) = 1 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue1,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 2 AND 7 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue2And7,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 8 AND 15 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue8And15,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 16 AND 30 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue16And30,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 31 AND 60 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue31And60,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 61 AND 90 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue61And90,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  > 90 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue90'
	SET @tSql +=' , HD.FCXphLeft '
	SET @tSql +=' FROM TAPTPiHD HD WITH (NOLOCK)'
	SET @tSql +=' INNER JOIN TAPTPiHDSpl HDSpl WITH (NOLOCK) ON HD.FTBchCode = HDSpl.FTBchCode AND HD.FTXphDocNo = HDSpl.FTXphDocNo'
	SET @tSql +=' LEFT JOIN TCNMBranch Bch WITH (NOLOCK) ON HD.FTBchCode = Bch.FTBchCode'
	SET @tSql +=' LEFT JOIN TCNMBranch_L BchL WITH (NOLOCK) ON HD.FTBchCode = BchL.FTBchCode	AND BchL.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	
	SET @tSql +=' LEFT JOIN TCNMSpl Spl  WITH (NOLOCK) ON HD.FTSplCode = Spl.FTSplCode'
	SET @tSql +=' LEFT JOIN TCNMSpl_L Spl_L  WITH (NOLOCK) ON HD.FTSplCode = Spl_L.FTSplCode AND Spl_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	
	SET @tSql +=' LEFT JOIN TCNMSplCredit SplCrd WITH (NOLOCK) ON HD.FTSplCode = SplCrd.FTSplCode'
	SET @tSql += @tSql1
	--PRINT @tSql
	EXECUTE(@tSql)

	--Create Note
	SET @tSql = 'INSERT INTO TRPTPurCreditorAgeTmp'
	--PRINT @tSql
	SET @tSql +=' (FTComName,FTRptCode,FTUsrSession,'
	SET @tSql +=' FTSplCode,FTSplName,FCSplCrLimit,FDXphDueDate,FTXphDocNo,FTXphRefInt,FDXphDocDate,FNXphCrTerm,'
	SET @tSql +=' FCXphBFDue60,FCXphBFDue31And60,FCXphBFDue0And30,FCXphOVDue1,FCXphOVDue2And7,FCXphOVDue8And15,FCXphOVDue16And30,FCXphOVDue31And60,'
	SET @tSql +=' FCXphOVDue61And90,FCXphOVDue90,FCXshLeft'
	SET @tSql +=' )'
	SET @tSql +=' SELECT '''+ @nComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'
	SET @tSql +=' HD.FTSplCode ,Spl_L.FTSplName,ISNULL(SplCrd.FCSplCrLimit,0) AS FCSplCrLimit,'
	SET @tSql +=' CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121) AS FDXphDueDate,HD.FTXphDocNo,FTXphRefInt,'
	SET @tSql +=' CONVERT(VARCHAR(10),HD.FDXphDocDate,121) AS FDXphDocDate,ISNULL(HDSpl.FNXphCrTerm,0) AS FNXphCrTerm,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) > 60 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphBFDue60,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) BETWEEN 31 AND 60 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphBFDue31And60,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) BETWEEN 0 AND 30 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphBFDue0And30,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) = 1 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphOVDue1,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 2 AND 7 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphOVDue2And7,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 8 AND 15 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphOVDue8And15,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 16 AND 30 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphOVDue16And30,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 31 AND 60 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphOVDue31And60,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 61 AND 90 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphOVDue61And90,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  > 90 THEN ISNULL(FCXphGrand,0)*-1 ELSE 0 END AS FCXphOVDue90'
	SET @tSql +=' , HD.FCXphLeft '
	SET @tSql +=' FROM TAPTPcHD HD WITH (NOLOCK)'
	SET @tSql +=' INNER JOIN TAPTPcHDSpl HDSpl WITH (NOLOCK) ON HD.FTBchCode = HDSpl.FTBchCode AND HD.FTXphDocNo = HDSpl.FTXphDocNo'
	SET @tSql +=' LEFT JOIN TCNMBranch Bch WITH (NOLOCK) ON HD.FTBchCode = Bch.FTBchCode'
	SET @tSql +=' LEFT JOIN TCNMBranch_L BchL WITH (NOLOCK) ON HD.FTBchCode = BchL.FTBchCode	AND BchL.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	
	SET @tSql +=' LEFT JOIN TCNMSpl Spl  WITH (NOLOCK) ON HD.FTSplCode = Spl.FTSplCode'
	SET @tSql +=' LEFT JOIN TCNMSpl_L Spl_L  WITH (NOLOCK) ON HD.FTSplCode = Spl_L.FTSplCode AND Spl_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	
	SET @tSql +=' LEFT JOIN TCNMSplCredit SplCrd WITH (NOLOCK) ON HD.FTSplCode = SplCrd.FTSplCode'
	SET @tSql += @tSql1
	--PRINT @tSql
	EXECUTE(@tSql)

	--Debit Note
	SET @tSql = 'INSERT INTO TRPTPurCreditorAgeTmp'
	--PRINT @tSql
	SET @tSql +=' (FTComName,FTRptCode,FTUsrSession,'
	SET @tSql +=' FTSplCode,FTSplName,FCSplCrLimit,FDXphDueDate,FTXphDocNo,FTXphRefInt,FDXphDocDate,FNXphCrTerm,'
	SET @tSql +=' FCXphBFDue60,FCXphBFDue31And60,FCXphBFDue0And30,FCXphOVDue1,FCXphOVDue2And7,FCXphOVDue8And15,FCXphOVDue16And30,FCXphOVDue31And60,'
	SET @tSql +=' FCXphOVDue61And90,FCXphOVDue90,FCXshLeft'
	SET @tSql +=' )'
	SET @tSql +=' SELECT '''+ @nComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'
	SET @tSql +=' HD.FTSplCode ,Spl_L.FTSplName,ISNULL(SplCrd.FCSplCrLimit,0) AS FCSplCrLimit,'
	SET @tSql +=' CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121) AS FDXphDueDate,HD.FTXphDocNo,FTXphRefInt,'
	SET @tSql +=' CONVERT(VARCHAR(10),HD.FDXphDocDate,121) AS FDXphDocDate,ISNULL(HDSpl.FNXphCrTerm,0) AS FNXphCrTerm,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) > 60 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphBFDue60,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) BETWEEN 31 AND 60 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphBFDue31And60,'
	SET @tSql +=' CASE WHEN (DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) BETWEEN 0 AND 30 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphBFDue0And30,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121))) = 1 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue1,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 2 AND 7 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue2And7,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 8 AND 15 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue8And15,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 16 AND 30 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue16And30,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 31 AND 60 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue31And60,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  BETWEEN 61 AND 90 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue61And90,'
	SET @tSql +=' CASE WHEN ABS(DATEDIFF(Day,CONVERT(VARCHAR(10),GETDATE(),121) ,CONVERT(VARCHAR(10),ISNULL(HDSpl.FDXphDueDate,GETDATE()),121)))  > 90 THEN ISNULL(FCXphGrand,0) ELSE 0 END AS FCXphOVDue90'
	SET @tSql +=' , HD.FCXphLeft '
	SET @tSql +=' FROM TAPTPdHD HD WITH (NOLOCK)'
	SET @tSql +=' INNER JOIN TAPTPdHDSpl HDSpl WITH (NOLOCK) ON HD.FTBchCode = HDSpl.FTBchCode AND HD.FTXphDocNo = HDSpl.FTXphDocNo'
	SET @tSql +=' LEFT JOIN TCNMBranch Bch WITH (NOLOCK) ON HD.FTBchCode = Bch.FTBchCode'
	SET @tSql +=' LEFT JOIN TCNMBranch_L BchL WITH (NOLOCK) ON HD.FTBchCode = BchL.FTBchCode	AND BchL.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	
	SET @tSql +=' LEFT JOIN TCNMSpl Spl  WITH (NOLOCK) ON HD.FTSplCode = Spl.FTSplCode'
	SET @tSql +=' LEFT JOIN TCNMSpl_L Spl_L  WITH (NOLOCK) ON HD.FTSplCode = Spl_L.FTSplCode AND Spl_L.FNLngID = '''  + CAST(@nLngID  AS VARCHAR(10)) + ''''	
	SET @tSql +=' LEFT JOIN TCNMSplCredit SplCrd WITH (NOLOCK) ON HD.FTSplCode = SplCrd.FTSplCode'
	SET @tSql += @tSql1
	EXECUTE(@tSql)

END TRY
BEGIN CATCH 
	SET @FNResult= -1
END CATCH	

