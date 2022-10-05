<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mBrowserPDTCallView extends CI_Model
{

    //#################################################### PDT VIEW HQ #################################################### 

    //PDT - สำหรับ VIEW HQ + ข้อมูล
    public function FSaMGetProductHQ($ptFilter, $ptLeftJoinPrice, $paData, $pnTotalResult){
        try {
            $aRowLen            = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
            $nLngID             = $this->session->userdata("tLangEdit");

            if ($paData['aPriceType'][0] == 'Pricesell') {
                //ถ้าเป็นราคาขาย
                $tSelectFiledPrice  = "0 AS FCPgdPriceNet, ";
                $tSelectFiledPrice .= "0 AS FCPgdPriceRet, ";
                $tSelectFiledPrice .= "0 AS FCPgdPriceWhs ";
            } else if ($paData['aPriceType'][0] == 'Price4Cst') {
                //ถ้าเป็นราคาลูกค้า
                $tSelectFiledPrice = '  0 AS FCPgdPriceNet ,
                                        0 AS FCPgdPriceWhs ,
                                        CASE 
                                            WHEN ISNULL(PCUS.FCPgdPriceRet,0) <> 0 THEN PCUS.FCPgdPriceRet
                                            WHEN ISNULL(PBCH.FCPgdPriceRet,0) <> 0 THEN PBCH.FCPgdPriceRet
                                            WHEN ISNULL(PEMPTY.FCPgdPriceRet,0) <> 0 THEN PEMPTY.FCPgdPriceRet
                                            ELSE 0 
                                        END AS FCPgdPriceRet ';
            } else if ($paData['aPriceType'][0] == 'Cost') {
                //ถ้าเป็นราคาทุน
                $tSelectFiledPrice  = "ISNULL(FCPdtCostStd,0)       AS FCPdtCostStd    , ISNULL(FCPdtCostAVGIN,0)     AS FCPdtCostAVGIN , ";
                $tSelectFiledPrice .= "ISNULL(FCPdtCostAVGEx,0)     AS FCPdtCostAVGEx  , ISNULL(FCPdtCostLast,0)      AS FCPdtCostLast, ";
                $tSelectFiledPrice .= "ISNULL(FCPdtCostFIFOIN,0)    AS FCPdtCostFIFOIN , ISNULL(FCPdtCostFIFOEx,0)    AS FCPdtCostFIFOEx ";
            }else{
                $tSelectFiledPrice  = "0 AS FCPgdPriceNet, ";
                $tSelectFiledPrice .= "0 AS FCPgdPriceRet, ";
                $tSelectFiledPrice .= "0 AS FCPgdPriceWhs ";
            }

            //ไม่ได้ส่งผู้จำหน่ายมา
            if ($paData['tSPL'] == '' || $paData['tSPL'] == null) {
                $tSqlSPL        = " ROW_NUMBER() OVER (PARTITION BY ProductM.FTPdtCode ORDER BY ProductM.FTPdtCode) AS FNPdtPartition , ";
                $tSqlWHERESPL   = '';
            } else {
                $tSqlSPL        = '';
                $tSqlWHERESPL   = '';
            }

            //Call View Sql
            $tSQL       = "SELECT c.* FROM ( ";
            $tSQL      .= "SELECT";
            $tSQL      .= " ROW_NUMBER() OVER(ORDER BY Products.FTPdtCode ASC) AS FNRowID , Products.* FROM (";
            $tSQL      .= "SELECT";
            $tSQL      .= "$tSqlSPL";
            $tSQL      .= " ProductM.*, " . $tSelectFiledPrice . " FROM ( ";
            $tSQL      .= " SELECT * FROM (";
            $tSQL      .= " SELECT *  , ROW_NUMBER() OVER(ORDER BY FTPdtCode ASC) AS ROWSUB from VCN_ProductsHQ WHERE FNLngIDPdt = '$nLngID' ";
            $tSQL      .= str_replace('Products.', '', $ptFilter);
            $tSQL      .= " ) MAINPDT WHERE 1=1 ";
            $tSQL      .= " ) AS ProductM";
            $tSQL      .= $ptLeftJoinPrice . " WHERE ProductM.ROWSUB > $aRowLen[0] and ProductM.ROWSUB <= $aRowLen[1] ";
            $tSQL      .= " ) AS Products WHERE 1=1 ";
            $tSQL      .= $tSqlWHERESPL;
            $tSQL      .= " ) AS c ";
            $tSQL      .= " WHERE 1=1 ";
            
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aList      = $oQuery->result_array();
                if ($paData['nPage'] == 1) {
                    //ถ้าเป็น page 1 ต้องวิ่งไปหาทั้งหมด
                    if ($paData['tFindOnlyPDT'] == 'normal') {
                        $oFoundRow  = $this->FSnMSPRGetPageAllByPDTHQ($tSQL, $ptFilter, 'SOME');
                    } else {
                        $oFoundRow  = 1;
                    }
                    $nFoundRow  = $oFoundRow;
                    if ($oFoundRow > 5000) {
                        $nPDTAll  = $this->FSnMSPRGetPageAllByPDTHQ($tSQL, $ptFilter, 'ALL');
                    } else {
                        $nPDTAll = 0;
                    }
                } else {
                    //ถ้า page 2 3 4 5 6 7 8 9 เราอยู่เเล้ว ว่ามัน total_page เท่าไหร่
                    $nFoundRow = $pnTotalResult;
                    $nPDTAll  = 0;
                }

                $nPageAll   = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                    'sql'           => $tSQL,
                    'nPDTAll'       => $nPDTAll,
                    'nRow'          => $paData['nRow']
                );
            } else {
                $aResult    = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found',
                    'sql'           => $tSQL,
                    'nPDTAll'       => 0,
                    'nRow'          => $paData['nRow']
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Count PDT - สำหรับ VIEW HQ + จำนวนเเถว
    public function FSnMSPRGetPageAllByPDTHQ($tSQL, $ptFilter, $ptType){
        $nLngID     = $this->session->userdata("tLangEdit");

        //เก็บข้อมูลลง  Cookie
        $nCheckPage  =  $this->input->cookie("PDTCookie_" . $this->session->userdata("tSesUserCode"), true);
        $tCookieVal = json_decode($nCheckPage);

        if (!empty($nCheckPage)) {
            $nMaxTopPage = $tCookieVal->nMaxPage;
        } else {
            $nMaxTopPage = '';
        }

        if ($nMaxTopPage == '' || null) {
            $nMaxTopPage = '5000';
        }

        $nMaxTopPage   = str_replace(',', '', $nMaxTopPage);

        if ($ptType == 'SOME') {
            $tSQL       = "SELECT TOP $nMaxTopPage FTPDTCode FROM ";
        } else if ($ptType == 'ALL') {
            $tSQL       = "SELECT FTPDTCode FROM ";
        }

        $tSQL       .= "( 
                            SELECT  TCNMPdt.FTPdtCode , TCNMPdt.FTPdtStaActive , TCNMPdt_L.FTPdtName , TCNMPdtBar.FTBarCode , TCNMPdt.FTPtyCode , 
                                    TCNMPDTSpl.FTSplCode,TCNMPdt.FTPdtStaAlwDis,TCNMPdtSpcBch.FTAgnCode, TCNMPdt.FTPdtType, TCNMPdt.FTPdtSetOrSN ,
                                    TCNMPdt.FTPgpChain , SUBPDT_SPL.FTUsrCode AS FTBuyer
                            FROM TCNMPdt
                            LEFT JOIN TCNMPdtSpcBch ON TCNMPdt.FTPdtCode = TCNMPdtSpcBch.FTPdtCode
                            LEFT JOIN TCNMPdtPackSize ON TCNMPdt.FTPdtCode = TCNMPdtPackSize.FTPdtCode
                            LEFT JOIN TCNMPdtBar ON TCNMPdtBar.FTPdtCode = TCNMPdtPackSize.FTPdtCode AND TCNMPdtBar.FTPunCode = TCNMPdtPackSize.FTPunCode
                            LEFT JOIN TCNMPDTSpl ON TCNMPdt.FTPdtCode = TCNMPDTSpl.FTPdtCode AND TCNMPdtBar.FTBarCode = TCNMPDTSpl.FTBarCode
                            LEFT JOIN TCNMPdtUnit_L ON TCNMPdtUnit_L.FTPunCode = TCNMPdtPackSize.FTPunCode AND TCNMPdtUnit_L.FNLngID = '$nLngID'
                            INNER JOIN TCNMPdt_L ON TCNMPdt.FTPdtCode = TCNMPdt_L.FTPdtCode AND TCNMPdt_L.FNLngID = '$nLngID'
                            LEFT OUTER JOIN (
                                SELECT
                                    PDT_SPL.FTPdtCode,
                                    PDT_SPL.FTSplCode,
                                    PDT_BAR.FTPunCode,
                                    PDT_SPL.FTUsrCode
                                FROM
                                    dbo.TCNMPdtSpl AS PDT_SPL
                                INNER JOIN dbo.TCNMPdtBar AS PDT_BAR ON PDT_SPL.FTBarCode = PDT_BAR.FTBarCode AND PDT_BAR.FTPdtCode = PDT_SPL.FTPdtCode
                            ) AS SUBPDT_SPL ON TCNMPdtPackSize.FTPdtCode = SUBPDT_SPL.FTPdtCode AND TCNMPdtPackSize.FTPunCode = SUBPDT_SPL.FTPunCode
                        ) AS Products WHERE 1=1 ";
        $tSQL       .= $ptFilter;
        $oQuery     = $this->db->query($tSQL);

        if ($oQuery->num_rows() >= $nMaxTopPage) {
            $nRow = $nMaxTopPage;
        } else if ($oQuery->num_rows() < $nMaxTopPage) {
            $nRow = $oQuery->num_rows();
        } else {
            $nRow = 0;
        }
        return $nRow;
    }

    //#################################################### PDT VIEW BCH ###################################################

    //PDT - สำหรับ VIEW BCH + ข้อมูล
    public function FSaMGetProductBCH($ptFilter, $ptLeftJoinPrice, $paData, $pnTotalResult , $aDataParamExe)
    {
        try {
                $tSesUserCode = $this->session->userdata('tSesUserCode');
                // $tSesUsrAgnCode     = $this->session->userdata('tSesUsrAgnCode');
                // $tBchSession        = $this->session->userdata("tSesUsrBchCodeMulti");
                // $tShpSession        = $this->session->userdata("tSesUsrShpCodeMulti");
                // $tMerSession        = $this->session->userdata("tSesUsrMerCode");
                $aRowLen            = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
                $nLngID             = $this->session->userdata("tLangEdit");
                $aDataUsrGrp        = $this->db->where('FTUsrCode',$tSesUserCode)->get('TCNTUsrGroup')->row_array();
                $tDefaultBchCode    = $aDataUsrGrp['FTBchCode'];

                $tSQL = '';

                // $tSesUserCode       = $this->session->userdata('tSesUserCode');
                $tSesUsrAgnCode     = $this->session->userdata('tSesUsrAgnCode');
                $tSesUsrShpCodeMulti        = $this->session->userdata("tSesUsrShpCodeMulti");
                $tSesUsrBchCodeMulti        = $this->session->userdata("tSesUsrBchCodeMulti");
                
                // $tShpSession        = $this->session->userdata("tSesUsrShpCodeMulti");
                // $tMerSession        = $this->session->userdata("tSesUsrMerCode");
                $tSesUsrLevel       = $this->session->userdata('tSesUsrLevel');
                $tSesUsrBchCodeMulti = ($tSesUsrBchCodeMulti) ? '' : FCNtAddSingleQuote($tSesUsrBchCodeMulti); 
                $tSesUsrShpCodeMulti = ($tSesUsrShpCodeMulti) ? '' : FCNtAddSingleQuote($tSesUsrShpCodeMulti); 

                $tSesUsrWahCode      = $this->session->userdata("tSesUsrWahCode");

                if ($paData['tBCH'] == '') {
                    $tBCH   = $tSesUsrBchCodeMulti;
                } else {
                    $tBCH   = "'" . str_replace(",", "','", $paData['tBCH']) . "'";
                }

                if ($paData['tSHP'] == '') {
                    $tSHP   = $tSesUsrShpCodeMulti;
                } else {
                    $tSHP   = $paData['tSHP'];
                }

                if ($paData['tMER'] == '') {
                    $tMER   = $tSesUsrBchCodeMulti;
                } else {
                    $tMER   = $paData['tMER'];
                }

                if ($paData['tWAH'] == '') {
                    $tWAH   = $tSesUsrWahCode;
                } else {
                    $tWAH   = $paData['tWAH'];
                }
            
                $nCheckPage  =  $this->input->cookie("PDTCookie_" . $this->session->userdata("tSesUserCode"), true);
                $tCookieVal = json_decode($nCheckPage);
                if (!empty($nCheckPage)) {
                    $nMaxTopPage = intval($tCookieVal->nMaxPage);
                } else {
                    $nMaxTopPage = 0;
                }
                
                $tCallStore = "{CALL SP_CNoBrowseProduct(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
                $aDataStore = array(
                    'ptUsrCode'           => $tSesUserCode,
                    'ptUsrLevel'          => $tSesUsrLevel,
                    'tSesUsrAgnCode'      => $tSesUsrAgnCode,
                    'ptSesBchCodeMulti'   => $tBCH,
                    'ptSesShopCodeMulti'  => $tSHP,
                    'ptSesMerCode'        => $tMER,
                    'ptWahCode'           => $tWAH,
                    'pnRow'               => $paData['nRow'],
                    'pnPage'              => $paData['nPage'],
                    'pnMaxTopPage'        => $nMaxTopPage,
                    'ptFilterBy'          => $aDataParamExe['ptFilterBy'],
                    'ptSearch'            => $aDataParamExe['ptSearch'],
                    'ptWhere'             => $aDataParamExe['ptWhere'],
                    'ptNotInPdtType'      => $aDataParamExe['ptNotInPdtType'],
                    'ptPdtCodeIgnorParam' => $aDataParamExe['ptPdtCodeIgnorParam'],
                    'ptPDTMoveon'         => $aDataParamExe['ptPDTMoveon'],
                    'ptPlcCodeConParam'   => $aDataParamExe['ptPlcCodeConParam'],
                    'ptDISTYPE'           => $aDataParamExe['ptDISTYPE'],
                    'ptPagename'          => $aDataParamExe['ptPagename'],
                    'ptNotinItemString'   => $aDataParamExe['ptNotinItemString'],
                    'ptSqlCode'           => $aDataParamExe['ptSqlCode'],
                    'ptPriceType'         => $aDataParamExe['ptPriceType'],
                    'ptPplCode'           => $aDataParamExe['ptPplCode'],
                    'FNResult'            => $nLngID
                );
                // echo "<pre>";
                // print_r($aDataStore);
                // echo "<br>";
                // exit;

                $oQuery = $this->db->query($tCallStore, $aDataStore);


                // echo $this->db->last_query();
                // die();
            
                if ($oQuery->num_rows() > 0) {
                    $aList      = $oQuery->result_array();

                    // print_r($aList);die();
                    if ($paData['nPage'] == 1) {
                        //ถ้าเป็น page 1 ต้องวิ่งไปหาทั้งหมด
                        if ($paData['tFindOnlyPDT'] == 'normal') {
                            $oFoundRow  = $aList[0]['rtCountData'];
                        } else {
                            $oFoundRow  = 1;
                        }
                        $nFoundRow  = $oFoundRow;
                        if ($oFoundRow > 5000) {
                            $nPDTAll  = $aList[0]['rtCountData'];
                        } else {
                            $nPDTAll  = 0;
                        }
                    } else {
                        //ถ้า page 2 3 4 5 6 7 8 9 เราอยู่เเล้ว ว่ามัน total_page เท่าไหร่
                        $nFoundRow = $pnTotalResult;
                        $nPDTAll   = 0;
                    }

                    $nPageAll   = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า

                    $aResult    = array(
                        'raItems'       => $aList,
                        'rnAllRow'      => $nFoundRow,
                        'rnCurrentPage' => $paData['nPage'],
                        'rnAllPage'     => $nPageAll,
                        'rtCode'        => '1',
                        'rtDesc'        => 'success',
                        'sql'           => $tSQL,
                        'nTotalResult'  => $nFoundRow,
                        'nPDTAll'       => $nPDTAll,
                        'nRow'          => $paData['nRow']
                    );
                } else {
                    //No Data
                    $aResult    = array(
                        'rnAllRow'      => 0,
                        'rnCurrentPage' => $paData['nPage'],
                        "rnAllPage"     => 0,
                        'rtCode'        => '800',
                        'rtDesc'        => 'data not found',
                        'sql'           => $tSQL,
                        'nPDTAll'       => 0,
                        'nRow'          => $paData['nRow']
                    );
                }
                return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Count PDT - สำหรับ VIEW BCH + จำนวนเเถว
    public function FSnMSPRGetPageAllByPDT($tSQL, $ptBCH, $ptFilter, $ptAGN, $ptType){
        $tSesUserCode = $this->session->userdata('tSesUserCode');
        $tMerSession        = $this->session->userdata("tSesUsrMerCode");
        $tShpSession        = $this->session->userdata("tSesUsrShpCodeMulti");
        $nLngID             = $this->session->userdata("tLangEdit");
        $aDataUsrGrp        = $this->db->where('FTUsrCode',$tSesUserCode)->get('TCNTUsrGroup')->row_array();
        $tDefaultBchCode    = $aDataUsrGrp['FTBchCode'];
        $nLngID             = $this->session->userdata("tLangEdit");

        // ********************************************************************************************
        // เก็บข้อมูลลง  Cookie 
        $nCheckPage  =  $this->input->cookie("PDTCookie_" . $this->session->userdata("tSesUserCode"), true);
        $tCookieVal = json_decode($nCheckPage);

        if (!empty($nCheckPage)) {
            $nMaxTopPage = $tCookieVal->nMaxPage;
        } else {
            $nMaxTopPage = '';
        }

        if ($nMaxTopPage == '' || null) {
            $nMaxTopPage = '5000';
        }

        $nMaxTopPage   = str_replace(',', '', $nMaxTopPage);

        $tSQL       = "SELECT FTPDTCode FROM ";
        $tSQL       .= " ( ";
        $tSQL       .= "SELECT DISTINCT TCNMPdt.FTPdtCode , FTAgnCode , TCNMPdt.FTPdtStaActive , TCNMPdt_L.FTPdtName , TCNMPdtBar.FTBarCode , TCNMPdt.FTPtyCode , 
                               TCNMPDTSpl.FTSplCode,TCNMPdt.FTPdtStaAlwDis, TCNMPdt.FTPdtType, TCNMPdt.FTPdtSetOrSN , TCNMPdtSpcBch.FTMerCode
                        FROM TCNMPdt
                        LEFT JOIN TCNMPdtSpcBch ON TCNMPdt.FTPdtCode = TCNMPdtSpcBch.FTPdtCode
                        LEFT JOIN TCNMPdtPackSize ON TCNMPdt.FTPdtCode = TCNMPdtPackSize.FTPdtCode
                        LEFT JOIN TCNMPdtBar ON TCNMPdtBar.FTPdtCode = TCNMPdtPackSize.FTPdtCode AND TCNMPdtBar.FTPunCode = TCNMPdtPackSize.FTPunCode
                        LEFT JOIN TCNMPDTSpl ON TCNMPdt.FTPdtCode = TCNMPDTSpl.FTPdtCode AND TCNMPdtBar.FTBarCode = TCNMPDTSpl.FTBarCode
                        LEFT JOIN TCNMPdtUnit_L ON TCNMPdtUnit_L.FTPunCode = TCNMPdtPackSize.FTPunCode AND TCNMPdtUnit_L.FNLngID = '$nLngID'
                        INNER JOIN TCNMPdt_L ON TCNMPdt.FTPdtCode = TCNMPdt_L.FTPdtCode AND TCNMPdt_L.FNLngID = '$nLngID' ";
        $tSQL       .= " WHERE  1=1 ";

        if(!empty($ptAGN)){
            $tSQL       .= " AND (ISNULL(TCNMPdtSpcBch.FTAgnCode, '') = '$ptAGN' OR ISNULL(TCNMPdtSpcBch.FTPdtCode,'')='')"; //สินค้าที่ไม่มีเฉพาะดีลเลอร์ใดเลย
        }
        
        //---------------------- การมองเห็นเฉพาะสินค้าตามระดับผู้ใช้--------------------------//
            $tSQL       .= " OR ( ";
            $tSQL       .= "     ISNULL(TCNMPdtSpcBch.FTAgnCode, '') = '$ptAGN' ";
        if(!empty($tMerSession)){ //กรณีผู้ใช้ผูก Mer จะเห็นสินค้าภายใต้ Mer
            $tSQL       .= " AND ISNULL(TCNMPdtSpcBch.FTMerCode, '') = '$tMerSession' ";
        }
        if(!empty($tDefaultBchCode)){ //กรณีผู้ใช้ผูก Bch จะเห็นสินค้าภายใต้ Bch
            $tSQL       .= " AND ISNULL(TCNMPdtSpcBch.FTBchCode, '') IN ($ptBCH) ";
        }
        if(!empty($tShpSession)){ //กรณีผู้ใช้ผูก Shp จะเห็นสินค้าภายใต้ Shp
            $tSQL       .= " AND ISNULL(TCNMPdtSpcBch.FTShpCode, '') IN ($tShpSession) ";
        }
            $tSQL       .= " ) ";

        //---------------------- การมองเห็นสินค้าระดับตัวแทนขาย--------------------------//
            $tSQL       .= " OR ( ";
            $tSQL       .= "     ISNULL(TCNMPdtSpcBch.FTAgnCode, '') = '$ptAGN' ";
        if(!empty($tMerSession)){ //กรณีผู้ใช้ผูก Mer จะเห็นสินค้าที่ไม่ผูก Mer
            $tSQL       .= " AND ISNULL(TCNMPdtSpcBch.FTMerCode, '') = '' ";
        }
        if(!empty($tDefaultBchCode)){ //กรณีผู้ใช้ผูก Bch จะเห็นสินค้าภายใต้ Bch
            $tSQL       .= " AND ISNULL(TCNMPdtSpcBch.FTBchCode, '') = '' ";
        }
        if(!empty($tShpSession)){ //กรณีผู้ใช้ผูก Shp จะเห็นสินค้าภายใต้ Shp
            $tSQL       .= " AND ISNULL(TCNMPdtSpcBch.FTShpCode, '') = '' ";
        }
            $tSQL       .= " ) ";

        //---------------------- การมองเห็นสินค้าระดับสาขา (สำหรับผู้ใช้ระดับร้านค้า)--------------------------//
        if(!empty($tShpSession)){  
            $tSQL .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่อยู่ใน Bch แต่ไม่ผูก Shp
            $tSQL .= "     ISNULL(TCNMPdtSpcBch.FTAgnCode,'') = '$ptAGN'";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTMerCode,'') = '$tMerSession'";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTBchCode,'') IN ($ptBCH) ";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTShpCode,'') = ''"   ;
            $tSQL .= " ) ";

            $tSQL .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่อยู่ใน Bch แต่ไม่ผูก Shp และไม่ผูก Mer
            $tSQL .= "     ISNULL(TCNMPdtSpcBch.FTAgnCode,'') = '$ptAGN'";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTMerCode,'') = ''";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTBchCode,'') IN ($ptBCH) ";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTShpCode,'') = ''"   ;
            $tSQL .= " ) ";

            $tSQL .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่ไม่ผูก Bch และ ไม่ผูก Shp
            $tSQL .= "     ISNULL(TCNMPdtSpcBch.FTAgnCode,'') = '$ptAGN'";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTMerCode,'') = '$tMerSession'";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTBchCode,'') = ''";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTShpCode,'') = ''"   ;
            $tSQL .= " ) ";

            $tSQL .= " OR ("; //กรณีผู้ใช้ผูก Shp จะต้องเห็นสินค้าที่ไม่ผูก Mer และสินค้าผูก Bch / Shp
            $tSQL .= "     ISNULL(TCNMPdtSpcBch.FTAgnCode,'') = '$ptAGN'";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTMerCode,'') = ''";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTBchCode,'') IN ($ptBCH) ";
            $tSQL .= " AND ISNULL(TCNMPdtSpcBch.FTShpCode,'') IN ($tShpSession) "   ;
            $tSQL .= " ) ";
        }

        $tSQL       .= " ) AS Products WHERE 1=1 ";
        $tSQL       .= $ptFilter;
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() >= $nMaxTopPage) {
            $nRow = $nMaxTopPage;
        } else if ($oQuery->num_rows() < $nMaxTopPage) {
            $nRow = $oQuery->num_rows();
        } else {
            $nRow = 0;
        }
        return $nRow;
    }

    //#################################################### PDT VIEW SHP #################################################### 

    //PDT - สำหรับ VIEW SHOP + ข้อมูล
    public function FSaMGetProductSHP($ptFilter, $ptLeftJoinPrice, $paData, $pnTotalResult){
        try {
            $tBCH               = $paData['tBCH'];
            $tShpSession        = $paData['tSHP'];
            $tMerSession        = '';
            $aRowLen            = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
            $nLngID             = $this->session->userdata("tLangEdit");

            if ($paData['aPriceType'][0] == 'Pricesell') {
                //ถ้าเป็นราคาขาย
                $tSelectFiledPrice  = "0 AS FCPgdPriceNet, ";
                $tSelectFiledPrice .= "0 AS FCPgdPriceRet, ";
                $tSelectFiledPrice .= "0 AS FCPgdPriceWhs ";
            } else if ($paData['aPriceType'][0] == 'Price4Cst') {
                $tSelectFiledPrice = '  0 AS FCPgdPriceNet ,
                                        0 AS FCPgdPriceWhs ,
                                        CASE 
                                            WHEN ISNULL(PCUS.FCPgdPriceRet,0) <> 0 THEN PCUS.FCPgdPriceRet
                                            WHEN ISNULL(PBCH.FCPgdPriceRet,0) <> 0 THEN PBCH.FCPgdPriceRet
                                            WHEN ISNULL(PEMPTY.FCPgdPriceRet,0) <> 0 THEN PEMPTY.FCPgdPriceRet
                                            ELSE 0 
                                        END AS FCPgdPriceRet ';
            } else if ($paData['aPriceType'][0] == 'Cost') {
                //ถ้าเป็นราคาทุน
                $tSelectFiledPrice  = "ISNULL(FCPdtCostStd,0)       AS FCPdtCostStd    , ISNULL(FCPdtCostAVGIN,0)     AS FCPdtCostAVGIN , ";
                $tSelectFiledPrice .= "ISNULL(FCPdtCostAVGEx,0)     AS FCPdtCostAVGEx  , ISNULL(FCPdtCostLast,0)      AS FCPdtCostLast, ";
                $tSelectFiledPrice .= "ISNULL(FCPdtCostFIFOIN,0)    AS FCPdtCostFIFOIN , ISNULL(FCPdtCostFIFOEx,0)    AS FCPdtCostFIFOEx ";
            }

            $tSQL       = "SELECT c.* FROM ( ";
            $tSQL      .= "SELECT ROW_NUMBER() OVER(ORDER BY Products.FTPdtCode ASC) AS FNRowID , Products.* FROM (";
            $tSQL      .= "SELECT ProductM.*, " . $tSelectFiledPrice . " FROM ( ";
            $tSQL      .= "SELECT * FROM VCN_ProductShop ";

            if ($paData['tSHP'] != '' && $paData['tMER'] != '') {
                //มี SHP มี MER
                $tSHP       = $paData['tSHP'];
                $tMER       = $paData['tMER'];
                $tSQL      .= " WHERE FTMerCode = '$tMER' AND FTShpCode = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            } else if ($paData['tSHP'] != '' && $paData['tMER'] == '') {
                //มี SHP ไม่มี MER
                $tSHP       = $paData['tSHP'];

                //หา MER 
                $aFindMer   = $this->FSaFindMerCodeBySHP($tSHP, $tBCH);
                $tMER       = '';
                for ($i = 0; $i < FCNnHSizeOf($aFindMer); $i++) {
                    $tMER   = $aFindMer[0]['FTMerCode'];
                }
                $tSQL      .= " WHERE FTMerCode = '$tMER' AND FTShpCode = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            } else if ($paData['tSHP'] == '' && $paData['tMER'] != '') {
                //ไม่มี SHP มี MER
                $tSHP       = '';
                $tMER       = $paData['tMER'];
                $tSQL      .= " WHERE FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID'  ";
            } else {
                //ไม่มี SHP ไม่มี MER
                $tSHP       = $tShpSession;
                $tMER       = $tMerSession;
                $tSQL      .= " WHERE FTShpCode = '$tSHP' AND FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            }

            $tSQL      .= " UNION SELECT * 
            FROM VCN_ProductShop
            WHERE FTShpCode = '$tSHP'
            AND FTPdtSpcBCH = '$tBCH'
            AND FNLngIDPdt = '$nLngID'
            AND FNLngIDUnit = '$nLngID' 
            ) AS ProductM ";

            $tSQL      .= $ptLeftJoinPrice;
            $tSQL      .= " ) AS Products WHERE 1=1 ";
            $tSQL      .= $ptFilter;
            $tSQL      .= " ) AS c ";
            $tSQL      .= " WHERE 1=1 ";
            $tSQL      .= "AND c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

            $oQuery = $this->db->query($tSQL);

            // echo $this->db->last_query();
            // die();
            if ($oQuery->num_rows() > 0) {
                $aList      = $oQuery->result_array();

                if ($paData['nPage'] == 1) {
                    //ถ้าเป็น page 1 ต้องวิ่งไปหาทั้งหมด
                    if ($paData['tFindOnlyPDT'] == 'normal') {
                        $oFoundRow  = $this->FSnMSPRGetPageAllBySHP($tSQL, $ptFilter, $tSHP, $tMER, $tBCH, $nLngID, 'SOME');
                    } else {
                        $oFoundRow  = 1;
                    }
                    $nFoundRow  = $oFoundRow;
                    if ($oFoundRow > 5000) {
                        $nPDTAll  = $this->FSnMSPRGetPageAllBySHP($tSQL, $ptFilter, $tSHP, $tMER, $tBCH, $nLngID, 'ALL');
                    } else {
                        $nPDTAll  = 0;
                    }
                } else {
                    //ถ้า page 2 3 4 5 6 7 8 9 เราอยู่เเล้ว ว่ามัน total_page เท่าไหร่
                    $nFoundRow = $pnTotalResult;
                    $nPDTAll   = 0;
                }


                $nPageAll   = ceil($nFoundRow / $paData['nRow']);
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                    'sql'           => $tSQL,
                    'nPDTAll'       => $nPDTAll,
                    'nRow'          => $paData['nRow']
                );
            } else {
                //No Data
                $aResult    = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found',
                    'sql'           => $tSQL,
                    'nPDTAll'       => 0,
                    'nRow'          => $paData['nRow']
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Count PDT - สำหรับ VIEW SHOP + จำนวนเเถว
    public function FSnMSPRGetPageAllBySHP($tSQL, $ptFilter, $tSHP, $tMER, $tBCH, $nLngID, $ptType){

        // Create By Witsarut 02/07/2020  เก็บข้อมูลลง  Cookie
        $nCheckPage  =  $this->input->cookie("PDTCookie_" . $this->session->userdata("tSesUserCode"), true);
        $tCookieVal = json_decode($nCheckPage);

        if (!empty($nCheckPage)) {
            $nMaxTopPage = $tCookieVal->nMaxPage;
        } else {
            $nMaxTopPage = '';
        }

        if ($nMaxTopPage == '' || null) {
            $nMaxTopPage = '5000';
        }

        $nMaxTopPage   = str_replace(',', '', $nMaxTopPage);
        // ******************************************************************************************************************

        if ($ptType == 'SOME') {
            $tSQL       = "SELECT * FROM ( SELECT TOP $nMaxTopPage * FROM VCN_ProductShop as Products WHERE 1=1 ";
        } else if ($ptType == 'ALL') {
            $tSQL       = "SELECT * FROM ( SELECT * FROM VCN_ProductShop as Products WHERE 1=1  ";
        }

        if ($tSHP != '' && $tMER != '') {
            //มี SHP มี MER
            $tSHP       = $tSHP;
            $tMER       = $tMER;
            //$tSQL      .= " AND (FTMerCode = '$tMER' OR FTShpCode = '$tSHP') AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            $tSQL      .= " AND  FTMerCode = '$tMER' AND FTShpCode = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
        } else if ($tSHP != '' && $tMER == '') {
            //มี SHP ไม่มี MER
            $tSHP       = $tSHP;
            $tMER       = $tMER;
            $tSQL      .= " AND FTShpCode = '$tSHP' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
        } else if ($tSHP == '' && $tMER != '') {
            //ไม่มี SHP มี MER
            $tSHP       = $tSHP;
            $tMER       = $tMER;
            $tSQL      .= " AND FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID'  ";
        } else {
            //ไม่มี SHP ไม่มี MER
            $tSHP       = $this->session->userdata("tSesUsrShpCode");
            $tMER       = $this->session->userdata("tSesUsrMerCode");
            $tSQL      .= " AND FTShpCode = '$tSHP' AND FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
        }

        $tSQL      .= " UNION SELECT * 
        FROM VCN_ProductShop
        WHERE FTShpCode = '$tSHP'
        AND FTPdtSpcBCH = '$tBCH'
        AND FNLngIDPdt = '$nLngID'
        AND FNLngIDUnit = '$nLngID' 
        ) AS Products WHERE 1=1 ";

        $tSQL .= $ptFilter;

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() >= $nMaxTopPage) {
            $nRow = $nMaxTopPage;
        } else if ($oQuery->num_rows() < $nMaxTopPage) {
            $nRow = $oQuery->num_rows();
        } else {
            $nRow = 0;
        }
        return $nRow;
    }

    //#################################################### Get หาต้นทุนใช้แบบไหน #################################################### 
    public function FSnMGetTypePrice($tSyscode, $tSyskey, $tSysseq){
        $tSQL = "SELECT FTSysStaDefValue,FTSysStaUsrValue
            FROM  TSysConfig 
            WHERE 
                FTSysCode = '$tSyscode' AND 
                FTSysKey = '$tSyskey' AND 
                FTSysSeq = '$tSysseq'
            ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oRes  = $oQuery->result();
            if ($oRes[0]->FTSysStaUsrValue != '') {
                $tDataSavDec = $oRes[0]->FTSysStaUsrValue;
            } else {
                $tDataSavDec = $oRes[0]->FTSysStaDefValue;
            }
        } else {
            //Decimal Default = 2 
            $tDataSavDec = 2;
        }
        return $tDataSavDec;
    }

    //Get vat จาก company กรณีที่ไม่มีผู้จำหน่าย ส่งมา
    public function FSaMGetWhsInorExIncompany(){
        $tSQL           = "SELECT TOP 1 FTCmpRetInOrEx FROM TCNMComp";
        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        return $oList;
    }

    //Get vat จาก ผู้จำหน่าย
    public function FSaMGetWhsInorExInSupplier($pnCode){
        $tSQL           = "SELECT FTSplStaVATInOrEx FROM TCNMSpl WHERE FTSplCode = '$pnCode'";
        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        return $oList;
    }

    //หาว่า BARCODE หรือ PLC นี้อยู่ใน PDT อะไร
    public function FSnMFindPDTByBarcode($tTextSearch, $tTypeSearch){
        $nLngID  = $this->session->userdata("tLangEdit");
        $tSQL    = "SELECT FTPdtCode , FTPunCode , FTBarCode FROM VCN_ProductBar WHERE 1=1";

        if ($tTypeSearch == 'FINDBARCODE') {
            $tSQL    .=  " AND FTBarCode = '$tTextSearch' ";
        } else if ($tTypeSearch == 'FINDPLCCODE') {
            $tSQL    .=  " AND FTPlcCode = '$tTextSearch' ";
        }

        $tSQL    .= "AND FNLngPdtBar = '$nLngID' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        } else {
            return false;
        }
    }

    //หาว่า shop นี้ Mercode อะไร
    public function FSaFindMerCodeBySHP($tSHP, $tBCH){
        $tSQL    = "SELECT FTShpCode , FTMerCode FROM TCNMShop WHERE ";
        $tSQL    .= "FTShpCode = '$tSHP' AND FTBchCode = '$tBCH' ";
        $tSQL    .= "ORDER BY FTMerCode ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        } else {
            return false;
        }
    }

    //หาว่าสาขา นี้ Mercode อะไร
    public function FSaFindMerCodeByBCH($tBCH){
        $tSQL    = "SELECT DISTINCT FTMerCode FROM TCNMShop WHERE ";
        $tSQL    .= "FTBchCode = '$tBCH' ";
        $tSQL    .= "ORDER BY FTMerCode ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        } else {
            return false;
        }
    }

    //#################################################### สินค้าล็อต ####################################################

    //สินค้าล็อต
    public function FSaMGetDetailPDTLot($tPDTCode){
        $nLngID  = $this->session->userdata("tLangEdit");
        $tSQL    = "SELECT A.* , COUNT(B.FTFhnRefCode) AS nCountRefCode FROM (
                        SELECT 
                            TFHMPdtColorSize.FTPdtCode ,
                            TFHMPdtColorSize.FTFhnRefCode ,
                            TFHMPdtColorSize.FNFhnSeq ,
                            TFHMPdtSeason_L.FTSeaName,
                            TFHMPdtFabric_L.FTFabName,
                            TCNMPdtColor_L.FTClrName,
                            TCNMPdtSize_L.FTPszName,
                            TCNMPDT_L.FTPdtName,
                            '' AS FCXtdQty
                        FROM 
                            TFHMPdtColorSize 
                        LEFT JOIN TFHMPdtSeason_L   ON TFHMPdtColorSize.FTSeaCode = TFHMPdtSeason_L.FTSeaCode AND TFHMPdtSeason_L.FNLngID = $nLngID
                        LEFT JOIN TFHMPdtFabric_L   ON TFHMPdtColorSize.FTFabCode = TFHMPdtFabric_L.FTFabCode AND TFHMPdtFabric_L.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtColor_L    ON TFHMPdtColorSize.FTClrCode = TCNMPdtColor_L.FTClrCode AND TCNMPdtColor_L.FNLngID = $nLngID 
                        LEFT JOIN TCNMPdtSize_L     ON TFHMPdtColorSize.FTPszCode = TCNMPdtSize_L.FTPszCode AND TCNMPdtSize_L.FNLngID = $nLngID
                        LEFT JOIN TCNMPDT_L         ON TFHMPdtColorSize.FTPdtCode = TCNMPDT_L.FTPdtCode AND TCNMPDT_L.FNLngID = $nLngID
                        WHERE TFHMPdtColorSize.FTPdtCode = '$tPDTCode' 
                    ) AS A 
                    LEFT JOIN TFHMPdtColorSize B ON A.FTPdtCode = B.FTPdtCode AND A.FTFhnRefCode = B.FTFhnRefCode 
                    GROUP BY A.FTPdtCode , A.FTFhnRefCode , A.FTSeaName , A.FTFabName , A.FTClrName , A.FTPszName , A.FTPdtName , A.FNFhnSeq , A.FCXtdQty
                    ORDER BY A.FTFhnRefCode";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        } else {
            return false;
        }
    }

    ///////////////////////////////////////////// สินค้าแฟชั่น + สินค้าซีเรียล /////////////////////////////////////////////

    //เช็คก่อนว่าสินค้า แฟชั่นที่ส่งมานั้นมีรายละเอียดครบถ้วนจริงไหม
    public function FSaMCheckDetailInPDTColorSize($aResult){
        $aItemCheck_have    = [];
        $tItemCheck_remove  = '';
        for($i=0; $i<count($aResult); $i++){
            $tPDTCode   = $aResult[$i]->PDTCode;
            $tPUNCode   = $aResult[$i]->PUNCode;
            $tBarcode   = $aResult[$i]->Barcode;
            // $tSQL       = "SELECT FTPdtCode FROM TFHMPdtColorSize WITH(NOLOCK)  WHERE FTPdtCode = '$tPDTCode' ";
            $tSQL  ="SELECT
                        PDTCLR.FTPdtCode
                    FROM
                        TFHMPdtColorSize PDTCLR WITH(NOLOCK)
                    LEFT JOIN TCNMPdtBar PDTBAR WITH(NOLOCK) ON PDTCLR.FTPdtCode = PDTBAR.FTPdtCode AND PDTCLR.FNFhnSeq = PDTBAR.FNBarRefSeq AND PDTCLR.FTFhnRefCode = PDTBAR.FTFhnRefCode
                    WHERE 1=1
                    AND PDTCLR.FTFhnStaActive = '1'
                    AND	PDTCLR.FTPdtCode = '$tPDTCode'
                    AND PDTBAR.FTBarCode = '$tBarcode'
                    GROUP BY PDTCLR.FTPdtCode";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                array_push($aItemCheck_have,['PDTCode' => $tPDTCode ,'PUNCode'=>$tPUNCode , 'Barcode'=>$tBarcode ]);
            }else{
                $tItemCheck_remove .= $tPDTCode . ',';
            }
        }
        
        $aItemCheckreturn = array(
            'aItemCheck_have'   => $aItemCheck_have,
            'aItemCheck_remove' => $tItemCheck_remove
        );
        return json_encode($aItemCheckreturn);
    }

    //หาว่าสินค้าแฟชั่นตัวนี้ มีรายละเอียดที่เกี่ยวข้องคืออะไร
    public function FSaMGetDetailPDTFashion($paPDTDetail){

        $tPdtCode = $paPDTDetail['tPdtCode'];
        $tPUNCode = $paPDTDetail['tPUNCode'];
        $tBarcode = $paPDTDetail['tBarcode'];

        $nLngID  = $this->session->userdata("tLangEdit");
        $tSQL    = "SELECT A.* , COUNT(B.FTFhnRefCode) AS nCountRefCode ,'$tPdtCode' AS RetPdtCode , '$tPUNCode' AS RetPunCode , '$tBarcode' AS RetBarCode , 1 AS RenDTSeq FROM (
                        SELECT 
                            TFHMPdtColorSize.FTPdtCode ,
                            TFHMPdtColorSize.FTFhnRefCode ,
                            TFHMPdtColorSize.FNFhnSeq ,
                            TFHMPdtSeason_L.FTSeaName,
                            TFHMPdtFabric_L.FTFabName,
                            TCNMPdtColor_L.FTClrName,
                            TCNMPdtSize_L.FTPszName,
                            TCNMPDT_L.FTPdtName,
                            '' AS FCXtdQty
                        FROM 
                            TFHMPdtColorSize  WITH (NOLOCK)
                        LEFT JOIN TFHMPdtSeason_L  WITH (NOLOCK) ON TFHMPdtColorSize.FTSeaCode = TFHMPdtSeason_L.FTSeaCode AND TFHMPdtSeason_L.FNLngID = $nLngID
                        LEFT JOIN TFHMPdtFabric_L  WITH (NOLOCK) ON TFHMPdtColorSize.FTFabCode = TFHMPdtFabric_L.FTFabCode AND TFHMPdtFabric_L.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtColor_L  WITH (NOLOCK)  ON TFHMPdtColorSize.FTClrCode = TCNMPdtColor_L.FTClrCode AND TCNMPdtColor_L.FNLngID = $nLngID 
                        LEFT JOIN TCNMPdtSize_L  WITH (NOLOCK)   ON TFHMPdtColorSize.FTPszCode = TCNMPdtSize_L.FTPszCode AND TCNMPdtSize_L.FNLngID = $nLngID
                        LEFT JOIN TCNMPDT_L    WITH (NOLOCK)     ON TFHMPdtColorSize.FTPdtCode = TCNMPDT_L.FTPdtCode AND TCNMPDT_L.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtBar PDTBAR WITH (NOLOCK) ON TFHMPdtColorSize.FTPdtCode = PDTBAR.FTPdtCode AND TFHMPdtColorSize.FNFhnSeq = PDTBAR.FNBarRefSeq AND TFHMPdtColorSize.FTFhnRefCode = PDTBAR.FTFhnRefCode
                        WHERE TFHMPdtColorSize.FTPdtCode = '$tPdtCode' AND PDTBAR.FTBarCode ='$tBarcode'
                    ) AS A 
                    LEFT JOIN TFHMPdtColorSize B ON A.FTPdtCode = B.FTPdtCode AND A.FTFhnRefCode = B.FTFhnRefCode 
                    GROUP BY A.FTPdtCode , A.FTFhnRefCode , A.FTSeaName , A.FTFabName , A.FTClrName , A.FTPszName , A.FTPdtName , A.FNFhnSeq , A.FCXtdQty
                    ORDER BY A.FTFhnRefCode";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        } else {
            return false;
        }
    }

    //หาว่าสินค้าแฟชั่นตัวนี้ ใน temp มีจำนวน QTY ที่เคยกรอกเป็นเท่าไหร่บ่้าง
    public function FSaMGetDetailPDTSingleFashion($paPDTDetail,$aDataInDocumentTemp){
        $tPdtCode = $paPDTDetail['tPdtCode'];
        $tPUNCode = $paPDTDetail['tPUNCode'];
        $tBarcode = $paPDTDetail['tBarcode'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tDocumentnumber    = $aDataInDocumentTemp['tDocumentnumber'];
        $tDocumentbranch    = $aDataInDocumentTemp['tDocumentbranch'];
        $tDocumentDockey   = $aDataInDocumentTemp['tDocumentDockey'];
        $nDTSeq            = $aDataInDocumentTemp['nDTSeq'];
        $tDocumentPLcCode    = $aDataInDocumentTemp['tDocumentPLcCode'];
        $tDocumentsession   = $aDataInDocumentTemp['tDocumentsession'];
        $tSpcControl   = $aDataInDocumentTemp['oOptionForFashion']['tSpcControl'];
        
        $tWherePlcCode = '';
        if($tSpcControl<>0){ //กรณีเรียกในเอกสารตรวจนับ
            $tWherePlcCode.=" AND ISNULL(D.FTAjdPlcCode,'') = '$tDocumentPLcCode' ";
        }

        $tSQL    = "SELECT  C.* , 
                            '$tPdtCode' AS RetPdtCode , '$tPUNCode' AS RetPunCode , '$tBarcode' AS RetBarCode, $nDTSeq AS RenDTSeq,
                            D.FCXtdQty , 
                            D.FDAjdDateTimeC1 ,     
                            D.FCAjdUnitQtyC1 , 
                            D.FDAjdDateTimeC2 , 
                            D.FCAjdUnitQtyC2 , 
                            D.FDAjdDateTime , 
                            D.FCAjdUnitQty 
                        FROM (

                            SELECT A.* , COUNT(B.FTFhnRefCode) AS nCountRefCode FROM (
                                SELECT 
                                    TFHMPdtColorSize.FTPdtCode ,
                                    TFHMPdtColorSize.FTFhnRefCode ,
                                    TFHMPdtColorSize.FNFhnSeq ,
                                    TFHMPdtSeason_L.FTSeaName,
                                    TFHMPdtFabric_L.FTFabName,
                                    TCNMPdtColor_L.FTClrName,
                                    TCNMPdtSize_L.FTPszName,
                                    TCNMPDT_L.FTPdtName
                                FROM 
                                    TFHMPdtColorSize WITH (NOLOCK)
                                LEFT JOIN TFHMPdtSeason_L WITH (NOLOCK)  ON TFHMPdtColorSize.FTSeaCode = TFHMPdtSeason_L.FTSeaCode AND TFHMPdtSeason_L.FNLngID = $nLngID
                                LEFT JOIN TFHMPdtFabric_L WITH (NOLOCK)  ON TFHMPdtColorSize.FTFabCode = TFHMPdtFabric_L.FTFabCode AND TFHMPdtFabric_L.FNLngID = $nLngID
                                LEFT JOIN TCNMPdtColor_L WITH (NOLOCK)   ON TFHMPdtColorSize.FTClrCode = TCNMPdtColor_L.FTClrCode AND TCNMPdtColor_L.FNLngID = $nLngID 
                                LEFT JOIN TCNMPdtSize_L WITH (NOLOCK)    ON TFHMPdtColorSize.FTPszCode = TCNMPdtSize_L.FTPszCode AND TCNMPdtSize_L.FNLngID = $nLngID
                                LEFT JOIN TCNMPDT_L   WITH (NOLOCK)      ON TFHMPdtColorSize.FTPdtCode = TCNMPDT_L.FTPdtCode AND TCNMPDT_L.FNLngID = $nLngID
                                LEFT JOIN TCNMPdtBar PDTBAR WITH (NOLOCK) ON TFHMPdtColorSize.FTPdtCode = PDTBAR.FTPdtCode AND TFHMPdtColorSize.FNFhnSeq = PDTBAR.FNBarRefSeq AND TFHMPdtColorSize.FTFhnRefCode = PDTBAR.FTFhnRefCode
                                WHERE TFHMPdtColorSize.FTPdtCode = '$tPdtCode'  AND PDTBAR.FTBarCode ='$tBarcode'
                            ) AS A 
                            LEFT JOIN TFHMPdtColorSize B ON A.FTPdtCode = B.FTPdtCode AND A.FTFhnRefCode = B.FTFhnRefCode 
                            GROUP BY A.FTPdtCode , A.FTFhnRefCode , A.FTSeaName , A.FTFabName , A.FTClrName , A.FTPszName , A.FTPdtName , A.FNFhnSeq

                    ) AS C LEFT JOIN TCNTDocDTFhnTmp D ON C.FTPdtCode = D.FTPdtCode AND C.FTFhnRefCode = D.FTFhnRefCode
                    AND D.FTBchCode = '$tDocumentbranch'
                    AND D.FTXshDocNo = '$tDocumentnumber' 
                    AND D.FTSessionID = '$tDocumentsession'
                    AND D.FTXthDocKey = '$tDocumentDockey'
                    AND D.FNXsdSeqNo  = '$nDTSeq'
                    $tWherePlcCode
                    ORDER BY C.FTFhnRefCode ";

        $oQuery = $this->db->query($tSQL);
       
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        } else {
            return false;
        }
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //หาว่าสินค้าตัวนี้ มีสินค้าลูกอะไรบ้าง
    public function FSaMGetDetailPDTSetOrSVSet($paPDTDetail){
        $tKeyInTemp         = $paPDTDetail['tKeyInTemp'];
        $tBchCode           = $paPDTDetail['tBchCode'];
        $tAgnCode           = $paPDTDetail['tAgnCode'];
        $tDocumentNumber    = $paPDTDetail['tDocumentNumber'];
        $tSession           = $this->session->userdata('tSesSessionID');
        $nLngID             = $this->session->userdata("tLangEdit");
        
        $tSQL = "INSERT INTO TCNTDocDTTmp (
                    FTBchCode , FTXthDocNo , FNXtdSeqNo , FTXthDocKey , FTSrnCode , FTPdtCode ,
                    FTXtdPdtName , FTPunCode , FNXtdPdtLevel , FTXtdPdtStaSet ,
                    FTXtdPdtParent , FCXtdQtySet , FDLastUpdOn ,FTLastUpdBy , 
                    FDCreateOn , FTCreateBy , FTSessionID )";
        $tSQL .= " SELECT DISTINCT A.* FROM ( SELECT 
                        '$tBchCode'             AS FTBchCode , 
                        '$tDocumentNumber'      AS FTXthDocNo ,
                        ROW_NUMBER() OVER ( ORDER BY A.FTPDTCode ASC) AS FNXtdSeqNo,
                        '$tKeyInTemp' 			AS FTXthDocKey,
                        A.FTPdtCode 		    AS FTSrnCode, 
                        A.FTPdtCodeSet      	AS FTPdtCode,  
                        PDTL.FTPdtName  	    AS FTXtdPdtName ,
                        A.FTPunCode 		    AS FTPunCode, 
                        '0' 					AS FNXtdPdtLevel , --ประเภทรายการ 1:เปลี่ยนคิดราคา , 2:ตรวจสอบไม่คิดราคา
                        '1' 				    AS FTXtdPdtStaSet , 
                        ''						AS FTXtdPdtParent , --สถานะแนะนำ 1:แนะนำ , 2:ไม่ได้แนะนำ 
                        A.FCPstQty 			    AS FCXtdQtySet , 
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                      AS FDLastUpdOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')  AS FTLastUpdBy,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                      AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')  AS FTCreateBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID
                FROM TCNTDocDTTmp DOCTMP
                LEFT JOIN TCNTPdtSet A ON DOCTMP.FTPdtCode = A.FTPdtCode
                LEFT JOIN TCNMPdt_L PDTL ON A.FTPdtCodeSet = PDTL.FTPdtCode AND PDTL.FNLngID = '$nLngID'
                WHERE 1 = 1
                AND DOCTMP.FTPdtSetOrSN = '2' --สินค้าเซต
                AND DOCTMP.FTBchCode    = '$tBchCode'
                AND DOCTMP.FTXthDocNo   = '$tDocumentNumber'
                AND DOCTMP.FTXthDocKey  = '$tKeyInTemp'
                AND DOCTMP.FTSessionID  = '$tSession'
            ) AS A  LEFT JOIN TCNTDocDTTmp B ON A.FTSrnCode = B.FTSrnCode 
            WHERE ISNULL(B.FTSrnCode,'') = ''
            UNION
            SELECT  DISTINCT A.* FROM ( SELECT 
                    '$tBchCode'             AS FTBchCode , 
                    '$tDocumentNumber'      AS FTXthDocNo ,
                    DOCTMP.FNXtdSeqNo       AS FNXtdSeqNo,
                    '$tKeyInTemp' 			AS FTXthDocKey,
                    A.FTPdtCode 		    AS FTSrnCode, 
                    A.FTPdtCodeSub      	AS FTPdtCode,  
                    PDTL.FTPdtName  	    AS FTXtdPdtName ,
                    A.FTPunCode 		    AS FTPunCode, 
                    A.FTPsvType             AS FNXtdPdtLevel , --ประเภทรายการ 1:เปลี่ยนคิดราคา , 2:ตรวจสอบไม่คิดราคา
                    '1' 				    AS FTXtdPdtStaSet , 
                    A.FTPsvStaSuggest       AS FTXtdPdtParent , --สถานะแนะนำ 1:แนะนำ , 2:ไม่ได้แนะนำ 
                    A.FCPsvQty              AS FCXtdQtySet , 
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                      AS FDLastUpdOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')  AS FTLastUpdBy,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                      AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')  AS FTCreateBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID
                FROM TCNTDocDTTmp DOCTMP
                LEFT JOIN TSVTPdtSet A ON DOCTMP.FTPdtCode = A.FTPdtCode
                LEFT JOIN TCNMPdt_L PDTL ON A.FTPdtCodeSub = PDTL.FTPdtCode AND PDTL.FNLngID = '$nLngID'
                WHERE 1 = 1
                AND DOCTMP.FTPdtSetOrSN = '5' --สินค้าเซตบำรุง
                AND DOCTMP.FTBchCode    = '$tBchCode'
                AND DOCTMP.FTXthDocNo   = '$tDocumentNumber'
                AND DOCTMP.FTXthDocKey  = '$tKeyInTemp'
                AND DOCTMP.FTSessionID  = '$tSession' 
                AND A.FTPdtCodeSub NOT IN (
                    SELECT FTPdtCode FROM TCNTDocDTTmp DOCTMP WHERE  
                    DOCTMP.FTXtdPdtStaSet = '1' 
                    AND DOCTMP.FTBchCode    = '$tBchCode'
                    AND DOCTMP.FTXthDocNo   = '$tDocumentNumber'
                    AND DOCTMP.FTXthDocKey  = '$tKeyInTemp'
                    AND DOCTMP.FTSessionID  = '$tSession' 
                )
            ) AS A  LEFT JOIN TCNTDocDTTmp B ON A.FTSrnCode = B.FTPdtCode 
            WHERE ISNULL(B.FTSrnCode,'') = '' ";
        $this->db->query($tSQL);
        return $this->db->affected_rows();
    }
}
