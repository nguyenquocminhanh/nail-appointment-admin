@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">New Appointment All</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <a href="{{ route('appointment.read.all') }}" class="btn btn-success waves-effect waves-light" style="float: right;"><i class="fas fa-eye"> Mark As Read All</i></a>
                    <br></br>

                    <h4 class="card-title">New Appointment Data</h4>
                    
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                @if(Auth::user()->role->name == 'admin')
                                <tr>
                                    <th>SL</th>
                                    <th>Staff</th>
                                    <th>Client</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                                @else 
                                <tr>
                                    <th>SL</th>
                                    <th>Client</th>
                                    <th>Phone</th>
                                    <th>Services</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                                @endif
                            </thead>

                            @if(Auth::user()->role->name == 'admin')
                            <tbody>
                                @forelse($appointments as $key => $appointment)
                                <tr @if($appointment->is_admin_read == '0') style="background: #f1f5f7" @endif>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $appointment->user->name }}</td>
                                    <td>{{ $appointment->name }}</td>
                                    <td>{{ $appointment->phone_number }}</td>
                                    <td>{{ date('D, M d, Y', strtotime($appointment->date)) }}</td>
                                    <td>{{ date('g:i A', strtotime($appointment->time)) }}</td>
                                    <td>
                                        @if($appointment->status == '1')
                                        <strong><span class="badge alert-success"> visited </span></strong>
                                        @else
                                        <strong><span class="badge alert-warning"> pending </span></strong>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('appointment.view', $appointment->id) }}" class="btn btn-warning sm" title="View Data"><i class="fas fa-eye"></i></a>
                                        @if($appointment->status == '0')
                                        <a href="{{ route('appointment.check', $appointment->id) }}" class="btn btn-success sm" title="Check Data"><i class="fas fa-check"></i></a>
                                        @endif
                                        <a href="{{ route('appointment.delete', $appointment->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <td colspan="8">No Data Avaialabel</td>
                                @endforelse
                            
                            </tbody>

                            @else
                            <tbody>
                                @forelse($appointments as $key => $appointment)
                                <tr @if($appointment->is_user_read == '0') style="background: #f1f5f7" @endif>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $appointment->name }}</td>
                                    <td>{{ $appointment->phone_number }}</td>
                                    <td>{{ $appointment->services }}</td>
                                    <td>{{ date('D, M d, Y', strtotime($appointment->date)) }}</td>
                                    <td>{{ date('g:i A', strtotime($appointment->time)) }}</td>
                                    <td>
                                        @if($appointment->status == '1')
                                        <strong><span class="badge alert-success"> visited </span></strong>
                                        @else
                                        <strong><span class="badge alert-warning"> pending </span></strong>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('appointment.view', $appointment->id) }}" class="btn btn-warning sm" title="View Data"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <td colspan="8">No Data Avaialabel</td>
                                @endforelse
                            
                            </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>

@endsection