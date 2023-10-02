@extends('core::layouts.master')

@section('content')
    <div class="col-sm-6 ml-6">
        <form method="POST" action="{{ route("core.register.user") }}">
            @csrf
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" aria-describedby="emailHelp" placeholder="First Name">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" aria-describedby="emailHelp" placeholder="Last Name">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Email Address">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" aria-describedby="emailHelp" placeholder="Phone">
                <small id="emailHelp" class="form-text text-muted">We'll never share your phone number with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" aria-describedby="emailHelp" placeholder="Date of Birth">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="f" checked>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="m">
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="others" value="o">
                    <label class="form-check-label" for="others">Others</label>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name">Address</label>
                <input type="text" class="form-control" id="address" name="address" aria-describedby="emailHelp" placeholder="Address">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>

            <button type="submit" class="btn btn-primary" name="register_btn">Submit</button>
        </form>
    </div>
@endsection