<style>
    .xCNTextLoadingSwichRouteColorred{
        color   : red;
        left    : 30% !important;
    }
</style>

<!--CSRF-->
<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

<div style='background: linear-gradient(179deg, rgba(255,255,255,1) 0%, rgb(253 253 253) 52%, rgb(231 242 251) 100%); height: 100%; width: 100%; display: block; position: fixed;'>
    <div>
        <img src="<?php echo base_url() ?>application/modules/common/assets/images/LoadingFitAuto.gif" style="width: 16%; margin: 15% auto; display: block;">
    </div>
    <p class="xCNTextLoadingSwichRoute" style='display: block; position: absolute; top: 47%; left: 40%; width: 100%; font-size: 47px !important;'>   
        <!-- กรุณารอสักครู่ กำลังโหลดหน้าจอ -->
    </p>
</div>
<input type="hidden" id="oetInputDatNotFoundJumpWeb" value="<?=base_url() ?>application/modules/common/assets/images/DataNotFound.jpg" >
<script src="<?php echo base_url(); ?>application/modules/common/assets/js/global/PasswordAES128/aes.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/js/global/PasswordAES128/cAES128.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/js/global/PasswordAES128/AESKeyIV.js"></script>
<script>

    var tSesUserLogin = '<?=$this->session->userdata("tSesUserLogin")?>';
    $('document').ready(function() {
        var tDecrytEmail = JCNtAES128DecryptData('<?=$aParams?>', tKey, tIV);
        var aItems       = tDecrytEmail.split("%82");
        if(aItems.length != 7){// ส่งมาไม่ถึง 4 ตัว
            alert('รูปแบบการเข้ารหัสผิด')
        }else{
            //Route เมนู
            var tParamRoute = aItems[0].split("=");
            var tParamRoute = tParamRoute[1];

            //ชื่อผู้ใช้
            var tParamUser = aItems[1].split("=");
            var tParamUser = tParamUser[1];

            //ภาษาที่ต้องการแสดง
            var tParamLang = aItems[2].split("=");
            var tParamLang = tParamLang[1];

            //วันที่ปัจจุบัน
            var tParamDate = aItems[3].split("=");
            var tParamDate = tParamDate[1];

            //สาขา
            var tParamBchCode = aItems[4].split("=");
            var tParamBchCode = tParamBchCode[1];

            //เลขที่เอกสาร
            var tParamDocNo = aItems[5].split("=");
            var tParamDocNo = tParamDocNo[1];

            //ตัวแทนขาย
            var tParamAgnCode = aItems[6].split("=");
            var tParamAgnCode = tParamAgnCode[1];
            
            //หาว่าวันที่ที่ส่งมา เท่ากับวันที่ปัจจุบันหรือเปล่า
            const dDateParameter    = new Date(tParamDate);
            const dDateCurrent      = new Date();

            if(dDateParameter.setHours(0,0,0,0) !== dDateCurrent.setHours(0,0,0,0)){
                $('.xCNTextLoadingSwichRoute').text('URL ของคุณหมดอายุกรุณาลองใหม่อีกครั้ง' + '\n' + 'ผ่าน AdaStoreFront !');
                $('.xCNTextLoadingSwichRoute').addClass('xCNTextLoadingSwichRouteColorred');
                return;
            }

            $.ajax({
                type    : "POST",
                url     : "../../authen/logout/cLogout/FSxCLOGLogoutWhenLoginWebView",
                cache   : false,
                data    : { 'csrf_storeback_name' : '<?=$this->security->get_csrf_hash();?>' },
                timeout : 0,
                success : function(oResult) {
                    $.ajax({
                        type    : "POST",
                        url     : "../../authen/login/cLogin/FSaCLOGChkLogin",
                        data    : {
                            'csrf_storeback_name'     : '<?=$this->security->get_csrf_hash();?>',
                            'tRouteByPass'            : tParamRoute,
                            'oetUsername'             : tParamUser,
                            'oetPasswordhidden'       : 'null',
                            'tUsrCode'                : 'null',
                            'tUsrLogType'             : '1',
                            'tStaByPass'              : '1',
                            'nLanguage'               : tParamLang,
                            'tParamBchCode'           : tParamBchCode,
                            'tParamDocNo'             : tParamDocNo,
                            'tParamAgnCode'           : tParamAgnCode
                        },
                        cache   : false,
                        timeout : 0,
                        success : function(oResult) {
                            var aResult = JSON.parse(oResult);
                            if( aResult['nStaReturn'] == 1 ){
                                setTimeout(function() {
                                    window.location.href = $('#ohdBaseURL').val();  
                                }, 1500);
                            }else{
                                $('.xCNTextLoadingSwichRoute').text('ชื่อผู้ใช้ผิดพลาดกรุณาลองใหม่อีกครั้ง !');
                                $('.xCNTextLoadingSwichRoute').addClass('xCNTextLoadingSwichRouteColorred');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR)
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR)
                }
            });
        }
    });

</script>