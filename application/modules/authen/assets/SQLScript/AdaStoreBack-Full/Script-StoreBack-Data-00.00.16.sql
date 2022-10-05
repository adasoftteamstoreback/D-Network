--################## CREATE TABLE FOR SCRIPT ##################
	IF OBJECT_ID(N'TCNTUpgradeHisTmp') IS NULL BEGIN
		CREATE TABLE [dbo].[TCNTUpgradeHisTmp] (
					[FTUphVersion] varchar(10) NOT NULL ,
					[FDCreateOn] datetime NULL ,
					[FTUphRemark] varchar(MAX) NULL ,
					[FTCreateBy] varchar(50) NULL 
			);
			ALTER TABLE [dbo].[TCNTUpgradeHisTmp] ADD PRIMARY KEY ([FTUphVersion]);
		END
	GO
--#############################################################
--Version ไฟล์ กับ Version บรรทัดที่ 15 ต้องเท่ากันเสมอ !! 

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.01') BEGIN
	DELETE FROM [dbo].[TSysMenuList_L]
	DELETE FROM [dbo].[TSysMenuList]
	DELETE FROM [dbo].[TSysMenuGrpModule_L]
	DELETE FROM [dbo].[TSysMenuGrpModule]
	DELETE FROM [dbo].[TSysMenuGrp_L]
	DELETE FROM [dbo].[TSysMenuGrp]
	DELETE FROM [dbo].[TSysMenuAlbAct]

	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ABI', 1, N'1', N'AB')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ABS', 2, N'1', N'AB')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'AD00001', 1, N'1', N'AD')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'AP', 1, N'1', N'AP')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'APD', 2, N'1', N'AP')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ARC', 4, N'1', N'AR')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ARD', 2, N'1', N'AR')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ARL', 5, N'0', N'AR')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ARM', 3, N'1', N'AR')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ARS', 1, N'1', N'AR')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'AST', 5, N'1', N'IC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'CAR', 10, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'CDV', 13, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'DOCSKU', 2, N'1', N'SKU')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'FDC', 2, N'1', N'FN')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'FNS', 1, N'1', N'FN')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'FNT', 3, N'1', N'FN')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ICB', 4, N'1', N'IC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'ICV', 3, N'1', N'IC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'MAN', 3, N'1', N'AP')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'MON', 4, N'1', N'AP')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'NEW', 1, N'1', N'NEW')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'PDM', 1, N'1', N'IC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'PFH', 12, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'QAS', 9, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SCR', 11, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SCT', 6, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SET', 1, N'1', N'ST')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SHP', 15, N'0', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SKU', 1, N'1', N'SKU')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SLK', 14, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SPD', 5, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SPS', 4, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SRC', 8, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SSP', 7, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'STO', 2, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SUR', 3, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SVCAR', 1, N'1', N'SVC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SVD', 16, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SVDOC', 2, N'1', N'SVC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SVMO', 3, N'1', N'SVC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'SYS', 1, N'1', N'MAS')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'TXO', 2, N'1', N'IC')
	INSERT [dbo].[TSysMenuGrp] ([FTGmnCode], [FNGmnShwSeq], [FTGmnStaUse], [FTGmnModCode]) VALUES (N'WAHCHK', 6, N'1', N'IC')

	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ABI', 1, N'Interface', N'AB')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ABI', 2, N'Interface KADS', N'AB')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ABS', 1, N'ตั้งค่า ', N'AB')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'AD00001', 1, N'Audit', N'AD')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'AD00001', 2, N'Audit', N'AD')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'AP', 1, N'ผู้จำหน่าย', N'AP')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'AP', 2, N'Supplier', N'AP')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'APD', 1, N'เอกสาร', N'AP')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'APD', 2, N'Document', N'AP')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARC', 1, N'ศูนย์อาหาร', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARC', 2, N'Sale Check', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARD', 1, N'เอกสาร', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARD', 2, N'Document', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARL', 1, N'ตู้ฝาก', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARL', 2, N'Locker', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARM', 1, N'มอนิเตอร์', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARS', 1, N'ลูกค้า', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ARS', 2, N'Sale', N'AR')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'AST', 1, N'ตรวจนับ', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'AST', 2, N'Adjust Stock', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'CAR', 1, N'ข้อมูลรถ', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'CDV', 1, N'อุปกรณ์เชื่อมต่อ', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'DOCSKU', 1, N'เอกสาร', N'SKU')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'DOCSKU', 2, N'Document', N'SKU')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'FDC', 1, N'เอกสาร', N'FN')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'FDC', 2, N'Document', N'FN')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'FNS', 1, N'สถานะ', N'FN')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'FNS', 2, N'Status', N'FN')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'FNT', 1, N'บัตรเงินสด', N'FN')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'FNT', 2, N'Payment Type', N'FN')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ICB', 1, N'โอนสาขา', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ICB', 2, N'Transfer Branch', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ICV', 1, N'ตู้ขายสินค้า', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'ICV', 2, N'Vending', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'MAN', 1, N'จัดการ', N'AP')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'MON', 1, N'มอนิเตอร์', N'AP')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'PDM', 1, N'คลังสินค้า', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'PDM', 2, N'Product movement', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'PFH', 1, N'สินค้าแฟชั่น', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'QAS', 1, N'ข้อมูลชุดคำถาม', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SCR', 1, N'ข้อมูลบริษัทขนส่ง', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SCR', 2, N'Transport', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SCT', 1, N'ข้อมูลลูกค้า', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SCT', 2, N'System Customer', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SET', 1, N'ตั้งค่าระบบ', N'ST')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SET', 2, N'Setting config', N'ST')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SHP', 1, N'ข้อมูลร้านค้า', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SHP', 2, N'System Shop', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SKU', 1, N'สินค้า', N'SKU')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SKU', 2, N'Product', N'SKU')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SLK', 1, N'ข้อมูลตู้ฝาก', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SLK', 2, N'Information Locker', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SPD', 1, N'ข้อมูลสินค้า', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SPD', 2, N'System Product', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SPS', 1, N'ข้อมูลจุดขาย', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SPS', 2, N'System Pos', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SRC', 1, N'ข้อมูลการชำระ', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SRC', 2, N'System Recive', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SSP', 1, N'ข้อมูลผู้จำหน่าย', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SSP', 2, N'System Supplier', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'STO', 1, N'ข้อมูลสาขา', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'STO', 2, N'System Branch', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SUR', 1, N'ข้อมูลผู้ใช้', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SUR', 2, N'System User', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SVCAR', 1, N'รถ', N'SVC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SVCAR', 2, N'Car', N'SVC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SVD', 1, N'ข้อมูลตู้ขาย', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SVD', 2, N'System Vending', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SVDOC', 1, N'เอกสาร', N'SVC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SVMO', 1, N'มอนิเตอร์', N'SVC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SVMO', 2, N'Monitor', N'SVC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SYS', 1, N'ข้อมูลระบบ', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'SYS', 2, N'System Info', N'MAS')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'TXO', 1, N'โอนคลัง', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'TXO', 2, N'Document', N'IC')
	INSERT [dbo].[TSysMenuGrp_L] ([FTGmnCode], [FNLngID], [FTGmnName], [FTGmnSystem]) VALUES (N'WAHCHK', 1, N'ตรวจสอบ', N'IC')

	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'AB', 12, N'1', N'/application/modules/common/assets/images/iconsmenu/adalink.png', N' ')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'AD', 10, N'1', N'/application/modules/common/assets/images/iconsmenu/tool-box.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'AP', 6, N'1', N'/application/modules/common/assets/images/iconsmenu/ap.png', N' ')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'AR', 4, N'1', N'/application/modules/common/assets/images/iconsmenu/ar.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'FAV', 1, N'1', N'/application/modules/common/assets/images/iconsmenu/fav.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'FN', 8, N'1', N'/application/modules/common/assets/images/iconsmenu/fn.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'IC', 5, N'1', N'/application/modules/common/assets/images/iconsmenu/inventory.png', N' ')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'MAS', 2, N'1', N'/application/modules/common/assets/images/iconsmenu/master.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'NEW', 9, N'1', N'/application/modules/common/assets/images/iconsmenu/new.png', NULL)
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'RPT', 9, N'1', N'/application/modules/common/assets/images/iconsmenu/rpt.png', N' ')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'SKU', 3, N'1', N'/application/modules/common/assets/images/iconsmenu/sku.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'ST', 11, N'1', N'/application/modules/common/assets/images/iconsmenu/set.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'SVC', 7, N'1', N'/application/modules/common/assets/images/iconsmenu/sv.png', N'')
	INSERT [dbo].[TSysMenuGrpModule] ([FTGmnModCode], [FNGmnModShwSeq], [FTGmnModStaUse], [FTGmmModPathIcon], [FTGmmModColorBtn]) VALUES (N'TK', 13, N'0', N'/application/modules/common/assets/images/iconsmenu/tk.png', N' ')

	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AR', N'Consignment', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'FN', N'Finance', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'IC', N'Inventory', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AB', N'Link', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'MAS', N'Master', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'SKU', N'Products', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AP', N'Purchase', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'RPT', N'Report', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'ST', N'Setting', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'TK', N'Ticket', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AD', N'Tools', N'2')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AR', N'การขาย', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'FN', N'การเงิน', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AB', N'การเชื่อมต่อ', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AP', N'การซื้อ', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'MAS', N'ข้อมูลหลัก', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'AD', N'เครื่องมือ', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'NEW', N'ตรวจสอบและแจ้งเตือน', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'ST', N'ตั้งค่าระบบ', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'TK', N'ระบบตั๋ว', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'RPT', N'รายงาน', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'SVC', N'ศูนย์บริการ', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'SKU', N'สินค้า', N'1')
	INSERT [dbo].[TSysMenuGrpModule_L] ([FTGmnModCode], [FTGmnModName], [FNLngID]) VALUES (N'IC', N'สินค้าคงคลัง', N'1')

	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ABI', N'ABI', N'ABI002', N'SB-ABABI002', 1, N'interfaceimport/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AB', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ABI', N'ABI', N'ABI003', N'SB-ABABI003', 3, N'interfaceexport/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'T', N'1', N'', N'AB', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ABI', N'ABI', N'ABI004', N'SB-ABABI004', 11, N'interfacehistory/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AB', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ABI', N'ABI', N'ABI005', N'SB-ABI005', 12, N'dasBCM', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AB', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ABI', N'ABI', N'ABI006', N'SB-ABI006', 2, N'masChkImport/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AB', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ABS', N'ABS', N'ABI001', N'SB-ABABI001', 1, N'connectionsetting/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AB', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AD00001', N'AD00001', N'AUD001', N'SB-ADAUD001', 1, N'Audit', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AD', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AD00001', N'AD00001', N'AUD002', N'SB-ADAUD002', 2, N'AuditMovedata', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AD', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AD00001', N'AD00001', N'AUD003', N'SB-ADAUD003', 3, N'Audit_newpage', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AD', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AP', N'AP', N'AP0001', N'SB-APAP0001', 1, N'supplier/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AP', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AP', N'AP', N'AP0005', N'SB-APAP0005', 2, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AP', N'AP', N'AP0006', N'SB-APAP0006', 3, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AP', N'AP', N'AP0007', N'SB-APAP0007', 4, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0002', N'SB-APAP0002', 8, N'dcmPI/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'AP', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0003', N'SB-APAP0003', 7, N'creditNote/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AP', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0004', N'SB-AP0015', 3, N'docPO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0008', N'SB-APAP0008', 5, N'docInvoice/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0009', N'SB-AP0009', 2, N'docPrs/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0010', N'SB-AP0010', 4, N'docDO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0011', N'SB-AP0011', 1, N'docPreOrderb/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AP0014', N'SB-AP0014', 6, N'docPN/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'APD0001', N'SB-APAPD0001', 1, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'APD0002', N'SB-APAPD0002', 3, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'APD0003', N'SB-APAPD0003', 6, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'APD0004', N'SB-APAPD0004', 7, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'AR0005', N'SB-AR0005', 10, N'docClaim/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'APD', N'APD', N'FDC003', N'SB-FDC003', 9, N'docPX/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARC', N'ARC', N'ARD005', N'SB-ARARD005', 1, N'dcmTXFC/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AR', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'AR0001', N'SB-AR0001', 9, N'docWhTax', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'AR0002', N'SB-AR0002', 4, N'dcmDPS/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'AR0003', N'SB-AR0003', 1, N'docQuotation/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'AR0004', N'SB-AR0004', 3, N'docBKO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARD002', N'SB-ARARD002', 7, N'dcmReprintEJ/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AR', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARD004', N'SB-ARARD004', 5, N'docPTU/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARD006', N'SB-ARARD006', 1, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARD007', N'SB-ARARD007', 3, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARD008', N'SB-ARARD008', 4, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARD009', N'SB-ARARD009', 6, N'dcmSOSTD/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARS003', N'SB-ARARS003', 8, N'dcmTXIN/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARS011', N'SB-ARARS011', 6, N'docABB/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'ARS012', N'SB-ARS012', 10, N'docDBN/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', NULL)
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'M00103', N'SB-ARARD001', 2, N'dcmSO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'TXO015', N'SB-ICTXO015', 5, N'docTXOWithdraw/0/1', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARD', N'ARD', N'TXO017', N'SB-ICTXO017', 11, N'docTXOWithdraw/0/2', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARL', N'ARL', N'ARC002', N'SB-ARARC002', 1, N'salBookingLocker/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AR', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARM', N'ARM', N'ARS001', N'SB-ARARS001', 1, N'dashboardsale/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARM', N'ARM', N'ARS004', N'SB-ARARS004', 2, N'salemonitor/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARM', N'ARM', N'MON003', N'SB-MON003', 3, N'monDelay/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARS', N'ARS', N'ARC001', N'SB-ARARC001', 5, N'dcmCheckSO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AR', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARS', N'ARS', N'ARS002', N'SB-ARARS002', 1, N'customer/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AR', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARS', N'ARS', N'ARS005', N'SB-ARARS005', 6, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARS', N'ARS', N'ARS006', N'SB-ARARS006', 9, N'customerlicense/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'AR', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARS', N'ARS', N'ARS007', N'SB-ARARS007', 10, N'HisBuyLicense', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AR', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARS', N'ARS', N'ARS008', N'SB-ARARS008', 11, N'ApproveCst', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', NULL, N'AR', NULL)
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ARS', N'ARS', N'ARS009', N'SB-ARARS009', 12, N'ApproveLic', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', NULL, N'AR', NULL)
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AST', N'AST', N'AST001', N'SB-ICAST001', 1, N'dcmAST/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AST', N'AST', N'AST003', N'SB-ICAST003', 2, N'adjStkSub/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'AST', N'AST', N'AST004', N'SB-ICAST004', 3, N'docSM/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00117', N'SB-M00117', 3, N'masCAIView/0/0/1', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00118', N'SB-M00118', 4, N'masCAIView/0/0/2', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00119', N'SB-M00119', 5, N'masCAIView/0/0/3', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00120', N'SB-M00120', 6, N'masCAIView/0/0/4', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00121', N'SB-M00121', 7, N'masCAIView/0/0/5', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00122', N'SB-M00122', 8, N'masCAIView/0/0/6', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00123', N'SB-M00123', 9, N'masCAIView/0/0/7', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'CAR', N'CAR', N'M00124', N'SB-M00124', 10, N'masCAIView/0/0/8', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'DOCSKU', N'DOCSKU', N'ABI001', N'SB-ABI001', 5, N'connectionsetting/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'SKU', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'DOCSKU', N'DOCSKU', N'SKU003', N'SB-SKUSKU003', 2, N'dcmSPA/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'SKU', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'DOCSKU', N'DOCSKU', N'SKU004', N'SB-SKUSKU004', 3, N'promotion/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'SKU', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'DOCSKU', N'DOCSKU', N'SKU005', N'SB-SKUSKU005', 4, N'ADJPL/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'SKU', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'DOCSKU', N'DOCSKU', N'SKU007', N'SB-SKUSKU007', 1, N'docADCCost/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SKU', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FDC', N'FDC', N'ARD003', N'SB-ARARD003', 1, N'dcmRDH/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FDC', N'FDC', N'ARD05', N'SB-ARARD05', 1, N'dcmRDH/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'FN', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FDC', N'FDC', N'FDC001', N'SB-FNFDC001', 3, N'deposit/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FDC', N'FDC', N'FNT002', N'SB-FNFNT002', 2, N'dcmCouponSetup/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNS', N'FNS', N'FNS001', N'SB-FNFNS001', 1, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'FN', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC002', N'SB-FNFDC002', 4, N'cardShiftNewCard/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC004', N'SB-FNFDC004', 6, N'cardShiftReturn/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC005', N'SB-FNFDC005', 7, N'cardShiftTopUp/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC006', N'SB-FNFDC006', 8, N'cardShiftRefund/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC007', N'SB-FNFDC007', 9, N'cardShiftStatus/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC008', N'SB-FNFDC008', 10, N'cardShiftChange/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC009', N'SB-FNFDC009', 11, N'cardmngdata/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FDC010', N'SB-FNFDC010', 5, N'cardShiftOut/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'FN', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FNT001', N'SB-FNFNT001', 1, N'card/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'FN', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FNT003', N'SB-FNFNT003', 2, N'docPTU/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'FN', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'FNT', N'FNT', N'FNT004', N'SB-FNFNT004', 2, N'cardhistory/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'FN', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICB', N'ICB', N'PDM002', N'SB-ICPDM002', 4, N'TBX/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICB', N'ICB', N'TBD001', N'SB-TBD001', 1, N'docTRB/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICB', N'ICB', N'TXO007', N'SB-ICTXO007', 2, N'docTransferBchOut/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICB', N'ICB', N'TXO008', N'SB-ICTXO008', 3, N'docTBI/0/0/5', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICB', N'ICB', N'TXO009', N'SB-ICTXO009', 5, N'docTBI/0/0/1', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICB', N'ICB', N'TXO011', N'SB-ICTXO011', 1, N'PO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICV', N'ICV', N'AST002', N'SB-ICAST002', 5, N'ADJSTKVD/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICV', N'ICV', N'AST005', N'SB-ICAST005', 1, N'TWO/0/0/4', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICV', N'ICV', N'AST006', N'SB-ICAST006', 2, N'TWI/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICV', N'ICV', N'TXO002', N'SB-ICTXO002', 3, N'TWXVD/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICV', N'ICV', N'TXO012', N'SB-ICTXO012', 4, N'docTVO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICV', N'ICV', N'TXO013', N'SB-ICTXO013', 6, N'docRVDRefillPDTVD/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'ICV', N'ICV', N'TXO014', N'SB-ICTXO014', 7, N'dcmRS/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'MAN', N'MAN', N'AP0012', N'SB-AP0012', 1, N'docMngDocPreOrdB/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'MAN', N'MAN', N'AP0013', N'SB-AP0013', 2, N'docMnpDocPO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', NULL, N'AP', NULL)
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'MON', N'MON', N'MON001', N'SB-MON001', 1, N'monSPL/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'AP', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'NEW', N'NEW', N'NEW001', N'SB-NEW001', 1, N'news/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'NEW', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'NEW', N'NEW', N'NEW002', N'SB-NEW002', 2, N'mntDocSta/2', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'NEW', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'NEW', N'NEW', N'NEW003', N'SB-NEW003', 3, N'mntNewSta/2', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'NEW', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PDM', N'PDM', N'MON002', N'SB-MON002', 3, N'monPAP/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PDM', N'PDM', N'PDM003', N'SB-ICPDM003', 3, N'movement/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PDM', N'PDM', N'PDM004', N'SB-ICPDM004', 1, N'mmtINV/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', NULL, N'IC', NULL)
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PFH', N'PFH', N'PFH001', N'SB-MASPFH001', 1, N'fashiondepart/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PFH', N'PFH', N'PFH002', N'SB-MASPFH002', 2, N'masPDTClass/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PFH', N'PFH', N'PFH003', N'SB-MASPFH003', 3, N'masPDTSubClass/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PFH', N'PFH', N'PFH004', N'SB-MASPFH004', 8, N'masPDTSeason/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PFH', N'PFH', N'PFH005', N'SB-MASPFH005', 7, N'masPDTFabric/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PFH', N'PFH', N'PFH006', N'SB-MASPFH006', 4, N'masPDTGroup/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'PFH', N'PFH', N'PFH007', N'SB-MASPFH007', 5, N'masPDTComLines/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'QAS', N'QAS', N'M00114', N'SB-M00114', 1, N'masQGPView/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'QAS', N'QAS', N'M00115', N'SB-M00115', 2, N'masQSGView/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'QAS', N'QAS', N'M00116', N'SB-M00116', 3, N'masQAHView/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'RPT', N'RPT', N'RPT001', N'SB-GRPRPT001', 1, N'rptReport/001/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'RPT', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'RPT', N'RPT', N'RPT002', N'SB-GRPRPT002', 2, N'rptReport/002/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'RPT', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'RPT', N'RPT', N'RPT004', N'SB-GRPRPT004', 4, N'rptReport/004/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'RPT', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'RPT', N'RPT', N'RPT005', N'SB-GRPRPT005', 5, N'rptReport/005/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'RPT', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'RPT', N'RPT', N'RPT006', N'SB-GRPRPT006', 5, N'rptReport/006/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'RPT', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'RPT', N'RPT', N'RPT007', N'SB-GRPRPT007', 8, N'rptReport/007/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'RPT', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCR', N'SCR', N'SCR001', N'SB-MASSCR001', 1, N'courier/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCR', N'SCR', N'SCR002', N'SB-MASSCR002', 2, N'courierMan/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCR', N'SCR', N'SCR003', N'SB-MASSCR003', 3, N'courierGrp/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCR', N'SCR', N'SCR004', N'SB-MASSCR004', 4, N'courierType/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCR', N'SCR', N'SCR005', N'SB-MASSCR005', 5, N'shipvia/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCT', N'SCT', N'SCT001', N'SB-MASSCT001', 1, N'customerGroup/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCT', N'SCT', N'SCT002', N'SB-MASSCT002', 2, N'customerType/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SCT', N'SCT', N'SCT003', N'SB-MASSCT003', 3, N'customerLevel/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SET', N'SET', N'SET001', N'SB-STSET001', 1, N'SettingConfig/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'ST', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SET', N'SET', N'SET002', N'SB-STSET002', 2, N'discountpolicy/0/0', 0, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'ST', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SET', N'SET', N'SET003', N'SB-STSET003', 4, N'CompSettingCon/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'ST', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SET', N'SET', N'SET004', N'SB-STSET004', 4, N'Server/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'ST', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SET', N'SET', N'SET005', N'SB-STSET005', 3, N'settingconperiod/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'ST', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SET', N'SET', N'SUR005', N'SB-MASSUR005', 5, N'funcSetting/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'ST', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SHP', N'SHP', N'STO003', N'SB-MASSTO003', 1, N'shop/0/0/POS', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SKU', N'SKU', N'ABI001', N'SB-ABI001', 5, N'connectionsetting/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'SKU', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SKU', N'SKU', N'SKU001', N'SB-SKUSKU001', 1, N'product/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'SKU', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SKU', N'SKU', N'SKU002', N'SB-SKUSKU002', 2, N'dcmPriRentLocker/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'SKU', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SKU', N'SKU', N'SKU006', N'SB-SKUSKU006', 3, N'dasPDTCheckProductPrice/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SKU', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SLK', N'SLK', N'SLK001', N'SB-MASSLK001', 1, N'', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SLK', N'SLK', N'SLK002', N'SB-MASSLK002', 2, N'shop/0/0/LOCKER', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'M00125', N'SB-M00125', 16, N'maslot/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD001', N'SB-MASSPD001', 1, N'pdtunit/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD002', N'SB-MASSPD002', 2, N'pdtpricegroup/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD003', N'SB-MASSPD003', 3, N'pdtgroup/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD004', N'SB-MASSPD004', 4, N'pdttype/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD005', N'SB-MASSPD005', 5, N'pdtmodel/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD006', N'SB-MASSPD006', 6, N'pdtcolor/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD007', N'SB-MASSPD007', 7, N'pdtbrand/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD008', N'SB-MASSPD008', 8, N'pdtsize/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD009', N'SB-MASSPD009', 9, N'pdtlocation/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD010', N'SB-MASSPD010', 10, N'pdtTouchGroup/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD011', N'SB-M00127', 11, N'masPdtCat/0/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD012', N'SB-M00128', 12, N'masPdtCat/0/0/1', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD013', N'SB-M00129', 13, N'masPdtCat/0/0/2', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD014', N'SB-M00130', 14, N'masPdtCat/0/0/3', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPD', N'SPD', N'SPD015', N'SB-M00131', 15, N'masPdtCat/0/0/4', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'CDV001', N'SB-CDV001', 3, N'setprinter/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'M00112', N'SB-M00112', 1, N'masMSGView/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'M00113', N'SB-M00113', 7, N'masCLDView/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'SPS001', N'SB-MASSPS001', 6, N'salemachine/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'SPS002', N'SB-MASSPS002', 3, N'slipMessage/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'SPS003', N'SB-MASSPS003', 5, N'funcSetting/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'SPS004', N'SB-MASSPS004', 2, N'adMessage/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'SPS005', N'SB-MASSPS005', 5, N'posreg/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'SPS006', N'SB-MASSPS006', 6, N'chanel/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', NULL, N'MAS', NULL)
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SPS', N'SPS', N'SRC006', N'SB-MASSRC006', 5, N'posEdc/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC001', N'SB-MASSRC001', 1, N'recive/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC002', N'SB-MASSRC002', 2, N'coupontype/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC003', N'SB-MASSRC003', 3, N'cardtype/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC004', N'SB-MASSRC004', 5, N'bankindex/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC005', N'SB-MASSRC005', 10, N'creditcard/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC007', N'SB-MASSRC007', 7, N'BookBank/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC008', N'SB-MASSRC008', 8, N'BookCheque/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC009', N'SB-MASSRC009', 9, N'bankdeptype/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC010', N'SB-MASSRC010', 4, N'banknote/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SRC', N'SRC', N'SRC011', N'SB-SRC011', 11, N'masInstallmentTerms/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SSP', N'SSP', N'M00126', N'SB-M00126', 4, N'masOdl/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SSP', N'SSP', N'SSP001', N'SB-MASSSP001', 1, N'groupsupplier/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SSP', N'SSP', N'SSP002', N'SB-MASSSP002', 2, N'suppliertype/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SSP', N'SSP', N'SSP003', N'SB-MASSSP003', 3, N'supplierlev/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'STO', N'STO', N'ABI001', N'SB-ABI001', 5, N'connectionsetting/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'STO', N'STO', N'ABI003', N'SB-ABI003', 11, N'interfaceexport/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'STO', N'STO', N'STO001', N'SB-MASSTO001', 1, N'branch/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'STO', N'STO', N'STO002', N'SB-MASSTO002', 2, N'warehouse/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'STO', N'STO', N'STO004', N'SB-MASSTO004', 4, N'zone/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SUR', N'SUR', N'SUR001', N'SB-MASSUR001', 3, N'user/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SUR', N'SUR', N'SUR002', N'SB-MASSUR002', 2, N'department/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SUR', N'SUR', N'SUR003', N'SB-MASSUR003', 1, N'role/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SUR', N'SUR', N'SUR004', N'SB-MASSUR004', 4, N'PermissionApproveDoc/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SUR', N'SUR', N'SUR006', N'SB-MASSUR006', 6, N'settingmenu/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVCAR', N'SVCAR', N'M00111', N'SB-M00111', 13, N'masCARView/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVD', N'SVD', N'SVD001', N'SB-MASSVD001', 1, N'CabinetType/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'MAS', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVD', N'SVD', N'SVD002', N'SB-MASSVD002', 2, N'shop/0/0/LAYOUT', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVDOC', N'SVDOC', N'SV0001', N'SB-SV0001', 7, N'docSatisfactionSurvey/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVDOC', N'SVDOC', N'SV0002', N'SB-SV0002', 1, N'docBookingCalendar/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVDOC', N'SVDOC', N'SV0003', N'SB-SV0003', 6, N'docIAS/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVDOC', N'SVDOC', N'SV0004', N'SB-SV0004', 2, N'docJR1/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVDOC', N'SVDOC', N'SV0005', N'SB-SV0005', 2, N'docJOB/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVDOC', N'SVDOC', N'SV0006', N'SB-SV0006', 4, N'docDWO/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SVDOC', N'SVDOC', N'SV0007', N'SB-SV0007', 5, N'docPreRepairResult/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'SVC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'ABI001', N'SB-ABI001', 8, N'connectionsetting/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'ABI002', N'SB-ABI002', 10, N'interfaceimport/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'ABI003', N'SB-ABI003', 10, N'interfaceexport/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'M00110', N'SB-M00110', 12, N'masQAHView/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'SYS001', N'SB-MASSYS001', 1, N'company/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'SYS002', N'SB-MASSYS002', 2, N'rate/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'SYS003', N'SB-MASSYS003', 3, N'vatrate/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'SYS004', N'SB-MASSYS004', 4, N'merchant/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'SYS005', N'SB-MASSYS005', 6, N'reason/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'SYS006', N'SB-MASSYS006', 5, N'agency/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'SYS', N'SYS', N'SYS007', N'SB-MASSYS007', 11, N'masNation/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'MAS', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'TXO', N'TXO', N'TXO001', N'SB-ICTXO001', 6, N'TFW/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'TXO', N'TXO', N'TXO003', N'SB-ICTXO003', 4, N'TWI/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'TXO', N'TXO', N'TXO005', N'SB-ICTXO005', 3, N'TWO/0/0/4', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'TXO', N'TXO', N'TXO006', N'SB-ICTXO006', 2, N'TWO/0/0/2', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N' ', N'IC', N' ')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'TXO', N'TXO', N'TXO010', N'SB-ICTXO010', 1, N'TXOOut/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'TXO', N'TXO', N'TXO016', N'SB-ICTXO016', 7, N'docPCK/0/0', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'1', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'WAHCHK', N'WAHCHK', N'WAH001', N'SB-WAH001', 1, N'mntDocSta/1', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')
	INSERT [dbo].[TSysMenuList] ([FTGmnCode], [FTMnuParent], [FTMnuCode], [FTLicPdtCode], [FNMnuSeq], [FTMnuCtlName], [FNMnuLevel], [FTMnuStaPosHpm], [FTMnuStaPosFhn], [FTMnuStaSmartHpm], [FTMnuStaSmartFhn], [FTMnuStaMoreHpm], [FTMnuStaMoreFhn], [FTMnuType], [FTMnuStaAPIPos], [FTMnuStaAPISmart], [FTMnuStaUse], [FTMnuPath], [FTGmnModCode], [FTMnuImgPath]) VALUES (N'WAHCHK', N'WAHCHK', N'WAH002', N'SB-WAH002', 2, N'mntDocSta/2', 1, N'Y', N'Y', N'Y', N'Y', N'Y', N'Y', N'1', N'Y', N'Y', N'0', N'', N'IC', N'')

	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI001', 1, N'กำหนดค่า', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI002', 1, N'นำเข้า', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI002', 2, N'Import', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI003', 1, N'ส่งออก', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI003', 2, N'Export', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI004', 1, N'ประวัติการนำเข้า - ส่งออก', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI004', 2, N'History import - export ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI005', 1, N'ตรวจสอบสรุปยอดสิ้นวัน Blue Card', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ABI006', 1, N'ตรวจสอบข้อมูลนำเข้า', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0001', 1, N'ผู้จำหน่าย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0001', 2, N'Supplier', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0002', 1, N'ใบรับของ-ใบซื้อสินค้า/บริการ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0002', 2, N'Purchase invoice', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0003', 1, N'ใบลดหนี้', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0003', 2, N'Credit note', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0004', 1, N'ใบสั่งซื้อ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0005', 1, N'ตรวจสอบบัญชีเจ้าหนี้', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0006', 1, N'ใบวางบิล', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0007', 1, N'ใบจ่ายชำระเงิน', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0008', 1, N'ใบซื้อ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0009', 1, N'ใบขอซื้อ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0010', 1, N'ใบรับของ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0011', 1, N'ใบสั่งสินค้าสำนักงานใหญ่', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0012', 1, N'ใบสั่งสินค้าจากสาขา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0013', 1, N'ใบสั่งซื้อจากไฟล์', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0014', 1, N'ใบคืน', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AP0015', 1, N'ใบบันทึกค่าใช้จ่าย', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'APD0001', 1, N'ใบขอซื้อ', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'APD0002', 1, N'ใบมัดจำ', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'APD0003', 1, N'ใบเพิ่มหนี้', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'APD0004', 1, N'ใบเอกสารอื่นๆ', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AR0001', 1, N'ใบภาษีถูกหัก ณ ที่จ่าย', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AR0002', 1, N'ใบมัดจำ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AR0003', 1, N'ใบเสนอราคา', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AR0004', 1, N'ใบจองสินค้า', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AR0005', 1, N'ใบเคลม', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARC001', 1, N'การอนุมัติใบสั่งขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARC001', 2, N'Approving purchase order', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARC002', 1, N'การจองช่องตู้ฝาก', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARC002', 2, N'Lockers booking', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD002', 1, N'สำเนาอิเล็กทรอนิคส์', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD002', 2, N'Electronic journals', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD003', 1, N'เงื่อนไขการแลกแต้ม', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD003', 2, N'Condition redeem point', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD004', 1, N'โปรโมชั่นเติมเงิน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD004', 2, N'Promotion topup card', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD005', 1, N'ใบกำกับภาษีเต็มรูป', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD005', 2, N'Tax invoice', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD006', 1, N'ใบเสนอราคา', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD007', 1, N'ใบมัดจำ', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD008', 1, N'ใบวางบิล', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD009', 1, N'ใบสั่งขาย - โรงพยาบาล', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD009', 2, N'Purchase order - statdose', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD05', 1, N'เงื่อนไขแลกแต้ม', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARD05', 2, N'Condition redeem', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS001', 1, N'แดชบอร์ด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS001', 2, N'Dashboard', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS002', 1, N'ลูกค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS002', 2, N'Customer', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS003', 1, N'ใบกำกับภาษีเต็มรูป', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS003', 2, N'Tax invoice', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS004', 1, N'ตรวจสอบการขายหน้าร้าน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS004', 2, N'Sale monitoring', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS005', 1, N'ตรวจสอบบัญชีลูกหนี้', N'ยังไม่มีเมนู')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS006', 1, N'ข้อมูลลูกค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS006', 2, N'Customer', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS007', 1, N'ประวัติการขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS007', 2, N'History sales', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS008', 1, N'อนุมัติลูกค้าใหม่', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS008', 2, N'Approve new customer', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS009', 1, N'ยืนยันการรับชำระใบอนุญาต', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS009', 2, N'Confirm license payment', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS011', 1, N'ใบกำกับภาษีอย่างย่อ (ใบขาย/ใบคืน)', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'ARS012', 1, N'ใบเพิ่มหนี้ (ยอดเงิน)', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST001', 1, N'ใบตรวจนับ - ยืนยัน สินค้าคงคลัง', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST001', 2, N'Stock & mearge checking', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST002', 1, N'ใบตรวจนับ - ตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST002', 2, N'Adjust stock vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST003', 1, N'ใบตรวจนับ - ย่อย สินค้าคงคลัง', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST003', 2, N'Adjust stock - sub invertory', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST004', 1, N'ใบตรวจนับ - รวม สินค้าคงคลัง', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST004', 2, N'Adjust stock - total invertory', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST005', 1, N'ใบจ่ายโอน - ตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST005', 2, N'Tranfer in vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST006', 1, N'ใบรับโอน - ตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AST006', 2, N'Tranfer out vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AUD001', 1, N'ตั้งค่าการเชื่อมต่อบัญชี', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AUD001', 2, N'Setting connect acoount', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AUD002', 1, N'โอนข้อมูลหลัก', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AUD002', 2, N'Transfer master data', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AUD003', 1, N'สร้างเอกสารใหม่', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'AUD003', 2, N'Create document', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'CDV001', 1, N'กำหนดเครื่องพิมพ์', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC001', 1, N'ใบนำฝาก', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC001', 2, N'Deposit slip', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC002', 1, N'ใบสร้างบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC002', 2, N'Cash card new', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC003', 1, N'ใบบันทึกค่าใช้จ่าย', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC004', 1, N'ใบคืนบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC004', 2, N'Cash card return', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC005', 1, N'ใบเติมเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC005', 2, N'Cash Card Topup', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC006', 1, N'ใบคืนเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC006', 2, N'Cash card refund', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC007', 1, N'ใบเปลี่ยนสถานะบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC007', 2, N'Cash card change status', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC008', 1, N'ใบเปลี่ยนบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC008', 2, N'Cash card change', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC009', 1, N'นำเข้า - ส่งออก บัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC009', 2, N'Cash card import - export', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC010', 1, N'ใบเบิกบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FDC010', 2, N'Shilf out card cash', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNS001', 1, N'แดชบอร์ด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNS001', 2, N'Dashboard', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT001', 1, N'บัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT001', 2, N'Cash card', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT002', 1, N'คูปอง / บัตรกำนัล', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT002', 2, N'Coupon / voucher', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT003', 1, N'โปรโมชั่นบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT003', 2, N'Promotion cash flow', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT004', 1, N'ประวัติบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'FNT004', 2, N'History card', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00103', 1, N'ใบสั่งขาย', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00110', 1, N'แม่แบบชุดคำถาม', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00111', 1, N'ข้อมูลรถ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00112', 1, N'การจัดการข้อความ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00113', 1, N'จุดให้บริการ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00114', 1, N'กลุ่มคำถาม (ระบบ)', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00115', 1, N'กลุ่มคำถาม', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00116', 1, N'แม่แบบชุดคำถาม', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00117', 1, N'ประเภท/ลักษณะ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00118', 1, N'ยี่ห้อ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00119', 1, N'รุ่น', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00120', 1, N'สี', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00121', 1, N'เกียร์', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00122', 1, N'เครื่องยนต์', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00123', 1, N'ขนาด', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00124', 1, N'ประเภท/เจ้าของ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00125', 1, N'ข้อมูล DOT ยาง', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'M00126', 1, N'ระดับวันครบกำหนดชำระ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'MON001', 1, N'ตรวจสอบเจ้าหนี้ค้างจ่าย', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'MON002', 1, N'ตรวจสอบสินค้าถึงจุดสั่งซื้อ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'MON003', 1, N'ตรวจสอบสถานะลูกหนี้ค้างชำระ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'NEW001', 1, N'การจัดการข่าวสาร', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'NEW001', 2, N'Manage Noti and news', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'NEW002', 1, N'ประวัติการแจ้งเตือน', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'NEW002', 2, N'Noti', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'NEW003', 1, N'ประวัติข่าวสาร', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'NEW003', 2, N'News', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PDM002', 1, N'ใบโอนสินค้าระหว่างสาขา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PDM002', 2, N'Transfer between branch', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PDM003', 1, N'ความเคลื่อนไหวสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PDM003', 2, N'Product movement', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PDM004', 1, N'สินค้าคงคลัง', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PDM004', 2, N'Product inventory', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH001', 1, N'หมวดหมู่ 1', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH001', 2, N'Product Depart', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH002', 1, N'หมวดหมู่ 2', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH002', 2, N'Product Class', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH003', 1, N'หมวดหมู่ 3', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH003', 2, N'Product Sub Class', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH004', 1, N'กลุ่มสินค้าฤดูกาล', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH004', 2, N'Product Season', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH005', 1, N'เนื้อผ้า', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH006', 1, N'หมวดหมู่ 4', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH006', 2, N'Fabric', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'PFH007', 1, N'หมวดหมู่ 5', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT001', 1, N'ข้อมูลการขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT001', 2, N'Report consignment', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT002', 1, N'ข้อมูลตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT002', 2, N'Report vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT004', 1, N'ข้อมูลบัตร', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT004', 2, N'Report card', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT005', 1, N'รายงานวิเคราะห์', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT005', 2, N'Report analysis', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT006', 1, N'ข้อมูลหลัก', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT006', 2, N'Report masters', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT007', 1, N'การซื้อ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'RPT007', 2, N'Report Buy', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR001', 1, N'บริษัท', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR001', 2, N'Company', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR002', 1, N'พนักงาน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR002', 2, N'Employee', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR003', 1, N'กลุ่ม', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR003', 2, N'Group', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR004', 1, N'ประเภท', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR004', 2, N'Type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR005', 1, N'ขนส่งโดย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCR005', 2, N'Ship via', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCT001', 1, N'กลุ่มลูกค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCT001', 2, N'Customer group', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCT002', 1, N'ระดับลูกค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCT002', 2, N'Customer level', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCT003', 1, N'ประเภทลูกค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SCT003', 2, N'Customer type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET001', 1, N'ตั้งค่าระบบ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET001', 2, N'Setting config', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET002', 1, N'ตั้งค่าเงื่อนไขส่วนลด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET002', 2, N'Discount policy', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET003', 1, N'ตั้งค่าการเชื่อมต่อ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET004', 1, N'ข้อมูลเซิฟเวอร์', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET004', 2, N'Data server', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET005', 1, N'ตั้งค่าเงื่อนไขช่วงการตรวจสอบ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SET005', 2, N'Setting conditions period', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU001', 1, N'สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU001', 2, N'Product', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU002', 1, N'กำหนดอัตราค่าเช่า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU002', 2, N'Rental rate', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU003', 1, N'ใบปรับราคาขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU003', 2, N'Adjust price', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU004', 1, N'โปรโมชั่น', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU004', 2, N'Promotion', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU005', 1, N'ใบปรับราคาสินค้าเช่า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU005', 2, N'Adjust price product rental', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU006', 1, N'ตรวจสอบการปรับราคาสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU006', 2, N'Check product price', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU007', 1, N'ใบปรับราคาทุน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SKU007', 2, N'Cost adjustment', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SLK001', 1, N'ลำดับ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SLK001', 2, N'Cabinet no', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SLK002', 1, N'รูปแบบตู้ฝาก', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SLK002', 2, N'Template locker', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD001', 1, N'หน่วยสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD001', 2, N'Product unit', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD002', 1, N'กลุ่มราคาสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD002', 2, N'Product price group', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD003', 1, N'กลุ่มสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD003', 2, N'Product group', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD004', 1, N'ประเภทสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD004', 2, N'Product type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD005', 1, N'รุ่น', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD005', 2, N'Model', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD006', 1, N'สี', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD006', 2, N'Color', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD007', 1, N'ยีห้อ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD007', 2, N'Brand', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD008', 1, N'ขนาด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD008', 2, N'Size', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD009', 1, N'ทีเก็บสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD009', 2, N'Location', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD010', 1, N'กลุ่มสินค้าด่วน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD010', 2, N'Product touch screen', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD011', 1, N'หมวดสินค้า 1', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD012', 1, N'หมวดสินค้า 2', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD013', 1, N'หมวดสินค้า 3', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD014', 1, N'หมวดสินค้า 4', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPD015', 1, N'หมวดสินค้า 5', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS001', 1, N'จุดขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS001', 2, N'POS', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS002', 1, N'กำหนดหัวท้ายใบเสร็จ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS002', 2, N'Config header - footer slip', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS003', 1, N'กำหนดฟังก์ชั่นจุดขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS003', 2, N'Config POS functions', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS004', 1, N'สื่อ,ข้อความ,โฆษณา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS004', 2, N'Advertisement', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS005', 1, N'ลงทะเบียนจุดขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS005', 2, N'Register POS', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS006', 1, N'ช่องทางการขาย', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SPS006', 2, N'Channel', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC001', 1, N'ประเภทการชำระเงิน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC001', 2, N'Payment type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC002', 1, N'ประเภทคูปอง/บัตรกำนัล ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC002', 2, N'Coupon/voucher type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC003', 1, N'ประเภทบัตรเงินสด', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC003', 2, N'Cash card type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC004', 1, N'ธนาคาร', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC004', 2, N'Bank', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC005', 1, N'บัตรเครดิต', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC005', 2, N'Credit card', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC006', 1, N'เครื่องอ่านบัตรอิเล็กทรอนิกส์', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC006', 2, N'Electronic data capture (EDC)', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC007', 1, N'สมุดบัญชีธนาคาร', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC007', 2, N'Book bank', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC008', 1, N'สมุดเช็ค', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC008', 2, N'Cheque book', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC009', 1, N'ประเภทใบนำฝาก', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC009', 2, N'Deposit slip type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC010', 1, N'ธนบัตร', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC010', 2, N'Bank note', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SRC011', 1, N'เงื่อนไขการผ่อนชำระ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SSP001', 1, N'กลุ่ม', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SSP001', 2, N'Group', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SSP002', 1, N'ประเภท', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SSP002', 2, N'Type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SSP003', 1, N'ระดับ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SSP003', 2, N'Level', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO001', 1, N'สาขา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO001', 2, N'Branch', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO002', 1, N'คลังสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO002', 2, N'Warehouse', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO003', 1, N'ร้านค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO003', 2, N'Shop', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO004', 1, N'โซน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'STO004', 2, N'Zone', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR001', 1, N'ผู้ใช้', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR001', 2, N'User', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR002', 1, N'แผนก', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR002', 2, N'Department', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR003', 1, N'สิทธิ์การใช้งาน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR003', 2, N'User authority', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR004', 1, N'สิทธิ์การอนุมัติเอกสาร', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR004', 2, N'Permission approve document', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR005', 1, N'ตั้งค่าการใช้งานฟังก์ชัน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR005', 2, N'Function setting', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR006', 1, N'ตั้งค่าการใช้งานเมนู', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SUR006', 2, N'Setting menu', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0001', 1, N'ใบประเมินความพึงพอใจของลูกค้า', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0001', 2, N'Satification Survey', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0002', 1, N'ตารางนัดหมาย', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0003', 1, N'ใบตรวจสอบสภาพหลังซ่อม', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0003', 2, N'Inspect After Service', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0004', 1, N'ใบรับรถ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0005', 1, N'ใบสั่งงาน', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0005', 2, N'Job Order', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0006', 1, N'ตรวจสอบใบสั่งงาน', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SV0007', 1, N'ใบบันทึกผลตรวจเช็คสภาพรถ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SVD001', 1, N'ประเภทตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SVD001', 2, N'Cabinet Type', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SVD002', 1, N'รูปแบบการจัดสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SVD002', 2, N'Template vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS001', 1, N'บริษัท', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS001', 2, N'Company', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS002', 1, N'สกุลเงิน', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS002', 2, N'Currency', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS003', 1, N'อัตราภาษี', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS003', 2, N'Vat rate', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS004', 1, N'กลุ่มธุรกิจ', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS004', 2, N'Merchant', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS005', 1, N'เหตุผล', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS005', 2, N'Reason', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS006', 1, N'ตัวแทนขาย/แฟรนไชส์', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS006', 2, N'Agency', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'SYS007', 1, N'ประเทศ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TBD001', 1, N'ใบขอโอน - สาขา', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO001', 1, N'ใบโอนสินค้าระหว่างคลัง', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO001', 2, N'Transfer between warehouse', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO002', 1, N'ใบเติมสินค้า - ตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO002', 2, N'Refill vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO003', 1, N'ใบรับโอน - คลังสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO003', 2, N'Transfer receipt input', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO005', 1, N'ใบจ่ายโอน - คลังสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO005', 2, N'Warehouse transfer in slip', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO006', 1, N'ใบเบิกออก - คลังสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO006', 2, N'Warehouse  withdraw', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO007', 1, N'ใบจ่ายโอน - สาขา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO007', 2, N'Transfer in recept branch', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO008', 1, N'ใบรับโอน - สาขา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO008', 2, N'Transfer receipt branch', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO009', 1, N'ใบรับเข้า - สาขา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO009', 2, N'Receipt branch', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO010', 1, N'ใบรับเข้า - คลังสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO010', 2, N'Transfer out receipt', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO011', 1, N'ใบสั่งสินค้า -  สาขา', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO011', 2, N'Purchase invoice branch', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO012', 1, N'ใบนำสินค้าออก - ตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO012', 2, N'Transfer product out vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO013', 1, N'ใบเติมสินค้าแบบชุด - ตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO013', 2, N'Refill product set vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO014', 1, N'ใบคืนสินค้า - ตู้สินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO014', 2, N'Return product vending', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO015', 1, N'ใบเบิกจ่าย (หน่วยงาน)', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO016', 1, N'ใบหยิบสินค้า', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'TXO017', 1, N'ใบแลกของพรีเมี่ยม', NULL)
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'WAH001', 1, N'ตรวจสอบสถานะเอกสาร HQ', N'')
	INSERT [dbo].[TSysMenuList_L] ([FTMnuCode], [FNLngID], [FTMnuName], [FTMnuRmk]) VALUES (N'WAH002', 1, N'ตรวจสอบสถานะเอกสาร BCH', N'')

	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ABI001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ABI002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ABI003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ABI004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ABI005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ABI006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0004', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0007', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0008', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0009', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0010', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0011', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0012', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0013', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0014', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AP0016', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'APD0001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'APD0002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'APD0003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'APD0004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'APD001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AR0001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AR0002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AR0003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AR0004', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AR0005', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARC001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARC002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD005', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD006', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD007', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD008', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD009', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARD05', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS001', N'1', N'1', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS004', N'1', N'1', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS007', N'1', N'0', N'1', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS008', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS009', N'0', N'0', N'0', N'0', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS011', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'ARS012', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AST001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AST002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AST003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AST004', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AST005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AST006', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AUD001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AUD002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'AUD003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'CDV001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC004', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC005', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC006', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC007', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC008', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC009', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FDC010', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FNS001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FNT001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FNT002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FNT003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'FNT004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00103', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00110', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00111', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00112', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00113', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00114', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00115', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00116', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00117', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00118', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00119', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00120', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00121', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00122', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00123', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00124', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00125', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'M00126', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'MON001', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'MON002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'MON003', N'1', N'1', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'NEW001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'NEW002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'NEW003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'NEW1', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'NEW2', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'NEW3', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PDM001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PDM002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PDM003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PDM004', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PFH001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PFH002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PFH003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PFH004', N'0', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PFH005', N'0', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PFH006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'PFH007', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT001', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT002', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT003', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT004', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT005', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT006', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT007', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'RPT008', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCR001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCR002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCR003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCR004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCR005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCT001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCT002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SCT003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SET001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SET002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SET003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SET004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SET005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SKU001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SKU002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SKU003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SKU004', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SKU005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SKU006', N'1', N'0', N'0', N'0', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SKU007', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SLK001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SLK002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD007', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD008', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD009', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD010', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD011', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD012', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD013', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD014', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPD015', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPS001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPS002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPS003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPS004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPS005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SPS006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC007', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC008', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC009', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC010', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SRC011', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SSP001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SSP002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SSP003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'STO001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'STO002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'STO003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'STO004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SUR001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SUR002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SUR003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SUR004', N'1', N'0', N'0', N'0', N'1', N'1', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SUR005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SUR006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SV0001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SV0002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SV0003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SV0004', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SV0005', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SV0006', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SV0007', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SVD001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SVD002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SYS001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SYS002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SYS003', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SYS004', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SYS005', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SYS006', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'SYS007', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TBD001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO001', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO002', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO003', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO005', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO006', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO007', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO008', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO009', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO010', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO011', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO012', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO013', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO014', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO015', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO016', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'TXO017', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'1')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'WAH001', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
	INSERT [dbo].[TSysMenuAlbAct] ([FTMnuCode], [FTAutStaRead], [FTAutStaAdd], [FTAutStaEdit], [FTAutStaDelete], [FTAutStaCancel], [FTAutStaAppv], [FTAutStaPrint], [FTAutStaPrintMore]) VALUES (N'WAH002', N'1', N'1', N'1', N'1', N'0', N'0', N'0', N'0')
