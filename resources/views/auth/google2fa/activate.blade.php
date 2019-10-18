@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Set up 2FA') }}</div>

                    <div class="card-body">
                        @if(Google2FA::isActivated())
                            <div>2FA is activated!</div>
                            <span>To deactivate 2FA, click:</span>
                            <a href="{{ route('2fa.deactivate') }}">
                                {{ __('deactivate 2FA') }}
                            </a>
                        @else
                            <form method="POST" action="{{ route('2fa.activate') }}">
                                @csrf

                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Step 1</label>

                                    <div class="col-md-6">
                                        <span class="form-control-plaintext">Install a compatible app on your mobile device</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Step 2</label>

                                    <div class="col-md-6">
                                        <span class="form-control-plaintext">Scan the following QR code</span>
                                        <div>
                                            <img src="{{ $qrCode }}" alt="">
                                            <div>
                                                <small>Alternatively, you can type the secret key.</small>
                                                <pre class="bg-light p-1">{{ $secret }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="otp" class="col-md-4 col-form-label text-md-right">Step 3</label>

                                    <div class="col-md-6">
                                        <span class="form-control-plaintext">Type the 2FA token below for verification</span>

                                        <input id="otp" type="text"
                                               class="form-control @error('one_time_password') is-invalid @enderror"
                                               name="one_time_password" required>

                                        @error('one_time_password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Submit') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
