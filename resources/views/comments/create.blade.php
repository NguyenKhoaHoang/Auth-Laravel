@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Comment') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('comment.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="content" class="col-md-4 col-form-label text-md-end">{{ __('Test Cookie') }}</label>

                            <div class="col-md-6">
                                <input required class="form-control" name="test_cookie" 
                                    @isset($test)
                                        value="{{ $test }}"
                                    @endisset
                                >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="content" class="col-md-4 col-form-label text-md-end">{{ __('Content') }}</label>

                            <div class="col-md-6">
                                <textarea required class="form-control" name="content" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
