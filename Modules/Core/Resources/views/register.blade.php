@extends('core::layouts.master')

@section('content')
    <div class="col-sm-6 ml-6">
        <form method="POST" action="{{ route("core.register.user") }}">
            @csrf
            <div class="form-group">
            <label for="exampleInputEmail1">First Name</label>
            <input type="text" class="form-control" id=""  aria-describedby="emailHelp" placeholder="First Name">
            </div>
            <div class="form-group">
            <label for="exampleInputEmail1">Last Name</label>
            <input type="text" class="form-control" id="" aria-describedby="emailHelp" placeholder="Last Name">
            </div>
            <div class="form-group">
            <label for="exampleInputEmail1">Email Address</label>
            <input type="email" class="form-control" id="" aria-describedby="emailHelp" placeholder="Email Address">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
            <label for="exampleInputEmail1">Phone</label>
            <input type="phone" class="form-control" id="" aria-describedby="emailHelp" placeholder="Phone">
            <small id="emailHelp" class="form-text text-muted">We'll never share your phone number with anyone else.</small>
            </div>
            <div class="form-group">
            <label for="exampleInputEmail1">Date of Birth</label>
            <input type="date" class="form-control" id="" aria-describedby="emailHelp" placeholder="Phone">
            </div>
            <div class="form-group">
            <label for="exampleInputEmail1">Gender</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="female" value="f" checked>
                <label class="form-check-label" for="female">
                Female
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="male" value="m">
                <label class="form-check-label" for="exampleRadios2">
                Male
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="others" value="o">
                <label class="form-check-label" for="others">
                Others
                </label>
            </div>
            </div>
            <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <div class="form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
