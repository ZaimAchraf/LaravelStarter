<form method="POST" action="{{ route('logout') }}">
    @csrf
    {{--                <a style="margin-top: -10px;" class="dropdown-item preview-item" href="{{ route('logout') }}"--}}
    {{--                   onclick="event.preventDefault();this.closest('form').submit();">--}}
    {{--                    <div class="preview-item-content text-center text-purple-600">--}}
    {{--                        Se déconnecter--}}
    {{--                    </div>--}}
    {{--                </a>--}}
    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html"
       onclick="event.preventDefault();this.closest('form').submit();">
        <span class="glyphicon glyphicon-off" aria-hidden="true">logout</span>
    </a>
</form>
