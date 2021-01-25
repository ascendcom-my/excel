<x-bigmom-auth::layout.main>
  <x-slot name="header">Excel Import</x-slot>
  <x-slot name="headerRightSide">
    <x-bigmom-auth::button.link.blue href="{{ route('bigmom-excel.import.getIndex') }}">Back</x-bigmom-auth::button.link.blue>2
  </x-slot>

  <x-bigmom-auth::card class="pt-8">
    <div class="w-full px-2 py-2">
      <div class="w-full flex justify-between items-center">
        <h1 class="text-3xl">BACKUP BEFORE IMPORTING</h1>
      </div>
      <div class="w-full flex justify-between items-center">
        <h4 class="text-xl">Are you sure you want to import the following tables?</h4>
      </div>
      <ol class="list-decimal pl-8 pt-3">
        @foreach($data->get('sheetNames') as $sheetName)
        <li>{{ $sheetName }}</li>
        @endforeach
      </ol>
      <form action="{{ route('bigmom-excel.import.postConfirmImport') }}" method="POST" class="w-full py-2">
        @csrf
        <input type="hidden" name="file" value="{{ $data->get('filePath') }}">
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