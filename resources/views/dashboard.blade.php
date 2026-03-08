@extends("backOffice.layout.panel")


@section("title","Tableau de bord")

@section("script_links")

    <script src="{{asset("adminPanel")}}/vendors/Chart.js/dist/Chart.min.js"></script>

@endsection


@section("content-wrapper")

        <!-- page content -->
        <div class="right_col" role="main">
            <h2>Dashboard</h2>
        </div>
        <!-- /page content -->

@endsection

@section("script")

@endsection
