<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Customerbranchaddress_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('customer/customer/CustomerBranchAddress_model');

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    // Functionality : Call View Branch Address
    public function FSvCCstBchAddressData(){
        $aDataConfigView    = [
            'aAlwBranchAddress'     => FCNaHCheckAlwFunc('branch/0/0'),
            'tCstCode'              => $this->input->post('tCstCode'),
            'tCstBchCode'           => $this->input->post('tCstBchCode')
        ];
        
        $this->load->view('customer/customer/tab/customerbranch/tab_address/wBranchAddressData',$aDataConfigView);
    }

    // Functionality : Call View Branch Address Data Table
    public function FSvCCstBchAddressDataTable(){
        $aDataWhere     = [
            'FNLngID'       => $this->session->userdata("tLangEdit"),
            'FTCstCode'   => $this->input->post('tCstCode'),
            'FTAddRefNo'  => $this->input->post('tCstBchCode')
        ];
        $aCstBchDataAddress = $this->CustomerBranchAddress_model->FSaMCstBchAddrDataList($aDataWhere);
        $aDataReturn = array(
            'aCstBchDataAddress' => $aCstBchDataAddress
        );
        
        $this->load->view('customer/customer/tab/customerbranch/tab_address/wBranchAddressDataTable',$aDataReturn);
    }

    // Functionality : Call View Branch Address Page Add
    public function FSvCCstBchAddressCallPageAdd(){
        $aCstBchDataVersion     = $this->CustomerBranchAddress_model->FSaMCstBchAddrGetVersion();
        $aDataViewAdd           = [
            'nStaCallView'          => 1, // 1 = Call View Add , 2 = Call View Edits
            'aCstBchDataVersion'    => $aCstBchDataVersion,
            'tCstCode'              =>  $this->input->post('tCstCode'),
            'tCstBchCode'           => $this->input->post('tCstBchCode')
        ];
        $this->load->view('customer/customer/tab/customerbranch/tab_address/wBranchAddressViewForm',$aDataViewAdd);
    }

    // Functionality : Call View Branch Address Page Edit
    public function FSvCCstBchAddressCallPageEdit(){
        $aDataWhereAddress  = [
            'FNLngID'       => $this->input->post('FNLngID'),
            'FTAddGrpType'  => $this->input->post('FTAddGrpType'),
            'FTAddRefNo'  => $this->input->post('FTAddRefNo'),
            'FNAddSeqNo'    => $this->input->post('FNAddSeqNo'),
            'FTCstCode'   =>  $this->input->post('FTCstCode'),
        ];

        $aCstBchDataVersion = $this->CustomerBranchAddress_model->FSaMCstBchAddrGetVersion();
        $aDataAddress       = $this->CustomerBranchAddress_model->FSaMCstBchAddrGetDataID($aDataWhereAddress);
        $aDataViewEdit      = [
            'nStaCallView'          => 2, // 1 = Call View Add , 2 = Call View Edits
            'tBchAddrBranchCode'    => $aDataWhereAddress['FTAddRefNo'],
            'aCstBchDataVersion'    => $aCstBchDataVersion,
            'aDataAddress'          => $aDataAddress,
            'tCstCode'              =>  $this->input->post('FTCstCode'),
            'tCstBchCode'           => $this->input->post('FTCbrRefBch')
        ];
        
        $this->load->view('customer/customer/tab/customerbranch/tab_address/wBranchAddressViewForm',$aDataViewEdit);
    }

    // Functionality : Event Branch Address Add
    public function FSoCCstBchAddressAddEvent(){
        try{
            $this->db->trans_begin();
            $tBranchAddrVersion = $this->input->post('ohdBranchAddressVersion');
            
            if(isset($tBranchAddrVersion) && $tBranchAddrVersion == 1){
                $aBranchDataAddress = [
                    'FTCstCode'         => $this->input->post('ohdBranchAddressCstCode'),
                    'FNLngID'           => $this->session->userdata("tLangEdit"),
                    'FTAddGrpType'      => $this->input->post("ohdBranchAddressGrpType"),
                    'FTAddRefNo'        => $this->input->post("ohdBranchAddressRefCode"),
                    'FTAddName'         => $this->input->post("oetBranchAddressName"),
                    'FTAddRmk'          => $this->input->post("oetBranchAddressRmk"),
                    'FTAreCode'         => '',
                    'FTZneCode'         => '',
                    'FTAddVersion'      => $tBranchAddrVersion,
                    'FTAddV1No'         => $this->input->post("oetBranchAddressNo"),
                    'FTAddV1Soi'        => $this->input->post("oetBranchAddressSoi"),
                    'FTAddV1Village'    => $this->input->post("oetBranchAddressVillage"),
                    'FTAddV1Road'       => $this->input->post("oetBranchAddressRoad"),
                    'FTAddV1SubDist'    => $this->input->post("oetBranchAddressSubDstCode"),
                    'FTAddV1DstCode'    => $this->input->post("oetBranchAddressDstCode"),
                    'FTAddV1PvnCode'    => $this->input->post("oetBranchAddressPvnCode"),
                    'FTAddV1PostCode'   => $this->input->post("oetBranchAddressPostCode"),
                    'FTAddWebsite'      => $this->input->post("oetBranchAddressWeb"),
                    'FTAddLongitude'    => $this->input->post("ohdBranchAddressMapLong"),
                    'FTAddLatitude'     => $this->input->post("ohdBranchAddressMapLat"),
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')
                ];

            }else{
                $aBranchDataAddress = [
                    'FTCstCode'         => $this->input->post('ohdBranchAddressCstCode'),
                    'FNLngID'           => $this->session->userdata("tLangEdit"),
                    'FTAddGrpType'      => $this->input->post("ohdBranchAddressGrpType"),
                    'FTAddRefNo'        => $this->input->post("ohdBranchAddressRefCode"),
                    'FTAddName'         => $this->input->post("oetBranchAddressName"),
                    'FTAddRmk'          => $this->input->post("oetBranchAddressRmk"),
                    'FTAreCode'         => 1,
                    'FTZneCode'         => 1,
                    'FTAddVersion'      => $tBranchAddrVersion,
                    'FTAddV2Desc1'      => $this->input->post("oetBranchAddressV2Desc1"),
                    'FTAddV2Desc2'      => $this->input->post("oetBranchAddressV2Desc2"),
                    'FTAddWebsite'      => $this->input->post("oetBranchAddressWebSite"),
                    'FTAddLongitude'    => $this->input->post("ohdBranchAddressMapLong"),
                    'FTAddLatitude'     => $this->input->post("ohdBranchAddressMapLat"),
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')
                ];
            }
            
            $this->CustomerBranchAddress_model->FSxMCstBchAddrAddData($aBranchDataAddress);

            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => "Error Unsucess Add Branch Address."
                );
            }else{
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaReturn'        => 1,
                    'tStaMessg'         => 'Success Add Branch Address.',
                    'tDataCodeReturn'   => $aBranchDataAddress['FTAddRefNo']
                );
            }
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['tCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality : Event Branch Address Edit
    public function FSoCCstBchAddressEditEvent(){
        try{
            $this->db->trans_begin();
            $tBranchAddrVersion = $this->input->post('ohdBranchAddressVersion');
            if(isset($tBranchAddrVersion) && $tBranchAddrVersion == 1){
                $aBranchDataAddress   = [
                    'FTCstCode'         => $this->input->post('ohdBranchAddressCstCode'),
                    'FNLngID'           => $this->session->userdata("tLangEdit"),
                    'FTAddGrpType'      => $this->input->post("ohdBranchAddressGrpType"),
                    'FTAddRefNo'        => $this->input->post("ohdBranchAddressRefCode"),
                    'FNAddSeqNo'        => $this->input->post("ohdBranchAddressSeqNo"),
                    'FTAddName'         => $this->input->post("oetBranchAddressName"),
                    'FTAddRmk'          => $this->input->post("oetBranchAddressRmk"),
                    'FTAddVersion'      => $tBranchAddrVersion,
                    'FTAddV1No'         => $this->input->post("oetBranchAddressNo"),
                    'FTAddV1Soi'        => $this->input->post("oetBranchAddressSoi"),
                    'FTAddV1Village'    => $this->input->post("oetBranchAddressVillage"),
                    'FTAddV1Road'       => $this->input->post("oetBranchAddressRoad"),
                    'FTAddV1SubDist'    => $this->input->post("oetBranchAddressSubDstCode"),
                    'FTAddV1DstCode'    => $this->input->post("oetBranchAddressDstCode"),
                    'FTAddV1PvnCode'    => $this->input->post("oetBranchAddressPvnCode"),
                    'FTAddV1PostCode'   => $this->input->post("oetBranchAddressPostCode"),
                    'FTAddWebsite'      => $this->input->post("oetBranchAddressWeb"),
                    'FTAddLongitude'    => $this->input->post("ohdBranchAddressMapLong"),
                    'FTAddLatitude'     => $this->input->post("ohdBranchAddressMapLat"),
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername')
                ];
            }else{
                $aBranchDataAddress   = [
                    'FTCstCode'         => $this->input->post('ohdBranchAddressCstCode'),
                    'FNLngID'           => $this->session->userdata("tLangEdit"),
                    'FTAddGrpType'      => $this->input->post("ohdBranchAddressGrpType"),
                    'FTAddRefNo'        => $this->input->post("ohdBranchAddressRefCode"),
                    'FNAddSeqNo'        => $this->input->post("ohdBranchAddressSeqNo"),
                    'FTAddName'         => $this->input->post("oetBranchAddressName"),
                    'FTAddRmk'          => $this->input->post("oetBranchAddressRmk"),
                    'FTAddVersion'      => $tBranchAddrVersion,
                    'FTAddV2Desc1'      => $this->input->post("oetBranchAddressV2Desc1"),
                    'FTAddV2Desc2'      => $this->input->post("oetBranchAddressV2Desc2"),
                    'FTAddWebsite'      => $this->input->post("oetBranchAddressWebSite"),
                    'FTAddLongitude'    => $this->input->post("ohdBranchAddressMapLong"),
                    'FTAddLatitude'     => $this->input->post("ohdBranchAddressMapLat"),
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'        => $this->session->userdata('tSesUsername')
                ];
            }
            
            $this->CustomerBranchAddress_model->FSxMCstBchAddrEditData($aBranchDataAddress);

            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaReturn' => 500,
                    'tStaMessg' => "Error Unsucess Update Branch Address."
                );
            }else{
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaReturn'        => 1,
                    'tStaMessg'         => 'Success Update Branch Address.',
                    'tDataCodeReturn'   => $aBranchDataAddress['FTAddRefNo']
                );
            }
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['tCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality : Event Branch Address Delete
    public function FSoCCstBchAddressDeleteEvent(){
        try{
            $this->db->trans_begin();

            $aDataWhereDelete   = [
                'FNLngID'       => $this->input->post('FNLngID'),
                'FTAddGrpType'  => $this->input->post('FTAddGrpType'),
                'FTAddRefNo'  => $this->input->post('FTAddRefNo'),
                'FNAddSeqNo'    => $this->input->post('FNAddSeqNo'),
                'FTCstCode'    => $this->input->post('FTCstCode')
            ];

            $this->CustomerBranchAddress_model->FSaMCstBchAddrDelete($aDataWhereDelete);

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaReturn' => 500,
                    'tStaMessg' => "Error Unsucess Delete Branch Address."
                );
            }else{
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaReturn'        => 1,
                    'tStaMessg'         => 'Success Delete Branch Address.',
                    'tDataCodeReturn'   => $aDataWhereDelete['FTAddRefNo']
                );
            }
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaReturn'    => $Error['tCodeReturn'],
                'tStaMessg'     => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }



}
