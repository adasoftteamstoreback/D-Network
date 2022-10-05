<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cBrowserPDTCallView extends MX_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('common/mBrowserPDTCallView');
        $this->load->model('company/branch/mBranch');

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    //List PDT Serach
    public function index(){
        //hidden filter search
        $Qualitysearch  = $this->input->post('Qualitysearch');

        //price type
        $PriceType      = $this->input->post('PriceType');

        //Select Tier : เลือกระดับไหน [PDT,Barcode]
        $SelectTier     = $this->input->post('SelectTier');
        if (isset($SelectTier)) {
            $SelectTier = $SelectTier[0];
        } else {
            $SelectTier = 'Barcode';
        }

        //element return input
        $tElementreturn     = $this->input->post('Elementreturn');

        // ******************************************************************************************************
        // Create By Witsarut 02/07/2020  เก็บข้อมูลลง  Cookie
        $nCheckPage  =  $this->input->cookie("PDTCookie_" . $this->session->userdata("tSesUserCode"), true);
        $tCookieVal = json_decode($nCheckPage);

        if (!empty($nCheckPage)) {
            $nPerPage = $tCookieVal->nPerPage;
        } else {
            $nPerPage = '';
        }

        //ShowCountRecord
        if ($nPerPage == '' || null) {
            $nShowCountRecord  = $this->input->post('ShowCountRecord');
        } else {
            $nShowCountRecord   = $nPerPage;
        }

        // ******************************************************************************************************

        //name NextFunc
        $tNameNextFunc      = $this->input->post('NextFunc');

        //Return type S,M
        $tReturnType        = $this->input->post('ReturnType');

        //Parameter SPL
        $tParameterSPL      = $this->input->post('SPL');

        //Parameter BCH
        $tParameterBCH      = $this->input->post('BCH');

        //Parameter MCH
        $tParameterMER      = $this->input->post('MER');

        //Parameter SHP
        $tParameterSHP      = $this->input->post('SHP');

        //Parameter DISTYPE
        $tParameterDISTYPE      = $this->input->post('DISTYPE');

        //Get Time for localstorage
        $tTimeLocalstorage      = $this->input->post('TimeLocalstorage');

        //Parameter Product Code
        $tParameterProductCode  = $this->input->post('ProductCode');

        //Parameter Agen Code
        $tParameterAgenCode     = $this->input->post('AgenCode');

        //Parameter NotInPdtType
        $tNotInPdtType          = $this->input->post('NotInPdtType');

        //Parameter Where
        $tWhere                 = $this->input->post('Where');

        //Parameter PageName
        $tPagename              = $this->input->post('PageName');
        if (isset($tPagename)) { $tPagename = $tPagename; } else { $tPagename = ''; }

        //Not in Item
        $aNotinItem             = $this->input->post('NOTINITEM');

        //Product Service
        $tPDTService            = $this->input->post('PDTService');
        if (isset($tPDTService)) { $tPDTService = $tPDTService; } else { $tPDTService = ''; }

        //Parameter Type
        $tTYPEPDT               = $this->input->post('tTYPEPDT');
        if (isset($tTYPEPDT)) { $tTYPEPDT = $tTYPEPDT; } else { $tTYPEPDT = ''; }

        //Parameter Product Type (New)
        $tNewPdtType            = $this->input->post('tNewPdtType');
        if (isset($tNewPdtType)) { $tNewPdtType = $tNewPdtType; } else { $tNewPdtType = ''; }

        //Parameter Array Allow Product Type (New)
        $aAlwPdtType            = $this->input->post('aAlwPdtType');
        if (isset($aAlwPdtType)) { $aAlwPdtType = $aAlwPdtType; } else { $aAlwPdtType = array(); }

        //Parameter Status Default Product Type
        $nStaDefaultPdtType     = $this->input->post('nStaDefaultPdtType');
        if (isset($nStaDefaultPdtType)) { $nStaDefaultPdtType = $nStaDefaultPdtType; } else { $nStaDefaultPdtType = 'ALL'; } // Default S5 ศูนย์บริการ สำหรับ FitAuto

        //Parameter Status Control Stock
        $tStaControlStk         = $this->input->post('tStaControlStk');
        if (isset($tStaControlStk)) { $tStaControlStk = $tStaControlStk; } else { $tStaControlStk = ''; }

        //Parameter Wahouse
        $tParameterWAH          = $this->input->post('WAH');

        //Parameter S/N
        $tSNPDT                 = $this->input->post('tSNPDT');
        if (isset($tSNPDT)) { $tSNPDT = $tSNPDT; } else { $tSNPDT = ''; }

        $aData = array(
            'nShowCountRecord'  => get_cookie('nShowRecordInPageList'), //$nShowCountRecord,
            'aQualitysearch'    => $Qualitysearch,
            'aPriceType'        => $PriceType,
            'tSelectTier'       => $SelectTier,
            'tElementreturn'    => $tElementreturn,
            'tNameNextFunc'     => $tNameNextFunc,
            'tReturnType'       => $tReturnType,
            'SesBch'            => $this->session->userdata("tSesUsrBchCode"),
            'SesShp'            => $this->session->userdata("tSesUsrShpCode"),
            'SesMer'            => $this->session->userdata("tSesUsrMerCode"),
            'tParameterSPL'     => $tParameterSPL,
            'tParameterMER'     => $tParameterMER,
            'tParameterBCH'     => $tParameterBCH,
            'tParameterSHP'     => $tParameterSHP,
            'tParameterDISTYPE' => $tParameterDISTYPE,
            'tTimeLocalstorage' => $tTimeLocalstorage,
            'tPagename'         => $tPagename,
            'aNotinItem'        => $aNotinItem,
            'tParameterProductCode' => $tParameterProductCode,
            'tParameterAgenCode'=> $tParameterAgenCode,
            'tNotInPdtType'     => $tNotInPdtType,
            'tWhere'            => $tWhere,
            'tPDTService'       => $tPDTService,
            'tTYPEPDT'          => $tTYPEPDT,
            'tSNPDT'            => $tSNPDT,
            'tNewPdtType'       => $tNewPdtType,
            'aAlwPdtType'       => $aAlwPdtType,
            'nStaDefaultPdtType' => $nStaDefaultPdtType,
            'tStaControlStk'     => $tStaControlStk,
            'tParameterWAH'      => $tParameterWAH
        );

        $this->load->view('common/wBrowsePDTCallView', $aData);
    }

    //วิ่งเข้าฟังก์ชั่น get product โยนพวก parameter ไป
    public function FSxGetProductfotPDT()
    {
        $tBarcode           = $this->input->post('tTextScan');
        $tProductCode       = $this->input->post('ProductCode');
        $tAgenCode          = $this->input->post('AgenCode');
        $tNotInPdtType      = $this->input->post('tNotInPdtType');
        $tWhere             = $this->input->post('tWhere');
        if ($tBarcode == '' || $tBarcode == null) { //เลือกสินค้าผ่านหน้าจอ
            $nPage                  =  $this->input->post("nPage");
            $tPagename              =  $this->input->post("tPagename");

            //เก็บข้อมูลลง  Cookie
            $nCheckPage  =  $this->input->cookie("PDTCookie_" . $this->session->userdata("tSesUserCode"), true);
            $tCookieVal = json_decode($nCheckPage);

            if (!empty($nCheckPage)) {
                $nPerPage = $tCookieVal->nPerPage;
            } else {
                $nPerPage = '';
            }

            if ($nPerPage == '' || null) {
                $nRow  = $this->input->post('nRow');
            } else {
                $nRow   = $nPerPage;
            }

            $aPriceType             =  json_decode($this->input->post("aPriceType"));
            $tBCH                   =  $this->input->post("BCH");
            $tSHP                   =  $this->input->post("SHP");
            $tMER                   =  $this->input->post("MER");
            $tSPL                   =  $this->input->post("SPL");
            $nDISTYPE               =  $this->input->post("DISTYPE");
            $tSelectTier            =  $this->input->post("SelectTier");
            $tReturnType            =  $this->input->post("ReturnType");
            $aNotinItem             =  $this->input->post("aNotinItem");
            $nTotalResult           =  $this->input->post("nTotalResult");
            $tSearchSelect          =  '';
            $tSearchText            =  '';
            $aPackDataForSerach     =  $this->input->post("aPackDataForSerach");
            $tTYPEPDT               =  $this->input->post("tTYPEPDT");
            $tSNPDT                 =  $this->input->post("tSNPDT");
            $tFindOnlyPDT           =  'normal';
        } else { //ผ่านการยิงสแกน
            $tBCH                   =  $this->input->post("BCH");
            $tSHP                   =  $this->input->post("SHP");
            $tMER                   =  $this->input->post("MER");
            $tSPL                   =  $this->input->post("SPL");
            $aPriceType             =  $this->input->post("aPriceType");
            $tSearchText            =  trim($tBarcode);
            $tSearchSelect          =  'PDTANDBarcode';
            $nTotalResult           =  0;
            $nRow                   =  90000;
            $nPage                  =  1;
            $tReturnType            =  'S';
            $tFindOnlyPDT           =  'Barcode';
            $tTYPEPDT               =  '';
            $tSNPDT                 =  '';
            $aPackDataForSerach     =  $this->input->post("aPackDataForSerach");
            $tWhere                 =  json_encode($this->input->post('tWhere'));
        }

        $aDataSearch = array(
            'FNLngID'               => $this->session->userdata("tLangEdit"),
            'nPage'                 => @$nPage,
            'nRow'                  => @$nRow,
            'aPriceType'            => @$aPriceType,
            'tWAH'                  => $this->input->post("WAH"),
            'tBCH'                  => @$tBCH,
            'tMER'                  => @$tMER,
            'tSHP'                  => @$tSHP,
            'tSPL'                  => @$tSPL,
            'nDISTYPE'              => @$nDISTYPE,
            'tSelectTier'           => @$tSelectTier,
            'tPagename'             => @$tPagename,
            'aNotinItem'            => @$aNotinItem,
            'aPackDataForSerach'    => @$aPackDataForSerach,
            'tSearchText'           => @$tSearchText,
            'tSearchSelect'         => @$tSearchSelect,
            'nTotalResult'          => ($nTotalResult == '') ? '0' : $nTotalResult,
            'tFindOnlyPDT'          => $tFindOnlyPDT,
            'tProductCode'          => $tProductCode,
            'tAgenCode'             => $tAgenCode,
            'tNotInPdtType'         => $tNotInPdtType,
            'tWhere'                => $tWhere,
            'tTYPEPDT'              => $tTYPEPDT,
            'tSNPDT'                => $tSNPDT
        );
        // echo "<pre>";
        // print_r($aDataSearch);
        //ค้นหาสินค้า
        $aProduct = $this->JSaCGetDataProduct($aDataSearch);

        // GetAllRow
        if ($aProduct['nPDTAll'] == 0) {
            $tGetAllRow = $this->session->userdata("tSesGetAllRow");
        } else {
            $tGetAllRow = $aProduct['nPDTAll'];
        }

        $this->session->set_userdata("tSesGetAllRow", $tGetAllRow);

        //หาประเภทราคา ใช้ต้นทุนแบบไหน [1,2,3,4];
        if ($aPriceType[0] == 'Pricesell' || $aPriceType[0] == 'Price4Cst') {
            $aCheckPrice = '';
            $tVatInorEx  = '';
        } else if ($aPriceType[0] == 'Cost') {
            $aCheckPrice = $this->mBrowserPDTCallView->FSnMGetTypePrice($aPriceType[1], $aPriceType[2], $aPriceType[3]);
            if ($tSPL == '' || $tSPL == null) {
                //ไม่ส่ง spl มา
                $aGetInorEx    = $this->mBrowserPDTCallView->FSaMGetWhsInorExIncompany();
                $tVatInorEx    = $aGetInorEx[0]['FTCmpRetInOrEx'];
            } else {
                $aGetInorEx    = $this->mBrowserPDTCallView->FSaMGetWhsInorExInSupplier($tSPL);
                if (empty($aGetInorEx)) {
                    $tVatInorEx    = 1;
                } else {
                    $tVatInorEx    = $aGetInorEx[0]['FTSplStaVATInOrEx'];
                }
            }
        }

        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataHTML          = array(
            'nPage'             => $this->input->post("nPage"),
            'aPriceType'        => $aPriceType,
            'tSelectTier'       => @$tSelectTier,
            'nOptDecimalShow'   => $nOptDecimalShow,
            'aTSysconfig'       => $aCheckPrice,
            'tReturnType'       => $tReturnType,
            'tVatInorEx'        => $tVatInorEx,
            'aProduct'          => $aProduct
        );


        if ($tBarcode == '' || $tBarcode  == null) {
            $this->load->view('common/wBrowsePDTTableCallView', $aDataHTML);
        } else {
            if ($aProduct['rtCode'] == 800) {
                $aReturn = 800;
            } else {
                $tReturn    = $aProduct;

                $aData      = $tReturn['raItems'];
                $aReturn    = array();
                for ($i = 0; $i < FCNnHSizeOf($tReturn['raItems']); $i++) {
                    $aPackData = array(
                        'SHP'       => $aData[$i]['FTShpCode'],
                        'BCH'       => $aData[$i]['FTPdtSpcBch'],
                        'PDTCode'   => $aData[$i]['FTPdtCode'],
                        'PDTName'   => $aData[$i]['FTPdtName'],
                        'PUNCode'   => $aData[$i]['FTPunCode'],
                        'UnitFact'  => $aData[$i]['FCPdtUnitFact'],
                        'tTypePDT'  => $aData[$i]['FTPdtType'],
                        'Barcode'   => $aData[$i]['FTBarCode'],
                        'PUNName'   => $aData[$i]['FTPunName'],
                        'IMAGE'     => $aData[$i]['FTImgObj'],
                        'LOCSEQ'    => '',
                        'Remark'    => $aData[$i]['FTPdtName'],
                        'CookTime'  => ($aData[$i]['FCPdtCookTime'] == '') ? 0 : $aData[$i]['FCPdtCookTime'],
                        'CookHeat'  => ($aData[$i]['FCPdtCookHeat'] == '') ? 0 : $aData[$i]['FCPdtCookHeat'],
                        'AlwDis'    => ($aData[$i]['FTPdtStaAlwDis'] == '' || $aData[$i]['FTPdtStaAlwDis'] == null) ? 2 : $aData[$i]['FTPdtStaAlwDis'],
                        'AlwVat'    => $aData[$i]['FTPdtStaVat'],
                        'nVat'      => $aData[$i]['FCVatRate'],
                        'tVatCode'  => $aData[$i]['FTVatCode'],
                        'nVat'      => $aData[$i]['FCVatRate'],
                        'SetOrSN'   => $aData[$i]['FTPdtSetOrSN']
                    );

                    if ($aPriceType[0] == 'Pricesell' || $aPriceType[0] == 'Price4Cst') {
                        $aPackData['PriceRet'] = number_format($aData[$i]['FCPgdPriceRet'], $nOptDecimalShow, '.', ',');
                        $aPackData['PriceWhs'] = number_format($aData[$i]['FCPgdPriceWhs'], $nOptDecimalShow, '.', ',');
                        $aPackData['PriceNet'] = number_format($aData[$i]['FCPgdPriceNet'], $nOptDecimalShow, '.', ',');
                        $aPackData['NetAfHD']   = number_format($aData[$i]['FCPgdPriceRet'], $nOptDecimalShow, '.', ',');
                    }else if($aPriceType[0] == 'Cost'){

                        //หาว่าใช้ต้นทุนแบบไหน
                        if($aCheckPrice == null || $aCheckPrice == ''){
                            $bCheckPrice    = false;
                            $nPriceon       = 0;
                        }else{
                            $bCheckPrice    = true;
                            $nPriceon       = $aCheckPrice;
                        }
                        $aDataFind = array(
                            'nINDEX'             => $nPriceon,
                            'nVATSPL'            => $tVatInorEx,
                            'nCostAvgIn'         => $aData[$i]['FCPdtCostAVGIN'],
                            'nCostAvgEX'         => $aData[$i]['FCPdtCostAVGEx'],
                            'nCostLast'          => $aData[$i]['FCPdtCostLast'],
                            'nCostFiFoIn'        => $aData[$i]['FCPdtCostFIFOIN'],
                            'nCostFiFoEx'        => $aData[$i]['FCPdtCostFIFOEx'],
                            'nCostSTD'           => $aData[$i]['FCPdtCostStd']
                        );
                        $nCost = $this->GetTotalByConfig($aDataFind);
                        $nCost = $nCost * $aData[$i]['FCPdtUnitFact'];
                        $aPackData['Price'] = number_format($nCost, $nOptDecimalShow, '.', ',');
                        $aPackData['NetAfHD']   = number_format($nCost, $nOptDecimalShow, '.', ',');
                    }
                    array_push($aReturn, [
                        "pnPdtCode" => $aData[$i]['FTPdtCode'],
                        "ptBarCode" => $aData[$i]['FTBarCode'],
                        "ptPunCode" => $aData[$i]['FTPunCode'],
                        "packData"  => $aPackData
                    ]);
                }
            }
            echo json_encode($aReturn);
        }
    }

    //GET Product
    public function JSaCGetDataProduct($paData)
    {
        $tFilter            = '';
        $tBchSession        = $this->session->userdata("tSesUsrBchCodeDefault");
        $tShpSession        = $this->session->userdata("tSesUsrShpCode");
        $tMerSession        = $this->session->userdata("tSesUsrMerCode");
        $tSelectTier        = $paData['tSelectTier'];
        $aDataParamExe     = array();

        $tWhere = $paData['tWhere'];
        // echo "<pre>";
        // print_r($paData);
        // var_dump($tWhere);
        if ( isset($tWhere) && !empty($tWhere) && $tWhere != 'null' ) {
            $aItem =  json_decode($tWhere);
            foreach($aItem as $nKey => $tValue){
                $tFilter .= $tValue;
            }
        }

        //------------------- ไม่แสดงสินค้าตาม ประเภทสินค้า -------------------
        $tNotInPdtType = json_decode($paData['tNotInPdtType']);
        $tNotInPdtTypeParam = '';
        if ( isset($tNotInPdtType) && !empty($tNotInPdtType) && is_array($tNotInPdtType) && FCNnHSizeOf($tNotInPdtType) > 0 ) {
            $nMax               = FCNnHSizeOf($tNotInPdtType);
            $nCount             = 1;
            $tPdtTypeMulti      = "";
            foreach($tNotInPdtType as $nKey => $tValue){
                if( $nCount == $nMax ){
                    $tPdtTypeMulti .= "'$tValue'";
                }else{
                    $tPdtTypeMulti .= "'$tValue',";
                }
                $nCount++;
            }
            $tFilter .= " AND Products.FTPdtType NOT IN ($tPdtTypeMulti) ";
            $tNotInPdtTypeParam = $tPdtTypeMulti;
        }

        //-------------------สินค้าที่ไม่ใช่ตัวข้อมูลหลักในการจัดสินค้าชุด-------------------
        $tProductCode = $paData['tProductCode'];
        $tFilter .= " AND Products.FTPdtCode != '$tProductCode' ";
        $tPdtCodeIgnorParam = $tProductCode;

        //-------------------แสดงสินค้าตาม Agen-------------------
        $tAgenCode = $paData['tAgenCode'];
        if ($tAgenCode != '') {
            $tFilter .= " AND Products.FTAgnCode = '$tAgenCode' ";
        }

        //-------------------สินค้าเคลื่อนไหว-------------------
        // $nPDTMoveon = (isset($paData['aPackDataForSerach']['nPDTMoveon']) ? $paData['aPackDataForSerach']['nPDTMoveon'] : '');
        // if ($nPDTMoveon == 1) {
            $tFilter .= " AND Products.FTPdtStaActive = '1' ";
        // } else if ($nPDTMoveon == 2) {
        //     $tFilter .= " AND Products.FTPdtStaActive = '2' ";
        // }

        //------------------- หมวดหมู่สินค้า -------------------
        $tPDTCatLv1 = (isset($paData['aPackDataForSerach']['tSearchPDTCatLv1']) ? $paData['aPackDataForSerach']['tSearchPDTCatLv1'] : '');
        if ( $tPDTCatLv1 != "" ) {
            $tFilter .= " AND CATINFO.FTPdtCat1 = '".$tPDTCatLv1."' ";
        }
        $tPDTCatLv2 = (isset($paData['aPackDataForSerach']['tSearchPDTCatLv2']) ? $paData['aPackDataForSerach']['tSearchPDTCatLv2'] : '');
        if ( $tPDTCatLv2 != "" ) {
            $tFilter .= " AND CATINFO.FTPdtCat2 = '".$tPDTCatLv2."' ";
        }

        //-------------------ฟิลเตอร์การค้นหา-------------------
        // สินค้าคงคลัง
        $tStaControlStk = (isset($paData['aPackDataForSerach']['tStaControlStk']) ? $paData['aPackDataForSerach']['tStaControlStk'] : '');
        if( $tStaControlStk != "" && ($tStaControlStk == "1" || $tStaControlStk == "2") ){
            $tSearchSelect = 'TCNTPdtStkBal';
            switch ($tStaControlStk) {
                case '1':
                    $tFilter    .= " AND ISNULL(STK.FCStkQty,0) > 0 ";
                    break;
                case '2':
                    $tFilter    .= " AND ISNULL(STK.FCStkQty,0) <= 0 ";
                    break;
            }
        }else{
            $tSearchSelect = $paData['tSearchSelect'];
        }

        // if( isset($paData['aPackDataForSerach']['aControlStock']) && !empty($paData['aPackDataForSerach']['aControlStock']) ){
        //     $tSearchSelect = 'TCNTPdtStkBal';

        //     $aControlStock = json_decode($paData['aPackDataForSerach']['aControlStock'],TRUE);
        //     print_r($aControlStock);

        // }else{
        //     $tSearchSelect = $paData['tSearchSelect'];
        // }

        $tPlcCodeConParam           = '';
        if ($tSearchSelect == 'PDTANDBarcode') {
            $tSearchText     = trim($paData['tSearchText']);
            $tFilter        .= " AND ( Products.FTPdtCode = '$tSearchText' OR PBAR.FTBarCode = '$tSearchText' )";
        }else{

            //สาขา
            // $tSearchPDTBchCode          = $paData['aPackDataForSerach']['tSearchPDTBchCode'];
            // if($tSearchPDTBchCode != ''){
            //     $tSQLFindAGN    = "SELECT FTAgnCode FROM TCNMBranch WHERE FTBchCode IN ($tSearchPDTBchCode)";
            //     $oQueryFindAGN  = $this->db->query($tSQLFindAGN);
            //     $aListAGN       = $oQueryFindAGN->result_array();
            //     if(!empty($aListAGN)) {
            //         $tAGN       = $aListAGN[0]['FTAgnCode'];
            //         $tFilter    .= " AND (Products.FTAgnCode = '$tAGN') OR (ISNULL(FTAgnCode,'') = '')";
            //     }
            // }

            //กลุ่มสินค้า
            $tSearchPDTPGPFrmCode       = $paData['aPackDataForSerach']['tSearchPDTPGPFrmCode'];
            $tSearchPDTPGPToCode        = $paData['aPackDataForSerach']['tSearchPDTPGPToCode'];
            if($tSearchPDTPGPFrmCode != '' && $tSearchPDTPGPToCode != ''){
                $tFilter .= " AND (Products.FTPgpChain BETWEEN '$tSearchPDTPGPFrmCode' AND '$tSearchPDTPGPToCode' )";
            }

            //ประเภทสินค้า
            $tSearchPDTPTYFrmCode       = $paData['aPackDataForSerach']['tSearchPDTPTYFrmCode'];
            $tSearchPDTPTYToCode        = $paData['aPackDataForSerach']['tSearchPDTPTYToCode'];
            if($tSearchPDTPTYFrmCode != '' && $tSearchPDTPTYToCode != ''){
                $tFilter .= " AND (Products.FTPtyCode BETWEEN '$tSearchPDTPTYFrmCode' AND '$tSearchPDTPTYToCode' )";
            }

            //ผู้จำหน่าย
            $tSearchPDTSPLFrmCode       = $paData['aPackDataForSerach']['tSearchPDTSPLFrmCode'];
            $tSearchPDTSPLToCode        = $paData['aPackDataForSerach']['tSearchPDTSPLToCode'];
            if($tSearchPDTSPLFrmCode != '' && $tSearchPDTSPLToCode != ''){
                $tFilter .= " AND (PDTSPL.FTSplCode BETWEEN '$tSearchPDTSPLFrmCode' AND '$tSearchPDTSPLToCode' )";
            }

            //ประเภทสินค้า
            //echo $paData['aPackDataForSerach']['tSearchPDTType'];
            $tSearchPdtType = $paData['aPackDataForSerach']['tSearchPDTType'];
            if( $tSearchPdtType != '' ){
                $aSearchPdtType = explode(",",$tSearchPdtType);
                // print_r($aSearchPdtType);
                // $tProductType = " AND ( ";
                $tProductType = "";
                foreach($aSearchPdtType as $aValue){
                    $tProductType .= $this->FStSwitchProductType($aValue);
                }
                $tProductType = substr($tProductType,3);
                $tFilter .= " AND ( " . $tProductType . " ) ";
                // $tProductType .= " ) ";
            }

            // if( $tSearchPdtType != '' ){
            //     $tFilter .= $this->FStSwitchProductType($tSearchPdtType);
            // }

            //ที่เก็บ
            $tSearchPDTLOGSEQCode       = $paData['aPackDataForSerach']['tSearchPDTLOGSEQCode'];
            if ($tSearchPDTLOGSEQCode != '') {
                $aFoundPDT      = $this->mBrowserPDTCallView->FSnMFindPDTByBarcode($tSearchPDTLOGSEQCode, 'FINDPLCCODE');
                if(!empty($aFoundPDT)){
                    $tBarCodeINPlc  = '';
                    for($i=0; $i<count($aFoundPDT); $i++){
                        $tBarCodeINPlc .= "'".$aFoundPDT[$i]['FTBarCode']."'" . ',';
                    }
                    $tBarCodeINPlc = substr($tBarCodeINPlc,0,-1);
                    $tFilter    .= " AND (PBAR.FTBarCode IN ($tBarCodeINPlc))";
                    $tPlcCodeConParam = $tBarCodeINPlc;
                }else{
                    $tFilter    .= " AND (PBAR.FTBarCode = 'EMPTY')";
                    $tPlcCodeConParam = 'EMPTY';
                }
            }

            //ชื่อ
            $tSearchText = trim($paData['aPackDataForSerach']['tSearchText']);
            if($tSearchText != ''){
                $tFilter .= " AND ((PDTL.FTPdtName LIKE '%$tSearchText%')";
                $tFilter .= " OR (Products.FTPdtCode LIKE '%$tSearchText%'))";
                //$tFilter .= " OR (Products.FTPgpChain = '$tSearchText')";
                //$tFilter .= " OR (Products.FTBuyer = '$tSearchText')";
            }
        }

        //-------------------ประเภทสินค้า-------------------
        $tTYPEPDT = $paData['tTYPEPDT'];
        if ($tTYPEPDT != '') {
            $tSQLTYPEPDT    = '';
            $aTPYEPDT       = explode(",",$tTYPEPDT);
            if(count($aTPYEPDT) > 1){
                for($i=0; $i<count($aTPYEPDT); $i++){
                    $tSQLTYPEPDT .=  "'".$aTPYEPDT[$i]."'" . ',';
                }
                $tSQLTYPEPDT = substr($tSQLTYPEPDT,0,-1);
            }else{
                $tSQLTYPEPDT = "'".$tTYPEPDT."'";
            }
            $tFilter .= " AND Products.FTPdtType IN ($tSQLTYPEPDT) ";
        }


        // $tPlcCodeConParam = '';
        // if ($tSearchText != '' || $tSearchText != null) {

        //     if ($tSearchSelect == 'PDTANDBarcode') {
        //         $tFilter .= " AND (Products.FTPdtCode = '$tSearchText' OR Products.FTBarCode = '$tSearchText' )";
        //     }
        //     switch ($tSearchSelect) {
        //         case "FTPdtName":
        //             //ชื่อสินค้า
        //             $tFilter .= " AND (Products.FTPdtName LIKE '%$tSearchText%')";
        //             break;
        //         case "FTPdtCode":
        //             //รหัสสินค้า
        //             $tFilter .= " AND (Products.FTPdtCode LIKE '%$tSearchText%') ";
        //             break;
        //         case "FTBarCode":
        //             //รหัสบาร์โค้ด
        //             $tFilter .= " AND (Products.FTBarCode LIKE '%$tSearchText%')";
        //             break;
        //         case "FTPgpCode":
        //             //กลุ่มสินค้า
        //             $tFilter .= " AND (Products.FTPgpChain = '$tSearchText')";
        //             break;
        //         case "FTPtyCode":
        //             //ประเภทสินค้า
        //             $tFilter .= " AND (Products.FTPtyCode = '$tSearchText' )";
        //             break;
        //         case "FTBuyer":
        //             //ผู้จัดซื้อ
        //             $tFilter .= " AND (Products.FTBuyer = '$tSearchText')";
        //             break;
        //         case "FTPlcCode":
        //             //ที่เก็บ
        //             $tFoundPDT = $this->mBrowserPDTCallView->FSnMFindPDTByBarcode($tSearchText, 'FINDPLCCODE');
        //             if ($tFoundPDT == false || empty($tFoundPDT)) {
        //                 //กรณีที่เข้าไปหา plc code เเล้วไม่เจอ PDT เลย ต้องให้มันค้นหา โดย KEYWORD : EMPTY
        //                 $tFilter    .= " AND (Products.FTPdtCode = 'EMPTY' AND Products.FTPunCode = 'EMPTY')";
        //                 $tPlcCodeConParam = 'EMPTY';
        //             } else {
        //                 $tPDT       = $tFoundPDT[0]['FTPdtCode'];
        //                 $tPunCode   = $tFoundPDT[0]['FTPunCode'];
        //                 $tBarCode   = $tFoundPDT[0]['FTBarCode'];
        //                 $tFilter    .= " AND (Products.FTBarCode = '$tBarCode')";
        //                 $tPlcCodeConParam = $tBarCode;
        //             }
        //             break;
        //         case "ALL":
        //             $tFilter .= " AND (Products.FTPdtName LIKE '%$tSearchText%')";
        //             $tFilter .= " OR (Products.FTPdtCode LIKE '%$tSearchText%')";
        //             $tFilter .= " OR (Products.FTPgpChain = '$tSearchText')";
        //             $tFilter .= " OR (Products.FTBuyer = '$tSearchText')";
        //             break;
        //         default:
        //             $tFilter .= "";
        //     }
        // }

        //-------------------ระดับสินค้าชุด-------------------
        $tSNPDT = $paData['tSNPDT'];
        if ($tSNPDT != '') {
            $tSQLSNPDT    = '';
            $aSNPDT       = explode(",",$tSNPDT);
            if(count($aSNPDT) > 1){
                for($i=0; $i<count($aSNPDT); $i++){
                    $tSQLSNPDT .=  "'".$aSNPDT[$i]."'" . ',';
                }
                $tSQLSNPDT = substr($tSQLSNPDT,0,-1);
            }else{
                $tSQLSNPDT = "'".$tSNPDT."'";
            }
            $tFilter .= " AND Products.FTPdtSetOrSN IN ($tSQLSNPDT) ";
        }

        //-------------------อนุญาตลด-------------------
        if (!empty($paData['nDISTYPE'])) {
            $nDISTYPE = $paData['nDISTYPE'];
            $tFilter .= " AND (Products.FTPdtStaAlwDis = $nDISTYPE )";
        }

        //-------------------เงื่อนไขพิเศษ ตามหน้า-------------------
        $tPagename = $paData['tPagename'];
        if ($tPagename == 'PI') {
            $tFilter .= " AND (Products.FTPdtSetOrSN != 4)";
        }

        //-------------------ไม่เอาสินค้าอะไรบ้าง NOT IN-------------------
        $aNotinItem = $paData['aNotinItem'];
        $tNotinItemParam = '';
        if (!empty($aNotinItem)) {
            if ($aNotinItem != '' || $aNotinItem != null) {
                $tNotinItem     = '';
                $tNotinBarcode  = '';
                $aNewNotinItem  = explode(',', $aNotinItem);

                for ($i = 0; $i < FCNnHSizeOf($aNewNotinItem); $i++) {
                    $aNewPDT  = explode(':::', $aNewNotinItem[$i]);
                    if($aNewPDT[1] == 0){ //ไม่ได้ส่งบาร์โค๊ดมา
                        $tFilter            .= " AND (Products.FTPdtCode != '$aNewPDT[0]' )";
                        $tNotinItemParam    .= " AND (Products.FTPdtCode != '$aNewPDT[0]' )";
                    }else{
                        $tFilter            .= " AND (Products.FTPdtCode != '$aNewPDT[0]' OR PBAR.FTBarCode != '$aNewPDT[1]' )";
                        $tNotinItemParam    .= " AND (Products.FTPdtCode != '$aNewPDT[0]' OR PBAR.FTBarCode != '$aNewPDT[1]' )";
                    }
                }
            }
        }

        //-------------------เลือกราคาแบบไหน-------------------
        if ($paData['aPriceType'][0] == 'Pricesell') {
            $tLeftJoinPrice  = "";

            //ถ้าเหมือนกันให้ใช้ Price4PDT ถ้าไม่เหมือนกันให้ใช้ Price4BCH
            // $tLeftJoinPrice  = " LEFT JOIN VCN_Price4PdtActive VPP ON ";
            // $tLeftJoinPrice .= " VPP.FTPdtCode = ProductM.FTPdtCode AND ";
            // $tLeftJoinPrice .= " VPP.FTPunCode = ProductM.FTPunCode ";
        } else if ($paData['aPriceType'][0] == 'Price4Cst') {

            $tUserBchCode = $this->session->userdata('tSesUsrBchCodeDefault');
            $tCstPplCode  = $paData['aPriceType'][1];

            //--ราคาของ customer
            $tLeftJoin = "LEFT JOIN (
                            SELECT * FROM (
                            SELECT
                                ROW_NUMBER () OVER ( PARTITION BY FTPdtCode , FTPunCode ORDER BY FDPghDStart DESC) AS FNRowPart,
                                FTPdtCode ,
                                FTPunCode ,
                                FCPgdPriceRet
                                FROM TCNTPdtPrice4PDT WHERE
                            FDPghDStart <= CONVERT (VARCHAR(10), GETDATE(), 121)
                            AND FDPghDStop >= CONVERT (VARCHAR(10), GETDATE(), 121)
                            AND FTPghTStart <= CONVERT(time,GETDATE())
	                        AND FTPghTStop >= CONVERT(time,GETDATE())
                            AND FTPplCode = '$tCstPplCode'
                            ) AS PCUS
                            WHERE PCUS.FNRowPart = 1
                        ) PCUS ON ProductM.FTPdtCode = PCUS.FTPdtCode AND ProductM.FTPunCode = PCUS.FTPunCode ";

            // --ราคาของสาขา
            $tLeftJoin .= "LEFT JOIN (
                            SELECT * FROM (
                            SELECT
                                ROW_NUMBER () OVER ( PARTITION BY FTPdtCode , FTPunCode ORDER BY FDPghDStart DESC) AS FNRowPart,
                                FTPdtCode ,
                                FTPunCode ,
                                FCPgdPriceRet
                                FROM TCNTPdtPrice4PDT WHERE
                            FDPghDStart <= CONVERT (VARCHAR(10), GETDATE(), 121)
                            AND FDPghDStop >= CONVERT (VARCHAR(10), GETDATE(), 121)
                            AND FTPghTStart <= CONVERT(time,GETDATE())
	                        AND FTPghTStop >= CONVERT(time,GETDATE())
                            AND FTPplCode = (SELECT FTPplCode FROM TCNMBranch WHERE FTPplCode != '' AND FTBchCode = '$tUserBchCode')
                            ) AS PCUS
                            WHERE PCUS.FNRowPart = 1
                        ) PBCH ON ProductM.FTPdtCode = PBCH.FTPdtCode AND ProductM.FTPunCode = PBCH.FTPunCode ";

            // --ราคาที่ไม่กำหนด PPL
            $tLeftJoin .= "LEFT JOIN (
                            SELECT * FROM (
                            SELECT
                                ROW_NUMBER () OVER ( PARTITION BY FTPdtCode , FTPunCode ORDER BY FDPghDStart DESC) AS FNRowPart,
                                FTPdtCode ,
                                FTPunCode ,
                                FCPgdPriceRet
                                FROM TCNTPdtPrice4PDT WHERE
                            FDPghDStart <= CONVERT (VARCHAR(10), GETDATE(), 121)
                            AND FDPghDStop >= CONVERT (VARCHAR(10), GETDATE(), 121)
                            AND FTPghTStart <= CONVERT(time,GETDATE())
	                        AND FTPghTStop >= CONVERT(time,GETDATE())
                            AND ISNULL(FTPplCode,'') = ''
                            ) AS PCUS
                            WHERE PCUS.FNRowPart = 1
                        ) PEMPTY ON ProductM.FTPdtCode = PEMPTY.FTPdtCode AND ProductM.FTPunCode = PEMPTY.FTPunCode ";

            $tLeftJoinPrice  = " $tLeftJoin ";
        } else if ($paData['aPriceType'][0] == 'Cost') {
            //ถ้าอยากหาราคา
            $tLeftJoinPrice  = " LEFT JOIN VCN_ProductCost VPC ON ";
            $tLeftJoinPrice .= " VPC.FTPdtCode = ProductM.FTPdtCode ";
        }

        //-------------------ผู้จำหน่าย-------------------
        if ($paData['tSPL'] != '' || $paData['tSPL'] != null) {
            $tFilter .= " AND (PDTSPL.FTSplCode = '" . $paData['tSPL'] . "' OR ISNULL(PDTSPL.FTSplCode,'') = '')";
        }

        //-------------------สาขา-------------------
        if ($paData['tBCH'] != '' || $paData['tBCH'] != null) {
            $tBCH      = $paData['tBCH'];
        } else {
            $tBCH      = $tBchSession;
        }

        //-------------------กลุ่มธุรกิจ-------------------
        if ($paData['tMER'] != '' || $paData['tMER'] != null) {
            $tFilter .= " AND (Products.FTMerCode = '" . $paData['tMER'] . "')";
        }

        //-------------------ร้านค้า-------------------
        if ($paData['tSHP'] != '' || $paData['tSHP'] != null) {
            //$tFilter .= " AND (Products.FTShpCode = '".$paData['tSHP']."')";
        }

        //เงือนไขว่าวิ่งเข้า VIEW SQL ชุดไหน
        //+-------------+-------------+-------------+-------------+
        //+     BCH     +     MER     +     SHP     +    V_SQL    +
        //+-------------+-------------+-------------+-------------+
        //+     null    +     null    +     null    +     HQ      +
        //+-------------+-------------+-------------+-------------+
        //+      /      +     null    +     null    +     BCH     +
        //+-------------+-------------+-------------+-------------+
        //+      /      +     /       +     null    +     BCH     +
        //+-------------+-------------+-------------+-------------+
        //+      /      +     /       +      /      +     SHP     +
        //+-------------+-------------+-------------+-------------+
        //+      /      +     null    +      /      +     SHP     +
        //+-------------+-------------+-------------+-------------+
        //+     null    +     /       +      /      +     SHP     +
        //+-------------+-------------+-------------+-------------+
        //+     null    +     null    +      /      +     SHP     +
        //+-------------+-------------+-------------+-------------+
        //+     null    +     /       +     null    +     SHP     +
        //+-------------+-------------+-------------+-------------+

        $nTotalResult       = $paData['nTotalResult'];
        if ($paData['tBCH'] == '' && $paData['tMER'] == '' && $paData['tSHP'] == '') {
            // HQ
            $tPermission    = 'HQ';
        } else if ($paData['tBCH'] != '' && $paData['tMER'] == '' && $paData['tSHP'] == '') {
            // BCH
            $tPermission    = 'BCH';
        } else if ($paData['tBCH'] != '' && $paData['tMER'] != '' && $paData['tSHP'] == '') {
            // BCH
            $tPermission    = 'BCH';
        } else if ($paData['tBCH'] != '' && $paData['tMER'] != '' && $paData['tSHP'] != '') {
            // SHP
            $paData['tBCH'] = $tBCH;
            $tPermission    = 'SHP';
        } else if ($paData['tBCH'] == '' && $paData['tMER'] != '' && $paData['tSHP'] != '') {
            // SHP
            $paData['tBCH'] = $tBCH;
            $tPermission    = 'SHP';
        } else if ($paData['tBCH'] == '' && $paData['tMER'] == '' && $paData['tSHP'] != '') {
            // SHP
            $paData['tBCH'] = $tBCH;
            $tPermission    = 'SHP';
        } else if ($paData['tBCH'] == '' && $paData['tMER'] != '' && $paData['tSHP'] == '') {
            // SHP
            $paData['tBCH'] = $tBCH;
            $tPermission    = 'SHP';
        } else if ($paData['tBCH'] != '' && $paData['tMER'] == '' && $paData['tSHP'] != '') {
            // SHP
            $paData['tBCH'] = $tBCH;
            $tPermission    = 'SHP';
        } else {
            $tPermission    = 'ETC';
        }

        $tWhereParam = $tFilter;

        $aDataParamExe = array(
            'ptFilterBy' => $tSearchSelect,
            'ptSearch' => $tSearchText,
            'ptWhere' => $tWhereParam,
            'ptNotInPdtType' => $tNotInPdtTypeParam,
            'ptNotInPdtType' => $tNotInPdtTypeParam,
            'ptPdtCodeIgnorParam' => $tPdtCodeIgnorParam,
            'ptPDTMoveon' => '1',
            'ptPlcCodeConParam' => $tPlcCodeConParam ,
            'ptDISTYPE' => $paData['nDISTYPE'] ,
            'ptPagename' => $tPagename,
            'ptNotinItemString' => $tNotinItemParam,
            'ptSqlCode' => $paData['tSPL'],
            'ptPriceType' => @$paData['aPriceType'][0],
            'ptPplCode' => @$paData['aPriceType'][1],
        );
        // echo "<pre>";
        // print_r($aDataParamExe);
        // echo "</pre>";

        $aResultPDT = $this->mBrowserPDTCallView->FSaMGetProductBCH($tFilter, $tLeftJoinPrice, $paData, $nTotalResult,$aDataParamExe);

        // switch ($tPermission) {
        //     case "HQ":

        //         if($this->session->userdata("tSesUsrLevel")=='HQ'){
        //             $aResultPDT = $this->mBrowserPDTCallView->FSaMGetProductHQ($tFilter, $tLeftJoinPrice, $paData, $nTotalResult);
        //         }else{
        //             // กรณีไม่ใช้ HQ แต่เป็น AGN
        //             $aResultPDT = $this->mBrowserPDTCallView->FSaMGetProductBCH($tFilter, $tLeftJoinPrice, $paData, $nTotalResult,$aDataParamExe);
        //         }
        //         // print_r(  $aResultPDT );
        //         break;
        //     case "BCH":
        //         $aResultPDT = $this->mBrowserPDTCallView->FSaMGetProductBCH($tFilter, $tLeftJoinPrice, $paData, $nTotalResult,$aDataParamExe);
        //         //print_r(  $aResultPDT );
        //         break;
        //     case "SHP":
        //         // $aResultPDT = $this->mBrowserPDTCallView->FSaMGetProductSHP($tFilter, $tLeftJoinPrice, $paData, $nTotalResult);
        //         $aResultPDT = $this->mBrowserPDTCallView->FSaMGetProductBCH($tFilter, $tLeftJoinPrice, $paData, $nTotalResult,$aDataParamExe);
        //         break;
        //     default:
        //         $aResultPDT = ' E R R O R';
        // }

        return $aResultPDT;
    }


    //เก็บ config ไว้ว่าจะหากี่ตัว
    public function FSvCallViewModalPdtConfig(){
        $aData = array(
            'nMaxPage'   => $this->input->post('nCheckMaxPage'),
            'nPerPage'   => $this->input->post('nCheckPerPage')
        );

        $tPrefixCookie = "PDTCookie_";

        $nCookieName = $tPrefixCookie . $this->session->userdata("tSesUserCode");
        $tCookieValue   = json_encode($aData);

        $aCookie = array(
            'name'    => $nCookieName,
            'value'   => $tCookieValue,
            'expire'  => 31556926,
        );

        $this->input->set_cookie($aCookie);
    }

    //หาว่าใช้ราคาแบบไหน
    public function GetTotalByConfig($aData){

        $nINDEXConfig       = explode(',',$aData['nINDEX']);
        $nVATSPL            = $aData['nVATSPL'];
        $nCostAvgIn         = $aData['nCostAvgIn'];
        $nCostAvgEX         = $aData['nCostAvgEX'];
        $nCostLast          = $aData['nCostLast'];
        $nCostFiFoIn        = $aData['nCostFiFoIn'];
        $nCostFiFoEx        = $aData['nCostFiFoEx'];
        $nCostSTD           = $aData['nCostSTD'];
        $nResultCost        = "";

        for($i=0; $i<FCNnHSizeOf($nINDEXConfig); $i++){
            switch ($nINDEXConfig[$i]) {
                case 1: //ต้นทุนเฉลี่ย
                    if($nVATSPL == 1){
                        $nResultCost = $nCostAvgIn;
                    }else if($nVATSPL == 2){
                        $nResultCost = $nCostAvgEX;
                    }
                    $t = 'ต้นทุนเฉลี่ย';
                    break;
                case 2: //ต้นทุนสุดท้าย
                    $nResultCost = $nCostLast;
                    $t = 'ต้นทุนสุดท้าย';
                    break;
                case 3: //ต้นทุนมาตราฐาน
                    $nResultCost = $nCostSTD;
                    $t = 'ต้นทุนมาตราฐาน';
                    break;
                case 4: //ต้นทุน FiFo
                    if($nVATSPL == 1){
                        $nResultCost = $nCostFiFoIn;
                    }else if($nVATSPL == 2){
                        $nResultCost = $nCostFiFoEx;
                    }
                    $t = 'ต้นทุน FiFo';
                    break;
                default:
                    $nResultCost = 'EMPTY';
            }

            if($nResultCost == '' || $nResultCost == null){

            }else{
                break;
            }
        }
        return $nResultCost;
    }

    ///////////////////////////////////////////// สินค้าล็อต /////////////////////////////////////////////

    //โหลดหน้าจอ สำหรับสินค้าล็อต
    public function FSvCallViewProductLot(){

        $tEventType  = $this->input->post('tEventType');
        $aData       = json_decode($this->input->post('aData'));
        $aOption     = $this->input->post('oOptionForLot');
        $tDataRemove = '';

        $aPackdata  = array(
            'aDetailPDT'            => $aData,                              //ข้อมูลสินค้า
            'tPDTType'              => $this->input->post('tPDTType'),      //สินค้าเป็น LOT
            'nNumberArray'          => $this->input->post('nNumber'),       //จำนวนสินค้าตัวแรก เริ่มต้นเป็น 0
            'nAll'                  => count($aData),                       //จำนวนสินค้าที่เลือกมาทั้งหมดมีกี่ตัว
            'tNameNextFunc'         => $this->input->post('tNameNextFunc'), //ชื่อฟังก์ชั่นที่ส่งค่ากลับไป
            'oObjectResult'         => array(),                             //ข้อมูลรายละเอียดสินค้าแฟชั่น
            'tEventType'            => $tEventType,                         //รูปแบบของการเลือกข้อมูล insert (pageadd) หรือ update (pageupdate)
            'oOptionForLot'         => $aOption                             //การเพิ่มสินค้าแฟชั่นแบบ พิเศษ
        );

        $tPDTCode  = $aData[$this->input->post('nNumber')]->PDTCode;
        if($tEventType == 'insert'){ //ถ้าเป็นขาเพิ่มข้อมูลต้องเอาข้อมูลทั้งหมด
            $aDetailPDT         = array(
                'FTPdtName' => $aData[$this->input->post('nNumber')]->PDTName
            );
        }else if($tEventType == 'update'){ //ถ้าเป็นขาแก้ไขข้อมูลจะเอามาแค่ตัวเดียว
            $aDetailPDT         = '';
        }

        $aPackdata['oObjectResult'] = $aDetailPDT;
        $tHTML = $this->load->view('common/browselot/wBrowseLot', $aPackdata , true);

        //ส่งค่ากลับไปหา ajax
        $aReturn = array(
            'tHTML'         => $tHTML,
            'nAll'          => count($aData),
            'tPDTRemove'    => substr($tDataRemove,0,-1),
            'tNameNextFunc' => $this->input->post('tNameNextFunc')
        );
        echo json_encode($aReturn);
    }

    ///////////////////////////////////////////// สินค้าแฟชั่น + สินค้าซีเรียล /////////////////////////////////////////////

    //โหลดหน้าจอ สำหรับสินค้าซีเรียล และสินค้าแฟชั่น
    public function FSvCallViewProductSerialandFashion(){

        $tEventType  = $this->input->post('tEventType');
        $aData       = json_decode($this->input->post('aData'));
        $aOption     = $this->input->post('oOptionForFashion');
        $tDataRemove = '';

        //check ก่อนว่าสินค้าแฟช่ั่นที่ส่งมานั้นมีรายละเอียดจริงไหม (จะเช็คต่อเมื่อเป็นรูปแบบหน้าจอ insert และ เช็คสำหรับครั้งเเรกเท่านั้น)
        if($this->input->post('nNumber') == 0 && $tEventType == 'insert'){ //สำหรับรอบแรกเท่านั้น
            //จะส่งสินค้าทั้งหมดไปหา และจะส่ง json กลับมาเฉพาะตัวที่มี ตัวที่ไม่มีจะเก็บเป็นรูปแบบ string คั่นด้วย คอมม่า
            $aCheckDetailInPDTColorSize     = $this->mBrowserPDTCallView->FSaMCheckDetailInPDTColorSize($aData);
            $aResultCheck                   = json_decode($aCheckDetailInPDTColorSize);
            $aData                          = $aResultCheck->aItemCheck_have;   //array สินค้าที่เจอ
            $tDataRemove                    = $aResultCheck->aItemCheck_remove; //string สินค้าที่ไม่เจอ
        }

        $aPackdata  = array(
            'aDetailPDT'            => $aData,                              //ข้อมูลสินค้า
            'tPDTType'              => $this->input->post('tPDTType'),      //สินค้าเป็น แฟชั่น FN หรือ ซีเรียล SN
            'nNumberArray'          => $this->input->post('nNumber'),       //จำนวนสินค้าตัวแรก เริ่มต้นเป็น 0
            'nAll'                  => count($aData),                       //จำนวนสินค้าที่เลือกมาทั้งหมดมีกี่ตัว
            'tNameNextFunc'         => $this->input->post('tNameNextFunc'), //ชื่อฟังก์ชั่นที่ส่งค่ากลับไป
            'oObjectResult'         => array(),                             //ข้อมูลรายละเอียดสินค้าแฟชั่น
            'tEventType'            => $tEventType,                         //รูปแบบของการเลือกข้อมูล insert (pageadd) หรือ update (pageupdate)
            'oOptionForFashion'     => $aOption                             //การเพิ่มสินค้าแฟชั่นแบบ พิเศษ
        );

        //ถ้าสินค้าที่เลือกมา ไม่มีการกำหนดรายละเอียดเลย ก็ให้จบลูป exit ออก
        if(FCNnHSizeOf($aData) == 0){
            //ส่งค่ากลับไปหา ajax
            $aReturn = array(
                'tHTML'         => '',
                'nAll'          => count($aData),
                'tPDTRemove'    => substr($tDataRemove,0,-1),
                'tNameNextFunc' => $this->input->post('tNameNextFunc'),
                'tMSG'          => 'เป็นสินค้าแฟชั่นที่ไม่มีการกำหนดรายละเอียด'
            );
            echo json_encode($aReturn);
            exit;
        }

        if($this->input->post('tPDTType') == 'FH'){ //สินค้าแฟชั่น
            $tPDTCode  = $aData[$this->input->post('nNumber')]->PDTCode;
            $tPUNCode  = $aData[$this->input->post('nNumber')]->PUNCode;
            $tBarcode  = $aData[$this->input->post('nNumber')]->Barcode;
            $aDataPackGetDetail = array(
                'tPdtCode'  => $tPDTCode,
                'tPUNCode'  => $tPUNCode,
                'tBarcode'  => $tBarcode,
            );
            if($tEventType == 'insert'){ //ถ้าเป็นขาเพิ่มข้อมูลต้องเอาข้อมูลทั้งหมด
                $aDetailPDTFashion      = $this->mBrowserPDTCallView->FSaMGetDetailPDTFashion($aDataPackGetDetail);

                //ถ้าสินค้าที่เลือกมา มีคุณลักษณะเดียว
                if(FCNnHSizeOf($aDetailPDTFashion) == 1 && FCNnHSizeOf($aData) == 1){
                    $aFashion = array(
                                    array(
                                        'tPDTCode' => $tPDTCode,
                                        'tRefCode' => $aDetailPDTFashion[0]['FTFhnRefCode'],
                                        'nSeqItem' => $aDetailPDTFashion[0]['FNFhnSeq'] ,
                                        'tPunCode' => $tPUNCode,
                                        'tBarCode' => $tBarcode,
                                        'nSeqItem' => 1 ,
                                        'nQty' => 1
                                    )
                            );
                    //ส่งค่ากลับไปหา ajax
                    $aReturn = array(
                        'tStaOne'       => '1',
                        'nAll'          => 1,
                        'aFashion'    => $aFashion,
                        'tNameNextFunc' => $this->input->post('tNameNextFunc'),
                    );
                    echo json_encode($aReturn);
                    exit;
                }


                // echo $this->db->last_query();
            }else if($tEventType == 'update'){ //ถ้าเป็นขาแก้ไขข้อมูลจะเอามาแค่ตัวเดียว
                if(!empty($aData[$this->input->post('nNumber')]->PlcCode)){
                    $tDocumentPLcCode = $aData[$this->input->post('nNumber')]->PlcCode;
                }else{
                    $tDocumentPLcCode = '';
                }


                $aDataInDocumentTemp    = array(
                    'tDocumentnumber'   => $aData[0]->tDocno,
                    'tDocumentbranch'   => $aData[0]->BCH,
                    'tDocumentDockey'   => $aData[0]->tDocKey,
                    'nDTSeq'             => $aData[0]->nDTSeq,
                    'tDocumentPLcCode'   => $tDocumentPLcCode,
                    'oOptionForFashion'  => $aPackdata['oOptionForFashion'],
                    'tDocumentsession'  => $this->session->userdata("tSesSessionID")
                );
                $aDetailPDTFashion      = $this->mBrowserPDTCallView->FSaMGetDetailPDTSingleFashion($aDataPackGetDetail,$aDataInDocumentTemp);
            }

            $aPackdata['oObjectResult'] = $aDetailPDTFashion;
            $tHTML = $this->load->view('common/browseserialandfashion/wBrowsefashion', $aPackdata , true);
        }else if($this->input->post('tPDTType') == 'SN'){ //สินค้าซีเรียล

            //รอทำต่อ...
            $tHTML = $this->load->view('common/browseserialandfashion/wBrowseserial', $aPackdata , true);
        }


        //option ว่าจะแสดง popup ที่ละตัว หรือ เอาเฉพาะค่ากลับไป
        if($aOption['bListItemAll'] === 'false'){ //false : แสดงป็อปอัพที่ละตัว
            $tHTMLReturn = $tHTML;
            $aItemReturn = '';
        }else{ //true : เอาลิสต์สินค้าทั้งหมด
            $tHTMLReturn        = '';
            $aItemFahsionAll    = [];
            for($i=0; $i<count($aData); $i++){
                $tPDTCode          = $aData[$i]->PDTCode;
                $tPUNCode          = $aData[$i]->PUNCode;
                $tBarcode          = $aData[$i]->Barcode;
                $aDataPackGetDetail = array(
                    'tPdtCode'  => $tPDTCode,
                    'tPUNCode'  => $tPUNCode,
                    'tBarcode'  => $tBarcode,
                );
                $aDetailPDTFashion = $this->mBrowserPDTCallView->FSaMGetDetailPDTFashion($aDataPackGetDetail);
                array_push($aItemFahsionAll,$aDetailPDTFashion);
            }
            $aItemReturn = $aItemFahsionAll;
        }

        //ส่งค่ากลับไปหา ajax
        $aReturn = array(
            'tHTML'         => $tHTMLReturn,
            'aItemReturn'   => $aItemReturn,
            'nAll'          => count($aData),
            'tPDTRemove'    => substr($tDataRemove,0,-1),
            'tNameNextFunc' => $this->input->post('tNameNextFunc')
        );
        echo json_encode($aReturn);
    }

     ///////////////////////////////////////////// สินค้าเซต + สินค้าชุดบำรุง /////////////////////////////////////////////

    //โหลดหน้าจอ สินค้าเซต + สินค้าชุดบำรุง
    public function FSvCallViewProductSetOrSVSet(){
        $aOption     = $this->input->post('oOptionForPDTSet');
        $nDetailItem = $this->mBrowserPDTCallView->FSaMGetDetailPDTSetOrSVSet($aOption);

        $aReturn = array(
            'nStatus'=> $nDetailItem
        );
        echo json_encode($aReturn);
    }

    public function FStSwitchProductType($ptSearchPdtType){
        $tReturn = "";
        switch($ptSearchPdtType){
            case 'T1': //สินค้าทั่วไป
                $tReturn = " OR ( Products.FTPdtType = '1' AND Products.FTPdtSetOrSN = '1' ) ";
                break;
            case 'T2': //สินค้าบริการ
                $tReturn = " OR ( Products.FTPdtType = '2' AND Products.FTPdtSetOrSN = '1' ) ";
                break;
            case 'T3': //สินค้าอื่นๆ
                $tReturn = " OR ( Products.FTPdtType = '3' AND Products.FTPdtSetOrSN = '1' ) ";
                break;
            case 'T4': //ของแถม
                $tReturn = " OR ( Products.FTPdtType = '4' AND Products.FTPdtSetOrSN = '1' ) ";
                break;
            case 'T5': //พิเศษ
                $tReturn = " OR ( Products.FTPdtType = '5' AND Products.FTPdtSetOrSN = '1' ) ";
                break;
            case 'T6': //สินค้าประกอบการขาย
                $tReturn = " OR ( Products.FTPdtType = '6' AND Products.FTPdtSetOrSN = '1' ) ";
                break;
            case 'T7': //ค่าใช้จ่าย
                $tReturn = " OR ( Products.FTPdtType = '7' AND Products.FTPdtSetOrSN = '1' ) ";
                break;

            case 'S2': //สินค้าปกติชุด
                $tReturn = " OR ( Products.FTPdtType = '1' AND Products.FTPdtSetOrSN = '2' ) ";
                break;
            case 'S3': //สินค้าSerial
                $tReturn = " OR ( Products.FTPdtType = '1' AND Products.FTPdtSetOrSN = '3' ) ";
                break;
            case 'S4': //สินค้าSerial Set
                $tReturn = " OR ( Products.FTPdtType = '1' AND Products.FTPdtSetOrSN = '4' ) ";
                break;
            case 'S5': //สินค้าศูนย์บริการ
                $tReturn = " OR ( Products.FTPdtSetOrSN = '5' ) ";
                break;
        }
        return $tReturn;
    }
}
