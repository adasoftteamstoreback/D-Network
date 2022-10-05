<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dailyworkorder_model extends CI_Model {

    // Functionality : Get Bay Datail
    // Parameters : Ajax and Function Parameter
    // Creator : 30/09/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSaMDWOGetBayDetail($paDataWhere)
    {
        $tWhereStatusCondition  = "";
        $tWhereDepartCode       = "";
        $tWhereCondition        = "";
        $tWhereConditionBay     = "";
        $dDate          = $paDataWhere['FDXshDocDate'];
        $tBchCode       = $paDataWhere['FTBchCode'];
        $nStatus        = $paDataWhere['FTXshStaDoc'];
        $tDepartCode    = $paDataWhere['FTDptCode'];

        if($dDate != ''){
            $tWhereCondition .=  " AND CONVERT(VARCHAR(10),HD.FDXshDocDate,121) = '$dDate'";
        }

        if($tBchCode != ''){
            $tWhereCondition    .= " AND HD.FTBchCode = '$tBchCode'";
            $tWhereConditionBay .= " AND BAYL.FTBchCode = '$tBchCode'";
        }

        if($tDepartCode != ''){
            $tWhereCondition    .=  " AND USR.FTDptCode = '$tDepartCode'";
            $tWhereDepartCode   .=  " AND USR.FTDptCode = '$tDepartCode'";
        }

        if($nStatus != '0'){
            $tWhereStatusCondition .=  " AND JOB.FTXshStaDoc = '$nStatus'";
        }

        $tLang = $this->session->userdata("tLangEdit");
        try {
            $tSQLAllBay = "SELECT 
                            BAYL.FTSpsCode , 
                            BAYL.FtSpsName 
                        FROM TSVMPos_L BAYL
                        WHERE BAYL.FNLngID = '$tLang' $tWhereConditionBay ";
                        
            $oQueryAllBay = $this->db->query($tSQLAllBay);
            $aAllBay = $oQueryAllBay->result_array();

            $tSQLBayDetail = "SELECT JOB.* FROM (
                SELECT DISTINCT HD.FTAgnCode, 
                       HD.FTBchCode, 
                       HD.FTCstCode,
                       HD.FTXshDocNo, 
                       HD.FTXshToPos,
                    HCS.FTCarCode,
                    CAR.FTCarRegNo,
                    CST.FTCstName,
                    HD.FDXshDocDate,
                    CASE WHEN  ISNULL(HD.FTXshStaDoc,'') = 1 AND ISNULL(HD.FTXshStaApv,'') = ''
                    THEN 1
                    WHEN  ISNULL(HD.FTXshStaDoc,'') = 1 AND ISNULL(HD.FTXshStaApv,'') = 1 AND ISNULL(HD.FTXshStaClosed,'') = ''
                    THEN 2
                    WHEN  ISNULL(HD.FTXshStaDoc,'') = 1 AND ISNULL(HD.FTXshStaApv,'') = 1 AND ISNULL(HD.FTXshStaClosed,'') = 1
                    THEN 3
                    ELSE 4 END AS FTXshStaDoc
                FROM TSVTJob2OrdHD HD
                     LEFT JOIN
                (
                    SELECT USR.FTUsrCode, 
                           UBY.FTSpsCode,
                           USR.FTDptCode
                    FROM TSVMPosUser UBY
                         LEFT JOIN TCNMUser USR ON UBY.FTUsrCode = USR.FTUsrCode
                    WHERE 1=1
                    $tWhereDepartCode
                ) USR ON HD.FTXshToPos = USR.FTSpsCode
                     LEFT JOIN TSVTJob2OrdHDCst HCS ON HCS.FTAgnCode = HD.FTAgnCode
                                                    AND HCS.FTBchCode = HD.FTBchCode
                                                    AND HCS.FTXshDocNo = HD.FTXshDocNo
                  LEFT JOIN TSVMCar CAR ON HCS.FTCarCode = CAR.FTCarCode
                  LEFT JOIN TCNMCst_L CST ON HD.FTCstCode = CST.FTCstCode AND CST.FNLngID = $tLang
                WHERE 1=1
                $tWhereCondition
                ) JOB WHERE JOB.FTXshStaDoc != '4'
                $tWhereStatusCondition ";

            $oQueryBayDetail = $this->db->query($tSQLBayDetail);

            $aBayDetail = $oQueryBayDetail->result_array();

            $aResult = array(
                'raItemsBay' => $aAllBay,
                'raItemsBayDetail' => $aBayDetail,
            );
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }

    }
}
