<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cInterfaceCheckImport extends MX_Controller {

    public $tRouteMenu  = 'product/0/0';
    public function __construct(){
        parent::__construct ();
        $this->load->model('interface/interfacecheckimport/mInterfaceCheckImport');
        date_default_timezone_set("Asia/Bangkok");
    }

    // Functionality : CallPage Index
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function index(){ 
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMGetImportList();  
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceImportList',$aGenTable);  
    }

    public function FSvCChkImportList(){ //load view มาแสดง
        $nCode       = $this->input->post('nCode');
        $aGenTable  = array(
            'nCode'   => $nCode,
        );

        $this->load->view ( 'interface/interfacecheckimport/wInterfaceCheckImportList', $aGenTable);
    }

    // Functionality : CenterToProfiCenter
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkCostCenterToProfiCenter(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKCostCenterToProfiCenter($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceCheckImportCostCenter',$aGenTable); 
    }

    // Functionality : InterBA
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkInterBA(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'tSearchAll'        => $tSearchAll,
            'nPage'             => $nPage,
            'nRow'              => 10,
            
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKInterBA($aData);
        $aGenTable  = array(
            'tSearchAll'        => $tSearchAll,
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            
        );
        $this->load->view('interface/interfacecheckimport/wInterBA',$aGenTable); 
    }

    // Functionality : SaleStaff
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkSaleStaff(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKSaleStaff($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceCheckImportSaleStaff',$aGenTable); 
    }

    // Functionality : Customer
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkCustomer(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKCustomer($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceCheckImportCustomer',$aGenTable); 
    }

    // Functionality : ChkRole
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkRole(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKRole($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceCheckImportRole',$aGenTable); 
    }

    // Functionality : SaleForStore
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkSaleForStore(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKSaleforStore($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceCheckImporSaleForStore',$aGenTable); 
    }

    // Functionality : CallPage ODL
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportCar(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKCar($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,

        );
        $this->load->view('interface/interfacecheckimport/wInterfaceCheckImportCar',$aGenTable); 
    }
    // Functionality : Productss
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportProducts(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'    => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProducts($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'    => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceProducts',$aGenTable); 
    }

    // Functionality : ProductGroup
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportProductGroup(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'    => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductGroup($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'    => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceProductGroup',$aGenTable); 
    }

    // Functionality : ProductDept
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportProductDept(){ //load view มาแสดง
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $tSearchAll       = $this->input->post('tSearchAll');
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductDept($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,
        );
        $this->load->view('interface/interfacecheckimport/wInterfaceProductDept',$aGenTable); 
    }

    // Functionality : ProductUnitSmalls
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportProductUnitSmalls(){ //load view มาแสดง
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');
        $tSearchAll       = $this->input->post('tSearchAll'); 
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductUnitSmall($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,

        );
        $this->load->view('interface/interfacecheckimport/wInterfaceProductUnitSmalls',$aGenTable); 
    }

    // Functionality : ProductComponent
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportProductComponent(){ //load view มาแสดง
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $tSearchAll       = $this->input->post('tSearchAll');
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductComponent($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,

        );
        $this->load->view('interface/interfacecheckimport/wInterfaceProductComponent',$aGenTable); 
    }

    // Functionality : ProductPrice
    // Parameters : -
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportProductPrice(){ //load view มาแสดง
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
        $tSearchAll       = $this->input->post('tSearchAll');
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'        => $tSearchAll,
        );
        $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductPrice($aData);
        $aGenTable  = array(
            'aDataListImport'   => $aDataListImport,
            'nPage'             => $nPage,
            'tSearchAll'        => $tSearchAll,

        );
        $this->load->view('interface/interfacecheckimport/wInterfaceProductPrice',$aGenTable); 
    }

    // Functionality : SelectPage
    // Parameters : ajax paramiter from wInterfaceProducts
    // Creator : 22/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function FSvCChkImportSelectPage(){ //load view มาแสดง
        $tSearchAll       = $this->input->post('tSearchAll');
        $nPage            = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');
        $aData  = array(
            'nPage'             => $nPage,
            'nRow'              => 10,
            'tSearchAll'    => $tSearchAll,
        );
        $tproduct   =  $this->input->post('tproduct'); // รับค่ามาจาก wInterfaceProducts ใน Ajax
        switch ($tproduct) {
            case "1":     // ค่าที่ได้จาก Select option
                $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProducts($aData);
                $aGenTable  = array(
                    'aDataListImport'   => $aDataListImport,
                    'nPage'             => $nPage,
                    'tSearchAll'        => $tSearchAll,
                );
                $this->load->view('interface/interfacecheckimport/wInterfaceProducts' ,$aGenTable ); 
              break;
            case "2":     // ค่าที่ได้จาก Select option  
                $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductGroup($aData);
                $aGenTable  = array(
                    'aDataListImport'   => $aDataListImport,
                    'nPage'             => $nPage,
                    'tSearchAll'        => $tSearchAll,
                    
                    
                );
                $this->load->view('interface/interfacecheckimport/wInterfaceProductGroup',$aGenTable); 
              break;
            case "3":     // ค่าที่ได้จาก Select option   
                $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductDept($aData); 
                $aGenTable  = array(
                    'aDataListImport'   => $aDataListImport,
                    'nPage'             => $nPage,
                    'tSearchAll'        => $tSearchAll,
                );
                $this->load->view('interface/interfacecheckimport/wInterfaceProductDept',$aGenTable);
            break;
              case "4":  // ค่าที่ได้จาก Select option
                $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductUnitSmall($aData); 
                $aGenTable  = array(
                    'aDataListImport'   => $aDataListImport,
                    'nPage'             => $nPage,
                    'tSearchAll'        => $tSearchAll,
                );   
                $this->load->view('interface/interfacecheckimport/wInterfaceProductUnitSmalls',$aGenTable);
            break;
              case "5":  // ค่าที่ได้จาก Select option
                $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductComponent($aData); 
                $aGenTable  = array(
                    'aDataListImport'   => $aDataListImport,
                    'nPage'             => $nPage,
                    'tSearchAll'        => $tSearchAll,
                );
                $this->load->view('interface/interfacecheckimport/wInterfaceProductComponent',$aGenTable);
            break;
              case "6": // ค่าที่ได้จาก Select option
                $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProductPrice($aData); 
                $aGenTable  = array(
                    'aDataListImport'   => $aDataListImport,
                    'nPage'             => $nPage,
                    'tSearchAll'        => $tSearchAll,
                );
                $this->load->view('interface/interfacecheckimport/wInterfaceProductPrice',$aGenTable);
              break;
            default: // ค่า default
                $aDataListImport  = $this->mInterfaceCheckImport->FSaMcHKProducts($aData);
                $aGenTable  = array(
                    'aDataListImport'   => $aDataListImport,
                    'nPage'             => $nPage,
                    'tSearchAll'        => $tSearchAll,
                );
                $this->load->view('interface/interfacecheckimport/wInterfaceProducts',$aGenTable);
        }
    }
}