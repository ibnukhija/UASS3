@extends('layout.app')

@section('content')
<div class="flex gap-4 h-screen pb-20">
    
    <div class="w-2/3 bg-white rounded-lg shadow-lg border-2 border-gray-300 p-4 overflow-y-auto">
        
        <div class="mb-4 flex gap-2">
            <input type="text" id="searchItem" placeholder="Cari Sparepart..." class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none">
            <select id="filterCategory" class="border-2 border-gray-300 p-2 rounded">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-3 gap-4" id="productGrid">
            @foreach($items as $item)
            <div class="product-card cursor-pointer bg-gray-50 border hover:border-racing-orange rounded-lg p-3 shadow-sm relative"
                onclick="addToCart({{ $item->item_id }}, '{{ $item->nama_item }}', {{ $item->harga_jual }}, {{ $item->stok }})"
                data-name="{{ strtolower($item->nama_item) }}"
                data-category="{{ $item->kategori }}">
                
                @if($item->stok <= 5)
                    <span class="absolute top-0 right-0 bg-red-600 text-white text-xs px-2 py-1 rounded-bl-lg">Stok Kritis: {{ $item->stok }}</span>
                @else
                    <span class="absolute top-0 right-0 bg-green-600 text-white text-xs px-2 py-1 rounded-bl-lg">Stok: {{ $item->stok }}</span>
                @endif

                <div class="h-24 bg-gray-200 mb-2 flex items-center justify-center rounded">
                    <i class="fa-solid fa-motorcycle text-3xl text-gray-400"></i>
                </div>
                <h3 class="font-bold text-sm text-gray-800 line-clamp-2 h-10">{{ $item->nama_item }}</h3>
                <p class="text-racing-orange font-bold mt-1">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="w-1/3 flex flex-col gap-2">
        <div class="bg-white rounded-lg shadow-lg border-t-4 border-racing-orange p-4 flex-1 flex flex-col">
            <h2 class="font-bold text-xl mb-4 border-b pb-2"><i class="fa-solid fa-cart-shopping"></i> Keranjang</h2>
            
            <div id="cartItems" class="flex-1 overflow-y-auto space-y-2 mb-4">
                <p class="text-center text-gray-400 mt-10">Keranjang Kosong</p>
            </div>

            <div class="border-t pt-2">
                <div class="flex justify-between text-xl font-bold mb-4">
                    <span>Total:</span>
                    <span class="text-racing-orange" id="displayTotal">Rp 0</span>
                </div>
                
                <form action="{{ route('kasir.bayar') }}" method="POST" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="cart" id="inputCart">
                    <input type="hidden" name="total_harga" id="inputTotal">
                    <input type="hidden" name="kembalian" id="inputKembalian">

                    <div class="mb-2">
                        <label class="block text-sm font-bold mb-1">Bayar (Rp)</label>
                        <input type="number" name="pembayaran" id="inputBayar" class="w-full border-2 border-gray-300 p-2 rounded text-right font-bold text-lg" oninput="calculateChange()">
                    </div>

                    <div class="grid grid-cols-4 gap-1 mb-4">
                        <button type="button" onclick="setAmount(100000)" class="bg-gray-200 text-xs py-1 rounded hover:bg-gray-300">100k</button>
                        <button type="button" onclick="setAmount(200000)" class="bg-gray-200 text-xs py-1 rounded hover:bg-gray-300">200k</button>
                        <button type="button" onclick="setAmount(500000)" class="bg-gray-200 text-xs py-1 rounded hover:bg-gray-300">500k</button>
                        <button type="button" onclick="setAmount(1000000)" class="bg-gray-200 text-xs py-1 rounded hover:bg-gray-300">1jt</button>
                    </div>

                    <div class="flex justify-between text-lg font-bold mb-4 text-green-700">
                        <span>Kembali:</span>
                        <span id="displayChange">Rp 0</span>
                    </div>

                    <button type="submit" class="w-full bg-racing-orange hover:bg-orange-700 text-white font-bold py-3 rounded shadow-lg uppercase tracking-wider" id="btnBayar" disabled>
                        PROSES TRANSAKSI
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];
    
    // Add to Cart
    function addToCart(id, name, price, stock) {
        if(stock <= 0) return alert('Stok Habis!');
        
        let existing = cart.find(item => item.id === id);
        if(existing) {
            if(existing.qty < stock) {
                existing.qty++;
            } else {
                alert('Maksimal stok tercapai');
            }
        } else {
            cart.push({ id, name, price, qty: 1, stock });
        }
        renderCart();
    }

    // Render Cart Logic
    function renderCart() {
        const container = document.getElementById('cartItems');
        container.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            total += item.price * item.qty;
            container.innerHTML += `
                <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-200">
                    <div class="flex-1">
                        <div class="font-bold text-sm">${item.name}</div>
                        <div class="text-xs text-gray-500">Rp ${item.price.toLocaleString()} x ${item.qty}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="font-bold text-racing-orange text-sm">Rp ${(item.price * item.qty).toLocaleString()}</div>
                        <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `;
        });

        document.getElementById('displayTotal').innerText = 'Rp ' + total.toLocaleString();
        document.getElementById('inputTotal').value = total;
        document.getElementById('inputCart').value = JSON.stringify(cart);
        calculateChange();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function setAmount(amount) {
        document.getElementById('inputBayar').value = amount;
        calculateChange();
    }

    function calculateChange() {
        const total = parseInt(document.getElementById('inputTotal').value) || 0;
        const bayar = parseInt(document.getElementById('inputBayar').value) || 0;
        const kembali = bayar - total;
        
        document.getElementById('displayChange').innerText = 'Rp ' + (kembali < 0 ? 0 : kembali).toLocaleString();
        document.getElementById('inputKembalian').value = kembali;

        // Enable button only if payment sufficient
        const btn = document.getElementById('btnBayar');
        if(bayar >= total && total > 0) {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Filter Logic
    document.getElementById('searchItem').addEventListener('keyup', filterProducts);
    document.getElementById('filterCategory').addEventListener('change', filterProducts);

    function filterProducts() {
        const search = document.getElementById('searchItem').value.toLowerCase();
        const cat = document.getElementById('filterCategory').value;
        const cards = document.querySelectorAll('.product-card');

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const category = card.getAttribute('data-category');
            
            const matchSearch = name.includes(search);
            const matchCat = cat === 'all' || category === cat;

            if(matchSearch && matchCat) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection