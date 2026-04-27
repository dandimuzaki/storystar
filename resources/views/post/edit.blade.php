<x-app-layout>
    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 grid gap-4">
                <h1 class="font-black text-3xl text-center">Edit Post</h1>
                <form action={{ route('post.update', $post) }} enctype="multipart/form-data" method="post"
                    class="w-full grid gap-4">
                    @csrf
                    @method('put')

                    @if ($post->imageUrl())
                        <div>
                            <img src={{ $post->imageUrl() }} alt={{ $post->title }}
                                class="w-full aspect-[16/9] object-cover" />
                        </div>
                    @endif

                    <!-- Image -->
                    <div>
                        <x-input-label for="image" :value="__('Image')" />
                        <x-text-input id="image"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 w-full"
                            type="file" name="image" :value="old('image')" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                            :value="old('title', $post->title)" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Category -->
                    <div>
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select id="category_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 w-full"
                            name="category_id">
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value={{ $category->id }} @selected(old('category_id', $post->category_id))>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <!-- Content -->
                    <div class="grid">
                        <x-input-label for="content" :value="__('Content')" />
                        <x-textarea-input id="content" type="text" class="block mt-1 w-full" name="content">
                            {{ old('content', $post->content) }}
                        </x-textarea-input>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <!-- Published At -->
                    <div>
                        <x-input-label for="published_at" :value="__('Published At')" />
                        <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local"
                            name="published_at" :value="old('published_at', $post->published_at)" min="{{ now()->format('Y-m-d\TH:i') }}" />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>

                    <x-primary-button type="submit" class="justify-self-center w-fit mt-4">
                        Save Updates
                    </x-primary-button>

                </form>

            </div>


        </div>
    </div>
</x-app-layout>
