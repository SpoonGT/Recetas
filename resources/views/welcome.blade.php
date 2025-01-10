<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estadística Temporal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" />

</head>

<body>
    <main>

        <div class="container px-4 py-5">
            <h2 class="pb-2 border-bottom">Estadística de Expedientes</h2>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-sm-12 col-md-6 gap-2">
                    <img src="{{ asset('logo.png') }}" width="75px" class="rounded" alt="logotipo">
                    <h2 class="fw-bold text-body-emphasis">Dirección General de Transportes (DGT)</h2>
                    <p class="text-body-secondary">
                        El objetivo de este portal es mostrar la estadística de expedientes ingresados en la
                        institución, la
                        información utilizada para generar las gráficas es extraida de un excel que se nos comparte.
                    </p>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="row row-cols-1 row-cols-sm-2 g-4">
                        <div class="col d-flex flex-column gap-2">
                            <h4 class="fw-semibold mb-0 text-body-emphasis">Expediente Completo</h4>
                            <p class="text-body-secondary">La cantidad de expedientes cargados correctamente son
                                <b>{{ number_format($completo) }}</b> y sobre esta cantidad se generan las gráficas.
                            </p>
                        </div>

                        <div class="col d-flex flex-column gap-2">
                            <h4 class="fw-semibold mb-0 text-body-emphasis">Expediente Imcompleto</h4>
                            <p class="text-body-secondary">La cantidad de expedientes que no fueron cargados son
                                <b>{{ number_format($incompleto) }}</b> y no se tomarán en cuenta en la generación de
                                gráficas.
                            </p>
                        </div>
                    </div>
                    <div class="row row-cols-1 g-2">
                        <div class="col d-flex flex-column gap-2">
                            <h4 class="fw-semibold mb-0 text-body-emphasis">Rango de Fecha</h4>
                            <p class="text-body-secondary">El rango de fecha a tomar en cuenta en esta estadística es
                                desde <b>{{ date('d/m/Y', strtotime($fecha_min)) }}</b> hasta
                                <b>{{ date('d/m/Y', strtotime($fecha_max)) }}</b>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-3">
                    <div class="alert alert-light" role="alert">
                        Según la información proporcionada en el excel existen <b>{{ $cantidad_anios->count() }}</b>
                        años
                        registrados.
                        <br><br>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#verAnios" aria-expanded="false" aria-controls="verAnios">
                            Ver años registrados
                        </button>
                    </div>
                    <div class="collapse collapse-horizontal" id="verAnios">
                        <div class="card card-body">
                            <div class="table-responsive">
                                <table class="table table-striped info">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cantidad_anios as $item)
                                            <tr>
                                                <td scope="row">{{ $item->valor }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-light" role="alert">
                        Según la información proporcionada en el excel existen
                        <b>{{ $cantidad_interesados->count() }}</b>
                        interesados registrados.
                        <br><br>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#verInteresados" aria-expanded="false" aria-controls="verInteresados">
                            Ver interesados registrados
                        </button>
                    </div>
                    <div class="collapse collapse-horizontal" id="verInteresados">
                        <div class="card card-body">
                            <div class="table-responsive">
                                <table class="table table-striped info">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cantidad_interesados as $item)
                                            <tr>
                                                <td scope="row">{{ $item->nombre_completo }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-light" role="alert">
                        Según la información proporcionada en el excel existen
                        <b>{{ $cantidad_tramites->count() }}</b>
                        trámites registrados.
                        <br><br>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#verTramites" aria-expanded="false" aria-controls="verTramites">
                            Ver trámites registrados
                        </button>
                    </div>
                    <div class="collapse collapse-horizontal" id="verTramites">
                        <div class="card card-body">
                            <div class="table-responsive">
                                <table class="table table-striped info">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cantidad_tramites as $item)
                                            <tr>
                                                <td scope="row">{{ $item->nombre }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-light" role="alert">
                        Según la información proporcionada en el excel existen
                        <b>{{ $cantidad_estados->count() }}</b>
                        estados registrados.
                        <br><br>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#verEstados" aria-expanded="false" aria-controls="verEstados">
                            Ver estados registrados
                        </button>
                    </div>
                    <div class="collapse collapse-horizontal" id="verEstados">
                        <div class="card card-body">
                            <div class="table-responsive">
                                <table class="table table-striped info">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cantidad_estados as $item)
                                            <tr>
                                                <td scope="row">{{ $item->nombre }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Top 10 de expedientes más recientes</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped info">
                                    <thead>
                                        <tr>
                                            <th scope="col">Año</th>
                                            <th scope="col">Fecha de Ingreso</th>
                                            <th scope="col">Correlativo</th>
                                            <th scope="col">Trámite</th>
                                            <th scope="col">Interesado</th>
                                            <th scope="col">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tabla_top_nuevo as $item)
                                            <tr>
                                                <td scope="row">{{ $item->anio->valor }}</td>
                                                <td>{{ date('d/m/Y', strtotime($item->ingreso)) }}</td>
                                                <td>{{ $item->correlativo }}</td>
                                                <td>{{ $item->tramite->nombre }}</td>
                                                <td>{{ $item->interesado->nombre_completo }}</td>
                                                <td>{{ $item->estado->nombre }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Top 10 de expedientes más antigüos</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped info">
                                    <thead>
                                        <tr>
                                            <th scope="col">Año</th>
                                            <th scope="col">Fecha de Ingreso</th>
                                            <th scope="col">Correlativo</th>
                                            <th scope="col">Trámite</th>
                                            <th scope="col">Interesado</th>
                                            <th scope="col">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tabla_top_viejo as $item)
                                            <tr>
                                                <td scope="row">{{ $item->anio->valor }}</td>
                                                <td>{{ date('d/m/Y', strtotime($item->ingreso)) }}</td>
                                                <td>{{ $item->correlativo }}</td>
                                                <td>{{ $item->tramite->nombre }}</td>
                                                <td>{{ $item->interesado->nombre_completo }}</td>
                                                <td>{{ $item->estado->nombre }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="resueltos" class="card-img-top"></div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="grafica1" class="card-img-top"></div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="grafica2" class="card-img-top"></div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="grafica3" class="card-img-top"></div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="grafica4" class="card-img-top"></div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="grafica5" class="card-img-top"></div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 py-5 border-bottom">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Expedientes cargados para la estadística</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped exp">
                                    <thead>
                                        <tr>
                                            <th scope="col">Año</th>
                                            <th scope="col">Fecha de Ingreso</th>
                                            <th scope="col">Correlativo</th>
                                            <th scope="col">Trámite</th>
                                            <th scope="col">Interesado</th>
                                            <th scope="col">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expedientes as $item)
                                            <tr>
                                                <td scope="row">{{ $item->anio->valor }}</td>
                                                <td>{{ date('d/m/Y', strtotime($item->ingreso)) }}</td>
                                                <td>{{ $item->correlativo }}</td>
                                                <td>{{ $item->tramite->nombre }}</td>
                                                <td>{{ $item->interesado->nombre_completo }}</td>
                                                <td>{{ $item->estado->nombre }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small>Fecha de creación: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/variwide.js"></script>

    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="https://kit.fontawesome.com/26184640bb.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        new DataTable('.info', {
            searching: false,
            paging: false,
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            }
        });

        new DataTable('.exp', {
            searching: true,
            paging: true,
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            }
        });
    </script>

    <!-- Grafica 1 -->
    <script type="text/javascript">
        var categoria = <?php echo json_encode($categoria_grafica1); ?>;
        var data = <?php echo json_encode($data_grafica1); ?>;

        Highcharts.chart('grafica1', {
            title: {
                text: 'Expedientes por año'
            },
            subtitle: {
                text: 'La gráfica muestra la cantidad de expedientes agrupados por año.'
            },
            xAxis: {
                categories: categoria
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de expedientes'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series: [{
                name: 'Expedientes',
                data: data
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            credits: {
                enabled: false
            }
        });
    </script>

    <!-- Grafica 2 -->
    <script type="text/javascript">
        var data = <?php echo json_encode($data_grafica2); ?>;

        Highcharts.chart('grafica2', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Expedientes por trámite'
            },
            subtitle: {
                text: 'La gráfica muestra la cantidad de expedientes agrupados por tipo de trámite.'
            },
            legend: {
                lenabled: false
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: [{
                        enabled: true,
                        distance: 20
                    }, {
                        enabled: true,
                        distance: -40,
                        format: '{point.percentage:.1f}%',
                        style: {
                            fontSize: '1.2em',
                            textOutline: 'none',
                            opacity: 0.7
                        },
                        filter: {
                            operator: '>',
                            property: 'percentage',
                            value: 10
                        }
                    }]
                }
            },
            series: [{
                name: 'Expedientes',
                colorByPoint: true,
                data: data
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            credits: {
                enabled: false
            }
        });
    </script>

    <!-- Grafica 3 -->
    <script type="text/javascript">
        var categoria = <?php echo json_encode($categoria_grafica3); ?>;
        var data = <?php echo json_encode($data_grafica3); ?>;

        Highcharts.chart('grafica3', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Expedientes por estado y año'
            },
            subtitle: {
                text: 'La gráfica muestra la cantidad de expedientes agrupados por estado que tenga más de 25 y la cantidad de los últimos 10 años.'
            },
            xAxis: {
                min: 0,
                categories: categoria
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de expedientes'
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: data,
            credits: {
                enabled: false
            }
        });
    </script>

    <!-- Grafica 4 -->
    <script type="text/javascript">
        var categoria = <?php echo json_encode($categoria_grafica4); ?>;
        var data = <?php echo json_encode($data_grafica4); ?>;

        Highcharts.chart('grafica4', {
            title: {
                text: 'Expedientes por interesado'
            },
            subtitle: {
                text: 'La gráfica muestra la cantidad de expedientes agrupados por interesado que tiene más de 1 trámite.'
            },
            xAxis: {
                categories: categoria
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de expedientes'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series: [{
                name: 'Expedientes',
                data: data
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            credits: {
                enabled: false
            }
        });
    </script>

    <!-- Grafica 5 -->
    <script type="text/javascript">
        var categoria = <?php echo json_encode($categoria_grafica_agrupado); ?>;
        var data = <?php echo json_encode($data_grafica5); ?>;

        Highcharts.chart('grafica5', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'Expedientes por período presidencial'
            },
            subtitle: {
                align: 'left',
                text: 'La gráfica muestra la cantidad de expedientes agrupados por período presidencial.'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                min: 0,
                categories: categoria,
                type: 'category'
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de expedientes'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Expedientes',
                colorByPoint: true,
                data: data
            }],
            credits: {
                enabled: false
            }
        });
    </script>

    <!-- Resueltos -->
    <script type="text/javascript">
        var data = <?php echo json_encode($data_resueltos); ?>;

        Highcharts.chart('resueltos', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Expedientes resueltos'
            },
            subtitle: {
                text: 'La gráfica muestra la cantidad de expedientes resueltos y pendientes de resolver.'
            },
            legend: {
                lenabled: false
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: [{
                        enabled: true,
                        distance: 20
                    }, {
                        enabled: true,
                        distance: -40,
                        format: '{point.percentage:.1f}%',
                        style: {
                            fontSize: '1.2em',
                            textOutline: 'none',
                            opacity: 0.7
                        },
                        filter: {
                            operator: '>',
                            property: 'percentage',
                            value: 10
                        }
                    }]
                }
            },
            series: [{
                name: 'Expedientes',
                colorByPoint: true,
                data: data
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            credits: {
                enabled: false
            }
        });
    </script>

</body>

</html>
