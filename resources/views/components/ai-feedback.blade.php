@props(['feedback'])

<div class="bg-gray-50 rounded-lg p-4 mb-4">
    <h5 class="text-lg font-semibold text-gray-900 mb-2">
        <i class="fas fa-robot mr-2"></i>AI Evaluation
    </h5>
    <div class="prose prose-sm max-w-none">
        {!! nl2br(e($feedback)) !!}
    </div>
</div>
