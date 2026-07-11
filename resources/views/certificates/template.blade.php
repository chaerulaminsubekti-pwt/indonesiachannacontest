<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat</title>
    <style>
        @page { margin: 5mm; size: A4 landscape; }
        body { margin: 0; padding: 0; font-family: 'DejaVu Sans', sans-serif; }
        .o { border: 3px solid #0A0A0A; border-radius: 14px; padding: 3px; background: #fff; }
        .i { border: 1.5px solid #FF1A1A; border-radius: 10px; background: linear-gradient(160deg, #fefefe, #fff5f5); padding: 6px 14px; }
        .wm { text-align: center; font-size: 60px; color: rgba(255,26,26,0.03); font-weight: 900; letter-spacing: 4px; margin: 0; line-height: 0.6; }
        .lc { text-align: center; }
        .lc img { max-width: 40px; max-height: 40px; }
        .o1 { text-align: center; font-size: 12px; font-weight: 800; color: #0A0A0A; letter-spacing: 2px; text-transform: uppercase; margin: 0; }
        .e1 { text-align: center; font-size: 9px; color: #FF1A1A; font-weight: 600; margin: 0; }
        .d1 { width: 50px; height: 1.5px; background: linear-gradient(90deg,#b8860b,#daa520,#b8860b); margin: 1px auto; }
        .t1 { text-align: center; font-size: 16px; font-weight: 900; color: #0A0A0A; letter-spacing: 3px; text-transform: uppercase; margin: 4px 0 1px; }
        .td1 { width: 120px; height: 1.5px; background: linear-gradient(90deg,transparent,#daa520,transparent); margin: 1px auto 2px; }
        .w1 { text-align: center; font-size: 20px; font-weight: 900; color: #0A0A0A; text-transform: uppercase; letter-spacing: 1px; margin: 3px 0 1px; }
        .bw { text-align: center; }
        .b1 { display: inline-block; padding: 2px 14px; background: linear-gradient(135deg,#FF1A1A,#CC1515); color: #fff; font-size: 10px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .x1 { text-align: center; font-size: 9px; color: #444; line-height: 1.5; margin: 2px 0; }
        .x1 strong { color: #0A0A0A; }
        .x1 .h1 { color: #FF1A1A; font-weight: 700; }
        .ft1 { width: 100%; margin-top: 2px; }
        .ft1 td { vertical-align: bottom; }
        .qc { text-align: center; width: 50px; }
        .qc img { width: 35px; height: 35px; }
        .qc p { font-size: 5px; color: #999; margin: 0; }
        .sc { text-align: center; padding: 0 3px; }
        .sc p { font-size: 6px; color: #555; margin: 0; }
        .sl { width: 60px; height: 1px; background: #0A0A0A; margin: 5px auto 1px; }
        .sn { font-size: 6px; font-weight: 700; color: #0A0A0A; }
        .nn { text-align: center; font-size: 6px; color: #aaa; margin: 0; }
    </style>
</head>
<body>
    <div class="o">
        <div class="i">
            <div class="wm">ICC</div>
            <div class="lc">
                @if ($logoExists)<img src="{{ $logoPath }}" alt="ICC">@endif
            </div>
            <p class="o1">Indonesia Channa Contest</p>
            <p class="e1">{{ $winner->event->nama_event }}</p>
            <div class="d1"></div>

            <p class="t1">Sertifikat Penghargaan</p>
            <div class="td1"></div>

            <p class="w1">{{ $winner->nama_pemenang }}</p>

            @php
                $pText = $winner->predikat?->nama_predikat ?? $winner->class?->nama_kelas ?? 'Juara';
                $isStd = in_array($pText, ['Juara 1','Juara 2','Juara 3','Juara 4','Juara 5']);
            @endphp

            <div class="bw"><span class="b1">{{ $pText }}</span></div>

            <p class="x1">
                @if ($isStd)
                    Telah meraih <strong>{{ $pText }}</strong> pada Kelas <span class="h1">{{ $winner->class?->nama_kelas ?? '-' }}</span><br>
                @else
                    Sebagai <span class="h1">{{ $pText }}</span><br>
                @endif
                dalam event <strong>{{ $winner->event->nama_event }}</strong><br>
                {{ \Carbon\Carbon::parse($winner->event->tanggal_mulai)->isoFormat('D MMMM Y') }} &middot; {{ $winner->event->venue }}, {{ $winner->event->wilayah_kota }}
            </p>

            <table class="ft1">
                <tr>
                    <td class="qc">
                        <img src="{{ $qrPath }}" alt="QR">
                        <p>Scan verifikasi</p>
                    </td>
                    <td class="sc">
                        <p>Ketua ICC</p>
                        <div class="sl"></div>
                        <p class="sn">{{ $settings->nama_ketua ?? '____________________' }}</p>
                    </td>
                    <td class="sc">
                        <p>Pembina ICC</p>
                        <div class="sl"></div>
                        <p class="sn">{{ $settings->nama_pembina ?? '____________________' }}</p>
                    </td>
                    <td class="sc">
                        <p>Penyelenggara</p>
                        <div class="sl"></div>
                        <p class="sn">{{ $winner->event->organizer?->user?->name ?? $winner->event->organizer?->nama_organisasi ?? '____________________' }}</p>
                    </td>
                </tr>
            </table>
            <p class="nn">No. Sertifikat: {{ $certificate->nomor_sertifikat }}</p>
        </div>
    </div>
</body>
</html>