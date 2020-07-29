<div class="form-body">
    <div class="row">
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12 ">
            <div class="form-group">
                <label class="form-lable">Organization Name</label>
                <input type="text" name="organization[name]" required="" value="{{old('organization.name', $entity->name)}}" class="form-control" placeholder="Name displayed in header when users sign in">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">First Name</label>
                <input type="text" name="manager[first_name]" required="" value="{{old('manager.first_name', $manager->first_name)}}" class="form-control" placeholder="First Name">
            </div>
        </div>

        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">Last Name</label>
                <input type="text" name="manager[last_name]" required="" value="{{old('manager.last_name', $manager->last_name)}}" class="form-control" placeholder="Last Name">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">Email</label>
                <input type="email" name="manager[email]" required="" value="{{old('manager.email', $manager->email)}}" class="form-control" placeholder="Email">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">Phone Number</label>
                <input type="text" name="manager[phone]" value="{{old('manager.phone', $manager->phone)}}" class="form-control" placeholder="Phone Number">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">Password</label>
                <input type="password" name="manager[password]" value="" class="form-control" placeholder="Password">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">Verify Password</label>
                <input type="password" name="manager[password_confirmation]" value="" class="form-control" placeholder="Verify Password">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">License Cap</label>
                <input type="number" name="organization[license_capacity]" value="{{old('organization.license_capacity', $entity->license_capacity ?? 10)}}" min="0" class="form-control" placeholder="" required>
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
            <div class="form-group">
                <label class="form-lable">Sign Up Date</label>
                <input type="text" name="organization[created_at]"
                       value="{{old('organization.created_at', optional($entity->created_at)->format('d/m/Y'))}}" min="0"
                       class="form-control datepicker-input" placeholder="" required>
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12 ">
            <div class="form-group">
                <label class="form-lable">Notes</label>
                <textarea class="form-control" placeholder="Record any relevant customer information here" name="organization[notes]" style="height: 220px;">{{old('organization.notes', $entity->notes)}}</textarea>
            </div>
        </div>
    </div>
</div>
<div class="table-control">
    <div class="table-control__inner">
        <div class="button-left">
            <a href="/" class="btn btn-primary btn-square">Back</a>
        </div>
        <div class="button_right">
            @if($entity->id && $entity->id != Auth::id())
            <a href="javascript:;" class="btn btn-primary btn-square" onclick="if(confirm('Are you sure you want to delete this organization?')) $('#entity-delete-form').submit();">Delete</a>
            @endif
            <a href="/" class="btn btn-primary btn-square">Cancel</a>
            <button type="submit" class="btn btn-primary btn-square">Save</button>
        </div>
    </div>
</div>
@section('js')
    <script>
        $('.datepicker-input').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    </script>
@endsection
