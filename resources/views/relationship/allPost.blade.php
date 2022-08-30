<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    All posts
    <br>
    @foreach ($posts as $post)
        {{ $post->name }} - {{ $post->user->name }} 
        - {{ $post->user->image->url }}
        <br>
        @foreach ($post->categories as $category)
            {{ $category->name }}
            @foreach ($category->tags as $tag)
                {{ $tag->name }}
            @endforeach
            <br>
            @foreach ($category->posts as $post2)
                {{ $post2->name }}
            @endforeach
        @endforeach
        {{-- {{ $post->categories_count }} --}}
        <br> <br>
    @endforeach
</body>
</html>