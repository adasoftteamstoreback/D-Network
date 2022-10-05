<script>
    $(document).ready(function(){
        $("#oetRoleNotiSearchAll").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".xCNRoleNotiSettingBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            console.log('JSaGetRoleNotiSettingSelect: ', JSaGetRoleNotiSettingSelect());
        });

        $(".xCNRoleNotiSettingPermissionItemAll").on("change", function(){
            var bIsChecked = $(this).is(":checked");
            if(bIsChecked){
                $(".xCNRoleNotiSettingPermissionItem").prop("checked", true);
            }else{
                $(".xCNRoleNotiSettingPermissionItem").prop("checked", false);
            }
        });
    });
    
    /**
     * Functionality: Get Function Setting Role on Selected
     * Creator: 24/04/2020 piya
     * LastUpdate: -
     * Return : Function Setting Role on Selected Items
     * ReturnType: array
     */
    function JSaGetRoleNotiSettingSelect(){
        var aRoleNotiSettingItems = [];
        var oRoleNotiSettingChecked = $(".xCNRoleNotiSettingTable .xCNRoleNotiSettingPermissionItem:checked");

        $.each(oRoleNotiSettingChecked, function(){
            var tNotiCode = $(this).data("notcode");

            aRoleNotiSettingItems.push({
                tNotiCode: tNotiCode
            });
        });

        return aRoleNotiSettingItems;
    }
</script>