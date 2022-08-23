@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">{{ __('Search for comment by ID') }}</div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger text-center">{{ session('error') }}</div>
                        @endif
                        <form action="{{ route('comment.search') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-3 col-form-label text-md-right">{{ __('Comment ID') }}</label>
                                <div class="col-md-6">
                                    <input class="form-control" name="id" type="number" value="{{ old('id') }}"
                                        placeholder="Comment ID" required>
                                    @if ($errors->has('id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-2">
                                <div class="col-md-8 offset-md-5">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Search Comment') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Show Comments') }}</div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Content</th>
                                <th scope="col">Name Author</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($comments)
                                @foreach ($comments as $comment)
                                    <tr>
                                        <th scope="row">{{ $comment->id }}</th>
                                        <td>{{ $comment->content }}</td>
                                        <td>{{ $comment->user->name }}</td>
                                        <td>
                                            <div class="ratio ratio-16x9" style="width: 200px; height: 100px;">
                                                <iframe t src="{{ asset($comment->photo) }}" title="File Uploaded" allowfullscreen></iframe>
                                            </div>
                                        </td>
                                        <td>
                                            @can('update', $comment)
                                                <a class="btn btn-primary"
                                                    href="{{ route('commment.edit', ['comment_id' => $comment->id]) }}">Edit</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset

                            @isset($commentSearch)
                                <tr>
                                    <th scope="row">{{ $commentSearch->id }}</th>
                                    <td>{{ $commentSearch->content }}</td>
                                    <td>{{ $commentSearch->user->name }}</td>
                                    <td>
                                        @can('update', $commentSearch)
                                            <a class="btn btn-primary"
                                                href="{{ route('commment.edit', ['comment_id' => $commentSearch->id]) }}">Edit</a>
                                        @endcan
                                    </td>
                                </tr>

                                <div class="card-footer">
                                    <div class="col-md-8 offset-md-5">
                                        <a href="{{ route('comment.show') }}"
                                            class="btn btn-primary">{{ __('Show all Comments') }}</a>
                                    </div>
                                </div>
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
