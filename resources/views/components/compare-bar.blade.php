{{-- Floating Compare Bar — muncul saat ada paket dipilih untuk dibandingkan --}}
<div
    x-data
    x-show="$store.compare.items.length > 0"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    class="fixed bottom-0 inset-x-0 z-50 pb-[env(safe-area-inset-bottom)]"
    style="display: none;"
>
    <div class="bg-white border-t border-gray-200 shadow-[0_-4px_24px_rgba(0,0,0,0.12)] px-4 py-3">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center gap-3">

                {{-- Label --}}
                <div class="hidden sm:flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-[12px] font-semibold text-dark">Bandingkan</span>
                </div>

                {{-- Slot paket (max 3) --}}
                <div class="flex-1 flex items-center gap-2 overflow-x-auto">
                    <template x-for="(item, i) in $store.compare.items" :key="item.id">
                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 shrink-0 min-w-0 max-w-[180px]">
                            <img :src="item.image" :alt="item.name" class="w-8 h-8 rounded-lg object-cover shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-medium text-dark truncate leading-tight" x-text="item.name"></p>
                                <p class="text-[10px] text-gray-400 truncate leading-tight" x-text="item.vendor"></p>
                            </div>
                            <button @click.prevent="$store.compare.remove(item.id)" class="text-gray-400 hover:text-red-500 transition shrink-0 ml-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>

                    {{-- Slot kosong --}}
                    <template x-for="i in (3 - $store.compare.items.length)" :key="'empty-' + i">
                        <div class="flex items-center justify-center w-[120px] h-[44px] rounded-xl border-2 border-dashed border-gray-200 shrink-0">
                            <span class="text-[10px] text-gray-300">+ Pilih paket</span>
                        </div>
                    </template>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex items-center gap-2 shrink-0">
                    <button
                        @click="$store.compare.clear()"
                        class="text-[11px] text-gray-400 hover:text-gray-600 transition px-2 py-1.5"
                    >Hapus</button>

                    <a
                        :href="$store.compare.compareUrl()"
                        :class="$store.compare.items.length < 2 ? 'opacity-50 pointer-events-none' : ''"
                        class="bg-accent text-white text-[12px] font-semibold px-4 py-2 rounded-xl transition hover:bg-accent/90 whitespace-nowrap"
                    >
                        Bandingkan
                        <span x-text="'(' + $store.compare.items.length + ')'"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
