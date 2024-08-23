document.addEventListener('DOMContentLoaded', function () {
    function editProfile(event) {
        event.preventDefault();
        document.getElementById('profile-buttons').classList.add('hidden');
        document.getElementById('edit-buttons').classList.remove('hidden');
        document.querySelectorAll('#address-display, #contact-display').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('#address-edit, #contact-edit').forEach(el => el.classList.remove('hidden'));
        document.getElementById('cameraIcon').classList.remove('hidden');

        let formAction = window.routes.profileUpdate;
        document.getElementById('profile-form').action = formAction;
    }

    function saveProfile(event) {
        event.preventDefault();
        document.getElementById('profile-form').submit();
    }

    function cancelEdit(event) {
        event.preventDefault();
        document.getElementById('profile-buttons').classList.remove('hidden');
        document.getElementById('edit-buttons').classList.add('hidden');
        document.querySelectorAll('#address-display, #contact-display').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('#address-edit, #contact-edit').forEach(el => el.classList.add('hidden'));
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
