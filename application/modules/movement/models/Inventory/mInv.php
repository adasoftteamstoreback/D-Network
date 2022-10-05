<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class mInv extends CI_Model {

    //Functionality : list Data Movement
    //Parameters : function parameters
    //Creator :  10/03/2020 Saharat(Golf)
    //Last Modified : 15/04/2020 surawat
    //Return : data
    //Return Type : Array
    public function FSaMInvList($paData){
        $tWhereBch      = "";
        $tWherePdt      = "";
        $tWhereWah      = "";
        $tWhereBooking  = "";
        $SqlWhere       = "";
        $nLngID         = $paData['FNLngID'];
        $tBchCode       = $paData['tSearchAll']['tBchCode'];
        $tWahCode       = $paData['tSearchAll']['tWahCode'];
        $tPdtCode       = $paData['tSearchAll']['tPdtCode'];
        $nStaBooking    = $paData['tSearchAll']['nStaBooking'];
        $this->session->set_userdata('tDataFilter',$paData['tSearchAll']);

        // ########################## Check Branch Filter ##########################
        if($tBchCode != ""){
            $tBchCodeText= str_replace(",","','",$tBchCode);
            $tWhereBch  = "AND BAL.FTBchCode IN ('$tBchCodeText')";
        }else{
            $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
            if($tStaUsrLevel == 'HQ'){
                $SqlWhere   .= "";
            }else if($tStaUsrLevel == 'BCH'){
                $tBCH    = $this->session->userdata("tSesUsrBchCom");
                $SqlWhere   .= " AND BAL.FTBchCode = '$tBCH'";
            }
        }
        // ########################################################################

        // ######################### Check Product Filter #########################
        if($tPdtCode != ""){
            $tPdtCodeText= str_replace(",","','",$tPdtCode);
            $tWherePdt = "AND PDT.FTPdtCode IN ('$tPdtCodeText')";
        }
        // ########################################################################
        
        // ######################## Check Warhouse Filter #########################
        if($tWahCode != ""){
            $tWahCodeText= str_replace(",","','",$tWahCode);
            $tWhereWah = "AND  BAL.FTWahCode IN ('$tWahCodeText')";
        }
        // ########################################################################

        // ######################## Booking #########################
        if($nStaBooking != 1){
            $tWhereBooking = "AND  BKL.FCXtdQtyAll > 0";
        }
        // ########################################################################
        $SqlWhere   =  $tWhereBch.' '.$tWherePdt.' '.$tWhereWah;
    
        $tSQL    = "
            SELECT TOP ". get_cookie('nShowRecordInPageList')."
                PDT.FTPdtCode,
                PDT.FTPdtForSystem,
                PDT_L.FTPdtName,
                BCH.FTBchCode,
                BCH.FTBchName,
                BAL.FTWahCode,
                WAH.FTWahName,
                BAL.FCStkQty,
                BAL.FDCreateOn,
                ISNULL(ITA.FCXtdQtyAll,0)	AS FCXtdQtyInt,
                ISNULL(BKL.FCXtdQtyAll,0) AS FCXtdQtySbk,
                (ISNULL( BAL.FCStkQty, 0 ) - ISNULL(BKL.FCXtdQtyAll,0))+ISNULL(ITA.FCXtdQtyAll,0) AS FCXtdQtyBal
            FROM TCNTPdtStkBal      BAL     WITH(NOLOCK)
            LEFT JOIN TCNMPdt       PDT     WITH(NOLOCK)    ON BAL.FTPdtCode = PDT.FTPdtCode
            LEFT JOIN TCNMPdt_L     PDT_L   WITH(NOLOCK)    ON BAL.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L  BCH 	WITH(NOLOCK)	ON BAL.FTBchCode = BCH.FTBchCode  AND BCH.FNLngID       = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAH 	WITH(NOLOCK)    ON BAL.FTBchCode = WAH.FTBchCode AND BAL.FTWahCode  = WAH.FTWahCode AND WAH.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN (
                SELECT FTBchCode,FTXthWahTo,FTPdtCode,SUM(INT.FCXtdQtyAll) AS FCXtdQtyAll 
                FROM (
                    SELECT FTBchCode,FTXthWahTo,FTPdtCode,FCXtdQtyAll 
                    FROM TCNTPdtIntDT WITH(NOLOCK)
                    WHERE ISNULL( FTXtdRvtRef, '' ) = ''
                    UNION ALL
                    SELECT FTXthBchTo AS FTBchCode,FTXthWahTo,FTPdtCode,FCXtdQtyAll 
                    FROM TCNTPdtIntDTBCH WITH(NOLOCK)
                    WHERE ISNULL( FTXtdRvtRef, '' ) = '' 
                )INT 
                GROUP BY FTBchCode,FTXthWahTo,FTPdtCode
            ) ITA ON BAL.FTBchCode = ITA.FTBchCode AND BAL.FTWahCode = ITA.FTXthWahTo AND BAL.FTPdtCode = ITA.FTPdtCode
            LEFT JOIN (
                SELECT 
                    SBK.FTBchCode,
                    SBK.FTWahCode,
                    SBK.FTPdtCode,
                    SUM(SBK.FCStbQtyAll) AS FCXtdQtyAll
                FROM TSVTStkBooking SBK WITH(NOLOCK)	
                WHERE SBK.FDCreateOn <> '' AND (SBK.FTStbStaPdt = '' OR SBK.FTStbStaPdt = NULL)
                GROUP BY SBK.FTBchCode,SBK.FTWahCode,SBK.FTPdtCode
            ) BKL ON BAL.FTBchCode = BKL.FTBchCode AND BAL.FTWahCode = BKL.FTWahCode AND BAL.FTPdtCode = BKL.FTPdtCode
            WHERE BAL.FDCreateOn <> ''
        ";
        $tSQL   .=  $SqlWhere;
        $tSQL   .=  $tWhereBooking;
        $tSQL   .= " ORDER BY BAL.FDCreateOn DESC, PDT.FTPdtCode ASC, BCH.FTBchCode ASC, BAL.FTWahCode ASC";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            // No Data
            $aResult    = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        unset($tWhereBch,$tWherePdt,$tWhereWah,$tWhereBooking,$SqlWhere);
        unset($nLngID,$tBchCode,$tWahCode,$tPdtCode,$nStaBooking);
        unset($tSQL,$oQuery,$aList);
        unset($paData);
        return $aResult;
    }


    //Functionality : list Data Movement
    //Parameters : function parameters
    //Creator :  10/03/2020 Saharat(Golf)
    //Last Modified : 15/04/2020 surawat
    //Return : data
    //Return Type : Array
    public function FSaMInvPdtFhnList($paData){
        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tWhereBch      = "";
        $tWherePdt      = "";
        $tWhereWah      = "";
        $SqlWhere       = "";
        $nLngID         = $paData['FNLngID'];
        $tBchCode       = $paData['tSearchAll']['tBchCode'];
        $tWahCode       = $paData['tSearchAll']['tWahCode'];
        $tPdtCode       = $paData['tSearchAll']['tPdtCode'];
        $tFhnRefCode    = $paData['tSearchAll']['tFhnRefCode'];
        $tSeaCode       = $paData['tSearchAll']['tSeaCode'];
        $tSzeCode       = $paData['tSearchAll']['tSzeCode'];
        $tFabCode       = $paData['tSearchAll']['tFabCode'];

        if($tBchCode != ""){
            $tBchCodeText   = str_replace(",","','",$tBchCode);
            $tWhereBch      = "AND BAL.FTBchCode IN ('$tBchCodeText')";
            $tJoinBch       = "  TFHTPdtStkBal.FTBchCode = BAL.FTBchCode AND ";
            $tGroupByBch    = "  TFHTPdtStkBal.FTBchCode, ";
        }else{
            $tStaUsrLevel   = $this->session->userdata("tSesUsrLevel");
            if($tStaUsrLevel == 'HQ'){
                $SqlWhere       .= "";
                $tJoinBch        = "";
                $tGroupByBch     = "";
            }else if($tStaUsrLevel == 'BCH'){
                $tBCH            = $this->session->userdata("tSesUsrBchCom");
                $SqlWhere       .= " AND BAL.FTBchCode = '$tBCH'";
                $tJoinBch        = "  TFHTPdtStkBal.FTBchCode = BAL.FTBchCode AND ";
                $tGroupByBch     = "  TFHTPdtStkBal.FTBchCode, ";
            }
        }

        if($tPdtCode != ""){
            $tPdtCodeText   = str_replace(",","','",$tPdtCode);
            $tWherePdt      = "AND PDT.FTPdtCode IN ('$tPdtCodeText')";
        }

        if($tWahCode != ""){
            $tWahCodeText   = str_replace(",","','",$tWahCode);
            $tWhereWah      = "AND  BAL.FTWahCode IN ('$tWahCodeText')";
        }


        if($tFhnRefCode != ""){
            $tWhereWah  .= " AND  BAL.FTFhnRefCode ='$tFhnRefCode' ";
        }

        if($tSeaCode != ""){
            $tWhereWah  .= " AND  PDTCS.FTSeaCode ='$tSeaCode' ";
        }

        if($tSzeCode != ""){
            $tWhereWah  .= " AND  PDTCS.FTPszCode ='$tSzeCode' ";
        }

        if($tFabCode != ""){
            $tWhereWah  .= " AND  PDTCS.FTFabCode ='$tFabCode' ";
        }

        $SqlWhere   =  $tWhereBch.' '.$tWherePdt.' '.$tWhereWah;
        $tMainQuery = "
            SELECT
                ROW_NUMBER () OVER (PARTITION BY BAL.FTFhnRefCode ORDER BY BAL.FTPdtCode,BAL.FTFhnRefCode,BAL.FTBchCode,BAL.FTWahCode ASC) AS RowNumberSub,
                PDT.FTPdtCode,
                PDT.FTPdtForSystem,
                PDT_L.FTPdtName, 
                BCH.FTBchCode,
                BCH.FTBchName,
                BAL.FTWahCode,
                WAH.FTWahName,
                BAL.FTFhnRefCode,
                PDTCS.FTSeaCode,
                PDTCS.FTPszCode,
                PDTCS.FTClrCode,
                PDTCS.FTFabCode,
                SEA_L.FTSeaChainName,
                FAB_L.FTFabName,
                PDTCLR_L.FTClrName,
                PDTSZE_L.FTPszName,
                BAL.FCStfBal,
                BAL.FDCreateOn,
                0 AS FCXtdQtyInt,
                ISNULL(BAL.FCStfBal,0) + 0 AS FCXtdQtyBal,
                ( SELECT SUM(TFHTPdtStkBal.FCStfBal) AS FCStfBal  FROM TFHTPdtStkBal WHERE $tJoinBch TFHTPdtStkBal.FTPdtCode = BAL.FTPdtCode AND TFHTPdtStkBal.FTFhnRefCode = BAL.FTFhnRefCode GROUP BY  $tGroupByBch TFHTPdtStkBal.FTPdtCode,TFHTPdtStkBal.FTFhnRefCode ) AS FCStBalPdt,
                0 AS FCStBalIntPdt,
                ISNULL((SELECT SUM(TFHTPdtStkBal.FCStfBal) AS FCStfBal  FROM TFHTPdtStkBal WHERE $tJoinBch TFHTPdtStkBal.FTPdtCode = BAL.FTPdtCode AND TFHTPdtStkBal.FTFhnRefCode = BAL.FTFhnRefCode GROUP BY  $tGroupByBch TFHTPdtStkBal.FTPdtCode ,TFHTPdtStkBal.FTFhnRefCode),0) + 0 AS FCXtdQtyPdtBal
            FROM TFHTPdtStkBal BAL WITH(NOLOCK)
            LEFT JOIN TCNMPdt PDT WITH(NOLOCK) ON BAL.FTPdtCode = PDT.FTPdtCode
            LEFT JOIN TCNMPdt_L PDT_L WITH(NOLOCK) ON BAL.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID = $nLngID 
            LEFT JOIN TCNMBranch_L BCH WITH(NOLOCK) ON BAL.FTBchCode = BCH.FTBchCode AND BCH.FNLngID = $nLngID 
            LEFT JOIN TCNMWaHouse_L WAH WITH(NOLOCK) ON BAL.FTBchCode = WAH.FTBchCode AND BAL.FTWahCode = WAH.FTWahCode AND WAH.FNLngID = $nLngID 
            LEFT JOIN TFHMPdtColorSize PDTCS WITH(NOLOCK) ON BAL.FTPdtCode = PDTCS.FTPdtCode AND  BAL.FTFhnRefCode = PDTCS.FTFhnRefCode AND PDTCS.FNFhnSeq = 1
            LEFT JOIN TFHMPdtSeason_L SEA_L WITH(NOLOCK) ON PDTCS.FTSeaCode = SEA_L.FTSeaChain AND SEA_L.FNLngID = $nLngID 
            LEFT JOIN TFHMPdtFabric_L FAB_L WITH(NOLOCK) ON PDTCS.FTFabCode = FAB_L.FTFabCode AND FAB_L.FNLngID = $nLngID 
            LEFT JOIN TCNMPdtColor_L PDTCLR_L WITH(NOLOCK) ON PDTCS.FTClrCode = PDTCLR_L.FTClrCode AND PDTCLR_L.FNLngID = $nLngID 
            LEFT JOIN TCNMPdtSize_L PDTSZE_L WITH(NOLOCK) ON PDTCS.FTPszCode = PDTSZE_L.FTPszCode AND PDTSZE_L.FNLngID = $nLngID 
            WHERE 1=1 $SqlWhere
        ";


        $tSQL = "
            SELECT c.* FROM (
                SELECT
                    ROW_NUMBER() OVER(PARTITION BY FTFhnRefCode ORDER BY FTPdtCode, FTFhnRefCode , FTBchCode, FTWahCode ASC) AS RowNumber,
                    ROW_NUMBER() OVER(PARTITION BY FTFhnRefCode , FTBchCode ORDER BY FTPdtCode, FTFhnRefCode , FTBchCode, FTWahCode ASC) AS RowNumberByBch,
                    ROW_NUMBER() OVER(ORDER BY FTPdtCode ,FTFhnRefCode, FTBchCode, FTWahCode ASC) AS rtRowID,* FROM
                (
                    $tMainQuery
        ";
    
        $tSQL   .= " ) Base";


        $tSQL   .= " LEFT JOIN ( SELECT FTPdtCode AS SumPdtCode,FTFhnRefCode AS SumRefCode,SUM(FCXtdQtyBal) AS SumQtyBal,SUM(FCStBalIntPdt) AS SumStBalIntPdt,SUM(FCXtdQtyPdtBal) AS SumQtyPdtBal , 	MAX (RowNumberSub) AS LastBchRowID FROM  (";
        $tSQL   .=  $tMainQuery ;
        $tSQL   .= ") SumFooter GROUP BY FTPdtCode,FTFhnRefCode ) SUM_FOOTER ON  Base.FTPdtCode = SUM_FOOTER.SumPdtCode AND  Base.FTFhnRefCode=SUM_FOOTER.SumRefCode";

            $tSQL .= ") AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";


        $tSQL   .= " ORDER BY  FTPdtCode,FTFhnRefCode, FTBchCode, FTWahCode ASC";
    

        $oQuery = $this->db->query($tSQL);
        
        if($oQuery->num_rows() > 0){
            $aList      = $oQuery->result_array();
            $oFoundRow  = $this->FSoMInvPdtFhnGetPageAll($SqlWhere,$nLngID);
            $nFoundRow  = $oFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $aList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            //No Data
            $aResult    = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"=> 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }

        unset($aRowLen,$tWhereBch,$tWherePdt,$tWhereWah,$SqlWhere,$nLngID,$tBchCode,$tWahCode,$tPdtCode,$tFhnRefCode,$tSeaCode,$tSzeCode,$tFabCode);
        unset($tMainQuery,$tSQL,$oQuery,$aList,$oFoundRow,$nFoundRow,$nPageAll);
        return $aResult;
    }

    //Functionality : All Page Of Movement
    //Parameters : function parameters
    //Creator :  11/03/2020 Saharat(Golf)
    //Last Modified : 15/04/2020 surawat
    //Return : object Count All Movement
    //Return Type : Object
    public function FSoMInvPdtFhnGetPageAll($SqlWhere,$ptLngID){
        $tSQL = "
            SELECT COUNT (*) AS counts
            FROM TFHTPdtStkBal BAL
            LEFT JOIN TCNMPdt_L PDT ON BAL.FTPdtCode = PDT.FTPdtCode AND PDT.FNLngID = $ptLngID
            LEFT JOIN TCNMBranch_L BCH ON BAL.FTBchCode = BCH.FTBchCode AND PDT.FNLngID = $ptLngID
            LEFT JOIN TCNMWaHouse_L WAH ON BAL.FTBchCode = WAH.FTBchCode AND BAL.FTWahCode = WAH.FTWahCode AND WAH.FNLngID = $ptLngID
            LEFT JOIN TFHMPdtColorSize PDTCS ON BAL.FTPdtCode = PDTCS.FTPdtCode AND  BAL.FTFhnRefCode = PDTCS.FTFhnRefCode AND PDTCS.FNFhnSeq = 1
            WHERE 1=1 $SqlWhere 
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataReturn    = $oQuery->result();
        }else{
            $aDataReturn    = false;
        }
        unset($SqlWhere,$ptLngID);
        unset($tSQL,$oQuery);
        return $aDataReturn;
    }


    //Functionality : All Page Of Movement
    //Parameters : function parameters
    //Creator :  11/03/2020 Saharat(Golf)
    //Last Modified : 15/04/2020 surawat
    //Return : object Count All Movement
    //Return Type : Object
    public function FSaMInvPdtFhnColorSizeActive($paData){
        $nLngID         = $this->session->userdata("tLangEdit");
        $tPdtCode       = $paData['tPdtCode'];
        $nPdtFhnStaUse  = $paData['nPdtFhnStaUse'];
        $tWhereStaActive = "";
        if(!empty($nPdtFhnStaUse)){
            $tWhereStaActive .= " AND PDTCS.FTFhnStaActive = '$nPdtFhnStaUse'";
        }
        $tSQL   = "
            SELECT
                PDTCS.FTFhnRefCode,
                SEA_L.FTSeaChainName,
                PDTCLR_L.FTClrName,
                PDTSZE_L.FTPszName,
                FAB_L.FTFabName,
                PDTCS.FTSeaCode,
                PDTCS.FTPszCode,
                PDTCS.FTClrCode,
                PDTCS.FTFabCode
            FROM TFHMPdtColorSize PDTCS WITH(NOLOCK)
            LEFT OUTER JOIN TFHMPdtSeason_L SEA_L ON PDTCS.FTSeaCode = SEA_L.FTSeaChain AND SEA_L.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT OUTER JOIN TFHMPdtFabric_L FAB_L ON PDTCS.FTFabCode = FAB_L.FTFabCode AND FAB_L.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT OUTER JOIN TCNMPdtColor_L PDTCLR_L ON PDTCS.FTClrCode = PDTCLR_L.FTClrCode AND PDTCLR_L.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT OUTER JOIN TCNMPdtSize_L PDTSZE_L ON PDTCS.FTPszCode = PDTSZE_L.FTPszCode AND PDTSZE_L.FNLngID = ".$this->db->escape($nLngID)."
            WHERE PDTCS.FTPdtCode = '$tPdtCode'
            $tWhereStaActive
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataReturn    = $oQuery->result_array();
        }else{
            $aDataReturn    = false;
        }
        unset($nLngID,$tPdtCode,$nPdtFhnStaUse,$tWhereStaActive,$tSQL,$oQuery);
        return $aDataReturn;
    }



    /**
     * Functionality: ดึงรายละเอียดข้อมูล การจอง Stock Booking By ID
     * Parameters:  Function Parameter
     * Creator: 04/04/2022 Wasin(Yoshi)
     * LastUpdate:
     * Return: file
     * ReturnType: file
    */
    public function FSaMInvStkBookDetailHD($paData){
        $tBchCode   = $paData['FTBchCode'];
        $tWahCode   = $paData['FTWahCode'];
        $tPdtCode   = $paData['FTPdtCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT DISTINCT
                PDT.FTPdtCode,
                PDT.FTPdtForSystem,
                PDT_L.FTPdtName,
                BCH.FTBchCode,
                BCH.FTBchName,
                BAL.FTWahCode,
                WAH.FTWahName,
                BAL.FCStkQty,
                BAL.FDCreateOn,
                ISNULL(ITA.FCXtdQtyAll,0)	AS FCXtdQtyInt,
                ISNULL(BKL.FCXtdQtyAll,0) AS FCXtdQtySbk,
                (ISNULL( BAL.FCStkQty, 0 ) - ISNULL(BKL.FCXtdQtyAll,0))+ISNULL(ITA.FCXtdQtyAll,0) AS FCXtdQtyBal
            FROM TCNTPdtStkBal      BAL     WITH(NOLOCK)
            LEFT JOIN TCNMPdt       PDT     WITH(NOLOCK)    ON BAL.FTPdtCode = PDT.FTPdtCode
            LEFT JOIN TCNMPdt_L     PDT_L   WITH(NOLOCK)    ON BAL.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L  BCH 	WITH(NOLOCK)	ON BAL.FTBchCode = BCH.FTBchCode  AND BCH.FNLngID       = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAH 	WITH(NOLOCK)    ON BAL.FTBchCode = WAH.FTBchCode AND BAL.FTWahCode      = WAH.FTWahCode AND WAH.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN (
                SELECT FTBchCode,FTXthWahTo,FTPdtCode,SUM(INT.FCXtdQtyAll) AS FCXtdQtyAll 
                FROM (
                    SELECT FTBchCode,FTXthWahTo,FTPdtCode,FCXtdQtyAll 
                    FROM TCNTPdtIntDT WITH(NOLOCK)
                    WHERE ISNULL( FTXtdRvtRef, '' ) = ''
                    UNION ALL
                    SELECT FTXthBchTo AS FTBchCode,FTXthWahTo,FTPdtCode,FCXtdQtyAll 
                    FROM TCNTPdtIntDTBCH WITH(NOLOCK)
                    WHERE ISNULL( FTXtdRvtRef, '' ) = '' 
                )INT 
                GROUP BY FTBchCode,FTXthWahTo,FTPdtCode
            ) ITA ON BAL.FTBchCode = ITA.FTBchCode AND BAL.FTWahCode = ITA.FTXthWahTo AND BAL.FTPdtCode = ITA.FTPdtCode
            LEFT JOIN (
                SELECT 
                    SBK.FTBchCode,
                    SBK.FTWahCode,
                    SBK.FTPdtCode,
                    SUM(SBK.FCStbQtyAll) AS FCXtdQtyAll
                FROM TSVTStkBooking SBK WITH(NOLOCK)	
                WHERE SBK.FDCreateOn <> '' AND (SBK.FTStbStaPdt = '' OR SBK.FTStbStaPdt = NULL)
                GROUP BY SBK.FTBchCode,SBK.FTWahCode,SBK.FTPdtCode
            ) BKL ON BAL.FTBchCode = BKL.FTBchCode AND BAL.FTWahCode = BKL.FTWahCode AND BAL.FTPdtCode = BKL.FTPdtCode
            WHERE BAL.FDCreateOn <> ''
            AND BAL.FTBchCode   = ".$this->db->escape($tBchCode)."
            AND  BAL.FTWahCode  = ".$this->db->escape($tWahCode)."
            AND BAL.FTPdtCode   = ".$this->db->escape($tPdtCode)."
            ORDER BY FDCreateOn DESC, FTPdtCode, FTBchCode, FTWahCode ASC
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aList      = $oQuery->row_array();
            $aResult    = array(
                'raItems'       => $aList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($tBchCode,$tWahCode,$tPdtCode,$nLngID);
        unset($tSQL,$oQuery,$aList);
        unset($paData);
        return $aResult;
    }

    /**
     * Functionality: ดึงรายละเอียดข้อมูล การจอง Stock Booking
     * Parameters:  Function Parameter
     * Creator: 04/04/2022 Wasin(Yoshi)
     * LastUpdate:
     * Return: file
     * ReturnType: file
    */
    public function FSaMInvStkBookDetailDT($paData){
        $tBchCode   = $paData['FTBchCode'];
        $tWahCode   = $paData['FTWahCode'];
        $tPdtCode   = $paData['FTPdtCode'];
        $tSQL       = "
            SELECT DISTINCT
                SBK.FTBchCode,
                SBK.FTWahCode,
                SBK.FTPdtCode,
                SBK.FTStbDocRef,
                SBK.FCStbQty,
                SBK.FCStbQtyAll,
                SBK.FDCreateOn,
                SBK.FTStbRefKey
            FROM TSVTStkBooking SBK WITH(NOLOCK)
            WHERE SBK.FDCreateOn <> '' 
            AND (SBK.FTStbStaPdt = '' OR SBK.FTStbStaPdt = NULL ) 
            AND (SBK.FTBchCode  = ".$this->db->escape($tBchCode).")
            AND (SBK.FTWahCode  = ".$this->db->escape($tWahCode).")
            AND (SBK.FTPdtCode  = ".$this->db->escape($tPdtCode).")
            ORDER BY SBK.FDCreateOn DESC
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems'       => $aList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($tBchCode,$tWahCode,$tPdtCode,$tSQL,$oQuery,$aList);
        return $aResult;
    }

    /**
     * Functionality: Get Data Table
     * Parameters:  Function Parameter
     * Creator: 05/04/2022 Wasin(Yoshi)
     * LastUpdate:
     * Return: file
     * ReturnType: file
    */
    public function FSaMInvStkBookGetDataPkTbl($tTbleName){
        $tSQL   = "
            SELECT DATATBL.*
            FROM (
                SELECT 
                    KU.table_name   AS FTTblName,
                    column_name 	AS FTTblPkName,
                    SUBSTRING(column_name,3,3) AS FTTblRefkey
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC 
                INNER JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KU ON TC.CONSTRAINT_TYPE = 'PRIMARY KEY' AND TC.CONSTRAINT_NAME = KU.CONSTRAINT_NAME AND KU.table_name = ".$this->db->escape($tTbleName)."
            ) DATATBL
            WHERE DATATBL.FTTblPkName LIKE '%hDocNo%'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aList  = $oQuery->row_array();
        }else{
            $aList  = [];
        }
        unset($tSQL,$oQuery);
        return $aList;
    }

    /**
     * Functionality    : Get Data Table
     * Parameters       :  Function Parameter
     * Creator          : 05/04/2022 Wasin(Yoshi)
     * LastUpdate       :
     * Return           : file
     * ReturnType       : file
    */
    public function FSaMInvStkBookGetDataDoc($paDataWhereDoc){
        $tTableTitle    = $paDataWhereDoc['tDocName'];
        $tTableName     = $paDataWhereDoc['tTblName'];
        $tTableNamePK   = $paDataWhereDoc['tTblPKField'];
        $tDocNoRef      = $paDataWhereDoc['tDocNoRef'];
        $tDocRefKey     = $paDataWhereDoc['tTblRefKey'];
        $tStkQty        = $paDataWhereDoc['tStkBklQtyAll'];
        $tSQL   = "
            SELECT 
                ".$this->db->escape($tTableTitle)."     AS rtDocName,
                ".$this->db->escape($tDocNoRef)."       AS rtDocNo,
                CONVERT(CHAR(10),DOC.FD".$tDocRefKey."DocDate,103)   AS rtDocDate,
                DOC.FT".$tDocRefKey."StaDoc AS rtStaDoc,
                DOC.FT".$tDocRefKey."StaApv AS rtStaApv,
                ".$tStkQty."                AS rtQtyStkBkl,
                DOCREF.FT".$tDocRefKey."RefDocNo 		AS rtRefDocNo,
	            DOCREF.FD".$tDocRefKey."RefDocDate	AS rtRefDocDate
            FROM ".$tTableName." DOC WITH(NOLOCK)
            LEFT JOIN ".$tTableName."DocRef DOCREF WITH(NOLOCK) ON DOC.FTBchCode = DOCREF.FTBchCode AND DOC.".$tTableNamePK." = DOCREF.".$tTableNamePK." AND DOCREF.FT".$tDocRefKey."RefType = 2
            WHERE DOC.".$tTableNamePK."  = ".$this->db->escape($tDocNoRef)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aList  = $oQuery->row_array();
        }else{
            $tSQL   = "
            SELECT 
                ".$this->db->escape($tTableTitle)."     AS rtDocName,
                ".$this->db->escape($tDocNoRef)."       AS rtDocNo,
                CONVERT(CHAR(10),DOC.FDCreateOn,103)   AS rtDocDate,
                '9' AS rtStaApv,
                FCStbQtyAll                AS rtQtyStkBkl
            FROM TSVTStkBooking DOC WITH(NOLOCK)
            WHERE DOC.FTStbDocRef  = ".$this->db->escape($tDocNoRef)."
            ";
            $oQuery = $this->db->query($tSQL);
            $aList  = $oQuery->row_array();
        }
        unset($tTableTitle,$tTableName,$tTableNamePK,$tDocNoRef,$tDocRefKey,$tStkQty,$tSQL,$oQuery);
        return $aList;
    }

}