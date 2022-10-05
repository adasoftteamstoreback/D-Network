
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
