document.addEventListener('DOMContentLoaded', function () {
    function editProfile(event) {
        event.preventDefault();

        // Hide the profile buttons and show the edit buttons
        document.getElementById('profile-buttons').classList.add('hidden');
        document.getElementById('edit-buttons').classList.remove('hidden');

        // Toggle the address, contact, birthdate, and gender fields
        document.querySelectorAll('#address-display, #contact-display, #birthdate-view, #gender-view').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('#address-edit, #contact-edit, #birthdate-edit, #gender-edit').forEach(el => el.classList.remove('hidden'));

        // Show the camera icon
        document.getElementById('cameraIcon').classList.remove('hidden');

        // Update the form action
        let formAction = window.routes.profileUpdate;
        document.getElementById('profile-form').action = formAction;
    }

    function saveProfile(event) {
        event.preventDefault();
        document.getElementById('profile-form').submit();
    }

    function cancelEdit(event) {
        event.preventDefault();

        // Show the profile buttons and hide the edit buttons
        document.getElementById('profile-buttons').classList.remove('hidden');
        document.getElementById('edit-buttons').classList.add('hidden');

        // Toggle the address, contact, birthdate, and gender fields back to view mode
        document.querySelectorAll('#address-display, #contact-display, #birthdate-view, #gender-view').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('#address-edit, #contact-edit, #birthdate-edit, #gender-edit').forEach(el => el.classList.add('hidden'));

        // Hide the camera icon
        document.getElementById('cameraIcon').classList.add('hidden');
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhoto').src = e.target.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Attach event listeners
    document.querySelector('a[onclick="editProfile(event)"]').addEventListener('click', editProfile);
    document.querySelector('a[onclick="saveProfile(event)"]').addEventListener('click', saveProfile);
    document.querySelector('a[onclick="cancelEdit(event)"]').addEventListener('click', cancelEdit);
    document.getElementById('profile_photo').addEventListener('change', previewImage);

    const toast = document.getElementById('toast');
    if (toast) {
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000); // Hide the toast after 3 seconds
    }
});
