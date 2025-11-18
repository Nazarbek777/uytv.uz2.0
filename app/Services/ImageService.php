<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected ImageManager $imageManager;
    
    // Standart o'lchamlar
    protected int $width = 1200;
    protected int $height = 800;
    protected int $quality = 90;
    
    // Watermark o'lchamlari
    protected int $watermarkWidth = 200;
    protected int $watermarkHeight = 60;
    protected int $watermarkOpacity = 50; // 0-100
    
    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Rasmni optimallashtirish va saqlash
     * 
     * @param UploadedFile $file
     * @param string $folder
     * @param bool $addWatermark
     * @return string
     */
    public function processAndStore(UploadedFile $file, string $folder = 'properties', bool $addWatermark = true): string
    {
        // Rasmni yuklash
        $image = $this->imageManager->read($file->getRealPath());
        
        // Rasmni 1200x800 ga to'liq moslashtirish (oq fon qo'shmasdan)
        $image = $this->resizeToExactSize($image);
        
        // Watermark qo'shish
        if ($addWatermark) {
            $image = $this->addWatermark($image);
        }
        
        // Fayl nomini yaratish
        $filename = $this->generateFilename($file);
        $path = $folder . '/' . $filename;
        
        // Rasm sifatini optimallashtirish va saqlash
        $encoded = $image->toJpeg($this->quality);
        Storage::disk('public')->put($path, $encoded);
        
        return $path;
    }

    /**
     * Rasmni 1200x800 ga to'liq moslashtirish (oq fon qo'shmasdan)
     * Kichik rasmlar ham kattalashtiriladi va to'liq 1200x800 ga moslashtiriladi
     */
    protected function resizeToExactSize($image)
    {
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        $originalRatio = $originalWidth / $originalHeight;
        $targetRatio = $this->width / $this->height;

        // Rasmni 1200x800 ga to'liq moslashtirish
        // Cover usuli - rasmni kattalashtirib, kerak bo'lsa qirqib olish
        // Scale ratio hisoblash - qaysi tomon bo'yicha kattalashtirish kerak
        $scaleRatio = max($this->width / $originalWidth, $this->height / $originalHeight);
        
        // Rasmni kattalashtirish (sifatga ta'sir qilmasdan)
        $newWidth = (int)($originalWidth * $scaleRatio);
        $newHeight = (int)($originalHeight * $scaleRatio);
        
        $image->scale($newWidth, $newHeight);
        
        // Markazdan qirqib olish (1200x800 ga)
        $cropX = (int)(($image->width() - $this->width) / 2);
        $cropY = (int)(($image->height() - $this->height) / 2);
        $image->crop($this->width, $this->height, $cropX, $cropY);

        return $image;
    }

    /**
     * Watermark qo'shish (uytv logosi)
     * PNG va JPG formatlarni qo'llab-quvvatlaydi
     * Orqa fonsiz va chetroq joylashtiriladi
     */
    protected function addWatermark($image)
    {
        // Watermark logo fayl yo'llari (avval PNG, keyin JPG)
        $watermarkPaths = [
            public_path('images/watermark.png'),
            public_path('images/watermark.jpg'),
            public_path('images/watermark.jpeg'),
        ];
        
        $watermarkPath = null;
        
        // Mavjud watermark faylini topish
        foreach ($watermarkPaths as $path) {
            if (file_exists($path)) {
                $watermarkPath = $path;
                break;
            }
        }
        
        // Agar watermark fayli mavjud bo'lsa
        if ($watermarkPath) {
            try {
                $watermark = $this->imageManager->read($watermarkPath);
                $extension = strtolower(pathinfo($watermarkPath, PATHINFO_EXTENSION));
                
                // JPG/JPEG formatda bo'lsa, oq orqa fonni olib tashlash
                if ($extension === 'jpg' || $extension === 'jpeg') {
                    $watermark = $this->removeWhiteBackground($watermark);
                }
                
                // Watermark o'lchamini optimallashtirish
                $watermark->scale($this->watermarkWidth);
                
                // Watermark opacity (PNG uchun ishlaydi)
                if ($extension === 'png') {
                    $watermark->opacity($this->watermarkOpacity);
                }
                
                // Watermark pozitsiyasi (o'ng yuqori burchak - chetroq)
                $margin = 30; // Chetroq joylashtirish uchun margin
                $x = $image->width() - $watermark->width() - $margin;
                $y = $margin; // Yuqoridan margin
                
                // Watermark qo'shish (orqa fonsiz)
                $image->place($watermark, $x, $y);
            } catch (\Exception $e) {
                // Watermark qo'shishda xatolik bo'lsa, davom etish
                \Log::warning('Watermark qo\'shishda xatolik: ' . $e->getMessage());
            }
        }

        return $image;
    }

    /**
     * JPG/JPEG rasmdan oq orqa fonni olib tashlash
     * Oq rangni transparent qiladi
     */
    protected function removeWhiteBackground($image)
    {
        $width = $image->width();
        $height = $image->height();
        
        // Oq rang tolerance (oqga yaqin ranglarni ham transparent qilish)
        $tolerance = 40; // 0-255 orasida
        
        // Temporary PNG faylga saqlash (transparent qo'llab-quvvatlash uchun)
        $tempPath = sys_get_temp_dir() . '/watermark_' . uniqid() . '.png';
        $image->toPng()->save($tempPath);
        
        // GD orqali o'qish va o'zgartirish
        $gdResource = imagecreatefrompng($tempPath);
        
        if ($gdResource === false) {
            @unlink($tempPath);
            return $image; // Xatolik bo'lsa, original qaytarish
        }
        
        // ImageAlphaBlending va SaveAlpha sozlamalari
        imagealphablending($gdResource, false);
        imagesavealpha($gdResource, true);
        
        // Har bir pixelni tekshirish va oq rangni transparent qilish
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                // GD orqali pixel rangini olish
                $colorIndex = imagecolorat($gdResource, $x, $y);
                $color = imagecolorsforindex($gdResource, $colorIndex);
                
                // RGB qiymatlarini olish
                $r = $color['red'];
                $g = $color['green'];
                $b = $color['blue'];
                
                // Oq rang yoki oqga yaqin rangni aniqlash
                if ($r >= (255 - $tolerance) && $g >= (255 - $tolerance) && $b >= (255 - $tolerance)) {
                    // Transparent qilish (alpha = 127 - GD da transparent)
                    $transparent = imagecolorallocatealpha($gdResource, 0, 0, 0, 127);
                    imagesetpixel($gdResource, $x, $y, $transparent);
                }
            }
        }
        
        // PNG formatda saqlash
        imagepng($gdResource, $tempPath);
        imagedestroy($gdResource);
        
        // Qayta o'qish
        $image = $this->imageManager->read($tempPath);
        
        // Temporary faylni o'chirish
        @unlink($tempPath);
        
        return $image;
    }

    /**
     * Unique fayl nomi yaratish
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        
        return $filename;
    }

    /**
     * Bir nechta rasmlarni qayta ishlash
     */
    public function processMultiple(array $files, string $folder = 'properties', bool $addWatermark = true): array
    {
        $paths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->processAndStore($file, $folder, $addWatermark);
            }
        }
        
        return $paths;
    }

    /**
     * Rasm o'lchamlarini sozlash
     */
    public function setDimensions(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;
        
        return $this;
    }

    /**
     * Rasm sifatini sozlash
     */
    public function setQuality(int $quality): self
    {
        $this->quality = max(1, min(100, $quality));
        
        return $this;
    }
}

