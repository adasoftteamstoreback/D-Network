
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
        SET @tSQLMaster += ' AND ( PBAR.FTPdtCode =''' + @tSearch + '''  OR  PBAR.FTBarCode =''' + @tSearch + ''' )' --//หาบาร์โค้ด
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
    ---//--------การค้นหา------///

    ---//--------การมองเห็นสินค้าตามผู้ใช้------///
    IF @tUsrLevel != 'HQ' BEGIN
        --//---------------------- การมองเห็นเฉพาะสินค้าตามระดับผู้ใช้--------------------------//
        SET @tSQLMaster += ' AND ( ('
        SET @tSQLMaster += ' ISNULL(PDLSPC.FTAgnCode,'''') = '''+@tSesAgnCode+''' '

                    IF @tSesMerCode != '' AND @tSesMerCode != '' BEGIN 
                            SET @tSQLMaster += ' AND ISNULL(PDLSPC.FTMerCode,'''') = '''+@tSesMerCode+''' '
                    END

                    IF (SELECT ISNULL(FTBchCode,'') FROM TCNTUsrGroup WHERE FTUsrCode= @tUsrCode)<>'' BEGIN
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
