<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class News_model extends CI_Model {

    //Functionality : list News
    //Parameters : function parameters
    //Creator :  20/09/2018 Witsarut(Bell)
    //Return : data
    //Return Type : Array
    public function FSaMNEWList($paData){
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID         = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $tAgnCode   = $this->session->userdata("tSesUsrAgnCode");
            $tSesUsrLevel           = $this->session->userdata('tSesUsrLevel');
            $tSessionBchCode        = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       = "SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY rtCreateOn DESC, rtNewCode DESC) AS rtRowID,* FROM
                    (SELECT DISTINCT
                            New.FTNewCode   AS rtNewCode,
                            New.FDCreateOn  AS rtCreateOn,
                            New_L.FTNewDesc1 AS rtNewName,
                            New_L.FTNewDesc2  AS rtNewRmk,
                            UsrL.FTUsrName AS rtUsrName,
                            BchL.FTBchName AS rtBchName
                    FROM [TCNMNews] New WITH(NOLOCK)
                    LEFT JOIN [TCNMNews_L]  New_L ON New.FTNewCode = New_L.FTNewCode AND New_L.FNLngID = $nLngID
                    LEFT JOIN [TCNMUser_L] UsrL ON New.FTCreateBy = UsrL.FTUsrCode AND UsrL.FNLngID = $nLngID
                    LEFT JOIN [TCNMBranch_L] BchL ON New.FTBchCode = BchL.FTBchCode AND BchL.FNLngID = $nLngID
                    WHERE 1=1 ";

                if($tSesUsrLevel != 'HQ'){
                    $tSQL .= "AND ( New.FTBchCode IN ($tSessionBchCode) )";
                }

                if(isset($tSearchList) && !empty($tSearchList)){
                    $tSQL .= " AND (New.FTNewCode COLLATE THAI_BIN LIKE '%$tSearchList%'";
                    $tSQL .= " OR New_L.FTNewDesc1  COLLATE THAI_BIN LIKE '%$tSearchList%')";
                }
                $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";

                $oQuery = $this->db->query($tSQL);

                    if($oQuery->num_rows() > 0){
                        $aList = $oQuery->result_array();
                        $oFoundRow = $this->FSoNewGetPageAll($tSearchList,$nLngID);
                        $nFoundRow = $oFoundRow[0]->counts;
                        $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                        $aResult = array(
                            'raItems'       => $aList,
                            'rnAllRow'      => $nFoundRow,
                            'rnCurrentPage' => $paData['nPage'],
                            'rnAllPage'     => $nPageAll,
                            'rtCode'        => '1',
                            'rtDesc'        => 'success'
                        );
                    }else{
                        //No Data
                        $aResult = array(
                            'rnAllRow' => 0,
                            'rnCurrentPage' => $paData['nPage'],
                            "rnAllPage"=> 0,
                            'rtCode' => '800',
                            'rtDesc' => 'data not found'
                        );
                    }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }

    }

    //Functionality : Get Data News By ID
    //Parameters : function parameters
    //Creator : 20/09/2018 Witsarut(Bell)
    //Return : data
    //Return Type : Array
    public function FSaMNEWGetDataByID($paData){
        try{

            $tNewCode    = $paData['tNewCode'];
            $nLngID      = $paData['nLangEdit'];

            $tSQL = "SELECT
                New.FTNewCode   AS rtNewCode,
                NewL.FTNewDesc1  AS rtNewName,
                NewL.FTNewDesc2   AS rtNewRmk,
                New.FTNewRefUrl     AS rtNewRefUrl,
                New.FNNewToType     AS rnNewToType,
                UsrL.FTUsrCode    AS rtUsrCode,
                UsrL.FTUsrName    AS rtUsrName,
                New.FTBchCode    AS rtBchCode,
                BchL.FTBchName AS rtBchName,
                New.FDCreateOn    AS rdCreateOn
            FROM [TCNMNews] New
            LEFT JOIN [TCNMNews_L] NewL ON New.FTNewCode = NewL.FTNewCode AND NewL.FNLngID = $nLngID
            LEFT JOIN [TCNMUser_L] UsrL ON New.FTCreateBy = UsrL.FTUsrCode AND UsrL.FNLngID = $nLngID
            LEFT JOIN [TCNMBranch_L] BchL ON New.FTBchCode = BchL.FTBchCode AND UsrL.FNLngID = $nLngID
            WHERE 1=1 ";
            $tSQL .= " AND New.FTNewCode = '$tNewCode' ";
            
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aDetail = $oQuery->row_array();
                $aResult = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Data not found.',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSaMNEWGetStmSeq($ptData)
    {
      try{
              $tSQL = "SELECT *
              FROM [TFNMNEWInstallment]  WHERE FTNewCode='$ptData' ORDER BY FNStmSeq DESC";
              $oQuery = $this->db->query($tSQL);
              if($oQuery->num_rows() > 0){
                  $aDetail = $oQuery->row_array();
                  $aResult = array(
                      'raItems'   => $aDetail,
                      'rtCode'    => '1',
                      'rtDesc'    => 'success',
                  );
              }else{
                  $aResult = array(
                      'rtCode' => '800',
                      'rtDesc' => 'Data not found.',
                  );
              }
              return $aResult;
      }catch(Exception $Error){
          echo $Error;
      }
    }
    public function FSaMNEWGetDataEditInfo2ByID($ptData)
    {
      try{
              $nStmSeq = $ptData['nStmSeq'];
              $tNewCode = $ptData['tNewCode'];
              $tSQL = "SELECT *
              FROM [TFNMNEWInstallment]  WHERE FTNewCode='$tNewCode' AND FNStmSeq='$nStmSeq' ORDER BY FNStmSeq DESC";
              $oQuery = $this->db->query($tSQL);
              if($oQuery->num_rows() > 0){
                  $aDetail = $oQuery->row_array();
                  $aResult = array(
                      'raItems'   => $aDetail,
                      'rtCode'    => '1',
                      'rtDesc'    => 'success',
                  );
              }else{
                  $aResult = array(
                      'rtCode' => '800',
                      'rtDesc' => 'Data not found.',
                  );
              }
              return $aResult;
      }catch(Exception $Error){
          echo $Error;
      }
    }
    public function FSaMNEWGetDataInfo2ByID($paData){
        try{

                $tNewCode    =  $paData['tNewCode'];
                $nLngID      = $paData['nLangEdit'];
                $tSQL = "SELECT *
                FROM [TFNMNEWInstallment]  WHERE FTNewCode='$tNewCode'";
                $oQuery = $this->db->query($tSQL);
                if($oQuery->num_rows() > 0){
                    $aDetail = $oQuery->result_array();
                    $aResult = array(
                        'raItems'   => $aDetail,
                        'rtCode'    => '1',
                        'rtDesc'    => 'success',
                    );
                }else{
                    $aResult = array(
                        'rtCode' => '800',
                        'rtDesc' => 'Data not found.',
                    );
                }
                return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : All Page Of News
    //Parameters : function parameters
    //Creator :  20/09/2018 Witsarut (Bell)
    //Return : object Count All News
    //Return Type : Object
    public function FSoNewGetPageAll($ptSearchList,$ptLngID){
        $tAgnCode   = $this->session->userdata("tSesUsrAgnCode");
        try{
            $tSQL = "SELECT COUNT (New.FTNewCode) AS counts
                     FROM [TCNMNews] New
                     LEFT JOIN [TCNMNews_L]  New_L ON New.FTNewCode = New_L.FTNewCode AND New_L.FNLngID = $ptLngID
                     WHERE 1=1 ";
            if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL .= " AND (New.FTNewCode COLLATE THAI_BIN LIKE '%$ptSearchList%'";
                $tSQL .= " OR New_L.FTNewDesc1  COLLATE THAI_BIN LIKE '%$ptSearchList%')";
            }
            if($tAgnCode != ''){
                $tSQL .= "AND ( New.FTAgnCode = '$tAgnCode' OR ISNULL(New.FTAgnCode,'') = '' )";
            }
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Checkduplicate
    //Parameters : function parameters
    //Creator : 15/05/2018 wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMNEWCheckDuplicate($ptNewCode){

        $tSQL = "SELECT COUNT(FTNewCode)   AS counts
                 FROM TCNMNews
                 WHERE FTNewCode = '$ptNewCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //Functionality : Checkduplicate
    //Parameters : function parameters
    //Creator : 04/06/2021 mos
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMNEWInfo2CheckDuplicate($ptNewCode,$ptStmName,$pnStmSeq){
        $tSQL = "SELECT COUNT(FTNewCode)   AS counts
                 FROM TFNMNEWInstallment
                 WHERE FTNewCode = '$ptNewCode' AND FTStmName='$ptStmName' AND FNStmSeq !='$pnStmSeq'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //Functionality : Update News
    //Parameters : function parameters
    //Creator : 02/07/2018 Witsarut
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMNEWAddUpdateMaster($paData){
        try{
            $this->db->set('FTNewRefUrl', $paData['FTNewRefUrl']);
            $this->db->set('FNNewToType', $paData['FNNewToType']);
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTNewCode', $paData['FTNewCode']);
            $this->db->update('TCNMNews');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                $this->db->insert('TCNMNews',array(
                    'FTNewCode'   => $paData['FTNewCode'],
                    'FDLastUpdOn' => $paData['FDLastUpdOn'],
                    'FTLastUpdBy' => $paData['FTLastUpdBy'],
                    'FDCreateOn'  => $paData['FDCreateOn'],
                    'FTCreateBy'  => $paData['FTCreateBy'],
                    'FTBchCode'   => $paData['FTBchCode'],
                    'FNNewToType' => $paData['FNNewToType'],
                    'FTNewRefUrl' => $paData['FTNewRefUrl']
                ));

                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSaMNEWInfo2AddUpdateMaster($paData){
        try{
            $this->db->set('FTStmName', $paData['FTStmName']);
            $this->db->set('FCStmLimit', $paData['FCStmLimit']);
            $this->db->set('FCStmQty', $paData['FCStmQty']);
            $this->db->set('FTStmStaUnit', $paData['FTStmStaUnit']);
            $this->db->set('FCStmRate', $paData['FCStmRate']);
            $this->db->where('FTNewCode', $paData['FTNewCode']);
            $this->db->where('FNStmSeq', $paData['FNStmSeq']);
            $this->db->update('TFNMNEWInstallment');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                $this->db->insert('TFNMNEWInstallment',array(
                    'FTNewCode'         => $paData['FTNewCode'],
                    'FNStmSeq'          => $paData['FNStmSeq'],
                    'FTStmName'         => $paData['FTStmName'],
                    'FCStmLimit'        => $paData['FCStmLimit'],
                    'FCStmQty'          => $paData['FCStmQty'],
                    'FTStmStaUnit'      => $paData['FTStmStaUnit'],
                    'FCStmRate'         => $paData['FCStmRate'],
                ));

                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Update Lang News
    //Parameters : function parameters
    //Creator : 02/07/2018 Witsarut(Bel)
    //Last Modified : -
    //Return : response
    //Return Type : num
    public function FSaMNEWAddUpdateLang($paData){

        try{
            $this->db->set('FTNewCode', $paData['FTNewCode']);
            $this->db->set('FTNewDesc1', $paData['FTNewDesc1']);
            $this->db->set('FTNewDesc2', $paData['FTNewDesc2']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTNewCode', $paData['FTNewCode']);
            $this->db->update('TCNMNews_L');
            if($this->db->affected_rows() > 0 ){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                $this->db->insert('TCNMNews_L',array(
                    'FTNewCode' => $paData['FTNewCode'],
                    'FNLngID'   => $paData['FNLngID'],
                    'FTNewDesc1' => $paData['FTNewDesc1'],
                    'FTNewDesc2' => $paData['FTNewDesc2'],
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Delete News
    //Parameters : function parameters
    //Creator : 20/09/2018 Witsarut(Bell)
    //Return : Status Delete
    //Return Type : array
    public function FSaMNEWDelAll($paData){
        try{
            $this->db->trans_begin();
            $this->db->where_in('FTNewCode', $paData['FTNewCode']);
            $this->db->delete('TCNMNews');

            $this->db->where_in('FTNewCode', $paData['FTNewCode']);
            $this->db->delete('TCNMNews_L');

            $this->db->where_in('FTNewCode', $paData['FTNewCode']);
            $this->db->delete('TCNMNewsBch');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            echo $Error;
        }
    }


    public function FSaMNEWDelAllInfo2($paData){
        try{
            $this->db->trans_begin();
            $this->db->where('FNStmSeq', $paData['FNStmSeq']);
            $this->db->where('FTNewCode', $paData['FTNewCode']);
            $this->db->delete('TFNMNEWInstallment');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Delete News
    //Parameters : Ajax jReason()
    //Creator : 21/09/2018 Witsarut(Bell)
    //Return : Status Delete Event
    //Return Type : String
    public function FSnMNEWGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMNews";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;

    }

    // Edit image
    public function FSaMNEWAddImgObj($paData){
        $this->db->set('FTImgRefID', $paData['FTNewCode']);
        $this->db->where('FTImgRefID', $paData['tNewCodeOld']);
        $this->db->update('TCNMImgObj');
    }

    //อัพเดทแม่ ต้องไปอัพเดทตารางลูกด้วย (เงื่อนไขการผ่อน)
    public function FSaMNEWUpdateInstallment($paData){
        $tCodeold = $paData['tNewCodeOld'];
        $tCodenew = $paData['FTNewCode'];

        $this->db->set('FTNewCode', $tCodenew);
        $this->db->where('FTNewCode', $tCodeold);
        $this->db->update('TFNMNEWInstallment');
    }

    //Functionality : Search TCNTUsrBch
    //Parameters : function parameters
    //Creator : 17/11/2021 Nattakit
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMNEWAddUpdateBch($paData,$paDataMaster){
    try{
        $this->db->where('FTNewCode',$paDataMaster['FTNewCode'])->delete('TCNMNewsBch');
        if(!empty($paData)){
        $this->db->insert_batch('TCNMNewsBch',$paData);
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
                    );
                }
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Functionality : Search TCNTUsrBch
    //Parameters : function parameters
    //Creator : 17/11/2021 Nattakit
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMNewBchByID($paData,$pnType){

        $tNewCode   = $paData['tNewCode'];
        $nLngID     = $paData['nLangEdit'];

        $tSQL = "   SELECT  DISTINCT
                        NewBch.FTNewBchTo ,
                        BCHL.FTBchName,
                        ISNULL(NewBch.FTNewAgnTo,'')     AS FTNewAgnTo,
                        ISNULL(AGNL.FTAgnName,'')       AS FTAgnName
                    FROM [TCNMNewsBch] NewBch WITH(NOLOCK)
                    LEFT JOIN [TCNMBranch_L] BCHL WITH(NOLOCK) ON NewBch.FTNewBchTo = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                    LEFT JOIN [TCNMAgency_L] AGNL WITH(NOLOCK) ON NewBch.FTNewAgnTo = AGNL.FTAgnCode AND AGNL.FNLngID = $nLngID
                    WHERE NewBch.FTNewCode = '$tNewCode'
                    AND NewBch.FTNewStaType = '$pnType'
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
  
        return $aResult;
    }

    //Functionality : Search TCNTUsrBch
    //Parameters : function parameters
    //Creator : 17/11/2021 Nattakit
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMNewGetFile($paData){

        $tNewCode   = $paData['tNewCode'];
        $nLngID     = $paData['nLangEdit'];

        $tSQL = "   SELECT
                        FLE.FTFleName,
                        FLE.FTFleObj
                        FROM
                            TCNMFleObj FLE
                        WHERE FLE.FTFleRefTable = 'TCNMNews'
                        AND FLE.FTFleRefID1 = '$tNewCode'
                    ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
  
        return $aResult;
    }

    
}
