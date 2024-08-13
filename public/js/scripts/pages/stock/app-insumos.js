$("#error_code").css("display", "none");
$("#error_phone").css("display", "none");
$("#error_imei").css("display", "none");

if (document.getElementById("bajaInsumo")) {
    document.getElementById("bajaInsumo").addEventListener("click", function() {

        let motivo = document.getElementById("baja").value;

        if (motivo == "") {
            // Swal.fire("Error", "Debe ingresar un motivo de baja", "error");
            $("#error_message").text(messagesj.no_establecido_motivo);
            $("#error_alert").removeClass("alert-warning");
            $("#error_alert").removeClass("alert-danger");
            $("#error_alert").addClass("alert-danger");
            $("#error_alert").fadeIn();

            window.setTimeout(function() {
                $("#error_alert").fadeOut();
            }, 4000);
            return;
        }

        let url = urlInsumoDelete + "/" + document.getElementById("IN_ID").value + "/" + motivo;

        Swal.fire({
            title: messagesj.desea_dar_de_baja + ' ' + messagesj.insumo + ': ' + idInsumo,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: messagesj.aceptar,
            cancelButtonText: messagesj.cancelar
        }).then((result) => {
            if (result.isConfirmed) {
                //Genera una peticion ajax de tipo get con el metodo fetch
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then((response) =>
                    response.json()
                ).then(function(data) {
                    if (data.success) {
                        // Swal.fire("Exito", data.message, "success").then(() => location.href = urlInsumosPage);
                        $("#error_message").text(data.message);
                        $("#error_alert").removeClass("alert-warning");
                        $("#error_alert").removeClass("alert-danger");
                        $("#error_alert").addClass("alert-success");
                        $("#error_alert").fadeIn();

                        window.setTimeout(function() {
                            $("#error_alert").fadeOut();
                            location.href = urlInsumosPage
                        }, 2500);
                    } else {
                        // Swal.fire("Error", data.message, "error");
                        $("#error_message").text(data.message);
                        $("#error_alert").removeClass("alert-warning");
                        $("#error_alert").removeClass("alert-danger");
                        $("#error_alert").addClass("alert-danger");
                        $("#error_alert").fadeIn();

                        window.setTimeout(function() {
                            $("#error_alert").fadeOut();
                        }, 4000);
                        return;
                    }
                });
            }
        })
    });
}

document.getElementById("IN_ID").addEventListener("keyup", waitResponse("IN_ID", urlCode, document.getElementById("id").value));

document.getElementById("IN_LinTel").addEventListener("keyup", waitResponse("IN_LinTel", urlPhone, document.getElementById("id").value));

//document.getElementById("IN_IMEI").addEventListener("keyup", waitResponse("IN_IMEI", urlIMEI, document.getElementById("id").value));

// document.getElementById("IN_LinTel").addEventListener("keyup", function() {
//     let url = urlPhone + "/" + document.getElementById("IN_LinTel").value + "/" + document.getElementById("id").value;

//     fetch(url, {
//         method: 'GET',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     }).then((response) =>
//         response.json()
//     ).then(function(data) {
//         if (data.success) {
//             $("#error_phone").css("display", "none");
//             $("#guardar").attr("disabled", false);

//         } else {
//             $("#error_phone").css("display", "block");

//             $("#guardar").attr("disabled", true);
//         }
//     });
// });

$("#IN_SR_ID").on('change', function() {
    var a = $("#IN_SR_ID").val()
    if (a == '' || a == null) {
        $('#guardar').prop('disabled', true);
        $('#guardar').css('pointer-events', 'none');
    } else {
        validateForm();
    }
});

document.getElementById("tipo").addEventListener("change", function() {
    var tipo = document.getElementById("tipo").value;
    var url = urlInsumo + "/" + tipo;
    //Generar una peticion Ajax con javascript con fetch
    fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
        }).then(response => response.json())
        .then(data => {;
            document.getElementById("TI_Nom").value = data.TI_Nom;
            document.getElementById("TI_Des").value = data.TI_Des;
            document.getElementById("TI_Mar").value = data.TI_Mar;
            document.getElementById("TI_Mod").value = data.TI_Mod;
            document.getElementById("TI_FabOpe").value = data.TI_FabOpe;

            if (data.TI_Tip == 'Tracker') {
                $("#col_in_sr_id").show();

                $("#IN_SR_ID").attr("required", true);
                validateForm();

            } else {

                $("#col_in_sr_id").hide();
                $("#IN_SR_ID").removeAttr('required');
                validateForm();

            }
            validateForm();
            if (data.TI_Tip.includes("Chip")) {
                document.getElementById("IN_LinTel").removeAttribute('readonly');
                document.getElementById("IN_LinTel").setAttribute('required', 'required');
            } else {
                document.getElementById("IN_LinTel").setAttribute('readonly', 'readonly');
                document.getElementById("IN_LinTel").removeAttribute('required');

                document.getElementById("IN_LinTel").value = "";
            }
            if (data.TI_Tip.includes("Tracker")) {
                document.getElementById("IN_IMEI").removeAttribute('readonly');
                document.getElementById("IN_IMEI").setAttribute('required', 'required');
            } else {
                document.getElementById("IN_IMEI").setAttribute('readonly', 'readonly');
                document.getElementById("IN_IMEI").removeAttribute('required');

                document.getElementById('error_imei_info').innerHTML = '';
                document.getElementById('success_imei_info').innerHTML = '';
                document.getElementById("IN_IMEI").value = "";
            }


        }).catch(error => console.log(error));
});

document.getElementById("DI_ID").addEventListener("change", function() {
    var id = document.getElementById("DI_ID").value;
    if (typeof urlCasillero === 'undefined') return;
    var url = urlCasillero + "/" + id;
    //Generar una peticion Ajax con javascript con fetch
    fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
        }).then(response => response.json())
        .then(data => {
            document.getElementById("CA_ID").options.length = 1;

            data.forEach(function(casillero) {
                var option = document.createElement("option");
                option.text = casillero.CA_Nom;
                option.value = casillero.CA_ID;
                document.getElementById("CA_ID").add(option);
            });
        }).catch(error => console.log(error));
});