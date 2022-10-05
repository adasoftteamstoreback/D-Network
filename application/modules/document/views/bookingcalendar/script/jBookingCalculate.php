<script>
      $('#ocmBKfoVatInOrEx').change(function (){ JSnBKCalculatePrice(); });

    //คำนวณราคาสินค้าทั้งหมด
    function JSnBKCalculatePrice(){
        var rowCount    = $('#otbBKPdtTemp >tbody >tr.xCNHavePDT').length;
        var nPrice      = 0;
        for(var i=0; i<rowCount; i++){
            var nPriceItem = $('#otbBKPdtTemp >tbody >tr').eq(i).find('.xCNPriceProductAll').text().replace(/,/g, '');
            if(nPriceItem == '' || nPriceItem == null || nPriceItem == 'NULL'){
                nPrice = nPrice + 0;
            }else{
                nPrice = nPrice + parseFloat($('#otbBKPdtTemp >tbody >tr').eq(i).find('.xCNPriceProductAll').text().replace(/,/g, ''))
            }
        }

        var nDecimalShow    = 2;
        var tVatList        = '';
        var aVat            = [];
        $('#otbBKPdtTemp tbody tr').each(function(){
            var nAlwVat  = $(this).attr('data-alwvat');
            var nVat     = parseFloat($(this).attr('data-vat'));
            var nKey     = $(this).attr('data-keycode');
            var tTypeVat = $('#ocmBKfoVatInOrEx').val();

            if(nAlwVat == 1){
                //อนุญาตคิด VAT
                if(tTypeVat == 1){
                    // ภาษีรวมใน tSoot = net - ((net * 100) / (100 + rate));
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = net - (net * 100 / (100 + nVat));
                    var nResult   = parseFloat(nTotalVat).toFixed(nDecimalShow);
                }else if(tTypeVat == 2){
                    // ภาษีแยกนอก tSoot = net - (net * (100 + 7) / 100) - net;
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = (net * (100 + nVat) / 100) - net;
                    var nResult   = parseFloat(nTotalVat).toFixed(nDecimalShow);
                }

                var oVat = { VAT: nVat , VALUE: nResult };
                aVat.push(oVat);
            }
        });

        //เรียงลำดับ array ใหม่
        aVat.sort(function (a, b) {
            return a.VAT - b.VAT;
        });

        //รวมค่าใน array กรณี vat ซ้ำ
        var nVATStart       = 0;
        var nSumValueVat    = 0;
        var aSumVat         = [];
        for(var i=0; i<aVat.length; i++){
            if(nVATStart == aVat[i].VAT){
                nSumValueVat = nSumValueVat + parseFloat(aVat[i].VALUE);
                aSumVat.pop();
            }else{
                nSumValueVat = 0;
                nSumValueVat = nSumValueVat + parseFloat(aVat[i].VALUE);
                nVATStart    = aVat[i].VAT;
            }

            var oSum = { VAT: nVATStart , VALUE: nSumValueVat };
            aSumVat.push(oSum);
        }

        //เอา VAT ไปทำในตาราง
        var nSumVat = 0;
        var nCount  = 1;
        for(var j=0; j<aSumVat.length; j++){
            var tVatRate    = aSumVat[j].VAT;
            if(nCount != aSumVat.length){
                var tSumVat     = parseFloat(aSumVat[j].VALUE).toFixed(nDecimalShow) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE).toFixed(nDecimalShow);
            }else{
                var tSumVat     = (aSumVat[j].VALUE - nSumVat).toFixed(nDecimalShow);
            }
            tVatList    += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(nDecimalShow)) + '</label><div class="clearfix"></div></li>';
            nSumVat += parseFloat(aSumVat[j].VALUE);
            nCount++;
        }
        $('#oulBKDataListVat').html(tVatList);

        //ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbBKVatSum').text(numberWithCommas(parseFloat(nSumVat).toFixed(nDecimalShow)));
        $('.xCNPriceSUMPDT').text(numberWithCommas(parseFloat(nSumVat).toFixed(nDecimalShow)));

        //เอาราคาไประบุ
        nPrice = nPrice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');  
        $('.xCNPriceSUMByPDT').text(nPrice);

        //สรุปราคารวม
        var tTypeVat = $('#ocmBKfoVatInOrEx').val();
        if(tTypeVat == 1){ //คิดแบบรวมใน
            var nTotal          = parseFloat($('.xCNPriceSUMByPDT').text().replace(/,/g, ''));
            var nVat            = parseFloat($('.xCNPriceSUMPDT').text().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal);
        }else if(tTypeVat == 2){ //คิดแบบแยกนอก
            var nTotal          = parseFloat($('.xCNPriceSUMByPDT').text().replace(/,/g, ''));
            var nVat            = parseFloat($('.xCNPriceSUMPDT').text().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal) + parseFloat(nVat);
        }
        $('.xCNPriceSUMALL').text(numberWithCommas(parseFloat(nResultTotal).toFixed(2)));

        //ราคารวมทั้งหมด ตัวเลขบาท
        var tThaibath 	= ArabicNumberToText(nResultTotal);
        $('#odvBKDataTextBath').text(tThaibath);
    }

    //พวกตัวเลขใส่ comma ให้มัน
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }

</script>