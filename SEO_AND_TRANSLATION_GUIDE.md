# SEO va Ko'p Tillilik Qo'llanmasi

## 📋 Umumiy Ma'lumot

Bu loyiha **SEO optimizatsiya** va **avtomatik ko'p tillilik** bilan ishlaydi.

## 🚀 O'rnatish

### 1. Paketlarni o'rnatish

```bash
cd backend
composer install
```

### 2. Database Migration

```bash
php artisan migrate
```

### 3. Environment Variables (.env)

Quyidagi o'zgaruvchilarni `.env` fayliga qo'shing:

```env
# Default locale
APP_LOCALE=uz
APP_FALLBACK_LOCALE=uz

# Translation API (birini tanlang)
GOOGLE_TRANSLATE_API_KEY=your_google_translate_api_key
# YOKI
DEEPL_API_KEY=your_deepl_api_key
```

## 📝 Qanday Ishlaydi

### 1. Property Yaratish

Property yaratishda:
- **Asosiy tilda** (uz, ru yoki en) ma'lumot kiritiladi
- **Qolgan tillar avtomatik tarjima qilinadi**
- **SEO metadata avtomatik yaratiladi** (har bir til uchun)

### 2. SEO Optimizatsiya

Har bir property uchun avtomatik yaratiladi:
- ✅ Meta tags (title, description, keywords)
- ✅ Open Graph tags (Facebook uchun)
- ✅ Twitter Card tags
- ✅ Canonical URLs
- ✅ Hreflang tags (ko'p tillilik uchun)
- ✅ Structured Data (JSON-LD) - Schema.org

### 3. URL Struktura

```
/uz/listings                    - Uzbek listings
/ru/listings                    - Russian listings
/en/listings                    - English listings

/uz/properties/property-slug     - Uzbek property
/ru/properties/property-slug     - Russian property
/en/properties/property-slug     - English property
```

## 🎯 Asosiy Xususiyatlar

### 1. Avtomatik Tarjima

```php
// Property yaratishda
$translationService = app(TranslationService::class);
$translations = $translationService->translateProperty($data, 'uz');
// Avtomatik ru va en tillariga tarjima qilinadi
```

### 2. SEO Metadata

```php
// Property uchun SEO yaratish
$seoService = app(SeoService::class);
$seoMeta = $seoService->generateForProperty($property, 'uz');
```

### 3. Blade Template'da Ishlatish

```blade
{{-- Layout faylida (head qismida) --}}
<x-seo-meta :seoMeta="$seoMeta" :locale="$locale" />
```

## 📊 Database Struktura

### Properties Table
- Asosiy ma'lumotlar (price, area, bedrooms, etc.)
- Tildan mustaqil ma'lumotlar

### Property Translations Table
- Tarjima qilinadigan maydonlar
- Har bir til uchun alohida qator

### SEO Metas Table
- SEO metadata
- Har bir property va til uchun alohida

## 🔧 Konfiguratsiya

### Supported Locales
`config/app.php`:
```php
'available_locales' => ['uz', 'ru', 'en'],
```

### Translation Service
`config/services.php`:
```php
'google_translate' => [
    'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
],
```

## 📈 SEO Best Practices

1. **Slug-based URLs** - Har bir property uchun unique slug
2. **Canonical URLs** - Duplicate contentni oldini olish
3. **Hreflang Tags** - Ko'p tillilik uchun
4. **Structured Data** - Google'da yaxshi ko'rinish
5. **Meta Descriptions** - 150-160 belgi
6. **Image Alt Tags** - Rasm SEO

## 🛠️ Keyingi Qadamlar

1. ✅ Google Translate API key olish
2. ✅ Blade template'larni yangilash (SEO component qo'shish)
3. ✅ Sitemap.xml yaratish (avtomatik)
4. ✅ Robots.txt sozlash
5. ✅ Image optimization

## 📝 Eslatmalar

- **Tarjima API** - Google Translate yoki DeepL ishlatiladi
- **SEO Metadata** - Har bir property yaratilganda avtomatik yaratiladi
- **Slug Generation** - Har bir til uchun alohida slug yaratiladi
- **Views Tracking** - Har bir ko'rishda views oshadi

## 🐛 Muammo Hal Qilish

### Tarjima ishlamayapti?
- `.env` faylida API key to'g'ri ekanligini tekshiring
- API key'ning limitini tekshiring

### SEO metadata ko'rinmayapti?
- `seo_metas` jadvalida ma'lumot borligini tekshiring
- Blade template'da `<x-seo-meta>` component qo'shilganligini tekshiring








