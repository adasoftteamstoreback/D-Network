<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Withholdingtax_controller extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('document/withholdingtax/Withholdingtax_Model');
        
    }   

    public function index()// load view wWithholdingtax
    {
        $this->load->view('document/withholdingtax/wWithholdingtax');
    }

    public function FSvCWhTaxFormSearchList() { // load view List wWithholdingtaxSearchList
        $this->load->view('document/withholdingtax/wWithholdingtaxSearchList');
    }

    public function FSvCWhTaxDataTable() { // ดึงข้อมูลมาแสดงในตารางทั้งแบบมีการค้นหาและแบบแสดงทั้งหมด
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            
            $nPage = $this->input->post('nPageCurrent');

            // Get Option Show Decimal
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();

            // Page Current 
            if ($nPage == '' || $nPage == null) {
                $nPage = 1;
            } else {
                $nPage = $this->input->post('nPageCurrent');
            }
            // Lang ภาษา
            $nLangID = $this->session->userdata("tLangID");

            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID' => $nLangID,
                'nPage' => $nPage,
                'nRow' => 10,
                'aDatSessionUserLogIn' => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aDataList = $this->Withholdingtax_Model->FSaMWhTaxGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'nOptDecimalShow' => $nOptDecimalShow,
                'aDataList' => $aDataList,
            );
            $tWhTaxViewDataTableList = $this->load->view('document/withholdingtax/wWithholdingtaxDataTable', $aConfigView, true);
            $aReturnData = array(
                'tWhTaxViewDataTableList' => $tWhTaxViewDataTableList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        echo json_encode($aReturnData);
    }

    public function FSvCWhTaxViewDataPage() // หน้าแสดงผลข้อมูลใบถูกหักภาษี ณ ที่จ่าย
    {
        try {
            $aData = array(
                'aBchCode' => $this->input->post('ptWHTaxBchNo'),
                'aDocNo' => $this->input->post('ptWHTaxDocNo')
            );

            $tBchCode = $aData['aBchCode'];

            $aDataList = '';

            if ($aData['aBchCode'] != '' && $aData['aDocNo'] != '') {
                $aDataList = $this->Withholdingtax_Model->FSaMWhTaxGetDataView($aData);
                $aDataDetailList = $this->Withholdingtax_Model->FSaMWhTaxGetDetailList($aData);
                $aDataRefFile = $this->Withholdingtax_Model->FSaMWhTaxGetRefFile($aData);
            }

            $aData = array(
                'aDataList'       => $aDataList,
                'aDataDetailList' => $aDataDetailList,
                'aDataRefFile'    => $aDataRefFile,
                'aBchCode'        => $tBchCode
            );
            
            $this->load->view('document/withholdingtax/wWithholdingtaxViewDataPage', $aData);
        } catch (Exception $Error) {
            echo $Error;
        };
    }
}   

/* End of file Controllername.php */
