<?php
defined('BASEPATH') or exit('No direct script access allowed');

class logmonitor_model extends CI_Model
{

    //Functionality : list Data SettingConperiod
    //Creator :  19/09/2019 Witsarut (Bell)
    public function FSaMLOGList($paData)
    {
        try {
            // print_r($paData); die();
            $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
            $nLngID         = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $tSesAgnCode    = $this->session->userdata("tSesUsrAgnCode");

            $tSearchDocDateFrom =  $tSearchList['tSearchDateFrom'];
            $tSearchDocDateTo =  $tSearchList['tSearchDateTo'];

            $this->session->set_userdata('tDataFilter', $paData['tSearchAll']);

            $tSQL           = "SELECT c.* FROM(
                                    SELECT  ROW_NUMBER() OVER(ORDER BY FDLogDate DESC) AS rtRowID,* FROM
                                        (SELECT 
                                        LOGC.*
                
                                        FROM TCNSLogClient LOGC   WITH(NOLOCK)
                                        WHERE LOGC.FTLogStaSync != '2'";

            //input ค้นหา
            if (isset($tSearchList['tSearchAll']) && !empty($tSearchList['tSearchAll'])) {
                $tSQL .= " AND (LOGC.FNLogCode LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR LOGC.FTAgnCode  LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR LOGC.FTBchCode  LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR LOGC.FTAppCode  LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%')";
            }


            //ค้นหาตัวแทนขายแบบ Browse
            if (isset($tSearchList['tSearchAgn']) && !empty($tSearchList['tSearchAgn'])) {
                $tSQL .= " AND LOGC.FTAgnCode = " . $this->db->escape($tSearchList['tSearchAgn']) . "";
            }

            //ค้นหาสาขาแบบ Browse
            if (isset($tSearchList['tSearchBch']) && !empty($tSearchList['tSearchBch'])) {
                // $tSQL .= " AND LOGC.FTBchCode = " . $this->db->escape($tSearchList['tSearchBch']) . "";
                $tDataWhereBch =   str_replace(",", "','", $tSearchList['tSearchBch']);
                $tSQL .= " AND LOGC.FTBchCode IN ('" . $tDataWhereBch . "')";
            }

            //ค้นหาเครื่องจุดขายแบบ Browse
            if (isset($tSearchList['tSearchPos']) && !empty($tSearchList['tSearchPos'])) {
                // $tSQL .= " AND LOGC.FTPosCode = " . $this->db->escape($tSearchList['tSearchPos']) . "";
                $tDataWherePos =   str_replace(",", "','", $tSearchList['tSearchPos']);
                $tSQL .= " AND LOGC.FTPosCode IN ('" . $tDataWherePos . "')";
            }

            //ค้นหาAppแบบ Browse
            if (isset($tSearchList['tSearchApp']) && !empty($tSearchList['tSearchApp'])) {
                $tSQL .= " AND LOGC.FTAppCode = " . $this->db->escape($tSearchList['tSearchApp']) . "";
            }

            //ค้นหารอบการขายแบบ Browse
            if (isset($tSearchList['tSearchShift']) && !empty($tSearchList['tSearchShift'])) {
                // $tSQL .= " AND LOGC.FTShfCode = " . $this->db->escape($tSearchList['tSearchShift']) . "";
                $tDataWhereShf =   str_replace(",", "','", $tSearchList['tSearchShift']);
                $tSQL .= " AND LOGC.FTShfCode IN ('" . $tDataWhereShf . "')";
            }

            //ค้นหาเมนูแบบ Browse
            if (isset($tSearchList['tSearchMenu']) && !empty($tSearchList['tSearchMenu'])) {
                // $tSQL .= " AND LOGC.FTMnuCodeRef = " . $this->db->escape($tSearchList['tSearchMenu']) . "";
                $tDataWhereMenu =   str_replace(",", "','", $tSearchList['tSearchMenu']);
                $tSQL .= " AND LOGC.FTMnuCodeRef IN ('" . $tDataWhereMenu . "')";
            }

            //ค้นหาประเภทแบบ Browse
            if (isset($tSearchList['tSearchType']) && !empty($tSearchList['tSearchType'])) {
                $tSQL .= " AND LOGC.FTLogType = " . $this->db->escape($tSearchList['tSearchType']) . "";
            }

            //ค้นหาประเภทแบบ Browse
            if (isset($tSearchList['tSearchLevel']) && !empty($tSearchList['tSearchLevel'])) {
                $tSQL .= " AND LOGC.FTLogLevel = " . $this->db->escape($tSearchList['tSearchLevel']) . "";
            }

            //ค้นหาผู้ใช้แบบ Browse
            if (isset($tSearchList['tSearchUsr']) && !empty($tSearchList['tSearchUsr'])) {
                // $tSQL .= " AND LOGC.FTUsrCode = " . $this->db->escape($tSearchList['tSearchUsr']) . "";
                $tDataWhereUsr =   str_replace(",", "','", $tSearchList['tSearchUsr']);
                $tSQL .= " AND LOGC.FTUsrCode IN ('" . $tDataWhereUsr . "')";
            }

            // ค้นหาจากวันที่ - ถึงวันที่
            if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
                $tSQL .= " AND ((LOGC.FDLogDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (LOGC.FDLogDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
            }

            $tSQL .= ") Base) AS c WHERE c.rtRowID > " . $this->db->escape($aRowLen[0]) . " AND c.rtRowID <= " . $this->db->escape($aRowLen[1]) . "";
            // print_r($tSQL); die();
            $oQuery = $this->db->query($tSQL);

            if ($oQuery->num_rows() > 0) {
                $aList = $oQuery->result_array();
                $oFoundRow = $this->FSoMBACGetPageAll($tSearchList, $nLngID);
                $nFoundRow = $oFoundRow[0]->counts;
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
                    'raItems'       => array(),
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
            $tSearchDocDateFrom =  $ptSearchList['tSearchDateFrom'];
            $tSearchDocDateTo =  $ptSearchList['tSearchDateTo'];

            $tSQL = "SELECT COUNT (LOGC.FNLogCode) AS counts
                    FROM TCNSLogClient LOGC   WITH(NOLOCK)
                    WHERE LOGC.FNLogCode != '2'";

            // //input ค้นหา
            if (isset($tSearchList['tSearchAll']) && !empty($tSearchList['tSearchAll'])) {
                $tSQL .= " AND (LOGC.FNLogCode LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR LOGC.FTAgnCode  LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR LOGC.FTBchCode  LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%'";
                $tSQL .= " OR LOGC.FTAppCode  LIKE '%" . $this->db->escape_like_str($tSearchList['tSearchAll']) . "%')";
            }


            //ค้นหาตัวแทนขายแบบ Browse
            if (isset($tSearchList['tSearchAgn']) && !empty($tSearchList['tSearchAgn'])) {
                $tSQL .= " AND LOGC.FTAgnCode = " . $this->db->escape($tSearchList['tSearchAgn']) . "";
            }

            //ค้นหาสาขาแบบ Browse
            if (isset($tSearchList['tSearchBch']) && !empty($tSearchList['tSearchBch'])) {
                // $tSQL .= " AND LOGC.FTBchCode = " . $this->db->escape($tSearchList['tSearchBch']) . "";
                $tDataWhereBch =   str_replace(",", "','", $tSearchList['tSearchBch']);
                $tSQL .= " AND D.FTBchCode IN ('" . $tDataWhereBch . "')";
            }

            //ค้นหาเครื่องจุดขายแบบ Browse
            if (isset($tSearchList['tSearchPos']) && !empty($tSearchList['tSearchPos'])) {
                // $tSQL .= " AND LOGC.FTPosCode = " . $this->db->escape($tSearchList['tSearchPos']) . "";
                $tDataWherePos =   str_replace(",", "','", $tSearchList['tSearchPos']);
                $tSQL .= " AND D.FTPosCode IN ('" . $tDataWherePos . "')";
            }

            //ค้นหาAppแบบ Browse
            if (isset($tSearchList['tSearchApp']) && !empty($tSearchList['tSearchApp'])) {
                $tSQL .= " AND LOGC.FTAppCode = " . $this->db->escape($tSearchList['tSearchApp']) . "";
            }

            //ค้นหารอบการขายแบบ Browse
            if (isset($tSearchList['tSearchShift']) && !empty($tSearchList['tSearchShift'])) {
                // $tSQL .= " AND LOGC.FTShfCode = " . $this->db->escape($tSearchList['tSearchShift']) . "";
                $tDataWhereShf =   str_replace(",", "','", $tSearchList['tSearchShift']);
                $tSQL .= " AND D.FTShfCode IN ('" . $tDataWhereShf . "')";
            }

            //ค้นหาเมนูแบบ Browse
            if (isset($tSearchList['tSearchMenu']) && !empty($tSearchList['tSearchMenu'])) {
                // $tSQL .= " AND LOGC.FTMnuCodeRef = " . $this->db->escape($tSearchList['tSearchMenu']) . "";
                $tDataWhereMenu =   str_replace(",", "','", $tSearchList['tSearchMenu']);
                $tSQL .= " AND D.FTMnuCodeRef IN ('" . $tDataWhereMenu . "')";
            }

            //ค้นหาประเภทแบบ Browse
            if (isset($tSearchList['tSearchType']) && !empty($tSearchList['tSearchType'])) {
                $tSQL .= " AND LOGC.FTLogType = " . $this->db->escape($tSearchList['tSearchType']) . "";
            }

            //ค้นหาประเภทแบบ Browse
            if (isset($tSearchList['tSearchLevel']) && !empty($tSearchList['tSearchLevel'])) {
                $tSQL .= " AND LOGC.FTLogLevel = " . $this->db->escape($tSearchList['tSearchLevel']) . "";
            }

            //ค้นหาผู้ใช้แบบ Browse
            if (isset($tSearchList['tSearchUsr']) && !empty($tSearchList['tSearchUsr'])) {
                // $tSQL .= " AND LOGC.FTUsrCode = " . $this->db->escape($tSearchList['tSearchUsr']) . "";
                $tDataWhereUsr =   str_replace(",", "','", $tSearchList['tSearchUsr']);
                $tSQL .= " AND D.FTUsrCode IN ('" . $tDataWhereUsr . "')";
            }

            // ค้นหาจากวันที่ - ถึงวันที่
            if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
                $tSQL .= " AND ((LOGC.FDLogDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (LOGC.FDLogDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
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

    public function FSaMLOGGetDataLogClient($aDataCode)
    {
        $tSQL = "SELECT * FROM TCNSLogClient LOGC WITH(NOLOCK)
        WHERE LOGC.FNLogCode IN ('$aDataCode') ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aList = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $aList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'raItems'       => array(),
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        return $aResult;
    }

    public function FSaMLOGListAdaLog($aData)
    {
        $aSearch = $aData['tSearchAll'];
        $tSearchDocDateFrom =  $aSearch['tSearchDateFrom'];
        $tSearchDocDateTo =  $aSearch['tSearchDateTo'];
        // print_r($aSearch);
        //อัพเดทข้อมูลก่อนถ้ามี	
        if ($aSearch['tSearchGroupMonitor'] == 1) {
            $tSqlUpdate     = "SELECT TOP 100 *
			FROM (SELECT  * FROM TCNSLogError
			UNION ALL
			SELECT * FROM TCNSLogEvent
			UNION ALL
			SELECT  * FROM TCNSLogInfo
			UNION ALL
			SELECT  * FROM TCNSLogWarning) AS D
			WHERE 1 =1";

            $tOrderBY = "ORDER BY D.FTUsrCode ASC ,D.FDLogDate ASC";
        } else if ($aSearch['tSearchGroupMonitor'] == 2) {
            $tSqlUpdate     = "SELECT TOP 100 *
			FROM (SELECT  * FROM TCNSLogError
			UNION ALL
			SELECT  * FROM TCNSLogEvent
			UNION ALL
			SELECT  * FROM TCNSLogInfo
			UNION ALL
			SELECT  * FROM TCNSLogWarning) AS D
			WHERE 1 =1";

            $tOrderBY = "ORDER BY D.FTAppCode ASC ,D.FDLogDate ASC";
        } else if ($aSearch['tSearchGroupMonitor'] == 3) {
            $tSqlUpdate     = "SELECT TOP 100 *
			FROM (SELECT * FROM TCNSLogError
			UNION ALL
			SELECT  * FROM TCNSLogEvent
			UNION ALL
			SELECT  * FROM TCNSLogInfo
			UNION ALL
			SELECT  * FROM TCNSLogWarning) AS D
			WHERE 1 =1";

            $tOrderBY = "ORDER BY D.FDLogDate ASC";
        } else {
            $tSqlUpdate     = "SELECT TOP 100 *
			FROM (SELECT * FROM TCNSLogError
			UNION ALL
			SELECT  * FROM TCNSLogEvent
			UNION ALL
			SELECT  * FROM TCNSLogInfo
			UNION ALL
			SELECT * FROM TCNSLogWarning) AS D
			WHERE 1 =1";

            $tOrderBY = "ORDER BY D.FDLogDate ASC";
        }

        //ค้นหาตัวแทนขายแบบ Browse
        if (isset($aSearch['tSearchAgn']) && !empty($aSearch['tSearchAgn'])) {
            $tSqlUpdate .= " AND D.FTAgnCode = '" . $aSearch['tSearchAgn'] . "'";
        }

        //ค้นหาสาขาแบบ Browse
        if (isset($aSearch['tSearchBch']) && !empty($aSearch['tSearchBch'])) {
            // $tSqlUpdate .= " AND D.FTBchCode = '" . $aSearch['tSearchBch'] . "'";
            $tDataWhereBch =   str_replace(",", "','", $aSearch['tSearchBch']);
            $tSqlUpdate .= " AND D.FTBchCode IN ('" . $tDataWhereBch . "')";
        }

        //ค้นหาเครื่องจุดขายแบบ Browse
        if (isset($aSearch['tSearchPos']) && !empty($aSearch['tSearchPos'])) {
            // $tSqlUpdate .= " AND D.FTPosCode = '" . $aSearch['tSearchPos'] . "'";
            $tDataWherePos =   str_replace(",", "','", $aSearch['tSearchPos']);
            $tSqlUpdate .= " AND D.FTPosCode IN ('" . $tDataWherePos . "')";
        }

        //ค้นหาAppแบบ Browse
        if (isset($aSearch['tSearchApp']) && !empty($aSearch['tSearchApp'])) {
            $tSqlUpdate .= " AND D.FTAppCode = '" . $aSearch['tSearchApp'] . "'";
        }

        //ค้นหารอบการขายแบบ Browse
        if (isset($aSearch['tSearchShift']) && !empty($aSearch['tSearchShift'])) {
            // $tSqlUpdate .= " AND D.FTShfCode = '" . $aSearch['tSearchShift'] . "'";
            $tDataWhereShf =   str_replace(",", "','", $aSearch['tSearchShift']);
            $tSqlUpdate .= " AND D.FTShfCode IN ('" . $tDataWhereShf . "')";
        }

        //ค้นหาเมนูแบบ Browse
        if (isset($aSearch['tSearchMenu']) && !empty($aSearch['tSearchMenu'])) {
            // $tSqlUpdate .= " AND D.FTMnuRefCode = '" . $aSearch['tSearchMenu'] . "'";
            $tDataWhereMenu =   str_replace(",", "','", $aSearch['tSearchMenu']);
            $tSqlUpdate .= " AND D.FTMnuCodeRef IN ('" . $tDataWhereMenu . "')";
        }

        //ค้นหาประเภทแบบ Browse
        if (isset($aSearch['tSearchType']) && !empty($aSearch['tSearchType'])) {
            $tSqlUpdate .= " AND D.FTLogType = '" . $aSearch['tSearchType'] . "'";
        }

        //ค้นหาประเภทแบบ Browse
        if (isset($aSearch['tSearchLevel']) && !empty($aSearch['tSearchLevel'])) {
            $tSqlUpdate .= " AND D.FTLogLevel = '" . $aSearch['tSearchLevel'] . "'";
        }

        //ค้นหาผู้ใช้แบบ Browse
        if (isset($aSearch['tSearchUsr']) && !empty($aSearch['tSearchUsr'])) {
            // $tSqlUpdate .= " AND D.FTUsrCode = '" . $aSearch['tSearchUsr'] . "'";
            $tDataWhereUsr =   str_replace(",", "','", $aSearch['tSearchUsr']);
            $tSqlUpdate .= " AND D.FTUsrCode IN ('" . $tDataWhereUsr . "')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSqlUpdate .= " AND ((D.FDLogDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (D.FDLogDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        $tSqlUpdate .= " $tOrderBY";

        $oQuery = $this->db->query($tSqlUpdate);

        if ($oQuery->num_rows() > 0) {
            $aList = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $aList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'raItems'       => array(),
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        return $aResult;
    }
}
