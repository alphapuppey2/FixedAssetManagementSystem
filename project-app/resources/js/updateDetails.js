document.addEventListener('DOMContentLoaded', function() {
    // Get references to the edit button and the div you want to toggle
    const editButton = document.getElementById('editBTN');
    const saveButton = document.getElementById('saveBTN');
    const cancelButton = document.getElementById('cancelBTN');
    const infoDiv = document.querySelector('#formEdit'); // Assuming the div has this class

    console.log(infoDiv);
    cancelButton.addEventListener('click', function(){
        editButton.classList.toggle('hidden');
        saveButton.classList.toggle('hidden');
        cancelButton.classList.toggle('hidden');

        const inputElements = infoDiv.querySelectorAll('.edit');
        const displayElements = infoDiv.querySelectorAll('.field-Info');

        // Toggle the visibility of input elements
        inputElements.forEach(element => {
          element.classList.toggle('hidden');
        });
        displayElements.forEach(element => {
          element.classList.toggle('hidden');
        });
    })

    // Add a click event listener to the edit button
    editButton.addEventListener('click', function() {
      // Toggle the visibility of the div and input elements
      editButton.classList.toggle('hidden');
      saveButton.classList.toggle('hidden');
      cancelButton.classList.toggle('hidden');

      // Get all input elements within the div (or a specific class if needed)
      console.log(infoDiv);
      const inputElements = infoDiv.querySelectorAll('.edit');
      const displayElements = infoDiv.querySelectorAll('.field-Info')


      // Toggle the visibility of input elements
      inputElements.forEach(element => {
        element.classList.toggle('hidden');
      });
      displayElements.forEach(element => {
        element.classList.toggle('hidden');
      });
    });
  });
