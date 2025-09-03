<header class="main-header">
    <!-- Logo header -->
    <a href="/admin/immediatePayWU" class="logo">
      <span class="logo-lg"><b>Dart</b>Survivor Management</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" >
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              
              {{--<img src="img/{{$client_id}}.png" class="user-image" alt="User Image">--}}
                <i class="fa fa-gears"></i>
              <span class="hidden-xs">{{Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu" style="width: unset !important">
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{route('logout')}}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>

      <div class="select-form">
        <label for="fdec">FDEC</label>
        <select name="fdec" id="fdec">
          @foreach($fdecList as $fdec)
              <option value="{{ $fdec }}">{{ $fdec }}</option>
          @endforeach
        </select>
      </div>
    </nav>
</header>