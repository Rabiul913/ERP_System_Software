@extends('layouts.backend-layout')
@section('title', 'User')

@section('breadcrumb-title')
    @if($formType == 'edit')  Edit  @else  Create  @endif
    User Info
@endsection

@section('style')
    <style>
        .input-group-addon{
            min-width: 120px;
        }
        .input-group-info .input-group-addon{
            /*background-color: #04748a!important;*/
        }
    </style>
@endsection
@section('breadcrumb-button')
    <a href="{{ route('users.index')}}" class="btn btn-out-dashed btn-sm btn-warning"><i class="fas fa-database"></i></a>
@endsection

@section('sub-title')
    <span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'offset-md-1 col-md-10 offset-lg-2 col-lg-8 my-3')

@section('content')
    @if($formType == 'create')
        {!! Form::open(array('url' => "admin/users",'method' => 'POST', 'class'=>'custom-form')) !!}
    @else
        {!! Form::open(array('url' => "admin/users/$user->id",'method' => 'PUT', 'class'=>'custom-form')) !!}
    @endif
     <div class="row">
         
       
         <div class="col-12">
             <div class="input-group input-group-sm input-group-primary">
                 <label class="input-group-addon" for="name">User Name <span class="text-danger">*</span></label>
                 {{Form::text('name', old('name') ? old('name') : (!empty($user->name) ? $user->name : null),['class' => 'form-control','id' => 'name', 'placeholder' => 'Enter User Name Here', 'required'] )}}
                 @error('name') <p class="text-danger">{{ $errors->first('name') }}</p> @enderror
             </div>
         </div>
         <div class="col-12">
             <div class="input-group input-group-sm input-group-primary">
                 <label class="input-group-addon" for="name">User Email<span class="text-danger">*</span></label>
                 {{Form::text('email', old('email') ? old('email') : (!empty($user->email) ? $user->email : null),['class' => 'form-control','id' => 'email', 'placeholder' => 'Enter User email Here','required'] )}}
                 @error('email') <p class="text-danger">{{ $errors->first('email') }}</p> @enderror
             </div>
         </div>
         <div class="col-12">
             <div class="input-group input-group-sm input-group-primary">
                 <label class="input-group-addon" for="name">Password<span class="text-danger">*</span></label>
                 {{Form::password('password', ['class' => 'form-control','id' => 'password', 'placeholder' => 'Enter User password Here', empty($user) ? 'required' : ''] )}}
                 @error('password') <p class="text-danger">{{ $errors->first('password') }}</p> @enderror
             </div>
         </div>
         <div class="col-12">
             <div class="input-group input-group-sm input-group-primary">
                 <label class="input-group-addon" for="name">Conf. Pass<span class="text-danger">*</span></label>
                 {{Form::password('confirm-password',['class' => 'form-control','id' => 'confirm-password', 'placeholder' => 'Enter User password Here',  empty($user) ? 'required' : '' ] )}}
                 @error('confirm-password') <p class="text-danger">{{ $errors->first('confirm-password') }}</p> @enderror
             </div>
         </div>
         <div class="col-12">
            <div class="input-group input-group-sm input-group-primary">
                <label class="input-group-addon" for="company_name">Company<span class="text-danger">*</span></label>
                {{Form::select('com_id',$companies, old('id') ? old('id') : (!empty($user) ? $user->com_id : null),['class' => 'form-control','id' => 'com_id', 'placeholder' => 'Select User Company', 'required'] )}}
                @error('id') <p class="text-danger">{{ $errors->first('id') }}</p> @enderror
            </div>
        </div>
         <div class="col-12">
             <div class="input-group input-group-sm input-group-primary">
                 <label class="input-group-addon" for="name">User Role<span class="text-danger">*</span></label>
                 {{Form::select('role',$roles, old('role') ? old('role') : (!empty($user) ? $user->roles : null),['class' => 'form-control','id' => 'role', 'placeholder' => 'Select User Role', 'required'] )}}
                 @error('role') <p class="text-danger">{{ $errors->first('role') }}</p> @enderror
             </div>
         </div>
    </div><!-- end row -->
    <div class="row">
        <div class="offset-md-4 col-md-4 mt-2">
            <div class="input-group input-group-sm ">
                <button class="btn btn-success btn-round btn-block py-2">Submit</button>
            </div>
        </div>
    </div> <!-- end row -->
    {!! Form::close() !!}
@endsection
@section('script')
    <script>
       

       
      
    </script>

@endsection
