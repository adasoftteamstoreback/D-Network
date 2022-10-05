<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mPromotionStep4CstCondition extends CI_Model
{
    /**
     * Functionality : Get Channel in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Data List PdtPmtHDChn
     * Return Type : Array
     */
    public function FSaMGetPdtPmtHDCstInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        // $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FTClvName ASC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        TMP.FTBchCode,
                        TMP.FTPmhDocNo,
                        TMP.FTClvCode,
                        CstL.FTClvName,
                        TMP.FTPmhStaType,
                        TMP.FTSessionID
                    FROM TCNTPdtPmtHDCstLev_Tmp TMP WITH(NOLOCK)
                    INNER JOIN  TCNMCstLev_L CstL ON  CstL.FTClvCode = TMP.FTClvCode
                    WHERE TMP.FTSessionID = '$tUserSessionID'
        ";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $nFoundRow = $this->FSnMTFWGetPdtPmtHDCstPriInTmpPageAll($paParams);
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
     * Functionality : Count PdtPmtHDCst in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Count PdtPmtHDCst
     * Return Type : Number
     */
    public function FSnMTFWGetPdtPmtHDCstPriInTmpPageAll($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT 
                FTSessionID
            FROM TCNTPdtPmtHDCstLev_Tmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID'
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    /**
     * Functionality : Insert PdtPmtHDCst to Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPdtPmtHDCstToTemp($paParams = [])
    {
       
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];

        $this->db->set('FTBchCode', $tBchCodeLogin);
        $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->set('FTClvCode', $paParams['tCstCode']);
        // $this->db->set('FTCstName', $paParams['tCstName']);
        $this->db->set('FTPmhStaType', '1'); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น

        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->insert('TCNTPdtPmtHDCstLev_Tmp');

        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert TCNTPdtPmtHDCstLev_Tmp Fail.',
        ];

        if ($this->db->affected_rows() > 0) {
            $aStatus['rtCode'] = '1';
            $aStatus['rtDesc'] = 'Insert TCNTPdtPmtHDCstLev_Tmp Success';
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
    public function FSbUpdateCstInTmpByKey($paParams = [])
    {
        $this->db->set('FTPmhStaType', $paParams['tPmhStaType']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTBchCode', $paParams['tBchCode']);
        $this->db->where('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->where('FTClvCode', $paParams['tCstCode']);
        $this->db->update('TCNTPdtPmtHDCstLev_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete PdtPmtHDCst in Temp by Primary Key
     * Parameters : -
     * Creator :  17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeletePdtPmtHDCstInTmpByKey($paParams = [])
    {
        $this->db->where('FTBchCode', $paParams['tBchCode']);
        $this->db->where('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->where('FTClvCode', $paParams['tCstCode']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->delete('TCNTPdtPmtHDCstLev_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Clear PdtPmtHDCst in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbClearPdtPmtHDCstPriInTmp($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->delete('TCNTPdtPmtHDCstLev_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }
}
