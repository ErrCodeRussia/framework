<?php

namespace base\model;

use base\security\Security;
use base\interfaces\ModelInterface;


class Model implements ModelInterface
{
    public $status;

    public function validate()
    {
        $rules = $this->rules();

        foreach ($rules as $field => $rule) {
            foreach ($rule as $item) {
                switch ($item) {
                    case "required":
                        if (isset($this->$field) && $this->$field !== "") {
                            break;
                        }
                        else {
                            $this->status = ['status' => false, 'error' => $item];
                            return $this->status;
                        }
                    case "email":
                        if (preg_match(Security::getEmailRegExp(), $this->$field)) {
                            break;
                        }
                        else {
                            $this->status = ['status' => false, 'error' => $item];
                            return $this->status;
                        }
                    case "phone":
                        if (preg_match(Security::getPhoneRegExp(), $this->$field) && strlen($this->$field) >= 10 && strlen($this->$field) <= 12) {
                            break;
                        }
                        else {
                            $this->status = ['status' => false, 'error' => $item];
                            return $this->status;
                        }
                    case "string":
                        if (ctype_digit($this->$field)) {
                            $this->status = ['status' => false, 'error' => $item];
                            return $this->status;
                        }
                        else {
                            break;
                        }
                }
            }
        }

        return ['status' => true, 'error' => null];
    }

    public function rules()
    {
        return [];
    }
}