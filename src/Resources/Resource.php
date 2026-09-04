<?php

namespace WaAPI\WaAPISdk\Resources;

use WaAPI\WaAPISdk\WaAPISdk;

/**
 * Resources are filled from whatever the API returns, so their properties are
 * not knowable at author time -- fill() assigns every key of the response.
 *
 * PHP 8.2 deprecated creating properties that are not declared, and PHP 9 will
 * make it an error. The attribute states the intent explicitly instead of
 * leaving a deprecation for whoever raises the floor next; it surfaced the
 * moment this package required 8.2, having been silently true for years.
 */
#[\AllowDynamicProperties]
class Resource
{
    /**
     * The resource attributes.
     *
     * @var array
     */
    public $attributes;

    /**
     * The Forge SDK instance.
     *
     * @var WaAPISdk|null
     */
    protected $whatsAppSdk;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     * @param WaAPISdk|null $whatsAppSdk
     * @return void
     */
    public function __construct(array $attributes, $whatsAppSdk = null)
    {
        $this->attributes = $attributes;
        $this->whatsAppSdk = $whatsAppSdk;

        $this->fill($this->attributes);
    }

    /**
     * Fill the resource with the array of attributes.
     *
     * @param array $data
     * @return void
     */
    protected function fill($data)
    {
        foreach ($data as $key => $value) {
            $key = $this->camelCase($key);

            $this->{$key} = $value;
        }
    }

    /**
     * Convert the key name to camel case.
     *
     * @param string $key
     * @return string
     */
    protected function camelCase($key)
    {
        $parts = explode('_', $key);

        foreach ($parts as $i => $part) {
            if ($i !== 0) {
                $parts[$i] = ucfirst($part);
            }
        }

        return str_replace(' ', '', implode(' ', $parts));
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param array $collection
     * @param string $class
     * @param array $extraData
     * @return array
     */
    protected function transformCollection($collection, $class, $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this->whatsAppSdk);
        }, $collection);
    }

}
