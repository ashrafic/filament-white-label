@props([
    'statePath' => null,
    'colors' => [],
])

<div
    x-data="{
        get selected() {
            return $wire.get(@js($statePath));
        },
        select(hex) {
            $wire.set(@js($statePath), hex);
        }
    }"
    class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2"
>
    @foreach ($colors as $key => $label)
        @php
            $hex = $label['hex'] ?? '';
            $name = $label['name'] ?? $key;
        @endphp
        <button
            type="button"
            x-on:click="select(@js($hex))"
            x-bind:class="selected === @js($hex) ? 'ring-primary-500 ring-2 ring-offset-1' : 'ring-1 ring-gray-200 dark:ring-gray-700'"
            class="flex flex-col items-center gap-1 rounded-lg p-2 text-center transition hover:ring-gray-400"
            title="{{ $name }} &middot; {{ $hex }}"
        >
            <span
                class="size-6 rounded shadow-inner"
                style="background-color: {{ $hex }}"
            ></span>
            <span class="text-xs text-gray-600 dark:text-gray-400 truncate w-full">{{ $name }}</span>
        </button>
    @endforeach
</div>
