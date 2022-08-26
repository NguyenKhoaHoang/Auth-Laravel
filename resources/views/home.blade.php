
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
                        <h5>Unread Notifications</h5>
                        @foreach (auth()->user()->unreadNotifications as $notification)
                            <div class="alert alert-success" role="alert">
                                {{ $notification->data['name'] }} started following you!!
                                <a href="{{ route('markAsRead', [$notification->id]) }}">Mask as read</a>
                            </div>
                        @endforeach

                        <h5>Readed Notifications</h5>
                        @foreach (auth()->user()->readNotifications as $notification)
                            <div class="alert alert-success" role="alert">
                                {{ $notification->data['name'] }} started following you!!
                            </div>
                        @endforeach
                    </div>



                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('showInfo') }}">User Infomation</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('relationship.poly.manymany') }}">Relationship</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('comment.create') }}">Create Comment</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('comment.show') }}">Show Comments</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('mail') }}">Send Email</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('notification') }}">Send Notification</a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('uploadAPI') }}" enctype="multipart/form-data">
                            @csrf
                            <label for="photo" class="col-md-4 col-form-label text-md-end">Upload file API</label>
                            <input type="file" name="file" class="form-control-file" required accept="/*">
                            <button type="submit" class="btn btn-primary">
                                Upload file
                            </button>
                        </form>
                    </div>


                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('cache') }}">Cache</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('httpClient') }}">Http client</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
