--################################################
--เพิ่มฟิวส์ ที่ตาราง TSVMCar

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TSVMCar' AND COLUMN_NAME = 'FTCbrBchCode') BEGIN
	ALTER TABLE TSVMCar ADD FTCbrBchCode VARCHAR(20)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TSVTBookDTSet' AND COLUMN_NAME = 'FTXsdStaPrcStk') BEGIN
	ALTER TABLE TSVTBookDTSet ADD FTXsdStaPrcStk VARCHAR(1)
END
GO
----------------------------------------------- Script Upgrade StoreBackOffice 00.00.02 2021-11-24 -----------------------------------------------

-------------- Update Table  --------------
IF OBJECT_ID(N'TCNSRptSpc') IS NULL BEGIN
	CREATE TABLE [dbo].[TCNSRptSpc](
		[FTAgnCode] [varchar](10) NULL,
		[FTBchCode] [varchar](5) NULL,
		[FTMerCode] [varchar](10) NULL,
		[FTShpCode] [varchar](5) NULL,
		[FNRptGrpSeq] [int] NOT NULL,
		[FTRptGrpCode] [varchar](20) NOT NULL,
		[FNRptSeq] [int] NOT NULL,
		[FTRptCode] [varchar](30) NOT NULL,
		[FTRptRoute] [varchar](255) NULL,
		[FTRptFilterCol] [varchar](255) NULL,
		[FTRptStaActive] [varchar](1) NULL,
		[FDLastUpdOn] [datetime] NULL,
		[FTLastUpdBy] [varchar](20) NULL,
		[FDCreateOn] [datetime] NULL,
		[FTCreateBy] [varchar](20) NULL,
		[FTRolCode] [varchar](5) NULL,
	 CONSTRAINT [PK_TCNSRptSpc] PRIMARY KEY CLUSTERED 
	(
		[FNRptGrpSeq] ASC,
		[FTRptGrpCode] ASC,
		[FNRptSeq] ASC,
		[FTRptCode] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]
END
GO

IF OBJECT_ID(N'TARTSqHDDocRef') IS NULL BEGIN

    CREATE TABLE [dbo].[TARTSqHDDocRef](
	    [FTAgnCode] [varchar](10) NOT NULL,
	    [FTBchCode] [varchar](20) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FTXshRefDocNo] [varchar](20) NOT NULL,
	    [FTXshRefType] [varchar](1) NOT NULL,
	    [FTXshRefKey] [varchar](10) NULL,
	    [FDXshRefDocDate] [datetime] NULL,
    PRIMARY KEY CLUSTERED 
    (
	    [FTAgnCode] ASC,
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC,
	    [FTXshRefDocNo] ASC,
	    [FTXshRefType] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]

    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสบริษัทย่อย ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSqHDDocRef', @level2type=N'COLUMN',@level2name=N'FTAgnCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสสาขา ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSqHDDocRef', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSqHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสารอ้างอิง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSqHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshRefDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ประเภทการอ้างอิงเอกสาร 1: อ้างอิงถึง(ภายใน),2:ถูกอ้างอิง(ภายใน),3: อ้างอิง ภายนอก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSqHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshRefType'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'กรณีType เดียวกันมีรายการมากกว่า 1 กลุ่ม /กำหนด Key เองได้' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSqHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshRefKey'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่เอกสารอ้างอิง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSqHDDocRef', @level2type=N'COLUMN',@level2name=N'FDXshRefDocDate'

END
GO

IF OBJECT_ID(N'TARTSpHD') IS NULL BEGIN
    CREATE TABLE [dbo].[TARTSpHD](
	    [FTAgnCode] [varchar](10) NOT NULL,
	    [FTBchCode] [varchar](5) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FDXshDocDate] [datetime] NULL,
	    [FTXshDocTime] [varchar](8) NULL,
	    [FDXshDueDate] [datetime] NULL,
	    [FTDptCode] [varchar](5) NULL,
	    [FTUsrCode] [varchar](20) NULL,
	    [FTSplCode] [varchar](20) NULL,
	    [FTCstCode] [varchar](20) NULL,
	    [FTPrdCode] [varchar](5) NULL,
	    [FTXshApvCode] [varchar](20) NULL,
	    [FTXshCtrName] [varchar](50) NULL,
	    [FNXshDocPrint] [bigint] NULL,
	    [FCXshTotal] [numeric](18, 4) NULL,
	    [FCXshWht] [numeric](18, 4) NULL,
	    [FCXshAfWht] [numeric](18, 4) NULL,
	    [FCXshInterest] [numeric](18, 4) NULL,
	    [FCXshDisc] [numeric](18, 4) NULL,
	    [FCXshAfDisc] [numeric](18, 4) NULL,
	    [FCXshAmt] [numeric](18, 4) NULL,
	    [FCXshPay] [numeric](18, 4) NULL,
	    [FCXshChgCredit] [numeric](18, 4) NULL,
	    [FCXshGnd] [numeric](18, 4) NULL,
	    [FTXshGndText] [varchar](200) NULL,
	    [FCXshMnyCsh] [numeric](18, 4) NULL,
	    [FCXshMnyChq] [numeric](18, 4) NULL,
	    [FCXshMnyCrd] [numeric](18, 4) NULL,
	    [FCXshMnyCtf] [numeric](18, 4) NULL,
	    [FCXshMnyCpn] [numeric](18, 4) NULL,
	    [FCXshMnyCls] [numeric](18, 4) NULL,
	    [FCXshMnyCxx] [numeric](18, 4) NULL,
	    [FTXshStaPaid] [varchar](1) NULL,
	    [FTXshStaDoc] [varchar](1) NULL,
	    [FTXshStaPrcDoc] [varchar](1) NULL,
	    [FTXshRmk] [varchar](200) NULL,
	    [FTXshCond] [varchar](100) NULL,
	    [FNXshStaDocAct] [int] NULL,
	    [FNXshStaRef] [int] NULL,
	    [FDLastUpdOn] [datetime] NULL,
	    [FTLastUpdBy] [varchar](20) NULL,
	    [FDCreateOn] [datetime] NULL,
	    [FTCreateBy] [varchar](20) NULL,
     CONSTRAINT [PK_TARTSpHD] PRIMARY KEY CLUSTERED 
    (
	    [FTAgnCode] ASC,
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]

    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสบริษัทย่อย ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTAgnCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสสาขา' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร XXYY-######' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)วันที่เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FDXshDocDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เวลาที่เกิดเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshDocTime'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันนัดรับ/จ่ายเงิน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FDXshDueDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสแผนก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTDptCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสผู้บันทึก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTUsrCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสผู้จำหน่าย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTSplCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสลูกค้า' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTCstCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสงวดบัญชี' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTPrdCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสผู้อนุมัติ (TSysUser)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshApvCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อผู้รับ/จ่ายเงิน (ชื่อควรเลือกจาก TCNTCTractor แต่พิมพ์ได้)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshCtrName'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จำนวนครั้งในการพิมพ์เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FNXshDocPrint'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวม ก่อนชำระ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshTotal'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอด หักภาษี ณ ที่จ่าย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshWht'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมหลัง หักภาษี ณ ที่จ่าย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshAfWht'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอด ดอกเบี้ย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshInterest'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอด ส่วนลด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshDisc'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมหลัง หัก ส่วนลด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshAfDisc'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวม ทั้งสิ้น (ชำระ - หักภาษี + ดอกเบี้ย - ส่วนลด)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshAmt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวม ชำระ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshPay'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมชาร์จบัตรเครดิต' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshChgCredit'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวม จ่ายชำระจริง (ยอดรวม ทั้งสิ้น+ชาร์จบัตร)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshGnd'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จำนวนเงินเป็นตัวอักษร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshGndText'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมรับ/จ่าย เงินสด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshMnyCsh'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมรับ/จ่าย เช็ค' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshMnyChq'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมรับ/จ่าย บัตรเครดิต' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshMnyCrd'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมรับ/จ่าย โอนเงิน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshMnyCtf'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมรับ/จ่าย คูปอง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshMnyCpn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมรับ/จ่าย ยกหนี้' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshMnyCls'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมรับ/จ่าย อื่นๆ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FCXshMnyCxx'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ รับ/จ่ายเงิน 1:ยังไม่จ่าย 2:บางส่วน, 3:ครบ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshStaPaid'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshStaDoc'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ prc เอกสาร  ว่าง:ยังไม่ทำ, 1:ทำแล้ว' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshStaPrcDoc'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'หมายเหตุ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshRmk'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เงื่อนไขการวางบิล' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTXshCond'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ เคลื่อนไหว 0:NonActive, 1:Active' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FNXshStaDocAct'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ อ้างอิง 0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FNXshStaRef'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FDLastUpdOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTLastUpdBy'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FDCreateOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHD', @level2type=N'COLUMN',@level2name=N'FTCreateBy'

END
GO

IF OBJECT_ID(N'TARTSpHDCst') IS NULL BEGIN
    CREATE TABLE [dbo].[TARTSpHDCst](
	    [FTAgnCode] [varchar](10) NOT NULL,
	    [FTBchCode] [varchar](5) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FTXshCardID] [varchar](20) NULL,
	    [FTXshCardNo] [varchar](20) NULL,
	    [FNXshCrTerm] [int] NULL,
	    [FDXshDueDate] [datetime] NULL,
	    [FDXshBillDue] [datetime] NULL,
	    [FTXshCtrName] [varchar](100) NULL,
	    [FDXshTnfDate] [datetime] NULL,
	    [FTXshRefTnfID] [varchar](20) NULL,
	    [FNXshAddrShip] [bigint] NULL,
	    [FTXshAddrTax] [varchar](20) NULL,
     CONSTRAINT [PK_TARTSpHDCst] PRIMARY KEY CLUSTERED 
    (
	    [FTAgnCode] ASC,
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]

    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสบริษัทย่อย ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTAgnCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สาขาสร้าง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่บัตรประจำตัวประชาชน/Passport' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCardID'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขบัตรสมาชิก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCardNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ระยะเครดิต' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FNXshCrTerm'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ครบกำหนด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FDXshDueDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่จะรับ/วางบิล' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FDXshBillDue'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อผู้ตืดต่อ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCtrName'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ส่งของ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FDXshTnfDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่ ใบขนส่ง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTXshRefTnfID'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ที่อยู่ส่งของ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FNXshAddrShip'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ที่อยู่ออกใบกำกับ (เก็บเลขประจำผู้เสียภาษี FTAddTaxNo)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDCst', @level2type=N'COLUMN',@level2name=N'FTXshAddrTax'

END
GO

IF OBJECT_ID(N'TARTSpHDDocRef') IS NULL BEGIN
    CREATE TABLE [dbo].[TARTSpHDDocRef](
	    [FTAgnCode] [varchar](10) NOT NULL,
	    [FTBchCode] [varchar](5) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FTXshRefDocNo] [varchar](20) NOT NULL,
	    [FTXshRefType] [varchar](1) NOT NULL,
	    [FTXshRefKey] [varchar](10) NULL,
	    [FDXshRefDocDate] [datetime] NULL,
     CONSTRAINT [PK_TARTSpHDDocRef] PRIMARY KEY CLUSTERED 
    (
	    [FTAgnCode] ASC,
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC,
	    [FTXshRefDocNo] ASC,
	    [FTXshRefType] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]

    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสบริษัทย่อย ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDDocRef', @level2type=N'COLUMN',@level2name=N'FTAgnCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสสาขา ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDDocRef', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสารอ้างอิง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshRefDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ประเภทการอ้างอิงเอกสาร 1: อ้างอิงถึง(ภายใน),2:ถูกอ้างอิง(ภายใน),3: อ้างอิง ภายนอก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshRefType'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'กรณีType เดียวกันมีรายการมากกว่า 1 กลุ่ม /กำหนด Key เองได้' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXshRefKey'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่เอกสารอ้างอิง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpHDDocRef', @level2type=N'COLUMN',@level2name=N'FDXshRefDocDate'

END
GO

