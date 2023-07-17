@extends('layouts.app')
@section('title', $page_title)
@push('styles')
@endpush
@section('content')
<form action="{{route('doctor.update', $data->id)}}" method="POST" style="width:85%; margin-left: 35px;" enctype="multipart/form-data">
    @csrf
    @method('PUT')    
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Doctor Name</label>
        <input type="text" class="form-control" id="recipient-name" name="name" value="{{$data->name}}" required>
    </div>
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Email</label>
        <input type="email" class="form-control" id="recipient-name" name="email" value="{{$data->email}}"  required>
    </div>
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Phone</label>
        <input type="phone" class="form-control" id="recipient-name" name="phone" value="{{$data->phone}}" required>
    </div>
    <div class="form-group">
        <label for="recipient-name">Department</label>
        <select name="department_id" class="form-control" id="recipient-name" required>
        <option>Select Department</option>
            @foreach($departments as $department)
            <option value="{{$department->id}}" @if($data->department_id==$department->id) selected="selected" @endif >{{$department->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="message-text" class="col-form-label">Qualification</label>
        <textarea class="form-control" id="message-text" name="qualification" required>{{$data->qualification}}</textarea>
    </div>
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Fee</label>
        <input type="text" class="form-control" id="recipient-name" name="fee" value="{{$data->fee}}" required>
    </div>
    <div class="form-group">
        <label for="message-text" class="col-form-label">Details</label>
        <textarea class="form-control" id="message-text" name="details" required>{{$data->details}}</textarea>
    </div>
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Image</label>
        <input type="file" class="form-control" id="recipient-name" name="avatar" value="{{$data->avatar}}" >
    </div>
    <div class="footer">
        <button type="close" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" id="submit" class="btn btn-primary">Save</button>
    </div>
</form>
@endsection