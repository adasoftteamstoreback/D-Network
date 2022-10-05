<?php
    if(isset($aNewData['raItems'])){
        $tNewCode           = $aNewData['raItems']['rtNewCode'];
        $tNewName           = $aNewData['raItems']['rtNewName'];
        $tNewRmk            = $aNewData['raItems']['rtNewRmk'];
        $tNewRefUrl          = $aNewData['raItems']['rtNewRefUrl'];
        $rnNewToType          = $aNewData['raItems']['rnNewToType'];
        $tUsrCode           = $aNewData['raItems']['rtUsrCode'];
        $tUsrName           = $aNewData['raItems']['rtUsrName'];
        $tBchCode           = $aNewData['raItems']['rtBchCode'];
        $dCreateOn           = date('d/m/Y',strtotime($aNewData['raItems']['rdCreateOn']));
   
    
    }else{
        $tNewCode           = "";
        $tNewName           = "";
        $tNewRmk            = "";
        $tNewRefUrl          = "";
        $rnNewToType        = 1;
        $tUsrCode           = $this->session->userdata('tSesUserCode');
        $tUsrName          = $this->session->userdata('tSesUsrUsername');
        $tBchCode         = $this->session->userdata('tSesUsrBchCodeDefault');
        $dCreateOn        ="";
    }
?>
<!doctype html>
<html lang="th" class="fullscreen-bg">
    <head>
        <title><?=$tNewName?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>application/modules/common/assets/images/AdaLogo.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>application/modules/common/assets/images/AdaLogo.png">
        <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/localcss/ada.fonts.css">
    </head>
<style>
.xParagraph{
    text-indent: 35px;
}
#odvLCGPageFromIn {
    border-top-right-radius: 15px;
    border-top-left-radius: 15px;
    margin-top: 30px !important;
    width:750px;
    font-family: THSarabunNew;
    font-size: 18px !important;
    font-weight: 500;
    margin: 0px 20px 20px 20px;
    box-shadow: 0 2px 6px rgb(0 0 0 / 17%);
    background-color: #fff;
    margin-bottom: 30px;
    background-image: linear-gradient(rgba(255,255,255,0.92), rgba(255,255,255,1)) , url('../../application/modules/common/assets/images/logo/fitauto.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    background-origin: content-box;
    background-size: auto;
    background-position: center;
}
html{
    background-color: #f0f4f7;
}
</style>
    <body class="xCNBody layout-fullwidth" >

        <div class="container" align="center">
            <div class="row" id="odvLCGPageFromIn" align="left">
                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="margin-bottom: 80px; padding: 30px;">
                    <div>
                        <h1 style="color: #4077ef;">
                            <span style="color: rgb(70, 70, 70);font-size: 30px !important;font-weight: bold;">
                                <?php echo language('news/news/news','tNewNameTitle');?> :</span>&nbsp;<?=$tNewName?>
                        </h1>

                        <div>
                            <hr style="margin-bottom: 30px; border-color: #ffffff42;">
                        </div>

                        <div class=""><?php echo language('news/news/news','tNewDate');?> <?=$dCreateOn?></div>
                        <div class=""><?php echo language('news/news/news','tNewUserCreate');?> <?=$tUsrName?></div>
                        <div class="xParagraph"><?=$tNewRmk?></div>
                        
                        <br>
                        <?php 
                            if($aNewBchFile['rtCode']=='1'){ ?>
                                <div>
                                    <b><label class="xCNLabelFrm"><?=language('common/main/main','tUPFDataTable')?></label></b>
                                </div>
                        
                            <?php
                                foreach($aNewBchFile['raItems'] as $k => $aFile){
                                    if(FCNUtf8StrLen($aFile['FTFleName'])>30){
                                        $tFleName = substr($aFile['FTFleName'],0,30).'...';
                                    }else{
                                        $tFleName = $aFile['FTFleName'];
                                    } ?>
                                <div class="xParagraph">
                                    <a href="<?=$aFile['FTFleObj']?>" target="_blank" title="<?=$aFile['FTFleName']?>" ><u><?=$tFleName?></a><br>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>                                                                             
                </div>

                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="background: #f5f5f5; border-top: 1px solid #dddddd; padding: 30px;">
                    <div class=""> PHONE : +66 2530 1681 LINE ID : @adasoft</div>
                </div>
            </div>
        </div>
    </body>
</html>
