@if($subcats->count())
    @foreach ($subcats as $subcat)
        <div style="margin-left:20px;">
            {{$subcat->name}}
            @include('demosub',['subcats'=>$subcat->subcategories])
        </div>
    @endforeach
@endif