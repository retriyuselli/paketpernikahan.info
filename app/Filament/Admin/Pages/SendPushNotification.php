<?php

namespace App\Filament\Admin\Pages;

use App\Models\DeviceToken;
use App\Models\User;
use App\Services\PushNotificationService;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SendPushNotification extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;
    protected static ?string $navigationLabel = 'Kirim Notifikasi';
    protected static string|UnitEnum|null $navigationGroup = 'Notifikasi';
    protected static ?string $title = 'Kirim Push Notification';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.admin.pages.send-push-notification';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('target')
                    ->label('Target Penerima')
                    ->options([
                        'all'  => 'Semua Pengguna',
                        'user' => 'Pengguna Tertentu',
                    ])
                    ->default('all')
                    ->live()
                    ->required(),

                Select::make('user_id')
                    ->label('Pilih Pengguna')
                    ->options(fn () => User::whereHas('deviceTokens')->pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn ($get) => $get('target') === 'user')
                    ->required(fn ($get) => $get('target') === 'user'),

                TextInput::make('title')
                    ->label('Judul Notifikasi')
                    ->required()
                    ->maxLength(100),

                Textarea::make('body')
                    ->label('Isi Notifikasi')
                    ->required()
                    ->rows(3)
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    public function send(PushNotificationService $push): void
    {
        $data = $this->form->getState();

        if ($data['target'] === 'all') {
            $push->broadcast($data['title'], $data['body']);
            $count = DeviceToken::count();
            $message = "Notifikasi dikirim ke {$count} device.";
        } else {
            $push->sendToUser((int) $data['user_id'], $data['title'], $data['body']);
            $user    = User::find($data['user_id']);
            $count   = DeviceToken::where('user_id', $data['user_id'])->count();
            $message = "Notifikasi dikirim ke {$user->name} ({$count} device).";
        }

        Notification::make()
            ->title('Berhasil Dikirim')
            ->body($message)
            ->success()
            ->send();

        $this->form->fill();
    }
}
