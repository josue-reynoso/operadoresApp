{{-- @if(count( json_decode($results->dataSet), true) > 0) --}}
    @section('page-script')
    <script type="text/javascript">
        var dt = $('#resultsDataTable');
        var aUrl = dt[0].getAttribute("actionsUrl");
        var indexed_array = {};

        $('#buscarForm').submit( (e) => {
            e.preventDefault();
            $('#buscarFormBtn').click();
        } );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", '#buscarFormBtn', () => {
            changeParams();

            dt.DataTable().clear().destroy()

            createDataTable();
        })

        function changeParams() {
            var unindexed_array =$("#buscarForm").serializeArray();
            console.log(unindexed_array);
            indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });
        }

        function createDataTable() {
                console.log("object");
                    var $columns = $('thead th:not(.dt-no-export)', dt);

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
                    
                    
                    dt.DataTable({
                        ajax: {
                            url: $("#buscarForm").attr("action"),
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
                        buttons: [
                            {
                                extend: 'collection',
                                className: 'btn btn-outline-secondary dropdown-toggle',
                                text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Exportar',
                                buttons: [
                                    {
                                        extend: 'print',
                                        text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Imprimir',
                                        className: 'dropdown-item',
                                        title: '',
                                        exportOptions: {
                                            columns: $columns
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'CSV',
                                        className: 'dropdown-item',
                                        title: '',
                                        exportOptions: {
                                            columns: $columns
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                                        className: 'dropdown-item',
                                        title: '',
                                        exportOptions: {
                                            columns: $columns
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'PDF',
                                        className: 'dropdown-item',
                                        title: '',
                                        exportOptions: {
                                            columns: $columns
                                        }
                                    },
                                    {
                                        extend: 'copy',
                                        text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copiar',
                                        className: 'dropdown-item',
                                        title: '',
                                        exportOptions: {
                                            columns: $columns
                                        }
                                    }
                                ],
                                init: function (api, node, config) {
                                    $(node).removeClass('btn-secondary');
                                    $(node).parent().removeClass('btn-group');
                                    setTimeout(function () {
                                        $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                                    }, 50);
                                }
                            },
                        ],
                        columnDefs: [
                            {
                                // Actions
                                targets: 0,
                                searchable: false,
                                title: '' + feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }),
                                orderable: false,
                                render: function (data, type, full, meta) {
                                    return (
                                        '<div class="d-inline-flex dropright">' +
                                            
                                            
                                        '</div>'
                                    );
                                }
                            }
                        ],
                    });
                
        }

        //Genera un dataTable con los resultados de la busqueda
        // dt.DataTable({
        //     "processing": true,
        //     "serverSide": true,
        //     "ajax": aUrl,

        
        $(function () {
            'use strict';
            
            
        });
    </script>
    @endsection
{{-- @endif --}}

