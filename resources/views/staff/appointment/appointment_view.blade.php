@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('appointment.all') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>

                        <h4 class="card-title">Appointment for {{ date('D, M d, Y', strtotime($appointment->date)) }} &nbsp;
                            @if($appointment->date < Carbon\Carbon::today()) 
                            <strong><span class="badge alert-secondary"> past </span></strong>
                            @endif
                            &nbsp;
                            @if($appointment->status == '1')
                            <strong><span class="badge alert-success"> visited </span></strong>
                            @else
                            <strong><span class="badge alert-warning"> pending </span></strong>
                            @endif</h4>
                        <br>
                
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">Client Basic Information</h5>
                                    <hr>
                                    <ul class="list-group">
                                        <li class="list-group-item">Name: {{ $appointment->name }}</li>
                                        <li class="list-group-item">Phone Number: {{ $appointment->phone_number }}</li>
                                        <li class="list-group-item">Email: {{ $appointment->email }}</li>
                                        <li class="list-group-item">Client Notes: {{ $appointment->notes }}</li>
                                        
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="card-title">Appointment Services</h5>
                                    <hr>
                                    <ul class="list-group">
                                        <li class="list-group-item">Date: {{ date('D, M d, Y', strtotime($appointment->date)) }}</li>
                                        <li class="list-group-item">Time: {{ $appointment->time }}</li>
                                        <li class="list-group-item">Services: {{ $appointment->services }}</li>
                                        <li class="list-group-item">Staff: {{ $appointment->user->name }}</li>
                                    </ul>
                                </div>    
          
                            </div>
                            <br><br>

                            @if($appointment->status == '0' && Auth::user()->role->name == 'admin')
                            <center>
                                <a href="{{ route('appointment.check', $appointment->id) }}" class="btn btn-info btn-rounded waves-effect waves-light">Check Visited</a>
                            </center>
                            @endif
                        </div>
                    </div>
                </div>      
                <!-- end row -->
            </div>
        </div>

    </div>
</div>




@endsection