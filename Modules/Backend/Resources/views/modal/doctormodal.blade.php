<!--begin::Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Doctor</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('doctor.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Doctor Name</label>
                        <input type="text" class="form-control" id="recipient-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Email</label>
                        <input type="email" class="form-control" id="recipient-name" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Phone</label>
                        <input type="phone" class="form-control" id="recipient-name" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name">Department</label>
                        <select name="department_id" class="form-control" id="recipient-name" required>
                            @foreach($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Qualification</label>
                        <textarea class="form-control" id="message-text" name="qualification" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Fee</label>
                        <input type="text" class="form-control" id="recipient-name" name="fee" required>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Details</label>
                        <textarea class="form-control" id="message-text" name="details" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Password</label>
                        <input type="password" class="form-control" id="recipient-name" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Image</label>
                        <input type="file" class="form-control" id="recipient-name" name="avatar" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="recipient-status" value="1" name="status" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="recipient-status" value="3" name="role_id" required>
                    </div>
                    <div class="modal-footer">
                        <button type="close" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end: Modal-->