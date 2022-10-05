<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Productcar_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        if($this->db->table_exists($tTable)){
            // echo 'ถ้ามีแล้วก็ไม่ต้องทำอะไร';
        }else{
            //echo 'ถ้าไม่มีก็ต้องเพิ่ม';
            $tSQL = "CREATE TABLE $tTable (
                        FTPdtCode VARCHAR(20) NOT NULL,
                        FTPdtCodeSub VARCHAR(20) NOT NULL,
                        FTPsvType VARCHAR(1),
                        FCPsvQty Float(53),
                        FTPunCode VARCHAR(5),
                        FCPsvFactor Float(53),
                        FTPsvStaSuggest VARCHAR(1),
                        FTSessionID VARCHAR(255)
                    );";
            $this->db->query($tSQL);
        }

        $tTable2 = "TSVTPdtSetChk_Tmp" . date("dmY");
        if($this->db->table_exists($tTable2)){
            // echo 'ถ้ามีแล้วก็ไม่ต้องทำอะไร';
        }else{
            //echo 'ถ้าไม่มีก็ต้องเพิ่ม';
            $tSQL = "CREATE TABLE $tTable2 (
                        FTPdtCode VARCHAR(20) NOT NULL,
                        FTPdtCodeSub VARCHAR(20) NOT NULL,
                        FNPdtSrvSeq bigint,
                        FTPdtChkResult VARCHAR(50),
                        FTPdtChkType int,
                        FTSessionID VARCHAR(255)
                    );";
            $this->db->query($tSQL);
        }
    }

    
    //Functionality : AddEdit Car Data
    //Parameters : -
    //Creator : 28/06/2021 Off
    //Return : status
    //Return Type : array
    public function FSaMPDTGetDataCar($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $tSQL       = " SELECT
                            PDTCAR.FCPsvMaDistance,
                            PDTCAR.FNPsvMaQtyMonth,
                            PDTCAR.FCPsvWaDistance,
                            PDTCAR.FNPsvWaQtyDay,
                            PDTCAR.FTPsvWaCond,
                            PDTCAR.FCPsvQtyTime
                        FROM TSVMPdtCar PDTCAR WITH(NOLOCK)
                        WHERE 1=1
                            AND PDTCAR.FTPdtCode = '$tPdtCode'
                        ";
        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query();exit;
        if ($oQuery->num_rows() > 0) {
            $aDataReturn    = $oQuery->result_array();
        } else {
            $aDataReturn    = array();
        }
        return $aDataReturn;
    }

    //Functionality : Add Product SV Tmp
    //Parameters : -
    //Creator : 28/06/2021 Off
    //Return : status
    //Return Type : array
    public function FSaMPDTInsertTmpSv($paPdtSVData)
    {
        // Update TCNMPdt
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        $aDataUpdate    = array_merge($paPdtSVData, array(
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        ));
        $this->db->where('FTPdtCode', $paPdtSVData['FTPdtCode']);
        $this->db->where('FTPdtCodeSub', $paPdtSVData['FTPdtCodeSub']);
        $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
        $this->db->update($tTable, $aDataUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'tCode' => '1',
                'tDesc' => 'Update Product Set Success',
            );
        } else {
            $aDataInsert = array_merge($paPdtSVData, array(
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ));
            $this->db->insert($tTable, $aDataInsert);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'tCode' => '1',
                    'tDesc' => 'Add Product Set Success',
                );
            } else {
                $aStatus    = array(
                    'tCode' => '801',
                    'tDesc' => 'Error Cannot Add/Update Product Set.',
                );
            }
        }
        return $aStatus;
    }

    //Functionality : Add Product SV Tmp
    //Parameters : -
    //Creator : 28/06/2021 Off
    //Return : status
    //Return Type : array
    public function FSaMPDTInsertTmpPdtChk($paPdtSVData)
    {
        // Update TSVTPdtSetChk_Tmp
        $tTable = "TSVTPdtSetChk_Tmp" . date("dmY");
        
        $aDataUpdate    = array_merge($paPdtSVData, array(
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        ));
        $this->db->where('FTPdtCode', $paPdtSVData['FTPdtCode']);
        $this->db->where('FTPdtCodeSub', $paPdtSVData['FTPdtCodeSub']);
        $this->db->where('FNPdtSrvSeq', $paPdtSVData['FNPdtSrvSeq']);
        $this->db->update($tTable, $aDataUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'tCode' => '1',
                'tDesc' => 'Update Product SV Detail Success',
            );
        } else {
            $aDataInsert = array_merge($paPdtSVData, array(
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ));
            $this->db->insert($tTable, $aDataInsert);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'tCode' => '1',
                    'tDesc' => 'Add Product SV Detail Success',
                );
            } else {
                $aStatus    = array(
                    'tCode' => '801',
                    'tDesc' => 'Error Cannot Add/Update Product SV Detail.',
                );
            }
        }
        return $aStatus;
    }

    //Functionality : Function Clear Detail Tmp
    //Parameters : function parameters
    //Creator :  30/06/2021 Off
    //Last Modified :
    //Return : Status Add Update Detail
    //Return Type : Array
    public function FSaMPDTDelDTTmp($paData){
        $tTable2 = "TSVTPdtSetChk_Tmp" . date("dmY");
        $this->db->where('FTPdtCode', $paData['FTPdtCode']);
        $this->db->where('FTPdtCodeSub', $paData['FTPdtCodeSub']);
        $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
        $this->db->delete($tTable2);
        return $aStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'success',
        );
    }

    // Functionality : Select Data Product SV From Tmp
    // Parameters : function parameters
    // Creator : 29/06/2021 Off
    // Last Modified : -
    // Return : Array Data Query For Database
    // Return Type : Array
    public function FSaMPDTGetDataPdtSV($paData)
    {
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        $tSQL_Config    = "SELECT FTSysStaUsrValue FROM TsysConfig WHERE FTSysCode='tCN_Cost' AND FTSysSeq='1'";
        $oQuery_Config  = $this->db->query($tSQL_Config);
        $aDataConfig    = $oQuery_Config->result_array();
        $tsession = $this->session->userdata('tSesSessionID');

        $tSQL = "SELECT
                PSET.FTPdtCode,
                PSET.FTPdtCodeSub,
                PSET.FCPsvQty,
                PSET.FTPsvType,
                PDT_L.FTPdtName,
                PSET.FTPunCode ,
                PIMG.FTImgObj,
                PUN_L.FTPunName,
                PDT.FTPdtForSystem
            FROM 
                $tTable PSET WITH(NOLOCK)
            LEFT JOIN TCNMPdt                   PDT   WITH(NOLOCK) ON PSET.FTPdtCodeSub = PDT.FTPdtCode
            LEFT JOIN TCNMPdt_L                 PDT_L WITH(NOLOCK) ON PSET.FTPdtCodeSub = PDT_L.FTPdtCode   AND PDT_L.FNLngID =  $paData[FNLngID]
            LEFT JOIN TCNMImgPdt                PIMG  WITH (NOLOCK) ON PSET.FTPdtCodeSub = PIMG.FTImgRefID AND PIMG.FTImgTable = 'TCNMPdt' AND PIMG.FNImgSeq = 1
            LEFT JOIN TCNMPdtUnit_L             PUN_L WITH(NOLOCK) ON PSET.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = $paData[FNLngID] 
            WHERE FTSessionID = '$tsession'
        ";
        if (isset($paData['FTPdtCode'])) {
            $tSQL .= " AND PSET.FTPdtCode    = '$paData[FTPdtCode]'";
        }
        // print_r($tSQL); die();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = array(
                'tSQL'          => $tSQL,
                'aItems'        => $oQuery->result_array(),
                'tStaUsrValue'  => $aDataConfig[0]['FTSysStaUsrValue'],
                'tCode'            => '1',
                'tDesc'            => 'success'
            );
        } else {
            $aResult = array(
                'tSQL'      => $tSQL,
                'tCode'        => '800',
                'tDesc'        => 'data not found'
            );
        }
        return $aResult;
    }

    //Functionality : Delete Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTDelPdtSV($paDataDel)
    {
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        $this->db->where('FTPdtCode', $paDataDel['FTPdtCode']);
        $this->db->where('FTPdtCodeSub', $paDataDel['FTPdtCodeSub']);
        $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
        $this->db->delete($tTable);

        $tTable2 = "TSVTPdtSetChk_Tmp" . date("dmY");
        $this->db->where('FTPdtCode', $paDataDel['FTPdtCode']);
        $this->db->where('FTPdtCodeSub', $paDataDel['FTPdtCodeSub']);
        $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
        $this->db->delete($tTable2);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'tCode' => '500',
                'tDesc' => 'Error Cannot Delete Product.',
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'tCode' => '1',
                'tDesc' => 'Delete Product Success.',
            );
        }
        return $aStatus;
    }

    public function FSxMPDTAddUpdateSetChk($paPdtWhere, $paDataBarCode)
    {
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        $tTable2 = "TSVTPdtSetChk_Tmp" . date("dmY");

        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TSVTPdtSet');

        $tSQL = "INSERT INTO TSVTPdtSet (FTPdtCode,
                        FTPdtCodeSub,
                        FTPsvType,
                        FCPsvQty,
                        FTPunCode,
                        FCPsvFactor,
                        FTPsvStaSuggest)
                SELECT FTPdtCode,
                        FTPdtCodeSub,
                        FTPsvType,
                        FCPsvQty,
                        FTPunCode,
                        FCPsvFactor,
                        FTPsvStaSuggest
                FROM $tTable
                WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);


        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TSVTPdtSetChk');

        $tSQL2 = "INSERT INTO TSVTPdtSetChk (FTPdtCode,
                        FTPdtCodeSub,
                        FNPdtSrvSeq,
                        FTPdtChkResult,
                        FTPdtChkType)
                SELECT FTPdtCode,
                        FTPdtCodeSub,
                        FNPdtSrvSeq,
                        FTPdtChkResult,
                        FTPdtChkType
                FROM $tTable2
                WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL2);

        $tSQLCheck = "SELECT FTPdtCode,
                    FTPdtCodeSub,
                    FTPsvType,
                    FCPsvQty,
                    FTPunCode,
                    FCPsvFactor,
                    FTPsvStaSuggest
            FROM $tTable
            WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID'";

            $oCheckQuery = $this->db->query($tSQLCheck);
            $aDataCheckQueryReturn    = $oCheckQuery->result_array();

        // exit;
        if ($oQuery > 0) {
            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->delete($tTable);
            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->delete($tTable2);

            if(count($aDataCheckQueryReturn) > 0){
                $aDataUpdate    = array(
                    'FTPdtSetOrSN'  => '5',
                    'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
                );
            }else{
                $aDataUpdate    = array(
                    'FTPdtSetOrSN'  => '1',
                    'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
                );
            }
            
            $this->db->where('FTPdtCode', $FTPdtCode);
            $this->db->update('TCNMPdt', $aDataUpdate);

            $aResult    =  array(
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    //Functionality : Insert to Tmp SVSET
    //Parameters : -
    //Creator : 29/06/2021 Off
    //Return : status
    //Return Type : array
    public function FSaMPDTInsertPdtSvSetTemp($paData)
    {
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        $tTable2 = "TSVTPdtSetChk_Tmp" . date("dmY");

        $tSQL = "INSERT INTO $tTable (
                FTPdtCode,
                FTPdtCodeSub,
                FTPsvType,
                FCPsvQty,
                FTPunCode,
                FCPsvFactor,
                FTPsvStaSuggest,
                FTSessionID
                )
            SELECT 
                FTPdtCode,
                FTPdtCodeSub,
                FTPsvType,
                FCPsvQty,
                FTPunCode,
                FCPsvFactor,
                FTPsvStaSuggest,
                '$FTMttSessionID'    AS FTSessionID
            FROM TSVTPdtSet
            WHERE FTPdtCode = '$FTPdtCode'";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery > 0) {
            $aResult = array(
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    //Functionality : Insert to Tmp SVSETCHK
    //Parameters : -
    //Creator : 29/06/2021 Off
    //Return : status
    //Return Type : array
    public function FSaMPDTInsertPdtSvSetChkTemp($paData)
    {
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];
        $tTable = "TSVTPdtSetChk_Tmp" . date("dmY");

        $tSQL = "INSERT INTO $tTable (
            FTPdtCode,
            FTPdtCodeSub,
            FNPdtSrvSeq,
            FTPdtChkResult,
            FTPdtChkType,
            FTSessionID
            )
        SELECT 
            FTPdtCode,
            FTPdtCodeSub,
            FNPdtSrvSeq,
            FTPdtChkResult,
            FTPdtChkType,
            '$FTMttSessionID'    AS FTSessionID
        FROM TSVTPdtSetChk
        WHERE FTPdtCode = '$FTPdtCode'";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery > 0) {
            $aResult = array(
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTDelDataSVSet($paPdtWhere)
    {
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        $tTable2 = "TSVTPdtSetChk_Tmp" . date("dmY");

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete($tTable);
    
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete($tTable2);

    }

    // Functionality: Func. Get Data Product Set SV By ID Product
    // Parameters: Array ข้อมูลสินค้าเซ็ท
    // Creator:  29/06/2021 Off
    // LastUpdate: -
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataPdtSetSVByID($paDataWhere)
    {
        $tTable = "TSVTPdtSet_Tmp" . date("dmY");
        $tTable2 = "TSVTPdtSetChk_Tmp" . date("dmY");
        $tSession = $this->session->userdata('tSesSessionID');
        $tSQL = "SELECT TOP 1
                    PUN_L.FTPunName,
                    PSET.FTPdtCode,
                    PSET.FTPdtCodeSub,
                    PSET.FTPsvType,
                    PSET.FCPsvQty,
                    PSET.FTPunCode,
                    PSET.FCPsvFactor,
                    PSET.FTPsvStaSuggest,
                    PDT_L.FTPdtName
                FROM 
                    $tTable PSET WITH(NOLOCK)
                LEFT JOIN TCNMPdt_L         PDT_L WITH(NOLOCK) ON PSET.FTPdtCodeSub = PDT_L.FTPdtCode AND PDT_L.FNLngID = $paDataWhere[FNLngID]
                LEFT JOIN TCNMPdtUnit_L     PUN_L WITH(NOLOCK) ON PSET.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = $paDataWhere[FNLngID]
                WHERE PSET.FTPdtCode      = '$paDataWhere[FTPdtCode]'
                  AND PSET.FTPdtCodeSub   = '$paDataWhere[FTPdtCodeSet]'
                  AND PSET.FTSessionID    = '$tSession'";
        $oQuery = $this->db->query($tSQL);

        $tSQL2 = "SELECT
                    PSET.FTPdtCode,
                    PSET.FTPdtCodeSub,
                    PSET.FNPdtSrvSeq,
                    PSET.FTPdtChkResult,
                    PSET.FTPdtChkType
                FROM 
                    $tTable2 PSET WITH(NOLOCK)
                WHERE PSET.FTPdtCode      = '$paDataWhere[FTPdtCode]'
                  AND PSET.FTPdtCodeSub   = '$paDataWhere[FTPdtCodeSet]'
                  AND PSET.FTSessionID    = '$tSession'";
        $oQuery2 = $this->db->query($tSQL2);


        if ($oQuery->num_rows() > 0) {
            $aResult = array(
                'tSQL'          => $tSQL,
                'aItems'        => $oQuery->result_array(),
                'aAnwser'       => $oQuery2->result_array(),
                'tCode'         => '1',
                'tDesc'         => 'success'
            );
        } else {
            $aResult = array(
                'tSQL'      => $tSQL,
                'tCode'        => '800',
                'tDesc'        => 'data not found'
            );
        }
        return $aResult;
    }

}