--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.01', getdate() , 'แก้ไขเมนูทั้งหมด', 'Supawat P.')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.02') BEGIN
	UPDATE TCNSListObj SET FTObjTable='TARTSpHD'WHERE FTObjCode='00008'
	UPDATE TCNSListObj_L SET FTObjName='หน้าจอ ใบรับชำระเงิน' WHERE FTObjCode='00008'
	UPDATE TSysMenuList SET FTMnuStaUse='0' WHERE FTMnuCode='NEW001'
	UPDATE TSysMenuList SET FTMnuStaUse='0' WHERE FTMnuCode='NEW002'
	UPDATE TSysMenuList SET FTMnuStaUse='0' WHERE FTMnuCode='NEW003'

	-------------- Initial Data  --------------
	UPDATE TPSMFuncDTSpc SET FTGdtRef2='tRoute=docIAS/1/2%82tUsrLogin={tUsrCode}%82nLanguage={nLang}%82dCurrentDay={dDate}%82tBchCode={tBchCode}%82tDocNo={tDocNo}%82tAgnCode={tAgnCode}'
	WHERE FTGhdCode ='003' AND FTGdtCallByName ='C_KBDxJobOrder' AND FTGdtKey = 'inspectAft'
	
	UPDATE TPSMFuncDTSpc SET FTGdtRef2='tRoute=docPreRepairResult/2/0%82tUsrLogin={tUsrCode}%82nLanguage={nLang}%82dCurrentDay={dDate}%82tBchCode={tBchCode}%82tDocNo={tDocNo}%82tAgnCode={tAgnCode}'
	WHERE FTGhdCode ='003' AND FTGdtCallByName ='C_KBDxJobOrder' AND FTGdtKey = 'inspectB4'
	
	UPDATE TPSMFuncDTSpc SET FTGdtRef2='tRoute=docSatisfactionSurvey/2/0%82tUsrLogin={tUsrCode}%82nLanguage={nLang}%82dCurrentDay={dDate}%82tBchCode={tBchCode}%82tDocNo={tDocNo}%82tAgnCode={tAgnCode}'
	WHERE FTGhdCode ='003' AND FTGdtCallByName ='C_KBDxJobOrder' AND FTGdtKey = 'survey'
	
	UPDATE TPSMFuncDTSpc
	SET FTGdtRef2 = 'tRoute=docIAS/0/0%82tUsrLogin={tUsrCode}%82nLanguage={nLang}%82dCurrentDay={dDate}%82tBchCode={tBchCode}%82tDocNo={tDocNo}%82tAgnCode={tAgnCode}'
	WHERE  FTGhdCode = '003' AND FTGdtCallByName = 'C_KBDxJobOrder' AND FTGdtKey = 'inspectAft'
	
	INSERT [dbo].[TCNTAuto] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FTSatGroup], [FTGmnCode], [FTSatDocTypeName], [FTSatStaAlwChr], [FTSatStaAlwBch], [FTSatStaAlwPosShp], [FTSatStaAlwYear], [FTSatStaAlwMonth], [FTSatStaAlwDay], [FTSatStaAlwSep], [FTSatStaDefUsage], [FTSatDefChar], [FTSatDefBch], [FTSatDefPosShp], [FTSatDefYear], [FTSatDefMonth], [FTSatDefDay], [FTSatDefSep], [FTSatDefNum], [FTSatDefFmtAll], [FNSatMaxFedSize], [FNSatMinRunning], [FTSatUsrChar], [FTSatUsrBch], [FTSatUsrPosShp], [FTSatUsrYear], [FTSatUsrMonth], [FTSatUsrDay], [FTSatUsrSep], [FTSatUsrNum], [FTSatUsrFmtAll], [FTSatStaReset], [FTSatStaRunBch], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTPrnCode]) 
	VALUES (N'TARTSpHD', N'FTXshDocNo', N'1', N'2', N'AR', N'FNXshDocType', N'1', N'1', N'0', N'1', N'1', N'1', N'1', N'0', N'SP', N'1', N'0', N'1', N'0', N'0', N'0', N'000001', N'SPBCHYY######', 20, 5, N'SP', N'1', N'0', N'1', N'0', N'0', N'0', N'000001', N'SPBCHYY######', N'4', N'0', CAST(N'2021-11-22T22:00:00.000' AS DateTime), N'', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', NULL)
	
	INSERT [dbo].[TCNTAuto_L] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FNLngID], [FTSatTblDesc], [FTSatRmk]) VALUES (N'TARTSpHD', N'FTXshDocNo', N'1', 1, N'ใบรับชำระ', NULL)
	
	INSERT [dbo].[TSysSyncData] ([FNSynSeqNo], [FTSynGroup], [FTSynTable], [FTSynTable_L], [FTSynType], [FDSynLast], [FNSynSchedule], [FTSynStaUse], [FTSynUriDwn], [FTSynUriUld]) 
	VALUES (133, N'AR', N'TARTSpHD', N'API2PSSale', N'2', CAST(N'2021-11-22 23:58:08.180' AS DateTime), 0, N'1', N'', N'/Service/Upload/RcvPayment')
	
	INSERT [dbo].[TSysSyncData_L] ([FNSynSeqNo], [FNLngID], [FTSynName], [FTSynRmk]) VALUES (133, 1, N'ข้อมูลใบรับชำระเงิน', N'')
	
	INSERT [dbo].[TSysSyncData_L] ([FNSynSeqNo], [FNLngID], [FTSynName], [FTSynRmk]) VALUES (133, 2, N'Receive Payment', N'')
	
	INSERT [dbo].[TSysSyncModule] ([FTAppCode], [FNSynSeqNo]) VALUES (N'PS', 133)
	
	INSERT [dbo].[TSysConfig] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FTGmnCode], [FTSysStaAlwEdit], [FTSysStaDataType], [FNSysMaxLength], [FTSysStaDefValue], [FTSysStaDefRef], [FTSysStaUsrValue], [FTSysStaUsrRef], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
	VALUES (N'nVB_ReOrdPntDay', N'CN', N'ALL', N'1', N'ALL', N'1', N'1', N'2', N'30', N'', N'30', N'', GETDATE(), N'Jirayu S.', GETDATE(), N'Jirayu S.')

	INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) 
	VALUES (N'nVB_ReOrdPntDay', N'CN', N'ALL', N'1', 1, N'จำนวนวันย้อนหลัง ในการคำนวนการขายเฉลี่ย สำรหับ ReOrder Point', N'', N'')

	INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) 
	VALUES (N'nVB_ReOrdPntDay', N'CN', N'ALL', N'1', 2, N'Day previous for calculate sale average of ReOrder Point', N'', N'')
	
	-- 00.00.02 --
	UPDATE TSysSyncData SET FTSynUriDwn='/Customer/Download?pdDate={pdDate}&ptAgnCode={ptAgnCode}&ptClvCode={ptClvCode}' WHERE FNSynSeqNo=31
	UPDATE TSysSyncData SET FTSynStaUse='2' WHERE FNSynSeqNo=119
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบรับรถ' WHERE FTGhdCode = '003' AND FTSysCode = 'KB015' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Job Request' WHERE FTGhdCode = '003' AND FTSysCode = 'KB015' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบเสนอราคา' WHERE FTGhdCode = '003' AND FTSysCode = 'KB016' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Quotation' WHERE FTGhdCode = '003' AND FTSysCode = 'KB016' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบมัดจำ' WHERE FTGhdCode = '003' AND FTSysCode = 'KB017' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Receive Deposit' WHERE FTGhdCode = '003' AND FTSysCode = 'KB017' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบสั่งงาน' WHERE FTGhdCode = '003' AND FTSysCode = 'KB018' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Job Order' WHERE FTGhdCode = '003' AND FTSysCode = 'KB018' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบเบิกจ่าย(หน่วยงาน)' WHERE FTGhdCode = '003' AND FTSysCode = 'KB019' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Sale Internal' WHERE FTGhdCode = '003' AND FTSysCode = 'KB019' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบหัก ณ ที่จ่าย' WHERE FTGhdCode = '003' AND FTSysCode = 'KB020' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Withholding Tax' WHERE FTGhdCode = '003' AND FTSysCode = 'KB020' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบกำกับภาษีเต็มรูป' WHERE FTGhdCode = '003' AND FTSysCode = 'KB023' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Tax invoice' WHERE FTGhdCode = '003' AND FTSysCode = 'KB023' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบรับชำระเงิน' WHERE FTGhdCode = '003' AND FTSysCode = 'KB024' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Receive Payment' WHERE FTGhdCode = '003' AND FTSysCode = 'KB024' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบนำฝาก' WHERE FTGhdCode = '003' AND FTSysCode = 'KB029' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Bank Deposit' WHERE FTGhdCode = '003' AND FTSysCode = 'KB029' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='พิมพ์ข้อความด่วน' WHERE FTGhdCode = '003' AND FTSysCode = 'KB030' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Print Message' WHERE FTGhdCode = '003' AND FTSysCode = 'KB030' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบจอง' WHERE FTGhdCode = '003' AND FTSysCode = 'KB031' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Booking' WHERE FTGhdCode = '003' AND FTSysCode = 'KB031' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ลูกค้า' WHERE FTGhdCode = '003' AND FTSysCode = 'KB032' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Customer' WHERE FTGhdCode = '003' AND FTSysCode = 'KB032' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='หลังบ้าน' WHERE FTGhdCode = '003' AND FTSysCode = 'KB033' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Back office' WHERE FTGhdCode = '003' AND FTSysCode = 'KB033' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบลดหนี้(ยอดเงิน)' WHERE FTGhdCode = '003' AND FTSysCode = 'KB036' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Credit Note(Amount)' WHERE FTGhdCode = '003' AND FTSysCode = 'KB036' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบเพิ่มหนี้(ยอดเงิน)' WHERE FTGhdCode = '003' AND FTSysCode = 'KB037' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Debit Note(Amount)' WHERE FTGhdCode = '003' AND FTSysCode = 'KB037' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='เปิดใช้งานบัตร' WHERE FTGhdCode = '003' AND FTSysCode = 'KB040' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Activate Card' WHERE FTGhdCode = '003' AND FTSysCode = 'KB040' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='เช็คคะแนนบัตร' WHERE FTGhdCode = '003' AND FTSysCode = 'KB041' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Card points check ' WHERE FTGhdCode = '003' AND FTSysCode = 'KB041' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='ใบหยิบสินค้า' WHERE FTGhdCode = '003' AND FTSysCode = 'KB042' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Picking List' WHERE FTGhdCode = '003' AND FTSysCode = 'KB042' AND FNLngID = 2
	UPDATE TPSMFuncDT_L SET FTGdtName ='แลกสินค้าพรีเมี่ยม' WHERE FTGhdCode = '003' AND FTSysCode = 'KB049' AND FNLngID = 1
	UPDATE TPSMFuncDT_L SET FTGdtName ='Premium Products' WHERE FTGhdCode = '003' AND FTSysCode = 'KB049' AND FNLngID = 2
	UPDATE TPSMFuncHD SET FDLastUpdOn = GETDATE() WHERE FTGhdCode = '003'
	
