@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('service.all') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>

                        <h4 class="card-title">Add Service</h4>
                        <br>
                       
                        <form id="myForm" method="POST" action="{{ route('service.store') }}">
                            @csrf
                   
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Service Name</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="name" value="{{old('name')}}">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <br>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Service Price</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="price" value="{{old('price')}}">
                                </div>
                            </div>
                            <br>
                        
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Service Duration</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="duration" value="{{old('duration')}}">
                                </div>
                            </div>
                            <br>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Service Description</label>
                                <div class="form-group col-sm-10">
                                    <textarea class="form-control" name="description">{{old('description')}}</textarea>
                                </div>
                            </div>
                            <br>

                            <center>
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Add Service">
                            </center>
                        </form>
                        
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                name: {
                    required : true,
                }
            },
            messages :{
                name: {
                    required : 'Service Name Required',
                }
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection