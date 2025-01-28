@props(['columns', 'sortColumn', 'sortDirection', 'route'])

<thead>
    <tr class="bg-gray-800">
        @foreach ($columns as $column => $label)
            <th class="border border-white px-4 py-2 {{ app()->getLocale() === 'ur' ? 'text-right' : 'text-left' }}">
                <a href="{{ route($route, ['sort' => $column, 'direction' => $sortColumn === $column && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                   class="hover:underline">
                    {{ $label }}
                    @if ($sortColumn === $column)
                        @if ($sortDirection === 'asc') ▲ @else ▼ @endif
                    @endif
                </a>
            </th>
        @endforeach
        <th class="border border-white px-4 py-2 {{ app()->getLocale() === 'ur' ? 'text-right' : 'text-left' }}">@lang('components.actions')</th>
    </tr>
</thead>
