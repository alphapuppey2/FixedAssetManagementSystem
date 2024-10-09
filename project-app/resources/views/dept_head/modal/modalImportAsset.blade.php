<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-[350px] shadow-lg relative"> <!-- Added relative for positioning -->

        <!-- Close Icon at the top-right corner -->
        <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-600 hover:text-gray-800 text-2xl">
            &times;
        </button>

        <h3 class="text-lg font-semibold mb-4">Import Asset</h3>

        <!-- Instructions Section -->
        <div class="bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3 rounded-md mb-4">
            <p class="font-medium">Instructions:</p>
            <ul class="list-disc ml-4 text-sm mt-2">
                <li>Download the <b><a href="{{ route('download.csv.template') }}" class="text-blue-900 underline">CSV template</a></b>.</li>
                <li>Ensure your CSV file follows the template format and columns.</li>
                <li>Once ready, upload your CSV file below.</li>
            </ul>
        </div>

        <!-- Upload CSV Button -->
        <div class="mb-4">
            <label for="csvUpload" class="block mb-2 text-sm font-medium text-gray-700">Upload CSV File:</label>
            <input type="file" id="csvUpload" accept=".csv" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
        </div>

        <!-- Error Notification -->
        <div id="uploadError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Please upload a valid CSV file matching the template columns.</span>
        </div>

        <!-- Next Button -->
        <div class="mb-4 hidden" id="nextButtonContainer">
            <button id="nextButton" class="w-full bg-blue-950 text-white py-2 px-4 rounded-md text-center">
                Next
            </button>
        </div>
    </div>
</div>


<!-- Preview Modal (Increased Size) -->
<div id="previewModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-[80%] h-[80%] shadow-lg"> <!-- Increased size to 80% of the viewport -->
        <h3 class="text-lg font-semibold mb-4">Preview CSV Data</h3>

        <!-- Scrollable Table for CSV Data -->
        <div class="tableContainer overflow-y-auto max-h-[60vh] border rounded-md"> <!-- Scrollable with a max height -->
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
                <button id="uploadButton" class="bg-blue-950 text-white px-4 py-2 rounded-md hover:bg-green-600">Upload</button>
                <button id="closePreviewModalBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
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
        const uploadError = document.getElementById('uploadError');
        // Fetch CSRF token from the meta tag in the HTML head
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                    parseCSV(csvText); // Parse CSV and display in preview modal
                    openPreviewModal();
                    closeModal('importModal'); // Close the upload modal
                };
                reader.readAsText(file);
            }
        });

        // Parse CSV Content
        function parseCSV(csvText) {
            const rows = csvText
                .split('\n')
                .map(row => row.split(','))
                .filter(row => row.some(cell => cell.trim() !== "")); // Filter out empty rows

            csvData = rows.slice(1); // Data rows (excluding header)
            const headers = rows[0]; // CSV headers

            // Build Table Header
            previewTableHeader.innerHTML = `<th><input type="checkbox" id="selectAll" checked></th>`;
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
            updateSelectedSummary(); // Update the checked summary
            setupCheckboxListeners(); // Setup the checkbox interactions
        }


        // Open and Close Modals
        function openPreviewModal() {
            previewModal.classList.remove('hidden'); // Show the preview modal
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden'); // Close the modal by adding the 'hidden' class
        }

        // Checkbox Logic for Rows and "Select All"
        function setupCheckboxListeners() {
            const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
            const selectAllCheckbox = document.getElementById('selectAll');

            // Handle "Select All" Checkbox
            selectAllCheckbox.checked = true; // Set selectAll as checked by default
            selectAllCheckbox.addEventListener('change', function() {
                const checked = selectAllCheckbox.checked;
                selectedRows = []; // Reset selected rows

                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = checked;
                    const index = parseInt(checkbox.getAttribute('data-index'));
                    if (checked) {
                        selectedRows.push(index); // Select all rows
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
            const totalRows = csvData.length; // Only count data rows, not the header
            selectedSummary.textContent = `${selectedRows.length} of ${totalRows} rows are checked`;
        }


        // Upload Button Click Event
        // Upload Button Click Event
        uploadButton.addEventListener('click', function() {
            if (selectedRows.length === 0) {
                alert('At least 1 row must be checked.'); // Show alert if no rows are checked
            } else {
                const checkedRows = selectedRows.map(index => csvData[index]); // Get only the checked rows
                const headers = Array.from(document.querySelectorAll('#previewTableHeader th')).slice(1).map(th => th.textContent.trim()); // Extract headers from the preview table

                // Send the headers and checked rows to the server via AJAX
                fetch('{{ route("upload.csv") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // Dynamically use the token
                        },
                        body: JSON.stringify({
                            headers: headers, // Include headers in the request
                            rows: checkedRows // Include rows in the request
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json(); // Attempt to parse JSON response
                    })
                    .then(data => {
                        if (data && data.success) {
                            alert('Data uploaded successfully.');
                            closeModal('previewModal');
                        } else {
                            alert('Error: ' + (data.message || 'Unknown error occurred'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error uploading data: ' + error.message);
                    });
            }
        });

        // Close Buttons for Modals
        closeModalBtn.addEventListener('click', function() {
            closeModal('importModal');
        });
        closePreviewModalBtn.addEventListener('click', function() {
            closeModal('previewModal');
        });
    });
</script>





