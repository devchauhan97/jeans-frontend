<header class="main-header">

    <!-- Logo -->
    <a href="{{ URL::to('admin/dashboard/this_month')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" style="font-size:12px"><b>Admin</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
		<div id="countdown" style="
    width: 350px;
    margin-top: 13px !important;
    position: absolute;
    font-size: 16px;
    color: #ffffff;
    display: inline-block;
    margin-left: -175px;
    left: 50%;
"></div>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-list-ul"></i>
              <span class="label label-success">{{ count($unseenOrders) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{ count($unseenOrders) }} new orders</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($unseenOrders as $unseenOrder)
                  <li><!-- start message -->
                    <a href="{{ URL::to("admin/viewOrder")}}/{{ $unseenOrder->orders_id}}">
                      <div class="pull-left">
                        
                         @if(!empty($unseenOrder->customers_picture))
                            <img src="{{asset('').'/'.$unseenOrder->customers_picture}}" class="img-circle" alt="{{ $unseenOrder->customers_name }} Image">
                            @else
                            <img src="{{asset('').'/resources/assets/images/default_images/user.png' }}" class="img-circle" alt="{{ $unseenOrder->customers_name }} Image">
                         @endif
                                                  
                      </div>
                      <h4>
                        {{ $unseenOrder->customers_name }}
                        <small><i class="fa fa-clock-o"></i> {{ date('d/m/Y', strtotime($unseenOrder->date_purchased)) }}</small>
                      </h4>
                      <p>Ordered Products ({{ $unseenOrder->total_products}})</p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                </ul>
              </li>
              <!--<li class="footer"><a href="#">See All Messages</a></li>-->
            </ul>
          </li>
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-users"></i>
              <span class="label label-warning">{{ count($newCustomers) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{ count($newCustomers) }} new users.</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($newCustomers as $newCustomer)
                  <li><!-- start message -->
                    <a href="{{ URL::to("admin/editCustomers")}}/{{ $newCustomer->customers_id}}">
                      <div class="pull-left">
                         @if(!empty($newCustomer->customers_picture))
                            <img src="{{asset('').'/'.$newCustomer->customers_picture}}" class="img-circle">
                            @else
                            <img src="{{asset('').'/resources/assets/images/default_images/user.png' }}" class="img-circle" alt="{{ $newCustomer->customers_firstname }} Image">
                         @endif
                      </div>
                      <h4>
                        {{ $newCustomer->customers_firstname }} {{ $newCustomer->customers_lastname }}
                        <small><i class="fa fa-clock-o"></i> {{ date('d/m/Y', $newCustomer->created_at) }}</small>
                      </h4>
                      <p></p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                </ul>
              </li>
              <!--<li class="footer"><a href="#">See All Messages</a></li>-->
            </ul>
          </li>
          
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-th"></i>
              <span class="label label-warning">{{ count($lowInQunatity) }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{ count($lowInQunatity) }} Products are in low quantity.</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach($lowInQunatity as $lowInQunatity)
                  <li><!-- start message -->
                    <a href="{{ URL::to("admin/editProduct")}}/{{ $lowInQunatity->products_id}}">
                      <div class="pull-left">                         
                         <img src="{{asset('').'/'.$lowInQunatity->products_image}}" class="img-circle" >
                      </div>
                      <h4 style="white-space: normal;">
                        {{ $lowInQunatity->products_name }} 
                      </h4>
                      <p></p>
                    </a>
                  </li>
                @endforeach
                  <!-- end message -->
                </ul>
              </li>
              <!--<li class="footer"><a href="#">See All Messages</a></li>-->
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu" style="display: none">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                      page and may cause design problems
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu" style="display: none">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Create a nice theme
                        <small class="pull-right">40%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">40% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Some task I need to do
                        <small class="pull-right">60%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">60% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Make beautiful transitions
                        <small class="pull-right">80%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">80% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{asset('').Auth::user()->image}}" class="user-image" alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} Image">
              <span class="hidden-xs">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{asset('').Auth::user()->image}}" class="img-circle" alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} Image">

                <p>
                  {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} 
                  <small>Administrator</small>
                </p>
              </li>
              <!-- Menu Body -->
              <!--<li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ URL::to('admin/adminProfile')}}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ URL::to('admin/logout')}}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>

    </nav>
  </header>