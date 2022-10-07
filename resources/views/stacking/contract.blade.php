@include('layout.home-header')
@include('modal.stacking')
<div class="header-out-1">
  <div class="container mt-1">
    <div class="package-2">
      <div class="package-title">Package Details</div>
      <div class="mt-4 mr-6 ml-6">
        <table class="table table-bordered my-3" style='font-size:15px;font-weight: 400;'>
          <thead>
            <tr>
              <th style="width: 10px">#</th> 
              <th>Date</th>
              <th>Terms</th>
              <th>Expiry date</th>
              <th>Bill No</th>
              <th>Package</th>
              <th style="width: 40px">Status</th>
              <th style="width: 40px">Action</th>
            </tr>
          </thead>
          <tbody>
                @forelse($data['contracts'] as $index => $contract)
                     <tr>
                        <td>{{ ($data['contracts']->currentPage() - 1) * $data['contracts']->perPage() + ($index + 1) }}
                                    </td>
                                    @if (Auth::user()->id === 1)
                                        <td>{{ $contract->user->name ?? '' }} (
                                            {{ $contract->user->username ?? '' }} )</td>
                                    @endif
                                    <td>{{ $contract->date }}</td>
                                    <td>{{ $contract->term->duration . ' Month' }}</td>
                                    <td>{{ $contract->expiry_date }}</td>
                                    <td>{{ $contract->billing_no }}</td>
                                    <td>{{ number_format($contract->package->coin) . ' ' . $contract->package->asset->symbol }}
                                    </td>
                                    <td>
                                        @if (is_null($contract->transaction_id) && $contract->status == 0)
                                            <span class="badge bg-warning">Transaction hash is important!</span>
                                        @elseif($contract->status == 0)
                                            <span class="badge bg-primary">Not Verified</span>
                                        @elseif($contract->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($contract->status == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($contract->status == 3)
                                            <span class="badge bg-info">Closed</span>
                                        @endif
                                    </td>
                                    <td class='text-center'>
                                        <a href="javascript://" data-toggle="tooltip" title="Click Here!"
                                            id="jsStackingAction" data-id="{{ $contract->id }}"
                                            data-url="{{ url('load-stacking') }}">
                                            @if ((is_null($contract->transaction_id) && $contract->status == 0) || Auth::user()->id === 1)
                                                <i class="fa fa-edit"></i>
                                            @else
                                                <i class="fa fa-eye"></i>
                                            @endif
                                        </a>
                                        @if (Auth::user()->id === 1)
                                            @if (is_null($contract->transaction_id))
                                                <a href="javascript://" data-toggle="tooltip"
                                                    title="Click here to delete!" id="stackingDeleteAction"
                                                    data-id="{{ $contract->id }}"
                                                    data-url="{{ url('remove-stacking') }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @else
                                                <span class="text-secondary">
                                                    <i class="fa fa-trash"></i>
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                        </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center" style='font-size: 17px; font-weight:500'>No records found !</td>
                                </tr>
                            @endforelse
                        </tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
                    {!! $data['contracts']->links() !!}
                </div>
    </div>
  </div>
</div>
</div>
@push('scripts')
    <script>
        $(document).on("click", "#jsStackingAction", function() {
            var id = $(this).attr("data-id");
            var __url = $(this).attr("data-url");
            $.ajax({
                url: __url,
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    id: id
                },
                cache: false,
                success: function(response) {
                    if (response.success) {
                        setTimeout(function() {
                            $("body").find("#stacking-view").html(response.html);
                        }, 500);
                    } else {
                        toastr.error("Something went wrong!");
                        return false;
                    }
                },
            });
            $("#exchangeStacking").modal({
                backdrop: "static",
                keyboard: false,
            });

            return false;
        });

        $("#stackingDeleteAction").confirm({
            icon: 'fas fa-exclamation-triangle',
            theme: 'material',
            title: 'Delete!',
            type: 'red',
            content: 'Are you sure to delete?',
            buttons: {
                Yes: {
                    btnClass: 'btn-red',
                    action: function() {
                        deleteAjax(this.$target);
                    }
                },
                No: {
                    btnClass: 'btn-green',
                    action: function() {
                        return true;
                    }
                }
            }
        });

        function deleteAjax(__this__) {
            var _id_ = __this__.attr("data-id");
            var _url_ = __this__.attr("data-url");
            $.ajax({
                url: _url_,
                type: "DELETE",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    id: _id_
                },
                cache: false,
                success: function(response) {
                    if (response.success) {
                        $.confirm({
                            title: 'Delete!',
                            content: 'This data is deleted successfully!',
                            buttons: {
                                Ok: {
                                    action: function() {
                                        location.reload();
                                    }
                                }
                            }
                        });
                    } else {
                        toastr.error("Something went wrong!");
                        return false;
                    }
                },
            });
        }
    </script>
@endpush
@include('layout.home-footer')

