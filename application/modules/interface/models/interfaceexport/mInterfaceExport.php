<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mInterfaceExport extends CI_Model {

    //ข้อมูล API สำหรับส่งออก
    public function FSaMIFXGetHD($pnLang){
        $tSesUsrAgnCode = $this->session->userdata('tSesUsrAgnCode');
        $tSQL = "   SELECT 
                        API.FTApiCode,
                        API_L.FTApiName
                    FROM TCNMTxnAPI API WITH(NOLOCK) 
                    LEFT JOIN TCNMTxnAPI_L API_L ON API.FTApiCode = API_L.FTApiCode AND API_L.FNLngID = $pnLang ";
                    if($this->session->userdata('tSesUsrLevel')!='HQ'){
                    $tSQL .= " LEFT JOIN TCNMTxnSpcAPI SpcAPI WITH(NOLOCK)  ON API.FTApiCode = SpcAPI.FTApiCode ";
                }
        $tSQL .= "WHERE 1=1 
                  AND API.FTApiTxnType = '2' 
                  AND ISNULL(API_L.FTApiName,'') != '' ";

        if($this->session->userdata('tSesUsrLevel') != 'HQ'){
            $tSQL .= " AND ( SpcAPI.FTAgnCode = '$tSesUsrAgnCode' OR  SpcAPI.FTAgnCode IS NULL )";
        }

        // $tSQL .= " AND API.FTApiCode NOT IN ('00040','00039','00041','00042','00036','00034','00038','00037','00033','00035') ";
        $tSQL .= " ORDER BY API.FTApiCode ASC ";
        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->result_array();
        return $aResult;
    }

    //Get Data TLKMConfig  
    public function FSaMINMGetDataConfig(){
        $tSQL = " SELECT  *
                FROM TLKMConfig WITH(NOLOCK)
                LEFT JOIN TLKMConfig_L ON TLKMConfig.FTCfgCode = TLKMConfig_L.FTCfgCode AND TLKMConfig_L.FNLngID = 1
                WHERE TLKMConfig.FTCfgKey = 'Noti'
                AND TLKMConfig_L.FTCfgSeq = '1'
                AND TLKMConfig.FTCfgSeq = '1'  ORDER BY TLKMConfig.FTCfgCode ASC ";

        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->result_array();
        return $aResult;
    }

    //19-11-2020 เนลว์เพิ่ม UNION การขายของ Vending
    //Get Data DocNo
    public function FSaMINMGetDataDocNo($ptDocNoFrom,$ptDocNoTo,$ptBchCode){
        $tSQL = "SELECT aData.*  FROM
                ( SELECT
                    0 + ROW_NUMBER () OVER (ORDER BY FTXshDocNo ASC) AS rtRowID,
                    TPSTSalHD.FTBchCode AS FTBchCode,
                    TPSTSalHD.FTXshDocNo AS FTXshDocNo
                FROM
                    TPSTSalHD WITH (NOLOCK)
                UNION
                    SELECT
                        (
                            SELECT
                                COUNT (*)
                            FROM
                                TPSTSalHD
                            WHERE
                                1 = 1
                        ) + ROW_NUMBER () OVER (
                
                            ORDER BY
                                TVDTSalHD.FTXshDocNo ASC
                        ) AS rtRowID,
                        TVDTSalHD.FTBchCode AS FTBchCode,
                        TVDTSalHD.FTXshDocNo AS FTXshDocNo
                    FROM
                        TVDTSalHD WITH (NOLOCK)
                ) AS aData
                WHERE 1=1
                ";

        if($ptBchCode!=''){
            $tSQL .=" AND aData.FTBchCode = '$ptBchCode' ";
        }

        $tSQL .=" AND aData.FTXshDocNo BETWEEN '$ptDocNoFrom' AND '$ptDocNoTo' ";

        $oQuery     = $this->db->query($tSQL);
    

        $aResult    = $oQuery->result_array();
        return $aResult;
    }

    public function FSaMINMGetLogHisError(){
        $tSql ="SELECT
        LKH.FTLogTaskRef,
        SHD.FTBchCode
        
        FROM
        dbo.TLKTLogHis AS LKH
        LEFT OUTER JOIN TPSTSalHD SHD ON LKH.FTLogTaskRef = SHD.FTXshDocNo
        WHERE
        LKH.FTLogType = 2 AND
        LKH.FTLogStaPrc = 2
        ";

        $oQuery     = $this->db->query($tSql);
    
        $aResult    = $oQuery->result_array();
        return $aResult;
    }

    //ยกบิลขายที่จะใช้ ไปในตาราง Temp 
    public function FSxMIFXFillterBill($paData){

        //ล้างข้อมูลก่อน insert ลงใหม่
        $tSesUserCode = $this->session->userdata('tSesUserCode');
        $this->db->where('FTUsrCode',$tSesUserCode)->delete('TCNTBrsBillTmp');

        $dDateFrm       = $paData['dDateFrm'];		
        $dDateTo        = $paData['dDateTo'];		
        $tBCHCodeFrm    = $paData['tBCHCodeFrm'];	
        $tBCHCodeTo     = $paData['tBCHCodeTo'];
        $tSPLCodeFrm    = $paData['tSPLCodeFrm'];	
        $tSQLWhere      = ' WHERE 1=1 ';
        $tSQLWhereBCH   = '';

        if($paData['tType'] == 'DocBill'){  //เอกสารใบวางบิล
            $dDateDocNo = 'A.FDXphDueDate';
            $tSQLWhere .= "   ";

            //สาขา
            if($tBCHCodeFrm != '' || $tBCHCodeTo != ''){
                $tSQLWhereBCH .= " AND ( HD.FTBchCode BETWEEN '$tBCHCodeFrm' AND '$tBCHCodeTo' ) ";
            }

            //ผู้จำหน่าย
            if($tSPLCodeFrm != '' ){
                $tSPLCodeText   = str_replace(",","','",$tSPLCodeFrm);
                $tSQLWhereBCH  .= "AND HD.FTSplCode IN ('$tSPLCodeText')";
            }

        }else if($paData['tType'] == 'DocSale'){ //เอกสารการขาย
            $dDateDocNo = 'HD.FDXshDocDate';
            $tSQLWhere .= " AND HD.FTXshStaDoc = 1 ";

            if($tBCHCodeFrm != '' || $tBCHCodeTo != ''){
                $tSQLWhereBCH .= " AND HD.FTBchCode BETWEEN '$tBCHCodeFrm' AND '$tBCHCodeTo' ";
            }
        }else if($paData['tType'] == 'DocIV'){ //เอกสารใบซื้อ
            $dDateDocNo = 'HDSPL.FDXshRefDocDate';
            $tSQLWhere .= " AND HDSPL.FTXshRefType = 3 AND FTXshRefKey = 'BillNote'   ";

            //สาขา
            if($tBCHCodeFrm != '' || $tBCHCodeTo != ''){
                $tSQLWhereBCH .= " AND ( POHD.FTXphBchTo BETWEEN '$tBCHCodeFrm' AND '$tBCHCodeTo' 
                                        --OR HD.FTBchCode BETWEEN '$tBCHCodeFrm' AND '$tBCHCodeTo'
                                        OR DOHD.FTBchCode BETWEEN '$tBCHCodeFrm' AND '$tBCHCodeTo' ) ";
            }

            //ผู้จำหน่าย
            if($tSPLCodeFrm != '' ){
                $tSPLCodeText   = str_replace(",","','",$tSPLCodeFrm);
                $tSQLWhereBCH  .= "AND HD.FTSplCode IN ('$tSPLCodeText')";
            }
        }

        if($dDateFrm != '' || $dDateTo != '' ){
            $tSQLWhere .=" AND CONVERT(VARCHAR(10),$dDateDocNo,121) >= '$dDateFrm' ";
            $tSQLWhere .=" AND CONVERT(VARCHAR(10),$dDateDocNo,121) <= '$dDateTo' ";
        }
        

        if($paData['tType'] == 'DocBill'){  //เอกสารใบวางบิล
            $tSQL = "INSERT INTO TCNTBrsBillTmp 
                        SELECT
                            '$tSesUserCode' AS FTUsrCode,
                            Document.*,
                            GETDATE()       AS FTCreateOn , 
                            '$tSesUserCode' AS FTCreateBy
                        FROM (
                            SELECT DISTINCT A.FTXphDocNo AS FTXshDocNo , A.FDXphDueDate AS FTXshDocDate FROM ( 
                                SELECT 	
                                    HD.FTXphDocNo ,
                                    HD.FDXphDueDate 
                                FROM 
                                    TACTPbHD HD WITH (NOLOCK) 
                                    WHERE 1=1  AND HD.FTXphStaApv = 1 
                                    $tSQLWhereBCH  
                            ) AS A
                            $tSQLWhere
                        ) Document ORDER BY Document.FTXshDocNo ";

        }else if($paData['tType'] == 'DocSale'){ //เอกสารการขาย
            $tSQL = "INSERT INTO TCNTBrsBillTmp 
                        SELECT
                            '$tSesUserCode' AS FTUsrCode,
                            Document.*,
                            GETDATE()       AS FTCreateOn , 
                            '$tSesUserCode' AS FTCreateBy
                        FROM (
                            SELECT
                                HD.FTXshDocNo   AS FTXshDocNo,
                                HD.FDXshDocDate AS FTXshDocDate
                            FROM
                                TPSTSalHD HD WITH (NOLOCK)
                                $tSQLWhere
                        ) Document ORDER BY Document.FTXshDocNo ";
        }else if($paData['tType'] == 'DocIV'){ //เอกสารใบซื้อ
            $tSQL = "INSERT INTO TCNTBrsBillTmp 
                        SELECT
                            '$tSesUserCode' AS FTUsrCode,
                            Document.*,
                            GETDATE()       AS FTCreateOn , 
                            '$tSesUserCode' AS FTCreateBy
                        FROM (
                            SELECT DISTINCT A.FTXphDocNo AS FTXshDocNo , A.FDXphDocDate AS FTXshDocDate FROM ( 
                            SELECT 	
                                HD.FTXphDocNo ,
                                POHD.FTXphBchTo ,
                                HDSPL.FTXshRefDocNo  AS FTXshDocNo,
                                HD.FDXphDocDate 
                            FROM 
                                TAPTPiHD HD WITH (NOLOCK) 
                                INNER JOIN TAPTPiHDDocRef HDSPL WITH (NOLOCK) ON HD.FTXphDocNo = HDSPL.FTXshDocNo AND HD.FTBchCode = HDSPL.FTBchCode
                                LEFT JOIN TAPTPoHD POHD WITH (NOLOCK) ON HDSPL.FTXshRefDocNo = POHD.FTXphDocNo 
                                LEFT JOIN TAPTDoHD DOHD WITH (NOLOCK) ON HDSPL.FTXshRefDocNo = DOHD.FTXphDocNo
                                LEFT JOIN 
                                    (
                                            SELECT HD.FTXphDocNo , DT.FTXpdRefDocNo FROM TACTPbHD HD 
                                            LEFT JOIN TACTPbDT DT ON HD.FTXphDocNo = DT.FTXphDocNo
                                            WHERE HD.FTXphStaApv = 1
                                    )
                                PB ON HD.FTXphDocNo = PB.FTXpdRefDocNo
                                WHERE 1=1  AND HD.FTXphStaApv = 1 
                                AND ISNULL(PB.FTXphDocNo,'') = '' 
                                $tSQLWhereBCH  
                            ) AS A
                            INNER JOIN TAPTPiHDDocRef HDSPL WITH (NOLOCK) ON A.FTXphDocNo = HDSPL.FTXshDocNo
                            $tSQLWhere
                        ) Document ORDER BY Document.FTXshDocNo ";
        }

        $this->db->query($tSQL);
    }

    //ค้นหาว่าเอกสารปรับสต็อคมีใช้เเล้วหรือยัง  
    public function FSxMIFCheckDocumentADJ($paData){

        $tBCHCodeFrm    = $paData['tBCHCodeFrm'];
        $tBCHCodeTo     = $paData['tBCHCodeTo'];
        $tMonth         = $paData['tMonth'];
        $tYear          = $paData['tYear'];
        $tDataCondition = '01'.'/'.$tMonth.'/'.$tYear;

        $tSQL     = "   SELECT HD.FTAjhDocNo FROM TCNTPdtAdjStkHD HD
                        WHERE HD.FTAjhCountType = '1' AND HD.FTAjhDocType IN (2,3)
                        AND (HD.FTBchCode BETWEEN '$tBCHCodeFrm' AND '$tBCHCodeTo') 
                        AND ( MONTH(HD.FDAjhDocDate) = '$tMonth' AND YEAR(HD.FDAjhDocDate) = '$tYear' ) 
                        OR HD.FDAjhDocDate BETWEEN convert(datetime, '$tDataCondition', 103) - 5 AND convert(datetime, '$tDataCondition', 103) + 5 ";
        $oQuery   = $this->db->query($tSQL);
        $nRows    = $oQuery->num_rows();
        return $nRows;
    }

    // หาว่าเอกสาร ของสาขานั้นมีการค้างอนุมัติไหม
    public function FSaMIFXCheckDocAllAproveINBCH($paParam){
        //ใบรับเข้า (คลัง)         = TCNTPdtTwiHD FNXthDocType = 1
        //ใบเบิกออก (คลัง)        = TCNTPdtTwoHD FNXthDocType = 2
        //ใบจ่ายโอน (คลัง)        = TCNTPdtTwoHD FNXthDocType = 4
        //ใบรับโอน (คลัง)         = TCNTPdtTwiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างคลัง    = TCNTPdtTwxHD FTXthDocType = ''
        //ใบจ่ายโอน (สาขา)       = TCNTPdtTboHD
        //ใบรับโอน (สาขา)        = TCNTPdtTbiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างสาขา   = TCNTPdtTbxHD
        //ใบรับของ               = TAPTDoHD
        //ใบลดหนี้แบบมีสินค้า       = TAPTPcHD FNXphDocType = 6
        //ใบนัดหมาย              = TSVTBookHD
        //ใบจอง                 = TCNTPdtTwxHD FTXthDocType = 1
        //ใบรับรถ                = TSVTJob1ReqHD

        $ptBCHFrm   = $paParam['tBCHCodeFrm'];
        $ptBCHTo    = $paParam['tBCHCodeTo'];
        $tMonth     = $paParam['tMonth'];
        $tYear      = $paParam['tYear'];

        $tSQL = "SELECT COUNT(A.FTBchCode) AS rnCount FROM(

                    SELECT FTBchCode from TCNTPdtTwiHD WHERE FNXthDocType = 1 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwoHD WHERE FNXthDocType = 2 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwoHD WHERE FNXthDocType = 4 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwiHD WHERE FNXthDocType = 5 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwxHD WHERE ISNULL(FTXthDocType,'') = '' AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTboHD WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTbiHD WHERE FNXthDocType = 5 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTbxHD WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TAPTDoHD WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXphDocDate) = '$tMonth'
                            AND datepart(year,FDXphDocDate) = '$tYear'
                            AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TAPTPcHD WHERE FNXphDocType = 6 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXphDocDate) = '$tMonth'
                            AND datepart(year,FDXphDocDate) = '$tYear'
                            AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1

                    UNION ALL
                    
                    SELECT FTBchCode FROM TSVTBookHD WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' AND FTXshStaPrcDoc = 2
                            AND datepart(month,FDXshDocDate) = '$tMonth'
                            AND datepart(year,FDXshDocDate) = '$tYear'
                            AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1

                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwxHD WHERE FTXthDocType = 1 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo'
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1

                    UNION ALL

                    SELECT FTBchCode FROM TSVTJob1ReqHD WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXshDocDate) = '$tMonth'
                            AND datepart(year,FDXshDocDate) = '$tYear'
                            AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1

                ) AS A ";

        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->row_array();
        return $aResult['rnCount'];
    }

    // หาว่าเอกสาร ที่ยังไม่อนุมัติ มีอะไรบ้าง
    public function FSaMIFXCheckDocFindAproveINBCH($paParam){
        //ใบรับเข้า (คลัง)         = TCNTPdtTwiHD FNXthDocType = 1
        //ใบเบิกออก (คลัง)        = TCNTPdtTwoHD FNXthDocType = 2
        //ใบจ่ายโอน (คลัง)        = TCNTPdtTwoHD FNXthDocType = 4
        //ใบรับโอน (คลัง)         = TCNTPdtTwiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างคลัง    = TCNTPdtTwxHD FTXthDocType = ''
        //ใบจ่ายโอน (สาขา)       = TCNTPdtTboHD
        //ใบรับโอน (สาขา)        = TCNTPdtTbiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างสาขา   = TCNTPdtTbxHD
        //ใบรับของ               = TAPTDoHD
        //ใบลดหนี้แบบมีสินค้า       = TAPTPcHD FNXphDocType = 6
        //ใบนัดหมาย              = TSVTBookHD
        //ใบจอง                 = TCNTPdtTwxHD FTXthDocType = 1
        //ใบรับรถ                = TSVTJob1ReqHD

        $ptBCHFrm   = $paParam['tBCHCodeFrm'];
        $ptBCHTo    = $paParam['tBCHCodeTo'];
        $tMonth     = $paParam['tMonth'];
        $tYear      = $paParam['tYear'];
        
        //ลบและ insert ลงตาราง Log
        $tSQL       = "DELETE TCNTLogDocNoApv"; /*WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo'  ";*/
        $oQuery     = $this->db->query($tSQL);
        
        //insert ข้อมูล
        $tSQLMain1 = "  INSERT TCNTLogDocNoApv (
                                FTBchCode,
                                FTXthDocNo,
                                FDXthDocDate,
                                FTXthPrefix,
                                FTXthNotiCode,
                                FDCreateOn,
                                FTCreateBy 
                            ) SELECT  
                               C.rtBchCode,
                               C.rtDocNo,
                               C.rdDate,
                               C.rtTableName, 
                               C.rnCodeNoti, 
                               CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                               CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy FROM ( ";
        $tSQLSub   = "  SELECT A.* ,  CONVERT(varchar,rdDate, 103) + ' ' + CONVERT(varchar,rdDate, 108) AS rdDateFormat  , BCHL.FTBchName AS rtBchName FROM (	
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTwiHD' AS rtTableName  , '00013' AS rnCodeNoti
                            FROM TCNTPdtTwiHD WITH(NOLOCK) WHERE FNXthDocType = 1 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTwoHD' AS rtTableName , '00014' AS rnCodeNoti
                            FROM TCNTPdtTwoHD WITH(NOLOCK) WHERE FNXthDocType = 2 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTwoHD' AS rtTableName , '00015' AS rnCodeNoti
                            FROM TCNTPdtTwoHD WITH(NOLOCK) WHERE FNXthDocType = 4 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTwiHD' AS rtTableName , '00016' AS rnCodeNoti
                            FROM TCNTPdtTwiHD WITH(NOLOCK) WHERE FNXthDocType = 5 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTwxHD' AS rtTableName , '00017' AS rnCodeNoti
                            FROM TCNTPdtTwxHD WITH(NOLOCK) WHERE ISNULL(FTXthDocType,'') = '' AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTboHD' AS rtTableName  , '00008' AS rnCodeNoti
                            FROM TCNTPdtTboHD WITH(NOLOCK) WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTbiHD' AS rtTableName , '00009' AS rnCodeNoti
                            FROM TCNTPdtTbiHD WITH(NOLOCK) WHERE FNXthDocType = 5 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTbxHD' AS rtTableName , '00012' AS rnCodeNoti
                            FROM TCNTPdtTbxHD WITH(NOLOCK) WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXphDocDate AS rdDate , FTXphDocNo AS rtDocNo , 'TAPTDoHD' AS rtTableName , '00011' AS rnCodeNoti
                            FROM TAPTDoHD WITH(NOLOCK) WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXphDocDate) = '$tMonth'
                            AND datepart(year,FDXphDocDate) = '$tYear'
                            AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXphDocDate AS rdDate , FTXphDocNo AS rtDocNo , 'TAPTPcHD' AS rtTableName , '00018' AS rnCodeNoti
                            FROM TAPTPcHD WITH(NOLOCK) WHERE FNXphDocType = 6 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXphDocDate) = '$tMonth'
                            AND datepart(year,FDXphDocDate) = '$tYear'
                            AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXshDocDate AS rdDate , FTXshDocNo AS rtDocNo , 'TSVTBookHD' AS rtTableName  , '00019' AS rnCodeNoti
                            FROM TSVTBookHD WITH(NOLOCK) WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXshDocDate) = '$tMonth'
                            AND datepart(year,FDXshDocDate) = '$tYear'
                            AND FTXshStaPrcDoc = 2 AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXthDocDate AS rdDate , FTXthDocNo AS rtDocNo , 'TCNTPdtTwxHD' AS rtTableName , '00020' AS rnCodeNoti
                            FROM TCNTPdtTwxHD WITH(NOLOCK) WHERE FTXthDocType = 1 AND FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXthDocDate) = '$tMonth'
                            AND datepart(year,FDXthDocDate) = '$tYear'
                            AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                        UNION ALL
                            SELECT FTBchCode AS rtBchCode , FDXshDocDate AS rdDate , FTXshDocNo AS rtDocNo , 'TSVTJob1ReqHD' AS rtTableName , '00021' AS rnCodeNoti
                            FROM TSVTJob1ReqHD WITH(NOLOCK) WHERE FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                            AND datepart(month,FDXshDocDate) = '$tMonth'
                            AND datepart(year,FDXshDocDate) = '$tYear'
                            AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1 
                        ) AS A 
                        LEFT JOIN TCNMBranch_L BCHL ON A.rtBchCode = BCHL.FTBchCode AND FNLngID = '" . $this->session->userdata ( "tLangEdit" ) . "' ";
        $tSQLSub2   = " ORDER BY A.rtBchCode , A.rdDate ";
        $tSQLMain2  = "  ) AS C ORDER BY C.rtBchCode , C.rdDate";

        //เพิ่มข้อมูล
        $tSQLInsert = $tSQLMain1.$tSQLSub.$tSQLMain2;
        $this->db->query($tSQLInsert);

        //แสดงข้อมูล
        $oQuery     = $this->db->query($tSQLSub.$tSQLSub2);
        if ($oQuery->num_rows() > 0) {
            $aDataList  = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $aDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'raItems'       => array(),
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        return $aResult;
    }

    // เอาข้อมูลใน Temp มาโชว์
    public function FSaMIFXGetDataInTempDocNoApv(){
        
        //insert ข้อมูล
        $tSQL = "  SELECT  
                        DOCNO.FTBchCode,
                        BCHL.FTBchName,
                        DOCNO.FTXthDocNo,
                        DOCNO.FDXthDocDate,
                        DOCNO.FTXthPrefix,
                        DOCNO.FTXthNotiCode 
                    FROM TCNTLogDocNoApv DOCNO WITH(NOLOCK) 
                    LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK)  ON DOCNO.FTBchCode = BCHL.FTBchCode AND FNLngID = '" . $this->session->userdata ( "tLangEdit" ) . "' 
                    ORDER BY DOCNO.FTBchCode , DOCNO.FDXthDocDate ";

        //แสดงข้อมูล
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList  = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $aDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'raItems'       => array(),
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        return $aResult;
    }

    public function FSaMIFXCheckDocSaleUploadSuccess($paParam){
        $ptBCHFrm   = $paParam['tBCHCodeFrm'];
        $ptBCHTo    = $paParam['tBCHCodeTo'];
        $tDay	    = $paParam['tDay'];
        $tBillFrm	= $paParam['tBillFrm'];
        $tBillTo	= $paParam['tBillTo'];
        $tSQLWhere  = '';

        if( $tBillFrm != '' || $tBillTo != ''){
            $tSQLWhere = " WHERE HD.FTXshDocNo BETWEEN '$tBillFrm' AND '$tBillTo' ";
        }

        $tSQL = " SELECT COUNT(CalDiff.BillDiff) AS rnCount FROM (
                        SELECT
						    CASE WHEN ISNULL(SHD.FNShdQtyBill, 0) <> 0 THEN (ISNULL(SHD.FNShdQtyBill, 0) - ISNULL(SALHD.BillQty, 0)) ELSE (ISNULL(SHLD.BillChk, 0) - ISNULL(SALHD.BillQty, 0)) END BillDiff
                        FROM
                            TPSTShiftHD SHD
                        LEFT OUTER JOIN TCNMBranch_L BCHL ON SHD.FTBchCode = BCHL.FTBchCode
                        LEFT OUTER JOIN (
                            SELECT
                                HD.FTShfCode, HD.FTBchCode, HD.FTPosCode, COUNT (HD.FTXshDocNo) AS BillQty
                            FROM
                                TPSTSalHD HD
                            $tSQLWhere
                            GROUP BY HD.FTBchCode, HD.FTPosCode, HD.FTShfCode
                        ) SALHD ON SHD.FTBchCode = SALHD.FTBchCode AND SHD.FTPosCode = SALHD.FTPosCode AND SHD.FTShfCode = SALHD.FTShfCode
                        LEFT JOIN (
                            SELECT
                                FTBchCode, FTPosCode, FTShfCode,
                                SUM (ISNULL((CONVERT (BIGINT,RIGHT (SLD.FTLstDocNoTo, 7)) - CONVERT (BIGINT,RIGHT (SLD.FTLstDocNoFrm, 7))) + 1,0)) AS BillChk
                            FROM
                                TPSTShiftSLastDoc SLD
                            GROUP BY FTBchCode, FTPosCode, FTShfCode
                        ) SHLD ON SHD.FTBchCode = SHLD.FTBchCode AND SHD.FTPosCode = SHLD.FTPosCode AND SHD.FTShfCode = SHLD.FTShfCode
				WHERE
					CONVERT (DATE, SHD.FDShdSignIn, 103) = '$tDay'
                    AND SHD.FTBchCode BETWEEN '$ptBCHFrm' AND '$ptBCHTo' 
                ) CalDiff WHERE 1=1 AND  CalDiff.BillDiff <> 0 ";
        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->row_array();
        return $aResult['rnCount'];

    }
   
}


