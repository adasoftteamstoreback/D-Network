<?php
use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class JobRequeststep1Discount_controller extends MX_Controller {
    public $tRouteMenu  = 'docJR1/0/0';

    public function __construct() {
        parent::__construct ();
        $this->load->model('document/jobrequeststep1/jobrequeststep1Discount_model');
    }

    // Functionality    : Function Call Data From JR1 HD
    // Parameters       : Ajax and Function Parameter
    // Creator          : 14/10/2021 Wasin
    public function FSoCJR1DisChgHDList(){
        try{
            $tDocNo             = $this->input->post('tDocNo');
            $nSeqNo             = $this->input->post('tSeqNo');
            $tBchCode           = $this->input->post('tBCHCode');
            $aAdvanceSearch     = $this->input->post('oAdvanceSearch');
            $nPage              = $this->input->post('nPageCurrent');
            $aAlwEvent          = FCNaHCheckAlwFunc($this->tRouteMenu);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Page Current 
            if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}

            // Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");

            // Data Conditon Get Data Document
            $aDataCondition  = array(
                'FNLngID'           => $nLangEdit,
                'nPage'             => $nPage,
                'nRow'              => 10,
                'aAdvanceSearch'    => $aAdvanceSearch,
                'tDocNo'            => $tDocNo,  
                'nSeqNo'            => $nSeqNo,
                'tBchCode'          => $tBchCode,
                'tSessionID'        => $this->session->userdata('tSesSessionID')
            );
            $aDataList  = $this->jobrequeststep1Discount_model->FSaMJR1GetDisChgHDList($aDataCondition);

            $aConfigView    = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tJR1ViewDataTableList   = $this->load->view('document/jobrequeststep1/dis_chg/wJobReq1DisChgHDList', $aConfigView, true);
            $aReturnData = array(
                'tJR1ViewDataTableList'  => $tJR1ViewDataTableList,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }
    
    // Functionality    : Function Call Data From JR1 DT
    // Parameters       : Ajax and Function Parameter
    // Creator          : 14/10/2021 Wasin
    public function FSoCJR1DisChgDTList(){
        try{
            $tDocNo             = $this->input->post('tDocNo');
            $nSeqNo             = $this->input->post('tSeqNo');
            $tBchCode           = $this->input->post('tBCHCode');
            $aAdvanceSearch     = $this->input->post('oAdvanceSearch');
            $nPage              = $this->input->post('nPageCurrent');
            $aAlwEvent          = FCNaHCheckAlwFunc($this->tRouteMenu);
            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            // Page Current 
            if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
            // Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");
            // Data Conditon Get Data Document
            $aDataCondition  = array(
                'tDocNo'            => $tDocNo,  
                'nSeqNo'            => $nSeqNo,
                'tBchCode'          => $tBchCode,
                'FNLngID'           => $nLangEdit,
                'nPage'             => $nPage,
                'nRow'              => 10,
                'aAdvanceSearch'    => $aAdvanceSearch,
                'tSessionID'        => $this->session->userdata('tSesSessionID')
            );
            $aDataList  = $this->jobrequeststep1Discount_model->FSaMJR1GetDisChgDTList($aDataCondition);
            $aConfigView    = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tJR1ViewDataTableList   = $this->load->view('document/jobrequeststep1/dis_chg/wJobReq1DisChgDTList', $aConfigView, true);
            $aReturnData = array(
                'tJR1ViewDataTableList' => $tJR1ViewDataTableList,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function         : เพิ่มและแก้ไข ส่วนลดรายการ
    // Parameters       : Ajax and Function Parameter
    // Creator          : 14/10/2021 Wasin
    public function FSoCJR1AddEditDTDis(){
        try{
            $tBchCode       = $this->input->post('tBchCode');
            $tDocNo         = $this->input->post('tDocNo');
            $nSeqNo         = $this->input->post('tSeqNo');
            $tVatInOrEx     = $this->input->post('tVatInOrEx');
            $tSessionID     = $this->session->userdata('tSesSessionID');
            $tDisChgItems   = $this->input->post('tDisChgItems');
            $tDisChgSummary = $this->input->post('tDisChgSummary');
            $aDisChgItems   = json_decode($tDisChgItems, true);
            $aDisChgSummary = json_decode($tDisChgSummary, true);

            $this->db->trans_begin();
            // ================================ Begin DB Process ================================
                $aParams    = array(
                    'nStaDis'         => 1,
                    'tDocNo'          => $tDocNo,
                    'nSeqNo'          => $nSeqNo,
                    'tBchCode'        => $tBchCode,
                    // 'nLngID'          => $this->session->userdata("tLangID"),
                    'tSessionID'      => $tSessionID,
                    'tVatInOrEx'      => $tVatInOrEx,
                    'aDisChgSummary'  => $aDisChgSummary
                );
                $this->jobrequeststep1Discount_model->FSaMJR1ClearDisChgTxtDTTemp($aParams);
                $this->jobrequeststep1Discount_model->FSaMJR1DeleteDTDisTemp($aParams);

                if(isset($aDisChgItems) && !empty($aDisChgItems)){
                    $aInsertDTDisTmp    =   array();
                    foreach ($aDisChgItems as $key => $item){
                        array_push($aInsertDTDisTmp,array(
                            'FTBchCode'         => $tBchCode,
                            'FTXthDocNo'        => $tDocNo,
                            'FNXtdSeqNo'        => $item['nSeqNo'],
                            'FDXtdDateIns'      => date('Y-m-d H:i:s',strtotime($item['tCreatedAt'])),
                            'FTXtdDisChgTxt'    => $item['tDisChgTxt'],
                            'FNXtdStaDis'       => $item['tStaDis'],
                            'FTXtdDisChgType'   => $item['nDisChgType'],
                            'FCXtdNet'          => $item['cAfterDisChg'],
                            'FCXtdValue'        => $item['cDisChgValue'],
                            'FTSessionID'       => $tSessionID,
                            'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                            'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                            'FDCreateOn'        => date('Y-m-d H:i:s'),
                            'FTCreateBy'        => $this->session->userdata('tSesUsername')
                        ));
                    }
                    //Insert TCNTDocDTDisTmp 
                    $this->jobrequeststep1Discount_model->FSaMJR1AddEditDTDisTemp($aInsertDTDisTmp);
                    //Update TCNTDocDTTmp 
                    $this->jobrequeststep1Discount_model->FSaMJR1UpdateDTDisInTemp($aInsertDTDisTmp);
                }
            // ==================================================================================
                
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Not Insert Document DT Dis Temp.'
                );
            }else{
                $this->db->trans_commit();

                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '',
                    'tDataVatInOrEx'    => $tVatInOrEx,
                    'tDataDocNo'        => $tDocNo,
                    'tDataDocKey'       => 'TSVTJob1ReqDT',
                    'tDataSeqNo'        => $nSeqNo
                ];
                $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if($aStaCalcDTTemp === TRUE){
                    // Prorate HD
                    FCNaHCalculateProrate('TSVTJob1ReqDT',$tDocNo);
                    FCNbHCallCalcDocDTTemp($aCalcDTParams);
                    $aCalEndOfBillHDDisParams = [
                        'tDocNo'        => $tDocNo,
                        'tBchCode'      => $tBchCode,
                        'tSessionID'    => $tSessionID,
                        'tSplVatType'   => $tVatInOrEx,
                        'nLngID'        => '',
                        'tDocKey'       => 'TSVTJob1ReqDT',
                        'nSeqNo'        => $nSeqNo
                    ];
                    FSvCCreditNoteCalEndOfBillHDDis($aCalEndOfBillHDDisParams);
                    $aReturnData    = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => 'Success Insert Document Dis Temp.'
                    );
                }else{
                    $aReturnData    = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => 'Error Not Calcurate DT Temp.'
                    );
                }
            }
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function         : เพิ่มและแก้ไข ส่วนลดท้ายบิล
    // Parameters       : Ajax and Function Parameter
    // Creator          : 14/10/2021 Wasin
    public function FSoCJR1AddEditHDDis(){
        try{
            $tBchCode       = $this->input->post('tBchCode');
            $tDocNo         = $this->input->post('tDocNo');
            $nSeqNo         = $this->input->post('tSeqNo');
            $tVatInOrEx     = $this->input->post('tVatInOrEx');
            $tSessionID     = $this->session->userdata('tSesSessionID');
            $tDisChgItems   = $this->input->post('tDisChgItems');
            $tDisChgSummary = $this->input->post('tDisChgSummary');
            $aDisChgItems   = json_decode($tDisChgItems, true);
            $aDisChgSummary = json_decode($tDisChgSummary, true);

            // ================================ Begin DB Process ================================
            $aParams = array(
                'tDocNo'          => $tDocNo,  
                'tBchCode'        => $tBchCode,
                'nLngID'          => $this->session->userdata("tLangID"),
                'tSessionID'      => $this->session->userdata('tSesSessionID'),
                'aDisChgSummary'  => $aDisChgSummary
            );
            
            // Delete Dis/Chg Tabel HD DIS Temp
            $this->jobrequeststep1Discount_model->FSaMJR1DeleteHDDisTemp($aParams);

            $this->db->trans_begin();
                if(isset($aDisChgItems) && !empty($aDisChgItems)){
                    $aInsertHDDisTmp    =   array();
                    foreach ($aDisChgItems as $nKey =>  $aItem) {
                        array_push($aInsertHDDisTmp,array(
                            'FTBchCode'             => $tBchCode,
                            'FTXthDocNo'            => $tDocNo,
                            'FDXtdDateIns'          => date('Y-m-d H:i:s',strtotime($aItem['tCreatedAt'])),
                            'FTXtdDisChgTxt'        => $aItem['tDisChgTxt'],
                            'FTXtdDisChgType'       => $aItem['nDisChgType'],
                            'FCXtdTotalAfDisChg'    => $aItem['cAfterDisChg'],
                            'FCXtdTotalB4DisChg'    => $aItem['cBeforeDisChg'],
                            'FCXtdDisChg'           => $aItem['cDisChgNum'],
                            'FCXtdAmt'              => $aItem['cDisChgValue'],
                            'FTSessionID'           => $tSessionID,
                            'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                            'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                            'FDCreateOn'            => date('Y-m-d H:i:s'),
                            'FTCreateBy'            => $this->session->userdata('tSesUsername')
                        ));
                    }
                    $this->jobrequeststep1Discount_model->FSaMJR1AddEditHDDisTemp($aInsertHDDisTmp);
                }
            // ==================================================================================
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Not Insert Document HD Dis Temp.'
                );
            }else{
                $this->db->trans_commit();
                // Prorate HD
                FCNaHCalculateProrate('TAPTPiDT',$tDocNo);
                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '',
                    'tDataVatInOrEx'    => $tVatInOrEx,
                    'tDataDocNo'        => $tDocNo,
                    'tDataDocKey'       => 'TAPTPiDT',
                    'tDataSeqNo'        => ''
                ];
                $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if($aStaCalcDTTemp === TRUE){
                    $aReturnData    = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => 'Success process'
                    );
                }else{
                    $aReturnData    = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => 'Error Calcurate DT Document Temp.'
                    );
                }
            }
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

}