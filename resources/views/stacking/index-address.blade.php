@include('layout.home-header')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<style>
    strong{
        font-size: 16px;
    }
    span {
        font-size: 14px;
        font-weight: 400;
    }
</style>
<div class="header-out-1">
    <div class="container">
        <div class="exc-ref-ou-1-0 mt-4 border-top"><h1 style='font-size: 22px;'>Stacking Address</h1></div>
        <div class="col-xs-12 text-center mt-5 mb-3">
              <img src="https://chart.googleapis.com/chart?chs=250x250&chld=M|0&cht=qr&chl={{ $data['wallet'] }}&choe=UTF-8">
        </div>
            <div class="row " >
             <div class="col-md-12 mt-2">
                <div class="table table-bordered">
                   <table class='table border-2 px-5'>
                   <tbody  class='border-2 mt-2'>
                            <tr >
                                        <td width="150" class="bold"><strong>WalletAddress :</strong></td>
                                        <td><span>{{ $data['wallet'] }}</span></td>
                                    </tr>
                                    <?php echo $data['stacking']->id; ?>
                                    <tr>
                                        <td><strong>Bill Number :</strong></td>
                                        <td><span>{{ $data['stacking']->billing_no ?? '0000' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stacking Amount :</strong></td>
                                        <td>
                                            <span>
                                        {{ number_format($data['stacking']->package->coin) . $data['symbol'] ?? '0000' }}
                                           </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stacking Term :</strong></td>
                                        <td><span>
                                        {{ number_format($data['stacking']->term->duration) . ' Month' ?? '0000' }}
                                        </span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Term Interest :</strong></td>
                                        <td><span>
                                        {{ number_format($data['stacking']->term->interest) . ' %' ?? '0000' }}
                                        </span></td>
                                    </tr>
                            </tbody>
                   </table>
                </div>
             </div>
        </div>
        <div class="card-footer">
            <a href="{{ url('stacking-contract') }}" class="btn btn-danger float-left mx-2  px-3"><i
             class="fas fa-long-arrow-alt-left"></i> Back</a>
            <a href="{{ url('contract') }}" class="btn btn-primary px-3">Next <i
             class="fas fa-long-arrow-alt-right "></i></a>
        </div>
    </div>
</div>
@include('layout.home-footer')
