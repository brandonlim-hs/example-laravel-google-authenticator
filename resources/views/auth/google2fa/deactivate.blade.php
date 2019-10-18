@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Set up 2FA') }}</div>

                    <div class="card-body">
                        <div>2FA is deactivated!</div>
                        <span>To activate 2FA, click:</span>
                        <a href="{{ route('2fa.activate') }}">
                            {{ __('activate 2FA') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
