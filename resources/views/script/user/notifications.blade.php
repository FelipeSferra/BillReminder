<script>
    let userInfo = @json($userInfo);
    $(document).ready(function() {

        $('#notifGasto').bootstrapToggle({
            onlabel: 'Sim',
            offlabel: 'Não',
            size: 'sm',
            onstyle: 'success',
            offstyle: 'danger'
        });

        $('#notifVenc').bootstrapToggle({
            onlabel: 'Sim',
            offlabel: 'Não',
            size: 'sm',
            onstyle: 'success',
            offstyle: 'danger'
        });

        $('#notifGasto').on('change', function() {
            if (this.checked) {
                $('#divGastos').css("display", "inline-block");
            } else {
                $('#divGastos').css("display", "none");
            }
            $('#saveNotify').attr('disabled', false)
        })

        $('#notifVenc').on('change', function() {
            if (this.checked) {
                $('#divVenc').css("display", "inline-block");
            } else {
                $('#divVenc').css("display", "none");
            }
            $('#saveNotify').attr('disabled', false)
        })

        if (userInfo.NOTIFICAR_GASTO === 'S') {
            $('#notifGasto').bootstrapToggle('on');
            $('#notifGasto').prop('checked', true).change();
            $('#emailGastos option').each(function() {
                if ($(this).data('tipo') === userInfo.TIPO_NOTIF_GASTO) {
                    $(this).prop('selected', true);
                }
            });
        }

        if (userInfo.NOTIFICAR_VENC === 'S') {
            $('#notifVenc').bootstrapToggle('on');
            $('#notifVenc').prop('checked', true).change();
            $('#emailVenc').val(userInfo.VENC_DIAS);
        }
    });
</script>
