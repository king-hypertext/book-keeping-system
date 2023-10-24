@extends('layout.auth')
@section('app')
    <div class="container-fluid  animate__animated animate__repeat-1 animate__fadeIn text-dark"
        style="margin: 50px auto auto auto">
        <div class="d-flex justify-content-center align-content-center align-items-center">
            <div class="container-lg login-form bg-white p-5 p-lg-5 p-md-3 p-sm-2 shadow-lg">
                <div class="text-center mb-4 text-purple">
                    <i class="display-block">Please create an account</i>
                </div>
                <form action="{{ route('save') }}" method="post">
                    @csrf
                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" @required(true)
                                class="form-control form-control-lg text-dark bg-light @error('username') border-danger @enderror"
                                name="username" id="username" placeholder="Username" value="{{ @old('username') }}">
                            @error('username')
                                <span class="form-text text-danger mt-0 pt-0 has-error">username has already been taken</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" @required(true)
                                class="form-control form-control-lg text-dark bg-light @error('name') border-danger @enderror"
                                name="name" id="name" placeholder="Name" value="{{ @old('name') }}">
                            @error('name')
                                <span class="form-text text-danger mt-0 pt-0 has-error">name is required</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control form-control-lg text-dark bg-light" name="position"
                                id="position" value="super admin" placeholder="Position">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" @required(true)
                                class="form-control form-control-lg text-dark bg-light @error('email') border-danger @enderror"
                                name="email" id="email" placeholder="Email" value="{{ @old('email') }}">
                            @if ($errors->has('email'))
                                <span class="form-text text-danger mt-0 pt-0 has-error">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" @required(true)
                                class="form-control form-control-lg text-dark bg-light @error('password') border-danger @enderror"
                                name="password" id="password" placeholder="Password">
                            @if ($errors->has('password'))
                                <span
                                    class="form-text text-danger mt-0 pt-0 has-error">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                </form>
                <div class="input-group mb-3">
                    Already have an account? <a class="ms-2" href="{{ route('login') }}">login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
