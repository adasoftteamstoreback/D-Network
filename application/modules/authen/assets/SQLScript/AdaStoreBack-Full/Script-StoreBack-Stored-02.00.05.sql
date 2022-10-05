IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxSalInstallment') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_RPTxSalInstallment
END
GO
CREATE PROCEDURE [dbo].[SP_RPTxSalInstallment] 
	  @ptAgnCode VARCHAR(20),
	  @ptSessionID VARCHAR(100),
	  @ptBchCode VARCHAR(500),
	  @ptCstCodeFrm VARCHAR(500),
	  @ptCstCodeTo VARCHAR(20),
	  @pdDocDateFrm VARCHAR(10),
	  @pdDocDateTo VARCHAR(10),
	  @pnLangID INT,
	  @pnResult INT OUTPUT
AS
BEGIN TRY

    DECLARE @tSQL VARCHAR(MAX)
    SET @tSQL = ''

    DECLARE @tSqlIns VARCHAR(MAX)
    SET @tSqlIns = ''

    DECLARE @tSQLFilter VARCHAR(255)
    SET @tSQLFilter = ''

    IF (@ptBchCode <> '' OR @ptBchCode <> NULL)
        BEGIN
                SET @tSQLFilter += ' AND ISNULL(HD.FTBchCode,'''') IN ('+@ptBchCode+')'
        END

    /*IF ((@ptCstCodeFrm <> '' OR @ptCstCodeFrm <> NULL) AND (@ptCstCodeTo <> '' OR @ptCstCodeTo <> NULL))
        BEGIN
                SET @tSQLFilter += ' AND ISNULL(HD.FTCstCode,'''') BETWEEN ''' + @ptCstCodeFrm + ''' AND ''' + @ptCstCodeTo + ''' '
        END*/

    IF (@ptCstCodeFrm <> '' OR @ptCstCodeFrm <> NULL)
        BEGIN
				SET @tSQLFilter += ' AND ISNULL(HD.FTCstCode,'''') IN ('+@ptCstCodeFrm+')'
        END

    IF ((@pdDocDateFrm <> '' OR @pdDocDateFrm <> NULL) AND (@pdDocDateTo <> '' OR @pdDocDateTo <> NULL))
        BEGIN
                SET @tSQLFilter += ' AND CONVERT(VARCHAR(10),ISNULL(HD.FDXshDocDate,''''),121) BETWEEN  ''' + @pdDocDateFrm + ''' AND ''' + @pdDocDateTo + ''' '
        END

    DELETE FROM TRPTSalInstallmentTmp WITH (ROWLOCK) WHERE FTUsrSessID =  '' + @ptSessionID + ''

    SET @tSQL += ' INSERT INTO TRPTSalInstallmentTmp '
    SET @tSQL += ' SELECT 
										ISNULL(HD.FTCstCode,''N/A'') 	AS FTCstCode, 
										ISNULL(CSTL.FTCstName,''N/A'') AS FTCstCompName, 
										ISNULL(CSTL.FTCstName,''N/A'') AS FTCstName, 
										ISNULL(CSTCRD.FCCstCrLimit,0) 	AS FTCstCreditLimit,
										HD.FTXshDocNo, 
										HD.FTXshDocVatFull, 
										CONVERT(VARCHAR(10), HD.FDXshDocDate, 121) AS FDXshDocDate, 
										RC.FTRcvCode, 
										RC.FTRcvName, 
										''0'' AS FCXrcNet,
										HD.FCXshTotal, 
										HD.FCXshDis, 
										CASE	
												WHEN HD.FNXshDocType = 9 THEN HD.FCXshGrand * -1
												ELSE HD.FCXshGrand
										END AS FCXshGrand,
										CASE	
												WHEN ISNULL(CSTCRD.FCCstCrLimit,0) = 0 THEN 0
												ELSE ISNULL(CSTCRD.FCCstCrLimit,0) - ISNULL(HD.FCXshGrand,0)
										END AS FCCstCreditBal,
										BCHL.FTBchCode AS FTBchCode, 
										BCHL.FTBchName AS FTBchName,'
    SET @tSQL += ' '''+@ptSessionID+''' AS FTUsrSessID, '
    SET @tSQL += '	GETDATE(), '
    SET @tSQL += '	DT.FTPdtCode,
										Pdt_L.FTPdtName,
										Pun_L.FTPunName,

										ISNULL(FCXsdSetPrice, 0) * FCXsdQty AS FCXsdAmt,

										FCXddDTDis AS FCXsdDis,

										FCXsdNetAfHD AS FCXsdNet ,

										CASE
												WHEN HD.FNXshDocType = ''1'' THEN
														ISNULL(FCXsdQty, 0)
												ELSE
														ISNULL(FCXsdQty, 0) *- 1
												END AS FCXsdQty,
										ISNULL(FCXsdSetPrice, 0) AS FCXsdSetPrice ,

										''1'' AS FNRptType ,

										CSTBCH.FTCbrBchCode AS FTBchCodeCst,

										CSTBCH.FTCbrBchName AS FTBchNameCst,

										FCXsdCostEx AS FCXshCost ,

										CASE
												WHEN FCXsdCostEx = ''0'' THEN
														0
												ELSE
														FCXsdCostEx + (FCXsdCostEx * (DT.FCXsdVatRate / 100))
												END AS FCXshCostIncludeVat,

										CASE
												WHEN FCXsdCostEx = ''0'' THEN
														0
												ELSE
														FCXsdCostEx + (FCXsdCostEx * (DT.FCXsdVatRate / 100)) * ISNULL(FCXsdQty, 1)
												END AS FCXshCostTotal,

										FCXsdNetAfHD - (FCXsdCostEx + (FCXsdCostEx * (DT.FCXsdVatRate / 100)) * ISNULL(FCXsdQty, 1)) AS FCXshProfit,

										CASE
												WHEN FCXsdCostEx = ''0'' THEN
														100
												ELSE
														(FCXsdNetAfHD - (FCXsdCostEx + (FCXsdCostEx * (DT.FCXsdVatRate / 100)) * ISNULL(FCXsdQty, 1)))*100 / (FCXsdCostEx + (FCXsdCostEx * (DT.FCXsdVatRate / 100)))
												END AS FCXshProfitPercent '

    SET @tSQL += ' FROM TPSTSalHD HD
									 LEFT JOIN TCNMCstCredit  CSTCRD 	ON HD.FTCstCode = CSTCRD.FTCstCode 
									 LEFT JOIN TPSTSalHDCst 	HDCst 	ON HD.FTXshDocNo = HDCst.FTXshDocNo 	AND HD.FTBchCode = HDCst.FTBchCode
									 LEFT JOIN (
											SELECT A.* FROM ( 
												SELECT 
														FTCstCode ,
														FTCbrBchCode ,
														FTCbrBchName ,
														ROW_NUMBER () OVER (PARTITION BY FTCstCode , FTCbrBchCode ORDER BY FTCbrBchCode DESC) AS PARTTITIONBY_BCH
												FROM TCNMCstBch 
											) AS A WHERE A.PARTTITIONBY_BCH = 1
									 ) CSTBCH ON HD.FTCstCode = CSTBCH.FTCstCode AND HDCst.FTXshCstRef = CSTBCH.FTCbrBchCode
									 LEFT JOIN (
													SELECT A.* FROM(
															SELECT
																	ROW_NUMBER () OVER (PARTITION BY FTXshDocNo ORDER BY FTXshDocNo ASC) AS PARTTITIONBYRCV,
																	RC.FTRcvCode,
																	RC.FTBchCode,
																	RC.FTXshDocNo,
																	RC.FTRcvName
															FROM
																	TPSTSalRC RC
																	INNER JOIN TFNMRcv 					 RCVF ON RC.FTRcvCode = RCVF.FTRcvCode AND RCVF.FTFmtCode = ''026'' 
													) A WHERE A.PARTTITIONBYRCV = 1 ) AS RC   ON HD.FTBchCode = RC.FTBchCode AND HD.FTXshDocNo = RC.FTXshDocNo '
    SET @tSQL += ' INNER JOIN TFNMRcv RCVF ON RC.FTRcvCode = RCVF.FTRcvCode AND RCVF.FTFmtCode = ''026'' '
    SET @tSQL += ' LEFT JOIN TCNMBranch_L   BCHL 		ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID =  ' + CAST(@pnLangID AS varchar(1))
    SET @tSQL += ' LEFT JOIN TCNMCst_L 			CSTL 		ON HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID =  ' + CAST(@pnLangID AS varchar(1))
    SET @tSQL += ' INNER JOIN TPSTSalDT 		DT 			ON HD.FTBchCode = DT.FTBchCode AND HD.FTXshDocNo = DT.FTXshDocNo 
									 LEFT JOIN TCNMPdt_L 			Pdt_L 	ON DT.FTPdtCode = Pdt_L.FTPdtCode AND Pdt_L.FNLngID = ' + CAST(@pnLangID AS varchar(1)) 
    SET @tSQL += ' LEFT JOIN TCNMPdtUnit_L 	Pun_L 	ON DT.FTPunCode = Pun_L.FTPunCode AND Pun_L.FNLngID = ' + CAST(@pnLangID AS varchar(1)) 
    SET @tSQL += ' LEFT JOIN (
														 SELECT
																FTBchCode,
																FTXshDocNo,
																FNXsdSeqNo,
																SUM (FCXddValue) AS FCXddDTDis
														FROM
																TPSTSalDTDis
														WHERE FTXddDisChgType != 8
														GROUP BY
																FTBchCode,
																FTXshDocNo,
																FNXsdSeqNo
														) DTDis ON DT.FTBchCode = DTDis.FTBchCode AND DT.FTXshDocNo = DTDis.FTXshDocNo AND DT.FNXsdSeqNo = DTDis.FNXsdSeqNo '
   SET @tSQL += ' LEFT JOIN (
                             SELECT
																HD.FTBchCode,
																HD.FTXshDocNo,
																ISNULL(HD.FTXshRefInt, ''N/A'') AS FTXshRefInt
														FROM TPSTSalHD HD WITH (NOLOCK)
														WHERE HD.FNXshDocType = 9 AND HD.FTXshStaDoc = 1 '
    SET @tSQL += @tSQLFilter
    SET @tSQL += ') REF ON HD.FTBchCode = REF.FTBchCode AND HD.FTXshDocNo = REF.FTXshRefInt '
    SET @tSQL += ' WHERE HD.FTXshStaDoc = 1 AND HD.FNXshDocType <> 9 AND ISNULL(REF.FTXshRefInt,'''') = '''' '
    SET @tSQL += @tSQLFilter
    EXEC(@tSQL)
    return 0
END TRY

BEGIN CATCH
    return -1
END CATCH
GO

--//////////////////////////////////////////////////////////////////////////////

IF EXISTS (SELECT name FROM dbo.sysobjects WHERE id = object_id(N'SP_CNoBrowseProduct') and OBJECTPROPERTY(id, N'IsProcedure') = 1) BEGIN
	DROP PROCEDURE SP_CNoBrowseProduct
END
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
    DECLARE	@tWhere VARCHAR(8000)
    DECLARE	@tNotInPdtType VARCHAR(8000)
    DECLARE	@tPdtCodeIgnorParam VARCHAR(30)
    DECLARE	@tPDTMoveon VARCHAR(1)
    DECLARE	@tPlcCodeConParam VARCHAR(10)
    DECLARE	@tDISTYPE VARCHAR(1)
    DECLARE	@tPagename VARCHAR(10)
    DECLARE	@tNotinItemString VARCHAR(8000)
    DECLARE	@tSqlCode VARCHAR(10)
    DECLARE	@tPriceType VARCHAR(10)
    DECLARE	@tPplCode VARCHAR(10)
    DECLARE @nLngID INT
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
				SET @tSQLMaster += ''
        --SET @tSQLMaster += ' LEFT JOIN TCNMPdtLot PDTLOT WITH (NOLOCK) ON Products.FTPdtCode = PDTLOT.FTPdtCode '
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
				SET @tSQLMaster += ''
        --SET @tSQLMaster += ' AND (Products.FTPdtStaLot = ''2'' OR Products.FTPdtStaLot = ''1'' AND Products.FTPdtStaLot = ''1'' AND ISNULL(PDTLOT.FTLotNo,'''') <> '''' ) '
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
        --SET @tSQL += '  WHEN ISNULL(PEMPTY.FCPgdPriceRet,0) <> 0 THEN PEMPTY.FCPgdPriceRet'
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
        SET @tSQL += 'LEFT JOIN ( '
			SET @tSQL += 'SELECT '
			SET @tSQL += '	BP.FNRowPart,BP.FTPdtCode,BP.FTPunCode,BP.FDPghDStart,BP.FCPgdPriceNet,BP.FCPgdPriceWhs, '
			SET @tSQL += '	CASE '
			SET @tSQL += '		WHEN ADJ.FTPghStaAdj = ''2'' AND ADJ.FTPdtCode IS NOT NULL THEN CONVERT(NUMERIC(18,4), (BP.FCPgdPriceRet - (BP.FCPgdPriceRet * (ADJ.FCPgdPriceRet*0.01)))) '
			SET @tSQL += '		WHEN ADJ.FTPghStaAdj = ''3'' AND ADJ.FTPdtCode IS NOT NULL THEN CONVERT(NUMERIC(18,4), BP.FCPgdPriceRet - ADJ.FCPgdPriceRet) '
			SET @tSQL += '		WHEN ADJ.FTPghStaAdj = ''4'' AND ADJ.FTPdtCode IS NOT NULL THEN CONVERT(NUMERIC(18,4), ((BP.FCPgdPriceRet * (ADJ.FCPgdPriceRet*0.01)) + BP.FCPgdPriceRet)) '
			SET @tSQL += '		WHEN ADJ.FTPghStaAdj = ''5'' AND ADJ.FTPdtCode IS NOT NULL THEN CONVERT(NUMERIC(18,4), BP.FCPgdPriceRet + ADJ.FCPgdPriceRet) '
			SET @tSQL += '		ELSE BP.FCPgdPriceRet '
			SET @tSQL += '	END AS FCPgdPriceRet '
			SET @tSQL += 'FROM ( '
			SET @tSQL += '	SELECT '
			SET @tSQL += '		ROW_NUMBER() OVER (PARTITION BY FTPdtCode,FTPunCode ORDER BY FTPplCode DESC, FDPghDStart DESC) AS FNRowPart, '
			SET @tSQL += '		CONVERT(VARCHAR(16), FDPghDStart, 121) AS FDPghDStart, '
			SET @tSQL += '		FTPdtCode,FTPunCode,0 AS FCPgdPriceNet,FCPgdPriceRet,0 AS FCPgdPriceWhs,FTPplCode '
			SET @tSQL += '   FROM TCNTPdtPrice4PDT WITH(NOLOCK) '
			SET @tSQL += '   WHERE FDPghDStart <= CONVERT(VARCHAR(10), GETDATE(), 121) AND FTPghStaAdj = ''1'' '
			IF @tPplCode = '' 
			BEGIN
				SET @tSQL += '   AND ISNULL(FTPplCode,'''') = '''' '
			END 
			ELSE
			BEGIN
				SET @tSQL += '   AND (FTPplCode = '''+@tPplCode+''' OR ISNULL(FTPplCode,'''') = '''')  '
			END
			SET @tSQL += ') BP '
			SET @tSQL += 'LEFT JOIN ( '
			SET @tSQL += '	SELECT '
			SET @tSQL += '		ROW_NUMBER() OVER (PARTITION BY FTPdtCode,FTPunCode ORDER BY FTPplCode DESC, FDPghDStart DESC) AS FNRowPart, '
			SET @tSQL += '		CONVERT(VARCHAR(16), FDPghDStart, 121) AS FDPghDStart, '
			SET @tSQL += '		FTPdtCode,FTPunCode,0 AS FCPgdPriceNet,FCPgdPriceRet,0 AS FCPgdPriceWhs,FTPghStaAdj,FTPplCode '
			SET @tSQL += '   FROM TCNTPdtPrice4PDT WITH(NOLOCK) '
			SET @tSQL += '   WHERE FDPghDStart <= CONVERT(VARCHAR(10), GETDATE(), 121) AND FTPghStaAdj <> ''1'' '
			IF @tPplCode = '' 
			BEGIN
				SET @tSQL += '   AND ISNULL(FTPplCode,'''') = '''' '
			END 
			ELSE
			BEGIN
				SET @tSQL += '   AND (FTPplCode = '''+@tPplCode+''' OR ISNULL(FTPplCode,'''') = '''') '
			END
			SET @tSQL += ') ADJ ON BP.FTPdtCode = ADJ.FTPdtCode AND BP.FTPunCode = ADJ.FTPunCode '
			SET @tSQL += 'WHERE BP.FNRowPart = 1 '
			SET @tSQL += 'AND (ADJ.FTPdtCode IS NULL OR ADJ.FNRowPart = 1) '
		SET @tSQL += ' ) PCUS ON PDT.FTPdtCode = PCUS.FTPdtCode AND PDT.FTPunCode = PCUS.FTPunCode ' 
		
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
        SET @tSQL += ' AND ISNULL(FTPplCode,'''') = '''' OR FTPplCode = (SELECT FTPplCode FROM TCNMBranch WHERE FTPplCode != '''' AND FTBchCode = (SELECT TOP 1 FTBchCode FROM TCNMBranch WHERE FTAgnCode = '''+@tSesAgnCode+''' ))'
        SET @tSQL += ') AS PCUS '
        SET @tSQL += ' WHERE PCUS.FNRowPart = 1 '
        SET @tSQL += ' ) PBCH ON PDT.FTPdtCode = PBCH.FTPdtCode AND PDT.FTPunCode = PBCH.FTPunCode '
    END

    IF @tPriceType = 'Cost' BEGIN
        SET @tSQL += '  LEFT JOIN VCN_ProductCost VPC WITH(NOLOCK) ON VPC.FTPdtCode = PDT.FTPdtCode'
    END
    EXECUTE(@tSQL)
END
GO
