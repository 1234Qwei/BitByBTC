<div class="row">
    <div class="col-12">
        @foreach($banks as $bank)
        <div class="icheck-success d-inline">
            <input type="radio" name="bank_id" id="{{ $bank->name }}" value="{{ $bank->id }}" @if($bank->id == $bank_id) checked @endif/>
            <label for="{{ $bank->name }}">
                {{ ($bank->selected_account == '1') ? 'Bank account details' : 'UPI details' }}
            </label>
        </div>

        @if($bank->selected_account == '1')
        <table class="table table-striped table-bordered mt-3">
            <tbody class="tbody">
                <tr data-index="0" class="entry">
                    <td class="cell data bold">Account Name.</td>
                    <td class="cell data">{{ $bank->name }}</td>
                    <td class="cell data center"><a role="button" class="cpy-btn" data-clipboard-text="{{ $bank->name }}"><i class="fa fa-copy"></i></a></td>
                    <td class="cell data center"><a tabindex="0" title="QR Code" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-url="{{ $bank->account_number }}"><i class="fa fa-qrcode"></i></a>
                    </td>
                </tr>
                <tr data-index="0" class="entry">
                    <td class="cell data bold">Account No.</td>
                    <td class="cell data">{{ $bank->account_number }}</td>
                    <td class="cell data center"><a role="button" class="cpy-btn" data-clipboard-text="{{ $bank->account_number }}"><i class="fa fa-copy"></i></a></td>
                    <td class="cell data center"><a tabindex="0" title="QR Code" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-url="{{ $bank->account_number }}"><i class="fa fa-qrcode"></i></a>
                    </td>
                </tr>
                <tr data-index="0" class="entry">
                    <td class="cell data bold">Bank Name.</td>
                    <td class="cell data">{{ $bank->bank_name }}</td>
                    <td class="cell data center"><a role="button" class="cpy-btn" data-clipboard-text="{{ $bank->bank_name }}"><i class="fa fa-copy"></i></a></td>
                    <td class="cell data center"><a tabindex="0" title="QR Code" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-url="{{ $bank->bank_name }}"><i class="fa fa-qrcode"></i></a></td>
                </tr>
                <tr data-index="0" class="entry">
                    <td class="cell data bold">Account Type.</td>
                    <td class="cell data">{{ ($bank->account_type == '1') ? 'Savings' : 'Current' }}</td>
                    <td class="cell data center"></td>
                    <td class="cell data center"></td>
                </tr>
                <tr data-index="0" class="entry">
                    <td class="cell data bold">IFSC Code.</td>
                    <td class="cell data">{{ $bank->ifsc_code }}</td>
                    <td class="cell data center"><a role="button" class="cpy-btn" data-clipboard-text="{{ $bank->ifsc_code }}"><i class="fa fa-copy"></i></a></td>
                    <td class="cell data center"><a tabindex="0" title="QR Code" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-url="{{ $bank->ifsc_code }}"><i class="fa fa-qrcode"></i></a></td>
                </tr>
            </tbody>
        </table>
        @else
        <table class="table table-striped table-bordered mt-3">
            <tbody class="tbody">
                <tr data-index="0" class="entry">
                    <td class="cell data bold"></td>
                    <td class="cell data">{{ $bank->upi }}</td>
                    <td class="cell data center"></td>
                    <td class="cell data center"><a role="button" class="cpy-btn" data-clipboard-text="{{ $bank->upi }}"><i class="fa fa-copy"></i></a></td>
                    <!-- <td class=" cell data center"><a tabindex="0" title="QR Code" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-url="{{ $bank->upi }}"><i class="fa fa-qrcode"></i></a></td> -->
                </tr>
            </tbody>
        </table>
        <div class="form-group text-center mt-3">
            <label>OR <br /> SCAN HERE!</label>
            <div class="justify-content">
                <div class="deposit-code-loader hide" id="depositCodeLoader">
                    <i class="fas fa-sync-alt fa-spin fa-3x deposit-loader" aria-hidden="true"></i>
                </div>
                @php $upiURL = 'https://upiqr.in/api/qr/?name=ANYSWAP&vpa='. $bank->upi; @endphp
                <div class="img-upi"><img src="{{ $upiURL }}"></div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    <div id="qrcode" style="display: none; width: auto; height: auto; padding: 15px;"></div>
</div>