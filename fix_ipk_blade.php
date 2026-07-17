<?php
$content = file_get_contents('resources/views/obe/pdf_recap.blade.php');

$old_dynamic = <<<'EOD'
    @if(empty($data) || (is_iterable($data) && count($data) == 0))
        <div style="margin-top: 30px; text-align: center; padding: 20px; background: #f9f9f9; border: 1px dashed #ccc;">
            Belum ada data untuk laporan ini.
        </div>
    @else
EOD;

$new_dynamic = <<<'EOD'
    @if(empty($data) || (is_iterable($data) && count($data) == 0))
        <div style="margin-top: 30px; text-align: center; padding: 20px; background: #f9f9f9; border: 1px dashed #ccc;">
            Belum ada data untuk laporan ini.
        </div>
    @elseif($kriteria === 'C2' && $ppepp === 'P3_Ipk')
        @php
            $grouped = collect($data)->groupBy('Tahun_Akademik');
        @endphp
        
        @foreach($grouped as $ta => $mhsList)
            @php
                $avg = $mhsList->avg('IPK');
            @endphp
            <div style="margin-top: 25px; margin-bottom: 5px; border-bottom: 2px solid #333; padding-bottom: 5px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.1rem;">Tahun Akademik: {{ $ta }}</h3>
                <h3 style="margin: 0; font-size: 1.1rem; color: #4f46e5;">Rata-rata IPK: {{ number_format($avg, 2) }}</h3>
            </div>
            
            <table class="data-table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">NIM</th>
                        <th style="width: 50%;">Nama Lengkap</th>
                        <th style="width: 20%;">IPK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mhsList as $index => $m)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ is_object($m) ? $m->NIM : $m['NIM'] }}</td>
                            <td>{{ is_object($m) ? $m->Nama_Lengkap : $m['Nama_Lengkap'] }}</td>
                            <td class="text-center">{{ number_format(is_object($m) ? $m->IPK : $m['IPK'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @else
EOD;

$content = str_replace($old_dynamic, $new_dynamic, $content);

file_put_contents('resources/views/obe/pdf_recap.blade.php', $content);
echo "Added specific grouping logic for C2_P3_Ipk to blade template.";
