<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventClass;
use App\Models\EventGallery;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('events')) {
            return view('event.index', ['events' => collect()]);
        }

        $query = Event::query()->whereIn('status', ['approved', 'berjalan', 'selesai']);

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('wilayah_kota')) {
            $query->where('wilayah_kota', 'like', '%'.$request->wilayah_kota.'%');
        }

        if ($request->filled('search')) {
            $query->where('nama_event', 'like', '%'.$request->search.'%');
        }

        $selectedKategori = $request->query('kategori', '');

        $events = $query->orderBy('created_at', 'desc')
            ->with('organizer.user')
            ->paginate(12);

        return view('event.index', compact('events', 'selectedKategori'));
    }

    public function show(string $slug)
    {
        if (! Schema::hasTable('events')) {
            abort(404);
        }

        $event = Event::where('slug', $slug)
            ->whereIn('status', ['approved', 'berjalan', 'selesai'])
            ->with('organizer.user', 'classes', 'judges', 'cps')
            ->firstOrFail();

        $winners = Winner::where('event_id', $event->id)
            ->with(['class', 'certificate', 'predikat'])
            ->orderBy('event_class_id')
            ->orderBy('peringkat')
            ->get()
            ->groupBy(fn ($w) => $w->class?->nama_kelas ?? 'Tanpa Kelas');

        $galleries = EventGallery::where('event_id', $event->id)->latest()->get();

        $classes = EventClass::where('event_id', $event->id)->get();

        $hasRekap = $classes->contains(fn (EventClass $class): bool => filled($class->rekap_sheet_url));

        return view('event.show', compact('event', 'winners', 'galleries', 'classes', 'hasRekap'));
    }
}
