@extends('layout.layout')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active h6">Admin</li>
            <li class="breadcrumb-item h6"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active h6" aria-current="page">Customer</li>
            <li class="breadcrumb-item active h6" aria-current="page">Customer - {{ $customer->name }}</li>
        </ol>
    </nav>
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
                icon: "success",
                text: "{{ session('success') }}",
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
                title: "{{ session('error') }}",
                text: 'Not enough funds to perform this transaction!',
                padding: '10px',
                width: 'auto'
            });
        </script>
    @endif
    <div class="row">
        <div class="col-sm-7 order-2">
            <div class="row">
                <div class="col-xl-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-center align-items-center justify-items-center">
                                <h3 class="d-block fs-5 text-success currency_font"><span
                                        class="text-success fw-semi-bold">+</span>
                                    @toCedis($total_savings ?? 0)</h3>
                            </div>
                            <h6 class="text-center text-muted font-weight-normal">Total Savings</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-center align-items-center justify-items-center">
                                <h3 class="d-block fs-5 text-danger currency_font"><span class="fw-semi-bold">-</span>
                                    @toCedis($withdrawals ?? 0)</h3>
                            </div>
                            <h6 class="text-center text-muted font-weight-normal">Total Withdrawals</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-center align-items-center justify-items-center">
                                <h3 class="d-block fs-5 text-primary currency_font"><span
                                        class="text-primary fw-semi-bold">+</span>
                                    @toCedis($balance ?? 0)</h3>
                            </div>
                            <h6 class="text-center text-muted font-weight-normal">Available Balance</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                @if (count($customer_data) == 0)
                    <h3 class="text-center text-white my-5">No Transaction History</h3>
                @else
                    <div class="card-body pb-1">
                        <h4 class="card-title text-center">
                            {{ $customer->name . ' ' . '[ Card Number: ' . $customer->card_number . ' ]' }}</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Trans Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                @foreach ($customer_data as $index => $data)
                                    <tbody>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->trans_type ?? 'deposit' }}</td>
                                        <td>
                                            @if ($data->trans_type == 'withdraw')
                                                @toCedis($data->withdrawal_amount)
                                            @else
                                                @toCedis($data->deposit_amount)
                                            @endif
                                        </td>
                                        <td>{{ $data->date }}</td>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                        <style>
                            .page-link:hover,
                            .page-link:focus {
                                color: var(---bs-dark);
                                background-color: var(---bs-white);
                                box-shadow: none;
                            }
                        </style>
                        <div class="d-flex justify-content-end mt-4">
                            {{ $customer_data->links('layout.pagination') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-sm-5 order-1">
            <style>
                .accordion-button.collapsed {
                    background-color: var(--bs-gray-100);
                    color: var(--bs-dark);
                }

                .accordion-button:not(.collapsed) {
                    background-color: var(--bs-gray-300);
                    color: var(--bs-dark);
                }

                .accordion-button:focus {
                    outline: none;
                    box-shadow: none;
                }
            </style>
            <div class="accordion border-0 outline-0" id="accordionExample">
                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Update Daily Savings (Deposit)
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body p-0">
                            <div class="card m-0 p-0 bg-white">
                                <div class="card-body border-0 text-dark">
                                    <h4 class="card-title"></h4>
                                    <form action="{{ url('/customer/' . $customer->id . '/deposit') }}" method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <label  class="col-sm-3 col-form-label">Card
                                                Number</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control text-dark bg-light"
                                                    value="{{ $customer->card_number }}" disabled  />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label  class="col-sm-3 col-form-label">Customer
                                                Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control text-dark bg-light"
                                                    value="{{ $customer->name }}" disabled  />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label  class="col-sm-3 col-form-label">Amount</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control text-dark bg-light"
                                                    value="{{ $customer->daily_payable_amount }}" disabled
                                                     />
                                            </div>
                                        </div>
                                        <input type="hidden" name="trans_type" value="deposit" />
                                        <div class="d-flex justify-content-end">
                                            <input type="submit" class="btn btn-primary" value="Update/Deposit" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                            Update Daily Savings (Withdraw)
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body p-0">
                            <div class="card m-0 p-0 bg-white">
                                <div class="card-body border-0 text-dark">
                                    <h4 class="card-title"></h4>
                                    <form action="{{ url('/customer/' . $customer->id . '/withdraw') }}" method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <label  class="col-sm-3 col-form-label">Card
                                                Number</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control text-dark bg-light"
                                                    value="{{ $customer->card_number }}" disabled  />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label  class="col-sm-3 col-form-label">Customer
                                                Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control text-dark bg-light"
                                                    value="{{ $customer->name }}" disabled  />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="withdraw" class="col-sm-3 col-form-label">Amount</label>
                                            <div class="col-sm-9">
                                                <input required type="number" class="form-control text-dark bg-light"
                                                    name="amount" id="withdraw"
                                                    placeholder="Enter the amount to withdraw" />
                                            </div>
                                        </div>
                                        <input type="hidden" name="trans_type" value="withdraw" />
                                        <div class="d-flex justify-content-end">
                                            <input type="submit" class="btn btn-primary" value="Update/Withdraw" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Edit Customer Info
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body p-0">
                            <div class="card bg-light">
                                <div class="card-body text-black">
                                    <form action="{{ url('/customer/' . $customer->id . '/edit') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-0">
                                            <label for="name" class="pb-0  col-form-label">Name</label>
                                            <div class="">
                                                <input type="text" required name="name"
                                                    value="{{ $customer->name }}"
                                                    class="form-control  text-dark bg-light border-primary"
                                                    id="name" />
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="dateOfBirth" class="pb-0  col-form-label">Date of
                                                Birth</label>
                                            <div class="">
                                                <input type="text" required name="date_of_birth"
                                                    value="{{ $customer->date_of_birth }}"
                                                    class="form-control  text-dark bg-light border-primary"
                                                    id="dateOfBirth" />
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="phone" class="pb-0  col-form-label">Phone
                                                Number</label>
                                            <div class="">
                                                <input type="text" required max="14" name="phone"
                                                    value="{{ $customer->phone }}"
                                                    class="form-control  text-dark bg-light border-primary"
                                                    id="phone" />
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="card_number" class="pb-0  col-form-label">Card
                                                Number</label>
                                            <div class="">
                                                <input type="number" required name="card_number"
                                                    value="{{ $customer->card_number }}"
                                                    class="form-control  text-dark bg-light border-primary"
                                                    id="card_number" />
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="next_of_kin" class="pb-0  col-form-label">Next of
                                                Kin</label>
                                            <div class="">
                                                <input type="text" required name="next_of_king"
                                                    value="{{ $customer->next_of_king }}"
                                                    class="form-control  text-dark bg-light border-primary"
                                                    id="next_of_kin" />
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="amount" class="pb-0  col-form-label">Daily Payable
                                                Amount</label>
                                            <div class="">
                                                <input type="number" required name="daily_payable_amount"
                                                    value="{{ $customer->daily_payable_amount }}"
                                                    class="form-control  text-dark bg-light border-primary" id="amount"
                                                    placeholder="Daily Savings Amount">
                                            </div>
                                        </div>
                                        <div class="form-group mb-0 d-flex justify-content-end">
                                            <input type="submit" class="btn btn-primary mt-3" value="Save" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
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
