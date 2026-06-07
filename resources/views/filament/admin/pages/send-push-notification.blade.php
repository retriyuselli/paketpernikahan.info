<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit="send">
            {{ $this->form }}

            <div style="height: 32px;"></div>

            <div>
                <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                    Kirim Notifikasi
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>
