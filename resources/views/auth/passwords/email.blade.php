@extends('layouts.blank')

@section('content')

<div class="cls-content-sm panel">
    <div class="panel-body">
        <h1 class="h3">{{ translate('Reset Password') }}</h1>
        <p class="pad-btm">{{translate('Enter your mobile no to recover your password.')}} </p>
        <form method="POST" action="{{ route('password.send.phone') }}">
            @csrf
            <div class="form-group">
                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                    <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('phone') }}" required placeholder="{{ translate('Phone') }}">
                @else
                    <input type="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{ translate('Mobile') }}" name="mobile">
                @endif

                @if ($errors->has('phone'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('mobile') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group text-right">
                <button class="btn btn-danger btn-lg btn-block" type="submit">
                    {{ translate('Send Password Reset Message') }}
                </button>
            </div>
        </form>
        <div class="pad-top">
            <div class="d-flex justify-content-between bg-secondary mb-3">
                <div class="p-2">
                    <a href="{{route('user.login')}}" class="btn-link text-bold text-main">{{translate('Back to Login')}}</a>
                </div>
                <div class="p-2">
                    <a href="{{route('password.phone.form')}}" class="btn-link text-bold text-main">{{translate('Update Password')}}</a>
                    
                </div>
              </div>
            
        </div>
    </div>
</div>


@endsection
