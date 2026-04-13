@extends('layouts.app')
@section('title', 'Loja — Aruanda Digital')

@push('styles')
<style>
    /* ── Categorias ── */
    .cat-scroll {
        display: flex;
        gap: 8px;
        padding: 10px 16px;
        overflow-x: auto;
        scrollbar-width: none;
        background: var(--surface);
        border-bottom: 1px solid var(--border-lt);
    }
    .cat-scroll::-webkit-scrollbar { display: none; }
    .cat-chip {
        flex-shrink: 0;
        padding: 6px 14px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--txt-2);
        background: var(--surface);
        cursor: pointer;
        white-space: nowrap;
        transition: all .15s;
        border: none;
    }
    .cat-chip:hover,
    .cat-chip.active {
        border: 1.5px solid var(--p);
        color: var(--p);
        background: var(--p-xl);
    }
    .cat-chip:not(.active) { border: 1.5px solid var(--border); }

    /* ── Grid ── */
    .products-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 12px;
    }

    /* ── Card ── */
    .product-card {
        background: var(--surface);
        border-radius: var(--r);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .18s, box-shadow .18s;
    }
    .product-card:active { transform: scale(.97); box-shadow: var(--shadow); }

    .product-img-wrap {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
        background: var(--p-lt);
        display: block;
    }
    .product-img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .3s;
        display: block;
    }
    .product-card:hover .product-img { transform: scale(1.05); }

    .product-badge {
        position: absolute;
        top: 7px; left: 7px;
        font-size: 9px; font-weight: 800;
        padding: 3px 7px;
        border-radius: 6px;
        letter-spacing: .4px;
        text-transform: uppercase;
        pointer-events: none;
    }

    .product-info {
        padding: 10px 10px 10px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .product-cat {
        font-size: 9px; font-weight: 700;
        color: var(--p); text-transform: uppercase;
        letter-spacing: .5px;
    }
    .product-name {
        font-size: 12px; font-weight: 700;
        color: var(--txt); line-height: 1.3;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
        gap: 4px;
    }
    .product-price {
        font-size: 15px; font-weight: 800;
        color: var(--p-dk); line-height: 1;
    }
    .product-price small {
        font-size: 9px; font-weight: 600;
        color: var(--txt-4); display: block; margin-bottom: 1px;
    }
    .btn-add {
        width: 30px; height: 30px;
        background: var(--p);
        border: none; border-radius: 8px;
        color: #fff; font-size: 18px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; flex-shrink: 0;
        text-decoration: none;
        transition: background .15s, transform .1s;
    }
    .btn-add:hover  { background: var(--p-hov); color: #fff; }
    .btn-add:active { transform: scale(.88); }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="page-hdr">
    <div>
        <h6 style="margin:0;"><i class="bi bi-bag me-2" style="color:var(--p);"></i>AxeShop</h6>
        <div class="t-muted" style="font-size:11px;margin-top:1px;">{{ $products->total() }} produtos disponíveis</div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;">
        <button class="btn btn-sm btn-outline-success"
                style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:5px;"
                onclick="openFilter()">
            <i class="bi bi-sliders"></i>Filtro
        </button>
        <a href="{{ route('cart') }}" class="btn btn-sm btn-outline-success"
           style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:5px;">
            <i class="bi bi-bag"></i>Carrinho
        </a>
    </div>
</div>

{{-- Busca --}}
<div class="search-wrap">
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar produtos...">
    </div>
</div>

{{-- Categorias --}}
<div class="cat-scroll">
    <button class="cat-chip active" data-cat="">Todos</button>
    <button class="cat-chip" data-cat="Velas">Velas</button>
    <button class="cat-chip" data-cat="Incensos">Incensos</button>
    <button class="cat-chip" data-cat="Guias">Guias</button>
    <button class="cat-chip" data-cat="Livros">Livros</button>
    <button class="cat-chip" data-cat="Roupas">Roupas</button>
    <button class="cat-chip" data-cat="Imagens">Imagens</button>
</div>

{{-- Grid --}}
<div class="products-grid" id="products-wrapper">
    @forelse($products as $product)
    <div class="product-card" id="pc-{{ $product->id }}" data-cat="{{ $product->category }}">

        @if($product->stock <= 3 && $product->stock > 0)
            <span class="product-badge badge-sale">Últimas {{ $product->stock }}</span>
        @endif

        <a href="{{ route('shop.products.show', $product->id) }}" class="product-img-wrap">
            <img src="{{ $product->first_image_url }}" alt="{{ $product->name }}" class="product-img"
                 onerror="this.src='https://placehold.co/200x150/dcfce7/166534?text={{ urlencode(substr($product->name,0,1)) }}'">
        </a>

        <div class="product-info">
            <div class="product-cat">{{ $product->category ?? 'Produto' }}</div>
            <a href="{{ route('shop.products.show', $product->id) }}" style="text-decoration:none;">
                <p class="product-name">{{ $product->name }}</p>
            </a>
            <div class="product-footer">
                <div class="product-price">
                    <small>a partir de</small>
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </div>
                <button class="btn-add"
                        onclick="addToCart({{ $product->id }}, this)"
                        title="Adicionar ao carrinho">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
        <div class="empty-state" style="grid-column:1/-1;">
            <i class="bi bi-bag-x"></i>
            <p>Nenhum produto disponível.</p>
        </div>
    @endforelse
</div>

@if ($products->hasPages())
    <div style="padding:16px 12px;">{{ $products->links('pagination::bootstrap-5') }}</div>
@endif

<div style="height:24px;"></div>

{{-- Modal de Filtro --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid var(--border-lt);padding:14px 16px;">
                <h6 class="modal-title fw-800" style="margin:0;">Filtrar Produtos</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:16px;">

                <div class="mb-4">
                    <label class="t-label mb-2 d-block">Categoria</label>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;" id="filterCatGroup">
                        @foreach(['Velas','Incensos','Guias','Livros','Roupas','Imagens'] as $cat)
                        <button class="fchip" data-fcat="{{ $cat }}" style="padding:5px 12px;font-size:12px;">{{ $cat }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="t-label mb-2 d-block">Faixa de Preço</label>
                    <div style="display:flex;gap:10px;align-items:center;">
                        <input type="number" id="filterMin" class="form-control" placeholder="Mín R$" min="0" style="font-size:14px;">
                        <span style="color:var(--txt-3);flex-shrink:0;">–</span>
                        <input type="number" id="filterMax" class="form-control" placeholder="Máx R$" min="0" style="font-size:14px;">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="t-label mb-2 d-block">Disponibilidade</label>
                    <div style="display:flex;gap:8px;">
                        <button class="fchip active" data-fstock="all" style="padding:5px 12px;font-size:12px;">Todos</button>
                        <button class="fchip" data-fstock="in" style="padding:5px 12px;font-size:12px;">Em estoque</button>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border-lt);padding:12px 16px;gap:8px;">
                <button type="button" class="btn btn-sm" style="border-radius:20px;border:1.5px solid var(--border);font-weight:600;color:var(--txt-2);" onclick="resetFilter()">Limpar</button>
                <button type="button" class="btn btn-primary btn-sm" style="border-radius:20px;flex:1;font-weight:700;" onclick="applyFilter()">Aplicar Filtro</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    // Busca
    var debounce;
    $('#searchInput').on('input', function () {
        clearTimeout(debounce);
        var q = $(this).val().toLowerCase();
        debounce = setTimeout(function () {
            $('.product-card').each(function () {
                $(this).toggle($(this).text().toLowerCase().includes(q));
            });
        }, 280);
    });

    // Filtro categoria
    $('.cat-chip').on('click', function () {
        $('.cat-chip').removeClass('active');
        $(this).addClass('active');
        var cat = $(this).data('cat');
        $('.product-card').each(function () {
            var c = $(this).data('cat') || '';
            $(this).toggle(!cat || c === cat);
        });
    });
});

function openFilter() {
    new bootstrap.Modal(document.getElementById('filterModal')).show();
}

var activeFilterCat  = '';
var activeFilterStock = 'all';

$('#filterCatGroup').on('click', '.fchip', function () {
    $('#filterCatGroup .fchip').removeClass('active');
    $(this).addClass('active');
    activeFilterCat = $(this).data('fcat') || '';
});

$('[data-fstock]').on('click', function () {
    $('[data-fstock]').removeClass('active');
    $(this).addClass('active');
    activeFilterStock = $(this).data('fstock');
});

function applyFilter() {
    var min   = parseFloat($('#filterMin').val()) || 0;
    var max   = parseFloat($('#filterMax').val()) || Infinity;
    var cat   = activeFilterCat;
    var stock = activeFilterStock;

    bootstrap.Modal.getInstance(document.getElementById('filterModal')).hide();

    // Atualiza também os chips de categoria na barra
    $('.cat-chip').removeClass('active');
    if (cat) {
        $('.cat-chip[data-cat="' + cat + '"]').addClass('active');
    } else {
        $('.cat-chip[data-cat=""]').addClass('active');
    }

    $('.product-card').each(function () {
        var c     = $(this).data('cat') || '';
        var price = parseFloat($(this).find('.product-price').text().replace(/[^\d,]/g,'').replace(',','.')) || 0;
        var inStock = $(this).find('.badge-sale').length || true; // simplificado: todos visíveis

        var show = true;
        if (cat && c !== cat) show = false;
        if (price < min || price > max) show = false;
        $(this).toggle(show);
    });
}

function resetFilter() {
    $('#filterMin, #filterMax').val('');
    activeFilterCat  = '';
    activeFilterStock = 'all';
    $('#filterCatGroup .fchip').removeClass('active');
    $('[data-fstock]').removeClass('active');
    $('[data-fstock="all"]').addClass('active');
    bootstrap.Modal.getInstance(document.getElementById('filterModal')).hide();
    $('.product-card').show();
    $('.cat-chip').removeClass('active');
    $('.cat-chip[data-cat=""]').addClass('active');
}

function addToCart(productId, btn) {
    var $btn = $(btn);
    $btn.html('<i class="bi bi-hourglass-split"></i>').prop('disabled', true);

    $.post('{{ route("cart.add") }}', {
        product_id: productId,
        quantity: 1,
        _token: '{{ csrf_token() }}'
    })
    .done(function (r) {
        $btn.html('<i class="bi bi-check2"></i>');
        Swal.fire({
            icon: 'success',
            title: 'Adicionado!',
            text: r.message,
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
        setTimeout(function () {
            $btn.html('<i class="bi bi-plus"></i>').prop('disabled', false);
        }, 2000);
    })
    .fail(function () {
        $btn.html('<i class="bi bi-plus"></i>').prop('disabled', false);
        Swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível adicionar.', confirmButtonColor: '#16a34a' });
    });
}
</script>
@endpush
