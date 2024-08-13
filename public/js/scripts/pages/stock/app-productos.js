$("#error_code").css("display", "none");

window.onload = () => {
    let servicio = 0;
    $('#guardar').prop('disabled', true);
    $('#guardar').css('pointer-events', 'none');

    if (typeof document.getElementById("restoreTP") != 'undefined' && document.getElementById("restoreTP") != null) {
        document.getElementById("restoreTP").setAttribute("disabled", "disabled");
        document.getElementById("restoreTP").style['pointer-events'] = 'none';
    }

    document.getElementById("productsForm").addEventListener("submit", async(e) => {
        let form = document.getElementById("productsForm")
        e.preventDefault();

        if (form.checkValidity() === false) {
            e.stopPropagation();
            form.classList.add('was-validated');
            $('#guardar').prop('disabled', true);
            $('#guardar').css('pointer-events', 'none');
            return;
        } else {
            $('#guardar').prop('disabled', false);
            $('#guardar').css('pointer-events', 'auto');
        }

        let cantinsumoNeed = JSON.parse(localStorage.getItem("stockNeed"));

        let isAllOk = true;

        if (action == "" && servicio == 0)
            await cantinsumoNeed.forEach(async(cantNeed) => {
                if (cantNeed["cantProd"] > cantNeed["cantUser"]) isAllOk = false;
            });


        if (isAllOk) {
            document.productForm.submit();
        } else {
            $("#error_message").text(messagesj.faltan_insumos_param_armar_producto);
            $("#error_alert").removeClass("alert-warning");
            $("#error_alert").removeClass("alert-danger");
            $("#error_alert").addClass("alert-danger");
            $("#error_alert").fadeIn();

            window.setTimeout(function() {
                $("#error_alert").fadeOut();
            }, 4000);
        }
    });

    let inputs = document.getElementsByTagName('input');
    let select = document.getElementsByTagName('select');

    for (let i = 0; i < select.length; i++) {
        console.log(select.length);
        select[i].addEventListener('change', function() {
            validateForm();
        });
    }

    for (let i = 0; i < inputs.length; i++) {
        console.log(inputs.length);
        inputs[i].addEventListener('keyup', function() {
            validateForm();
        });
    }
    var faltan2 = true;

    function validateForm() { //insumos_faltantes

        let form = document.getElementById("productsForm")
        let valHiddenId = document.getElementById("id")
        console.log('----' + faltan2 + '::' + valHiddenId.value);
        form.classList.add('was-validated');
        if (form.checkValidity() === false || (faltan2 && valHiddenId.value == '')) {
            $('#guardar').prop('disabled', true);
            $('#guardar').css('pointer-events', 'none');
            return;
        } else {
            $('#guardar').prop('disabled', false);
            $('#guardar').css('pointer-events', 'auto');
        }

        if (typeof document.getElementById('restoreTP') != 'undefined' && document.getElementById('restoreTP') != null) {
            if (form.checkValidity() === false) {
                document.getElementById('restoreTP').setAttribute('disabled', '');
                document.getElementById('restoreTP').style['pointer-events'] = 'none';
            } else {
                document.getElementById('restoreTP').removeAttribute('disabled');
                document.getElementById('restoreTP').style['pointer-events'] = 'auto';
            }
        }
    }

    document.getElementById("PS_ID").addEventListener("keyup", waitResponse("PS_ID", urlCode, document.getElementById("id").value));
    // function() {
    //     let url = urlCode + "/" + document.getElementById("PS_ID").value + "/" + document.getElementById("id").value;

    //     fetch(url, {
    //         method: 'GET',
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     }).then((response) =>
    //         response.json()
    //     ).then(function(data) {
    //         if (data.success) {
    //             $("#error_code").css("display", "none");
    //             $("#guardar").attr("disabled", false);

    //         } else {
    //             $("#error_code").css("display", "block");

    //             $("#guardar").attr("disabled", true);
    //         }
    //     });
    // });

    if (document.getElementById("DI_ID") != undefined && document.getElementById("DI_ID") != null) {
        document.getElementById("DI_ID").addEventListener("change", function() {
            var id = document.getElementById("DI_ID").value;
            if (typeof urlCasillero === 'undefined') return;
            var url = urlCasillero + "/" + id;
            console.log(url);
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
    }
    $(document).on("change", "#TP_Tip", function() {
        changeEvent();
    })

    // $(".addTinsumo").click(function(){
    $(document).on("click", ".addTinsumo", async function() {



        let codigo = $(this).attr("data-codigo");
        let nombre = $(this).attr("data-nombre");
        let cantidad = $(this).attr("data-cantidad");
        let tipo = $(this).attr("data-tipo");
        let insumo = $(this).attr("data-insumo");
        let insumosStock = JSON.parse(localStorage.getItem("controlStock"));
        let cantinsumoNeed = JSON.parse(localStorage.getItem("stockNeed"));
        console.log(insumo);

        let count;

        var find = await cantinsumoNeed.find(function(item, index) {
            if (item.id == insumo) {
                count = index;
                return item;
            }
        });

        if (cantinsumoNeed[count]["cantProd"] == cantinsumoNeed[count]["cantUser"]) {
            // Swal.fire(
            //     "Error",
            //     "Se han seleccionado los insumos suficientes del tipo " + tipo,
            //     "error"
            // );

            $("#error_message").text(messagesj.insumos_suficientes_seleccionados + ": " + tipo);
            $("#error_alert").removeClass("alert-warning");
            $("#error_alert").removeClass("alert-danger");
            $("#error_alert").addClass("alert-warning");
            $("#error_alert").fadeIn();

            window.setTimeout(function() {
                $("#error_alert").fadeOut();
            }, 4000);
            return;
        }
        validateForm();
        let insumos = localStorage.getItem("Tinsumos");
        let insumos_array = JSON.parse(insumos);


        if (insumos_array == null) {
            insumos_array = [];
        }
        //console.log(insumos_array);

        var i = -1;
        var find = await insumos_array.find(function(item, index) {
            if (item.codigo == codigo) {
                i = index;
                return item;
            }
        });

        if (insumos_array.length > 0 && i > -1) {
            insumos_array[i]["cantidad"] = cantidad;
        } else {
            insumos_array.push({
                "codigo": codigo,
                "nombre": nombre,
                "cantidad": cantidad,
                "tipo": tipo,
                "insumo": insumo
            });
        }


        localStorage.setItem("Tinsumos", JSON.stringify(insumos_array));

        $("#tinsumosSelect").val(JSON.stringify(insumos_array));



        cantinsumoNeed[count]["cantUser"] += 1;

        localStorage.setItem("stockNeed", JSON.stringify(cantinsumoNeed));

        $("#cantNeed").val(JSON.stringify(cantinsumoNeed));

        let faltantes = false;
        $("#insumos_faltantes").html("");
        cantinsumoNeed.forEach((elementSN, indexSN) => {
            if ((elementSN.cantProd - elementSN.cantUser) > 0) {
                faltantes = true;
                console.log("Aun faltan");
                faltan2 = true;
                console.log('+++' + faltan2);
                $("#insumos_faltantes").append(`<span class="badge badge-warning text-dark ml-1">${messagesj.requerido_seleccionar} ${elementSN.cantProd - elementSN.cantUser} ${messagesj.insumo} "${elementSN.tipo}"</span>`);
            }

            if (cantinsumoNeed.length - 1 == indexSN && !faltantes) {
                faltan2 = false;
                console.log('////' + faltan2);
                console.log("complete....");
                $("#insumos_faltantes").append(`<span class="badge badge-success ml-1">${messagesj.insumos_necesarios_seleccionados}</span>`);
            }
            validateForm();
        });


        $("#tinsumoselected tbody").empty();
        insumos_array.forEach(function(insumo) {
            console.log(insumo);
            $("#tinsumoselected tbody").append(`
                <tr>
                    <td>
                        ${insumo.nombre}    
                    </td>
                    <td>
                        ${insumo.tipo}
                    </td>
                    <td>
                        ${insumo.codigo}
                    </td>
                    <td>
                        ${insumo.cantidad}
                    </td>
                    <td>
                        <a class="btn btn-secondary removeTinsumo" data-codigo="${insumo.codigo}">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    });

    // $(".selectinsumo").click(function() {
    $(document).on("click", ".selectinsumo", function() {
        // print("Hola mundo");
        // console.log("object");
        let codigo = $(this).attr("data-id");
        let nombre = $(this).attr("data-nombre");
        let cantidad = $(this).attr("data-cantidad");
        let tipo = $(this).attr("data-tipo");
        let insumo = $(this).attr("data-tinsumo");
        $("#insumoTableSelect tbody").empty();

        $("#insumoTableSelect tbody").append(`
                <tr>
                    <td>
                        ${nombre}    
                    </td>
                    <td>
                        ${tipo}
                    </td>
                    <td>
                        ${codigo}
                    </td>
                    <td>
                        ${cantidad}
                    </td>
                    <td>
                        <a class="btn btn-primary addTinsumo" data-dismiss="modal" id="${codigo}" data-cantidad="${cantidad}" data-insumo="${insumo}"  data-codigo="${codigo}" data-nombre="${nombre}" data-tipo="${tipo}">
                            <i class="fas fa-plus"></i>
                        </a>
                    </td>
                </tr>
            `);
    })

    // $(".changeCantidad").change(function() {
    $(document).on("change", ".changeCantidad", function() {
        let id = $(this).attr("data-id");

        $("#" + id).attr("data-cantidad", $(this).val());
    })

    // $(".removeTinsumo").click(function(){
    $(document).on("click", ".removeTinsumo", async function() {
        // $(this).parent().parent().remove();
        // console.log($(this).attr("data-codigo"));
        let codigo = $(this).attr("data-codigo");
        let insumos = localStorage.getItem("Tinsumos");
        let insumos_array = JSON.parse(insumos);
        let insumosStock = JSON.parse(localStorage.getItem("controlStock"));
        let cantinsumoNeed = JSON.parse(localStorage.getItem("stockNeed"));
        let insumo;
        let i;

        let array = await insumos_array.find(function(item, index) {
            console.log(item);
            if (item.codigo == codigo) {
                insumo = item.insumo;
                i = index;
                return item;
            }
        });

        insumos_array.splice(i, 1);

        console.log(insumo, i);

        let indice;
        var find = await cantinsumoNeed.find(function(item2, index) {

            if (item2.id == insumo) {
                indice = index;
                return item2;
            }
        });

        cantinsumoNeed[indice]["cantUser"] -= 1;

        if (cantinsumoNeed[indice]["cantUser"] < 0) cantinsumoNeed[indice]["cantUser"] = 0;
        // cantinsumoNeed[`${item.insumo}`]["cantUser"] -= 1;
        localStorage.setItem("stockNeed", JSON.stringify(cantinsumoNeed));

        $("#cantNeed").val(JSON.stringify(cantinsumoNeed));

        let faltantes = false;
        faltan2 = false;
        $("#insumos_faltantes").html("");
        cantinsumoNeed.forEach((elementSN, indexSN) => {
            if ((elementSN.cantProd - elementSN.cantUser) > 0) {
                faltantes = true;
                faltan2 = true;
                console.log('{{{{{' + faltan2);
                $("#insumos_faltantes").append(`<span class="badge badge-warning text-dark ml-1">${messagesj.requerido_seleccionar} ${elementSN.cantProd - elementSN.cantUser} ${messagesj.insumo} "${elementSN.tipo}"</span>`);
            }

            if (cantinsumoNeed.length - 1 == indexSN && !faltantes) $("#insumos_faltantes").append(`<span class="badge badge-success ml-1">>${messagesj.insumos_necesarios_seleccionados}</span>`);
        });
        validateForm();
        localStorage.setItem("Tinsumos", JSON.stringify(insumos_array));

        // $("#cantNeed").val(JSON.stringify(cantinsumoNeed));

        $("#tinsumosSelect").val(JSON.stringify(insumos_array));
        $("#tinsumoselected tbody").empty();
        insumos_array.forEach(function(insumo) {
            console.log(insumo);
            $("#tinsumoselected tbody").append(`
                <tr>
                    <td>
                        ${insumo.nombre}    
                    </td>
                    <td>
                        ${insumo.tipo}
                    </td>
                    <td>
                        ${insumo.codigo}
                    </td>
                    <td>
                        ${insumo.cantidad}
                    </td>
                    <td>
                        <a class="btn btn-secondary removeTinsumo" data-codigo="${insumo.codigo}">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    });

    let showInsumos = document.getElementById("showInsumos")
    if (showInsumos != null)
        document.getElementById("showInsumos").addEventListener("click", function() {
            let tproducto = document.getElementById("TP_Tip").value;
            let url = urlInsumos + "/" + tproducto;
            $("#insumoTable tbody").empty();
            $('#insumoTable').DataTable().clear().destroy();

            fetch(
                url, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                }
            ).then(
                response => response.json()
            ).then(
                async data => {

                    if (('success' in data)) {
                        // Swal.fire("Error", data.message, "error").then(() => {});
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

                    let insumos = localStorage.getItem("Tinsumos");
                    let insumos_array = JSON.parse(insumos);
                    console.log(localStorage.getItem("stockNeed"));

                    let insumosStock = JSON.parse(localStorage.getItem("controlStock"));
                    let cantinsumoNeed = JSON.parse(localStorage.getItem("stockNeed"));


                    //Genera un foreach para data
                    await data.forEach(async function(tinsumo, indexTI) {
                        let cantidad = 1;

                        var find;

                        if (insumos_array != null) {

                            find = await insumos_array.find(function(item, index) {
                                console.log("object")
                                console.log(tinsumo)
                                console.log(item)
                                console.log("object");
                                if (item.codigo == tinsumo.IN_ID) {
                                    return item;
                                }
                            });

                            if (find != null && find["codigo"] != undefined && tinsumo.IN_ID == find["codigo"]) {
                                cantidad = find["cantidad"];
                            }
                        }

                        console.log(find);
                        if (find == null || find["codigo"] == undefined)
                            $("#insumoTable tbody").append(
                                `<tr>
                                <td>
                                    ${tinsumo.TI_Nom}
                                </td>
                                <td>
                                    ${tinsumo.TI_Tip}
                                </td>
                                <td>
                                    ${tinsumo.IN_ID}
                                </td>
                                <td>
                                    ${tinsumo.DI_Nom}
                                </td>
                                <td>
                                    ${tinsumo.CA_Nom}
                                </td>
                                <td>
                                    ${cantidad}
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm addTinsumo" data-dismiss="modal" data-codigo="${tinsumo.IN_ID}" data-insumo="${tinsumo.TI_ID}" data-cantidad="${cantidad}" data-nombre="${tinsumo.TI_Nom}" data-tipo="${tinsumo.TI_Tip}">
                                        <i class="fas fa-plus"></i>
                                        ${messagesj.agregar}    
                                    </button>
                                </td>
                            </tr>`
                            );

                        if (indexTI >= data.length - 1) $('#insumoTable').DataTable();
                    });



                }
            );
        });

    function changeEvent() {
        var tipo = $("#TP_Tip").val();
        console.log(tipo);
        if (tipo == "") return;

        console.log(tipo);
        var url = urlInfoProduct + "/" + tipo;
        localStorage.removeItem("Tinsumos");
        localStorage.removeItem("controlStock")
        localStorage.removeItem("stockNeed")
        $("#insumos_faltantes").html("")

        //Genera una peticion ajax de tipo get usando fetch para obtener los datos del tipo de producto
        fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(
                response => response.json()
            ).then(
                data => {

                    if (data.TP_Tip == 1) {
                        $("#tableInsumosShow").hide();
                        $("#casilleroProducto").hide();
                        $("#CA_ID").attr('required', false);
                    } else {
                        $("#tableInsumosShow").show();
                        $("#casilleroProducto").show();
                        $("#CA_ID").attr('required', true);
                    }

                    console.log(data.insumos);
                    $("#insumoTable tbody").empty();
                    $("#tinsumoselected tbody").empty();
                    localStorage.removeItem("insumos");
                    $("#tinsumosSelect").val("");


                    $("#TP_Nom").val(data.TP_Nom);
                    $("#TP_Des").val(data.TP_Des);

                    stockNeed = [];
                    controlStock = [];
                    let enableButton = false;
                    servicio = 1;
                    data.insumos.forEach((element, index) => {
                        if (!element.cant) {
                            enableButton = true;

                            // Swal.fire(
                            //     "Error",
                            //     "Insumos insuficientes para la generación del producto",
                            //     "error"
                            // );
                            $("#error_message").text(messagesj.faltan_insumos_param_armar_producto);
                            $("#error_alert").removeClass("alert-warning");
                            $("#error_alert").removeClass("alert-danger");
                            $("#error_alert").addClass("alert-danger");
                            $("#error_alert").fadeIn();

                            window.setTimeout(function() {
                                $("#error_alert").fadeOut();
                            }, 4000);
                            //return;
                        }
                        console.log(element);
                        console.log(index);
                        // stockNeed.push({element["TI_ID"]: {
                        //             "cantProd": element["TPLIN_Can"],
                        //             "cantUser": 0 }
                        // });

                        stockNeed.push({
                            "id": element["TI_ID"],
                            "cantProd": element["TPLIN_Can"],
                            "cantUser": 0,
                            "tipo": element["tipo_insumo"],
                        });


                        controlStock.push({
                            "tipo": element["tipo_insumo"],
                            "cantidad": element["TPLIN_Can"],
                            "id": element["TI_ID"]
                        });

                        if (index == data.insumos.length - 1) {
                            // $("#guardar").attr("disabled", enableButton);

                            localStorage.setItem("controlStock", JSON.stringify(controlStock));
                            localStorage.setItem("stockNeed", JSON.stringify(stockNeed));

                            $("#cantNeed").val(JSON.stringify(stockNeed));
                            servicio = 0;
                            let faltantes = false;
                            stockNeed.forEach((elementSN, indexSN) => {
                                if ((elementSN.cantProd - elementSN.cantUser) > 0) {
                                    faltantes = true;
                                    $("#insumos_faltantes").append(`<span class="badge badge-warning text-dark ml-1">${messagesj.requerido_seleccionar} ${elementSN.cantProd - elementSN.cantUser} ${messagesj.insumo} "${elementSN.tipo}"</span>`);
                                }

                                if (stockNeed.length - 1 == indexSN && !faltantes) $("#insumos_faltantes").append(`<span class="badge badge-success ml-1">${messagesj.insumos_necesarios_seleccionados}</span>`);
                            });
                        }

                    });


                }
            );
    }

    localStorage.removeItem("Tinsumos");
    localStorage.removeItem("controlStock")
    localStorage.removeItem("stockNeed")

    let baja = document.getElementById("bajaTP");
    if (baja == null && showInsumos != null)
        changeEvent();
    // console.log(baja);
    if (baja != undefined)
        baja.addEventListener("click", function() {

            let motivo = document.getElementById("PS_MotBaj").value;

            if (motivo == "") {
                // Swal.fire("Error", "Debe ingresar un motivo de baja", "error");
                $("#error_message").text(messagesj.no_establecido_motivo);
                $("#error_alert").removeClass("alert-warning");
                $("#error_alert").removeClass("alert-danger");
                $("#error_alert").addClass("alert-warning");
                $("#error_alert").fadeIn();

                window.setTimeout(function() {
                    $("#error_alert").fadeOut();
                }, 4000);
                return;
            }

            let url = urlcreateProducto + "/" + document.getElementById("PS_ID").value + "/" + motivo;

            Swal.fire({
                title: messagesj.desea_dar_de_baja + ' ' + messagesj.producto + ': ' + idProducto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                input: 'text',
                inputLabel: messagesj.contrasena,
                inputAttributes: {
                    autocapitalize: 'off',
                    autocomplete: 'off'
                },
                confirmButtonText: messagesj.aceptar,
                cancelButtonText: messagesj.cancelar,
                inputValidator: (value) => {
                    if (!value) {
                        return messagesj.ingrese_contraseña
                    }
                },
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    //Genera una peticion ajax de tipo get con el metodo fetch
                    fetch(url + "/" + btoa(result.value), {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then((response) =>
                        response.json()
                    ).then(function(data) {
                        if (data.success) {
                            // Swal.fire("Exito", data.message, "success").then(() => location.href = urlProductos);
                            $("#error_message").text(data.message);
                            $("#error_alert").removeClass("alert-warning");
                            $("#error_alert").removeClass("alert-danger");
                            $("#error_alert").addClass("alert-success");
                            $("#error_alert").fadeIn();

                            window.setTimeout(function() {
                                $("#error_alert").fadeOut();
                                location.href = urlProductos
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


    let restaurar = document.getElementById("restoreTP");

    if (restaurar != undefined)
        restaurar.addEventListener("click", function() {
            let casillero = document.getElementById("CA_ID").value;
            if (casillero != '') {
                $('#CA_ID')[0].classList.remove('is-invalid')
                let url = urlrestoreProducto + "/" + document.getElementById("PS_ID").value + "/" + casillero;

                console.log(url)
                Swal.fire({
                    title: messagesj.restaurar_producto + ' ' + messagesj.producto + ': ' + idProducto,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    input: 'password',
                    inputLabel: 'Contraseña',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return messagesj.ingrese_contraseña
                        }
                    },
                }).then((result) => {
                    console.log(result);
                    if (result.isConfirmed) {
                        //Genera una peticion ajax de tipo get con el metodo fetch
                        fetch(url + "/" + btoa(result.value), {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).then((response) =>
                            response.json()
                        ).then(function(data) {
                            if (data.success) {
                                // Swal.fire("Exito", data.message, "success").then(() => location.href = urlProductos);
                                $("#error_message").text(data.message);
                                $("#error_alert").removeClass("alert-warning");
                                $("#error_alert").removeClass("alert-danger");
                                $("#error_alert").addClass("alert-success");
                                $("#error_alert").fadeIn();

                                window.setTimeout(function() {
                                    $("#error_alert").fadeOut();
                                    location.href = urlProductos
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
            } else {
                $('#CA_ID')[0].classList.add('is-invalid')
            }
        });



}