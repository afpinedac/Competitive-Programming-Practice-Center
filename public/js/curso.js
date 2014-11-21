curso = {
    set_publico: function() {
        var public = $("#is_public").data('public');
        //     window.alert(public);
        if (public == 1) {
            $("#is_public").data('public', 0);
            $("#password_curso").show();
        } else {
            $("#is_public").data('public', 1);
            $("#password_curso").hide();
        }

    },
    modulo: {
        crear: function() {
            value = $("#div-crear-modulo").data('state');
            if (value == 0) {
                $("#div-crear-modulo").show();
                $("#div-crear-modulo").focus();
                $("#div-crear-modulo").data('state', 1);

            } else {
                $("#div-crear-modulo").hide();
                $("#div-crear-modulo").data('state', 0);
            }
        },
        recursos_extras: function(e) {

            var visible = $("#div-recursos-extra").data('visible');
            if (visible == 1) {
                $("#div-recursos-extra").hide();
                $("#div-recursos-extra").data('visible', 0);
            } else {
                $("#div-recursos-extra").show();
                $("#div-recursos-extra").data('visible', 1);
            }
        },
      
    },
      verificar_pre_requisito: function(start, end,r) {

            $.ajax({
                type: 'get',
                url: URL.set_pre_requisito,
                async : true,
                data: {
                    a: start,
                    b: end,
                    tipo : r.checked
                },
                success: function(data) {
                    //alert(data);
                    if(data==0){
                        alertify.alert('No se puede establecer una relación entre estos modulos');
                        r.checked = false;
                    }
                }
            });


        }
       


}