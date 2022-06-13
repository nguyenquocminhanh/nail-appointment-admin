<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            
            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="ri-search-line"></span>
                </div>
            </form>

        </div>

        <div class="d-flex">
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>
            
            @if(Auth::user()->role->name == 'staff')
            @php
                $notifications = App\Models\UserAppNoti::latest()->where('user_id', Auth::user()->id)->get();
            @endphp
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class=" far fa-calendar-alt"></i>
                    @if(App\Models\UserAppNoti::where('user_id', Auth::user()->id)->where('is_read', '0')->first())
                        <span class="badge rounded-pill bg-danger float-end"> 
                            {{App\Models\UserAppNoti::where('user_id', Auth::user()->id)->where('is_read', '0')->first()->appointments()->count()}}
                        </span>
                    @endif
                    <!-- <span class="noti-dot"></span> -->
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('appointment.read.all') }}" class="small"> Mark As Read All</a>
                            </div>
                        </div>
                    </div>
                    
                    <div data-simplebar style="max-height: 230px;">
                        @forelse($notifications as $key => $noti)
                        <a href="{{ route('user.notification.read', $noti->id) }}" class="text-reset notification-item">
                            <div class="d-flex" @if($noti->is_read == '0') style="background: #f1f5f7" @endif>
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="ri-calendar-line"></i>
                                    </span>
                                </div>

                                
                                <div class="flex-1">
                                    <h6 class="mb-1">You have {{ $noti->appointments()->count() }} new appointments</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i>{{ Carbon\Carbon::parse($noti->updated_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-1">
                                    <span class="mb-1">You have 0 notification</span>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div> 
            @endif



            @if(Auth::user()->role->name == 'admin')
            @php
                $notifications = App\Models\AdminAppNoti::latest()->get();
                $update_noties = App\Models\AdminUpdateNoti::latest()->get();
            @endphp


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        
                    <i class=" far fa-calendar-alt"></i>
                    @php
                    if(App\Models\AdminAppNoti::where('is_read', '0')->first()) {
                        $appointment_noti = App\Models\AdminAppNoti::where('is_read', '0')->first()->appointments()->count();
                    } else {
                        $appointment_noti = 0;
                    }
                    @endphp
                    @if($appointment_noti > 0)
                    <span class="badge rounded-pill bg-danger float-end"> 
                        {{ $appointment_noti }}
                    </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('appointment.read.all') }}" class="small"> Mark As Read All</a>
                            </div>
                        </div>
                    </div>

                    @if(count($notifications) > 0)
                    <div data-simplebar style="max-height: 230px;">
                        @foreach($notifications as $key => $noti)
                        <a href="{{ route('admin.notification.read', $noti->id) }}" class="text-reset notification-item">
                            <div class="d-flex" @if($noti->is_read == '0') style="background: #f1f5f7" @endif>
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="ri-calendar-line"></i>
                                    </span>
                                </div>

                                
                                <div class="flex-1">
                                    <h6 class="mb-1">You have {{ $noti->appointments()->count() }} new appointments</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i>{{ Carbon\Carbon::parse($noti->updated_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else 
                    <div data-simplebar style="max-height: 230px;">
                        <div class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-1">
                                    <span class="mb-1">You have 0 notification</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>



            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    @php
                    if(App\Models\AdminUpdateNoti::where('is_read', '0')->first()) {
                        $update_noti = App\Models\AdminUpdateNoti::where('is_read', '0')->count();
                    } else {
                        $update_noti = 0;
                    }
                    @endphp

                    @if($update_noti > 0)
                    <span class="badge rounded-pill bg-danger float-end"> 
                        {{ $update_noti }}
                    </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('update.read.all') }}" class="small"> Mark As Read All</a>
                            </div>
                        </div>
                    </div>
                    
                    @if(count($update_noties) > 0)
                    <div data-simplebar style="max-height: 230px;">
                        @foreach($update_noties as $key => $noti)
                        <a href="{{ route('update.notification.read', [$noti->user->id, $noti->id]) }}" class="text-reset notification-item">
                            <div class="d-flex" @if($noti->is_read == '0') style="background: #f1f5f7" @endif>
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                </div>

                                
                                <div class="flex-1">
                                    <h6 class="mb-1">{{ $noti->user->name }} has updated {{ $noti->update_thing }}</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i>{{ Carbon\Carbon::parse($noti->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else 
                    <div data-simplebar style="max-height: 230px;">
                        <div class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-1">
                                    <span class="mb-1">You have 0 notification</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div> 
            @endif                    

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ (!empty(Auth::user()->profile_image)) ? asset(Auth::user()->profile_image) : url('upload/no_image.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">{{ Auth::user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                    <a class="dropdown-item" href="{{ route('change.password') }}"><i class="ri-lock-unlock-line align-middle me-1"></i>Change Password</a>
                    <!-- <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end mt-1">11</span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
                    <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>