<nav class="navbar navbar-default point-border " style="background-color: #ffffff; border-radius: 0px;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="#">NSI :: Inventory `5</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    Logged in as :: {{ Auth::user()->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                    	@if (Auth::user()->type == 'admin')
                        <li><a href="{!! URL::to('/') !!}/auth/register"><span class="glyphicon glyphicon-user"></span> Register User</a></li>
                        @endif
                        <li><a href="{!! URL::to('/') !!}/history"><span class="glyphicon glyphicon-book"></span> History</a></li>
                        <li><a href="{!! URL::to('/') !!}/auth/change_password"><span class="glyphicon glyphicon-refresh"></span> Change Password</a></li>
                        <li class="divider"></li>
                        <li><a href="{!! URL::to('/') !!}/auth/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                    </ul>
                </li>
                 <li><a href="{!! URL::to('/') !!}"><span class="glyphicon glyphicon-home"></span> Home</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>