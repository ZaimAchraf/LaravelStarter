@extends("backOffice.layout.panel")


@section("title","Liste des utilisateurs")

@section("style_links")



@endsection

@section("script_links")



@endsection




@section("content-wrapper")

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Fonctionnaires</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="x_panel">
                    <div class="x_content">
                        @foreach($employees as $employee)
                            <div class="col-md-4 col-sm-4  profile_details">
                                <div class="well profile_view">
                                    <div class="col-sm-12">
                                        <h4 class="brief"><i>{{$employee->user->username}}</i></h4>
                                        <div class="left col-md-7 col-sm-7">
                                            <h2>{{$employee->user->name}}</h2>
                                            <p><strong>Fonction : </strong> {{$employee->fonction}} </p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-building"></i> Address: {{$employee->user->adresse}}</li>
                                                <li><i class="fa fa-phone"></i> Phone #: {{$employee->user->phone}}</li>
                                                <li><i class="fa fa-money"></i> Salaire: {{$employee->salaire}}</li>
                                                <li><i class="fa fa-user"></i> Statut: {{$employee->statut}}</li>
                                            </ul>
                                        </div>
                                        <div class="right col-md-5 col-sm-5 text-center">
                                            <img src="{{asset('uploads')}}/users/{{$employee->user->picture}}" alt="" class="img-circle img-fluid">
                                        </div>
                                    </div>
                                    <div class=" profile-bottom text-center">
                                        <div class=" col-sm-12 emphasis">
                                            <button type="button" class="btn btn-success btn-sm">
                                                    <a href="{{ route('users.edit', $employee->user) }}" class="text-white"> <i class="fa fa-edit"></i> Edit </a>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="fa fa-user"> </i> View Profile
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
