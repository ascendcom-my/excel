<x-bigmom-auth::layout.main>
  <x-slot name="header">Excel Import</x-slot>
  <x-slot name="headerRightSide">
    <x-bigmom-auth::button.link.blue href="{{ route('bigmom-auth.getHome') }}">Home</x-bigmom-auth::button.link.blue>
  </x-slot>

  <x-bigmom-auth::card class="pt-8">
    <div class="w-full">
      <div class="w-full flex justify-between pl-2 items-center">
        <h4 class="text-xl">Submit a file to import</h4>
      </div>
      <div class="w-full flex justify-between pl-2 items-center">
        <h1 class="text-3xl">BACKUP BEFORE IMPORTING</h1>
      </div>
      <form action="{{ route('bigmom-excel.import.postImport') }}" method="POST" class="w-full px-2 py-2" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <input type="submit" value="Import" class="cursor-pointer mt-5 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
      </form>
    </div>
    @if(session('message'))
    <span class="px-5 @if(session('error')) text-red @endif">{{ session('message') }}</span>
    @endif
    @if (isset($errors) && $errors->any())
      @foreach ($errors->all() as $error)
      <span class="px-5 text-red">{{ $error }}</span>
      @endforeach
    @endif
  </x-bigmom-auth::card>
</x-bigmom-auth::layout.main>