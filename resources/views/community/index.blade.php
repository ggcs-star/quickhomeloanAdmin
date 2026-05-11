@extends('layouts.admin')

@section('title', 'Community')

@section('content')
<div class="p-4 md:p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Community</h2>
        <p class="text-sm text-gray-500 mt-1">Manage posts, comments and engage with users</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <!-- Create Post Section -->
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="px-5 py-4 bg-gray-50 border-b rounded-t-xl">
            <h3 class="font-semibold text-gray-800">Create New Post</h3>
        </div>
        <div class="p-5">
            <form method="POST" action="{{ route('community.post.store') }}">
                @csrf
                <textarea name="content" rows="3" 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="What's on your mind? Share with the community..."></textarea>
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Posts List -->
    @forelse($posts as $post)
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <!-- Post Header -->
        <div class="px-5 py-4 bg-gray-50 border-b rounded-t-xl flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                    {{ substr($post->user_name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $post->user_name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-400">{{ $post->created_at ? $post->created_at->format('d M Y, h:i A') : '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('community.post.destroy', $post->_id) }}" 
                  onsubmit="return confirm('Delete this post permanently?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1 rounded-full hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Post Content -->
        <div class="px-5 py-4">
            <p class="text-gray-800 leading-relaxed">{{ $post->content }}</p>
            
            <!-- Stats Row -->
            <div class="flex items-center gap-6 mt-4 pt-2 text-sm">
                <div class="flex items-center gap-1.5 text-gray-500">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span class="font-medium">{{ $post->likes_count ?? 0 }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-gray-500">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 14.905 3 13.492 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="font-medium">{{ $post->comments_count ?? 0 }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-gray-500">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    <span class="font-medium">{{ $post->shares_count ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Comments Section with Toggle Box -->
        <div class="bg-gray-50 px-5 py-4 border-t rounded-b-xl">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-700 text-sm">Comments ({{ $post->comments_count ?? 0 }})</h4>
                <button type="button" class="toggle-comments text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center gap-1" data-post-id="{{ $post->_id }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: rotate(0deg); transition: transform 0.2s;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <span>Show Comments</span>
                </button>
            </div>
            
            <div class="comments-container hidden" id="comments-{{ $post->_id }}">
                @php
                    $comments = App\Models\CommunityComment::where('post_id', $post->_id)
                        ->orderBy('created_at', 'asc')
                        ->get();
                @endphp

                <div class="space-y-3 mt-2">
                    @forelse($comments as $comment)
                    <div class="flex items-start gap-3 pb-3 border-b border-gray-200 last:border-0 last:pb-0">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-600 text-sm font-medium">{{ substr($comment->user_name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-gray-800 text-sm">{{ $comment->user_name ?? 'User' }}</span>
                                @if($comment->is_admin_reply)
                                    <span class="px-2 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded-full font-medium">Admin</span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-1 leading-relaxed">{{ $comment->comment }}</p>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-red-300" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    {{ $comment->likes_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('community.comment.destroy', $comment->_id) }}"
                              onsubmit="return confirm('Delete this comment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-gray-400 text-sm py-2 text-center">No comments yet</p>
                    @endforelse
                </div>

                <!-- Admin Reply Form -->
                <form method="POST" action="{{ route('community.comment.store') }}" class="mt-4 pt-3 border-t border-gray-200">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->_id }}">
                    <div class="flex gap-2">
                        <input type="text" name="comment" placeholder="Write a reply as Admin..." 
                            class="flex-1 p-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition font-medium">
                            Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-16 bg-white rounded-xl shadow-sm border">
        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">No posts yet</h3>
        <p class="mt-1 text-gray-500">Create your first post using the form above</p>
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>

<script>
    // Toggle comments functionality (Pure JS, no logic change)
    document.querySelectorAll('.toggle-comments').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const container = document.getElementById('comments-' + postId);
            const arrow = this.querySelector('svg');
            const text = this.querySelector('span');
            
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
                text.textContent = 'Hide Comments';
            } else {
                container.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
                text.textContent = 'Show Comments';
            }
        });
    });
</script>

<style>
    .comments-container.hidden {
        display: none;
    }
    .comments-container {
        transition: all 0.2s ease;
    }
</style>
@endsection