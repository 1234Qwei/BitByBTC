@include('layout.home-header')
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title"> Bank Account Details</div>
      <div class="e-ou-space">
        @if(count($data['bank']) < 2) <div class="row">
          <div class="col-md-2 col-md-offset-10 mr-2 mt-2 mb-2">
            <a href="{{ url('bank') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Bank Account</a>
          </div>
      </div>
      @endif
      <div class="mt-4 mr-5 ml-5">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">#</th> 
              <th>Customer name</th>
              <th>Account Number / Address</th>
              <th>Type</th>
              <th>Is Primary</th>
              <th>Created Date</th>
              <th style="width: 40px">Status</th>
              <th style="width: 40px">Action</th>
            </tr>
          </thead>
          <tbody> 
            @forelse($data['bank'] as $index => $bank)
            <tr>
              <td>{{ ($data['bank']->currentPage() - 1) * $data['bank']->perPage() + ( $index + 1 ) }}</td>
              <td>{{ Auth::user()->first_name }}</td>
              <td>{{ ($bank->selected_account == '1') ? $bank->account_number : $bank->upi }}</td>
              <td>{{ ($bank->selected_account == '1') ? 'Account' : 'UPI' }}</td>
              <td>{{ ($bank->is_primary == '1') ? 'Yes' : 'No' }}</td>
              <td>{{ $bank->created_at }}</td>
              @if($bank->status == 0)
              <td><span class="badge bg-primary">Not Verified</span></td>
              @elseif($bank->status == 1)
              <td><span class="badge bg-success">Verified</span></td>
              @else
              <td><span class="badge bg-danger">Rejected</span></td>
              @endif
              <td align="center">
                <a href="{{ url('bank') . '/' . $bank->id }}" data-toggle="tooltip" title="Click to edit">
                  <i class="fa fa-edit"></i>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" align="center">No records found!</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
@include('layout.home-footer')