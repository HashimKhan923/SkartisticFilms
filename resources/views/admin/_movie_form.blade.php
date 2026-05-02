{{-- Reusable movie form fields --}}
@php $m = $movie; @endphp

<div class="form-grid">
    <div class="form-group">
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control" required
            value="{{ $m->title ?? old('title') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Genre</label>
        <input type="text" name="genre" class="form-control"
            placeholder="e.g. Drama, Thriller"
            value="{{ $m->genre ?? old('genre') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Year</label>
        <input type="number" name="year" class="form-control"
            min="1900" max="{{ date('Y') + 2 }}"
            value="{{ $m->year ?? old('year') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Duration (min)</label>
        <input type="number" name="duration" class="form-control"
            min="1" value="{{ $m->duration ?? old('duration') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Rating (0–10)</label>
        <input type="number" name="rating" class="form-control"
            min="0" max="10" step="0.1"
            value="{{ $m->rating ?? old('rating') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control"
            value="{{ $m->sort_order ?? 0 }}">
    </div>
    <div class="form-group full">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $m->description ?? old('description') }}</textarea>
    </div>
    <div class="form-group full">
        <label class="toggle-label">
            <input type="checkbox" name="is_featured" value="1"
                {{ ($m && $m->is_featured) ? 'checked' : '' }}>
            Mark as Featured (shown on hero)
        </label>
    </div>
</div>

{{-- Media --}}
<div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
    <p style="font-size:11px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);margin-bottom:16px;">
        Media Files
    </p>

    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Poster Image</label>
            <div class="file-input-wrap">
                <label class="file-input-label">
                    <i class="fas fa-image"></i>
                    <span>{{ ($m && $m->poster) ? basename($m->poster) : 'Choose poster…' }}</span>
                </label>
                <input type="file" name="poster" accept="image/*">
            </div>
            @if($m && $m->poster)
            <img src="{{ Storage::url($m->poster) }}" class="file-preview">
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">Banner / Wide Image</label>
            <div class="file-input-wrap">
                <label class="file-input-label">
                    <i class="fas fa-image"></i>
                    <span>{{ ($m && $m->banner) ? basename($m->banner) : 'Choose banner…' }}</span>
                </label>
                <input type="file" name="banner" accept="image/*">
            </div>
            @if($m && $m->banner)
            <img src="{{ Storage::url($m->banner) }}" class="file-preview">
            @endif
        </div>
    </div>

    {{-- Video type --}}
    @php $uid = $m ? 'edit_'.$m->id : 'add'; @endphp

    <div class="form-group" style="margin-top:16px;">
        <label class="form-label">Video Source</label>
        <div class="radio-group">
            <label class="radio-option">
                <input type="radio" name="video_type" value="youtube"
                    {{ (!$m || ($m->video_type ?? 'youtube') === 'youtube') ? 'checked' : '' }}
                    onchange="toggleMovieVideo('{{ $uid }}', this.value)">
                YouTube Link
            </label>
            <label class="radio-option">
                <input type="radio" name="video_type" value="upload"
                    {{ ($m && $m->video_type === 'upload') ? 'checked' : '' }}
                    onchange="toggleMovieVideo('{{ $uid }}', this.value)">
                Upload Video
            </label>
        </div>
    </div>

    <div id="mv-yt-{{ $uid }}" class="form-group" style="margin-top:10px;">
        <label class="form-label">YouTube URL</label>
        <input type="url" name="video_youtube" class="form-control"
            placeholder="https://www.youtube.com/watch?v=…"
            value="{{ $m->video_youtube ?? old('video_youtube') }}">
    </div>

    <div id="mv-file-{{ $uid }}" class="form-group" style="margin-top:10px;display:none;">
        <label class="form-label">Video File (MP4/WebM, max 200MB)</label>
        <div class="file-input-wrap">
            <label class="file-input-label">
                <i class="fas fa-video"></i>
                <span>{{ ($m && $m->video_file) ? basename($m->video_file) : 'Choose video…' }}</span>
            </label>
            <input type="file" name="video_file" accept="video/mp4,video/webm">
        </div>
        @if($m && $m->video_file)
        <p class="form-hint" style="color:var(--gold);">Current: {{ basename($m->video_file) }}</p>
        @endif
    </div>
</div>

<script>
(function() {
    var uid = '{{ $uid }}';
    function init() {
        var type = document.querySelector('input[name="video_type"]:checked');
        if (type) toggleMovieVideo(uid, type.value);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>