<div x-data="view">
    <x-panel::alert />
    <x-panel::loader target="changeStatus,delete" />
    <div class="card">
        <div class="card-header d-flex flex-column gap-4 border-0">
            <div class="border-bottom border-2 border-dark">
                <div class="row align-items-center g-3 py-2">
                    <div class="col-auto text-start flex-shrink-0">
                        <img src="{{ asset('build/panel/images/logo/logo.png') }}" alt="logo"
                            style="width: 110px; height: 110px; object-fit: contain;">
                    </div>
                    <div
                        class="col d-flex flex-column justify-content-center align-items-center text-center min-w-0 py-1">
                        <div class="fw-bolder text-uppercase text-dark"
                            style="font-size: 37px; letter-spacing: 2px; line-height: 1; font-family: Arial, sans-serif;">
                            Estimate
                        </div>
                        <div class="fw-bolder text-uppercase mt-2"
                            style="font-size: 48px; letter-spacing: 2px; color: #1d5fa9; line-height: 1; font-family: 'Times New Roman', Georgia, serif;">
                            Parshva Designers
                        </div>
                    </div>
                </div>
                <div class="border-top border-4 border-dark mt-2"></div>
                <div class="text-center fw-bold text-dark px-2 py-2"
                    style="font-size: 26px; line-height: 1.2; font-family: Arial, sans-serif;">
                    Shop No.3, First Floor, Jain Amardham,Above Abhay Colour Lab, Opp. Inderabai Park,Bhuj - Kutch,370
                    001
                </div>
                <div class="border-top border-4 border-dark pt-1"></div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-6 fs-1">
                    <div class="fw-bold">{{ __('Party Name') }} : {{ $inquiry->customer->name ?? '-' }}</div>
                </div>
                <div class="col-6">
                </div>
                <div class="col-6">
                </div>
                <div class="col-6 fs-1">
                    <div class="fw-bold">{{ __('Inquiry No') }} : {{ $inquiry->id ?? '-' }}</div>
                </div>
                <div class="col-6">
                </div>
                <div class="col-6 fs-1">
                    <div class="fw-bold">{{ __('Date') }} : {{ \Carbon\Carbon::parse($inquiry->date)->format('d/m/Y') ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="card-body border-0 pt-6">
            <div class="table-responsive">
                <table class="table align-middle table-bordered table-sm border-dark rounded px-4 py-3 fs-6 no-footer">
                    <thead>
                        <tr class="text-start fw-bold fs-7 bg-light-warning gs-0">
                            <th class="text-center w-10px">{{ __('company.table.sr_no') }}</th>
                            <th class="text-center min-w-200px">{{ __('company.input.product_name') }}</th>
                            <th class="text-center w-50px">{{ __('company.input.unit') }}</th>
                            <th class="text-center w-20px">{{ __('company.input.width') }}</th>
                            <th class="text-center w-20px">{{ __('company.input.height') }}</th>
                            <th class="text-center w-20px">{{ __('company.input.qty') }}</th>
                            <th class="text-center w-20px">{{ __('company.input.sq_ft') }}</th>
                            <th class="text-center w-20px">{{ __('Rate') }}</th>
                            <th class="text-center w-20px">{{ __('Pesting') }}</th>
                            <th class="text-center w-20px">{{ __('Fitting') }}</th>
                            <th class="text-center w-20px">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @foreach ($inquiry->inquiryJobs as $key => $item)
                            <tr wire:key="{{ $item->id }}">
                                <td class="text-center w-10px">{{ $key + 1 }}</td>
                                <td class="text-center min-w-200px">{{ $item->product_name }}</td>
                                <td class="text-center w-50px">{{ $item->measurement_unit == 'feet' ? 'Sq. Ft' : $item->measurement_unit }}</td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->width }})"></td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->height }})"></td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->qty }})"></td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->sq_ft }})"></td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->rate }})"></td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->pesting_charge }})"></td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->fitting_charge }})"></td>
                                <td class="text-center w-20px" x-text="simplifyValue({{ $item->amount ?? 0 }})"></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-end">Total</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center"><span class="fw-bold text-uppercase">
                                </span>{{ $inquiry->inquiryJobs->sum('sq_ft') ?? '-' }}</td>
                            <td class="text-center">-</td>
                            <td class="text-center"><span class="fw-bold text-uppercase">
                                </span>{{ $inquiry->inquiryJobs->sum('pesting_charge') ?? '-' }}</td>
                            <td class="text-center"><span class="fw-bold text-uppercase">
                                </span>{{ $inquiry->inquiryJobs->sum('fitting_charge') ?? '-' }}</td>
                            <td class="text-center"><span class="fw-bold text-uppercase">
                                </span>{{ $inquiry->inquiryJobs->sum('amount') ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-between mt-4">
                <div class="col-6 border border-gray-300 rounded">
                    <div class="table-responsive px-4 py-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-start fw-bold text-uppercase">
                                        {{ __('company.input.narration') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start">{{ $inquiry->description ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-uppercase">
                                        {{ __('company.input.total_amount') }}</td>
                                    <td class="text-center" x-text="simplifyValue({{ $inquiry->total_amount ?? 0 }})"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">
                                        {{ __('company.input.pesting_charge') }}</td>
                                    <td class="text-center" x-text="simplifyValue({{ $inquiry->pesting_charge ?? 0 }})"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">
                                        {{ __('company.input.fitting_charge') }}</td>
                                    <td class="text-center" x-text="simplifyValue({{ $inquiry->fitting_charge ?? 0 }})"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">
                                        {{ __('company.input.transportation_charge') }}</td>
                                    <td class="text-center" x-text="simplifyValue({{ $inquiry->transportation_charge ?? 0 }})"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">
                                        {{ __('company.input.discount') }}</td>
                                    <td class="text-center" x-text="simplifyValue({{ $inquiry->discount ?? 0 }})"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">
                                        {{ __('company.input.rounded_amount') }}</td>
                                    <td class="text-center" x-text="simplifyValue({{ $inquiry->rounded_amount ?? 0 }})"></td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase border-top">
                                        {{ __('company.input.estimated_amount') }}</td>
                                    <td class="text-center fw-bold border-top" x-text="simplifyValue({{ $inquiry->estimated_amount ?? 0 }})"></td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between mt-4">
                <div class="col-6">
                    <div class="text-start">
                        <p class="fw-bold text-uppercase m-0">Bank Details</p>
                        <p class="m-0">Bank name: Punjab National Bank</p>
                        <p class="m-0">Branch: Bhuj</p>
                        <p class="m-0">A/c No. 07434015003266</p>
                        <p class="m-0">IFSC Code: PUNB0074310</p>
                        <p class="m-0">Account Holder Name: Parshva Designers</p>

                        <p class="underline m-0">Terms & Conditions :</p>
                        <ul>
                            <li>Payment Must Be Advance</li>
                            <li>Delivery Period 3 Days After Conﬁrmation
                                E.& O.E., Subject To Bhuj Jurisdiction</li>
                        </ul>
                    </div>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-end">
                    <div class="border-top border-2 border-dark">
                        <p class="fw-bold"
                            style="font-size: 20px; color: #1d5fa9; line-height: 1; font-family: 'Times New Roman', Georgia, serif;">
                            For, PARSHVA DESIGNERS
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@script('scripts')
    <script>
        Alpine.data('view', () => {
            return {
                simplifyValue(value) {
                    return Number.isInteger(Number(value)) ? Number(value) : Number(value).toFixed(2);
                }
            }
        });
    </script>
@endscript
