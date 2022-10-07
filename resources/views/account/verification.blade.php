@include('layout.home-header')
<div class="clear"></div>
<!--body-start--->
<div class="header-out-1">
    <div class="container">
        <div class="col-md-6">
            <div class="acc-ou-1">
                <a href="{{ url('account') }}">
                    <div class="acc-ou-2-2">Account</div>
                </a>
            </div>
            <div class="acc-ou-2">
                <a href="{{ url('profile-verification') }}">
                    <div class="acc-ou-1-1">Profile Verification</div>
                </a>
            </div>
        </div>
        <div class="clear"></div>
        <div class="clear"></div>
        <div class="prof_verf">
            <h3>Profile Verification</h3>
            <span class="unspa">Unverified</span>
            <div class="clear"></div>
            <p><span>*</span>Please be sure to use your real identity, the platform will encrypt your identity
                information, which will be stored and automatically audited; even if the platform staff can not view,
                please rest assured that it is filled in.</p>
        </div>
        <div class="clear"></div>
        <form method="post" enctype="multipart/form-data" action="{{ route('update-kyc') }}" id="kyc_form_sec"
            novalidate>
            @csrf
            <div class="packages-bgg" style="text-align:center;">
                <div class="package-1" style="padding:20px;">
                    <div class="packout_det">
                        <div class="packout_det1">
                            <p>Please select ID type: <span>*</span></p>
                            <div class="selcText">
                                <select class="enq-input" name="verifytype" required>
                                    <option value="1">Passport</option>
                                    <option value="2">ID Card</option>
                                    <option value="3">Driving License</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="profouter">
                        <h3>Picture of Your ID (Front page / Cover page)</h3>
                        <p><span>*</span> Please make sure that the photo is complete and clearly visible, in .jpg or
                            .png format. Passport must be in the valid period.</p>
                        <div class="clearfix"></div>
                        <div class="outericons">
                            <div class="outericons1">
                                <div class="upload_fun">
                                    <div class="imageUploadForm">
                                        <input type='file' name="file1" class="uploadButton" data-id="jsFrontImage"
                                            accept="image/*" />
                                        <div id="uploadedImg" class="uploadedImg">
                                            <span class="unveil"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <p><span>*</span> image Format in .jpg or .png.</p>
                            </div>
                            <div class="outericons1">
                                <div class="upload_fun1">
                                    <img src="{{ $data['user']->verification->id_proof_front ?? asset('img/verf1.png') }}"
                                        id="jsFrontImage" alt="BitByBTC Verfications" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="profouter">
                        <h3>Picture of Your ID (Biographical page)</h3>
                        <p><span>*</span> Please make sure that the photo is complete and clearly visible, in .jpg or
                            .png format. Passport must be in the valid period.</p>
                        <div class="clearfix"></div>
                        <div class="outericons">
                            <div class="outericons1">
                                <div class="upload_fun">
                                    <div class="imageUploadForm imageUploadForm2">
                                        <input type='file' name="file2" class="uploadButton" data-id="jsBackImage"
                                            accept="image/*" />
                                        <div id="uploadedImg" class="uploadedImg">
                                            <span class="unveil"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <p><span>*</span> image Format in .jpg or .png.</p>
                            </div>
                            <div class="outericons1">
                                <div class="upload_fun1">
                                    <img src="{{ $data['user']->verification->id_proof_back ?? asset('img/verf2.png') }}"
                                        id="jsBackImage" alt="BitByBTC Verfications" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--1-->
                    <div class="clearfix"></div>
                    <!--1-->
                    <div class="profouter">
                        <h3>Your head shot holding biographical page of your ID</h3>
                        <p><span>*</span> Please upload a picture of yourself holding biographical page of your ID with
                            your personal signature should contain <br /><b style="color:#000">BitByBTC</b> and <b
                                style="color:#000">current date</b> of <b style="color:#000">signature</b>. Please make
                            sure the contents of picture is clearly visible</p>
                        <div class="clearfix"></div>
                        <div class="brdbox">Face clearly visible / ID number clearly visible / Writing clearly visible /
                            Note with word BitByBTC and today's date</div>
                        <div class="outericons">
                            <div class="outericons1">
                                <div class="upload_fun">
                                    <div class="imageUploadForm imageUploadForm3">
                                        <input type='file' name="file3" class="uploadButton" data-id="jsPhotoImage"
                                            accept="image/*" />
                                        <div id="uploadedImg" class="uploadedImg">
                                            <span class="unveil"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <p><span>*</span> image Format in .jpg or .png.</p>
                            </div>
                            <div class="outericons1">
                                <div class="upload_fun1">
                                    <img src="{{ $data['user']->verification->selfie_proof ?? asset('img/verf1.png') }}"
                                        id="jsPhotoImage" alt="BitByBTC Photo" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <div class="sub-ouo">
                            <button type="submit" class="form-btnn semibold" id="js-upload-submit"
                                style=" width:auto">Submit</button>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--body-end--->
    @push('scripts')
        <script>
            $(document).on('change', '.uploadButton', function(e) {
                var id = $(this).attr('data-id');
                var input = $(this)[0];
                var validExtensions = ['jpg', 'png', 'jpeg'];
                var fileName = input.files[0].name;
                var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
                if ($.inArray(fileNameExt, validExtensions) == -1) {
                    toastr.error("Only files with jpg,png,jpeg extension are allowed");
                    return false;
                }
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('body').find('#' + id).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
                return false;
            });

            $('#kyc_form_sec').validate({
                rules: {
                    file1: {
                        required: true,
                        filesize: '40'
                    },
                    file2: {
                        required: true,
                        filesize: '40'
                    },
                    file3: {
                        required: true,
                        filesize: '40'
                    },
                },
                messages: {
                    file1: {
                        required: 'Please select / upload front image from your ID proof!.'
                    },
                    file2: {
                        required: 'Please select / upload back image from your ID proof!.'
                    },
                    file3: {
                        required: 'Please select / upload your face image!'
                    },
                },
                errorPlacement: function(error, element) {
                    toastr.error(error);
                },
                submitHandler: function(form) {
                    $("body").find("#js-upload-submit")
                        .attr("disabled", "disabled")
                        .text("Loading...");
                    setTimeout(function() {
                        form.submit();
                    }, 100);
                    return true;
                }
            });
        </script>
    @endpush
    @include('layout.home-footer')
