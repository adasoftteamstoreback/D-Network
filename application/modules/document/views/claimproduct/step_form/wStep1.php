<div class="row p-t-10">
        <div class="col-lg-12">
            <ul class="bb-wizard-steps markers bb-justified">
                <li class="active">
                    <a data-toggle="tab" href="#odvClaimStep1Point1" class="xCNClaimStep1Point1"><label>รับสินค้าจากลูกค้า</label></a>
                </li>
                <li>      
                    <a data-toggle="tab" href="#odvClaimStep1Point2" class="xCNClaimStep1Point2"><label>ตรวจสอบเงื่อนไขภายใน</label></a>
                </li>  
                <li>
                    <a data-toggle="tab" href="#odvClaimStep1Point3" class="xCNClaimStep1Point3"><label>ตรวจสอบเงื่อนไขผู้จำหน่าย</label></a>  
                </li>
                <li>
                    <a data-toggle="tab" href="#odvClaimStep1Point4" class="xCNClaimStep1Point4"><label>การเปลี่ยน / เบิกสินค้า</label></a>
                </li>
            </ul>
        </div>

        <div style="margin-top: 40px;">
            <div class="containter tab-content">
                <div class="bb-wizard-page tab-pane active" id="odvClaimStep1Point1">
                    <?php include('wStep1Point1.php'); ?>
                </div>
                <div class="bb-wizard-page tab-pane" id="odvClaimStep1Point2">
                    <?php include('wStep1Point2.php'); ?>
                </div>
                <div class="bb-wizard-page tab-pane" id="odvClaimStep1Point3">
                    <?php include('wStep1Point3.php'); ?>   
                </div>
                <div class="bb-wizard-page tab-pane" id="odvClaimStep1Point4">
                    <?php include('wStep1Point4.php'); ?>   
                </div>  
            </div>
        </div>
</div>

<script>

    //กด "รับสินค้าจากลูกค้า"
    $('.xCNClaimStep1Point1').unbind().click(function(){ 
        $('.xCNClaimConfirm').css('display','none');
    });

    //กด "ตรวจสอบเงื่อนไขภายใน"
    $('.xCNClaimStep1Point2').unbind().click(function(){ 
        //โหลดสินค้า
        $('.xCNClaimConfirm').css('display','none');
        JSxCLMStep1Point2LoadDatatable();
    });

    //กด "ตรวจสอบเงื่อนไขผู้จำหน่าย"
    $('.xCNClaimStep1Point3').unbind().click(function(){ 
        //โหลดสินค้า
        $('.xCNClaimConfirm').css('display','none');
        JSxCLMStep1Point3LoadDatatable();
    });

    //กด "การเปลี่ยน / เบิกสินค้า"
    $('.xCNClaimStep1Point4').unbind().click(function(){ 
        //โหลดสินค้า
        $('.xCNClaimConfirm').css('display','block');
        JSxCLMStep1Point4LoadDatatable();
    });

</script>