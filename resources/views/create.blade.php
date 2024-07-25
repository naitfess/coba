<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <title>Document</title>
    <style>
        .form-container {
            display: none;
        }
    </style>
</head>
<body class="h-full">
<div class="min-h-full">
    <nav class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="/dashboard" class="rounded-md  px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white" aria-current="page">Dashboard</a>
                <a href="/dashboard/create" class="rounded-md px-3 py-2 text-sm font-medium bg-gray-900 text-white">Create</a>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Mobile menu, show/hide based on menu state. -->
      <div class="md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
          <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
          <a href="#" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white" aria-current="page">Dashboard</a>
          <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Team</a>
        </div>
      </div>
    </nav>
  
    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
      </div>
    </header>
    <main>
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <form action="/dashboard" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-12">
                <div class="pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Event Information</h2>            
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="select-event" class="block text-sm font-medium leading-6 text-gray-900">Select Event</label>
                            <div class="mt-2">
                            <select id="select-event" name="select-event" onchange="showEvent()" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option value="new">New Event</option>
                                @foreach ($masters as $master)
                                <option value="{{ $master->id}}">{{ $master->name }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        {{-- if event new --}}
                        <div class="sm:col-span-4 toggle-active">
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Event Name</label>
                            <div class="mt-2">
                            <input type="text" value="{{ old('name') }}" name="name" id="name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
            
                        <div class="sm:col-span-4 toggle-active">
                            <label for="year" class="block text-sm font-medium leading-6 text-gray-900">Year</label>
                            <div class="mt-2">
                            <input id="year" value="{{ old('year') }}" name="year" type="date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        @error('year')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror

                        <div class="sm:col-span-4 toggle-active">
                            <label for="location" class="block text-sm font-medium leading-6 text-gray-900">Location</label>
                            <div class="mt-2">
                            <input id="location" value="{{ old('location') }}" name="location" type="text" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        @error('location')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror

                        <div class="sm:col-span-4 toggle-active">
                            <div class="sm:col-span-4 toggle-active">                                
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                                <input name="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF (MAX. 800x400px).</p>
                            </div>
                        </div>
                        @error('file')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                        
                        <div class="sm:col-span-4 toggle-active mb-10">
                            <label for="description" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Description</label>
                            <div id="editor"></div>
                            <input type="hidden" name="description" id="hiddenInput">       
                        </div>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
            
                        <div class="sm:col-span-3">
                            <label for="category_id" class="block text-sm font-medium leading-6 text-gray-900">Competition Category</label>
                            <div class="mt-2">
                            <select id="category_id" name="category_id" onchange="showForm()" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option value="">Select</option>
                                <option value="1">Swimming</option>
                                <option value="2">Football</option>
                                <option value="3">Badminton</option>
                            </select>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="sm:col-span-4 mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            {{-- form 1 --}}
            <div id="1" class="space-y-12 form-container">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Swimming Competition Details</h2>            
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="swimming-name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                        <div class="mt-2">
                        <input type="text" value="{{ old('swimming-name') }}" name="swimming-name" id="swimming-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    @error('swimming-name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
            
                    <div class="sm:col-span-4">
                        <label for="swimming-distance" class="block text-sm font-medium leading-6 text-gray-900">Distance</label>
                        <div class="mt-2">
                        <input id="swimming-distance" value="{{ old('swimming-distance') }}" name="swimming-distance" type="text" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    @error('swimming-distance')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror

                    <div class="sm:col-span-4">
                        <label for="swimming-stroke" class="block text-sm font-medium leading-6 text-gray-900">Stroke</label>
                        <div class="mt-2">
                        <input id="swimming-stroke" value="{{ old('swimming-distance') }}" name="swimming-stroke" type="text" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    @error('swimming-distance')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                    </div>
                </div>
            </div>

            {{-- form 2 --}}
            <div id="2" class="space-y-12 form-container">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Football Competition Details</h2>            
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="football-name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                        <div class="mt-2">
                        <input type="text" value="{{ old('football-name') }}" name="football-name" id="football-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    @error('football-name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
            
                    <div class="sm:col-span-4">
                        <label for="football-category_umur" class="block text-sm font-medium leading-6 text-gray-900">Category</label>
                        <div class="mt-2">
                        <input id="football-category_umur" value="{{ old('football-category_umur') }}" name="football-category_umur" type="text" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                        @error('football-category_umur')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    </div>
                </div>
            </div>

            {{-- form 3 --}}
            <div id="3" class="space-y-12 form-container">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Badminton Competition Details</h2>            
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="badminton-name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                        <div class="mt-2">
                        <input type="text" value="{{ old('badminton-name') }}" name="badminton-name" id="badminton-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    @error('badminton-name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
            
                    <div class="sm:col-span-4">
                        <label for="badminton-category_kelas" class="block text-sm font-medium leading-6 text-gray-900">Category</label>
                        <div class="mt-2">
                        <input id="badminton-category_kelas" value="{{ old('badminton-category_kelas') }}" name="badminton-category_kelas" type="text" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    @error('badminton-category_kelas')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror

                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-x-6">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>
      </div>
    </main>
  </div>
<script>
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
        toolbar: [
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ font: [] }],
            ["bold", "italic"],
            ["link", "blockquote", "code-block", "image"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ script: "sub" }, { script: "super" }],
            [{ color: [] }, { background: [] }],
        ]
        },
    });

    var form = document.querySelector("form");
    var hiddenInput = document.querySelector('#hiddenInput');

    form.addEventListener('submit', function(e){
        hiddenInput.value = quill.root.innerHTML;
    });
</script>
<script>
    function showEvent() {
        var selectedEvent = document.getElementById('select-event').value;
        if (selectedEvent === 'new') {
            document.querySelectorAll('.toggle-active').forEach(function (element) {
                element.style.display = 'block';
            });
        } else {
            document.querySelectorAll('.toggle-active').forEach(function (element) {
                element.style.display = 'none';
            });
        }
    }
    function showForm() {
        // Hide all forms
        var forms = document.getElementsByClassName('form-container');
        for (var i = 0; i < forms.length; i++) {
            forms[i].style.display = 'none';
        }

        // Show the selected form
        var selectedForm = document.getElementById('category_id').value;
        if (selectedForm) {
            document.getElementById(selectedForm).style.display = 'block';
        }
    }
</script>   
</body>
</html>