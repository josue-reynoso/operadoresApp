@if(count( json_decode($results->dataSet2), true) > 0)
@section('page-script2')
<script type="text/javascript">
    var dt2 = $('#resultsDataTable2');
    var aUrl2 = dt2[0].getAttribute("actionsUrl");

    $('#buscarForm2').submit((e) => {
        e.preventDefault();
        $('#buscarFormBtn2').click();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", '#buscarFormBtn2', () => {
        changeParams2();

        dt2.DataTable().clear().destroy()

        createDataTable2();
    })

    function changeParams2() {
        var unindexed_array = $("#buscarForm2").serializeArray();
        console.log(unindexed_array);
        indexed_array = {};

        $.map(unindexed_array, function(n, i) {
            indexed_array[n['name']] = n['value'];
        });
    }

    function createDataTable2() {
        console.log("object");
        var $columns = $('thead th:not(.dt-no-export)', dt2);

        let columns = [];

        $columns.each(function() {
            let column = {};
            column.data = columns.length;
            columns.push(column);
        });
        columns.push({
            data: columns.length
        });
        console.log(columns);


        dt2.DataTable({
            ajax: {
                url: $("#buscarForm2").attr("action"),
                type: "POST",
                data: indexed_array,
                dataSrc: 'data'
            },
            pageLength:50,
            columns: columns,
            // processing: true,
            // serverSide: true,
            // responsive: {
            //     details: {
            //         display: $.fn.dataTable.Responsive.display.modal({
            //             header: function ( row ) { return 'Detalles de la fila'; }
            //         }),
            //         renderer: $.fn.dataTable.Responsive.renderer.tableAll({ tableClass: 'table' })
            //     }
            // },
            language: {
                processing: "Procesando...",
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "Ningún dato disponible en esta tabla",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                search: "Buscar:",
                infoThousands: ",",
                loadingRecords: "Cargando...",
                buttonText: "Imprimir",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior",
                },
                buttons: {
                    copyTitle: 'Copiado al portapapeles',
                    copySuccess: {
                        _: '%d registros copiados',
                        1: 'Se copio un registro'
                    }
                },
            },
            dom: '<"d-flex justify-content-end"<"head-label"><"dt-action-buttons d-flex justify-content-end"B>><"d-flex justify-content-between align-items-center row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [{
                extend: 'collection',
                className: 'btn btn-outline-secondary dropdown-toggle',
                text: feather.icons['share'].toSvg({
                    class: 'font-small-4 mr-50'
                }) + 'Exportar',
                buttons: [{
                        extend: 'print',
                        text: feather.icons['printer'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'Imprimir',
                        className: 'dropdown-item',
                        title: '',
                        exportOptions: {
                            columns: $columns
                        }
                    },
                    {
                        extend: 'csv',
                        text: feather.icons['file-text'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'CSV',
                        className: 'dropdown-item',
                        title: '',
                        exportOptions: {
                            columns: $columns
                        }
                    },
                    {
                        extend: 'excel',
                        text: feather.icons['file'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'Excel',
                        className: 'dropdown-item',
                        title: '',
                        exportOptions: {
                            columns: $columns
                        }
                    },
                    {
                        extend: 'pdf',
                        text: feather.icons['clipboard'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'PDF',
                        className: 'dropdown-item',
                        title: '',
                        exportOptions: {
                            columns: $columns
                        }
                    },
                    {
                        extend: 'copy',
                        text: feather.icons['copy'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'Copiar',
                        className: 'dropdown-item',
                        title: '',
                        exportOptions: {
                            columns: $columns
                        }
                    }
                ],
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary');
                    $(node).parent().removeClass('btn-group');
                    setTimeout(function() {
                        $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                    }, 50);
                }
            }, ],
            columnDefs: [{
                // Actions
                targets: 0,
                searchable: false,
                title: '' + feather.icons['more-vertical'].toSvg({
                    class: 'font-small-4'
                }),
                orderable: false,
                render: function(data, type, full, meta) {
                    return (
                        '<div class="d-inline-flex dropright">' +
                        '<a href="#" class="dropdown-toggle hide-arrow text-primary" data-toggle="dropdown">' +
                        feather.icons['more-vertical'].toSvg({
                            class: 'font-small-4'
                        }) +
                        '</a>' +
                        '<div class="dropdown-menu dropdown-menu-right justify-content-center navbar-shadow">' +
                        '<a href="' + aUrl2 + '/edit/' + full[1] + '" class="dropdown-item">' +
                        feather.icons['edit'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'Editar' +
                        '</a>' +
                        '<a href="' + aUrl2 + '/delete/' + full[1] + '" class="dropdown-item">' +
                        feather.icons['trash-2'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'Eliminar' +
                        '</a>' +
                        '</div>' +
                        '</div>'
                    );
                }
            }],
        });

    }
    
    $(function() {
        'use strict';


    });
</script>
@endsection
@endif