<?php

//หาต้นทุนขาขาย (ลูกค้า)
//Note  : ใช้ View ในการหาต้นทุน -> VCN_ProductCost
//      : หาว่าใช้ต้นทุนแบบไหน หาจาก TSysConfig 
//      1   : ต้นทุนเฉลี่ย 
//          - FCPdtCostAVGIN 				                -> ลงฟิวส์ CostIn
//          - FCPdtCostAVGEX 				                -> ลงฟิวส์ CostEx
//      2   : ต้นทุนสุดท้าย
//          - (FCPdtCostLast * 7 / 100) + FCPdtCostLast   	-> ลงฟิวส์ CostIn
//          - FCPdtCostLast 		   		                -> ลงฟิวส์ CostEx
//      3   : ต้นทุนมาตรฐาน
//          - (FCPdtCostStd * 7 / 100) + FCPdtCostStd 	    -> ลงฟิวส์ CostIn
//          - FCPdtCostStd 		   		                    -> ลงฟิวส์ CostEx
//      4 :  ต้นทุน FIFO
//          - FCPdtCostFIFOIN				                -> ลงฟิวส์ CostIn
//          - FCPdtCostFIFOEX				                -> ลงฟิวส์ CostEx


function FCNaHGetCostInAndCostEx($pnParamSeq = 1 , $ptPDTCode = '' , $paDetailDocument){

    $ci = &get_instance();
    $ci->load->database();
    $ci->load->library('session');
    $tDataSessionID     = $ci->session->userdata("tSesSessionID");
    $tDocKey            = $paDetailDocument['tDataDocKey'];
    $tDocNo             = $paDetailDocument['tDataDocNo'];

    //SEQ : 1 , 2 , 3 , 4 , 5
    //1 : ต้นทุนสำหรับ การรับเข้า เบิกออก โอน
    //2 : ต้นทุนสำหรับ การสั่งซื้อ , ซื้อ , เพิ่มหนี้ (ผู้จำหน่าย)
    //3 : ต้นทุนสำหรับ การคืน , ลดหนี้(ผู้จำหน่าย)
    //4 : ต้นทุนสำหรับ การคืน , ลดหนี้(ลูกค้า) 
    //5 : ต้นทุนสำหรับ การสั่งขาย , ขาย , เพิ่มหนี้ , ใบเสนอราคา (ลูกค้า)

    $tSQL   = "SELECT FTSysStaUsrValue , FTSysStaDefValue FROM TSysConfig WHERE FTSysCode = 'tCN_Cost' AND FTSysSeq = '$pnParamSeq' ";
    $oQuery = $ci->db->query($tSQL);
    if($oQuery->num_rows() > 0){
        $aItem              = $oQuery->result_array();
        $tStaUsrValue       = $aItem[0]['FTSysStaUsrValue'];
        if($tStaUsrValue == '' || $tStaUsrValue == null){
            $tValueCost   = $aItem[0]['FTSysStaDefValue'];
        }else{
            $tValueCost   = $aItem[0]['FTSysStaUsrValue'];
        }
        $nINDEXConfig       = explode(',',$tValueCost); 

        // $tSQLItem           = "SELECT TOP 1 
        //                             FCPdtCostAVGIN,
        //                             FCPdtCostAVGEX,
        //                             FCPdtCostLast,
        //                             FCPdtCostStd,
        //                             FCPdtCostFIFOIN,
        //                             FCPdtCostFIFOEX 
        //                       FROM VCN_ProductCost COST WHERE COST.FTPdtCode = '$ptPDTCode' ";
        // $oQueryItem         = $ci->db->query($tSQLItem);
        // $aProductCost       = $oQueryItem->result_array();

        $tSQLItem = "UPDATE TMP SET 
                        TMP.FCXtdCostIn = CASE '$nINDEXConfig[0]' 
                                        WHEN 1 THEN COST.FCPdtCostAVGIN 
                                        WHEN 2 THEN (COST.FCPdtCostLast * 7 / 100) + COST.FCPdtCostLast 
                                        WHEN 3 THEN (COST.FCPdtCostStd * 7 / 100) + COST.FCPdtCostStd  
                                        WHEN 4 THEN COST.FCPdtCostFIFOIN 
                                    END , 
                        TMP.FCXtdCostEx = CASE '$nINDEXConfig[0]' 
                                        WHEN 1 THEN COST.FCPdtCostAVGEX 
                                        WHEN 2 THEN COST.FCPdtCostLast
                                        WHEN 3 THEN COST.FCPdtCostStd 
                                        WHEN 4 THEN COST.FCPdtCostFIFOEX 
                                    END
                    FROM TCNTDocDTTmp TMP
                    INNER JOIN VCN_ProductCost COST ON COST.FTPdtCode = TMP.FTPdtCode
                    WHERE TMP.FTXthDocKey = '$tDocKey' AND TMP.FTSessionID = '$tDataSessionID' AND TMP.FTXthDocNo = '$tDocNo' ";
        $oQueryItem = $ci->db->query($tSQLItem);
        return $oQueryItem;

        //     for($i=0; $i<FCNnHSizeOf($nINDEXConfig); $i++){
        //         if($nINDEXConfig[$i] == 1){ //ต้นทุนเฉลี่ย
        //             if($aProductCost[0]['FCPdtCostAVGIN'] == null || $aProductCost[0]['FCPdtCostAVGEX'] == null ){
        //                 continue;
        //             }else{
        //                 $nCostIN = $aProductCost[0]['FCPdtCostAVGIN'];
        //                 $nCostEX = $aProductCost[0]['FCPdtCostAVGEX'];
        //                 break;
        //             }
        //         }else if($nINDEXConfig[$i] == 2){ //ต้นทุนสุดท้าย
        //             if($aProductCost[0]['FCPdtCostLast'] == null ){
        //                 continue;
        //             }else{
        //                 $nCostIN = ($aProductCost[0]['FCPdtCostLast'] * 7 / 100) + $aProductCost[0]['FCPdtCostLast'];
        //                 $nCostEX = $aProductCost[0]['FCPdtCostLast'];
        //                 break;
        //             }
        //         }else if($nINDEXConfig[$i] == 3){ //ต้นทุนมาตราฐาน
        //             if($aProductCost[0]['FCPdtCostStd'] == null ){
        //                 continue;
        //             }else{
        //                 $nCostIN = ($aProductCost[0]['FCPdtCostStd'] * 7 / 100) + $aProductCost[0]['FCPdtCostStd'];
        //                 $nCostEX = $aProductCost[0]['FCPdtCostStd'];
        //                 break;
        //             }
        //         }else if($nINDEXConfig[$i] == 4){ //ต้นทุน FiFo
        //             if($aProductCost[0]['FCPdtCostFIFOIN'] == null || $aProductCost[0]['FCPdtCostFIFOEX'] == null){
        //                 continue;
        //             }else{
        //                 $nCostIN = $aProductCost[0]['FCPdtCostFIFOIN'];
        //                 $nCostEX = $aProductCost[0]['FCPdtCostFIFOEX'];
        //                 break;
        //             }
        //         }else{
        //             $nCostIN = 0;
        //             $nCostEX = 0;
        //         }
        //     }
    }
}
   