@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Staff All</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <a href="{{ route('staff.add') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-plus-circle"> Add Staff</i></a>

                    <br></br>

                    <h4 class="card-title">Staff Data</h4>
                    
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th width="10%">Online Booking</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($staffs as $key => $staff)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $staff->name }}</td>
                                    <td><img src="{{ $staff->profile_image != null ? asset($staff->profile_image) : url('upload/no_image.jpg') }}" style="width: 60px; height: 50px;"></td>
                                    <td>{{ $staff->phone_number }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td class="text-center">{{ $staff->visible == '1' ? 'Yes' : "No" }}</td>
                                    <td>
                                        <a href="{{ route('staff.view', $staff->id) }}" class="btn btn-warning sm" title="View Data"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('staff.delete', $staff->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>

@endsection