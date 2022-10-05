<?php
    // Functional : Encypt EJ Slip
    // Create By : Kitpipat 
    // Last Update By : Nattakit (เอาตรวจสอบตรวจแถวสุดท้ายออก เหตุผลเพราะทำให้รูปภาพแสดงขาดไปบางส่วนด้านท้าย)
    // Last Update On 8/05/2020
    // Create On : 10/10/2019
    // Parameter : EJ File Path
    // Parameter Type : String
    // Return : EJ Information 
    // Return Type : Array
    function FCNaHFLEDeCypther($ptEJFilePath){
        // $ptEJFilePath= 'http://202.44.55.96:80/AdaFileServer/API2CNFile/Adasoft/AdaFile/00267/EJ/210303091255951d70a3d036830.EJ';

        $aEJInfo = array();
        // ตรวจสอบ file path ว่ามีการส่งมาหรือไม่ ถ้าไม่ให้ return error กลับไป
        if($ptEJFilePath == '' or $ptEJFilePath == null){

           $aEJInfo['tStatus'] = 'Error'; 
           $aEJInfo['tErrorType'] = 'file path does not exist'; 
           $aEJInfo['tEJFile'] = null;

        }else{

            $aFile_headers = @get_headers($ptEJFilePath);
            if($aFile_headers[0] != 'HTTP/1.1 404 Not Found') { //ตรวจสอบว่า url มีจริงไหม
            // if(false!==file($ptEJFilePath)){
                $oData          = '';
                $aHeader        = array();
                $tCurlEx        = curl_init($ptEJFilePath);                 // เปิดการทำงาน + url 
                curl_setopt($tCurlEx, CURLOPT_CUSTOMREQUEST, 'GET');    // method
                curl_setopt($tCurlEx, CURLOPT_POSTFIELDS, $oData);      // data หรือ parameter ที่จะส่ง 
                curl_setopt($tCurlEx, CURLOPT_RETURNTRANSFER, true);    //  return ค่ากลับมาในรูป string
                curl_setopt($tCurlEx, CURLOPT_HTTPHEADER, $aHeader);    //  ค่าของ HEADER
                curl_setopt($tCurlEx, CURLOPT_TIMEOUT, 60);             //  กำหนดเวลาสูงสุดที่คำขอจะได้รับอนุญาตให้ใช้ curl
                curl_setopt($tCurlEx, CURLOPT_CONNECTTIMEOUT, 60);      //  กำหนดเวลาสูงสุดที่คำขอจะได้รับอนุญาตให้เชื่อมต่อ
                curl_setopt($tCurlEx, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($tCurlEx, CURLOPT_SSL_VERIFYPEER , FALSE);
                $oResult        = curl_exec($tCurlEx); //  สั่งให้ curl ทำงาน
                $aExplodeFILE   = explode("\n",$oResult);
                $tImageBinary   = str_replace($aExplodeFILE[0],"",$oResult);
                $aEJInfo['tStatus']     = 'Success'; 
                $aEJInfo['tErrorType']  = null; 
                $aEJInfo['tEJFile']     = trim($tImageBinary);
                curl_close($tCurlEx);

                // แบบเดิม
                // $oHandleFile = file($ptEJFilePath);
                // $tImageBinary = '';
                // foreach($oHandleFile as $nLine => $tContenLine){
                //     if($nLine==0){
                //         continue;
                //     }
                //     $tImageBinary.=$tContenLine;
                // }
                // // print_r(($tImageBinary));
                // // print_r(base64_encode($tImageBinary));

                // $aEJInfo['tStatus'] = 'Success'; 
                // $aEJInfo['tErrorType'] = null; 
                // $aEJInfo['tEJFile'] = $tImageBinary;

            }else {
                $aEJInfo['tStatus'] = 'Error'; 
                $aEJInfo['tErrorType'] = 'file path does not exist'; 
                $aEJInfo['tEJFile'] = 'Null';
            }
            return $aEJInfo;
        }
    }

    // Functional : Initail Encypt EJ Slip
    // Create By : Kitpipat 
    // Last Update By :
    // Last Update On 
    // Create On : 10/10/2019
    // Parameter : EJ File Path, Image content Type to retrun
    // Parameter Type : String
    // Return : EJ Information 
    // Return Type : Array
    function FCNaHFLEGetEJ($ptEJFilePath,$ptImageType){

        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        
             $aEJInfo = array();
             $aEJInfo = FCNaHFLEDeCypther($ptEJFilePath);
             if($aEJInfo['tStatus'] == 'Success' and $aEJInfo['tEJFile'] != ''){
                
                if($ptImageType == '' or $ptImageType == null){
                    $ptImageType = 'png';
                }else{
                    $ptImageType = $ptImageType;
                }
                $tDataImage = 'data:image/'.$ptImageType.';base64,';

                // Convert Binary to Base64 Format
                $tEJImage=$tDataImage.base64_encode($aEJInfo['tEJFile']);
               
                $aEJInfo['tStatus'] = 'Success'; 
                $aEJInfo['tErrorType'] = null; 
                $aEJInfo['tEJFile'] = $tEJImage;


             }else{
                 return $aEJInfo;
             }
             return $aEJInfo;
    }
?>