IF OBJECT_ID(N'TARTSpDT') IS NULL BEGIN
    CREATE TABLE [dbo].[TARTSpDT](
	    [FTAgnCode] [varchar](10) NOT NULL,
	    [FTBchCode] [varchar](5) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FNXsdSeqNo] [bigint] NOT NULL,
	    [FTXsdInvNo] [varchar](20) NOT NULL,
	    [FNXsdInvType] [bigint] NULL,
	    [FTXsdRefExt] [varchar](20) NULL,
	    [FDXsdInvDate] [datetime] NULL,
	    [FDXsdDueDate] [datetime] NULL,
	    [FCXsdInvGrand] [numeric](18, 4) NULL,
	    [FCXsdInvPaid] [numeric](18, 4) NULL,
	    [FCXsdInvRem] [numeric](18, 4) NULL,
	    [FCXsdInvPay] [numeric](18, 4) NULL,
	    [FTXsdCtrCode] [varchar](20) NULL,
	    [FTXsdStaInvB4] [varchar](1) NULL,
	    [FTXsdStaInvNo] [varchar](1) NULL,
	    [FNXsdLevel] [bigint] NULL,
	    [FTXsdRmk] [varchar](200) NULL,
	    [FDLastUpdOn] [datetime] NULL,
	    [FTLastUpdBy] [varchar](20) NULL,
	    [FDCreateOn] [datetime] NULL,
	    [FTCreateBy] [varchar](20) NULL,
     CONSTRAINT [PK_TARTSpDT] PRIMARY KEY CLUSTERED 
    (
	    [FTAgnCode] ASC,
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC,
	    [FNXsdSeqNo] ASC,
	    [FTXsdInvNo] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]

    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสบริษัทย่อย ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTAgnCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสสาขา' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร XXYY-######' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ลำดับรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FNXsdSeqNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่ใบรับ/วางบิล' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTXsdInvNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ประเภทเอกสาร 1:ใบขาย 2:ใบมัดจำ 3:ใบลดหนี้ 4:ใบเพิ่มหนี้' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FNXsdInvType'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)เลขที่ใบบิลอ้างอิง (D-Bill)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTXsdRefExt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ใบรับ/วางบิล (D-Bill)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FDXsdInvDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)วันที่ครบกำหนด (D-Bill)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FDXsdDueDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดเอกสาร (D-Inv-field)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FCXsdInvGrand'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดที่เคยชำระ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FCXsdInvPaid'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดคงเหลือ (ค้างชำระครั้งต่อไป)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FCXsdInvRem'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดจ่าย/รับชำระครั้งปัจจุบัน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FCXsdInvPay'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสลูกค้า/ผู้จำหน่าย (D-Bill)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTXsdCtrCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ รับ/วางบิล 1:ไม่เคย,2:เคย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTXsdStaInvB4'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ บิล 1:ใบบิล,2:ลดหนี้,3:เพิ่มหนี้,4:อื่นๆ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTXsdStaInvNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ระดับความลึก (Outline)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FNXsdLevel'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'หมายเหตุ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTXsdRmk'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FDLastUpdOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTLastUpdBy'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FDCreateOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpDT', @level2type=N'COLUMN',@level2name=N'FTCreateBy'

END
GO

