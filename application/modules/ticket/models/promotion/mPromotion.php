<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mPromotion extends CI_Model {

    public $nPmtId;

    private function FCNaMPMTCallLenData($pnPerPage, $pnPage) {
        $nPerPage = $pnPerPage;
        if (isset($pnPage)) {
            $nPage = $pnPage;
        } else {
            $nPage = 1;
        }
        $nRowStart = (($nPerPage * $nPage) - $nPerPage);
        $nRowEnd = $nPerPage * $nPage;
        $aLenData = array(
            $nRowStart,
            $nRowEnd
        );
        return $aLenData;
    }

    /**
     * Zone
     */
    public function FSxMPMTList($tFTPmtName, $nPageNo = 1) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $aRowLen    = $this->FCNaMPMTCallLenData(8, $nPageNo);
        $tSQL       = "
            SELECT c.* FROM(
                SELECT 
                    ROW_NUMBER() OVER(ORDER BY PMT.FNPmhID DESC) AS RowID,	
                    PMT.*, 
                    PML.FTPmhName,
                    PML.FTPmhDesc,
                    IMG.FTImgObj
                FROM TTKTPmtList PMT
                LEFT JOIN TTKTPmtList_L PML ON PML.FNPmhID = PMT.FNPmhID AND PML.FNLngID = ".$this->db->escape($nLangID)."
                LEFT JOIN TCNMImgObj IMG ON IMG.FTImgRefID = PMT.FNPmhID AND FTImgTable  = 'TTKTPmtList'";
        if ($tFTPmtName != '') {
            $tSQL   .= " WHERE PML.FTPmhName LIKE '%".$this->db->escape_like_str($tFTPmtName)."%'";
        }
        $tSQL   .= ") AS c WHERE c.RowID > ".$this->db->escape($aRowLen[0])." AND c.RowID <= ".$this->db->escape($aRowLen[1])." ";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FStMPMTCount($tFTPmtName) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT COUNT(PMT.FNPmhID) AS counts
            FROM TTKTPmtList PMT
            LEFT JOIN TTKTPmtList_L PML ON PML.FNPmhID = PMT.FNPmhID AND PML.FNLngID = ".$this->db->escape($nLangID)."
        ";
        if ($tFTPmtName != '') {
            $tSQL   .= " WHERE PML.FTPmhName LIKE '%".$this->db->escape_like_str($tFTPmtName)."%'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTBranch() {
        $nLangID        = $this->session->userdata("tLangEdit");
        $tSesUsername   = $this->session->userdata("tSesUsername");
        $tSQL           = "
            SELECT MOD.FNPmoID, MOL.FTPmoName
            FROM TCNMUser USR
            INNER JOIN TCNTUsrGroup GRP ON USR.FTUsrCode = GRP.FTUsrCode
            INNER JOIN TTKMPdtModel MOD ON MOD.FTBchCode = GRP.FTBchCode
            INNER JOIN TTKMPdtModel_L MOL ON MOL.FNPmoID = MOD.FNPmoID AND MOL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE  USR.FTUsrCode    = ".$this->db->escape($tSesUsername)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTAdd($aData) {
        $this->db->insert('TTKTPmtList', array(
            'FTPmhCode' => $aData ['FTPmhCode'],
            'FCPmhBuyAmt' => $aData ['FCPmhBuyAmt'],
            'FCPmhGetValue' => $aData ['FCPmhGetValue'],
            'FCPmhGetCond' => $aData ['FCPmhGetCond'],
            'FTPmhClosed' => $aData ['FTPmhClosed'],
            'FDPmhActivate' => $aData ['FDPmhActivate'],
            'FDPmhExpired' => $aData ['FDPmhExpired'],
            'FTPmhStaSpcPdt' => $aData ['FTPmhStaSpcPdt'],
            'FTPmhStaSpcPark' => $aData ['FTPmhStaSpcPark'],
            'FTPmhStaSpcGrp' => $aData ['FTPmhStaSpcGrp'],
            'FDPmhTActivate' => $aData ['FDPmhTActivate'],
            'FDPmhTExpired' => $aData ['FDPmhTExpired'],
            'FTWhoIns' => $this->session->userdata("tSesUsername"),
            'FDDateIns' => date('Y-m-d'),
            'FTTimeIns' => date('h:i:s')
        ));
        $this->nPmtId = $this->db->insert_id();
        $this->db->insert('TTKTPmtList_L', array(
            'FNPmhID' => $this->nPmtId,
            'FTPmhName' => $aData ['FTPmhName'],
            'FNLngID' => $this->session->userdata("tLangEdit")
        ));
        return $this->nPmtId;
    }

    public function FSxMPMTSpcPdt($aData) {
        $this->db->insert('TTKTPmtSpcPdt', array(
            'FNPmhID' => $aData ['FNPmhID'],
            'FTPspRefType' => $aData ['FTPspRefType'],
            'FTPspStaExclude' => $aData ['FTPspStaExclude'],
            'FNPspCodeRef' => $aData ['FNPspCodeRef']
        ));        
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->update('TTKTPmtList', array(
            'FTPmhStaSpcPdt' => '1'
        ));
    }

    public function FSxMPMTSpcPark($aData) {
        $this->db->insert('TTKTPmtSpcPark', array(
            'FNPmhID' => $aData ['FNPmhID'],
            'FTPspStaExclude' => $aData ['FTPspStaExclude'],
            'FNPmoID' => $aData ['FNPmoID']
        ));        
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->update('TTKTPmtList', array(
            'FTPmhStaSpcPark' => '1'
        ));
    }

    public function FSxMPMTSpcGrp($aData) {
        $this->db->insert('TTKTPmtSpcGrp', array(
            'FNPmhID' => $aData ['FNPmhID'],
            'FTPsgType' => $aData ['FTPsgType'],
            'FTPsgStaExclude' => $aData ['FTPsgStaExclude'],
            'FTPsgRefID' => $aData ['FTPsgRefID']
        ));

        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->update('TTKTPmtList', array(
            'FTPmhStaSpcGrp' => '1'
        ));
    }

    public function FSxMPMTEdit($aData) {
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->update('TTKTPmtList', array(
            'FCPmhBuyAmt' => $aData ['FCPmhBuyAmt'],
            'FCPmhGetValue' => $aData ['FCPmhGetValue'],
            'FCPmhGetCond' => $aData ['FCPmhGetCond'],
            'FTPmhClosed' => $aData ['FTPmhClosed'],
            'FDPmhActivate' => $aData ['FDPmhActivate'],
            'FDPmhExpired' => $aData ['FDPmhExpired'],
            'FTPmhStaSpcPdt' => $aData ['FTPmhStaSpcPdt'],
            'FTPmhStaSpcPark' => $aData ['FTPmhStaSpcPark'],
            'FTPmhStaSpcGrp' => $aData ['FTPmhStaSpcGrp'],
            'FDPmhTActivate' => $aData ['FDPmhTActivate'],
            'FDPmhTExpired' => $aData ['FDPmhTExpired'],
            'FTWhoUpd' => $this->session->userdata("tSesUsername"),
            'FDDateUpd' => date('Y-m-d'),
            'FTTimeUpd' => date('h:i:s')
        ));
        $nChk = FSnCheckUpdateLang('TTKTPmtList_L', 'FNPmhID', $aData ['FNPmhID']);
        if ($nChk [0]->counts == 0) {
            $this->db->insert('TTKTPmtList_L', array(
                'FNPmhID' => $aData ['FNPmhID'],
                'FTPmhName' => $aData ['FTPmhName'],
                'FNLngID' => $this->session->userdata("tLangEdit")
            ));
        } else {
            $this->db->where('FNPmhID', $aData ['FNPmhID']);
            $this->db->where('FNLngID', $this->session->userdata("tLangEdit"));
            $this->db->update('TTKTPmtList_L', array(
                'FTPmhName' => $aData ['FTPmhName']
            ));
        }
    }

    public function FSxMPMTApv($aData) {
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->update('TTKTPmtList', array(
            'FTPmhStaPrcDoc' => 1,
            'FTWhoUpd' => $this->session->userdata("tSesUsername"),
            'FDDateUpd' => date('Y-m-d'),
            'FTTimeUpd' => date('h:i:s')
        ));
    }

    public function FSxMPMTDel($aData) {
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->delete('TTKTPmtList');
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->delete('TTKTPmtList_L');
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->delete('TTKTPmtSpcPark');
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->delete('TTKTPmtSpcPdt');
        $this->db->where('FNPmhID', $aData ['FNPmhID']);
        $this->db->delete('TTKTPmtSpcGrp');
    }

    public function FSxMPMTChkCode($tFTPmhCode) {
        $tSQL = "SELECT COUNT(FTPmhCode) AS FNCount
		FROM TTKTPmtList 
		WHERE FTPmhCode = '$tFTPmhCode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTSBranch($nId) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT PRK.*, MOD.FNPmoID, MOL.FTPmoName
            FROM TTKTPmtSpcPark PRK
            LEFT JOIN TTKMPdtModel MOD ON MOD.FNPmoID = PRK.FNPmoID
            LEFT JOIN TTKMPdtModel_L MOL ON MOL.FNPmoID = MOD.FNPmoID AND MOL.FNLngID   = ".$this->db->escape($nLangID)."
            WHERE PRK.FNPmhID   = ".$this->db->escape($nId)."
		";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTSPdt($nId) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT PDT.*, PKG.FNPkgID, PGL.FTPkgName
            FROM TTKTPmtSpcPdt PDT
            LEFT JOIN TTKTPkgList PKG ON PKG.FNPkgID = PDT.FNPspCodeRef
            LEFT JOIN TTKTPkgList_L PGL ON PGL.FNPkgID = PKG.FNPkgID AND PGL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE PDT.FNPmhID    = ".$this->db->escape($nId)."
            AND PDT.FTPspRefType = '2'
		";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTDelSpcPdt($aData) {
        $this->db->where('FNPspID', $aData ['FNPspID']);
        $this->db->delete('TTKTPmtSpcPdt');
        $tSQL   = "
            SELECT COUNT(FNPmhID) AS FNCount
            FROM TTKTPmtSpcPdt WITH(NOLOCK)
            WHERE FNPmhID   = ".$this->db->escape($aData ['FNPmhID'])."
        ";
        $query = $this->db->query($tSQL);
        $oResult = $query->result();
        if ($oResult [0]->FNCount == 0) {
            $this->db->where('FNPmhID', $aData ['FNPmhID']);
            $this->db->update('TTKTPmtList', array(
                'FTPmhStaSpcPdt' => 2,
            ));
        }
    }

    public function FSxMPMTDelSpcPark($aData) {
        $this->db->where('FNPspID', $aData ['FNPspID']);
        $this->db->delete('TTKTPmtSpcPark');
        // Check TTKTPmtSpcPark
        $tSQL   = "
            SELECT COUNT(FNPmhID) AS FNCount
            FROM TTKTPmtSpcPark WITH(NOLOCK)
            WHERE FNPmhID = ".$this->db->escape($aData ['FNPmhID'])."
        ";
        $query      = $this->db->query($tSQL);
        $oResult    = $query->result();
        if ($oResult [0]->FNCount == 0) {
            $this->db->where('FNPmhID', $aData ['FNPmhID']);
            $this->db->update('TTKTPmtList', array(
                'FTPmhStaSpcPark' => 2,
            ));
        }
    }

    public function FSxMPMTDelSpcGrp($aData) {
        $this->db->where('FNPsgGrpID', $aData ['FNPsgGrpID']);
        $this->db->delete('TTKTPmtSpcGrp');
        $tSQL   = "
            SELECT COUNT(FNPmhID) AS FNCount
            FROM TTKTPmtSpcGrp WITH(NOLOCK)
            WHERE FNPmhID   = ".$this->db->escape($aData ['FNPmhID'])."
        ";
        $query = $this->db->query($tSQL);
        $oResult = $query->result();
        if ($oResult [0]->FNCount == 0) {
            $this->db->where('FNPmhID', $aData ['FNPmhID']);
            $this->db->update('TTKTPmtList', array(
                'FTPmhStaSpcGrp' => 2,
            ));
        }
    }

    public function FSxMPMTSAgn($nId) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT GRP.*, AGN.FTAggCode, AGL.FTAggName
            FROM TTKTPmtSpcGrp GRP WITH(NOLOCK)
            LEFT JOIN TCNMAgencyGrp AGN WITH(NOLOCK) ON AGN.FTAggCode = GRP.FTPsgRefID
            LEFT JOIN TCNMAgencyGrp_L AGL WITH(NOLOCK) ON AGL.FTAggCode = AGN.FTAggCode AND AGL.FNLngID  = ".$this->db->escape($nLangID)."
            WHERE GRP.FNPmhID   = ".$this->db->escape($nId)."
            AND GRP.FTPsgType   = '1'
		";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTSCst($nId) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT GRP.*, CST.FNCgpID, CSL.FTCgpName
            FROM TTKTPmtSpcGrp GRP WITH(NOLOCK)
            LEFT JOIN TTKMCstGrp CST WITH(NOLOCK) ON CST.FNCgpID = GRP.FTPsgRefID
            LEFT JOIN TTKMCstGrp_L CSL WITH(NOLOCK) ON CSL.FNCgpID = CST.FNCgpID AND CSL.FNLngID    = ".$this->db->escape($nLangID)."
            WHERE GRP.FNPmhID   = ".$this->db->escape($nId)."
            AND GRP.FTPsgType   = '2'
		";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTSPmt($nId) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT PMT.*, PML.FTPmhName, IMG.FTImgObj
            FROM TTKTPmtList PMT WITH(NOLOCK)
            LEFT JOIN TTKTPmtList_L PML WITH(NOLOCK) ON PML.FNPmhID = PMT.FNPmhID AND PML.FNLngID   = ".$this->db->escape($nLangID)."
            LEFT JOIN TCNMImgObj IMG WITH(NOLOCK) ON IMG.FTImgRefID = PMT.FNPmhID AND FTImgTable    = 'TTKTPmtList'
            WHERE PMT.FNPmhID   = ".$this->db->escape($nId)."
		";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTPkgList($tFTPkgName, $tListItem, $nPageNo = 1) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $aRowLen    = $this->FCNaMPMTCallLenData(8, $nPageNo);
        $tSQL       = "
            SELECT c.* FROM(
                SELECT ROW_NUMBER() OVER(ORDER BY PKG.FNPkgID DESC) AS RowID,
                PKG.*, PKL.FTPkgName
            FROM TTKTPkgList PKG
            LEFT JOIN TTKTPkgList_L PKL ON PKL.FNPkgID = PKG.FNPkgID AND PKL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE PKG.FTPkgStaPrcDoc = '1'
        ";
        if ($tFTPkgName != '') {
            $tSQL   .= " AND PKL.FTPkgName LIKE '%".$this->db->escape_like_str($tFTPkgName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND PKG.FNPkgID NOT IN ($tListItem)";
        }
        $tSQL .= ") AS c WHERE c.RowID > ".$this->db->escape($aRowLen[0])." AND c.RowID <= ".$this->db->escape($aRowLen[1])." ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTPkgCount($tFTPkgName, $tListItem) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT COUNT(PKG.FNPkgID) AS counts
            FROM TTKTPkgList PKG WITH(NOLOCK)
            LEFT JOIN TTKTPkgList_L PKL WITH(NOLOCK) ON PKL.FNPkgID = PKG.FNPkgID AND PKL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE PKG.FTPkgStaPrcDoc = '1'
        ";
        if ($tFTPkgName != '') {
            $tSQL   .= " AND PKL.FTPkgName LIKE '%".$this->db->escape_like_str($tFTPkgName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND PKG.FNPkgID NOT IN ($tListItem)";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTBchList($tFTPmoName, $tListItem, $nPageNo = 1) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $aRowLen    = $this->FCNaMPMTCallLenData(8, $nPageNo);
        $tSQL       = "
            SELECT c.* FROM(
                SELECT ROW_NUMBER() OVER(ORDER BY MOD.FNPmoID DESC) AS RowID,
                MOD.*,
                MOL.FTPmoName
            FROM TTKMPdtModel MOD WITH(NOLOCK)
            LEFT JOIN TTKMPdtModel_L MOL WITH(NOLOCK) ON MOL.FNPmoID = MOD.FNPmoID AND MOL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE 1 = 1
		";
        if ($tFTPmoName != '') {
            $tSQL   .= " AND MOL.FTPmoName LIKE '%".$this->db->escape_like_str($tFTPmoName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND MOD.FNPmoID NOT IN ($tListItem)";
        }
        $tSQL .= ") AS c WHERE c.RowID > $aRowLen[0] AND c.RowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTBchCount($tFTPmoName, $tListItem) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT COUNT(MOD.FNPmoID) AS counts
            FROM TTKMPdtModel MOD
            LEFT JOIN TTKMPdtModel_L MOL ON MOL.FNPmoID = MOD.FNPmoID AND MOL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE 1 = 1
        ";
        if ($tFTPmoName != '') {
            $tSQL   .= " AND MOL.FTPmoName LIKE '%".$this->db->escape_like_str($tFTPmoName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND MOD.FNPmoID NOT IN ($tListItem)";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTAgnList($tFTAggName, $tListItem, $nPageNo = 1) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $aRowLen    = $this->FCNaMPMTCallLenData(8, $nPageNo);
        $tSQL       = "
            SELECT c.* FROM(
                SELECT 
                    ROW_NUMBER() OVER(ORDER BY GRP.FTAggCode DESC) AS RowID,
                    GRP.*,
                    GPL.FTAggName
                FROM TCNMAgencyGrp GRP
                LEFT JOIN TCNMAgencyGrp_L GPL ON GPL.FTAggCode = GRP.FTAggCode AND GPL.FNLngID = ".$this->db->escape($nLangID)."
                WHERE 1=1
		";
        if ($tFTAggName != '') {
            $tSQL   .= " AND GPL.FTAggName LIKE '%".$this->db->escape_like_str($tFTAggName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND GRP.FTAggCode NOT IN ($tListItem)";
        }
        $tSQL .= ") AS c WHERE c.RowID > ".$this->db->escape($aRowLen[0])." AND c.RowID <= ".$this->db->escape($aRowLen[1])." ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTAgnCount($tFTAggName, $tListItem) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT COUNT(GRP.FTAggCode) AS counts
            FROM TCNMAgencyGrp GRP
            LEFT JOIN TCNMAgencyGrp_L GPL ON GPL.FTAggCode = GRP.FTAggCode AND GPL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE 1=1
        ";
        if ($tFTAggName != '') {
            $tSQL   .= " AND GPL.FTAggName LIKE '%".$this->db->escape_like_str($tFTAggName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND GRP.FTAggCode NOT IN ($tListItem)";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTCstList($tFTCgpName, $tListItem, $nPageNo = 1) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $aRowLen    = $this->FCNaMPMTCallLenData(8, $nPageNo);
        $tSQL       = "
            SELECT c.* FROM(
                SELECT 
                    ROW_NUMBER() OVER(ORDER BY GRP.FNCgpID DESC) AS RowID,
                    GRP.*,
                    GPL.FTCgpName
                FROM TTKMCstGrp GRP
                LEFT JOIN TTKMCstGrp_L GPL ON GPL.FNCgpID = GRP.FNCgpID AND GPL.FNLngID = ".$this->db->escape($nLangID)."
                WHERE 1=1
		";
        if ($tFTCgpName != '') {
            $tSQL   .= " AND GPL.FTCgpName LIKE '%".$this->db->escape_like_str($tFTCgpName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND GRP.FNCgpID NOT IN ($tListItem)";
        }
        $tSQL  .= ") AS c WHERE c.RowID > ".$this->db->escape($aRowLen[0])." AND c.RowID <= ".$this->db->escape($aRowLen[1])." ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    public function FSxMPMTCstCount($tFTCgpName, $tListItem) {
        $nLangID    = $this->session->userdata("tLangEdit");
        $tSQL       = "
            SELECT COUNT(GRP.FNCgpID) AS counts
            FROM TTKMCstGrp GRP
            LEFT JOIN TTKMCstGrp_L GPL ON GPL.FNCgpID = GRP.FNCgpID AND GPL.FNLngID = ".$this->db->escape($nLangID)."
            WHERE 1=1
        ";
        if ($tFTCgpName != '') {
            $tSQL   .= " AND GPL.FTCgpName LIKE '%".$this->db->escape_like_str($tFTCgpName)."%'";
        }
        if ($tListItem != '') {
            $tSQL   .= " AND GRP.FNCgpID NOT IN ($tListItem)";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

}
