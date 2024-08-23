function addNewFields() {
    // Create a new fieldSet div
    let newFieldSet = document.createElement('div');
    newFieldSet.className = 'fieldSet mt-2';

    // Create the key input
    let newKeyInput = document.createElement('input');
    newKeyInput.type = 'text';
    newKeyInput.name = 'field[key][]';
    newKeyInput.placeholder = 'key';
    newKeyInput.className = 'mr-2';

    // Create the value input
    let newValueInput = document.createElement('input');
    newValueInput.type = 'text';
    newValueInput.name = 'field[value][]';
    newValueInput.placeholder = 'value';

    // Append the new inputs to the fieldSet div
    newFieldSet.appendChild(newKeyInput);
    newFieldSet.appendChild(newValueInput);

    // Append the new fieldSet div to the addInfoContainer
    document.querySelector('.addInfoContainer').appendChild(newFieldSet);
}

// Add event listener to the "Add More Fields" button
document.getElementById('addMoreFields').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent form submission
    addNewFields();
});
