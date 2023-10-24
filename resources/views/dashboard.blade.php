@extends('layout.layout')
@section('content')
    @php
        use App\Models\Savings;
    @endphp
    <h3>Dashboard</h3>
    <div class="row">
        {{-- <span class="mdi mdi-arrow-top-right icon-item"></span> --}}
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-center align-items-center justify-items-center">
                        <h3 class="d-block fs-5 text-success currency_font"><span class="text-success fw-semi-bold">+</span>
                            @toCedis($revenue)</h3>
                    </div>
                    <h6 class="text-center text-muted font-weight-normal">Total Revenue</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-center align-items-center justify-items-center">
                        <h3 class="d-block fs-5 text-danger currency_font"><span class="fw-semi-bold">-</span>
                            @toCedis($withdrawals)</h3>
                    </div>
                    <h6 class="text-center text-muted font-weight-normal">Total Withdrawals</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-center align-items-center justify-items-center">
                        <h3 class="d-block fs-5 text-primary currency_font"><span class="text-primary fw-semi-bold">+</span>
                            @toCedis($balance)</h3>
                    </div>
                    <h6 class="text-center text-muted font-weight-normal">Balance</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-center align-items-center justify-items-center">
                        <div class="icon icon-box-success mb-2 me-3">
                            <span class="mdi mdi-account-multiple icon-item"></span>
                        </div>
                        <h3 class="d-block fs-5">{{ $customers->count() ?? '0' }}</h3>
                    </div>
                    <h6 class="text-center text-muted font-weight-normal">Total Customers</h6>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container px-0 mx-0"> --}}
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
    @if (session('delete'))
        <script>
            Swal.fire(
                "Deleted!",
                "{{ session('delete') }}",
                "success"
            )
        </script>
    @endif
    @if (count($customers) == 0)
        <h3 class="text-center mt-5">No Records Available</h3>
    @else
        <div class="card">
            <div class="card-body pb-1">
                <h4 class="card-title">Customers</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Customer</th>
                                <th>Card Number</th>
                                <th>Daily savings</th>
                                <th>Total Savings</th>
                                <th>Withdrawals</th>
                                <th>Current Balance</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <style>
                                .table td {
                                    padding: 4px !important;
                                }
                            </style>
                            @foreach ($customers as $num => $customer)
                                <tr class="text-white">
                                    <td>{{ $num + 1 }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->card_number }}</td>
                                    <td class="text-success currency">@toCedis($customer->daily_payable_amount)<i class="mdi mdi-arrow-left"></i>
                                    </td>
                                    <td class="text-warning currency">@toCedis(Savings::where('customer_id', $customer->id)->sum('deposit_amount'))<i class="mdi mdi-arrow-up"></i>
                                    </td>
                                    <td class="text-danger currency"><span class="fw-semi-bold">-</span> @toCedis(Savings::where('customer_id', $customer->id)->sum('withdrawal_amount'))
                                    </td>
                                    <td class="text-primary currency"><span class="fw-semi-bold">+</span>@toCedis(Savings::where('customer_id', $customer->id)->sum('deposit_amount') - Savings::where('customer_id', $customer->id)->sum('withdrawal_amount'))
                                    </td>
                                    <td>{{ $customer->created_at }}</td>
                                    <td>
                                        <style>
                                            .action-icon {
                                                cursor: pointer;
                                                font-size: 1.0rem;
                                                color: var(---bs-white);
                                            }
                                        </style>
                                        <div class="d-flex">
                                            <a href="{{ url('customer/' . $customer->id . '\/') }}"
                                                class="btn btn-sm btn-outline-primary mx-2 text-center"
                                                title="view customer details">view
                                            </a>
                                            <form action='{{ url('/customer/' . $customer->id . '/delete') }}'
                                                method="post">
                                                @csrf
                                                <button onclick="confirmDelete(event)"
                                                    class="btn btn-sm btn-outline-danger ms-2 text-center"
                                                    title="delete customer">
                                                    delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
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
                    <div class="d-flex justify-content-end mt-4">
                        {{ $customers->links('layout.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- </div> --}}
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
