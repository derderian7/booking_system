@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Add user</h4> 
        </div>
        <div class="card-body">
            <form action="{{ route('new_user')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group">
                    <div class="col-sm-10">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                </div>
                    <div class="form-group">
                    <div class="col-sm-10">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label>Password</label>
                        <input type="text" class="form-control" name="password">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Role</label>
                    <input type="text" name="role">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </div>
            </form>
            </div>        
    </div>
@endsection