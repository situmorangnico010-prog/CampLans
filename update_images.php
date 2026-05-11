<?php
use App\Models\Item;

$images = [
    1 => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&q=80', // Canon
    2 => 'https://images.unsplash.com/photo-1542567455-cd733f23fbb1?w=800&q=80', // Sony
    3 => 'https://images.unsplash.com/photo-1516961642265-531546e84af2?w=800&q=80', // Canon DSLR
    4 => 'https://images.unsplash.com/photo-1581591524425-c7e0978865fc?w=800&q=80', // Fuji
    5 => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&q=80', // Tent
    6 => 'https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?w=800&q=80', // Sleeping Bag
    7 => 'https://images.unsplash.com/photo-1616423640778-28d1b53229bd?w=800&q=80', // Lens
    8 => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=800&q=80', // Lens 2
    9 => 'https://images.unsplash.com/photo-1601666497334-a1fbdbb13fb9?w=800&q=80', // Tripod
    10 => 'https://images.unsplash.com/photo-1525220959441-11d735591cd9?w=800&q=80', // Camera bag
];

foreach ($images as $id => $url) {
    $item = Item::find($id);
    if ($item) {
        $item->image_url = $url;
        $item->save();
    }
}
echo "Images updated successfully.\n";
