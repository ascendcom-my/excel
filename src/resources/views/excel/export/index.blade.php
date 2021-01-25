<x-bigmom-auth::layout.main>
  <x-slot name="header">Excel Export</x-slot>
  <x-slot name="headerRightSide">
    <x-bigmom-auth::button.link.blue href="{{ route('bigmom-auth.getHome') }}">Home</x-bigmom-auth::button.link.blue>
  </x-slot>

  <x-bigmom-auth::card class="pt-8">
    <div class="w-full">
      <div class="w-full flex justify-between pl-2 items-center">
        <h4 class="text-xl">Select tables to download</h4>
        <div>
          <button onclick="setAllCheckBoxes(true)" class="cursor-pointer mx-2 my-2 w-auto bg-gray-300 hover:bg-gray-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Select all</button>
          <button onclick="setAllCheckBoxes(false)" class="cursor-pointer mx-2 my-2 w-auto bg-gray-300 hover:bg-gray-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Deselect all</button>
        </div>
      </div>
      <form action="{{ route('bigmom-excel.export.download') }}" method="GET" class="w-full px-2 py-2">
        <div class="w-full flex flex-wrap">
          @forelse($tables as $table)
          <div class="w-1/4 px-2 py-2 border-solid border-2 border-gray-400">
            <label for="checkbox-{{ $table->{'Tables_in_'.config('excel.database')} }}" class="text-black font-bold flex justify-between ">
              <span>{{ $table->{'Tables_in_'.config('excel.database')} }}</span>
              <input id="checkbox-{{ $table->{'Tables_in_'.config('excel.database')} }}" type="checkbox" name="table[]" value="{{ $table->{'Tables_in_'.config('excel.database')} }}" class="mr-2 leading-tight">
            </label>
          </div>
          @empty
          No tables yet.
          @endforelse
        </div>
        <div class="w-full">
          @if(count($tables))
            <input type="submit" value="Download" class="cursor-pointer mt-5 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          @endunless
          @if(session('message'))
          <span class="px-5 @if(session('error')) text-red @endif">{{ session('message') }}</span>
          @endif
          @if (isset($errors) && $errors->any())
            @foreach ($errors->all() as $error)
            <span class="px-5 text-red">{{ $error }}</span>
            @endforeach
          @endif
        </div>
      </form>
    </div>
  </x-bigmom-auth::card>

  @push('script')
  <script defer>
    function setAllCheckBoxes(truth) {
      let checkBoxes = document.querySelectorAll('input[type="checkbox"]');
      checkBoxes.forEach(element => {
        element.checked = truth;
      });
    }
  </script>
  @endpush
</x-bigmom-auth::layout.main>
