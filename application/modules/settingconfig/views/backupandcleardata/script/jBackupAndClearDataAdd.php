<script type="text/javascript">

    //Functionality : (event) Add/Edit Backup And Clean
    //Parameters : form
    //Creator : 06/08/2022 wasin
    //Return : object Status Event And Event Call Back
    //Return Type : object
    function JSnAddEditBackupAndClean(ptRoute) {
        var nStaSession = JCNxFuncChkSessionExpired();

        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: ptRoute,
                data: $('#ofmBackupAndClean').serialize(),
                async: false,
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    JSvBACCallPageDataTable();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }


</script>