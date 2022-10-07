</div>
</div>
<div class="signfoot mt-5">Copyright &copy {{ date('Y') }} <a href="{{ url('/') }}">BitByBTC</a>. All rights
    reserved.
</div>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script src="{{ asset('js/auth.js') }}"></script>
<script type="text/javascript">
    @if (Session::has('message'))
        toastr.success(`{{ Session::get('message') }}`)
    @endif

    @if (Session::has('error'))
        toastr.error(`{{ Session::get('error') }}`)
    @endif
</script>
@stack('scripts')
</body>

</html>
