<?php


namespace base\model;


class User extends Model
{
    private $login;
    private $email;
    private $phone;

    private $name;
    private $surname;

    public function __construct()
    {
        $this->login = &$_SESSION['user']['login'];
        $this->email = &$_SESSION['user']['email'];
        $this->phone = &$_SESSION['user']['phone'];

        $this->name = &$_SESSION['user']['name'];
        $this->surname = &$_SESSION['user']['surname'];
    }

    /**
     *  Задать нестандартный параметр у пользователя.
     *
     * @param $param - название параметра
     * @param $value - значение параметра
     */
    public function setParam($param, $value)
    {
        $_SESSION['user'][$param] = $value;
    }

    /**
     *  Получить нестандартный параметр у пользователя.
     *
     * @param $param - название параметра
     * @return mixed - значение параметра
     */
    public function getParam($param)
    {
        return $_SESSION['user'][$param];
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }
}