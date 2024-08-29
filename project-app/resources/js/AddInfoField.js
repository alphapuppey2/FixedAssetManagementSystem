function addNewFields() {
    // Create a new fieldSet div
    let newFieldSet = document.createElement('div');
    newFieldSet.className = 'fieldSet mt-2 grid grid-cols-2 gap-2';

    // Create the key input
    let newKeyInput = document.createElement('input');
    newKeyInput.type = 'text';
    newKeyInput.name = 'field[key][]';
    newKeyInput.placeholder = 'key';
    // newKeyInput.className = '';

    // Create the value input
    let newValueInput = document.createElement('input');
    newValueInput.type = 'text';
    newValueInput.name = 'field[value][]';
    newValueInput.placeholder = 'value';

    // add key and Value to div.newFieldSet
    newFieldSet.appendChild(newKeyInput);
    newFieldSet.appendChild(newValueInput);

    // Add newFieldSet to the Container
    document.querySelector('.addInfoContainer').appendChild(newFieldSet);
}

// Add event listener to the "Add More Fields" button
document.getElementById('addMoreFields').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent form submission
    addNewFields();
});
