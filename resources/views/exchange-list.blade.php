@include('layout.home-header')
@php use App\Models\UserBank; @endphp
<main>
    <div class="one-edge-shadow" aria-label="">
        <div class="container">
            <div class="row">
                <div class="container-fluid">
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example"
                        style="margin-top: 12px;float: left;">
                        <button type="button" class="btn btn-success">Buy</button>
                        <button type="button" class="btn btn-danger">Sell</button>
                    </div>

                    <ul class="nav" style="display:flex;">
                        @foreach ($data['coins'] as $coin)
                            <li class="nav-item">
                                <a class="nav-link  @if ($loop->first) active @endif" aria-current="page"
                                    href="#">{{ strtoupper($coin->symbol) }}</a>
                            </li>
                        @endforeach
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="offcanvasNavbarLgDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                More
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="offcanvasNavbarLgDropdown">
                                <li><a class="dropdown-item" href="#">XRP</a></li>
                                <li><a class="dropdown-item" href="#">SOL</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>


                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row d-flex p-2">
            <div class=" col col-md-4">
                <input type="hidden" id="url" value="{{ route('sell.order.fillter') }}">
                <div data-bn-type="text" class="csswc0fcl">Amount</div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control search" placeholder="Enter amount" id="search">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2"
                        onClick="getSearchValue()">Search</button>
                </div>

            </div>
            <div class="col col-md-4">
                <div data-bn-type="text" class="csswc0fcl">Payment</div>
                <div class="">
                    <select class="form-control banks" aria-label="Default select example" onChange="getSearchValue()">
                        @forelse($data['userbanks'] as $banks)
                            <option value="{{ $banks->id }}">{{ $banks->name}}</option>
                        @empty
                            <option selected>---No bank is found---</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="col col-md-2 my-4">

                <button onClick="window.location.reload();" class="cssqev57u">Refresh</button>
            </div>


        </div>
        <div class="container">
            <div class="row" id="reload-content">
                <table class="table  table-responsive-stack" id="tableOne">
                    <thead>
                        <tr>
                            <th scope="col">Advertisers (Completion rate)</th>
                            <th scope="col">
                                Price
                                <div class="low-high">lowest to highest</div>
                            </th>

                            <th scope="col">Limit/Available</th>
                            <th scope="col">Payment</th>
                            <th scope="col">
                                Trade
                                <div class="fee-alart">0 Fee</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['sellorder_list'] as $order)



                            <tr>
                                <td>
                                    <div class="listsell">
                                        <div class="name-id-letter">{{ $order->getuserdetail->last_name ?? 'Unknown' }}
                                        </div>
                                        <a id="C2Cofferlistsell_link_merchant" class="listsell_link_id" href="#"
                                            target="_self"
                                            style="color: rgb(40, 92, 147); margin-left: 8px; margin-right: 0px; text-decoration: none; cursor: pointer;">{{ $order->getuserdetail->first_name }}</a>
                                    </div>
                                    <div class="listsell_order">
                                        <div class="listsell_orders">10 orders</div>
                                        <div class="listsell_orders_line"></div>
                                        <div class="listsell_completion">100.00% completion</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="listsell_price_sec">
                                        <div class="listsell_price">{{ $order->initial_price }}</div>
                                        <div class="listsell_priceINR">INR</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="listsell_price_sec1">
                                        <div class="listsell_available">
                                            <div class="listsell_available-name">Available</div>
                                            <div class="listsell_available_price">{{ $order->coin_volume }}
                                                {{ ucwords($order->coin_id) }}</div>
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    <div class="csstlcbro" style="border-right: 1px solid rgb(234, 236, 239);">

                                        @forelse($order->getpaymentoption as $payoption)
                                            @php $bankname = UserBank::getbankdetail($payoption->user_id,$payoption->bank_id); @endphp
                                            <div class="css1n3cl9k">
                                                <div class="bn-tooltip-box css-1yof1af"
                                                    data-popper-reference-hidden="false" data-popper-escaped="false"
                                                    data-popper-placement="bottom"
                                                    style="position: absolute; left: -7px; top: 29px; transition: opacity 120ms ease-in-out 0s, transform 120ms ease-in-out 0s; opacity: 0; transform: translate3d(0px, 6px, 0px); visibility: hidden;">
                                                    <div data-bn-type="text" class="css-vurnku">{{ $bankname->name }}
                                                    </div>
                                                    <div class="bn-tooltip-arrow css-1u9esp9" data-popper-arrow="true"
                                                        style="position: absolute; left: 19px;"></div>
                                                    <i class="gap-fill"></i>
                                                </div>
                                                <div class="css15b4at0"><a
                                                        data-bn-type="link">{{ $bankname->name }}</a></div>
                                            </div>
                                        @empty
                                            <p>-</p>
                                        @endforelse
                                    </div>

                                </td>
                                <td>
                                    <a href="{{ url('buy/exchange-form/' . Crypt::encrypt($order->id)) }}"
                                        id="C2CofferBuy__btn" class="csss1iig6">Buy {{ $order->getcoin->symbol }}
                                        </button>
                                </td>

                            </tr>
                        @empty

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
</main>
@push('scripts')
@endpush
@include('layout.home-footer')
