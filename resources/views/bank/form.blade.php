@include('layout.home-header')
<div class="header-out-1">
    <div class="container">
        <div class="package-2">
            <div class="package-title"> Bank Account</div> 
            <div class="e-ou-space">
                <div class="p-5">
                    <form id="bankUpdateForm" method="post" action="{{ route('bank') }}">
                        <input type="hidden" name="id" value="{{ $data['bank']->id ?? 0 }}">
                        @csrf
                        <div class="form-group">
                            <label> Type <span class="text-danger">*</span></label>
                            <select name="selected_account" class="form-control" id="selected_account">
                                <option>Select a Type</option>
                                @foreach ($data['bankMaster'] as $bank)
                                    <option value="{{ $bank->value }}"
                                        @if ($data['bank']['selected_account'] == $bank->value) selected="selected" @endif>{{ $bank->label }}
                                    </option>
                                @endforeach

                            </select>
                            @error('selected_account')
                                <span id="selected_account-error"
                                    class="error invalid-feedback display-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row bank_account @if ($data['bank']['selected_account'] == '2') hide @endif">
                            <div class="form-group col-md-6">
                                <label> Bank Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="bank_name" type="text" autocomplete="off"
                                    value="{{ $data['bank']['bank_name'] ?? '' }}" id="bank_name" />
                                @error('bank_name')
                                    <span id="bank_name-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label> IFSC Code <span class="text-danger">*</span></label>
                                <input class="form-control" name="ifsc_code" id="ifsc_code" type="text"
                                    autocomplete="off" value="{{ $data['bank']['ifsc_code'] }}" />
                                @error('ifsc_code')
                                    <span id="ifsc_code-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row bank_account @if ($data['bank']['selected_account'] == '2') hide @endif">
                            <div class="form-group col-md-6">
                                <label> Account Number <span class="text-danger">*</span></label>
                                <input class="form-control" name="account_number" id="account_number" type="text"
                                    autocomplete="off" value="{{ $data['bank']['account_number'] }}" />
                                @error('account_number')
                                    <span id="account_number-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label> Confirm Account Number <span class="text-danger">*</span></label>
                                <input class="form-control" name="confirm_account_number" id="confirm_account_number"
                                    type="text" autocomplete="off" value="{{ $data['bank']['account_number'] }}" />
                                @error('confirm_account_number')
                                    <span id="confirm_account_number-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group upi_account @if ($data['bank']['selected_account'] == '1' || !$data['bank']->id) hide @endif col-md-6">
                                <label> UPI </label>
                                <input class="form-control" name="upi" id="upi" type="text"
                                    autocomplete="off" value="{{ $data['bank']['upi'] }}" />
                                @error('upi')
                                    <span id="upi-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 bank_account @if ($data['bank']['selected_account'] == '2') hide @endif">
                                <label> Account type <span class="text-danger">*</span></label>
                                <select name="account_type" class="form-control">
                                    <option value="1" @if (!$data['bank']['account_type'] || $data['bank']['account_type'] == '1') selected="selected" @endif>
                                        Savings</option>
                                    <option value="2" @if ($data['bank']['account_type'] == '2') selected="selected" @endif>
                                        Current</option>
                                </select>
                                @error('account_type')
                                    <span id="account_type-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" name="is_primary" id="is_primary" value="1"
                                    @if ($data['bank']['is_primary'] == 1) checked @endif />
                                <label for="is_primary">Click to make is a primary account</label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-8 pull-right">
                                <button type="submit" class="btn-md btn btn-warning js-progress"
                                    id="js-update">{{ $data['bank']->id ? 'Update' : 'Add' }}</button>
                                <a href="{{ url('banks') }}" class="btn-md btn btn-danger js-progress">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            @if ($data['bank']['selected_account'])
                $("body").find("#selected_account").change();
            @endif
        });

        $("#bankUpdateForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 4,
                },
                bank_name: {
                    required: function(element) {
                        return $("body").find("#selected_account").val() == "1";
                    },
                    minlength: 4,
                },
                ifsc_code: {
                    required: function(element) {
                        return $("body").find("#selected_account").val() == "1";
                    },
                },
                account_number: {
                    required: function(element) {
                        return $("body").find("#selected_account").val() == "1";
                    },
                    number: true,
                },
                confirm_account_number: {
                    equalTo: "#account_number",
                },
                upi: {
                    required: function(element) {
                        return $("body").find("#selected_account").val() == "2";
                    },
                },
            },
            messages: {
                name: {
                    required: "Please enter a name",
                    minlength: "Your name must be at least 4 characters long",
                },
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
            submitHandler: function(form) {
                // do other things for a valid form
                form.submit();
                $("body").find(".js-progress").prop("disabled", true);
                $("body").find("#js-update").text("Loading...");
            },
        });

        $(document).on("change", "#selected_account", function() {
            var self = $(this);
            $("body").find(".upi_account, .bank_account").addClass('hide');
            if (self.val() === "1") {
                $("body").find(".bank_account").removeClass("hide");
            } else {
                $("body").find(".upi_account").removeClass("hide");
            }

            return false;
        });
    </script>
@endpush
@include('layout.home-footer')
