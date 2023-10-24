@extends('layout.layout')
@section('content')
    @if (session('success'))
        <script>
            const showSuccessAlert = Swal.mixin({
                position: 'top-end',
                toast: true,
                timer: 5500,
                showConfirmButton: false,
                timerProgressBar: false,
            });
            showSuccessAlert.fire({
                icon: 'success',
                text: '{{ session('success') }}',
                padding: '10px',
                width: 'auto'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            const showErrorAlert = Swal.mixin({
                position: 'top-end',
                toast: true,
                timer: 5500,
                showConfirmButton: false,
                timerProgressBar: false,
            });
            showErrorAlert.fire({
                icon: "error",
                titleText: "{{ session('error') }}",
                padding: '10px',
                width: 'auto'
            });
        </script>
    @endif
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active h6">Admin</li>
            <li class="breadcrumb-item h6"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active h6" aria-current="page">Settings</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-sm-8 col-md-8 order-2 order-md-1">
            <div class="card shadow bg-light border-0">
                <div class="card-body">
                    <div class="card-title h6 text-dark">
                        User Information
                    </div>
                    <form action="{{ url('/admin/' . auth()->id() . '/update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input required type="text" class="form-control form-control-lg text-dark bg-light"
                                        name="username" id="username" placeholder="Username"
                                        value="{{ $admin->username }}">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input required type="text" class="form-control form-control-lg text-dark bg-light"
                                        name="name" id="name" placeholder="Name" value="{{ $admin->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control form-control-lg text-dark bg-light"
                                        name="position" id="position" value="super admin" placeholder="Position">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email"
                                        class="form-control form-control-lg text-dark bg-light @error('email') border-danger @enderror"
                                        name="email" id="email" placeholder="Email" value="{{ $admin->email }}">
                                    @if ($errors->has('email'))
                                        <span
                                            class="form-text text-danger mt-0 pt-0 has-error">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password"
                                        class="form-control form-control-lg text-dark bg-light @error('password') border-danger @enderror"
                                        name="password" id="password" placeholder="Password">
                                    @if ($errors->has('password'))
                                        <span
                                            class="form-text text-danger mt-0 pt-0 has-error">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-lg btn-primary me-2 mt-4">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-md-4 order-1 order-md-2">
            <div class="card shadow bg-light border-0 my-4 my-sm-0">
                <div class="card shadow border-0 text-center bg-light p-0">
                    <div class="profile-cover rounded-top" data-background="{{ url('assets/images/faces/face15.jpg') }}"
                        style="background: url('{{ url('assets/images/faces/face15.jpg') }}');"></div>
                    <div class="card-body pb-0">
                        @if (!empty($admin->image))
                            <img width="80" src="{{ url("assets/images/admin/$admin->image") }}"
                                class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="user image" />
                        @else
                            <img width="80" src="{{ url('assets/images/faces/face15.jpg') }}"
                                class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="user image" />
                        @endif
                        <h4 class="h3">{{ auth()->user()->name }}</h4>
                        <h5 class="fw-normal">{{ auth()->user()->position }}</h5>
                        <p class="text-gray mb-4">{{ auth()->user()->email }}</p>
                        <div class="d-flex justify-content-center align-items-center">
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                                <input type="submit" class="btn btn-md btn-dark me-2" value="Logout" />
                            </form>
                            <form action="{{ url('/admin/' . Auth::user()->id . '/destroy') }}" method="post">
                                @csrf
                                <button onclick="confirmDelete(event)" type="button"
                                    class="btn btn-md btn-danger ms-2">Delete Account</button>
                            </form>
                        </div>
                        <style>
                            input.input-file::-webkit-file-upload-button,
                            input.input-file::file-selector-button {
                                border: 0;
                                color: #222;
                                display: none;
                                background: none;
                            }
                        </style>
                        <div class="d-block">
                            <p class="text-gray mb-0 pb-0 mt-3" style="text-align: left">upload display picture</p>
                            <form action="{{ url('/admin/' . $admin->id . '/upload') }}" enctype="multipart/form-data"
                                method="POST">
                                <div class="form-group d-flex">
                                    @csrf
                                    <input required type="file" name="image" accept=".jpg,.jpeg,.png"
                                        class="form-control text-dark bg-light input-file">
                                    <input type="submit" class="btn btn-primary pb-0 mb-0" value="upload">
                                </div>
                                <div style="margin-top: -5px">
                                    @if ($errors->has('image'))
                                        <span
                                            class="form-text text-danger mt-0 pt-0 pb-1 has-error">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.confirmDelete = function(e) {
            e.preventDefault();
            var form = e.target.form;
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
@endsection
