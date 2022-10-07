@include('layout.home-header')
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title">Coin Settings</div>
      <div class="e-ou-space">
        <div class="p-5">
                <form id="coinSettingsForm" method="post" action="{{ route('coin-settings') }}">
                    @csrf
                    <div class="card-body">
                        @foreach($data['coinSettings'] as $key => $settings)
                        @if($loop->odd) <div class="row"> @endif
                            <div class="form-group col-md-6">
                                @php
                                $title = Str::replace('_', ' ',ucfirst($settings->config_key));
                                @endphp
                                <label> {{ $title }} <span class=" text-danger">*</span></label>
                                <input class="form-control" name="{{ $settings->config_key  }}" type="number" autocomplete="off" value="{{ $settings->config_value }}" required />
                            </div>
                            @if($loop->even)
                        </div> @endif
                        @endforeach
                    </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary js-progress" id="js-update">Save</button>
                <a href="{{ url('coins') }}" class="btn btn-danger js-progress">Cancel</a>
            </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
@push('scripts')
 <script type="text/javascript">
    $("#coinSettingsForm").validate({
        rules: {},
        messages: {},
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
            $("body")
                .find("#js-update")
                .text("Loading...")
                .prop("disabled", true);
        },
    });
</script>
@endpush
@include('layout.home-footer')