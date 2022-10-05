<style>
    .layout-fullwidth #wrapper .main {
        padding-left: 60px;
    }

    #odvContentWellcome {
        background-image: url('application/modules/common/assets/images/bg/Backoffice.jpg');
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        opacity: 0.6 !important;
    }
</style>

<?php if ($this->session->userdata("tStaByPass") != 1) {
    $tClassControlMenuStyle = 'main xWWidth100';
} else {
    $tClassControlMenuStyle = ''; 
    // xCNLayoutWithStoreFont 
}
?>
<div class="odvMainContent <?= $tClassControlMenuStyle ?>" style="padding-bottom: 0px;">
    <div class="container-fluid">
        <div class="" id="odvContentWellcome" style="margin:0px 0px; background-color:#FFF;">
        </div>
    </div>
</div>

<script>
    var tStaBuyPackage = '<?= $this->session->userdata("bSesRegStaBuyPackage") ?>';
    if (tStaBuyPackage == '') {

        $.ajax({
            type: "POST",
            url: "ImformationRegister",
            timeout: 0,
            success: function(tHtmlResult) {
                $('.odvMainContent').html(tHtmlResult);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

    }

    //ถ้าเข้ามาแบบ pos front จะวิ่งไปซ้อนเมนู
    var tStaByPass = '<?= $this->session->userdata("tStaByPass") ?>';
    if (tStaByPass == '1') {
        var tRouteByPass = '<?= $this->session->userdata("tRouteByPass") ?>';
        var tByPassAgnCode = '<?= $this->session->userdata("tByPassAgnCode") ?>';
        var tByPassBchCode = '<?= $this->session->userdata("tByPassBchCode") ?>';
        var tByPassDocNo = '<?= $this->session->userdata("tByPassDocNo") ?>';
        var tImageCarLoad = '<?php echo base_url() ?>application/modules/common/assets/images/LoadingFitAuto.gif';
        var tImage = "<div style='background: linear-gradient(179deg, rgba(255,255,255,1) 0%, rgb(253 253 253) 52%, rgb(231 242 251) 100%); height: 100%; width: 100%; display: block; position: fixed;'>";
        tImage += "<div>";
        tImage += "<img src=" + tImageCarLoad + " style='width: 16%; margin: 15% auto; display: block;'>";
        tImage += " </div>";
        tImage += "<p style='display: block; position: absolute; top: 40%; left: 40%; width: 100%; font-size: 47px !important;'>";
        tImage += ""; //กรุณารอสักครู่ กำลังโหลดหน้าจอ
        tImage += "</p>";
        tImage += "</div>";
        $('.odvMainContent').html(tImage);

        $.ajax({
            type: "POST",
            url: tRouteByPass,
            data: {
                'tAgnCode'              : tByPassAgnCode,
                'tBchCode'              : tByPassBchCode,
                'tDocNo'                : tByPassDocNo,
                'csrf_storeback_name'   : '<?=$this->security->get_csrf_hash();?>'
            },
            timeout: 0,
            success: function(tHtmlResult) {
                setTimeout(function() {
                    $('.odvMainContent').html(tHtmlResult);

                    //ถ้าเป็นการเข้าจาก WebView ไม่ต้องโชว์ Favorite
                    $('#oimImgFavicon').parent('li').eq(0).css('display','none');
                }, 1500);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });


    }
</script>