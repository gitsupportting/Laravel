<div class="form-body">
    <form class="form form-admin">
        <div class="row">
            <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
                <div class="form-group">
                    <label class="form-lable">First Name</label>
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" required
                           value="{{ old('first_name', $entity->first_name) }}">
                </div>
            </div>
            <div class="col col-12 col-md-12 col-lg-6 col-xl-6 ">
                <div class="form-group">
                    <label class="form-lable">Last Name</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required
                           value="{{ old('last_name', $entity->last_name) }}">
                </div>
            </div>
            <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
                <div class="form-group">
                    <label class="form-lable">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="E-Mail" required
                           value="{{ old('email', $entity->email) }}">
                </div>
            </div>
            <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
                <div class="form-group">
                    <label class="form-lable">Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="Telephone Number (optional)"
                           value="{{ old('phone', $entity->phone) }}">
                </div>
            </div>
            <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
                <div class="form-group">
                    <label class="form-lable">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
            </div>
            <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
                <div class="form-group">
                    <label class="form-lable">Verify Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Verify Password">
                </div>
            </div>
        </div>
    </form>
    @component('components.form.footer')
        @slot('entity', $entity)
    @endcomponent
</div>
