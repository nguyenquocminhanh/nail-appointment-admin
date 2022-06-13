<!-- Page Content -->
@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Minh Nguyen</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        @php
            if(Auth::user()->role->name == 'admin') {
                $total_appointments = App\Models\Appointment::where('date', '>=', Carbon\Carbon::today())->count();
                $complete_appointments = App\Models\Appointment::where('date', '>=', Carbon\Carbon::today())->where('status', '1')->count();
                $new_appointments = App\Models\Appointment::where('date', '>=', Carbon\Carbon::today())->where('is_admin_read', '0')->count();
            } else {
                $total_appointments = App\Models\Appointment::where('user_id', Auth::user()->id)->where('date', '>=', Carbon\Carbon::today())->count();
                $complete_appointments = App\Models\Appointment::where('user_id', Auth::user()->id)->where('date', '>=', Carbon\Carbon::today())->where('status', '1')->count();
                $new_appointments = App\Models\Appointment::where('user_id', Auth::user()->id)->where('date', '>=', Carbon\Carbon::today())->where('is_user_read', '0')->count();
            }

            
        @endphp

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Appointments</p>
                                <h4 class="mb-2">{{ $total_appointments }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="far fa-calendar-alt font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                            
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">New Appointments</p>
                                <h4 class="mb-2">{{ $new_appointments }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="far fa-calendar-plus font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Completed Appointments</p>
                                <h4 class="mb-2">{{ $complete_appointments }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="far fa-calendar-check font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            @php
                $role_id = App\Models\Role::where('name', 'staff')->first()->id;
                $total_staffs = App\Models\User::where('role_id', $role_id)->count();
            @endphp
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Staffs</p>
                                <h4 class="mb-2">{{ $total_staffs }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="fas fa-users font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    
</div>

@endsection