@extends('layout.layout')
@section('content')
    @php
        use App\Models\Savings;
    @endphp
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
    @if (!count($customers) == 0)
        <div class="card">
            <div class="card-body pb-1">
                <h4 class="card-title">Search Results for {{ $search }}</h4>
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
                                                <input type="hidden" name="id" value="{{ $customer->id }}">
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
                    <ul class="pagination pagination-sm">
                        <li class="page-item"><a class="page-link text-white" href="#">prev</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link  bg-white text-black border-white" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link text-white" href="#">next</a></li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <h3 class="text-center mt-5">No Records found for {{ $search }}</h3>
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
