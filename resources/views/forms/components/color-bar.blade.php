@props(['hex' => null])

@if ($hex)
    <span class="inline-block size-5 rounded align-middle mx-1 shadow-inner border border-gray-300 dark:border-gray-600" style="background-color: {{ $hex }}"></span>
@endif
