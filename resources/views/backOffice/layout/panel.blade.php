
@include("backOffice.layout.includes.header")


<body class="nav-md">
<div class="container body">
    <div class="main_container">

        @include("backOffice.layout.includes.sidebar")

        @include("backOffice.layout.includes.navbar")

        @yield('content-wrapper')


        <!-- footer content start -->
        <footer>
            <div class="pull-right">
                Developped by <a href="https://zaimachraf.github.io/">Zaim Achraf</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- footer content end -->

    </div>
</div>
</body>

@include("backOffice.layout.includes.footer")
