<?php

// Functionality : Function Clear File Non Active On File Server
// Parameters : Ajax and Function Parameter
// Creator : 16/06/2021 Nattakit
// LastUpdate: -
// Return : Object Clear File Non Active On File Server
// Return Type : object
function FCNaHUPFClearFileNonActiveOnFileServer($paParam){

    $ci = &get_instance();
    $ci->load->model('common/UploadFile_Model');

    $oItemsApi = FCNaHUPFCallAPIToGetFiles($paParam);
    $aItemsDB = $ci->UploadFile_Model->FCNaMUPFCheckFile($paParam);
    // print_r($aItemsDB);
    // print_r($oItemsApi);
    if($oItemsApi['rtCode']=='001'){
      
            foreach($oItemsApi['raItems'] as $aPathFile){
                $nStaFileLose = 0;
                if($aItemsDB['rtCode']=='1'){
                        foreach($aItemsDB['raItems'] as $aDataDb){
                                if($aPathFile == $aDataDb['FTFleObj']){
                                    $nStaFileLose = 1;
                                }
                        }
                }
            
                if($nStaFileLose==0){
                 
                  FCNaMUPFCallAPIToDelete($aPathFile);
                
                }
            }
    }

}

// Functionality : Function Get File On File Server
// Parameters : Ajax and Function Parameter
// Creator : 16/06/2021 Nattakit
// LastUpdate: -
// Return : Object Get File On File Server
// Return Type : object
function FCNaHUPFCallAPIToGetFiles($paParam){
    $ci = &get_instance();
    $ci->load->model('common/UploadFile_Model');

    $tBchCode   = $paParam['tBchCode'];
    $tDocKey   = $paParam['tDocKey'];
    $tDocNo   = $paParam['tDocNo'];
    $tUrlAddr   = $ci->UploadFile_Model->FCNaMUPFGetObjectUrl();
    $tUrlApi    = $tUrlAddr.'/File/GetFiles?ptKey=AdaFile&ptRef1=branch_'.$tBchCode.'&ptRef2=saleorder';
    $aAPIKey    = array(
        'tKey'      => 'X-Api-Key',
        'tValue'    => '12345678-1111-1111-1111-123456789410'
    );

    $aParam     = array(
        'ptKey'		=>  $tDocKey,
        'ptRef1'    => 'branch_'.$tBchCode,
        'ptRef2'    => $tDocNo,
    );

    $oReuslt = FCNaHCallAPIBasic($tUrlApi,'GET',$aParam,$aAPIKey);
   return $oReuslt;
}

// Functionality : Function Delete File Server
// Parameters : Ajax and Function Parameter
// Creator : 16/06/2021 Nattakit
// LastUpdate: -
// Return : Object Delete File Server
// Return Type : object
function FCNaMUPFCallAPIToDelete($tFullpath){

    $ci = &get_instance();
    $ci->load->model('common/UploadFile_Model');

    $tUrlAddr   = $ci->UploadFile_Model->FCNaMUPFGetObjectUrl();
    $tUrlApi    = $tUrlAddr.'/File/Delete';
    $aAPIKey    = array(
        'tKey'      => 'X-Api-Key',
        'tValue'    => '12345678-1111-1111-1111-123456789410'
    );

    $aParam     = array(
        'ptUrl'		=> $tFullpath,
    );

    $oReuslt = FCNaHCallAPIBasic($tUrlApi,'POST',$aParam,$aAPIKey);

}


// Functionality : Function Delete File Server And File In DB 
// Parameters : Ajax and Function Parameter
// Creator : 16/06/2021 Nattakit
// LastUpdate: -
// Return : Object Delete File Server And File In DB 
// Return Type : object
function FCNaUPFDelDocFileEvent($paParam){
    $ci = &get_instance();
    $ci->load->database();

    $tBchCode   = $paParam['tBchCode'];
    $tDocKey   = $paParam['tDocKey'];
    $tDocNo   = $paParam['tDocNo'];

    $tSQL ="SELECT
                OJBFLE.FTFleObj
            FROM
                TCNMFleObj OJBFLE
            WHERE
                1 = 1
            AND OJBFLE.FTFleRefTable = '$tDocKey'
            AND OJBFLE.FTFleRefID1 = '$tDocNo'
            AND OJBFLE.FTFleRefID2 = '$tBchCode'
            ";

             $oQuery = $ci->db->query($tSQL);
             if($oQuery->num_rows() > 0){
                $aDataFile = $oQuery->result_array();

                foreach($aDataFile as $aData){
                    FCNaMUPFCallAPIToDelete($aData['FTFleObj']);
                }
             }  

        $ci->db->where('FTFleRefTable',$tDocKey);
        $ci->db->where('FTFleRefID1',$tDocNo);
        $ci->db->where('FTFleRefID2',$tBchCode);
        $ci->db->delete('TCNMFleObj');

}


