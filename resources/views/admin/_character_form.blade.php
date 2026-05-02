@php $c = $character; @endphp

<div class="form-grid">
    <div class="form-group">
        <label class="form-label">Character Name *</label>
        <input type="text" name="name" class="form-control" required
            value="{{ $c->name ?? old('name') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Actor / Actress Name</label>
        <input type="text" name="actor_name" class="form-control"
            value="{{ $c->actor_name ?? old('actor_name') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Role</label>
        <input type="text" name="role" class="form-control"
            placeholder="e.g. Lead, Villain, Supporting"
            value="{{ $c->role ?? old('role') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Movie</label>
        <select name="movie_id" class="form-control">
            <option value="">— No movie —</option>
            @foreach($movies as $movie)
            <option value="{{ $movie->id }}" {{ ($c && $c->movie_id == $movie->id) ? 'selected' : '' }}>
                {{ $movie->title }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control"
            value="{{ $c->sort_order ?? 0 }}">
    </div>
    <div class="form-group">
        <label class="form-label">Photo</label>
        <div class="file-input-wrap">
            <label class="file-input-label">
                <i class="fas fa-user-circle"></i>
                <span>{{ ($c && $c->photo) ? basename($c->photo) : 'Choose photo…' }}</span>
            </label>
            <input type="file" name="photo" accept="image/*">
        </div>
        @if($c && $c->photo)
        <img src="{{ Storage::url($c->photo) }}" class="file-preview">
        @endif
    </div>
    <div class="form-group full">
        <label class="form-label">Bio / Character Description</label>
        <textarea name="bio" class="form-control" rows="3">{{ $c->bio ?? old('bio') }}</textarea>
    </div>
</div>