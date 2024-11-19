<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-3">
    <div class="bg-white rounded-lg p-6 w-[350px] shadow-lg relative">

        <!-- Close Icon at the top-right corner -->
        <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-600 hover:text-gray-800 text-2xl">
            &times;
        </button>

        <h3 class="text-lg font-semibold mb-4">Import Asset</h3>

        <!-- Instructions Section -->
        <div class="bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3 rounded-md mb-4">
            <p class="font-medium">Instructions:</p>
            <ul class="list-disc ml-4 text-sm mt-2">
                <li>Download the <b><a href="{{ route('download.csv.template') }}" class="text-blue-900 underline">CSV
                            template</a></b>.</li>
                <li>Ensure your CSV file follows the template format and columns.</li>
                <li>Once ready, upload your CSV file below.</li>
            </ul>
        </div>

        <!-- Upload CSV Button -->
        <div class="mb-4">
            <label for="csvUpload" class="block mb-2 text-sm font-medium text-gray-700">Upload CSV File:</label>
            <input type="file" id="csvUpload" accept=".csv"
                class="block w-full text-sm text-gray-900 border border-gray-300 cursor-pointer bg-gray-50" />
        </div>

        <!-- Error Notification -->
        <div id="uploadError"
            class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Please upload a valid CSV file matching the template columns.</span>
        </div>

        <!-- Next Button -->
        <div class="mb-4 hidden" id="nextButtonContainer">
            <button id="nextButton"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-md text-center hover:bg-blue-600">
                Next
            </button>
        </div>
    </div>
</div>

<!-- New Manufacturer Modal -->
<div id="newManufacturerModal"
    class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-3">
    <div class="bg-white rounded-lg p-6 w-[500px] shadow-lg">
        <h3 class="text-lg font-semibold mb-4">New Manufacturers</h3>
        <form id="newManufacturerForm">
            <div id="newManufacturerContainer">
                <!-- Manufacturer rows dynamically added here -->
            </div>
            <button type="button" id="saveManufacturersButton"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mt-4">
                Continue
            </button>
        </form>
    </div>
</div>

<!-- Preview Modal (Increased Size) -->
<div id="previewModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-3">
    <div class="bg-white rounded-lg p-6 w-[80%] h-[80%] shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Preview CSV Data</h3>

        <!-- Scrollable Table for CSV Data -->
        <div class="tableContainer overflow-y-auto max-h-[60vh] border rounded-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr id="previewTableHeader">
                        <!-- CSV Headers will be dynamically inserted here -->
                    </tr>
                </thead>
                <tbody id="previewTableBody">
                    <!-- CSV Data will be dynamically inserted here -->
                </tbody>
            </table>
        </div>

        <!-- Checkboxes Summary and Upload Button -->
        <div class="flex justify-between items-center mt-4">
            <span id="selectedSummary" class="text-gray-700">21 of 21 rows are checked</span>
            <div class="flex space-x-2">
                <button id="uploadButton"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Upload</button>
                <button id="closePreviewModalBtn"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
</div>
@if (session('success'))
    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                showFlashMessage("{{ session('success') }}");
            }, 500); // 500ms delay after the page loads
        });

        function showFlashMessage(message) {
            const flashContainer = document.createElement('div');
            flashContainer.className = 'flash-message';
            flashContainer.innerText = message;
            document.body.appendChild(flashContainer);

            // Auto-hide after 3 seconds
            setTimeout(() => {
                flashContainer.style.display = 'none';
            }, 3000);
        }
    </script>
@endif

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>

