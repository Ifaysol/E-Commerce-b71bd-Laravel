@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h3 class="heading heading-4 strong-500">
                                    {{__('Phone Verification')}}
                                </h3>
                                <p>Verification code has been sent. Please wait a few minutes.</p>
                                {{-- <a href="{{ route('verification.phone.resend') }}">{{__('Resend Code')}}</a> --}}
                                <span type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
 {{__('Resend Code')}}
</span>
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{__('Resend Code')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="{{ route('verification.phone.resend') }}" method="get" accept-charset="utf-8">
           <div class="form-group">
                <span style="position: absolute;padding: 13px;font-size: 14px;left: 24px;">+ 88</span>
                <input maxlength="11" minlength="11" style="padding-left: 40px;" type="text" class="h-auto form-control-lg form-control " value="" placeholder="Mobile" required="" name="mobile">
            </div>
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-styled btn-base-1 w-100 btn-md">Resend Code</button>
            </div>
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
                            </div>
                            <div class="px-5 py-lg-5">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg">
                                        <form class="form-default" role="form" action="{{ route('verification.submit') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <!-- <label>{{ __('name') }}</label> -->
                                                <div class="input-group input-group--style-1">
                                                    <input type="text" class="form-control" name="verification_code">
                                                    <span class="input-group-addon">
                                                        <i class="text-md la la-key" style="line-height: 0;margin-top: 14px;"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="row align-items-center">
                                                <div class="col-12 text-right">
                                                    <button type="submit" class="btn btn-styled btn-base-1 w-100 btn-md">{{ __('Verify') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
