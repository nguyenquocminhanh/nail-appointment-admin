@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('staff.all') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>

                        <h4 class="card-title">Add Staff Member</h4>
                        <br>
                       
                        <form id="myForm" method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Full Name</label>
                                    <div class="form-group col-sm-10">
                                        <input class="form-control" type="text" name="name" value="{{old('name')}}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Email</label>
                                    <div class="form-group col-sm-10">
                                        <input class="form-control" type="text" name="email" value="{{old('email')}}">
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Username</label>
                                    <div class="form-group col-sm-10">
                                        <input class="form-control" type="text" name="username" value="{{old('username')}}">
                                        @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Password</label>
                                    <div class="form-group col-sm-10">
                                        <input class="form-control" type="password" name="password">
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="example-text-input" class="col-sm-6 col-form-label">Phone Number</label>
                                    <div class="form-group col-sm-10">
                                        <input class="form-control" type="text" name="phone_number" value="{{old('phone_number')}}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="example-text-input" class="col-sm-2 col-form-label">Profile Image</label>
                                            <div class="form-group col-sm-10">
                                                <input class="form-control" type="file" name="profile_image" id="image">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <img id="showImage" class="rounded avatar-lg" src="{{ url('upload/no_image.jpg') }}" class="card-img-top">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Service Offered During Online Booking</label>
                                    <div class="form-group col-sm-10">
                                        <p class="checkbox">
                                            <input type="checkbox" id="service-checkall" onClick="for(c in document.getElementsByName('service_id[]')) 
                                            document.getElementsByName('service_id[]').item(c).checked=this.checked"> Select All
                                        </p>
                                        <hr>
                                        @forelse($services as $key => $service)
                                        <p class="checkbox">
                                            <input type="checkbox" class="service-checkbox" name="service_id[]" value="{{ $service->id }}"
                                            @if(is_array(old("service_id")) && in_array($service->id, old("service_id"))) checked @endif
                                            > {{$service->name}}
                                        </p>
                                        @empty
                                        <p class="checkbox">
                                            No Services Offered
                                        </p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-lg-6 px-0">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Staff Working Hours</label>
                                    @php
                                        $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    @endphp
                                    <p class="checkbox">
                                        <input type="checkbox" onClick="for(c in document.getElementsByName('day[]')) 
                                        document.getElementsByName('day[]').item(c).checked=this.checked" id="day-checkall"> Select All
                                    </p>
                                    <hr>
                                    <div class="form-group col-sm-12">
                                        @foreach($days_of_week as $key => $day)
                                        <div class="row checkbox"> 
                                            <div class="col-md-3 d-sm-flex align-items-center">
                                                <input type="checkbox" name="day[]" value="{{ $key }}" class="day-checkbox"
                                                @if(is_array(old("day")) && in_array($key, old("day"))) checked @endif
                                                >&nbsp; {{ $day }}
                                            </div>
                                            <div class="col-md-9">
                                                From: &nbsp;
                                                <input name="from_time[]" class="time-picker" type="time" value="{{ old('from_time.'.$key) }}">
                                                &nbsp; &nbsp; &nbsp;
                                                To: &nbsp;
                                                <input name="to_time[]" class="time-picker" type="time" value="{{ old('to_time.'.$key) }}">
                                            </div>   
                                        </div>
                                        @endforeach
                        
                                        @error('to_time.*')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="example-text-input" class="col-sm-6 col-form-label">Online Booking Visibility</label>
                                    <div class="form-group col-sm-10">
                                        <input type="checkbox" checked name="visible" value="1">
                                        When enabled, this staff member will appear in you Booking Site.
                                    </div>
                                </div>
                            </div>

                            <br>

                            <center>
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Add Staff Member">
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
                username: {
                    required : true,
                }, 
                email: {
                    required : true,
                    email: true
                }, 
                password: {
                    required : true,
                    minlength: 5,
                    maxlength: 15
                }, 
            },
            messages :{
                name: {
                    required : 'Staff Name Required',
                },
                username: {
                    required : 'Staff Username Required',
                },
                email: {
                    required : 'Staff Email Required',
                },
                password: {
                    required : 'Staff Password Required',
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
        if ($('.service-checkbox:checked').length == $('.service-checkbox').length) {
            $('#service-checkall').prop('checked', true); 
        } else {
            $('#service-checkall').prop('checked', false); 
        }

        if ($('.day-checkbox:checked').length == $('.day-checkbox').length) {
            $('#day-checkall').prop('checked', true); 
        } else {
            $('#day-checkall').prop('checked', false); 
        }

        $(".service-checkbox").change(function(){
            if ($('.service-checkbox:checked').length == $('.service-checkbox').length) {
                $('#service-checkall').prop('checked', true); 
            } else {
                $('#service-checkall').prop('checked', false); 
            }
        });

        $(".day-checkbox").change(function(){
            if ($('.day-checkbox:checked').length == $('.day-checkbox').length) {
                $('#day-checkall').prop('checked', true); 
            } else {
                $('#day-checkall').prop('checked', false); 
            }
        });

        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>

<style type="text/css">
    input[type="checkbox"] {
        zoom: 1.3;
        font-size: 18px;
    }
    .checkbox {
        font-size: 15px;
    }
    .time-picker {
        padding: 0.47rem 0.75rem;
        font-size: .9rem;
        font-weight: 400;
        line-height: 1.5;
        color: #505d69;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        appearance: none;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        margin: 0;
        font-family: inherit;
    }
</style>


@endsection