<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Delaystatus_model extends CI_Model
{



    public function FSoMDLSGetDataOption($paData)
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
     * Creator : 29/10/2021 Off
     * Last Modified : -
     * Return : data
     * Return Type : array
     */
    public function FSoMDLSGetData($paData)
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
          $tConditionCustomerCode = $paData['tConditionCustomerCode'];
          $tConditionCodeGroup = $paData['tConditionCodeGroup'];
          $tConditionCodeType = $paData['tConditionCodeType'];
          $tConditionCodeClass = $paData['tConditionCodeClass'];
          //-----

          $tSql = "SELECT  HDD.FTCstCode,CST.FTCStName,HDD.FNXshAddrShip,CSTHD.FTCstTel,CSTHD.FTCstEmail,
          CASE WHEN ADDR.FTAddVersion = 1 THEN
              ISNULL(FTAddV1No ,'')+' ' + ISNULL(FTAddV1Soi ,'') + ' '+
              ISNULL(FTAddV1Village ,'')+' ' + ISNULL(FTAddV1Road ,'') + ' '+
              ISNULL(FTAddV1SubDist ,'')+' ' + ISNULL(FTAddV1DstCode ,'') + ' '+
              ISNULL(FTAddV1PvnCode ,'')+' ' + ISNULL(FTAddV1PostCode ,'') + ' '+
              ISNULL(FTAddTel ,'')
              ELSE
              ISNULL(FTAddV2Desc1 ,'')+' ' + ISNULL(FTAddV2Desc2 ,'') + ' '+ ISNULL(FTAddV1PostCode ,'')
              END AS FTCstAddress,
              HDD.FCXshGrand
          FROM (
            SELECT
            HD.FTCstCode,
            SUM ( ISNULL( HD.FCXshGrand, 0 ) ) AS FCXshGrand ,
            HD.FNXshAddrShip
        FROM
            (
          SELECT
          HD.FTCstCode
          ,HCT.FNXshAddrShip
                  ,SUM(CASE
                          WHEN HD.FNXshDocType = 1
                          THEN HD.FCXshGrand * 1
                          ELSE HD.FCXshGrand * -1
                      END) AS FCXshGrand,
                      HD.FDXshDocDate,
            DATEDIFF(DAY,GETDATE(),DATEADD(DAY, ISNULL(CRD.FNCstCrTerm, 0), HD.FDXshDocDate))  AS FNXphCreditDay
          FROM TPSTSalHD HD
              LEFT JOIN TCNMCst CST ON HD.FTCstCode = CST.FTCstCode
              LEFT JOIN TCNMCstCredit CRD ON HD.FTCstCode = CRD.FTCstCode
              LEFT JOIN TPSTSalHDCst HCT ON HD.FTBchCode = HCT.FTBchCode AND HD.FTXshDocNo = HCT.FTXshDocNo
          WHERE HD.FTXshStaDoc = 1
          AND ISNULL(CRD.FNCstCrTerm, 0) > 0
          AND ISNULL(HD.FCXshLeft, 0) > 0
          $tConditionCustomerCode
          $tConditionDocDate
          $tConditionDealDate
          GROUP BY HD.FTCstCode,HCT.FNXshAddrShip,HD.FDXshDocDate,CRD.FNCstCrTerm ) HD
          WHERE
                HD.FTCstCode != '' 
                $tCondition
            GROUP BY
                HD.FTCstCode,
                HD.FNXshAddrShip
            ) HDD
          LEFT JOIN  TCNMCst_L CST ON HDD.FTCstCode = CST.FTCstCode AND CST.FNLngID = '$nLngID'
          LEFT JOIN  TCNMCst CSTHD ON HDD.FTCstCode = CSTHD.FTCstCode
          LEFT JOIN  TCNMCstAddress_L  ADDR ON  HDD.FTCstCode = ADDR.FTCstCode AND ADDR.FNLngID = '$nLngID' 
          WHERE HDD.FTCstCode != '' 
          AND HDD.FCXshGrand > 0
          $tConditionCodeGroup
          $tConditionCodeType
          $tConditionCodeClass
                                                      ";
//AND HDD.FCXshGrand > 0
                                                      //$tConditionDealDate
                                                      //HD.FTCstCode = ADDR.FTCstCode
          $oQuery = $this->db->query($tSql);
        //   echo $tSql;
          if($oQuery->num_rows() > 0){
              $aList = $oQuery->result_array();
              $oFoundRow = $this->FSoMDLSGetPageAll($tSearchList,$nLngID);
              $nFoundRow =  ($oFoundRow != false) ? count($oFoundRow) : 0 ;
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
    public function FSoMDLSGetPageAll($ptSearchList,$ptLngID)
    {
      $tAgnCode   = $this->session->userdata("tSesUsrAgnCode");
      try{
          $tSql = "SELECT SPLD.FTSplCode,
                           SPL.FTSplName,
                           SPLADD.FTAddV2Desc1,
													 SPLADD.FTAddTaxNo
                    FROM
                    (
                        SELECT DISTINCT
                               SPLPI.FTSplCode
                        FROM
                        (
                            SELECT HD.FTSplCode,
                                   HD.FTXphDocNo,
                                   HD.FTBchCode,
                                   ISNULL(SPL.FNXphCrTerm, 0) AS FNXphCrTerm,
                                   DOCREF.FTXshRefDocNo,
                                   DOCREF.FDXshRefDocDate,
                                   DATEADD(DAY, ISNULL(SPL.FNXphCrTerm, 0), DOCREF.FDXshRefDocDate) AS FDXphDueDate,
                                   CASE
                                       WHEN DATEADD(DAY, ISNULL(SPL.FNXphCrTerm, 0), DOCREF.FDXshRefDocDate) > GETDATE()
                                       THEN 0
                                       ELSE DATEDIFF(DAY, DATEADD(DAY, ISNULL(SPL.FNXphCrTerm, 0), DOCREF.FDXshRefDocDate), GETDATE())
                                   END AS FNXphDueQtyLate
                            FROM TAPTPiHD HD
                                 INNER JOIN TAPTPiHDSpl SPL ON HD.FTBchCode = SPL.FTBchCode
                                                               AND HD.FTXphDocNo = SPL.FTXphDocNo
                                 LEFT JOIN TAPTPiHDDocRef DOCREF ON HD.FTBchCode = DOCREF.FTBchCode
                                                                    AND HD.FTXphDocNo = DOCREF.FTXshDocNo
                                                                    AND DOCREF.FTXshRefType = 3
                          --JOIN SPL

                            --Condition

                            WHERE FCXphLeft > 0
                        ) SPLPI
                        WHERE FNXphDueQtyLate BETWEEN '0' AND '20'
                     --AND วันครบกำหนดชำระ
                    ) SPLD
                    LEFT JOIN TCNMSpl_L SPL ON SPLD.FTSplCode = SPL.FTSplCode
                                               AND SPL.FNLngID = 1
                    LEFT JOIN TCNMSplAddress_L SPLADD ON SPL.FTSplCode = SPLADD.FTSplCode
                                                         AND SPLADD.FNAddSeqNo = 1";


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

    public function FSoMDLSDTGetData($paData)
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
          $tConditionCustomerCode = $paData['tConditionCustomerCode'];
          $tConditionCodeGroup = $paData['tConditionCodeGroup'];
          $tConditionCodeType = $paData['tConditionCodeType'];
          $tConditionCodeClass = $paData['tConditionCodeClass'];
          $tCstCode = $paData['ptCstCode'];
          //-----
          $tSql = "SELECT
          FTBchCode,
          FTBchName,
          FTXshDocNo,
          FDXshDocDate,
          FTXshBillingNO,
          FDXshBillingDate,
          FDXshDueDate,
          FNXshDelayDate,
          FCXshGrand,
          FCXshPaid,
          FCXshLeft,
          FNXphCreditDay,
          FNXshDocType
      FROM
          (SELECT
                HD.FTBchCode,
                BCH.FTBchName,
                HD.FTXshDocNo,
                CONVERT(CHAR(10), HD.FDXshDocDate, 103) AS FDXshDocDate,
                HD.FTXshDocNo+'-'+'DN' AS FTXshBillingNO,
                CONVERT(CHAR(10), HD.FDXshDocDate, 103) AS FDXshBillingDate,
                CONVERT(CHAR(10), DATEADD(DAY,ISNULL(CRD.FNCstCrTerm,0),HD.FDXshDocDate), 103) AS FDXshDueDate,
                CASE WHEN DATEADD(DAY,ISNULL(CRD.FNCstCrTerm,0),HD.FDXshDocDate) < GETDATE() THEN
                    DATEDIFF(DAY , DATEADD(DAY,ISNULL(CRD.FNCstCrTerm,0),HD.FDXshDocDate) , GETDATE())
                ELSE
                0 END AS FNXshDelayDate,
                DATEDIFF(DAY,GETDATE(),DATEADD(DAY, ISNULL(CRD.FNCstCrTerm, 0), HD.FDXshDocDate))  AS FNXphCreditDay,
                ISNULL(HD.FCXshGrand,0) AS FCXshGrand,
                ISNULL(HD.FCXshPaid,0) AS FCXshPaid,
                ISNULL(HD.FCXshLeft,0) AS FCXshLeft,
                HD.FNXshDocType
                FROM TPSTSalHD HD
                    LEFT JOIN TCNMCst CST ON HD.FTCstCode = CST.FTCstCode
                    LEFT JOIN TCNMCstCredit CRD ON HD.FTCstCode = CRD.FTCstCode
                    LEFT JOIN TCNMBranch_L BCH ON HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID = 1
                WHERE HD.FTXshStaDoc = 1
                AND HD.FTCstCode ='$tCstCode'
                AND ISNULL(CRD.FNCstCrTerm, 0) > 0
                AND ISNULL(HD.FCXshLeft, 0) > 0
                $tConditionDealDate
                $tConditionDocDate) A
                WHERE ISNULL(A.FDXshDocDate,'') <> ''
                $tCondition
          ";
        //   print_r($tSql);
          // exit();
          $oQuery = $this->db->query($tSql);
          if($oQuery->num_rows() > 0){
              $aList = $oQuery->result_array();
              $oFoundRow = $this->FSoMDLSDTGetPageAll($tSearchList,$nLngID);
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

    public function FSoMDLSDTGetPageAll($ptSearchList,$ptLngID)
    {
      $tAgnCode   = $this->session->userdata("tSesUsrAgnCode");

      try{
          $tSql = "SELECT TPI.*,
                   DOCREF1.FTXshRefDocNo AS FTXshRefInt,
                   DOCREF1.FTXshRefDocNo AS FTXshRefDocNoInt
            FROM
            (
                SELECT BCH.FTBchCode,
                       BCH.FTBchName,
                       HD.FTXphDocNo,
                       HD.FDXphDocDate,
                       DOCREF.FTXshRefDocNo AS FTXshBillingNote,
                       DOCREF.FDXshRefDocDate FDXshBillingNoteDate,
                       DATEADD(DAY, ISNULL(SPL.FNXphCrTerm, 0), DOCREF.FDXshRefDocDate) AS FDXphDueDate,
                       CASE
                           WHEN DATEADD(DAY, ISNULL(SPL.FNXphCrTerm, 0), DOCREF.FDXshRefDocDate) > GETDATE()
                           THEN 0
                           ELSE DATEDIFF(DAY, DATEADD(DAY, ISNULL(SPL.FNXphCrTerm, 0), DOCREF.FDXshRefDocDate), GETDATE())
                       END AS FNXphDueQtyLate,
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
                                                   AND BCH.FNLngID = 1
                  WHERE HD.FTXphStaDoc = 1
                          AND HD.FTXphStaApv = 1
                          AND HD.FCXphLeft > 0
                 --ส่ง Spl ที่คลิ๊กมา WHERE
                 --AND HD.FTSplCode = '0010020198'
            ) TPI
            LEFT JOIN TAPTPiHDDocRef DOCREF1 WITH(NOLOCK) ON TPI.FTBchCode = DOCREF1.FTBchCode
                                                AND TPI.FTXphDocNo = TPI.FTXphDocNo
                                                AND DOCREF1.FTXshRefType = 1
            LEFT JOIN TCNMSpl SPL WITH(NOLOCK) ON TPI.FTSplCode = SPL.FTSplCode

            WHERE ISNULL(TPI.FTXphDocNo,'') <> ''
            --AND SPL.FTStyCode = ''
            --AND SPL.FTSgpCode = ''
            --AND TPI.FNXphDueQtyLate BETWEEN '0' AND  '30'";


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

}
