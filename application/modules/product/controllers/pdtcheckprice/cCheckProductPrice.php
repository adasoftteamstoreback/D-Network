<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cCheckProductPrice extends MX_Controller {
    public function __construct(){
        parent::__construct ();
        $this->load->model('product/pdtcheckprice/mCheckProductPrice');
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nPIBrowseType, $tPIBrowseOption) {
        $aDataConfigView = array(
            'nPIBrowseType'     => $nPIBrowseType,
            'tPIBrowseOption'   => $tPIBrowseOption,
        );
        $this->load->view('product/pdtcheckprice/wPdtCheckPrice', $aDataConfigView);
        unset($aDataConfigView);
        unset($nPIBrowseType,$tPIBrowseOption);
    }

    //Functionality : Function Get ProductPrice List
	//Parameters : -
	//Creator : 03/09/2020 Sooksanti(Non)
	//Last Modified :-
	//Return :-
	//Return Type : -
    public function FSxCPPGetListPage(){
        $nPage      = $this->input->post('nPageCurrent');
        $aParams    = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nRow'              => 100,
            'nPage'             => $nPage,
            'oAdvanceSearch'    => $this->input->post('oAdvanceSearch'),
            'nPagePDTAll'       => $this->input->post('nPagePDTAll'),
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'tDisplayType'      => $this->input->post('tDisplayType')
        );
        $aDataList  = $this->mCheckProductPrice->FSaMCPPGetListData($aParams);
        $aData      = [
            'aDataList'         => $aDataList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'tPdtForSys'        => $this->input->post('tPdtForSys'),
            'tDisplayType'      => $this->input->post('tDisplayType')
        ];
        $this->load->view('product/pdtcheckprice/wPdtCheckPriceTable',$aData);
        unset($nPage,$aParams,$aDataList,$aData);
    }

    function FSxCPPFormSearchList(){
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aParams    = array(
            'FNLngID'   => $nLangEdit,
        );
        $aDataList  = $this->mCheckProductPrice->FSaMCPPGetPriList($aParams);
        $aData      = [
            'aDataList'  => $aDataList,
        ];
        $this->load->view('product/pdtcheckprice/wPdtCheckPriceSearchlist',$aData);
        unset($nLangEdit,$aParams,$aDataList,$aData);
    }

}