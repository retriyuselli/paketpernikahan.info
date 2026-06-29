<?php

namespace App\Filament\Admin\Resources\VipGuests\Pages;

use App\Exports\VipGuestTemplateExport;
use App\Exports\VipGuestsExport;
use App\Filament\Admin\Resources\VipGuests\VipGuestResource;
use App\Imports\VipGuestImport;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListVipGuests extends ListRecords
{
    protected static string $resource = VipGuestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplate')
                ->label('Unduh Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(
                    new VipGuestTemplateExport(),
                    'template-tamu-vip.xlsx'
                )),

            Action::make('exportXlsx')
                ->label('Export XLSX')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->options(fn () => User::role(['customer', 'pengunjung'])
                            ->orderBy('name')
                            ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->helperText('Pilih customer yang data tamu VIP-nya akan diexport.'),
                ])
                ->action(fn (array $data) => Excel::download(
                    new VipGuestsExport((int) $data['user_id']),
                    'tamu-vip.xlsx'
                )),

            Action::make('importXlsx')
                ->label('Import XLSX')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->options(fn () => User::role(['customer', 'pengunjung'])
                            ->orderBy('name')
                            ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->helperText('Pilih customer pemilik data tamu VIP ini.'),

                    FileUpload::make('file')
                        ->label('File XLSX')
                        ->disk('local')
                        ->directory('vip-guest-imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->maxSize(2048)
                        ->required()
                        ->helperText('Maks. 2 MB. Gunakan template yang tersedia.'),
                ])
                ->action(function (array $data): void {
                    $path   = Storage::disk('local')->path($data['file']);
                    $import = new VipGuestImport((int) $data['user_id']);

                    Excel::import($import, $path);

                    // Hapus file setelah import selesai
                    Storage::disk('local')->delete($data['file']);

                    Notification::make()
                        ->title('Import Berhasil')
                        ->body("{$import->getImported()} tamu VIP berhasil diimpor, {$import->getSkipped()} baris dilewati.")
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
