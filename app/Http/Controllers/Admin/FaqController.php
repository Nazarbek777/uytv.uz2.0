<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query()->orderBy('sort_order')->orderBy('id', 'desc');

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($search = trim($request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('question_uz', 'like', "%{$search}%")
                    ->orWhere('question_ru', 'like', "%{$search}%")
                    ->orWhere('question_en', 'like', "%{$search}%")
                    ->orWhere('answer_uz', 'like', "%{$search}%")
                    ->orWhere('answer_ru', 'like', "%{$search}%")
                    ->orWhere('answer_en', 'like', "%{$search}%");
            });
        }

        $faqs = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Faq::count(),
            'active' => Faq::where('is_active', true)->count(),
        ];

        $categories = Faq::distinct()->pluck('category')->filter()->sort()->values();

        return view('admin.faqs.index', compact('faqs', 'stats', 'categories'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_uz' => 'required|string',
            'question_ru' => 'nullable|string',
            'question_en' => 'nullable|string',
            'answer_uz' => 'required|string',
            'answer_ru' => 'nullable|string',
            'answer_en' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Savol-javob muvaffaqiyatli qo\'shildi.');
    }

    public function show(Faq $faq)
    {
        return view('admin.faqs.show', compact('faq'));
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question_uz' => 'required|string',
            'question_ru' => 'nullable|string',
            'question_en' => 'nullable|string',
            'answer_uz' => 'required|string',
            'answer_ru' => 'nullable|string',
            'answer_en' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Savol-javob muvaffaqiyatli yangilandi.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Savol-javob muvaffaqiyatli o\'chirildi.');
    }
}
