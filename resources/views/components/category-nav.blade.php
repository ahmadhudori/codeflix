<ul class="mb-2 navbar-nav me-auto mb-lg-0">
    <li class="nav-item dropdown kategori-dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            KATEGORI
        </a>
        <div class="dropdown-menu">
            @foreach($categories as $chunk)
            <ul>
                @foreach($chunk as $category)
                <li>
                    <a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">
                        {{ $category->title }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endforeach
        </div>
    </li>
	<li class="nav-item"><a class="text-white nav-link" href="{{ route('movies.all') }}">All Movies</a> </li>
</ul>