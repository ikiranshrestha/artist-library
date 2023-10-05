@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form id = "login-form" method="POST" action="">
                            @csrf

                            <!-- Email -->
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
    <script>
        document.getElementById('login-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            console.log(formData);
            axios.post('/api/user/login', formData)
                .then(response => {
                    console.log(response.data.message);
                    if (response.data.message === 'Logged in successfully') {
                        const tokenType = response.data.token_type;
                        const accessToken = response.data.access_token;
                        const completeToken = tokenType + " " + accessToken;

                        localStorage.setItem('authToken', completeToken);
                        window.location.href = '/dashboard';
                    } else {
                        console.log(response.data);
                    }
                })
                .catch(error => {
                    console.error(error.response.data);
                });
        });
    </script>
@endsection

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>