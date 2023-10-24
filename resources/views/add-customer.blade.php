@extends('layout.layout')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active h6">Admin</li>
            <li class="breadcrumb-item h6"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active h6" aria-current="page">Add Customer</li>
        </ol>
    </nav>
    <div class="container">
        <div class="card bg-light">
            <div class="card-body text-black">
                <h4 class="card-title text-center text-dark">Add New Customer</h4>
                {{-- @if (session('status'))
                    <div style="z-index: 1"
                        class="animate__animated animate__zoomIn alert alert-danger p-1 rounded-0 text-center">
                        {{ session('status') }}
                    </div>
                @endif --}}
                @if ($errors->any())
                    <div style="z-index: 1"
                        class="animate__animated animate__zoomIn alert alert-danger p-1 rounded-0 text-center">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ url('add-customer') }}">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 col-form-label">Name</label>
                                <div class="col-sm-12">
                                    @csrf
                                    <input type="text" @required(true) name="name"
                                        class="form-control form-control-lg text-dark bg-light border-primary"
                                        id="name" placeholder="Customer Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateOfBirth" class="col-sm-12 col-form-label">Date of Birth</label>
                                <div class="col-sm-12">
                                    <input type="text" max="@php echo date('Y-m-d') @endphp" @required(true)
                                        name="date_of_birth"
                                        class="form-control form-control-lg text-dark bg-light border-primary"
                                        id="dateOfBirth" placeholder="Date of Birth">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-sm-12 col-form-label">Phone Number</label>
                                <div class="col-sm-12">
                                    <input type="text" @required(true) max="14" name="phone"
                                        class="form-control form-control-lg text-dark bg-light border-primary"
                                        id="phone" placeholder="Phone Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="card_number" class="col-sm-12 col-form-label">Card Number</label>
                                <div class="col-sm-12">
                                    <input type="number" @required(true) name="card_number"
                                        class="form-control form-control-lg text-dark bg-light border-primary"
                                        id="card_number" placeholder="Card Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="next_of_kin" class="col-sm-12 col-form-label">Next of Kin</label>
                                <div class="col-sm-12">
                                    <input type="text" @required(true) name="next_of_king"
                                        class="form-control form-control-lg text-dark bg-light border-primary"
                                        id="next_of_kin" placeholder="Next of Kin">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="amount" class="col-sm-12 col-form-label">Daily Payable Amount</label>
                                <div class="col-sm-12">
                                    <input type="number" @required(true) name="daily_payable_amount"
                                        class="form-control form-control-lg text-dark bg-light border-primary"
                                        id="amount" placeholder="Daily Savings Amount">
                                </div>
                            </div>
                            <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" class="btn btn-primary" value="Save" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var date_of_birth = document.getElementById('dateOfBirth')
        var phone_number = document.getElementById('phone')
        if (date_of_birth) {
            date_of_birth.addEventListener('focus', () => {
                date_of_birth.type = "date"
            })
            date_of_birth.addEventListener('blur', () => {
                date_of_birth.type = "text"
            })
        }
        if (phone_number) {
            phone_number.addEventListener('focus', () => {
                phone_number.type = "number"
                phone_number.max = "14"
            })
            phone_number.addEventListener('blur', () => {
                phone_number.type = "text"
            })
        }
    </script>
@endsection
