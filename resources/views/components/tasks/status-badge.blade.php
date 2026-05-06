@props(['completed' => false])

<span @class([
    'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold uppercase tracking-wide',
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' => $completed,
    'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' => ! $completed,
])>
    {{ $completed ? 'Complete' : 'Incomplete' }}
</span>
