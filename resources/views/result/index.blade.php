<x-app-layout>
    <div class="md:py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid gap-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">
                    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 justify-center">
                        <li class="me-2">
                            <a href="?search={{ $search }}&tab=posts"
                                class="{{ request('tab', 'posts') === 'posts'
                                    ? 'inline-block px-4 py-2 text-white bg-blue-600 rounded-lg active'
                                    : 'inline-block px-4 py-2 rounded-lg hover:text-gray-900 hover:bg-gray-100' }}">
                                Post
                            </a>
                        </li>

                        <li class="me-2">
                            <a href="?search={{ $search }}&tab=users"
                                class="{{ request('tab') === 'users'
                                    ? 'inline-block px-4 py-2 text-white bg-blue-600 rounded-lg active'
                                    : 'inline-block px-4 py-2 rounded-lg hover:text-gray-900 hover:bg-gray-100' }}">
                                User
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @if (request('tab', 'posts') === 'posts')
                @foreach ($posts as $post)
                    <x-post-card :post=$post />
                @endforeach
                {{ $posts->appends(['search' => $search, 'tab' => 'posts'])->links() }}
            @else
                <div class="grid grid-cols-5 gap-4">
                    @foreach ($users as $user)
                        <div class="bg-white p-6">
                            <x-follow-ctr :user=$user class="grid gap-2 justify-center text-center">
                                <a href={{ route('public_profile.show', $user) }}>
                                    <x-avatar :size=20 :user="$user" />
                                </a>
                                <h3 class="font-bold text-lg text-center">{{ $user->name }}</h3>
                                <p><span x-text="followersCount"></span> followers</p>
                                <p>{{ $user->bio }}</p>
                                @if (auth()->user() && auth()->user()->id !== $user->id)
                                    <div>
                                        <button class="px-4 py-2 rounded-full"
                                            x-text="following ? 'Unfollow' : 'Follow'"
                                            :class="following ? 'border border-gray-500' : 'text-white bg-green-500'"
                                            @click="follow()"></button>
                                    </div>
                                @endif
                            </x-follow-ctr>
                        </div>
                    @endforeach
                </div>
                {{ $users->appends(['search' => $search, 'tab' => 'users'])->links() }}
            @endif
        </div>
    </div>
</x-app-layout>
