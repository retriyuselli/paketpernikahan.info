<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public static array $themes = [
        'gold-ivory' => [
            'label'               => 'Gold & Ivory',
            'sage-green'          => '#C9A84C',
            'light-sage'          => '#E8D5A3',
            'soft-pink'           => '#F5ECD7',
            'cream'               => '#FFFFF0',
            'dark-gray'           => '#3B2F2F',
            'accent-dark'         => '#A8872A',
            'sidebar-active-text' => '#3B2F2F',
        ],
        'sage-green' => [
            'label'               => 'Sage Green',
            'sage-green'          => '#9CAF88',
            'light-sage'          => '#C8D5B9',
            'soft-pink'           => '#F9D5E5',
            'cream'               => '#FAF3E7',
            'dark-gray'           => '#444444',
            'accent-dark'         => '#7A9068',
            'sidebar-active-text' => '#444444',
        ],
        'dusty-rose' => [
            'label'               => 'Dusty Rose & Mauve',
            'sage-green'          => '#C4846B',
            'light-sage'          => '#D4A5A5',
            'soft-pink'           => '#F2D7D5',
            'cream'               => '#FDF6F0',
            'dark-gray'           => '#3D3D3D',
            'accent-dark'         => '#A36756',
            'sidebar-active-text' => '#3D3D3D',
        ],
        'navy-gold' => [
            'label'               => 'Navy & Gold',
            'sage-green'          => '#1B3A6B',
            'light-sage'          => '#4A7FAA',
            'soft-pink'           => '#F0D080',
            'cream'               => '#F8F8F4',
            'dark-gray'           => '#0D1F3C',
            'accent-dark'         => '#132B50',
            'sidebar-active-text' => '#ffffff',
        ],
        'blush-burgundy' => [
            'label'               => 'Blush & Burgundy',
            'sage-green'          => '#7B2D42',
            'light-sage'          => '#C8909A',
            'soft-pink'           => '#F5D5DA',
            'cream'               => '#FDF8F5',
            'dark-gray'           => '#3D1020',
            'accent-dark'         => '#5C1F31',
            'sidebar-active-text' => '#ffffff',
        ],
        'pinkbride' => [
            'label'               => 'Pink Bride',
            'sage-green'          => '#D4796B',
            'light-sage'          => '#E8A898',
            'soft-pink'           => '#FAE8E6',
            'cream'               => '#FFF8F7',
            'dark-gray'           => '#2D2020',
            'accent-dark'         => '#B56358',
            'sidebar-active-text' => '#2D2020',
        ],
    ];

    public static function active(): array
    {
        $themePath = storage_path('app/theme.json');
        $name = 'gold-ivory';

        if (file_exists($themePath)) {
            $data = json_decode(file_get_contents($themePath), true);
            if (isset($data['theme']) && array_key_exists($data['theme'], self::$themes)) {
                $name = $data['theme'];
            }
        }

        return ['name' => $name] + self::$themes[$name];
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'string', 'in:' . implode(',', array_keys(self::$themes))],
        ]);

        file_put_contents(
            storage_path('app/theme.json'),
            json_encode(['theme' => $request->theme])
        );

        return back()->with('theme_updated', 'Tema berhasil diubah ke ' . self::$themes[$request->theme]['label']);
    }
}
