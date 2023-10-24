@extends('layout.auth')
@section('app')
    <div class="container-fluid  animate__animated animate__repeat-1 animate__fadeIn text-dark"
        style="margin: 50px auto auto auto">
        <div class="d-flex justify-content-center align-content-center align-items-center">
            <div class="container-lg login-form bg-white p-5 p-lg-5 p-md-3 p-sm-2 shadow-lg">
                <div class="text-center mb-4 text-purple">
                    <i class="display-block">Please login to your account</i>
                </div>
                @if (session('failed'))
                    <div style="z-index: 1"
                        class="animate__animated animate__zoomIn alert alert-danger p-1 rounded-0 text-center">
                        {{ session('failed') }}
                    </div>
                @endif
                @if (session('reload'))
                    <script>
                        setTimeout(() => {
                            location.reload(true);
                        }, 500);
                    </script>
                @endif
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" required
                                class="form-control form-control-lg text-dark bg-light @error('username') border-danger @enderror"
                                name="username" id="username" placeholder="Username" value="{{ @old('username') }}">
                            @error('username')
                                <span class="form-text text-danger mt-0 pt-0 has-error">username is required</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" required
                                class="form-control form-control-lg text-dark bg-light @error('password') border-danger @enderror"
                                name="password" id="password" placeholder="Password">
                            @if ($errors->has('password'))
                                <span
                                    class="form-text text-danger mt-0 pt-0 has-error">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Login</button>
                    </form>
                </form>
                <div class="input-group mb-3">
                    Not an User? <a class="ms-2" href="{{ route('save') }}">sign up</a>
                </div>
            </div>
        </div>
    </div>
@endsection
