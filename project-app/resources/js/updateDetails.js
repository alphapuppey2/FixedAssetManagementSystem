document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('editBTN');
    const saveButton = document.getElementById('saveBTN');
    const cancelButton = document.getElementById('cancelBTN');
    const infoDiv = document.querySelector('#formEdit');

    cancelButton.addEventListener('click', function(){
        editButton.classList.toggle('hidden');
        saveButton.classList.toggle('hidden');
        cancelButton.classList.toggle('hidden');

        const inputElements = infoDiv.querySelectorAll('.edit');
        const displayElements = infoDiv.querySelectorAll('.field-Info');

        inputElements.forEach(element => {
          element.classList.toggle('hidden');
        });
        displayElements.forEach(element => {
          element.classList.toggle('hidden');
        });
    })


    editButton.addEventListener('click', function() {

      editButton.classList.toggle('hidden');
      saveButton.classList.toggle('hidden');
      cancelButton.classList.toggle('hidden');

      const inputElements = infoDiv.querySelectorAll('.edit');
      const displayElements = infoDiv.querySelectorAll('.field-Info')

      inputElements.forEach(element => {
        element.classList.toggle('hidden');
      });
      displayElements.forEach(element => {
        element.classList.toggle('hidden');
      });
    });
  });
