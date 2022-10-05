<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mSalePriceAdj extends CI_Model{

    //Functionality : list Sale Price Adj
    //Parameters    : function parameters
    //Creator       :  18/02/2019 Napat(Jame)
    //Return        : data
    //Return Type   : Array
    public function FSaMSPAList($paData){
        $tUsrBchCode    = $this->session->userdata("tSesUsrBchCodeMulti");
        $nLngID         = $paData['FNLngID'];
        $aAdvanceSearch = $paData['oAdvanceSearchData'];
        $tSQL = " 
            SELECT TOP ". get_cookie('nShowRecordInPageList')."
                (CASE
                    WHEN 
                        PPH.FTXphStaDoc <> '3'
                        AND CONCAT(CONVERT(CHAR(10),PPH.FDXphDStart,120),' ',CONVERT(CHAR(8), PPH.FTXphTStart, 108)) <= CONVERT(CHAR(19), GETDATE(), 120) 
                        AND CONCAT(CONVERT(CHAR(10),PPH.FDXphDStop,120),' ',CONVERT(CHAR(8), PPH.FTXphTStop, 108)) >= CONVERT(CHAR(19), GETDATE(), 120)
                    THEN '2'

                    WHEN  
                        PPH.FTXphStaDoc <> '3' 
                        AND CONVERT(CHAR(10),PPH.FDXphDStart,120) > CONVERT(CHAR(19), GETDATE(), 120)
                    THEN '3'

                    WHEN 
                        PPH.FTXphStaDoc <> '3' 
                        AND CONCAT(CONVERT(CHAR(10),PPH.FDXphDStop,120),' ',CONVERT(CHAR(8), PPH.FTXphTStop, 108)) < CONVERT(CHAR(19), GETDATE(), 120)
                    THEN '4'

                    WHEN 
                        PPH.FTXphStaDoc = '3'
                    THEN '5'
                    
                    ELSE '6'
                END) AS UsedStatus, 
                BCH_L.FTBchName AS FTBchName,
                PPH.FTBchCode AS FTBchCode,
                PPH.FTXphDocNo AS FTXphDocNo,
                PPH.FDXphDocDate AS FDXphDocDate,
                PPH.FTXphStaDoc AS FTXphStaDoc,
                PPH.FTXphStaPrcDoc AS FTXphStaPrcDoc,
                PPH.FTXphStaApv AS FTXphStaApv,
                PPH.FDCreateON AS FDCreateON,
                CUSR_L.FTUsrName AS FTCreateBy,
                AUSR_L.FTUsrName AS FTXphUsrApv
            FROM TCNTPdtAdjPriHD PPH WITH(NOLOCK)
            LEFT JOIN TCNMBranch BCHPPL WITH(NOLOCK) ON PPH.FTPplCode = BCHPPL.FTPplCode    AND PPH.FTBchCode   = BCHPPL.FTBchCode
            LEFT JOIN TCNMBranch_L BCH_L WITH(NOLOCK) ON PPH.FTBchCode = BCH_L.FTBchCode    AND BCH_L.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L CUSR_L WITH(NOLOCK) ON PPH.FTCreateBy = CUSR_L.FTUsrCode   AND CUSR_L.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L AUSR_L WITH(NOLOCK) ON PPH.FTXphUsrApv = AUSR_L.FTUsrCode  AND AUSR_L.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNTPdtAdjPriRT ART WITH(NOLOCK) ON ART.FTXphDocNo = PPH.FTXphDocNo
            WHERE ART.FTXphDocNo IS NULL    
        ";
        $tSearchList = $aAdvanceSearch['tSearchAll'];
        if ($tSearchList != '') {
            $tSQL   .= " AND (PPH.FTXphDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }
        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)) {
            $tSQL   .= " AND ((PPH.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).") OR (PPH.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom)."))";
        }
        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL   .= " AND ((PPH.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:59:59").")) OR (PPH.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00").")))";
        }
        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {

            if ($tSearchStaDoc == 3) {
                $tSQL   .= " AND PPH.FTXphStaDoc  = ".$this->db->escape($tSearchStaDoc)." ";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL   .= " AND ISNULL(PPH.FTXphStaApv,'') = '' AND PPH.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL   .= " AND PPH.FTXphStaApv IS NOT NULL";
            }
        }
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaAct = $aAdvanceSearch['tSearchStaAct'];
        if (!empty($tSearchStaAct) && ($tSearchStaAct != "0")) {
            if ($tSearchStaAct == 1) {
                $tSQL   .= " AND PPH.FNXphStaDocAct = 1";
            } else {
                $tSQL   .= " AND PPH.FNXphStaDocAct = 0";
            }
        }
        // ค้นหาสถานะประมวลผล
        $tSearchStaPrc = $aAdvanceSearch['tSearchStaPrcStk'];
        if (isset($tSearchStaPrc) && !empty($tSearchStaPrc)) {
            if ($tSearchStaPrc == 3) {
                $tSQL   .= " AND (PPH.FTXphStaPrcDoc = ".$this->db->escape($tSearchStaPrc)." OR ISNULL(PPH.FTXphStaPrcDoc,'') = '') ";
            } else {
                $tSQL   .= " AND PPH.FTXphStaPrcDoc  = ".$this->db->escape($tSearchStaPrc)." ";
            }
        }
        // สถานะการใช้งาน
        $tSearchUsedStatus = $aAdvanceSearch['tSearchUsedStatus'];
        switch ($tSearchUsedStatus) {
            case '2': { // ใช้งาน
                    $tSQL .= "
                        AND (
                            PPH.FTXphStaDoc <> '3' 
                            AND CONCAT(CONVERT(CHAR(10),PPH.FDXphDStart,120),' ',CONVERT(CHAR(8), PPH.FTXphTStart, 108)) <= CONVERT(CHAR(19), GETDATE(), 120) 
                            AND CONCAT(CONVERT(CHAR(10),PPH.FDXphDStop,120),' ',CONVERT(CHAR(8), PPH.FTXphTStop, 108)) >= CONVERT(CHAR(19), GETDATE(), 120)
                        )
                    ";
                    break;
                }
            case '3': { // ยังไม่เริ่ม
                    $tSQL .= "
                        AND (
                            PPH.FTXphStaDoc <> '3' 
                            AND CONVERT(CHAR(10),PPH.FDXphDStart,120) > CONVERT(CHAR(19), GETDATE(), 120)
                        )
                    ";
                    break;
                }
            case '4': { // หมดอายุ
                    $tSQL .= "
                        AND (
                            PPH.FTXphStaDoc <> '3' 
                            AND CONCAT(CONVERT(CHAR(10),PPH.FDXphDStop,120),' ',CONVERT(CHAR(8), PPH.FTXphTStop, 108)) < CONVERT(CHAR(19), GETDATE(), 120)
                        )
                    ";
                    break;
                }
            case '5': { // ยกเลิก
                    $tSQL .= "
                        AND PPH.FTXphStaDoc = '3'
                    ";
                    break;
                }
            default: {
                }
        }
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tSQL .= " AND PPH.FTBchCode IN ($tUsrBchCode)";
        }
        $tSQL   .= " ORDER BY FDCreateON DESC";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems'       => $aList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        
        return $aResult;
    }

    //Functionality : list Product Price Data Table
    //Parameters : function parameters
    //Creator :  18/02/2019 Napat(Jame)
    //Return : data
    //Return Type : Array
    public function FSaMSPAPdtPriList($paData){
        try {
            
            $nLngID         = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $FTXthDocKey    = $paData['FTXthDocKey'];
            $FTXphDocNo     = $paData['FTXphDocNo'];
            $FTSessionID    = $paData['FTSessionID'];

    ////เช็คตัวที่ไม่มี puncode
            $tSQL2 = " 
            SELECT
			TMP.FTPunCode AS FTPunCodeTemp,
			TMP.FTPdtCode AS FTPdtCodeTemp,
			ISNULL( MAS.FTPunCode, MAS.FTPunCode ) AS FTPunCode,
			ISNULL( MAS.FTPdtCode, MAS.FTPdtCode ) AS FTPdtCode
		    FROM
			TCNTDocDTTmp TMP
			LEFT JOIN TCNMPdtPackSize MAS WITH ( NOLOCK ) ON MAS.FTPdtCode = TMP.FTPdtCode AND MAS.FCPdtUnitFact = 1
		    WHERE
			1 = 1 
			AND TMP.FTSessionID = ".$this->db->escape($FTSessionID)."
            AND ( FTTmpStatus = '2' OR FTTmpStatus = '7' )
            AND MAS.FTPunCode != ''
            ";

            $oQuery2  = $this->db->query($tSQL2);
            $aList2      = $oQuery2->result_array();

            foreach($aList2 as $key => $aval){
                    $this->db->where('FTSessionID', $paData['FTSessionID']);
                    $this->db->where('FTPunCode', $aval['FTPunCodeTemp']);
                    $this->db->where('FTPdtCode', $aval['FTPdtCodeTemp']);
                    $this->db->update('TCNTDocDTTmp', array(
                        'FTPunCode'         => $aval['FTPunCode'],
                        'FTTmpStatus'       => 1,
                        'FTTmpRemark'       => '',
                    ));
            }

    ////จบเช็คตัวที่ไม่มี puncode

            $tSQL   = "
                SELECT --TOP ". get_cookie('nShowRecordInPageList')."
                    DTP.FTXthDocNo AS FTXthDocNo,
                    DTP.FNXtdSeqNo AS FNXtdSeqNo,
                    DTP.FTPdtCode AS FTPdtCode,
                    DTP.FTPunCode AS FTPunCode,
                    PDT_L.FTPdtName AS FTPdtName,
                    PUN_L.FTPunName AS FTPunName,
                    BCH_L.FTBchName AS FTBchName,
                    SHP_L.FTShpName AS FTShpName,
                    DTP.FCXtdPriceRet AS FCXtdPriceRet,
                    DTP.FCXtdPriceWhs AS FCXtdPriceWhs,
                    DTP.FCXtdPriceNet AS FCXtdPriceNet,
                    DTP.FTXthDocNo AS FTDefalutPrice,
                    DTP.FTXtdShpTo AS FTXtdShpTo,
                    DTP.FTXtdBchTo AS FTXtdBchTo,
                    DTP.FTTmpRemark AS FTTmpRemark,
                    DTP.FTTmpStatus AS FTTmpStatus,
                    convert(varchar, DTP.FDCreateOn,103) AS FDDateIns,
                    convert(varchar, DTP.FDLastUpdOn,103) AS FDDateUpd,
					stuff( (  SELECT DISTINCT ',' + cast(PAC.FTPunCode as varchar(max))
							  FROM TCNTDocDTTmp DTP
											LEFT JOIN TCNMPdtPackSize PAC ON PAC.FTPdtCode = DTP.FTPdtCode
											WHERE DTP.FTSessionID = ".$this->db->escape($FTSessionID)." 
											AND DTP.FTXthDocKey = ".$this->db->escape($FTXthDocKey)."
							  for xml path ('')
							), 1, 1, '' ) AS FTAllPunCode,

					stuff( (	SELECT DISTINCT ',' + cast(UNITL.FTPunName as varchar(max))
								FROM TCNTDocDTTmp DTP
												LEFT JOIN TCNMPdtPackSize PAC ON DTP.FTPdtCode = PAC.FTPdtCode  
												LEFT JOIN TCNMPdtUnit_L UNITL ON PAC.FTPunCode = UNITL.FTPunCode AND UNITL.FNLngID = ".$this->db->escape($nLngID)."
												WHERE DTP.FTSessionID = ".$this->db->escape($FTSessionID)." 
												AND DTP.FTXthDocKey = ".$this->db->escape($FTXthDocKey)."
								 for xml path ('')
							), 1, 1, '' ) AS FTAllPunName
                    /*STRING_AGG(PAC.FTPunCode,',') AS FTAllPunCode,
                    STRING_AGG ( UNITL.FTPunName, ',' ) AS FTAllPunName*/
                FROM TCNTDocDTTmp DTP
                LEFT JOIN TCNMPdt_L PDT_L ON DTP.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID      = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMPdtUnit_L PUN_L ON DTP.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID  = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMShop_L SHP_L ON DTP.FTXtdShpTo = SHP_L.FTShpCode AND DTP.FTBchCode = SHP_L.FTBchCode  AND SHP_L.FNLngID   = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMBranch_L BCH_L ON DTP.FTXtdBchTo = BCH_L.FTBchCode AND BCH_L.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMPdtPackSize PAC ON PAC.FTPdtCode = DTP.FTPdtCode  AND PAC.FTPunCode = DTP.FTPunCode
                LEFT JOIN TCNMPdtUnit_L UNITL ON PAC.FTPunCode = UNITL.FTPunCode AND UNITL.FNLngID = ".$this->db->escape($nLngID)."
                WHERE DTP.FDCreateOn <> '' 
                AND DTP.FTSessionID     = ".$this->db->escape($FTSessionID)." 
                AND DTP.FTXthDocKey     = ".$this->db->escape($FTXthDocKey)."
            ";
            if (isset($FTXphDocNo) && !empty($FTXphDocNo)) {
                // $tSQL .= " AND DTP.FTXthDocNo = '$FTXphDocNo'";
            }
                // AND (DTP.FTTmpStatus    = 1 OR ISNULL(DTP.FTTmpStatus,'') = '')
            if (isset($tSearchList) && !empty($tSearchList)) {
                $tSQL   .= " AND (DTP.FTPdtCode LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR PDT_L.FTPdtName LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR PUN_L.FTPunName LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR SHP_L.FTShpName LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR BCH_L.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
            }

            $tSQL   .= " GROUP BY DTP.FTPdtCode,
									DTP.FTXthDocNo,
									DTP.FNXtdSeqNo,
									DTP.FTPunCode,
									PAC.FTPunCode,
									UNITL.FTPunName,
									PDT_L.FTPdtName,
									PUN_L.FTPunName,
									BCH_L.FTBchName,
									SHP_L.FTShpName,
									DTP.FCXtdPriceRet,
									DTP.FCXtdPriceWhs,
									DTP.FCXtdPriceNet,
									DTP.FTXthDocNo,
									DTP.FTXtdShpTo,
									DTP.FTXtdBchTo,
									DTP.FTTmpRemark,
									DTP.FTTmpStatus,
									DTP.FDCreateOn,
									DTP.FDLastUpdOn 
            ORDER BY DTP.FNXtdSeqNo ASC";
        
            $oQuery  = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aList      = $oQuery->result_array();
                $oFoundRow  = $this->FSoMSPAGetPdtPriPageAll($tSearchList, $FTXphDocNo, $FTSessionID, $nLngID, $FTXthDocKey);
                $nFoundRow  = $oFoundRow[0]->counts;
                $nPageAll   = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            } else {
                //No Data
                $aResult    = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : All Page Of Product Size
    //Parameters : function parameters
    //Creator :  25/02/2019 Napat(Jame)
    //Return : object Count All Product Model
    //Return Type : Object
    public function FSoMSPAGetPdtPriPageAll($ptSearchList, $ptXphDocNo, $FTSessionID, $nLngID, $FTXthDocKey){
        try {
            $tSQL   = "
                SELECT 
                    COUNT (DTP.FTSessionID) AS counts
                FROM TCNTDocDTTmp DTP WITH(NOLOCK)
                LEFT JOIN TCNMPdt_L PDT_L      ON DTP.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID  = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMPdtUnit_L PUN_L  ON DTP.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID  = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMShop_L SHP_L     ON DTP.FTXtdShpTo = SHP_L.FTShpCode AND DTP.FTBchCode = SHP_L.FTBchCode AND SHP_L.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMBranch_L BCH_L   ON DTP.FTXtdBchTo = BCH_L.FTBchCode AND BCH_L.FNLngID = ".$this->db->escape($nLngID)."
                WHERE DTP.FTSessionID = ".$this->db->escape($FTSessionID)." AND DTP.FTXthDocKey = ".$this->db->escape($FTXthDocKey)."
                AND (DTP.FTTmpStatus = 1 OR ISNULL(DTP.FTTmpStatus,'') = '')
            ";
            if (isset($ptSearchList) && !empty($ptSearchList)) {
                $tSQL   .= " AND (DTP.FTPdtCode  LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
                $tSQL   .= " OR PDT_L.FTPdtName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
                $tSQL   .= " OR PUN_L.FTPunName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
                $tSQL   .= " OR SHP_L.FTShpName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
                $tSQL   .= " OR BCH_L.FTBchName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
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

    //Functionality : Get Data Sale Price Adj By ID
    //Parameters : function parameters
    //Creator : 15/02/2019 Napat(Jame)
    //Return : data
    //Return Type : Array
    public function FSaMSPAGetDataByID($paData){
        try {
            $FTXphDocNo = $paData['FTXphDocNo'];
            $nLngID     = $paData['FNLngID'];
            $tSQL       = "
                SELECT
                    BCH_L.FTBchCode     AS FTBchCode,
                    BCH_L.FTBchName     AS FTBchName,
                    ''     AS FTZneName,
                    ''     AS FTAggName,
                    PPL_L.FTPplName     AS FTPplName,
                    CUSR_L.FTUsrName    AS FTCreateBy,
                    CUSR_L.FTUsrName    AS FTUsrName,
                    AUSR_L.FTUsrName    AS FTXphUsrApvName,
                    ''     AS FTMerName,
                    PPH.*
                FROM TCNTPdtAdjPriHD PPH
                LEFT JOIN TCNMBranch_L      BCH_L   ON PPH.FTBchCode    = BCH_L.FTBchCode   AND BCH_L.FNLngID   = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L        CUSR_L  ON PPH.FTCreateBy   = CUSR_L.FTUsrCode  AND CUSR_L.FNLngID  = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L        AUSR_L  ON PPH.FTXphUsrApv  = AUSR_L.FTUsrCode  AND AUSR_L.FNLngID  = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMPdtPriList_L  PPL_L   ON PPH.FTPplCode    = PPL_L.FTPplCode   AND PPL_L.FNLngID   = ".$this->db->escape($nLngID)."
                WHERE PPH.FTXphDocNo = ".$this->db->escape($FTXphDocNo)."
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aDetail = $oQuery->row_array();
                $aResult = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            } else {
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Data not found.',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Checkduplicate Sale Price Adj
    //Parameters : function parameters
    //Creator : 21/02/2019 Napat(Jame)
    //Return : data
    //Return Type : Array
    public function FSnMSPACheckDuplicate($ptXphDocNo){
        $tSQL = "
            SELECT 
                COUNT(PPH.FTXphDocNo) AS counts
            FROM TCNTPdtAdjPriHD PPH 
            WHERE PPH.FTXphDocNo = ".$this->db->escape($ptXphDocNo)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->row_array();
        } else {
            return FALSE;
        }
    }

    //Functionality : Check Duplicate Date Start and Stop
    //Parameters : function parameters
    //Creator : 05/03/2019 Napat(Jame)
    //Return : data
    //Return Type : Array
    public function FSnMSPACheckDuplicatePdtPriHD($paData){

        $FDXphDStart    = $paData['FDXphDStart'];
        $FTXphTStart    = $paData['FTXphTStart'];
        $tSQL           = "
            SELECT 
                COUNT(DTTmp.FTXthDocNo) AS counts
            FROM TCNTDocDTTmp DTTmp WITH(NOLOCK)
            LEFT JOIN TCNTPdtPrice4PDT PDT WITH(NOLOCK) ON DTTmp.FTPdtCode = PDT.FTPdtCode AND DTTmp.FTPunCode = PDT.FTPunCode
            WHERE DTTmp.FDCreateOn <> ''
            AND DTTmp.FTXthDocNo    = ".$this->db->escape($paData['FTXphDocNo'])." 
            AND DTTmp.FTSessionID   = ".$this->db->escape($paData['FTSessionID'])." 
            AND DTTmp.FTXthDocKey   = ".$this->db->escape($paData['FTXthDocKey'])."
            AND PDT.FDPghDStart     = ".$this->db->escape($FDXphDStart)."
            AND PDT.FTPghTStart     = ".$this->db->escape($FTXphTStart)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->row_array();
        } else {
            return FALSE;
        }
    }

    //Functionality : Update Sale Price Adj (TCNTPdtAdjPriHD)
    //Parameters : function parameters
    //Creator : 21/02/2019 Napat(Jame)
    //Return : Array Stutus Add Update
    //Return Type : Array
    public function FSaMSPAAddUpdateMaster($paData)
    {
        try {
            // Update TCNTPdtAdjPriHD
            $this->db->where('FTXphDocNo', $paData['FTXphDocNo']);
            $this->db->update('TCNTPdtAdjPriHD', array(
                'FDXphDocDate'      => $paData['FDXphDocDate'],
                'FTXphDocTime'      => $paData['FTXphDocTime'],
                'FTXphDocType'      => $paData['FTXphDocType'],
                'FTXphStaAdj'       => $paData['FTXphStaAdj'],
                'FTPplCode'         => $paData['FTPplCode'],
                'FDXphDStart'       => $paData['FDXphDStart'],
                'FDXphDStop'        => $paData['FDXphDStop'],
                'FTXphTStart'       => $paData['FTXphTStart'],
                'FTXphTStop'        => $paData['FTXphTStop'],
                'FTXphName'         => $paData['FTXphName'],
                'FTXphRefInt'       => $paData['FTXphRefInt'],
                'FDXphRefIntDate'   => $paData['FDXphRefIntDate'],
                'FTXphPriType'      => $paData['FTXphPriType'],
                'FNXphStaDocAct'    => $paData['FNXphStaDocAct'],
                'FTXphRmk'          => $paData['FTXphRmk'],
                'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                'FTLastUpdBy'       => $paData['FTLastUpdBy'],
            ));
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Sale Price Adj Success',
                );
            } else {
                //Add TCNTPdtAdjPriHD
                $this->db->insert('TCNTPdtAdjPriHD', array(
                    'FTBchCode'         => $paData['FTBchCode'],
                    'FTXphDocNo'        => $paData['FTXphDocNo'],
                    'FTXphStaDoc'       => $paData['FTXphStaDoc'],
                    'FDXphDocDate'      => $paData['FDXphDocDate'],
                    'FTXphDocTime'      => $paData['FTXphDocTime'],
                    'FTXphDocType'      => $paData['FTXphDocType'],
                    'FTXphStaAdj'       => $paData['FTXphStaAdj'],
                    'FTPplCode'         => $paData['FTPplCode'],
                    'FDXphDStart'       => $paData['FDXphDStart'],
                    'FDXphDStop'        => $paData['FDXphDStop'],
                    'FTXphTStart'       => $paData['FTXphTStart'],
                    'FTXphTStop'        => $paData['FTXphTStop'],
                    'FTXphName'         => $paData['FTXphName'],
                    'FTXphRefInt'       => $paData['FTXphRefInt'],
                    'FDXphRefIntDate'   => $paData['FDXphRefIntDate'],
                    'FTXphPriType'      => $paData['FTXphPriType'],
                    'FNXphStaDocAct'    => $paData['FNXphStaDocAct'],
                    'FTXphRmk'          => $paData['FTXphRmk'],
                    'FDCreateOn'        => $paData['FDCreateOn'],
                    'FTCreateBy'        => $paData['FTCreateBy'],
                    'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                    'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                    'FTUsrCode'         => $paData['FTUsrCode'],
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Sale Price Adj Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Sale Price Adj',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Update Product Size (TCNMPdtSize_L)
    //Parameters : function parameters
    //Creator : 21/09/2018 Witsarut(Bell)
    //Return : Array Stutus Add Update
    //Return Type : array
    public function FSaMPSZAddUpdateLang($paDataPdtSize)
    {
        try {
            //Update Pdt Size Lang
            $this->db->where('FNLngID', $paDataPdtSize['FNLngID']);
            $this->db->where('FTPszCode', $paDataPdtSize['FTPszCode']);
            $this->db->update('TCNMPdtSize_L', array(
                'FTPszName' => $paDataPdtSize['FTPszName'],
                'FTPszRmk'  => $paDataPdtSize['FTPszRmk']
            ));
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Product Size Lang Success.',
                );
            } else {
                //Add Pdt Size Lang
                $this->db->insert('TCNMPdtSize_L', array(
                    'FTPszCode' => $paDataPdtSize['FTPszCode'],
                    'FNLngID'   => $paDataPdtSize['FNLngID'],
                    'FTPszName' => $paDataPdtSize['FTPszName'],
                    'FTPszRmk'  => $paDataPdtSize['FTPszRmk']
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Product Size Lang Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Product Size Lang.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Delete Sale Price Adj
    //Parameters : function parameters
    //Creator : 18/02/2019 Napat(Jame)
    //Return : Status Delete
    //Return Type : array
    public function FSaMSPADelAll($paData)
    {
        try {
            $this->db->trans_begin();
            $this->db->where_in('FTXphDocNo', $paData['FTXphDocNo']);
            $this->db->delete('TCNTPdtAdjPriHD');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Delete Product Price List
    //Parameters : function parameters
    //Creator : 25/02/2019 Napat(Jame)
    //Return : Status Delete
    //Return Type : array
    /* public function FSaMSPAPdtPriDelByID($paData){
        try{
            $this->db->trans_begin();
            $this->db->where_in('FTXthDocNo', $paData['FTXphDocNo']);
            $this->db->where_in('FTPdtCode', $paData['FTPdtCode']);
            $this->db->delete('TCNTDocDTTmp');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    } */

    //Functionality : Delete All Product Price List
    //Parameters : function parameters
    //Creator : 27/02/2019 Napat(Jame)
    //Return : Status Delete
    //Return Type : array
    /* public function FSaMSPAPdtPriDelAll($paData){
        try{
            $this->db->trans_begin();
            $this->db->where('FTXthDocNo', $paData['FTXphDocNo']);
            $this->db->delete('TCNTDocDTTmp');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    } */

    //Functionality : Add Product to Doc Temp
    //Parameters : function parameters
    //Creator : 27/02/2019 Napat(Jame)
    //Return : Status Add Event
    //Return Type : array
    public function FSaMSPAAddPdtDocTmp($paData)
    {
        try {
            // Add TCNTDocDTTmp
            $this->db->insert('TCNTDocDTTmp', array(
                'FNXtdSeqNo'      => $paData['FNXtdSeqNo'],
                'FTBchCode'       => $paData['FTBchCode'],
                'FTXthDocNo'      => $paData['FTXthDocNo'],
                'FTXthDocKey'     => $paData['FTXthDocKey'],
                'FTXtdShpTo'      => $paData['FTXtdShpTo'],
                'FTXtdBchTo'      => $paData['FTXtdBchTo'],
                'FTPdtCode'       => $paData['FTPdtCode'],
                'FTPunCode'       => $paData['FTPunCode'],
                'FCXtdPriceRet'   => str_replace(',', '', $paData['FCXtdPriceRet']),
                'FCXtdPriceWhs'   => $paData['FCXtdPriceWhs'],
                'FCXtdPriceNet'   => $paData['FCXtdPriceNet'],
                'FTTmpStatus'     => '1',
                'FTTmpRemark'     => '',
                'FTSessionID'     => $paData['FTSessionID'],
                'FDLastUpdOn'     => $paData['FDLastUpdOn'],
                'FDCreateOn'      => $paData['FDCreateOn'],
                'FTLastUpdBy'     => $paData['FTLastUpdBy'],
                'FTCreateBy'      => $paData['FTCreateBy']
            ));

            // echo $this->db->last_query();
            // die();
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product to tmp Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Product to tmp',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    public function FSaMSPAGet4PDT($paData)
    {
        $FTPdtCode  = $paData['FTPdtCode'];
        $FTPunCode  = $paData['FTPunCode'];
        $FTPghDocNo = $paData['FTPghDocNo'];

        $tSQL = "
            SELECT
                FCPgdPriceRet,
                FCPgdPriceWhs,
                FCPgdPriceNet
            FROM TCNTPdtPrice4PDT WITH(NOLOCK)
            WHERE FTPdtCode = ".$this->db->escape($FTPdtCode)." 
            AND FTPunCode   = ".$this->db->escape($FTPunCode)." 
            AND FTPghDocNo  = ".$this->db->escape($FTPghDocNo)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        } else {
            return FALSE;
        }
    }

    //Functionality : Delete Product Temp
    //Parameters : function parameters
    //Creator : 27/02/2019 Napat(Jame)
    //Return : Status Delete
    //Return Type : array
    public function FSaMSPAPdtTmpDelAll($paData)
    {
        try {
            $this->db->trans_begin();
            // $this->db->where('FTXthDocNo', $paData['FTXphDocNo']);
            // $this->db->where('FTPdtCode', $paData['FTPdtCode']);
            // $this->db->where('FTPunCode', $paData['FTPunCode']);
            $this->db->where('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->delete('TCNTDocDTTmp');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Function Delete all data where docno from TCNTPdtAdjPriDT
    public function FSaMSPADelAllProductDT($paData)
    {
        try {
            $this->db->trans_begin();
            $this->db->where('FTXphDocNo', $paData['FTXphDocNo']);
            $this->db->delete('TCNTPdtAdjPriDT');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.'
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.'
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Function Delete data from TCNTDocDTTmp
    public function FSaMSPADelPdtTmp($paData)
    {
        try {
            $this->db->trans_begin();
            $this->db->where('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Function Select Data From Table TCNTPdtAdjPriDT
    public function FSaMSPADTList($paData)
    {
        $FTXphDocNo  = $paData['FTXphDocNo'];
        $FTSessionID = $paData['FTSessionID'];

        $tSQL = "SELECT * FROM TCNTPdtAdjPriDT WHERE FTXphDocNo = '$FTXphDocNo'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList = $oQuery->result_array();
            $aResult = array(
                'rtCode'      => '1',
                'rtDesc'      => 'success',
                'FTXphDocNo'  => $FTXphDocNo,
                'raItems'     => $aList
            );
        } else {
            $aResult = array(
                'rtCode'      => '800',
                'rtDesc'      => 'data not found'
            );
        }
        return $aResult;
    }

    //Function Updated Product Price for Table Doc Temp
    public function FSaMSPAUpdatePriceTemp($paData)
    {
        try {
            switch ($paData['tPrice']) {
                case "FCXtdPriceRet":
                    $this->db->set('FCXtdPriceRet', $paData['tValue']);
                    break;
                case "FCXtdPriceNet":
                    $this->db->set('FCXtdPriceNet', $paData['tValue']);
                    break;
                case "FCXtdPriceWhs":
                    $this->db->set('FCXtdPriceWhs', $paData['tValue']);
                    break;
                case "All":
                    $this->db->set('FCXtdPriceRet', $paData['FCXtdPriceRet']);
                    $this->db->set('FCXtdPriceNet', $paData['FCXtdPriceNet']);
                    $this->db->set('FCXtdPriceWhs', $paData['FCXtdPriceWhs']);
                    break;
            }

            if ($paData['tColValidate'] == "[3]") { // Update กรณีกรอกราคาไม่ถูกต้อง
                $this->db->set('FTTmpStatus', '1');
                $this->db->set('FTTmpRemark', '');
            }

            // $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
            // $this->db->where('FTPdtCode', $paData['FTPdtCode']);
            // $this->db->where('FTPunCode', $paData['FTPunCode']);
            if ($paData['tSeq'] != 'N') {
                $this->db->where('FNXtdSeqNo', $paData['tSeq']);
            }

            $this->db->where('FTSessionID', $paData['FTSessionID']);

            $this->db->update('TCNTDocDTTmp');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Updated Price Temp Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Updated Price Temp',
                );
            }

            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

        //Function Updated Product Pun for Table Doc Temp
        public function FSaMSPAUpdatePunTemp($paData)
        {
            try {
    
                $this->db->set('FTPunCode', $paData['tValue']);
                $this->db->set('FCXtdPriceRet', 0);
                
    
                // $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
                // $this->db->where('FTPdtCode', $paData['FTPdtCode']);
                // $this->db->where('FTPunCode', $paData['FTPunCode']);
                if ($paData['tSeq'] != 'N') {
                    $this->db->where('FNXtdSeqNo', $paData['tSeq']);
                }
    
                $this->db->where('FTSessionID', $paData['FTSessionID']);
    
                $this->db->update('TCNTDocDTTmp');
    
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Updated Price Temp Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Updated Price Temp',
                    );
                }
    
                return $aStatus;
            } catch (Exception $Error) {
                echo $Error;
            }
        }

    //Function Get Data From Table DocTemp
    public function FSaMSPAGetDataFromTemp($paData)
    {
        try {
            $FTXphDocNo   = $paData['FTXphDocNo'];
            $FTSessionID  = $paData['FTSessionID'];

            $tSQL = "SELECT *
                     FROM TCNTDocDTTmp
                     WHERE FTXthDocNo = '$FTXphDocNo' AND FTSessionID = '$FTSessionID'";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result_array();
            } else {
                return FALSE;
            }
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Function Check Duplicate Data from Tmemp
    public function FSaMSPACheckDataTempDuplicate($paData){
        try {
            $FTXthDocNo   = $paData['FTXthDocNo'];
            $FTPdtCode    = $paData['FTPdtCode'];
            $FTPunCode    = $paData['FTPunCode'];
            $FTSessionID  = $paData['FTSessionID'];
            $FTXthDocKey  = $paData['FTXthDocKey'];
            $tSQL   = "
                SELECT *
                FROM TCNTDocDTTmp WITH(NOLOCK)
                WHERE FTXthDocNo    = ".$this->db->escape($FTXthDocNo)."
                AND FTPdtCode       = ".$this->db->escape($FTPdtCode)."
                AND FTPunCode       = ".$this->db->escape($FTPunCode)."
                AND FTSessionID     = ".$this->db->escape($FTSessionID)."
                AND FTXthDocKey     = ".$this->db->escape($FTXthDocKey)."
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->row_array();
            } else {
                return FALSE;
            }
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Function Select PdtPriDT for Insert into DocTmp
    public function FSoMSPADTtoTmp($paData){
        try {
            $FTXphDocNo     = $paData['FTXphDocNo'];
            $FTSessionID    = $paData['FTSessionID'];
            $FTXthDocKey    = $paData['FTXthDocKey'];
            $tSQL           = "
                INSERT INTO TCNTDocDTTmp (
                    FTBchCode,FTXthDocNo,FNXtdSeqNo,FTPdtCode,FTPunCode,FCXtdPriceRet,FCXtdPriceWhs,FCXtdPriceNet,FTXtdShpTo,FTXtdBchTo,
                    FTSessionID,FTXthDocKey,FTTmpStatus,FTTmpRemark,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
                )
                SELECT 
                    FTBchCode,
                    FTXphDocNo AS FTXthDocNo,
                    FNXpdSeq AS FNXtdSeqNo,
                    FTPdtCode,
                    FTPunCode,
                    FCXpdPriceRet AS FCXtdPriceRet,
                    FCXpdPriceWhs AS FCXtdPriceWhs,
                    FCXpdPriceNet AS FCXtdPriceNet,
                    FTXpdShpTo AS FTXtdShpTo,
                    FTXpdBchTo AS FTXtdBchTo,
                    '$FTSessionID' AS FTSessionID,
                    '$FTXthDocKey' AS FTXthDocKey,
                    '1' AS FTTmpStatus,
                    '' AS FTTmpRemark,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy
                FROM TCNTPdtAdjPriDT WITH(NOLOCK)
                WHERE FTXphDocNo    = '$FTXphDocNo'
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Insert DT to Doc Temp Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Insert Product to Doc Temp',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Function Select DocTmp and Insert into PdtPriDT
    public function FSoMSPATmptoDT($paData){
        $FTXphDocNo     = $paData['FTXphDocNo'];
        $FTSessionID    = $paData['FTSessionID'];
        $FTXthDocKey    = $paData['FTXthDocKey'];
        $FDLastUpdOn    = $paData['FDLastUpdOn'];
        $FDCreateOn     = $paData['FDCreateOn'];
        $FTLastUpdBy    = $paData['FTLastUpdBy'];
        $FTCreateBy     = $paData['FTCreateBy'];
        $FTBchCode      = $paData['FTBchCode'];
        $tSQL           = "
            INSERT INTO TCNTPdtAdjPriDT (
                FTBchCode,FTXphDocNo,FNXpdSeq,FTPdtCode,FTPunCode,FCXpdPriceRet,FCXpdPriceWhs,FCXpdPriceNet,
                FTXpdShpTo,FTXpdBchTo,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
            )
            SELECT 
                '$FTBchCode' AS FTBchCode,
                '$FTXphDocNo' AS FTXphDocNo,
                ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS FNXpdSeq,
                FTPdtCode,
                FTPunCode,
                FCXtdPriceRet AS FCXpdPriceRet,
                FCXtdPriceWhs AS FCXpdPriceWhs,
                FCXtdPriceNet AS FCXpdPriceNet,
                FTXtdShpTo AS FTXpdShpTo,
                FTXtdBchTo AS FTXpdBchTo,
                '$FDLastUpdOn' AS FDLastUpdOn,
                '$FDCreateOn' AS FDCreateOn,
                '$FTLastUpdBy' AS FTLastUpdBy,
                '$FTCreateBy' AS FTCreateBy
            FROM TCNTDocDTTmp WITH(NOLOCK)
            WHERE FTTmpStatus = '1' AND FTSessionID = '$FTSessionID' AND FTXthDocKey = '$FTXthDocKey'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Insert Doc Temp to DT Success'
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Insert Product to DT'
            );
        }
        return $aStatus;
    }

    public function FSaMSPAAddUpdateDocNoInDocTemp($aDataWhere){
        try {
            $this->db->set('FTXthDocNo', $aDataWhere['FTXphDocNo']);
            $this->db->where('FTSessionID', $aDataWhere['FTSessionID']);
            $this->db->where('FTXthDocKey', $aDataWhere['FTXthDocKey']);
            $this->db->update('TCNTDocDTTmp');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Function : Approve Doc
    public function FSaMSPAApprove($paDataUpdate)
    {
        try {

            $this->db->set('FTXphUsrApv', $paDataUpdate['FTXphUsrApv']);
            $this->db->set('FTXphStaPrcDoc', $paDataUpdate['FTXphStaPrcDoc']);
            $this->db->where('FTXphDocNo', $paDataUpdate['FTXthDocNo']);
            $this->db->update('TCNTPdtAdjPriHD');

            if ($this->db->affected_rows() > 0) {

                $aStatus = array(

                    'rtCode' => '1',
                    'rtDesc' => 'OK',

                );
            } else {

                $aStatus = array(

                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',

                );
            }

            return $aStatus;
        } catch (Exception $Error) {

            return $Error;
        }
    }

    //Function : Approve Doc
    public function FSaMSPAApproveStatus($paDataUpdate)
    {
        try {

            $this->db->set('FTXphUsrApv', $paDataUpdate['FTXphUsrApv']);
            $this->db->set('FTXphStaApv', $paDataUpdate['FTXphStaApv']);
            $this->db->set('FTXphStaPrcDoc', $paDataUpdate['FTXphStaPrcDoc']);
            $this->db->where('FTXphDocNo', $paDataUpdate['FTXthDocNo']);
            $this->db->update('TCNTPdtAdjPriHD');

            if ($this->db->affected_rows() > 0) {

                $aStatus = array(

                    'rtCode' => '1',
                    'rtDesc' => 'OK',

                );
            } else {

                $aStatus = array(

                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',

                );
            }

            return $aStatus;
        } catch (Exception $Error) {

            return $Error;
        }
    }

    //Function Update Status Doc Cancel
    public function FSaMSPAUpdateStaDocCancel($paDataUpdate)
    {

        try {

            $this->db->set('FTXphStaDoc', $paDataUpdate['FTXphStaDoc']);
            $this->db->where('FTXphDocNo', $paDataUpdate['FTXphDocNo']);
            $this->db->update('TCNTPdtAdjPriHD');

            if ($this->db->affected_rows() > 0) {

                $aStatus = array(

                    'rtCode' => '1',
                    'rtDesc' => 'Updated Status Document Cancel Success.',

                );
            } else {

                $aStatus = array(

                    'rtCode' => '903',
                    'rtDesc' => 'Not Update Status Document.',

                );
            }

            return $aStatus;
        } catch (Exception $Error) {

            return $Error;
        }
    }

    //Function Check Duplicate Data from Tmemp
    public function FSaMSPACheckDataSeq($paData)
    {

        try {
            $FTSessionID  = $paData['FTSessionID'];
            $FTXthDocKey  = $paData['FTXthDocKey'];

            $tSQL = "SELECT MAX(FNXtdSeqNo) AS nSeq
                     FROM TCNTDocDTTmp
                     WHERE FTSessionID = '$FTSessionID' AND FTXthDocKey = '$FTXthDocKey'";
            $oQuery = $this->db->query($tSQL);

            if ($oQuery->num_rows() > 0) {

                return $oQuery->result_array();
            } else {

                return FALSE;
            }
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality: Fine Price Last Adjust
    //Parameters:  Function Parameter
    //Creator: -
    //Last Modified : 11/06/2020   nattakit
    //Return : 
    //Return Type: Array
    public function FSaMSPADataPrice4Pdt($paData){
        try {
            $nLngID    = $paData['pnLngID'];
            $tPdtCode  = $paData['ptPdtCode'];
            $tPunCode  = $paData['ptPunCode'];
            $tPplCode  = $paData['tPplCode'];
            $tTable    = $paData['ptTable'];
            $tField    = $paData['ptField'];
            $tSQL       = "
                SELECT TOP 1 PDT_L.FTPdtName,PRI.FTPdtCode,PUN_L.FTPunName,PRI.FCPgdPriceRet,0 AS FCPgdPriceWhs,0 AS FCPgdPriceNet,
                    CONVERT(VARCHAR(10),PRI.FDPghDStart,121) AS FDPghDStart,
                    CONVERT(VARCHAR(10),PRI.FDPghDStop,121) AS FDPghDStop,
                    PRI.FTPghTStart,
                    PRI.FTPghTStop,
                    PRI.FTPghDocType
                FROM TCNTPdtPrice4PDT PRI
                LEFT JOIN TCNMPdt_L PDT_L ON PDT_L.FTPdtCode = PRI.FTPdtCode AND PDT_L.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMPdtUnit_L PUN_L ON PUN_L.FTPunCode = PRI.FTPunCode AND PUN_L.FNLngID = ".$this->db->escape($nLngID)."
                WHERE PRI.FTPdtCode = ".$this->db->escape($tPdtCode)." 
                AND PRI.FTPunCode   = ".$this->db->escape($tPunCode)."
                AND PRI.FTPplCode   = ".$this->db->escape($tPplCode)."
                AND PRI.FDPghDStart <= CONVERT(VARCHAR(10),GETDATE(),121)
                AND PRI.FTPghTStart <= CONVERT(TIME, CONCAT(DATEPART(HOUR, GETDATE()),':', DATEPART(MINUTE, GETDATE()))) 
                AND PRI.FDPghDStop > CONVERT(VARCHAR(10),GETDATE(),121)
                AND PRI.FTPghTStop > CONVERT(TIME, CONCAT(DATEPART(HOUR, GETDATE()),':', DATEPART(MINUTE, GETDATE())))   
            ";
            if ($tField != "") {
                switch ($tTable) {
                    case "TCNTPdtPrice4AGG":
                        $tSQL   .= " AND PRI.FTAggCode  = ".$this->db->escape($tField)." ";
                        break;
                    case "TCNTPdtPrice4ZNE":
                        $tSQL   .= " AND PRI.FTPghZneTo = ".$this->db->escape($tField)." ";
                        break;
                    case "TCNTPdtPrice4BCH":
                        $tSQL   .= " AND PRI.FTPghBchTo = ".$this->db->escape($tField)." ";
                        break;
                }
            }
            $tSQL   .= " ORDER BY PRI.FDCreateOn DESC ,PRI.FDPghDStart DESC , PRI.FTPghTStart DESC";
            $oQuery  = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aReturn    = array(
                    'tSQL'      => $tSQL,
                    'aItems'    => $oQuery->result_array(),
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Found Data'
                );
            } else {
                $aReturn    = array(
                    'tSQL'      => $tSQL,
                    'aItems'    => NULL,
                    'nStaEvent' => 99,
                    'tStaMessg' => 'Not Found'
                );
            }
            return $aReturn;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality: Get column for showing in grid table
    //Parameters:  Function Parameter
    //Creator: 26/02/2018 kitpipat P'รันต์
    //Last Modified : -
    //Return : 
    //Return Type: Array
    function FCNaDCLGetColumnShow($ptTable = ''){
        $tLangActive    = $_SESSION['tLangEdit'];
        $ci             = &get_instance();
        $ci->load->database();
        $tSQL   = "
            SELECT SDT.* ,SDTL.FTShwNameUsr 
            FROM  TSysShwDT SDT WITH(NOLOCK) 
            LEFT JOIN  TSysShwDT_L SDTL ON SDT.FTShwTblDT = SDTL.FTShwTblDT 
            AND SDT.FTShwFedShw = SDTL.FTShwFedShw  
            AND SDTL.FNLngID = ".$this->db->escape($tLangActive)."
            WHERE SDT.FTShwTblDT = ".$this->db->escape($ptTable)."
            AND SDT.FTShwFedStaUsed     = 1
            AND SDT.FTShwFedSetByUsr    = 1
            AND SDT.FTShwFedShw != 'FTBchName'
            ORDER BY SDT.FTShwTblDT , SDT.FNShwSeq";
        $oQuery = $ci->db->query($tSQL);
        $aDataResult = $oQuery->result();
        return $aDataResult;
    }






}
