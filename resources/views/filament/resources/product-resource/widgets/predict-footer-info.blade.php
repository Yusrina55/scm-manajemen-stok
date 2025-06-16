<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4 w-full">
            <label for="produk" class="font-semibold">Nama Produk</label>
            <select id="produk" wire:model.defer="produk"
                class="filament-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 mb-4">
                <option value="">-- Pilih Produk --</option>
                @foreach ($this->produkOptions as $name)
                    <option value="{{ $name }}">{{ ucwords($name) }}</option>
                @endforeach
            </select>
            
            <label for="days" class="font-semibold">Jumlah Hari</label>
            <input type="number" id="days" wire:model.defer="days"
                class="filament-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">

            <button type="button" wire:click="predict"
                class="filament-button inline-flex items-center justify-center rounded-lg bg-primary-600 text-white px-4 py-2 font-semibold shadow transition hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500">
                Prediksi
            </button>


            @if (!empty($result))
                <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Prediksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($result as $tanggal => $nilai)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ number_format($nilai, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
            <p>{{ $error ?? '' }}</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
