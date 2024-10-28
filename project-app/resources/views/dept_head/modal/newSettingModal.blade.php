<style>
    /* Modal Background */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    /* Modal Content */
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Close Button */
    .close-button {
        font-size: 24px;
        font-weight: bold;
        float: right;
        cursor: pointer;
    }

    /* Form Group Styling */
    .form-group {
        margin-bottom: 15px;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn-primary {
        background-color: #3498db;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }
</style>

<!-- Modal Structure -->
<!-- Modal Structure -->
<div id="settingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-1">
    <div class="bg-white p-6 rounded-md shadow-md md:w-1/4 sm:w-[80%]">
        <span class="close-button text-xl cursor-pointer" onclick="closeModal()">×</span>
        <h2 class="text-xl font-semibold mb-4">New Setting</h2>
        <form action="{{ route('setting.create', $activeTab) }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="block font-medium">Name</label>
                <input type="text" id="name" name="nameSet"
                       class="w-full border border-gray-300 rounded-md p-2"
                       placeholder="Name" required />
            </div>
            @if ($activeTab !== 'customFields')
                <div class="mb-3">
                    <label for="decr" class="block font-medium">Description</label>
                    <input type="text" id="decr" name="description"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Description" required />
                </div>
            @else
                <div class="mb-3">
                    <label for="type" class="block font-medium">Type</label>
                    <select name="type" class="type-input w-full">
                        <option value="number">Number</option>
                        <option value="text">Text</option>
                        <option value="date">Date</option>
                    </select>
                    {{-- <input type="text" id="type" name="type"
                           class="w-full border border-gray-300 rounded-md p-2"
                           placeholder="Type" required /> --}}
                </div>
                <div class="mb-3">
                    <label for="helptxt" class="block font-medium">Helper Text</label>
                    <input type="text" id="helptxt" name="helptxt"
                           class="w-full border border-gray-300 rounded-md p-2"
                           placeholder="Helper Text" required />
                </div>
            @endif
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-200 transition duration-300 ease-in-out rounded-md" onclick="closeModal()">Close</button>
                <button type="submit" class="px-4 py-2 bg-blue-950 text-white transition duration-300 ease-in-out hover:bg-blue-950/80 rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>


<script>
  const modal = document.getElementById('settingModal');
const modalContent = modal.querySelector('.bg-white');

function openModal() {
    modal.classList.remove('hidden');
    modal.classList.add('flex'); // Makes modal visible and center-aligned

    // Add an event listener for the Esc key when the modal opens
    document.addEventListener('keydown', handleEscKey);
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    // Remove the Esc key listener when the modal closes
    document.removeEventListener('keydown', handleEscKey);
}

function handleEscKey(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
}

// Prevent clicks inside the modal content from triggering the backdrop close
modalContent.addEventListener('click', (event) => {
    event.stopPropagation();
});

</script>
