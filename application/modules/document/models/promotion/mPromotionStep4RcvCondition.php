<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mPromotionStep4RcvCondition extends CI_Model
{
    /**
     * Functionality : Get Pay Type in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Data List PdtPmtHDChn
     * Return Type : Array
     */
    public function FSaMGetPdtPmtHDRcvInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        // $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FTRcvName ASC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        TMP.FTBchCode,
                        TMP.FTPmhDocNo,
                        TMP.FTRcvCode,
                        RCVL.FTRcvName,
                        TMP.FTPmhStaType,
                        TMP.FTSessionID
                    FROM TCNTPdtPmtHDPay_Tmp TMP WITH(NOLOCK)
                    INNER JOIN  TFNMRcv_L RCVL ON  RCVL.FTRcvCode = TMP.FTRcvCode
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
     * Functionality : Count PdtPmtHDRcv in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Count PdtPmtHDRcv
     * Return Type : Number
     */
    public function FSnMTFWGetPdtPmtHDCstPriInTmpPageAll($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT 
                FTSessionID
            FROM TCNTPdtPmtHDPay_Tmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID'
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    /**
     * Functionality : Insert PdtPmtHDRcv to Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPdtPmtHDRcvToTemp($paParams = [])
    {
       
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];

        $this->db->set('FTBchCode', $tBchCodeLogin);
        $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->set('FTRcvCode', $paParams['tRcvCode']);
        // $this->db->set('FTRcvName', $paParams['tRcvName']);
        $this->db->set('FTPmhStaType', '1'); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น

        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->insert('TCNTPdtPmtHDPay_Tmp');

        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert TCNTPdtPmtHDPay_Tmp Fail.',
        ];

        if ($this->db->affected_rows() > 0) {
            $aStatus['rtCode'] = '1';
            $aStatus['rtDesc'] = 'Insert TCNTPdtPmtHDPay_Tmp Success';
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
    public function FSbUpdateRcvInTmpByKey($paParams = [])
    {
        $this->db->set('FTPmhStaType', $paParams['tPmhStaType']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTBchCode', $paParams['tBchCode']);
        $this->db->where('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->where('FTRcvCode', $paParams['tRcvCode']);
        $this->db->update('TCNTPdtPmtHDPay_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete PdtPmtHDRcv in Temp by Primary Key
     * Parameters : -
     * Creator :  17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeletePdtPmtHDRcvInTmpByKey($paParams = [])
    {
        $this->db->where('FTBchCode', $paParams['tBchCode']);
        $this->db->where('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->where('FTRcvCode', $paParams['tRcvCode']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->delete('TCNTPdtPmtHDPay_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Clear PdtPmtHDRcv in Temp
     * Parameters : -
     * Creator : 17/09/2021 Worakorn
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbClearPdtPmtHDCstPriInTmp($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->delete('TCNTPdtPmtHDPay_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }
}
