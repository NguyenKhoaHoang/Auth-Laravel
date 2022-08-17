@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="page-title text-center">{{ __('Comment not found') }}</h3>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-8 offset-md-5">
                            <a href="{{ route('comment.show') }}" class="btn btn-primary">{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection