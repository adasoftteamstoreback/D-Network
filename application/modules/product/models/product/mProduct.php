<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mProduct extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $tTableTmp = "TCNTPdtSet_Tmp" . date("dmY");
        if ($this->db->table_exists($tTableTmp)) {
            // echo 'ถ้ามีแล้วก็ไม่ต้องทำอะไร' herenaha;
        } else {
            //echo 'ถ้าไม่มีก็ต้องเพิ่ม';
            $tSQL = "CREATE TABLE $tTableTmp (
                        FTPdtCode VARCHAR(20) NOT NULL,
                        FTPdtCodeSet VARCHAR(20) NOT NULL,
                        FCPstQty Float(18),
                        FDLastUpdOn DATETIME,
                        FTLastUpdBy VARCHAR(20),
                        FDCreateOn DATETIME,
                        FTCreateBy VARCHAR(20),
                        FTPunCode VARCHAR(5),
                        FCXsdFactor Float(18),
                        FTSessionID VARCHAR(255)
                    );";
            $this->db->query($tSQL);
        }
    }

    // Functionality: ดึงข้อมูลสินค้า
    // Parameters: array
    // Creator: 31/01/2019 wasin(Yoshi) - 08/06/2020 wat
    // Return: ข้อมูลสินค้าแบบ Array
    // ReturnType: Object Array
    public function FSaMPDTGetDataTable($paData)
    {
        $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID         = (empty($paData['FNLngID'])) ? 1 : $paData['FNLngID'];
        $tSearch        = $paData['tSearchAll'];
        $nSearchProductType   = $paData['nSearchProductType'];
        $tPdtForSys     = $paData['tPdtForSys'];
        if (isset($tPdtForSys) && !empty($tPdtForSys)) {
            $tPdtForSysLine1    = " AND PDT.FTPdtForSystem = " . $paData['tPdtForSys'];
        } else {
            $tPdtForSysLine1    = "";
        }
        $tSesUserCode   = $this->session->userdata('tSesUserCode');
        $aDataUsrGrp    = $this->db->where('FTUsrCode', $tSesUserCode)->get('TCNTUsrGroup')->row_array();

        $tSesUsrLevel           = $this->session->userdata('tSesUsrLevel');
        $tSessionMerCode        = $this->session->userdata('tSesUsrMerCode');
        $tSessionShopCode       = $this->session->userdata('tSesUsrShpCodeMulti');
        $tSesUsrAgnCode         = $this->session->userdata('tSesUsrAgnCode');
        $tSessionBchCode        = $this->session->userdata('tSesUsrBchCodeMulti');
        $tDefaultBchCode        = $aDataUsrGrp['FTBchCode'];
        $tDefaultShpCode        = $aDataUsrGrp['FTShpCode'];
        $tWHEREPermission_BCH   = '';
        $tWHEREPermission_SHP   = '';


        if ($tSesUsrLevel != 'HQ') {
            //---------------------- การมองเห็นเฉพาะสินค้าตามระดับผู้ใช้--------------------------//
            $tWHEREPermission_BCH .= " AND ( (";
            $tWHEREPermission_BCH .= "     ISNULL(PDLSPC.FTAgnCode,'')      = " . $this->db->escape($tSesUsrAgnCode) . " ";
            if (!empty($tSessionMerCode)) { //กรณีผู้ใช้ผูก Mer จะเห็นสินค้าภายใต้ Mer
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTMerCode,'')  = " . $this->db->escape($tSessionMerCode) . " ";
            }
            if (!empty($tDefaultBchCode)) { //กรณีผู้ใช้ผูก Bch จะเห็นสินค้าภายใต้ Bch
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTBchCode,'')  IN ($tSessionBchCode) ";
            }
            if (!empty($tSessionShopCode)) { //กรณีผู้ใช้ผูก Shp จะเห็นสินค้าภายใต้ Shp
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTShpCode,'' ) IN ($tSessionShopCode) ";
            }
            $tWHEREPermission_BCH .= " ) ";
            /* |-------------------------------------------------------------------------------------------| */

            //---------------------- การมองเห็นสินค้าระดับสาขา (สำหรับผู้ใช้ระดับร้านค้า)--------------------------//
            if (!empty($tSessionShopCode)) {
                $tWHEREPermission_BCH   .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่อยู่ใน Bch แต่ไม่ผูก Shp
                $tWHEREPermission_BCH   .= " ISNULL(PDLSPC.FTAgnCode,'')        = " . $this->db->escape($tSesUsrAgnCode) . " ";
                $tWHEREPermission_BCH   .= " AND ISNULL(PDLSPC.FTMerCode,'')    = " . $this->db->escape($tSessionMerCode) . " ";
                $tWHEREPermission_BCH   .= " AND ISNULL(PDLSPC.FTBchCode,'') IN ($tSessionBchCode) ";
                $tWHEREPermission_BCH   .= " AND ISNULL(PDLSPC.FTShpCode,'')    = '' ";
                $tWHEREPermission_BCH   .= " ) ";

                $tWHEREPermission_BCH .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่อยู่ใน Bch แต่ไม่ผูก Shp และไม่ผูก Mer
                $tWHEREPermission_BCH .= "     ISNULL(PDLSPC.FTAgnCode,'')  = " . $this->db->escape($tSesUsrAgnCode) . " ";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTMerCode,'')  = ''";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTBchCode,'') IN ($tSessionBchCode) ";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTShpCode,'')  = ''";
                $tWHEREPermission_BCH .= " ) ";

                $tWHEREPermission_BCH .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่ไม่ผูก Bch และ ไม่ผูก Shp
                $tWHEREPermission_BCH .= "     ISNULL(PDLSPC.FTAgnCode,'')  = " . $this->db->escape($tSesUsrAgnCode) . " ";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTMerCode,'')  = " . $this->db->escape($tSessionMerCode) . " ";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTBchCode,'')  = ''";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTShpCode,'')  = ''";
                $tWHEREPermission_BCH .= " ) ";

                $tWHEREPermission_BCH .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่ไม่ผูก Mer และสินค้าผูก Bch / Shp
                $tWHEREPermission_BCH .= "     ISNULL(PDLSPC.FTAgnCode,'')  = " . $this->db->escape($tSesUsrAgnCode) . " ";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTMerCode,'')  = ''";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTBchCode,'')  IN ($tSessionBchCode) ";
                $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTShpCode,'')  IN ($tSessionShopCode) ";
                $tWHEREPermission_BCH .= " ) ";
            }
            /* |-------------------------------------------------------------------------------------------| */

            //---------------------- การมองเห็นสินค้าระดับตัวแทนขาย--------------------------//

            $tWHEREPermission_BCH   .= " OR (";
            $tWHEREPermission_BCH   .= "     ISNULL(PDLSPC.FTAgnCode,'')    = " . $this->db->escape($tSesUsrAgnCode) . " ";
            if (!empty($tSessionMerCode)) { //กรณีผู้ใช้ผูก Mer จะต้องเห็นสินค้าที่ไม่ได้ผูก Mer ด้วย
                $tWHEREPermission_BCH   .= " AND ISNULL(PDLSPC.FTMerCode,'')    = '' ";
            }
            if (!empty($tDefaultBchCode)) { //กรณีผู้ใช้ผูก Bch จะต้องเห็นสินค้าที่ไม่ได้ผูก Bch ด้วย
                $tWHEREPermission_BCH   .= " AND ISNULL(PDLSPC.FTBchCode,'')    = '' ";
            }
            if (!empty($tSessionShopCode)) { //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่ไม่ได้ผูก Shp ด้วย
                $tWHEREPermission_BCH   .= " AND ISNULL(PDLSPC.FTShpCode,'')    = ''";
            }
            $tWHEREPermission_BCH   .= " ) ";

            /* |-------------------------------------------------------------------------------------------| */


            //---------------------- การมองเห็นสินค้าระดับส่วนกลางหรือสินค้าที่ไม่ได้ผูกกับอะไรเลย--------------------------//
            $tWHEREPermission_BCH .= " OR (";
            $tWHEREPermission_BCH .= "     ISNULL(PDLSPC.FTAgnCode,'') = '' ";
            $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTMerCode,'') = '' ";
            $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTBchCode,'') = '' ";
            $tWHEREPermission_BCH .= " AND ISNULL(PDLSPC.FTShpCode,'') = '' ";
            $tWHEREPermission_BCH .= " )) ";
            /* |-------------------------------------------------------------------------------------------| */
        }


        $aSpcJoinTableMaster =  array(
            1 => "", //รหัสสินค้า
            2 => "LEFT JOIN TCNMPdt_L PDTL WITH (NOLOCK)       ON PDT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID  = " . $this->db->escape($nLngID),  //หาชื่อสินค้า
            3 => "LEFT JOIN TCNMPdtPackSize PPCZ WITH (NOLOCK) ON PDT.FTPdtCode = PPCZ.FTPdtCode LEFT JOIN TCNMPdtBar PBAR WITH (NOLOCK)      ON PDT.FTPdtCode = PBAR.FTPdtCode  AND PPCZ.FTPunCode = PBAR.FTPunCode",  //หาบาร์โค้ด
            4 => "LEFT JOIN TCNMPdtPackSize PPCZ WITH (NOLOCK) ON PDT.FTPdtCode = PPCZ.FTPdtCode LEFT JOIN TCNMPdtUnit_L PUNL WITH (NOLOCK)   ON PPCZ.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID   = " . $this->db->escape($nLngID), //หาหน่วย
            5 => "LEFT JOIN TCNMPdtGrp_L PGL WITH (NOLOCK)     ON PGL.FTPgpChain = PDT.FTPgpChain", //หากลุ่มสินค้า
            6 => "LEFT JOIN TCNMPdtType_L PTL WITH (NOLOCK)    ON PDT.FTPtyCode = PTL.FTPtyCode   AND PTL.FNLngID = " . $this->db->escape($nLngID), //หาประเภทสินค้า
        );


        $aSpcWhereTableMaster =  array(
            1 => " AND ( PDT.FTPdtCode  COLLATE THAI_BIN    LIKE '%" . $this->db->escape_like_str($tSearch) . "%' ) ", //รหัสสินค้า
            2 => " AND ( UPPER(PDTL.FTPdtName)  COLLATE THAI_BIN    LIKE UPPER('%" . $this->db->escape_like_str($tSearch) . "%') ) ",  //หาชื่อสินค้า
            3 => " AND ( PBAR.FTBarCode  COLLATE THAI_BIN   LIKE '%" . $this->db->escape_like_str($tSearch) . "%' ) ",  //หาบาร์โค้ด
            4 => " AND ( PUNL.FTPunName  COLLATE THAI_BIN   LIKE '%" . $this->db->escape_like_str($tSearch) . "%' OR PUNL.FTPunCode COLLATE THAI_BIN LIKE '%" . $this->db->escape_like_str($tSearch) . "%' ) ", //หาหน่วย
            5 => " AND ( PGL.FTPgpName   COLLATE THAI_BIN   LIKE '%" . $this->db->escape_like_str($tSearch) . "%' OR PGL.FTPgpChainName COLLATE THAI_BIN LIKE '%" . $this->db->escape_like_str($tSearch) . "%' ) ", //หากลุ่มสินค้า
            6 => " AND ( PTL.FTPtyName   COLLATE THAI_BIN   LIKE '%" . $this->db->escape_like_str($tSearch) . "%' ) ", //หาประเภทสินค้า
        );

        $tSQLPdtMaster = "";
        if (!empty($tSearch)) {
            $tSQLPdtMaster .= $aSpcJoinTableMaster[$nSearchProductType];
        }
        $tSQLPdtMaster .= " WHERE PDT.FTPdtCode <> ''
                            " . $tPdtForSysLine1 . "
                            " . $tWHEREPermission_BCH . "
                            " . $tWHEREPermission_SHP . "
                            ";
        if (!empty($tSearch)) {
            $tSQLPdtMaster .= $aSpcWhereTableMaster[$nSearchProductType];
        }

        $tSQL = "SELECT PDT.*, 
        PDTL.FTPdtName, 
        PUNL.FTPunCode, 
        PUNL.FTPunName, 
        PBAR.FTBarCode, 
        PGL.FTPgpName, 
        PTL.FTPtyName, 
        PIMG.FTImgObj
 FROM
 (
    
             SELECT 
        TOP ". get_cookie('nShowRecordInPageList')." 
                    PDT.FTPdtForSystem, 
                    PDT.FTPdtCode, 
                    PDT.FTPtyCode, 
                    PDT.FTPgpChain, 
                    PDT.FTCreateBy, 
                    PDT.FDCreateOn
             FROM TCNMPdt PDT WITH(NOLOCK)
                  LEFT JOIN TCNMPdtSpcBch PDLSPC WITH(NOLOCK) ON PDT.FTPdtCode = PDLSPC.FTPdtCode
                   $tSQLPdtMaster ORDER BY
                   PDT.FDCreateOn DESC
 
 
 ) PDT
 INNER JOIN TCNMPdtPackSize PPCZ WITH(NOLOCK) ON PDT.FTPdtCode = PPCZ.FTPdtCode
 LEFT JOIN TCNMPdt_L PDTL WITH(NOLOCK) ON PDT.FTPdtCode = PDTL.FTPdtCode
                                          AND PDTL.FNLngID = " . $this->db->escape($nLngID) . "
 LEFT JOIN TCNMPdtBar PBAR WITH(NOLOCK) ON PDT.FTPdtCode = PBAR.FTPdtCode
                                           AND PPCZ.FTPunCode = PBAR.FTPunCode
 LEFT JOIN TCNMImgPdt PIMG WITH(NOLOCK) ON PDT.FTPdtCode = PIMG.FTImgRefID
                                           AND PIMG.FTImgTable = 'TCNMPdt'
                                           AND PIMG.FNImgSeq = 1
 LEFT JOIN TCNMPdtType_L PTL WITH(NOLOCK) ON PDT.FTPtyCode = PTL.FTPtyCode
                                             AND PTL.FNLngID = " . $this->db->escape($nLngID) . "
 LEFT JOIN TCNMPdtUnit_L PUNL WITH(NOLOCK) ON PPCZ.FTPunCode = PUNL.FTPunCode
                                              AND PUNL.FNLngID = " . $this->db->escape($nLngID) . "
 LEFT JOIN TCNMPdtGrp_L PGL WITH(NOLOCK) ON PGL.FTPgpChain = PDT.FTPgpChain 
 
 ";

        $tSQL .= "ORDER BY  PDT.FDCreateOn DESC";
        // print_r($tSQL);
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList  = $oQuery->result_array();
            // if ($paData['nPage'] == 1) {
            //     $nFoundRow  = '';
            // } else {
            //     $nFoundRow  = $paData['nPagePDTAll'];
            // }

            // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            // $nPageAll       = ceil($nFoundRow / $paData['nRow']);
            $aDataReturn    = array(
                'raItems'       => $aList,
                // 'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                // 'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        return $aDataReturn;
    }

    // Functionality : All Page Of Product
    // Parameters : function parameters
    // Creator :  31/01/2019 wasin(Yoshi) - 08/06/2020 wat
    // Last Modified : -
    // Return : data
    // Return Type : Array
    public function FSaMPDTGetPageAll($ptSQlData)
    {
        $oQueryNumrows      = $this->db->query($ptSQlData)->num_rows();
        if ($oQueryNumrows > 0) {
            $aDataReturn    =  array(
                'rtCountData'   => $oQueryNumrows,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        return $aDataReturn;
    }

    // Functionality : Select Data Event Not Sale
    // Parameters : function parameters
    // Creator : 07/02/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data Query For Database
    // Return Type : Array
    public function FSaMPDTEvnNotSaleByID($paData)
    {
        $tEvnCode   = $paData['FTEvnCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " 
            SELECT
                PNSE.FTEvnCode,
                PNSE.FNEvnSeqNo,
                PNSE.FTEvnType,
                PNSE.FTEvnStaAllDay,
                PNSE.FDEvnDStart,
                PNSE.FTEvnTStart,
                PNSE.FDEvnDFinish,
                PNSE.FTEvnTFinish,
                PNSE_L.FTEvnName
            FROM [TCNMPdtNoSleByEvn]  PNSE WITH (NOLOCK)
            LEFT JOIN TCNMPdtNoSleByEvn_L PNSE_L WITH (NOLOCK) ON PNSE.FTEvnCode = PNSE_L.FTEvnCode AND PNSE_L.FNLngID = " . $this->db->escape($nLngID) . "
            WHERE PNSE.FTEvnCode    = " . $this->db->escape($tEvnCode) . "
            ORDER BY PNSE.FNEvnSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
            $aResult = array(
                'raItems'    => $aDetail,
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

    // Functionality : Select Data Product Set
    // Parameters : function parameters
    // Creator : 07/02/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data Query For Database
    // Return Type : Array
    public function FSaMPDTGetDataPdtSet($paData)
    {
        $tSQL_Config    = " SELECT FTSysStaUsrValue FROM TsysConfig WHERE FTSysCode='tCN_Cost' AND FTSysSeq='1' ";
        $oQuery_Config  = $this->db->query($tSQL_Config);
        $aDataConfig    = $oQuery_Config->result_array();
        $tSQL   = "
            SELECT
                PSET.FTPdtCodeSet,
                PSET.FCPstQty,
                PDT_L.FTPdtName,
                PSET.FTPunCode ,
                PIMG.FTImgObj,
                PUN_L.FTPunName,
                PDT.FTPdtForSystem
            FROM TCNTPdtSet PSET WITH(NOLOCK)
            LEFT JOIN TCNMPdt PDT WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT.FTPdtCode
            LEFT JOIN TCNMPdt_L PDT_L WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT_L.FTPdtCode   AND PDT_L.FNLngID   = " . $this->db->escape($paData['FNLngID']) . "
            LEFT JOIN TCNMImgPdt PIMG  WITH (NOLOCK) ON PSET.FTPdtCodeSet = PIMG.FTImgRefID AND PIMG.FTImgTable = 'TCNMPdt' AND PIMG.FNImgSeq = 1
            LEFT JOIN TCNMPdtUnit_L PUN_L WITH(NOLOCK) ON PSET.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID    = " . $this->db->escape($paData['FNLngID']) . "
        ";
        if (isset($paData['FTPdtCode'])) {
            $tSQL   .= " WHERE PSET.FTPdtCode = " . $this->db->escape($paData['FTPdtCode']) . " ";
        }
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

    // Functionality : Select Data Product Set
    // Parameters : function parameters
    // Creator : 07/02/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data Query For Database
    // Return Type : Array
    public function FSaMPDTGetDataPdtSetTmp($paData)
    {
        $tTable         = "TCNTPdtSet_Tmp" . date("dmY");
        $tSQL_Config    = "SELECT FTSysStaUsrValue FROM TsysConfig WHERE FTSysCode='tCN_Cost' AND FTSysSeq='1'";
        $oQuery_Config  = $this->db->query($tSQL_Config);
        $aDataConfig    = $oQuery_Config->result_array();
        $tsession       = $this->session->userdata('tSesSessionID');
        $tSQL   = "
            SELECT
                PSET.FTPdtCodeSet,
                PSET.FCPstQty,
                PDT_L.FTPdtName,
                PSET.FTPunCode ,
                PIMG.FTImgObj,
                PUN_L.FTPunName,
                PDT.FTPdtForSystem
            FROM $tTable PSET WITH(NOLOCK)
            LEFT JOIN TCNMPdt PDT   WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT.FTPdtCode
            LEFT JOIN TCNMPdt_L PDT_L WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT_L.FTPdtCode   AND PDT_L.FNLngID   = " . $this->db->escape($paData['FNLngID']) . "
            LEFT JOIN TCNMImgPdt PIMG  WITH (NOLOCK) ON PSET.FTPdtCodeSet = PIMG.FTImgRefID AND PIMG.FTImgTable = 'TCNMPdt' AND PIMG.FNImgSeq = 1
            LEFT JOIN TCNMPdtUnit_L PUN_L WITH(NOLOCK) ON PSET.FTPunCode = PUN_L.FTPunCode  AND PUN_L.FNLngID   = " . $this->db->escape($paData['FNLngID']) . " 
            WHERE FTSessionID   = " . $this->db->escape($tsession) . "
        ";
        if (isset($paData['FTPdtCode'])) {
            $tSQL   .= " AND PSET.FTPdtCode = " . $this->db->escape($paData['FTPdtCode']) . " ";
        }
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

    // Functionality : Select Data Product Unit
    // Parameters : function parameters
    // Creator : 08/02/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data Query For Database
    // Return Type : Array
    public function FSaMPDTGetDataPdtUnit($paData)
    {
        $FTPunCode  = $paData['FTPunCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " 
            SELECT
                PUN.FTPunCode,
                PUN_L.FTPunName
            FROM TCNMPdtUnit PUN WITH (NOLOCK)
            LEFT JOIN TCNMPdtUnit_L PUN_L WITH (NOLOCK) ON  PUN.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = " . $this->db->escape($nLngID) . "
            WHERE PUN.FDCreateOn <> '' AND PUN.FTPunCode = " . $this->db->escape($FTPunCode) . "
        ";
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->row_array();
            $aResult = array(
                'raItems'    => $aDetail,
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

    public function FSaMPDTGetDataMasTemp($paData)
    {
        $nLngID = $paData['FNLngID'];
        $tSQL   = " 
            SELECT
                MTT.FTPdtCode,
                MTT.FTPunCode,
                MTT.FTPunName,
                MTT.FCPdtUnitFact,
                MTT.FTPdtGrade,
                MTT.FCPdtWeight,
                MTT.FTClrCode,
                MTT.FTClrName,
                MTT.FTPszCode,
                MTT.FTPszName,
                MTT.FTPdtUnitDim,
                MTT.FTPdtPkgDim,
                MTT.FTPdtStaAlwPick,
                MTT.FTPdtStaAlwPoHQ,
                MTT.FTPdtStaAlwBuy,
                MTT.FTPdtStaAlwSale,
                (	SELECT TOP 1 P4PDT.FCPgdPriceRet
                    FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                    WHERE 1=1
                    AND ((CONVERT(VARCHAR(19),GETDATE(),121) >= CONVERT(VARCHAR(19),P4PDT.FDPghDStart,121)) AND (CONVERT(VARCHAR(19),GETDATE(),121) <= CONVERT(VARCHAR(19),P4PDT.FDPghDStop,121)))
                    AND P4PDT.FTPghDocType = 1 AND P4PDT.FTPdtCode = MTT.FTPdtCode AND P4PDT.FTPunCode = MTT.FTPunCode
                    ORDER BY P4PDT.FDPghDStart DESC
                ) FCPgdPriceRet,
                (SELECT COUNT(BAR.FTBarCode) FROM TsysMasTmp BAR WITH (NOLOCK) WHERE BAR.FTMttTableKey=MTT.FTMttTableKey AND BAR.FTMttRefKey = 'TCNMPdtBar' AND BAR.FTPdtCode=MTT.FTPdtCode AND BAR.FTPunCode=MTT.FTPunCode AND BAR.FTMttSessionID=MTT.FTMttSessionID) AS nCountBarCode
            FROM TsysMasTmp MTT WITH (NOLOCK)
            WHERE MTT.FDCreateOn <> '' 
        ";
        if (isset($paData['FTMttTableKey'])) {
            $FTMttTableKey  = $paData['FTMttTableKey'];
            $tSQL   .= " AND FTMttTableKey  = " . $this->db->escape($FTMttTableKey) . " ";
        }
        if (isset($paData['FTMttRefKey'])) {
            $FTMttRefKey = $paData['FTMttRefKey'];
            $tSQL   .= " AND FTMttRefKey    = " . $this->db->escape($FTMttRefKey) . " ";
        }
        if (isset($paData['FTPdtCode'])) {
            $FTPdtCode = $paData['FTPdtCode'];
            $tSQL   .= " AND FTPdtCode      = " . $this->db->escape($FTPdtCode) . " ";
        }
        if (isset($paData['FTMttSessionID'])) {
            $FTMttSessionID = $paData['FTMttSessionID'];
            $tSQL   .= " AND FTMttSessionID = " . $this->db->escape($FTMttSessionID) . " ";
        }
        $tSQL  .= " ORDER BY MTT.FCPdtUnitFact ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->result_array();
            $aResult    = array(
                'raItems'    => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTGetDataUnitMasTemp($paData)
    {
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " 
            SELECT
                MTT.FTPdtCode,
                MTT.FTPunCode,
                UNL.FTPunName,
                MTT.FCPdtUnitFact,
                MTT.FTPdtGrade,
                MTT.FCPdtWeight,
                MTT.FTClrCode,
                CLL.FTClrName,
                MTT.FTPszCode,
                PRZ.FTPszName,
                MTT.FTPdtStaAlwPick,
                MTT.FTPdtStaAlwPoHQ,
                MTT.FTPdtStaAlwBuy,
                MTT.FTPdtStaAlwPoSPL,
                MTT.FTPdtStaAlwSale,
                MTT.FTPdtPkgDim,
                MTT.FTPdtUnitDim
            FROM TCNMPdtPackSizeTmp MTT WITH (NOLOCK)
            LEFT JOIN TCNMPdtColor_L CLL WITH (NOLOCK) ON MTT.FTClrCode = CLL.FTClrCode AND CLL.FNLngID = " . $this->db->escape($nLngID) . "
            LEFT JOIN TCNMPdtUnit_L UNL WITH (NOLOCK) ON MTT.FTPunCode = UNL.FTPunCode AND UNL.FNLngID  = " . $this->db->escape($nLngID) . "
            LEFT JOIN TCNMPdtSize_L PRZ WITH (NOLOCK) ON MTT.FTPszCode = PRZ.FTPszCode AND PRZ.FNLngID  = " . $this->db->escape($nLngID) . "
            WHERE MTT.FDCreateOn <> ''
        ";
        if (isset($paData['FTPdtCode'])) {
            $FTPdtCode   = $paData['FTPdtCode'];
            $tSQL .= " AND FTPdtCode = " . $this->db->escape($FTPdtCode) . " ";
        }
        if (isset($paData['FTMttSessionID'])) {
            $FTMttSessionID  = $paData['FTMttSessionID'];
            $tSQL .= " AND FTSessionID = " . $this->db->escape($FTMttSessionID) . " ";
        }
        $tSQL .= " ORDER BY MTT.FCPdtUnitFact ASC ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $aDetail,
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


    public function FSaMPDTGetDataBarcodeMasTemp($paData)
    {
        $FTPdtCode  = $paData['FTPdtCode'];
        $FTPunCode  = $paData['FTPunCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " 
            SELECT
                BAR.FTPdtCode,
                BAR.FTBarCode,
                BAR.FNBarRefSeq,
                BAR.FTPunCode,
                UNL.FTPunName,
                BAR.FTBarStaUse,
                BAR.FTBarStaAlwSale,
                BAR.FTBarStaByGen,
                BAR.FTPlcCode,
                PLL.FTPlcName,
                BAR.FNPldSeq
            FROM TCNMPdtBarTmp BAR WITH (NOLOCK)
            LEFT JOIN TCNMPdtUnit_L UNL WITH (NOLOCK) ON BAR.FTPunCode = UNL.FTPunCode AND UNL.FNLngID = '$nLngID'
            LEFT JOIN TCNMPdtLoc_L  PLL WITH (NOLOCK) ON BAR.FTPlcCode = PLL.FTPlcCode AND UNL.FNLngID = '$nLngID'
            WHERE BAR.FTPunCode = '$FTPunCode'
        ";
        if (isset($paData['FTPdtCode'])) {
            $FTPdtCode = $paData['FTPdtCode'];
            $tSQL  .= " AND BAR.FTPdtCode = '$FTPdtCode'";
        }
        if (isset($paData['FTMttSessionID'])) {
            $FTMttSessionID = $paData['FTMttSessionID'];
            $tSQL  .= " AND BAR.FTSessionID = '$FTMttSessionID'";
        }
        $tSQL   .= " ORDER BY BAR.FTBarCode ASC ";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }


    public function FSaMPDTGetDataSupplierMasTemp($paData)
    {
        //Show ทศนิยม 2 ตำแหน่ง
        // $nDecimalShow =  FCNxHGetOptionDecimalShow();
        $FTPdtCode  = $paData['FTPdtCode'];
        $FTPunCode  = $paData['FTPunCode'];
        $FTBarCode  = $paData['FTBarCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                            SPL.FTPdtCode,
                            SPL.FTBarCode,
                            SPL.FTSplCode,
                            SPLL.FTSplName,
                            SPL.FTUsrCode,
                            USRL.FTUsrName,
                            SPL.FTPdtStaAlwOrdSun,
                            SPL.FTPdtStaAlwOrdMon,
                            SPL.FTPdtStaAlwOrdTue,
                            SPL.FTPdtStaAlwOrdWed,
                            SPL.FTPdtStaAlwOrdThu,
                            SPL.FTPdtStaAlwOrdFri,
                            SPL.FTPdtStaAlwOrdSat
                        FROM TCNMPdtSplTmp SPL WITH (NOLOCK)
                        LEFT JOIN TCNMSpl_L SPLL WITH (NOLOCK) ON SPLL.FTSplCode = SPL.FTSplCode AND SPLL.FNLngID = '$nLngID'
                        LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON USRL.FTUsrCode = SPL.FTUsrCode AND SPLL.FNLngID = '$nLngID'
                        WHERE SPL.FTBarCode = '$FTBarCode' ";

        if (isset($paData['FTPdtCode'])) {
            $FTPdtCode = $paData['FTPdtCode'];
            $tSQL  .= " AND SPL.FTPdtCode = '$FTPdtCode'";
        }
        if (isset($paData['FTMttSessionID'])) {
            $FTMttSessionID = $paData['FTMttSessionID'];
            $tSQL  .= " AND SPL.FTSessionID = '$FTMttSessionID'";
        }

        $tSQL .= " ORDER BY SPL.FTSplCode ASC ";

        $oQuery     = $this->db->query($tSQL);
        // echo $this->db->last_query();
        // exit;
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->result_array();
            $aResult = array(
                'raItems'    => $aDetail,
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



    public function FSaMPDTGetPunData($paData)
    {
        //Show ทศนิยม 2 ตำแหน่ง
        // $nDecimalShow =  FCNxHGetOptionDecimalShow();
        $FTPdtCode  = $paData['FTPdtCode'];
        $FTPunCode  = $paData['FTPunCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                            PUN.FTPunCode,
                            PUN.FTPunName
                        FROM TCNMPdtUnit_L PUN WITH (NOLOCK)
                        WHERE PUN.FTPunCode = '$FTPunCode' AND PUN.FNLngID = '$nLngID' ";

        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->result_array();
            $aResult = array(
                'raItems'    => $aDetail,
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


    // Last Update : Napat(Jame) 09/06/2020
    public function FSaMPDTDelDataMasTemp($paPdtWhere)
    {
        $tDeleteBarCode = false;
        if ($paPdtWhere['DeleteType'] == 1) {
            //1 Delete Singal
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->where('FTPunCode', $paPdtWhere['FTPunCode']);
            $this->db->delete('TsysMasTmp');

            $tDeleteBarCode = true;
        } else if ($paPdtWhere['DeleteType'] == 2) {
            //2 Delete All Temp
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->delete('TsysMasTmp');

            $tDeleteBarCode = true;
        } else if ($paPdtWhere['DeleteType'] == 3) {
            //3 Delete All Pdt
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->delete('TsysMasTmp');

            $tDeleteBarCode = true;
        } else if ($paPdtWhere['DeleteType'] == 4) {
            //4 Delete Multi
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->where_in('FTPunCode', $paPdtWhere['FTPunCode']);
            $this->db->delete('TsysMasTmp');

            $tDeleteBarCode = true;
        }

        //ถ้าลบหน่วยสินค้า ต้องลบ barcode ที่ผูกกับหน่วยสินค้าด้วย
        if ($tDeleteBarCode == true) {
            if ($paPdtWhere['DeleteType'] != 2) {
                $this->db->where_in('FTPunCode', $paPdtWhere['FTPunCode']);
                $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            }
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->where('FTMttRefKey', 'TCNMPdtBar');
            $this->db->delete('TsysMasTmp');
        }
    }

    // Last Update : Off 10/11/2021
    public function FSaMPDTDelDataUnitMasTemp($paPdtWhere)
    {
        $tDeleteBarCode = false;
        if ($paPdtWhere['DeleteType'] == 1) {
            //1 Delete Singal
            $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->where('FTPunCode', $paPdtWhere['FTPunCode']);
            $this->db->delete('TCNMPdtPackSizeTmp');

            $tDeleteBarCode = true;
            $tDeleteSplCode = true;
        } else if ($paPdtWhere['DeleteType'] == 2) {
            //2 Delete All Temp
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->delete('TsysMasTmp');

            $tDeleteBarCode = true;
        } else if ($paPdtWhere['DeleteType'] == 3) {
            //3 Delete All Pdt
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->delete('TsysMasTmp');

            $tDeleteBarCode = true;
        } else if ($paPdtWhere['DeleteType'] == 4) {
            //4 Delete Multi
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->where_in('FTPunCode', $paPdtWhere['FTPunCode']);
            $this->db->delete('TsysMasTmp');

            $tDeleteBarCode = true;
        }

        //ถ้าลบหน่วยสินค้า ต้องลบ barcode ที่ผูกกับหน่วยสินค้าด้วย
        if ($tDeleteBarCode == true) {
            if ($paPdtWhere['DeleteType'] != 2) {
                $this->db->where_in('FTPunCode', $paPdtWhere['FTPunCode']);
                $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            }
            $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->delete('TCNMPdtBarTmp');
        }
    }

    // Del Spl On Delete Pun
    // Last Update : Off 10/11/2021
    public function FSaMPDTDelBarCodeWithPackFile($paPdtWhere)
    {
        $FTPdtCode = $paPdtWhere['FTPdtCode'];
        $FTPunCode = $paPdtWhere['FTPunCode'];
        $FTMttSessionID = $paPdtWhere['FTMttSessionID'];

        $this->db->where('FTPunCode', $FTPunCode);
        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->where('FTSessionID', $FTMttSessionID);
        $this->db->delete('TCNMPdtBarTmp');
    }

    // Del Spl On Delete Pun
    // Last Update : Off 10/11/2021
    public function FSaMPDTDelSplWithBarcode($paPdtWhere)
    {
        $FTPdtCode = $paPdtWhere['FTPdtCode'];
        $FTPunCode = $paPdtWhere['FTPunCode'];
        $FTMttSessionID = $paPdtWhere['FTMttSessionID'];
        $tSQL2 = "SELECT FTBarCode
                FROM TCNMPdtBarTmp
                WHERE FTPdtCode='$FTPdtCode'
                AND FTPunCode='$FTPunCode'
                AND FTSessionID='$FTMttSessionID'
                ";
        $oQuery2 = $this->db->query($tSQL2);
        $aDataQuery2 = $oQuery2->result_array();

        foreach ($aDataQuery2 as $aValue) {
            $this->db->where('FTBarCode', $aValue['FTBarCode']);
            $this->db->where('FTPdtCode', $FTPdtCode);
            $this->db->where('FTSessionID', $FTMttSessionID);
            $this->db->delete('TCNMPdtSplTmp');
        }
    }

    // Last Update : Off 10/11/2021
    public function FSaMPDTDelDataBarCodeMasTemp($paPdtWhere)
    {
        //ถ้าลบหน่วยสินค้า ต้องลบ barcode ที่ผูกกับหน่วยสินค้าด้วย
        $this->db->where('FTPunCode', $paPdtWhere['FTPunCode']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTBarCode', $paPdtWhere['FTBarCode']);
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->delete('TCNMPdtBarTmp');

        //ถ้าลบหน่วยBarcode ต้องลบ Spl ที่ผูกกับ BarCode ด้วย
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTBarCode', $paPdtWhere['FTBarCode']);
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->delete('TCNMPdtSplTmp');
    }

    // Last Update : Off 10/11/2021
    public function FSaMPDTDelDataSupplierMasTemp($paPdtWhere)
    {
        $this->db->where('FTSplCode', $paPdtWhere['FTSplCode']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTBarCode', $paPdtWhere['FTBarCode']);
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->delete('TCNMPdtSplTmp');
    }


    //ตัวอย่าง
    public function FSaMPDTInsertPackSizeMasTemp($paData)
    {
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];

        $tSQL           = "INSERT INTO TCNMPdtPackSizeTmp (FTSessionID,FTPdtCode,FTPunCode,FCPdtUnitFact,FTPdtGrade,FCPdtWeight,FTClrCode,FTPszCode,FTPdtUnitDim,FTPdtPkgDim,FTPdtStaAlwPick,FTPdtStaAlwPoHQ,FTPdtStaAlwBuy,FTPdtStaAlwSale,FTPdtStaAlwPoSPL,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                            SELECT
                                '$FTMttSessionID'    AS FTSessionID,
                                PKS.FTPdtCode,
                                UNI_L.FTPunCode,
                                PKS.FCPdtUnitFact,
                                PKS.FTPdtGrade,
                                PKS.FCPdtWeight,
                                PKS.FTClrCode,
                                PKS.FTPszCode,
                                PKS.FTPdtUnitDim,
                                PKS.FTPdtPkgDim,
                                PKS.FTPdtStaAlwPick,
                                PKS.FTPdtStaAlwPoHQ,
                                PKS.FTPdtStaAlwBuy,
                                PKS.FTPdtStaAlwSale,
                                PKS.FTPdtStaAlwPoSPL,
                                '$dDate' AS FDLastUpdOn,
                                '$dDate' AS FDCreateOn,
                                '$tUser' AS FTLastUpdBy,
                                '$tUser' AS FTCreateBy
                            FROM TCNMPdtPackSize PKS
                            LEFT JOIN TCNMPdtUnit_L  UNI_L ON UNI_L.FTPunCode = PKS.FTPunCode AND UNI_L.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtColor_L CLR_L ON CLR_L.FTClrCode = PKS.FTClrCode AND CLR_L.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtSize_L  PSZ_L ON PSZ_L.FTPszCode = PKS.FTPszCode AND PSZ_L.FNLngID = $nLngID
                            WHERE PKS.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
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

    //ตัวอย่าง
    public function FSaMPDTInsertUnitPackSizeMasTemp($paData)
    {
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];

        $tSQL           = "INSERT INTO TsysMasTmp (FTMttTableKey,FTMttRefKey,FTMttSessionID,FTPdtCode,FTPunCode,FTPunName,FCPdtUnitFact,FTPdtGrade,FCPdtWeight,FTClrCode,FTClrName,FTPszCode,FTPszName,FTPdtUnitDim,FTPdtPkgDim,FTPdtStaAlwPick,FTPdtStaAlwPoHQ,FTPdtStaAlwBuy,FTPdtStaAlwSale,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                            SELECT
                                '$FTMttTableKey' 	 AS FTMttTableKey,
                                '$FTMttRefKey'       AS FTMttRefKey,
                                '$FTMttSessionID'    AS FTMttSessionID,
                                PKS.FTPdtCode,
                                UNI_L.FTPunCode,
                                UNI_L.FTPunName,
                                PKS.FCPdtUnitFact,
                                PKS.FTPdtGrade,
                                PKS.FCPdtWeight,
                                PKS.FTClrCode,
                                CLR_L.FTClrName,
                                PKS.FTPszCode,
                                PSZ_L.FTPszName,
                                PKS.FTPdtUnitDim,
                                PKS.FTPdtPkgDim,
                                PKS.FTPdtStaAlwPick,
                                PKS.FTPdtStaAlwPoHQ,
                                PKS.FTPdtStaAlwBuy,
                                PKS.FTPdtStaAlwSale,
                                '$dDate' AS FDLastUpdOn,
                                '$dDate' AS FDCreateOn,
                                '$tUser' AS FTLastUpdBy,
                                '$tUser' AS FTCreateBy
                            FROM TCNMPdtPackSize PKS
                            LEFT JOIN TCNMPdtUnit_L  UNI_L ON UNI_L.FTPunCode = PKS.FTPunCode AND UNI_L.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtColor_L CLR_L ON CLR_L.FTClrCode = PKS.FTClrCode AND CLR_L.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtSize_L  PSZ_L ON PSZ_L.FTPszCode = PKS.FTPszCode AND PSZ_L.FNLngID = $nLngID
                            WHERE PKS.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
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

    public function FSaMPDTInsertBarCodeMasTemp($paData)
    {
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];
        $tSQL           = "INSERT INTO TsysMasTmp (FTMttTableKey,FTMttRefKey,FTMttSessionID,FTPdtCode,FTBarCode,FTPunCode,FTPlcCode,FTPlcName,FTSplCode,FTSplName,FTSplStaAlwPO,FTBarStaUse,FTBarStaAlwSale,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                            SELECT
                                    '$FTMttTableKey'    AS FTMttTableKey,
                                    '$FTMttRefKey'      AS FTMttRefKey,
                                    '$FTMttSessionID'   AS FTMttSessionID,
                                    PBAR.FTPdtCode,
                                    PBAR.FTBarCode,
                                    PPSZ.FTPunCode,
                                    PLCL.FTPlcCode,
                                    PLCL.FTPlcName,
                                    SPL_L.FTSplCode,
                                    SPL_L.FTSplName,
                                    SPL.FTSplStaAlwPO,
                                    PBAR.FTBarStaUse,
                                    PBAR.FTBarStaAlwSale,
                                    '$dDate' AS FDLastUpdOn,
                                    '$dDate' AS FDCreateOn,
                                    '$tUser' AS FTLastUpdBy,
                                    '$tUser' AS FTCreateBy
                            FROM TCNMPdtPackSize PPSZ
                            LEFT JOIN TCNMPdtBar PBAR 	    ON PPSZ.FTPdtCode = PBAR.FTPdtCode AND PBAR.FTPunCode = PPSZ.FTPunCode
                            LEFT JOIN TCNMPdtLoc_L PLCL    ON PBAR.FTPlcCode = PLCL.FTPlcCode AND PLCL.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtSpl SPL        ON SPL.FTPdtCode = PPSZ.FTPdtCode AND SPL.FTBarCode = PBAR.FTBarCode
                            LEFT JOIN TCNMSpl_L SPL_L       ON SPL_L.FTSplCode = SPL.FTSplCode AND SPL_L.FNLngID = $nLngID
                            WHERE PPSZ.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
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

    public function FSaMPDTInsertUnitBarCodeMasTemp($paData)
    {
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];
        $tSQL           = "INSERT INTO TCNMPdtBarTmp (FTSessionID,FTPdtCode,FTBarCode,FNBarRefSeq,FTFhnRefCode,FTPunCode,FTBarStaUse,FTBarStaAlwSale,FTBarStaByGen,FTPlcCode,FNPldSeq,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                            SELECT
                                    '$FTMttSessionID'   AS FTMttSessionID,
                                    PBAR.FTPdtCode,
                                    PBAR.FTBarCode,
                                    PBAR.FNBarRefSeq,
                                    PBAR.FTFhnRefCode,
                                    PBAR.FTPunCode,
                                    PBAR.FTBarStaUse,
                                    PBAR.FTBarStaAlwSale,
                                    PBAR.FTBarStaByGen,
                                    PBAR.FTPlcCode,
                                    PBAR.FNPldSeq,
                                    '$dDate' AS FDLastUpdOn,
                                    '$dDate' AS FDCreateOn,
                                    '$tUser' AS FTLastUpdBy,
                                    '$tUser' AS FTCreateBy
                            FROM TCNMPdtBar PBAR
                            WHERE PBAR.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
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


    public function FSaMPDTInsertUnitSupplierMasTemp($paData)
    {
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];
        $tSQL           = "INSERT INTO TCNMPdtSplTmp (FTSessionID,FTPdtCode,FTBarCode,FTSplCode,FTUsrCode,FTPdtStaAlwOrdSun,FTPdtStaAlwOrdMon,FTPdtStaAlwOrdTue,FTPdtStaAlwOrdWed,FTPdtStaAlwOrdThu,FTPdtStaAlwOrdFri,FTPdtStaAlwOrdSat,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                            SELECT
                                    '$FTMttSessionID'   AS FTMttSessionID,
                                    PBAR.FTPdtCode,
                                    PBAR.FTBarCode,
                                    PBAR.FTSplCode,
                                    PBAR.FTUsrCode,
                                    PBAR.FTPdtStaAlwOrdSun,
                                    PBAR.FTPdtStaAlwOrdMon,
                                    PBAR.FTPdtStaAlwOrdTue,
                                    PBAR.FTPdtStaAlwOrdWed,
                                    PBAR.FTPdtStaAlwOrdThu,
                                    PBAR.FTPdtStaAlwOrdFri,
                                    PBAR.FTPdtStaAlwOrdSat,
                                    '$dDate' AS FDLastUpdOn,
                                    '$dDate' AS FDCreateOn,
                                    '$tUser' AS FTLastUpdBy,
                                    '$tUser' AS FTCreateBy
                            FROM TCNMPdtSpl PBAR
                            WHERE PBAR.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
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


    // Functionality: Chack Barcode Duplicate in DB
    // Parameters: Data BarCode
    // Creator: 12/02/2018 wasin
    // Return:
    // ReturnType:  Array
    public function FSaMStaChkBarcode($ptPdtCode, $ptBarCode)
    {
        $tSQL   = " SELECT
                        COUNT(PBAR.FTBarCode) AS Counts
                    FROM TCNMPdtBar PBAR WITH (NOLOCK)
                    WHERE 1=1 AND PBAR.FTPdtCode = '$ptPdtCode' AND PBAR.FTBarCode = '$ptBarCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->row_array();
            $aResult    =  array(
                'raItems'   => $aDetail,
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

    // Functionality : Check Product Duplicate In DB
    // Parameters : function parameters
    // Creator : 18/02/2019 Wasin(Yoshi)
    // Return : Data Check Product Duplicate
    // Return Type : Array
    public function FSaMPDTCheckDuplicate($ptPdtCode)
    {
        $tSQL   =   "SELECT COUNT(PDT.FTPdtCode) AS counts
					 FROM TCNMPdt PDT WITH (NOLOCK)
                     WHERE PDT.FTPdtCode = '$ptPdtCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->row_array();
            $aResult    =  array(
                'rnCountPdt'    => $aDataQuery['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality : Function Add Product
    // Parameters : function parameters
    // Creator : 18/02/2019 Wasin(Yoshi)
    // Return : Array Status Add Product (TCNMPdt)
    // Return Type : array
    public function FSaMPDTAddUpdateMaster($paPdtWhere, $paPdtData)
    {
        // Delete Spc Bch
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete('TCNMPdtSpcBch');

        // Update TCNMPdt
        $aDataUpdate    = array_merge($paPdtData, array(
            'FDLastUpdOn'   => date('Y-m-d H:i:s'),
            'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
        ));
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->update('TCNMPdt', $aDataUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            // Add TCNMPdt
            $aDataInsert = array_merge($paPdtWhere, $paPdtData, array(
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername')
            ));

            $this->db->insert('TCNMPdt', $aDataInsert);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Success',
                );
            } else {
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product.',
                );
            }
        }
        return $aStatus;
    }

    // Functionality : Function Add TSVMPdtCar 
    // Parameters : function parameters
    // Creator : 28/06/2021 Off
    // Return : Array Status Add TSVMPdtCar (TSVMPdtCar)
    // Return Type : array
    public function FSaMPDTAddUpdateCar($paPdtWhere, $paPdtData)
    {

        // Update TSVMPdtCar
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->update('TSVMPdtCar', $paPdtData);

        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            // Add TCNMPdt
            $aDataInsert = array_merge($paPdtWhere, $paPdtData);

            $this->db->insert('TSVMPdtCar', $aDataInsert);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Success',
                );
            } else {
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product.',
                );
            }
        }
        return $aStatus;
    }

    // Functionality : Function Add Product Lang
    // Parameters : function parameters
    // Creator : 18/02/2019 Wasin(Yoshi)
    // Return : Array Status Add Product (TCNMPdt_L)
    // Return Type : array
    public function FSaMPDTAddUpdateLang($paPdtWhere, $paPdtDataLang)
    {
        $this->db->where('FNLngID', $paPdtWhere['FNLngID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->update('TCNMPdt_L', $paPdtDataLang);

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Lang Success.',
            );
        } else {
            $aDataInsertLang    = array_merge($paPdtWhere, $paPdtDataLang);
            $this->db->insert('TCNMPdt_L', $aDataInsertLang);
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Lang Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product Lang.',
                );
            }
        }
        return $aStatus;
    }

    // Functionality: Functio Add/Update PackSize
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update PackSize
    // ReturnType: Array
    public function FSxMPDTAddUpdatePackSize($paPdtWhere, $paPackSizeWhere)
    {
        $FTPdtCode        = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey    = $paPackSizeWhere['FTMttTableKey'];
        $FTMttRefKey      = $paPackSizeWhere['FTMttRefKey'];
        $FTMttSessionID   = $paPackSizeWhere['FTMttSessionID'];
        // Delete Pack Size
        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtPackSize');

        $tSQL = "INSERT INTO TCNMPdtPackSize (FTPdtCode,
                    FTPunCode,
                    FCPdtUnitFact,
                    FTPdtGrade,
                    FCPdtWeight,
                    FTClrCode,
                    FTPszCode,
                    FTPdtUnitDim,
                    FTPdtPkgDim,
                    FTPdtStaAlwPick,
                    FTPdtStaAlwPoHQ,
                    FTPdtStaAlwBuy,
                    FTPdtStaAlwSale,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy)
                SELECT
                    FTPdtCode,
                    FTPunCode,
                    FCPdtUnitFact,
                    FTPdtGrade,
                    FCPdtWeight,
                    FTClrCode,
                    FTPszCode,
                    FTPdtUnitDim,
                    FTPdtPkgDim,
                    FTPdtStaAlwPick,
                    FTPdtStaAlwPoHQ,
                    FTPdtStaAlwBuy,
                    FTPdtStaAlwSale,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy
                FROM TsysMasTmp
                WHERE FTPdtCode='$FTPdtCode' AND FTMttTableKey='$FTMttTableKey' AND FTMttRefKey='$FTMttRefKey' AND FTMttSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        // print_r($tSQL);
        // exit;
        if ($oQuery > 0) {
            $aResult    =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Functio Add/Update PackSize
    // Parameters: function parameters
    // Creator:  10/11/2021 Off
    // Return: Status Add Update PackSize
    // ReturnType: Array
    public function FSxMPDTAddUpdateUnitPackSize($paPdtWhere, $paPackSizeWhere)
    {
        $FTPdtCode        = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey    = $paPackSizeWhere['FTMttTableKey'];
        $FTMttRefKey      = $paPackSizeWhere['FTMttRefKey'];
        $FTMttSessionID   = $paPackSizeWhere['FTMttSessionID'];
        // Delete Pack Size
        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtPackSize');

        $tSQL = "INSERT INTO TCNMPdtPackSize (
                    FTPdtCode,
                    FTPunCode,
                    FCPdtUnitFact,
                    FTPdtGrade,
                    FCPdtWeight,
                    FTClrCode,
                    FTPszCode,
                    FTPdtUnitDim,
                    FTPdtPkgDim,
                    FTPdtStaAlwPick,
                    FTPdtStaAlwPoHQ,
                    FTPdtStaAlwBuy,
                    FTPdtStaAlwSale,
                    FTPdtStaAlwPoSPL,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy)
                SELECT
                    FTPdtCode,
                    FTPunCode,
                    FCPdtUnitFact,
                    FTPdtGrade,
                    FCPdtWeight,
                    FTClrCode,
                    FTPszCode,
                    FTPdtUnitDim,
                    FTPdtPkgDim,
                    FTPdtStaAlwPick,
                    FTPdtStaAlwPoHQ,
                    FTPdtStaAlwBuy,
                    FTPdtStaAlwSale,
                    FTPdtStaAlwPoSPL,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy
                FROM TCNMPdtPackSizeTmp
                WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        // print_r($tSQL);
        // exit;
        if ($oQuery > 0) {
            $aResult    =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Get Last Seq PlcCode In BarCode
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Array Last Seq
    // ReturnType: Array
    public function FSaMPDTGetPlcSeq($ptPdtCode, $ptPlcCode)
    {
        $tSQL   = " SELECT TOP 1 PBAR.FNPldSeq
                    FROM TCNMPdtBar PBAR
                    WHERE PBAR.FTPdtCode = '$ptPdtCode' AND PBAR.FTPlcCode = '$ptPlcCode'
                    ORDER BY PBAR.FNPldSeq DESC ";
        $oQuery = $this->db->query($tSQL);
        return $oQuery->row_array();
    }

    // Functionality: Functio Add/Update BarCode
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi) - Modifly: 26/06/2562 Napat(Jame)
    // Return: Status Add Update BarCode
    // ReturnType: Array
    public function FSxMPDTAddUpdateBarCode($paPdtWhere, $paDataBarCode)
    {
        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey      = $paDataBarCode['FTMttTableKey'];
        $FTMttRefKey        = $paDataBarCode['FTMttRefKey'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtBar');

        $tSQL = "INSERT INTO TCNMPdtBar (FTPdtCode,
                        FTBarCode,
                        FTPunCode,
                        FTBarStaUse,
                        FTBarStaAlwSale,
                        FTPlcCode,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy,
                        FNBarRefSeq)
                SELECT FTPdtCode,
                        FTBarCode,
                        FTPunCode,
                        FTBarStaUse,
                        FTBarStaAlwSale,
                        FTPlcCode,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy,
                        0 AS FNBarRefSeq
                FROM TsysMasTmp
                WHERE FTPdtCode='$FTPdtCode' AND FTMttTableKey='$FTMttTableKey' AND FTMttRefKey='$FTMttRefKey' AND FTMttSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        // print_r($tSQL);
        // exit;
        if ($oQuery > 0) {
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

    // Functionality: Functio Add/Update Pdt Loc
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi) - Modifly: 26/06/2562 Napat(Jame)
    // Return: Status Add Update BarCode
    // ReturnType: Array
    public function FSxMPDTAddUpdatePdtLoc($paPdtWhere, $paDataBarCode)
    {
        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNTPdtLocSeq');

        $tSQL = "INSERT INTO TCNTPdtLocSeq 
                        (FTPlcCode,
                        FTBarCode,
                        FTPdtCode,
                        FNPldSeq)
                SELECT FTPlcCode,
                        FTBarCode,
                        FTPdtCode,
                        ROW_NUMBER() OVER(ORDER BY FNPldSeq ASC) + (SELECT ISNULL(MAX(FNPldSeq), 0) FROM TCNTPdtLocSeq) AS FNPldSeq
                FROM TCNMPdtBarTmp
                WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID' 
                AND FTPlcCode != '' 
                ";
        $oQuery = $this->db->query($tSQL);


        $tSQL2 = "SELECT FTBarCode,
                        FTPdtCode,
                        FNPldSeq
                FROM TCNTPdtLocSeq
                WHERE FTPdtCode='$FTPdtCode'";
        $oQuery2 = $this->db->query($tSQL2);
        $aDataQuery2 = $oQuery2->result_array();

        foreach ($aDataQuery2 as $aValue) {
            $this->db->set('FNPldSeq', $aValue['FNPldSeq']);
            $this->db->where('FTBarCode', $aValue['FTBarCode']);
            $this->db->where('FTPdtCode', $aValue['FTPdtCode']);
            $this->db->where('FTSessionID', $FTMttSessionID);
            $this->db->update('TCNMPdtBarTmp');
        }

        if ($oQuery > 0) {
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

    // Functionality: Functio Add/Update BarCode
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi) - Modifly: 26/06/2562 Napat(Jame)
    // Return: Status Add Update BarCode
    // ReturnType: Array
    public function FSxMPDTAddUpdateBarCodeTmp($paPdtWhere, $paDataBarCode)
    {
        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey      = $paDataBarCode['FTMttTableKey'];
        $FTMttRefKey        = $paDataBarCode['FTMttRefKey'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtBar');

        $this->db->set('FTBarCode', $FTPdtCode);
        $this->db->where('FTBarCode', 'TMP');
        $this->db->where('FTSessionID', $FTMttSessionID);
        $this->db->update('TCNMPdtBarTmp');


        $tSQL = "INSERT INTO TCNMPdtBar 
                        (FTPdtCode,
                        FTBarCode,
                        FTPunCode,
                        FTBarStaUse,
                        FTBarStaAlwSale,
                        FTPlcCode,
                        FNPldSeq,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy,
                        FNBarRefSeq)
                SELECT FTPdtCode,
                        FTBarCode,
                        FTPunCode,
                        FTBarStaUse,
                        FTBarStaAlwSale,
                        FTPlcCode,
                        FNPldSeq,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy,
                        0 AS FNBarRefSeq
                FROM TCNMPdtBarTmp
                WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        // print_r($tSQL);
        // exit;
        if ($oQuery > 0) {
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

    public function FSaMPDTCheckBarCodeByID($paData)
    {
        $tSQL = "SELECT FTBarCode
            FROM TsysMasTmp
            WHERE 1=1
            AND FTPdtCode = '$paData[FTPdtCode]'
            AND FTBarCode = '$paData[FTBarCode]'
            AND FTMttSessionID =  '$paData[FTMttSessionID]'
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTCheckBarOldCodeByID($paData)
    {
        $tSQL = "SELECT FTBarCode
        FROM TsysMasTmp
        WHERE 1=1
        AND FTPdtCode = '$paData[FTPdtCode]'
        AND FTBarCode = '$paData[tOldBarCode]'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        return $aResult;
    }

    public function FSxMPDTAddUpdateBarCodeByID($paDataPackSize)
    {
        $FTMttTableKey          = $paDataPackSize['FTMttTableKey'];
        $FTMttRefKey            = $paDataPackSize['FTMttRefKey'];
        $FTPdtCode              = $paDataPackSize['FTPdtCode'];
        $FTBarCode              = $paDataPackSize['FTBarCode'];
        $tOldBarCode            = $paDataPackSize['tOldBarCode'];
        $FTPunCode              = $paDataPackSize['FTPunCode'];
        $FTSplCode              = $paDataPackSize['FTSplCode'];
        $FTSplName              = $paDataPackSize['FTSplName'];
        $FTPlcCode              = $paDataPackSize['FTPlcCode'];
        $FTPlcName              = $paDataPackSize['FTPlcName'];
        $FTBarStaUse            = $paDataPackSize['FTBarStaUse'];
        $FTBarStaAlwSale        = $paDataPackSize['FTBarStaAlwSale'];
        $FTSplStaAlwPO          = $paDataPackSize['FTSplStaAlwPO'];
        $FTMttSessionID         = $paDataPackSize['FTMttSessionID'];

        $tSQL       = "SELECT FTBarCode
                        FROM TsysMasTmp
                        WHERE FTMttTableKey         = '$FTMttTableKey'
                         AND FTMttRefKey            = '$FTMttRefKey'
                         AND FTPdtCode              = '$FTPdtCode'
                         AND FTBarCode              = '$tOldBarCode'
                         AND FTMttSessionID         = '$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataBarCode = array(
                'FTBarCode'         => $FTBarCode,
                'FTSplCode'         => $FTSplCode,
                'FTSplName'         => $FTSplName,
                'FTPlcCode'         => $FTPlcCode,
                'FTPlcName'         => $FTPlcName,
                'FTBarStaUse'       => $FTBarStaUse,
                'FTBarStaAlwSale'   => $FTBarStaAlwSale,
                'FTSplStaAlwPO'     => $FTSplStaAlwPO,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            );
            $this->db->where('FTMttTableKey', $FTMttTableKey);
            $this->db->where('FTMttRefKey', $FTMttRefKey);
            $this->db->where('FTPdtCode', $FTPdtCode);
            $this->db->where('FTPunCode', $FTPunCode);
            $this->db->where('FTBarCode', $tOldBarCode);
            $this->db->update('TsysMasTmp', $aDataBarCode);
        } else {
            $aDataBarCode   = array(
                'FTMttTableKey'     => $FTMttTableKey,
                'FTMttRefKey'       => $FTMttRefKey,
                'FTPdtCode'         => $FTPdtCode,
                'FTBarCode'         => $FTBarCode,
                'FTPunCode'         => $FTPunCode,
                'FTSplCode'         => $FTSplCode,
                'FTSplName'         => $FTSplName,
                'FTPlcCode'         => $FTPlcCode,
                'FTPlcName'         => $FTPlcName,
                'FTBarStaUse'       => $FTBarStaUse,
                'FTBarStaAlwSale'   => $FTBarStaAlwSale,
                'FTSplStaAlwPO'     => $FTSplStaAlwPO,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTMttSessionID'    => $FTMttSessionID
            );
            $this->db->insert('TsysMasTmp', $aDataBarCode);
        }


        $aResult    =  array(
            'rtCode'    => '1',
            'rtDesc'    => 'success'
        );
        return $aResult;
    }

    // Functionality: Functio Add/Update Supplier
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update Supplier
    // ReturnType: Array
    public function FSxMPDTAddUpdateSupplier($paPdtWhere, $paDataBarCode)
    {
        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey      = $paDataBarCode['FTMttTableKey'];
        $FTMttRefKey        = $paDataBarCode['FTMttRefKey'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        // Delete Supplier All
        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtSpl');

        $tSQL = "INSERT INTO TCNMPdtSpl (FTPdtCode,
                        FTBarCode,
                        FTSplCode,
                        FTSplStaAlwPO)
                SELECT FTPdtCode,
                        FTBarCode,
                        FTSplCode,
                        FTSplStaAlwPO
                FROM TsysMasTmp
                WHERE FTPdtCode='$FTPdtCode'
                AND FTMttTableKey='$FTMttTableKey'
                AND FTMttRefKey='$FTMttRefKey'
                AND FTMttSessionID='$FTMttSessionID'
                AND FTSplCode!=''";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery > 0) {
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

        // if(is_array($paDataPackSize) && !empty($paDataPackSize)){
        //     // Loop Data Pack Size
        //     foreach($paDataPackSize AS $nKey => $aPackSize){
        //        if(isset($aPackSize['oDataSupplier']) && !empty($aPackSize['oDataSupplier'])){
        //             foreach($aPackSize['oDataSupplier'] AS $nKey => $aSupplier){
        //                 $aDataSupllier  = array(
        //                     'FTPdtCode'     => $paPdtWhere['FTPdtCode'],
        //                     'FTBarCode'     => $aSupplier['tPdtBarCode'],
        //                     'FTSplCode'     => $aSupplier['tPdtSplCode'],
        //                     'FTSplStaAlwPO' => $aSupplier['tPdtStaAlwPO'],
        //                 );
        //                 $this->db->insert('TCNMPdtSpl',$aDataSupllier);
        //             }
        //        }
        //     }
        // }
        // return;
    }

    // Functionality: Functio Add/Update Supplier
    // Parameters: function parameters
    // Creator:  16/11/2021 Off
    // Return: Status Add Update Supplier
    // ReturnType: Array
    public function FSxMPDTAddUpdateSupplierTmp($paPdtWhere, $paDataBarCode)
    {
        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey      = $paDataBarCode['FTMttTableKey'];
        $FTMttRefKey        = $paDataBarCode['FTMttRefKey'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        // Delete Supplier All
        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtSpl');

        $tSQL = "INSERT INTO TCNMPdtSpl (FTPdtCode,
                        FTBarCode,
                        FTSplCode,
                        FTUsrCode,
                        FTPdtStaAlwOrdSun,
                        FTPdtStaAlwOrdMon,
                        FTPdtStaAlwOrdTue,
                        FTPdtStaAlwOrdWed,
                        FTPdtStaAlwOrdThu,
                        FTPdtStaAlwOrdFri,
                        FTPdtStaAlwOrdSat)
                SELECT FTPdtCode,
                        FTBarCode,
                        FTSplCode,
                        FTUsrCode,
                        FTPdtStaAlwOrdSun,
                        FTPdtStaAlwOrdMon,
                        FTPdtStaAlwOrdTue,
                        FTPdtStaAlwOrdWed,
                        FTPdtStaAlwOrdThu,
                        FTPdtStaAlwOrdFri,
                        FTPdtStaAlwOrdSat
                FROM TCNMPdtSplTmp
                WHERE FTPdtCode='$FTPdtCode'
                AND FTSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery > 0) {
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

    public function FSxMPDTAddUpdateSpcBch($aDataSpcBch)
    {
        $this->db->insert('TCNMPdtSpcBch', $aDataSpcBch);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Functionality: Functio Add/Update Product Set
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update Supplier
    // ReturnType: Array
    public function FSxMPDTAddUpdatePdtSet($paPdtWhere, $paPdtDataAllSet)
    {
        // Delete Product Set All
        $this->db->where_in('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete('TCNTPdtSet');

        if (is_array($paPdtDataAllSet) && !empty($paPdtDataAllSet)) {
            // Loop Data Pack Size
            foreach ($paPdtDataAllSet['oPdtCodeSetData'] as $nKey => $aDataPdtSet) {
                $aDataPdtSet    = array(
                    'FTPdtCode'     => $paPdtWhere['FTPdtCode'],
                    'FTPdtCodeSet'  => $aDataPdtSet['tPdtCodeSetCode'],
                    'FCPstQty'      => $aDataPdtSet['tPdtCodeSetQty'],
                    'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                    'FDCreateOn'    => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                    'FTCreateBy'    => $this->session->userdata('tSesUsername')
                );
                $this->db->insert('TCNTPdtSet', $aDataPdtSet);
            }
            $aDataStaUpdPdtSet = array(
                'FTPdtSetOrSN'      => 2,
                'FTPdtStaSetPri'    => $paPdtDataAllSet['tPdtStaSetPri'],
                'FTPdtStaSetShwDT'  => $paPdtDataAllSet['tPdtStaSetShwDT'],
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->update('TCNMPdt', $aDataStaUpdPdtSet);
        } else {
            $aDataStaUpdPdtSet = array(
                'FTPdtSetOrSN'      => 1,
                'FTPdtStaSetPri'    => NULL,
                'FTPdtStaSetShwDT'  => NULL,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->update('TCNMPdt', $aDataStaUpdPdtSet);
        }
        return;
    }

    // Functionality: Functio Add/Update Product Event Not Sale
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update Supplier
    // ReturnType: Array
    public function FSxMPDTAddUpdatePdtEvnNosale($paPdtWhere, $ptPdtEvnNotSale)
    {
        if (isset($ptPdtEvnNotSale) && !empty($ptPdtEvnNotSale)) {
            $aPdtEvnNoSale  =   array(
                'FTEvnCode'     => $ptPdtEvnNotSale,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->update('TCNMPdt', $aPdtEvnNoSale);
        } else {
            $aPdtEvnNoSale  =   array(
                'FTEvnCode'     => NULL,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->update('TCNMPdt', $aPdtEvnNoSale);
        }
        return;
    }


    //  ============================ Edit Modal ============================================================

    // Functionality: Func. Get Data Image By ID Product
    // Parameters: function parameters
    // Creator:  20/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataImgByID($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $tSQL       = " SELECT
                            IMGPDT.FNImgID,
                            IMGPDT.FTImgRefID,
                            IMGPDT.FNImgSeq,
                            IMGPDT.FTImgObj
                        FROM TCNMImgPdt IMGPDT
                        WHERE 1=1
                        AND IMGPDT.FTImgRefID   = '$tPdtCode'
                        AND IMGPDT.FTImgTable   = 'TCNMPdt'
                        AND IMGPDT.FTImgKey     = 'master'
                        ORDER BY IMGPDT.FNImgSeq ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    // Functionality: Func. Get Data Info By ID Product
    // Parameters: function parameters
    // Creator:  20/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataInfoByID($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            PDT.FTPdtCode,PDTL.FTPdtName,PDTL.FTPdtNameOth,PDTL.FTPdtNameABB,PDT.FTPdtStkControl,PDT.FTPdtGrpControl,PDT.FTPdtForSystem,
                            PDT.FCPdtQtyOrdBuy,PDT.FCPdtCostDef,PDT.FCPdtCostOth,PDT.FCPdtCostStd,PDT.FCPdtMax,PDT.FTPdtPoint,PDT.FCPdtPointTime,PDT.FTPdtType,
                            PDT.FTPdtSaleType,PDT.FTPdtSetOrSN,PDT.FTPdtStaAlwDis,PDT.FTPdtStaAlwReturn,PDT.FTPdtStaVatBuy,PDT.FTPdtStaVat,PDT.FTPdtStaActive,PDT.FTPdtStaAlwReCalOpt,
                            PDT.FTPdtStaCsm,PDT.FTTcgCode,TCGL.FTTcgName,PDT.FTPgpChain,PGPL.FTPgpChainName,
                            PDT.FTPtyCode,PTYL.FTPtyName,PDT.FTPbnCode,PBNL.FTPbnName,PDT.FTPmoCode,PMOL.FTPmoName,PDT.FTVatCode,VAT.FCVatRate,
                            PDT.FTPdtStaLot,
                            PDT.FTPdtStaAlwWHTax,
                            PDT.FTPdtStaAlwBook,
                            PDT.FTPdtStaSetPri,PDT.FTPdtStaSetShwDT, --Napat(Jame) 13/11/2019
                            PDT.FTPdtType,PDT.FTPdtSaleType, --Napat(Jame) 10/09/2019
                            SPC.FCPdtMin, --Napat(Jame) 17/09/2019 ย้ายจาก TCNMPdt ไป TCNMPdtSpcBch
                            FTPdtStaSetPrcStk, --Napat(Jame) 20/11/2020

                            BCHL.FTBchName,MERL.FTMerName,SHPL.FTShpName,
                            BCHL.FTBchCode,MERL.FTMerCode,SHPL.FTShpCode,
                            MPGL.FTMgpCode,MPGL.FTMgpName,
                            AGNL.FTAgnCode,AGNL.FTAgnName,
                            PDT.FTPdtCtrlRole,
							              ROLEL.FTRolName,
                            ROLEL.FTRolCode,

                            CONVERT(CHAR(10),PDT.FDPdtSaleStart,126)    AS FDPdtSaleStart,
                            CONVERT(CHAR(10),PDT.FDPdtSaleStop,126)     AS FDPdtSaleStop,
                            PDTL.FTPdtRmk
                        FROM TCNMPdt PDT
                        LEFT JOIN TCNMPdt_L         PDTL    ON PDT.FTPdtCode    = PDTL.FTPdtCode    AND PDTL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtTouchGrp_L TCGL    ON PDT.FTTcgCode    = TCGL.FTTcgCode    AND TCGL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtGrp_L      PGPL    ON PDT.FTPgpChain   = PGPL.FTPgpChain   AND PGPL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtType_L     PTYL    ON PDT.FTPtyCode    = PTYL.FTPtyCode    AND PTYL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtBrand_L    PBNL    ON PDT.FTPbnCode    = PBNL.FTPbnCode    AND PBNL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtModel_L    PMOL    ON PDT.FTPmoCode    = PMOL.FTPmoCode    AND PMOL.FNLngID = $nLngID
                        LEFT JOIN VCN_VatActive       VAT     ON PDT.FTVatCode    = VAT.FTVatCode
                        LEFT JOIN TCNMPdtSpcBch     SPC     ON SPC.FTPdtCode    = PDT.FTPdtCode
                        LEFT JOIN TCNMAgency_L      AGNL    ON SPC.FTAgnCode    = AGNL.FTAgnCode    AND AGNL.FNLngID = $nLngID
                        LEFT JOIN TCNMBranch_L      BCHL    ON SPC.FTBchCode    = BCHL.FTBchCode    AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TCNMMerchant_L    MERL    ON SPC.FTMerCode    = MERL.FTMerCode    AND MERL.FNLngID = $nLngID
                        LEFT JOIN TCNMShop_L        SHPL    ON SPC.FTShpCode    = SHPL.FTShpCode  AND  SPC.FTBchCode = SHPL.FTBchCode  AND SHPL.FNLngID = $nLngID
                        LEFT JOIN TCNMMerPdtGrp_L   MPGL    ON SPC.FTMgpCode    = MPGL.FTMgpCode    AND MPGL.FNLngID = $nLngID
                        LEFT JOIN TCNMUsrRole_L		ROLEL	ON PDT.FTPdtCtrlRole = ROLEL.FTRolCode AND ROLEL.FNLngID = $nLngID
                        WHERE 1=1 AND PDT.FTPdtCode = '$tPdtCode' ";
        // echo $tSQL;
        // exit();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->row_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    public function FSaMPDTGetDataRentalByID($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            RNT.FTPdtRentType,
                            RNT.FTPdtStaReqRet,
                            RNT.FTPdtStaPay,
                            RNT.FCPdtDeposit,
                            RNT.FTShpCode,
                            SHPL.FTShpName
                        FROM TRTMPdtRental RNT
                        LEFT JOIN TCNMShop_L SHPL ON RNT.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID = $nLngID
                        WHERE 1=1 AND RNT.FTPdtCode = '$tPdtCode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->row_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    // Functionality: Func. Get Data PackSize By ID Product
    // Parameters: function parameters
    // Creator:  21/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataPackSizeByID($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT DISTINCT
                            PPSZ.FTPdtCode,PPSZ.FTPunCode,PUNL.FTPunName,PPSZ.FCPdtUnitFact,PPSZ.FTPdtGrade,PPSZ.FCPdtWeight,PPSZ.FTClrCode,
                            PCLL.FTClrName,PPSZ.FTPszCode,PSZL.FTPszName,PPSZ.FTPdtUnitDim,PPSZ.FTPdtPkgDim,PPSZ.FTPdtStaAlwPick,
                            PPSZ.FTPdtStaAlwPoHQ,PPSZ.FTPdtStaAlwBuy,PPSZ.FTPdtStaAlwSale,
                            ISNULL(P4PDT.FCPgdPriceRet,0) AS FCPgdPriceRet,
                            --ISNULL(P4PDT.FCPgdPriceWhs,0) AS FCPgdPriceWhs,
                            --ISNULL(P4PDT.FCPgdPriceNet,0) AS FCPgdPriceNet
                        FROM TCNMPdtPackSize PPSZ
                        LEFT JOIN TCNMPdtUnit_L	PUNL        ON PPSZ.FTPunCode = PUNL.FTPunCode  AND PUNL.FNLngID	= $nLngID
                        LEFT JOIN TCNMPdtColor_L PCLL       ON PPSZ.FTClrCode = PCLL.FTClrCode  AND PCLL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtSize_L PSZL        ON PPSZ.FTPszCode = PSZL.FTPszCode  AND PSZL.FNLngID = $nLngID
                        LEFT JOIN TCNTPdtPrice4PDT P4PDT	ON PPSZ.FTPdtCode = P4PDT.FTPdtCode AND PPSZ.FTPunCode = P4PDT.FTPunCode
                        AND ((CONVERT(VARCHAR(19),GETDATE(),103) >= CONVERT(VARCHAR(19),P4PDT.FDPghDStart,103)) AND (CONVERT(VARCHAR(19),GETDATE(),103) <= CONVERT(VARCHAR(19),P4PDT.FDPghDStop,103)))
                        AND P4PDT.FTPghDocType = 1
                        WHERE 1=1
                        AND PPSZ.FTPdtCode = '$tPdtCode'
                        ORDER BY PPSZ.FTPunCode ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    // Functionality: Func. Get Data BarCode By ID Product
    // Parameters: function parameters
    // Creator:  21/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataBarCodeByID($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT DISTINCT
                            PBAR.FTPdtCode,
                            PBAR.FTBarCode,
                            PBAR.FTPunCode,
                            PBAR.FNPldSeq,
                            PBAR.FTPlcCode,
                            PLCL.FTPlcName,
                            PBAR.FTBarStaUse,
                            PBAR.FTBarStaAlwSale
                        FROM TCNMPdtPackSize PPSZ
                        LEFT JOIN TCNMPdtBar PBAR 	ON PPSZ.FTPdtCode = PBAR.FTPdtCode AND PPSZ.FTPunCode = PBAR.FTPunCode
                        LEFT JOIN TCNMPdtLoc_L PLCL	ON PBAR.FTPlcCode = PLCL.FTPlcCode AND PLCL.FNLngID = $nLngID
                        WHERE 1=1 AND PPSZ.FTPdtCode = '$tPdtCode'
                        ORDER BY PBAR.FTPunCode ASC,PBAR.FNPldSeq ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    // Functionality: Func. Get Data Supplier By ID Product
    // Parameters: function parameters
    // Creator:  21/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataSupplierByID($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            PPSZ.FTPdtCode,
                            PPSZ.FTPunCode,
                            PBAR.FTBarCode,
                            PSPL.FTSplCode,
                            SPLL.FTSplName,
                            PSPL.FTSplStaAlwPO
                        FROM TCNMPdtPackSize PPSZ
                        LEFT JOIN TCNMPdtBar PBAR ON PPSZ.FTPdtCode = PBAR.FTPdtCode AND PPSZ.FTPunCode = PBAR.FTPunCode
                        LEFT JOIN TCNMPdtSpl PSPL	ON PPSZ.FTPdtCode = PSPL.FTPdtCode AND PBAR.FTBarCode = PSPL.FTBarCode
                        LEFT JOIN TCNMSpl_L  SPLL	ON PSPL.FTSplCode	= SPLL.FTSplCode AND SPLL.FNLngID = $nLngID
                        WHERE 1=1 AND PPSZ.FTPdtCode = '$tPdtCode'
                        ORDER BY PPSZ.FTPunCode ASC , PBAR.FNPldSeq ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    // Functionality: Func. Get Data Product Set By ID Product
    // Parameters: Array ข้อมูลสินค้าเซ็ท
    // Creator:  22/02/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataPdtSetByID($paDataWhere)
    {
        $tSQL = "SELECT TOP 1
                    PUN_L.FTPunName,
                    PSET.FTPunCode,
                    PSET.FTPdtCodeSet,
                    PSET.FCPstQty,
                    PSET.FCXsdFactor,
                    PDT_L.FTPdtName,
                    0 AS FCPdtCostStd,
                    (SELECT TOP 1 FCPgdPriceRet FROM VCN_Price4PdtActive WITH(NOLOCK) WHERE FTPdtCode = PSET.FTPdtCodeSet)                  AS FCPgdPriceRet,
                    (SELECT TOP 1 FCPgdPriceRet FROM VCN_Price4PdtActive WITH(NOLOCK) WHERE FTPdtCode = PSET.FTPdtCodeSet) * PSET.FCPstQty  AS tSumPrice
                FROM
                    TCNTPdtSet PSET WITH(NOLOCK)
                LEFT JOIN TCNMPdt_L         PDT_L WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT_L.FTPdtCode AND PDT_L.FNLngID = $paDataWhere[FNLngID]
                /*LEFT JOIN TCNMPdtBar        BAR   WITH(NOLOCK) ON PSET.FTPdtCodeSet = BAR.FTPdtCode*/
                LEFT JOIN TCNMPdtUnit_L     PUN_L WITH(NOLOCK) ON /*BAR.FTPunCode = PUN_L.FTPunCode*/ PSET.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = $paDataWhere[FNLngID]
                WHERE PSET.FTPdtCode      = '$paDataWhere[FTPdtCode]'
                  AND PSET.FTPdtCodeSet   = '$paDataWhere[FTPdtCodeSet]'";

        //   print_r($tSQL); die();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = array(
                'tSQL'      => $tSQL,
                'aItems'    => $oQuery->result_array(),
                'tCode'        => '1',
                'tDesc'        => 'success'
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

    // Functionality: Func. Get Data Product Set By ID Product
    // Parameters: function parameters
    // Creator:  22/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataEvnNoSaleByID($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            PNSE.FTEvnCode,
                            PNSE.FNEvnSeqNo,
                            PNSE.FTEvnType,
                            PNSE.FTEvnStaAllDay,
                            PNSE.FDEvnDStart,
                            PNSE.FTEvnTStart,
                            PNSE.FDEvnDFinish,
                            PNSE.FTEvnTFinish,
                            PNSE_L.FTEvnName
                        FROM TCNMPdt PDT
                        INNER JOIN TCNMPdtNoSleByEvn     PNSE    ON PDT.FTEvnCode    = PNSE.FTEvnCode
                        INNER JOIN TCNMPdtNoSleByEvn_L   PNSE_L	ON PNSE.FTEvnCode   = PNSE_L.FTEvnCode  AND PNSE_L.FNLngID = $nLngID
                        WHERE PDT.FTPdtCode = '$tPdtCode'
                        ORDER BY PNSE.FNEvnSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    // Functionality : Delete Product
    // Parameters : function parameters
    // Creator :  30/08/2018 wasin
    // Return : Status Delete Product
    // Return Type : Array
    public function FSaMPdtDeleteAll($paDataDel)
    {

        try {
            $this->db->trans_begin();

            // Delete Table PDT
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdt');

            // Delete Table PDT_L
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdt_L');

            // Delete Table PackSize
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtPackSize');

            // Delete Table BarCode
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtBar');

            // Delete Table LocSeq
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNTPdtLocSeq');

            // Delete Table Rental
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TRTMPdtRental');

            // Delete Table SpcBch
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtSpcBch');

            // Delete Table Set
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNTPdtSet');

            // Delete Table Product Supplier
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtSpl');

            // Delete Table Product TCNMPDTDrug
            // Create By WItsarut 21/01/2020
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtDrug');

            // Delete Table Product TCNMPdtCostAvg
            // Create By Sooksanti(Nont) 11/03/2021
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtCostAvg');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '500',
                    'rtDesc' => 'Error Cannot Delete Product.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Product Success.',
                );
            }
        } catch (Exception $Error) {
            $aStatus = array(
                'rtCode' => '500',
                'rtDesc' => $Error->getMessage()
            );
        }
        return $aStatus;
    }

    public function FSaMPDTGetDataTableBarCodeByID($paDataWhere)
    {
        $FTMttTableKey   = $paDataWhere['FTMttTableKey'];
        $FTMttRefKey     = $paDataWhere['FTMttRefKey'];
        $FTPdtCode       = $paDataWhere['FTPdtCode'];
        $FTPunCode       = $paDataWhere['FTPunCode'];
        $FTMttSessionID  = $paDataWhere['FTMttSessionID'];
        $tSQL       = "SELECT
                            FTPdtCode,
                            FTBarCode,
                            FTPunCode,
                            FTPlcCode,
                            FTPlcName,
                            FTSplCode,
                            FTSplName,
                            FTSplStaAlwPO,
                            FTBarStaUse,
                            FTBarStaAlwSale
                        FROM TsysMasTmp
                        WHERE FTMttTableKey = '$FTMttTableKey'
                        AND FTMttRefKey     = '$FTMttRefKey'
                        AND FTPdtCode       = '$FTPdtCode'
                        AND FTPunCode       = '$FTPunCode'
                        AND FTMttSessionID  = '$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
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

    public function FSxMPDTDeleteBarCode($paDataDel)
    {
        // Delete Table PDT
        $this->db->where('FTMttTableKey', $paDataDel['FTMttTableKey']);
        $this->db->where('FTMttRefKey', $paDataDel['FTMttRefKey']);
        $this->db->where('FTPdtCode', $paDataDel['FTPdtCode']);
        $this->db->where('FTBarCode', $paDataDel['FTBarCode']);
        $this->db->where('FTPunCode', $paDataDel['FTPunCode']);
        $this->db->where('FTMttSessionID', $paDataDel['FTMttSessionID']);
        $this->db->delete('TsysMasTmp');
    }

    public function FSaMPDTCheckMasTempDuplicate($paData)
    {
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $FTPunCode      = $paData['FTPunCode'];
        $FTMttSessionID = $paData['FTMttSessionID'];

        $tSQL           = " SELECT
                                FTPunCode
                            FROM TsysMasTmp WITH(NOLOCK)
                            WHERE FTMttTableKey='$FTMttTableKey'
                              AND FTMttRefKey='$FTMttRefKey'
                              AND FTPdtCode='$FTPdtCode'
                              AND FTPunCode='$FTPunCode'
                              AND FTMttSessionID='$FTMttSessionID'
                          ";
        $oQuery         = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
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

    public function FSaMPDTAddPackSizeByIDMasTemp($paDataAdd)
    {
        $this->db->insert('TsysMasTmp', $paDataAdd);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '801',
                'rtDesc' => 'Error Cannot Add/Update Product.',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTAddPackSizeUnitByIDMasTemp($paDataAdd)
    {
        $this->db->insert('TCNMPdtPackSizeTmp', $paDataAdd);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '801',
                'rtDesc' => 'Error Cannot Add/Update Product.',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTAddBarCodeUnitByIDMasTemp($paDataAdd)
    {
        $this->db->insert('TCNMPdtBarTmp', $paDataAdd);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '801',
                'rtDesc' => 'Error Cannot Add/Update Product.',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTAddSupplierUnitByIDMasTemp($paDataAdd)
    {
        $this->db->insert('TCNMPdtSplTmp', $paDataAdd);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '801',
                'rtDesc' => 'Error Cannot Add/Update Product.',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTUpdatePackSizeUnitByIDMasTemp($paDataAdd, $paPdtWhere)
    {
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTPunCode', $paPdtWhere['FTPunCode']);
        $this->db->update('TCNMPdtPackSizeTmp', $paDataAdd);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTUpdateBarCodeeUnitByIDMasTemp($paDataAdd, $paPdtWhere)
    {
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTPunCode', $paPdtWhere['FTPunCode']);
        $this->db->where('FTBarCode', $paPdtWhere['FTBarCode']);
        $this->db->update('TCNMPdtBarTmp', $paDataAdd);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTDelSplByChangeBarCode($paPdtWhere)
    {
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTBarCode', $paPdtWhere['FTBarCode']);
        $this->db->delete('TCNMPdtSplTmp');
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTUpdateSupplierUnitByIDMasTemp($paDataAdd, $paPdtWhere)
    {
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTSplCode', $paPdtWhere['FTSplCode']);
        $this->db->where('FTBarCode', $paPdtWhere['FTBarCode']);
        $this->db->update('TCNMPdtSplTmp', $paDataAdd);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTUpdatePackSizeByIDMasTemp($paPdtWhere, $paDataUpdate)
    {
        $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
        $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
        $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTPunCode', $paPdtWhere['FTPunCode']);
        $this->db->update('TsysMasTmp', $paDataUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    // Last Update : Napat(Jame) 08/02/2021 ถ้ามีการอัพเดทรหัสหน่วย ให้อัพเดทข้อมูลใน barcode
    public function FSaMPDTUpdatePackSizeByIDMasTempOnly($paPdtWhere, $paDataUpdate)
    {
        //อัพเดทข้อมูลใน packsize
        $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
        $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
        $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTPunCode', $paPdtWhere['tUnitOld']);
        $this->db->update('TsysMasTmp', $paDataUpdate);

        // echo $this->db->last_query();

        // ถ้ามีการอัพเดทรหัสหน่วย ให้อัพเดทข้อมูลใน barcode
        // if( $paPdtWhere['tUnitOld'] != $paDataUpdate['FTPunCode'] ){
        //     $this->db->set('FTPunCode', $paDataUpdate['FTPunCode']);
        //     $this->db->set('FCPdtUnitFact', $paDataUpdate['FCPdtUnitFact']);
        //     $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
        //     $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey_BarCode']);
        //     $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
        //     $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        //     $this->db->where('FTPunCode', $paPdtWhere['tUnitOld']);
        //     $this->db->update('TsysMasTmp');
        //     // echo $this->db->last_query();
        // }

        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    // Last Update : Off 10/11/2021
    public function FSaMPDTCheckUnitMasTempDuplicate($paPdtWhere)
    {
        $FTPdtCode      = $paPdtWhere['FTPdtCode'];
        $FTPunCode      = $paPdtWhere['FTPunCode'];
        $FTMttSessionID = $paPdtWhere['FTMttSessionID'];

        $tSQL           = " SELECT
                                FTPunCode
                            FROM TCNMPdtPackSizeTmp WITH(NOLOCK)
                            WHERE  FTPdtCode='$FTPdtCode'
                                AND FTPunCode='$FTPunCode'
                                AND FTSessionID='$FTMttSessionID'
                            ";
        $oQuery         = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
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

    // Last Update : Off 10/11/2021
    public function FSaMPDTCheckBarCodeMasTempDuplicate($paPdtWhere)
    {
        $FTPdtCode      = $paPdtWhere['FTPdtCode'];
        $FTPunCode      = $paPdtWhere['FTPunCode'];
        $FTBarCode      = $paPdtWhere['FTBarCode'];
        $FTMttSessionID = $paPdtWhere['FTMttSessionID'];

        $tSQL           = " SELECT
                                FTBarCode
                            FROM TCNMPdtBarTmp WITH(NOLOCK)
                            WHERE  FTPdtCode='$FTPdtCode'
                                AND FTBarCode='$FTBarCode'
                                AND FTSessionID='$FTMttSessionID'
                            ";
        $oQuery         = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
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

    // Last Update : Off 10/11/2021
    public function FSaMPDTCheckSupplierMasTempDuplicate($paPdtWhere)
    {
        $FTPdtCode      = $paPdtWhere['FTPdtCode'];
        $FTPunCode      = $paPdtWhere['FTPunCode'];
        $FTBarCode      = $paPdtWhere['FTBarCode'];
        $FTMttSessionID = $paPdtWhere['FTMttSessionID'];

        $tSQL           = " SELECT
                                FTSplCode
                            FROM TCNMPdtSplTmp WITH(NOLOCK)
                            WHERE  FTPdtCode='$FTPdtCode'
                                AND FTSplCode='$FTPunCode'
                                AND FTBarCode='$FTBarCode'
                                AND FTSessionID='$FTMttSessionID'
                            ";
        $oQuery         = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
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

    //Update
    public function FSaMPDTUpdateUnitCodeMasTemp($paPdtWhere, $paDataUpdate)
    {

        //อัพเดทข้อมูลใน packsize
        $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
        $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
        $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['tPdtCode']);
        $this->db->where('FTPunCode', $paPdtWhere['tUnitOld']);
        $this->db->update('TsysMasTmp', $paDataUpdate);

        //อัพเดทข้อมูลใน barcode
        $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
        $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey_BarCode']);
        $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['tPdtCode']);
        $this->db->where('FTPunCode', $paPdtWhere['tUnitOld']);
        $this->db->update('TsysMasTmp', $paDataUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    public function FSxMPDTUpdatePdtCodeMasTmp($paPdtWhere, $paPdtUpdate)
    {
        $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
        $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->update('TsysMasTmp', $paPdtUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update FTPdtCode Table MasTmp Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update FTPdtCode Table MasTmp',
            );
        }
        return $aStatus;
    }

    public function FSxMPDTUpdateUnitPdtCodeMasTmp($paPdtWhere, $paPdtUpdate)
    {
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->update('TCNMPdtPackSizeTmp', $paPdtUpdate);

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->update('TCNMPdtBarTmp', $paPdtUpdate);

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->update('TCNMPdtSplTmp', $paPdtUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update FTPdtCode Table MasTmp Success',
            );
        } else {
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update FTPdtCode Table MasTmp',
            );
        }
        return $aStatus;
    }

    public function FSxMPDTAddUpdateRental($paDataAddUpdateRental)
    {
        $FTPdtCode = $paDataAddUpdateRental['FTPdtCode'];
        $tSQL   =   "SELECT FTPdtCode
					 FROM TRTMPdtRental WITH (NOLOCK)
                     WHERE FTPdtCode = '$FTPdtCode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $this->db->where('FTPdtCode', $FTPdtCode);
            $this->db->update('TRTMPdtRental', $paDataAddUpdateRental);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update TRTMPdtRental Success',
                );
            } else {
                $aStatus    = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Error Update TRTMPdtRental',
                );
            }
        } else {
            $this->db->insert('TRTMPdtRental', $paDataAddUpdateRental);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Success',
                );
            } else {
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product.',
                );
            }
        }
        return $aStatus;
    }

    public function FSxMPDTAutoAddBarCodeAndUnit($paData)
    {
        $tAgnCode  = $this->session->userdata("tSesUsrAgnCode");
        $tSQL   =   "SELECT TOP 1 FTPunCode FROM TCNMPdtUnit WITH (NOLOCK) WHERE TCNMPdtUnit.FTAgnCode = '$tAgnCode'  ORDER BY FTPunCode ASC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->result_array();
            $aDataAddPackSize = array(
                'FTPdtCode'     => $paData['FTPdtCode'],
                'FTPunCode'     => $aDataQuery[0]['FTPunCode'],
                'FCPdtUnitFact' => 1,
                'FCPdtWeight'   => 0
            );
            $aDataAddBarCode = array(
                'FTPdtCode'         => $paData['FTPdtCode'],
                'FTBarCode'         => $paData['FTPdtCode'],
                'FTPunCode'         => $aDataQuery[0]['FTPunCode'],
                'FTBarStaUse'       => '1',
                'FTBarStaAlwSale'   => '1',
                'FNBarRefSeq'       => 0
            );

            $this->db->insert('TCNMPdtPackSize', $aDataAddPackSize);
            $this->db->insert('TCNMPdtBar', $aDataAddBarCode);

            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success',
                );
            } else {
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error FSxMPDTAutoAddBarCodeAndUnit',
                );
            }
        } else {
            $aStatus    = array(
                'rtCode' => '801',
                'rtDesc' => 'Error FSxMPDTAutoAddBarCodeAndUnit',
            );
        }
        return $aStatus;
    }


    //get VatRate Company
    public function FSaMPDTGetVatRateCpn()
    {
        $tSQL   = "SELECT
                        EMP.FTVatCode,
                        EVT.FCVatRate,
                        EVT.FDVatStart
                from TCNMComp EMP
                LEFT JOIN VCN_VatActive EVT ON EMP.FTVatCode = EVT.FTVatCode
                WHERE 1=1
                AND CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),EVT.FDVatStart,121)
                ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
            $aResult = array(
                'raItems'    => $aDetail,
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

    // Functionality : Get Data History In PI
    // Parameters : function parameters
    // Creator :  16/09/2019 wasin(Yoshi)
    // Return : Array Data History
    // Return Type : Array
    public function FSaMPDTGetDataHistoryPI($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            THPIHD.FTXphDocNo,
                            CONVERT(VARCHAR(10),THPIHD.FDXphDocDate,121) AS FDXphDocDate,
                            THPIDT.FTPdtCode,
                            THPIDT.FTXpdPdtName,
                            SPL.FTSplCode,
                            SPL_L.FTSplName,
                            THPIHD.FTXphRefExt,
                            THPIDT.FTPunCode,
                            THPIDT.FTPunName,
                            THPIDT.FCXpdQty,
                            THPIDT.FCXpdQtyAll,
                            THPIDT.FCXpdAmtB4DisChg,
                            THPIDT.FCXpdSetPrice,
                            THPIDT.FCXpdDis,
                            THPIDT.FCXpdChg,
                            THPIDT.FCXpdNet,
                            THPIDT.FCXpdNetAfHD
                        FROM TAPTPiDT       THPIDT  WITH(NOLOCK)
                        LEFT JOIN TAPTPiHD  THPIHD  WITH(NOLOCK) ON THPIDT.FTBchCode    = THPIHD.FTBchCode  AND THPIDT.FTXphDocNo = THPIHD.FTXphDocNo
                        LEFT JOIN TCNMSpl   SPL     WITH(NOLOCK) ON THPIHD.FTSplCode    = SPL.FTSplCode
                        LEFT JOIN TCNMSpl_L SPL_L   WITH(NOLOCK) ON SPL.FTSplCode       = SPL_L.FTSplCode   AND SPL_L.FNLngID = '$nLngID'
                        WHERE 1=1
                        AND THPIDT.FTPdtCode  = '$tPdtCode' AND THPIHD.FTXphStaApv = 1 ORDER BY THPIHD.FDXphDocDate , THPIHD.FTXphDocNo DESC  ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataReturn    = $oQuery->result_array();
        } else {
            $aDataReturn    = array();
        }
        return $aDataReturn;
    }

    //Functionality : get all row
    //Parameters : -
    //Creator : 18/09/2019 saharat(Golf)
    //Return : array result from db
    //Return Type : array
    public function FSnMPdtGetAllNumRow($ptData)
    {
        $tPdtForSystem          = $ptData['tPdtForSystem'];
        $tLangEdit              = $this->session->userdata('tLangEdit');

        $tSQL   = "	SELECT ROW_NUMBER() OVER(ORDER BY PDT.FTPdtCode ASC) AS FNAllNumRow,
                PDT.FTPdtCode
                FROM TCNMPdt PDT WITH(NOLOCK)
                LEFT JOIN TCNMPdtSpcBch     PDLSPC  WITH(NOLOCK)    ON PDT.FTPdtCode    = PDLSPC.FTPdtCode
                LEFT JOIN TCNMPdt_L         PDTL    WITH(NOLOCK)    ON PDT.FTPdtCode    = PDTL.FTPdtCode    AND PDTL.FNLngID    = '$tLangEdit'
                LEFT JOIN TCNMPdtPackSize   PPCZ    WITH(NOLOCK)    ON PDT.FTPdtCode    = PPCZ.FTPdtCode
                LEFT JOIN TCNMPdtBar        PBAR    WITH(NOLOCK)    ON PDT.FTPdtCode    = PBAR.FTPdtCode    AND PPCZ.FTPunCode  = PBAR.FTPunCode
                LEFT JOIN TCNMImgPdt        PIMG    WITH(NOLOCK)    ON PDT.FTPdtCode    = PIMG.FTImgRefID   AND PIMG.FTImgTable = 'TCNMPdt' AND PIMG.FNImgSeq = '$tLangEdit'
                LEFT JOIN TCNMPdtType_L     PTL     WITH(NOLOCK)    ON PDT.FTPdtType    = PTL.FTPtyCode     AND PTL.FNLngID     = '$tLangEdit'
                LEFT JOIN TCNMPdtUnit_L     PUNL    WITH(NOLOCK)    ON PPCZ.FTPunCode   = PUNL.FTPunCode    AND PUNL.FNLngID    = '$tLangEdit'
                LEFT JOIN TCNMPdtGrp_L      PGL     WITH(NOLOCK)    ON PGL.FTPgpChain   = PDT.FTPgpChain
                WHERE 1=1
                AND PDT.FTPdtForSystem = '$tPdtForSystem' ";

        /* |-------------------------------------------------------------------------------------------| */
        /* |                            สิทธิในการมองเห็นสินค้า CR.wat                                      | */
        /* |-------------------------------------------------------------------------------------------| */
        /* | */
        $tSesUsrLevel           = $this->session->userdata('tSesUsrLevel');             // | */
        /* | */
        $tSessionMerCode        = $this->session->userdata('tSesUsrMerCode');           // | */
        /* | */
        $tSessionShopCode       = $this->session->userdata('tSesUsrShpCodeMulti');      // | */
        /* | */
        $tSessionBchCode        = $this->session->userdata('tSesUsrBchCodeMulti');      // | */
        /* | */                                                                                     // | */
        /* | */     //PERMISSION BCH    : ต้องเห็นสินค้าที่ผูกสาขา และสินค้าที่ไม่ผูกอะไรเลย(HQ)                // | */
        /* | */
        if ($tSesUsrLevel == 'BCH') {                                                     // | */
            /* | */
            $tSQL   .= " AND ISNULL(PDLSPC.FTBchCode,'') ";                             // | */
            /* | */
            $tSQL   .= "  IN ('',$tSessionBchCode) ";                                   // | */
            /* | */
            if ($tSessionMerCode != '' || $tSessionMerCode != null) {                     // | */
                /* | */
                $tSQL   .= " OR ( ISNULL(PDLSPC.FTMerCode,'') ";                         // | */
                /* | */
                $tSQL   .= "  IN ($tSessionMerCode) ";                                   // | */
                /* | */
                $tSQL   .= "  AND ISNULL(PDLSPC.FTBchCode,'') = '' ";                    // | */
                /* | */
                $tSQL   .= "  AND ISNULL(PDLSPC.FTShpCode,'') = '' )";                   // | */
                /* | */
            }                                                                           // | */
            /* | */
        }                                                                               // | */
        /* | */                                                                                     // | */
        /* | */     //PERMISSION SHP    : ต้องเห็นสินค้าระดับร้านค้า และ สินค้าของกลุ่มธุรกิจที่ไม่ได้ผูกร้านค้า       // | */
        /* | */
        if ($tSesUsrLevel == 'SHP') {                                                     // | */
            /* | */
            $tSQL  .= " AND ( PDLSPC.FTBchCode = '$tSessionBchCode' )";                 // | */
            /* | */
            $tSQL  .= " AND ";                                                          // | */
            /* | */
            $tSQL  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";                  // | */
            /* | */
            $tSQL  .= " ISNULL(PDLSPC.FTShpCode,'') = '' ) ";                           // | */
            /* | */
            $tSQL  .= " OR ";                                                           // | */
            /* | */
            $tSQL  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";                  // | */
            /* | */
            $tSQL  .= " PDLSPC.FTShpCode = '$tSessionShopCode' )";                      // | */
            /* | */
        }                                                                               // | */
        /* |-------------------------------------------------------------------------------------------| */

        $tSQL   .= " GROUP BY PDT.FTPdtCode";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $oQuery->num_rows(),
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Add Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtSet($paPdtSetData, $paPdtWhere)
    {
        // Update TCNMPdt
        $aDataUpdate    = array_merge($paPdtSetData, array(
            'FDLastUpdOn'   => date('Y-m-d H:i:s'),
            'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
        ));
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTPdtCodeSet', $paPdtWhere['FTPdtCodeSet']);
        $this->db->update('TCNTPdtSet', $aDataUpdate);
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'tCode' => '1',
                'tDesc' => 'Update Product Set Success',
            );
        } else {
            // Add TCNMPdt
            $aDataInsert = array_merge($paPdtWhere, $paPdtSetData, array(
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername')
            ));
            $this->db->insert('TCNTPdtSet', $aDataInsert);
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

    //Functionality : Add Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array herenaha
    public function FSaMPDTUpdPdtSetTmp($paPdtSetData, $paPdtWhere)
    {
        // Update TCNTPdtSet_Tmp
        $tTable = "TCNTPdtSet_Tmp" . date("dmY");

        $aDataUpdate    = array_merge($paPdtSetData, array(
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'FDLastUpdOn'   => date('Y-m-d H:i:s'),
            'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
        ));
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTPdtCodeSet', $paPdtWhere['FTPdtCodeSet']);
        $this->db->update($tTable, $aDataUpdate);

        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'tCode' => '1',
                'tDesc' => 'Update Product Set Success',
            );
        } else {
            // Add TCNMPdt
            $aDataInsert = array_merge($paPdtWhere, $paPdtSetData, array(
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
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

    //Functionality : Update Status Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtStaSet($aPdtSetWhere)
    {
        $tSQL       = "SELECT FTPdtCode FROM TCNTPdtSet WHERE FTPdtCode='$aPdtSetWhere[FTPdtCode]'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataUpdate    = array(
                'FTPdtSetOrSN'  => '2',
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
        } else {
            $aDataUpdate    = array(
                'FTPdtSetOrSN'  => '1',
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
        }

        $this->db->where('FTPdtCode', $aPdtSetWhere['FTPdtCode']);
        $this->db->update('TCNMPdt', $aDataUpdate);
        if ($this->db->affected_rows() > 0) {
            $aDataReturn    = array(
                'tCode' => '1',
                'tDesc' => 'Update product set success',
            );
        } else {
            $aDataReturn    = array(
                'tCode' => '801',
                'tDesc' => 'Eoor update product set',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Delete Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTDelPdtSet($paDataDel)
    {
        $tTable = "TCNTPdtSet_Tmp" . date("dmY");

        $this->db->where('FTPdtCode', $paDataDel['FTPdtCode']);
        $this->db->where('FTPdtCodeSet', $paDataDel['FTPdtCodeSet']);
        $this->db->delete($tTable);
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

    //Functionality : Update FTPdtStaSetPri
    //Parameters : FTPdtCode,FTPdtStaSetPri
    //Creator : 13/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtSetPri($paDataUpd, $paDataWhere)
    {
        $this->db->where('FTPdtCode', $paDataWhere['FTPdtCode']);
        $this->db->update('TCNMPdt', $paDataUpd);
        if ($this->db->affected_rows() > 0) {
            $aDataReturn    = array(
                'tCode' => '1',
                'tDesc' => 'Update product set success',
            );
        } else {
            $aDataReturn    = array(
                'tCode' => '801',
                'tDesc' => 'Eoor update product set',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Update FTPdtStaSetShwDT
    //Parameters : FTPdtCode,FTPdtStaSetShwDT
    //Creator : 13/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtStaSetShwDT($paDataUpd, $paDataWhere)
    {
        $this->db->where('FTPdtCode', $paDataWhere['FTPdtCode']);
        $this->db->update('TCNMPdt', $paDataUpd);
        if ($this->db->affected_rows() > 0) {
            $aDataReturn    = array(
                'tCode' => '1',
                'tDesc' => 'Update product set success',
            );
        } else {
            $aDataReturn    = array(
                'tCode' => '801',
                'tDesc' => 'Eoor update product set',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Get Data Other product from TCNTPdtSet
    //Parameters : -
    //Creator : 14/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTGetOthPdt($aDataSearch)
    {
        $tPDTCode = $aDataSearch['FTPdtCode'];
        $tSQL = "SELECT FTPdtCodeSet FROM TCNTPdtSet WITH(NOLOCK) WHERE FTPdtCode = '$tPDTCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataReturn    = array(
                'aItems'    => $oQuery->result_array(),
                'tCode'     => '1',
                'tDesc'     => 'Select success',
            );
        } else {
            $aDataReturn    = array(
                'aItems'    => array(),
                'tCode'     => '801',
                'tDesc'     => 'Eoor select',
            );
        }
        return $aDataReturn;
    }
    /*
    //Functionality :GetData กำหนดเงื่อนไขการควบคุมสต็อค
    //Parameters : -
    //Creator : 20/1/2020 nonapwich(petch)
    //Return : status
    //Return Type : object
    */
    public function FSaMPDTGetDataPdtSpcWah($paDataWhereSpcWah)
    {

        // $FTPdtCode      = $paDataWhereSpcWah['FTPdtCode'];

        $tSQL = "SELECT   Temp.FTPdtCode AS TmpFTPdtCode,
        Temp.FTBchCode AS TmpFTBchCode,
        Temp.FTWahCode AS TmpFTWahCode,
        Temp.FCSpwQtyMin AS TmpFCSpwQtyMin,
        Temp.FCSpwQtyMax AS TmpFCSpwQtyMax,
        Temp.FTSpwRmk AS TmpFTSpwRmk FROM  TsysMasTmp Temp
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->result_array();
    }

    public function FSaMPDTChkChainPdtSet($paDataWhere)
    {
        $tSQL = "SELECT
                    PSET.FTPdtCode,
                    PDT_L.FTPdtName
                 FROM TCNTPdtSet PSET WITH(NOLOCK)
                 LEFT JOIN TCNMPdt_L PDT_L ON PSET.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID = $paDataWhere[FNLngID]
                 WHERE PSET.FTPdtCodeSet = '$paDataWhere[FTPdtCode]'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataReturn    = array(
                'aItems'    => $oQuery->result_array(),
                'tCode'     => '1',
                'tDesc'     => 'Select success',
            );
        } else {
            $aDataReturn    = array(
                'aItems'    => array(),
                'tCode'     => '801',
                'tDesc'     => 'Eoor select',
            );
        }
        return $aDataReturn;
    }

    /*
    //Functionality :  insert into  กำหนดเงื่อนไขการควบคุมสต็อค
    //Parameters : -
    //Creator : 16/1/2020
    //Return : status
    //Return Type : array
    */
    public function FSaMPDTSpcWahInsertData($paData)
    {

        $FTPdtCode      = $paData['FTPdtCode'];

        $tSQL    = "INSERT INTO TsysMasTmp Temp ()
        SELECT

            Temp.FTPdtCode,
            Temp.FTBchCode,
            Temp.FTWahCode,
            Temp.FCSpwQtyMin,
            Temp.FCSpwQtyMax,
            Temp.FTSpwRmk


        WHERE Temp.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
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

    /*
    //Functionality : del
    //Parameters : -
    //Creator : 16/1/2020
    //Return : status
    //Return Type : array
    */
    public function FSaMPDTDelPdtSpcWah($paDataDel)
    {

        $this->db->where('FTPdtCode', $paDataDel['FTPdtCode']);
        $this->db->where('FTBchCode', $paDataDel['FTBchCode']);
        $this->db->where('FTWahCode', $paDataDel['FTWahCode']);
        $this->db->delete('TsysMasTmp');
    }

    /*
    //Functionality : update pdtSpcWah
    //Parameters : -
    //Creator : 16/1/2020
    //Return : status
    //Return Type : array
    */

    public function FSaMPDTUpdatePdtSpcWah($paDataAddUpdatePdtSpcWah)
    {

        $FTPdtCode = $paDataAddUpdatePdtSpcWah['FTPdtCode'];
        $FTBchCode = $paDataAddUpdatePdtSpcWah['FTBchCode'];
        $FTWahCode = $paDataAddUpdatePdtSpcWah['FTWahCode'];
        $tSQL   =   "SELECT FTPdtCode , FTBchCode , FTWahCode ,FCSpwQtyMin ,FCSpwQtyMax ,FTSpwRmk
                        FROM TsysMasTmp WITH (NOLOCK)
                        WHERE FTPdtCode = '$FTPdtCode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $this->db->where('FTPdtCode', $FTPdtCode);
            $this->db->update('TsysMasTmp', $paDataAddUpdatePdtSpcWah);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update TsysMasTmp Success',
                );
            } else {
                $aStatus    = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Error Update TsysMasTmp',
                );
            }
        } else {
            $this->db->insert('TsysMasTmp', $paDataAddUpdatePdtSpcWah);
            if ($this->db->affected_rows() > 0) {
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Success',
                );
            } else {
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product.',
                );
            }
        }
        return $aStatus;
    }

    //Functionality : Update&insert TsysMasTmp
    //Parameters : function parameters
    //Creator : 23/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsAddEditTemp($paData)
    {
        try {
            //Update Master
            $this->db->set('FTMttSessionID', $paData['FTMttSessionID']);
            $this->db->set('FTPdtCode', $paData['FTPdtCode']);
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FTWahCode', $paData['FTWahCode']);
            $this->db->set('FCSpwQtyMin', $paData['FCSpwQtyMin']);
            $this->db->set('FCSpwQtyMax', $paData['FCSpwQtyMax']);
            $this->db->set('FCPdtLeadTime', $paData['FCPdtLeadTime']);
            $this->db->set('FCPdtPerSLA', $paData['FCPdtPerSLA']);
            $this->db->set('FCPdtQtySugges', $paData['FCPdtQtySugges']);
            $this->db->set('FTSpwRmk', $paData['FTSpwRmk']);

            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);

            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->where('FTWahCode', $paData['FTWahCode']);
            $this->db->where('FTPdtCode', $paData['FTPdtCode']);
            $this->db->update('TsysMasTmp');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            } else {
                //Add Master
                $this->db->insert('TsysMasTmp', array(
                    'FTMttTableKey'     => $paData['FTMttTableKey'],
                    'FTMttSessionID'    => $paData['FTMttSessionID'],
                    'FTPdtCode'         => $paData['FTPdtCode'],
                    'FTBchCode'         => $paData['FTBchCode'],
                    'FTWahCode'         => $paData['FTWahCode'],
                    'FCSpwQtyMin'       => $paData['FCSpwQtyMin'],
                    'FCSpwQtyMax'       => $paData['FCSpwQtyMax'],
                    'FCPdtLeadTime'     => $paData['FCPdtLeadTime'],
                    'FCPdtDailyUseAvg'  => $paData['FCPdtDailyUseAvg'],
                    'FCPdtPerSLA'       => $paData['FCPdtPerSLA'],
                    'FCPdtQtySugges'    => $paData['FCPdtQtySugges'],
                    'FCPdtQtyOrdBuy'    => $paData['FCPdtQtyOrdBuy'],
                    'FTSpwRmk'          => $paData['FTSpwRmk'],

                    'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                    'FDCreateOn'        => $paData['FDCreateOn'],
                    'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                    'FTCreateBy'        => $paData['FTCreateBy'],
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : List Data  StockConditions
    //Parameters : function parameters
    //Creator : 21/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsList($paData)
    {

        $aRowLen = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $tPdtCode       = $paData['FTPdtCode'];
        $tSessionID     = $paData['FTMttSessionID'];
        $nLngID         = $paData['FNLngID'];
        $tTableKey      = $paData['FTMttTableKey'];

        $tSQL   = "SELECT c.* FROM( SELECT  ROW_NUMBER() OVER(ORDER BY FTBchCode ASC) AS FNRowID,* FROM
                  (SELECT  DISTINCT
                         TMT.FTPdtCode,
                         TMT.FTWahCode,
                         TMT.FTBchCode,
                         TMT.FCSpwQtyMin,
                         TMT.FCSpwQtyMax,
                         TMT.FTMttTableKey,
                         PDTL.FTPdtName,
                         BCHL.FTBchName,
                         WAHL.FTWahName,
                         TMT.FCPdtLeadTime,
                         TMT.FCPdtDailyUseAvg,
                         TMT.FCPdtPerSLA,
                         TMT.FCPdtQtySugges,
                         TMT.FCPdtQtyOrdBuy,
                         TMT.FDLastUpdOn
                    FROM TsysMasTmp         TMT  WITH(NOLOCK)
                    LEFT JOIN TCNMPdt_L     PDTL WITH(NOLOCK) ON TMT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID   =  $nLngID
                    LEFT JOIN TCNMBranch_L  BCHL WITH(NOLOCK) ON TMT.FTBchCode = BCHL.FTBchCode  AND BCHL.FNLngID   =  $nLngID
                    LEFT JOIN TCNMWaHouse_L WAHL WITH(NOLOCK) ON TMT.FTWahCode = WAHL.FTWahCode AND TMT.FTBchCode = WAHL.FTBchCode  AND WAHL.FNLngID   =  $nLngID
                    WHERE 1=1
                        AND TMT.FTPdtCode       = '$tPdtCode'
                        AND TMT.FTMttSessionID  = '$tSessionID'
                        AND TMT.FTMttTableKey   = '$tTableKey' ";

        // Check User Login Branch
        if ($this->session->userdata("tSesUsrLevel") != "HQ") {
            $tBchMulti = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND TMT.FTBchCode IN (" . $tBchMulti . ") ";
        }


        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";


        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMPDTStockConditionsGetPageAll($nLngID);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
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
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : All Page Of StockConditions
    //Parameters : function parameters
    //Creator :  23/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMPDTStockConditionsGetPageAll($paData)
    {
        $nLngID   = $paData;
        $tSQL = "SELECT COUNT (TMT.FTMttTableKey) AS counts
                 FROM TsysMasTmp TMT
                        LEFT JOIN TCNMPdt_L PDTL     ON TMT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID   =  $nLngID
                        LEFT JOIN TCNMBranch_L BCHL  ON TMT.FTBchCode = BCHL.FTBchCode  AND BCHL.FNLngID   =  $nLngID
                        LEFT JOIN TCNMWaHouse_L WAHL ON TMT.FTWahCode = WAHL.FTWahCode  AND TMT.FTBchCode = WAHL.FTBchCode  AND WAHL.FNLngID   =  $nLngID
                 WHERE 1=1 ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            //No Data
            return false;
        }
    }

    //Functionality : ดึงข้อมูล StockConditions ไปแก้ไข
    //Parameters : function parameters
    //Creator : 23/01/2020 saharat(Golโ)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsGetDataByID($paData)
    {
        $tPdtCode   = $paData['FTPdtCode'];
        $tBchCode   = $paData['FTBchCode'];
        $tWahCode   = $paData['FTWahCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                         TMT.FTPdtCode,
                         TMT.FTWahCode,
                         TMT.FTBchCode,
                         TMT.FCSpwQtyMin,
                         TMT.FCSpwQtyMax,
                         TMT.FTMttTableKey,
                         TMT.FTSpwRmk,
                         PDTL.FTPdtName,
                         BCHL.FTBchName,
                         WAHL.FTWahName,
                         TMT.FCPdtLeadTime,
                         TMT.FCPdtDailyUseAvg,
                         TMT.FCPdtPerSLA,
                         TMT.FCPdtQtySugges,
                         TMT.FCPdtQtyOrdBuy,
                         CONVERT(VARCHAR(10),TMT.FDLastUpdOn,121) AS FDLastUpdOn
                        FROM TsysMasTmp TMT WITH(NOLOCK)
                        LEFT JOIN TCNMPdt_L PDTL WITH(NOLOCK)     ON TMT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK)  ON TMT.FTBchCode = BCHL.FTBchCode  AND BCHL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMWaHouse_L WAHL WITH(NOLOCK) ON TMT.FTWahCode = WAHL.FTWahCode  AND TMT.FTBchCode = WAHL.FTBchCode  AND WAHL.FNLngID   =  $nLngID 
                        WHERE 1=1 
                        AND TMT.FTMttTableKey = 'TCNMPdtSpcWah' 
                        AND TMT.FTPdtCode   = '$tPdtCode'
                        AND TMT.FTBchCode   = '$tBchCode'
                        AND TMT.FTWahCode   = '$tWahCode'
                    ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    // Functionality : ลบข้อมูลในตาราง TsysMasTmp
    // Parameters : function parameters
    // Creator : 23/01/2020 Saharat(GolF)
    // Return : Array
    // Return Type : array
    public function FSaMPDTStockConditionsDel($paData)
    {

        $this->db->where_in('FTPdtCode', $paData['FTPdtCode']);
        $this->db->where_in('FTBchCode', $paData['FTBchCode']);
        $this->db->where_in('FTWahCode', $paData['FTWahCode']);
        $this->db->delete('TsysMasTmp');

        if ($this->db->affected_rows() > 0) {
            //Success
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //Functionality : บันทึกข้อมูลงตาราง  TCNMPdtSpcWah
    //Parameters : function parameters
    //Creator : 24/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsAddEdit($ptPdtCode)
    {
        try {
            $tPdtCode = $ptPdtCode;
            // delete TCNMPdtSpcWah
            $this->db->where('FTPdtCode', $tPdtCode);
            $this->db->delete('TCNMPdtSpcWah');

            //Add Master

            $tSQL  = "  INSERT INTO TCNMPdtSpcWah (FTPdtCode, FTBchCode, FTWahCode, FCPdtMin, FCPdtMax, FCPdtLeadTime, FCPdtDailyUseAvg, FCPdtPerSLA, FCPdtQtySugges, FCPdtQtyOrdBuy, FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy, FTPdtRmk)
                        SELECT FTPdtCode, FTBchCode, FTWahCode, FCSpwQtyMin, FCSpwQtyMax, FCPdtLeadTime, FCPdtDailyUseAvg, FCPdtPerSLA, FCPdtQtySugges, FCPdtQtyOrdBuy, FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy, FTSpwRmk
                        FROM TsysMasTmp WITH(NOLOCK)
                        WHERE FTPdtCode     = '$tPdtCode'
                            AND FTMttTableKey = 'TCNMPdtSpcWah'
                ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery) {
                // ลบข้อมูลในตาราง Tmp
                $this->db->where('FTPdtCode', $tPdtCode);
                $this->db->delete('TsysMasTmp');

                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Master Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : บันทึกข้อมูลงตาราง  TsysMasTmp
    //Parameters : function parameters
    //Creator : 24/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsGetDataList($ptData)
    {
        try {

            $this->db->where_in('FTPdtCode', $ptData['FTPdtCode']);
            $this->db->delete('TsysMasTmp');
            $tPdtCode = $ptData['FTPdtCode'];
            //Add Master
            $tSQL  = "  INSERT INTO  TsysMasTmp (FTPdtCode, FTBchCode, FTWahCode, FCSpwQtyMin, FCSpwQtyMax, 
                        FCPdtLeadTime, FCPdtDailyUseAvg, FCPdtPerSLA, FCPdtQtySugges, FCPdtQtyOrdBuy, FDLastUpdOn,FTSpwRmk,FTMttTableKey,FTMttSessionID)
                        SELECT 
                        FTPdtCode,
                        FTBchCode,
                        FTWahCode,
                        FCPdtMin, 
                        FCPdtMax,
                        FCPdtLeadTime,
                        FCPdtDailyUseAvg,
                        FCPdtPerSLA,
                        FCPdtQtySugges,
                        FCPdtQtyOrdBuy, 
                        FDLastUpdOn,
                        FTPdtRmk, 
                        'TCNMPdtSpcWah' AS TCNMPdtSpcWah,
                        '" . $ptData['FTMttSessionID'] . "' AS FTMttSessionID
                        FROM TCNMPdtSpcWah WITH(NOLOCK)
                        WHERE FTPdtCode     = '$tPdtCode'
                    ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Master Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : ดึงข้อมูล StockConditions ไปแก้ไข
    //Parameters : function parameters
    //Creator : 23/01/2020 saharat(GolF)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsCheckBchWah($paData)
    {
        $tPdtCode   = $paData['FTPdtCode'];
        $tBchCode   = $paData['FTBchCode'];
        $tWahCode   = $paData['FTWahCode'];
        $tSQL       = " SELECT
                        TMT.FTPdtCode
                        FROM TsysMasTmp TMT WITH(NOLOCK)
                        WHERE 1=1
                        AND TMT.FTPdtCode   = '$tPdtCode'
                        AND TMT.FTBchCode   = '$tBchCode'
                        AND TMT.FTWahCode   = '$tWahCode'
                    ";
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

    //Functionality : เพิ่มสีแทนรูป
    //Parameters : function parameters
    //Creator : 19/03/2020 Saharat(Golf)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMPDTAddUpdateImgObj($paData)
    {
        try {

            $this->db->where_in('FTImgTable', $paData['tImgTable']);
            $this->db->where_in('FTImgRefID', $paData['tImgRefID']);
            $this->db->delete('TCNMImgPdt');
            if ($this->db->affected_rows() > 0) {
                if (file_exists('application/modules/' . $paData['tModuleName'] . '/assets/systemimg/' . $paData['tImgFolder'])) {
                    $files    = glob('application/modules/' . $paData['tModuleName'] . '/assets/systemimg/' . $paData['tImgFolder'] . "/" . $paData['tImgRefID'] . "/*"); // get all file names
                    foreach ($files as $file) { // iterate files
                        if (is_file($file))
                            unlink($file); // delete file
                    }
                }
            }
            //Add Master
            $this->db->insert('TCNMImgPdt', array(
                'FTImgRefID'        => $paData['tImgRefID'],
                'FTImgTable'        => $paData['tImgTable'],
                'FTImgObj'          => $paData['tImgObj'],
                'FNImgSeq'          => 1,
                'FTImgKey'          => $paData['tImgKey'],
                'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                'FDCreateOn'        => $paData['FDCreateOn'],
                'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                'FTCreateBy'        => $paData['FTCreateBy'],
            ));
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Master Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }


    //Functionality : Get ข้อมูล ต้นทุนล่าสุด
    //Parameters : function parameters
    //Creator : 03/09/2020 Worakorn
    //Last Modified : -
    //Return : response
    //Return Type : Array

    function FSaMPDTGetPdtCostDef($ptData)
    {
        $tPdtCode   = $ptData['FTPdtCode'];

        $tSQL       = " SELECT TOP 1 DT.FCXpdSetPrice FROM TAPTPiDT DT  INNER JOIN  TAPTPiHD HD ON  DT.FTXphDocNo = HD.FTXphDocNo  WHERE DT.FTPdtCode = '$tPdtCode'  AND HD.FTXphStaApv = 1 ORDER BY HD.FDLastUpdOn DESC ";
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();

        $aData =  $oQuery->result_array();

        return $aData;
    }


    //Functionality : Get ข้อมูล ต้นทุนเฉลี่ยแยกนอก รวมใน
    //Parameters : function parameters
    //Creator : 03/09/2020 Worakorn
    //Last Modified : -
    //Return : response
    //Return Type : Array
    function FSaMPDTGetPDTCostExIn($ptData)
    {
        $tPdtCode   = $ptData['FTPdtCode'];

        $tSQL       = " SELECT FCPdtCostEx,FCPdtCostIn FROM TCNMPdtCostAvg WHERE  FTPdtCode = '$tPdtCode' ";
        $oQuery = $this->db->query($tSQL);

        $aData =  $oQuery->result_array();

        return $aData;
    }

    //Functionality : Get ข้อมูล Factor
    //Parameters : function parameters
    //Creator : 02/11/2020 Worakorn
    //Last Modified : -
    //Return : response
    //Return Type : Array
    function FSaMPDTGetFactor($ptPdtCode)
    {

        $tSQL       = " SELECT FCPdtUnitFact,FTPunCode FROM TCNMPdtPackSize WHERE  FTPdtCode = '$ptPdtCode' ";
        $oQuery = $this->db->query($tSQL);

        $aData =  $oQuery->result_array();

        return $aData;
    }


    //Functionality : ตรวจสอบว่าเป็นสินค้าชุดของสินค้าอื่นหรือไม่
    //Parameters : function parameters
    //Creator : 13/11/2020 Nattakit
    //Last Modified : -
    //Return : number
    //Return Type : number
    public function FSnMPDTChkStaPdtSet($ptData)
    {
        try {
            $tPdtCode = $ptData['FTPdtCode'];

            $nNumrowsData = $this->db->where('FTPdtCodeSet', $tPdtCode)->get('TCNTPdtSet')->num_rows();

            return $nNumrowsData;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Update FTPdtStaSetPrcStk
    //Parameters : FTPdtCode,FTPdtStaSetPrcStk
    //Creator : 20/11/2020 Napat(Jame)
    //Return : -
    //Return Type : -
    public function FSxMPDTUpdPdtStaSetPrcStk($paDataUpd)
    {
        $this->db->set('FTPdtStaSetPrcStk', $paDataUpd['FTPdtStaSetPrcStk']);
        $this->db->where('FTPdtCode', $paDataUpd['FTPdtCode']);
        $this->db->update('TCNMPdt');
    }


    //Functionality : ตรวจสอบว่ามีข้อมูลหน่วยสินค้าในตาราง Master
    //Parameters :
    //Creator : 29/01/2021 Sooksanti
    //Last Modified : -
    //Return : number
    //Return Type : number
    public function FSxMPDTCheckUnitMaster()
    {
        $tAgnCode  = $this->session->userdata("tSesUsrAgnCode");
        $tSQL   =   "SELECT COUNT(FTPunCode) AS FTPunCount FROM TCNMPdtUnit WITH (NOLOCK) WHERE TCNMPdtUnit.FTAgnCode = '$tAgnCode'";
        $oQuery = $this->db->query($tSQL);

        $aData =  $oQuery->result_array();

        return $aData;
    }

    // Creator :  17/06/2021 Napat(Jame)
    public function FSaMPDTGetDataControlLot($paDataWhere)
    {
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $tAgnCode   = $this->session->userdata("tSesUsrAgnCode");
        $tSQL       = " SELECT
                            PDTLOT.FTPdtCode,
                            LOT.FTLotBatchNo,
                            PDTLOT.FTLotNo,
                            PDTLOT.FCPdtCost,
                            PDTLOT.FDPdtDateMFG,
                            PDTLOT.FDPdtDateEXP
                        FROM TCNMPdtLot PDTLOT WITH(NOLOCK)
                        INNER JOIN TCNMLot LOT WITH(NOLOCK) ON PDTLOT.FTLotNo = LOT.FTLotNo AND (LOT.FTAgnCode = '$tAgnCode' OR ISNULL(LOT.FTAgnCode,'') = '')
                        WHERE 1=1
                            AND PDTLOT.FTPdtCode = '$tPdtCode'
                        ORDER BY PDTLOT.FDCreateOn DESC
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

    //Functionality : ListData StockLot
    //Parameters : function parameters
    // Creator : 28/07/2021 Phaksaran(Golf)
    // Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockLotsList($paData)
    {
        $tPdtCode   = $paData['FTPdtCode'];
        $tPbnCode   = $paData['FTPbnCode'];
        $tPmoCode   = $paData['FTPmoCode'];
        $FNLngID    = $paData['FNLngID'];
        $tSqlWhere = '';
        if ($tPbnCode != '') {
            $tSqlWhere .= "AND TCNMPdtLot.FTPbnCode =  '$tPbnCode'";
        }
        if ($tPmoCode != '') {
            $tSqlWhere .= "AND TCNMPdtLot.FTPmoCode =  '$tPmoCode'";
        }

        $tSQL   = "SELECT TCNMLot.FTLotYear ,
                    TCNMLot.FTLotNo ,
                    TCNMLot.FTLotBatchNo ,
                    TCNMPdtLot.FTPbnCode ,
                    TCNMPdtLot.FTPmoCode ,
                    TCNMPdtLot.FCPdtCost ,
                    TCNMPdtLot.FDPdtDateMFG ,
                    TCNMPdtLot.FDPdtDateEXP ,
                    TCNMPdtBrand_L.FTPbnName,
                    TCNMPdtModel_L.FTPmoName  
                FROM TCNMPdtLot 
                inner join TCNMLot on TCNMPdtLot.FTLotNo = TCNMLot.FTLotNo
                LEFT JOIN TCNMPdtBrand_L ON TCNMPdtLot.FTPbnCode = TCNMPdtBrand_L.FTPbnCode AND TCNMPdtBrand_L.FNLngID = '$FNLngID'
	            LEFT JOIN TCNMPdtModel_L ON TCNMPdtLot.FTPmoCode = TCNMPdtModel_L.FTPmoCode AND TCNMPdtModel_L.FNLngID = '$FNLngID'
                ";

        if ($tPbnCode != '' || $tPmoCode != '') {
            $tSQL .= "WHERE 1=1 $tSqlWhere ";
        } else {
            $tSQL .= "WHERE TCNMPdtLot.FTPbnCode =  '$tPbnCode' AND TCNMPdtLot.FTPmoCode =  '$tPmoCode'";
        }

        // echo $tSQL;
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'oList'         => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    // Functionality: Func. Get Data ProductLot by ID
    // Parameters: function parameters
    // Creator : 28/07/2021 Phaksaran(Golf)
    // Return: Array Data 
    // ReturnType: Array
    public function FSaMPDTStockLotsListByid($paData)
    {
        $tPdtCode  = $paData['FTPdtCode'];
        $tLotNo    = $paData['FTLotNo'];
        $tSQL      = "SELECT TCNMPdtLot.FTPdtCode ,  TCNMLot.FTLotNo , TCNMLot.FTLotBatchNo ,TCNMPdtLot.FCPdtCost ,TCNMPdtLot.FDPdtDateMFG , TCNMPdtLot.FDPdtDateEXP  from TCNMPdtLot 
                      inner join TCNMLot on TCNMPdtLot.FTLotNo = TCNMLot.FTLotNo
                      WHERE TCNMPdtLot.FTPdtCode ='$tPdtCode' AND TCNMLot.FTLotNo =  '$tLotNo'";
        $oQuery    =  $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'oList'         => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }
    //Functionality :  insert into  กำหนดเงื่อนไขการควบคุมสต็อค
    //Parameters : function parameters
    //Creator : 16/1/2020 Phaksaran(Golf)
    //Return : array
    //Return Type : array
    public function FSaMPdtLotAddData($paData)
    {
        try {
            $this->db->insert('TCNMPdtLot', array(
                'FTPdtCode'    => $paData['FTPdtCode'],
                'FTLotNo'      => $paData['FTLotNo'],
                'FCPdtCost'    => $paData['FCPdtCost'],
                'FDPdtDateMFG' => $paData['FDPdtDateMFG'],
                'FDPdtDateEXP' => $paData['FDPdtDateEXP'],
                'FDLastUpdOn'  => $paData['FDLastUpdOn'],
                'FTLastUpdBy'  => $paData['FTLastUpdBy'],
                'FDCreateOn'   => $paData['FDCreateOn'],
                'FTCreateBy'   => $paData['FTCreateBy'],
            ));
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Master Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }
    //Functionality :  Edit data  กำหนดเงื่อนไขการควบคุมสต็อค
    //Parameters : function parameters
    //Creator : 16/1/2020 Phaksaran(Golf)
    //Return : array
    //Return Type : array
    public function FSaMPDTStockLotEdit($paData)
    {
        try {
            $this->db->set('FTLotNo', $paData['FTLotNo']);
            $this->db->set('FCPdtCost', $paData['FCPdtCost']);
            $this->db->set('FDPdtDateMFG', $paData['FDPdtDateMFG']);
            $this->db->set('FDPdtDateEXP', $paData['FDPdtDateEXP']);
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->set('FDCreateOn', $paData['FDCreateOn']);
            $this->db->set('FTCreateBy', $paData['FTCreateBy']);
            $this->db->where('FTPdtCode', $paData['FTPdtCode']);
            $this->db->where('FTLotNo', $paData['FTLotNo']);
            $this->db->update('TCNMPdtLot');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Edit Master Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Edit Master.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }
    // Functionality : ลบข้อมูลในตาราง TCNMPdtLot
    // Parameters : function parameters
    // Creator : 28/07/2021 Phaksaran(Golf)
    // Return : Array
    // Return Type : array
    public function FSaMPDTStockLotDel($paData)
    {
        $this->db->where('FTPdtCode', $paData['FTPdtCode']);
        $this->db->where('FTLotNo', $paData['FTLotNo']);
        $this->db->delete('TCNMPdtLot');
        if ($this->db->affected_rows() > 0) {
            //Success
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    // Functionality : ลบข้อมูลในตาราง TCNMPdtLot
    // Parameters : function parameters
    // Creator : 19/10/2021 Off
    // Return : Array
    // Return Type : array
    public function FSaMPDTDelDataSetTMP($paPdtWhere)
    {
        $tTable = "TCNTPdtSet_Tmp" . date("dmY");

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete($tTable);
    }

    // Functionality : ลบข้อมูลในตาราง TCNMPdtLot
    // Parameters : function parameters
    // Creator : 19/10/2021 Off
    // Return : Array
    // Return Type : array
    public function FSaMPDTDelDataUnitPackTMP($paPdtWhere)
    {
        $tTable = "TCNMPdtPackSizeTmp";

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete($tTable);

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete('TCNMPdtBarTmp');

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete('TCNMPdtSplTmp');
    }

    // Functionality : ลบข้อมูลในตาราง SetTMP ADD
    // Parameters : function parameters
    // Creator : 19/10/2021 Off
    // Return : Array
    // Return Type : array
    public function FSaMPDTDelDataSetTMPAdd($paPdtWhere)
    {
        $tTable = "TCNTPdtSet_Tmp" . date("dmY");

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->delete($tTable);
    }

    // Functionality : ลบข้อมูลในตาราง TCNMPdtPackSizeTmp
    // Parameters : function parameters
    // Creator : 10/11/2021 Off
    // Return : Array
    // Return Type : array
    public function FSaMPDTDelDataUnitPdtTMP($paPdtWhere)
    {
        $tTable = "TCNMPdtPackSizeTmp";
        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->delete($tTable);

        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->delete('TCNMPdtBarTmp');


        $this->db->where('FTSessionID', $paPdtWhere['FTMttSessionID']);
        $this->db->delete('TCNMPdtSplTmp');
    }

    //Functionality : Insert to Tmp SVSET
    //Parameters : -
    //Creator : 29/06/2021 Off
    //Return : status
    //Return Type : array
    public function FSaMPDTInsertPdtSetTemp($paData)
    {
        $FTPdtCode      = $paData['FTPdtCode'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $tTable = "TCNTPdtSet_Tmp" . date("dmY");

        $tSQL = "INSERT INTO $tTable (FTPdtCode,
                FTPdtCodeSet,
                FCPstQty,
                FDLastUpdOn,
                FTLastUpdBy,
                FDCreateOn,
                FTCreateBy,
                FTPunCode,
                FCXsdFactor,
                FTSessionID
                )
            SELECT 
                FTPdtCode,
                FTPdtCodeSet,
                FCPstQty,
                FDLastUpdOn,
                FTLastUpdBy,
                FDCreateOn,
                FTCreateBy,
                FTPunCode,
                FCXsdFactor,
                '$FTMttSessionID'    AS FTSessionID
            FROM TCNTPdtSet
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

    public function FSxMPDTAddUpdateSetItem($paPdtWhere, $paDataBarCode)
    {
        $tTable = "TCNTPdtSet_Tmp" . date("dmY");

        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNTPdtSet');

        $tSQL = "INSERT INTO TCNTPdtSet (FTPdtCode,
                        FTPdtCodeSet,
                        FCPstQty,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy,
                        FTPunCode,
                        FCXsdFactor
                        )
                SELECT FTPdtCode,
                        FTPdtCodeSet,
                        FCPstQty,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy,
                        FTPunCode,
                        FCXsdFactor
                FROM $tTable
                WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);

        $tSQLCheck = "SELECT FTPdtCode,
                        FTPdtCodeSet
            FROM $tTable
            WHERE FTPdtCode='$FTPdtCode' AND FTSessionID='$FTMttSessionID'";

        $oCheckQuery = $this->db->query($tSQLCheck);
        $aDataCheckQueryReturn    = $oCheckQuery->result_array();

        // exit;
        if ($oQuery > 0) {
            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->delete($tTable);

            if (count($aDataCheckQueryReturn) > 0) {
                $aDataUpdate    = array(
                    'FTPdtSetOrSN'  => '2',
                    'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
                );
            } else {
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

    // Functionality : เรียกข้อมูลตรวจสอบราคาสินค้า
    // Parameters : Function Parameter
    // Creator : 20/08/2020 Sooksanti(Non)
    // Last Modified :
    // Return : array
    // Return Type : array
    public function FSaMPDTGetPrictListData($paData)
    {
        $nLngID         = $paData['FNLngID'];
        $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $oAdvanceSearch = $paData['oAdvanceSearch'];
        $tWhere         = "";
        $FTPdtCode      = $paData['FTPdtCode'];

        if ($paData['tDisplayType'] == '1') {
            $tOrderBy1 = " ADJP_HD.FTXphDocNo DESC ";
            // $tOrderBy1 = " ADJP_DT.FTPdtCode ASC, ADJP_DT.FTPunCode ASC, ADJP_HD.FDXphDStart ASC ";
            // $tOrderBy2 = " ORDER BY B.FTXphTStart DESC,B.FTPdtCode DESC,B.FTPunCode DESC ";
        } else {
            $tOrderBy1 = " ADJP_HD.FTXphDocNo DESC ";
            // $tOrderBy1 = " ADJP_HD.FTPplCode ASC, ADJP_DT.FTPdtCode ASC, ADJP_DT.FTPunCode ASC, ADJP_HD.FDXphDStart ASC ";
            // $tOrderBy2 = " ORDER BY B.FTPplCode DESC, B.FTPdtCode ASC,B.FTPunCode ASC,B.FTXphDocNo ASC ";
        }

        // @$tSearchList = $oAdvanceSearch['tSearchAll'];
        // if(@$tSearchList != ''){
        //     $tWhere .= " AND ((ADJP_DT.FTPdtCode LIKE '%$tSearchList%') OR (PDTL.FTPdtName LIKE '%$tSearchList%') OR (PUNL.FTPunName LIKE '%$tSearchList%') OR (ADJP_HD.FTXphDocNo LIKE '%$tSearchList%') OR (CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) LIKE '%$tSearchList%') OR '%$tSearchList%' BETWEEN CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) AND CONVERT(VARCHAR(10),ADJP_HD.FDXphDStop,121))";
        // }

        // จากวันที่เอกสาร - ถึงวันที่เอกสาร
        $tSearchDocDateFrom = $oAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $oAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tWhere .= " AND ((CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) BETWEEN '$tSearchDocDateFrom' AND '$tSearchDocDateTo') OR (CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) BETWEEN '$tSearchDocDateTo' AND '$tSearchDocDateFrom'))";
        }

        // หน่วยสินค้า
        // $tPunCodeFrom   = $oAdvanceSearch['tPunCodeFrom'];
        // $tPunCodeTo     = $oAdvanceSearch['tPunCodeTo'];
        // if(!empty($tPunCodeFrom) && !empty($tPunCodeTo)){
        //     $tWhere .= " AND ((ADJP_DT.FTPunCode BETWEEN $tPunCodeFrom AND $tPunCodeTo) OR (ADJP_DT.FTPunCode BETWEEN $tPunCodeTo AND $tPunCodeFrom))";
        // }

        // วันที่มีผล
        // $tWhere .= " AND ( CONVERT(VARCHAR(10),GETDATE(),121) BETWEEN CONVERT(VARCHAR(10), ADJP_HD.FDXphDStart, 121) AND CONVERT(VARCHAR(10), ADJP_HD.FDXphDStop, 121) ) ";
        // $tWhere .= " AND ( CONVERT(VARCHAR(5),GETDATE(),114) BETWEEN ADJP_HD.FTXphTStart AND ADJP_HD.FTXphTStop ) ";

        // กลุ่มราคาที่มีผล
        $tPplCodeFrom   = $oAdvanceSearch['tPplCodeFrom'];
        $tPplCodeTo     = $oAdvanceSearch['tPplCodeTo'];
        if (!empty($tPplCodeFrom) && !empty($tPplCodeTo)) {
            if ($tPplCodeFrom == 'NA' && $tPplCodeTo != 'NA') {
                $tWhere .= " AND (ADJP_HD.FTPplCode = '' OR ADJP_HD.FTPplCode IS NULL) ";
                $tWhere .= " AND ADJP_HD.FTPplCode = '$tPplCodeTo' ";
            }

            if ($tPplCodeFrom != 'NA' && $tPplCodeTo == 'NA') {
                $tWhere .= " AND (ADJP_HD.FTPplCode = '' OR ADJP_HD.FTPplCode IS NULL) ";
                $tWhere .= " AND ADJP_HD.FTPplCode = '$tPplCodeFrom' ";
            }

            if ($tPplCodeFrom != 'NA' && $tPplCodeTo != 'NA') {
                $tWhere .= " AND ((ADJP_HD.FTPplCode BETWEEN $tPplCodeFrom AND $tPplCodeTo) OR (ADJP_HD.FTPplCode BETWEEN $tPplCodeTo AND $tPplCodeFrom)) ";
            }

            if ($tPplCodeFrom == 'NA' && $tPplCodeTo == 'NA') {
                $tWhere .= " AND (ADJP_HD.FTPplCode = '' OR ADJP_HD.FTPplCode IS NULL) ";
            }
        }

        if ($this->session->userdata("tSesUsrLevel") != "HQ") {
            $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
            $tWhere .= " AND ADJP_HD.FTBchCode IN ($tBchCodeMulti) ";

            //ถ้ามี Mer ต้อง Where Mer เพิ่ม
            if ($this->session->userdata("tSesUsrMerCode") != '') {
                $tMerCode = $this->session->userdata("tSesUsrMerCode");
                $tWhere .= " AND PDTSPC.FTMerCode IN ($tMerCode) ";
            }
        }

        $tSQLMain = "   SELECT ROW_NUMBER () OVER ( ORDER BY $tOrderBy1 ) FNRowID,
                        ADJP_DT.FTPdtCode,
                        PDTL.FTPdtName,
                        ADJP_DT.FTPunCode,
                        PUNL.FTPunName,
                        CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) AS FDXphDStart,
                        CASE WHEN ADJP_HD.FTXphDocType = '2' THEN CONVERT(VARCHAR(10),ADJP_HD.FDXphDStop,121) ELSE '-' END AS FDXphDStop,
                        ADJP_HD.FTXphTStart,
                        ADJP_HD.FTXphDocType,
                        ADJP_HD.FTXphTStop,
                        ADJP_DT.FCXpdPriceRet,
                        ADJP_HD.FTPplCode,
                        PL.FTPplName,
                        ADJP_HD.FTXphDocNo,
                        CONVERT(VARCHAR(10),ADJP_HD.FDXphDocDate,121) AS FDXphDocDate ";

        $tSQLCount = " SELECT COUNT(ADJP_HD.FTXphDocNo) AS FNCountData ";

        $tSQL1 = "  SELECT B.* FROM ( ";
        $tSQL2 = "  FROM TCNTPdtAdjPriHD ADJP_HD WITH (NOLOCK)
                    INNER JOIN TCNTPdtAdjPriDT ADJP_DT WITH (NOLOCK) ON ADJP_DT.FTXphDocNo = ADJP_HD.FTXphDocNo AND ADJP_DT.FTBchCode = ADJP_HD.FTBchCode
                    LEFT JOIN TCNMPdtUnit_L PUNL WITH (NOLOCK)   ON ADJP_DT.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID = $nLngID
                    LEFT JOIN TCNMPdt_L PDTL WITH (NOLOCK) ON ADJP_DT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID    = $nLngID
                    LEFT JOIN TCNMPdtPriList_L PL WITH (NOLOCK) ON ADJP_HD.FTPplCode = PL.FTPplCode AND PL.FNLngID = $nLngID
                    LEFT JOIN TCNMPdtSpcBch PDTSPC WITH (NOLOCK) ON PDTSPC.FTPdtCode = PDTL.FTPdtCode 
                    WHERE 1=1
                    AND ADJP_DT.FTPdtCode = '$FTPdtCode'
                    $tWhere
                ";
        $tSQL3 = " ) B WHERE B.FNRowID > $aRowLen[0] AND B.FNRowID <= $aRowLen[1] ";

        // Data
        $tFinalDataQuery = $tSQL1 . $tSQLMain . $tSQL2 . $tSQL3;
        $oQueryData = $this->db->query($tFinalDataQuery);

        // Count Data
        $tFinalCountQuery = $tSQLCount . $tSQL2;
        $oQueryCount = $this->db->query($tFinalCountQuery);

        //    echo  $tFinalDataQuery; 
        //    echo  $tFinalCountQuery; 
        //    die(); 

        if ($oQueryData->num_rows() > 0) {
            $aList          = $oQueryData->result_array();
            $aCount         = $oQueryCount->result_array();
            if ($paData['nPage'] == 1) {
                $nFoundRow      = $aCount[0]['FNCountData'];
            } else {
                $nFoundRow      = $paData['nPagePDTAll'];
            }
            $nPageAll       = ceil($nFoundRow / $paData['nRow']);
            $aResult = array(
                'raItems'       => $aList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        unset($aList);
        return $aResult;
    }

    //Functionality : Add PdtFashion
    //Parameters : function parameters
    //Creator : 27/04/2021 Nattakit
    //Return : Array Stutus Add Update
    //Return Type : Array
    public function FSaMPFHAddUpdateMasterDepartMent($paData)
    {
        try {
            //ต้องไปอัพเดท วันที่ + เวลา ที่ตาราง TCNMPdt ว่ามีการเปลี่ยนแปลงด้วย
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTPdtCode', $paData['FTPdtCode']);
            $this->db->update('TCNMPdt');

            $this->db->where('FTPdtCode', $paData['FTPdtCode']);
            $this->db->delete('TCNMPdtCategory');


            $aResult = array(
                'FTPdtCode'             => $paData['FTPdtCode'],
                'FTPdtCat1'             => $paData['FTDepCode'],
                'FTPdtCat2'             => $paData['FTClsCode'],
                'FTPdtCat3'             => $paData['FTSclCode'],
                'FTPdtCat4'            => $paData['FTPgpCode'],
                'FTPdtCat5'            => $paData['FTCmlCode'],
                'FTPdtModNo'            => $paData['FTFhnModNo'],
                'FTPdtGender'           => $paData['FTFhnGender'],
            );

            // Add Data Master
            $this->db->insert('TCNMPdtCategory', $aResult);

            if ($this->db->affected_rows() > 0) {
                $aStatus   = array(
                    'reCode'    => '1',
                    'rtDesc'    => 'Add Master Success',
                );
            } else {
                $aStatus = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Error Cannot Add MAster',
                );
            }


            $jStatus = json_encode($aStatus);
            $aStatus = json_decode($jStatus, true);
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }
}
