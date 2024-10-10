@props(['title' , 'counts'])

<div {{ $attributes->merge(['class' => 'card w-[50%]']) }}>
    <div class="card-header">
        {{ $title }}
    </div>
    <div class="card-body">
        {{ $counts }}
    </div>
</div>
