@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('staff.all') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>

                        <h4 class="card-title">Staff Profile</h4>
                        <br>
                        <center>
                            <img class="rounded-circle avatar-xl" src="{{ (!empty($staff->profile_image)) ? asset($staff->profile_image) : url('upload/no_image.jpg') }}" class="card-img-top" alt="...">
                        </center>
                        <br>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="card-title">Staff Basic Information</h5>
                                    <hr>
                                    <ul class="list-group">
                                        <li class="list-group-item">Name: {{ $staff->name }}</li>
                                        <li class="list-group-item">Username: {{ $staff->username }}</li>
                                        <li class="list-group-item">Email: {{ $staff->email }}</li>
                                        <li class="list-group-item">Phone: {{ $staff->phone_number }}</li>
                                        
                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <h5 class="card-title">Staff Services</h5>
                                    <hr>
                                    <ul class="list-group">
                                        @forelse($staff->services as $key => $service)
                                        <li class="list-group-item">{{ $service->name }}</li>
                                        @empty
                                        <li class="list-group-item">No data</li>
                                        @endforelse
                                    </ul>
                                </div>    

                                <div class="col-md-4">
                                    <h5 class="card-title">Staff Working Hours</h5>
                                    <hr>
                                    <ul class="list-group">
                                        @forelse($staff->staff_days as $key => $staff_day)
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {{ $staff_day->day_of_week }}
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $staff_day->from_time }} to {{ $staff_day->to_time }}
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="list-group-item">No data</li>
                                        @endforelse
                                    </ul>
                                </div>                    
                            </div>
                            <br>
                            @if($staff->visible)
                            <i class='fas fa-check-circle' style='color:#39f35f; font-size: 18px'></i>  Visible for online Booking Site
                            @else
                            <i class='fas fa-times-circle' style='color:#f33939; font-size: 18px'></i>  Not Visible for online Booking Site
                            @endif
                            <br><br>
                            <center>
                                <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-info btn-rounded waves-effect waves-light">Edit Staff</a>
                            </center>
                        </div>
                    </div>
                </div>      
                <!-- end row -->
            </div>
        </div>

    </div>
</div>




@endsection