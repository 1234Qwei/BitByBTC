@include('layout.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('contract-update') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data['stacking']->id }}" />
                    <div class="card-header">
                        <h3 class="card-title">Stacking Contract Request</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label> Package </label>
                            <label
                                class="form-control">{{ number_format($data['stacking']->package->coin) . ' ' . $data['stacking']->package->asset->symbol }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label> Term </label>
                            <label class="form-control">{{ $data['stacking']->term->duration . ' Month' }}</label>
                        </div>
                        <div class="form-group">
                            <label>Staus</label>
                            <div class="ml-5 icheck-success d-inline">
                                <input type="radio" name="status" id="under_queue" value="1"
                                    @if ($data['stacking']->status == 1) checked @endif />
                                <label for="under_queue">
                                    Under Queue
                                </label>
                            </div>
                            <div class="ml-5 icheck-success d-inline">
                                <input type="radio" name="status" id="approved" value="2"
                                    @if ($data['stacking']->status == 2) checked @endif />
                                <label for="approved">
                                    Approved
                                </label>
                            </div>
                            <div class="ml-5 icheck-success d-inline">
                                <input type="radio" name="status" id="rejected" value="3"
                                    @if ($data['stacking']->status == 3) checked @endif />
                                <label for="rejected">
                                    Rejected
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ url('contract') }}" class="btn btn-danger">Cance</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
