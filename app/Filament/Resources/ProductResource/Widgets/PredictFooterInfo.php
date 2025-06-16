<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Http;

class PredictFooterInfo extends Widget
{
    public $produk = '';
    public $error = null;
    public int $days = 7;
    public $result = null;

    public $produkOptions = ['insektisida', 'fungisida', 'benih sawi', 'pupuk urea', 'npk', 'biji tomat', 'vitamin', 'tsp', 'benih padi', 'biji cabai'];
    // protected int | string | array $columnSpan = 'full';

    public function predict()
    {
        try {
            $this->error = null;
            $response = Http::get(env('API_BACKEND_PREDICT') . '/predict', [
                'produk' => $this->produk,
                'days' => $this->days,
            ]);

            $this->result = $response->successful()
                ? $response->json()['prediksi'] : null;
        } catch (\Exception $e) {
            $this->error = 'No data';
        }
    }

    protected static string $view = 'filament.resources.product-resource.widgets.predict-footer-info';
}
