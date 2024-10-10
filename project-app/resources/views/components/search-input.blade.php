<div class="flex">
    <div class="relative items-center">
        <!-- Search Input -->
        <input type="text" name="query" value="{{ request('query') }}" placeholder="{{ $placeholder }}"
            class="border border-gray-300 rounded-l px-4 py-2 w-72 focus:outline-none pr-8" id="searchInput">
        <!-- Clear "X" button inside the search bar -->
        <button type="button" id="clearBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-transparent text-gray-500 hidden focus:outline-none">
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
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearBtn');

    // Show "X" button when input is clicked and has value
    searchInput.addEventListener('focus', () => {
        if (searchInput.value.length > 0) {
            clearBtn.classList.remove('hidden');
        }
    });

    // Show "X" button when user types
    searchInput.addEventListener('input', () => {
        if (searchInput.value.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    });

    // Hide "X" button when input loses focus, unless there is still a value
    searchInput.addEventListener('blur', () => {
        setTimeout(() => {
            clearBtn.classList.add('hidden');
        }, 200); // Delay to allow clicking on the "X" button before hiding it
    });

    // Clear input when "X" button is clicked
    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('hidden');
        searchInput.focus(); // Refocus on the input after clearing
    });
</script>
