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
