<div class="flex">
    <div class="relative items-center">
        <!-- Search Input -->
        <input
            type="text"
            name="query"
            value="{{ request('query') }}"
            placeholder="{{ $placeholder }}"
            class="border border-gray-300 rounded-l px-4 py-2 focus:outline-none pr-8 {{ $attributes->get('class') }}"
            data-search-input>

        <!-- Clear "X" button inside the search bar -->
        <button
            type="button"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-transparent text-gray-500 hidden focus:outline-none"
            data-clear-btn>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Search Button -->
    <button type="submit" class="bg-blue-500 text-white rounded-r px-3 py-1 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
    </button>
</div>

<script>
    document.querySelectorAll('.search-container').forEach((container) => {
        const searchInput = container.querySelector('[data-search-input]');
        const clearBtn = container.querySelector('[data-clear-btn]');

        // Show "X" button when input has value
        searchInput.addEventListener('input', () => {
            if (searchInput.value.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        });

        // Clear input and hide "X" button when clicked
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('hidden');
            searchInput.focus(); // Refocus the input after clearing
        });

        // Show "X" button on focus if input has value
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.length > 0) {
                clearBtn.classList.remove('hidden');
            }
        });

        // Hide "X" button on blur, with a small delay
        searchInput.addEventListener('blur', () => {
            setTimeout(() => {
                clearBtn.classList.add('hidden');
            }, 200); // Delay to allow clicking on "X" button before hiding
        });
    });
</script>