<div class="flex">
    <input type="text" name="query" value="{{ request('query') }}" placeholder="{{ $placeholder }}" class="border border-gray-300 rounded-l px-4 py-2 w-72">
    <button type="submit" class="bg-blue-500 text-white rounded-r px-3 py-1 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
    </button>
</div>
