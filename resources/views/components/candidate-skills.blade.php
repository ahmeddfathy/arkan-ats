@props(['skills'])

<div class="skills-container">
    @foreach(explode(',', $skills) as $skill)
        <span class="badge bg-secondary me-2 mb-2">{{ trim($skill) }}</span>
    @endforeach
</div>
