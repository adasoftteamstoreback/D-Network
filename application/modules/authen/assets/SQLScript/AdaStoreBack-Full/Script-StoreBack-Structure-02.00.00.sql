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
	[FTBchNameCst] [varchar](100) NULL
) ON [PRIMARY]
GO
