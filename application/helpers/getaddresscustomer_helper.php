<?php

// Get Shipping Custmer Address
// $ptHDCstTableName = ชื่อตารางหลัก
// $ptCstCode = ID ลูกค้า
// $pnLangID = ID ภาษา 1 ไทย 2 eng
// $pnAddrShip = ลำดับที่อยู่เพื่อ Join Seq ของตาราง CstAddress_L 
//FCNtGetAddressCustmer('TSVTJob2OrdHDCst','AR0000100004','1','FNXshAddrShip');
function FCNtGetAddressCustmer($ptHDCstTableName = '', $ptCstCode = '', $pnLangID = '', $ptAddrShip = '') {
    $ci = &get_instance();
    $ci->load->database();

    if ($ptHDCstTableName == "" || $ptCstCode == '' || $pnLangID == '' || $ptAddrShip == '') {
        $aDataReturn = array();
    }else{
        $tSQL   = "SELECT   CAD.FTCstCode, 
                            CAD.FTAddVersion, 
                            CAD.FTAddV1No, 
                            CAD.FTAddV1Soi, 
                            CAD.FTAddV1Road, 
                            SDT.FTSudName, 
                            DTS.FTDstName, 
                            PVN.FTPvnName, 
                            CAD.FTAddV1PostCode,
                            CAD.FTAddCountry, 
                            CAD.FTAddV2Desc1, 
                            CAD.FTAddV2Desc2, 
                            CST.FTCstTel, 
                            CST.FTCstEmail, 
                            CAD.FTAddFax
                        FROM $ptHDCstTableName HCS WITH(NOLOCK)
                        LEFT JOIN TCNMCstAddress_L CAD WITH(NOLOCK) ON HCS.$ptAddrShip = CAD.FNAddSeqNo AND CAD.FTCstCode = '$ptCstCode'
                        INNER JOIN TCNMCst CST WITH(NOLOCK) ON CAD.FTCstCode = CST.FTCstCode
                        LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = $pnLangID
                        LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = $pnLangID
                        LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = $pnLangID;
                    ";
        $oQuery = $ci->db->query($tSQL);
        if(empty($oQuery->result_array())){
            $aDataReturn = array();
        }else{
            $aDataReturn        = $oQuery->result_array();
        }
    }
    return $aDataReturn;
}


function FCNtGetAddressCustmerDefVersion($ptHDCstCode = '', $pnLangID = '')
{
    $ci = &get_instance();
    $ci->load->database();

    $nAddressVersion = FCNaHAddressFormat('TCNMCst');

    if ($ptHDCstCode == "") {
        $aDataReturn = array();
    } else {
        $tSQL   = "SELECT TOP 1 CAD.FTCstCode, 
                        CAD.FTAddVersion, 
                        ISNULL(CAD.FTAddV1No,'') AS FTAddV1No,
                        ISNULL(CAD.FTAddV1Soi,'') AS  FTAddV1Soi,
                        ISNULL(CAD.FTAddV1Road,'') AS FTAddV1Road,
                        ISNULL(CAD.FTAddV1Village,'') AS FTAddV1Village,
                        ISNULL(SDT.FTSudName,'') AS FTSudName,
                        ISNULL(DTS.FTDstName,'') AS FTDstName,
                        ISNULL(PVN.FTPvnName,'') AS FTPvnName,
                        CAD.FTAddV1PostCode,
                        CAD.FTAddCountry, 
                        CAD.FTAddV2Desc1, 
                        CAD.FTAddV2Desc2, 
                        HCS.FTCstTel, 
                        HCS.FTCstEmail, 
                        CAD.FTAddFax
                    FROM TCNMCst HCS WITH(NOLOCK)
                    LEFT JOIN TCNMCstAddress_L CAD WITH(NOLOCK) ON HCS.FTCstCode = CAD.FTCstCode AND CAD.FTAddVersion = '$nAddressVersion'
                    LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = '$pnLangID'
                    LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = '$pnLangID'
                    LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = '$pnLangID'
                    WHERE HCS.FTCstCode = '$ptHDCstCode'
                ";
        $oQuery = $ci->db->query($tSQL);
        if (empty($oQuery->result_array())) {
            $aDataReturn = array();
        } else {
            $aDataReturn        = $oQuery->result_array();
        }
    }
    return $aDataReturn;
}