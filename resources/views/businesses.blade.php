@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Businesses</h4> </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>status</th>
                        <th>opening_hours</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> @foreach ($businesses as $business)
                    <tr>
                        <td>{{$business->id}}</td>
                        <td>{{$business->name}}</td>
                        <td>{{$business->status}}</td>
                        <td>{{$business->opening_hours}}</td>
                        <td><a href="{{ url('business-edit/'.$business->id)}}" class="btn btn-primary">Edit</a>
                            <a href="{{ url('business-delete/'.$business->id)}}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>        
    </div>
@endsection