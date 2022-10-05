<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CarDetail_controller extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('service/car/CarDetail_model');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index($nCarCode){
        $aResult = $this->CarDetail_model->FSaMCarDetailSearchByID($nCarCode);
        $aReturn = array(
            'aResult' => $aResult
        );
        $this->load->view('service/car/wCarDetail', $aReturn);
    }
}
