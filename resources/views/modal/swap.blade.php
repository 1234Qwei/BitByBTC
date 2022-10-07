<div class="modal fade" id="exchangeSwap">
  <div class="modal-dialog @if(Auth::user()->id === 1) modal-lg @endif">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Swap Information!</h4>
      </div>
      <div id="swap-view">
        <div class="modal-body text-center">
        <i class="fas fa-sync-alt fa-spin" aria-hidden="true"></i> &nbsp;Loading... Please wait!
      </div>
      </div>  
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->