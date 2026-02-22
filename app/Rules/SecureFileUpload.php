<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class SecureFileUpload implements ValidationRule
{
    protected array $allowedExtensions;

    /** Map of allowed MIME types to their extensions (prevents extension spoofing). */
    protected array $safeMimes = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif'],
        'image/webp' => ['webp'],
    ];

    /** @param string[] $allowedExtensions e.g. ['jpeg', 'png', 'jpg', 'gif', 'webp'] */
    public function __construct(array $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'webp'])
    {
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());
        $mime = $value->getMimeType();

        if (! in_array($extension, $this->allowedExtensions, true)) {
            $fail(__('validation.mimes', ['attribute' => $attribute, 'values' => implode(',', $this->allowedExtensions)]));
            return;
        }

        if (! array_key_exists($mime, $this->safeMimes)) {
            $fail(__('validation.mimes', ['attribute' => $attribute, 'values' => implode(',', $this->allowedExtensions)]));
            return;
        }

        if (! in_array($extension, $this->safeMimes[$mime], true)) {
            $fail(__('validation.mimes', ['attribute' => $attribute, 'values' => implode(',', $this->allowedExtensions)]));
        }
    }
}
