@props(['post'])

@auth
    <div class="flex gap-2 items-center" x-data="{
        hasClapped: {{ auth()->user()->hasClapped($post) ? 'true' : 'false' }},
        clapsCount: {{ $post->claps_count ?? 0 }},
        clap() {
            axios.post('/clap/{{ $post->id }}')
                .then(res => {
                    this.clapsCount = res.data.clapsCount
                    this.hasClapped = !this.hasClapped
                })
                .catch(err => {
                    console.error(err)
                })
        },
    }">
        <button @click="clap()" :class="hasClapped ? 'text-green-500' : 'text-gray-500'">
            <x-phosphor-hands-clapping-duotone class="w-6 h-6" />
        </button>

        <span x-text="clapsCount" class="text-gray-500"></span>
    </div>
@endauth
