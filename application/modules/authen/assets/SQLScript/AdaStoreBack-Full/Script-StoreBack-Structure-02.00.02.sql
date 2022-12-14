/* รายงานการใช้จากระบบสินเชื่อ */
IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TRPTSalInstallmentTmp') BEGIN
    DROP TABLE [dbo].[TRPTSalInstallmentTmp]
END
GO
CREATE TABLE [dbo].[TRPTSalInstallmentTmp](
	[FTCstCode] [varchar](20) NULL,
	[FTCstCompName] [varchar](150) NULL,
	[FTCstName] [varchar](150) NULL,
	[FTCstCreditLimit] [int] NULL,
	[FTXshDocNo] [varchar](20) NULL,
	[FTXshDocVatFull] [varchar](20) NULL,
	[FDXshDocDate] [varchar](10) NULL,
	[FTRcvCode] [varchar](5) NULL,
	[FTRcvName] [varchar](100) NULL,
	[FCXrcNet] [numeric](18, 4) NULL,
	[FCXshTotal] [numeric](18, 4) NULL,
	[FCXshDis] [numeric](18, 4) NULL,
	[FCXshGrand] [numeric](18, 4) NULL,
	[FCCstCreditBal] [numeric](18, 4) NULL,
	[FTBchCode] [varchar](5) NULL,
	[FTBchName] [varchar](100) NULL,
	[FTUsrSessID] [varchar](150) NULL,
	[FDCreateOn] [datetime] NULL,
	[FTPdtCode] [varchar](255) NULL,
	[FTPdtName] [varchar](255) NULL,
	[FTPunName] [varchar](50) NULL,
	[FCXsdAmt] [float] NULL,
	[FCXsdDis] [float] NULL,
	[FCXsdNet] [float] NULL,
	[FCXsdQty] [float] NULL,
	[FCXsdSetPrice] [float] NULL,
	[FNRptType] [varchar](10) NULL,
	[FTBchCodeCst] [varchar](5) NULL,
	[FTBchNameCst] [varchar](100) NULL,
	[FCXshCost]	[float] NULL,
	[FCXshCostIncludeVat] [float] NULL,
	[FCXshCostTotal] [float] NULL,		
	[FCXshProfit] [float] NULL,
	[FCXshProfitPercent] [float] NULL
) ON [PRIMARY]
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPdtStaVat') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPdtStaVat VARCHAR(1)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPtyCode') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPtyCode VARCHAR(5)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPtyName') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPtyName VARCHAR(100)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPmoCode') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPmoCode VARCHAR(5)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPmoName') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPmoName VARCHAR(50)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPgpChain') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPgpChain VARCHAR(30)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPgpName') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPgpName VARCHAR(100)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TFNTCouponHD' AND COLUMN_NAME = 'FTCphRefInt') BEGIN
	ALTER TABLE TFNTCouponHD ADD FTCphRefInt VARCHAR(20)
END
GO