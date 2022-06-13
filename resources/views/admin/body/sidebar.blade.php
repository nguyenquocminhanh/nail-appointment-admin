<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ url('/dashboard') }}" class="waves-effect">
                        <i class="ri-home-7-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(Auth::check() && Auth::user()->role->name == 'admin')
                <li>
                    <a href="{{ route('business') }}" class="waves-effect">
                        <i class="fas fa-business-time"></i>
                        <span>Manage Business</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('staff.all') }}" class="waves-effect">
                        <i class="fas fa-users"></i>
                        <span>Manage Staff</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('service.all') }}" class="waves-effect">
                        <i class="ri-customer-service-fill"></i>
                        <span>Manage Service</span>
                    </a>
                </li>
                @endif


                @if(Auth::check() && Auth::user()->role->name == 'staff')
                <li>
                    <a href="{{ route('my.profile') }}" class="waves-effect">
                        <i class="ri-profile-fill"></i>
                        <span>Manage Profile</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-calendar-todo-fill"></i>
                        <span>Manage Appointment</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('appointment.all') }}">All Appointment</a></li>
                        <li>
                            <a href="{{ route('appointment.new') }}">New Appointment 
                                @if(Auth::user()->role->name == 'staff' && App\Models\Appointment::where('user_id', Auth::user()->id)->where('is_user_read', '0')->count() > 0)
                                    <span class="badge rounded-pill bg-danger float-end">{{App\Models\Appointment::where('user_id', Auth::user()->id)->where('is_user_read', '0')->count()}}</span>
                                @endif

                                @if(Auth::user()->role->name == 'admin' && App\Models\Appointment::where('is_admin_read', '0')->count() > 0)
                                    <span class="badge rounded-pill bg-danger float-end">{{App\Models\Appointment::where('is_admin_read', '0')->count()}}</span>
                                @endif
                            </a>
                        </li>
                        <li><a href="{{ route('appointment.visited') }}">Completed Appointment</a></li>
                        <li><a href="{{ route('appointment.past') }}">Past Appointment</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>