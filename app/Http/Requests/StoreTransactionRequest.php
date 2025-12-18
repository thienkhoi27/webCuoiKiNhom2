<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('username') !== null;
    }

    protected function prepareForValidation(): void
    {
        // nếu bạn nhập kiểu 1.234.567 thì chuyển về 1234567
        $total = $this->input('total');
        if (is_string($total)) {
            $total = str_replace(['.', ',', ' '], '', $total);
        }

        $this->merge([
            'total' => $total,
        ]);
    }

    public function rules(): array
    {
        $user = session('username');

        return [
            'type' => ['required', Rule::in(['expense', 'income'])],

            // tên thu/chi
            'expense' => ['required', 'string', 'max:255'],

            // số tiền
            'total' => ['required', 'numeric', 'min:1'],

            // ngày
            'date' => ['required', 'date'],

            // danh mục: chỉ bắt buộc khi CHI
            'category_id' => [
                Rule::requiredIf(fn () => $this->input('type') === 'expense'),
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($q) => $q->where('user', $user)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Vui lòng chọn loại (Chi/Thu).',
            'type.in' => 'Loại không hợp lệ.',
            'expense.required' => 'Vui lòng nhập tên thu/chi.',
            'total.required' => 'Vui lòng nhập số tiền.',
            'total.numeric' => 'Số tiền phải là số.',
            'total.min' => 'Số tiền phải lớn hơn 0.',
            'date.required' => 'Vui lòng chọn ngày.',
            'date.date' => 'Ngày không hợp lệ.',
            'category_id.required' => 'Vui lòng chọn danh mục cho khoản chi.',
            'category_id.exists' => 'Danh mục không tồn tại hoặc không thuộc về bạn.',
        ];
    }
}
