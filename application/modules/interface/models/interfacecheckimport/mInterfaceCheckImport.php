<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mInterfaceCheckImport extends CI_Model {
    // ดึงข้อมูล List Import
    public function FSaMGetImportList(){
            try{
                    $tSQL = "  SELECT API.FTApiCode, API_L.FTApiName, API.FTApiGrpPrc
                               FROM TCNMTxnAPI API WITH(NOLOCK)
                               LEFT JOIN TCNMTxnAPI_L API_L ON API.FTApiCode = API_L.FTApiCode AND API_L.FNLngID = 1
                               WHERE 1=1 AND API.FTApiTxnType = '1'
                               ORDER BY API.FTApiCode ASC";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                        $aList = $oQuery->result_array();
                        $aResult = array(
                            'aItems'       => $aList,
                            'tCode'        => '1',
                            'tDesc'        => 'success',
                        );
                    }else{
                        //No Data
                        $aResult = array(
                            'tCode' => '800',
                            'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

	public function FSaMcHKCostCenterToProfiCenter($paData){ //ดึงข้อมูลมาแสดงหน้า List CenterToProfiCenter
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = "SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    CPC.CostCenter,
                                    CPC.ProfitCenter,
                                    CPC.FDCreateOn
                                FROM [SAP_CostCenterToProfiCenter] CPC
                                WHERE 1=1 ";
                    if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                        $tSQL .= " AND ((CPC.CostCenter COLLATE THAI_BIN LIKE '%$tSearchList%')OR (CPC.ProfitCenter COLLATE THAI_BIN LIKE '%$tSearchList%'))";
                    }
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKCostCenterToProfiCenterAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKCostCenterToProfiCenterAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List CenterToProfiCenter
            try{
                    $tSQL = "SELECT COUNT (CPC.CostCenter) AS counts
                             FROM [SAP_CostCenterToProfiCenter] CPC
                             WHERE 1=1 ";
                    if(isset($ptSearchList) && !empty($ptSearchList) || $ptSearchList == 0){
                        $tSQL .= " AND ((CPC.CostCenter COLLATE THAI_BIN LIKE '%$ptSearchList%')OR (CPC.ProfitCenter LIKE '%$ptSearchList%') or (CPC.FDCreateOn LIKE '%$ptSearchList%') )";
                    }
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKInterBA($paData){ //ดึงข้อมูลมาแสดงหน้า List InterBA
            $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = "SELECT c.* FROM(
                               SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC) AS rtRowID,* FROM
                               (SELECT
                                    ITB.InterBaType,
                                    ITB.BusinessArea,
                                    ITB.Partner_BusinessArea,
                                    ITB.InterBaID,
                                    ITB.FDCreateOn
                                FROM [InterBA] ITB
                                WHERE 1=1 ";
                    if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                        $tSQL .= " AND ((ITB.InterBaType COLLATE THAI_BIN LIKE '%$tSearchList%') OR (ITB.BusinessArea LIKE '%$tSearchList%')
                        OR (ITB.Partner_BusinessArea LIKE '%$tSearchList%') OR (ITB.InterBaID LIKE '%$tSearchList%'))";
                    }
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKInterBAAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                            );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                            );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKInterBAAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List InterBA
            try{
                    $tSQL = "SELECT COUNT (ITB.InterBaType) AS counts
                            FROM [InterBA] ITB
                            WHERE 1=1 ";
                    if(isset($ptSearchList) && !empty($ptSearchList) || $ptSearchList == 0){
                        $tSQL .= " AND ((ITB.InterBaType COLLATE THAI_BIN LIKE '%$ptSearchList%') OR (ITB.BusinessArea LIKE '%$ptSearchList%')
                        OR (ITB.Partner_BusinessArea LIKE '%$ptSearchList%') OR (ITB.InterBaID LIKE '%$ptSearchList%'))";
                    }
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
            $dNewdate = DateTime::createFromFormat($format, $date);
            // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
            return $dNewdate && $dNewdate->format($format) === $date;
    }

    public function FSaMcHKSaleStaff($paData){ //ดึงข้อมูลมาแสดงหน้า List SaleStaff
        $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tSearchList    = trim($paData['tSearchAll']);
        $tNewdate  = "";
        if ($this->validateDate($tSearchList, 'Y/m/d') == True){
            $tSearch = date_format(date_create($tSearchList),'Y-m-d');
            $tNewdate    = " OR (SAS.START_DATE = '$tSearch') OR (SAS.END_DATE = '$tSearch')";
        }
            try{
                    $tSQL   = "SELECT c.* FROM(
                               SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                               (SELECT
                                    SAS.CUST_NUMBER,
                                    SAS.SALE_ORG,
                                    SAS.DIST_CHANNEL,
                                    SAS.DIVISION,
                                    SAS.START_DATE,
                                    SAS.END_DATE,
                                    SAS.OLD_SALE_DISTRICT,
                                    SAS.NEW_SALE_DISTRICT,
                                    SAS.CUSTOMER_GROUP,
                                    SAS.PRICE_GROUP,
                                    SAS.FDCreateOn
                               FROM [SAP_CUST_ASSIGN_SALES_DISTRICT] SAS
                               WHERE 1=1 ";
                    if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                        $tSQL .= " AND ((SAS.CUST_NUMBER COLLATE THAI_BIN LIKE '%$tSearchList%') OR (SAS.SALE_ORG LIKE '%$tSearchList%')
                        OR (SAS.DIST_CHANNEL LIKE '%$tSearchList%') OR (SAS.DIVISION LIKE '%$tSearchList%')
                        OR (SAS.OLD_SALE_DISTRICT LIKE '%$tSearchList%') OR (SAS.NEW_SALE_DISTRICT LIKE '%$tSearchList%')
                        OR (SAS.CUSTOMER_GROUP LIKE '%$tSearchList%') OR (SAS.PRICE_GROUP LIKE '%$tSearchList%'))";
                         $tSQL .= $tNewdate;
                    }
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKSaleStaffAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                            );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                            );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKSaleStaffAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List SaleStaff
        $tNewdate  = "";
            if ($this->validateDate($ptSearchList, 'Y/m/d') == True){
                $tSearch = date_format(date_create($ptSearchList),'Y-m-d');
                $tNewdate    = " OR (SAS.START_DATE = '$tSearch') OR (SAS.END_DATE = '$tSearch')";
            }
            try{
                    $tSQL = "SELECT COUNT (SAS.CUST_NUMBER) AS counts
                            FROM [SAP_CUST_ASSIGN_SALES_DISTRICT] SAS
                            WHERE 1=1 ";
                    if(isset($ptSearchList) && !empty($ptSearchList) || $ptSearchList == 0){
                        $tSQL .= " AND ((SAS.CUST_NUMBER COLLATE THAI_BIN LIKE '%$ptSearchList%') OR (SAS.SALE_ORG LIKE '%$ptSearchList%')
                        OR (SAS.DIST_CHANNEL LIKE '%$ptSearchList%') OR (SAS.DIVISION LIKE '%$ptSearchList%')
                        OR (SAS.OLD_SALE_DISTRICT LIKE '%$ptSearchList%') OR (SAS.NEW_SALE_DISTRICT LIKE '%$ptSearchList%')
                        OR (SAS.CUSTOMER_GROUP LIKE '%$ptSearchList%') OR (SAS.PRICE_GROUP LIKE '%$ptSearchList%'))";
                         $tSQL .= $tNewdate;
                    }
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKCustomer($paData){ //ดึงข้อมูลมาแสดงหน้า List Customer
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = "SELECT c.* FROM(
                               SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                               (SELECT
                                    CSV.SALES_ORG,
                                    CSV.SALES_ORG_DESC,
                                    CSV.DIST_CHANNEL,
                                    CSV.DIST_CHANNEL_DESC,
                                    CSV.DIVISION,
                                    CSV.DIVISION_DESC,
                                    CSV.CUST_NUMBER,
                                    CSV.CUST_NAME1,
                                    CSV.CUST_NAME2,
                                    CSV.CUST_GR,
                                    CSV.CUST_GR_DESC,
                                    CSV.CUST_GR1,
                                    CSV.CUST_GR1_DESC,
                                    CSV.SALES_DISTRICT,
                                    CSV.SALES_DISTRICT_DESC,
                                    CSV.PRICE_GR,
                                    CSV.PRICE_GR_DESC,
                                    CSV.FDCreateOn
                                FROM [SAP_CUST_SALE_VIEW]  CSV
                                WHERE 1=1 ";
                    if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                    $tSQL .= " AND ((CSV.SALES_ORG COLLATE THAI_BIN LIKE '%$tSearchList%') OR (CSV.SALES_ORG_DESC LIKE '%$tSearchList%')
                    OR (CSV.DIST_CHANNEL LIKE '%$tSearchList%') OR (CSV.DIST_CHANNEL_DESC LIKE '%$tSearchList%')
                    OR (CSV.DIVISION LIKE '%$tSearchList%') OR (CSV.DIVISION_DESC LIKE '%$tSearchList%')
                    OR (CSV.CUST_NUMBER LIKE '%$tSearchList%') OR (CSV.CUST_NAME1 LIKE '%$tSearchList%')
                    OR (CSV.CUST_NAME2 LIKE '%$tSearchList%') OR (CSV.CUST_GR LIKE '%$tSearchList%')
                    OR (CSV.CUST_GR_DESC LIKE '%$tSearchList%') OR (CSV.CUST_GR1 LIKE '%$tSearchList%')
                    OR (CSV.CUST_GR1_DESC LIKE '%$tSearchList%')OR (CSV.SALES_DISTRICT LIKE '%$tSearchList%')
                    OR (CSV.SALES_DISTRICT_DESC LIKE '%$tSearchList%')OR (CSV.PRICE_GR LIKE '%$tSearchList%')
                    OR (CSV.PRICE_GR_DESC LIKE '%$tSearchList%'))";
                    }
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKCustomerAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                            );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                            );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKCustomerAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List Customer
            try{
                    $tSQL = "SELECT COUNT (CSV.SALES_ORG) AS counts
                             FROM [SAP_CUST_SALE_VIEW] CSV
                             WHERE 1=1 ";
                    if(isset($ptSearchList) && !empty($ptSearchList) || $ptSearchList == 0){
                    $tSQL .= " AND ((CSV.SALES_ORG COLLATE THAI_BIN LIKE '%$ptSearchList%') OR (CSV.SALES_ORG_DESC LIKE '%$ptSearchList%')
                    OR (CSV.DIST_CHANNEL LIKE '%$ptSearchList%') OR (CSV.DIST_CHANNEL_DESC LIKE '%$ptSearchList%')
                    OR (CSV.DIVISION LIKE '%$ptSearchList%') OR (CSV.DIVISION_DESC LIKE '%$ptSearchList%')
                    OR (CSV.CUST_NUMBER LIKE '%$ptSearchList%') OR (CSV.CUST_NAME1 LIKE '%$ptSearchList%')
                    OR (CSV.CUST_NAME2 LIKE '%$ptSearchList%') OR (CSV.CUST_GR LIKE '%$ptSearchList%')
                    OR (CSV.CUST_GR_DESC LIKE '%$ptSearchList%') OR (CSV.CUST_GR1 LIKE '%$ptSearchList%')
                    OR (CSV.CUST_GR1_DESC LIKE '%$ptSearchList%')OR (CSV.SALES_DISTRICT LIKE '%$ptSearchList%')
                    OR (CSV.SALES_DISTRICT_DESC LIKE '%$ptSearchList%')OR (CSV.PRICE_GR LIKE '%$ptSearchList%')
                    OR (CSV.PRICE_GR_DESC LIKE '%$ptSearchList%'))";
                    }
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKRole($paData){ //ดึงข้อมูลมาแสดงหน้า List Role
        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tSearchList    = trim($paData['tSearchAll']);
        $tNewdate       = "";
        if ($this->validateDate($tSearchList, 'Y/m/d') == True){
            $tSearch = date_format(date_create($tSearchList),'Y-m-d');
            $tNewdate    = " OR (ROL.START_DATE = '$tSearch') OR (ROL.END_DATE = '$tSearch')";
        }

        try{
            $tSQL   = " SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                        (SELECT
                            ROL.LOCATION_ID,
                            ROL.ROLE,
                            ROL.ROLE_DESC,
                            ROL.START_DATE,
                            ROL.END_DATE,
                            ROL.CUSTOMER_NO,
                            ROL.CUSTOMER_NAME,
                            ROL.FDCreateOn
                        FROM [SAP_PBL_ROLE] ROL
                        WHERE 1=1 ";
            if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                $tSQL .= " AND ((ROL.LOCATION_ID COLLATE THAI_BIN LIKE '%$tSearchList%') OR (ROL.ROLE LIKE '%$tSearchList%')
                OR (ROL.ROLE_DESC LIKE '%$tSearchList%')   OR (ROL.CUSTOMER_NO LIKE '%$tSearchList%')
                OR (ROL.CUSTOMER_NAME LIKE '%$tSearchList%'))";
                $tSQL .= $tNewdate;
            }
            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->result_array();
                $oFoundRow = $this->FSaMcHKSaleRoleAll($tSearchList);
                $nFoundRow = $oFoundRow[0]->counts;
                $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult = array(
                    'aItems'       => $aList,
                    'nAllRow'      => $nFoundRow,
                    'nCurrentPage' => $paData['nPage'],
                    'nAllPage'     => $nPageAll,
                    'tCode'        => '1',
                    'tDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'nAllRow' => 0,
                    'nCurrentPage' => $paData['nPage'],
                    "nAllPage"=> 0,
                    'tCode' => '800',
                    'tDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSaMcHKSaleRoleAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List Role

        $tNewdate  = "";
        if ($this->validateDate($ptSearchList, 'Y/m/d') == True){
            $tSearch        = date_format(date_create($ptSearchList),'Y-m-d');
            $tNewdate       = " OR (ROL.START_DATE = '$tSearch') OR (ROL.END_DATE = '$tSearch')";
        }

        try{
            $tSQL = "SELECT COUNT (ROL.LOCATION_ID) AS counts
                     FROM [SAP_PBL_ROLE] ROL
                     WHERE 1=1 ";

            if(isset($ptSearchList) && !empty($ptSearchList) || $ptSearchList == 0){
                $tSQL .= " AND ((ROL.LOCATION_ID COLLATE THAI_BIN LIKE '%$ptSearchList%') OR (ROL.ROLE LIKE '%$ptSearchList%')
                OR (ROL.ROLE_DESC LIKE '%$ptSearchList%') OR (ROL.CUSTOMER_NO LIKE '%$ptSearchList%')
                OR (ROL.CUSTOMER_NAME LIKE '%$ptSearchList%'))";
                $tSQL .= $tNewdate;
            }

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSaMcHKSaleforStore($paData){ //ดึงข้อมูลมาแสดงหน้า SaleforStore
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            $tNewdate  = "";
            if ($this->validateDate($tSearchList, 'Y/m/d') == True){
                $tSearch = date_format(date_create($tSearchList),'Y-m-d');
                $tNewdate    = " OR (SER.STARTDATE = '$tSearch') OR (SER.ENDDATE = '$tSearch')";
            }
            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    SER.PERSONNEL,
                                    SER.STARTDATE,
                                    SER.ENDDATE,
                                    SER.SALES_ORG,
                                    SER.SALESOFF,
                                    SER.SALESGRP,
                                    SER.SALESDIST,
                                    SER.NAME,
                                    SER.FDCreateOn
                                FROM [SAP_SALESREP] SER
                                WHERE 1=1 ";
                    if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                        $tSQL .= " AND ((SER.PERSONNEL COLLATE THAI_BIN LIKE '%$tSearchList%')
                        OR (SER.SALES_ORG LIKE '%$tSearchList%') OR (SER.SALESOFF LIKE '%$tSearchList%')
                        OR (SER.SALESGRP LIKE '%$tSearchList%') OR (SER.SALESDIST LIKE '%$tSearchList%')
                        OR (SER.NAME LIKE '%$tSearchList%'))";
                    $tSQL .= $tNewdate;
                    }
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKSaleforStoreAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKSaleforStoreAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List SaleforStore
        $tNewdate  = "";
            if ($this->validateDate($ptSearchList, 'Y/m/d') == True){
                $ptSearchList = date_format(date_create($ptSearchList),'Y-m-d');
                $tNewdate    = " OR (SER.STARTDATE = '$ptSearchList') OR (SER.ENDDATE = '$ptSearchList')";
            }
        try{
            $tSQL = "SELECT COUNT (SER.PERSONNEL) AS counts
                     FROM [SAP_SALESREP] SER
                     WHERE 1=1 ";
             if(isset($ptSearchList) && !empty($ptSearchList) || $ptSearchList == 0){
                $tSQL .= " AND ((SER.PERSONNEL COLLATE THAI_BIN LIKE '%$ptSearchList%')
                OR (SER.SALES_ORG LIKE '%$ptSearchList%') OR (SER.SALESOFF LIKE '%$ptSearchList%')
                OR (SER.SALESGRP LIKE '%$ptSearchList%') OR (SER.SALESDIST LIKE '%$ptSearchList%')
                OR (SER.NAME LIKE '%$ptSearchList%'))";
            $tSQL .= $tNewdate;
            }
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSaMcHKCar($paData){ //ดึงข้อมูลมาแสดงหน้า SaleforStore
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    CAR.CARID,
                                    CAR.REGNO,
                                    CAR.IO,
                                    CAR.COSTCENTER,
                                    CAR.PAY_VAT,
                                    CAR.CARSTATUS,
                                    CAR.CONTRACTSTATUS,
                                    CAR.OWNERID,
                                    CAR.OWNERNAME,
                                    CAR.BRANDNAME,
                                    CAR.MODELNAME,
                                    CAR.COLOR,
                                    CAR.ENGINENO,
                                    CAR.ENGINESIZE,
                                    CAR.CARTYPE,
                                    CAR.FDCreateOn
                                FROM [PTTCAR] CAR
                                WHERE 1=1 ";
                    if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                        $tSQL .= " AND ((CAR.CARID COLLATE THAI_BIN LIKE '%$tSearchList%') OR (CAR.REGNO LIKE '%$tSearchList%')
                        OR (CAR.IO LIKE '%$tSearchList%') OR (CAR.COSTCENTER LIKE '%$tSearchList%')
                        OR (CAR.PAY_VAT LIKE '%$tSearchList%') OR (CAR.CARSTATUS LIKE '%$tSearchList%')
                        OR (CAR.CONTRACTSTATUS LIKE '%$tSearchList%') OR (CAR.OWNERID LIKE '%$tSearchList%')
                        OR (CAR.OWNERNAME LIKE '%$tSearchList%') OR (CAR.BRANDNAME LIKE '%$tSearchList%')
                        OR (CAR.MODELNAME LIKE '%$tSearchList%') OR (CAR.COLOR LIKE '%$tSearchList%')
                        OR (CAR.ENGINENO LIKE '%$tSearchList%')OR (CAR.ENGINESIZE LIKE '%$tSearchList%')
                        OR (CAR.CARTYPE LIKE '%$tSearchList%'))";
                    }
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKCarAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKCarAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List Car
            try{
                    $tSQL = "SELECT COUNT (CAR.CARID) AS counts
                             FROM [PTTCAR] CAR
                             WHERE 1=1 ";
                    $tSQL .= " AND ((CAR.CARID COLLATE THAI_BIN LIKE '%$ptSearchList%') OR (CAR.REGNO LIKE '%$ptSearchList%')
                    OR (CAR.IO LIKE '%$ptSearchList%') OR (CAR.COSTCENTER LIKE '%$ptSearchList%')
                    OR (CAR.PAY_VAT LIKE '%$ptSearchList%') OR (CAR.CARSTATUS LIKE '%$ptSearchList%')
                    OR (CAR.CONTRACTSTATUS LIKE '%$ptSearchList%') OR (CAR.OWNERID LIKE '%$ptSearchList%')
                    OR (CAR.OWNERNAME LIKE '%$ptSearchList%') OR (CAR.BRANDNAME LIKE '%$ptSearchList%')
                    OR (CAR.MODELNAME LIKE '%$ptSearchList%') OR (CAR.COLOR LIKE '%$ptSearchList%')
                    OR (CAR.ENGINENO LIKE '%$ptSearchList%')OR (CAR.ENGINESIZE LIKE '%$ptSearchList%')
                    OR (CAR.CARTYPE LIKE '%$ptSearchList%'))";
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProducts($paData){ //ดึงข้อมูลมาแสดงหน้า Products
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                PDT.ProductID,
                                PDT.ProductDeptID,
                                PDT.ProductCode,
                                PDT.ProductBarCode,
                                PDT.ProductName,
                                PDT.ProductName1,
                                PDT.DiscountAllow,
                                PDT.Deleted,
                                PDT.VATTYPE,
                                PDT.FDCreateOn
                                FROM [Product] PDT
                                WHERE 1=1 ";
                    if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                    $tSQL .= " AND ((PDT.ProductID COLLATE THAI_BIN LIKE '%$tSearchList%')OR (PDT.ProductDeptID LIKE '%$tSearchList%') or (PDT.ProductCode LIKE '%$tSearchList%')
                                   OR (PDT.ProductBarCode LIKE '%$tSearchList%') or (PDT.ProductName LIKE '%$tSearchList%')
                                   or (PDT.ProductName1 LIKE '%$tSearchList%') or (PDT.DiscountAllow LIKE '%$tSearchList%')
                                   or (PDT.Deleted LIKE '%$tSearchList%') or (PDT.VATTYPE LIKE '%$tSearchList%') or (PDT.FDCreateOn LIKE '%$tSearchList%') )";
                    }

                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKProductsAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }


    public function FSaMcHKProductsAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List Products
            try{
                    $tSQL = "SELECT COUNT (PDT.ProductID) AS counts
                            FROM [Product] PDT
                            WHERE 1=1 ";
                    $tSQL .= " AND ((PDT.ProductID COLLATE THAI_BIN LIKE '%$ptSearchList%')OR (PDT.ProductDeptID LIKE '%$ptSearchList%') or (PDT.ProductCode LIKE '%$ptSearchList%')
                               OR (PDT.ProductBarCode LIKE '%$ptSearchList%') or (PDT.ProductName LIKE '%$ptSearchList%')
                               or (PDT.ProductName1 LIKE '%$ptSearchList%') or (PDT.DiscountAllow LIKE '%$ptSearchList%')
                               or (PDT.Deleted LIKE '%$ptSearchList%') or (PDT.VATTYPE LIKE '%$ptSearchList%') or (PDT.FDCreateOn LIKE '%$ptSearchList%') )";
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductGroup($paData){ //ดึงข้อมูลมาแสดงหน้า Products Group
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    PDG.ProductGroupID,
                                    PDG.ProductGroupCode,
                                    PDG.ProductGroupName,
                                    PDG.Deleted,
                                    PDG.FDCreateOn
                                FROM [ProductGroup] PDG
                                WHERE 1=1 ";
                    $tSQL .= " AND ((PDG.ProductGroupID COLLATE THAI_BIN LIKE '%$tSearchList%')OR (PDG.ProductGroupCode LIKE '%$tSearchList%') or (PDG.ProductGroupName LIKE '%$tSearchList%')
                               or (PDG.Deleted LIKE '%$tSearchList%') or (PDG.FDCreateOn LIKE '%$tSearchList%'))";
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKProductGroupAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductGroupAll($tSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List Products Group
            try{
                    $tSQL = "SELECT COUNT (PDG.ProductGroupID) AS counts
                             FROM [ProductGroup] PDG
                             WHERE 1=1 ";
                    $tSQL .= " AND ((PDG.ProductGroupID COLLATE THAI_BIN LIKE '%$tSearchList%')OR (PDG.ProductGroupCode LIKE '%$tSearchList%') or (PDG.ProductGroupName LIKE '%$tSearchList%')
                               or (PDG.Deleted LIKE '%$tSearchList%') or (PDG.FDCreateOn LIKE '%$tSearchList%'))";
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductDept($paData){ //ดึงข้อมูลมาแสดงหน้า ProductDept
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    PDD.ProductDeptID,
                                    PDD.ProductDeptCode,
                                    PDD.ProductDeptName,
                                    PDD.Deleted,
                                    PDD.FDCreateOn
                                FROM [ProductDept] PDD
                                WHERE 1=1 ";
                    $tSQL .= " AND ((PDD.ProductDeptID COLLATE THAI_BIN LIKE '%$tSearchList%')OR (PDD.ProductDeptCode LIKE '%$tSearchList%') or (PDD.ProductDeptName LIKE '%$tSearchList%')
                               or (PDD.Deleted LIKE '%$tSearchList%') or (PDD.FDCreateOn LIKE '%$tSearchList%'))";
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKProductDeptAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductDeptAll($ptSearchList ){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List ProductDept
            try{
                    $tSQL = "SELECT COUNT (PDD.ProductDeptID) AS counts
                             FROM [ProductDept] PDD
                             WHERE 1=1 ";
                    $tSQL .= " AND ((PDD.ProductDeptID COLLATE THAI_BIN LIKE '%$ptSearchList%')OR (PDD.ProductDeptCode LIKE '%$ptSearchList%') or (PDD.ProductDeptName LIKE '%$ptSearchList%')
                               or (PDD.Deleted LIKE '%$ptSearchList%') or (PDD.FDCreateOn LIKE '%$ptSearchList%'))";
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductUnitSmall($paData){ //ดึงข้อมูลมาแสดงหน้า UnitSmall
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);
            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    UTS.UnitSmallID,
                                    UTS.UnitSmallName,
                                    UTS.FTFlag,
                                    UTS.FTDesc,
                                    UTS.FTFrmFile,
                                    UTS.FDCreateOn
                                FROM [UnitSmall] UTS
                                WHERE 1=1 ";
                    $tSQL .= " AND ((UTS.UnitSmallID COLLATE THAI_BIN LIKE '%$tSearchList%')OR (UTS.UnitSmallName LIKE '%$tSearchList%') or (UTS.FTFlag LIKE '%$tSearchList%')
                               or (UTS.FTDesc LIKE '%$tSearchList%') or (UTS.FTFrmFile LIKE '%$tSearchList%') or (UTS.FDCreateOn LIKE '%$tSearchList%'))";
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKUnitSmallAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKUnitSmallAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List UnitSmall
            try{
                    $tSQL = "SELECT COUNT (UTS.UnitSmallID) AS counts
                             FROM [UnitSmall] UTS
                             WHERE 1=1 ";
                    $tSQL .= " AND ((UTS.UnitSmallID COLLATE THAI_BIN LIKE '%$ptSearchList%')OR (UTS.UnitSmallName LIKE '%$ptSearchList%') or (UTS.FTFlag LIKE '%$ptSearchList%')
                               or (UTS.FTDesc LIKE '%$ptSearchList%') or (UTS.FTFrmFile LIKE '%$ptSearchList%') or (UTS.FDCreateOn LIKE '%$ptSearchList%'))";
                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductComponent($paData){ //ดึงข้อมูลมาแสดงหน้า ProductComponent
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = trim($paData['tSearchAll']);



            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    PDD.ProductDeptID,
                                    PCG.ProductGroupID,
                                    PCP.UnitSmallID,
                                    PCP.ProductID,
                                    PCP.FDCreateOn
                                FROM [ProductComponent] PCP
                                INNER JOIN Product PDT ON  PDT.ProductID = PCP.ProductID
                                INNER JOIN ProductDept PDD ON  PDD.ProductDeptID = PDT.ProductDeptID
                                INNER JOIN ProductGroup PCG ON  PCG.ProductGroupID = PDD.ProductGroupID
                                WHERE 1=1 ";
                    $tSQL .= " AND ((PCP.ProductID COLLATE THAI_BIN LIKE '%$tSearchList%')OR (PCP.UnitSmallID LIKE '%$tSearchList%')
                    or (PDD.ProductDeptID LIKE '%$tSearchList%')  or (PCG.ProductGroupID LIKE '%$tSearchList%')
                               or (PCP.ProductID LIKE '%$tSearchList%') or (PCP.FDCreateOn LIKE '%$tSearchList%'))";
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKProductComponentAll($tSearchList);
                      
                            $nFoundRow =($oFoundRow!=false) ? count($oFoundRow) : 0 ;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductComponentAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List ProductComponent
            try{
              $tSQL   = " SELECT
                              PDD.ProductDeptID,
                              PCG.ProductGroupID,
                              PCP.UnitSmallID,
                              PCP.ProductID,
                              PCP.FDCreateOn
                          FROM [ProductComponent] PCP
                          INNER JOIN Product PDT ON  PDT.ProductID = PCP.ProductID
                          INNER JOIN ProductDept PDD ON  PDD.ProductDeptID = PDT.ProductDeptID
                          INNER JOIN ProductGroup PCG ON  PCG.ProductGroupID = PDD.ProductGroupID
                          WHERE 1=1 ";
              $tSQL .= " AND ((PCP.ProductID COLLATE THAI_BIN LIKE '%$ptSearchList%')OR (PCP.UnitSmallID LIKE '%$ptSearchList%')
              or (PDD.ProductDeptID LIKE '%$ptSearchList%')  or (PCG.ProductGroupID LIKE '%$ptSearchList%')
                         or (PCP.ProductID LIKE '%$ptSearchList%') or (PCP.FDCreateOn LIKE '%$ptSearchList%'))";

                    $oQuery = $this->db->query($tSQL);
                    if ($oQuery->num_rows() > 0) {
                        return $oQuery->result();
                    }else{
                        return false;
                    }
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductPrice($paData){ //ดึงข้อมูลมาแสดงหน้า ProductPrice
            $aRowLen  = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList  = trim($paData['tSearchAll']);
            try{
                    $tSQL   = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS rtRowID,* FROM
                                (SELECT
                                    PDP.ProductPriceID,
                                    PDP.ProductID,
                                    PDP.ProductPrice,
                                    PDP.FDCreateOn
                                FROM [ProductPrice] PDP
                                WHERE 1=1 ";
                    $tSQL .= " AND ((PDP.ProductPriceID COLLATE THAI_BIN LIKE '%$tSearchList%')OR (PDP.ProductID LIKE '%$tSearchList%') or (PDP.ProductPrice LIKE '%$tSearchList%')
                               or (PDP.FDCreateOn LIKE '%$tSearchList%'))";
                    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                            $aList = $oQuery->result_array();
                            $oFoundRow = $this->FSaMcHKProductPriceAll($tSearchList);
                            $nFoundRow = $oFoundRow[0]->counts;
                            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                            $aResult = array(
                                'aItems'       => $aList,
                                'nAllRow'      => $nFoundRow,
                                'nCurrentPage' => $paData['nPage'],
                                'nAllPage'     => $nPageAll,
                                'tCode'        => '1',
                                'tDesc'        => 'success',
                        );
                    }else{
                            //No Data
                            $aResult = array(
                                'nAllRow' => 0,
                                'nCurrentPage' => $paData['nPage'],
                                "nAllPage"=> 0,
                                'tCode' => '800',
                                'tDesc' => 'data not found',
                        );
                    }
                    return $aResult;
                }catch(Exception $Error){
                    echo $Error;
                }
    }

    public function FSaMcHKProductPriceAll($ptSearchList){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List ProductPrice
        try{
                $tSQL = "SELECT COUNT (PDP.ProductPriceID) AS counts
                         FROM [ProductPrice] PDP
                         WHERE 1=1 ";
                $tSQL .= " AND ((PDP.ProductPriceID COLLATE THAI_BIN LIKE '%$ptSearchList%')OR (PDP.ProductID LIKE '%$ptSearchList%') or (PDP.ProductPrice LIKE '%$ptSearchList%')
                            or (PDP.FDCreateOn LIKE '%$ptSearchList%'))";
                $oQuery = $this->db->query($tSQL);
                if ($oQuery->num_rows() > 0) {
                    return $oQuery->result();
                }else{
                    return false;
                }
            }catch(Exception $Error){
                echo $Error;
            }
    }

}
