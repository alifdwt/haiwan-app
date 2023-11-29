@props(['value'])

<label {{ $attributes->merge(['class' => 'block mb-2 font-medium text-[#374151] dark:text-[#d1d5db]']) }}>
    {{ $value ?? $slot }}
</label>
