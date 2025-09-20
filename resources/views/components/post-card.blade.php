<div class="flex h-fit bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="p-6 flex-1 grid gap-4">
        <div class="flex gap-2 items-center">
            <a href={{ route('public_profile.show', $post->user) }}>
                <x-avatar :size=6 :user="$post->user" />
            </a>
            <a href={{ route('public_profile.show', $post->user) }}>
                {{ $post->user->name }}
            </a>
        </div>
        <a href={{ route('post.show', ['username' => $post->user->username, 'post' => $post->slug]) }}>
            <h5 class="text-2xl font-bold tracking-tight text-gray-900">{{ $post->title }}</h5>
        </a>
        <p class="font-normal text-gray-700">{{ Str::words($post->content, 15) }}</p>
        <div class="flex gap-4 text-gray-400 items-center">
            @if (auth()->user() && auth()->user()->id === $post->user->id && $post->published_at > now())
                <div class="py-1 px-2 rounded bg-orange-500 text-white">Pending</div>
            @elseif (auth()->user() && auth()->user()->id === $post->user->id && $post->published_at <= now())
                <div class="py-1 px-2 rounded bg-blue-500 text-white">Published</div>
            @endif
            <p>{{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}</p>
            <div class="flex gap-1 items-center">
                <x-phosphor-hands-clapping-duotone class="w-6" />
                <span>{{ $post->claps_count ?? 0 }}</span>
            </div>
        </div>
    </div>

    <a href="#" class="w-56 aspect-square">
        <img class="rounded-r-lg w-full h-full object-cover" src={{ $post->imageUrl('preview') }}
            alt={{ $post->title }} />
    </a>

</div>