--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.02', getdate() , 'แก้ไขเมนูข่าวสาร + ได้รับมาจากเน็ต', 'Supawat P.')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.03') BEGIN
	UPDATE TSysMenuList SET FTMnuStaUse = '0' WHERE FTMnuCode = 'TXO016'

--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.03', getdate() , 'ปิดการใช้งานเมนู ใบหยิบสินค้า', 'Napat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.04') BEGIN

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB073' ) BEGIN
		INSERT TPSMFuncDT
		(
			FTGhdCode, FTSysCode, FTLicPdtCode, FNGdtPage, FNGdtDefSeq, FNGdtUsrSeq, FNGdtBtnSizeX, FNGdtBtnSizeY, FTGdtCallByName, FTGdtStaUse, FNGdtFuncLevel, FTGdtSysUse
		)
		VALUES('031', 'KB073', 'SF-PS031KB073', 1, 7, 7, 1, 1, 'C_KBDxRedeemPoint', '1', '1', '1')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB073' AND FNLngID = 1 ) BEGIN
		INSERT TPSMFuncDT_L
		(
			FTGhdCode, FTSysCode, FNLngID, FTGdtName
		)
		VALUES('031', 'KB073', 1, 'แลกคะแนน')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB073' AND FNLngID = 2 ) BEGIN
		INSERT TPSMFuncDT_L
		(
			FTGhdCode, FTSysCode, FNLngID, FTGdtName
		)
		VALUES('031', 'KB073', 2, 'Redeem Point')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT WITH(NOLOCK) WHERE FTGhdCode = '005' AND FTSysCode = 'KB043' ) BEGIN
		INSERT [dbo].[TPSMFuncDT] ([FTGhdCode], [FTSysCode], [FTLicPdtCode], [FNGdtPage], [FNGdtDefSeq], [FNGdtUsrSeq], [FNGdtBtnSizeX], [FNGdtBtnSizeY], [FTGdtCallByName], [FTGdtStaUse], [FNGdtFuncLevel], [FTGdtSysUse]) 
		VALUES (N'005', N'KB043', N'SF-PS005KB043', 1, 8, 8, 1, 1, N'C_KBDxAddCst', N'2', 1, N'3')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '005' AND FTSysCode = 'KB043' AND FNLngID = 1 ) BEGIN
		INSERT [dbo].[TPSMFuncDT_L] ([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) VALUES (N'005', N'KB043', 1, N'ลูกค้า (ออฟไลน์)')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '005' AND FTSysCode = 'KB043' AND FNLngID = 2 ) BEGIN
		INSERT [dbo].[TPSMFuncDT_L] ([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) VALUES (N'005', N'KB043', 2, N'Customer (Offline)')
	END

	UPDATE TPSMFuncDT SET FTGdtStaUse='2' , FTGdtSysUse ='3' WHERE FTGhdCode='005' AND FTSysCode = 'KB043'
	UPDATE TPSMFuncDT SET FTGdtStaUse='1' , FTGdtSysUse ='1' WHERE FTGhdCode='031' AND FTSysCode = 'KB073'
	UPDATE TPSMFuncHD SET FDLastUpdOn=GETDATE() WHERE FTGhdCode='005'
	UPDATE TPSMFuncHD SET FDLastUpdOn=GETDATE() WHERE FTGhdCode='031'

	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '1') BEGIN
		INSERT [dbo].[TCNTAuto] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FTSatGroup], [FTGmnCode], [FTSatDocTypeName], [FTSatStaAlwChr], [FTSatStaAlwBch], [FTSatStaAlwPosShp], [FTSatStaAlwYear], [FTSatStaAlwMonth], [FTSatStaAlwDay], [FTSatStaAlwSep], [FTSatStaDefUsage], [FTSatDefChar], [FTSatDefBch], [FTSatDefPosShp], [FTSatDefYear], [FTSatDefMonth], [FTSatDefDay], [FTSatDefSep], [FTSatDefNum], [FTSatDefFmtAll], [FNSatMaxFedSize], [FNSatMinRunning], [FTSatUsrChar], [FTSatUsrBch], [FTSatUsrPosShp], [FTSatUsrYear], [FTSatUsrMonth], [FTSatUsrDay], [FTSatUsrSep], [FTSatUsrNum], [FTSatUsrFmtAll], [FTSatStaReset], [FTSatStaRunBch], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTPrnCode]) 
		VALUES (N'TPSTSalHD', N'FTXshDocNo', N'1', N'2', N'XSAL', N'FNXshDocType', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'0', N'S', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'SBCHPOSYY#######', 20, 5, N'S', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'SBCHPOSYY#######', N'1', N'0', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', NULL)
	END

	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '9') BEGIN
		INSERT [dbo].[TCNTAuto] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FTSatGroup], [FTGmnCode], [FTSatDocTypeName], [FTSatStaAlwChr], [FTSatStaAlwBch], [FTSatStaAlwPosShp], [FTSatStaAlwYear], [FTSatStaAlwMonth], [FTSatStaAlwDay], [FTSatStaAlwSep], [FTSatStaDefUsage], [FTSatDefChar], [FTSatDefBch], [FTSatDefPosShp], [FTSatDefYear], [FTSatDefMonth], [FTSatDefDay], [FTSatDefSep], [FTSatDefNum], [FTSatDefFmtAll], [FNSatMaxFedSize], [FNSatMinRunning], [FTSatUsrChar], [FTSatUsrBch], [FTSatUsrPosShp], [FTSatUsrYear], [FTSatUsrMonth], [FTSatUsrDay], [FTSatUsrSep], [FTSatUsrNum], [FTSatUsrFmtAll], [FTSatStaReset], [FTSatStaRunBch], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTPrnCode]) 
		VALUES (N'TPSTSalHD', N'FTXshDocNo', N'9', N'2', N'XSAL', N'FNXshDocType', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'0', N'R', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'RBCHPOSYY#######', 20, 5, N'R', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'RBCHPOSYY#######', N'1', N'0', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', NULL)
	END

	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto_L WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '1' AND FNLngID = 1) BEGIN
		INSERT [dbo].[TCNTAuto_L] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FNLngID], [FTSatTblDesc], [FTSatRmk]) 
		VALUES (N'TPSTSalHD', N'FTXshDocNo', N'1', 1, N'ขายปลีก - ขาย', N'')
	END

	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto_L WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '9' AND FNLngID = 1) BEGIN
		INSERT [dbo].[TCNTAuto_L] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FNLngID], [FTSatTblDesc], [FTSatRmk]) 
		VALUES (N'TPSTSalHD', N'FTXshDocNo', N'9', 1, N'ขายปลีก - คืน', N'')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB031' ) BEGIN
		INSERT TPSMFuncDT
		(
			FTGhdCode, FTSysCode, FTLicPdtCode, FNGdtPage, FNGdtDefSeq, FNGdtUsrSeq, FNGdtBtnSizeX, FNGdtBtnSizeY, FTGdtCallByName, FTGdtStaUse, FNGdtFuncLevel, FTGdtSysUse
		)
		VALUES('031', 'KB031', 'SF-PS031KB031', 1, 8, 8, 1, 1, 'C_KBDxReferWhTax', '1', '1', '1')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB031' AND FNLngID = 1 ) BEGIN
		INSERT TPSMFuncDT_L
		(
			FTGhdCode, FTSysCode, FNLngID, FTGdtName
		)
		VALUES('031', 'KB031', 1, 'หักภาษี ณ ที่จ่าย')
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB031' AND FNLngID = 2 ) BEGIN
		INSERT TPSMFuncDT_L
		(
			FTGhdCode, FTSysCode, FNLngID, FTGdtName
		)
		VALUES('031', 'KB031', 2, 'Refer WithholdingTax')
	END

	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto WITH(NOLOCK) WHERE FTSatTblName = 'TPSTWhTaxHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '0' ) BEGIN
		INSERT [dbo].[TCNTAuto] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FTSatGroup], [FTGmnCode], [FTSatDocTypeName], [FTSatStaAlwChr], [FTSatStaAlwBch], [FTSatStaAlwPosShp], [FTSatStaAlwYear], [FTSatStaAlwMonth], [FTSatStaAlwDay], [FTSatStaAlwSep], [FTSatStaDefUsage], [FTSatDefChar], [FTSatDefBch], [FTSatDefPosShp], [FTSatDefYear], [FTSatDefMonth], [FTSatDefDay], [FTSatDefSep], [FTSatDefNum], [FTSatDefFmtAll], [FNSatMaxFedSize], [FNSatMinRunning], [FTSatUsrChar], [FTSatUsrBch], [FTSatUsrPosShp], [FTSatUsrYear], [FTSatUsrMonth], [FTSatUsrDay], [FTSatUsrSep], [FTSatUsrNum], [FTSatUsrFmtAll], [FTSatStaReset], [FTSatStaRunBch], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTPrnCode]) 
		VALUES (N'TPSTWhTaxHD', N'FTXshDocNo', N'0', N'2', N'XSAL', N'', N'1', N'1', N'0', N'1', N'0', N'0', N'0', N'0', N'W', N'1', N'0', N'1', N'0', N'0', N'0', N'0000001', N'WBCHYY#######', 20, 5, N'W', N'1', N'0', N'1', N'0', N'0', N'0', N'0000001', N'WBCHYY#######', N'1', N'0', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', NULL)
	END

	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto_L WITH(NOLOCK) WHERE FTSatTblName = 'TPSTWhTaxHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '8' ) BEGIN
		INSERT [dbo].[TCNTAuto_L] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FNLngID], [FTSatTblDesc], [FTSatRmk]) 
		VALUES (N'TPSTWhTaxHD', N'FTXshDocNo', N'8', 1, N'ใบหักภาษี ณ ที่จ่าย', N'')
	END
	
