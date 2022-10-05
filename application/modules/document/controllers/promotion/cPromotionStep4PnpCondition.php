<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cPromotionStep4PnpCondition extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('document/promotion/mPromotionStep4PnpCondition');
        $this->load->model('document/promotion/mPromotion');
    }

    /**
     * Functionality : Get PdtPmtHDPnp in Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSxCPromotionGetHDPnpInTmp()
    {
        $tSearchAll = $this->input->post('tSearchAll');
        $nPage = $this->input->post('nPageCurrent');
        $aAlwEvent = FCNaHCheckAlwFunc('promotion/0/0');
        $nOptDecimalShow = FCNxHGetOptionDecimalShow();
        $tUserSessionID = $this->session->userdata("tSesSessionID");
        $tUserLevel = $this->session->userdata('tSesUsrLevel');
        $tBchCodeLogin = $tUserLevel == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata("tSesUsrBchCodeDefault");

        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPageCurrent');
        }
        $nLangEdit = $this->session->userdata("tLangEdit");

        $aGetPdtPmtHDPnpPriInTmpParams  = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 50,
            'tSearchAll' => $tSearchAll,
            'tUserSessionID' => $tUserSessionID
        );
        $aResList = $this->mPromotionStep4PnpCondition->FSaMGetPdtPmtHDPnpInTmp($aGetPdtPmtHDPnpPriInTmpParams);

      

        $aGenTable = array(
            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage' => $nPage,
            'nOptDecimalShow' => $nOptDecimalShow
        );
        $tHtml = $this->load->view('document/promotion/advance_table/wStep4PnpConditionTableTmp', $aGenTable, true);
        
        $aResponse = [
            'html' => $tHtml
        ];

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aResponse));
    }

    /**
     * Functionality : Insert PdtPmtHDPnp to Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaCPromotionInsertPnpToTmp()
    {

       
        $tPnpList = $this->input->post('tPnpList');
        $nLangEdit = $this->session->userdata("tLangEdit");
        $tUserSessionID = $this->session->userdata("tSesSessionID");
        $tUserSessionDate = $this->session->userdata("tSesSessionDate");
        $tUserLoginCode = $this->session->userdata("tSesUsername");
        $tUserLevel = $this->session->userdata('tSesUsrLevel');
        $tBchCodeLogin = $tUserLevel == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata("tSesUsrBchCodeDefault");
        
        $aPnpList = json_decode($tPnpList);



        if(!isset($aPnpList[0]) || !isset($aPnpList[1])) {
            return;
        }

        $tPnpCode = $aPnpList[0];
        $tPnpName = $aPnpList[1];

        $this->db->trans_begin();

        $aPdtPmtHDPnpPriToTempParams = [
            'tDocNo' => 'PMTDOCTEMP',
            'tPnpCode' => $tPnpCode,
            'tPnpName' => $tPnpName,
            'tBchCodeLogin' => $tBchCodeLogin,
            'tUserSessionID' => $tUserSessionID,
            'tUserSessionDate' => $tUserSessionDate,
            'tUserLoginCode' => $tUserLoginCode,
            'nLngID' => $nLangEdit
        ];
        
   
        $this->mPromotionStep4PnpCondition->FSaMPdtPmtHDPnpToTemp($aPdtPmtHDPnpPriToTempParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess InsertPaymentTypeToTmp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success InsertPaymentTypeToTmp'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Update PdtPmtHDPnp in Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxCPromotionUpdatePnpInTmp()
    {
        $tDocNo = $this->input->post('tDocNo');
        $tPnpCode = $this->input->post('tPnpCode');
        $tBchCode = $this->input->post('tBchCode');
        $tPmhStaType = $this->input->post('tPmhStaType');
        $tUserSessionID = $this->session->userdata("tSesSessionID");
        $tUserLoginCode = $this->session->userdata("tSesUsername");
        
        $this->db->trans_begin();

        $aUpdatePmtCBInTmpBySeqParams = [
            'tDocNo' => $tDocNo,
            'tPnpCode' => $tPnpCode,
            'tBchCode' => $tBchCode,
            'tPmhStaType' => $tPmhStaType,
            'tUserLoginCode' => $tUserLoginCode,
            'tUserSessionID' => $tUserSessionID
        ];
        $this->mPromotionStep4PnpCondition->FSbUpdatePnpInTmpByKey($aUpdatePmtCBInTmpBySeqParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess UpdatePaymentTypeInTmp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success UpdatePaymentTypeInTmp'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Delete PdtPmtHDPnp by Primary Key in Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxCPromotionDeletePnpInTmp()
    {
        $tBchCode = $this->input->post('tBchCode');
        $tDocNo = $this->input->post('tDocNo');
        $tPnpCode = $this->input->post('tPnpCode');
        $tUserSessionID = $this->session->userdata("tSesSessionID");

        $this->db->trans_begin();

        $aDeleteInTmpByKeyParams = [
            'tUserSessionID' => $tUserSessionID,
            'tBchCode' => $tBchCode,
            'tDocNo' => $tDocNo,
            'tPnpCode' => $tPnpCode
        ];
        // print_r($aDeleteInTmpByKeyParams); die();
        $this->mPromotionStep4PnpCondition->FSbDeletePdtPmtHDPnpInTmpByKey($aDeleteInTmpByKeyParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess DeletePaymentTypeInTmp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success DeletePaymentTypeInTmp'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }
}