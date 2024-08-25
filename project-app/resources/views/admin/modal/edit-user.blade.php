<!-- Modal backdrop -->
<div id="editUserModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mx-4 sm:mx-auto relative">
        <!-- Close button -->
        <button type="button" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-3xl font-bold" onclick="closeModal()">
            &times;
        </button>
        
        <h2 class="text-xl font-semibold mb-4">Edit User</h2>

        <hr class="my-4 border-gray-700">

        <form id="editUserForm" method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex mb-4 space-x-4">
                <div class="flex-none">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 border">
                        <img id="currentProfilePhoto" src="/images/default_profile.jpg" alt="Current Profile Photo" class="w-full h-full object-cover">
                    </div>
                    <input type="file" name="profile_photo" id="profile_photo" class="mt-2 block w-full text-sm text-gray-700" accept="image/*">
                </div>

                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row mb-4 space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="flex-1">
                            <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="firstname" id="firstname" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex-1">
                            <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" name="middlename" id="middlename" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex-1">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 border-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="id" class="block text-sm font-medium text-gray-700">User ID</label>
                    <input type="text" name="id" id="id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
                <div class="mb-4">
                    <label for="usertype" class="block text-sm font-medium text-gray-700">User Type</label>
                    <select name="usertype" id="usertype" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="user">User</option>
                        <option value="dept_head">Department Head</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <hr class="my-4 border-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                    <input type="text" name="contact" id="contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700">Birth Date</label>
                    <input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <hr class="my-4 border-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="dept_id" class="block text-sm font-medium text-gray-700">Department</label>
                    <select name="dept_id" id="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1">IT</option>
                        <option value="2">Sales</option>
                        <option value="3">Fleet</option>
                        <option value="4">Production</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label for="account_created" class="block text-sm font-medium text-gray-700">Account Created</label>
                    <input type="text" id="account_created" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
                <div>
                    <label for="account_updated" class="block text-sm font-medium text-gray-700">Account Updated</label>
                    <input type="text" name="account_updated" id="account_updated" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" id="cancelEdit" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>
