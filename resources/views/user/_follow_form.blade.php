@if (Auth::user()->id !== $user->id)
    <div id="follow_form">
        @if (Auth::user()->isFollowing($user->id))
            <form action="{{ route('follower.destroy', $user->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-sm">取消关注</button>
            </form>
        @else
            <form action="{{ route('follower.store', $user->id) }}" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-sm btn-primary">关注</button>
            </form>
        @endif
    </div>
@endif