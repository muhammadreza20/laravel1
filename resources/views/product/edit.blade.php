<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto">
                        <div class="w-full w-sm  bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">

                            <form action="{{route('updatedproduct',$db->id)}}" method="POST" class="max-w-sm mx-auto" enctype="multipart/form-data">
                                @csrf
                                <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Product</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M14 7h-4v3a1 1 0 0 1-2 0V7H6a1 1 0 0 0-.997.923l-.917 11.924A2 2 0 0 0 6.08 22h11.84a2 2 0 0 0 1.994-2.153l-.917-11.924A1 1 0 0 0 18 7h-2v3a1 1 0 1 1-2 0V7Zm-2-3a2 2 0 0 0-2 2v1H8V6a4 4 0 0 1 8 0v1h-2V6a2 2 0 0 0-2-2Z" clip-rule="evenodd" />
                                        </svg>

                                    </span>
                                    <input type="text" name="name" id="website-admin" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') is-invalid  border-color: red; @enderror" value="{{ $db->name }}">
                                </div>
                                @error('name')
                                <p class=" text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oops!</span> {{ $message }}</p>
                                @enderror

                                <label for="website-admin" class="block mb-2 mt-3 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <div class="flex">
                                    <textarea id="message" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('description', $db->description) }}</textarea>
                                </div>

                                <label for="harga" class="block mb-2 mt-3 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M8 17.345a4.76 4.76 0 0 0 2.558 1.618c2.274.589 4.512-.446 4.999-2.31.487-1.866-1.273-3.9-3.546-4.49-2.273-.59-4.034-2.623-3.547-4.488.486-1.865 2.724-2.899 4.998-2.31.982.236 1.87.793 2.538 1.592m-3.879 12.171V21m0-18v2.2" />
                                        </svg>
                                    </span>
                                    <input type="text" id="rupiahInput" oninput="updateFormattedValue(this)" placeholder="Rp." class=" rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('price') is-invalid @enderror" value="{{ old('price') }}">
                                    <input type="hidden" id="hiddenRupiahInput" name="price">
                                </div>
                                @error('price')
                                <p class="text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oops!</span> {{ $message }}</p>
                                @enderror

                                <input type="hidden" name="users_id" value="{{ auth()->user()->id }}">

                                <label for="website-admin" class="block mb-2 mt-3 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.045 3.007 12.31 3a1.965 1.965 0 0 0-1.4.585l-7.33 7.394a2 2 0 0 0 0 2.805l6.573 6.631a1.957 1.957 0 0 0 1.4.585 1.965 1.965 0 0 0 1.4-.585l7.409-7.477A2 2 0 0 0 21 11.479v-5.5a2.972 2.972 0 0 0-2.955-2.972Zm-2.452 6.438a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z" />
                                        </svg>
                                    </span>
                                    <select id="underline_select" name="category_id" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('category_id') is-invalid  border-color: red; @enderror">
                                        <option value="{{ $db->category->id }}" selected disabled>{{ $db->category->name }}</option>
                                        @foreach($category as $c)
                                        <option value=" {{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="website-admin" class="block mb-2 mt-5 text-sm font-medium text-gray-900 dark:text-white">Before Image</label>
                                    <div align="center">
                                        <figure class="relative max-w-xs transition-all duration-300 cursor-pointer filter grayscale hover:grayscale-0">
                                            <img class="rounded-lg" src="{{ asset('product/img/' . $db->image) }}" alt="image description">
                                            <figcaption class="absolute px-4 text-lg text-white bottom-6">
                                                <p>{{ $db->description }}</p>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <label for="website-admin" class="block mb-2 mt-6 text-sm font-medium text-gray-900 dark:text-white">After Image</label>
                                    <div class="flex custom-file-input mt-3">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            <svg class="w-4 h-10 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M16 18H8l2.5-6 2 4 1.5-2 2 4Zm-1-8.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z" />
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 18h8l-2-4-1.5 2-2-4L8 18Zm7-8.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z" />
                                            </svg>
                                        </span>
                                        <label class="flex-1 min-w-0 w-full">
                                            <input type="file" id="user_avatar" name="image" class="hidden" onchange="updateFileName(this)">
                                            <span class="block w-full text-sm text-gray-900 border border-gray-300 rounded-e-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2.5">Pilih File....</span>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 mt-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function updateFormattedValue(input) {
        // Mengambil nilai input
        let value = input.value;

        // Menghapus karakter selain angka
        value = value.replace(/\D/g, '');

        // Menetapkan nilai kembali ke input dengan format Rupiah
        if (value) {
            input.value = "Rp." + formatNumber(parseInt(value));
        } else {
            input.value = "";
        }

        // Menetapkan nilai integer ke input tersembunyi
        document.getElementById('hiddenRupiahInput').value = value ? parseInt(value) : '';
    }

    // Function untuk memformat angka dengan tanda titik setiap 3 digit
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Ketika formulir disubmit, tidak perlu mengubah nilai input harga lagi
    document.getElementById('myForm').addEventListener('submit', function(event) {
        // Nilai integer sudah disiapkan di input tersembunyi, jadi tidak perlu melakukan apapun di sini
    });
</script>