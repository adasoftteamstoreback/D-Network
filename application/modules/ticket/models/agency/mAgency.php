<?php
    defined('BASEPATH') or exit('No direct script access allowed');
class mAgency extends CI_Model {

    //Functionality : Search Agency By ID
    //Parameters : function parameters
    //Creator : 10/06/2019 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMAGNSearchByID($paData){
        $tAgnCode   = $paData['FTAgnCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT
                AGN.FTAgnCode,
                AGN.FTPplCode,
                PRL.FTPplName,
                AGN.FTAgnPwd,
                AGN.FTAgnMo,
                AGN.FTAgnFax,
                AGN.FTAgnStaActive,
                AGNL.FTAgnName,
                AGN.FTAgnRefCode,
                AGN.FTAgnType,
                FTAgnEmail,
                FTAgnTel,
                BCHL.FTBchCode,
                BCHL.FTBchName,
                CSTL.FTCstCode,
                CSTL.FTCstName,
                CASE FTAgnStaApv
                    WHEN '1' THEN 'อนุม้ติแล้ว'
                    WHEN '2' THEN 'ยังไม่'
                ELSE 'ไม่ระบุ' END AS  FTAgnStaApvText,
                FTAgnStaApv,
                IMP.FTImgRefID,
                IMP.FTImgObj,
                (SELECT COUNT (DISTINCT AGN.FTAgnCode) AS c FROM TCNMAgency AGN
                    LEFT JOIN TCNMAgency_L AGNL ON AGNL.FTAgnCode = AGN.FTAgnCode AND AGNL.FNLngID =  $nLngID
                        LEFT JOIN TCNMImgPerson IMP ON IMP.FTImgRefID = AGN.FTAgnCode AND IMP.FTImgTable = 'TCNMAgency'
                    WHERE 1 = 1
                ) AS counts,
                AGNSPC.FTCmpVatInOrEx,
                AGNSPC.FTVatCode,
                AGNSPC.FTRteCode,
                RTEL.FTRteName,
                CHNL.FTChnName,
                AGN.FTChnCode
            FROM TCNMAgency AGN WITH(NOLOCK)
            LEFT JOIN TCNMAgency_L AGNL WITH(NOLOCK) ON AGNL.FTAgnCode = AGN.FTAgnCode AND AGNL.FNLngID     =  ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMPdtPriList_L PRL WITH(NOLOCK) ON AGN.FTPplCode = PRL.FTPplCode AND PRL.FNLngID    =  ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMImgPerson IMP WITH(NOLOCK) ON IMP.FTImgRefID = AGN.FTAgnCode AND IMP.FTImgTable   = 'TCNMAgency' AND IMP.FNImgSeq = 1
            LEFT JOIN TCNMAgencySpc AGNSPC WITH(NOLOCK) ON AGN.FTAgnCode = AGNSPC.FTAgnCode
            LEFT JOIN TFNMRate_L     RTEL WITH(NOLOCK) ON AGNSPC.FTRteCode = RTEL.FTRteCode  AND RTEL.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON BCHL.FTBchCode = AGN.FTBchCode AND BCHL.FNLngID     = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMChannel_L CHNL WITH(NOLOCK) ON AGN.FTChnCode = CHNL.FTChnCode AND CHNL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON AGN.FTAgnRefCst = CSTL.FTCstCode AND CSTL.FNLngID      = ".$this->db->escape($nLngID)."
            WHERE AGN.FDCreateOn <> ''
        ";

        if($tAgnCode!= ""){
            $tSQL   .= "AND AGN.FTAgnCode = ".$this->db->escape($tAgnCode)."";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
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

    //Functionality : list Agency
    //Parameters : function parameters
    //Creator :  10/06/2019 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMAGNList($paData){
        $tUsrAgnCode    = $this->session->userdata("tSesUsrAgnCode");
        $tSesUserCode   = $this->session->userdata("tSesUserCode");
        $tWhere         = "";
        if(!empty($paData['tStaUsrLevel']) && $paData['tStaUsrLevel'] != "HQ"){
            $tWhere .=  " AND AGN.FTAgnCode = ".$this->db->escape($tUsrAgnCode)." ";
        }
        @$tSearchList   = $paData['tSearchAll'];
        if(@$tSearchList != ''){
            $tWhere .= " AND (AGN.FTAgnCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tWhere .= " OR  AGNL.FTAgnName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%' OR  FTAgnEmail COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%' )";
        }
        $aRowLen    = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID     = $paData['FNLngID'];
        $tSQL1      = " SELECT c.* FROM( SELECT  ROW_NUMBER() OVER(ORDER BY  FTAgnCode DESC) AS FNRowID,* FROM ( ";
        $tSubSQL    = "
            SELECT DISTINCT
                AGN.FTAgnCode,
                AGNL.FTAgnName,
                AGN.FTAgnType,
                AGN.FTChnCode,
                AGN.FDCreateOn,
                FTAgnEmail,
                FTAgnTel,
                BCHL.FTBchCode,
                BCHL.FTBchName,
                CASE FTAgnStaApv
                    WHEN '1' THEN 'อนุม้ติแล้ว'
                    WHEN '2' THEN 'ยังไม่'
                ELSE 'ไม่ระบุ' END AS  FTAgnStaApvText,
                FTAgnStaApv,
                FTAgnStaActive,
                IMP.FTImgRefID,
                IMP.FTImgObj,
                ISNULL(CHNL.FTChnName,'-') AS FTChnName
            FROM TCNMAgency AGN WITH(NOLOCK)
            LEFT JOIN TCNMAgency_L AGNL WITH(NOLOCK) ON AGNL.FTAgnCode = AGN.FTAgnCode AND AGNL.FNLngID     = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMImgPerson IMP WITH(NOLOCK) ON IMP.FTImgRefID = AGN.FTAgnCode AND IMP.FTImgTable   = 'TCNMAgency'
            LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON BCHL.FTBchCode = AGN.FTBchCode AND BCHL.FNLngID     = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMChannel_L CHNL WITH(NOLOCK) ON CHNL.FTChnCode = AGN.FTChnCode AND CHNL.FNLngID    = ".$this->db->escape($nLngID)."
            WHERE AGN.FDCreateOn <> ''
            $tWhere
        ";
        $tSQL2 = ") Base) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])."";
        $tMainSQL   = $tSQL1.$tSubSQL.$tSQL2;
        $oQuery     = $this->db->query($tMainSQL);
        $oSubQuery  = $this->db->query($tSubSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $nFoundRow  = $oSubQuery->num_rows();
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            //No Data
            $aResult    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult    = json_encode($aResult);
        $aResult    = json_decode($jResult, true);
        return $aResult;
    }

    public function FSaMAGNListRelation(){
        $tUsrAgnCode    = $this->session->userdata("tSesUsrAgnCode");
        $tSQL           = "SELECT FTAgnCode FROM TCNMBranch group by FTAgnCode";
        $oQuery         = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = $oList;
        }else{
            $aResult    = array();
        }
        $jResult    = json_encode($aResult);
        $aResult    = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : All Page Of Agency
    //Parameters : function parameters
    //Creator :  10/06/2019 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMAGNGetPageAll($ptSearchList,$ptLngID){
        $tSQL   = "
            SELECT COUNT (ANG.FTAgnCode) AS counts
            FROM TCNMAgency ANG
            LEFT JOIN [TCNMAgency_L] ANGL ON ANG.FTAgnCode = ANGL.FTAgnCode AND ANGL.FNLngID = ".$this->db->escape($ptLngID)."
            WHERE ANG.FDCreateOn
        ";
        if($ptSearchList != ''){
            $tSQL   .= " AND (ANG.FTAgnCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR ANGL.FTAgnName COLLATE THAI_BIN  LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    //Functionality : Update&insert Agency
    //Parameters : function parameters
    //Creator : 10/06/2019 saharat(Golf)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMAGNAddUpdateMaster($paData){
        try{
            if ($paData['FTAgnType'] == 1 && $paData['FTAgnRefCst'] != '') {
                $this->db->set('FTCstStaFC', NULL);
                $this->db->where('FTCstCode', $paData['FTAgnRefCst']);
                $this->db->update('TCNMCst');
            }
            //Update Master
            $this->db->set('FTAgnCode' , $paData['FTAgnCode']);
            $this->db->set('FTPplCode' , $paData['FTPplCode']);
            $this->db->set('FTAgnEmail' , $paData['FTAgnEmail']);
            $this->db->set('FTAgnPwd' , $paData['FTAgnPwd']);
            $this->db->set('FTAgnTel' , $paData['FTAgnTel']);
            $this->db->set('FTAgnFax' , $paData['FTAgnFax']);
            $this->db->set('FTAgnMo' , $paData['FTAgnMo']);
            $this->db->set('FTAgnStaApv' , $paData['FTAgnStaApv']);
            $this->db->set('FTAgnStaActive' , $paData['FTAgnStaActive']);
            $this->db->set('FTAgnRefCode', $paData['FTAgnRefCode']);
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FTChnCode', $paData['FTChnCode']);
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FDCreateOn' , $paData['FDCreateOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTCreateBy' , $paData['FTCreateBy']);
            if ($paData['FTAgnType'] == 1) {
                $paData['FTAgnRefCst'] = '';
            }
            $this->db->set('FTAgnType' , $paData['FTAgnType']);
            $this->db->set('FTAgnRefCst' , $paData['FTAgnRefCst']);
            $this->db->where('FTAgnCode', $paData['FTAgnCode']);
            $this->db->update('TCNMAgency');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNMAgency',array(
                    'FTAgnCode'         => $paData['FTAgnCode'],
                    'FTPplCode'         => $paData['FTPplCode'],
                    'FTAgnEmail'        => $paData['FTAgnEmail'],
                    'FTAgnPwd'          => $paData['FTAgnPwd'],
                    'FTAgnTel'          => $paData['FTAgnTel'],
                    'FTAgnFax'          => $paData['FTAgnFax'],
                    'FTAgnMo'           => $paData['FTAgnMo'],
                    'FTAgnStaApv'       => $paData['FTAgnStaApv'],
                    'FTAgnStaActive'    => $paData['FTAgnStaActive'],
                    'FTAgnRefCode'      => $paData['FTAgnRefCode'],
                    'FTChnCode'         => $paData['FTChnCode'],
                    'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                    'FDCreateOn'        => $paData['FDCreateOn'],
                    'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                    'FTCreateBy'        => $paData['FTCreateBy'],
                    'FTBchCode'         => $paData['FTBchCode'],
                    'FTAgnType'         => $paData['FTAgnType'],
                    'FTAgnRefCst'       => $paData['FTAgnRefCst']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Update Lang Agency
    //Parameters : function parameters
    //Creator : 10/06/2019 saharat(Golf)
    //Last Modified : -
    //Return : response
    //Return Type : num
    public function FSaMAGNAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTAgnName', $paData['FTAgnName']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTAgnCode', $paData['FTAgnCode']);
            $this->db->update('TCNMAgency_L');
            if($this->db->affected_rows() > 0 ){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                $this->db->insert('TCNMAgency_L',array(
                    'FTAgnCode'     => $paData['FTAgnCode'],
                    'FNLngID'       => $paData['FNLngID'],
                    'FTAgnName'     => $paData['FTAgnName']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Update Lang Agency
    //Parameters : function parameters
    //Creator : 09/12/2020 nattakit(nale)
    //Last Modified : -
    //Return : response
    //Return Type : num
    public function FSaMAGNAddUpdateSpc($paData){
        try{
            //Update Lang
            $this->db->set('FTCmpVatInOrEx', $paData['FTCmpVatInOrEx']);
            $this->db->set('FTVatCode', $paData['FTVatCode']);
            $this->db->set('FTRteCode', $paData['FTRteCode']);
            $this->db->where('FTAgnCode', $paData['FTAgnCode']);
            $this->db->update('TCNMAgencySpc');
            if($this->db->affected_rows() > 0 ){
                $this->db->set('FDLastUpdOn',$paData['FDLastUpdOn']);
                $this->db->set('FTLastUpdBy',$paData['FTLastUpdBy']);
                $this->db->where('FTAgnCode', $paData['FTAgnCode']);
                $this->db->update('TCNMAgency');
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                $this->db->insert('TCNMAgencySpc',array(
                    'FTAgnCode'     => $paData['FTAgnCode'],
                    'FTCmpVatInOrEx'     => $paData['FTCmpVatInOrEx'],
                    'FTVatCode'       => $paData['FTVatCode'],
                    'FTRteCode'     => $paData['FTRteCode']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Checkduplicate
    //Parameters : function parameters
    //Creator : 10/06/2016 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMAGNCheckDuplicate($ptCpnCode){
        $tSQL   = "
            SELECT COUNT(FTAgnCode)AS counts
            FROM TCNMAgency
            WHERE FTAgnCode = ".$this->db->escape($ptCpnCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //Functionality : Delete Agency
    //Parameters : function parameters
    //Creator : 11/06/2019 saharat(Golf)
    //Return : response
    //Return Type : array
    public function FSnMAGNDel($paData){
        $this->db->where_in('FTAgnCode', $paData['FTAgnCode']);
        $this->db->delete('TCNMAgency');
        $this->db->where_in('FTAgnCode', $paData['FTAgnCode']);
        $this->db->delete('TCNMAgency_L');
        $this->db->where_in('FTAgnCode', $paData['FTAgnCode']);
        $this->db->delete('TCNMAgencySpc');
        if($this->db->affected_rows() > 0){
            //อันนี้มันจะมีหรือไม่มีก็ได้
            $this->db->where_in('FTAgnCode', $paData['FTAgnCode']);
            $this->db->delete('TCNTConfigSpc');
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //Functionality : get all row
    //Parameters : -
    //Creator : 11/06/2019 saharat(Golf)
    //Return : array result from db
    //Return Type : array
    public function FSnMAGNGetAllNumRow(){
        $tSQL   = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMAgency";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    public function FSaMAGNAddUpdateCstFC($paData){
        try{
            if ($paData['FTCstCodeFCOld'] != '') {
                $this->db->set('FTCstStaFC', NULL);
                $this->db->where('FTCstCode', $paData['FTCstCodeFCOld']);
                $this->db->update('TCNMCst');
                if($this->db->affected_rows() > 0 ){
                    $this->db->set('FTCstStaFC', 1);
                    $this->db->where('FTCstCode', $paData['FTCstCode']);
                    $this->db->update('TCNMCst');
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Update Customer FC. Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Update Customer FC. Fail.',
                    );
                }
            }else{
                $this->db->set('FTCstStaFC', 1);
                $this->db->where('FTCstCode', $paData['FTCstCode']);
                $this->db->update('TCNMCst');
                if($this->db->affected_rows() > 0 ){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Update Customer FC. Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Update Customer FC. Fail.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }
}
?>
