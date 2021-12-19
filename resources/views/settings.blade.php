@extends('layouts.layoutpage')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Settings</h4></div>
                <div class="panel-body">
                   <div>
                       @if(Session::has('messagefail'))
                       <div class="alert alert-success alert-dismissable fade in">
                           <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                           {{ Session::get('messagefail') }}
                       </div>
                       @endif    
                   </div>
                    <form action="{{action('PhotoGallary@doSettings', $settings->id)}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="project_name">Project Name (without space)</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" value="{{ $settings->project_name }}">
                        </div>
                        <div class="form-group">
                            <label for="emailToResetPassword">Email To Reset Password</label>
                            <input type="email" class="form-control" id="projemailToResetPassword" name="emailToResetPassword" value="{{ $settings->email_to_reset_password }}">
                            <br>
                            <p class="alert alert-danger">Please be careful! This email helps user to reset password. <b>Please use varified gmail for this</b>. You don't need to use your personal email. It will be better to create a new email for this. After that reduce gmail security. (in Gmail) My Account -> Sign-in and Security -> 2-Step Verification : Off -> Allow less secure apps: ON</p>  
                        </div>
                        <div class="form-group">
                            <label for="passwordOfEmail">Password of the Email</label>
                            <input type="password" class="form-control" id="passwordOfEmail" name="passwordOfEmail" value="{{ $settings->password_of_email }}">
                        </div>
                        <p class="alert alert-danger">Password for that email to use for the project not for the user personal use.</p>
                        <br>
                        <div class="form-group">
                            <label for="maxFileSize">Uploaded File Size Limit in KB</label>
                            <input type="number" class="form-control" id="maxFileSize" name="maxFileSize" value="{{ $settings->max_uploaded_file_size }}">
                            <br>
                            <p class="alert alert-danger">Your server maximum file upload limit {{ ini_get('upload_max_filesize') }}, so you can't put the value in here more than  {{ ini_get('upload_max_filesize') }}. <b>Please, input only integer value!</b></p>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="maxFileSize">Display Total Images</label>
                            <input type="number" class="form-control" id="totalImgDisplay" name="totalImgDisplay" value="{{ $settings->total_images_to_display }}">
                            <br>
                            <p class="alert alert-danger">Please, set this value for displaying images in one page. If you give the value '0' or empty then works default value. <b>Please, input only integer value!</b></p>
                        </div>
                        
                        <button id="btnUpload" type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