--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.04', getdate() , 'script ของ Net', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.05') BEGIN
	
	DELETE FROM [dbo].[TSysReportFilter_L]
	DELETE FROM [dbo].[TSysReportFilter]
	DELETE FROM [dbo].[TSysReport_L]
	DELETE FROM [dbo].[TSysReport]
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001001', N'001', N'001001', N'rptRptSaleByBill', N'', N'', N'1,6,2,3,27,4,26', N'', N'1', N'1', 1, N'1', N'SB-RPT001001001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001002', N'001', N'001001', N'rptRptSaleByProduct', N'', N'', N'1,6,2,3,26,13,8,9,4', N'', N'1', N'1', 2, N'1', N'SB-RPT001001002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001003', N'001', N'001001', N'rptRptSaleToPayment', N'', N'', N'1,6,2,3,7,4,26', N'', N'1', N'1', 3, N'1', N'SB-RPT001001003')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001004', N'001', N'001001', N'rptBestSell', N'', N'', N'1,6,2,3,13,8,9,4,10,26', N'', N'1', N'1', 4, N'1', N'SB-RPT001001004')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001005', N'001', N'001001', N'rptRptSaleRecive', N'', N'', N'1,6,2,3,7,4,26', N'', N'1', N'1', 5, N'1', N'SB-RPT001001005')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001006', N'001', N'001001', N'rptRptTaxSalePos', N'', N'', N'1,6,2,3,4,26', N'', N'1', N'1', 6, N'1', N'SB-RPT001001006')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001007', N'001', N'001001', N'rptRptTaxSalePosByDate', N'', N'', N'1,6,2,3,4,26', N'', N'1', N'1', 7, N'1', N'SB-RPT001001007')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001008', N'001', N'001001', N'rptRptAnalysisProfitLossProductPos', N'', N'', N'1,6,2,3,4,8,13,26', N'', N'1', N'1', 8, N'1', N'SB-RPT001001008')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001010', N'001', N'001001', N'rptMnyShotOver', NULL, NULL, N'1,3,4,45', NULL, N'1', N'1', 9, N'1', N'SB-RPT001001010')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001011', N'001', N'001001', N'rptMnyShotOverDairy', NULL, NULL, N'1,3,4,45', NULL, N'1', N'1', 10, N'1', N'SB-RPT001001011')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001012', N'001', N'001001', N'rptMnyShotOverMonthly', NULL, NULL, N'1,3,28,5,45', NULL, N'1', N'1', 11, N'1', N'SB-RPT001001012')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001014', N'001', N'001001', N'rptsaledailybypos', NULL, NULL, N'1,2,3,4,45', NULL, N'1', N'1', 12, N'1', N'SB-RPT001001014')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001015', N'001', N'001001', N'rptSalesDailyByCashier', N'', N'', N'1,2,3,4,45', N'', N'1', N'1', 13, N'1', N'SB-RPT001001015')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001016', N'001', N'001001', N'rptSMP', NULL, NULL, N'1,2,3,13,5,45', NULL, N'1', N'1', 14, N'1', N'SB-RPT001001016')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001017', N'001', N'001001', N'rptRPD', NULL, NULL, N'1,2,3,45,4', NULL, N'1', N'1', 15, N'1', N'SB-RPT001001017')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001018', N'001', N'001001', N'rptBankDepositBch', N'', N'', N'1,6,2,4,44', N'', N'1', N'1', 12, N'1', N'SB-RPT001001018')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001019', N'001', N'001001', N'rptRptDayEndSales', N'', N'', N'1,6,2,3,4,26', N'', N'1', N'1', 17, N'1', N'SB-RPT001001019')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001020', N'001', N'001001', N'rptRptTaxSaleFull', N'', N'', N'1,6,2,3,4', N'', N'1', N'1', 18, N'1', N'SB-RPT001001020')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001021', N'001', N'001001', N'rptRptTaxSalePosByDateFull', N'', N'', N'1,6,2,3,4', N'', N'1', N'1', 19, N'1', N'SB-RPT001001021')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001022', N'001', N'001001', N'rptSaleByCashierAndPos', N'', N'', N'1,6,2,3,4,45', N'', N'1', N'1', 20, N'1', N'SB-RPT001001022')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001023', N'001', N'001001', N'rptCancelBillByDate', N'', N'', N'1,4', N'', N'1', N'1', 21, N'1', N'SB-RPT001001023')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001024', N'001', N'001001', N'rptCancelPdtDetailByDate', N'', N'', N'1,4', N'', N'1', N'1', 22, N'1', N'SB-RPT001001024')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001025', N'001', N'001001', N'rptBestSellByValue', N'', N'', N'1,6,2,3,13,8,9,4,10,26', N'', N'1', N'1', 4, N'1', N'SB-RPT001001025')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001026', N'001', N'001001', N'rptSaleMember', N'', N'', N'1,6,2,3,4,27', N'', N'1', N'1', 23, N'1', N'SB-RPT001001026')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001027', N'001', N'001001', N'rptPointByCst', N'', N'', N'1,4,27', N'', N'1', N'1', 24, N'1', N'SB-RPT001001027')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001028', N'001', N'001001', N'rptSalByPdtSet', NULL, NULL, N'1,2,3,4', NULL, N'1', N'1', 25, N'1', N'SB-RPT001001028')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001029', N'001', N'001001', N'rptSaleBillPaymentDate', N'', N'', N'1,2,3,4,7', N'', N'1', N'1', 26, N'1', N'SB-RPT001001029')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001030', N'001', N'001001', N'rptSalesByDatePayment', N'', N'', N'1,2,3,4,7', N'', N'1', N'1', 27, N'1', N'SB-RPT001001030')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001031', N'001', N'001001', N'rptSalByBillPdt', N'', N'', N'1,2,3,4,13', N'', N'1', N'1', 28, N'1', N'SB-RPT001001031')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001032', N'001', N'001001', N'rptPdtSalePromotion', N'', N'', N'1,6,2,3,4,50', N'', N'1', N'1', 29, N'1', N'SB-RPT001001032')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001033', N'001', N'001001', N'rptPdtSalePromotionPerDoc', N'', N'', N'1,6,2,3,4', N'', N'1', N'1', 30, N'1', N'SB-RPT001001033')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001034', N'001', N'001001', N'rptCompareSaleByPdt', N'', N'', N'1,6,2,3,13,9,51,52,8,53,54', N'', N'1', N'1', 31, N'1', N'SB-RPT001001034')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001035', N'001', N'001001', N'rptCompareSaleByPdtType', N'', N'', N'1,6,2,3,13,9,51,52,8,53,54', N'', N'1', N'1', 32, N'1', N'SB-RPT001001035')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001036', N'001', N'001001', N'rptSalePending', NULL, NULL, N'1,6,2,3,4,27', NULL, N'1', N'1', 33, N'1', N'SB-RPT001001036')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001001037', N'001', N'001001', N'rptLicClosetExpir', NULL, NULL, N'13,27,55', NULL, N'1', N'1', 34, N'1', N'SB-RPT001001037')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002001', N'001', N'001002', N'rptRptInventoryPos', N'', N'', N'1,6,2,3,12', N'', N'1', N'1', 1, N'1', N'SB-RPT001002001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002002', N'001', N'001002', N'rtpMovePosVD', N'', N'', N'1,6,2,3,5,12,13,28,49', N'', N'1', N'1', 2, N'1', N'SB-RPT001002002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002006', N'001', N'001002', N'rptProductRefill', N'', N'', N'1,6,2,3,4', N'', N'1', N'1', 13, N'1', N'SB-RPT001002006')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002007', N'001', N'001002', N'rptDocPdtTwi', NULL, NULL, N'1,2,3,4,12', NULL, N'1', N'1', 14, N'1', N'SB-RPT001002007')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002029', N'001', N'001002', N'rptPdtPointWah', N'', N'', N'1,12,13', N'', N'1', N'1', 15, N'1', N'SB-RPT001002029')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002030', N'001', N'001002', N'rptRptInventoryPdtGrp', N'', N'', N'1,6,2,8', N'', N'1', N'1', 16, N'1', N'SB-RPT001002030')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002031', N'001', N'001002', N'rptRptInventoriesByBch', N'', N'', N'1,2,6,8,12,13', N'', N'1', N'1', 17, N'1', N'SB-RPT001002031')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002032', N'001', N'001002', N'rptReorderPointPerPdt', N'', N'', N'1,2,6,8,12,13', N'', N'1', N'1', 18, N'1', N'SB-RPT001002032')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002033', N'001', N'001002', N'rptPdtHisTnfIN', N'', N'', N'1,6,2,12,4,13', N'', N'1', N'1', 19, N'1', N'SB-RPT001002033')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002034', N'001', N'001002', N'rptPickingHistory', N'', N'', N'1,6,2,12,4,13', N'', N'1', N'1', 20, N'1', N'SB-RPT001002034')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002036', N'001', N'001002', N'rptPdtStock', N'', N'', N'1,2,3,5,6,12,13,28,49', N'', N'1', N'1', 22, N'1', N'SB-RPT001002036')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002037', N'001', N'001002', N'rptPdtHisTnfBch', N'', N'', N'1,6,2,4,12,13', N'', N'1', N'1', 23, N'1', N'SB-RPT001002037')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001002038', N'001', N'001002', N'rptRptInventoryTranfer', NULL, NULL, N'1,4,13,78,79', NULL, N'1', N'1', 24, N'1', N'SB-RPT001002038')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003001', N'001', N'001003', N'rptOpenJob', NULL, NULL, N'1,2,4,26,27,69', NULL, N'1', N'1', 1, N'1', N'SB-RPT001003001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003002', N'001', N'001003', N'#', N'', N'', N'1', N'', N'1', N'1', 2, N'1', N'SB-RPT001003002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003003', N'001', N'001003', N'rptSaleQuantation', NULL, NULL, N'1,4,27,63', NULL, N'1', N'1', 3, N'1', N'SB-RPT001003003')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003004', N'001', N'001003', N'rptRptDepositDoc', NULL, NULL, N'1,4,27,59', NULL, N'1', N'1', 4, N'1', N'SB-RPT001003004')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003005', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 5, N'1', N'SB-RPT001003005')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003006', N'001', N'001003', N'rptSaleQuantationBlc', NULL, NULL, N'1,4', NULL, N'1', N'1', 6, N'1', N'SB-RPT001003006')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003007', N'001', N'001003', N'rptPerchaseOrder', NULL, NULL, N'1,4,50,63,65,66', NULL, N'1', N'1', 7, N'1', N'SB-RPT001003007')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003008', N'001', N'001003', N'rptProductUnreceived', NULL, NULL, N'1,4,50,65,66', NULL, N'1', N'1', 8, N'1', N'SB-RPT001003008')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003009', N'001', N'001003', N'rptBuyHistoryPdt', NULL, NULL, N'1,2,4,8,9,13,49,50,53,54,63,64,65,66,67,68', NULL, N'1', N'1', 9, N'1', N'SB-RPT001003009')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003010', N'001', N'001003', N'rptTireDot', NULL, NULL, N'1,13,53,54,71,76', NULL, N'1', N'1', 10, N'1', N'SB-RPT001003010')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003011', N'001', N'001003', N'rptPricePdtGrp', NULL, NULL, N'1,13,47,48', NULL, N'1', N'1', 11, N'1', N'SB-RPT001003011')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003012', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 12, N'1', N'SB-RPT001003012')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003013', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 13, N'1', N'SB-RPT001003013')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003014', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 14, N'1', N'SB-RPT001003014')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003015', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 15, N'1', N'SB-RPT001003015')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003016', N'001', N'001003', N'rptPurCrOverDue', NULL, NULL, N'1,4,50,64,65,66', NULL, N'1', N'1', 16, N'1', N'SB-RPT001003016')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003017', N'001', N'001003', N'rptHisPayDePt', NULL, NULL, N'1,47,50,64', NULL, N'1', N'1', 17, N'1', N'SB-RPT001003017')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003018', N'001', N'001003', N'rptDailypayment', NULL, NULL, N'1,50,72', NULL, N'1', N'1', 18, N'1', N'SB-RPT001003018')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003019', N'001', N'001003', N'rptPurVatNew', NULL, NULL, N'1,2,4,50', NULL, N'1', N'1', 19, N'1', N'SB-RPT001003019')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003020', N'001', N'001003', N'rptCreditAging', NULL, NULL, N'1,2,4,50,63,64,65,66', NULL, N'1', N'1', 20, N'1', N'SB-RPT001003020')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003021', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 21, N'1', N'SB-RPT001003021')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003022', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 22, N'1', N'SB-RPT001003022')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003023', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 23, N'1', N'SB-RPT001003023')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003024', N'001', N'001003', N'rptDebtorOverdue', NULL, NULL, N'1,2,4,27', NULL, N'1', N'1', 24, N'1', N'SB-RPT001003024')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003025', N'001', N'001003', N'rptCreditDebtor', NULL, NULL, N'1,2,4,47', NULL, N'1', N'1', 25, N'1', N'SB-RPT001003025')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003026', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 26, N'1', N'SB-RPT001003026')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003027', N'001', N'001003', N'rptCustomerHistoryService', NULL, NULL, N'1,4,6,27,60', NULL, N'1', N'1', 27, N'1', N'SB-RPT001003027')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003028', N'001', N'001003', N'rptCstForCastByCar', NULL, NULL, N'1,27,60,4', NULL, N'1', N'1', 28, N'1', N'SB-RPT001003028')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003029', N'001', N'001003', N'rptCstLostCont', NULL, NULL, N'1,27,60,61', NULL, N'1', N'1', 29, N'1', N'SB-RPT001003029')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003030', N'001', N'001003', N'rptSatificationAnalysis', NULL, NULL, N'1,4', NULL, N'1', N'1', 30, N'1', N'SB-RPT001003030')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003031', N'001', N'001003', N'rptCstFollowAft', NULL, NULL, N'1,27,60,4', NULL, N'1', N'1', 31, N'1', N'SB-RPT001003031')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003032', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 32, N'1', N'SB-RPT001003032')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003033', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 33, N'1', N'SB-RPT001003033')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003034', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 34, N'1', N'SB-RPT001003034')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003035', N'001', N'001003', N'rptSaleconditiongroup', NULL, NULL, N'1', NULL, N'1', N'1', 35, N'1', N'SB-RPT001003035')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003036', N'001', N'001003', N'rptSaleByPaymentType', NULL, NULL, N'1,4,7', NULL, N'1', N'1', 36, N'1', N'SB-RPT001003036')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003037', N'001', N'001003', N'rptAverageDayToWeekSales', NULL, NULL, N'1,74,75', NULL, N'1', N'1', 37, N'1', N'SB-RPT001003037')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003038', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 38, N'1', N'SB-RPT001003038')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003039', N'001', N'001003', N'rptStkCountVariance', NULL, NULL, N'1,4', NULL, N'1', N'1', 39, N'1', N'SB-RPT001003039')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003040', N'001', N'001003', N'rptPoByBchByPdt', NULL, NULL, N'1,4,13', NULL, N'1', N'1', 40, N'1', N'SB-RPT001003040')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003041', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 41, N'1', N'SB-RPT001003041')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003042', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 42, N'1', N'SB-RPT001003042')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003043', N'001', N'001003', N'rptServiceByCustomer', NULL, NULL, N'1,27', NULL, N'1', N'1', 43, N'1', N'SB-RPT001003043')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003044', N'001', N'001003', N'#', NULL, NULL, N'1', NULL, N'1', N'1', 44, N'1', N'SB-RPT001003044')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003045', N'001', N'001003', N'rptIncomeFromCreditSystem', NULL, NULL, N'1,4,27', NULL, N'1', N'1', 45, N'1', N'SB-RPT001003045')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003046', N'001', N'001003', N'rptCstCreditMoneyFleet', NULL, NULL, N'1,4', NULL, N'1', N'1', 46, N'1', N'SB-RPT001003046')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003047', N'001', N'001003', N'rptPreviewCustomer', NULL, NULL, N'27,56,57,58', NULL, N'1', N'1', 47, N'1', N'SB-RPT001003047')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'001003048', N'001', N'001003', N'rptSaleGrpByCond', NULL, NULL, N'1,2,8,13,52,77', NULL, N'1', N'1', 48, N'1', N'SB-RPT001003048')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002001001', N'002', N'002001', N'rptRptSaleShopGroup', N'', N'', N'6,2,4', N'', N'1', N'1', 1, N'0', N'SB-RPT002001001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002001002', N'002', N'002001', N'rptSalePaymentSummary', N'', N'', N'1,2,3,7,4', N'', N'1', N'1', 2, N'0', N'SB-RPT002001002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002001003', N'002', N'002001', N'rptRptSalePayDetailVending', N'', N'', N'7,1,2,4', N'', N'1', N'1', 3, N'0', N'SB-RPT002001003')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002001006', N'002', N'002001', N'rptRetPdtVd', NULL, NULL, N'1,2,3,4', NULL, N'1', N'1', 6, N'1', N'SB-RPT002001006')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002002001', N'002', N'002002', N'rptRptInventory', N'', N'', N'1,6,2,3', N'', N'1', N'1', 1, N'1', N'SB-RPT002002001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002002002', N'001', N'001002', N'rptRptAdjStockVending', N'', N'', N'1,6,2,3,12,4', N'', N'1', N'1', 4, N'1', N'SB-RPT002002002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002002003', N'002', N'002002', N'rptTransferVendingOut', N'', N'', N'1,2,3,4,13', N'', N'1', N'1', 2, N'1', N'SB-RPT002002003')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'002002004', N'002', N'002002', N'rptRequisitionProductByDate', N'', N'', N'1,2,4,13', N'', N'1', N'1', 3, N'1', N'SB-RPT002002004')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001001', N'003', N'003001', N'rptRptChangeStaSale', N'', N'', N'1,6,2,38,4', N'', N'1', N'1', 8, N'1', N'SB-RPT003001001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001002', N'003', N'003001', N'rptRptOpenSysAdmin', N'', N'', N'1,6,2,38,4', N'', N'1', N'1', 1, N'1', N'SB-RPT003001002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001003', N'003', N'003001', N'rptRptTaxSaleLocker', N'', N'', N'11,2,4', N'', N'1', N'1', 0, N'1', N'SB-RPT003001003')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001004', N'003', N'003001', N'rptRptLocToPayment', N'', N'', N'1,2,3,7,4', N'', N'1', N'1', 0, N'1', N'SB-RPT003001004')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001005', N'003', N'003001', N'rptRptSaleByPaymentDetail', N'', N'', N'1,2,3,7,4', N'', N'1', N'1', 0, N'1', N'SB-RPT003001005')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001006', N'003', N'003001', N'rptDepositAccSlotSize', N'', N'', N'1,6,2,38,37,4', N'', N'1', N'1', 3, N'1', N'SB-RPT003001006')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001007', N'003', N'003001', N'rptRentAmountFollowCourier', N'', N'', N'4,14', N'', N'1', N'1', 0, N'1', N'SB-RPT003001007')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001008', N'003', N'003001', N'rptRentAmountDetail', N'', N'', N'1,2,3,4,15', N'', N'1', N'1', 0, N'1', N'SB-RPT003001008')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001009', N'003', N'003001', N'rptTimeDeposit', N'', N'', N'1,6,2,38,4', N'', N'1', N'1', 4, N'1', N'SB-RPT003001009')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001010', N'003', N'003001', N'rptRptBookingLocker', N'', N'', N'1,6,2,38,37,4,35,36', N'', N'1', N'1', 11, N'1', N'SB-RPT003001010')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001011', N'003', N'003001', N'rptLockerDetailDepositAmount', N'', N'', N'1,6,2,38,39,4', N'', N'1', N'1', 9, N'1', N'SB-RPT003001011')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001012', N'003', N'003001', N'rptLockerPaymentByBill', N'', N'', N'1,6,2,38,27,4', N'', N'1', N'1', 10, N'1', N'SB-RPT003001012')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001013', N'003', N'003001', N'rptRptDepositsNotPicked', N'', N'', N'1,6,2,38,39,4', N'', N'1', N'1', 5, N'1', N'SB-RPT003001013')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001014', N'003', N'003001', N'rptRptRecePtionByTime', N'', N'', N'1,6,2,38,4', N'', N'1', N'1', 6, N'1', N'SB-RPT003001014')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001015', N'003', N'003001', N'rptRptDetailReceiveDeposit', N'', N'', N'1,6,2,38,4', N'', N'1', N'1', 7, N'1', N'SB-RPT003001015')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'003001016', N'003', N'003001', N'rptLockerPayment', N'', N'', N'1,6,2,38,27,4', N'', N'1', N'1', 2, N'1', N'SB-RPT003001016')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001001', N'004', N'004001', N'rptCrdUseCard1', N'', N'', N'1,6,2,3,16,4', N'', N'1', N'1', 1, N'1', N'SB-RPT004001001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001002', N'004', N'004001', N'rptCrdCheckStatusCard', N'', N'', N'17,16,18,4,20,21', N'', N'1', N'1', 2, N'1', N'SB-RPT004001002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001003', N'004', N'004001', N'rptCrdTransferCardInfo', N'', N'', N'1,22,23,24,25,4', N'', N'1', N'1', 3, N'1', N'SB-RPT004001003')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001004', N'004', N'004001', N'rptCrdAdjustCashInCard', N'', N'', N'1,6,2,3,16,4', N'', N'1', N'1', 4, N'1', N'SB-RPT004001004')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001005', N'004', N'004001', N'rptCrdClearCardValueForReuse', N'', N'', N'1,17,16', N'', N'1', N'1', 5, N'1', N'SB-RPT004001005')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001006', N'004', N'004001', N'rptCrdCardNoActive', N'', N'', N'1,17,16,20,21', N'', N'1', N'1', 6, N'1', N'SB-RPT004001006')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001007', N'004', N'004001', N'rptCrdCardTimesUsed', N'', N'', N'1,17,16', N'', N'1', N'1', 7, N'1', N'SB-RPT004001007')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001008', N'004', N'004001', N'rptCrdCardBalance', N'', N'', N'1,18,4', N'', N'1', N'1', 8, N'1', N'SB-RPT004001008')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001009', N'004', N'004001', N'rptCrdCollectExpireCard', N'', N'', N'1,4', N'', N'1', N'1', 9, N'1', N'SB-RPT004001009')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001010', N'004', N'004001', N'rptCrdCardPrinciple', N'', N'', N'1,5,17', N'', N'1', N'1', 10, N'1', N'SB-RPT004001010')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001011', N'004', N'004001', N'rptCrdCardDetail', N'', N'', N'1,17,16,18,20,21', N'', N'1', N'1', 11, N'1', N'SB-RPT004001011')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001013', N'004', N'004001', N'rptCrdCheckCardUseInfo', N'', N'', N'1,6,2,3,16,19,18,4', N'', N'1', N'1', 13, N'0', N'SB-RPT004001013')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001015', N'004', N'004001', N'rptCrdUseCard2', N'', N'', N'1,6,2,3,16,19,18,4', N'', N'1', N'1', 15, N'0', N'SB-RPT004001015')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001016', N'004', N'004001', N'rptRedeemReturnCard', NULL, NULL, N'1,6,2,3,4', NULL, N'1', N'1', 16, N'1', N'SB-RPT004001016')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'004001017', N'004', N'004001', N'rptIncomeNotReturnCard', NULL, NULL, N'1,6,2,3,4', NULL, N'1', N'1', 17, N'1', N'SB-RPT004001017')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'005001001', N'005', N'005001', N'rptSaleShopByDate', N'', N'', N'1,2,4', N'', N'1', N'1', 1, N'1', N'SB-RPT005001001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'006001001', N'006', N'006001', N'rptADJPrice', N'', N'', N'1,13,4,46,47,48', N'', N'1', N'1', 1, N'1', N'SB-RPT006001001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'006001002', N'006', N'006001', N'rptADJPriceByGroup', N'', N'', N'1,13,4,46,47,48', N'', N'1', N'1', 2, N'1', N'SB-RPT006001002')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'006001003', N'006', N'006001', N'rptEntryProduct', NULL, NULL, N'1,2,3,6,8,9,53,54', NULL, N'1', N'1', 3, N'1', N'SB-RPT006001003')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'006001004', N'006', N'006001', N'rptEntryProductUnit', NULL, NULL, N'1,2,46', NULL, N'1', N'1', 4, N'1', N'SB-RPT006001004')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'007001001', N'007', N'007001', N'rptBuyPurSplByPdt', NULL, NULL, N'1,2,3,6,8,9,50,52,53,54', NULL, N'1', N'1', 1, N'1', N'SB-RPT007001001')
	INSERT [dbo].[TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES (N'007001002', N'007', N'007001', N'rptBuyByPdt', NULL, NULL, N'1,2,3,4,6,8,9,11,13,49,50,52,53,54', NULL, N'1', N'1', 2, N'1', N'SB-RPT007001002')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001001', 1, N'รายงาน - ยอดขายตามบิล', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001001', 2, N'Report Sale By Bill', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001002', 1, N'รายงาน - ยอดขายตามสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001002', 2, N'Report Sale By Product', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001003', 1, N'รายงาน - ยอดขายตามการชำระเงิน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001003', 2, N'Report Sale By Payment', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001004', 1, N'รายงาน - สินค้าขายดีตามจำนวน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001004', 2, N'Report Product Sale By Pos', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001005', 1, N'รายงาน - ยอดขายตามการชำระเงินแบบละเอียด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001005', 2, N'Report Sale Payment Detail', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001006', 1, N'รายงาน - ภาษีขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001006', 2, N'Report Tax Sale By Pos', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001007', 1, N'รายงาน - ภาษีขายตามวันที่', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001007', 2, N'Report Tax Sale By Date', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001008', 1, N'รายงาน - วิเคราะห์กำไรขาดทุนตามสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001008', 2, N'', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001010', 1, N'รายงาน - ยอดเงินขาด / เงินเกิน ของแคชเชียร์', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001010', 2, N'Report missing /over money of cashier', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001011', 1, N'รายงาน - ยอดเงินขาด / เงินเกิน ของแคชเชียร์ ประจำวัน (ละเอียด)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001011', 2, N'Report missing / over money Of daily cashier (details)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001012', 1, N'รายงาน - ยอดเงินขาด / เงินเกิน ของแคชเชียร์ (ประจำเดือน)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001012', 2, N'Report cash balance / excess cash of the Daily Cashier  (Monthly)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001014', 1, N'รายงานยอดขาย - ตามจุดขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001014', 2, N'Report Sales report - by point of sale', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001015', 1, N'รายงานยอดขาย - ตามแคชเชียร์', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001015', 2, N'Report ', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001016', 1, N'รายงานจำนวนขายประจำเดือน - ตามสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001016', 2, N'Report Monthly sales amount by product', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001017', 1, N'รายงาน - การคืนสินค้าตามวันที่', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001017', 2, N'', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001018', 1, N' รายงานการนำเงินฝากแบบละเอียด (สาขา-สำนักงานใหญ่)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001018', 2, N'', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001019', 1, N'รายงาน - ยอดขายสิ้นวัน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001019', 2, N'รายงาน - ยอดขายสิ้นวัน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001020', 1, N'รายงาน- ภาษีขาย (เต็มรูป)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001020', 2, N'Report TaxSale (Full)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001021', 1, N'รายงาน- ภาษีขายตามวันที่ (เต็มรูป)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001021', 2, N'Report TaxSale By Date (Full)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001022', 1, N'รายงาน- ยอดขายตามแคชเชียร์ - ตามเครื่องจุดขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001022', 2, N'Report Sale By Cashier-Pos ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001023', 1, N'รายงาน - การยกเลิกบิลตามวันที่', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001023', 2, N'Report cancellation by date', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001024', 1, N'รายงาน - การยกเลิกรายการตามวันที่', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001024', 2, N'Report - Cancel product list by date', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001025', 1, N'รายงาน - สินค้าขายดีตามมูลค่า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001025', 2, N'Report Best Product Sale By Value', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001026', 1, N'รายงาน - ยอดขายตามสมาชิก', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001026', 2, N'Sales report by membership', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001027', 1, N'รายงาน - แต้มสมาชิกแบบละเอียด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001027', 2, N'Report-SaleByCustomer', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001028', 1, N'รายงานยอดขายตามสาขาตามสินค้า-สินค้าชุด', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001028', 2, N'Report By branch by product - set product', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001029', 1, N'รายงาน - ยอดขายตามบิลตามวันที่ตามการชำระเงิน (ละเอียด)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001030', 1, N'รายงาน - ยอดขายตามวันที่ตามการชำระเงิน (สรุป)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001031', 1, N'รายงาน - ยอดขายตามบิลตามสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001032', 1, N'รายงาน - การขายสินค้าโปรโมชัน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001033', 1, N'รายงาน - การขายสินค้าโปรโมชัน ตามเอกสาร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001034', 1, N'รายงาน - เปรียบเทียบยอดขายตามสินค้า(MTD)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001035', 1, N'รายงาน - เปรียบเทียบยอดขายตามประเภทสินค้า(YTD)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001036', 1, N'รายงาน - การขายรอการชำระเงิน', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001036', 2, N'Report SalePending', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001001037', 1, N'รายงาน - ใบอนุญาตใช้งานไกล้หมดอายุ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002001', 1, N'รายงาน - สินค้าคงคลังตามคลังสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002001', 2, N'Report Inventory Center', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002002', 1, N'รายงาน - ความเคลื่อนไหวสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002002', 2, N'รายงาน - ความเคลื่อนไหวสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002006', 1, N'รายงาน - การเติมสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002007', 1, N'รายงาน - การรับเข้าสินค้าตามวันที่', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002007', 2, N'Report - the day''s imports', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002029', 1, N'รายงาน - สินค้าถึงจุดสั่งซื้อตามคลัง', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002030', 1, N'รายงาน - สินค้าคงเหลือ - กลุ่มสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002031', 1, N'รายงาน - สินค้าสินค้าคงเหลือ - ตามสาขา', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002032', 1, N'รายงาน - สินค้าถึงจุดสั่งซื้อตามสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002033', 1, N'รายงาน - ประวัติการรับเข้าสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002034', 1, N'รายงาน - ประวัติการเบิกออกสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002036', 1, N'รายงาน - สต๊อกสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002037', 1, N'รายงาน - ประวัติการโอนสินค้าระหว่างสาขา', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001002038', 1, N'รายงาน - โอนสินค้าระหว่างคลัง', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003001', 1, N'รายงานใบเปิดงาน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003002', 1, N'รายงานใบสั่งขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003003', 1, N'รายงานใบเสนอราคา', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003004', 1, N'รายงานใบรับเงินมัดจำ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003005', 1, N'รายงานใบสั่งขายกว่าทุน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003006', 1, N'รายงานใบเสนอราคาต่ำกว่าทุน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003007', 1, N'รายงานใบสั่งซื้อ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003008', 1, N'รายงานค้างรับสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003009', 1, N'รายงานประวัติซื้อสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003010', 1, N'รายงาน Dot ยาง', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003011', 1, N'รายงานร่องราคาสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003012', 1, N'รายงานเพิ่มหนี้เจ้าหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003013', 1, N'รายงานใบเพิ่มหนี้เจ้าหนี้ตามการรับเข้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003014', 1, N'รายงานใบลดหนี้เจ้าหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003015', 1, N'รายงานใบลดหนี้เจ้าหนี้ตามใบเบิกออก', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003016', 1, N'รายงานเจ้าหนี้ค้างชำระ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003017', 1, N'รายงานการจ่ายชำระเจ้าหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003018', 1, N'รายงานสรุปการจ่ายชำระประจำวัน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003019', 1, N'รายงานภาษีซื้อ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003020', 1, N'รายงานอายุเจ้าหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003021', 1, N'รายงานการวางบิล/รับชำระลูกหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003022', 1, N'รายงานเพิ่มหนี้ลูกหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003023', 1, N'รายงานใบลดหนี้ลูกหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003024', 1, N'รายงานลูกหนี้ค้างชำระ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003025', 1, N'รายงานอายุลูกหนี้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003026', 1, N'รายงานวิเคราะห์การซื้อตามเงื่อนไข', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003027', 1, N'รายงานประวัติการใช้บริการลูกค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003028', 1, N'รายงานบริการครั้งต่อไป', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003029', 1, N'รายงานลูกค้าที่ขาดการติดต่อ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003030', 1, N'รายงานวิเคราะห์ความพึงพอใจ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003031', 1, N'รายงานติดตาม3วันหลังให้บริการ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003032', 1, N'รายงานการรับ/จ่ายชำระเงินสดประจำวัน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003033', 1, N'รายงานทะเบียนรถยนต์ และทะเบียนลูกค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003034', 1, N'รายงานประวัติการใช้งานระบบ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003035', 1, N'รายงานยอดขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003036', 1, N'รายงานการขายตามประเภทการชำระเงิน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003037', 1, N'รายงานยอดขายเฉลี่ย (ชิ้น) ดามวันต่อสัปดาห์', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003038', 1, N'รายงานภาษีขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003039', 1, N'รายงานผลต่างการตรวจนับสต๊อก', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003040', 1, N'รายงานการสั่งซื้อตามสาขา', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003041', 1, N'รายงานรายรับรายจ่าย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003042', 1, N'รายงานการขายแยกตามประเภทคูปอง', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003043', 1, N'รายงานแสดงลูกค้าที่ต้องเข้าใช้บริการ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003044', 1, N'รายงานการให้บริการ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003045', 1, N'รายงานการใช้จากระบบสินเชื่อ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003046', 1, N'รายงานเบิกจ่ายกองยาน (ลูกค้า Fleet)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003047', 1, N'รายงานแสดงข้อมูลลูกค้า', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'001003048', 1, N'รายงานยอดขายตามเงื่อนไข', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001001', 1, N'รายงานภาษีขายตามกลุ่มร้านค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001001', 2, N'รายงานภาษีขายตามกลุ่มร้านค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001002', 1, N'รายงานยอดขายตามการชำระเงินแบบสรุป (Vending)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001002', 2, N'Report Sale Payment By Summary (Vending)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001003', 1, N'รายงานยอดขายตามการชำระเงินแบบละเอียด (Vending)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001003', 2, N'Report Sale Payment Detail (Vending)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001004', 1, N'รายงานสินค้าขายดี (Vending)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001004', 2, N'Report Best Sale (Vending)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001006', 1, N'รายงาน - การคืนสินค้าตามวันที่ (Vending)', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002001006', 2, N'Report - Return Product By Date (Vending)', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002002001', 1, N'รายงาน - สินค้าคงคลังตามตู้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002002001', 2, N'รายงาน - สินค้าคงคลังตามตู้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002002002', 1, N'รายงาน - การตรวจนับสต็อก [Vending]', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002002002', 2, N'Report Adjust Stock (Vending)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002002003', 1, N'รายงาน - นำสินค้าออกจากตู้', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'002002004', 1, N'รายงาน - การเบิกออกสินค้าตามวันที่', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001001', 1, N'รายงาน - ประวัติการเปลี่ยนสถานะช่องฝาก', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001001', 2, N'Report Change Status Sale', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001002', 1, N'รายงาน - การเปิดตู้โดยผู้ดูแลระบบ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001002', 2, N'Report Open System by Admin', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001003', 1, N'รายงาน - ภาษีขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001003', 2, N'รายงาน - ภาษีขาย', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001004', 1, N'รายงาน - ยอดขายตามการชำระเงิน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001004', 2, N'Report Sale Payment', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001005', 1, N'รายงาน - ยอดขายตามการชำระเงินแบบละเอียด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001005', 2, N'Report Sale By Payment Detail', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001006', 1, N'รายงาน - การฝากตามขนาดช่อง', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001006', 2, N'Report - Deposit according to channel size', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001007', 1, N'รายงาน - ยอดฝากตามบริษัทขนส่ง', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001007', 2, N'Report Deposit amount by carrier', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001008', 1, N'รายงาน - การฝากแบบละเอียด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001008', 2, N'Report - Detailed Deposit', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001009', 1, N'รายงาน - การฝากตามช่วงเวลา', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001009', 2, N'Report  Time Deposit', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001010', 1, N'รายงาน - การจองช่องฝากของ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001010', 2, N'Report  Booking deposit boxes', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001011', 1, N'รายงาน - ยอดฝากแบบละเอียด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001011', 2, N'Report  Detailed Deposit', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001012', 1, N'รายงาน - การชำระเงิน ตามบิล', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001012', 2, N'Report - Bill payment', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001013', 1, N'รายงาน - การฝากที่ยังไม่มารับ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001013', 2, N'Report - DepositsNotPicked', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001014', 1, N'รายงาน - การรับตามช่วงเวลา', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001014', 2, N'Report -  RecePtionByTime', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001015', 1, N'รายงาน - การรับ-ฝากแบบละเอียด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001015', 2, N'Report  Receiving  Deposit in detail', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001016', 1, N'รายงาน- การชำระเงิน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'003001016 ', 2, N'Report  Payment', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001001', 1, N'รายงานข้อมูลการใช้บัตร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001001', 2, N'Report card usage information', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001002', 1, N'รายงานตรวจสอบสถานะบัตร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001002', 2, N'Card Status Report', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001003', 1, N' รายงานโอนข้อมูลบัตร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001003', 2, N'Card data transfer report', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001004', 1, N'รายงานการปรับมูลค่าเงินสดในบัตร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001004', 2, N'Report the adjustment of cash value on cards', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001005', 1, N'รายงานการล้างมูลค่าบัตรเพื่อกลับมาใช้งานใหม่', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001005', 2, N'Report the value of cards to be reused', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001006', 1, N'รายงานการลบข้อมูลบัตรที่ไม่ใช้งาน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001006', 2, N'Report unused card data deletion', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001007', 1, N'รายงานจำนวนรอบการใช้บัตร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001007', 2, N'Card usage cycle report', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001008', 1, N'รายงานบัตรคงเหลือ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001008', 2, N'Balance card report', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001009', 1, N'รายงานยอดสะสมบัตรหมดอายุ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001009', 2, N'Card expiration report', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001010', 1, N'รายงานรายการต้นงวดบัตรและเงินสด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001010', 2, N'Report of beginning of card and cash period', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001011', 1, N'รายงานข้อมูลบัตร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001011', 2, N'Report card info', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001012', 1, N'รายงาน - ตรวจสอบการเติมเงิน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001012', 2, N'Report Check Top-up', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001013', 1, N'รายงานตรวจสอบข้อมูลการใช้บัตร', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001013', 2, N'Card Usage Data Audit Report', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001015', 1, N'รายงานข้อมูลการใช้บัตร (แบบละเอียด)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001015', 2, N'Report Card usage data (detailed)', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001016', 1, N'รายงาน - การแลก/คืน บัตรเงินสด', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001016', 2, N'Report - Redemption / Return of Cash Card', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001017', 1, N'รายงาน - รายได้เนื่องจากการไม่คืนบัตร', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'004001017', 2, N'Report -IncomeNotReturnCard', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001001', 1, N'รายงานยอดขายร้านค้า-ตามวันที่', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001001', 2, N'Report Store sales by date', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001002', 1, N'รายงานยอดขายร้านค้า-ตามร้านค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001002', 2, N'Report Shop sales by Shop', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001003', 1, N'รายงานการเคลื่อนไหวบัตร-แบบสรุป', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001003', 2, N'Report Card movement Summary form', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001004', 1, N'รายงานการเคลื่อนไหวบัตร-แบบละเอียด', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001004', 2, N'Report Card movement detailed', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001005', 1, N'รายงานสรุปยอดเงินคงเหลือบัตรไม่ได้แลกคืน', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'005001005', 2, N'Report Card summary is not refundable', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'006001001', 1, N'รายงานการปรับราคาสินค้า', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'006001001', 2, N'Report Price Adjust ', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'006001002', 1, N'รายงานการปรับราคาสินค้าตามกลุ่มราคา', NULL)
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'006001002', 2, N'รายงานการปรับราคาสินค้าตามกลุ่มราคา', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'006001003', 1, N'รายงานสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'006001004', 1, N'รายงานหน่วยนับสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'007001001', 1, N'รายงาน - ยอดซื้อตามผู้จำหน่ายตามสินค้า', N'')
	INSERT [dbo].[TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES (N'007001002', 1, N'รายงาน - สรุปยอดซื้อตามสินค้า', N'')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (1, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (2, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (3, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (4, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (5, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (6, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (7, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (8, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (9, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (10, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (11, 1, 0, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (12, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (13, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (14, 1, 1, N'G5')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (15, 1, 1, N'G6')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (16, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (17, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (18, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (19, 1, 1, N'G8')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (20, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (21, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (22, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (23, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (24, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (25, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (26, 1, 0, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (27, 1, 1, N'G9')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (28, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (29, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (30, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (31, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (32, 1, 1, N'G1')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (33, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (34, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (35, 1, 1, N'G6')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (36, 1, 1, N'G6')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (37, 1, 1, N'G6')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (38, 1, 1, N'G6')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (39, 1, 1, N'G6')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (40, 1, 1, N'G0')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (41, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (42, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (44, 1, 1, N'G7')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (45, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (46, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (47, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (48, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (49, 1, 0, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (50, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (51, 1, 1, NULL)
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (52, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (53, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (54, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (55, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (56, 1, 0, N'G9')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (57, 1, 1, N'G9')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (58, 1, 1, N'G9')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (59, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (60, 1, 1, N'G9')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (61, 1, 0, N'G9')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (63, 1, 0, N'G10')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (64, 1, 0, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (65, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (66, 1, 1, N'G3')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (67, 1, 0, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (68, 1, 0, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (69, 1, 0, N'G10')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (70, 1, 0, N'G10')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (71, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (72, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (73, 1, 0, N'G10')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (74, 1, 1, N'G2')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (75, 1, 0, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (76, 1, 1, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (77, 1, 1, N'G10')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (78, 1, 0, N'G4')
	INSERT [dbo].[TSysReportFilter] ([FTRptFltCode], [FTRptFltStaFrm], [FTRptFltStaTo], [FTRptGrpFlt]) VALUES (79, 1, 0, N'G4')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (20, N'2', N'')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (21, N'2', N'')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (22, N'2', N'')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (23, N'2', N'')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (24, N'2', N'')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (25, N'2', N'')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (18, N'2', N'Card Status')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (73, N'2', N'backlog type')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (44, N'2', N'BankAcc No.')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (35, N'2', N'Booking Status ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (36, N'2', N'Booking System ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (32, N'2', N'Box Receive')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (31, N'2', N'Box Transfer')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (1, N'2', N'Branch')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (53, N'2', N'Brand')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (16, N'2', N'Card No.')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (17, N'2', N'Card Type')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (45, N'2', N'Cashier')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (77, N'2', N'Condition Time')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (14, N'2', N'Courier')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (57, N'2', N'Create On')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (27, N'2', N'Customer')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (4, N'2', N'Date')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (52, N'2', N'Date')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (74, N'2', N'Day(Mon-Sun)')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (63, N'2', N'Doc Status')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (47, N'2', N'Effective Date')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (48, N'2', N'Effective Price Group')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (19, N'2', N'Employee')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (55, N'2', N'Expires')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (56, N'2', N'Gender')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (51, N'2', N'GroupReport')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (58, N'2', N'Level Customer')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (38, N'2', N'Locker')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (37, N'2', N'Locker Box Size')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (39, N'2', N'Locker Channel')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (71, N'2', N'Manufacture Year')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (6, N'2', N'Merchant')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (11, N'2', N'Merchant')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (54, N'2', N'Model')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (28, N'2', N'Month')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (42, N'2', N'Month')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (49, N'2', N'Moving status')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (72, N'2', N'Payment Date')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (7, N'2', N'PaymentType')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (41, N'2', N'PaymentType')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (3, N'2', N'Pos')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (26, N'2', N'Pos Type')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (13, N'2', N'Product')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (75, N'2', N'Product Detail')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (8, N'2', N'Product Group')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (9, N'2', N'Product Type')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (46, N'2', N'Product Unit')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (15, N'2', N'Rack')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (2, N'2', N'Shop')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (30, N'2', N'Shop Receive')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (29, N'2', N'Shop Transfer')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (59, N'2', N'Status Deposit')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (64, N'2', N'Status Get Money / Pay')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (69, N'2', N'Status Open Job')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (70, N'2', N'Status Payment')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (68, N'2', N'Status Vat')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (50, N'2', N'Supplier')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (65, N'2', N'Supplier Group')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (66, N'2', N'Supplier Type')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (76, N'2', N'Tire Dot')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (10, N'2', N'Top Sale')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (67, N'2', N'Type Price')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (12, N'2', N'Warehouse')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (79, N'2', N'Warehouse In')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (78, N'2', N'Warehouse Out')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (34, N'2', N'Warehouse Receive')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (33, N'2', N'Warehouse Transfer')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (5, N'2', N'Year')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (15, N'1', N'กลุ่มช่อง')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (6, N'1', N'กลุ่มธุรกิจ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (11, N'1', N'กลุ่มธุรกิจ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (65, N'1', N'กลุ่มผู้จำหน่าย')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (48, N'1', N'กลุ่มราคาที่มีผล')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (51, N'1', N'กลุ่มรายงาน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (8, N'1', N'กลุ่มสินค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (37, N'1', N'ขนาดช่องฝาก')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (76, N'1', N'ข้อมูล Dot ยาง')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (75, N'1', N'ข้อมูลสินค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (79, N'1', N'คลังรับ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (12, N'1', N'คลังสินค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (34, N'1', N'คลังสินค้าที่รับโอน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (33, N'1', N'คลังสินค้าที่โอน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (78, N'1', N'คลังโอน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (3, N'1', N'เครื่องจุดขาย')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (45, N'1', N'แคชเชียร์')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (77, N'1', N'เงื่อนไขช่วงเวลา')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (36, N'1', N'จองจากระบบ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (61, N'1', N'จำนวนวันที่ขาดการติดต่อ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (42, N'1', N'ช่วงเดือน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (39, N'1', N'ช่องฝาก')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (67, N'1', N'ใช้ราคาขาย')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (28, N'1', N'เดือน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (32, N'1', N'ตู้ที่รับโอน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (31, N'1', N'ตู้ที่โอน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (38, N'1', N'ตู้ฝาก')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (60, N'1', N'ทะเบียนรถ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (14, N'1', N'บริษัทขนส่ง')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (41, N'1', N'ประเภทการชำระเงิน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (26, N'1', N'ประเภทเครื่องจุดขาย')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (7, N'1', N'ประเภทชำระเงิน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (17, N'1', N'ประเภทบัตร')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (22, N'1', N'ประเภทบัตรเดิม')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (23, N'1', N'ประเภทบัตรใหม่')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (66, N'1', N'ประเภทผู้จำหน่าย')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (73, N'1', N'ประเภทรายการค้าง')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (9, N'1', N'ประเภทสินค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (5, N'1', N'ปี')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (71, N'1', N'ปีผลิต')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (50, N'1', N'ผู้จำหน่าย')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (19, N'1', N'พนักงาน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (56, N'1', N'เพศ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (53, N'1', N'ยี่ห้อ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (58, N'1', N'ระดับ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (2, N'1', N'ร้านค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (30, N'1', N'ร้านค้าที่รับโอน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (29, N'1', N'ร้านค้าที่โอน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (54, N'1', N'รุ่น')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (27, N'1', N'ลูกค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (44, N'1', N'เลขที่บัญชี')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (74, N'1', N'วัน(จ-อา)')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (4, N'1', N'วันที่')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (72, N'1', N'วันที่ชำระ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (21, N'1', N'วันที่บัตรหมดอายุ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (47, N'1', N'วันที่มีผล')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (20, N'1', N'วันที่เริ่มใช้งานบัตร')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (57, N'1', N'วันที่ลงทะเบียน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (52, N'1', N'วันที่เอกสาร')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (64, N'1', N'สถานะ รับ/จ่ายเงิน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (70, N'1', N'สถานะการชำระ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (49, N'1', N'สถานะเคลื่อนไหว')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (35, N'1', N'สถานะจอง')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (18, N'1', N'สถานะบัตร')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (69, N'1', N'สถานะเปิดงาน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (68, N'1', N'สถานะภาษีขาย')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (59, N'1', N'สถานะมัดจำ')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (63, N'1', N'สถานะเอกสาร')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (1, N'1', N'สาขา')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (13, N'1', N'สินค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (46, N'1', N'หน่วยสินค้า')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (55, N'1', N'หมดอายุภายใน')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (16, N'1', N'หมายเลขบัตร')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (24, N'1', N'หมายเลขบัตรเดิม')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (25, N'1', N'หมายเลขบัตรใหม่')
	INSERT [dbo].[TSysReportFilter_L] ([FTRptFltCode], [FNLngID], [FTRptFltName]) VALUES (10, N'1', N'อันดับสูงสุด')

--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.05', getdate() , 'เมนู Report ใหม่', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.06') BEGIN
	UPDATE TSysSyncData_L SET FTSynName='ข้อมูลการปิดรอบ LMS' WHERE FNSynSeqNo=128 AND FNLngID=1
--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.06', getdate() , 'อัพเดทชื่อ จาก NET', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.07') BEGIN
	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig WITH(NOLOCK) WHERE FTSysCode = 'nPS_LimitFileSize' AND FTSysSeq = '1' ) BEGIN
		INSERT [dbo].[TSysConfig] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FTGmnCode], [FTSysStaAlwEdit], [FTSysStaDataType], [FNSysMaxLength], [FTSysStaDefValue], [FTSysStaDefRef], [FTSysStaUsrValue], [FTSysStaUsrRef], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'nPS_LimitFileSize', N'PS', N'Sale', N'1', N'PS', N'1', N'1', N'3', N'100', N'', N'100', N'', GETDATE(), N'Jirayu S.', GETDATE(), N'Jirayu S.')
	END
	
	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig_L WITH(NOLOCK) WHERE FTSysCode = 'nPS_LimitFileSize' AND FTSysSeq = '1' AND FNLngID=1) BEGIN
		INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) VALUES (N'nPS_LimitFileSize', N'PS', N'Sale', N'1', 1, N'ขนาดไฟล์แนบสูงสุด (MB)', N'กำหนดเอง: ขนาดไฟล์หน่วย (MB)', N'')
	END
	
	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig_L WITH(NOLOCK) WHERE FTSysCode = 'nPS_LimitFileSize' AND FTSysSeq = '1' AND FNLngID=2) BEGIN
		INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) VALUES (N'nPS_LimitFileSize', N'PS', N'Sale', N'1', 2, N'Max size file attatch (MB)', N'Value: FileSize unit (MB)', N'')
	END
	

	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig WITH(NOLOCK) WHERE FTSysCode = 'nPS_PrnSlipDateTime' AND FTSysSeq = '1' ) BEGIN
		INSERT [dbo].[TSysConfig] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FTGmnCode], [FTSysStaAlwEdit], [FTSysStaDataType], [FNSysMaxLength], [FTSysStaDefValue], [FTSysStaDefRef], [FTSysStaUsrValue], [FTSysStaUsrRef], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'nPS_PrnSlipDateTime', N'PS', N'Sale', N'1', N'PS', N'1', N'1', N'1', N'1', N'', N'1', N'', GETDATE(), N'Jirayu S.', GETDATE(), N'Jirayu S.')
	END
	
	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig_L WITH(NOLOCK) WHERE FTSysCode = 'nPS_PrnSlipDateTime' AND FTSysSeq = '1' AND FNLngID=1) BEGIN
		INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) VALUES (N'nPS_PrnSlipDateTime', N'PS', N'Sale', N'1', 1, N'รูปแบบการพิมพ์วันที่และเวลาบนสลิป', N'1:วันที่ขาย, 2:วันที่ระบบ', N'')
	END
	
	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig_L WITH(NOLOCK) WHERE FTSysCode = 'nPS_PrnSlipDateTime' AND FTSysSeq = '1' AND FNLngID=2) BEGIN
		INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) VALUES (N'nPS_PrnSlipDateTime', N'PS', N'Sale', N'1', 2, N'Printing slip date and time ', N'1:Sale Date, 2:System Date', N'')
	END
	

	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdt WITH(NOLOCK) WHERE FTPdtCode = 'CREDITNOTE' ) BEGIN
		INSERT [dbo].[TCNMPdt] ([FTPdtCode], [FTPdtStkControl], [FTPdtGrpControl], [FTPdtForSystem], [FCPdtQtyOrdBuy], [FCPdtCostDef], [FCPdtCostOth], [FCPdtCostStd], [FCPdtMin], [FCPdtMax], [FTPdtPoint], [FCPdtPointTime], [FTPdtType], [FTPdtSaleType], [FTPdtSetOrSN], [FTPdtStaSetPri], [FTPdtStaSetShwDT], [FTPdtStaAlwDis], [FTPdtStaAlwReturn], [FTPdtStaVatBuy], [FTPdtStaVat], [FTPdtStaActive], [FTPdtStaAlwReCalOpt], [FTPdtStaCsm], [FTTcgCode], [FTPgpChain], [FTPtyCode], [FTPbnCode], [FTPmoCode], [FTVatCode], [FTEvhCode], [FDPdtSaleStart], [FDPdtSaleStop], [FTPdtStaSetPrcStk], [FTPdtStaAlwBook], [FCPdtCostType], [FTPdtCtrlRole], [FCPdtDepAmtPer], [FTPdtStaLot], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FCPdtCostLast], [FTPdtStaAlwWHTax]) 
		VALUES (N'CREDITNOTE', N'2', NULL, N'1', CAST(0.0000 AS Numeric(18, 4)), NULL, NULL, CAST(0.0000 AS Numeric(18, 4)), NULL, CAST(0.0000 AS Numeric(18, 4)), N'1', NULL, N'5', N'2', N'1', N'1', N'2', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'', N'', N'', N'', N'', N'00001', NULL, CAST(N'2021-09-20T00:00:00.000' AS DateTime), CAST(N'2022-09-20T00:00:00.000' AS DateTime), N'1', N'2', NULL, N'', NULL, N'2', GETDATE(), N'System', GETDATE(), N'System', NULL, N'2')
	END
	
	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdt WITH(NOLOCK) WHERE FTPdtCode = 'DEBITNOTE' ) BEGIN
		INSERT [dbo].[TCNMPdt] ([FTPdtCode], [FTPdtStkControl], [FTPdtGrpControl], [FTPdtForSystem], [FCPdtQtyOrdBuy], [FCPdtCostDef], [FCPdtCostOth], [FCPdtCostStd], [FCPdtMin], [FCPdtMax], [FTPdtPoint], [FCPdtPointTime], [FTPdtType], [FTPdtSaleType], [FTPdtSetOrSN], [FTPdtStaSetPri], [FTPdtStaSetShwDT], [FTPdtStaAlwDis], [FTPdtStaAlwReturn], [FTPdtStaVatBuy], [FTPdtStaVat], [FTPdtStaActive], [FTPdtStaAlwReCalOpt], [FTPdtStaCsm], [FTTcgCode], [FTPgpChain], [FTPtyCode], [FTPbnCode], [FTPmoCode], [FTVatCode], [FTEvhCode], [FDPdtSaleStart], [FDPdtSaleStop], [FTPdtStaSetPrcStk], [FTPdtStaAlwBook], [FCPdtCostType], [FTPdtCtrlRole], [FCPdtDepAmtPer], [FTPdtStaLot], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FCPdtCostLast], [FTPdtStaAlwWHTax]) 
		VALUES (N'DEBITNOTE', N'2', NULL, N'1', CAST(0.0000 AS Numeric(18, 4)), CAST(0.0000 AS Numeric(18, 4)), NULL, CAST(0.0000 AS Numeric(18, 4)), NULL, CAST(0.0000 AS Numeric(18, 4)), N'1', NULL, N'5', N'2', N'1', N'1', N'2', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'', N'', N'', N'', N'', N'00001', NULL, CAST(N'2021-09-24T00:00:00.000' AS DateTime), CAST(N'2022-09-24T00:00:00.000' AS DateTime), N'1', N'2', NULL, N'', NULL, N'2', GETDATE(), N'System', GETDATE(), N'System', NULL, N'2')
	END
	
	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdt_L WITH(NOLOCK) WHERE FTPdtCode = 'CREDITNOTE' AND FNLngID = 1 ) BEGIN
		INSERT [dbo].[TCNMPdt_L] ([FTPdtCode], [FNLngID], [FTPdtName], [FTPdtNameOth], [FTPdtNameABB], [FTPdtRmk]) 
		VALUES (N'CREDITNOTE', 1, N'CREDITNOTE', N'CREDITNOTE', N'CREDITNOTE', N'')
	END
	
	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdt_L WITH(NOLOCK) WHERE FTPdtCode = 'DEBITNOTE' AND FNLngID = 1 ) BEGIN
		INSERT [dbo].[TCNMPdt_L] ([FTPdtCode], [FNLngID], [FTPdtName], [FTPdtNameOth], [FTPdtNameABB], [FTPdtRmk]) 
		VALUES (N'DEBITNOTE', 1, N'DEBITNOTE', N'DEBITNOTE', N'DEBITNOTE', N'')
	END
	
	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdtBar WITH(NOLOCK) WHERE FTPdtCode = 'CREDITNOTE' AND FTBarCode = 'CREDITNOTE' ) BEGIN
		INSERT [dbo].[TCNMPdtBar] ([FTPdtCode], [FTBarCode], [FNBarRefSeq], [FTFhnRefCode], [FTPunCode], [FTBarStaUse], [FTBarStaAlwSale], [FTBarStaByGen], [FTPlcCode], [FNPldSeq], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'CREDITNOTE', N'CREDITNOTE', 0, NULL, N'CN', N'1', N'1', NULL, N'', NULL, GETDATE(), N'System', GETDATE(), N'System')
	END
	
	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdtBar WITH(NOLOCK) WHERE FTPdtCode = 'DEBITNOTE' AND FTBarCode = 'DEBITNOTE' ) BEGIN
		INSERT [dbo].[TCNMPdtBar] ([FTPdtCode], [FTBarCode], [FNBarRefSeq], [FTFhnRefCode], [FTPunCode], [FTBarStaUse], [FTBarStaAlwSale], [FTBarStaByGen], [FTPlcCode], [FNPldSeq], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'DEBITNOTE', N'DEBITNOTE', 0, NULL, N'DN', N'1', N'1', NULL, N'', NULL, GETDATE(), N'System', GETDATE(), N'System')
	END
	
	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdtPackSize WITH(NOLOCK) WHERE FTPdtCode = 'CREDITNOTE' AND FTPunCode = 'CN' ) BEGIN
		INSERT [dbo].[TCNMPdtPackSize] ([FTPdtCode], [FTPunCode], [FCPdtUnitFact], [FCPdtPriceRET], [FCPdtPriceWHS], [FCPdtPriceNET], [FTPdtGrade], [FCPdtWeight], [FTClrCode], [FTPszCode], [FTPdtUnitDim], [FTPdtPkgDim], [FTPdtStaAlwPick], [FTPdtStaAlwPoHQ], [FTPdtStaAlwBuy], [FTPdtStaAlwSale], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'CREDITNOTE', N'CN', CAST(1.0000 AS Numeric(18, 4)), NULL, NULL, NULL, NULL, CAST(0.0000 AS Numeric(18, 4)), NULL, NULL, NULL, NULL, '1', '1', '1', '1', GETDATE(), N'System', GETDATE(), N'System')
	END
	
	IF NOT EXISTS(SELECT FTPdtCode FROM TCNMPdtPackSize WITH(NOLOCK) WHERE FTPdtCode = 'DEBITNOTE' AND FTPunCode = 'DN' ) BEGIN
		INSERT [dbo].[TCNMPdtPackSize] ([FTPdtCode], [FTPunCode], [FCPdtUnitFact], [FCPdtPriceRET], [FCPdtPriceWHS], [FCPdtPriceNET], [FTPdtGrade], [FCPdtWeight], [FTClrCode], [FTPszCode], [FTPdtUnitDim], [FTPdtPkgDim], [FTPdtStaAlwPick], [FTPdtStaAlwPoHQ], [FTPdtStaAlwBuy], [FTPdtStaAlwSale], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'DEBITNOTE', N'DN', CAST(1.0000 AS Numeric(18, 4)), NULL, NULL, NULL, NULL, CAST(0.0000 AS Numeric(18, 4)), NULL, NULL, NULL, NULL, '1', '1', '1', '1', GETDATE(), N'System', GETDATE(), N'System')
	END
	
	IF NOT EXISTS(SELECT FTPunCode FROM TCNMPdtUnit WITH(NOLOCK) WHERE FTPunCode = 'CN' ) BEGIN
		INSERT [dbo].[TCNMPdtUnit] ([FTPunCode], [FTAgnCode], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'CN', N'', GETDATE(), N'System', GETDATE(), N'System')
	END
	
	IF NOT EXISTS(SELECT FTPunCode FROM TCNMPdtUnit WITH(NOLOCK) WHERE FTPunCode = 'DN' ) BEGIN
		INSERT [dbo].[TCNMPdtUnit] ([FTPunCode], [FTAgnCode], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'DN', N'', GETDATE(), N'System', GETDATE(), N'System')
	END
	
	IF NOT EXISTS(SELECT FTPunCode FROM TCNMPdtUnit_L WITH(NOLOCK) WHERE FTPunCode = 'CN' AND FNLngID = 1 ) BEGIN
		INSERT [dbo].[TCNMPdtUnit_L] ([FTPunCode], [FNLngID], [FTPunName]) 
		VALUES (N'CN', 1, N'CN')
	END
	
	IF NOT EXISTS(SELECT FTPunCode FROM TCNMPdtUnit_L WITH(NOLOCK) WHERE FTPunCode = 'DN' AND FNLngID = 1 ) BEGIN
		INSERT [dbo].[TCNMPdtUnit_L] ([FTPunCode], [FNLngID], [FTPunName]) 
		VALUES (N'DN', 1, N'DN')
	END
	
--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.07', getdate() , 'อัพเดทข้อมูลจาก NET', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.08') BEGIN
--ทุกครั้งที่รันสคริปใหม่
	UPDATE TCNTAuto SET FTSatDefNum='000001', FTSatDefFmtAll='DNBCHYY######', FTSatUsrNum='000001', FTSatUsrFmtAll='DNBCHYY######', FDLastUpdOn='2021-12-20 23:43:00' WHERE FTSatTblName='TPSTTaxHD' AND FTSatStaDocType='10'
	UPDATE TCNTAuto SET FTSatDefNum='000001', FTSatDefFmtAll='CNBCHYY######', FTSatUsrNum='000001', FTSatUsrFmtAll='CNBCHYY######', FDLastUpdOn='2021-12-20 23:43:00' WHERE FTSatTblName='TPSTTaxHD' AND FTSatStaDocType='11'
	UPDATE TCNTAuto SET FTSatDefNum='0000001', FTSatDefFmtAll='WBCHYY#######', FTSatUsrNum='0000001', FTSatUsrFmtAll='WBCHYY#######', FDLastUpdOn='2021-12-20 23:43:00' WHERE FTSatTblName='TPSTWhTaxHD' AND FTSatStaDocType='0'

	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig WITH(NOLOCK) WHERE FTSysCode = 'tDoc_PdtDefault' AND FTSysSeq = '1' ) BEGIN
		INSERT [dbo].[TSysConfig] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FTGmnCode], [FTSysStaAlwEdit], [FTSysStaDataType], [FNSysMaxLength], [FTSysStaDefValue], [FTSysStaDefRef], [FTSysStaUsrValue], [FTSysStaUsrRef], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'tDoc_PdtDefault', N'CN', N'ProductDefNewDoc', N'1', N'XDOC', N'1', N'0', N'99999', NULL, NULL, NULL, NULL, CAST(N'2021-12-07T00:00:00.000' AS DateTime), N'Kitpipat', CAST(N'2021-12-07T00:00:00.000' AS DateTime), N'Kitpipat')
	END

	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig_L WITH(NOLOCK) WHERE FTSysCode = 'tDoc_PdtDefault' AND FTSysSeq = '1' ) BEGIN
		INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) 
		VALUES (N'tDoc_PdtDefault', N'CN', N'ProductDefNewDoc', N'1', 1, N'รายการสินค้าตั้งต้นเอกสารใหม่', N'กำหนดรหัสสินค้าตั้งต้น โดยกำหนดได้มากกว่า 1 รายการ ขั่นด้วยเครื่องหมายคอมม่า ,', NULL)
	END

	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig WITH(NOLOCK) WHERE FTSysCode = 'tDoc_PdtDefault' AND FTSysSeq = '1' ) BEGIN
		INSERT [dbo].[TSysConfig] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FTGmnCode], [FTSysStaAlwEdit], [FTSysStaDataType], [FNSysMaxLength], [FTSysStaDefValue], [FTSysStaDefRef], [FTSysStaUsrValue], [FTSysStaUsrRef], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'tDoc_PdtDefault', N'CN', N'ProductDefNewDoc', N'1', N'XDOC', N'1', N'0', N'99999', NULL, NULL, NULL, NULL, CAST(N'2021-12-07T00:00:00.000' AS DateTime), N'Kitpipat', CAST(N'2021-12-07T00:00:00.000' AS DateTime), N'Kitpipat')
	END

	IF NOT EXISTS(SELECT FTSysCode FROM TSysConfig_L WITH(NOLOCK) WHERE FTSysCode = 'tDoc_PdtDefault' AND FTSysSeq = '1' ) BEGIN
		INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) 
		VALUES (N'tDoc_PdtDefault', N'CN', N'ProductDefNewDoc', N'1', 1, N'รายการสินค้าตั้งต้นเอกสารใหม่', N'กำหนดรหัสสินค้าตั้งต้น โดยกำหนดได้มากกว่า 1 รายการ ขั่นด้วยเครื่องหมายคอมม่า ,', NULL)

		INSERT [dbo].[TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) 
		VALUES (N'tDoc_PdtDefault', N'CN', N'ProductDefNewDoc', N'1', 2, N'รายการสินค้าตั้งต้นเอกสารใหม่', N'กำหนดรหัสสินค้าตั้งต้น โดยกำหนดได้มากกว่า 1 รายการ ขั่นด้วยเครื่องหมายคอมม่า ,', NULL)
	END

	--คลังของเสีย
	INSERT INTO [TCNSListObj] ([FTObjCode], [FTAppode], [FTObjTable], [FTObjStaUse]) VALUES ('10', 'SB', 'TCNMWaHouse', '1');
	INSERT INTO [TCNSListObj_L] ([FTObjCode], [FNLngID], [FTObjName]) VALUES ('10', '1', 'คลังของเสีย');

	--ประเภทการชำระเงิน มีผลต่อใบนำฝาก
	INSERT INTO [TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) VALUES ('tPS_RcvCash', 'CN', 'RcvCash', '1', '1', 'ประเภทการชำระเงินแบบเงินสด', 'กำหนดประเภทการชำระเงิน แบบเงินสด กำหนดได้มากกว่า 1 ขั่นด้วยเครื่องหมายคอมม่า (,)', '-');
	INSERT INTO [TSysConfig] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FTGmnCode], [FTSysStaAlwEdit], [FTSysStaDataType], [FNSysMaxLength], [FTSysStaDefValue], [FTSysStaDefRef], [FTSysStaUsrValue], [FTSysStaUsrRef], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('tPS_RcvCash', 'CN', 'RcvCash', '1', 'MPOS', '1', '6', '99999999', NULL, NULL, NULL, NULL, '2021-12-21 00:00:00.000', 'Kitpipat', '2021-12-21 00:00:00.000', 'Kitpipat');

	--อนุญาตตรวจสอบสต๊อกก่อนโอน
	INSERT INTO [TSysConfig_L] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FNLngID], [FTSysName], [FTSysDesc], [FTSysRmk]) VALUES ('tDoc_ChkStkTranfer', 'CN', 'ChkStkTranfer', '1', '1', 'อนุญาต ตรวจสอบสต๊อกก่อนโอนสินค้า', 'อนุญาต ตรวจสอบสต๊อกก่อนโอนสินค้า 1 : ตรวจสอบ , 2 : ไม่ตรวจสอบ', '-');
	INSERT INTO [TSysConfig] ([FTSysCode], [FTSysApp], [FTSysKey], [FTSysSeq], [FTGmnCode], [FTSysStaAlwEdit], [FTSysStaDataType], [FNSysMaxLength], [FTSysStaDefValue], [FTSysStaDefRef], [FTSysStaUsrValue], [FTSysStaUsrRef], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('tDoc_ChkStkTranfer', 'CN', 'ChkStkTranfer', '1', 'TRANFER', '1', '1', '1', '2', NULL, '2', NULL, '2021-12-21 00:00:00.000', 'Kitpipat', '2021-12-21 00:00:00.000', 'Kitpipat');

	--ของเน็ต c#
	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto WITH(NOLOCK) WHERE FTSatTblName = 'TPSTWhTaxHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '0' ) BEGIN
		INSERT [dbo].[TCNTAuto] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FTSatGroup], [FTGmnCode], [FTSatDocTypeName], [FTSatStaAlwChr], [FTSatStaAlwBch], [FTSatStaAlwPosShp], [FTSatStaAlwYear], [FTSatStaAlwMonth], [FTSatStaAlwDay], [FTSatStaAlwSep], [FTSatStaDefUsage], [FTSatDefChar], [FTSatDefBch], [FTSatDefPosShp], [FTSatDefYear], [FTSatDefMonth], [FTSatDefDay], [FTSatDefSep], [FTSatDefNum], [FTSatDefFmtAll], [FNSatMaxFedSize], [FNSatMinRunning], [FTSatUsrChar], [FTSatUsrBch], [FTSatUsrPosShp], [FTSatUsrYear], [FTSatUsrMonth], [FTSatUsrDay], [FTSatUsrSep], [FTSatUsrNum], [FTSatUsrFmtAll], [FTSatStaReset], [FTSatStaRunBch], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTPrnCode]) 
		VALUES (N'TPSTWhTaxHD', N'FTXshDocNo', N'0', N'2', N'XSAL', N'', N'1', N'1', N'0', N'1', N'0', N'0', N'0', N'0', N'W', N'1', N'0', N'1', N'0', N'0', N'0', N'0000001', N'WBCHYY#######', 20, 5, N'W', N'1', N'0', N'1', N'0', N'0', N'0', N'0000001', N'WBCHYY#######', N'1', N'0', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', CAST(N'2020-12-23T00:00:00.000' AS DateTime), N'', NULL)
	END
	
	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto_L WITH(NOLOCK) WHERE FTSatTblName = 'TPSTWhTaxHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType = '0' ) BEGIN
		INSERT [dbo].[TCNTAuto_L] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FNLngID], [FTSatTblDesc], [FTSatRmk]) 
		VALUES (N'TPSTWhTaxHD', N'FTXshDocNo', N'0', 1, N'ใบหักภาษี ณ ที่จ่าย', N'')
	END

	UPDATE TCNTAuto SET FTSatDefNum='000001', FTSatDefFmtAll='DNBCHYY######', FTSatUsrNum='000001', FTSatUsrFmtAll='DNBCHYY######', FDLastUpdOn='2021-12-20 23:43:00' WHERE FTSatTblName='TPSTTaxHD' AND FTSatStaDocType='10'
	UPDATE TCNTAuto SET FTSatDefNum='000001', FTSatDefFmtAll='CNBCHYY######', FTSatUsrNum='000001', FTSatUsrFmtAll='CNBCHYY######', FDLastUpdOn='2021-12-20 23:43:00' WHERE FTSatTblName='TPSTTaxHD' AND FTSatStaDocType='11'
	UPDATE TCNTAuto SET FTSatDefNum='0000001', FTSatDefFmtAll='WBCHYY#######', FTSatUsrNum='0000001', FTSatUsrFmtAll='WBCHYY#######', FDLastUpdOn='2021-12-20 23:43:00' WHERE FTSatTblName='TPSTWhTaxHD' AND FTSatStaDocType='0'
	UPDATE TCNTAuto_L SET FTSatTblDesc = 'ใบหักภาษี ณ ที่จ่าย' WHERE FTSatTblName='TPSTWhTaxHD' AND FTSatFedCode = 'FTXshDocNo' AND FTSatStaDocType='0' AND FNLngID=1

	IF NOT EXISTS(SELECT FTFmtCode FROM TSysRcvFmt WITH(NOLOCK) WHERE FTFmtCode = '027' ) BEGIN
		INSERT INTO [dbo].[TSysRcvFmt]([FTFmtCode], [FTFmtKbRef], [FTFmtRef], [FTFmtStaUsed], [FTFmtStaAlwKeySum]) 
		VALUES ('027', ' ', 'C_KBDxReferWhTax', '1', '2');
	END

	IF NOT EXISTS(SELECT FTFmtCode FROM TSysRcvFmt_L WITH(NOLOCK) WHERE FTFmtCode = '027' AND FNLngID = 1 ) BEGIN
		INSERT INTO [dbo].[TSysRcvFmt_L]([FTFmtCode], [FNLngID], [FTFmtName], [FTRcvRmk]) 
		VALUES ('027', 1, 'หักภาษี ณ ที่จ่าย', NULL);
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB031' ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT]([FTGhdCode], [FTSysCode], [FTLicPdtCode], [FNGdtPage], [FNGdtDefSeq], [FNGdtUsrSeq], [FNGdtBtnSizeX], [FNGdtBtnSizeY], [FTGdtCallByName], [FTGdtStaUse], [FNGdtFuncLevel], [FTGdtSysUse]) 
		VALUES ('031', 'KB031', 'SF-PS031KB031', 1, 22, 22, 1, 1, 'C_KBDxReferWhTax', '1', 1, '1');
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB031' AND FNLngID = 1 ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT_L]([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) 
		VALUES ('031', 'KB031', 1, 'หักภาษี ณ ที่จ่าย');
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB031' AND FNLngID = 2 ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT_L]([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) 
		VALUES ('031', 'KB031', 2, 'Refer WithholdingTax');
	END

	IF NOT EXISTS(SELECT FTFmtCode FROM TSysRcvFmt WITH(NOLOCK) WHERE FTFmtCode = '028' ) BEGIN
		INSERT INTO [dbo].[TSysRcvFmt]([FTFmtCode], [FTFmtKbRef], [FTFmtRef], [FTFmtStaUsed], [FTFmtStaAlwKeySum]) 
		VALUES ('028', ' ', 'C_KBDxQRPayment', '1', '2');
	END

	IF NOT EXISTS(SELECT FTFmtCode FROM TSysRcvFmt_L WITH(NOLOCK) WHERE FTFmtCode = '028' AND FNLngID = 1 ) BEGIN
		INSERT INTO [dbo].[TSysRcvFmt_L]([FTFmtCode], [FNLngID], [FTFmtName], [FTRcvRmk]) 
		VALUES ('028', 1, 'Castle QR Payment', NULL);
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB039' ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT]([FTGhdCode], [FTSysCode], [FTLicPdtCode], [FNGdtPage], [FNGdtDefSeq], [FNGdtUsrSeq], [FNGdtBtnSizeX], [FNGdtBtnSizeY], [FTGdtCallByName], [FTGdtStaUse], [FNGdtFuncLevel], [FTGdtSysUse]) 
		VALUES ('031', 'KB039', 'SF-PS031KB039', 1, 23, 23, 1, 1, 'C_KBDxQRPayment', '1', 1, '1');
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB039' AND FNLngID = 1 ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT_L]([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) 
		VALUES ('031', 'KB039', 1, 'คิวอาร์ เพย์เม้นท์');
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB039' AND FNLngID = 2 ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT_L]([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) 
		VALUES ('031', 'KB039', 2, 'QR Payment');
	END

	IF NOT EXISTS(SELECT FTFmtCode FROM TSysRcvFmt WITH(NOLOCK) WHERE FTFmtCode = '029' ) BEGIN
		INSERT INTO [dbo].[TSysRcvFmt]([FTFmtCode], [FTFmtKbRef], [FTFmtRef], [FTFmtStaUsed], [FTFmtStaAlwKeySum]) 
		VALUES ('029', ' ', 'C_KBDxReferClaim', '1', '2');
	END

	IF NOT EXISTS(SELECT FTFmtCode FROM TSysRcvFmt_L WITH(NOLOCK) WHERE FTFmtCode = '029' AND FNLngID = 1 ) BEGIN
		INSERT INTO [dbo].[TSysRcvFmt_L]([FTFmtCode], [FNLngID], [FTFmtName], [FTRcvRmk]) 
		VALUES ('029', 1, 'อ้างอิงใบเคลม', NULL);
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB034' ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT]([FTGhdCode], [FTSysCode], [FTLicPdtCode], [FNGdtPage], [FNGdtDefSeq], [FNGdtUsrSeq], [FNGdtBtnSizeX], [FNGdtBtnSizeY], [FTGdtCallByName], [FTGdtStaUse], [FNGdtFuncLevel], [FTGdtSysUse]) 
		VALUES ('031', 'KB034', 'SF-PS031KB034', 1, 24, 24, 1, 1, 'C_KBDxReferClaim', '1', 1, '1');
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB034' AND FNLngID = 1 ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT_L]([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) 
		VALUES ('031', 'KB034', 1, 'อ้างอิงใบเคลม');
	END

	IF NOT EXISTS(SELECT FTGhdCode FROM TPSMFuncDT_L WITH(NOLOCK) WHERE FTGhdCode = '031' AND FTSysCode = 'KB034' AND FNLngID = 2 ) BEGIN
		INSERT INTO [dbo].[TPSMFuncDT_L]([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) 
		VALUES ('031', 'KB034', 2, 'Refer Claim');
	END

	UPDATE TPSMFuncHD SET FDLastUpdOn=GETDATE() WHERE FTGhdCode='031'

	UPDATE TSysSyncData SET FTSynStaUse = '1' WHERE FNSynSeqNo=119

INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.08', getdate() , 'เพิ่ม config', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.09') BEGIN
--ทุกครั้งที่รันสคริปใหม่
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00012', '1', '2021-12-29 16:44:31.000', 'Wat', '2021-12-29 16:44:36.000', 'Wat');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00012', '1', 'ใบโอนสินค้าระหว่างสาขา', NULL);
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.09', getdate() , 'เพิ่ม Noti ใบโอนสินค้าระหว่างสาขา', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.10') BEGIN
--ทุกครั้งที่รันสคริปใหม่
	IF NOT EXISTS (SELECT FTGhdCode FROM TPSMFuncHD WHERE FTGhdCode = '083' ) BEGIN
		INSERT TPSMFuncHD(FTGhdCode, FTGhdApp, FTKbdScreen, FTKbdGrpName, FNGhdMaxPerPage, FTGhdLayOut, FNGhdMaxLayOutX, FNGhdMaxLayOutY, FTGhdStaAlwChg, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		VALUES( '083', 'PS', 'WHTTAX', 'ROLE', 0, 'ALL', 0, 0, '1', GETDATE(), 'Jirayu S.', GETDATE(), 'Jirayu S.')
	END
	
	IF NOT EXISTS (SELECT FTGhdCode FROM TPSMFuncDT WHERE FTGhdCode = '083' AND FTSysCode = 'KB094' ) BEGIN
		INSERT TPSMFuncDT(FTGhdCode, FTSysCode, FTLicPdtCode, FNGdtPage, FNGdtDefSeq, FNGdtUsrSeq, FNGdtBtnSizeX, FNGdtBtnSizeY, FTGdtCallByName, FTGdtStaUse, FNGdtFuncLevel, FTGdtSysUse)
		VALUES('083', 'KB094', '', 0, 1, 1, 0, 0, 'C_KBDxPdtAlwWHTax', '1', 1, '1')
	END
	
	IF NOT EXISTS (SELECT FTGhdCode FROM TPSMFuncDT_L WHERE FTGhdCode = '083' AND FTSysCode = 'KB094' AND FNLngID = 1 ) BEGIN
		INSERT TPSMFuncDT_L(FTGhdCode, FTSysCode, FNLngID, FTGdtName)
		VALUES('083', 'KB094', 1, 'อนุญาตหักภาษี ณ ที่จ่าย สินค้าที่ไม่อนุญาตหัก')
	END
	
	IF NOT EXISTS (SELECT FTGhdCode FROM TPSMFuncHD WHERE FTGhdCode = '084' ) BEGIN
		INSERT TPSMFuncHD(FTGhdCode, FTGhdApp, FTKbdScreen, FTKbdGrpName, FNGhdMaxPerPage, FTGhdLayOut, FNGhdMaxLayOutX, FNGhdMaxLayOutY, FTGhdStaAlwChg, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		VALUES( '084', 'PS', 'SALEINT', 'ROLE', 0, 'ALL', 0, 0, '1', GETDATE(), 'Jirayu S.', GETDATE(), 'Jirayu S.')
	END
	
	IF NOT EXISTS (SELECT FTGhdCode FROM TPSMFuncDT WHERE FTGhdCode = '084' AND FTSysCode = 'KB094' ) BEGIN
		INSERT TPSMFuncDT(FTGhdCode, FTSysCode, FTLicPdtCode, FNGdtPage, FNGdtDefSeq, FNGdtUsrSeq, FNGdtBtnSizeX, FNGdtBtnSizeY, FTGdtCallByName, FTGdtStaUse, FNGdtFuncLevel, FTGdtSysUse)
		VALUES('084', 'KB094', '', 0, 1, 1, 0, 0, 'C_KBDxCarAlwSaleTwo', '1', 1, '1')
	END
	
	IF NOT EXISTS (SELECT FTGhdCode FROM TPSMFuncDT_L WHERE FTGhdCode = '084' AND FTSysCode = 'KB094' AND FNLngID = 1 ) BEGIN
		INSERT TPSMFuncDT_L(FTGhdCode, FTSysCode, FNLngID, FTGdtName)
		VALUES('084', 'KB094', 1, 'อนุญาตเบิกจ่ายรถที่ไม่ใช้งานแล้ว')
	END
	
	IF NOT EXISTS (SELECT FTEvnCode FROM TSysShiftEvent_L WHERE FTEvnCode = '009' AND FNLngID = 1 ) BEGIN
		INSERT TSysShiftEvent_L(FTEvnCode, FNLngID, FTEvnName, FTEvnFuncRef, FTEvnStaUsed)
		VALUES('009', 1, 'อนุญาตสิทธิ์', 'fC_Allow', '1')
	END
	
	IF NOT EXISTS (SELECT FTRolCode FROM TCNTUsrFuncRpt WHERE FTRolCode = '00002' AND FTUfrType = '1' AND FTUfrGrpRef = '083' AND FTUfrRef = 'KB094') BEGIN
		INSERT TCNTUsrFuncRpt(FTRolCode, FTUfrType, FTUfrGrpRef, FTUfrRef, FTGhdApp, FTUfrStaAlw, FTUfrStaFavorite, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		VALUES('00002', '1', '083', 'KB094', 'PS', '1', '0', GETDATE(), 'Dev', GETDATE(), 'Dev')
	END
	
	IF NOT EXISTS (SELECT FTRolCode FROM TCNTUsrFuncRpt WHERE FTRolCode = '00002' AND FTUfrType = '1' AND FTUfrGrpRef = '084' AND FTUfrRef = 'KB094') BEGIN
		INSERT TCNTUsrFuncRpt(FTRolCode, FTUfrType, FTUfrGrpRef, FTUfrRef, FTGhdApp, FTUfrStaAlw, FTUfrStaFavorite, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
		VALUES('00002', '1', '084', 'KB094', 'PS', '1', '0', GETDATE(), 'Dev', GETDATE(), 'Dev')
	END
	
	UPDATE TCNTUsrFuncRpt SET FDLastUpdOn = GETDATE() WHERE FTRolCode='00002'
	
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.10', getdate() , 'เอามาจากเน็ต c#', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.11') BEGIN
--ทุกครั้งที่รันสคริปใหม่
	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshCshOrCrd' AND FTSatStaDocType = '1') BEGIN
		INSERT [dbo].[TCNTAuto] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FTSatGroup], [FTGmnCode], [FTSatDocTypeName], [FTSatStaAlwChr], [FTSatStaAlwBch], [FTSatStaAlwPosShp], [FTSatStaAlwYear], [FTSatStaAlwMonth], [FTSatStaAlwDay], [FTSatStaAlwSep], [FTSatStaDefUsage], [FTSatDefChar], [FTSatDefBch], [FTSatDefPosShp], [FTSatDefYear], [FTSatDefMonth], [FTSatDefDay], [FTSatDefSep], [FTSatDefNum], [FTSatDefFmtAll], [FNSatMaxFedSize], [FNSatMinRunning], [FTSatUsrChar], [FTSatUsrBch], [FTSatUsrPosShp], [FTSatUsrYear], [FTSatUsrMonth], [FTSatUsrDay], [FTSatUsrSep], [FTSatUsrNum], [FTSatUsrFmtAll], [FTSatStaReset], [FTSatStaRunBch], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'TPSTSalHD', N'FTXshCshOrCrd', N'1', N'2', N'XSAL', N'FNXshDocType', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'0', N'S', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'SBCHPOSYY#######', 20, 5, N'S', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'SBCHPOSYY#######', N'1', N'0', GETDATE(), N'Jirayu S.', GETDATE(), N'Jirayu S.')
	END
	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshCshOrCrd' AND FTSatStaDocType = '9') BEGIN
		INSERT [dbo].[TCNTAuto] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FTSatGroup], [FTGmnCode], [FTSatDocTypeName], [FTSatStaAlwChr], [FTSatStaAlwBch], [FTSatStaAlwPosShp], [FTSatStaAlwYear], [FTSatStaAlwMonth], [FTSatStaAlwDay], [FTSatStaAlwSep], [FTSatStaDefUsage], [FTSatDefChar], [FTSatDefBch], [FTSatDefPosShp], [FTSatDefYear], [FTSatDefMonth], [FTSatDefDay], [FTSatDefSep], [FTSatDefNum], [FTSatDefFmtAll], [FNSatMaxFedSize], [FNSatMinRunning], [FTSatUsrChar], [FTSatUsrBch], [FTSatUsrPosShp], [FTSatUsrYear], [FTSatUsrMonth], [FTSatUsrDay], [FTSatUsrSep], [FTSatUsrNum], [FTSatUsrFmtAll], [FTSatStaReset], [FTSatStaRunBch], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) 
		VALUES (N'TPSTSalHD', N'FTXshCshOrCrd', N'9', N'2', N'XSAL', N'FNXshDocType', N'1', N'1', N'1', N'1', N'1', N'1', N'1', N'0', N'R', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'RBCHPOSYY#######', 20, 5, N'R', N'1', N'1', N'1', N'0', N'0', N'0', N'0000001', N'RBCHPOSYY#######', N'1', N'0', GETDATE(), N'Jirayu S.', GETDATE(), N'Jirayu S.')
	END
	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto_L WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshCshOrCrd' AND FTSatStaDocType = '1' AND FNLngID = 1) BEGIN
		INSERT [dbo].[TCNTAuto_L] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FNLngID], [FTSatTblDesc], [FTSatRmk]) 
		VALUES (N'TPSTSalHD', N'FTXshCshOrCrd', N'1', 1, N'ขายปลีก - ขาย (เงินเชื่อ)', NULL)
	END
	IF NOT EXISTS(SELECT FTSatTblName FROM TCNTAuto_L WITH(NOLOCK) WHERE FTSatTblName = 'TPSTSalHD' AND FTSatFedCode = 'FTXshCshOrCrd' AND FTSatStaDocType = '9' AND FNLngID = 1) BEGIN
		INSERT [dbo].[TCNTAuto_L] ([FTSatTblName], [FTSatFedCode], [FTSatStaDocType], [FNLngID], [FTSatTblDesc], [FTSatRmk]) 
		VALUES (N'TPSTSalHD', N'FTXshCshOrCrd', N'9', 1, N'ขายปลีก - คืน (เงินเชื่อ)', NULL)
	END
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.11', getdate() , 'เอามาจากเน็ต c#', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.12') BEGIN
	--ทุกครั้งที่รันสคริปใหม่

	--รายงาน - การคืนสินค้าข้ามวัน
	INSERT INTO [TSysReport] ([FTRptCode], [FTGrpRptModCode], [FTGrpRptCode], [FTRptRoute], [FTRptStaUseFrm], [FTRptTblView], [FTRptFilterCol], [FTRptFileName], [FTRptStaShwBch], [FTRptStaShwYear], [FTRptSeqNo], [FTRptStaUse], [FTLicPdtCode]) VALUES ('001001038', '001', '001001', 'rptRPDNextDate', NULL, NULL, '1,2,3,45,4', NULL, '1', '1', '16', '1', 'SB-RPT001001038');
	INSERT INTO [TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES ('001001038', '1', 'รายงาน - การคืนสินค้าข้ามวัน', '');
	INSERT INTO [TSysReport_L] ([FTRptCode], [FNLngID], [FTRptName], [FTRptDes]) VALUES ('001001038', '2', 'รายงาน - การคืนสินค้าข้ามวัน', '');
	INSERT INTO [TCNTUsrFuncRpt] ([FTRolCode], [FTUfrType], [FTUfrGrpRef], [FTUfrRef], [FTGhdApp], [FTUfrStaAlw], [FTUfrStaFavorite], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00002', '2', '001001', '001001038', NULL, '1', '0', '2022-01-23 22:33:32.890', NULL, '2021-12-29 20:11:10.000', '00002');

	--รองรับการ Noti 
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00013', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:19.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00014', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00015', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00016', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00017', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00018', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00019', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00020', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');
	INSERT INTO [TCNSNoti] ([FTNotCode], [FTNotStaResponse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('00021', '1', '2022-01-27 13:52:17.000', 'Supawat', '2022-01-27 13:52:17.000', 'Supawat');

	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00013', '1', 'ใบรับเข้า (คลัง)', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00014', '1', 'ใบเบิกออก (คลัง)', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00015', '1', 'ใบจ่ายโอน (คลัง)', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00016', '1', 'ใบรับโอน (คลัง)', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00017', '1', 'ใบโอนสินค้าระหว่างคลัง', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00018', '1', 'ใบลดหนี้ (แบบมีสินค้า)', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00019', '1', 'ใบนัดหมาย', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00020', '1', 'ใบจองสินค้า', '');
	INSERT INTO [TCNSNoti_L] ([FTNotCode], [FNLngID], [FTNotTypeName], [FTNotRmk]) VALUES ('00021', '1', 'ใบรับรถ', '');

	--ให้แอดมินเห็น Noti ของทุกเอกสาร
	DELETE [TCNSRptSpc] WHERE [FTRolCode] = '00002'
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '0', '00000', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '1', '00001', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '2', '00002', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '3', '00003', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '4', '00004', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '5', '00005', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '6', '00006', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '7', '00007', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '8', '00008', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '9', '00009', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '10', '00010', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '11', '00011', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '12', '00012', '', NULL, '1', '2022-01-23 22:33:31.527', '00002', '2021-12-29 20:11:10.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '13', '00013', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '14', '00014', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '15', '00015', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '16', '00016', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '17', '00017', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '18', '00018', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '19', '00019', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '20', '00020', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');
	INSERT INTO  [TCNSRptSpc] ([FTAgnCode], [FTBchCode], [FTMerCode], [FTShpCode], [FNRptGrpSeq], [FTRptGrpCode], [FNRptSeq], [FTRptCode], [FTRptRoute], [FTRptFilterCol], [FTRptStaActive], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy], [FTRolCode]) VALUES (NULL, NULL, NULL, NULL, '3', '00002', '21', '00021', NULL, NULL, '1', '2022-01-27 14:00:58.000', '00002', '2022-01-27 14:00:58.000', '00002', '00002');


--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.12', getdate() , 'เพิ่มรายงานตัวใหม่ + Noti', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.13') BEGIN
	--ทุกครั้งที่รันสคริปใหม่
	UPDATE TSysMenuList_L SET FTMnuName = 'ใบถูกหักภาษี ณ ที่จ่าย' WHERE FTMnuCode = 'AR0001' AND FNLngID = '1'
--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.13', getdate() , 'เปลี่ยนชื่อเมนูหักภาษี', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.14') BEGIN
--ทุกครั้งที่รันสคริปใหม่
	IF NOT EXISTS(
		SELECT * FROM TCNTUsrFuncRpt WHERE FTRolCode = '00002' AND FTUfrGrpRef = '085' AND FTUfrRef = 'KB901'
	) BEGIN
		INSERT INTO TCNTUsrFuncRpt(FTRolCode,FTUfrType,FTUfrGrpRef,FTUfrRef,FTGhdApp,FTUfrStaAlw,FTUfrStaFavorite,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
		VALUES ('00002',1,'085','KB901','ALL',1,0,'2022-01-23 22:33:32.890','00002','2021-11-20 19:37:53.087','00002')
	END

	IF NOT EXISTS(
		SELECT * FROM TSysFuncKB WHERE FTSysCode = 'KB901'
	) BEGIN
        INSERT INTO TSysFuncKB (FTSysCode, FNSysKeyShift, FNSysKeyAscii, FTSysKeyName, FTSysKeyFunc, FTSysStaUse)
		VALUES ('KB901', 0, 8, '', '',  0 );
	END

	IF NOT EXISTS(
		SELECT * FROM TPSMFuncHD WHERE FTGhdCode = '085' AND FTGhdApp = 'SB' AND FTKbdScreen = 'WAREHOUSE' AND FTKbdGrpName = 'FUNC'
	) BEGIN
        INSERT INTO TPSMFuncHD (FTGhdCode, FTGhdApp, FTKbdScreen, FTKbdGrpName, FNGhdMaxPerPage, FTGhdLayOut,  FNGhdMaxLayOutX, FNGhdMaxLayOutY, FTGhdStaAlwChg,  FDLastUpdOn, FTLastUpdBy,  FDCreateOn,  FTCreateBy)
		VALUES ('085', 'SB', 'WAREHOUSE', 'FUNC', 0, 'ALL', 0,  0, 0,  '2022-01-23 22:33:33.190', 'Kitpipat', '2021-07-02 00:00:00.000', 'Kitpipat');
	END

	IF NOT EXISTS (
		SELECT * FROM TPSMFuncDT  WHERE FTGhdCode = '085' AND FTSysCode = 'KB901'
	) BEGIN
        INSERT INTO TPSMFuncDT (FTGhdCode, FTSysCode,  FTLicPdtCode, FNGdtPage, FNGdtDefSeq, FNGdtUsrSeq, FNGdtBtnSizeX, FNGdtBtnSizeY, FTGdtCallByName, FTGdtStaUse, FNGdtFuncLevel, FTGdtSysUse)
        VALUES ('085', 'KB901', '', 1, 2, 2, 0, 0, '', 1, 1, 1);
	END

	IF NOT EXISTS (
		SELECT * FROM TPSMFuncDT_L WHERE FTGhdCode = '085' AND FTSysCode = 'KB901'
	) BEGIN
        INSERT INTO TPSMFuncDT_L(FTGhdCode,FTSysCode,FNLngID,FTGdtName)
        VALUES ('085','KB901',1,'อนุญาต จัดการคลังสินค้า')
	END
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.14', getdate() , 'เพิ่มกลุ่มสิทธิฟังก์ชั่น', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.15') BEGIN
--ทุกครั้งที่รันสคริปใหม่
	UPDATE TLKMConfig_L SET FTCfgName = 'Email ผู้รับ การส่งแจ้งเตือน(สำเนา)' WHERE FTCfgCode = 'tLK_MailCC' AND FTCfgApp = 'LINK' AND FTCfgSeq = 1
	UPDATE TLKMConfig_L SET FTCfgName = 'Email ผู้รับ การส่งแจ้งเตือน' WHERE FTCfgCode = 'tLK_MailReceive' AND FTCfgApp = 'LINK' AND FTCfgSeq = 1
	UPDATE TLKMConfig_L SET FTCfgName = 'Email ผู้ส่ง การส่งแจ้งเตือน' WHERE FTCfgCode = 'tLK_MailSender' AND FTCfgApp = 'LINK' AND FTCfgSeq = 1
	UPDATE TLKMConfig_L SET FTCfgName = 'หัวข้อการส่งแจ้งเตือน' WHERE FTCfgCode = 'tLK_MailSubject' AND FTCfgApp = 'LINK' AND FTCfgSeq = 1
	UPDATE TLKMConfig_L SET FTCfgName = 'รหัสผ่าน Email ผู้ส่ง' WHERE FTCfgCode = 'tLK_SMTPPwd' AND FTCfgApp = 'LINK' AND FTCfgSeq = 1
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.15', getdate() , 'เปลี่ยนชื่อในตาราง', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.16') BEGIN
--ทุกครั้งที่รันสคริปใหม่
	UPDATE [TSysReportGrp_L] SET [FTGrpRptName]='รายงานการขาย (Fit Auto)' WHERE ([FTGrpRptCode]='001003');
	UPDATE [TSysReport] SET [FTRptFilterCol]='1,2,4,27,47' WHERE ([FTRptCode]='001003025');
	UPDATE [TSysReport] SET [FTRptFilterCol]='1,4,50' WHERE ([FTRptCode]='001003035');
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.16', getdate() , 'อัพเดทคำว่า Fit Auto และ report', 'Supawat')
END
GO