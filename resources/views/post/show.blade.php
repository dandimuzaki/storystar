<x-app-layout>
    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid gap-4">
            <div class="p-8 grid gap-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="font-black text-3xl">{{ $post->title }}</h1>
                <x-follow-ctr class="flex gap-2" :user="$post->user">
                    <a href={{ route('public_profile.show', $post->user) }}>
                        <x-avatar :size=12 :user="$post->user" />
                        <p>{{ $post->user->imageUrl() }}</p>
                    </a>
                    <div class="grid gap-1">
                        <div class="flex gap-2 items-center">
                            <span>{{ $post->user->name }}</span>
                            @if (auth()->user() && auth()->user()->id !== $post->user->id)
                                &middot;
                                <button x-text="following ? 'Unfollow' : 'Follow'"
                                    :class="following ? 'text-gray-500' : 'text-green-500'" @click="follow()"></button>
                            @endif
                        </div>
                        <div class="grid gap-2">
                            <div class="flex gap-2 text-sm text-gray-500 items-center">
                                <span>
                                    {{ $post->readTime() }} min read
                                </span>
                                &middot;
                                <span>
                                    {{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-follow-ctr>

                @if ($post->user->id == Auth::id())
                    <div class="grid grid-cols-2 gap-4">
                        <a href={{ route('post.edit', $post) }}
                            class="h-full w-full px-4 flex items-center justify-center rounded bg-green-500 text-white">
                            Edit
                        </a>
                        <form method='post' action="{{ route('post.destroy', $post) }}">
                            @csrf
                            @method('delete')
                            <x-danger-button type="submit"
                                class="w-full px-4 py-2 justify-center items-center flex">Delete</x-danger-button>
                        </form>
                    </div>
                @endif

                <div>
                    <x-clap-button :post="$post" />
                </div>
                <div>
                    <img src={{ $post->imageUrl() }} alt={{ $post->title }}
                        class="w-full object-cover aspect-[16/9]" />
                </div>
                <div>
                    {{ $post->content }}
                </div>
            </div>
        </div>
</x-app-layout>
