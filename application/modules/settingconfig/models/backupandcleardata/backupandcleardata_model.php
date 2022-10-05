<?php
defined('BASEPATH') or exit('No direct script access allowed');

class backupandcleardata_model extends CI_Model
{

    /**
     * Functionality : Search BackupandClean By ID
     * Parameters : $paData
     * Creator : 06/08/2022 Off
     * Last Modified : -
     * Return : Data
     * Return Type : array
     */
    public function FSaBACSearchByID($paData)
    {
        $FNPrgDocType   = $paData['FNPrgDocType'];
        $tPrgKey        = $paData['FTPrgKey'];
        $nLngID         = $paData['FNLngID'];
        // Ad message querys
        $tSQL      =   "
        SELECT DISTINCT
        Purge.FNPrgDocType,
        Purge.FNPrgType,
        CASE WHEN Purge.FNPrgType = 1 THEN 'MASTER' WHEN Purge.FNPrgType = 2 THEN 'Transaction' ELSE 'File' END AS FNPrgTypeName,
        CASE WHEN Purge.FTPrgGroup = 1 THEN 'Server' WHEN Purge.FTPrgGroup = 2 THEN 'Client' WHEN Purge.FTPrgGroup = 3 THEN 'Server + Client' ELSE 'Server Log' END AS FTPrgGroup,
        Purge.FTPrgStaPrg AS FTPrgStaPrg,
        Purge_S.FTPrgStaPrg AS FTPrgStaPrgSpl,
        Purge.FTPrgStaUse AS FTPrgStaUse,
        Purge_S.FTPrgStaUse AS FTPrgStaUseSpl,
        Purge.FTPrgKey,
        Purge.FDPrgLast,
        Purge.FNPrgKeep,
        Purge_S.FNPrgKeep AS FNPrgKeepSpl,
        Purge.FDCreateOn,
        Purge_S.FTAgnCode,
        Purge_L.FTPrgName,
        AGN_L.FTAgnName
        FROM TCNSPurgeHD Purge  WITH(NOLOCK)
        LEFT JOIN TCNSPurgeHD_L Purge_L       WITH(NOLOCK) ON Purge.FTPrgKey = Purge_L.FTPrgKey AND Purge.FNPrgDocType = Purge_L.FNPrgDocType AND Purge_L.FNLngID = " . $this->db->escape($nLngID) . "
        LEFT JOIN TCNTPurgeSpc  Purge_S       WITH(NOLOCK) ON Purge.FTPrgKey = Purge_S.FTPrgTblHD AND Purge.FNPrgDocType = Purge_S.FNPrgDocType AND Purge_L.FNLngID = " . $this->db->escape($nLngID) . "
        LEFT JOIN TCNMAgency_L AGN_L          WITH(NOLOCK) ON Purge_S.FTAgnCode = AGN_L.FTAgnCode AND AGN_L.FNLngID = " . $this->db->escape($nLngID) . "
        WHERE Purge.FDCreateOn <> '' AND Purge.FTPrgKey = " . $this->db->escape($tPrgKey) . " AND Purge.FNPrgDocType = " . $this->db->escape($FNPrgDocType) . " 
        ";

        $oQuery    = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) { // Have ad
            $oDetail       = $oQuery->result();
            // Found
            $aResult        = array(
                'raItems'       => $oDetail[0],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            // Not Found
            $aResult        = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : list Data SettingConperiod
    //Creator :  19/09/2019 Witsarut (Bell)
    public function FSaMBACList($paData)
    {
        try {
            $nLngID                 = $paData['FNLngID'];
            $tSearchList            = $paData['tSearchAll'];
            $tSesAgnCode            = $this->session->userdata("tSesUsrAgnCode");
            $tSearchDocDateFrom     = $tSearchList['tSearchDocDateFrom'];
            $tSearchDocDateTo       = $tSearchList['tSearchDocDateTo'];
            $tSearchPrgType         = $tSearchList['tSearchPrgType'];
            $tSearchPrgGroup        = $tSearchList['tSearchPrgGroup'];
            $tSearchPrgAllowPurge   = $tSearchList['tSearchPrgAllowPurge'];
            $tSearchPrgStaUse       = $tSearchList['tSearchPrgStaUse'];


            $tSQL           = "SELECT base.* FROM (
                                    SELECT 
                                    CASE
                                        WHEN ISNULL(c.FTPrgStaPrg2,'0') = 0 THEN
                                        c.FTPrgStaPrg1
                                        ELSE c.FTPrgStaPrg2 
                                    END AS ChkStaPurge,
                                    CASE 
                                        WHEN ISNULL( c.FTPrgStaUse2, '0' ) = 0 THEN
                                        c.FTPrgStaUse1 ELSE c.FTPrgStaUse2 
                                    END AS ChkStaUse,
                                    c.* FROM
                                        (SELECT DISTINCT
                                            Purge.FNPrgDocType,
                                            Purge.FNPrgType,
                                            Purge_S.FTPrgStaPrg AS FTPrgStaPrg2,
		                                    Purge.FTPrgStaPrg AS FTPrgStaPrg1,
                                            Purge_S.FTPrgStaUse AS FTPrgStaUse2,
				                            Purge.FTPrgStaUse AS FTPrgStaUse1,
                                            CASE WHEN Purge.FNPrgType = 1 THEN 'MASTER' WHEN Purge.FNPrgType = 2 THEN 'Transaction' ELSE 'File' END AS FNPrgTypeName,
                                            CASE WHEN Purge.FTPrgGroup = 1 THEN 'Server' WHEN Purge.FTPrgGroup = 2 THEN 'Client' WHEN Purge.FTPrgGroup = 3 THEN 'Server + Client' ELSE 'Server Log' END AS FTPrgGroup,
                                            Purge.FTPrgKey,
                                            Purge.FDPrgLast,
                                            Purge.FNPrgKeep,
                                            Purge_S.FNPrgKeep AS FNPrgKeepSpl,
                                            Purge.FDCreateOn,
                                            Purge_S.FTAgnCode,
                                            Purge_L.FTPrgName,
                                            AGN_L.FTAgnName
                                        FROM TCNSPurgeHD Purge  WITH(NOLOCK)
                                        LEFT JOIN TCNSPurgeHD_L Purge_L       WITH(NOLOCK) ON Purge.FTPrgKey = Purge_L.FTPrgKey AND Purge.FNPrgDocType = Purge_L.FNPrgDocType AND Purge_L.FNLngID = $nLngID
                                        LEFT JOIN TCNTPurgeSpc  Purge_S       WITH(NOLOCK) ON Purge.FTPrgKey = Purge_S.FTPrgTblHD AND Purge.FNPrgDocType = Purge_S.FNPrgDocType AND Purge_L.FNLngID = $nLngID
                                        LEFT JOIN TCNMAgency_L AGN_L          WITH(NOLOCK) ON Purge_S.FTAgnCode = AGN_L.FTAgnCode AND AGN_L.FNLngID = $nLngID
                                        WHERE Purge.FDCreateOn <> '' ";

            //input ค้นหา
            if (isset($tSearchList['tSearchAll']) && !empty($tSearchList['tSearchAll'])) {
                $tSQL .= " AND (Purge_L.FTPrgName LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR Purge.FNPrgKeep  LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%')";
            }
            //ค้นหาตัวแทนขายแบบ Browse
            if (isset($tSearchList['tSearchAgn']) && !empty($tSearchList['tSearchAgn'])) {
                $tSQL .= " AND AGN_L.FTAgnName = " . $this->db->escape($tSearchList['tSearchAgn']) . "";
            }

            //ค้นหาประเภทข้อมูล
            if ($tSearchPrgType != '0') {
                $tSQL .= " AND Purge.FNPrgType = " . $this->db->escape($tSearchPrgType) . "";
            }

            //ค้นหากลุ่ม
            if ($tSearchPrgGroup != '0') {
                $tSQL .= " AND Purge.FTPrgGroup = " . $this->db->escape($tSearchPrgGroup) . "";
            }


            //ค้นหาวันที่ ล้างข้อมูล
            if (!empty($tSearchList['tSearchDocDateFrom']) && !empty($tSearchList['tSearchDocDateTo'])) {
                $tSQL   .= " AND ((Purge.FDPrgLast BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (Purge.FDPrgLast BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
            }


            // Check User Login Branch
            if ($this->session->userdata('tSesUsrLevel') != 'HQ') {
                $tUserLoginBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
                $tSQL .=  " AND POS.FTBchCode IN($tUserLoginBchCode)";
            }

            $tSQL .= ") AS c ) base WHERE 1=1 ";

            //ค้นหาอนุญาติใช้
            if ($tSearchPrgStaUse != '0') {
                $tSQL .= " AND base.ChkStaUse = " . $this->db->escape($tSearchPrgStaUse) . "";
            }

            //ค้นหาอนุญาติล้างข้อมูล
            if ($tSearchPrgAllowPurge != '0') {
                $tSQL .= " AND base.ChkStaPurge = " . $this->db->escape($tSearchPrgAllowPurge) . "";
            }

            $tSQL .= " ORDER BY base.FTPrgKey DESC";

            $oQuery = $this->db->query($tSQL);

            if ($oQuery->num_rows() > 0) {
                $aList = $oQuery->result_array();
                // $oFoundRow = $this->FSoMBACGetPageAll($tSearchList, $nLngID);
                $nFoundRow = 1;
                $nPageAll = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            } else {
                //No Data
                $aResult = array(
                    'rnAllRow' => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage" => 0,
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : All Page Of SettingConperiod
    //Creator :  19/09/2019 Witsarut (Bell)
    public function FSoMBACGetPageAll($ptSearchList, $pnLngID)
    {
        try {
            $tSesAgnCode  = $this->session->userdata("tSesUsrAgnCode");
            $tSQL = "SELECT COUNT (POS.FTPosCode) AS counts
                    FROM TCNMPos POS  WITH(NOLOCK)
                    LEFT JOIN TCNMPos_L POS_L       WITH(NOLOCK) ON POS.FTPosCode = POS_L.FTPosCode AND POS_L.FNLngID = $pnLngID
                    LEFT JOIN TCNMBranch BCH        WITH(NOLOCK) ON POS.FTBchCode = BCH.FTBchCode
                    LEFT JOIN TCNMBranch_L BCH_L    WITH(NOLOCK) ON BCH.FTBchCode = BCH_L.FTBchCode AND BCH_L.FNLngID = $pnLngID
                    LEFT JOIN TCNMAgency_L AGN_L    WITH(NOLOCK) ON BCH.FTAgnCode = AGN_L.FTAgnCode AND AGN_L.FNLngID = $pnLngID
                    WHERE POS.FDCreateOn <> '' ";

            //input ค้นหา
            if (isset($ptSearchList['tSearchAll']) && !empty($ptSearchList['tSearchAll'])) {
                $tSQL .= " AND (POS.FTPosCode LIKE '%" . $this->db->escape_like_str($ptSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR POS_L.FTPosName  LIKE '%" . $this->db->escape_like_str($ptSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR AGN_L.FTAgnName  LIKE '%" . $this->db->escape_like_str($ptSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR BCH_L.FTBchName  LIKE '%" . $this->db->escape_like_str($ptSearchList['tSearchAll']) . "%')";
            }
            //ค้นหาตัวแทนขายแบบ Browse
            if (isset($ptSearchList['tSearchAgn']) && !empty($ptSearchList['tSearchAgn'])) {
                $tSQL .= " AND BCH.FTAgnCode = " . $this->db->escape($ptSearchList['tSearchAgn']) . "";
            }

            //ค้นหาสาขาแบบ Browse From - To
            $tSearchBchCodeFrom = $ptSearchList['tSearchBchCodeFrom'];
            $tSearchBchCodeTo   = $ptSearchList['tSearchBchCodeTo'];

            if ((isset($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) && (isset($tSearchBchCodeTo) && !empty($tSearchBchCodeTo))) {
                $tSQL .= " AND ((BCH.FTBchCode BETWEEN " . $this->db->escape($tSearchBchCodeFrom) . " AND " . $this->db->escape($tSearchBchCodeTo) . ") OR (BCH.FTBchCode BETWEEN " . $this->db->escape($tSearchBchCodeTo) . " AND " . $this->db->escape($tSearchBchCodeFrom) . "))";
            }

            //ค้นหาเครื่องจุดขายแบบ Browse From - To
            $tSearchPosCodeFrom = $ptSearchList['tSearchPosCodeFrom'];
            $tSearchPosCodeTo   = $ptSearchList['tSearchPosCodeTo'];

            if ((isset($tSearchPosCodeFrom) && !empty($tSearchPosCodeFrom)) && (isset($tSearchPosCodeTo) && !empty($tSearchPosCodeTo))) {
                $tSQL .= " AND ((POS.FTPosCode BETWEEN " . $this->db->escape($tSearchPosCodeFrom) . " AND " . $this->db->escape($tSearchPosCodeTo) . ") OR (POS.FTPosCode BETWEEN " . $this->db->escape($tSearchPosCodeTo) . " AND " . $this->db->escape($tSearchPosCodeFrom) . "))";
            }

            // Check User Login Branch
            if ($this->session->userdata('tSesUsrLevel') != 'HQ') {
                $tUserLoginBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
                $tSQL .=  " AND POS.FTBchCode IN($tUserLoginBchCode)";
            }

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            } else {
                return false;
            }
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Function Add/Update BacupAndClean
    //Parameters : function parameters
    //Creator : 06/08/2022 wasin
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMRSNAddUpdateBAC($paData)
    {
        try {
            $FNPrgKeep      = $paData['FNPrgKeep'];
            $FTPrgStaUse    = $paData['FTPrgStaUse'];
            $FTPrgStaPrg    = $paData['FTPrgStaPrg'];
            $FTPrgKey       = $paData['FTPrgKey'];
            $FNPrgDocType   = $paData['FNPrgDocType'];
            $tPrgUseStd     = $paData['tPrgUseStd'];

            if ($tPrgUseStd == '1') {
                
                $this->db->where('FTPrgTblHD', $paData['FTPrgKey']);
                $this->db->where('FNPrgDocType', $paData['FNPrgDocType']);
                $this->db->delete('TCNTPurgeSpc');
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success',
                );
            } else {
                //Update Master
                $this->db->set('FTPrgStaUse', $paData['FTPrgStaUse']);
                $this->db->set('FTPrgStaPrg', $paData['FTPrgStaPrg']);
                $this->db->set('FNPrgKeep', $paData['FNPrgKeep']);
                $this->db->where('FTPrgTblHD', $paData['FTPrgKey']);
                $this->db->where('FNPrgDocType', $paData['FNPrgDocType']);
                $this->db->update('TCNTPurgeSpc');
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Update Success',
                    );
                } else {

                    $tSQL = "
                    INSERT INTO TCNTPurgeSpc (
                        FTAgnCode, 
                        FTPrgTblHD, 
                        FNPrgDocType, 
                        FNPrgType, 
                        FTPrgGroup, 
                        FTPrgStaUse, 
                        FTPrgStaPrg, 
                        FDPrgLast, 
                        FNPrgKeep)
                    
                    SELECT 
                        '' AS FTAgnCode, 
                        FTPrgKey AS FTPrgTblHD, 
                        FNPrgDocType, 
                        FNPrgType, 
                        FTPrgGroup, 
                        $FTPrgStaUse, 
                        $FTPrgStaPrg, 
                        FDPrgLast, 
                        $FNPrgKeep AS FNPrgKeep
                    FROM TCNSPurgeHD HD WITH (NOLOCK)
                    WHERE HD.FTPrgKey = '$FTPrgKey'
                    AND HD.FNPrgDocType = '$FNPrgDocType'
                ";

                    $oQuery = $this->db->query($tSQL);

                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }
}
