@extends("backOffice.layout.panel")


@section("title","Tableau de bord")

@section("script_links")

    <script src="{{asset("adminPanel")}}/vendors/Chart.js/dist/Chart.min.js"></script>

@endsection


@section("content-wrapper")

        <!-- page content -->
        <div class="right_col" role="main">
            <!-- top tiles -->
            <div class="row" style="display: inline-block;" >
                <div class="tile_count">
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Nbr Clients</span>
                        <div id="clientCount" class="count"></div>
                        <span class="count_bottom"><i class="green"> <i id="clientChange">  </i> </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-clock-o"></i> Nbr Employées</span>
                        <div id="employeeCount" class="count"></div>
                        <span class="count_bottom"><i class="green"><i id="employeeChange">  </i> </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Nbr Fournisseurs</span>
                        <div id="supplierCount" class="count green"></div>
                        <span class="count_bottom"><i class="green"><i id="supplierChange">  </i> </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Nbr Devis</span>
                        <div id="quotationCount" class="count"></div>
                        <span class="count_bottom"><i class="green"><i id="quotationChange">  </i> </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Nbr Produits</span>
                        <div id="productCount" class="count"></div>
                        <span class="count_bottom"><i class="green"><i id="productChange">  </i> </i> From last Week</span>
                    </div>
                    <div style="visibility: hidden !important;" class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
                        <div class="count">7,261</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
                    </div>
                </div>
            </div>
            <!-- /top tiles -->

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="dashboard_graph">

                        <div class="row x_title">
                            <div class="col-md-6">
                                <h3>Activite des devis <small>Nombre des devis par mois</small></h3>
                            </div>
                        </div>

                        <div class="col-md-9 col-sm-9 ">
                            <canvas id="canvas_line_chart"></canvas>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="dashboard_graph">

                        <div class="col-md-3 col-sm-12 mr-md-4">
                            <div>
                                <div class="x_title">
                                    <h2>Anciens crédits clients</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li>
                                            <a href="{{route('credits.nonPaid')}}" class="collapse-link">voir tous</a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <ul class="list-unstyled top_profiles scroll-view">
                                    @foreach($credits as $credit)
                                        <li class="media event">
                                            <a class="pull-left border-green profile_thumb">
                                                <i class="fa fa-user green"></i>
                                            </a>
                                            <div class="media-body">
                                                <a class="title" href="#">{{$credit->folder->client->name}}</a>
                                                <p>Montant de : <strong style="color: #569a66"> {{$credit->total - $credit->paid}} DH </strong> </p>
                                                <p>Depuis le : <small><b>{{$credit->created_at}}</b></small>
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div>
                                <div class="x_title">
                                    <h2>Anciens crédits fournisseurs</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li>
                                            <a href="{{route('supplierCredits.nonPaid')}}" class="collapse-link">voir tous</a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <ul class="list-unstyled top_profiles scroll-view">
                                    @foreach($suplierCredits as $credit)
                                        <li class="media event">
                                            <a class="pull-left border-red profile_thumb">
                                                <i class="fa fa-user red"></i>
                                            </a>
                                            <div class="media-body">
                                                <a class="title" href="#">{{$credit->order->provider->name}} : ({{$credit->order->title}})</a>
                                                <p>Montant de : <strong style="color: #cc4a61"> {{$credit->total - $credit->paid}} DH </strong> </p>
                                                <p>Depuis le : <small><b>{{$credit->created_at}}</b></small>
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

@endsection

@section("script")
    <script>
        // var canvas_line_00 = new Chart(document.getElementById("canvas_line"), {
        //     type: 'line',
        //     data: {
        //         labels: ["January", "February", "March", "April", "May", "June", "July"],
        //         datasets: [{
        //             label: "My First dataset",
        //             backgroundColor: "rgba(38, 185, 154, 0.31)",
        //             borderColor: "rgba(38, 185, 154, 0.7)",
        //             pointBorderColor: "rgba(38, 185, 154, 0.7)",
        //             pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
        //             pointHoverBackgroundColor: "#fff",
        //             pointHoverBorderColor: "rgba(220,220,220,1)",
        //             pointBorderWidth: 1,
        //             data: [31, 74, 6, 39, 20, 85, 7]
        //         }, {
        //             label: "My Second dataset",
        //             backgroundColor: "rgba(3, 88, 106, 0.3)",
        //             borderColor: "rgba(3, 88, 106, 0.70)",
        //             pointBorderColor: "rgba(3, 88, 106, 0.70)",
        //             pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
        //             pointHoverBackgroundColor: "#fff",
        //             pointHoverBorderColor: "rgba(151,187,205,1)",
        //             pointBorderWidth: 1,
        //             data: [82, 23, 66, 9, 99]
        //         }]
        //     },
        // });
    </script>
    <script>
        $(document).ready(function() {

            $.ajax({
                url: '/api/dashboard/counts',
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#clientCount').text(data.clients.count);
                    $('#clientChange').text('+' + data.clients.change);

                    $('#employeeCount').text(data.employees.count);
                    $('#employeeChange').text('+' + data.employees.change);

                    $('#supplierCount').text(data.suppliers.count);
                    $('#supplierChange').text('+' + data.suppliers.change);

                    $('#quotationCount').text(data.quotations.count);
                    $('#quotationChange').text('+' + data.quotations.change);

                    $('#productCount').text(data.products.count);
                    $('#productChange').text('+' + data.products.change);
                }
            });

            // Fetch orders by month using $.ajax
            $.ajax({
                url: '/api/dashboard/quotations-by-month',
                method: 'GET',
                success: function(data) {
                    var canvas_line_00 = new Chart(document.getElementById("canvas_line_chart"), {
                        type: 'line',
                        data: {
                            labels: data.map(item => item.month),
                            datasets: [{
                                label: "Nombre des Quotations non confirmée",
                                backgroundColor: "rgba(38, 185, 154, 0.31)",
                                borderColor: "rgba(38, 185, 154, 0.7)",
                                pointBorderColor: "rgba(38, 185, 154, 0.7)",
                                pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
                                pointHoverBackgroundColor: "#fff",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                pointBorderWidth: 1,
                                data: data.map(item => item.status_0) // Correction ici
                            }, {
                                label: "Nombre des Quotations confirmée",
                                backgroundColor: "rgba(3, 88, 106, 0.3)",
                                borderColor: "rgba(3, 88, 106, 0.70)",
                                pointBorderColor: "rgba(3, 88, 106, 0.70)",
                                pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
                                pointHoverBackgroundColor: "#fff",
                                pointHoverBorderColor: "rgba(151,187,205,1)",
                                pointBorderWidth: 1,
                                data: data.map(item => item.status_1) // Correction ici
                            }]
                        },
                    });
                }
            });
        });
    </script>
@endsection
