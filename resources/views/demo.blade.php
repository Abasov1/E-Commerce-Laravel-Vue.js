<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    </head>
    <body class="antialiased">
        <form action="/createbr" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" id=""> <br>
            <input type="text" name="name" id=""> <br>
            <button type="submit">Brand yarat</button>
        </form> <br> <br>
        <form action="/createmer" method="post">
            @csrf
            <input type="text" name="name" id=""><br>
            <button type="submit">Merchant yarat</button>
        </form><br><br>
        <form action="/createcat" method="post">
            @csrf
            <select name="category" id="">
                <option value=""></option>
                @foreach($allcategories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select><br>
            <input type="text" name="name" id=""><br>
            <button type="submit">Category yarat</button>
        </form><br><br>
        <form action="/createinf" method="post">
            @csrf
            <select name="product" id="">
                <option value=""></option>
                @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->name}}</option>
                @endforeach
            </select><br>
            <input type="text" name="title" id=""><br>
            <input type="text" name="body" id=""><br>
            <button type="submit">Info yarat</button>
        </form><br><br>
        <form action="/createpr" method="post">
            @csrf
            Merchant:
            <select name="merchant" id="">
                @foreach($merchants as $merchant)
                    <option value="{{$merchant->id}}">{{$merchant->name}}</option>
                @endforeach
            </select><br>
            Brand:
            <select name="brand" id="">
                @foreach($brands as $brand)
                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                @endforeach
            </select><br>
            Category:
            <select name="category" id="">
                @foreach($allcategories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select><br>
            <input type="text" name="name" id="">
            <button type="submit">Product yarat</button>
        </form><br><br>
        <h3>Categories:</h3>
        @foreach ($categories as $category)
            <div>
                {{$category->name}} :
                @foreach ($category->subcategories as $subcat)
                    <div style="margin-left:20px;">
                        {{$subcat->name}}
                        @include('demosub',['subcats'=>$subcat->subcategories])
                    </div>

                @endforeach
            </div>
        @endforeach
        <h3>merchants:</h3>
        @foreach ($merchants as $merchant)
            {{$merchant->name}}
        @endforeach
        <h3>brands:</h3>
        @foreach ($brands as $brand)
            {{$brand->name}}
            <img src="{{asset('storage/'.$brand->image)}}" alt="">
        @endforeach
        <h3>products:</h3>
        @foreach ($products as $product)
            <h1>{{$product->name}} </h1>
            Brend: {{$product->brand->name}}<br>
            Category: {{$product->category->name}}<br>
            Merchant: {{$product->merchant->name}}<br>
            Info: <br>
            @foreach ($product->informations as $info)
            <b>{{$info->title}}</b>:{{$info->body}} <br>
            @endforeach
            <br> <br>
        @endforeach
    </body>
</html>
