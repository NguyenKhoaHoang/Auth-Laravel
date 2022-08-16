@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- {{ __('You are logged in!') }} --}}
                </div>

                <div class="card-body">
                    <a class="btn btn-primary w-100" href="{{ route('showInfo') }}">User Infomation</a>
                </div>

                <div class="card-body">
                    <a class="btn btn-primary w-100" href="{{ route('comment.create') }}">Create Comment</a>
                </div>

                <div class="card-body">
                    <a class="btn btn-primary w-100" href="{{ route('comment.show') }}">Show Comments</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
