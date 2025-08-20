<div class="reviewList">
    <ol>
        @foreach ($comments as $index => $comment)
            <li>
                <div class="d-flex gap-2">
                    {{-- Avatar của người bình luận --}}
                    @if ($comment->user?->avatar)
                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar"
                            class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/client/images/author/default.jpg') }}" alt="Default Avatar"
                            class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                    @endif

                    <div class="flex-grow-1">
                        <h2 class="h6 mb-1">
                            {{ $comment->user?->fullname ?? $comment->user?->name ?? 'Người dùng đã xoá' }}
                            <span class="text-muted">#{{ ($comments->currentPage() - 1) * $comments->perPage() + $loop->iteration }}</span>
                        </h2>

                        <div class="postReviewContent mb-2">{{ $comment->content }}</div>

                        <div class="reviewMeta mb-2">
                            <span>{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <a href="javascript:void(0);" class="toggle-reply text-muted small" data-id="{{ $comment->id }}">
                            <i class="fa fa-reply"></i> Trả lời
                        </a>

                        {{-- Form trả lời --}}
                        <form method="POST" class="reply-form mt-2 d-none" id="reply-form-{{ $comment->id }}">
                            @csrf
                            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                            <input type="hidden" name="reply_user_id" value="{{ $comment->user_id }}">
                            <div class="input-group">
                                <input type="text" name="content" class="form-control form-control-sm" placeholder="Nhập câu trả lời...">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">Gửi</button>
                            </div>
                        </form>
                        <div class="reply-message text-danger mt-1"></div> {{-- lỗi sẽ hiển thị tại đây --}}

                        {{-- Danh sách trả lời --}}
                        @foreach ($comment->replies as $reply)
                            <div class="mt-3 ps-3 border-start d-flex gap-2">
                                @if ($reply->user?->avatar)
                                    <img src="{{ asset('storage/' . $reply->user->avatar) }}" alt="Avatar"
                                        class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('assets/client/images/author/default.jpg') }}" alt="Default Avatar"
                                        class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                @endif

                                <div>
                                    <strong>{{ $reply->user?->fullname ?? $reply->user?->name ?? 'Người dùng đã xoá' }}</strong>
                                    <p class="mb-1">{{ $reply->content }}</p>
                                    <small class="text-muted">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </li>
        @endforeach
    </ol>
</div>

<div class="pagination-area mt-3">{{ $comments->links() }}</div>

<style>
    .d-none {
        display: none;
    }
</style>
