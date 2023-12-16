<x-filament-panels::page>
    <div >
        @foreach ($values as $value)
        <div class="flex flex-row justify-center">
            <div class="flex flex-col ">
                <label for="">Attribute</label>
                <input type="text" value="{{$value->name['en']}}" class="mb-5" disabled>
            </div>
            <div class="flex flex-col ">
                <label for="">Price</label>
                <input type="text" value="0" class="mb-5" disabled>
            </div>
        </div>
            

        @endforeach

    </div>
</x-filament-panels::page>
