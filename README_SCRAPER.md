# Property Scraper Integration (OpenAI Powered)

Bu sistem **OpenAI sun'iy intellekt** yordamida turli xil saytlardan uy-joy ma'lumotlarini avtomatik yig'ib database'ga saqlaydi.

## Qanday ishlaydi?

1. **Sayt HTML'ini olish** - Guzzle HTTP Client orqali
2. **OpenAI tahlil qilish** - HTML'ni AI tahlil qilib, struktur ma'lumotlar ajratadi
3. **Database'ga saqlash** - Ma'lumotlar avtomatik saqlanadi

## Afzalliklari:

✅ **Scraping kerak emas** - AI HTML'ni o'qib, ma'lumotlarni ajratadi  
✅ **Aniq tahlil** - AI har xil formatlarni tushunadi  
✅ **Avtomatik formatlash** - Ma'lumotlar to'g'ri formatda qaytadi  
✅ **Multi-language** - Uzbek, Russian, English tarjimalar  

## Ishlatish

### 1. .env faylga API key qo'shish:

```env
OPENAI_API_KEY=sk-proj-...
```

### 2. Manual ishga tushirish:

```bash
cd backend
php artisan properties:scrape --limit=100
```

### 3. GitHub Actions orqali:

1. GitHub repository'ga `.github/workflows/scrape-properties.yml` faylini qo'shing
2. GitHub Secrets'ga `OPENAI_API_KEY` qo'shing:
   - Repository > Settings > Secrets > New secret
   - Name: `OPENAI_API_KEY`
   - Value: OpenAI API key'ingiz
3. Workflow avtomatik har kuni ishga tushadi (00:00 UTC = 05:00 Toshkent)
4. Manual ishga tushirish: Actions tab > Scrape Properties > Run workflow

## Qo'llab-quvvatlanadigan saytlar:

- **OLX.uz** - Kvartiralar, uylar
- **Uybor.uz** - Kvartiralar, uylar  
- **E-xarid.uz** - Nедвижимость
- Boshqa saytlar (qo'shish oson)

## OpenAI Model:

- **gpt-4o-mini** - Arzon va tez (default)
- **gpt-4o** - Qimmatroq, lekin yaxshiroq tahlil
- **gpt-4-turbo** - Eng yaxshi natija

Model'ni `.env` faylda o'zgartirishingiz mumkin:
```env
OPENAI_MODEL=gpt-4o-mini
```

## Eslatmalar:

- OpenAI API key kerak (token xarajati bor)
- Barcha uy-joylar `pending` holatida saqlanadi (admin tasdiqlashi kerak)
- Duplicate'lar avtomatik tekshiriladi (source + source_id)
- Rasm yuklash avtomatik amalga oshiriladi
- HTML'ni qisqartirish - token xarajatini kamaytirish uchun

## Xarajatlar:

- **gpt-4o-mini**: ~$0.15 per 1M input tokens, ~$0.60 per 1M output tokens
- **gpt-4o**: ~$2.50 per 1M input tokens, ~$10 per 1M output tokens

100 ta uy-joy uchun taxminan: **$0.10 - $0.50** (model'ga qarab)

## Qo'shimcha ma'lumot:

- Scraper service: `app/Services/PropertyScraperService.php`
- Command: `app/Console/Commands/ScrapeProperties.php`
- Workflow: `.github/workflows/scrape-properties.yml`
