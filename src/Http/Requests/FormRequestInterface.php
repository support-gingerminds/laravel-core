<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Http\Requests;

use Illuminate\Contracts\Validation\Validator;

interface FormRequestInterface
{
    public function setMethod(string $method): void;
    public function replace(array $input);
    public function all($keys = null);
    public function rules(): array;
    public function messages();
    public function attributes();
    public function setValidator(Validator $validator);
    public function validated($key = null, $default = null);
    public function only($keys);
    public function filled($key);
    public function has($key);
    public function hasFile($key);
    public function file($key);
    public function input($key = null, $default = null);
    public function boolean($key = null, $default = false);
    public function except($keys);
}
