<?php

namespace Tshafer\Traits\Traits;

trait BoomTrait
{
    /**
     * @param $name
     * @param $arguments
     *
     * @return array|mixed|null
     */
    public static function __callStatic($name, $arguments)
    {
        $pattern = '!^([a-zA-z]*)(Option|option)((Key|Value|Random|KeyRandom)s?)$!';

        if (preg_match($pattern, $name, $matches)) {
            $target = $matches[1];
            $method_type = $matches[3];
            $method = camel_case($target.'_options');

            if (method_exists(get_class(), $method)) {
                $options = call_user_func('self::'.$method);

                if ($method_type == 'Value') {
                    return array_get($options, $arguments[0], '');
                } else {
                    if ($method_type == 'Key') {
                        return array_search($arguments[0], $options);
                    } else {
                        if ($method_type == 'Keys') {
                            return array_keys($options);
                        } else {
                            if ($method_type == 'Values') {
                                $option_ids = (!empty($arguments[0])) ? $arguments[0] : [];

                                if (!empty($option_ids)) {
                                    foreach ($options as $option_id => $option) {
                                        if (in_array($option_id, $option_ids)) {
                                            $option_values[] = $option;
                                        }
                                    }
                                } else {
                                    $option_values = array_values($options);
                                }

                                return $option_values;
                            } else {
                                if ($method_type == 'Random') {
                                    $request_number = (!empty($arguments[0])) ? intval($arguments[0]) : 1;
                                    $random_keys = array_rand($options, $request_number);

                                    if (is_array($random_keys)) {
                                        $random_values = [];

                                        foreach ($random_keys as $random_key) {
                                            $random_values[] = $options[$random_key];
                                        }

                                        return $random_values;
                                    }

                                    return $options[$random_keys];
                                } else {
                                    if ($method_type == 'KeyRandom') {
                                        $request_number = (!empty($arguments[0])) ? intval($arguments[0]) : 1;

                                        return array_rand($options, $request_number);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            throw new \BadMethodCallException('Method ['.$method.'] does not exist.');
        } else {
            return is_callable(['parent', '__callStatic']) ? parent::__callStatic($name, $arguments) : null;
        }
    }
}