<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/rabbitmq/vendor/autoload.php');
require_once(APPPATH . 'config/rabbitmq.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Checknews_controller extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index($nType){
        $tLangEdit  = $this->session->userdata("tLangEdit");
        $tUserCode  = $this->session->userdata('tSesUserCode');
        $aData      = array(
            'tLangEdit'     => $tLangEdit,
            'tUserCode'     => $tUserCode,
            'nType'         => $nType,
        );
        $this->load->view('checknews/wChecknews',$aData);
    }

    //Functionality : Get Page Form And Request Api Customer
    //Parameters : FTRGCstKey Customer Key
    //Creator : 11/01/2021 Nale
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCNWKGetPageForm(){
        $tMNTTypePage   = $this->input->post('tMNTTypePage');
        $aDataParam     = array(
            'tMNTTypePage' => $tMNTTypePage,
        );
        $this->load->view('checknews/wChecknewsPageForm',$aDataParam);
    }

    //Functionality : Get Page Form And Request Api Customer
    //Parameters : FTRGCstKey Customer Key
    //Creator : 11/01/2021 Nale
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCNWKGetPageDataTable(){
        $tMNTTypePage       = $this->input->post('tMNTTypePage');
        $aDataNotiNews      = $this->session->userdata("aDataNotiNews");
        $dMNTDocDateFrom    = $this->input->post('tMNTDocDateFrom');
        $dMNTDocDateTo      = $this->input->post('tMNTDocDateTo');
        $tMNTDocType        = $this->input->post('tMNTDocType');
        $aDatTableNoti      = array();
        
        if(!empty($aDataNotiNews)){
            foreach($aDataNotiNews as $nKey => $aData){
        
                if(!empty($dMNTDocDateFrom)){
                    if($dMNTDocDateFrom>date('Y-m-d',strtotime($aData['FDNotDate']))){
                        continue;
                    }
                }

                if(!empty($dMNTDocDateTo)){
                    if($dMNTDocDateTo<date('Y-m-d',strtotime($aData['FDNotDate']))){
                        continue;
                    }
                }

                $aDatTableNoti[]=$aData;
            }
        }

        $aDataParam = array(
            'tMNTTypePage'      => $tMNTTypePage,
            'aDataNotiNews'     => $aDatTableNoti,
        );
        $this->load->view('checknews/wChecknewsDataTable',$aDataParam);
    }
}
