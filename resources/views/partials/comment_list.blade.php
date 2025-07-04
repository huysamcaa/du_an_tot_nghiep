<div class="reviewList">
    <ol>
        @foreach ($comments as $comment)
        <li>
            <div class="postReview">
                <img src="{{ asset('assets/client/images/author/7.jpg') }}" alt="Post Review">
                <h2>{{ $comment->user->fullname ?? $comment->user->name ?? 'Khách' }}</h2>
                <div class="postReviewContent">{{ $comment->content }}</div>
                <div class="reviewMeta">
                    <span>{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <!-- NÚT HIỆN FORM TRẢ LỜI -->
                <a href="javascript:void(0);" class="toggle-reply text-muted small" data-id="{{ $comment->id }}">
                    <i class="fa fa-reply"></i> Trả lời
                </a>

                <!-- FORM TRẢ LỜI GỌN GÀNG -->
                <form method="POST" class="reply-form mt-2 d-none" id="reply-form-{{ $comment->id }}">
                    @csrf
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <input type="hidden" name="reply_user_id" value="{{ $comment->user_id }}">
                    <div class="input-group">
                        <input type="text" name="content" class="form-control form-control-sm" placeholder="Nhập câu trả lời...">
                        <button class="btn btn-sm btn-outline-secondary" type="submit">
                            Gửi
                        </button>
                    </div>
                </form>

                <!-- DANH SÁCH TRẢ LỜI -->
                @foreach ($comment->replies as $reply)
                <div class="mt-3 ps-3 border-start">
                    <strong>{{ $reply->user->fullname ?? $reply->user->name ?? 'Khách' }}</strong>
                    <p>{{ $reply->content }}</p>
                    <small>{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                </div>
                @endforeach
            </div>
        </li>
        @endforeach
    </ol>
</div>
<div class="pagination-area mt-3">{{ $comments->links() }}</div>


<style>
.d-none { display: none; }
</style>