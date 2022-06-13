@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('business') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>

                        <h4 class="card-title">Add Business Page</h4>
                        <br></br>
                       
                        <form id="myForm" method="POST" action="{{ route('business.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Business Name</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="name">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Business Phone Number</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="phone_number">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Business Address</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="address">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Business Logo Image</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="file" name="logo_image" id="logo_image">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <img id="showLogoImage" class="rounded avatar-lg" src="{{ url('upload/no_image.jpg') }}" class="card-img-top">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Business Cover Image</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="file" name="cover_image" id="cover_image">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <img id="showCoverImage" class="rounded avatar-lg" src="{{ url('upload/no_image.jpg') }}" class="card-img-top">
                                </div>
                            </div>

                            <center>
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Add Business">
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
                }, 
                phone_number: {
                    required : true,
                }, 
                address: {
                    required : true,
                }, 
                logo_image: {
                    required : true,
                }, 
                cover_image: {
                    required: true
                }
            },
            messages :{
                name: {
                    required : 'Business Name Required',
                },
                phone_number: {
                    required : 'Business Phone Number Required',
                },
                address: {
                    required : 'Business Address Required',
                },
                logo_image: {
                    required : 'Logo Image Required',
                },
                cover_image: {
                    required : 'Cover Image Required',
                },
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#logo_image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showLogoImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });

        $('#cover_image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showCoverImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>


@endsection