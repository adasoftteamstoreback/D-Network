<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CustomerBranch_controller extends MX_Controller {
    
    public function __construct(){
        parent::__construct ();
        $this->load->model('customer/customer/CustomerBranch_model');
        date_default_timezone_set("Asia/Bangkok");

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }
    
    /**
     * Functionality : Function Call District Page List
     */
    public function FSvCstBchListPage(){
        $tCstCode = $this->input->post('tCstCode');
        $aParam = array(
            'tCstCode' => $tCstCode,
        );
        $this->load->view('customer/customer/tab/customerbranch/wCstBchTabList',$aParam);
    }

    /**
     * Functionality : Function Call DataTables Customer
     */
    public function FSvCstBchDataList(){
        $tCstCode       = $this->input->post('tCstCode');
        $nPage          = $this->input->post('nPageCurrent');
        $tSearchAll     = $this->input->post('tSearchAll');
        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}
        //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'tCstCode'      => $tCstCode,
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll
        );

        $aResList = $this->CustomerBranch_model->FSaMCstBchList($aData);
        $aGenTable = array(
            'aDataList' => $aResList,
            'nPage' => $nPage,
            'tSearchAll' => $tSearchAll
        );
        $this->load->view('customer/customer/tab/customerbranch/wCstBchTabDataTable', $aGenTable);
    }
    
    /**
     * Functionality : Function Call Page Customer Add
     */
    public function FSvCstBchAddPage(){
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $tCstCode = $this->input->post('tCstCode');

        $aDataAdd = array(
            'tCstCode'  => $tCstCode,
            'aResult'   => array('rtCode'=>'99'),
            'FNLngID'   => $nLangEdit,
        );
        
        $this->load->view('customer/customer/tab/customerbranch/wCstBchTabPageForm',$aDataAdd);
    }




    /**
     * Functionality : Function Call Page Customer Add
     */
    public function FSaCstBchAddEvent(){
        try{
            $tCstCode = $this->input->post('ohdCstCode');
            $nCstBchSeq = $this->input->post('ohdCstBchSeq');
            $tCstBchCode = $this->input->post('oetCstBchCode_InTab');
            $tCstBchName = $this->input->post('oetCstBchName_InTab');
            $tCstBchRegNo = $this->input->post('oetCstBchRegNo');
            $nCstBchStatus = $this->input->post('ocmCstBchStatus');
            $tCstBchShipTo = $this->input->post('oetCstBchShipTo');
            $tCstBchSoldTo = $this->input->post('oetCstBchSoldTo');
            $tCstBchCreateBy = $this->input->post('ohdCstBchCreateBy');
    
            $nLastSeq =  $this->CustomerBranch_model->FSaMGetLastSeqCstBch($tCstCode);
            

            $aDataCstBch = array(
                'FTCstCode' => $tCstCode,
                'FNCbrSeq' => $nLastSeq+1,
                'FTCbrBchCode' => $tCstBchCode,
                'FTCbrBchName' => $tCstBchName,
                'FTCbrRegNo' => $tCstBchRegNo,
                'FTCbrShipTo' => $tCstBchShipTo,
                'FTCbrSoldTo' => $tCstBchSoldTo,
                'FTCbrStatus' => $nCstBchStatus,
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FTLastUpdBy' => $this->session->userdata('tSesUserCode'),
                'FDCreateOn' => date('Y-m-d H:i:s'),
                'FTCreateBy' => $this->session->userdata('tSesUserCode'),
            );
            
            $this->db->trans_begin();
            $this->CustomerBranch_model->FSaCstBchInsertUpdate($aDataCstBch);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Event"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaEvent'    => '01',
                    'tStaMessg'    => "Sucess Add Event"
                );

            }
            
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }


     /**
     * Functionality : Function Call Page Customer Add
     */
    public function FSvCstBchEditPage(){
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $tCstCode       = $this->input->post('tCstCode');
        $aParam = array(
            'FNLngID' => $nLangEdit,
            'FTCstCode' => $this->input->post('tCstCode'),
            'FNCbrSeq' => $this->input->post('nCbrSeq'),
        );
        $aResult = $this->CustomerBranch_model->FSaMCstBchSearchByID($aParam);
        $aDataAdd = array(
            'aResult'   => $aResult,
            'FNLngID'   => $nLangEdit,
            'tCstCode'  => $tCstCode,
        );
        
        $this->load->view('customer/customer/tab/customerbranch/wCstBchTabPageForm',$aDataAdd);
    }



    /**
     * Functionality : Function Call Page Customer Edit
     */
    public function FSaCstBchEditEvent(){
        try{

            $tCstCode   = $this->input->post('ohdCstCode');
            $nCstBchSeq = $this->input->post('ohdCstBchSeq');
            $tCstBchCode = $this->input->post('oetCstBchCode_InTab');
            $tCstBchName = $this->input->post('oetCstBchName_InTab');
            $tCstBchRegNo = $this->input->post('oetCstBchRegNo');
            $nCstBchStatus = $this->input->post('ocmCstBchStatus');
            $tCstBchShipTo = $this->input->post('oetCstBchShipTo');
            $tCstBchSoldTo = $this->input->post('oetCstBchSoldTo');

            $aDataCstBch = array(
                'FTCstCode' => $tCstCode,
                'FNCbrSeq' => $nCstBchSeq,
                'FTCbrBchCode' => $tCstBchCode,
                'FTCbrBchName' => $tCstBchName,
                'FTCbrRegNo' => $tCstBchRegNo,
                'FTCbrShipTo' => $tCstBchShipTo,
                'FTCbrSoldTo' => $tCstBchSoldTo,
                'FTCbrStatus' => $nCstBchStatus,
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FTLastUpdBy' => $this->session->userdata('tSesUserCode')
            );

            $this->db->trans_begin();
            $this->CustomerBranch_model->FSaCstBchInsertUpdate($aDataCstBch);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Event"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaEvent'    => '01',
                    'tStaMessg'    => "Sucess Add Event"
                );

            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }
    /**
     * Functionality : Delete Customer Branch
     */
    public function FSoCstBchDelete(){
        try{
            $tCstCode = $this->input->post('tCstCode');
            $nCstBchSeq = $this->input->post('nCstBchSeq');

            $tCbrRefBch =  $this->db->where('FTCstCode',$tCstCode)->where('FNCbrSeq',$nCstBchSeq)->get('TCNMCstBch')->row_array()['FTCbrBchCode'];
            if(!empty($tCbrRefBch)){
                $aParamData = array(
                        'FTCstCode'=> $tCstCode,
                        'FTAddRefNo' => $tCbrRefBch,
                        'FTAddGrpType' => 4
                );
                $this->CustomerBranch_model->FSnMCstBchDelBchAddr($aParamData);
            }
            $aCst = ['FTCstCode' => $tCstCode,'FNCbrSeq' => $nCstBchSeq ];
            $this->CustomerBranch_model->FSnMCstBchDel($aCst);
            $aReturn = array(
                'nStaEvent'    => '01',
                'tStaMessg'    => "Sucess Delete Event"
            );
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }
    
    

}
