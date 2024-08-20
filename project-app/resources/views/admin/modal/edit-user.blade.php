<!-- Modal backdrop -->
<div id="editUserModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-9/12 maw-w-4x1">
        <h2 class="text-xl font-semibold mb-4">Edit User</h2>

        <form id="editUserForm" method="POST" action="{{ route('user.update') }}">
            @csrf
            @method('PUT')

            <div class="flex mb-4 space-x-4">
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

            <hr class="my-4 border-gray-300">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="id" class="block text-sm font-medium text-gray-700">User ID</label>
                    <input type="text" name="id" id="id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
            </div>

            <hr class="my-4 border-gray-300">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                    <input type="text" name="contact" id="contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <hr class="my-4 border-gray-300">

            <div class="grid grid-cols-2 gap-4 mb-4">
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

            <div class="grid grid-cols-2 gap-4 mb-4">
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
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700">Birth Date</label>
                    <input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="account_updated" class="block text-sm font-medium text-gray-700">Account Updated</label>
                    <input type="text" name="account_updated" id="account_updated" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
            </div>

            <div class="mb-4">
                <label for="usertype" class="block text-sm font-medium text-gray-700">User Type</label>
                <select name="usertype" id="usertype" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="user">User</option>
                    <option value="dept_head">Department Head</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="button" id="cancelEdit" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Save</button>
            </div>
        </form>
    </div>
</div>
