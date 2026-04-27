@props(['user'])

<div {{ $attributes }} x-data="{
    following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
    followersCount: {{ $user->followers()->count() }},
    follow() {
        axios.post('/follow/{{ $user->id }}')
            .then(res => {
                this.followersCount = res.data.followersCount
                this.following = !this.following
            })
            .catch(err => {
                console.error(err)
            })
    }
}">
    {{ $slot }}
</div>
