IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.17') BEGIN
    --ทุกครั้งที่รันสคริปใหม่
    INSERT INTO [[TCNSPmtPdtCond] ([FNPmtID], [FTPmtRefCode], [FTPmtRefPdt], [FTPmtSubRef], [FTPmtSubRefPdt], [FTPmtStaUse], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('8', 'TCNTPdtAdjPriHD', 'TCNTPdtAdjPriHD.FTXphDocNo', '', '', '1', '2022-01-23 22:33:31.520', '009', '2020-10-29 00:00:00.000', '009');
    INSERT INTO [TCNSPmtPdtCond_L] ([FNPmtID], [FNLngID], [FTDropName], [FTPmtRefN], [FTPmtSubRefN], [FTSubRefNTitle], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('8', '1', 'ใบปรับราคา', 'รหัสใบปรับราคา,วันที่เอกสาร', 'รหัส,วันที่', '', '2022-01-23 22:33:31.520', '009', '2020-10-29 00:00:00.000', '009');
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.17', getdate() , 'promotion เพิ่มประเภทใบปรับราคา (ออฟ)', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.18') BEGIN
    --ทุกครั้งที่รันสคริปใหม่
    UPDATE TPSMFuncDT SET FNGdtDefSeq = 13,FNGdtUsrSeq = 13 WHERE FTGhdCode = '048' AND FTSysCode = 'KB071'
    UPDATE TPSMFuncDT SET FNGdtDefSeq = 14,FNGdtUsrSeq = 14 WHERE FTGhdCode = '048' AND FTSysCode = 'KB036'
    UPDATE TPSMFuncDT SET FNGdtDefSeq = 15,FNGdtUsrSeq = 15 WHERE FTGhdCode = '048' AND FTSysCode = 'KB043'
    UPDATE TPSMFuncDT SET FNGdtDefSeq = 16,FNGdtUsrSeq = 16 WHERE FTGhdCode = '048' AND FTSysCode = 'KB054'
    UPDATE TPSMFuncDT SET FNGdtDefSeq = 17,FNGdtUsrSeq = 17 WHERE FTGhdCode = '048' AND FTSysCode = 'KB006'
    UPDATE TPSMFuncHD SET FDLastUpdOn = GETDATE(),FTLastUpdBy ='System' WHERE FTGhdCode = '048'
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.18', getdate() , 'ได้ script มาจากพี่เอ็ม', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.19') BEGIN
    --ทุกครั้งที่รันสคริปใหม่
    INSERT INTO [TPSMFuncHD] ([FTGhdCode], [FTGhdApp], [FTKbdScreen], [FTKbdGrpName], [FNGhdMaxPerPage], [FTGhdLayOut], [FNGhdMaxLayOutX], [FNGhdMaxLayOutY], [FTGhdStaAlwChg], [FDLastUpdOn], [FTLastUpdBy], [FDCreateOn], [FTCreateBy]) VALUES ('086', 'SB', 'CUSTOMER', 'FUNC', '0', 'ALL', '0', '0', '0', '2022-03-31 10:46:49.000', 'Kitpipat', '2022-03-31 10:46:57.000', 'Kitpipat');
    INSERT INTO [TPSMFuncDT_L] ([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) VALUES ('086', 'KB901', '1', 'อนุญาต จัดการลูกค้าเครดิต');
    INSERT INTO [TPSMFuncDT_L] ([FTGhdCode], [FTSysCode], [FNLngID], [FTGdtName]) VALUES ('086', 'KB901', '2', 'Allow Customer Credits');
    INSERT INTO [TPSMFuncDT] ([FTGhdCode], [FTSysCode], [FTLicPdtCode], [FNGdtPage], [FNGdtDefSeq], [FNGdtUsrSeq], [FNGdtBtnSizeX], [FNGdtBtnSizeY], [FTGdtCallByName], [FTGdtStaUse], [FNGdtFuncLevel], [FTGdtSysUse]) VALUES ('086', 'KB901', NULL, '1', '2', '2', '0', '0', NULL, '1', '1', '1');
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.19', getdate() , 'เพิ่มหน้าจอลูกค้า ให้มองเห็น ลูกค้าเครดิต ตามสิทธิ์', 'Supawat')
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.20') BEGIN
    --ทุกครั้งที่รันสคริปใหม่
    UPDATE [TSysMenuList_L] SET [FTMnuCode]='ARS013', [FNLngID]='1', [FTMnuName]='ใบวางบิลลูกค้าเครดิต', [FTMnuRmk]='' WHERE ([FTMnuCode]='ARS013') AND ([FNLngID]='1');
    UPDATE [TSysMenuList_L] SET [FTMnuCode]='ARS013', [FNLngID]='2', [FTMnuName]='ใบวางบิลลูกค้าเครดิต', [FTMnuRmk]='' WHERE ([FTMnuCode]='ARS013') AND ([FNLngID]='2');
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.20', getdate() , 'เปลี่ยนชื่อ ใบวางบิลลูกค้าเครดิต', 'Supawat')
END
GO