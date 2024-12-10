@extends('front.layouts.app')
@section('title')
    Home
@endsection

@section('content')
    <main>
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-8">
                    <article class="blog-details">
                        <h1 class="mb-4">{{ $blog->title }}</h1>
                        <p class="text-muted">Ngày đăng: {{ $blog->created_at->format('d/m/Y') }}</p>
                        <img src="{{ asset('uploads/blogs/' . $blog->image) }}" alt="{{ $blog->title }}"
                             class="img-fluid mb-4">
                        <div class="blog-content">
                            {!! $blog->content !!}
                        </div>
                    </article>
                </div>
                <div class="col-lg-4">
                    <aside class="related-blogs">
                        <h5 class="mb-3">Bài viết liên quan</h5>
                        <ul class="list-unstyled">
                            @foreach ($relatedBlogs as $relatedBlog)
                                <li class="mb-3">
                                    <a href="{{ route('blog.details', $relatedBlog->slug) }}"
                                       class="text-decoration-none text-primary">
                                        <img src="{{ asset('uploads/blogs/' . $relatedBlog->image) }}"
                                             alt="{{ $relatedBlog->title }}" class="img-fluid me-3"
                                             style="width: 70px; height: 70px; object-fit: cover;">
                                        {{ $relatedBlog->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </main>
@endsection
