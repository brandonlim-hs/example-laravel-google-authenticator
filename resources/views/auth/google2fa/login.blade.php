@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('2FA authentication') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('2fa.login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    {{ __('E-Mail Address') }}
                                </label>

                                <div class="col-md-6">
                                    <div class="form-control-plaintext">{{ old('email') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="otp" class="col-md-4 col-form-label text-md-right">
                                    {{ __('2FA Code') }}
                                </label>

                                <div class="col-md-6">
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
                                        {{ __('Login') }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