IF OBJECT_ID(N'TCNTUsrNoti') IS NULL BEGIN
	CREATE TABLE [dbo].[TCNTUsrNoti](
		[FTUsrCode] [varchar](20) NOT NULL,
		[FNNotID] [bigint] NOT NULL,
		[FTNotCode] [varchar](5) NULL,
		[FTNotTypeName] [varchar](100) NULL,
		[FNNotUrlType] [bigint] NULL,
		[FTNotUrlRef] [varchar](255) NULL,
		[FTAgnCode] [varchar](10) NULL,
		[FTNotBchRef] [varchar](5) NULL,
		[FTNotDocRef] [varchar](50) NULL,
		[FDNotDate] [datetime] NULL,
		[FTNotDesc1] [varchar](255) NULL,
		[FTNotDesc2] [varchar](255) NULL,
		[FTStaRead] [varchar](1) NULL,
	 CONSTRAINT [PK_TCNTUsrNoti] PRIMARY KEY CLUSTERED 
	(
		[FTUsrCode] ASC,
		[FNNotID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]
END
GO

IF OBJECT_ID(N'TCNTUsrNotiAct') IS NULL BEGIN
	CREATE TABLE [dbo].[TCNTUsrNotiAct](
		[FTUsrCode] [varchar](20) NOT NULL,
		[FNNotID] [bigint] NOT NULL,
		[FDNoaDateIns] [datetime] NOT NULL,
		[FTNoaDesc] [varchar](255) NULL,
	 CONSTRAINT [PK_TCNTUsrNotiAct] PRIMARY KEY CLUSTERED 
	(
		[FTUsrCode] ASC,
		[FNNotID] ASC,
		[FDNoaDateIns] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTUsrNoti' AND COLUMN_NAME = 'FTNotUrlRef2') BEGIN
	ALTER TABLE TCNTUsrNoti ADD FTNotUrlRef2 VARCHAR(255)
END
GO

IF OBJECT_ID(N'TARTSpRC') IS NULL BEGIN

    CREATE TABLE [dbo].[TARTSpRC](
	    [FTAgnCode] [varchar](10) NOT NULL,
	    [FTBchCode] [varchar](5) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FNXrcSeqNo] [bigint] NOT NULL,
	    [FTXphDocType] [varchar](1) NULL,
	    [FDXphDocDate] [datetime] NULL,
	    [FTRcvCode] [varchar](5) NULL,
	    [FTRcvName] [varchar](50) NULL,
	    [FTXrcRefNo1] [varchar](100) NULL,
	    [FTXrcRefNo2] [varchar](100) NULL,
	    [FTBnkCode] [varchar](5) NULL,
	    [FTBnkName] [varchar](100) NULL,
	    [FTXrcBnkBch] [varchar](100) NULL,
	    [FDXrcRefDate] [datetime] NULL,
	    [FCXrcChgCreditPer] [float] NULL,
	    [FCXrcChgCreditAmt] [float] NULL,
	    [FCXrcFAmt] [float] NULL,
	    [FCXrcAmt] [float] NULL,
	    [FCXrcNet] [float] NULL,
	    [FCXrcChg] [float] NULL,
	    [FTXrcStaPrc] [varchar](1) NULL,
	    [FTXrcRmk] [varchar](250) NULL,
	    [FTRteCode] [varchar](5) NULL,
	    [FCXrcRteAmt] [float] NULL,
	    [FCXrcRteFac] [float] NULL,
	    [FTXrcStaChg] [varchar](1) NULL,
	    [FDLastUpdOn] [datetime] NULL,
	    [FTLastUpdBy] [varchar](20) NULL,
	    [FDCreateOn] [datetime] NULL,
	    [FTCreateBy] [varchar](20) NULL,
     CONSTRAINT [PK_TARTSpRC] PRIMARY KEY CLUSTERED 
    (
	    [FTAgnCode] ASC,
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC,
	    [FNXrcSeqNo] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]

    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสบริษัทย่อย ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTAgnCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร  XXYY-######' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXphDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ลำดับการชำระเงินต่อ 1 เลขที่เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FNXrcSeqNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)ประเภทเอกสาร 1:ขาย(S), 9:คืน(R)  (D-H)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXphDocType'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)วันที่เอกสาร (D-H)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FDXphDocDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสประเภทการชำระเงิน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTRcvCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อประเภทชำระเงิน ณ บันทึก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTRcvName'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่อ้างอิง1' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXrcRefNo1'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่อ้างอิง2' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXrcRefNo2'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสธนาคาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTBnkCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อธนาคาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTBnkName'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อสาขา' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXrcBnkBch'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ลงวันที่ เช็ค' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FDXrcRefDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชาร์จบัตรเครดิต (%)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcChgCreditPer'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชาร์จบัตรเครดิต (มูลค่า)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcChgCreditAmt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จากจำนวนเงิน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcFAmt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จำนวนเงินที่ชำระ (เงินที่รับ:อาจจะรวมเงินทอน)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcAmt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จำนวนเงินที่ได้จริง (ไม่รวมเงินทอน)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcNet'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จำนวนเงินทอน (ถ้ามีทอนจะพบ ในตารางนี้ที่ลำดับสุดท้ายของบิลนั้นๆ)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcChg'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ ประมวลผล ว่าง:ยังไม่ทำ, 1:ทำแล้ว' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXrcStaPrc'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'หมายเหตุ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXrcRmk'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)รหัสสกุลเงิน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTRteCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จำนวนเงินที่ชำระ (สกุลต่างประเทศ)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcRteAmt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อัตราแลกเปลี่ยนขณะชำระ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FCXrcRteFac'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'(INDEX)สถานะ เงินทอน 1:เงินทอน, 2:ตั้งยอดเครดิตสะสม' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTXrcStaChg'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FDLastUpdOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTLastUpdBy'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FDCreateOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTSpRC', @level2type=N'COLUMN',@level2name=N'FTCreateBy'

END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNMSlipMsgHD_L' AND COLUMN_NAME = 'FTMshCode') BEGIN
	ALTER TABLE TCNMSlipMsgHD_L ADD FTMshCode VARCHAR(5)
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TARTSpRC' AND COLUMN_NAME = 'FTXphDocNo') BEGIN
	EXEC sp_rename 'TARTSpRC.FTXphDocNo', 'FTXshDocNo', 'COLUMN'
END
GO

IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TARTSpRC' AND COLUMN_NAME = 'FTBchCode') BEGIN
    ALTER TABLE TARTSpRC ADD FTBchCode varchar(5) NOT NULL

    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TARTSpRC' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TARTSpRC DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql
    ALTER TABLE TARTSpRC ADD PRIMARY KEY(FTAgnCode,FTBchCode,FTXshDocNo,FNXrcSeqNo)
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TARTSpHD' AND COLUMN_NAME = 'FTBchCode') BEGIN

    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TARTSpHD' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TARTSpHD DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql

	ALTER TABLE TARTSpHD ALTER COLUMN FTBchCode VARCHAR (5) NOT NULL
    ALTER TABLE TARTSpHD ADD PRIMARY KEY(FTAgnCode,FTBchCode,FTXshDocNo)
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TARTSpHDCst' AND COLUMN_NAME = 'FTBchCode') BEGIN

    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TARTSpHDCst' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TARTSpHDCst DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql

	ALTER TABLE TARTSpHDCst ALTER COLUMN FTBchCode VARCHAR (5) NOT NULL
    ALTER TABLE TARTSpHDCst ADD PRIMARY KEY(FTAgnCode,FTBchCode,FTXshDocNo)
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TARTSpHDDocRef' AND COLUMN_NAME = 'FTBchCode') BEGIN

    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TARTSpHDDocRef' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TARTSpHDDocRef DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql

	ALTER TABLE TARTSpHDDocRef ALTER COLUMN FTBchCode VARCHAR (5) NOT NULL
    ALTER TABLE TARTSpHDDocRef ADD PRIMARY KEY(FTAgnCode,FTBchCode,FTXshDocNo,FTXshRefDocNo,FTXshRefType)
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TARTSpDT' AND COLUMN_NAME = 'FTBchCode') BEGIN

    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TARTSpDT' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TARTSpDT DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql

	ALTER TABLE TARTSpDT ALTER COLUMN FTBchCode VARCHAR (5) NOT NULL
    ALTER TABLE TARTSpDT ADD PRIMARY KEY(FTAgnCode,FTBchCode,FTXshDocNo,FNXsdSeqNo,FTXsdInvNo)
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TARTSpRC' AND COLUMN_NAME = 'FTBchCode') BEGIN

    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TARTSpRC' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TARTSpRC DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql

	ALTER TABLE TARTSpRC ALTER COLUMN FTBchCode VARCHAR (5) NOT NULL
    ALTER TABLE TARTSpRC ADD PRIMARY KEY(FTAgnCode,FTBchCode,FTXshDocNo,FNXrcSeqNo)
END
GO

IF OBJECT_ID(N'TARTRcvDepositDT') IS NULL BEGIN
    CREATE TABLE [dbo].[TARTRcvDepositDT](
    	[FTBchCode] [varchar](20) NOT NULL,
    	[FTXshDocNo] [varchar](20) NOT NULL,
    	[FNXsdSeqNo] [bigint] NOT NULL,
    	[FTXsdName] [varchar](100) NULL,
    	[FCXsdTotal] [numeric](18, 4) NULL,
    	[FTXsdVatType] [varchar](1) NULL,
    	[FTVatCode] [varchar](5) NULL,
    	[FTVatRate] [float] NULL,
    	[FCXsdVat] [numeric](18, 4) NULL,
    	[FCXsdVatable] [numeric](18, 4) NULL,
    	[FCXsdDeposit] [numeric](18, 4) NULL,
    	[FTXsdRmk] [varchar](100) NULL,
    	[FTXsdSoRef] [varchar](1) NULL,
     CONSTRAINT [PK_TARTRcvDepositDT] PRIMARY KEY CLUSTERED 
    (
    	[FTBchCode] ASC,
    	[FTXshDocNo] ASC,
    	[FNXsdSeqNo] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสสาขา' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร  Def : XYYPOS-1234567 Gen ตาม TCNTAuto' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ลำดับรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FNXsdSeqNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อรายการ เช่น ค่ามัดจำโช๊ค , SO20210000100001' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTXsdName'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'กรณีอ้างอิงเอกสาร คือ Grand  , กรณีไม่อ้างอิงคือยอดมัดจำปกติ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FCXsdTotal'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ประเภทภาษี 1:มีภาษี, 2:ไม่มีภาษี' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTXsdVatType'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสภาษี ณ. ซื้อ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTVatCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อัตราภาษี' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTVatRate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'มูลค่าภาษี IN: FCXsdDeposit-((FCXsdDeposit*100)/(100+VatRate)) ,EX: ((FCXsdDeposit*(100+VatRate))/100)-FCXsdDeposit' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FCXsdVat'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'มูลค่าแยกภาษี (FCXsdDeposit-FCXpdVat)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FCXsdVatable'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมเงินมัดจำ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FCXsdDeposit'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'หมายเหตุ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTXsdRmk'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อ้างอิงใบสั่งขาด 1:ใบสั่งขาย 2:สินค้าปกติ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositDT', @level2type=N'COLUMN',@level2name=N'FTXsdSoRef'
END
GO
IF OBJECT_ID(N'TARTRcvDepositHDCst') IS NULL BEGIN

    CREATE TABLE [dbo].[TARTRcvDepositHDCst](
	    [FTBchCode] [varchar](5) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FTCstCode] [varchar](20) NULL,
	    [FTXshCardID] [varchar](20) NULL,
	    [FTXshCstName] [varchar](255) NULL,
	    [FTXshCstTel] [varchar](255) NULL,
	    [FTXshCardNo] [varchar](20) NULL,
	    [FNXshCrTerm] [int] NULL,
	    [FDXshDueDate] [datetime] NULL,
	    [FDXshBillDue] [datetime] NULL,
	    [FTXshCtrName] [varchar](100) NULL,
	    [FDXshTnfDate] [datetime] NULL,
	    [FTXshRefTnfID] [varchar](20) NULL,
	    [FNXshAddrShip] [bigint] NULL,
	    [FNXshAddrTax] [bigint] NULL,
     CONSTRAINT [PK_TARTRcvDepositHDCst] PRIMARY KEY CLUSTERED 
    (
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สาขาสร้าง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสลูกค้า' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTCstCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่บัตรประจำตัวประชาชน/Passport' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCardID'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อสมาชิก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCstName'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เบอร์โทร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCstTel'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'หมายเลขบัตรสมาชิก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCardNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ระยะเครดิต' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FNXshCrTerm'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ครบกำหนด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FDXshDueDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่จะรับ/วางบิล' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FDXshBillDue'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อผู้ตืดต่อ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTXshCtrName'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ส่งของ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FDXshTnfDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่ ใบขนส่ง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FTXshRefTnfID'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ที่อยู่ส่งของ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FNXshAddrShip'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ที่อยู่ใบกำกับภาษี' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHDCst', @level2type=N'COLUMN',@level2name=N'FNXshAddrTax'

END
GO
IF OBJECT_ID(N'TARTRcvDepositHD') IS NULL BEGIN

    CREATE TABLE [dbo].[TARTRcvDepositHD](
	    [FTBchCode] [varchar](5) NOT NULL,
	    [FTXshDocNo] [varchar](20) NOT NULL,
	    [FTShpCode] [varchar](5) NULL,
	    [FNXshDocType] [int] NULL,
	    [FDXshDocDate] [datetime] NULL,
	    [FTXshCshOrCrd] [varchar](1) NULL,
	    [FTXshVATInOrEx] [varchar](1) NULL,
	    [FTDptCode] [varchar](5) NULL,
	    [FTPosCode] [varchar](5) NULL,
	    [FTShfCode] [varchar](20) NULL,
	    [FNSdtSeqNo] [int] NULL,
	    [FTUsrCode] [varchar](20) NULL,
	    [FTSpnCode] [varchar](20) NULL,
	    [FTXshApvCode] [varchar](20) NULL,
	    [FTCstCode] [varchar](20) NULL,
	    [FTXshDocVatFull] [varchar](20) NULL,
	    [FTXshRefExt] [varchar](20) NULL,
	    [FDXshRefExtDate] [datetime] NULL,
	    [FTXshRefInt] [varchar](20) NULL,
	    [FDXshRefIntDate] [datetime] NULL,
	    [FNXshDocPrint] [int] NULL,
	    [FTRteCode] [varchar](5) NULL,
	    [FCXshRteFac] [numeric](18, 4) NULL,
	    [FCXshTotal] [numeric](18, 4) NULL,
	    [FCXshTotalNV] [numeric](18, 4) NULL,
	    [FCXshTotalNoDis] [numeric](18, 4) NULL,
	    [FCXshAmtV] [numeric](18, 4) NULL,
	    [FCXshAmtNV] [numeric](18, 4) NULL,
	    [FCXshVat] [numeric](18, 4) NULL,
	    [FCXshVatable] [numeric](18, 4) NULL,
	    [FCXshGrand] [numeric](18, 4) NULL,
	    [FCXshRnd] [numeric](18, 4) NULL,
	    [FTXshGndText] [varchar](200) NULL,
	    [FCXshPaid] [numeric](18, 4) NULL,
	    [FCXshLeft] [numeric](18, 4) NULL,
	    [FTXshRmk] [varchar](200) NULL,
	    [FTXshStaRefund] [varchar](1) NULL,
	    [FTXshStaDoc] [varchar](1) NULL,
	    [FTXshStaApv] [varchar](1) NULL,
	    [FTXshStaPrcDoc] [varchar](255) NULL,
	    [FTXshStaPaid] [varchar](1) NULL,
	    [FNXshStaDocAct] [int] NULL,
	    [FNXshStaRef] [int] NULL,
	    [FDLastUpdOn] [datetime] NULL,
	    [FTLastUpdBy] [varchar](20) NULL,
	    [FDCreateOn] [datetime] NULL,
	    [FTCreateBy] [varchar](20) NULL,
     CONSTRAINT [PK_TARTRcvDepositHD] PRIMARY KEY CLUSTERED 
    (
	    [FTBchCode] ASC,
	    [FTXshDocNo] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]

    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สาขาสร้าง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTBchCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร  Def : XYYPOS-1234567 Gen ตาม TCNTAuto' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshDocNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ร้านค้า' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTShpCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ประเภทเอกสาร ดูจาก ตาราง TSysDocType' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FNXshDocType'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่/เวลา เอกสาร dd/mm/yyyy H:mm:ss' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FDXshDocDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สด/เครดิต 1:สด 2:credit' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshCshOrCrd'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ภาษีมูลค่าเพิ่ม 1:รวมใน, 2:แยกนอก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshVATInOrEx'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'แผนก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTDptCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เครื่องจุดขาย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTPosCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รอบ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTShfCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ลำดับการ SignIn DT' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FNSdtSeqNo'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'พนักงาน Key' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTUsrCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'พนักงานขาย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTSpnCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้อนุมัติ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshApvCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ลูกค้า' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTCstCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่ใบกำกับ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshDocVatFull'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อ้างอิง เลขที่เอกสาร ภายนอก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshRefExt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อ้างอิง วันที่เอกสาร ภายนอก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FDXshRefExtDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อ้างอิง เลขที่เอกสาร ภายใน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshRefInt'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อ้างอิง วันที่เอกสาร ภายใน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FDXshRefIntDate'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'จำนวนครั้งที่พิมพ์' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FNXshDocPrint'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสสกุลเงิน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTRteCode'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'อัตราแลกเปลี่ยน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshRteFac'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวม' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshTotal'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมสินค้าไม่มีภาษี' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshTotalNV'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมสินค้าห้ามลด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshTotalNoDis'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมเฉพาะภาษี (FCXshTotal-FCXshTotalNV-(FCXshTotalB4DisChgV-FCXshTotalAfDisChgV))' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshAmtV'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวมเฉพาะไม่มีภาษี (FCXshTotalNV-(FCXshTotalB4DisChgNV-FCXshTotalAfDisChgNV))' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshAmtNV'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดภาษี' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshVat'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดแยกภาษี (FCXshAmtV-FCXshVat)+FCXshAmt์NV' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshVatable'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดรวม FCXshAmtV+FCXshAmtNV+FCXshRnd' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshGrand'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดปัดเศษ (เมื่อชำระด้วยเงินสดเฉพาะขายปลีก)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshRnd'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ข้อความ ยอดรวมสุทธิ(FCXphGrand)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshGndText'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดจ่าย' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshPaid'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ยอดค้าง Default: 0' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FCXshLeft'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'หมายเหตุ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshRmk'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะ การคืน 1:ไม่เคยคืน, 2:เคยคืน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshStaRefund'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshStaDoc'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะ อนุมัติ เอกสาร ว่าง:ยังไม่ทำ, 1:อนุมัติแล้ว' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshStaApv'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะอนุมัติ 1: อนุมัติแล้ว  ว่าง null ยังไม่อนุมัติ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshStaPrcDoc'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะ รับ/จ่ายเงิน 1:ยังไม่จ่าย 2:บางส่วน, 3:ครบ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTXshStaPaid'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะ เคลื่อนไหว 0:NonActive, 1:Active' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FNXshStaDocAct'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะ อ้างอิง 0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FNXshStaRef'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FDLastUpdOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTLastUpdBy'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FDCreateOn'
    EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TARTRcvDepositHD', @level2type=N'COLUMN',@level2name=N'FTCreateBy'

END
GO
-- 00.00.02 --
IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTPdtTwxHD' AND COLUMN_NAME = 'FTXthDocType') BEGIN
	ALTER TABLE TCNTPdtTwxHD ADD FTXthDocType VARCHAR(1)
END
GO
-- End 00.00.02 --
-------------- Update Table  --------------

-------------- Update Stored Procedure  --------------
IF EXISTS
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_PRCxReorderPoint')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_PRCxReorderPoint
GO

CREATE PROCEDURE [dbo].STP_PRCxReorderPoint
    @ptBchCode varchar(5)
    , @ptWahCode varchar(5)
    , @ptWho varchar(100)
    , @FNResult INT OUTPUT AS
DECLARE @tDate varchar(10)
DECLARE @tTime varchar(8)
DECLARE @tTrans varchar(20)
DECLARE @nPrevDay INT
/*---------------------------------------------------------------------
Document History
Version		Date			User	Remark
07.00.00	01/09/2021		Net		create 
07.01.00	01/09/2021		Net		แก้ไข Process คำนวน 
07.02.00	23/11/2021		Net		แก้ไขการคำนวนการเฉลี่ยย้อนหลังตาม Option
----------------------------------------------------------------------*/
SET @tTrans = 'ReorderPoint'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @nPrevDay = ISNULL((SELECT FTSysStaUsrValue FROM TSysConfig WHERE FTSysCode='nVB_ReOrdPntDay' AND FTSysSeq='1'),30)*-1

    INSERT INTO TCNTPdtStkBal
    (
        FTBchCode, FTWahCode, FTPdtCode, FCStkQty, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
    )
    SELECT DISTINCT
        PdtWah.FTBchCode, PdtWah.FTWahCode, PdtWah.FTPdtCode, 0 AS FCStkQty
        , GETDATE() AS FDLastUpd,@ptWho, GETDATE() AS FDCreateOn,@ptWho
    FROM TCNMPdtSpcWah PdtWah WITH(NOLOCK)
    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
        PdtWah.FTBchCode = STK.FTBchCode AND PdtWah.FTWahCode = STK.FTWahCode AND PdtWah.FTPdtCode = STK.FTPdtCode
    WHERE ISNULL(STK.FTPdtCode,'') = ''

    UPDATE SPCW
    SET SPCW.FCPdtDailyUseAvg = CASE WHEN ISNULL(SalQty.FTPdtCode,'')='' OR ISNULL(SalMinDate.FTPdtCode,'')='' OR ISNULL(SalMaxDate.FTPdtCode,'')=''
                                        THEN 0
                                     ELSE SalQty.FCXsdQty / 
                                            ISNULL((CASE WHEN DATEDIFF(DAY,SalMinDate.FDXshDocDate, DATEADD(DAY,1,SalMaxDate.FDXshDocDate))<=0 
                                                    THEN 1 ELSE DATEDIFF(DAY,SalMinDate.FDXshDocDate, DATEADD(DAY,1,SalMaxDate.FDXshDocDate))
                                                  END),1)
                                     END
    FROM TCNMPdtSpcWah SPCW WITH(NOLOCK)
    LEFT JOIN 
    (
        SELECT DT.FTPdtCode, SUM(DT.FCXsdQty * CASE WHEN HD.FNXshDocType=1 THEN 1 ELSE -1 END) AS FCXsdQty
        FROM TPSTSalHD HD WITH(NOLOCK)
        INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTXshDocNo = DT.FTXshDocNo
        WHERE HD.FTBchCode=@ptBchCode AND HD.FTWahCode=@ptWahCode
        GROUP BY DT.FTPdtCode
    )SalQty ON SPCW.FTPdtCode = SalQty.FTPdtCode
    LEFT JOIN 
    (
        SELECT DT.FTPdtCode, MIN(HD.FDXshDocDate) AS FDXshDocDate
        FROM TPSTSalHD HD WITH(NOLOCK)
        INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTXshDocNo = DT.FTXshDocNo
        WHERE HD.FTBchCode=@ptBchCode AND HD.FTWahCode=@ptWahCode
            AND CONVERT(VARCHAR(10), HD.FDXshDocDate, 121) BETWEEN CONVERT(VARCHAR(10), DATEADD(DAY,@nPrevDay,GETDATE()), 121) AND CONVERT(VARCHAR(10), GETDATE(), 121) 
        GROUP BY DT.FTPdtCode
    )SalMinDate ON SPCW.FTPdtCode = SalMinDate.FTPdtCode
    LEFT JOIN 
    (
        SELECT DT.FTPdtCode, MAX(HD.FDXshDocDate) AS FDXshDocDate
        FROM TPSTSalHD HD WITH(NOLOCK)
        INNER JOIN TPSTSalDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTXshDocNo = DT.FTXshDocNo
        WHERE HD.FTBchCode=@ptBchCode AND HD.FTWahCode=@ptWahCode
            AND CONVERT(VARCHAR(10), HD.FDXshDocDate, 121) BETWEEN CONVERT(VARCHAR(10), DATEADD(DAY,@nPrevDay,GETDATE()), 121) AND CONVERT(VARCHAR(10), GETDATE(), 121) 
        GROUP BY DT.FTPdtCode
    )SalMaxDate ON SPCW.FTPdtCode = SalMaxDate.FTPdtCode
    WHERE SPCW.FTBchCode=@ptBchCode AND SPCW.FTWahCode=@ptWahCode

    UPDATE TCNMPdtSpcWah
    SET FCPdtQtyOrdBuy = ISNULL(CONVERT(decimal,FCPdtLeadTime),0)
        * ISNULL(CONVERT(decimal,FCPdtDailyUseAvg),0)
        + ISNULL(CONVERT(decimal,FCPdtMin),0)

    UPDATE SPCW
    SET FTPdtStaOrder = (CASE WHEN ISNULL(STK.FCStkQty,0) <= 0 AND ISNULL(SPCW.FCPdtQtyOrdBuy,0)>0 THEN '1'
                              WHEN ISNULL(STK.FCStkQty,0) <= 0 AND ISNULL(SPCW.FCPdtQtyOrdBuy,0)<=0 THEN ''
                              ELSE (CASE WHEN (ISNULL(SPCW.FCPdtQtyOrdBuy,0)*100)/ISNULL(STK.FCStkQty,0) > ISNULL(SPCW.FCPdtPerSLA,90)
                                            THEN '1' ELSE ''
                                    END)
                              END)
    , FDLastUpdOn = GETDATE()
    , FTLastUpdBy = @ptWho
    FROM TCNMPdtSpcWah SPCW
    LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
        SPCW.FTBchCode = STK.FTBchCode AND SPCW.FTWahCode = STK.FTWahCode
        AND SPCW.FTPdtCode = STK.FTPdtCode
    WHERE SPCW.FTBchCode=@ptBchCode AND SPCW.FTWahCode=@ptWahCode
	
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
            DTWrn.FTBchCode = DTRet.FTBchCode AND DTWrn.FTPchDocNo = DT.FTPchDocNo
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
            DTWrn.FTBchCode = DTRet.FTBchCode AND DTWrn.FTPchDocNo = DT.FTPchDocNo
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
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPdtClaimRcvCst')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPdtClaimRcvCst
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPdtClaimRcvCst
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
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
07.00.00	1/11/2021		Net		create 
07.01.00    23/11/2021      Net     แก้ไขการเลือก Vat
----------------------------------------------------------------------*/
SET @tTrans = 'GenTwi'
BEGIN TRY
	BEGIN TRANSACTION @tTrans

    SET @dDateNow = GETDATE()

    -- Get สถานะเอกสาร
    SELECT @tStaPrcDoc = ISNULL(HD.FTPchStaPrcDoc, '')
    , @tAgnDoc = ISNULL(HD.FTAgnCode, '')
    FROM TCNTPdtClaimHD HD WITH(NOLOCK)
    WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

    IF @tStaPrcDoc = '1'  -- รออนุมัติ
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
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, ROW_NUMBER() OVER(ORDER BY DT.FNPcdSeqNo) AS FNXtdSeqNo, DT.FTPdtCode, DT.FTPcdPdtName
        , DT.FTPunCode, DT.FTPunName, DT.FCPcdFactor, DT.FTPcdBarCode, ISNULL(PDT.FTPdtStaVat,'2')
        , ISNULL(PDT.FTVatCode, @tVatCode), ISNULL(VAT.FCVatRate, @cVatRate), DT.FCPcdQty, DT.FCPcdQtyAll, ISNULL(PRI.FCPgdPriceRet,0)
        , DT.FCPcdQty * ISNULL(PRI.FCPgdPriceRet,0)
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
        , NULL AS FTXtdPdtStaSet, '' AS FTXtdRmk, '' AS FTXtdBchRef, '' AS FTXtdDocNoRef
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
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
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, 1, '5' AS FTXthRsnType, GETDATE() AS FDXthDocDate
        , @tVatInOrExt, '' AS FTDptCode, '' AS FTXthMerCode, '' AS FTXthShopFrm, '' AS FTXthShopTo
        , '' AS FTXthWhFrm, DT.FTWahCode, '' AS FTXthPosFrm, '' AS FTXthPosTo, '' AS FTSplCode
        , '' AS FTXthOther, HD.FTUsrcode, '' AS FTSpnCode, '' AS FTXthApvCode, '' AS FTXthRefExt
        , NULL AS FDXthRefExtDate, '' AS FTXthRefInt, NULL AS FDXthRefIntDate, 0 AS FNXthDocPrint, TDT.FCXthTotal
        , TDT.FCXthVat, TDT.FCXthVatable, '' AS FTXthRmk, '1' AS FTXthStaDoc, '' AS FTXthStaApv
        , '' AS FTXthStaPrcStk, '' AS FTXthStaDelMQ, 1 AS FNXthStaDocAct, 0 AS FNXthStaRef, '' AS FTRsnCode, '5' AS FTXthTypRefFrm
        , GETDATE(), @ptWho, GETDATE(), @ptWho
        , HD.FTCstCode
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimDT DT WITH(NOLOCK) ON
            HD.FTBchCode = DT.FTBchCode AND HD.FTPchDocNo = DT.FTPchDocNo
        INNER JOIN (
            SELECT @ptBchCode AS FTBchCode, @ptDocNo AS FTPchDocNo
            , SUM(DT.FCXtdNet) AS FCXthTotal
            , SUM(DT.FCXtdVat) AS FCXthVat
            , SUM(DT.FCXtdVatable) AS FCXthVatable
            FROM TCNTPdtTwiDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = @ptBchCode AND DT.FTXthDocNo = @tGenDocNo
        )TDT ON
            HD.FTBchCode = TDT.FTBchCode AND HD.FTPchDocNo = TDT.FTPchDocNo
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        ---------- End Gen เอกสาร ----------

        IF( (SELECT COUNT(*) FROM TCNTPdtTwiHD WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 OR
            (SELECT COUNT(*) FROM TCNTPdtTwiDT WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 )
            THROW 50000, 'Gen Doc Empty', 0;


    END --End รออนุมัติ


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
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'STP_DOCxGenPdtClaimPick')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].STP_DOCxGenPdtClaimPick
GO

CREATE PROCEDURE [dbo].STP_DOCxGenPdtClaimPick
    @ptBchCode varchar(5)
    , @ptDocNo varchar(30)
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
07.00.00	1/11/2021		Net		create 
07.01.00    23/11/2021      Net     แก้ไขการเลือก Vat
----------------------------------------------------------------------*/
SET @tTrans = 'GenTwx'
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
        AND ISNULL(DTSpl.FTPcdStaPick,'') = '1'

    IF @tStaPrcDoc = '2'  -- อนุมัติแล้ว
    BEGIN

        --Gen เลขที่เอกสาร ใบรับของ
        INSERT @TblGenDoc 
        EXEC @nStoreRet = [dbo].[SP_CNtAUTAutoDocNo]
            @ptTblName = N'TCNTPdtTwxHD'
		    , @ptDocType = N'3'
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
        , 'TNFEX', GETDATE()
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
        
        INSERT TCNTPdtTwxHDRef
        (
            FTBchCode, FTXthDocNo, FTXthCtrName, FDXthTnfDate, FTXthRefTnfID
            , FTCarCode, FTXthQtyAndTypeUnit, FNXthShipAdd, FTViaCode
        )
        SELECT HD.FTBchCode, @tGenDocNo, ISNULL(CSTL.FTCstName,''), NULL, NULL
        , HDCst.FTCarCode, NULL, NULL, NULL
        FROM TCNTPdtClaimHD HD WITH(NOLOCK)
        INNER JOIN TCNTPdtClaimHDCst HDCst WITH(NOLOCK) ON
            HD.FTBchCode = HDCst.FTBchCode AND HD.FTPchDocNo = HDCst.FTPchDocNo
        LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON
            HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = 1
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo

        INSERT TCNTPdtTwxDT
        (
            FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName
            , FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTXtdVatType
            , FTVatCode, FCXtdVatRate, FCXtdQty, FCXtdQtyAll, FCXtdSetPrice
            , FCXtdAmt, FCXtdVat, FCXtdVatable, FCXtdNet, FCXtdCostIn
            , FCXtdCostEx, FTXtdStaPrcStk, FNXtdPdtLevel, FTXtdPdtParent, FCXtdQtySet
            , FTXtdPdtStaSet, FTXtdRmk
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, ROW_NUMBER() OVER(ORDER BY DTSpl.FNPcdSeqNo) AS FNXtdSeqNo, DTSpl.FTPcdPdtPick, DT.FTPcdPdtName
        , DT.FTPunCode, DT.FTPunName, DT.FCPcdFactor, DT.FTPcdBarCode, ISNULL(PDT.FTPdtStaVat,'2') 
        , ISNULL(PDT.FTVatCode, @tVatCode) , ISNULL(VAT.FCVatRate, @cVatRate) , DTSpl.FCPcdQtyPick, DTSpl.FCPcdQtyPick * DT.FCPcdFactor, ISNULL(PRI.FCPgdPriceRet,0)
        , DTSpl.FCPcdQtyPick * ISNULL(PRI.FCPgdPriceRet,0) AS FCXtdAmt
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DTSpl.FCPcdQtyPick * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DTSpl.FCPcdQtyPick * ISNULL(PRI.FCPgdPriceRet,0)) * ISNULL(VAT.FCVatRate, @cVatRate)/100
                     END
                ELSE 0
          END AS FCXtdVat
        , CASE WHEN ISNULL(PDT.FTPdtStaVat,'2') = '1' -- 1:มีภาษี 2:ไม่มีภาษี
                THEN CASE WHEN @tVatInOrExt = '1'  -- 1:รวมใน 2:แยกนอก
                            THEN (DTSpl.FCPcdQtyPick * ISNULL(PRI.FCPgdPriceRet,0)) * 100/(100+ISNULL(VAT.FCVatRate, @cVatRate))
                            ELSE (DTSpl.FCPcdQtyPick * ISNULL(PRI.FCPgdPriceRet,0))
                     END
                ELSE (DTSpl.FCPcdQtyPick * ISNULL(PRI.FCPgdPriceRet,0))
          END AS FCXtdVatable
        , (DTSpl.FCPcdQtyPick * ISNULL(PRI.FCPgdPriceRet,0)) AS FCXtdNet, NULL AS FCXtdCostIn
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
            AND ISNULL(DTSpl.FTPcdStaPick,'') = '1'

        INSERT TCNTPdtTwxHD
        (
            FTBchCode, FTXthDocNo, FTXthDocType, FDXthDocDate, FTXthVATInOrEx
            , FTDptCode, FTXthMerCode, FTXthShopFrm, FTXthShopTo, FTXthWhFrm
            , FTXthWhTo, FTXthPosFrm, FTXthPosTo, FTUsrCode, FTSpnCode
            , FTXthApvCode, FTXthRefExt, FDXthRefExtDate, FTXthRefInt, FDXthRefIntDate
            , FNXthDocPrint, FCXthTotal, FCXthVat, FCXthVatable, FTXthRmk
            , FTXthStaDoc, FTXthStaApv, FTXthStaPrcStk, FTXthStaDelMQ, FNXthStaDocAct
            , FNXthStaRef, FTRsnCode
            , FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy
        )
        SELECT DISTINCT HD.FTBchCode, @tGenDocNo, '1' AS FTXthDocType, GETDATE() AS FDXthDocDate, @tVatInOrExt AS FTXthVATInOrEx
        , '' AS FTDptCode, '' AS FTXthMerCode, '' AS FTXthShopFrm, '' AS FTXthShopTo
        , CASE WHEN ISNULL(BCH.FTWahCode,'') = '' 
                   THEN (SELECT TOP 1 FTWahCode FROM TCNMWaHouse WITH(NOLOCK) WHERE FTBchCode = HD.FTBchCode AND FTWahStaType = '1')
               ELSE BCH.FTWahCode
          END AS FTXthWhFrm
        , DTSpl.FTWahCode, '' AS FTXthPosFrm, '' AS FTXthPosTo, HD.FTUsrcode, '' AS FTSpnCode
        , '' AS FTXthApvCode, '' AS FTXthRefExt, NULL AS FDXthRefExtDate, '' AS FTXthRefInt, NULL AS FDXthRefIntDate
        , 0 AS FNXthDocPrint, TDT.FCXthTotal, TDT.FCXthVat, TDT.FCXthVatable, '' AS FTXthRmk
        , '1' AS FTXthStaDoc, '' AS FTXthStaApv, '' AS FTXthStaPrcStk, '' AS FTXthStaDelMQ, 1 AS FNXthStaDocAct
        , 0 AS FNXthStaRef, '' AS FTRsnCode
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
            FROM TCNTPdtTwxDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = @ptBchCode AND DT.FTXthDocNo = @tGenDocNo
        )TDT ON
            HD.FTBchCode = TDT.FTBchCode AND HD.FTPchDocNo = TDT.FTPchDocNo
        INNER JOIN TCNMBranch BCH WITH(NOLOCK) ON
            HD.FTBchCode = BCH.FTBchCode
        WHERE HD.FTBchCode = @ptBchCode AND HD.FTPchDocNo = @ptDocNo
            AND ISNULL(DTSpl.FTPcdStaPick,'') = '1'

        ---------- End Gen เอกสาร ----------
        
        IF( (SELECT COUNT(*) FROM TCNTPdtTwxHD WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 OR
            (SELECT COUNT(*) FROM TCNTPdtTwxDT WHERE FTBchCode = @ptBchCode AND FTXthDocNo = @tGenDocNo) = 0 )
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

	    SET @tStaPrc = (CASE WHEN (SELECT COUNT(*) AS FTXphStaPrcStk 
                                   FROM TSVTJob1ReqDT WITH(NOLOCK) 
                                   WHERE FTBchCode = @ptBchCode AND FTXshDocNo = @ptDocNo
                                       AND ISNULL(FTXsdStaPrcStk,'')='1' ) > 0
                             THEN '1' ELSE '2' END) -- 1เคยตัด Stk ไปแล้ว 2ยังไม่เคยตัดStk

        
        -- เคยตัด Stk ไปแล้
	    IF @tStaPrc <> '2'	
	    BEGIN
            
            --หาคลังจอง
            SET @tWahCodeTo = (SELECT TOP 1 FTWahCode
                               FROM TCNMWaHouse WAH WITH(NOLOCK)
                               WHERE WAH.FTBchCode = @ptBchCode AND ISNULL(FTWahStaType,'') = '7')
            
            --ถ้ามีไม่คลังจอง
            IF ISNULL(@tWahCodeTo, '') = '' 
                THROW 50000, 'Wahouse not found', 0;
            
		    -- ตัด Stk เข้า คลังต้นทาง
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
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			-- Update ตัด Stk เข้า คลังต้นทาง
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
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
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			    GROUP BY DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk เข้า คลังต้นทาง

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
                AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้
		    --End ตัด Stk ออก คลังต้นทาง
        



		    -- ตัด Stk ออกคลังปลายทาง 
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
                AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี
            
			-- Update ตัด Stk ออกคลังปลายทาง
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
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
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			    GROUP BY DT.FTBchCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk ออกคลังปลายทาง

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
                AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้
		    --End ตัด Stk เข้า คลังต้นปลายทาง 



		    --Insert ลง Stock Card
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

		    INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , FDCreateOn, FTCreateBy
            )
		    SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk
		    --End Insert ลง Stock Card
		
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
                AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
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
                AND ISNULL(DTP.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
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
                AND ISNULL(DT.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
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
                AND ISNULL(DTP.FTXsdStaPrcStk,'') IN ('') AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
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
            
            --หาคลังจอง
            SET @tWahCodeTo = (SELECT TOP 1 FTWahCode
                               FROM TCNMWaHouse WAH WITH(NOLOCK)
                               WHERE WAH.FTBchCode = @ptBchCode AND ISNULL(FTWahStaType,'') = '7')
            
            --ถ้ามีไม่คลังจอง
            IF ISNULL(@tWahCodeTo, '') = '' 
                THROW 50000, 'Wahouse not found', 0;
            
		    -- ตัด Stk เข้า คลังต้นทาง
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
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                DT.FTBchCode = STK.FTBchCode AND DT.FTWahCode = STK.FTWahCode AND DT.FTPdtCode = STK.FTPdtCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                AND DT.FTWahCode<>@tWahCodeTo
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
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1'
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                AND DTP.FTWahCode<>@tWahCodeTo
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
			-- Update ตัด Stk เข้า คลังต้นทาง
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
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
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    AND DT.FTWahCode<>@tWahCodeTo
			    GROUP BY DT.FTBchCode, DT.FTWahCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk เข้า คลังต้นทาง
            
			-- Update ตัด Stk เข้า คลังต้นทางd ตัวลูก
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty + ISNULL(DocStk.FCXtdQtyAll,0)
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
                    PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
                INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                    DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			    WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                    AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    AND DTP.FTWahCode<>@tWahCodeTo
			    GROUP BY DT.FTBchCode, DTP.FTWahCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk เข้า คลังต้นทาง ตัวลูก

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
			, SUM(DT.FCXsdQtyAll) AS FCStkQty, DT.FTWahCode AS FTWahCode, HD.FDXshDocDate AS FDStkDate
			, ROUND(SUM(DT.FCXsdSetPrice)/SUM(DT.FCXsdQtyAll),2) AS FCStkSetPrice
			, 0 AS FCStkCostIn
			, 0 AS FCStkCostEx
			FROM TSVTJob2OrdHD HD WITH(NOLOCK)
			INNER JOIN TSVTJob2OrdDT DT WITH(NOLOCK) ON
                HD.FTAgnCode = DT.FTAgnCode AND HD.FTBchCode = DT.FTBchCode
                AND HD.FTXshDocNo = DT.FTXshDocNo
			INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode AND DT.FTWahCode = WAH.FTWahCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                AND DT.FTWahCode<>@tWahCodeTo
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DT.FTWahCode, HD.FDXshDocDate
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
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode AND DTP.FTWahCode = WAH.FTWahCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo
                AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                AND DTP.FTWahCode<>@tWahCodeTo
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, DTP.FTWahCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้  ตัวลูก
		    --End ตัด Stk ออก คลังต้นทาง
        



		    -- ตัด Stk ออกคลังปลายทาง 
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
                AND ISNULL(DT.FTXsdStaPrcStk,'') = '1'
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
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode
			LEFT JOIN TCNTPdtStkBal STK WITH(NOLOCK) ON 
                DT.FTBchCode = STK.FTBchCode AND DT.FTPdtCode = STK.FTPdtCode AND STK.FTWahCode = @tWahCodeTo
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1'
			    AND ISNULL(STK.FTPdtCode,'') = '' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
            --End Create stk balance qty 0 ตัวที่ไม่เคยมี ตัวลูก
            
			-- Update ตัด Stk ออกคลังปลายทาง
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
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
                    AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    AND DT.FTWahCode<>@tWahCodeTo
			    GROUP BY DT.FTBchCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk ออกคลังปลายทาง
            
			-- Update ตัด Stk ออกคลังปลายทาง ตัวลูก
			UPDATE STK WITH(ROWLOCK)
			SET FCStkQty = STK.FCStkQty - ISNULL(DocStk.FCXtdQtyAll,0)
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
                    AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                    AND DTP.FTWahCode<>@tWahCodeTo
			    GROUP BY DT.FTBchCode, DT.FTPdtCode
            ) DocStk  ON 
                DocStk.FTBchCode = STK.FTBchCode AND DocStk.FTWahCode = STK.FTWahCode AND DocStk.FTPdtCode = STK.FTPdtCode
			--End Update ตัด Stk ออกคลังปลายทาง ตัวลูก

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
                AND ISNULL(DT.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                AND DT.FTWahCode<>@tWahCodeTo
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
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
                PDT.FTPdtCode = DT.FTPdtCode AND PDT.FTPdtStkControl = '1' --AND ISNULL(PDT.FTPdtStaAlwBook,'')='1'
            INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON
                DT.FTBchCode = WAH.FTBchCode
			WHERE DT.FTBchCode = @ptBchCode AND DT.FTXshDocNo = @ptDocNo AND WAH.FTWahCode = @tWahCodeTo
                AND ISNULL(DTP.FTXsdStaPrcStk,'') = '1' AND ISNULL(WAH.FTWahStaPrcStk,'') = '2'
                AND DTP.FTWahCode<>@tWahCodeTo
			GROUP BY DT.FTBchCode, DT.FTXshDocNo, DT.FTPdtCode, HD.FDXshDocDate
            --End เก็บตัวที่ตัด Stk ไว้ ตัวลูก
		    --End ตัด Stk เข้า คลังต้นปลายทาง 



		    --Insert ลง Stock Card
		    DELETE TCNTPdtStkCrd WITH(ROWLOCK)
		    WHERE FTBchCode = @ptBchCode AND FTStkDocNo = @ptDocNo+'C'

		    INSERT INTO TCNTPdtStkCrd WITH(ROWLOCK)
            (
                FTBchCode, FDStkDate, FTStkDocNo, FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , FDCreateOn, FTCreateBy
            )
		    SELECT FTBchCode, FDStkDate, FTStkDocNo+'C', FTWahCode, FTPdtCode, FTStkType, FTStkSysType
                , FCStkQty, FCStkSetPrice, FCStkCostIn, FCStkCostEx, FTPdtParent
                , GETDATE() AS FDCreateOn, @ptWho AS FTCreateBy
		    FROM @TTmpPrcStk
		    --End Insert ลง Stock Card
		
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

-------------- Update Stored Procedure  --------------
IF OBJECT_ID(N'TCNMBchSplSpc') IS NULL BEGIN
	CREATE TABLE [dbo].[TCNMBchSplSpc](
		[FTAgnCode] [varchar](10) NOT NULL,
        [FTBchCode] [varchar](5) NOT NULL,
        [FTSplCode] [varchar](20) NOT NULL,
        [FTStaUse] [varchar](1) NULL,
	 CONSTRAINT [PK_TCNMBchSplSpc] PRIMARY KEY CLUSTERED 
	(
		[FTAgnCode] ASC,
        [FTBchCode] ASC,
        [FTSplCode] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]
END
GO

IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTImpMasTmp' AND COLUMN_NAME = 'FTPosIP') BEGIN
	ALTER TABLE TCNTImpMasTmp ADD FTPosIP varchar(20);
END
GO

IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTSalHDCst' AND COLUMN_NAME = 'FTXshCstRef') BEGIN
	ALTER TABLE TPSTSalHDCst ADD FTXshCstRef VARCHAR(50)
END
GO
IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TSVMCar' AND COLUMN_NAME = 'FTCbrBchCode') BEGIN
    ALTER TABLE TSVMCar ADD FTCbrBchCode VARCHAR(20)
END
GO

----------------------------------------------- Script Upgrade StoreBackOffice 00.00.05 2021-12-03 -----------------------------------------------

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
    07.01.00	12/03/2021		Net		แก้ไข QtyLef  
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
            , PDT.FTPdtStaVat, ISNULL(PDT.FTVatCode, @tVatCode), ISNULL(VAT.FCVatRate, @cVatRate), PDT.FTPdtSaleType, ISNULL(PDT.FCPdtCostStd, 0)
            , MDT.FCXpdQty, MDT.FCXpdQty*MDT.FCXpdFactor, ISNULL(PDT.FCPdtCostStd, 0), ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty, ''
            , 0, 0, ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty, ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty
            , CASE WHEN PDT.FTPdtStaVat='2' THEN 0 --ไม่มีภาษี
                ELSE CASE WHEN @tVatInOrExt = '1' --รวมใน
                                THEN (ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty*7)/(100+@cVatRate)
                            ELSE (ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty*7)/(100) --แยกนอก
                        END
            END AS FCXpdVat
            , CASE WHEN PDT.FTPdtStaVat='2' THEN ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty --ไม่มีภาษี
                ELSE CASE WHEN @tVatInOrExt = '1' --รวมใน
                                THEN (ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty*100)/(100+@cVatRate)
                            ELSE ISNULL(PDT.FCPdtCostStd, 0)*MDT.FCXpdQty --แยกนอก
                        END
            END AS FCXpdVatable
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
(SELECT * FROM dbo.sysobjects WHERE id = object_id(N'SP_RPTxPdtHisTnfWah')and OBJECTPROPERTY(id, N'IsProcedure') = 1)
    DROP PROCEDURE [dbo].SP_RPTxPdtHisTnfWah
GO

    CREATE PROCEDURE [dbo].[SP_RPTxPdtHisTnfWah]
        @pnLngID int , 
        @pnComName Varchar(100),
        @ptRptCode Varchar(100),
        @ptUsrSession Varchar(255),
        @pnFilterType int, --1 BETWEEN 2 IN
        @ptAgnCode VARCHAR(20),
        --สาขา
        @ptBchL Varchar(8000),
        --คลัง
        @ptWahF Varchar(20),
        @ptWahT Varchar(20),
        --@ptWahL Varchar(8000),
        --สินค้า
        @ptPdtF Varchar(20),
        @ptPdtT Varchar(20),
        --วันที่โออนออก FDLastUpdOn
        @ptDocDateF Varchar(10), 
        @ptDocDateT Varchar(10), 
        @FNResult INT OUTPUT 
    AS

    BEGIN TRY
        DECLARE @nLngID int 
        DECLARE @nComName Varchar(100)
        DECLARE @tRptCode Varchar(100)
        DECLARE @tUsrSession Varchar(255)
        DECLARE @tSql VARCHAR(8000)
        DECLARE @tSql1 VARCHAR(8000)

        DECLARE @tBchF Varchar(5)
        DECLARE @tBchT Varchar(5)
        DECLARE @tWahF Varchar(5)
        DECLARE @tWahT Varchar(5)
        DECLARE @tPdtF Varchar(20)
        DECLARE @tPdtT Varchar(20)

        SET @nLngID = @pnLngID
        SET @nComName = @pnComName
        SET @tUsrSession = @ptUsrSession
        SET @tRptCode = @ptRptCode

        --SET @tBchF = @ptBchF
        --SET @tBchT = @ptBchT
        --SET @tWahF = @ptWahF
        --SET @tWahT = @ptWahT


        SET @tPdtF = @ptPdtF
        SET @tPdtT = @ptPdtT

        SET @ptDocDateF = CONVERT(VARCHAR(10),@ptDocDateF,121)
        SET @ptDocDateT = CONVERT(VARCHAR(10),@ptDocDateT,121)

        SET @FNResult= 0


        IF @nLngID = null
        BEGIN
            SET @nLngID = 1
        END	

        IF @tBchF = null
        BEGIN
            SET @tBchF = ''
        END
        IF @tBchT = null OR @tBchT = ''
        BEGIN
            SET @tBchT = @tBchF
        END

        --Branch
        IF @ptBchL = null
        BEGIN
            SET @ptBchL = ''
        END

        ------------------
        IF @tWahF = null
        BEGIN
            SET @tWahF = ''
        END 
        IF @tWahT = null OR @tWahT =''
        BEGIN
            SET @tWahT = @tWahF
        END 

        IF @tPdtF = null
        BEGIN
            SET @tPdtF = ''
        END 
        IF @tPdtT = null OR @tPdtT =''
        BEGIN
            SET @tPdtT = @tPdtF
        END 


        SET @tSql1 =   ' '
        --SET @tSql1 +=' WHERE 1=1 AND TFW.FNXthStaDocAct = 1 '
        SET @tSql1 +=' WHERE 1=1 AND TFW.FTXthStaDoc = 1 AND TFW.FTXthStaApv = 1 '
        IF @pnFilterType = '2'
        BEGIN
            IF (@ptBchL <> '' )
            BEGIN
                SET @tSql1 +=' AND TFW.FTBchCode IN (' + @ptBchL + ')'
            END	

        END

        --IF (@ptWahL <> '')
        --BEGIN
        --	SET @tSql1 +=' AND HD.FTXthWhFrm IN (' + @ptWahL + ')'
        --END
        IF (@ptWahF <> '' )
        BEGIN
            SET @tSql1 +=' AND TFW.FTXthWhFrm = ''' + @ptWahF + ''' '
        END

            IF (@ptWahT <> '')
        BEGIN
                SET @tSql1 +=' AND TFW.FTXthWhTo = ''' + @ptWahT + ''' '
        END

        IF (@tPdtF <> '' AND @tPdtT <> '')
        BEGIN
            SET @tSql1 +=' AND TFWDT.FTPdtCode BETWEEN ''' + @tPdtF + ''' AND ''' + @tPdtT + ''''
        END

        IF (@ptDocDateF <> '' AND @ptDocDateT <> '')
        BEGIN
            SET @tSql1 +=' AND CONVERT(VARCHAR(10),FDXthDocDate,121) BETWEEN ''' + @ptDocDateF + ''' AND ''' + @ptDocDateT + ''''
        END

    DELETE FROM TRPTPdtHisTnfWahTmp WITH (ROWLOCK) WHERE FTComName =  '' + @nComName + ''  AND FTRptCode = '' + @tRptCode + '' AND FTUsrSession = '' + @tUsrSession + ''
    SET @tSql = ' INSERT INTO TRPTPdtHisTnfWahTmp'
    SET @tSql +=' (FTComName,FTRptCode,FTUsrSession,'
    SET @tSql+='	FTBchCode, FTBchName, FTXthDocNo, FDXthDocDate, FTXthWhFrm,  FTWahNameFrm, FTXthWhTo,FTWahNameTo,  ';
    SET @tSql+='	 FTXthApvCode,  FTUsrName,FTPdtCode,FTXtdPdtName,  FTPunName,FTXtdBarCode,FCXtdQty ';
    SET @tSql +=' )'
    SET @tSql +=' SELECT '''+ @nComName + ''' AS FTComName,'''+ @tRptCode +''' AS FTRptCode, '''+ @tUsrSession +''' AS FTUsrSession,'       
    SET @tSql+='	FTBchCode, FTBchName, FTXthDocNo, FDXthDocDate, FTXthWhFrm,  FTWahNameFrm, FTXthWhTo,FTWahNameTo,  ';
    SET @tSql+='	 FTXthApvCode,  FTUsrName,FTPdtCode,FTXtdPdtName,  FTPunName,FTXtdBarCode,FCXtdQty ';
    SET @tSql +=' FROM'
    SET @tSql +=' ('			
    SET @tSql+='	SELECT TFW.FTBchCode, BCHL.FTBchName,  TFW.FTXthDocNo, TFW.FDXthDocDate, TFW.FTXthWhFrm,  WahLF.FTWahName AS FTWahNameFrm, TFW.FTXthWhTo, WahLT.FTWahName AS FTWahNameTo,  ';
    SET @tSql+='	  TFW.FTXthApvCode,  USRLAPV.FTUsrName,TFWDT.FTPdtCode, TFWDT.FTXtdPdtName,  TFWDT.FTPunName, TFWDT.FTXtdBarCode,TFWDT.FCXtdQty ';
    SET @tSql+='	  FROM [TCNTPdtTwxHD] TFW WITH(NOLOCK) ';
    SET @tSql+='	  LEFT JOIN TCNTPdtTwxDT TFWDT WITH(NOLOCK) ON TFW.FTXthDocNo = TFWDT.FTXthDocNo ';
    SET @tSql+='	 LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON TFW.FTBchCode = BCHL.FTBchCode
                                                    AND BCHL.FNLngID = ''' + CAST(@nLngID AS VARCHAR(10)) + '''';
    SET @tSql+='	 LEFT JOIN TCNMUser_L USRL WITH(NOLOCK) ON TFW.FTCreateBy = USRL.FTUsrCode
                                                AND USRL.FNLngID = ''' + CAST(@nLngID AS VARCHAR(10)) + '''';
    SET @tSql+='	   LEFT JOIN TCNMUser_L USRLAPV WITH(NOLOCK) ON TFW.FTXthApvCode = USRLAPV.FTUsrCode
                                                    AND USRLAPV.FNLngID = ''' + CAST(@nLngID AS VARCHAR(10)) + '''';
    SET @tSql+='	   LEFT JOIN TCNMWahouse_L WahLT WITH(NOLOCK) ON TFW.FTBchCode = WahLT.FTBchCode
                                                    AND TFW.FTXthWhTo = WahLT.FTWahCode
                                                    AND WahLT.FNLngID = ''' + CAST(@nLngID AS VARCHAR(10)) + '''';
    SET @tSql+='	   LEFT JOIN TCNMWahouse_L WahLF WITH(NOLOCK) ON TFW.FTBchCode = WahLF.FTBchCode
                                                    AND TFW.FTXthWhFrm = WahLF.FTWahCode
                                                    AND WahLF.FNLngID = ''' + CAST(@nLngID AS VARCHAR(10)) + '''';
            SET @tSql += @tSql1
            SET @tSql +=' ) TnfWah'
        EXECUTE(@tSql)
    END TRY
    BEGIN CATCH 
        SET @FNResult= -1
    END CATCH	

IF OBJECT_ID(N'TRPTPdtHisTnfWahTmp') IS NULL BEGIN
    CREATE TABLE [dbo].[TRPTPdtHisTnfWahTmp](
        [FTRptRowSeq] [bigint] IDENTITY(1,1) NOT NULL,
        [FNRowPartID] [bigint] NULL,
        [FTUsrSession] [varchar](255) NULL,
        [FTComName] [varchar](100) NULL,
        [FTRptCode] [varchar](100) NULL,
        [FTBchCode] [varchar](5) NULL,
        [FTBchName] [varchar](200) NULL,
        [FTXthDocNo] [varchar](20) NULL,
        [FDXthDocDate] [datetime] NULL,
        [FTXthWhFrm] [varchar](5) NULL,
        [FTWahNameFrm] [varchar](200) NULL,
        [FTXthWhTo] [varchar](5) NULL,
        [FTWahNameTo] [varchar](200) NULL,
        [FTXthApvCode] [varchar](5) NULL,
        [FTUsrName] [varchar](200) NULL,
        [FTPdtCode] [varchar](20) NULL,
        [FTXtdPdtName] [varchar](200) NULL,
        [FTPunName] [varchar](50) NULL,
        [FTXtdBarCode] [nchar](50) NULL,
        [FCXtdQty] [numeric](18, 4) NULL,
    CONSTRAINT [PK__TRPTPdtH__F671FB6B5A86F19A] PRIMARY KEY CLUSTERED 
    (
        [FTRptRowSeq] ASC
    )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
END
GO

/* ขยายฟิวส์จากเดิม */
IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNMPdtCatInfo' AND COLUMN_NAME = 'FTCatParent' AND CHARACTER_MAXIMUM_LENGTH = 10) BEGIN
	ALTER TABLE TCNMPdtCatInfo ALTER COLUMN FTCatParent VARCHAR(20) NULL
    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TCNMPdtCatInfo' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TCNMPdtCatInfo DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql
	ALTER TABLE TCNMPdtCatInfo ADD PRIMARY KEY(FTCatCode,FNCatLevel)
END
GO

/* ขยายฟิวส์จากเดิม */
IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNMWaHouse' AND COLUMN_NAME = 'FTWahStaType' AND CHARACTER_MAXIMUM_LENGTH = 1) BEGIN
	ALTER TABLE TCNMWaHouse ALTER COLUMN FTWahStaType VARCHAR(2) NULL
    DECLARE @tConstraint VARCHAR(30) = (select OBJECT_NAME(OBJECT_ID) AS NameofConstraint FROM sys.objects where OBJECT_NAME(parent_object_id)='TCNMWaHouse' and type_desc LIKE '%CONSTRAINT')
    DECLARE @tSql NVARCHAR(250) = 'ALTER TABLE TCNMWaHouse DROP CONSTRAINT '+@tConstraint
    EXECUTE sp_executesql @tSql
	ALTER TABLE TCNMWaHouse ADD PRIMARY KEY(FTBchCode,FTWahCode)
END
GO

/* รายงานกองยาน */
IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TRPTSalTwoFleetTmp') BEGIN
    DROP TABLE [dbo].[TRPTSalTwoFleetTmp]
END
GO

CREATE TABLE [dbo].[TRPTSalTwoFleetTmp](
	[FTBchCode] [varchar](5) NOT NULL,
	[FTBchName] [varchar](140) NULL,
	[FDXshDocDate] [datetime] NULL,
	[FTXshDocNo] [varchar](20) NOT NULL,
	[FTPdtCode] [varchar](20) NOT NULL,
	[FTXsdPdtName] [varchar](177) NULL,
	[FCXsdSalePrice] [numeric](18, 4) NULL,
	[FCXsdQtyAll] [numeric](18, 4) NULL,
	[FCXsdAmtB4DisChg] [numeric](18, 4) NULL,
	[FCXsdDis] [numeric](18, 4) NULL,
	[FCXsdNetAfHD] [numeric](18, 4) NULL,
	[FTUsrSession] [varchar](72) NOT NULL,
	[FTXshCstName] [varchar](255) NULL,
	[FTCarRegNo] [varchar](20) NULL,
	[FTCbaStaTax] [varchar](1) NULL,
	[FCXsdVat] [float] NULL
) ON [PRIMARY]
GO

/* รายงานสินค้าคงคลัง */
IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TRPTPdtStkBalTmp') BEGIN
    DROP TABLE [dbo].[TRPTPdtStkBalTmp]
END
GO

CREATE TABLE [dbo].[TRPTPdtStkBalTmp](
	[FTRptRowSeq] [bigint] IDENTITY(1,1) NOT NULL,
	[FNRowPartID] [bigint] NULL,
	[FTBchCode] [varchar](5) NULL,
	[FTWahCode] [varchar](5) NULL,
	[FTWahName] [varchar](255) NULL,
	[FTPdtCode] [varchar](20) NULL,
	[FTPdtName] [varchar](255) NULL,
	[FTPgpChainName] [varchar](255) NULL,
	[FCPdtCostAVGEX] [numeric](18, 4) NULL,
	[FCPdtCostTotal] [numeric](18, 4) NULL,
	[FCStkQty] [numeric](18, 4) NULL,
	[FDLastUpdOn] [datetime] NULL,
	[FTLastUpdBy] [varchar](20) NULL,
	[FDCreateOn] [datetime] NULL,
	[FTCreateBy] [varchar](20) NULL,
	[FTComName] [varchar](50) NULL,
	[FTRptCode] [varchar](50) NULL,
	[FTUsrSession] [varchar](255) NULL,
	[FDTmpTxnDate] [datetime] NOT NULL,
	[FTBchName] [varchar](100) NULL,
	[FCPdtCostStd] [numeric](18, 4) NULL,
	[FCPdtCostStdTotal] [numeric](18, 4) NULL,
PRIMARY KEY CLUSTERED 
(
	[FTRptRowSeq] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = OFF, ALLOW_PAGE_LOCKS = OFF, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY]
ALTER TABLE [dbo].[TRPTPdtStkBalTmp] ADD  DEFAULT (getdate()) FOR [FDTmpTxnDate]
GO

IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TSVMCar' AND COLUMN_NAME = 'FTCbaConSta') BEGIN
    ALTER TABLE TSVMCar ADD FTCbaConSta varchar(1)
END
GO

IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxHD' AND COLUMN_NAME = 'FCXshWpTax') BEGIN
	ALTER TABLE TPSTWhTaxHD ADD FCXshWpTax NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxHD' AND COLUMN_NAME = 'FCXshGrand') BEGIN
	ALTER TABLE TPSTWhTaxHD ADD FCXshGrand NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FTXsdBarCode') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FTXsdBarCode VARCHAR(25)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FTPunCode') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FTPunCode VARCHAR(5)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FTPunName') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FTPunName VARCHAR(50)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXsdFactor') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXsdFactor NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXsdQty') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXsdQty NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXsdQtyAll') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXsdQtyAll NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FTXsdVatType') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FTXsdVatType VARCHAR(1)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FTVatCode') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FTVatCode VARCHAR(5)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXsdVatRate') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXsdVatRate NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FTXsdSaleType') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FTXsdSaleType VARCHAR(1)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXsdSetPrice') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXsdSetPrice NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXsdVat') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXsdVat NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXsdVatable') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXsdVatable NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxDT' AND COLUMN_NAME = 'FCXshWpTax') BEGIN
	ALTER TABLE TPSTWhTaxDT ADD FCXshWpTax NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxHD' AND COLUMN_NAME = 'FCXshGrand') BEGIN
	ALTER TABLE TPSTWhTaxHD ADD FCXshGrand NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTWhTaxHD' AND COLUMN_NAME = 'FCXshGrand') BEGIN
	ALTER TABLE TPSTWhTaxHD ADD FCXshGrand NUMERIC(18, 4)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTPdtPrice4PDT' AND COLUMN_NAME = 'FTPgdRmk') BEGIN
	ALTER TABLE TCNTPdtPrice4PDT ADD FTPgdRmk VARCHAR(200)
END
GO
IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTSalDTDis' AND COLUMN_NAME = 'FTXddRmk') BEGIN
	ALTER TABLE TPSTSalDTDis ADD FTXddRmk VARCHAR(200)
END
GO

/* รายงาน - สินค้าคงคลังตามช่วงวัน */
IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TRPTTaxStockCardByDateTmp') BEGIN
    DROP TABLE [dbo].[TRPTTaxStockCardByDateTmp]
END
GO

CREATE TABLE [dbo].[TRPTTaxStockCardByDateTmp](
	[FTRptCode] [varchar](50) NULL,
	[FTUsrSession] [varchar](255) NULL,
	[FTBchCode] [varchar](50) NULL,
	[FTBchName] [varchar](200) NULL,
	[FTWahCode] [varchar](50) NULL,
	[FTWahName] [varchar](200) NULL,
	[FTPdtCode] [varchar](50) NULL,
	[FTPdtName] [varchar](200) NULL,
	[FCStkQtyBal] [varchar](20) NULL,
	[FCStkCostStd] [varchar](20) NULL
) ON [PRIMARY]
GO

/* เอกสารใบรับวางบิล */
    IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TACTPbHDDocRef') BEGIN
        DROP TABLE [dbo].[TACTPbHDDocRef]
    END
    GO
    CREATE TABLE [dbo].[TACTPbHDDocRef](
            [FTAgnCode] [varchar](10) NOT NULL,
            [FTBchCode] [varchar](20) NOT NULL,
            [FTXphDocNo] [varchar](20) NOT NULL,
            [FTXphRefDocNo] [varchar](20) NOT NULL,
            [FTXphRefType] [varchar](1) NOT NULL,
            [FTXphRefKey] [varchar](10) NULL,
            [FDXphRefDocDate] [datetime] NULL,
        CONSTRAINT [PK_TACTPbHDDocRef] PRIMARY KEY CLUSTERED 
        (
            [FTAgnCode] ASC,
            [FTBchCode] ASC,
            [FTXphDocNo] ASC,
            [FTXphRefDocNo] ASC,
            [FTXphRefType] ASC
        )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
        ) 
        GO
        EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสบริษัทย่อย ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TACTPbHDDocRef', @level2type=N'COLUMN',@level2name=N'FTAgnCode'
        GO
        EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสสาขา ที่สร้างเอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TACTPbHDDocRef', @level2type=N'COLUMN',@level2name=N'FTBchCode'
        GO
        EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสาร' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TACTPbHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXphDocNo'
        GO
        EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'เลขที่เอกสารอ้างอิง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TACTPbHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXphRefDocNo'
        GO
        EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ประเภทการอ้างอิงเอกสาร 1: อ้างอิงถึง(ภายใน),2:ถูกอ้างอิง(ภายใน),3: อ้างอิง ภายนอก' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TACTPbHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXphRefType'
        GO
        EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'กรณีType เดียวกันมีรายการมากกว่า 1 กลุ่ม /กำหนด Key เองได้' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TACTPbHDDocRef', @level2type=N'COLUMN',@level2name=N'FTXphRefKey'
        GO
        EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่เอกสารอ้างอิง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TACTPbHDDocRef', @level2type=N'COLUMN',@level2name=N'FDXphRefDocDate'
        GO

    /*-----------------*/

    IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TACTPbHDSpl') BEGIN
        DROP TABLE [dbo].[TACTPbHDSpl]
    END
    GO
    CREATE TABLE [dbo].[TACTPbHDSpl](
            [FTAgnCode] [varchar](10) NOT NULL,
            [FTBchCode] [varchar](5) NOT NULL,
            [FTXphDocNo] [varchar](20) NOT NULL,
            [FTXphDstPaid] [varchar](1) NULL,
            [FTXphCshOrCrd] [varchar](1) NULL,
            [FNXphCrTerm] [int] NULL,
            [FTXphCtrName] [varchar](100) NULL,
            [FDXphTnfDate] [datetime] NULL,
            [FTXphRefTnfID] [varchar](20) NULL,
            [FTXphRefVehID] [varchar](50) NULL,
            [FTXphRefInvNo] [varchar](30) NULL,
            [FTXphQtyAndTypeUnit] [varchar](30) NULL,
            [FNXphShipAdd] [bigint] NULL,
            [FNXphTaxAdd] [bigint] NULL,
        PRIMARY KEY CLUSTERED 
        (
            [FTAgnCode] ASC,
            [FTBchCode] ASC,
            [FTXphDocNo] ASC
        )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
        ) ON [PRIMARY]
        GO
    /*-----------------*/

    IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TACTPbHD') BEGIN
        DROP TABLE [dbo].[TACTPbHD]
    END
    GO
    CREATE TABLE [dbo].[TACTPbHD](
            [FTAgnCode] [varchar](10) NOT NULL,
            [FTBchCode] [varchar](5) NOT NULL,
            [FTXphDocNo] [varchar](20) NOT NULL,
            [FTXphDocType] [varchar](10) NULL,
            [FDXphDocDate] [datetime] NULL,
            [FTSplCode] [varchar](20) NULL,
            [FTCstCode] [varchar](20) NULL,
            [FTPrdCode] [varchar](5) NULL,
            [FDXphDueDate] [datetime] NULL,
            [FTXphCond] [varchar](100) NULL,
            [FCXphTotal] [float] NULL,
            [FCXphGndXC] [float] NULL,
            [FCXphGndXN] [float] NULL,
            [FCXphGndXX] [float] NULL,
            [FCXphGrand] [float] NULL,
            [FTXphGrandText] [varchar](200) NULL,
            [FTXphCtrName] [varchar](50) NULL,
            [FTXphRmk] [varchar](200) NULL,
            [FTUsrCode] [varchar](20) NULL,
            [FTDptCode] [varchar](5) NULL,
            [FTXphStaApv] [varchar](1) NULL,
            [FTXphApvCode] [varchar](20) NULL,
            [FTXphStaDoc] [varchar](1) NULL,
            [FTXphStaPaid] [varchar](1) NULL,
            [FNXphStaDocAct] [int] NULL,
            [FNXphStaRef] [int] NULL,
            [FNXphDocPrint] [bigint] NULL,
            [FDLastUpdOn] [datetime] NULL,
            [FTLastUpdBy] [varchar](20) NULL,
            [FDCreateOn] [datetime] NULL,
            [FTCreateBy] [varchar](20) NULL,
        PRIMARY KEY CLUSTERED 
        (
            [FTAgnCode] ASC,
            [FTBchCode] ASC,
            [FTXphDocNo] ASC
        )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
        ) ON [PRIMARY]
        GO
    /*-----------------*/

    IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TACTPbDT') BEGIN
        DROP TABLE [dbo].[TACTPbDT]
    END
    CREATE TABLE [dbo].[TACTPbDT](
            [FTAgnCode] [varchar](10) NOT NULL,
            [FTBchCode] [varchar](5) NOT NULL,
            [FTXphDocNo] [varchar](20) NOT NULL,
            [FNXpdSeqNo] [bigint] NOT NULL,
            [FTXpdRefDocNo] [varchar](20) NOT NULL,
            [FTXpdRefDocType] [varchar](10) NULL,
            [FDXpdRefDocDate] [datetime] NULL,
            [FTSplCode] [varchar](20) NULL,
            [FTCstCode] [varchar](20) NULL,
            [FTXpdStaInvB4] [varchar](1) NULL,
            [FTXpdRefInvEx] [varchar](20) NULL,
            [FDXpdRefInvExDate] [datetime] NULL,
            [FCXpdInvLeft] [float] NULL,
            [FCXpdInvPaid] [float] NULL,
            [FCXpdInvRem] [float] NULL,
            [FDXpdDueDate] [datetime] NULL,
            [FTXpdRmk] [varchar](200) NULL,
            [FDLastUpdOn] [datetime] NULL,
            [FTLastUpdBy] [varchar](20) NULL,
            [FDCreateOn] [datetime] NULL,
            [FTCreateBy] [varchar](20) NULL,
        PRIMARY KEY CLUSTERED 
        (
            [FTAgnCode] ASC,
            [FTBchCode] ASC,
            [FTXphDocNo] ASC,
            [FNXpdSeqNo] ASC,
            [FTXpdRefDocNo] ASC
        )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
        ) ON [PRIMARY]
        GO

-------------- Update Table  --------------
IF OBJECT_ID(N'TAPTPcHDDocRef') IS NULL BEGIN
	CREATE TABLE [dbo].[TAPTPcHDDocRef](
        [FTAgnCode] [varchar](10) NOT NULL,
        [FTBchCode] [varchar](20) NOT NULL,
        [FTXshDocNo] [varchar](20) NOT NULL,
        [FTXshRefDocNo] [varchar](20) NOT NULL,
        [FTXshRefType] [varchar](1) NOT NULL,
        [FTXshRefKey] [varchar](10) NULL,
        [FDXshRefDocDate] [datetime] NULL,
    PRIMARY KEY CLUSTERED 
    (
        [FTAgnCode] ASC,
        [FTBchCode] ASC,
        [FTXshDocNo] ASC,
        [FTXshRefDocNo] ASC,
        [FTXshRefType] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTShiftSKeyRcv' AND COLUMN_NAME = 'FTRcvCode') BEGIN
	ALTER TABLE TPSTShiftSKeyRcv ALTER COLUMN FTRcvCode VARCHAR(5) NOT NULL
END
GO

IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TPSTShiftSSumRcv' AND COLUMN_NAME = 'FTRcvCode') BEGIN
	ALTER TABLE TPSTShiftSSumRcv ALTER COLUMN FTRcvCode VARCHAR(5) NOT NULL
END
GO

/* ขยายฟิวส์จากเดิม */
IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TCNTPdtTwiHDRef' AND COLUMN_NAME = 'FTViaCode' AND CHARACTER_MAXIMUM_LENGTH = 5) BEGIN
	ALTER TABLE TCNTPdtTwiHDRef ALTER COLUMN FTViaCode VARCHAR(20) NULL
END
GO

/* ขยายฟิวส์จากเดิม */
IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TSVMPos' AND COLUMN_NAME = 'FTSpsApvCode' AND CHARACTER_MAXIMUM_LENGTH = 5) BEGIN
	ALTER TABLE TSVMPos ALTER COLUMN FTSpsApvCode VARCHAR(20) NULL
END
GO

IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TSVTJob3ChkHD' AND COLUMN_NAME = 'FTXshCarFuel') BEGIN
	ALTER TABLE TSVTJob3ChkHD ADD FTXshCarFuel VARCHAR(1) NULL
END
GO

/* รายงานอายุลูกหนี้ */
IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TRPTDebtorAgingTmp') BEGIN
    DROP TABLE [dbo].[TRPTDebtorAgingTmp]
END
GO
CREATE TABLE [dbo].[TRPTDebtorAgingTmp](
	[FNXshRowPART] [int] NULL,
	[FTCstCode] [varchar](50) NULL,
	[FTCstName] [varchar](255) NULL,
	[FCCstCrLimit] [numeric](18, 4) NULL,
	[FDXshDueDate] [datetime] NULL,
	[FTXshDocNo] [varchar](50) NULL,
	[FDXshDocDate] [datetime] NULL,
	[FTXshRefInt] [varchar](50) NULL,
	[FNCstCrTerm] [varchar](10) NULL,
	[FDXshBFDue60U] [numeric](18, 4) NULL,
	[FDXshBFDue31T60] [numeric](18, 4) NULL,
	[FDXshBFDue0T30] [numeric](18, 4) NULL,
	[FDXshPastDue1] [numeric](18, 4) NULL,
	[FDXshPastDue2T7] [numeric](18, 4) NULL,
	[FDXshPastDue8T15] [numeric](18, 4) NULL,
	[FDXshPastDue16T30] [numeric](18, 4) NULL,
	[FDXshPastDue31T60] [numeric](18, 4) NULL,
	[FDXshPastDue61T90] [numeric](18, 4) NULL,
	[FDXshPastDue90U] [numeric](18, 4) NULL,
	[FCXshLeft] [numeric](18, 4) NULL,
	[FTUsrSession] [varchar](100) NULL
) ON [PRIMARY]
GO

/* รายงานอายุเจ้าหนี้ */
IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TRPTPurCreditorAgeTmp') BEGIN
    DROP TABLE [dbo].[TRPTPurCreditorAgeTmp]
END
GO
CREATE TABLE [dbo].[TRPTPurCreditorAgeTmp](
	[FTRptRowSeq] [bigint] IDENTITY(1,1) NOT NULL,
	[FTUsrSession] [varchar](255) NOT NULL,
	[FTComName] [varchar](50) NOT NULL,
	[FTRptCode] [varchar](50) NOT NULL,
	[FNRowPartID] [bigint] NULL,
	[FTSplCode] [varchar](20) NULL,
	[FTSplName] [varchar](200) NULL,
	[FCSplCrLimit] [numeric](18, 4) NULL,
	[FDXphDueDate] [datetime] NULL,
	[FTXphDocNo] [varchar](20) NULL,
	[FTXphRefInt] [varchar](50) NULL,
	[FDXphDocDate] [datetime] NULL,
	[FNXphCrTerm] [bigint] NULL,
	[FCXphBFDue60] [numeric](18, 4) NULL,
	[FCXphBFDue31And60] [numeric](18, 4) NULL,
	[FCXphBFDue0And30] [numeric](18, 4) NULL,
	[FCXphOVDue1] [numeric](18, 4) NULL,
	[FCXphOVDue2And7] [numeric](18, 4) NULL,
	[FCXphOVDue8And15] [numeric](18, 4) NULL,
	[FCXphOVDue16And30] [numeric](18, 4) NULL,
	[FCXphOVDue31And60] [numeric](18, 4) NULL,
	[FCXphOVDue61And90] [numeric](18, 4) NULL,
	[FCXphOVDue90] [numeric](18, 4) NULL,
	[FCXshLeft] [numeric](18, 4) NULL
 ) ON [PRIMARY]
GO

/* รายงานสรุปการจ่ายชำระประจำวัน */
IF EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TRPTxHisSplPayment') BEGIN
    DROP TABLE [dbo].[TRPTxHisSplPayment]
END
GO
CREATE TABLE [dbo].[TRPTxHisSplPayment](
	[FTUsrSession] [varchar](255) NOT NULL,
	[FDXphLastPay] [datetime] NULL,
	[FTSplCode] [varchar](255) NULL,
	[FTSplName] [varchar](255) NULL,
	[FCXphGrand] [numeric](18, 4) NULL,
	[FCXphPaid] [numeric](18, 4) NULL,
	[FCXphLeft] [numeric](18, 4) NULL
) ON [PRIMARY]
GO

/* ขยายฟิวส์จากเดิม */
IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'TLKMLMSShop' AND COLUMN_NAME = 'FTApiLoginPwd' AND CHARACTER_MAXIMUM_LENGTH = 20) BEGIN
 ALTER TABLE TLKMLMSShop ALTER COLUMN FTApiLoginPwd VARCHAR(80) NULL
END
GO