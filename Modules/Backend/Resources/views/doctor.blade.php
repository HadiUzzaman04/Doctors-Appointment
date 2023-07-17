
@extends('layouts.app')
@section('title', $page_title)
@push('styles')
@endpush
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <div class="card-toolbar">
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> {{__('Add New')}}</a>
                    </div>
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-body">
                <!--begin: Datatable-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                    <th>SL</th>
                                        <th>Doctor Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Department</th>
                                        <th>Qualification</th>
                                        <th>Fee</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key=>$doctor)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $doctor->name}}</td>
                                            <td>{{ $doctor->email}}</td>
                                            <td>{{ $doctor->phone}}</td>
                                            <td>{{ $doctor->department->name}}</td>
                                            <td>{{ $doctor->qualification}}</td>
                                            <td>{{ $doctor->fee}}</td>
                                            <td>{{ $doctor->details}}</td>
                                            <td>
                                            @if($doctor->status ==1)
                                                <a href="{{route('doctor.status', $doctor->id)}}" onclick="return confirm('Are you sure?')" class="btn btn-success">Active</a>
                                                @else
                                                <a href="{{route('doctor.status', $doctor->id)}}" onclick="return confirm('Are you sure?')" class="btn btn-danger">Inactive</a>
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{('storage/'.$doctor->avatar)}}" height="70px" width="70px" alt="image">
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Select
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="{{route('doctor.edit', $doctor->id)}}">Edit</a>
                                                        <a class="dropdown-item" onclick="return confirm('Are you sure?')" href="{{route('doctor.delete', $doctor->id)}}">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@include('backend::modal.doctormodal')
@endsection
















