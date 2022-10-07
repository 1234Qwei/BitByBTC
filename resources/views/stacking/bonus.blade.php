@include('layout.home-header')
<div class="header-out-1">
  <div class="container mt-1">
    <div class="package-2">
      <div class="package-title">Bonus Amount</div>
      <div class="mt-4 mr-6 ml-6">
        <table class="table table-bordered my-3" style='font-size:16px;font-weight: 400;'>
            <thead>
                <tr>
                    <th style="width: 10px" class='px-2'>#</th>
                    @if (Auth::user()->id === 1)
                    <th>User</th>
                    @endif 
                    <th>Date</th>
                    <th>Leasing type</th>
                    <th>Amount</th>
                    <th>Payment type</th>
                </tr>
            </thead>
            <tbody>
            @forelse($data['bonus'] as $index => $bonus)
                                <tr>
                                    <td>{{ ($data['bonus']->currentPage() - 1) * $data['bonus']->perPage() + ($index + 1) }}
                                    </td> 
                                    @if (Auth::user()->id === 1)
                                        <td>{{ $bonus->user->name ?? '' }} ( {{ $bonus->user->username ?? '' }} )
                                        </td>
                                    @endif
                                    <td>{{ $bonus->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $bonus->stacking->term->duration . ' Month' }}</td>
                                    <td>{{ $bonus->amount . ' ' . $bonus->stacking->package->asset->symbol }}
                                    </td>
                                    <td class="text-center">{{ $bonus->payment_type == 1 ? 'Credited' : '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center" style='font-size: 17px; font-weight:500'>No records found !</td>
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
