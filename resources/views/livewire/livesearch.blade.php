<div>
    {{-- Search Input with Enhanced Icon --}}
    <div class="search-form">
        <svg style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); width:1.35rem; height:1.35rem; color:var(--accent); pointer-events:none; z-index:2; transition:all 0.3s ease" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        
        <input type="search" 
               wire:model.live.debounce.300ms="search" 
               placeholder="Cari roti favorit Anda..." 
               class="search-input">
        
        @if($search)
        <button wire:click="$set('search', '')" 
                style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; color:#999; padding:0.5rem; border-radius:50%; transition:all 0.3s ease"
                onmouseover="this.style.background='rgba(180,83,9,0.1)'; this.style.color='var(--accent)'"
                onmouseout="this.style.background='transparent'; this.style.color='#999'">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        @endif
    </div>
    
    @if($search)
    <div class="search-note">
        🔍 Hasil pencarian untuk: "<strong>{{ $search }}</strong>"
    </div>
    @endif

    {{-- Loading indicator --}}
    <div wire:loading style="margin-top:1rem; padding:0.75rem; background:rgba(180,83,9,0.05); border-radius:10px; text-align:center; border:1px dashed var(--accent)">
        <span style="display:inline-flex; align-items:center; gap:0.5rem; color:var(--accent); font-weight:500">
            <svg style="width:1.25rem; height:1.25rem; animation:spin 1s linear infinite" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Sedang mencari roti terbaik untuk Anda...
        </span>
    </div>

    {{-- Results --}}
    <div wire:loading.class="opacity-50" style="margin-top:1.5rem">
        @if($breads->isEmpty())
            <div class="empty">
                <svg style="width:4rem; height:4rem; margin:0 auto 1rem; color:#d1d5db" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p style="font-size:1.1rem; margin:0 0 0.5rem; color:#6b7280">Maaf, tidak ada produk yang ditemukan</p>
                <p style="font-size:0.9rem; color:#9ca3af">Coba gunakan kata kunci yang berbeda</p>
            </div>
        @else
            <div class="grid">
                @foreach($breads as $bread)
                <article class="card">
                    <a class="card-link" href="{{ route('breads.show', $bread) }}">
                        @if($bread->image)
                        <img src="{{ asset('storage/' . $bread->image) }}" class="thumb" alt="{{ $bread->name }}">
                        @else
                        <img src="{{ asset('images/placeholder.png') }}" class="thumb" alt="{{ $bread->name }}">
                        @endif
                        <div class="card-body">
                            <h3 class="title">{{ $bread->name }}</h3>
                            <p class="excerpt">{{ Str::limit($bread->description, 120) }}</p>
                            <div class="price">Rp {{ number_format($bread->price, 0, ',', '.') }}</div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
            
            <div style="margin-top:1.5rem; padding:1rem; background:rgba(180,83,9,0.05); border-radius:10px; text-align:center">
                <span style="color:var(--muted); font-size:0.9rem">
                    Menampilkan <strong style="color:var(--accent)">{{ $breads->count() }}</strong> produk
                </span>
            </div>
        @endif
    </div>

    <style>
        @keyframes spin{
            from{transform:rotate(0deg)}
            to{transform:rotate(360deg)}
        }
    </style>
</div>
