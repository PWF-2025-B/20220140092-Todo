<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <div class="flex justify-end items-center px-6 py-4">
                        <form method="GET" action="{{ route('user.index') }}" id="searchForm">
                            <input type="text" name="search" id="searchInput" placeholder="Search..."
                                value="{{ request('search') }}" autofocus
                                class="w-[200px] px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring focus:border-blue-500">
                        </form>
                    </div>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Id</th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="hidden px-6 py-3 md:block">Email</th> {{-- ✅ Ganti md:block → md:table-cell --}}
                                <th scope="col" class="px-6 py-3">Todo</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @foreach ($users as $data)
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td scope="row"
                                        class="px-6 py-4 font-medium whitespace-nowrap dark:text-white text-center">
                                        {{ $data->id }}
                                    </td>
                                    <td class="px-6 py-4 text-center">{{ $data->name }}</td>
                                    <td class="px-6 py-4 hidden md:table-cell text-center">{{ $data->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <p>{{ $data->todos->count() }}
                                            <span>
                                                <span class="text-green-600 dark:text-green-400">
                                                    ({{ $data->todos->where('is_done', true)->count() }}
                                                </span>/
                                                <span class="text-blue-600 dark:text-blue-400">
                                                    {{ $data->todos->where('is_done', false)->count() }})
                                                </span>
                                            </span>
                                        </p>
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-5">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Menangani event input untuk form pencarian tanpa reload halaman
        document.getElementById('searchInput').addEventListener('input', function() {
            let searchValue = this.value;

            fetch("{{ route('user.index') }}?search=" + searchValue)
                .then(response => response.text())
                .then(data => {
                    let tableBody = document.getElementById('userTableBody');
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, 'text/html');
                    tableBody.innerHTML = doc.querySelector('tbody').innerHTML;
                });
        });
    </script>
</x-app-layout>
