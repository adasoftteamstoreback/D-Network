<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitor_model extends CI_Model
{



    public function FSoMMONGetDataOption($paData)
    {

      $tSesUsrAgnCode = $this->session->userdata('tSesUsrAgnCode');
      $nLngID = $paData['FNLngID'];
      try{
          $tSQL       = " SELECT
                              ODL.FTOdlCode ,
                              ODL.FTOdlType ,
                              ODL.FNOdlMin ,
                              ODL.FNOdlMax ,
                              ODL.FTAgnCode   AS tAgnCode,
                              AGNL.FTAgnName  AS tAgnName
                          FROM TCNMOverDueLev ODL
                          LEFT JOIN TCNMAgency_L AGNL ON  ODL.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID  = $nLngID
                          WHERE  ODL.FTAgnCode = '$tSesUsrAgnCode' ORDER BY ODL.FTOdlCode DESC";
          $oQuery = $this->db->query($tSQL);
          if ($oQuery->num_rows() > 0){
              $aDetail = $oQuery->result_array();
              $aResult = array(
                  'aItems'   => $aDetail,
                  'tCode'    => '1',
                  'tDesc'    => 'success',
              );
          }else{
              $aResult = array(
                  'tCode' => '800',
                  'tDesc' => 'Data not found.',
              );
          }
          return $aResult;
      }catch(Exception $Error){
          echo $Error;
      }
    }

    /**
     * Functionality : List class
     * Parameters :  $paData is data for select filter
     * Creator : 26/04/2021 Worakorn
     * Last Modified : -
     * Return : data
     * Return Type : array
     */
    public function FSoMMONGetData($paData)
    {
        try {
          $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
          $tSesUsrAgnCode = $this->session->userdata('tSesUsrAgnCode');
          $tSearchList = $paData['tSearchAll'];
          $nLngID = $paData['FNLngID'];
          //----Condition
          $tCondition = $paData['tCondition'];
          $tConditionDocDate = $paData['tConditionDocDate'];
          $tConditionDealDate = $paData['tConditionDealDate'];
          $tConditionSupplierCode = $paData['tConditionSupplierCode'];
          $tConditionCodeGroup = $paData['tConditionCodeGroup'];
          $tConditionCodeType = $paData['tConditionCodeType'];
          $tConditionCodeClass = $paData['tConditionCodeClass'];
          //-----

          $tSql = "SELECT SPLD.FTSplCode,
                           SPL_L.FTSplName,
                           SPL.FTSplEmail,
                           SPLADD.FTAddV2Desc1,
													 SPLADD.FTAddTaxNo,
                           SPLD.FCXphLeft
                    FROM
                    (
                        SELECT
                               SPLPI.FTSplCode,
                               SUM(ISNULL(SPLPI.FCXphLeft,0)) AS FCXphLeft
                        FROM
                        (
                            SELECT HD.FTSplCode,
                                   HD.FTXphDocNo,
                                   HD.FTBchCode,
                                   ISNULL(SPL.FNXphCrTerm, 0) AS FNXphCrTerm,
                                   DOCREF.FDXshRefDocDate,
                                   SPL.FDXphDueDate,
                                   CASE
                                       WHEN SPL.FDXphDueDate > GETDATE()
                                       THEN 0
                                       ELSE DATEDIFF(DAY, SPL.FDXphDueDate, GETDATE())
                                   END AS FNXphDueQtyLate,
                                   DATEDIFF(DAY,GETDATE(),SPL.FDXphDueDate)  AS FNXphCreditDay,
                                   HD.FCXphLeft
                            FROM TAPTPiHD HD
                            INNER JOIN TAPTPiHDSpl SPL      ON HD.FTBchCode = SPL.FTBchCode     AND HD.FTXphDocNo = SPL.FTXphDocNo
                            LEFT JOIN TAPTPiHDDocRef DOCREF ON HD.FTBchCode = DOCREF.FTBchCode
                                        AND HD.FTXphDocNo = DOCREF.FTXshDocNo
                                        AND DOCREF.FTXshRefKey = 'BillNote'
                                        AND DOCREF.FTXshRefType = '3'
                            WHERE HD.FTXphStaDoc = 1 AND HD.FTXphStaApv = 1 AND HD.FCXphLeft > 0
                            $tConditionDocDate
                        ) SPLPI
                        WHERE SPLPI.FTSplCode !=''
                        AND ISNULL(SPLPI.FDXshRefDocDate,'') <> ''
                        $tCondition


                        GROUP BY SPLPI.FTSplCode

                    ) SPLD
                    LEFT JOIN TCNMSpl SPL ON SPLD.FTSplCode = SPL.FTSplCode

                    LEFT JOIN TCNMSpl_L SPL_L ON SPLD.FTSplCode = SPL_L.FTSplCode
                                               AND SPL_L.FNLngID = '$nLngID'

                    LEFT JOIN TCNMSplAddress_L SPLADD ON SPL.FTSplCode = SPLADD.FTSplCode
                                                AND SPLADD.FNAddSeqNo = 1
                                                WHERE SPLD.FTSplCode !='' $tConditionSupplierCode
                                                      $tConditionCodeGroup
                                                      $tConditionCodeType
                                                      $tConditionCodeClass ";
                                                      //$tConditionDealDate
          $oQuery = $this->db->query($tSql);
        //   echo $tSql;
          if($oQuery->num_rows() > 0){
              $aList = $oQuery->result_array();
              $oFoundRow = $this->FSoMMONGetPageAll($tSql,$nLngID);
              $nFoundRow = count($oFoundRow);
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
        } catch (Exception $Error) {
                return $Error;
        }
    }
    //Functionality : ข้อมูล
    //Parameters : function parameters
    //Creator :
    //Return : data
    //Return Type : object
    public function FSoMMONGetPageAll($tSql,$ptLngID)
    {
      try{
          $oQuery = $this->db->query($tSql);
          if ($oQuery->num_rows() > 0) {
              return $oQuery->result();
          }else{
              return false;
          }
      }catch(Exception $Error){
          echo $Error;
      }
    }

    public function FSoMMONDTGetData($paData){
        try {
          $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
          $tSesUsrAgnCode = $this->session->userdata('tSesUsrAgnCode');
          $tSearchList = $paData['tSearchAll'];
          $nLngID = $paData['FNLngID'];
          //----Condition
          $tCondition = $paData['tCondition'];
          $tConditionDocDate = $paData['tConditionDocDate'];
          $tConditionDealDate = $paData['tConditionDealDate'];
          $tConditionSupplierCode = $paData['tConditionSupplierCode'];
          $tConditionCodeGroup = $paData['tConditionCodeGroup'];
          $tConditionCodeType = $paData['tConditionCodeType'];
          $tConditionCodeClass = $paData['tConditionCodeClass'];
          $tSplCode = $paData['tSplCode'];
          //-----
          $tSql = "SELECT TPI.*,
                   CONVERT(CHAR(10), TPI.FDXphDueDate, 103) AS FDXphDueDate,
                   DOCREF1.FTXshRefDocNo AS FTXshRefInt,
                   DOCREF1.FTXshRefDocNo AS FTXshRefDocNoInt
            FROM
            (
                SELECT BCH.FTBchCode,
                       BCH.FTBchName,
                       HD.FTXphDocNo,

                       CONVERT(CHAR(10), HD.FDXphDocDate, 103) AS FDXphDocDate,
                       DOCREF.FTXshRefDocNo AS FTXshBillingNote,
                       CONVERT(CHAR(10), DOCREF.FDXshRefDocDate, 103) AS FDXshBillingNoteDate,
                       SPL.FDXphDueDate AS FDXphDueDate,
                       CASE
                           WHEN SPL.FDXphDueDate > GETDATE()
                           THEN 0
                           ELSE DATEDIFF(DAY, SPL.FDXphDueDate, GETDATE())
                       END AS FNXphDueQtyLate,
                       DATEDIFF(DAY,GETDATE(),SPL.FDXphDueDate)  AS FNXphCreditDay,
                 HD.FCXphGrand,
                 HD.FCXphPaid,
                 HD.FCXphLeft,
                 HD.FTSplCode
                FROM TAPTPiHD HD WITH(NOLOCK)
                     INNER JOIN TAPTPiHDSpl SPL WITH(NOLOCK) ON HD.FTBchCode = SPL.FTBchCode
                                                   AND HD.FTXphDocNo = SPL.FTXphDocNo
                     LEFT JOIN TAPTPiHDDocRef DOCREF WITH(NOLOCK)  ON HD.FTBchCode = DOCREF.FTBchCode
                                                        AND DOCREF.FTXshRefType = 3
                                                        AND HD.FTXphDocNo = DOCREF.FTXshDocNo
                     LEFT JOIN TCNMBranch_L BCH WITH(NOLOCK) ON HD.FTBchCode = BCH.FTBchCode
                                                   AND BCH.FNLngID = '$nLngID'
                  WHERE HD.FTXphStaDoc = 1
                          AND ISNULL(DOCREF.FTXshRefDocNo,'') <> ''
                          AND HD.FTXphStaApv = 1
                          AND HD.FCXphLeft > 0

                  AND HD.FTSplCode = '$tSplCode'
                  $tConditionDocDate

            ) TPI
            LEFT JOIN TAPTPiHDDocRef DOCREF1 WITH(NOLOCK) ON TPI.FTBchCode = DOCREF1.FTBchCode
                                                AND TPI.FTXphDocNo = DOCREF1.FTXshDocNo
                                                AND DOCREF1.FTXshRefType = 1
            LEFT JOIN TCNMSpl SPL WITH(NOLOCK) ON TPI.FTSplCode = SPL.FTSplCode

            WHERE ISNULL(TPI.FTXphDocNo,'') <> ''
            $tConditionDealDate

            $tCondition";
          $oQuery = $this->db->query($tSql);
        //   echo $tSql;
          if($oQuery->num_rows() > 0){
              $aList = $oQuery->result_array();
              $oFoundRow = $this->FSoMMONDTGetPageAll($tSql,$nLngID);
              $nFoundRow = count($oFoundRow);
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
        } catch (Exception $Error) {
                return $Error;
        }
    }

    //ส่งออก Excel
    public function FSoMMONDTGetDataExcel($paData){
        try {
          $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
          $tSesUsrAgnCode = $this->session->userdata('tSesUsrAgnCode');
          $tSearchList = $paData['tSearchAll'];
          $nLngID = $paData['FNLngID'];
          //----Condition
          $tCondition = $paData['tCondition'];
          $tConditionDocDate = $paData['tConditionDocDate'];
          $tConditionDealDate = $paData['tConditionDealDate'];
          $tConditionSupplierCode = $paData['tConditionSupplierCode'];
          $tConditionCodeGroup = $paData['tConditionCodeGroup'];
          $tConditionCodeType = $paData['tConditionCodeType'];
          $tConditionCodeClass = $paData['tConditionCodeClass'];
          $tSplCode = $paData['tSplCode'];
          if ($tSplCode=="") {
            $tConditionSpl = " 1 = 1 ";
          }else {
            $tConditionSpl = "HD.FTSplCode = '$tSplCode'";
          }
          //-----
          $tSql = "SELECT TPI.*,
                   CONVERT(CHAR(10), TPI.FDXphDueDate, 103) AS FDXphDueDate,
                   DOCREF1.FTXshRefDocNo AS FTXshRefInt,
                   DOCREF1.FTXshRefDocNo AS FTXshRefDocNoInt
            FROM
            (
                SELECT BCH.FTBchCode,
                       BCH.FTBchName,
                       HD.FTXphDocNo,
                       CONVERT(CHAR(10), HD.FDXphDocDate, 103) AS FDXphDocDate,
                       DOCREF.FTXshRefDocNo AS FTXshBillingNote,
                       CONVERT(CHAR(10), DOCREF.FDXshRefDocDate, 103) AS FDXshBillingNoteDate,
                       SPL.FDXphDueDate AS FDXphDueDate,
                       CASE
                           WHEN SPL.FDXphDueDate > GETDATE()
                           THEN 0
                           ELSE DATEDIFF(DAY, SPL.FDXphDueDate, GETDATE())
                       END AS FNXphDueQtyLate,
                       DATEDIFF(DAY,GETDATE(),SPL.FDXphDueDate)  AS FNXphCreditDay,
                 HD.FCXphGrand,
                 HD.FCXphPaid,
                 HD.FCXphLeft,
                 HD.FTSplCode
                FROM TAPTPiHD HD WITH(NOLOCK)
                     INNER JOIN TAPTPiHDSpl SPL WITH(NOLOCK) ON HD.FTBchCode = SPL.FTBchCode
                                                   AND HD.FTXphDocNo = SPL.FTXphDocNo
                     LEFT JOIN TAPTPiHDDocRef DOCREF WITH(NOLOCK)  ON HD.FTBchCode = DOCREF.FTBchCode
                                                        AND DOCREF.FTXshRefType = 3
                                                        AND HD.FTXphDocNo = DOCREF.FTXshDocNo
                     LEFT JOIN TCNMBranch_L BCH WITH(NOLOCK) ON HD.FTBchCode = BCH.FTBchCode
                                                   AND BCH.FNLngID = '$nLngID'
                  WHERE HD.FTXphStaDoc = 1
                          AND ISNULL(DOCREF.FTXshRefDocNo,'') <> ''
                          AND HD.FTXphStaApv = 1
                          AND HD.FCXphLeft > 0

                  AND $tConditionSpl
                  $tConditionDocDate

            ) TPI
            LEFT JOIN TAPTPiHDDocRef DOCREF1 WITH(NOLOCK) ON TPI.FTBchCode = DOCREF1.FTBchCode
                                                AND TPI.FTXphDocNo = DOCREF1.FTXshDocNo
                                                AND DOCREF1.FTXshRefType = 1
            LEFT JOIN TCNMSpl SPL WITH(NOLOCK) ON TPI.FTSplCode = SPL.FTSplCode

            WHERE ISNULL(TPI.FTXphDocNo,'') <> ''
            $tConditionDealDate

            $tCondition";
          // echo $tSql;
          // exit();
          $oQuery = $this->db->query($tSql);
          if($oQuery->num_rows() > 0){
              $aList = $oQuery->result_array();
              $oFoundRow = $this->FSoMMONDTGetPageAll($tSql,$nLngID);
              $nFoundRow = count($oFoundRow);
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
        } catch (Exception $Error) {
                return $Error;
        }
    }

    public function FSoMMONDTGetPageAll($tSQL,$ptLngID){
      try{
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

}
