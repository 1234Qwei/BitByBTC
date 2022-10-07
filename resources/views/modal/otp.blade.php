<div class="modal fade" id="otpVerification">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Otp Verification</h4>
            </div>
            <div id="stacking-view">
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-10">
                          <input type="text" name="otp" id="otp" placeholder="Enter your otp." class="user_box user_box1 password passlog" tabindex="1" >
                        </div>
                        <div class="col-md-2" style="margin-top: 10px;">
                            <a href="javascript:void(0)" id="resend" style="display:block;color: #faf3f3;width: 75px;float:right" onclick="resendotp('')" class="btn btn-danger btn-sm">Resend</a>
                          </div>
                      </div>
                      <div class="row mt-2">
                        <span id="timer"></span>
                      </div>
                      <div class="row">
                        <button type="submit" class="login_but" id="js-submit">Submit</button>
                      </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
