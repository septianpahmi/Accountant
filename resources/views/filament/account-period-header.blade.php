<div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded-lg border mb-2">
    <h3 class="text-sm font-medium text-gray-600">
        @if ($start && $end)
            <span class="font-semibold text-gray-800">
                {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
                —
                {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            </span>
        @elseif($start)
            <span class="font-semibold text-gray-800">
                Mulai {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
            </span>
        @elseif($end)
            <span class="font-semibold text-gray-800">
                Sampai {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            </span>
        @endif
    </h3>
</div>
