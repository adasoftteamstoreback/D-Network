<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cPromotionStep4RcvCondition extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('document/promotion/mPromotionStep4RcvCondition');
        $this->load->model('document/promotion/mPromotion');
    }

    /**
     * Functionality : Get PdtPmtHDRcv in Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSxCPromotionGetHDRcvInTmp()
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

        $aGetPdtPmtHDCstPriInTmpParams  = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 50,
            'tSearchAll' => $tSearchAll,
            'tUserSessionID' => $tUserSessionID
        );
        $aResList = $this->mPromotionStep4RcvCondition->FSaMGetPdtPmtHDRcvInTmp($aGetPdtPmtHDCstPriInTmpParams);

        // print_r($aResList); die();

        $aGenTable = array(
            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage' => $nPage,
            'nOptDecimalShow' => $nOptDecimalShow
        );
        $tHtml = $this->load->view('document/promotion/advance_table/wStep4RcvConditionTableTmp', $aGenTable, true);
        
        $aResponse = [
            'html' => $tHtml
        ];

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aResponse));
    }

    /**
     * Functionality : Insert PdtPmtHDRcv to Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaCPromotionInsertRcvToTmp()
    {
        $tRcvList = $this->input->post('tRcvList');
        $nLangEdit = $this->session->userdata("tLangEdit");
        $tUserSessionID = $this->session->userdata("tSesSessionID");
        $tUserSessionDate = $this->session->userdata("tSesSessionDate");
        $tUserLoginCode = $this->session->userdata("tSesUsername");
        $tUserLevel = $this->session->userdata('tSesUsrLevel');
        $tBchCodeLogin = $tUserLevel == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata("tSesUsrBchCodeDefault");
        
        $aRcvList = json_decode($tRcvList);



        if(!isset($aRcvList[0]) || !isset($aRcvList[1])) {
            return;
        }

        $tRcvCode = $aRcvList[0];
        $tRcvName = $aRcvList[1];

        $this->db->trans_begin();

        $aPdtPmtHDCstPriToTempParams = [
            'tDocNo' => 'PMTDOCTEMP',
            'tRcvCode' => $tRcvCode,
            'tRcvName' => $tRcvName,
            'tBchCodeLogin' => $tBchCodeLogin,
            'tUserSessionID' => $tUserSessionID,
            'tUserSessionDate' => $tUserSessionDate,
            'tUserLoginCode' => $tUserLoginCode,
            'nLngID' => $nLangEdit
        ];
        
        $this->mPromotionStep4RcvCondition->FSaMPdtPmtHDRcvToTemp($aPdtPmtHDCstPriToTempParams);

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
     * Functionality : Update PdtPmtHDRcv in Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxCPromotionUpdateRcvInTmp()
    {
        $tDocNo = $this->input->post('tDocNo');
        $tRcvCode = $this->input->post('tRcvCode');
        $tBchCode = $this->input->post('tBchCode');
        $tPmhStaType = $this->input->post('tPmhStaType');
        $tUserSessionID = $this->session->userdata("tSesSessionID");
        $tUserLoginCode = $this->session->userdata("tSesUsername");
        
        $this->db->trans_begin();

        $aUpdatePmtCBInTmpBySeqParams = [
            'tDocNo' => $tDocNo,
            'tRcvCode' => $tRcvCode,
            'tBchCode' => $tBchCode,
            'tPmhStaType' => $tPmhStaType,
            'tUserLoginCode' => $tUserLoginCode,
            'tUserSessionID' => $tUserSessionID
        ];
        $this->mPromotionStep4RcvCondition->FSbUpdateRcvInTmpByKey($aUpdatePmtCBInTmpBySeqParams);

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
     * Functionality : Delete PdtPmtHDRcv by Primary Key in Temp
     * Parameters : -
     * Creator : 17/09/2021 Woakorn
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxCPromotionDeleteRcvInTmp()
    {
        $tBchCode = $this->input->post('tBchCode');
        $tDocNo = $this->input->post('tDocNo');
        $tRcvCode = $this->input->post('tRcvCode');
        $tUserSessionID = $this->session->userdata("tSesSessionID");

        $this->db->trans_begin();

        $aDeleteInTmpByKeyParams = [
            'tUserSessionID' => $tUserSessionID,
            'tBchCode' => $tBchCode,
            'tDocNo' => $tDocNo,
            'tRcvCode' => $tRcvCode
        ];
        // print_r($aDeleteInTmpByKeyParams); die();
        $this->mPromotionStep4RcvCondition->FSbDeletePdtPmtHDRcvInTmpByKey($aDeleteInTmpByKeyParams);

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