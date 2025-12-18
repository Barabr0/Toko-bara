<h1>{{ $title }}</h1>
@if ($user->isAdmin())
 <p>Selamat datang, Admin</p>
 @elseif ($user->isCustomer())
 <p>Selamat datang, {{ $user->name }}</p>
 @else
 <p>Silahkan Login terlebih dahulu</p>
@endif
@auth
    <p>Halo, {{ auth()->user()->name }}</p>
@endauth

@guest
    <a href="{{ route('login') }}">Login</a>
@endguest


@foreach($products as $product)
    <div class="product-card">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->formatted_price }}</p>
    </div>
@endforeach

@forelse($products as $product)
    <div>{{ $product->name }}</div>
@empty
    <p>Tidak ada produk.</p>
@endforelse


@include('partials.header')
@include('partials.product-card', ['product' => $product])


<form method="POST" action="{{ route('products.store') }}">
    @csrf
</form>


<form method="POST" action="{{ route('products.update', $product) }}">
    @csrf
    @method('PUT')
</form>
