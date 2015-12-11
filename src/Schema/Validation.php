<?php
namespace Rmtram\TextDatabase\Schema;

use Respect\Validation\Validatable;
use Respect\Validation\Validator;

/**
 * Class Validation
 * @package Rmtram\TextDatabase\Schema
 * @method Validator age(int $minAge = null, int $maxAge = null)
 * @method Validator allOf()
 * @method Validator alnum(string $additionalChars = null)
 * @method Validator alpha(string $additionalChars = null)
 * @method Validator alwaysInvalid()
 * @method Validator alwaysValid()
 * @method Validator arrayVal()
 * @method Validator arrayType()
 * @method Validator attribute(string $reference, Validatable $validator = null, bool $mandatory = true)
 * @method Validator bank(string $countryCode)
 * @method Validator bankAccount(string $countryCode)
 * @method Validator base()
 * @method Validator between(mixed $min = null, mixed $max = null, bool $inclusive = true)
 * @method Validator bic(string $countryCode)
 * @method Validator boolType()
 * @method Validator boolVal()
 * @method Validator bsn()
 * @method Validator call()
 * @method Validator callableType()
 * @method Validator callback(mixed $callback)
 * @method Validator charset(mixed $charset)
 * @method Validator cnh()
 * @method Validator cnpj()
 * @method Validator consonant(string $additionalChars = null)
 * @method Validator contains(mixed $containsValue, bool $identical = false)
 * @method Validator countable()
 * @method Validator countryCode()
 * @method Validator currencyCode()
 * @method Validator cpf()
 * @method Validator creditCard()
 * @method Validator date(string $format = null)
 * @method Validator digit(string $additionalChars = null)
 * @method Validator directory()
 * @method Validator domain(bool $tldCheck = true)
 * @method Validator each(Validatable $itemValidator = null, Validatable $keyValidator = null)
 * @method Validator email()
 * @method Validator endsWith(mixed $endValue, bool $identical = false)
 * @method Validator equals(mixed $compareTo)
 * @method Validator even()
 * @method Validator executable()
 * @method Validator exists()
 * @method Validator extension(string $extension)
 * @method Validator factor(int $dividend)
 * @method Validator falseVal()
 * @method Validator file()
 * @method Validator filterVar(int $filter, mixed $options = null)
 * @method Validator finite()
 * @method Validator floatVal()
 * @method Validator floatType()
 * @method Validator graph(string $additionalChars = null)
 * @method Validator hexRgbColor()
 * @method Validator imei()
 * @method Validator in(mixed $haystack, bool $compareIdentical = false)
 * @method Validator infinite()
 * @method Validator instance(string $instanceName)
 * @method Validator intVal()
 * @method Validator intType()
 * @method Validator ip(mixed $ipOptions = null)
 * @method Validator iterable()
 * @method Validator json()
 * @method Validator key(string $reference, Validatable $referenceValidator = null, bool $mandatory = true)
 * @method Validator keyNested(string $reference, Validatable $referenceValidator = null, bool $mandatory = true)
 * @method Validator keySet(Key $rule...)
 * @method Validator keyValue(string $comparedKey, string $ruleName, string $baseKey)
 * @method Validator leapDate(string $format)
 * @method Validator leapYear()
 * @method Validator length(int $min = null, int $max = null, bool $inclusive = true)
 * @method Validator lowercase()
 * @method Validator macAddress()
 * @method Validator max(mixed $maxValue, bool $inclusive = true)
 * @method Validator mimetype(string $mimetype)
 * @method Validator min(mixed $minValue, bool $inclusive = true)
 * @method Validator minimumAge(int $age)
 * @method Validator multiple(int $multipleOf)
 * @method Validator negative()
 * @method Validator no($useLocale = false)
 * @method Validator noneOf()
 * @method Validator not(Validatable $rule)
 * @method Validator notBlank()
 * @method Validator notEmpty()
 * @method Validator notOptional()
 * @method Validator noWhitespace()
 * @method Validator nullType()
 * @method Validator numeric()
 * @method Validator objectType()
 * @method Validator odd()
 * @method Validator oneOf()
 * @method Validator optional(Validatable $rule)
 * @method Validator perfectSquare()
 * @method Validator phone()
 * @method Validator positive()
 * @method Validator postalCode(string $countryCode)
 * @method Validator primeNumber()
 * @method Validator prnt(string $additionalChars = null)
 * @method Validator punct(string $additionalChars = null)
 * @method Validator readable()
 * @method Validator regex(string $regex)
 * @method Validator resourceType()
 * @method Validator roman()
 * @method Validator scalarVal()
 * @method Validator sf(string $name, array $params = null)
 * @method Validator size(string $minSize = null, string $maxSize = null)
 * @method Validator slug()
 * @method Validator space(string $additionalChars = null)
 * @method Validator startsWith(mixed $startValue, bool $identical = false)
 * @method Validator stringType()
 * @method Validator subdivisionCode(string $countryCode)
 * @method Validator symbolicLink()
 * @method Validator tld()
 * @method Validator trueVal()
 * @method Validator type(string $type)
 * @method Validator uploaded()
 * @method Validator uppercase()
 * @method Validator url()
 * @method Validator version()
 * @method Validator videoUrl(string $service = null)
 * @method Validator vowel()
 * @method Validator when(Validatable $if, Validatable $then, Validatable $when = null)
 * @method Validator writable()
 * @method Validator xdigit(string $additionalChars = null)
 * @method Validator yes($useLocale = false)
 * @method Validator zend(mixed $validator, array $params = null)
 */
class Validation
{
    /**
     * @var mixed
     */
    private $expression;

    private $operator = 'validate';

    /**
     * @param $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    public function _or(\Closure $closure) {

    }

    public function modeValidate()
    {
        $this->operator = 'validate';
        return $this;
    }

    public function modeAssert()
    {
        $this->operator = 'assert';
        return $this;
    }

    public function __call($method, $args) {
        $callable = [Validator::class, $method];
        if (!is_callable($callable)) {
            throw new \BadMethodCallException('undefined method ' . $method);
        }
        /** @var Validator $validator */
        $validator = call_user_func_array($callable, $args);
        $operator = $this->operator;
        return $validator->$operator($this->expression);
    }
}