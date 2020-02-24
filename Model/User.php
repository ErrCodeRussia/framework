<?php


namespace base\model;


class User extends Model
{
    public $auth;

    private $id;
    private $login;
    private $email;
    private $phone;

    private $name;
    private $surname;

    private $group;
    private $role;

    private $storage;

    public function __construct()
    {
        $this->auth = &$_SESSION['user']['auth'];

        $this->id = &$_SESSION['user']['id'];
        $this->login = &$_SESSION['user']['login'];
        $this->email = &$_SESSION['user']['email'];
        $this->phone = &$_SESSION['user']['phone'];

        $this->name = &$_SESSION['user']['name'];
        $this->surname = &$_SESSION['user']['surname'];

        $this->group = &$_SESSION['user']['group'];
        $this->role = &$_SESSION['user']['role'];

        $this->storage = &$_SESSION['userStorage'];
    }

    /**
     *  Задать нестандартный параметр у пользователя.
     *
     * @param $param - название параметра
     * @param $value - значение параметра
     */
    public function set($param, $value)
    {
        $this->storage[$param] = $value;
    }

    /**
     *  Получить нестандартный параметр у пользователя.
     *
     * @param $param - название параметра
     * @return mixed - значение параметра
     */
    public function get($param)
    {
        return $this->storage[$param];
    }

    /**
     *  Удаляет переданный параметр у пользователя.
     *
     * @param $param - название параметра для удаления
     */
    public function remove($param)
    {
        if ($this->has($param))
            unset($this->storage[$param]);
    }

    /**
     *  Проверяет наличие определённого параметра у пользователя
     *
     * @param $param - название параметра для проверки
     * @return bool
     */
    public function has($param)
    {
        return isset($this->storage[$param]);
    }

    /**
     * @return mixed
     */
    public function isAuth()
    {
        return $this->auth;
    }

    /**
     * @param mixed $auth
     */
    public function setAuth($auth): void
    {
        $this->auth = $auth;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group): void
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }
}