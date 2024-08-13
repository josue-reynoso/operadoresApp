window.onload = () => {

    var html5QrcodeScanner;
    $('#scannProd').on('hidden.bs.modal', function(e) {
        html5QrcodeScanner.stop();
    })
    document.getElementById("scannCodePI").addEventListener("click", async function() {
        try {
            html5QrcodeScanner.stop();
        } catch (error) {

        }

        html5QrcodeScanner = new Html5Qrcode("reader");
        const config = { fps: 5, qrbox: { width: 200, height: 200 } };

        // html5QrcodeScanner.render(onScanSuccess, onScanError);
        await html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess, onScanError);
        console.log("Cambio de tamaño");
        $("#reader>video").css("width", "100%");
    });

    function onScanError(errorMessage) {
        console.log(errorMessage);
    }

    async function onScanSuccess(decodedText, decodedResult) {
        html5QrcodeScanner.stop();

        console.log(`Code scanned = ${decodedText}`, decodedResult);

        document.getElementById("codigoBar").value = decodedText;

        await getInfoProd(decodedText);

    }

    let buttonSendStock = document.getElementById("enviarPedido");

    if (buttonSendStock)
        buttonSendStock.addEventListener("click", async function() {
            let idPed = document.getElementById("idPed").value;

            let peFch = document.getElementById("PE_FchEnv").value;
            let peC = document.getElementById("PE_CO_ID").value;
            let peNT = document.getElementById("PE_NroTra").value;
            console.log(peFch, peC, peNT);
            if (peFch == "" || peC == "" || peNT == "") {
                $("#error_alert").show();
                $("#error_message").html(messagesj.fecha_num_guia_paqueteria_requeridos);

                return;
            }

            Swal.fire({
                title: messagesj.confirme_envio_pedido,
                input: 'text',
                inputPlaceholder: messagesj.ingrese_contraseña,
                inputLabel: messagesj.contrasena,
                inputAttributes: {
                    autocapitalize: 'off',
                    autocomplete: 'off'
                },
                showCancelButton: true,
                confirmButtonText: messagesj.confirme_envio_pedido,
                cancelButtonText: messagesj.cancelar,
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    console.log("preconfirm");

                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        'title': messagesj.enviando,
                        'html': messagesj.espere_por_favor,
                        'allowOutsideClick': false,
                        'didOpen': () => {
                            Swal.showLoading();
                            console.log("Iniciar fetch");
                            //fetch ajax request

                            fetch(`${urlSendPedido}/${idPed}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    "pws": result.value,
                                    "PE_FchEnv": document.getElementById("PE_FchEnv").value,
                                    "PE_CO_ID": document.getElementById("PE_CO_ID").value,
                                    "PE_NroTra": document.getElementById("PE_NroTra").value,
                                    "PE_Com": document.getElementById("PE_Com").value,
                                })
                            }).then(result => result.json()).then(response => {
                                Swal.close();
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })


                                // if (response.status == 200) {
                                if (response.status) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: response.message
                                    }).then(r => location.href = pedidos);
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: response.message
                                    })
                                }
                                // } else {
                                //     Toast.fire({
                                //         icon: 'error',
                                //         title: 'Ha ocurrido un error de conexión'
                                //     })
                                // }
                            })
                        }
                    });
                }
            });
        });

    async function getInfoProd(code) {
        let lineaPedido = document.getElementById("PE_Tip").value;
        let url;

        if (lineaPedido == 1) {
            url = urlInfoProducto + "/" + code;
        } else {
            url = urlInfoInsumo + "/" + code;
        }

        let response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).then(res => res.json());

        if (!response.success) {
            Swal.fire({
                icon: 'error',
                title: messagesj.advertencia,
                text: response.message
            });
            document.getElementById("codigoBar").value = "";

            return;
        }

        let pedido;
        if (lineaPedido == 1) {
            let data = response.producto;

            pedido = {
                "codigo": data.PS_ID,
                "nombre": data.tipoProducto.TP_Nom,
                "cantidad": 1,
                "tipo": data.tipoProducto.TP_Tip,
                "descripcion": data.tipoProducto.TP_Des,
                "producto": data.tipoProducto.TP_ID
            };
            document.getElementById("guardarScan").setAttribute("data-prod", JSON.stringify(pedido))

        } else {
            let data = response.insumo;

            pedido = {
                "codigo": data.IN_ID,
                "nombre": data.tipoInsumo.TI_Nom,
                "cantidad": 1,
                "tipo": data.tipoInsumo.TI_Tip,
                "descripcion": data.tipoInsumo.TI_Des,
                "producto": data.tipoInsumo.TI_ID
            };
            document.getElementById("guardarScan").setAttribute("data-prod", JSON.stringify(pedido))
        }
        //${()} 
        document.getElementById("TipoPedido").textContent = pedido.tipo == 1 || pedido.tipo == 0 ? messagesj.producto : messagesj.insumo;
        document.getElementById("NombrePed").textContent = pedido.nombre;
        document.getElementById("DescPed").textContent = pedido.descripcion;
        document.getElementById("DirPed").textContent = response.producto.casillero.CA_Nom;
        document.getElementById("CasPed").textContent = response.producto.direccion.DI_Nom;

    }

    document.getElementById("guardarScan").addEventListener("click", async function() {
        console.log(JSON.parse(this.getAttribute("data-prod")));
        let pedido = JSON.parse(this.getAttribute("data-prod"));

        document.getElementById("TipoPedido").textContent = "";
        document.getElementById("NombrePed").textContent = "";
        document.getElementById("DescPed").textContent = "";
        document.getElementById("DirPed").textContent = "";
        document.getElementById("CasPed").textContent = "";
        document.getElementById("guardarScan").removeAttribute("data-prod")
        document.getElementById("codigoBar").value = "";

        let productos = localStorage.getItem("productos");
        let productos_array = JSON.parse(productos);

        var i = -1;
        if (productos_array != undefined)
            var find = await productos_array.find(function(item, index) {
                if (item.codigo == pedido.codigo) {
                    i = index;
                    return item;
                }
            });



        if (pedido != undefined && i == -1) {
            generateTableSelected(
                pedido
            );
        } else {
            Swal.fire({
                icon: 'error',
                title: messagesj.advertencia,
                text: i > -1 ? messagesj.producto_ya_seleccionado : messagesj.producto_no_existe
            });
        }
    })




    async function generateTableSelected(values) {
        let productos = localStorage.getItem("productos");
        let productos_array = JSON.parse(productos);

        let tipoPedido = document.getElementById("PE_Tip").value == 1 ? messagesj.productos : messagesj.insumos;

        if (productos_array == null) {
            productos_array = [];
        }
        console.log(productos_array);

        var i = -1;
        var find = await productos_array.find(function(item, index) {
            if (item.codigo == values.codigo) {
                i = index;
                return item;
            }
        });



        if (productos_array.length > 0 && i > -1) {
            productos_array[i]["cantidad"] = values.cantidad;
        } else {
            productos_array.push({
                "codigo": values.codigo,
                "nombre": values.nombre,
                "cantidad": values.cantidad,
                "tipo": values.tipo,
                "descripcion": values.descripcion,
                "producto": values.producto
            });
        }


        localStorage.setItem("productos", JSON.stringify(productos_array));

        $("#tinsumosSelect").val(JSON.stringify(productos_array));


        $("#tinsumoselected tbody").empty();
        if (productos_array.length == 0) {
            //$('#guardar').prop('disabled', true);
            //$('#guardar').css('pointer-events', 'none');
            $("#error_selected_product").show();

        } else {
            //$('#guardar').prop('disabled', false);
            //$('#guardar').css('pointer-events', 'auto');
            $("#error_selected_product").hide();

        }
        validateForm();
        productos_array.forEach(function(producto) {

            console.log(producto);

            $("#tinsumoselected tbody").append(`
            <tr>
                <td>
                    ${tipoPedido}
                </td>
                <td>
                    ${(producto.tipo == 1 || producto.tipo == 0 ? (producto.tipo == 1 ? messagesj.servicio : messagesj.producto) : messagesj.insumo)}      
                </td>
                <td>
                    ${producto.codigo}
                </td>
                <td>
                    ${producto.nombre}
                </td>
                <td>
                    ${producto.descripcion}
                </td>
                <td>
                    ${producto.cantidad}
                </td>
                <td>
                    <a class="btn btn-danger removeTinsumo" data-codigo="${producto.codigo}">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        `);
        });
    }


    $(document).on("change", "#PE_MB_ID", function() {
        var tipo = $(this).val();
        var url = urlInfoMB + "/" + tipo;

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

                    document.getElementById("PE_PA").value = data.pais;
                    document.getElementById("PE_Ciudad").value = data.ciudad;
                    document.getElementById("PE_Prov").value = data.provincia;
                    document.getElementById("PE_Dir1").value = data.MB_DirLin1;
                    document.getElementById("PE_Dir2").value = data.MB_DirLin2;
                    document.getElementById("PE_CP").value = data.MB_CodPos;

                }
            ).catch(
                error => {
                    document.getElementById("PE_PA").value = "";
                    document.getElementById("PE_Ciudad").value = "";
                    document.getElementById("PE_Prov").value = "";
                    document.getElementById("PE_Dir1").value = "";
                    document.getElementById("PE_Dir2").value = "";
                    document.getElementById("PE_CP").value = "";
                }
            );
    })

    $(document).on("change", "#CA_ID", function() {
        var id = $(this).val();
        var url = urlDireccion + "/" + id;

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
                    $("#DI_ID").val(data.DI_Nom);
                }
            );
    })

    // $(".addTinsumo").click(function(){
    $(document).on("click", ".addTinsumo", async function() {


        let tipoPedido = document.getElementById("PE_Tip").value == 1 ? messagesj.productos : messagesj.insumos;
        let codigo = $(this).attr("data-codigo");
        let nombre = $(this).attr("data-nombre");
        let cantidad = $(this).attr("data-cantidad");
        let tipo = $(this).attr("data-tipo");
        let descripcion = $(this).attr("data-description");
        let producto = $(this).attr("data-tproducto");



        generateTableSelected({
            "codigo": codigo,
            "nombre": nombre,
            "cantidad": cantidad,
            "tipo": tipo,
            "descripcion": descripcion,
            "producto": producto
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
                    [${codigo}] - ${nombre}    
                </td>
                <td>
                    ${tipo}
                </td>
                <td>
                    <div class="form-group">
                      <input type="number" class="form-control changeCantidad" value="${cantidad}" data-id="${codigo}" readonly>
                      <small id="helpId" class="text-muted">${messagesj.cantidad_requerida}</small>messagesj
                    </div>
                </td>
                <td>
                    <a class="btn btn-primary addTinsumo" data-dismiss="modal" id="${codigo}" data-cantidad="${cantidad}" data-insumo="${insumo}"  data-codigo="${codigo}" data-nombre="${nombre}" data-tipo="${tipo}">
                        <i class="fas fa-plus"></i>
                    </a>
                </td>
            </tr>
        `);
    })

    $(document).on("change", ".changeCantidad", function() {
        let id = $(this).attr("data-id");

        $("#" + id).attr("data-cantidad", $(this).val());
    })

    // $(".removeTinsumo").click(function(){
    $(document).on("click", ".removeTinsumo", async function() {
        // $(this).parent().parent().remove();
        // console.log($(this).attr("data-codigo"));
        let tipoPedido = document.getElementById("PE_Tip").value == 1 ? messagesj.productos : messagesj.insumos;
        let codigo = $(this).attr("data-codigo");
        let productos = localStorage.getItem("productos");
        let productos_array = JSON.parse(productos);
        let insumo;
        let i;

        let array = productos_array.find(async function(item, index) {
            console.log(item);
            if (item.codigo == codigo) {
                insumo = item.insumo;
                i = index;
                return item;
            }
        });

        productos_array.splice(i, 1);

        localStorage.setItem("productos", JSON.stringify(productos_array));

        $("#tinsumosSelect").val(JSON.stringify(productos_array));
        $("#tinsumoselected tbody").empty();

        if (productos_array.length == 0) {
            //$('#guardar').prop('disabled', true);
            //$('#guardar').css('pointer-events', 'none');
            $("#error_selected_product").show();

        } else {
            //$('#guardar').prop('disabled', false);
            //$('#guardar').css('pointer-events', 'auto');
            $("#error_selected_product").hide();
        }
        validateForm();
        productos_array.forEach(function(producto) {

            $("#tinsumoselected tbody").append(`
            <tr>
                <td>
                    ${tipoPedido}
                </td>
                <td>
                    ${(producto.tipo == 1 || producto.tipo == 0 ? (producto.tipo == 1 ? messagesj.servicio : messagesj.producto) : messagesj.insumo)}    
                </td>
                <td>
                    ${producto.codigo}
                </td>
                <td>
                    ${producto.nombre}
                </td>
                <td>
                    ${producto.descripcion}
                </td>
                <td>
                    ${producto.cantidad}
                </td>
                <td>
                    <a class="btn btn-danger removeTinsumo" data-codigo="${producto.codigo}">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        `);
        });
    });

    document.getElementById("PE_Tip").addEventListener("change", function() {
        localStorage.removeItem("productos");
        $("#tinsumoselected tbody").empty();

        if (this.value == 1) {
            document.getElementById("form_pedidos_insumos_productos").action = urlSavePedidoProd;
        } else {
            document.getElementById("form_pedidos_insumos_productos").action = urlSavePedidoIns;
        }
    });

    let showInsumosBtn = document.getElementById("showInsumos");
    if (showInsumosBtn != null)
        showInsumosBtn.addEventListener("click", function() {
            let tipoPedido = document.getElementById("PE_Tip").value == 1 ? messagesj.productos : messagesj.insumos;
            console.log(tipoPedido);
            if (tipoPedido == messagesj.productos) getProducts();
            else getInsumos();

        });

    function getInsumos() {

        let url = urlGetInsumos;
        $("#insumoTable tbody").empty();

        fetch(
            url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
        ).then(
            response => response.json()
        ).then(
            async data => {
                // console.log(data);
                // return;
                if (('success' in data)) {
                    Swal.fire("Error", data.message, "error").then(() => {});
                    return;
                }

                let productos = localStorage.getItem("productos");
                let productos_array = JSON.parse(productos);

                //Genera un foreach para data
                await data.forEach(async function(producto) {
                    let cantidad = 1;

                    var find;

                    if (productos_array != null) {

                        find = await productos_array.find(function(item, index) {

                            if (item.codigo == producto.IN_ID) {
                                return item;
                            }
                        });

                        if (find != null && find["codigo"] != undefined && producto.IN_ID == find["codigo"]) {
                            cantidad = find["cantidad"];
                        }
                    }

                    if (find == null || find["codigo"] == undefined)
                        $("#insumoTable tbody").append(
                            `<tr>
                            <td>
                                ${messagesj.insumos}
                            </td>
                            <td>
                                [${producto.tipoInsumo.TI_ID}] - ${producto.tipoInsumo.TI_Nom + " " + producto.tipoInsumo.TI_Mar}
                            </td>
                            <td>
                                ${producto.IN_ID}
                            </td>
                            <td>
                                ${producto.tipoInsumo.TI_Nom + " " + producto.tipoInsumo.TI_Mar + " " + producto.tipoInsumo.TI_Mod}
                            </td>
                            <td>
                                [${producto.tipoInsumo.TI_FabOpe}] - ${producto.tipoInsumo.TP_Des}
                            </td>
                            <td>
                                ${cantidad}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm addTinsumo" data-dismiss="modal" data-codigo="${producto.IN_ID}" data-description="${producto.tipoInsumo.TI_Des}" data-tproducto="${producto.tipoInsumo.TI_ID}" data-cantidad="${cantidad}" data-nombre="${producto.tipoInsumo.TI_Nom}" data-tipo="${producto.tipoInsumo.TI_Tip}">
                                    <i class="fas fa-plus"></i>
                                    ${messagesj.agregar}    
                                </button>
                            </td>
                        </tr>`
                        );
                });
            }
        );
    }

    function getProducts() {
        let url = urlGetProducts;
        $("#insumoTable tbody").empty();
        // localStorage.removeItem("productos");

        fetch(
            url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }
            }
        ).then(
            response => response.json()
        ).then(
            async data => {
                console.log(data);
                if (('success' in data)) {
                    Swal.fire("Error", data.message, "error").then(() => {});
                    return;
                }

                let productos = localStorage.getItem("productos");
                let productos_array = JSON.parse(productos);

                //Genera un foreach para data
                await data.forEach(async function(producto) {
                    let cantidad = 1;

                    var find;

                    if (productos_array != null) {

                        find = await productos_array.find(function(item, index) {

                            if (item.codigo == producto.PS_ID) {
                                return item;
                            }
                        });

                        if (find != null && find["codigo"] != undefined && producto.PS_ID == find["codigo"]) {
                            cantidad = find["cantidad"];
                        }
                    }

                    if (find == null || find["codigo"] == undefined)
                        $("#insumoTable tbody").append(
                            `<tr>
                            <td>
                                ${messagesj.productos}
                            </td>
                            <td>
                                [${producto.tipoProducto.TP_ID}] - ${producto.tipoProducto.TP_Nom}
                            </td>
                            <td>
                                ${producto.PS_ID}
                            </td>
                            <td>
                                ${producto.tipoProducto.TP_Nom}
                            </td>
                            <td>
                                ${producto.tipoProducto.TP_Des}
                            </td>
                            <td>
                                ${cantidad}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm addTinsumo" data-dismiss="modal" data-codigo="${producto.PS_ID}" data-description="${producto.tipoProducto.TP_Des}" data-tproducto="${producto.tipoProducto.TP_ID}" data-cantidad="${cantidad}" data-nombre="${producto.tipoProducto.TP_Nom}" data-tipo="${producto.tipoProducto.TP_Tip}">
                                    <i class="fas fa-plus"></i>
                                    ${messagesj.agregar}    
                                </button>
                            </td>
                        </tr>`
                        );
                });
            }
        );
    }

    localStorage.removeItem("productos");
    localStorage.removeItem("controlStock")
    localStorage.removeItem("stockNeed")
    let bajaBTN = document.getElementById("bajaTP");
    if (bajaBTN != null)
        bajaBTN.addEventListener("click", function() {

            let motivo = document.getElementById("PS_MotBaj").value;

            if (motivo == "") {
                Swal.fire("Error", messagesj.no_establecido_motivo, "error");
                return;
            }

            let url = urlDeleteProd + "/" + document.getElementById("PS_ID").value + "/" + motivo;

            Swal.fire({
                title: messagesj.desea_dar_de_baja + ' ' + messagesj.pedido + ': ' + idPedido + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
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
                            Swal.fire("Exito", data.message, "success").then(() => location.href = urlRedirect);
                        } else {
                            Swal.fire("Error", data.message, "error");
                        }
                    });
                }
            })
        });
    // 

}