@extends('layout.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-4 min-h-screen pb-28 px-2">
    <div class="w-full lg:w-2/3 bg-white rounded-lg shadow-lg border-2 border-gray-300 p-4 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-1 py-2 rounded">Kembali</a>
        </div>
        
        <div class="mb-4 flex flex-col sm:flex-row gap-2">
            <input type="text" id="searchItem" placeholder="Cari Sparepart..." class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none">
            <select id="filterCategory" class="border-2 border-gray-300 p-2 rounded w-full sm:w-auto">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="productGrid">
            @foreach($items as $item)
            <div class="product-card cursor-pointer bg-gray-50 border hover:border-racing-orange rounded-lg p-3 shadow-sm relative"
                onclick='addToCart({{ $item->item_id}}, @json($item->nama_item), {{ $item->harga_jual }}, {{ $item->stok}})'
                data-name="{{ strtolower($item->nama_item) }}"
                data-category="{{ $item->kategori }}">
                
                <div class="h-24 mb-2 rounded overflow-hidden relative">
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_item }}" class="w-full h-full object-cover">
                </div>

                @if($item->stok <= 5)
                    <span class="absolute top-0 right-0 bg-red-600 text-white text-xs px-2 py-1 rounded-bl-lg">
                        Stok Menipis: {{ $item->stok }}
                    </span>
                @else
                    <span class="absolute top-0 right-0 bg-green-600 text-white text-xs px-2 py-1 rounded-bl-lg">
                        Stok: {{ $item->stok }}
                    </span>
                @endif

                <h3 class="font-bold text-sm text-gray-800 line-clamp-2 min-h-40px">
                    {{ $item->nama_item }}
                </h3>

                <p class="text-racing-orange font-bold mt-1 text-sm">
                    Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- CART AREA --}}
    <div class="w-full lg:w-1/3 flex flex-col gap-2">
        <div class="bg-white rounded-lg shadow-lg border-t-4 border-racing-orange p-4 flex-1 flex flex-col">
            <h2 class="font-bold text-xl mb-4 border-b pb-2">
                <i class="fa-solid fa-cart-shopping"></i> Keranjang
            </h2>
            
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
                        <input type="number" name="pembayaran" id="inputBayar"
                            class="w-full border-2 border-gray-300 p-2 rounded text-right font-bold text-lg"
                            oninput="calculateChange()">
                    </div>

                    <div class="grid grid-cols-4 gap-1 mb-4 text-xs">
                        <button type="button" onclick="setAmount(100000)" class="bg-gray-200 py-1 rounded hover:bg-gray-300">100k</button>
                        <button type="button" onclick="setAmount(200000)" class="bg-gray-200 py-1 rounded hover:bg-gray-300">200k</button>
                        <button type="button" onclick="setAmount(500000)" class="bg-gray-200 py-1 rounded hover:bg-gray-300">500k</button>
                        <button type="button" onclick="setAmount(1000000)" class="bg-gray-200 py-1 rounded hover:bg-gray-300">1jt</button>
                    </div>

                    <div class="flex justify-between text-lg font-bold mb-4 text-green-700">
                        <span>Kembali:</span>
                        <span id="displayChange">Rp 0</span>
                    </div>

                    <button type="submit"
                        class="w-full bg-racing-orange hover:bg-orange-700 text-white font-bold py-3 rounded shadow-lg"
                        id="btnBayar" disabled>
                        PROSES TRANSAKSI
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    //Ambil elemen input dan dropdown
    const searchInput = document.getElementById('searchItem');
    const categorySelect = document.getElementById('filterCategory');
    const cards = document.querySelectorAll('.product-card');

    function filterProducts() {
        const searchText = searchInput.value.toLowerCase();
        const selectedCategory = categorySelect.value; //kategori yang dipilih

        //Loop semua kartu produk satu per satu
        cards.forEach(card => {
            const productName = card.getAttribute('data-name');     // Ambil nama dari data-name
            const productCategory = card.getAttribute('data-category'); // Ambil kategori dari data-category

            const isNameMatch = productName.includes(searchText);

            // mengecek apakah kategori cocok? (Fitur Filter)
            // Jika pilih 'all', anggap cocok semua. Jika tidak, harus sama persis.
            const isCategoryMatch = selectedCategory === 'all' || productCategory === selectedCategory;

            if (isNameMatch && isCategoryMatch) {
                card.style.display = ""; // Tampilkan (reset display)
            } else {
                card.style.display = "none"; // Sembunyikan
            }
        });
    }

    // Setiap kali mengetik di search bar, jalankan fungsi filter
    searchInput.addEventListener('keyup', filterProducts);
    searchInput.addEventListener('input', filterProducts);

    // Setiap kali ganti kategori, jalankan fungsi filter
    categorySelect.addEventListener('change', filterProducts);
