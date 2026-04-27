@props(['size', 'user'])

@if ($user->imageUrl())
    <img src={{ $user->imageUrl() }} alt="{{ $post->title }}"
        class="w-{{ $size }} h-{{ $size }} object-cover rounded-full" />
@else
    <x-iconsax-bul-profile-circle
        class="w-{{ $size }} h-{{ $size }} text-{{ $user->random_color }}-200" />
@endif
