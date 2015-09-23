<nav class="navbar navbar-default navbar-default-2 point-border " style="background-color: #ffffff; border-radius: 0px;background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#dd4f14), to(#9b1f03)) no-repeat;border-width: 0px;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand navbar-default-2" href="#">
            	<span>
            		<img alt="Brand" src="{{ URL::to('/') }}/packages/images/logo-nsi.png" style="margin: -1rem 0rem -1.5rem 7rem;">
				</span>
            	<span>
            		<label class="size-20" style="margin-top: -2rem; top: 3rem;">Inventory `5</label>
            	</span>
            </a>git rm -r --cached <file name>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle m-top-nav-hover" data-toggle="dropdown" role="button" aria-expanded="false">
                    Logged in as :: {{ Auth::user()->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                    	@if (Auth::user()->type == 'admin')
                        <li><a href="{!! URL::to('/') !!}/auth/register"><span class="glyphicon glyphicon-user"></span> Register User</a></li>
                        @endif
                        <li><a href="{{ route('activity.index') }}"><span class="glyphicon glyphicon-book"></span> History</a></li>
                        <li><a href="{{ route('change_password') }}"><span class="glyphicon glyphicon-refresh"></span> Change Password</a></li>
                        <li class="divider"></li>
                        <li><a href="{!! URL::to('/') !!}/auth/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                    </ul>
                </li>
                 <li><a href="{!! URL::to('/') !!}"><span class="glyphicon glyphicon-home"></span> Home</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>