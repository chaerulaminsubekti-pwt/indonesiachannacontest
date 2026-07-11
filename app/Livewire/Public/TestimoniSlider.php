<?php

namespace App\Livewire\Public;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class TestimoniSlider extends Component
{
    public function render()
    {
        if (! Schema::hasTable('testimonials')) {
            return view('livewire.public.testimoni-slider', ['testimonials' => collect()]);
        }

        $testimonials = Testimonial::where('status', 'approved')
            ->with('event', 'organizer.user')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.public.testimoni-slider', compact('testimonials'));
    }
}