</script>

<!-- //script pembayaran -->
<script>
let cart = []
let total = 0

function addToCart(id, name, price, stock) {
    if (stock <= 0) {
        alert("Stok habis!")
        return
    }

    const existing = cart.find(item => item.id === id)

    if (existing) {
        if (existing.qty >= stock) {
            alert("Stok tidak mencukupi")
            return
        }
        existing.qty++
    } else {
        cart.push({ id, name, price, qty: 1 })
    }

    renderCart()
}

function renderCart() {
    const cartContainer = document.getElementById('cartItems')
    const inputCart = document.getElementById('inputCart') 
    const displayTotal = document.getElementById('displayTotal')

    cartContainer.innerHTML = ''
    total = 0

    if (cart.length === 0) {
        cartContainer.innerHTML = `<p class="text-center text-gray-400 mt-10">Keranjang Kosong</p>`
        displayTotal.innerHTML = 'Rp 0'
        document.getElementById('btnBayar').disabled = true
        return
    }

    // Loop melalui setiap item di keranjang
    cart.forEach((item, index) => {
        let subtotal = item.qty * item.price
        total += subtotal

        cartContainer.innerHTML += `
            <div class="flex justify-between items-center border-b pb-1 text-sm">
                <div>
                    <p class="font-bold">${item.name}</p>
                    <p>${item.qty} x Rp ${formatRupiah(item.price)}</p>
                </div>
                <div class="flex items-center gap-1">
                    <button onclick="changeQty(${index},-1)" class="bg-gray-300 px-2 rounded">-</button>
                    <button onclick="changeQty(${index},1)" class="bg-gray-300 px-2 rounded">+</button>
                    <button onclick="removeItem(${index})" class="bg-red-500 text-white px-2 rounded">x</button>
                </div>
            </div>
        `
    })

    displayTotal.innerHTML = "Rp " + formatRupiah(total)
    inputCart.value = JSON.stringify(cart)

    document.getElementById('inputTotal').value = total
    calculateChange()
}

// Fungsi untuk mengubah jumlah barang
function changeQty(index, value) {
    cart[index].qty += value
    if (cart[index].qty <= 0) cart.splice(index, 1)
    renderCart()
}

// Fungsi untuk menghapus barang
function removeItem(index) {
    cart.splice(index, 1)
    renderCart()
}

// Fungsi untuk mengatur jumlah pembayaran
function setAmount(amount){
    document.getElementById('inputBayar').value = amount
    calculateChange()
}

// Fungsi untuk menghitung kembalian
function calculateChange(){
    const bayar = parseInt(document.getElementById('inputBayar').value) || 0
    const kembali = bayar - total

    // Menampilkan kembalian
    if(bayar > 0) {
        document.getElementById('displayChange').innerHTML = "Rp " + formatRupiah(kembali)
    } else {
        document.getElementById('displayChange').innerHTML = "Rp 0"
    }

    if (total > 0 && bayar >= total){
        document.getElementById('btnBayar').disabled = false
        document.getElementById('btnBayar').classList.remove('opacity-50', 'cursor-not-allowed')
    } else {
        document.getElementById('btnBayar').disabled = true
        document.getElementById('btnBayar').classList.add('opacity-50', 'cursor-not-allowed')
    }
    document.getElementById('inputKembalian').value = kembali
}

// Fungsi untuk format rupiah
function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}
</script>
@endsection