<script>
    // Function to display the new manufacturer modal
    function openNewManufacturerModal(manufacturers) {
        if (!Array.isArray(manufacturers)) {
            console.error('Expected manufacturers to be an array, but received:', manufacturers);
            manufacturers = Object.values(manufacturers); // Convert object to array
        }
        const newManufacturerModal = document.getElementById('newManufacturerModal');
        const newManufacturerContainer = document.getElementById('newManufacturerContainer');
        newManufacturerContainer.innerHTML = '';

        manufacturers.forEach(manufacturer => {
            newManufacturerContainer.innerHTML += `
            <div class="flex items-center mb-2">
                <input type="text" value="${manufacturer}" readonly class="bg-gray-100 border rounded p-2 flex-1 mr-2">
                <input type="text" name="descriptions[]" placeholder="Description" required class="border rounded p-2 flex-1">
            </div>
        `;
        });
        newManufacturerModal.classList.remove('hidden');
    }

    // Ensure this function is globally accessible (optional)
    window.openNewManufacturerModal = openNewManufacturerModal;

    document.addEventListener('DOMContentLoaded', function() {
        // const newManufacturerModal = document.getElementById('newManufacturerModal');
        // const newManufacturerContainer = document.getElementById('newManufacturerContainer');
        // const saveManufacturersButton = document.getElementById('saveManufacturersButton');

        // Function to display the new manufacturer modal
        // function openNewManufacturerModal(manufacturers) {
        //     newManufacturerContainer.innerHTML = '';
        //     manufacturers.forEach(manufacturer => {
        //         newManufacturerContainer.innerHTML += `
        //     <div class="flex items-center mb-2">
        //         <input type="text" value="${manufacturer}" readonly class="bg-gray-100 border rounded p-2 flex-1 mr-2">
        //         <input type="text" name="descriptions[]" placeholder="Description" required class="border rounded p-2 flex-1">
        //     </div>
        // `;
        //     });
        //     newManufacturerModal.classList.remove('hidden');
        // }

        // Handle saving new manufacturers
        saveManufacturersButton.addEventListener('click', function() {
            const rows = [...newManufacturerContainer.querySelectorAll('.flex')];
            const data = rows.map(row => ({
                name: row.querySelector('input[type="text"]:first-child').value,
                description: row.querySelector('input[name="descriptions[]"]').value,
            }));

            fetch('{{ route('save.new.manufacturers') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Use the CSRF token here
                    },
                    body: JSON.stringify({
                        manufacturers: data
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        newManufacturerModal.classList.add('hidden');
                        alert('New manufacturers added successfully. Continuing upload...');
                        // Retry the CSV upload
                        retryCsvUpload();
                    } else {
                        alert('Error saving manufacturers: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });


        // Retry CSV Upload
        function retryCsvUpload() {
            uploadButton.click(); // Trigger the upload logic again
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const csvUploadInput = document.getElementById('csvUpload');
        const nextButtonContainer = document.getElementById('nextButtonContainer');
        const nextButton = document.getElementById('nextButton');
        const importModal = document.getElementById('importModal');
        const previewModal = document.getElementById('previewModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const closePreviewModalBtn = document.getElementById('closePreviewModalBtn');
        const previewTableHeader = document.getElementById('previewTableHeader');
        const previewTableBody = document.getElementById('previewTableBody');
        const selectedSummary = document.getElementById('selectedSummary');
        const uploadButton = document.getElementById('uploadButton');
        const loadingScreen = document.getElementById('loadingScreen');
        const uploadError = document.getElementById('uploadError');
        // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let csvData = [];
        let selectedRows = [];

        // CSV Upload Event
        csvUploadInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            // Check if the file is a CSV
            if (file && file.type === 'text/csv') {
                // Hide error message if any and show the "Next" button
                uploadError.classList.add('hidden');
                nextButtonContainer.classList.remove('hidden');
            } else {
                // Show error message and hide "Next" button
                uploadError.classList.remove('hidden');
                nextButtonContainer.classList.add('hidden');
            }
        });

        // "Next" Button Click Event
        nextButton.addEventListener('click', function() {
            const file = csvUploadInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const csvText = e.target.result;
                    parseCSV(csvText);
                    openPreviewModal();
                    closeModal('importModal');
                };
                reader.readAsText(file);
            }
        });

        // Parse CSV Content
        function parseCSV(csvText) {
            const rows = csvText
                .split('\n')
                .map(row => row.split(','))
                .filter(row => row.some(cell => cell.trim() !== ""));

            csvData = rows.slice(1);
            const headers = rows[0];

            // Build Table Header
            previewTableHeader.innerHTML = `<th><input type="checkbox" id="selectAllImport" checked></th>`;
            headers.forEach(header => {
                previewTableHeader.innerHTML += `<th>${header}</th>`;
            });

            // Build Table Body with Checkbox per row
            previewTableBody.innerHTML = '';
            csvData.forEach((row, index) => {
                const rowHTML = `<tr>
            <td><input type="checkbox" class="rowCheckbox" data-index="${index}" checked></td>
                ${row.map(cell => `<td>${cell}</td>`).join('')}
            </tr>`;
                previewTableBody.innerHTML += rowHTML;
            });

            // Set default selected rows to all
            selectedRows = csvData.map((_, index) => index);
            updateSelectedSummary();
            setupCheckboxListeners();
        }

        // Open and Close Modals
        function openPreviewModal() {
            previewModal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Checkbox Logic for Rows and "Select All"
        function setupCheckboxListeners() {
            const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
            const selectAllCheckbox = document.getElementById('selectAllImport');

            // Handle "Select All" Checkbox
            selectAllCheckbox.checked = true;
            selectAllCheckbox.addEventListener('change', function() {
                const checked = selectAllCheckbox.checked;
                selectedRows = [];

                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = checked;
                    const index = parseInt(checkbox.getAttribute('data-index'));
                    if (checked) {
                        selectedRows.push(index);
                    }
                });
                updateSelectedSummary();
            });

            // Handle Individual Row Checkbox Changes
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const index = parseInt(checkbox.getAttribute('data-index'));
                    if (checkbox.checked) {
                        selectedRows.push(index);
                    } else {
                        selectedRows = selectedRows.filter(i => i !== index);
                    }
                    updateSelectedSummary();
                });
            });
        }

        // Update Selected Summary
        function updateSelectedSummary() {
            const totalRows = csvData.length;
            selectedSummary.textContent = `${selectedRows.length} of ${totalRows} rows are checked`;
        }

        uploadButton.addEventListener('click', function() {
            if (selectedRows.length === 0) {
                showToast('At least 1 row must be checked.', 'error');
            } else {
                const checkedRows = selectedRows.map(index => csvData[index]);
                const headers = Array.from(document.querySelectorAll('#previewTableHeader th')).slice(1)
                    .map(th => th.textContent.trim());

                uploadButton.disabled = true;
                loadingScreen.classList.remove('hidden');

                fetch('{{ route('upload.csv') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            headers: headers,
                            rows: checkedRows
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.success) {
                            showToast('Data uploaded successfully.', 'success');
                            closeModal('previewModal');
                        } else if (data.newManufacturers) {
                            // Display the New Manufacturers Modal
                            closeModal(
                                'previewModal'
                            );
                            openNewManufacturerModal(data.newManufacturers);
                        } else {
                            showToast(`Error: ${data.message || 'Unknown error occurred'}`,
                                'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast(`Error uploading data: ${error.message}`, 'error');
                    })
                    .finally(() => {
                        // Re-enable the button and hide the loading screen
                        uploadButton.disabled = false;
                        loadingScreen.classList.add('hidden');
                        // location.reload();
                    });
            }
        });

        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');

            toast.className =
                `px-4 py-2 rounded shadow-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.textContent = message;

            toastContainer.appendChild(toast);
            toastContainer.classList.remove('hidden');

            setTimeout(() => {
                toast.style.transition = 'opacity 0.5s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        closeModalBtn.addEventListener('click', function() {
            closeModal('importModal');
        });
        closePreviewModalBtn.addEventListener('click', function() {
            closeModal('previewModal');
        });
    });
</script>
