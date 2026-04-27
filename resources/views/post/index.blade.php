<x-app-layout>
    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid gap-4">
            <x-category-tabs></x-category-tabs>

            <div class="overflow-hidden shadow-sm sm:rounded-lg grid gap-4">
                @forelse ($posts as $post)
                    <x-post-card :post=$post></x-post-card>

                @empty
                    <p class="py-16 text-center text-gray-400">No posts</p>
                @endforelse


            </div>

            {{ $posts->onEachSide(1)->links() }}

        </div>
    </div>
</x-app-layout>
