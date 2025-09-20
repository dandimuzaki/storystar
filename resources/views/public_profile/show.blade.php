<x-app-layout>
    <div class="md:py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid md:grid-cols-[3fr_1fr] gap-4">
            <div class="grid gap-4 order-1">
                @forelse ($posts as $post)
                    <x-post-card :post=$post />
                @empty
                    <div class="bg-white rounded flex justify-center items-center">No Posts</div>
                @endforelse
            </div>
            <div class="bg-white p-6 h-fit order-3 md:order-2 z-1">
                <x-follow-ctr :user=$user class="grid gap-2">
                    <x-avatar :size=20 :user="$user" />
                    <h3 class="font-bold text-lg">{{ $user->name }}</h3>
                    <p><span x-text="followersCount"></span> followers</p>
                    <p>{{ $user->bio }}</p>
                    @if (auth()->user() && auth()->user()->id !== $user->id)
                        <div>
                            <button class="px-4 py-2 rounded-full" x-text="following ? 'Unfollow' : 'Follow'"
                                :class="following ? 'border border-gray-500' : 'text-white bg-green-500'"
                                @click="follow()"></button>
                        </div>
                    @endif
                </x-follow-ctr>
            </div>
            <div class="md:order-3">
                {{ $posts->onEachSide(1)->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
