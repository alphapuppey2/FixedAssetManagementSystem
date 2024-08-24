<aside class="h-screen w-auto p-3 fixed bg-blue-200 font-semibold">
    <div class="profileAccount flex mt-3 items-center p-2 rounded-lg hover:bg-gray-300/50 transition ease-in ">
        <div class="imagepart overflow-hidden rounded-full relative p-4 border-3 border-slate-500">
            <img src="{{ asset('storage/images/defaultICON.png') }}" class="absolute  bg-white top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover" alt="assetImage">
        </div>
        <div class="profileUser flex flex-col ml-2 text-[12px]">
            <span class="font-normal">
                Soqueno , Joshua Loui
            </span>
            <span>
                IT head
            </span>
        </div>
    </div>
    <div class="divder w-full h-[2px] bg-black mt-2 mb-2"></div>
    <nav class="flex flex-col font-semibold">
        <ul class="sb h-[100%]">
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-dashIcon/>
                    <span class="ml-2">Dashboard</span>
                </a>
            </li>
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-receipticon />
                    <span class="ml-2">Asset</span>
                </a>
            </li>
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-paperplane />
                    <span class="ml-2">Request</span>
                </a>
            </li>
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-wrenchIcon />
                    <span class="ml-2">Maintenance</span>
                </a>
            </li>
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-chartIcon></x-chartIcon>
                    <span class="ml-2">Report</span>
                </a>
            </li>
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-gearIcon />
                    <span class="ml-2">Setting</span>
                </a>
            </li>
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-bellIcon/>
                    <span class="ml-2">Nofitifcation</span>
                </a>
            </li>
            <li class="hover:bg-slate-400/50 transition ease-in mb-1 p-1 rounded-md">
                <a href="# " target="_blank" class="flex" rel="noopener noreferrer">
                    <x-icons.logoutIcon />
                    <span class="ml-2">logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
