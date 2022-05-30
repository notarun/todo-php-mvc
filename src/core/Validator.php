<?php

namespace Core;

use Exception;

class Validator
{
    /**
     * Stores the validator rules along with the function.
     *
     * @var array
     */
    private $rules = [];

    /**
     * Load up the validators file
     *
     * @param string $file
     * @return Validator
     */
    public static function load(string $file): Validator
    {
        $validator = new static;
        include $file;
        return $validator;
    }

    /**
     * Add new validator rule to the rules array
     *
     * @param string $name
     * @param Callable $fn
     * @return void
     */
    public function add(string $name, Callable $fn)
    {
        $this->rules[$name] = $fn;
    }

    /**
     * Return the errors if found.
     *
     * @param array $request
     * @param array $inputRules
     * @return array
     */
    public function make(array $request, array $inputRules): array
    {
        $errorBag = [];

        foreach ($inputRules as $key => $rules) {
            foreach ($rules as $rule) {
                $item = $request[$key];
                $result = null;

                if (!is_callable($rule)) {
                    if (array_key_exists($rule, $this->rules)) {
                        $result = $this->rules[$rule]($item, $request);
                    }
                } else {
                    $result = $rule($item, $request);
                }

                // if previous validation failed don't change error message.
                if ($result !== null && empty($errorBag[$key])) {
                    $errorBag[$key] = $result;
                }
            }
        }

        return $errorBag;
    }
}