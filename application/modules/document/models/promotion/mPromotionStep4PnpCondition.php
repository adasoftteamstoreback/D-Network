<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mPromotionStep4PnpCondition extends CI_Model
{
    /**
     * Functionality : Get Channel in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Data List PdtPmtHDChn
     * Return Type : Array
     */
    public function FSaMGetPdtPmtHDPnpInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        // $nLngID = $paParams['FNLngID'];
        
        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FTPmhName ASC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        TMP.FTBchCode,
                        TMP.FTPmhDocNo,
                        PnpL.FTPmhName,
                        TMP.FTPmhStaType,
                        TMP.FTSessionID,
                        TMP.FTPmhDocRef
                    FROM TCNTPdtPmtHDSeq_Tmp TMP WITH(NOLOCK)
                    INNER JOIN  TCNTPdtPmtHD_L PnpL ON  PnpL.FTPmhDocNo = TMP.FTPmhDocRef
                    WHERE TMP.FTSessionID = '$tUserSessionID'
        ";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";



        
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $nFoundRow = $this->FSnMTFWGetPdtPmtHDPnpPriInTmpPageAll($paParams);
            $nPageAll = ceil($nFoundRow / $paParams['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paParams['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            // No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paParams['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : Count PdtPmtHDPnp in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Count PdtPmtHDPnp
     * Return Type : Number
     */
    public function FSnMTFWGetPdtPmtHDPnpPriInTmpPageAll($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT 
                FTSessionID
            FROM TCNTPdtPmtHDSeq_Tmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID'
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    /**
     * Functionality : Insert PdtPmtHDPnp to Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPdtPmtHDPnpToTemp($paParams = [])
    {
       
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];

        $this->db->set('FTBchCode', $tBchCodeLogin);
        $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->set('FTPmhDocRef', $paParams['tPnpCode']);
        // $this->db->set('FTPnpName', $paParams['tPnpName']);
        $this->db->set('FTPmhStaType', '2'); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น

        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->insert('TCNTPdtPmtHDSeq_Tmp');

        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert TCNTPdtPmtHDSeq_Tmp Fail.',
        ];

        if ($this->db->affected_rows() > 0) {
            $aStatus['rtCode'] = '1';
            $aStatus['rtDesc'] = 'Insert TCNTPdtPmtHDSeq_Tmp Success';
        }
        return $aStatus;
    }

    /**
     * Functionality : Update PmtCB Value in Temp by Primary Key
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status Update
     * Return Type : Boolean
     */
    public function FSbUpdatePnpInTmpByKey($paParams = [])
    {
        $this->db->set('FTPmhStaType', $paParams['tPmhStaType']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTBchCode', $paParams['tBchCode']);
        $this->db->where('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->where('FTPmhDocRef', $paParams['tPnpCode']);
        // $this->db->where('FTClvCode', $paParams['tPnpCode']);
        $this->db->update('TCNTPdtPmtHDSeq_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete PdtPmtHDPnp in Temp by Primary Key
     * Parameters : -
     * Creator :  17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeletePdtPmtHDPnpInTmpByKey($paParams = [])
    {
        $this->db->where('FTBchCode', $paParams['tBchCode']);
        $this->db->where('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->where('FTPmhDocRef', $paParams['tPnpCode']);
        // $this->db->where('FTClvCode', $paParams['tPnpCode']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->delete('TCNTPdtPmtHDSeq_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Clear PdtPmtHDPnp in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbClearPdtPmtHDPnpPriInTmp($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->delete('TCNTPdtPmtHDSeq_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }
}
