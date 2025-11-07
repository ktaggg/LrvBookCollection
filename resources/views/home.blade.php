@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @auth
                        <div class="alert alert-success" role="alert">
                            {{ session('status') ?? 'You are logged in!' }}
                            {{ __('Enjoy your visit^^') }}
                        </div>
                    @else
                        {{ __('Welcome to JDBooks! Login to visit all pages') }}
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
