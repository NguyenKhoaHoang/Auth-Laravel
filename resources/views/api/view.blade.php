
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('File') }}</div>

                    <div class="ratio ratio-16x9">
                        <iframe src="{{ asset($result) }}" title="File Uploaded" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


