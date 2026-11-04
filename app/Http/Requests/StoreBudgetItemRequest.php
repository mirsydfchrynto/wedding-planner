<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\WeddingCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreBudgetItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category' => ['required', Rule::enum(WeddingCategory::class)],
            'title' => ['required', 'string', 'max:180'],
            'estimated_cost' => ['required', 'integer', 'min:0'],
            'actual_cost' => ['nullable', 'integer', 'min:0'],
            'paid_amount' => ['nullable', 'integer', 'min:0'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
