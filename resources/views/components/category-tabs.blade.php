<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-4 text-gray-900">
        <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 justify-center">
            <li class="me-2">
                <a href="{{ route('dashboard') }}"
                    class="{{ Route::currentRouteNamed('dashboard')
                        ? 'inline-block px-4 py-2 bg-[#f9df85] rounded-lg active'
                        : 'inline-block px-4 py-2 rounded-lg hover:text-gray-900 hover:bg-gray-100' }}">
                    Home
                </a>
            </li>

            @foreach ($categories as $category)
                <li class="me-2">
                    <a href="{{ route('post.byCategory', ['category' => $category->name]) }}"
                        class="{{ Route::currentRouteNamed('post.byCategory') && request('category') == $category->name
                            ? 'inline-block px-4 py-2 bg-[#f9df85] rounded-lg active'
                            : 'inline-block px-4 py-2 rounded-lg hover:text-gray-900 hover:bg-gray-100' }}">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
