@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Business</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    @if(App\Models\Business::count() == 0)
                    <a href="{{ route('business.add') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-plus-circle"> Add Business</i></a>
                    @endif

                    <br></br>

                    <h4 class="card-title">Business Data</h4>
                    
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Business Name</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Logo</th>
                                    <th>Cover</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @if($business)
                                <tr>
                                    <td>{{ $business->name }}</td>
                                    <td>{{ $business->phone_number }}</td>
                                    <td>{{ $business->address }}</td>
                                    <td><img src="{{ $business->logo_image != null ? asset($business->logo_image) : url('upload/no_image.jpg') }}" style="width: 60px; height: 50px;"></td>
                                    <td><img src="{{ $business->cover_image != null ? asset($business->cover_image) : url('upload/no_image.jpg') }}" style="width: 60px; height: 50px;"></td>
                                    <td>
                                        <a href="{{ route('business.edit') }}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>



@endsection