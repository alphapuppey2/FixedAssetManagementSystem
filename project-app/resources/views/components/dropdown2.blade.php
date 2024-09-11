
<style>
    .dropdown-toggle::after {
      display: none !important;
    }
  </style>

<div class="dropdown">
    <button class="ease-in duration-100 border-solid text-blue-900 border-1 border-blue-900 flex items-center p-1 rounded-lg dropdown-toggle hover:text-blue-100 hover:bg-blue-900" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $trigger }}
        <div class="icon">
            <x-icons.plus-icon />
        </div>
    </button>
    <ul class="dropdown-menu w-full">
      {{$content}}
    </ul>
  </div>
