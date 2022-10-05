<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class mCommon extends CI_Model {
    //Functionality : Function Update Password User Login
    //Parameters : usrlogin , oldpass , newpass
    //Creator : 13/05/2020 Napat(Jame)
    // Last Update : 16/11/2020 Napat(Jame) เพิ่มการตรวจสอบ Error Message
    //Last Modified : -
    //Return : Status Update Password
    //Return Type : Array
    public function FCNaMCMMChangePassword($paPackData){
        try{
            $tSQL   = " 
                SELECT TOP 1
                    A.*,
                    CASE 
                        WHEN ISNULL(B.FTUsrLogin,'')     = '' THEN '999'  /*ไม่พบชื่อผู้ใช้*/
                        WHEN ISNULL(C.FTUsrLoginPwd,'')  = '' THEN '998'  /*รหัสผ่านเดิมไม่ถูกต้อง*/
                        WHEN ISNULL(B.FTUsrStaActive,'') = '2' THEN '997' /*สถานะไม่ใช้งาน ไม่สามารถเปลี่ยนรหัสผ่าน*/
                        WHEN CONVERT(VARCHAR(10),GETDATE(),121) > CONVERT(VARCHAR(10),B.FDUsrPwdExpired,121) THEN '996' /*หมดอายุไม่สามารถเปลี่ยนรหัสผ่าน*/
                        ELSE '0'
                    END AS FTErrMsg 
                FROM (
                    SELECT '1' AS Seq
                ) A
                LEFT JOIN TCNMUsrLogin B WITH(NOLOCK) ON B.FTUsrLogin = ".$this->db->escape($paPackData['FTUsrLogin'])."    AND B.FTUsrLogType  = ".$this->db->escape($paPackData['tStaLogType'])."
                LEFT JOIN TCNMUsrLogin C WITH(NOLOCK) ON C.FTUsrLogin = ".$this->db->escape($paPackData['FTUsrLogin'])."    AND C.FTUsrLoginPwd = ".$this->db->escape($paPackData['tPasswordOld'])."
            ";
            $oQuery     = $this->db->query($tSQL);
            $aListData  = $oQuery->result_array();
            if( $aListData[0]['FTErrMsg'] == '0' ){
                // ถ้าส่ง parameters UsrStaActive = 3 คือ เปลี่ยนรหัสผ่าน ครั้งแรก
                // ให้ปรับสถานะ = 1 เพื่อเริ่มใช้งาน
                if($paPackData['nChkUsrSta'] == 3){
                    $this->db->set('FTUsrStaActive'  , '1');
                }
                $this->db->set('FTUsrLoginPwd', $paPackData['tPasswordNew']);
                $this->db->where('FTUsrLogin', $paPackData['FTUsrLogin']);
                $this->db->where('FTUsrLoginPwd', $paPackData['tPasswordOld']);
                $this->db->update('TCNMUsrLogin');
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'nCode'     => 1,
                        'tDesc'     => 'Update Password Success',
                    );
                }else{
                    $aStatus = array(
                        'nCode'     => 905,
                        'tDesc'     => 'Error Cannot Update Password.',
                    );
                }
            }else{
                $aStatus = array(
                    'nCode'     => $aListData[0]['FTErrMsg'],
                    'tDesc'     => 'Data false',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Delete
    public function FCNaMCMMDeleteTmpExcelCasePDT($paPackData)
    {
        try {
            $aWhere = array('TCNMPdt', 'TCNMPdtUnit', 'TCNMPdtBrand', 'TCNMPdtTouchGrp', 'TCNMPdtType', 'TCNMPdtModel', 'TCNMPdtGrp', 'TCNMPdtSpcBch');
            $this->db->where_in('FTTmpTableKey', $aWhere);
            $this->db->where_in('FTSessionID', $paPackData['tSessionID']);
            $this->db->delete($paPackData['tTableNameTmp']);
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Import Excel To Temp
    public function FCNaMCMMImportExcelToTmp($paPackData, $poIns)
    {
        try {

            $tTableNameTmp      = $paPackData['tTableNameTmp'];
            $tNameModule        = $paPackData['tNameModule'];
            $tTypeModule        = $paPackData['tTypeModule'];
            $tFlagClearTmp      = $paPackData['tFlagClearTmp'];
            $tTableRefPK        = $paPackData['tTableRefPK'];

            //ลบข้อมูลทั้งหมดก่อน
            if ($tTypeModule == 'document' && $tFlagClearTmp == 1) {
                //ลบช้อมูลของ document
                if ($tTableNameTmp == 'TCNTPrnLabelTmp') {
                    $tIP = $this->input->ip_address();
                    $tFullHost = gethostbyaddr($tIP);
                    $this->db->where_in('FTComName', $tFullHost);
                    $this->db->delete($tTableNameTmp);
                } else {
                    $this->db->where_in('FTXthDocKey', $tTableRefPK);
                    $this->db->where_in('FTSessionID', $paPackData['tSessionID']);
                    $this->db->delete($tTableNameTmp);
                    if ($tNameModule == 'transferreceipt') {
                        $this->db->where_in('FTSessionID', $paPackData['tSessionID']);
                        $this->db->delete('TCNTDocDTSNTmp');
                        
                        if( $poIns[0]['FTPdtSetOrSN'] == '1' ){
                            $aDataInsertSN = array(
                                'FTAgnCode'     => '',
                                'FTBchCode'     => $poIns[0]['FTBchCode'],
                                'FTXthDocNo'    => $poIns[0]['FTXthDocNo'],
                                'FNXtdSeqNo'    => $poIns[0]['FNXtdSeqNo'],
                                'FTPdtSerial'   => '',
                                'FTXtdStaRet'   => '1',
                                'FTPdtBatchID'  => $poIns[0]['FTPgpChain'],
                                'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                                'FDCreateOn'    => date('Y-m-d h:i:s'),
                                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                                'FTXthDocKey'   => $tTableRefPK,
                                'FTSessionID'   => $poIns[0]['FTSessionID']
                            );
                            $this->db->insert('TCNTDocDTSNTmp', $aDataInsertSN);
                        }
                    }
                }
            } else if ($tTypeModule == 'master' && $tFlagClearTmp == 1) {
                //ลบข้อมูลของ master
                if ($tNameModule != 'product') {
                    $this->db->where_in('FTTmpTableKey', $tTableRefPK);
                    $this->db->where_in('FTSessionID', $paPackData['tSessionID']);
                    $this->db->delete($tTableNameTmp);
                }
            }

            //เพิ่มข้อมูล
            // echo "<pre>"; print_r($poIns); echo "</pre>";
            if( FCNnHSizeOf($poIns) > 0 ){
                $this->db->insert_batch($tTableNameTmp, $poIns);
            }

            // $tTableNameTmp, $poIns
            // echo "<pre>"; print_r($tTableNameTmp); print_r($poIns); echo "</pre>";

            /*เพิ่มข้อมูล
             $tNameProject   = explode('/', $_SERVER['REQUEST_URI'])[1];
             $tPathFileBulk  = $_SERVER['DOCUMENT_ROOT'].'/'.$tNameProject.'/application/modules/common/assets/writeFileImport/FileImport_Branch.txt';
             $tSQL = "BULK INSERT dbo.TCNTImpMasTmp FROM '".$tPathFileBulk."'
                     WITH
                     (
                         FIELDTERMINATOR=',',
                         ROWTERMINATOR = '\n'
            )";*/
        } catch (Exception $Error) {
            return $Error;
        }
    }
}